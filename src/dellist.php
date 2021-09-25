<?php

include "init.php";

$list_id = isset($_GET["list_id"]) ? $_GET["list_id"] : -1;

$page_name = isset($_GET["page"]) ? sanitize($_GET["page"]) : "";
$main_text = array();
$side_text = array();
$main_text_class = array();
$side_text_class = array();
$navbar_string;
$navigation_string;
$page_title;

$list_date = "";
$list_title = "";
$list_body = "";
$list_keywords = "";

$page_title = "Delete list item";

$sql = $conn->prepare("SELECT * FROM Lists WHERE list_id = :list_id");
$sql->bindParam(':list_id', $list_id);
$sql->execute();
$result = $sql->fetchAll();

if (sizeof($result) > 0) {
	$list_date = guess_date($result[0]["list_date"]);
	$list_title = $result[0]["list_title"];
	$list_body = $result[0]["list_body"];
	$list_id = $result[0]["list_id"];
	$list_keywords = $result[0]["list_keywords"];
}

$navbar_string  = "<span class=\"hlink\"><a href=\"//home\">Home</a></span>";
$navbar_string .= "<span class=\"hlink\"><a href=\"//list\">List</a></span>";
$navbar_string .= "<span class=\"hlink\">";
$navbar_string .= "<a href=\"dellist.asp?list_id=" . $list_id;
$navbar_string .= "\">Delete</a></span>";

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
  $side_text[$login_side] .= "<span class=\"vlink\"><a href=\"//list&amp;list_id=" . $list_id . "\">View list item</a></span>\n";
  $side_text[$login_side] .= "<span class=\"vlink\"><a href=\"./editacc.php\">Edit account</a></span>\n";
  $side_text[$login_side] .= "<span class=\"vlink\"><a href=\"//email\">Email</a></span>\n";
  $side_text[$login_side] .= "</div>";
}

$edit_main  = "<h2>Delete list item</h2>\n";
$edit_main .= "<form action=\"dellist_do.php\" method=\"post\">\n";
$edit_main .= "<p><b>This will delete the list item entitled '" . $list_title . "'.</b>\n";
$edit_main .= "<p>If you are sure you want to do this, you must enter your password below.\n";
$edit_main .= "<input type=hidden name=list_id value=\"" . $list_id . "\">\n";
$edit_main .= "<p>Password: <input class=\"text\" type=password name=password value=\"\">\n";
$edit_main .= "<p><input class=\"submit\" type=submit name=\"toss\" value=\"Submit\">\n";
$edit_main .= "</form>\n";

$main_text_class[0] = "maintext";
if ($user_type === "admin") {
  $main_text[0] = $edit_main;
}
else {
  $main_text[0] = "<h2>Delete list item</h2>\n<p>You must be logged in to delete list items.\n";
}

include "template.php";
include "fin.php";

?>

