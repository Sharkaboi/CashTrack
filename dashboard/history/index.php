<!DOCTYPE html>
<html lang="en">
<head>
    <!--Meta Tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Favicons-->
    <link rel="apple-touch-icon" sizes="57x57" href="../../assets/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="../../assets/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="../../assets/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="../../assets/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="../../assets/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="../../assets/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="../../assets/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="../../assets/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="../../assets/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="../../assets/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="../../assets/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="../../assets/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="../../assets/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <!--Other-->
    <title>History</title>
    <!--CSS-->
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/fontawesome.all.min.css">
</head>
<body>
        
    <?php
        // Import constants and secrets.
        include('../../php/db/db_config.php');
        include('../../php/db/sql_queries.php');

        if(!isset($_SESSION)) { 
            session_start(); 
        }

        // check if session active 
        if(!isset($_SESSION['username'])){
            navigate_to_login_page("Not logged in");
        }

        function navigate_to_login_page($error) {
            echo '<script>';
            echo 'alert("Error : '.$error.'");';
            echo 'window.location.href = "http://localhost/php/pages/login.php";';
            echo '</script>';
        }

    ?>

    <!-- Header for settings, history-->
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="">
                <img src="../../assets/favicon/android-icon-36x36.png" width="30" height="30" class="d-inline-block align-top" alt="Logo">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav mr-auto">
                    <a class="nav-item nav-link" href="../">Dashboard</a>
                    <a class="nav-item nav-link active" href=".">History <span class="sr-only">(current)</span></a>
                    <a class="nav-item nav-link" href="../settings">Settings</a>
                    <a class="nav-item nav-link text-danger" href="../../php/auth/logout.php">Log Out</a>
                </div>
                <span class="navbar-text ml-auto mr-3">
                    Welcome, 
                    <span class="font-weight-bold">
                        <?php echo '@'.$_SESSION['username']; ?>
                    </span>
                </span>
                <form class="form-inline" action="" method="GET">
                    <input class="form-control mr-sm-2" type="text" name="query" placeholder="Search" aria-label="Search">
                    <input class="btn btn-outline-primary my-2 my-sm-0" type="submit" value="Search">
                </form>
            </div>
        </nav>
    </header> 

    <main class="p-3">
        <div class="card container p-1">
            <div class="card-header">History Log</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr class="table-active">
                                <th scope="col">Type</th>
                                <th scope="col">Account</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Date</th>
                                <th scope="col">Description</th>
                                <th scope="col">Balance Before</th>
                                <th scope="col">Balance After</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $username = $_SESSION['username'];
                                $query = get_user_currency($conn,$username);
                                $result = mysqli_query($conn,$query);
                                if(!$result) {
                                    show_alert("Database error!");
                                } else {
                                    $row = mysqli_fetch_assoc($result);

                                    switch ($row['currency_default']) {
                                        case 1:
                                            $currency = "₹";
                                            break;
                                        case 2:
                                            $currency = "$";
                                            break;
                                        case 3:
                                            $currency = "£";
                                            break;
                                        default:
                                            $currency = "€";
                                    }
                                } 

                                if(isset($_GET['query'])){
                                    $search_query = $_GET['query'];
                                    $query = get_log_by_query($conn,$username,$search_query);
                                    $result = mysqli_query($conn,$query);
                                    render_from_data($conn,$username,$currency,$result);
                                } else {
                                    $query = get_all_logs($conn,$username);
                                    $result = mysqli_query($conn,$query);
                                    render_from_data($conn,$username,$currency,$result);
                                }

                                function render_from_data($conn,$username,$currency,$result) {
                                    if(mysqli_num_rows($result)==0) {
                                        echo '<tr><td colspan="7" class="text-center">No history available.</td></tr>';
                                    } else {
                                        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                            $type = $row['type'];
                                            $account = $row['account'];
                                            $amount = $row['amount'];
                                            $date = $row['log_date'];
                                            $desc = $row['description'];
                                            $balance_before = $row['balance_before'];
                                            $balance_after = $row['balance_after'];
                                            echo '<tr>';
                                            echo '<td>';
                                            if($type == 1) {
                                                echo '<i class="fa fa-plus text-success"></i>';
                                            } else {
                                                echo '<i class="fa fa-minus text-danger"></i>';
                                            }
                                            echo '</td>';
                                            echo '<td>';
                                            switch($account) {
                                                case 1 :
                                                    echo 'Cash';
                                                break;
                                                case 2 :
                                                    echo 'Debit';
                                                break;
                                                case 3 :
                                                    echo 'Credit';
                                                break;
                                            }
                                            echo '</td>';
                                            echo '<td>';
                                            echo $currency.$amount;
                                            echo '</td>';
                                            echo '<td>';
                                            echo $date;
                                            echo '</td>';
                                            echo '<td>';
                                            echo $desc;
                                            echo '</td>';
                                            echo '<td>';
                                            echo $currency.$balance_before;
                                            echo '</td>';
                                            echo '<td>';
                                            echo $currency.$balance_after;
                                            echo '</td>';                                    
                                            echo '</tr>';
                                        }
                                    }
                                }

                                function show_alert($error) {
                                    echo '<script>';
                                    echo 'alert("Error : '.$error.'");';
                                    echo '</script>';
                                }

                                mysqli_close($conn);
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    
    <!--JS-->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
  
</body>
</html>