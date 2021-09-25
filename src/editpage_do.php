<?php

include "init.php";

$page_id = isset($_POST["page_id"]) ? $_POST["page_id"] : -1;
$page_name = isset($_POST["page_name"]) ? $_POST["page_name"] : "";
$value_string = array();

$value_string[0] = isset($_POST["page_name"]) ? $_POST["page_name"] : "";
$value_string[1] = isset($_POST["page_title"]) ? $_POST["page_title"] : "";
$value_string[2] = isset($_POST["page_short_title"]) ? $_POST["page_short_title"] : "";
$value_string[3] = isset($_POST["page_url_rel"]) ? $_POST["page_url_rel"] : "";
$value_string[4] = isset($_POST["navbar"]) ? $_POST["navbar"] : "";
$value_string[5] = isset($_POST["navigation"]) ? $_POST["navigation"] : "";
$value_string[6] = isset($_POST["main"]) ? $_POST["main"] : "";
$value_string[7] = isset($_POST["botstop"]) ? (int)($_POST["botstop"] == "true") : 0;
$value_string[8] = isset($_POST["access"]) ? (int)($_POST["access"] == "true") : 0;
$value_string[9] = isset($_POST["comments"]) ? (int)($_POST["comments"] == "true") : 0;

if ($value_string[7] != 1) {
	$value_string[7] = 0;
}
if ($value_string[8] != 1) {
	$value_string[8] = 0;
}
if ($value_string[9] != 1) {
	$value_string[9] = 0;
}

$value_string[7] = 0;

if ($user_type == "admin") {
	$sql = $conn->prepare("update Pages set page_name = :value where page_id = :page_id");
	$sql->bindParam(':value', $value_string[0]);
	$sql->bindParam(':page_id', $page_id);
	$sql->execute();

	$sql = $conn->prepare("update Pages set page_title = :value where page_id = :page_id");
	$sql->bindParam(':value', $value_string[1]);
	$sql->bindParam(':page_id', $page_id);
	$sql->execute();

	$sql = $conn->prepare("update Pages set page_short_name = :value where page_id = :page_id");
	$sql->bindParam(':value', $value_string[2]);
	$sql->bindParam(':page_id', $page_id);
	$sql->execute();

	$sql = $conn->prepare("update Pages set page_url_rel = :value where page_id = :page_id");
	$sql->bindParam(':value', $value_string[3]);
	$sql->bindParam(':page_id', $page_id);
	$sql->execute();

	$sql = $conn->prepare("update Pages set navbar = :value where page_id = :page_id");
	$sql->bindParam(':value', $value_string[4]);
	$sql->bindParam(':page_id', $page_id);
	$sql->execute();

	$sql = $conn->prepare("update Pages set navigation = :value where page_id = :page_id");
	$sql->bindParam(':value', $value_string[5]);
	$sql->bindParam(':page_id', $page_id);
	$sql->execute();

	$sql = $conn->prepare("update Pages set main_text = :value where page_id = :page_id");
	$sql->bindParam(':value', $value_string[6]);
	$sql->bindParam(':page_id', $page_id);
	$sql->execute();

	$sql = $conn->prepare("update Pages set page_botstop = :value where page_id = :page_id");
	$sql->bindParam(':value', $value_string[7]);
	$sql->bindParam(':page_id', $page_id);
	$sql->execute();

	$sql = $conn->prepare("update Pages set page_access = :value where page_id = :page_id");
	$sql->bindParam(':value', $value_string[8]);
	$sql->bindParam(':page_id', $page_id);
	$sql->execute();

	$sql = $conn->prepare("update Pages set page_comments = :value where page_id = :page_id");
	$sql->bindParam(':value', $value_string[9]);
	$sql->bindParam(':page_id', $page_id);
	$sql->execute();
}
header("Location: " . rootredirect("//" . $page_name, $conn, $style_code));

include "fin.php";

?>

