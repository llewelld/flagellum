<?php

include "init.php";

$list_id = isset($_POST["list_id"]) ? $_POST["list_id"] : -1;
$password = isset($_POST["password"]) ? pass_hash($_POST["password"]) : "";

if (($user_type === "admin") && ($user_password === $password)) {
  $sql = $conn->prepare("DELETE FROM Lists WHERE list_id = :list_id");
	$sql->bindParam(':list_id', $list_id);
	$sql->execute();

	header("Location: " . rootredirect("//listdel", $conn, $style_code));
}
else {
	header("Location: " . rootredirect("//list&list_id=" . $list_id, $conn, $style_code));
}

include "fin.php";

?>

