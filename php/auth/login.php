<!DOCTYPE html>
<html lang="en">
<head>
    <!--Meta Tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Favicons-->
    <link rel="apple-touch-icon" sizes="57x57" href="../../assets/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="../../assets/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="../../assets/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="../../assets/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="../../assets/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="../../assets/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="../../assets/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="../../assets/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="../../assets/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="../../assets/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="../../assets/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="../../assets/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="../../assets/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <!--Other-->
    <title>Loading....</title>
    <!--CSS-->
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
</head>
<body>
    <div class="container center-page">
        <img src="../../assets/gifs/loading.gif" alt="Loading....." style="width: 10%;height: auto;pointer-events: none;">
    </div>

    <?php

    // Connect to db.
    include('../db/db_config.php');
    include('../db/sql_queries.php');

    session_start();  

    if(isset($_POST['username']) && isset($_POST['password'])) {

        $username = $_POST['username'];
        $pass = $_POST['password'];

        // get user with username as given
        $query = get_user($conn,$username);
        $result = mysqli_query($conn,$query);

        if(!$result){
            //DB error
            navigate_to_login_page(mysqli_errno($conn));
        } else if(mysqli_num_rows($result) <= 0) {
            // no account with same username
            navigate_to_login_page("Username doesn't exist");
        } else {
            // Get first row of result (only one row should exist)
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);  

            // strip and escape pass to prevent sql injection
            $striped_pass = stripcslashes($pass);
            $mysql_pass = mysqli_real_escape_string($conn,$striped_pass);

            //verify pass after hashing with hashed pass from db
            if(!password_verify($mysql_pass,$row['hash_pwd'])){
                // password mismatch
                navigate_to_login_page("Invalid password");
            } else {
                // login successful, set username in session
                $_SESSION['username'] = $username;
                navigate_to_dashboard();
            }
        }
    } else {
        navigate_to_login_page("Cannot get username and password");
    }

    function navigate_to_login_page($error) {
        echo '<script>';
        echo 'alert("Error : '.$error.'");';
        echo 'window.location.href = "http://localhost/php/pages/login.php";';
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