<?php

include "init.php";

$sw_id = isset($_GET["dl_id"]) ? $_GET["dl_id"] : -1;

$page_name = isset($_GET["page"]) ? sanitize($_GET["page"]) : "";
$main_text = array();
$side_text = array();
$main_text_class = array();
$side_text_class = array();
$navbar_string;
$navigation_string;

$sw_title = "";
$sw_icon = "";
$sw_description = "";
$sw_bin = "";
$sw_src = "";
$sw_screenshot = "";
$sw_page = "";
$sw_version = "";
$sw_date = "";
$sw_date_string = "";
$sw_os = "";
$sw_keywords = "";

$page_title = "Delete download";

$sql = $conn->prepare("SELECT * FROM Software where software_id = :sw_id");
$sql->bindParam(':sw_id', $sw_id);
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
$navbar_string .= "<a href=\"deldl.php?dl_id=" . $sw_id;
$navbar_string .= "\">Delete</a></span>";

$navigation_string = "This is a dead end.";

$side_text_class[0] = "navigate";
$side_text[0]  = "<div id=\"navigate\">\n";
$side_text[0] .= "<h2>Navigate</h2>\n";
$side_text[0] .= $navigation_string . "\n</div>";

if ($user_type == "none") {
  $login_side = sizeof($side_text);
	$side_text_class[$login_side] = "actions";
  $side_text[$login_side]  = "<div id=\"actions\">\n";
  $side_text[$login_side] .= "<h2>Actions</h2>\n";
  $side_text[$login_side] .= "<span class=\"vlink\"><a href=\"//login\">Login</a></span>\n";
  $side_text[$login_side] .= "<span class=\"vlink\"><a href=\"//email\">Email</a></span>\n";
  $side_text[$login_side] .= "</div>";
}
else if ($user_type == "admin") {
  $login_side = sizeof($side_text);
	$side_text_class[$login_side] = "actions";
  $side_text[$login_side]  = "<div id=\"actions\">\n";
  $side_text[$login_side] .= "<h2>Actions</h2>\n";
  $side_text[$login_side] .= "<span class=\"vlink\"><a href=\"./logout_do.php\">Logout</a></span>\n";
  $side_text[$login_side] .= "<span class=\"vlink\"><a href=\"//download&amp;dl_id=' + sw_id + '\">View software</a></span>\n";
  $side_text[$login_side] .= "<span class=\"vlink\"><a href=\"./editacc.php\">Edit account</a></span>\n";
  $side_text[$login_side] .= "<span class=\"vlink\"><a href=\"//email\">Email</a></span>\n";
  $side_text[$login_side] .= "</div>";
}

$edit_main  = "<h2>Delete download</h2>\n";
$edit_main .= "<form action=\"deldl_do.php?" . "\" method=\"post\">\n";
$edit_main .= "<p><b>This will delete the download entry for \"" . $sw_title . "\".</b>\n";
$edit_main .= "<p>If you are sure you want to do this, you must enter your password below.\n";
$edit_main .= "<input type=hidden name=dl_id value=\"" . $sw_id . "\">\n";
$edit_main .= "<p>Password: <input class=\"text\" type=password name=password value=\"\">\n";
$edit_main .= "<p><input class=\"submit\" type=submit name=\"toss\" value=\"Submit\">\n";
$edit_main .= "</form>\n";

$main_text_class[0] = "maintext";
if ($user_type == "admin") {
  $main_text[0] = $edit_main;
}
else {
  $main_text[0] = "<h2>Delete download</h2>\n<p>You must be logged in to delete download entries.\n";
}

include "template.php";

include "fin.php";

?>

