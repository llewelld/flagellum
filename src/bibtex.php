<?php

include "init.php";

if (isset(getallheaders()["Redirect"]) && getallheaders()["Redirect"] != "") {
	$page_to = getallheaders()["Redirect"];
	header_remove("Redirect");
}
else {
	$page_to = isset($_GET["to"]) ? sanitize($_GET["to"]) : "";
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

$page_title = "";
$navbar_list = "";
$navigation_list = "";
$page_botstop = FALSE;
$page_access = FALSE;
$page_comments= FALSE;

$navbar_array = explode(" ", $navbar_list);
$navbar_string = "";

$navigation_string = '';

$side_text_class[0] = "navigate";
$side_text[0]  = '';

$login_side = sizeof($side_text);
$side_text_class[$login_side] = "actions";
$side_text[$login_side]  = "<div id=\"actions\">\n";
$side_text[$login_side] .= "<h2>Actions</h2>\n";
$side_text[$login_side] .= "<span class=\"vlink\"><a href=\"//login\">Login</a></span>\n";
$side_text[$login_side] .= "<span class=\"vlink\"><a href=\"//email\">Email</a></span>\n";
//$side_text[$login_side] .= "<span class="vlink"><a href=\"http://www.addthis.com/bookmark.php"\ onclick=\"return addthis_sendto();\">Share</a><script type=\"text/javascript\" src=\"http://s7.addthis.com/js/250/addthis_widget.js?pub=xa-4a509bdd38bdc013\"></script></span>\n"
$side_text[$login_side] .= "<span class=\"vlink\"><a href=\"https://www.addtoany.com/share\">Share</a></span>\n";
$side_text[$login_side] .= "</div>";

include "references-bibtex.php";

include "template.php";

include "fin.php";

?>

