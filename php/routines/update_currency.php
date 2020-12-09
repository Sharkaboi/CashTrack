<?php
    // Connect to db.
    include('../db/db_config.php');
    include('../db/sql_queries.php');

    session_start();  

    if(isset($_POST['currency']) && isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        $currency = (int)$_POST['currency'];

        $query = update_currency($conn,$username,$currency);
        $result = mysqli_query($conn,$query);
        if(!$result) {
            navigate_to_settings_page_with_error(mysqli_error($conn));
        } else {
            navigate_to_settings_page();
        }

    } else {
        navigate_to_settings_page_with_error("Cannot get username or new password");
    }

    function navigate_to_settings_page_with_error($error) {
        echo '<script>';
        echo 'alert("Error : '.$error.'");';
        echo 'window.location.href = "http://localhost/dashboard/settings/index.php";';
        echo '</script>';
    }

    function navigate_to_settings_page() {
        echo '<script>';
        echo 'window.location.href = "http://localhost/dashboard/settings/index.php";';
        echo '</script>';
    }

    mysqli_close($conn);
?>