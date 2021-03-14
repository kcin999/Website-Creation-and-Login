<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = $confirm_password = $first_name = $last_name = $short_bio = $fav_number = $email_address = "";
$username_err = $password_err = $confirm_password_err = $first_name_err = $last_name_err = $short_bio_err = $fav_number_err = $email_address_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
	if(empty(trim($_POST["first_name"]))){
		$first_name_err = "Please enter your first name.";
	} else{
		$first_name = trim($_POST["first_name"]);
	}

	if(empty(trim($_POST["last_name"]))){
		$last_name_err = "Please enter your last name.";
	} else{
		$last_name = trim($_POST["last_name"]);
	}

	if(empty(trim($_POST["short_bio"]))){
		$short_bio_err = "Please enter your first name.";
	} else{
		$short_bio = trim($_POST["short_bio"]);
	}

	if(empty(trim($_POST["fav_number"]))){
		$fav_number_err = "Please enter your favorite number.";
	} else{
		$fav_number = trim($_POST["fav_number"]);
	}

	if(empty(trim($_POST["email_address"]))){
		$email_address_err = "Please enter your email address.";
	} else{
		$email_address = trim($_POST["email_address"]);
	}
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($first_name_err) && empty($last_name_err) && empty($short_bio_err) && empty($fav_number_err) && empty($email_address_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password, first_name, last_name, short_bio, email_address, fav_number) VALUES (?, ?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssssi", $param_username, $param_password, $param_first_name, $param_last_name, $param_short_bio, $param_email_address, $param_fav_number);
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
			$param_first_name = $first_name;
			$param_last_name = $last_name;
			$param_short_bio = $short_bio;
			$param_email_address = $email_address;
			$param_fav_number = $fav_number;
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Something went wrong. Please try again later.";
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
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
			<div class="form-group <?php echo (!empty($first_name_err)) ? 'has-error' : ''; ?>">
                <label>First Name</label>
                <input type="text" name="first_name" class="form-control" value="<?php echo $first_name; ?>">
                <span class="help-block"><?php echo $first_name_err; ?></span>
            </div>
			<div class="form-group <?php echo (!empty($last_name_err)) ? 'has-error' : ''; ?>">
                <label>Last Name</label>
                <input type="text" name="last_name" class="form-control" value="<?php echo $last_name; ?>">
                <span class="help-block"><?php echo $last_name_err; ?></span>
            </div>
			<div class="form-group <?php echo (!empty($short_bio_err)) ? 'has-error' : ''; ?>">
                <label>Short Bio</label>
                <input type="text" name="short_bio" class="form-control" value="<?php echo $short_bio; ?>">
                <span class="help-block"><?php echo $short_bio_err; ?></span>
            </div>
			<div class="form-group <?php echo (!empty($fav_number_err)) ? 'has-error' : ''; ?>">
                <label>Favorite Integer</label>
                <input type="number" min="-100000000000000000000" step="1" name="fav_number" class="form-control" value="<?php echo $fav_number; ?>">
                <span class="help-block"><?php echo $fav_number_err; ?></span>
            </div>
			<div class="form-group <?php echo (!empty($email_address_err)) ? 'has-error' : ''; ?>">
                <label>Email</label>
                <input type="email" name="email_address" class="form-control" value="<?php echo $email_address; ?>">
                <span class="help-block"><?php echo $email_address_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>    
</body>
</html>