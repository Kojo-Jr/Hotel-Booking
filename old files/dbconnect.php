<?php
define('SERVER', 'localhost');
define('DB_USER', 'root');
define('DB_PWD', '');

try{
	$conn = new PDO("mysql:host=" . SERVER . ";dbname=luxuryhotel", DB_USER, DB_PWD);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	//echo "Database connected successfully";
} catch(Exception $e){
	echo "Connection failed: " . $e->getMessage();
}

?>