<?php
    include('../db/db_config.php');
    include('../db/sql_queries.php');
    session_start();

    if(!isset($_SESSION['username']) || !isset($_POST['amount']) || !isset($_POST['faccount']) || !isset($_POST['taccount']) || !isset($_POST['desc'])) {
        navigate_to_login_page("Error sending data!");
    } else {
        $username = $_SESSION['username'];
        $amount = (int)$_POST['amount'];
        $description = $_POST['desc'];
        $old_balance_after = (int)$_POST['balance'];
        $faccount = (int)$_POST['faccount'];
        $taccount = (int)$_POST['taccount'];
        $type = 3;
        $account = (int) "$faccount$taccount";

        //copy old balance after to new balance before
        $new_balance_before = $old_balance_after;

        // if to account is credit, reduce total balance else leave it (only transfer)
        if($taccount == 3) {
            $new_balance_after = $old_balance_after - $amount;
        } else {
            $new_balance_after = $old_balance_after;
        }

        // negative total balance impossible
        if($new_balance_after<0) {
            navigate_to_dashboard_with_error("Cannot have negative total balance");
        } else {
            // get current balance breakdowns
            $query = get_user($conn,$username);
            $result = mysqli_query($conn,$query);
            if(!$result) {
                navigate_to_dashboard_with_error("Database error");
            } else {
                $row = mysqli_fetch_assoc($result);
                // check if from account balance can be negative.
                switch($faccount) {
                    case 1:
                        $fField = 'cash_bal';
                        $previous = $row['cash_bal'];
                        if($previous-$amount<0){
                            navigate_to_dashboard_with_error("Cannot have negative balance");
                            exit;
                        }
                    break;
                    case 2:
                        $fField = 'debit_bal';
                        $previous = $row['debit_bal'];
                        if($previous-$amount<0){
                            navigate_to_dashboard_with_error("Cannot have negative balance");
                            exit;
                        }
                    break;
                }

                // get field of to account
                switch($taccount) {
                    case 1:
                        $tField = 'cash_bal';
                    break;
                    case 2:
                        $tField = 'debit_bal';
                    break;
                    case 3:
                        // If to account is credit, then check if credit balance is negative.
                        $tField = 'credit_bal';
                        $cPrevious = $row['credit_bal'];
                        if($cPrevious-$amount<0){
                            navigate_to_dashboard_with_error("Cannot have negative credit");
                            exit;
                        }
                    break;
                }

                // Open new transaction to update both balances.
                mysqli_autocommit($conn,FALSE);
                $query_1 = add_to_log($conn,$username,$account,$type,$amount,$description,$new_balance_before,$new_balance_after);
                $result = mysqli_query($conn,$query_1);
                $query_2 = update_user_transfer_bal($conn,$username,$tField,$fField,$amount);
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