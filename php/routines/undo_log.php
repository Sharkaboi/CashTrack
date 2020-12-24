<?php
    // Connect to db.
    include('../db/db_config.php');
    include('../db/sql_queries.php');

    session_start();  
    if(!isset($_POST['logDate']) || !isset($_SESSION['username'])) {
        navigate_to_history_with_error("Cannot fetch data");
    } else {
        $logDate = $_POST['logDate'];
        $username = $_SESSION['username'];

        $query = get_logs_from($conn,$logDate);
        $result = mysqli_query($conn,$query);
        mysqli_autocommit($conn,FALSE);
        if(mysqli_num_rows($result)==0) {
            navigate_to_history_with_error(mysqli_error($conn));
        } else {
            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                $log_id = $row['log_id'];
                $type = $row['type'];
                $account = $row['account'];
                $amount = $row['amount'];
                $mysqlDate = $row['log_date'];
                $balanceBefore = $row['balance_before'];
                $balanceAfter = $row['balance_after'];
                
                $query_1 = perform_undo_in_balance($conn,$type,$account,$amount,$username);
                $result_1 = mysqli_query($conn,$query_1);
                $query_2 = remove_log_with_id($conn,$log_id);
                $result_2 = mysqli_query($conn,$query_2);
            }
            $commit = mysqli_commit($conn);
            mysqli_autocommit($conn,TRUE);
            if (!$commit) {
                navigate_to_history_with_error("Database error");
            } else {
                navigate_to_history();
            }
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