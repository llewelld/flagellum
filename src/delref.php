<?php

include "init.php";

$ref_id = isset($_GET["ref_id"]) ? $_GET["ref_id"] : -1;

$page_name = isset($_GET["page"]) ? sanitize($_GET["page"]) : "";
$main_text = array();
$side_text = array();
$main_text_class = array();
$side_text_class = array();
$navbar_string;
$navigation_string;
$page_title;

$ref_authors = "";
$ref_title = "";
$ref_publication = "";
$ref_date = "";
$ref_date_string = "";
$ref_location = "";
$ref_page = "";
$ref_dl1 = "";
$ref_link1 = "";
$ref_dl2 = "";
$ref_link2 = "";
$ref_dl3 = "";
$ref_link3 = "";
$ref_extra = "";
$ref_keywords = "";

$page_title = "Delete reference";

$sql = $conn->prepare("SELECT * FROM Refs WHERE ref_id = :ref_id");
$sql->bindParam(':ref_id', $ref_id);
$sql->execute();
$result = $sql->fetchAll();

if (sizeof($result) > 0) {
	$ref_authors = $result[0]["ref_authors"];
	$ref_title = $result[0]["ref_title"];
	$ref_publication = $result[0]["ref_publication"];
  $ref_date = guess_date($result[0]["ref_date_string"]);
  $ref_date_string = short_date($ref_date);
	$ref_location = $result[0]["ref_location"];
	$ref_page = $result[0]["ref_page"];
	$ref_dl1 = $result[0]["ref_dl1"];
	$ref_link1 = $result[0]["ref_link1"];
	$ref_dl2 = $result[0]["ref_dl2"];
	$ref_link2 = $result[0]["ref_link2"];
	$ref_dl3 = $result[0]["ref_dl3"];
	$ref_link3 = $result[0]["ref_link3"];
	$ref_extra = $result[0]["ref_extra"];
	$ref_id = $result[0]["ref_id"];
	$ref_keywords = $result[0]["ref_keywords"];
}

$navbar_string  = "<span class=\"hlink\"><a href=\"//home\">Home</a></span>";
$navbar_string .= "<span class=\"hlink\"><a href=\"//references\">References</a></span>";
$navbar_string .= "<span class=\"hlink\">";
$navbar_string .= "<a href=\"delref.php?ref_id=" . $ref_id;
$navbar_string .= "\">Delete</a></span>";

$navigation_string = "<span class=\"deadend\">This is a dead end.</span>";

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
	$side_text[$login_side] .= "<span class=\"vlink\"><a href=\"//references&amp;ref_id=" . $ref_id . "\">View reference</a></span>\n";
	$side_text[$login_side] .= "<span class=\"vlink\"><a href=\"./editacc.php\">Edit account</a></span>\n";
	$side_text[$login_side] .= "<span class=\"vlink\"><a href=\"//email\">Email</a></span>\n";
	$side_text[$login_side] .= "</div>";
}

$edit_main  = "<h2>Delete reference</h2>\n";
$edit_main .= "<form action=\"delref_do.php\" method=\"post\">\n";
$edit_main .= "<p><b>This will delete the reference for '" . $ref_title . "'.</b>\n";
$edit_main .= "<p>If you are sure you want to do this, you must enter your password below.\n";
$edit_main .= "<input type=hidden name=ref_id value=\"" . $ref_id . "\">\n";
$edit_main .= "<p>Password: <input class=\"text\" type=password name=password value=\"\">\n";
$edit_main .= "<p><input class=\"submit\" type=submit name=\"toss\" value=\"Submit\">\n";
$edit_main .= "</form>\n";

$main_text_class[0] = "maintext";
if ($user_type === "admin") {
	$main_text[0] = $edit_main;
}
else {
	$main_text[0] = "<h2>Delete reference</h2>\n<p>You must be logged in to delete references.\n";
}

include "template.php";
include "fin.php";

?>

