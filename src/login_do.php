<?php

include "init.php";

$user_username = isset($_POST["username"]) ? sanitize($_POST["username"]) : "";
$user_password = isset($_POST["password"]) ? pass_hash($_POST["password"]) : "";
$user_name;
$user_email;
$user_type;
$user_persist = isset($_POST["persist"]) ? (bool)($_POST["persist"] == "1") : FALSE;

$ses_username = isset($_COOKIE["user[user]"]) ? $_COOKIE["user[user]"] : "";
$ses_password = isset($_COOKIE["user[pass]"]) ? $_COOKIE["user[pass]"] : "";

$sql = $conn->prepare("SELECT * FROM Users where user_username = :user_username");
$sql->bindParam(':user_username', $user_username);
$sql->execute();
$result = $sql->fetchAll();

$user_name = "";
$user_email = "";
$user_type = "none";

if (sizeof($result) > 0) {
  if ($user_password === $result[0]["user_password"]) {
    $user_name = $result[0]["user_name"];
    $user_email = $result[0]["user_email"];
    $user_type = $result[0]["user_type"];

		$expires = time();
    if ($user_persist === TRUE) {
    	$expires += 60 * 60 * 24 * 365;
    }
    else {
    	$expires += 60 * 60;
    }
    
    setcookie("user[user]", $user_username, $expires);
    setcookie("user[pass]", $user_password, $expires);

		header("Location: " . rootredirect("//", $conn, $style_code));
  }
  else {
		header("Location: " . rootredirect("//login", $conn, $style_code));
  }
}
else {
	header("Location: " . rootredirect("//login", $conn, $style_code));
}

include "fin.php";

?>
