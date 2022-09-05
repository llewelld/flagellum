<?php

include "init.php";

$list_id = -1;
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

	$sql = $conn->prepare("INSERT INTO Lists (list_date, list_title, list_body, list_keywords, list_comments) VALUES (:list_date, :list_title, :list_body, :list_keywords, :list_comments)");
	$sql->bindParam(':list_date', $list_date);
	$sql->bindParam(':list_title', $value_string[1]);
	$sql->bindParam(':list_body', $value_string[2]);
	$sql->bindParam(':list_keywords', $value_string[3]);
	$sql->bindParam(':list_comments', $value_string[4]);
	$sql->execute();

	$sql = $conn->prepare("SELECT * FROM Lists where list_title = :list_title");
	$sql->bindParam(':list_title', $value_string[1]);
	$sql->execute();
	$result = $sql->fetchAll();

	if (sizeof($result) > 0) {
		$list_id = $result[sizeof($result) - 1]["list_id"];
	}
}

if ($list_id >= 0) {
	header("Location: " . rootredirect("//list&list_id=" . $list_id, $conn, $style_code));
}
else {
	header("Location: " . rootredirect("//", $conn, $style_code));
}

include "fin.php";

?>

