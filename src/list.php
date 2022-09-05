<?php

$list = isset($_GET["list"]) ? sanitize($_GET["list"]) : "";
$list_id = isset($_GET["list_id"]) ? (int)sanitize($_GET["list_id"]) : -1;
$list_num = isset($_GET["list_num"]) ? (int)sanitize($_GET["list_num"]) : -1;
$list_date = isset($_GET["list_date"]) ? sanitize($_GET["list_date"]) : "";
$list_index = isset($_GET["list_index"]) ? (bool)sanitize($_GET["list_index"]) : FALSE;
$list_main = sizeof($main_text);
$list_count = 0;
$comma = FALSE;
$first_date = 0;
$last_date = 0;
$start_date = 0;
$end_date = 0;
$comments_id = "";

$list_day = -1;
$list_month = -1;
$list_year = -1;
$subheading = "";
$month = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
$archive_month = array();
$archive_year = array();
$archive_count = 0;
$archive_later = FALSE;
$archive_truncated = FALSE;
$permalink = "";
$override_comments = FALSE;

if (($page_name === "home") && ($list === "")) {
	$list = "news";
}

if ($list_date != "") {
	if (strlen($list_date) >= 4) {
		$list_year = (int)(substr($list_date, 0, 4));
	}
	if (strlen($list_date) >= 6) {
		$list_month = (int)(substr($list_date, 4, 2)) - 1;
	}
	if (strlen($list_date) >= 8) {
		$list_day = (int)(substr(list_date, 6, 2));
	}
	if ($list_num < 0) {
		$list_num = -1;
	}
}
else {
	if ($list_num < 0) {
		if ($page_name === "home") {
			$list_num = 5;
		}
		else {
			$list_num = 100;
		}
	}
	$list_date = -1;
}

if (($list !== "") || ($list_id >= 0)) {
	$main_text_class[$list_main] = "mainlist";

	if ($list !== "") {
		if ($list === "%") {
			$list_header = "All lists\n";
		}
		else {
			$list_header = ucfirst($list);
		}

		if ($list_index === TRUE) {
			$list_header .= " Index";
		}

		$main_text[$list_main] = "<h2>" . $list_header . "</h2>\n";
	}
	else {
		$main_text[$list_main] = "<h2>List item</h2>\n";
	}

	if ($list_num < 0) {
		$subheading = "All items";
	}
	else {
		if ($list_num === 1) {
			$subheading = "Most recent item";
		}
		else {
			$subheading = $list_num . " most recent items";
		}
	}

	if ($list_date !== -1) {
		$subheading .= " from ";
		if ($list_day !== -1) {
			$subheading .= $list_day . " ";
		}
		if ($list_month !== -1) {
			$subheading .= $month[$list_month - 1] . " ";
		}
		if ($list_year !== -1) {
			$subheading .= $list_year;
		}
	}

	if (isset($_GET["list_num"]) || ($list_date != -1)) {
		$main_text[$list_main] .= "<h3>" . $subheading . "</h3>\n";
	}

	//$main_text[$list_main] .= "<p>" . $list_id . "</p>\n";

	if ($list_id == -1) {
		$sql = $conn->prepare("SELECT * FROM Lists WHERE list_keywords LIKE :list ORDER BY list_date DESC");
		$list_wildcard = "%" . $list . "%";
		$sql->bindParam(':list', $list_wildcard);
	}
	else {
		$sql = $conn->prepare("SELECT * FROM Lists WHERE list_id = :list_id");
		$sql->bindParam(':list_id', $list_id);
		$archive_truncated = TRUE;
		$override_comments = TRUE;
	}

	$sql->execute();
	$result = $sql->fetchAll();

	if (sizeof($result) <= 0) {
		$main_text[$list_main] .= "<p>Sorry. There are no list items that match the search criteria provided.</p>\n";
	}
	else {
		// Create the list of items

		$now = time();
		$first_date = mktime(0, 0, 0, $list_month >= 0 ? $list_month : date("n", $now), $list_date >= 0 ? $list_date : date("j", $list_date), $list_year >= 0 ? $list_year : date("Y", $list_year));
		$last_date = $first_date;
		$start_date = $first_date;
		$end_date = $first_date;
		$list_date = $first_date;

		$main_text[$list_main] .= "<div class=\"list\">\n";

		$archive_count = 0;
		$archive_year[$archive_count] = -1;
		$archive_month[$archive_count] = -1;
		$archive_later = FALSE;

		$list_count = 0;
		$list_pos = 0;
		while ($list_pos < sizeof($result)) {
			$item_date = guess_date($result[$list_pos]["list_date"]);
			$item_date_string = short_date($item_date);
			$item_year = date("Y", $item_date);
			$item_month = date("m", $item_date);
			$item_day = date("d", $item_date);

			if ((($list_count < $list_num) || ($list_num <= 0))
				&& (($list_year < 0) || ($item_year == $list_year))
				&& (($list_month < 0) || ($item_month == $list_month))
				&& (($list_day < 0) || ($item_day == $list_day))) {

				$list_title = $result[$list_pos]["list_title"];
				$list_body = $result[$list_pos]["list_body"];
				$list_id = $result[$list_pos]["list_id"];
				$list_comments = $result[$list_pos]["list_comments"];
				if ($override_comments == TRUE) {
					$page_comments = $list_comments;
				}

				$main_text[$list_main] .= "<div class=\"list_item\">";
				$permalink = "//list&amp;list_id=" . $list_id;
				if ($list != "") {
					$permalink .= "&amp;list=" . $list;
				}
				if ($list_title != "") {
					$main_text[$list_main] .= "<span class=\"list_title\">" . $item_date_string . " : " . $list_title . "</span> <span class=\"list_link\">";
					if ($list_index !== TRUE) {
						$main_text[$list_main] .= "<a href=\"";
						$main_text[$list_main] .= $permalink;
						$main_text[$list_main] .= "\">#</a>";
					}
					$main_text[$list_main] .= "</span>\n";
				}
				if ($list_body != "") {
					if ($list_index === TRUE) {
							$main_text[$list_main] .= "<div class=\"list_body\"><a href=\"" . $permalink . "\">Read more...</a></div>\n";
					}
					else {
						$main_text[$list_main] .= "<div class=\"list_body\">" . $list_body . "</div>\n";
					}
				}

				if (($list_comments == TRUE) && ($override_comments != TRUE) && ($list_index != TRUE)) {
					$main_text[$list_main] .= "<span class=\"list_link\"><a href=\"";
					$main_text[$list_main] .= $permalink . "#disqus_thread\"";
					//$main_text[$list_main] .= " data-disqus-identifier=\"";

					//$comments_id = generate_id("list", $list, $list_id, "", "", "", "");

					//main_text[$list_main] .= $comments_id;
					//main_text[$list_main] .= "\"";
					$main_text[$list_main] .= ">Comment</a></span>\n";
				}

				if ($user_type == "admin") {
					$main_text[$list_main] .= "<a href=\"./editlist.php?list_id=" . $list_id . "\">[Edit]</a> <a href=\"./dellist.php?list_id=" . $list_id . "\">[Delete]</a>.\n";
				}

				$main_text[$list_main] .= "</div>\n";
				$list_count += 1;
			}
			else {
				if ($list_count == 0) {
					$archive_later = TRUE;
				}
			}
			$list_pos += 1;

			if ($archive_count < 8) {
				if (($archive_month[$archive_count] != $item_month)
					|| ($archive_year[$archive_count] != $item_year)) {
					if (($list_count > 0) && ($archive_month[$archive_count] >= 0)) {
						$archive_count += 1;
					}
					$archive_month[$archive_count] = $item_month;
					$archive_year[$archive_count] = $item_year;
				}
			}
		}
		$archive_count++;
		$main_text[$list_main] .= "</div>\n";

		$end_date = $item_date;

		$last_pos = sizeof($result) - 1;
		$last_date = guess_date($result[$last_pos]["list_date"]);

		// Create the archive list
		if ($list != "") {
			$archive_side = sizeof($side_text);
			$side_text_class[$archive_side] = "archives";

			$side_text[$archive_side]  = "<div id=\"archives\">\n";
			$side_text[$archive_side] .= "<h2>Archives</h2>\n";

			for ($i = 0; (($i < $archive_count) && ($i < 5)); $i++) {
				$side_text[$archive_side] .= "<span class=\"vlink\"><a href=\"";
				$side_text[$archive_side] .= "//list&amp;list=";
				$side_text[$archive_side] .= $list . "&amp;list_date=";
				$side_text[$archive_side] .= archive_date_to_string($archive_year[$i], $archive_month[$i] + 1);

				if ($list_index === TRUE) {
					$side_text[$archive_side] .= "&amp;list_index=1";
				}
				$side_text[$archive_side] .= "\">";

				if ($archive_truncated == TRUE) {
					$side_text[$archive_side] .= "View";
				}
				else
				{
					if (($i != 0) || ($archive_later == FALSE)) {
						$side_text[$archive_side] .= $month[$archive_month[$i] - 1];
						$side_text[$archive_side] .= " " . $archive_year[$i];
					}
					else {
						$side_text[$archive_side] .= "More recent";
					}
				}
				$side_text[$archive_side] .= "</a></span>\n";
			}

			if ($i < $archive_count) {
				$side_text[$archive_side] .= "<span class=\"vlink\"><a href=\"";
				$side_text[$archive_side] .= "//list&amp;list=";
				$side_text[$archive_side] .= $list . "&amp;list_date=";
				$side_text[$archive_side] .= archive_date_to_string($archive_year[$i], $archive_month[$i] + 1);

				if ($list_index === TRUE) {
					$side_text[$archive_side] .= "&amp;list_index=1";
				}

				$side_text[$archive_side] .= "\">Older</a></span>\n";

			}

			$side_text[$archive_side] .= "</div>";
		}
	}

	if ($user_type == "admin") {
		$main_text[$list_main] .= "<p /><a href=\"./newlist.php?list=" . $list . "\">[New]</a>\n";
	}
}

?>

