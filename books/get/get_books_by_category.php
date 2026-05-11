<?php
require_once __DIR__ .'/../BooksHelpers.php';
$booksHelpers=new BooksHelpers();
$response=array("error"=>false);
if (isset($_POST["book_category_name"])) {
    $book_category_name=$_POST["book_category_name"];
    $response["error"]=false;
    $response["books"]=$booksHelpers->getBooksByCategory($book_category_name);
    echo json_encode($response);
}
else {
    $response["error"]=true;
    $response["msg"]="Server error.";
    echo json_encode($response);
}
?>
