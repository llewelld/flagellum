<?php

	$list = isset($_GET["list"]) ? sanitize($_GET["list"]) : "";
	$list_id = isset($_GET["list_id"]) ? (int)sanitize($_GET["list_id"]) : -1;
	$reference = isset($_GET["ref"]) ? sanitize($_GET["ref"]) : "";
	$ref_id = isset($_GET["ref_id"]) ? (int)sanitize($_GET["ref_id"]) : -1;
	$software = isset($_GET["dnload"]) ? sanitize($_GET["dnload"]) : "";
	$sw_id = isset($_GET["dl_id"]) ? (int)sanitize($_GET["dl_id"]) : -1;
	$comments_main = sizeof($main_text);

	$comments_id = generate_id($page_name, $list, $list_id, $reference, $ref_id, $software, $sw_id);
	$comments_url = current_url();

	if ($page_comments == TRUE) {
	  $main_text_class[$comments_main] = "maincomments";
		$main_text[$comments_main] = "<h2>Comments</h2>\n";

		$main_text[$comments_main] .= "<div id=\"disqus_thread\"></div>\n";
		$main_text[$comments_main] .= "\n";
		$main_text[$comments_main] .= "<a id=\"show_comments\" href=\"#disqus_thread\" onClick=\"return show_comments()\">Uncover Disqus comments</a>\n";
		$main_text[$comments_main] .= "\n";
		$main_text[$comments_main] .= "<script type=\"text/javascript\">\n";
		$main_text[$comments_main] .= "    var disqus_shortname = \"flypig\";\n";
		$main_text[$comments_main] .= "    var disqus_identifier = \"" . $comments_id . "\";\n";
		$main_text[$comments_main] .= "    var disqus_url = \"" . $comments_url . "\";\n";
		$main_text[$comments_main] .= "    function show_comments() {\n";
		$main_text[$comments_main] .= "        document.getElementById(\"show_comments\").style.display = \"none\";\n";
		$main_text[$comments_main] .= "        var dsq = document.createElement(\"script\"); dsq.type = \"text/javascript\"; dsq.async = true;\n";
		$main_text[$comments_main] .= "        dsq.src = \"https://\" + disqus_shortname + \".disqus.com/embed.js\";\n";
		$main_text[$comments_main] .= "        (document.getElementsByTagName(\"head\")[0] || document.getElementsByTagName(\"body\")[0]).appendChild(dsq);\n";
		$main_text[$comments_main] .= "        return false;\n";
		$main_text[$comments_main] .= "    };\n";
		$main_text[$comments_main] .= "</script>\n";
		$main_text[$comments_main] .= "<noscript>Please enable JavaScript to view the <a href=\"http://disqus.com/?ref_noscript\">comments powered by Disqus.</a></noscript>\n";
	}

?>

