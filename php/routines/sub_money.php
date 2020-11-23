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
        $type = 2;
        $description = $_POST['desc'];
        $old_balance_after = (int)$_POST['balance'];

        // get username from session
        // check if negative balance
        // set new balance_before to old balance_after
        // set new balance_after to old balance_after+amount IF ACCOUNT = 3
        $new_balance_before = $old_balance_after;
        if($account == 3) {
            $new_balance_after = $old_balance_after;
        } else {
            $new_balance_after = $old_balance_after - $amount;
        }

        if($new_balance_after<0) {
            navigate_to_dashboard_with_error("Cannot have negative total balance");
        } else {
            $query = get_user($conn,$username);
            $result = mysqli_query($conn,$query);
            if(!$result) {
                navigate_to_dashboard_with_error("Database error");
            } else {
                $row = mysqli_fetch_assoc($result);
                switch($account) {
                    case 1:
                        $field = 'cash_bal';
                        $previous = $row['cash_bal'];
                        if($previous-$amount<0){
                            navigate_to_dashboard_with_error("Cannot have negative balance");
                            exit;
                        }
                    break;
                    case 2:
                        $field = 'debit_bal';
                        $previous = $row['debit_bal'];
                        if($previous-$amount<0){
                            navigate_to_dashboard_with_error("Cannot have negative balance");
                            exit;
                        }
                    break;
                    case 3:
                        $field = 'credit_bal';
                        $previous = $row['credit_bal'];
                        if($previous-$amount<0){
                            navigate_to_dashboard_with_error("Cannot have negative balance");
                            exit;
                        }
                    break;
                }

                mysqli_autocommit($conn,FALSE);
                $query_1 = add_to_log($conn,$username,$account,$type,$amount,$description,$new_balance_before,$new_balance_after);
                $result = mysqli_query($conn,$query_1);
                $query_2 = update_user_sub_bal($conn,$username,$field,$amount);
                $result = mysqli_query($conn,$query_2);
                $commit = mysqli_commit($conn);
                mysqli_autocommit($conn,TRUE);
                if (!$commit) {
                    navigate_to_dashboard_with_error("Database error");
                } else {
                    navigate_to_dashboard();
                }
            }            
        }
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

    function navigate_to_dashboard_with_error($error) {
        echo '<script>';
        echo 'alert("Error : '.$error.'");';
        echo 'window.location.href = "http://localhost/dashboard";';
        echo '</script>';
    } 

    mysqli_close($conn);
?>