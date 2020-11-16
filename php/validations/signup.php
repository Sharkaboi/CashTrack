<?php

    // Import constants and secrets.
    include_once '../../../secrets/mysql_secrets.php';
    include_once '../../../secrets/app_constants.php';
    include_once '../db/sql_queries.php';

    // check username and hash password and check from db
    // if success, set cookie and session id and goto dashboard
    // on failure, show alert and navigate back. 
    $conn = mysqli_connect(MYSQL_SERVER_IP,MYSQL_USER_NAME,MYSQL_PWD,DB_NAME);
    $username = $_POST['username'];
    $pass = $_POST['password'];
    $curr = (int)$_POST['currency'];

    if(!$conn){
        mysqli_close($conn);
        connection_failed_error();
    } else {
        // check if username exists
        $result = mysqli_query($conn,check_username($conn,$username));
        if(mysqli_num_rows($result) > 0) {
            // username exists
            mysqli_close($conn);
            navigate_to_signup_page();
        } else {
            // register user and set cookie.
            $hashed_pass = password_hash($pass,PASSWORD_DEFAULT);
            $result = mysqli_query($conn,insert_username($conn,$username,$hashed_pass,$curr));
            if($result) {
                $result = mysqli_query($conn,insert_session($conn,$username));
                if($result) {
                    setcookie(COOKIE_NAME,$username,time()+10 * 365 * 24 * 60 * 60,'/');
                    mysqli_close($conn);
                    navigate_to_dashboard();
                } else {
                    mysql_error($conn);
                }
            } else {
                mysql_error($conn);
            }
        }
    }
    
    function navigate_to_signup_page() {
        echo '<script>
        alert("Username already exists!");
        window.location.href="/pages/signup.html";
        </script>';
        exit;
    }

    function navigate_to_dashboard() {
        echo '<script>
        window.location.href="/dashboard";
        </script>';
        exit;
    }
    function connection_failed_error() {
        echo '<script>
        alert("Database connection failed!");
        window.location.href="/";
        </script>';
        exit;
    }

    function mysql_error($conn) {
        $error = mysqli_error($conn);
        mysqli_close($conn);
        echo "$error
        <script>
        alert('$error');
        window.location.href='/pages/signup.html';
        </script>";
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!--Meta Tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Favicons-->
    <link rel="apple-touch-icon" sizes="57x57" href="/assets/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/assets/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/assets/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/assets/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/assets/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/assets/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/assets/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/assets/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/assets/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/assets/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="/assets/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="assets/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <!--Other-->
    <title>Loading....</title>
    <!--CDN-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!--CSS-->
    <link rel="stylesheet" href="/css/main.css">
</head>
<body>
    <div class="container center-page">
        <img src="/assets/gifs/loading.gif" alt="Loading....." style="width: 10%;height: auto;pointer-events: none;">
    </div>
</body>
</html>