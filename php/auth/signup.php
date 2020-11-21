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
    <!--CSS-->
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
</head>
<body>
    <div class="container center-page">
        <img src="/assets/gifs/loading.gif" alt="Loading....." style="width: 10%;height: auto;pointer-events: none;">
    </div>

    <?php

        // Connect to db.
        include('../db/db_config.php');
        include('../db/sql_queries.php');
        
        session_start();

        if(isset($_POST['username']) && $_POST['password'] && $_POST['currency']) {

            $username = $_POST['username'];
            $pass = $_POST['password'];
            $curr = (int)$_POST['currency'];

            // check if username exists
            $query = check_username($conn,$username);
            $result = mysqli_query($conn,$query);

            if(mysqli_num_rows($result) > 0) {
                // username exists
                navigate_to_signup_page("Username already exists");
            } else {
                // register user and log in.
                $query = insert_username($conn,$username,$pass,$curr);
                $result = mysqli_query($conn,$query);
                if($result) {
                    // log in
                    $_SESSION['username'] = $username;
                    navigate_to_dashboard();
                } else {
                    navigate_to_signup_page(mysqli_error($conn));
                }
            }
        } else {
            navigate_to_signup_page("Cannot get username and password");
        }
        
        function navigate_to_signup_page($error) {
            echo '<script>';
            echo 'alert("Error : '.$error.'");';
            echo 'window.location.href = "http://localhost/php/pages/signup.php";';
            echo '</script>';
        }

        function navigate_to_dashboard() {
            echo '<script>';
            echo 'window.location.href = "http://localhost/dashboard";';
            echo '</script>';
        }

        mysqli_close($conn);
    ?>

</body>
</html>