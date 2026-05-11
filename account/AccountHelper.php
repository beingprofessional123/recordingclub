<?php

require '../includes/DBConnection.php';

class AccountHelper
{

    private $con;

    function __construct()
    {
        $db_connect=new DBConnection();
        $this->con=$db_connect->getDatabaseConnection();
    }

    public function setUser($user_name,$user_email_address) {
        $stmt=$this->con->prepare("insert into users (user_name,user_email_address,user_role,user_status) values (?,?,0,0)");
        $stmt->bind_param("ss",$user_name,$user_email_address);
        $result=$stmt->execute();
        $stmt->close();
        if ($result) {
            return true;
        }
        else {
            return false;
        }
    }

    public function isUserExists($user_email_address) {
        $stmt=$this->con->prepare("select * from users where user_email_address = ?");
        $stmt->bind_param("s",$user_email_address);
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

    public function getUserByEmail($user_email_address) {
        $stmt=$this->con->prepare("select * from users where user_email_address = ?");
        $stmt->bind_param("s",$user_email_address);
        $stmt->execute();
        $user_info=$stmt->get_result()->fetch_assoc();
        $stmt->close();
      return $user_info;
    }

}
?>
