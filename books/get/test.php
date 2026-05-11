<?php
require_once __DIR__ .'/../BooksHelpers.php';
$booksHelpers=new BooksHelpers();
$response=array("error"=>false);
    $book_category_name="T3 i";
    $response["error"]=false;
    $response["books"]=$booksHelpers->getBooksByCategory($book_category_name);
    echo json_encode($response);
?>
