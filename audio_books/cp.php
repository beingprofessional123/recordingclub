<?php
$file = __DIR__ . '/insert_book.php'; // Path to insert_book.php in the same folder

if (file_exists($file)) {
    if (chmod($file, 0777)) {
        echo "Permissions changed to 0777 for $file successfully.";
    } else {
        echo "Failed to change permissions for $file.";
    }
} else {
    echo "File $file does not exist.";
}
?>
