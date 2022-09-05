<?php

include "init.php";

$check = isset($_GET["check"]) ? (int)sanitize($_GET["check"]) : 0;
$page_title = "Edit account";
$main_text = array();
$side_text = array();
$main_text_class = array();
$side_text_class = array();
$navbar_list;
$navbar_array;
$navbar_string;
$navigation_list;
$navigation_string;
$inputval = array();
$inputvalcheck;	

if (!check_number($check)) {
	$check = 0;
}

$inputval[0] = $user_name;
$inputval[1] = $user_email;

if ($check != 0) {
	for ($i = 0; $i < 2; $i++) {
		$inputvalcheck = isset($_GET["v". $i]) ? sanitize($_GET["v" . $i]) : "";
		if ($inputvalcheck != "") {
			$inputval[$i] = $inputvalcheck;
		}
	}
}

$navbar_string  = "<span class=\"hlink\"><a href=\"//home\">Home</a></span>";
$navbar_string .= "<span class=\"hlink\">";
$navbar_string .= "<a href=\"editacc.php\">Edit account</a>";
$navbar_string .= "</span>";

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
	$side_text[$login_side] .= "<span class=\"vlink\"><a href=\"//email\">Email</a></span>\n";
	$side_text[$login_side] .= "</div>";
}

$edit_main  = "<h2>Edit account</h2>\n";
$edit_main .= "<form action=\"editacc_do.php\" method=\"post\">\n";
$edit_main .= "<p/>This page can be used to edit your user account. Enter values below, then click \"Submit\" to update your user details. You <em>must</em> include your password to do this.\n";

if ($check != 0) {
	$edit_main .= "<p/><span class=\"warning\">Please check the fields marked with a * as there was a problem with the information you entered.</span>\n";
}

$edit_main .= "<p/>Username: " . $user_username . "\n";
$edit_main .= "<p/>Account type: " . $user_type . "\n";

$edit_main .= "<input type=hidden name=user_username value=\"" . $user_username . "\"/>\n";

if (($check & 1) == 1) {
	$edit_main .= "<p/><span class=\"warning\">Name: * </span>";
}
else {
	$edit_main .= "<p/>Name: ";
}
$edit_main .= "<input class=\"text\" type=text name=user_name value=\"" . $inputval[0] . "\" size=\"40\" maxlength=\"50\"/>\n";

if (($check & 2) == 2) {
	$edit_main .= "<p/><span class=\"warning\">Email: * </span>";
}
else {
	$edit_main .= "<p/>Email: ";
}

$edit_main .= "<input class=\"text\" type=text name=user_email value=\"" . $inputval[1] . "\" size=\"40\" maxlength=\"50\"/>\n";

if (($check & 4) == 4) {
	$edit_main .= "<p/><span class=\"warning\">Password: * </span>";
}
else {
	$edit_main .= "<p/>Password: ";
}

$edit_main .= "<input class=\"text\" type=password name=user_password value=\"\"/>\n";

$edit_main .= "<p/>Leave the following fields blank to stick with your current password.\n";

if (($check & 8) == 8) {
	$edit_main .= "<p/><span class=\"warning\">New password: * </span>";
}
else {
	$edit_main .= "<p/>New password: ";
}

$edit_main .= "<input class=\"text\" type=password name=user_newpassword1 value=\"\"/>\n";

if (($check & 8) == 8) {
	$edit_main .= "<p/><span class=\"warning\">New password again: * </span>";
}
else {
	$edit_main .= "<p/>New password again: ";
}

$edit_main .= "<input class=\"text\" type=password name=user_newpassword2 value=\"\"/>\n";

$edit_main .= "<p><input class=\"submit\" type=submit name=\"toss\" value=\"Submit\">\n";

$edit_main .= "</form>\n";

$main_text_class[0] = "maintext";
if ($user_type === "admin") {
	$main_text[0] = $edit_main;
}
else {
	$main_text[0] = "<h2>Edit account</h2>\n<p\>You must be logged in to edit your account details.\n";
}

include "template.php";
include "fin.php";

?>

