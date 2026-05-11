<?php
header('Access-Control-Allow-Origin: *');

require_once __DIR__ .'/../includes/DBConnection.php';



class BooksHelpers

{



    private $con;



    function __construct()

    {

        $dbConnection=new DBConnection();

        $this->con=$dbConnection->getDatabaseConnection();

    }



    public function setParentCategory($book_category_name) {

        $stmt=$this->con->prepare("insert into books_categories (book_category_name,parent_category_name) values (?,'no_parent')");

        $stmt->bind_param("s",$book_category_name);

        $result=$stmt->execute();

        $stmt->close();

        if ($result) {

            return true;

        }

        else {

            return false;

        }

    }



    public function setSubCategory($book_category_name,$parent_category_name) {

        $stmt=$this->con->prepare("insert into books_categories (book_category_name,parent_category_name) values (?,?)");

        $stmt->bind_param("ss",$book_category_name,$parent_category_name);

        $result=$stmt->execute();

        $stmt->close();

        if ($result) {

            return true;

        }

        else {

            return false;

        }

    }



    public function setBook($book_title,$book_description,$book_category_name) {

        $stmt=$this->con->prepare("insert into books (book_title,book_description,book_category_name) values (?,?,?)");

        $stmt->bind_param("sss",$book_title,$book_description,$book_category_name);

        $result=$stmt->execute();

        $stmt->close();h

        if ($result) {

            return true;

        }

        else {

            return false;

        }

    }



    public function setChapter($chapter_title,$chapter_file_link,$book_title) {

        $stmt=$this->con->prepare("insert into books_chapter (chapter_title,chapter_file_link,book_title) values (?,?,?)");

        $stmt->bind_param("sss",$chapter_title,$chapter_file_link,$book_title);

        $result=$stmt->execute();

        $stmt->close();

        if ($result) {

            return true;

        }

        else {

            return false;

        }

    }



    public function isCategoryExists($book_category_name) {

        $stmt=$this->con->prepare("select * from books_categories where book_category_name = ?");

        $stmt->bind_param("s",$book_category_name);

        $stmt->execute();

        $stmt->store_result();

        if ($stmt->num_rows > 0) {

            $stmt->close();

            return true;

        }

        else {

            $stmt->close();

            return false;

        }

    }



    public function isBookExists($book_title) {

        $stmt=$this->con->prepare("select * from books where book_title = ?");

        $stmt->bind_param("s",$book_title);

        $stmt->execute();

        $stmt->store_result();

        if ($stmt->num_rows > 0) {

            $stmt->close();

            return true;

        }

        else {

            $stmt->close();

            return false;

        }

    }



    public function getCategories() {

        $sql="select book_category_name from books_categories";

        $query=$this->con->query($sql);

        $categories=array();

        $count=0;

        while ($row=$query->fetch_assoc()) {

            $categories[$count]=$row["book_category_name"];

            $count++;

        }

        $query->close();

        sort($categories);

        return $categories;

    }



    public function getRootCategories() {

        $sql="select book_category_name from books_categories where parent_category_name = 'no_parent'";

        $query=$this->con->query($sql);

        $categories=array();

        $count=0;

        while ($row=$query->fetch_assoc()) {

            $categories[$count]=$row["book_category_name"];

            $count++;

        }

        $query->close();

        sort($categories);

        return $categories;

    }



    public function getSubCategories($parent_category_name) {

        $sql="select book_category_name from books_categories where parent_category_name = '".$parent_category_name."'";

        $query=$this->con->query($sql);

        $categories=array();

        $count=0;

        while ($row=$query->fetch_assoc()) {

            $categories[$count]=$row["book_category_name"];

            $count++;

        }

        $query->close();

        sort($categories);

        return $categories;

    }



    public function getBooks() {

        $sql="select book_title from books";

        $query=$this->con->query($sql);

        $books=array();

        $count=0;

        while ($row=$query->fetch_assoc()) {

            $books[$count]=$row["book_title"];

            $count++;

        }

        $query->close();

        sort($books);

        return $books;

    }



    public function getBooksByCategory($book_category_name) {

        $sql="select book_title from books where book_category_name = '".$book_category_name."'";

        $query=$this->con->query($sql);

        $books=array();

        $count=0;

        while ($row=$query->fetch_assoc()) {

            $books[$count]=$row["book_title"];

            $count++;

        }

        $query->close();

        sort($books);

        return $books;

    }



    public function getChapters($book_title) {

        $sql="select * from books_chapter where book_title = '".$book_title."' order by id";

        $query=$this->con->query($sql);

        $book_info=array();

        $count=0;

        while ($row=$query->fetch_assoc()) {

            $book_info[$count]["chapter_title"]=$row["chapter_title"];

            $book_info[$count]["chapter_file_link"]=$row["chapter_file_link"];

            $count++;

        }

        $query->close();

//sort($book_info);

        return $book_info;

    }



}



?>

