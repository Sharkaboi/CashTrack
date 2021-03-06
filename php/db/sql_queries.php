<?php

    // Auth queries
    function check_username($conn,$username) {
        $stripped_username = stripcslashes($username);
        $mysql_username = mysqli_real_escape_string($conn,$stripped_username);
        return "select * from user where username='$mysql_username'";
    }
    function insert_username($conn,$username,$pass,int $currency) {
        $stripped_username = stripcslashes($username);
        $stripped_pass = stripcslashes($pass);
        $mysql_username = mysqli_real_escape_string($conn,$stripped_username);
        $mysql_pass = mysqli_real_escape_string($conn,$stripped_pass);
        $mysql_currency = mysqli_real_escape_string($conn,"".$currency);
        $hashed_pass = password_hash($mysql_pass,PASSWORD_DEFAULT);
        return "INSERT INTO `user`(`username`, `hash_pwd`, `currency_default`) VALUES ('$mysql_username','$hashed_pass',$mysql_currency)";
    }
    function update_password($conn,$username,$npassword) {
        $stripped_username = stripcslashes($username);
        $stripped_pass = stripcslashes($npassword);
        $mysql_username = mysqli_real_escape_string($conn,$stripped_username);
        $mysql_pass = mysqli_real_escape_string($conn,$stripped_pass);
        $hashed_pass = password_hash($mysql_pass,PASSWORD_DEFAULT);
        return "update user set hash_pwd='$hashed_pass' where username='$mysql_username'";
    }
    function delete_account($conn,$username) {
        $stripped_username = stripcslashes($username);
        $mysql_username = mysqli_real_escape_string($conn,$stripped_username);
        return "delete from user where username='$mysql_username'";
    }
    function delete_data($conn,$username) {
        $stripped_username = stripcslashes($username);
        $mysql_username = mysqli_real_escape_string($conn,$stripped_username);
        return "delete from log where username='$mysql_username'";
    }

    // User table queries
    function get_user($conn,$username) {
        $stripped_username = stripcslashes($username);
        $mysql_username = mysqli_real_escape_string($conn,$stripped_username);
        return "select * from user where username='$mysql_username'";
    }
    function get_balances($conn,$username) {
        $stripped_username = stripcslashes($username);
        $mysql_username = mysqli_real_escape_string($conn,$stripped_username);
        return "select credit_bal,debit_bal,cash_bal from user where username='$mysql_username'";
    }
    function update_currency($conn,$username,int $currency){
        $stripped_username = stripcslashes($username);
        $mysql_username = mysqli_real_escape_string($conn,$stripped_username);
        $mysql_currency = mysqli_real_escape_string($conn,"".$currency);
        return "update user set currency_default=$mysql_currency where username='$mysql_username'";
    }
    function reset_balances($conn,$username) {
        $stripped_username = stripcslashes($username);
        $mysql_username = mysqli_real_escape_string($conn,$stripped_username);
        return "update user set credit_bal=0,cash_bal=0,debit_bal=0 where username='$mysql_username'";
    }

    // Log table queries
    function get_balance_graph_data($conn,$username) {
        $stripped_username = stripcslashes($username);
        $mysql_username = mysqli_real_escape_string($conn,$stripped_username);
        return "select balance_after,log_date from log where username='$mysql_username' ORDER BY log_date ASC;";
    }
    function get_total_balance($conn,$username) {
        $stripped_username = stripcslashes($username);
        $mysql_username = mysqli_real_escape_string($conn,$stripped_username);
        return "select balance_after from log where username='$mysql_username' ORDER BY log_id desc limit 1;";
    }
    function get_user_currency($conn,$username) {
        $stripped_username = stripcslashes($username);
        $mysql_username = mysqli_real_escape_string($conn,$stripped_username);
        return "select currency_default from user where username='$mysql_username'";
    }
    function get_spending_analysis_graph_data($conn,$username) {
        $stripped_username = stripcslashes($username);
        $mysql_username = mysqli_real_escape_string($conn,$stripped_username);
        // Sql without credit : select count(case type when 1 then 1 else null end) as count_add,count(case type when 2 then 1 else null end) as count_sub  from log WHERE username='test' AND account != 3
        // Includes credit balance add as count_sub and credit subtract as count_add.
        return "select count(case when ((type=1 AND account!=3) or (type=2 and account=3)) then 1 else null end) as count_add,count(case when ((type=2 and account!=3) or (type=1 and account=3)) then 1 else null end) as count_sub  from log WHERE username='$mysql_username'";
    }
    function get_common_descriptions($conn,$username) {
        $stripped_username = stripcslashes($username);
        $mysql_username = mysqli_real_escape_string($conn,$stripped_username);
        return "select description,count(*) as count FROM log where username='$mysql_username' group by description order by count desc";
    }
    function get_all_logs($conn,$username) {
        $stripped_username = stripcslashes($username);
        $mysql_username = mysqli_real_escape_string($conn,$stripped_username);
        return "select log_id,type,account,amount,log_date,description,balance_after,balance_before from log where username='$mysql_username' order BY log_date desc";
    }
    function get_log_by_query($conn,$username,$query) {
        $stripped_username = stripcslashes($username);
        $mysql_username = mysqli_real_escape_string($conn,$stripped_username);
        $stripped_query = stripcslashes($query);
        $mysql_query = mysqli_real_escape_string($conn,$stripped_query);
        if(trim($mysql_query) === ''){
            return get_all_logs($conn,$username);
        } else {
            return "select * from log where username='$mysql_username' and match(description) against('$mysql_query' in natural language mode) order by log_date desc";
        }
    }
    function update_description($conn,int $logId,$description) {
        $stripped_logId = stripcslashes($logId);
        $mysql_logId = mysqli_real_escape_string($conn,$stripped_logId);
        $stripped_description = stripcslashes($description);
        $mysql_description = mysqli_real_escape_string($conn,$stripped_description);
        return "update log set description='$mysql_description' where log_id=$mysql_logId";
    }
    function get_logs_from($conn,$logDate) {
        $stripped_logDate = stripcslashes($logDate);
        $mysql_logDate = mysqli_real_escape_string($conn,$stripped_logDate);
        return "select * from log where log_date >= '$mysql_logDate'";
    }
    function perform_undo_in_balance($conn,$type,$account,$amount,$username){
        $stripped_type = stripcslashes($type);
        $mysql_type = mysqli_real_escape_string($conn,$stripped_type);
        $stripped_account = stripcslashes($account);
        $mysql_account = mysqli_real_escape_string($conn,$stripped_account);
        $stripped_amount = stripcslashes($amount);
        $mysql_amount = mysqli_real_escape_string($conn,$stripped_amount);
        $stripped_username = stripcslashes($username);
        $mysql_username = mysqli_real_escape_string($conn,$stripped_username);
        switch($mysql_account) {
            case 1 :
                // transaction on cash balance only (add/sub)
                if($mysql_type == 1) {
                    // added to cash, so subtract amount
                    return "update user set cash_bal=cash_bal-'$mysql_amount' where username='$mysql_username'";
                } else if($mysql_type == 2){
                    // subtracted from cash, so add amount
                    return "update user set cash_bal=cash_bal+'$mysql_amount' where username='$mysql_username'";
                }
            break;
            case 2 :
                // transaction on debit balance only (add/sub)
                if($mysql_type == 1) {
                    // added to debit, so subtract amount
                    return "update user set debit_bal=debit_bal-'$mysql_amount' where username='$mysql_username'";
                } else if($mysql_type == 2){
                    // subtracted from debit, so add amount
                    return "update user set debit_bal=debit_bal+'$mysql_amount' where username='$mysql_username'";
                }
            break;
            case 3 :
                // transaction on credit balance only (add/sub)
                if($mysql_type == 1) {
                    // added to credit, so subtract amount
                    return "update user set credit_bal=credit_bal-'$mysql_amount' where username='$mysql_username'";
                } else if($mysql_type == 2){
                    // subtracted from credit, so add amount
                    return "update user set credit_bal=credit_bal+'$mysql_amount' where username='$mysql_username'";
                }
            break;
            case 12 :
                // transaction from cash to debit (transfer)
                // subtracted from cash and added to debit, so add to cash and subtract from debit
                return "update user set cash_bal=cash_bal+'$mysql_amount',debit_bal=debit_bal-'$mysql_amount' where username='$mysql_username'";
            break;
            case 21 :
                // transaction from debit to cash (transfer)
                // subtracted from debit and added to cash, so add to debit and subtract from cash
                return "update user set debit_bal=debit_bal+'$mysql_amount',cash_bal=cash_bal-'$mysql_amount' where username='$mysql_username'";
            break;
            case 13 :
                // transaction from cash to credit (transfer)
                // subtracted from cash and subtracted from credit (due), so add to cash and add to credit
                return "update user set cash_bal=cash_bal+'$mysql_amount',credit_bal=credit_bal+'$mysql_amount' where username='$mysql_username'";
            break;
            case 23 :
                // transaction from debit to credit (transfer)
                // subtracted from debit and subtracted to credit (due), so add to debit and add from credit
                return "update user set debit_bal=debit_bal+'$mysql_amount',credit_bal=credit_bal+'$mysql_amount' where username='$mysql_username'";
            break;
        }
        
    }
    function remove_log_with_id($conn,$log_id){
        $stripped_logId = stripcslashes($log_id);
        $mysql_logId = mysqli_real_escape_string($conn,$stripped_logId);
        return "delete from log where log_id='$mysql_logId'";
    }

    //Relative statements
    function add_to_log($conn,$username,int $account,int $type,int $amount,$description,int $new_balance_before,int $new_balance_after) {
        $stripped_username = stripcslashes($username);
        $mysql_username = mysqli_real_escape_string($conn,$stripped_username);
        $mysql_account = mysqli_real_escape_string($conn,$account);
        $mysql_type = mysqli_real_escape_string($conn,$type);
        $mysql_amount = mysqli_real_escape_string($conn,$amount);
        $stripped_desc = stripcslashes($description);
        $mysql_desc = mysqli_real_escape_string($conn,$stripped_desc);
        $mysql_balance_before = mysqli_real_escape_string($conn,$new_balance_before);
        $mysql_balance_after = mysqli_real_escape_string($conn,$new_balance_after);
        //switch($mysql_account) {
        //    case 1 :
        //        $field = "cash_bal";
        //    break;
        //    case 2 :
        //        $field = "debit_bal";
        //    break;
        //    case 3 :
        //        $field = "credit_bal";
        //    break;
        //}
        $sql = "insert into log (username,type,account,amount,description,balance_before,balance_after) VALUES ('$mysql_username',$mysql_type,$mysql_account,$mysql_amount,'$mysql_desc',$mysql_balance_before,$mysql_balance_after)";
        //$sql2 = "update user set $field=$field+$mysql_amount where username=$mysql_username";
        return $sql;
    }
    function update_user_add_bal($conn,$username,int $account,int $type,int $amount,$description,int $new_balance_before,int $new_balance_after) {
        $stripped_username = stripcslashes($username);
        $mysql_username = mysqli_real_escape_string($conn,$stripped_username);
        $mysql_account = mysqli_real_escape_string($conn,$account);
        $mysql_type = mysqli_real_escape_string($conn,$type);
        $mysql_amount = mysqli_real_escape_string($conn,$amount);
        $stripped_desc = stripcslashes($description);
        $mysql_desc = mysqli_real_escape_string($conn,$stripped_desc);
        $mysql_balance_before = mysqli_real_escape_string($conn,$new_balance_before);
        $mysql_balance_after = mysqli_real_escape_string($conn,$new_balance_after);
        switch($mysql_account) {
            case 1 :
                $field = "cash_bal";
            break;
            case 2 :
                $field = "debit_bal";
            break;
            case 3 :
                $field = "credit_bal";
            break;
        }
        //$sql = "insert into log (username,type,account,amount,description,balance_before,balance_after) VALUES ('$mysql_username',$mysql_type,$mysql_account,$mysql_amount,'$mysql_desc',$mysql_balance_before,$mysql_balance_after)";
        $sql2 = "update user set $field=$field+$mysql_amount where username='$mysql_username'";
        return $sql2;
    }
    function update_user_sub_bal($conn,$username,$field,int $amount) {
        $stripped_username = stripcslashes($username);
        $mysql_username = mysqli_real_escape_string($conn,$stripped_username);
        $mysql_field = mysqli_real_escape_string($conn,$field);
        $mysql_amount = mysqli_real_escape_string($conn,$amount);
        //$sql = "insert into log (username,type,account,amount,description,balance_before,balance_after) VALUES ('$mysql_username',$mysql_type,$mysql_account,$mysql_amount,'$mysql_desc',$mysql_balance_before,$mysql_balance_after)";
        $sql2 = "update user set $mysql_field=$mysql_field-$mysql_amount where username='$mysql_username'";
        return $sql2;
    }
    function update_user_transfer_bal($conn,$username,$tField,$fField,int $amount) {
        $stripped_username = stripcslashes($username);
        $mysql_username = mysqli_real_escape_string($conn,$stripped_username);
        $mysql_tField = mysqli_real_escape_string($conn,$tField);
        $mysql_fField = mysqli_real_escape_string($conn,$fField);
        $mysql_amount = mysqli_real_escape_string($conn,$amount);
        //$sql = "insert into log (username,type,account,amount,description,balance_before,balance_after) VALUES ('$mysql_username',$mysql_type,$mysql_account,$mysql_amount,'$mysql_desc',$mysql_balance_before,$mysql_balance_after)";
        if($mysql_tField=="credit_bal") {
            $sql2 = "update user set $mysql_fField=$mysql_fField-$mysql_amount,$mysql_tField=$mysql_tField-$mysql_amount where username='$mysql_username'";
        } else {
            $sql2 = "update user set $mysql_fField=$mysql_fField-$mysql_amount,$mysql_tField=$mysql_tField+$mysql_amount where username='$mysql_username'";
        }
        return $sql2;
    }
?>
