<?php

include "init.php";

$ref_id = "";
$ref_keywords = isset($_POST["ref_keywords"]) ? $_POST["ref_keywords"] : "";
$value_string = array();

$value_string[0] = isset($_POST["ref_authors"]) ? $_POST["ref_authors"] : "";
$value_string[1] = isset($_POST["ref_title"]) ? $_POST["ref_title"] : "";
$value_string[2] = isset($_POST["ref_publication"]) ? $_POST["ref_publication"] : "";
$value_string[3] = isset($_POST["ref_date"]) ? $_POST["ref_date"] : "";
$value_string[4] = isset($_POST["ref_location"]) ? $_POST["ref_location"] : "";
$value_string[5] = isset($_POST["ref_page"]) ? $_POST["ref_page"] : "";
$value_string[6] = isset($_POST["ref_dl1"]) ? $_POST["ref_dl1"] : "";
$value_string[7] = isset($_POST["ref_link1"]) ? $_POST["ref_link1"] : "";
$value_string[8] = isset($_POST["ref_dl2"]) ? $_POST["ref_dl2"] : "";
$value_string[9] = isset($_POST["ref_link2"]) ? $_POST["ref_link2"] : "";
$value_string[10] = isset($_POST["ref_dl3"]) ? $_POST["ref_dl3"] : "";
$value_string[11] = isset($_POST["ref_link3"]) ? $_POST["ref_link3"] : "";
$value_string[12] = isset($_POST["ref_extra"]) ? $_POST["ref_extra"] : "";
$value_string[13] = $ref_keywords;

if ($user_type === "admin") {
	for ($i = 0; $i < sizeof($value_string); $i++) {
		$value_string[$i] = preg_replace("/\'/", "''", $value_string[$i]);
	}

	$ref_date = epoch_to_db_datetime(guess_date($value_string[3]));

	$sql  = $conn->prepare("INSERT INTO Refs (ref_authors, ref_title, ref_publication, ref_date, ref_date_string, ref_location, ref_page, ref_dl1, ref_link1, ref_dl2, ref_link2, ref_dl3, ref_link3, ref_extra, ref_keywords) VALUES (:ref_authors, :ref_title, :ref_publication, :ref_date, :ref_date_string, :ref_location, :ref_page, :ref_dl1, :ref_link1, :ref_dl2, :ref_link2, :ref_dl3, :ref_link3, :ref_extra, :ref_keywords)");
	$sql->bindParam(':ref_authors', $value_string[0]);
	$sql->bindParam(':ref_title', $value_string[1]);
	$sql->bindParam(':ref_publication', $value_string[2]);
	$sql->bindParam(':ref_date', $ref_date);
	$sql->bindParam(':ref_date_string', $value_string[3]);
	$sql->bindParam(':ref_location', $value_string[4]);
	$sql->bindParam(':ref_page', $value_string[5]);
	$sql->bindParam(':ref_dl1', $value_string[6]);
	$sql->bindParam(':ref_link1', $value_string[7]);
	$sql->bindParam(':ref_dl2', $value_string[8]);
	$sql->bindParam(':ref_link2', $value_string[9]);
	$sql->bindParam(':ref_dl3', $value_string[10]);
	$sql->bindParam(':ref_link3', $value_string[11]);
	$sql->bindParam(':ref_extra', $value_string[12]);
	$sql->bindParam(':ref_keywords', $value_string[13]);
	$sql->execute();

	$sql = $conn->prepare("SELECT * FROM Refs WHERE ref_title = :ref_title");
	$sql->bindParam(':ref_title', $value_string[1]);
	$sql->execute();
	$result = $sql->fetchAll();

	if (sizeof($result) > 0) {
		$ref_id = $result[sizeof($result) - 1]["ref_id"];
	}
}

if ($ref_id >= 0) {
	header("Location: " . rootredirect("//references&ref_id=" . $ref_id, $conn, $style_code));
}
else {
	header("Location: " . rootredirect("//", $conn, $style_code));
}

include "fin.php";

?>

