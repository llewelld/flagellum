<?php

include "init.php";

$list_id = isset($_POST["list_id"]) ? $_POST["list_id"] : -1;
$list_keywords = isset($_POST["list_keywords"]) ? $_POST["list_keywords"] : "";
$value_string = array();

$value_string[0] = isset($_POST["list_date"]) ? $_POST["list_date"] : "";
$value_string[1] = isset($_POST["list_title"]) ? $_POST["list_title"] : "";
$value_string[2] = isset($_POST["list_body"]) ? $_POST["list_body"] : "";
$value_string[3] = $list_keywords;
$value_string[4] = isset($_POST["comments"]) ? (int)($_POST["comments"] == "true") : 0;

if ($value_string[4] != 1) {
	$value_string[4] = 0;
}

if ($user_type === "admin") {
	$list_date = epoch_to_db_datetime(guess_date($value_string[0]));

	$sql = $conn->prepare("UPDATE Lists SET list_date = :list_date WHERE list_id = :list_id");
	$sql->bindParam(':list_date', $list_date);
	$sql->bindParam(':list_id', $list_id);
	$sql->execute();

	$sql = $conn->prepare("UPDATE Lists SET list_title = :list_title where list_id = :list_id");
	$sql->bindParam(':list_title', $value_string[1]);
	$sql->bindParam(':list_id', $list_id);
	$sql->execute();

	$sql = $conn->prepare("UPDATE Lists SET list_body = :list_body WHERE list_id = :list_id");
	$sql->bindParam(':list_body', $value_string[2]);
	$sql->bindParam(':list_id', $list_id);
	$sql->execute();

	$sql = $conn->prepare("UPDATE Lists SET list_keywords = :list_keywords WHERE list_id = :list_id");
	$sql->bindParam(':list_keywords', $value_string[3]);
	$sql->bindParam(':list_id', $list_id);
	$sql->execute();

	$sql = $conn->prepare("UPDATE Lists SET list_comments = :list_comments WHERE list_id = :list_id");
	$sql->bindParam(':list_comments', $value_string[4]);
	$sql->bindParam(':list_id', $list_id);
	$sql->execute();
}

header("Location: " . rootredirect("//list&list_id=" . $list_id, $conn, $style_code));

include "fin.php";

?>

