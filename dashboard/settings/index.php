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
    <title>Settings</title>
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
            <a class="navbar-brand" href="http://localhost">
                <img src="../../assets/favicon/android-icon-36x36.png" width="30" height="30" class="d-inline-block align-top" alt="Logo">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav mr-auto">
                    <a class="nav-item nav-link" href="../">Dashboard</a>
                    <a class="nav-item nav-link" href="../history">History</a>
                    <a class="nav-item nav-link active" href=".">Settings <span class="sr-only">(current)</span></a>
                    <a class="nav-item nav-link text-danger" href="../../php/auth/logout.php">Log Out</a>
                </div>
                <span class="navbar-text">
                    Welcome, 
                    <span class="font-weight-bold">
                        <?php echo '@'.$_SESSION['username']; ?>
                    </span>
                </span>
            </div>
        </nav>
    </header>   

    <?php
        $username = $_SESSION['username'];
        $query = get_user_currency($conn,$username);
        $result = mysqli_query($conn,$query);
        if(!$result) {
            show_alert("Database error!");
        } else {
            $row = mysqli_fetch_assoc($result);
            $currencyChoice = $row['currency_default'];
        } 
    ?>

    <main>
        <div class="container pt-5">
            <div class="card">
                <h5 class="card-header">Account Settings</h5>
                <div class="card-body">
                    <form action="../../php/auth/password_change.php" onsubmit="return validateAccountSettingsForm()" method="post">
                        <div class="form-group">
                            <label for="cpassword">Enter Current Password</label>
                            <input type="password" class="form-control form-control-lg" id="cpassword" name="cpassword" placeholder="Enter Current Password" required>
                        </div>
                        <div class="form-group">
                            <label for="cpassword">Enter New Password</label>
                            <input type="password" class="form-control form-control-lg" id="npassword" name="npassword" placeholder="Enter New Password" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg m-1">Change Password</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="container pt-3">
            <div class="card">
                <h5 class="card-header">Account Preferences</h5>
                <div class="card-body">
                    <form action="../../php/routines/update_currency.php" method="post">
                        <div class="form-group">
                            <label for="currency">Currency prefix</label>
                            <select class="custom-select custom-select-lg" name="currency" id="currency">
                                <?php
                                    switch ($currencyChoice) {
                                        case 1:
                                            echo '<option value="1" selected>INR</option><option value="2">USD</option><option value="3">GBP</option><option value="4">EUR</option>';
                                            break;
                                        case 2:
                                            echo '<option value="1">INR</option><option value="2" selected>USD</option><option value="3">GBP</option><option value="4">EUR</option>';
                                            break;
                                        case 3:
                                            echo '<option value="1">INR</option><option value="2">USD</option><option value="3" selected>GBP</option><option value="4">EUR</option>';
                                            break;
                                        default:
                                            echo '<option value="1">INR</option><option value="2">USD</option><option value="3">GBP</option><option value="4" selected>EUR</option>';
                                            break;
                                    }
                                ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg m-1">Change currency prefix</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="container py-5">
            <div class="row mb-auto mt-auto">
                <div class=" col-sm-12 col-md-6 text-center p-1">
                    <button class="btn btn-danger btn-lg m-1" onclick="confirmDeleteAccount()">Delete Account</button><br>
                    <small class="text-muted">Deletes your account permanently.</small>
                </div>
                <div class="col-sm-12 col-md-6 text-center p-1">
                    <button class="btn btn-danger btn-lg m-1" onclick="confirmResetData()">Reset Data</button><br>
                    <small class="text-muted">This only deletes your price history and not your account.</small>
                </div>
            </div>
        </div>
    </main>

    <!--JS-->
    <script src="../../js/form_validations.js"></script>
    <script src="../../js/event_listeners.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
  
</body>
</html>