<?php
require_once __DIR__ .'/../BooksHelpers.php';
$booksHelpers=new BooksHelpers();
$response=array("error"=>false);
$response["error"]=false;
$response["categories"]=$booksHelpers->getCategories();
echo json_encode($response);
?>
