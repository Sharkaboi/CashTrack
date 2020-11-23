<?php

    // Auth queries
    function check_username($conn,$username) {
        $stripped_username = stripcslashes($username);
        $mysql_username = mysqli_real_escape_string($conn,$stripped_username);
        return "select * from user where username='$mysql_username'";
    }
    function insert_username($conn,$username,$pass,int $currency) {
        $stripped_username = stripcslashes($username);
        $striped_pass = stripcslashes($pass);
        $mysql_username = mysqli_real_escape_string($conn,$stripped_username);
        $mysql_pass = mysqli_real_escape_string($conn,$striped_pass);
        $mysql_currency = mysqli_real_escape_string($conn,"".$currency);
        $hashed_pass = password_hash($mysql_pass,PASSWORD_DEFAULT);
        return "INSERT INTO `user`(`username`, `hash_pwd`, `currency_default`) VALUES ('$mysql_username','$hashed_pass',$mysql_currency)";
    }
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
        return "select description,count(*) as count FROM log where username='$mysql_username' group by description ";
    }
?>
