<?php
    // Connect to db.
    include('../db/db_config.php');
    include('../db/sql_queries.php');

    session_start();  
    if(!isset($_POST['logDate']) || !isset($_SESSION['username'])) {
        navigate_to_history_with_error("Cannot fetch data");
    } else {
        $logDate = $_POST['logDate'];

        $query = undo_log($conn,$logDate);
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