<?php
    // Import constants and secrets.
    include('../db/db_config.php');
    include('../db/sql_queries.php');

    if(!isset($_SESSION)) { 
        session_start(); 
    }

    // check if session active 
    if(!isset($_SESSION['username'])){
        navigate_to_login_page("Not logged in");
    }

    $username = $_SESSION['username'];

    $query = get_user_currency($conn,$username);
    $result = mysqli_query($conn,$query);
    if(!$result) {
        show_alert_and_navigate("Database error!");
    } else {
        $row = mysqli_fetch_assoc($result);

        switch ($row['currency_default']) {
            case 1:
                $currency = "&#8377 ";
                break;
            case 2:
                $currency = "$";
                break;
            case 3:
                $currency = "£";
                break;
            default:
                $currency = "&euro ";
        }
    } 

    $query = get_all_logs($conn,$username);
    $result = mysqli_query($conn,$query);


    $output = '<table> <thead> <tr> <th scope="col">Type</th> <th scope="col">Account</th> <th scope="col">Amount</th> <th scope="col">Date</th> <th scope="col">Description</th> <th scope="col">Balance Before</th> <th scope="col">Balance After</th> </tr> </thead> <tbody>';
            
    if(mysqli_num_rows($result)==0) {
        $output .= '<tr><td colspan="7">No history available.</td></tr>';
    } else {

        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            $log_id = $row['log_id'];
            $type = $row['type'];
            $account = $row['account'];
            $amount = $row['amount'];
            $mysqlDate = $row['log_date'];
            $phpdate = strtotime( $mysqlDate );
            $date = date('d/m/y g:i A', $phpdate );
            $desc = $row['description'];
            $balance_before = $row['balance_before'];
            $balance_after = $row['balance_after'];
            $output .= '<tr>';
            $output .= '<td>';
            if($type == 1) {
                $output .= 'Added to';
            } else if($type == 2){
                $output .= 'Subtracted from';
            } else {
                $output .= 'Transferred to and from';
            }
            $output .= '</td>';
            $output .= '<td>';
            switch($account) {
                case 1 :
                    $output .= 'Cash';
                break;
                case 2 :
                    $output .= 'Debit';
                break;
                case 3 :
                    $output .= 'Credit';
                break;
                case 12 :
                    $output .= 'Cash to Debit';
                break;
                case 21 :
                    $output .= 'Debit to Cash';
                break;
                case 13 :
                    $output .= 'Cash to Credit';
                break;
                case 23 :
                    $output .= 'Debit to Credit';
                break;
            }
            $output .= '</td>';
            $output .= '<td>';
            $output .= $currency.$amount;
            $output .= '</td>';
            $output .= '<td>';
            $output .= $date;
            $output .= '</td>';
            $output .= '<td>';
            $output .= $desc;
            $output .= '</td>';
            $output .= '<td>';
            $output .= $currency.$balance_before;
            $output .= '</td>';
            $output .= '<td>';
            $output .= $currency.$balance_after;
            $output .= '</td>';                                    
            $output .= '</tr>';
        }
    }

    $output .= '</tbody></table>';

    $filename = "cashtrack_". $username . "_logs_" . date('Ymd') . ".xls";
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename='.$filename);
    echo utf8_decode($output);
    navigate();
    
    function show_alert_and_navigate($error) {
        echo '<script>';
        echo 'alert("Error : '.$error.'");';
        echo 'window.location.href = "http://localhost/dashboard/settings";';
        echo '</script>';
    }
    function navigate() {
        echo '<script>';
        echo 'window.location.href = "http://localhost/dashboard/settings";';
        echo '</script>';
    }

    function navigate_to_login_page($error) {
        echo '<script>';
        echo 'alert("Error : '.$error.'");';
        echo 'window.location.href = "http://localhost/php/pages/login.php";';
        echo '</script>';
    }

    mysqli_close($conn);
?>