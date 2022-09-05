<?php

include "init.php";

$page_name = isset($_GET["page"]) ? sanitize($_GET["page"]) : "";
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
$code_string;
$code_date = time();
$code_hash;
$code_key = pack("H*", $CONFIG['botstopkey']);
$code_cypher;

$sql = $conn->prepare("SELECT * FROM Pages where page_name = :page_name");
$sql->bindParam(':page_name', $page_name);
$sql->execute();
$result = $sql->fetchAll();

if ((sizeof($result) <= 0) && ($page_name !== "")) {
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
$navbar_string .= "<a href=\"botstop.php?page=" . $page_name . $append;
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
  $side_text[$login_side] .= "<span class=\"vlink\"><a href=\"./logout_do.php\">Logout</a></span>\n";
  $side_text[$login_side] .= "<span class=\"vlink\"><a href=\"./editacc.php\">Edit account</a></span>\n";
  $side_text[$login_side] .= "<span class=\"vlink\"><a href=\"//email\">Email</a></span>\n";
  $side_text[$login_side] .= "</div>";
}

$code_string = "";
for ($count = 0; $count < 5; $count++) {
	$code_string .= chr(ord("A") + rand(0, 25));
}

$code_hash = sha1($code_string . $code_date . $code_salt, FALSE);

$code_cypher = bin2hex(openssl_encrypt($code_string, "aes-128-ecb", $code_key, $options = OPENSSL_RAW_DATA));

$edit_main  = "<h2>Are you sentient?</h2>\n";
$edit_main .= "<form action=\"botstop_do.php" . $appendinit . "\" method=\"post\">\n";

$edit_main .= "<p />I am trying to avoid the legendary spam monster. According to <a href=\"http://www.hermitscave.org/\">The Inquirer</a>,";
$edit_main .= "<p /><div class=\"quote\">&quot;They say it's as big as four cats, and it's got a retractable leg so as it can leap up at you better and it lights up at night, and it's got four ears. Two of them are for listening and the other two are kind of back-up ears, and its claws are as big as cups and for some reason it's got a tremendous fear of stamps and Flashy was tellin' me that it's got magnets on its tail so if you're made out of metal it can attach itself to you, and instead of a mouth it's got four arses.&quot;</div>";

$edit_main .= "<p />Please help me in my quest and prove that you are sentient by entering the text shown below, and hitting submit.\n";
$edit_main .= "<input type=hidden name=page value=\"" . $page_name . "\" />\n";
$edit_main .= "<input type=hidden name=time value=\"" . $code_date . "\" />\n";
$edit_main .= "<input type=hidden name=token value=\"" . $code_hash . "\" />\n";

$edit_main .= "<p /><img style=\"float_left\" src=\"./cgi-bin/botstop.cgi?text=" . $code_cypher . "\" alt=\"Code\" width=\"248\" height=\"44\"/>\n";

$edit_main .= "<p />Code displayed above: <input class=\"text\" type=text name=code value=\"\" size=\"16\" maxlength=\"5\" /><br />(5 capital letters, no numbers)\n";

$edit_main .= "<p /><input class=\"submit\" type=submit name=\"toss\" value=\"Submit\" />\n";
$edit_main .= "</form>\n";

$main_text[0] = $edit_main;
$main_text_class[0] = "maintext";

include "template.php";

include "fin.php";

?>

