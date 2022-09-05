<?php

include "init.php";

$ref_id = isset($_GET["ref_id"]) ? (int)sanitize($_GET["ref_id"]) : -1;

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

$page_title = "Edit reference";

$sql = $conn->prepare("SELECT * FROM Refs WHERE ref_id = :ref_id");
$sql->bindParam(":ref_id", $ref_id);
$sql->execute();
$result = $sql->fetchAll();

if (sizeof($result) > 0) {
	$ref_authors = $result[0]["ref_authors"];
	$ref_title = $result[0]["ref_title"];
	$ref_publication = $result[0]["ref_publication"];
	$ref_date = guess_date($result[0]["ref_date"]);
	$ref_date_string = $result[0]["ref_date_string"];
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
$navbar_string .= "<a href=\"editref.php?ref_id=" . $ref_id;
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
  $side_text[$login_side] .= "<span class=\"vlink\"><a href=\"//references&amp;ref_id=" . $ref_id . "\">View reference</a></span>\n";
  $side_text[$login_side] .= "<span class=\"vlink\"><a href=\"./editacc.php\">Edit account</a></span>\n";
  $side_text[$login_side] .= "<span class=\"vlink\"><a href=\"//email\">Email</a></span>\n";
  $side_text[$login_side] .= "</div>";
}

$edit_main  = "<h2>Edit reference</h2>\n";

// Add in code to support CKEditor
$edit_main .= "<script src=\"ckeditor/ckeditor.js\"></script>\n";

$edit_main .= "<form action=\"editref_do.php\" method=\"post\">\n";
$edit_main .= "<p>This page can be used to edit a reference from the references list. Enter values below, then click \"Submit\" to update the reference details.\n";
$edit_main .= "<input type=hidden name=ref_id value=\"" . $ref_id . "\">\n";

$edit_main .= "<p>Authors: <input class=\"text\" type=text name=ref_authors value=\"" . clean_text_for_input($ref_authors) . "\" size=\"40\" maxlength=\"255\">\n";
$edit_main .= "<p>Title: <input class=\"text\" type=text name=ref_title value=\"" . clean_text_for_input($ref_title) . "\" size=\"40\" maxlength=\"255\">\n";
$edit_main .= "<p>Publication: <input class=\"text\" type=text name=ref_publication value=\"" . clean_text_for_input($ref_publication) . "\" size=\"40\" maxlength=\"255\">\n";
$edit_main .= "<p>Date: <input class=\"text\" type=text name=ref_date value=\"" . clean_text_for_input($ref_date_string) . "\" size=\"40\" maxlength=\"64\">\n";
$edit_main .= "<p>Location: <input class=\"text\" type=text name=ref_location value=\"" . clean_text_for_input($ref_location) . "\" size=\"40\" maxlength=\"100\">\n";
$edit_main .= "<p>Page: <input class=\"text\" type=text name=ref_page value=\"" . clean_text_for_input($ref_page) . "\" size=\"40\" maxlength=\"255\">\n";
$edit_main .= "<p>Download 1: <input class=\"text\" type=text name=ref_dl1 value=\"" . clean_text_for_input($ref_dl1) . "\" size=\"40\" maxlength=\"255\">\n";
$edit_main .= "<p>Link 1: <input class=\"text\" type=text name=ref_link1 value=\"" . clean_text_for_input($ref_link1) . "\" size=\"40\" maxlength=\"255\">\n";
$edit_main .= "<p>Download 2: <input class=\"text\" type=text name=ref_dl2 value=\"" . clean_text_for_input($ref_dl2) . "\" size=\"40\" maxlength=\"255\">\n";
$edit_main .= "<p>Link 2: <input class=\"text\" type=text name=ref_link2 value=\"" . clean_text_for_input($ref_link2) . "\" size=\"40\" maxlength=\"255\">\n";
$edit_main .= "<p>Download 3: <input class=\"text\" type=text name=ref_dl3 value=\"" . clean_text_for_input($ref_dl3) . "\" size=\"40\" maxlength=\"255\">\n";
$edit_main .= "<p>Link 3: <input class=\"text\" type=text name=ref_link3 value=\"" . clean_text_for_input($ref_link3) . "\" size=\"40\" maxlength=\"255\">\n";
$edit_main .= "<p>Extra:\n";
$edit_main .= "<p><textarea class=\"text\" name=\"ref_extra\" rows=8 cols=60 wrap=\"virtual\">" . clean_text_for_input($ref_extra) . "</textarea>\n";

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
	$main_text[0] = "<h2>Edit reference</h2>\n<p>You must be logged in to edit references.\n";
}

include "template.php";
include "fin.php";

?>

