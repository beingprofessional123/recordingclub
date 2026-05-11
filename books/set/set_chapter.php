<?php
require_once __DIR__ .'/../BooksHelpers.php';
$booksHelpers=new BooksHelpers();
$response=array("error"=>false, "msg"=>"Server error.");
if (isset($_POST["chapter_title"])&&isset($_POST["chapter_file_link"])&&isset($_POST["book_title"])) {
    $chapter_title=$_POST["chapter_title"];
    $chapter_file_link=$_POST["chapter_file_link"];
    $book_title=$_POST["book_title"];
    if ($booksHelpers->setChapter($chapter_title,$chapter_file_link,$book_title)) {
        $response["error"]=false;
        $response["msg"]="The chapter is created successfully.";
        echo json_encode($response);
    }
    else {
        $response["error"]=true;
        $response["msg"]="The chapter is not created.";
        echo json_encode($response);
    }
}
else {
    $response["error"]=true;
    $response["msg"]="Server error.";
    echo json_encode($response);
}
?>
