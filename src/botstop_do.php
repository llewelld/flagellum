<?php

include "init.php";

$page_name = isset($_POST["page"]) ? sanitize($_POST["page"]) : "";


$code_string = isset($_POST["code"]) ? sanitize($_POST["code"]) : "";
$code_date = isset($_POST["time"]) ? sanitize($_POST["time"]) : 0;
$code_token = isset($_POST["token"]) ? sanitize($_POST["token"]) : "";

$main_text = array();
$side_text = array();
$main_text_class = array();
$side_text_class = array();
$page_id;
$page_title;
$page_short_title;
$page_url_rel;
$navbar_list;
$navbar_array;
$navbar_string;
$navigation_list;
$navigation_string;
$code_hash;
$date = time();
$timeout = 600 * 1000; /* milliseconds */
$human_ipadd;
$human_salt = $CONFIG['botstopsalt'];

$sql = $conn->prepare("SELECT * FROM Pages where page_name = :page_name");
$sql->bindParam(':page_name', $page_name);
$sql->execute();
$result = $sql->fetchAll();

if ((sizeof($result) <= 0) && ($page_name != "")) {
	$page_name = "lost";
	$sql = $conn->prepare("SELECT * FROM Pages where page_name = :page_name");
	$sql->bindParam(':page_name', $page_name);
	$sql->execute();
	$result = $sql->fetchAll();
}

if (sizeof($result) <= 0) {
	$page_name = "home";
	$sql = $conn->prepare("SELECT * FROM Pages where page_name = :page_name");
	$sql->bindParam(':page_name', $page_name);
	$sql->execute();
	$result = $sql->fetchAll();
}

if (sizeof($result) <= 0) {
	$page_title = "";
	$navbar_list = "";
	$navigation_list = "";
}
else {
	$page_title = "Bot Stop";
	$navbar_list = $result[0]["navbar"];
	$navigation_list = "";
}

$navbar_array = explode(" ", $navbar_list);
$navbar_string = "";
for ($i = 0; $i < (sizeof($navbar_array) - 1); $i++) {
	$sql = $conn->prepare("SELECT * FROM Pages where page_name = :navbar_item");
	$navbar_item = $navbar_array[$i];
	$sql->bindParam(':navbar_item', $navbar_item);
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
$navbar_string .= "<a href=\"botstop.asp?page=" . $page_name . $append;
$navbar_string .= "\">Bot Stop</a></span>";

$navigation_string = preg_replace("/<a/", "<span class=\"vlink\"><a", $navigation_list);
$navigation_string = preg_replace("/a>/", "a></span>", $navigation_string);

if ($navigation_string == "") {
  $navigation_string = "<span class=\"deadend\">This is a dead end.</span>";
}

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
  $side_text[$login_side] .= "<span class=\"vlink\"><a href=\"./logout_do.asp\">Logout</a></span>\n";
  $side_text[$login_side] .= "<span class=\"vlink\"><a href=\"./editacc.php\">Edit account</a></span>\n";
  $side_text[$login_side] .= "<span class=\"vlink\"><a href=\"//email\">Email</a></span>\n";
  $side_text[$login_side] .= "</div>";
}

$code_hash = sha1(strtoupper($code_string) . $code_date . $code_salt, FALSE);
$edit_main = "<h2>Level Up!</h2>\n";

if (($code_date + $timeout) > $date) {
  if ($code_hash == $code_token) {
		/* Success */
		$human = TRUE;
		$human_ipadd = $_SERVER['REMOTE_ADDR'];

		// Expires after a year
		$expires = time() + 60 * 60 * 24 * 365;
		setcookie("botstop[human]", botstop_hash($human_ipadd . $human_salt), $expires);

		$edit_main .= "<p />Thank you for submitting the information and proving that you're no bot! You have successfully been verified as a sentient entity\n";
		$edit_main .= "<p />In order to avoid having to ask you to do this again, an attempt will have been made to set a cookie. This cookie is not used for any other purpose except allowing you to skip this test in future.\n";

		$edit_main .= "<p />If you are not in fact sentient, and believe that this diagnosis has been made in error, please get in contact and we will regrade you accordingly.\n";
		$edit_main .= "<p />Please now feel free to <a href=\"//" . $page_name . "\">continue</a>.\n";
	}
	else {
		/* Failure */
		$edit_main .= "<p />Failure!\n";
		header("Location: " . rootredirect("//botfail", $conn, $style_code));
	}
}
else {
  /* Timeout */
  $edit_main .= "<p />Timeout\n";
		header("Location: " . rootredirect("//bottimeout", $conn, $style_code));
}

$main_text[0] = $edit_main;
$main_text_class[0] = "maintext";

include "template.php";
include "fin.php";

?>

