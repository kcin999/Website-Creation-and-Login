<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body style = "background-color:black;">
    <div class="page-header">
        <h1 style="color:white;">Welcome Agent <?php echo htmlspecialchars($_SESSION["last_name"]); ?>. We were expecting you.</h1>
        <h2 style="color:white;"><b>Are you ready for mission <?php echo htmlspecialchars($_SESSION["fav_number"]); ?>?</b></h2>
        <h2 style="color:white;">Your last login was <?php echo htmlspecialchars($_SESSION["last_login"]);?></h2>
    </div>
    <p>
        <a href="reset-password.php" class="btn btn-warning">Reset Your Password</a>
        <a href="change-number.php" class="btn btn-warning">Change Favorite Number</a>
        <a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a>
    </p>
</body>
</html>