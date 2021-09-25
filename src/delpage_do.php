<?php

include "init.php";

$page_id = isset($_POST["page_id"]) ? $_POST["page_id"] : -1;
$page_name = isset($_POST["page_name"]) ? sanitize($_POST["page_name"]) : "";
$password = isset($_POST["password"]) ? pass_hash($_POST["password"]) : "";

if (($user_type === "admin") && ($user_password === $password)) {
	$sql = $conn->prepare("delete from Pages where page_id = :page_id");
	$sql->bindParam(':page_id', $page_id);
	$sql->execute();

	header("Location: " . rootredirect("//pagedel", $conn, $style_code));
}
else {
	header("Location: " . rootredirect("//" . $page_name, $conn, $style_code));
}

include "fin.php";

?>
