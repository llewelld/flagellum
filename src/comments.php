<?php
	$list = isset($_GET["list"]) ? sanitize($_GET["list"]) : "";
	$list_id = isset($_GET["list_id"]) ? (int)sanitize($_GET["list_id"]) : -1;
	$reference = isset($_GET["ref"]) ? sanitize($_GET["ref"]) : "";
	$ref_id = isset($_GET["ref_id"]) ? (int)sanitize($_GET["ref_id"]) : -1;
	$software = isset($_GET["dnload"]) ? sanitize($_GET["dnload"]) : "";
	$sw_id = isset($_GET["dl_id"]) ? (int)sanitize($_GET["dl_id"]) : -1;
	$comments_main = sizeof($main_text);

	$comments_url = current_url();

	if (isset($page_comments_id) && $page_comments_id !== "") {
		$main_text_class[$comments_main] = "maincomments";
		$main_text[$comments_main] = "<h2>Comments</h2>\n";
		$main_text[$comments_main] .= "<div id=\"comment_thread\"></div>\n";
		$main_text[$comments_main] .= "\n";
		$main_text[$comments_main] .= "<a id=\"show_comments\" href=\"#comment_thread\" onClick=\"return loadComments('" . $comments_instance . "', '" . $page_comments_id . "')\">Uncover Fediverse comments</a>\n";
		$main_text[$comments_main] .= "\n";
		$main_text[$comments_main] .= "<noscript>~View comments from the <a href=\"" . $comments_instance . "/statuses/" . $page_comments_id . "\">Fediverse</a></noscript>\n";
		$main_text[$comments_main] .= "<div id=\"comments_list\"></div>\n";
		$main_text[$comments_main] .= "\n";
	}
?>

