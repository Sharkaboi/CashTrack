<?php

//    CurrentSession table queries
//    $get_all_sessions = "select * from CurrentSessions";

//    function get_session_by_id($conn,$session_id) {
//        $stripped_session_id = stripcslashes($session_id);
//        $mysql_session_id = mysqli_real_escape_string($conn,$stripped_session_id);
//        return "select * from CurrentSessions where session_id='$mysql_session_id'";
//    }

//    function delete_session_by_id($conn,$session_id) {
//        $stripped_session_id = stripcslashes($session_id);
//        $mysql_session_id = mysqli_real_escape_string($conn,$stripped_session_id);
//        return "delete from `CurrentSessions` where `session_id`='$mysql_session_id'";
//    }

//    function insert_session($conn,$session_id) {
//        $stripped_session_id = stripcslashes($session_id);
//        $mysql_session_id = mysqli_real_escape_string($conn,$stripped_session_id);
//        return "insert into CurrentSessions(`session_id`, `started_at`) VALUES ('$mysql_session_id',CURRENT_TIMESTAMP())";
//    }

//    function update_insert_session($conn,$session_id) {
//        $stripped_session_id = stripcslashes($session_id);
//        $mysql_session_id = mysqli_real_escape_string($conn,$stripped_session_id);
//        return "insert into CurrentSessions(`session_id`, `started_at`) VALUES ('$mysql_session_id',CURRENT_TIMESTAMP()) ON DUPLICATE KEY UPDATE";
//    }
    
    // Auth queries
    function check_username($conn,$username) {
        $stripped_username = stripcslashes($username);
        $mysql_username = mysqli_real_escape_string($conn,$stripped_username);
        return "select * from User where username='$mysql_username'";
    }
    function insert_username($conn,$username,$pass,int $currency) {
        $stripped_username = stripcslashes($username);
        $striped_pass = stripcslashes($pass);
        $mysql_username = mysqli_real_escape_string($conn,$stripped_username);
        $mysql_pass = mysqli_real_escape_string($conn,$striped_pass);
        $mysql_currency = mysqli_real_escape_string($conn,"".$currency);
        $hashed_pass = password_hash($mysql_pass,PASSWORD_DEFAULT);
        return "INSERT INTO `User`(`username`, `hash_pwd`, `currency_default`) VALUES ('$mysql_username','$hashed_pass',$mysql_currency)";
    }
    function get_user($conn,$username) {
        $stripped_username = stripcslashes($username);
        $mysql_username = mysqli_real_escape_string($conn,$stripped_username);
        return "select * from User where username='$mysql_username'";
    }
?>