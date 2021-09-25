<?php

$software = isset($_GET["dnload"]) ? sanitize($_GET["dnload"]) : "";
$sw_id = isset($_GET["dl_id"]) ? (int)sanitize($_GET["dl_id"]) : -1;
$sw_num = isset($_GET["dl_num"]) ? sanitize($_GET["dl_num"]) : -1;
$sw_main = sizeof($main_text);
$comma = FALSE;
$sw_count = 0;

if (isset($_GET["dnload"]) && ($_GET["dnload"] === "%")) {
	$software = "%";
}

$download_description = "";
$download_items = "";
$permalink = "";
$description = "";

if ($software != "" || $sw_id != -1) {
	if ($sw_id == -1) {
		$sql = $conn->prepare("SELECT * FROM Software where software_keywords LIKE :software ORDER BY software_date DESC");
		$software_wildcard = "%" . $software . "%";
		$sql->bindParam(':software', $software_wildcard);
	}
	else {
		$sql = $conn->prepare("SELECT * FROM Software where software_id = :sw_id");
		$sql->bindParam(":sw_id", $sw_id);
	}

	$sql->execute();
	$result = $sql->fetchAll();

	if (sizeof($result) > 0) {
		$download_description .= "Downloads";

		$sw_count = 0;
		$sw_pos = 0;
		while ($sw_pos < sizeof($result)) {
			if (($sw_count < $sw_num) || ($sw_num <= 0)) {
				$sw_title = $result[$sw_pos]["software_title"];
				$sw_icon = $result[$sw_pos]["software_icon"];
				$sw_description = $result[$sw_pos]["software_description"];
				$sw_bin = $result[$sw_pos]["software_bin"];
				$sw_src = $result[$sw_pos]["software_src"];
				$sw_screenshot = $result[$sw_pos]["software_screenshot"];
				$sw_page = $result[$sw_pos]["software_page"];
				$sw_version = $result[$sw_pos]["software_version"];
				$sw_date = guess_date($result[$sw_pos]["software_date"]);
				$sw_date_string = short_date($sw_date);
				$sw_os = $result[$sw_pos]["software_os"];
				$sw_id = $result[$sw_pos]["software_id"];

				$download_items .= "    <item>\n";

				$download_items .= "      <title>" . clean_text_rss($sw_title) . "</title>\n";
				$download_items .= "      <pubDate>" . date("c", $sw_date) . "</pubDate>\n";

				$permalink = "//download&amp;dl_id=" . $sw_id;

				$rootprefix = rootprefix($permalink, $conn, $style_code, $res_prefix);

				$download_items .= "      <link>" . $rootprefix . "</link>\n";
				$download_items .= "      <guid isPermaLink=\"true\">" . $rootprefix . "</guid>\n";

				$description = "";

				if (($sw_version != "") || ($sw_date_string != "")) {
					$description .= "Version " . $sw_version . " (" . $sw_date_string . ")";
					if ($sw_os != "") {
						$description .= " for " . $sw_os;
					}
					$description .= ". ";
				}
				else {
					if ($sw_os != "") {
						$description .= "<br/>For " . $sw_os . ". ";
					}
				}

				$description .= "<br/>" . $sw_description . " ";
				if ($sw_page != "") {
					$description .= "<a href=\"" . $sw_page . "\">More info...</a>\n";
				}

				if (($sw_bin != "") || ($sw_src != "") || ($sw_screenshot != "")) {
					$comma = FALSE;
					$description .= "<br/>Download: ";
					if ($sw_bin != "") {
						$description .= "<a href=\"" . $sw_bin . "\">binary</a>";
						$comma = TRUE;
					}
					if ($sw_src != "") {
						if ($comma == TRUE) {
							$description .= ", ";
						}
						$description .= "<a href=\"" . $sw_src . "\">source</a>";
						$comma = TRUE;
					}
					if ($sw_screenshot != "") {
						if ($comma == TRUE) {
							$description .= ", ";
						}
						$description .= "<a href=\"" . $sw_screenshot . "\">screenshot</a>";
						$comma = TRUE;
					}
					$description .= ".";
				}

				$download_items .= "      <description>" . clean_text_rss(root($description, $conn, $style_code, $res_prefix, $human)) . "</description>\n";

				$download_items .= "    </item>\n";
				
				$sw_count += 1;
			}
			$sw_pos++;
		}
	}
}

?>
