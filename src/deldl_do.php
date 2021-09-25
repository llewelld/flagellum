<?php

include "init.php";

$sw_id = isset($_POST["dl_id"]) ? $_POST["dl_id"] : -1;
$password = isset($_POST["password"]) ? pass_hash($_POST["password"]) : "";

if (($user_type === "admin") && ($user_password === $password)) {
  $sql = $conn->prepare("DELETE FROM Software WHERE software_id = :sw_id");
	$sql->bindParam(':sw_id', $sw_id);
	$sql->execute();

	header("Location: " . rootredirect("//dldel", $conn, $style_code));
}
else {
	header("Location: " . rootredirect("//download&dl_id=" . $sw_id, $conn, $style_code));
}

include "fin.php";

?>
