<?php
include "init.php";

$location = "";

if (isset(getallheaders()["Redirect"]) && getallheaders()["Redirect"] != "") {
	$page_to = getallheaders()["Redirect"];
	header_remove("Redirect");
}
else {
	$page_to = isset($_GET["to"]) ? sanitize($_GET["to"]) : "";
}

if ($page_to != "") {
	$redirect = rootredirect("//" . $page_to, $conn, $style_code);
	if (substr($redirect, 0, 3) == "./?") {
		// If we can avoid redirecting and just use the new query string
		// details then we should
		parse_str(substr($redirect, 3), $getnew);
		$_GET += $getnew;
	}
	else {
		// Need to redirect elsewhere
		// There may be redirects (private, botstop) which take priority
		// so we won't actually add the redirect until we've decoded the details
		$_GET["page"] = $page_to;
	}
}

$page_name = isset($_GET["page"]) ? sanitize($_GET["page"]) : "";
$main_text = array();
$side_text = array();
$main_text_class = array();
$side_text_class = array();
$page_title = "";
$navbar_list = "";
$navbar_array = "";
$navbar_string = "";
$navigation_list = "";
$navigation_string = "";
$page_botstop = TRUE;
$page_access = "";
$url_extra = "";
$comments = FALSE;
$comments_id = "";
$comments_url= "";
$page_comments = FALSE;
$list_comments = FALSE;

$sql = $conn->prepare("SELECT * FROM Pages where page_name = :page_name");
$sql->bindParam(':page_name', $page_name);
$sql->execute();
$result = $sql->fetchAll();

if ((sizeof($result) == 0) && ($page_name != "")) {
	$sql = $conn->prepare("SELECT * FROM Pages where page_name = 'lost'");
	$sql->execute();
	$result = $sql->fetchAll();
	$page_name = "lost";
}

if (sizeof($result) == 0) {
	$sql = $conn->prepare("SELECT * FROM Pages where page_name = 'home'");
	$sql->execute();
	$result = $sql->fetchAll();
	$page_name = "home";
}

if (sizeof($result) == 0) {
	$page_title = "";
	$navbar_list = "";
	$navigation_list = "";
	$page_botstop = FALSE;
	$page_access = FALSE;
	$page_comments = FALSE;
}
else {
	$main_text[0] = $result[0]["main_text"];
	$main_text_class[0] = "maintext";
	$page_title = $result[0]["page_title"];
	$navbar_list = $result[0]["navbar"];
	$navigation_list = $result[0]["navigation"];
	$page_botstop = $result[0]["page_botstop"];
	$page_access = $result[0]["page_access"];
	$page_comments = $result[0]["page_comments"];
}

if (($page_access == TRUE) && ($user_type == "none") && ($page_name != "private")) {
	// We need to override previous redirects, so store this in the $location variable
	// to be added to the headers below
	$location = rootredirect("//private", $conn, $style_code);
}
else {
	if (($page_botstop == TRUE) && ($human != TRUE)) {
		if ($style_val != 0) {
			$url_extra = "style=" . $style_val . "&amp;";
		}
		else {
			$url_extra = "";
		}
		// We need to override previous redirects, so store this in the $location variable
		// to be added to the headers below
		$location = $res_prefix . "botstop.php?" . $url_extra . "page=" . $page_name;
	}
}

// We may need to redirect
if ($location != "") {
	// We apply the highest priority redirect
	header("Location: " . $location);
}

$navbar_array = explode(" ", $navbar_list);
$navbar_string = "";

for ($i = 0; $i < sizeof($navbar_array); $i++) {
	$sql = $conn->prepare("SELECT * FROM Pages where page_name = :navbar_item");
	$sql->bindParam(':navbar_item', $navbar_item);	
	$navbar_item = $navbar_array[$i];
	$sql->execute();
	$result = $sql->fetchAll();

	if (sizeof($result) > 0) {
		$navbar_string .= "<span class=\"hlink\">";
		$navbar_string .= "<a href=\"//" . $navbar_array[$i] . "\">";
		$navbar_string .= $result[0]["page_short_name"];
		$navbar_string .= "</a></span> ";
	}
}

$navigation_string = preg_replace("/<a/", "<span class=\"vlink\"><a", $navigation_list);
$navigation_string = preg_replace("/a>/", "a></span>", $navigation_string);

if ($navigation_string == "") {
	$navigation_string = "This is a dead end.";
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
	//$side_text[£login_side] .= "<span class=\"vlink\"><a href=\"http://www.addthis.com/bookmark.php\" onclick=\"return addthis_sendto();\">Share</a><script type=\"text/javascript\" src=\"http://s7.addthis.com/js/250/addthis_widget.js?pub=xa-4a509bdd38bdc013\"></script></span>\n";
	$side_text[$login_side] .= "<span class=\"vlink\"><a href=\"https://www.addtoany.com/share\">Share</a></span>\n";
	$side_text[$login_side] .= "</div>";
}
else if ($user_type == "admin") {
	$login_side = sizeof($side_text);
	$side_text_class[$login_side] = "actions";
	$side_text[$login_side]  = "<div id=\"actions\">\n";
	$side_text[$login_side] .= "<h2>Actions</h2>\n";
	$side_text[$login_side] .= "<span class=\"vlink\"><a href=\"./logout_do.php" . "\">Logout</a></span>\n";
	$side_text[$login_side] .= "<span class=\"vlink\"><a href=\"./editpage.php?page=" . $page_name . "\">Edit page</a></span>\n";
	$side_text[$login_side] .= "<span class=\"vlink\"><a href=\"./newpage.php?page=" . $page_name . "\">New page</a></span>\n";
	$side_text[$login_side] .= "<span class=\"vlink\"><a href=\"./delpage.php?page=" . $page_name . "\">Delete page</a></span>\n";
	$side_text[$login_side] .= "<span class=\"vlink\"><a href=\"./editacc.php\">Edit account</a></span>\n";
	$side_text[$login_side] .= "<span class=\"vlink\"><a href=\"//email\">Email</a></span>\n";
	//$side_text[$login_side] .= "<span class=\"vlink\"><a href=\"http://www.addthis.com/bookmark.php\" onclick=\"return addthis_sendto();\">Share</a><script type=\"text/javascript\" src=\"http://s7.addthis.com/js/250/addthis_widget.js?pub=xa-4a509bdd38bdc013\"></script></span>\n";
	$side_text[$login_side] .= "<span class=\"vlink\"><a href=\"https://www.addtoany.com/share\">Share</a></span>\n";
	$side_text[$login_side] .= "</div>";
}

include "list.php"; 
include "download.php"; 
include "references.php"; 
include "comments.php"; 
include "template.php"; 
include 'fin.php';
?>

