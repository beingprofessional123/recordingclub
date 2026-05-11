<?php
require_once __DIR__ .'/../BooksHelpers.php';
$booksHelpers=new BooksHelpers();
$response=array("error"=>false);
if (isset($_POST["book_title"])) {
    $book_title=$_POST["book_title"];
    $response["error"]=false;
    $response["book_info"]=$booksHelpers->getChapters($book_title);
    echo json_encode($response);
}
else {
    $response["error"]=true;
    $response["msg"]="Server error.";
    echo json_encode($response);
}
?>
