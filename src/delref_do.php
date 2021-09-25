<?php

include "init.php";

$ref_id = isset($_POST["ref_id"]) ? $_POST["ref_id"] : -1;
$password = isset($_POST["password"]) ? pass_hash($_POST["password"]) : "";

if (($user_type === "admin") && ($user_password === $password)) {
	$sql = $conn->prepare("DELETE FROM Refs WHERE ref_id = :ref_id");
	$sql->bindParam(':ref_id', $ref_id);
	$sql->execute();

	header("Location: " . rootredirect("//refdel", $conn, $style_code));
}
else {
	header("Location: " . rootredirect("//references&ref_id=" . $ref_id, $conn, $style_code));
}

include "fin.php";

?>
