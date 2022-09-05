<?php

include "init.php";

$sw_keywords = isset($_GET["dnload"]) ? sanitize($_GET["dnload"]) : "";

$main_text = array();
$side_text = array();
$main_text_class = array();
$side_text_class = array();
$navbar_string = "";
$navigation_string = "";
$sw_date = time();
$page_title = "";

$page_title = "Create new download";

$navbar_string  = "<span class=\"hlink\"><a href=\"//home\">Home</a>";
$navbar_string .= "<span class=\"hlink\"><a href=\"//download\">Download</a>";
$navbar_string .= "<span class=\"hlink\">";
$navbar_string .= "<a href=\"newdl.php?download=" . $sw_keywords;
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

$edit_main  = "<h2>New download</h2>\n";

// Add in code to support CKEditor
$edit_main .= "<script src=\"ckeditor/ckeditor.js\"></script>\n";

$edit_main .= "<form action=\"newdl_do.php\" method=\"post\">\n";
$edit_main .= "<p>This page can be used to create a new software entry in the software list. Enter values below, then click \"Submit\" to enter the software details.\n";
$edit_main .= "<p>Title: <input class=\"text\" type=text name=dl_title value=\"\" size=\"40\" maxlength=\"255\">\n";
$edit_main .= "<p>Version: <input class=\"text\" type=text name=dl_version value=\"0.00\" size=\"40\" maxlength=\"64\">\n";
$edit_main .= "<p>Date: <input class=\"text\" type=text name=dl_date value=\"" . short_date($sw_date) . "\" size=\"40\" maxlength=\"64\">\n";
$edit_main .= "<p>Icon: <input class=\"text\" type=text name=dl_icon value=\"\" size=\"40\" maxlength=\"255\">\n";
$edit_main .= "<p>Binary: <input class=\"text\" type=text name=dl_bin value=\"\" size=\"40\" maxlength=\"255\">\n";
$edit_main .= "<p>Source: <input class=\"text\" type=text name=dl_src value=\"\" size=\"40\" maxlength=\"255\">\n";
$edit_main .= "<p>Screenshot: <input class=\"text\" type=text name=dl_screenshot value=\"\" size=\"40\" maxlength=\"255\">\n";
$edit_main .= "<p>OS: <input class=\"text\" type=text name=dl_os value=\"\" size=\"40\" maxlength=\"255\">\n";
$edit_main .= "<p>Page: <input class=\"text\" type=text name=dl_page value=\"\" size=\"40\" maxlength=\"255\">\n";
$edit_main .= "<p>Description:\n";
$edit_main .= "<p><textarea class=\"text\" name=\"dl_description\" rows=10 cols=60 wrap=\"virtual\"></textarea>\n";

// Apply the CKEditor
$edit_main .= "<script>CKEDITOR.replace( \"dl_description\", {enterMode: CKEDITOR.ENTER_BR} );</script>";

$edit_main .= "<p>Keywords:\n";
$edit_main .= "<p><textarea class=\"text\" name=\"dl_keywords\" rows=3 cols=60 wrap=\"virtual\">" . clean_text_for_input($sw_keywords) . "</textarea>\n";
$edit_main .= "<p><input class=\"submit\" type=submit name=\"toss\" value=\"Submit\">\n";
$edit_main .= "</form>\n";

$main_text_class[0] = "maintext";
if ($user_type == "admin") {
	$main_text[0] = $edit_main;
}
else {
	$main_text[0] = "<h2>New download</h2>\n<p>You must be logged in to create new download details.\n";
}

include "template.php";

include "fin.php";

?>

