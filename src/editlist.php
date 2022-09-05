<?php

include "init.php";

$list_id = isset($_GET["list_id"]) ? (int)sanitize($_GET["list_id"]) : -1;

$page_name = isset($_GET["page"]) ? sanitize($_GET["page"]) : "";
$main_text = array();
$side_text = array();
$main_text_class = array();
$side_text_class = array();
$navbar_string;
$navigation_string;
$page_title;
$list_comments = FALSE;

$list_date = "";
$list_title = "";
$list_body = "";
$list_keywords = "";

$page_title = "Edit list item";

$sql = $conn->prepare("SELECT * FROM Lists where list_id = :list_id");
$sql->bindParam(":list_id", $list_id);
$sql->execute();
$result = $sql->fetchAll();

if (sizeof($result) > 0) {
	$list_date = guess_date($result[0]["list_date"]);
	$list_date_string = full_datetime($list_date);
	$list_title = $result[0]["list_title"];
	$list_body = $result[0]["list_body"];
	$list_id = $result[0]["list_id"];
	$list_keywords = $result[0]["list_keywords"];
	$list_comments = $result[0]["list_comments"];
}

$navbar_string  = "<span class=\"hlink\"><a href=\"//home\">Home</a></span>";
$navbar_string .= "<span class=\"hlink\"><a href=\"//list\">List</a></span>";
$navbar_string .= "<span class=\"hlink\">";
$navbar_string .= "<a href=\"editlist.php?list_id=" . $list_id;
$navbar_string .= "\">Edit</a></span>";

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
  $side_text[$login_side] .= "<span class=\"vlink\"><a href=\"//list&amp;list_id=" . $list_id . "\">View list item</a></span>\n";
  $side_text[$login_side] .= "<span class=\"vlink\"><a href=\"./editacc.php\">Edit account</a></span>\n";
  $side_text[$login_side] .= "<span class=\"vlink\"><a href=\"//email\">Email</a></span>\n";
  $side_text[$login_side] .= "</div>";
}

$edit_main  = "<h2>Edit list item</h2>\n";

// Add in code to support CKEditor
$edit_main .= "<script src=\"ckeditor/ckeditor.js\"></script>\n";

$edit_main .= "<form action=\"editlist_do.php\" method=\"post\">\n";
$edit_main .= "<p />This page can be used to edit a list item in a list. Enter values below, then click 'Submit' to update the list item details.\n";
$edit_main .= "<input type=hidden name=list_id value=\"" . $list_id . "\">\n";

$edit_main .= "<p />Date: <input class=\"text\" type=text name=list_date value=\"" . $list_date_string . "\" size=\"40\" maxlength=\"64\">\n";
$edit_main .= "<p />Title: <input class=\"text\" type=text name=list_title value=\"" . clean_text_for_input($list_title) . "\" size=\"40\" maxlength=\"255\">\n";

$edit_main .= "<p />Body:\n";
$edit_main .= "<p /><textarea class=\"text\" name=\"list_body\" rows=10 cols=60 wrap=\"virtual\">" . clean_text_for_input($list_body) . "</textarea>\n";

// Apply the CKEditor
$edit_main .= "<script>CKEDITOR.replace( \"list_body\", {enterMode: CKEDITOR.ENTER_BR} );</script>";

$edit_main .= "<p />Keywords:\n";
$edit_main .= "<p /><textarea class=\"text\" name=\"list_keywords\" rows=3 cols=60 wrap=\"virtual\">" . clean_text_for_input($list_keywords) . "</textarea>\n";

$edit_main .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";

if ($list_comments == TRUE) {
  $edit_main .= "<p /><input type=\"checkbox\" name=\"comments\" value=\"true\" checked=\"1\">Allow comments?</input>\n";
}
else {
  $edit_main .= "<p /><input type=\"checkbox\" name=\"comments\" value=\"true\">Allow comments?</input>\n";
}

$edit_main .= "<p /><input class=\"submit\" type=submit name=\"toss\" value=\"Submit\">\n";
$edit_main .= "</form>\n";

$main_text_class[0] = "maintext";
if ($user_type === "admin") {
  $main_text[0] = $edit_main;
}
else {
  $main_text[0] = "<h2>Edit list item</h2>\n<p />You must be logged in to edit a list item.\n";
}

include "template.php";
include "fin.php";

?>

