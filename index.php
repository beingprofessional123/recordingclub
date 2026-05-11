<?php
session_start();

// Database connection
$servername = "localhost";
$db_username = "recordingclub_user";
$db_password = "Vaibhav8888";
$dbname = "recordingclub";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function isSessionExpired() {
    $inactive = 3600;
    if (isset($_SESSION['timeout'])) {
        $session_life = time() - $_SESSION['timeout'];
        if ($session_life > $inactive) {
            session_destroy();
            return true;
        }
    }
    $_SESSION['timeout'] = time();
    return false;
}

if (isSessionExpired() && !isset($_SESSION['username'])) {
    header("Location: /");
    exit();
}

$isLoggedIn = isset($_SESSION['username']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['logout'])) {
        // Handle logout
        session_destroy();
        header("Location: /");
        exit();
    } else if (!$isLoggedIn) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT password FROM auths WHERE username = ?");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("s", $username);

        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }

        $stmt->bind_result($hashed_password);

        if ($stmt->fetch()) {
            if (password_verify($password, $hashed_password)) {
                session_regenerate_id(true);
                $_SESSION['username'] = $username;
                $_SESSION['timeout'] = time();
                $isLoggedIn = true;
            } else {
                echo '<div class="alert alert-danger">Incorrect password. Please try again.</div>';
            }
        } else {
            echo '<div class="alert alert-danger">Username not found. Please try again.</div>';
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #dashboard {
            display: none;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <?php if ($isLoggedIn): ?>
                <div id="dashboard">
                    <h2 class="text-center mt-5">Dashboard</h2>
                    <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
                    <p><a href="https://api.recordingclub.in/newspapers/upload_newspaper.php" class="btn btn-success w-100">Upload Newspaper</a></p>
		    <p><a href="http://api.recordingclub.in/admin_panel/audio_panel" class="btn btn-success w-100">Audio Uploading Panel</a></p>
            <p><a href="http://api.recordingclub.in/admin_panel/audio_panel_old" class="btn btn-success w-100">Old Audio Uploading Panel</a></p>
            <p><a href="admin_panel/add_chapter_label.html" class="btn btn-success w-100">Table of content panel</a></p>
		    <form method="post" action="">
                        <button type="submit" name="logout" class="btn btn-danger w-100">Logout</button>
                    </form>
                </div>
            <?php else: ?>
                <h2 class="text-center mt-5">Login</h2>
                <form method="post" action="">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="admin@admin.com" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Pass PHP session data to JavaScript
    const isLoggedIn = <?php echo json_encode($isLoggedIn); ?>;
    const username = <?php echo json_encode($isLoggedIn ? $_SESSION['username'] : ''); ?>;

    if (isLoggedIn) {
        // Store session data in localStorage
        localStorage.setItem('isLoggedIn', true);
        localStorage.setItem('username', username);
        document.getElementById('dashboard').style.display = 'block';
    } else {
        // Clear localStorage if not logged in
        localStorage.removeItem('isLoggedIn');
        localStorage.removeItem('username');
    }
</script>

</body>
</html>
