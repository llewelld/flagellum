<?php

include "init.php";

$ref_keywords = isset($_GET["ref"]) ? sanitize($_GET["ref"]) : "";

$main_text = array();
$side_text = array();
$main_text_class = array();
$side_text_class = array();
$navbar_string = "";
$navigation_string = "";
$ref_date = time();
$page_title = "";

$page_title = "Create new reference";

$navbar_string  = "<span class=\"hlink\"><a href=\"//home\">Home</a>";
$navbar_string .= "<span class=\"hlink\"><a href=\"//references\">References</a>";
$navbar_string .= "<span class=\"hlink\">";
$navbar_string .= "<a href=\"newref.php?ref=" . $ref_keywords;
$navbar_string .= "\">New</a></span>";

$navigation_string = "This is a dead end.";

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

$edit_main  = "<h2>New reference</h2>\n";

// Add in code to support CKEditor
$edit_main .= "<script src=\"ckeditor/ckeditor.js\"></script>\n";

$edit_main .= "<form action=\"newref_do.php\" method=\"post\">\n";
$edit_main .= "<p>This page can be used to create a new reference in the references list. Enter values below, then click \"Submit\" to enter the reference details.\n";
$edit_main .= "<p>Authors: <input class=\"text\" type=text name=ref_authors value=\"\" size=\"40\" maxlength=\"255\">\n";
$edit_main .= "<p>Title: <input class=\"text\" type=text name=ref_title value=\"\" size=\"40\" maxlength=\"255\">\n";
$edit_main .= "<p>Publication: <input class=\"text\" type=text name=ref_publication value=\"\" size=\"40\" maxlength=\"255\">\n";
$edit_main .= "<p>Date: <input class=\"text\" type=text name=ref_date value=\"" . full_datetime($ref_date) . "\" size=\"40\" maxlength=\"64\">\n";
$edit_main .= "<p>Location: <input class=\"text\" type=text name=ref_location value=\"\" size=\"40\" maxlength=\"255\">\n";
$edit_main .= "<p>Page: <input class=\"text\" type=text name=ref_page value=\"\" size=\"40\" maxlength=\"255\">\n";
$edit_main .= "<p>Download 1: <input class=\"text\" type=text name=ref_dl1 value=\"\" size=\"40\" maxlength=\"255\">\n";
$edit_main .= "<p>Link 1: <input class=\"text\" type=text name=ref_link1 value=\"\" size=\"40\" maxlength=\"255\">\n";
$edit_main .= "<p>Download 2: <input class=\"text\" type=text name=ref_dl2 value=\"\" size=\"40\" maxlength=\"255\">\n";
$edit_main .= "<p>Link 2: <input class=\"text\" type=text name=ref_link2 value=\"\" size=\"40\" maxlength=\"255\">\n";
$edit_main .= "<p>Download 3: <input class=\"text\" type=text name=ref_dl3 value=\"\" size=\"40\" maxlength=\"255\">\n";
$edit_main .= "<p>Link 3: <input class=\"text\" type=text name=ref_link3 value=\"\" size=\"40\" maxlength=\"255\">\n";
$edit_main .= "<p>Extra:\n";
$edit_main .= "<p><textarea class=\"text\" name=\"ref_extra\" rows=8 cols=60 wrap=\"virtual\"></textarea>\n";

// Apply the CKEditor
$edit_main .= "<script>CKEDITOR.replace( \"ref_extra\", {enterMode: CKEDITOR.ENTER_BR} );</script>";

$edit_main .= "<p>Keywords:\n";
$edit_main .= "<p><textarea class=\"text\" name=\"ref_keywords\" rows=3 cols=60 wrap=\"virtual\">" . clean_text_for_input($ref_keywords) . "</textarea>\n";
$edit_main .= "<p><input class=\"submit\" type=submit name=\"toss\" value=\"Submit\">\n";
$edit_main .= "</form>\n";

$main_text_class[0] = "maintext";
if ($user_type === "admin") {
	$main_text[0] = $edit_main;
}
else {
	$main_text[0] = "<h2>New reference</h2>\n<p>You must be logged in to create new references.\n";
}

include "template.php";

include "fin.php";

?>

