<?php
    include('../db/db_config.php');
    include('../db/sql_queries.php');
    session_start();

    if(!isset($_SESSION['username']) || !isset($_POST['amount']) || !isset($_POST['account']) || !isset($_POST['desc'])) {
        navigate_to_login_page("Error sending data!");
    } else {
        $username = $_SESSION['username'];
        $account = (int)$_POST['account'];
        $amount = (int)$_POST['amount'];
        $type = 1;
        $description = $_POST['desc'];
        $old_balance_after = (int)$_POST['balance'];
        // set new balance_before to old balance_after
        // set new balance_after to old balance_after+amount IF ACCOUNT = 3
        $new_balance_before = $old_balance_after;
        if($account == 3) {
            $new_balance_after = $old_balance_after;
        } else {
            $new_balance_after = $old_balance_after + $amount;
        }
        
        mysqli_autocommit($conn,FALSE);
        $query_1 = add_to_log($conn,$username,$account,$type,$amount,$description,$new_balance_before,$new_balance_after);
        $result = mysqli_query($conn,$query_1);
        $query_2 = update_user_add_bal($conn,$username,$account,$type,$amount,$description,$new_balance_before,$new_balance_after);
        $result = mysqli_query($conn,$query_2);
        $commit = mysqli_commit($conn);
        mysqli_autocommit($conn,TRUE);
        if (!$commit) {
            navigate_to_dashboard_with_error("Database error");
        } else {
            navigate_to_dashboard();
        }
    }

    function navigate_to_dashboard_with_error($error) {
        echo '<script>';
        echo 'alert("Error : '.$error.'");';
        echo 'window.location.href = "http://localhost/dashboard";';
        echo '</script>';
    }

    function navigate_to_dashboard() {
        echo '<script>';
        echo 'window.location.href = "http://localhost/dashboard";';
        echo '</script>';
    }
    
    mysqli_close($conn);
?>