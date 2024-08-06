<?php

//error_reporting(E_ALL);
//ini_set('display_errors', 1);

@include('dbconnect.php');
@include('user.php');

$user = new User($conn);

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['submit'])) {
        $user->register($_POST['username'], $_POST['firstName'], $_POST['lastName'], $_POST['email'], $_POST['password']);
        echo "<script>alert('Registration successful. Login to continue');</script>";
    } elseif(isset($_POST['login'])){
        $user->login($_POST['email'], $_POST['password']);
        //echo "<script>alert('Login successful. Redirectiing.. ')</script>";
    }
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luxury Hotel</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<style>.error{color: #ff0000; font-size: 15px; font-weight: bold;}</style>
<body>
    <header>
        <h1>Luxury Hotel</h1>
    </header>
    <div class="form">
        <input type="checkbox" id="chk">
        <div class="signup">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" autocomplete="off">
                <label for="chk" style="color: white;">Sign Up</label>
                <!--<label>Username: </label> <br/>-->
                <input type="text" name="username" placeholder="username" required="" value=""><br/>
                <!--<label>First Name: </label> <br/>-->
                <input type="text" name="firstName" placeholder="First Name" required="" value=""><br/>
                <!-- <label>Last Name: </label>-->
                <input type="text" name="lastName" placeholder="Last Name" required="" value=""><br/>
                <!--<label>Email: </label> <br/>-->
                <input type="email" name="email" placeholder="Enter your email" required="" value=""><br/>
                <!--<label>Password: </label> <br/>-->
                <input type="password" name="password" placeholder="Enter your password" required="" value="" id="myInput">
                <label style="font-size: 15px; position: relative; bottom: 50px; right: 60px; color:white;"><input type="checkbox" onclick="myFunction('myInput')">Show password</label>
                <input type="submit" name="submit" value="Sign Up">
                <!--<input type="reset" name="reset" value="Cancel">-->
            </form>
        </div>
       



    <div class="login">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" autocomplete="off">
            <label for="chk">Log In</label>
            <input type="email" name="email" placeholder="Enter your email" required=""><br/>
            <!--<label>Password: </label> <br/>-->
            <input type="password" name="password" placeholder="Enter your password" required="" id="loginInput"><br/>
            <label style="font-size: 15px; position: relative; bottom: 50px; right: 60px; color:#6d44b8;"><input type="checkbox" onclick="myFunction('loginInput')">Show password</label>
            <input type="submit" name="login" value="Log In">
			<center style='font-size: 12px;'>Don't have an account? <a href="signup.php">Click to Register</a></center>
        </form>
    </div>
    </div>

    <script>
    	function myFunction(id) {
    		var x = document.getElementById(id);
    		if(x.type === 'password'){
    			x.type = 'text';
    		} else {
    			x.type = 'password';
    		}
    	}
    </script>
</body>
</html>