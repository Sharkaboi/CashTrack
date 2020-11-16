<?php

    // Import constants and secrets.
    include_once '../../secrets/mysql_secrets.php';
    include_once '../../secrets/app_constants.php';
    include_once '../php/db/sql_queries.php';

    // Check and navigate logged in session
    check_log_cookie();





    function check_log_cookie() {
        // if log cookie is set and session id is in session table, redirect to dashboard.
        if(isset($_COOKIE[COOKIE_NAME])){
            // Getting log cookie
            $cookie_data = $_COOKIE[COOKIE_NAME];
            
            // checking session id
            $conn = mysqli_connect(MYSQL_SERVER_IP,MYSQL_USER_NAME,MYSQL_PWD,DB_NAME);
            if(!$conn) {
                echo '<script>
                alert("Database connection failed!");
                window.location.href="/";
                </script>';
                mysqli_close($conn);
                exit;
            } else {
                $result = mysqli_query($conn,get_session_by_id($conn,$cookie_data));
                mysqli_close($conn);
                if(mysqli_num_rows($result) > 0) {
                    // session is active, stay on dashboard.
                    return ;
                } else {
                    // session inactive, clear cookie and goto login page.
                    unset($_COOKIE[COOKIE_NAME]);
                    // setcookie(COOKIE_NAME, '', time() - 3600, '/'); // empty value and old timestamp to make cookie expire.
                    navigate_to_login_page();
                }
            }
        }
        else{
            // log cookie is not set, thus make user log in.
            navigate_to_login_page();
        }
    }

    function navigate_to_login_page() {
        header("Location: /pages/login.html");
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
    <link rel="manifest" href="assets/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="assets/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <!--Other-->
    <title>Dashboard</title>
    <!--CDN-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!--CSS-->
    <link rel="stylesheet" href="/css/main.css">
</head>
<body>
    
</body>
</html>