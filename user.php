<?php
class User {
	private $conn;

	public function __construct($conn){
		$this->conn = $conn;
	}

	public function register($username, $firstName, $lastName, $email, $password) {
		//validation
		if(empty($username) || !preg_match("/^[a-zA-Z0-9]*$/", $username)){
			throw new Exception("Username can contain letters and numbers only");
		}

		if(empty($firstName) || !preg_match("/^[a-zA-Z-]*$/", $firstName)){
			throw new Exception("First name can contain only letters");
		} 

		if(empty($lastName) || !preg_match("/^[a-zA-Z-]*$/", $lastName)){
			throw new Exception("Last name can contain only letters and hyphens");
		}

		if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)){
			throw new Exception("Invalid email format");
		}

		if(empty($password) || strlen($password) < 8 || !preg_match("/^[a-zA-Z0-9]*$/", $password)){
			throw new Exception("Password must be at least 8 characters long and should contain letters and numbers");
		}


		$sql = 'SELECT * FROM users WHERE email=?';
		$stmt = $this->conn->prepare($sql);
		$stmt->execute([$email]);

		if($stmt->rowCount() > 0){
			throw new Exception('Email already exists');
		}

		//hash the password
		$password = md5($password);

		//insert the new user into the database
		$sql = 'INSERT INTO users(username, firstName, lastName, email, password) VALUES (?, ?, ?, ?, ?)';
		$stmt = $this->conn->prepare($sql);
		$stmt->execute([$username, $firstName, $lastName, $email, $password]);
	}


	public function login($email, $password){
		//validation
		if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)){
			throw new Exception("Invalid email format");
			//echo "<script>alert('Invalid email format');</script>";
		}

		if(empty($password)) {
			throw new Exception("Password is required");
		}

		//hash the password
		$password = md5($password);

		//check if the user exists
		$sql = 'SELECT * FROM users WHERE email = ? AND password =?';
		$stmt = $this->conn->prepare($sql);
		$stmt->execute([$email, $password]);

		if($stmt->rowCount() == 1) {
			//user aunthenticated, set session or cookie
			session_start();
			$_SESSION['email'] = $email;
			$_SESSION['time'] = time();
			//Redirect to dashboard or another page
			header('location: hotelbooking.php');
			//exit();
		} else {
			//Authentication failed, show error message
			throw new Exception("Invalid email or password");
		}
	}


	public function logout() {
		//End session
		session_start();
		session_unset();
		session_destroy();

		//Redirect to login page
		header('location: signup.php');
	}
}
?>