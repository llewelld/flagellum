<?php

include "init.php";

$page_name = isset($_GET["page"]) ? $_GET["page"] : "";
$main_text = array();
$side_text = array();
$main_text_class = array();
$side_text_class = array();
$page_id = "";
$page_title = "";
$page_short_title = "";
$page_url_rel = "";
$navbar_list = "";
$navbar_array = "";
$navbar_string = "";
$navigation_list = "";
$navigation_string = "";

$sql = $conn->prepare("SELECT * FROM Pages WHERE page_name = :page_name");
$sql->bindParam(":page_name", $page_name);
$sql->execute();
$result = $sql->fetchAll();

if (sizeof($result) <= 0) {
	$sql = $conn->prepare("SELECT * FROM Pages where page_name = 'home'");
	$sql->execute();
	$result = $sql->fetchAll();
	$page_name = "home";
}

if (sizeof($result) <= 0) {
	$page_title = "";
	$navbar_list = "";
	$navigation_list = "";
	$page_short_title = "";
	$page_url_rel = "";
}
else {
	$page_id = $result[0]["page_id"];
	$main_text[0] = $result[0]["main_text"];
	$main_text_class[0] = "maintext";
	$page_title = $result[0]["page_title"];
	$navbar_list = $result[0]["navbar"];
	$navigation_list = $result[0]["navigation"];
	$page_short_title = $result[0]["page_short_name"];
	$page_url_rel = $result[0]["page_url_rel"];
}

$navbar_array = explode(" ", $navbar_list);
$navbar_string = "";
for ($i = 0; $i < sizeof($navbar_array); $i++) {
	$sql = $conn->prepare("SELECT * FROM Pages WHERE page_name = :navbar_item");
	$sql->bindParam(":navbar_item", $navbar_array[$i]);
	$sql->execute();
	$result = $sql->fetchAll();

	if (sizeof($result) > 0) {
		$navbar_string .= "<span class=\"hlink\">";
		$navbar_string .= "<a href=\"//" . $navbar_array[$i] . "\">";
		$navbar_string .= $result[0]["page_short_name"];
		$navbar_string .= "</a></span> ";
	}
}
$navbar_string .= "<span class=\"hlink\">";
$navbar_string .= "<a href=\"newpage.php?page=" . $page_name;
$navbar_string .= "\">New</a></span>";

$navigation_string = preg_replace ("/<a/", "<span class=\"vlink\"><a", $navigation_list);
$navigation_string = preg_replace ("/a>/", "a></span>", $navigation_string);

if ($navigation_string == "") {
  $navigation_string = "<span class=\"deadend\">This is a dead end.</span>";
}

$side_text_class[0] = "navigate";
$side_text[0]  = "<div id=\"navigate\">\n";
$side_text[0] .= "<h2>Navigate</h2>\n";
$side_text[0] .= $navigation_string . "\n</div>";

if ($user_type === "none") {
	$login_side = sizeof($side_text);
	$side_text_class[$login_side] = "actions";
	$side_text[$login_side]  = "<div id=\"actions\">\n";
	$side_text[$login_side] .= "<h2>Actions</h2>\n";
	$side_text[$login_side] .= "<span class=\"vlink\"><a href=\"//login\">Login</a></span>\n";
	$side_text[$login_side] .= "<span class=\"vlink\"><a href=\"//email\">Email</a></span>\n";
	$side_text[$login_side] .= "</div>";
}
else if ($user_type === "admin") {
  $login_side = sizeof($side_text);
	$side_text_class[$login_side] = "actions";
  $side_text[$login_side]  = "<div id=\"actions\">\n";
  $side_text[$login_side] .= "<h2>Actions</h2>\n";
  $side_text[$login_side] .= "<span class=\"vlink\"><a href=\"./logout_do.php\">Logout</a></span>\n";
  $side_text[$login_side] .= "<span class=\"vlink\"><a href=\"//" . $page_name . "\">View page</a></span>\n";
  $side_text[$login_side] .= "<span class=\"vlink\"><a href=\"./editacc.php\">Edit account</a></span>\n";
  $side_text[$login_side] .= "<span class=\"vlink\"><a href=\"//email\">Email</a></span>\n";
  $side_text[$login_side] .= "</div>";
}

$edit_main  = "<h2>Create new page</h2>\n";
$edit_main .= "<form action=\"newpage_do.php\" method=\"post\">\n";
$edit_main .= "<p>This page can be used to add a new page to the site. The page will be linked from the page <a href=\"" . $page_name . "\">\"" . $page_title . "\"</a> that you\"ve just come from. To change the material on the new page, use the Edit Page option once the new page has been created.\n";
$edit_main .= "<input type=hidden name=page_id value=\"" . $page_id . "\">\n";
$edit_main .= "<p>ID: <input class=\"text\" type=text name=page_name value=\"\" size=\"40\" maxlength=\"64\">\n";
$edit_main .= "<p>Short title: <input class=\"text\" type=text name=page_short_title value=\"\" size=\"40\" maxlength=\"255\">\n";
$edit_main .= "<p><input class=\"submit\" type=submit name=\"toss\" value=\"Submit\">\n";
$edit_main .= "</form>\n";

$main_text_class[0] = "maintext";
if ($user_type === "admin") {
  $main_text[0] = $edit_main;
}
else {
  $main_text[0] = "<h2>Create new page</h2>\n<p>You must be logged in to create new pages.\n";
}

include "template.php";

include "fin.php";

?>

