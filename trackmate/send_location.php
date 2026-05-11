<?php
error_reporting(0);
ini_set('display_errors', 0);

$host = "localhost";
$user = "recordingclub_user";
$pass = "Vaibhav8888";
$db = "trackmate";

$mysqli = new mysqli($host, $user, $pass, $db);

// Load service account credentials
$serviceAccountPath = 'service-account-key.json'; // 🔁 path to your downloaded JSON
$credentials = json_decode(file_get_contents($serviceAccountPath), true);

// FCM project ID
$projectId = $credentials['project_id'];

// Get email from request
$email = $_GET['email'] ?? '';

if (empty($email    )) {
    echo json_encode(["status" => "error", "message" => "Email is required"]);
    exit;
}

// Get FCM token from DB
$stmt = $mysqli->prepare("SELECT fcm_token FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "No FCM token found for this email"]);
    exit;
}

$stmt->bind_result($fcm_token);
$stmt->fetch();

// Get Access Token using Service Account
function getAccessToken($credentials) {
    $header = base64_encode(json_encode(["alg" => "RS256", "typ" => "JWT"]));

    $now = time();
    $payload = [
        "iss" => $credentials['client_email'],
        "scope" => "https://www.googleapis.com/auth/firebase.messaging",
        "aud" => "https://oauth2.googleapis.com/token",
        "iat" => $now,
        "exp" => $now + 3600
    ];
    $payloadEncoded = base64_encode(json_encode($payload));

    $signatureInput = "$header.$payloadEncoded";

    // Sign with private key
    $privateKey = openssl_pkey_get_private($credentials['private_key']);
    openssl_sign($signatureInput, $signature, $privateKey, "sha256WithRSAEncryption");
    $signatureEncoded = base64_encode($signature);

    $jwt = "$signatureInput.$signatureEncoded";

    // Get access token
    $postFields = http_build_query([
        'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
        'assertion' => $jwt
    ]);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://oauth2.googleapis.com/token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);
    return $result['access_token'] ?? null;
}

$accessToken = getAccessToken($credentials);

if (!$accessToken) {
    echo json_encode(["status" => "error", "message" => "Failed to retrieve access token"]);
    exit;
}

// Create payload
$payload = [
    "message" => [
        "token" => $fcm_token,
        "data" => [
            "command" => "get_location"
        ]
    ]
];

// Send FCM v1 request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/v1/projects/$projectId/messages:send");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $accessToken",
    "Content-Type: application/json"
]);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode(["status" => "error", "message" => "Curl error: " . curl_error($ch)]);
    curl_close($ch);
    exit;
}

curl_close($ch);
echo json_encode(["status" => "success", "message" => "FCM command sent", "response" => $response]);
