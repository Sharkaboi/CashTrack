<?php
    include('../db/db_config.php');
    include('../db/sql_queries.php');
    session_start();

    if(!isset($_SESSION['username']) || !isset($_POST['currentDesc']) || !isset($_POST['logId'])) {
        navigate_to_login_page("Error sending data!");
    } else {
        $username = $_SESSION['username'];
        $desc = $_POST['currentDesc'];
        $logId = (int) $_POST['logId'];

        $query = update_description($conn,$logId,$desc);
        $result = mysqli_query($conn,$query);
        if(!$result) {
            navigate_to_history_with_error(mysqli_error($conn));
        } else {
            navigate_to_history();
        }
    }
    
    function navigate_to_history_with_error($error) {
        echo '<script>';
        echo 'alert("Error : '.$error.'");';
        echo 'window.location.href = "http://localhost/dashboard/history";';
        echo '</script>';
    }

    function navigate_to_history() {
        echo '<script>';
        echo 'window.location.href = "http://localhost/dashboard/history";';
        echo '</script>';
    }
    
    mysqli_close($conn);
?>