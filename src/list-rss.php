<?php

$list = isset($_GET["list"]) ? sanitize($_GET["list"]) : "";
$list_id = isset($_GET["list_id"]) ? (int)sanitize($_GET["list_id"]) : -1;
$list_num = isset($_GET["list_num"]) ? (int)sanitize($_GET["list_num"]) : -1;
$list_date = isset($_GET["list_date"]) ? sanitize($_GET["list_date"]) : "";
$list_main = sizeof($main_text);
$list_count = 0;
$first_date = 0;

$list_day = -1;
$list_month = -1;
$list_year = -1;
$subheading = "";
$month = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

$list_maintitle = "";
$list_title ="";
$list_description = "";
$list_items = "";
$permalink = "";

if (isset($_GET["list"]) && (($_GET["list"] === "%") || ($_GET["list"] === "*"))) {
	$list = "%";
}

if (($page_name === "home") && ($list === "") && !isset($_GET["dnload"]) && !isset($_GET["dl_id"]) && !isset($_GET["ref"]) && !isset($_GET["ref_id"])) {
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
		$list_day = (int)(substr($list_date, 6, 2));
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
		if ($list !== "") {
			if ($list === "%") {
				$list_maintitle = "All lists";
			}
			else {
				$list_maintitle = ucfirst($list);
			}
		}
		else {
		$list_maintitle = "List item";
	}

	if ($list_num < 0) {
		$subheading = "all items";
	}
	else {
		if ($list_num == 1) {
			$subheading = "most recent item";
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
			$subheading .= $month[$list_month] . " ";
		}
		if ($list_year !== -1) {
			$subheading .= $list_year;
		}
	}

	$list_description = $list_maintitle;

	if (isset($_GET["list_num"]) || ($list_date != -1)) {
		$list_description .= ", " . $subheading;
	}

	if ($list_id == -1) {
		$sql = $conn->prepare("SELECT * FROM Lists WHERE list_keywords LIKE :list ORDER BY list_date DESC");
		$list_wildcard = "%" . $list . "%";
		$sql->bindParam(':list', $list_wildcard);
	}
	else {
		$sql = $conn->prepare("SELECT * FROM Lists WHERE list_id = :list_id");
		$sql->bindParam(':list_id', $list_id);
	}

	$sql->execute();
	$result = $sql->fetchAll();

	if (sizeof($result) > 0) {
		// Create the list of items

		$first_date = guess_date($result[0]["list_date"]);

		$list_count = 0;
		$list_pos = 0;
		while ($list_pos < sizeof($result)) {
			$item_date = guess_date($result[$list_pos]["list_date"]);
			$item_date_string = short_date($item_date);
			$item_year = date("Y", $item_date);
			$item_month = date("m", $item_date);
			$item_day = date("d", $item_date);


			$list_date = guess_date($result[0]["list_date"]);
			$list_date_string = short_date($list_date);

			if ((($list_count < $list_num) || ($list_num <= 0))
				&& (($list_year < 0) || ($item_year == $list_year))
				&& (($list_month < 0) || ($item_month == $list_month))
				&& (($list_day < 0) || ($item_day == $list_day))) {

				$list_title = $result[$list_pos]["list_title"];
				$list_body = $result[$list_pos]["list_body"];
				$list_id = $result[$list_pos]["list_id"];

				$list_items .= "    <item>\n";

				$list_items .= "      <title>" . clean_text_rss($list_title) . "</title>\n";
				$list_items .= "      <pubDate>" . Date("c", $list_date) . "</pubDate>\n";

				$permalink = "//list&amp;list_id=" . $list_id;
				if ($list != "") {
					$permalink .= "&amp;list=" . $list;
				}

				$list_items .= "      <link>" . rootprefix($permalink, $conn, $style_code, $res_prefix) . "</link>\n";
				$list_items .= "      <guid isPermaLink=\"true\">" . rootprefix($permalink, $conn, $style_code, $res_prefix) . "</guid>\n";

				if ($list_body != "") {
					$list_items .= "      <description>" . clean_text_rss(root($list_body, $conn, $style_code, $res_prefix, $human)) . "</description>\n";
				}

				$list_items .= "    </item>\n";

				$list_count += 1;
			}
			$list_pos += 1;
		}
	}
}

?>
