<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$new_number = "";
$new_number_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate new password
    if(empty(trim($_POST["new_number"]))){
        $new_number_err = "Please enter the new integer.";     
    } else{
        $new_number = trim($_POST["new_number"]);
    }
    
        
    // Check input errors before updating the database
    if(empty($new_number_err)){
        // Prepare an update statement
        $sql = "UPDATE users SET fav_number = ? WHERE id = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ii", $param_number, $param_id);
            
            // Set parameters
            $param_number = $new_number;
            $param_id = $_SESSION["id"];
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Password updated successfully. Destroy the session, and redirect to login page
				$_SESSION["fav_number"] = explode(".",$new_number)[0];
                header("location: welcome.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body style = "background-color:black;">

    <div class="wrapper">
        <h2 style="color:white;">Change your number</h2>
        <p style="color:white;">Please fill out this form to change your favorite number</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
            <div class="form-group <?php echo (!empty($new_number_err)) ? 'has-error' : ''; ?>">
                <label style="color:white;">New Number</label>
                <input type="number" min="-100000000000000000000" step="1" name="new_number" class="form-control" value="<?php echo $new_number; ?>">
                <span  style="color:white;"class="help-block"><?php echo $new_number_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <a class="btn btn-link" href="welcome.php">Cancel</a>
            </div>
        </form>
    </div>    
</body>
</html>