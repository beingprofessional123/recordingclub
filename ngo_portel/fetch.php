<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stateId = $_POST['state'];
    $district = strtolower(trim($_POST['district']));

    // Construct the NGO DARPAN district-wise URL (as per current structure)
    $url = "https://ngodarpan.gov.in/index.php/home/statewise_ngo/0/$stateId";

    // cURL setup
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $html = curl_exec($ch);
    curl_close($ch);

    if (!$html) {
        die("Could not fetch data.");
    }

    // Parse HTML
    libxml_use_internal_errors(true);
    $dom = new DOMDocument();
    $dom->loadHTML($html);
    libxml_clear_errors();
    $xpath = new DOMXPath($dom);

    $rows = $xpath->query('//table[contains(@class, "table")]/tbody/tr');

    if ($rows->length == 0) {
        echo "No NGO table found. Page structure may have changed.";
        exit;
    }

    echo "<h2>NGOs in district containing '$district'</h2>";
    echo "<table border='1'><tr><th>NGO Name</th><th>Reg No.</th><th>Address</th></tr>";

    $count = 0;
    foreach ($rows as $row) {
        $cells = $row->getElementsByTagName('td');
        if ($cells->length >= 3) {
            $ngoAddress = strtolower($cells->item(2)->textContent);
            if (strpos($ngoAddress, $district) !== false) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($cells->item(0)->textContent) . "</td>";
                echo "<td>" . htmlspecialchars($cells->item(1)->textContent) . "</td>";
                echo "<td>" . htmlspecialchars($cells->item(2)->textContent) . "</td>";
                echo "</tr>";
                $count++;
            }
        }
    }
    echo "</table>";

    if ($count == 0) {
        echo "No NGOs found for district containing '$district'. Try different wording.";
    }
}
?>
