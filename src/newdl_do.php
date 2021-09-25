<?php

include "init.php";

$sw_id = -1;
$sw_keywords = isset($_POST["dl_keywords"]) ? $_POST["dl_keywords"] : "";
$value_string = array();

$value_string[0] = isset($_POST["dl_title"]) ? $_POST["dl_title"] : "";
$value_string[1] = isset($_POST["dl_version"]) ? $_POST["dl_version"] : "";
$value_string[2] = isset($_POST["dl_date"]) ? $_POST["dl_date"] : "";
$value_string[3] = isset($_POST["dl_icon"]) ? $_POST["dl_icon"] : "";
$value_string[4] = isset($_POST["dl_bin"]) ? $_POST["dl_bin"] : "";
$value_string[5] = isset($_POST["dl_src"]) ? $_POST["dl_src"] : "";
$value_string[6] = isset($_POST["dl_screenshot"]) ? $_POST["dl_screenshot"] : "";
$value_string[7] = isset($_POST["dl_description"]) ? $_POST["dl_description"] : "";
$value_string[8] = isset($_POST["dl_page"]) ? $_POST["dl_page"] : "";
$value_string[9] = isset($_POST["dl_os"]) ? $_POST["dl_os"] : "";
$value_string[10] = $sw_keywords;

if ($user_type === "admin") {
	for ($i = 0; $i < sizeof($value_string); $i++) {
		$value_string[$i] = preg_replace("/\'/", "''", $value_string[$i]);
	}

	$sw_date = epoch_to_db_date(guess_date($value_string[2]));

	$sql = $conn->prepare("INSERT INTO Software (software_title, software_version, software_date, software_icon, software_bin, software_src, software_screenshot, software_description, software_page, software_os, software_keywords) VALUES (:software_title, :software_version, :software_date, :software_icon, :software_bin, :software_src, :software_screenshot, :software_description, :software_page, :software_os, :software_keywords)");

	$sql->bindParam(':software_title', $value_string[0]);
	$sql->bindParam(':software_version', $value_string[1]);
	$sql->bindParam(':software_date', $sw_date);
	$sql->bindParam(':software_icon', $value_string[3]);
	$sql->bindParam(':software_bin', $value_string[4]);
	$sql->bindParam(':software_src', $value_string[5]);
	$sql->bindParam(':software_screenshot', $value_string[6]);
	$sql->bindParam(':software_description', $value_string[7]);
	$sql->bindParam(':software_page', $value_string[8]);
	$sql->bindParam(':software_os', $value_string[9]);
	$sql->bindParam(':software_keywords', $value_string[10]);
	$sql->execute();

	$sql = $conn->prepare("SELECT * FROM Software WHERE software_title = :software_title");
	$sql->bindParam(':software_title', $value_string[0]);
	$sql->execute();
	$result = $sql->fetchAll();

	if (sizeof($result) > 0) {
		$sw_id = $result[sizeof($result) - 1]["software_id"];
	}
}

if ($sw_id >= 0) {
	header("Location: " . rootredirect("//download&dl_id=" . $sw_id, $conn, $style_code));
}
else {
	header("Location: " . rootredirect("//", $conn, $style_code));
}

include "fin.php";

?>

