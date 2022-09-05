<?php

include "init.php";

$sw_id = isset($_GET["dl_id"]) ? (int)sanitize($_GET["dl_id"]) : -1;

$page_name = isset($_GET["page"]) ? sanitize($_GET["page"]) : "";
$main_text = array();
$side_text = array();
$main_text_class = array();
$side_text_class = array();
$navbar_string;
$navigation_string;
$page_title;

$page_title = "Edit download";

$sql = $conn->prepare("SELECT * FROM Software where software_id = :sw_id");
$sql->bindParam(":sw_id", $sw_id);
$sql->execute();
$result = $sql->fetchAll();

if (sizeof($result) > 0) {
	$sw_title = $result[0]["software_title"];
	$sw_icon = $result[0]["software_icon"];
	$sw_description = $result[0]["software_description"];
	$sw_bin = $result[0]["software_bin"];
	$sw_src = $result[0]["software_src"];
	$sw_screenshot = $result[0]["software_screenshot"];
	$sw_page = $result[0]["software_page"];
	$sw_version = $result[0]["software_version"];
	$sw_date = guess_date($result[0]["software_date"]);
	$sw_date_string = short_date($sw_date);
	$sw_os = $result[0]["software_os"];
	$sw_id = $result[0]["software_id"];
	$sw_keywords = $result[0]["software_keywords"];
}

$navbar_string  = "<span class=\"hlink\"><a href=\"//home\">Home</a></span>";
$navbar_string .= "<span class=\"hlink\"><a href=\"//download\">Download</a></span>";
$navbar_string .= "<span class=\"hlink\">";
$navbar_string .= "<a href=\"editdl.php?sw_id=" . $sw_id;
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
	$side_text[$login_side] .= "<span class=\"vlink\"><a href=\"//download&amp;dl_id=" . $sw_id . "\">View software</a></span>\n";
	$side_text[$login_side] .= "<span class=\"vlink\"><a href=\"./editacc.php\">Edit account</a></span>\n";
	$side_text[$login_side] .= "<span class=\"vlink\"><a href=\"//email\">Email</a></span>\n";
	$side_text[$login_side] .= "</div>";
}

$edit_main  = "<h2>Edit download</h2>\n";

// Add in code to support CKEditor
$edit_main .= "<script src=\"ckeditor/ckeditor.js\"></script>\n";

$edit_main .= "<form action=\"editdl_do.php\" method=\"post\">\n";
$edit_main .= "<p>This page can be used to edit a software entry from the software list. Enter values below, then click \"Submit\" to update the software details.\n";
$edit_main .= "<input type=hidden name=dl_id value=\"" . $sw_id . "\">\n";
$edit_main .= "<p>Title: <input class=\"text\" type=text name=dl_title value=\"" . clean_text_for_input($sw_title) . "\" size=\"40\" maxlength=\"255\">\n";
$edit_main .= "<p>Version: <input class=\"text\" type=text name=dl_version value=\"" . clean_text_for_input($sw_version) . "\" size=\"40\" maxlength=\"64\">\n";
$edit_main .= "<p>Date: <input class=\"text\" type=text name=dl_date value=\"" . short_date($sw_date) . "\" size=\"40\" maxlength=\"64\">\n";
$edit_main .= "<p>Icon: <input class=\"text\" type=text name=dl_icon value=\"" . clean_text_for_input($sw_icon) . "\" size=\"40\" maxlength=\"255\">\n";
$edit_main .= "<p>Binary: <input class=\"text\" type=text name=dl_bin value=\"" . clean_text_for_input($sw_bin) . "\" size=\"40\" maxlength=\"255\">\n";
$edit_main .= "<p>Source: <input class=\"text\" type=text name=dl_src value=\"" . clean_text_for_input($sw_src) . "\" size=\"40\" maxlength=\"255\">\n";
$edit_main .= "<p>Screenshot: <input class=\"text\" type=text name=dl_screenshot value=\"" . clean_text_for_input($sw_screenshot) . "\" size=40>\n";
$edit_main .= "<p>OS: <input class=\"text\" type=text name=dl_os value=\"" . clean_text_for_input($sw_os) . "\" size=\"40\" maxlength=\"64\">\n";
$edit_main .= "<p>Page: <input class=\"text\" type=text name=dl_page value=\"" . clean_text_for_input($sw_page) . "\" size=\"40\" maxlength=\"255\">\n";
$edit_main .= "<p>Description:\n";
$edit_main .= "<p><textarea class=\"text\" name=\"dl_description\" rows=10 cols=60 wrap=\"virtual\">" . clean_text_for_input($sw_description) . "</textarea>\n";

// Apply the CKEditor
$edit_main .= "<script>CKEDITOR.replace( \"dl_description\", {enterMode: CKEDITOR.ENTER_BR} );</script>";

$edit_main .= "<p>Keywords:\n";
$edit_main .= "<p><textarea class=\"text\" name=\"dl_keywords\" rows=3 cols=60 wrap=\"virtual\">" . clean_text_for_input($sw_keywords) . "</textarea>\n";
$edit_main .= "<p><input class=\"submit\" type=submit name=\"toss\" value=\"Submit\">\n";
$edit_main .= "</form>\n";

$main_text_class[0] = "maintext";
if ($user_type === "admin") {
	$main_text[0] = $edit_main;
}
else {
	$main_text[0] = "<h2>Edit download</h2>\n<p>You must be logged in to edit download details.\n";
}

include "template.php";
include "fin.php";

?>

