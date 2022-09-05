<?php

include "init.php";

$page_name = isset($_GET["page"]) ? sanitize($_GET["page"]) : "";
$main_text = array();
$side_text = array();
$main_text_class = array();
$side_text_class = array();
$page_id = -1;
$page_title = "";
$page_short_title = "";
$page_url_rel = "";
$navbar_list = "";
$navbar_array = "";
$navbar_string = "";
$navigation_list = "";
$navigation_string = "";
$page_botstop = FALSE;
$page_access = FALSE;
$page_comments = FALSE;

$sql = $conn->prepare("SELECT * FROM Pages where page_name = :page_name");
$sql->bindParam(':page_name', $page_name);
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
  $page_botstop = FALSE;
  $page_access = FALSE;
  $page_comments = FALSE;
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
  $page_botstop = $result[0]["page_botstop"];
  $page_access = $result[0]["page_access"];
  $page_comments = $result[0]["page_comments"];
}

$navbar_array = explode(" ", $navbar_list);
$navbar_string = "";
for ($i = 0; $i < sizeof($navbar_array); $i++) {
  $sql = $conn->prepare("SELECT * FROM Pages where page_name = :navbar_item");
	$sql->bindParam(':navbar_item', $navbar_array[$i]);
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
$navbar_string .= "<a href=\"editpage.asp?page=" . $page_name;
$navbar_string .= "\">Edit</a></span>";

$navigation_string = preg_replace("/<a/", "<span class=\"vlink\"><a", $navigation_list);
$navigation_string = preg_replace("/a>/", "a></span>", $navigation_string);

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
  $side_text[$login_side] .= "<span class=\"vlink\"><a href=\"./logout_do.asp\">Logout</a></span>\n";
  $side_text[$login_side] .= "<span class=\"vlink\"><a href=\"//" . $page_name . "\">View page</a></span>\n";
  $side_text[$login_side] .= "<span class=\"vlink\"><a href=\"./editacc.asp\">Edit account</a></span>\n";
  $side_text[$login_side] .= "<span class=\"vlink\"><a href=\"//email\">Email</a></span>\n";
  $side_text[$login_side] .= "</div>";
}

$edit_main  = "<h2>Edit page</h2>\n";

// Add in code to support CKEditor
$edit_main .= "<script src=\"ckeditor/ckeditor.js\"></script>\n";

$edit_main .= "<form action=\"editpage_do.php\" method=\"post\">\n";
$edit_main .= "<p />This page can be used to edit the material on the page. Enter values below, then click 'Submit' to update the page contents.\n";
$edit_main .= "<input type=hidden name=page_id value=\"" . $page_id . "\" />\n";
$edit_main .= "<p />ID: <input class=\"text\" type=text name=page_name value=\"" . $page_name . "\" size=\"40\" maxlength=\"64\" />\n";
$edit_main .= "<p />Title: <input class=\"text\" type=text name=page_title value=\"" . clean_text_for_input($page_title) . "\" size=\"40\" maxlength=\"255\" />\n";
$edit_main .= "<p />Short title: <input class=\"text\" type=text name=page_short_title value=\"" . clean_text_for_input($page_short_title) . "\" size=\"40\" maxlength=\"255\" />\n";
$edit_main .= "<p />URL: <input class=\"text\" type=text name=page_url_rel value=\"" . clean_text_for_input($page_url_rel) . "\" size=\"40\" maxlength=\"255\" />\n";
$edit_main .= "<p />Navbar: <input class=\"text\" type=text name=navbar value=\"" . clean_text_for_input($navbar_list) . "\" size=\"40\" maxlength=\"255\" />\n";
$edit_main .= "<p />Navigation:\n";
$edit_main .= "<p /><textarea class=\"text\" name=\"navigation\" rows=8 cols=60 wrap=\"virtual\">" . clean_text_for_input($navigation_list) . "</textarea>\n";

$edit_main .= "<p />Content:\n";
$edit_main .= "<p /><textarea class=\"text\" name=\"main\" rows=64 cols=60 wrap=\"virtual\">" . clean_text_for_input($main_text[0]) . "</textarea>\n";

// Apply the CKEditor
$edit_main .= "<script>CKEDITOR.replace(\"main\");</script>";

if ($page_botstop == TRUE) {
  $edit_main .= "<p /><input type=\"checkbox\" name=\"botstop\" value=\"true\" checked=\"true\">Protect from bots?</input>\n";
}
else {
  $edit_main .= "<p /><input type=\"checkbox\" name=\"botstop\" value=\"true\">Protect from bots?</input>\n";
}

$edit_main .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";

if ($page_access == TRUE) {
  $edit_main .= "<input type=\"checkbox\" name=\"access\" value=\"true\" checked=\"1\">Make private?</input>\n";
}
else {
  $edit_main .= "<input type=\"checkbox\" name=\"access\" value=\"true\">Make private?</input>\n";
}

$edit_main .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";

if ($page_comments == TRUE) {
  $edit_main .= "<input type=\"checkbox\" name=\"comments\" value=\"true\" checked=\"1\">Allow comments?</input>\n";
}
else {
  $edit_main .= "<input type=\"checkbox\" name=\"comments\" value=\"true\">Allow comments?</input>\n";
}

$edit_main .= "<p><input class=\"submit\" type=submit name=\"toss\" value=\"Submit\">\n";
$edit_main .= "</form>\n";

$main_text_class[0] = "maintext";
if ($user_type == "admin") {
  $main_text[0] = $edit_main;
}
else {
  $main_text[0] = "<h2>Edit page</h2>\n<p>You must be logged in to edit this page.\n";
}

include "template.php";
include "fin.php";

?>

