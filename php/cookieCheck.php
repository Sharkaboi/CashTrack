<?php
    $cookieName = 'cashtrack_log_cookie';

    if(isset($_COOKIE[$cookieName])){
        // log cookie is set and session id is in session table, redirect to dashboard.
        // $cookie = $_COOKIE[$cookieName];
        header("Location: /php/dashboard.php");
        exit;
    }
    else{
        // log cookie is not set, thus make user log in.
        header("Location: /pages/login.html");
        exit;
    }
?>