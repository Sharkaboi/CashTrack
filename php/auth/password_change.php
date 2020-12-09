<?php
    // Connect to db.
    include('../db/db_config.php');
    include('../db/sql_queries.php');

    session_start();  

    if(isset($_POST['cpassword']) && isset($_POST['npassword']) && isset($_SESSION['username'])) {
        $cpassword = $_POST['cpassword'];
        $npassword = $_POST['npassword'];
        $username = $_SESSION['username'];

        // get user with username as given
        $query = get_user($conn,$username);
        $result = mysqli_query($conn,$query);

        if(!$result){
            //DB error
            navigate_to_settings_page(mysqli_errno($conn));
        } else if(mysqli_num_rows($result) <= 0) {
            // no account with same username
            navigate_to_settings_page("Username doesn't exist");
        } else {
            // Get first row of result (only one row should exist)
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);  

            // strip and escape pass to prevent sql injection
            $striped_pass = stripcslashes($cpassword);
            $mysql_pass = mysqli_real_escape_string($conn,$striped_pass);

            //verify pass after hashing with hashed pass from db
            if(!password_verify($mysql_pass,$row['hash_pwd'])){
                // password mismatch
                navigate_to_settings_page("Invalid current password");
            } else {
                // Current Password correct
                $query = update_password($conn,$username,$npassword);
                $result2 = mysqli_query($conn,$query);

                if(!$result2){
                    //DB error
                    navigate_to_settings_page(mysqli_errno($conn));
                } else {
                    navigate_to_settings_page("Password updated");
                }
            }
        }

    } else {
            navigate_to_settings_page("Cannot get username or new password");
    }
    
        function navigate_to_settings_page($error) {
            echo '<script>';
            echo 'alert("Error : '.$error.'");';
            echo 'window.location.href = "http://localhost/dashboard/settings/index.php";';
            echo '</script>';
        }
?>