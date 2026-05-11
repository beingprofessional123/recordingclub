<?php
require_once __DIR__ .'/../BooksHelpers.php';
$booksHelpers=new BooksHelpers();
$response=array("error"=>false, "msg"=>"Server error.");
if (isset($_POST["book_category_name"])&&isset($_POST["parent_category_name"])) {
    $book_category_name=$_POST["book_category_name"];
    $parent_category_name=$_POST["parent_category_name"];
    if ($booksHelpers->isCategoryExists($book_category_name)) {
        $response["error"]=true;
        $response["msg"]="The category is already exists.";
        echo json_encode($response);
    }
    else {
        if ($booksHelpers->setSubCategory($book_category_name,$parent_category_name)) {
            $response["error"]=false;
            $response["msg"]="The category is created successfully.";
            echo json_encode($response);
        }
        else {
            $response["error"]=true;
            $response["msg"]="The category is not created.";
            echo json_encode($response);
        }
    }
}
else {
    $response["error"]=true;
    $response["msg"]="Server error.";
    echo json_encode($response);
}
?>
