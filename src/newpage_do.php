<?php

include "init.php";

$page_id = isset($_POST["page_id"]) ? $_POST["page_id"] : "";
$page_name = isset($_POST["page_name"]) ? strtolower($_POST["page_name"]) : "";
$page_short_title = isset($_POST["page_short_title"]) ? $_POST["page_short_title"] : "";
$value_string = array();
$found_referring = FALSE;
$navbar_list = "";
$navigation_list = "";

if ($user_type === "admin") {
	$sql = $conn->prepare("SELECT * FROM Pages WHERE page_id = :page_id");
	$sql->bindParam(":page_id", $page_id);
	$sql->execute();
	$result = $sql->fetchAll();

	if (sizeof($result) > 0) {
		$navbar_list = $result[0]["navbar"];
		$navigation_list = $result[0]["navigation"];
		$found_referring = TRUE;
	}
	else {
		$found_referring = FALSE;
	}

	if ($found_referring == TRUE) {
		$value_string[0] = $page_name;
		$value_string[1] = $page_short_title;
		$value_string[2] = $page_short_title;
		$value_string[3] = "//" . $page_name;
		$value_string[4] = $navbar_list . " " . $page_name;
		$value_string[5] = $navigation_list;
		$value_string[6] = "<h2>" . $page_short_title . "</h2>\n\n<p />This page is currently empty. To add content to the page, use the \"Edit Page\" option from the Actions menu.\n";

		for ($i = 0; $i < sizeof($value_string); $i++) {
			$value_string[$i] = preg_replace("/\'/", "''", $value_string[$i]);
		}

		$sql  = $conn->prepare("INSERT INTO Pages (page_name, page_title, page_short_name, page_url_rel, navbar, navigation, main_text) VALUES (:page_name, :page_title, :page_short_name, :page_url_rel, :navbar, :navigation, :main_text)");
		$sql->bindParam(":page_name", $value_string[0]);
		$sql->bindParam(":page_title", $value_string[1]);
		$sql->bindParam(":page_short_name", $value_string[2]);
		$sql->bindParam(":page_url_rel", $value_string[3]);
		$sql->bindParam(":navbar", $value_string[4]);
		$sql->bindParam(":navigation", $value_string[5]);
		$sql->bindParam(":main_text", $value_string[6]);
		$sql->execute();

		$navigation_list .= "\n<a href=\"" . $value_string[3] . "\">" . $page_short_title . "</a>\n";
		$navigation_list = preg_replace ("/\'/", "''", $navigation_list);
		$navigation_list = preg_replace ("/\n\n/", "\n", $navigation_list);

		$sql = $conn->prepare("UPDATE Pages SET navigation = :navigation_list WHERE page_id = :page_id");

		$sql->bindParam(":navigation_list", $navigation_list);
		$sql->bindParam(":page_id", $page_id);
		$sql->execute();
	}
}
header("Location: " . rootredirect("//" . $page_name, $conn, $style_code));

include "fin.php";

?>

