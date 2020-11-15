<?php
    // check username and hash password and check from db
    // if success, set cookie and goto dashboard
    // on failure, show alert and navigate back. 
    $conn = mysqli_connect(MYSQL_SERVER_IP,MYSQL_USER_NAME,MYSQL_PWD,DB_NAME);
    echo $conn;

?>