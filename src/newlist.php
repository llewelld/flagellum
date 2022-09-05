<?php

include "init.php";

$list_keywords = isset($_GET["list"]) ? sanitize($_GET["list"]) : "";

$main_text = array();
$side_text = array();
$main_text_class = array();
$side_text_class = array();
$navbar_string = "";
$navigation_string;
$list_date = time();
$page_title = "";

$page_title = "Create list item";

$navbar_string  = "<span class=\"hlink\"><a href=\"//home\">Home</a>";
$navbar_string .= "<span class=\"hlink\"><a href=\"//list\">List</a>";
$navbar_string .= "<span class=\"hlink\">";
$navbar_string .= "<a href=\"newref.php?list=" . $list_keywords;
$navbar_string .= "\">New</a></span>";

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
	$side_text[$login_side] .= "<span class=\"vlink\"><a href=\"./editacc.php\">Edit account</a></span>\n";
	$side_text[$login_side] .= "<span class=\"vlink\"><a href=\"//email\">Email</a></span>\n";
	$side_text[$login_side] .= "</div>";
}

$edit_main  = "<h2>New list item</h2>\n";

// Add in code to support CKEditor
$edit_main .= "<script src=\"ckeditor/ckeditor.js\"></script>\n";

$edit_main .= "<form action=\"newlist_do.php\" method=\"post\">\n";
$edit_main .= "<p>This page can be used to create a new list item in a list. Enter values below, then click \"Submit\" to enter the list item details.\n";
$edit_main .= "<p>Date: <input class=\"text\" type=\"text\" name=\"list_date\" value=\"" . full_datetime($list_date) . "\" size=\"40\" maxlength=\"64\">\n";
$edit_main .= "<p>Title: <input class=\"text\" type=text name=list_title value=\"\" size=\"40\" maxlength=\"255\">\n";
$edit_main .= "<p>Body:\n";
$edit_main .= "<p><textarea class=\"text\" name=\"list_body\" rows=10 cols=60 wrap=\"virtual\"></textarea>\n";

// Apply the CKEditor
$edit_main .= "<script>CKEDITOR.replace( \"list_body\", {enterMode: CKEDITOR.ENTER_BR} );</script>";

$edit_main .= "<p>Keywords:\n";
$edit_main .= "<p><textarea class=\"text\" name=\"list_keywords\" rows=3 cols=60 wrap=\"virtual\">" . clean_text_for_input($list_keywords) . "</textarea>\n";

$edit_main .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
$edit_main .= "<p /><input type=\"checkbox\" name=\"comments\" value=\"true\">Allow comments?</input>\n";

$edit_main .= "<p><input class=\"submit\" type=submit name=\"toss\" value=\"Submit\">\n";
$edit_main .= "</form>\n";

$main_text_class[0] = "maintext";
if ($user_type === "admin") {
	$main_text[0] = $edit_main;
}
else {
	$main_text[0] = "<h2>New list item</h2>\n<p>You must be logged in to create new list items.\n";
}

include "template.php";

include "fin.php";

?>

