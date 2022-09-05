<?php

include "init.php";

$check = 0;
$value_string = array();
$redirect = "//home";

$value_string[0] = isset($_POST["user_name"]) ? sanitize($_POST["user_name"]) : "";
$value_string[1] = isset($_POST["user_email"]) ? sanitize($_POST["user_email"]) : "";
$value_string[2] = isset($_POST["user_password"]) ? $_POST["user_password"] : "";
$value_string[3] = isset($_POST["user_newpassword1"]) ? $_POST["user_newpassword1"] : "";
$value_string[4] = isset($_POST["user_newpassword2"]) ? $_POST["user_newpassword2"] : "";

if ($user_type === "admin") {
	for ($i = 0; $i < sizeof($value_string); $i++) {
		$value_string[$i] = preg_replace("/\'/", "''", $value_string[$i]);
	}

	// Check that all of the fields are valid

	// User name
	if ($value_string[0] === "") {
		$check |= 1;
	}

	// User email
	if (parse_email_address($value_string[1])) {
		$check |= 2;
	}

	// User password
	if (pass_hash($value_string[2]) !== $user_password) {
		$check |= 4;
	}

	// New passwords
	if ($value_string[3] != $value_string[4]) {
		$check |= 8;
	}

	if ($check != 0) {
		$redirect  = "./editacc.php?check=" . $check;
		for ($i = 0; $i < 2; $i++) {
			$redirect .= "&v" . $i . "=" . $value_string[$i];
		}
	}
	else {
		// Everything was okay, so we can do the update
		$sql = $conn->prepare("UPDATE Users SET user_name = :user_name WHERE user_uid = :user_uid");
		$user_name = $value_string[0];
		$sql->bindParam(':user_name', $user_name);
		$sql->bindParam(':user_uid', $user_uid);
		$sql->execute();

		$sql = $conn->prepare("UPDATE Users SET user_email = :user_email WHERE user_uid = :user_uid");
		$user_email = $value_string[1];
		$sql->bindParam(':user_email', $user_email);
		$sql->bindParam(':user_uid', $user_uid);
		$sql->execute();

		if ($value_string[3] != "") {
			$sql = $conn->prepare("update Users set user_password = :password where user_uid = :user_uid");
			$password = pass_hash($value_string[3]);
			$sql->bindParam(':password', $password);
			$sql->bindParam(':user_uid', $user_uid);
			$sql->execute();
		}
	}
}

header("Location: " . rootredirect($redirect, $conn, $style_code));

include "fin.php";

?>

