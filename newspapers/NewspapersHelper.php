<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

require_once ('../includes/DBConnection.php');

class NewspapersHelper
{

    private $con;
    private $current_date;
    private $current_month;
    private $current_year;
    private $current_file_path= __DIR__ ."/../../stream.recordingclub.in/newspapers/";

    function __construct()
    {
        $db_connect=new DBConnection();
        $this->con=$db_connect->getDatabaseConnection();
        date_default_timezone_set('Asia/Kolkata');
        $this->current_date=date("d");
        $this->current_month=date("m");
        $this->current_year=date("Y");

    }

    public function setNewspaperDailyPost($newspaper_title,$newspaper_name,$newspaper_file_name,$file_object) {
        $this->setFileFolder($newspaper_name);
        $file_name=$this->current_file_path."/".$newspaper_name."/".$this->current_date."_".$this->current_month."_".$this->current_year."/".$newspaper_file_name;
        move_uploaded_file($file_object,$file_name);
        $tmp_link_v="https://stream.recordingclub.in/newspapers/".$newspaper_name."/".$this->current_date."_".$this->current_month."_".$this->current_year."/".$newspaper_file_name;
        $newspaper_file_link=str_replace(' ','%20',$tmp_link_v);


        $stmt=$this->con->prepare("insert into newspaper_daily_posts (newspaper_title,newspaper_name,newspaper_file_link,newspaper_date,newspaper_month,newspaper_year) values (?,?,?,?,?,?)");
        $stmt->bind_param("ssssss",$newspaper_title,$newspaper_name,$newspaper_file_link,$this->current_date,$this->current_month,$this->current_year);
        $result=$stmt->execute();
        $stmt->close();
        if ($result) {
            return true;
        }
        else {
            return false;
        }
    }

    public function setNewspaper($newspaper_name) {
        $stmt=$this->con->prepare("insert into newspapers (newspaper_name) values (?)");
        $stmt->bind_param("s",$newspaper_name);
        $result=$stmt->execute();
        $stmt->close();
        if ($result) {
            return true;
        }
        else {
            return false;
        }
    }

    public function getNewspapers() {
        $sql="select * from newspapers";
        $query=$this->con->query($sql);
        $count=0;
        $my_array=array();
        while ($row=$query->fetch_assoc()) {
            $my_array[$count]=$row["newspaper_name"];
            $count++;
        }
        $query->close();
        sort($my_array);
        return $my_array;
    }

    public function getNewspaperDailyPost($newspaper_name) {
        $sql="select * from newspaper_daily_posts where newspaper_name = '".$newspaper_name."' and newspaper_date = '".$this->current_date."' and newspaper_month = '".$this->current_month."' and newspaper_year = '".$this->current_year."'";
        $query=$this->con->query($sql);
        $my_array=array();
        $count=0;
        while ($row=$query->fetch_assoc()) {
            $my_array[$count]["id"]=$row["id"];
            $my_array[$count]["newspaper_title"]=$row["newspaper_title"];
            $my_array[$count]["newspaper_file_link"]=$row["newspaper_file_link"];
            $count++;
        }
        $query->close();
        sort($my_array);
        return $my_array;
    }

    public function getNewspaperDailyPostByNewspaperName($newspaper_name) {
        $sql="select * from newspaper_daily_posts where newspaper_name = '".$newspaper_name."'";
        $query=$this->con->query($sql);
        $my_array=array();
        $count=0;
        while ($row=$query->fetch_assoc()) {
            $my_array[$count]["id"]=$row["id"];
            $my_array[$count]["newspaper_title"]=$row["newspaper_title"];
            $my_array[$count]["newspaper_file_link"]=$row["newspaper_file_link"];
            $count++;
        }
        $query->close();
        $tmp_array=array();
        $tmp_array=array_reverse($my_array);
        return $tmp_array;
    }

    public function getNewspaperDailyPostInfo($id) {
        $stmt=$this->con->prepare("select * from newspaper_daily_posts where id = ?");
        $stmt->bind_param("d",$id);
        $stmt->execute();
        $my_array=$stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $my_array;
    }

    private function setFileFolder($newspaper_name) {
        $tmp1=$this->current_file_path."/".$newspaper_name."/".$this->current_date."_".$this->current_month."_".$this->current_year;
        if (!file_exists($tmp1)) {
$oldUmask = umask(0);
            mkdir($tmp1,0777,true);
        }
    }

}
?>
