<?php

include "config/config.php";

$servername = $CONFIG['dbhost'];
$username = $CONFIG['dbuser'];
$password = $CONFIG['dbpassword'];
$dbname = $CONFIG['dbname'];

$conn = null;

try {
	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
	// Set the PDO error mode to exception
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e) {
	echo "Error: " . $e->getMessage();
}

include "functions.php";
include "check.php";

?>
