<?php

header('Content-Type:text/xml');
echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";

include "init.php";

$page_name = isset($_GET["page"]) ? sanitize($_GET["page"]) : "";
$main_text = array();
$side_text = array();
$page_title = "";
$navbar_list = "";
$navbar_array = "";
$navbar_string = "";
$navigation_list = "";
$navigation_string = "";

$description = "";

$res_prefix = $root;

$sql = $conn->prepare("SELECT * FROM Pages where page_name = :page_name");
$sql->bindParam(':page_name', $page_name);
$sql->execute();
$result = $sql->fetchAll();

if ((sizeof($result) == 0) && ($page_name != "")) {
	$sql = $conn->prepare("SELECT * FROM Pages where page_name = 'lost'");
	$sql->execute();
	$result = $sql->fetchAll();
	$page_name = "lost";
}

if (sizeof($result) == 0) {
	$sql = $conn->prepare("SELECT * FROM Pages where page_name = 'home'");
	$sql->execute();
	$result = $sql->fetchAll();
	$page_name = "home";
}


if (sizeof($result) == 0) {
	$page_title = "";
	$navbar_list = "";
	$navigation_list = "";
}
else {
	$main_text[0] = $result[0]["main_text"];

	if ($page_name === "home") {
		$page_title = "News";
	}
	else {
		$page_title = $result[0]["page_title"];
	}
	$navbar_list = $result[0]["navbar"];
	$navigation_list = $result[0]["navigation"];
}

$navbar_array = explode(" ", $navbar_list);
$navbar_string = "";
for ($i = 0; $i < sizeof($navbar_array); $i++) {
	$sql = $conn->prepare("SELECT * FROM Pages where page_name = :navbar_item");
	$sql->bindParam(':navbar_item', $navbar_item);
	$navbar_item = $navbar_array[$i];
	$sql->execute();
	$result = $sql->fetchAll();

	if (sizeof($result) > 0) {
		$navbar_string .= "<span class=\"hlink\">";
		$navbar_string .= "<a href=\"//" . $navbar_array[$i] . "\">";
		$navbar_string .= $result[0]["page_short_name"];
		$navbar_string .= "</a></span> ";
	}
}

$navigation_string = preg_replace("/<a/", "<span class=\"vlink\"><a", $navigation_list);
$navigation_string = preg_replace("/a>/", "a></span>", $navigation_string);

if ($navigation_string == "") {
	$navigation_string = "This is a dead end.";
}

$side_text[0]  = "<div id=\"navigate\">\n";
$side_text[0] .= "<h2>Navigate</h2>\n";
$side_text[0] .= $navigation_string . "\n</div>";

if ($user_type === "none") {
	$login_side = sizeof($side_text);
	$side_text[$login_side]  = "<div id=\"actions\">\n";
	$side_text[$login_side] .= "<h2>Actions</h2>\n";
	$side_text[$login_side] .= "<span class=\"vlink\"><a href=\"//login\">Login</a></span>\n";
	$side_text[$login_side] .= "<span class=\"vlink\"><a href=\"//email\">Email</a></span>\n";
	$side_text[$login_side] .= "</div>";
}
else if ($user_type === "admin") {
	$login_side = sizeof($side_text);
	$side_text[$login_side]  = "<div id=\"actions\">\n";
	$side_text[$login_side] .= "<h2>Actions</h2>\n";
	$side_text[$login_side] .= "<span class=\"vlink\"><a href=\"./logout_do.php" . "\">Logout</a></span>\n";
	$side_text[$login_side] .= "<span class=\"vlink\"><a href=\"./editpage.php?page=" . $page_name . "\">Edit page</a></span>\n";
	$side_text[$login_side] .= "<span class=\"vlink\"><a href=\"./newpage.php?page=" . $page_name . "\">New page</a></span>\n";
	$side_text[$login_side] .= "<span class=\"vlink\"><a href=\"./delpage.php?page=" . $page_name . "\">Delete page</a></span>\n";
	$side_text[$login_side] .= "<span class=\"vlink\"><a href=\"./editacc.php\">Edit account</a></span>\n";
	$side_text[$login_side] .= "<span class=\"vlink\"><a href=\"//email\">Email</a></span>\n";
	$side_text[$login_side] .= "</div>";
}

include "list-rss.php";
include "references-rss.php";
include "download-rss.php";
include "template-rss.php";
include "fin.php";

?>
