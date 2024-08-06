<?php
/*
session_start();
if (!isset($_SESSION['logged_in'])) {
	echo "<script>alert('You can not access this page, kindly login before you can access this page');window.Location:'signup.php'</script>";
   // header("Location: signup.php");
    //exit(); 
}*/

ini_set('date.timezone', 'Africa/Accra');
include('dbconnect.php');

session_start();
$timelimit = 15 * 60;
$now = time();

if(!isset($_SESSION['email'])){
	echo "<script>alert('You can not access this page! Kindly login before, you can access this page');window.location='signup.php'</script>";
	exit;
}
if($now > $_SESSION['time']+ $timelimit) {
	echo "<script>alert('Your session has expired! Login to continue your process');window.location='signup.php'</script>";
	exit;
}

try {
	$sql = 'SELECT * FROM users';
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	$rowCount = $stmt->rowCount();
} catch (PDOException $e) {
	
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Dashboard</title>
	<!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">-->
	<style>
		header {
			float: right;
		}

		header nav a {
			font-size: 20px;
			color: black;
			text-decoration: none;
			margin-right: 20px;
		}

		header li {
			list-style: none;
		}

		header #signout {
			border-radius: 3px;
			padding: 5px;
			background-color: lightblue;
			color: black;
			font-weight: bolder;
		}

		.container {
			max-width: 600px;
			margin:0 auto;
			padding: 50px;
			position: relative;
			top: 50px;
			box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
		}

		.container h1 {
			text-align: center;
			font-size: 50px;
		}

		/**
		 * Styling the table */
		 table{
		 	border:1px solid black;
		 	border-collapse: collapse;
		 	width: 80%;
		 }

		 th{
		 	background-color: green;
		 	color: white;
		 }

		 table a {
		 	text-decoration: none;
		 }

		 table #delete {
		 	color: red;
		 }
		 table #edit {
		 	color: blue;
		 }

	</style>
</head>
<body>
	<header>
		<nav>
			<ol>
				<li>
					<a href="#"><?php echo $_SESSION['email'];?></a>
					<a href="logout.php" id="signout">Sign Out</a>
				</li>
			</ol>
		</nav>
	</header>

	<div class="container">
		<h1>Welcome to Dashboard</h1>
		 <!--<a href="logout.php" class="btn btn-warning">Logout</a>-->
	</div>  
	<br/><br/><br/><br/>
	
	<center>The total number of registered people is: <?php echo $rowCount;?></center>

	<table border='1' align = 'center'>
		<tr>
			<th align="left">#</th>
			<th align="left">Username</th>
			<th align="left">First Name</th>
			<th align="left">Last Name</th>
			<th align="left">E-mail</th>
			<th align="left">Delete</th>
			<th align="left">Edit</th>
		</tr>

		<?php
		//set background color
		$bg = '#eeeeee';

		if($rowCount > 0) {
			for($i=1; $row = $stmt->fetch(PDO::FETCH_ASSOC); $i++){
				//$bg =($bg == '#eeeeee' ? '#ffffff': '#eeeeee');
				if($bg == '#eeeeee') {
					$bg = '#ffffff';
				} else {
					$bg = '#eeeeee';
				}

				echo '<tr bgcolor = "' .$bg . '">';
				echo '<td>' . $i . '</td>';
				echo '<td>' . $row['username'] . '</td>';
				echo '<td>' . $row['firstName'] . '</td>';
				echo '<td>' . $row['lastName'] . '</td>';
				echo '<td>' . $row['email'] . '</td>';
				echo '<td><a href = "delete.php?username=' .$row['username'] . '" id="delete">Delete<a/></td>';
				echo '<td><a href = "edit.php?username=' .$row['username'] . '" id="edit">Edit<a/></td>';
				echo '</tr>';
			}
		}

		?>
	</table>
	<script>
	window.onload = function() {
    if (document.cookie.indexOf('logged_out=true') !== -1) {
        // Clear the cookie
        document.cookie = 'logged_out=;expires=Thu, 01 Jan 1970 00:00:00 UTC;path=/;';

        // Redirect to the login page
        window.location.href = 'signup.php';
    }
};
</script>
</body>
</html>