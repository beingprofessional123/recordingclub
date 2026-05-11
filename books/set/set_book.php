<?php
require_once __DIR__ .'/../BooksHelpers.php';
$booksHelpers=new BooksHelpers();
$response=array("error"=>false, "msg"=>"Server error.");
if (isset($_POST["book_title"])&&isset($_POST["book_description"])&&isset($_POST["book_category_name"])) {
    $book_title=$_POST["book_title"];
    $book_description=$_POST["book_description"];
    $book_category_name=$_POST["book_category_name"];
    if ($booksHelpers->isBookExists($book_title)) {
        $response["error"]=true;
        $response["msg"]="The book is already exists.";
        echo json_encode($response);
    }
    else {
        if ($booksHelpers->setBook($book_title,$book_description,$book_category_name)) {
$oldUmask = umask(0);
            $folderPath="../../../stream.recordingclub.in/books/".$book_title;
            mkdir($folderPath, 0777, true);
            $response["error"]=false;
            $response["msg"]="The book is created successfully.";
            echo json_encode($response);
        }
        else {
            $response["error"]=true;
            $response["msg"]="The book is not created.";
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
