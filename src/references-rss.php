<?php

$reference = isset($_GET["ref"]) ? sanitize($_GET["ref"]) : "";
$ref_id = isset($_GET["ref_id"]) ? (int)sanitize($_GET["ref_id"]) : -1;
$ref_num = isset($_GET["ref_num"]) ? sanitize($_GET["ref_num"]) : -1;
$ref_main = sizeof($main_text);
$comma = FALSE;
$ref_count = 0;

if (isset($_GET["ref"]) && ($_GET["ref"] === "%")) {
	$reference = "%";
}

$reference_description = "";
$reference_items = "";
$permalink = "";
$description = "";

if (($ref_num == "") || ($ref_num == "")) {
	$ref_num = -1;
}

if (($reference != "") || ($ref_id != -1)) {

	if ($reference != "") {
		$sql = $conn->prepare("SELECT * FROM Refs WHERE ref_keywords LIKE :reference ORDER BY ref_date DESC");
		$reference_wildcard = "%" . $reference . "%";
		$sql->bindParam(':reference', $reference_wildcard);
	}
	else {
		$sql = $conn->prepare("SELECT * FROM Refs WHERE ref_id = :ref_id");
		$sql->bindParam(':ref_id', $ref_id);
	}

	$sql->execute();
	$result = $sql->fetchAll();

	if (sizeof($result) > 0) {
		$reference_description .= "References";

		$ref_count = 0;
		$ref_pos = 0;
		while ($ref_pos < sizeof($result)) {
			if (($ref_count < $ref_num) || ($ref_num <= 0)) {
				$ref_authors = $result[$ref_pos]["ref_authors"];
				$ref_title = $result[$ref_pos]["ref_title"];
				$ref_publication = $result[$ref_pos]["ref_publication"];
				$ref_date = guess_date($result[$ref_pos]["ref_date"]);;
				$ref_date_string = $result[$ref_pos]["ref_date_string"];
				$ref_location = $result[$ref_pos]["ref_location"];
				$ref_page = $result[$ref_pos]["ref_page"];
				$ref_dl1 = $result[$ref_pos]["ref_dl1"];
				$ref_link1 = $result[$ref_pos]["ref_link1"];
				$ref_dl2 = $result[$ref_pos]["ref_dl2"];
				$ref_link2 = $result[$ref_pos]["ref_link2"];
				$ref_dl3 = $result[$ref_pos]["ref_dl3"];
				$ref_link3 = $result[$ref_pos]["ref_link3"];
				$ref_extra = $result[$ref_pos]["ref_extra"];
				$ref_id = $result[$ref_pos]["ref_id"];

				$reference_items .= "    <item>\n";

				$reference_items .= "      <title>" . clean_text_rss($ref_title) . "</title>\n";
				$reference_items .= "      <pubDate>" . full_datetime($ref_date) . "</pubDate>\n";

				$permalink = "//references&amp;ref_id=" . $ref_id;

				$reference_items .= "      <link>" . rootprefix($permalink, $conn, $style_code, $res_prefix) . "</link>\n";
				$reference_items .= "      <guid isPermaLink=\"true\">" . rootprefix($permalink, $conn, $style_code, $res_prefix) . "</guid>\n";

				$description = "";

				if ($ref_authors !== "") {
					$description .= $ref_authors . ", ";
				}
				if ($ref_title !== "") {
					$description .= $ref_title . ", ";
				}
				if ($ref_publication !== "") {
					$description .= $ref_publication . ", ";
				}
				if ($ref_location !== "") {
					$description .= $ref_location . ", ";
				}
				if ($ref_date_string !== "") {
					$description .= $ref_date_string . ". ";
				}
				if ($ref_extra !== "") {
					$description .= $ref_extra . ". ";
				}

				if ($ref_page !== "") {
					$description .= "<a href=\"" . $ref_page . "\">More info...</a>. ";
				}

				if (($ref_dl1 !== "") || ($ref_dl2 !== "") || ($ref_dl3 !== "")) {
					$comma = FALSE;
					$description .= "<br/>Download: ";
					if ($ref_dl1 !== "") {
						$description .= "<a href=\"" . $ref_link1 . "\">" . $ref_dl1 . "</a>";
						$comma = TRUE;
					}
					if ($ref_dl2 !== "") {
						if ($comma == TRUE) {
							$description .= ", ";
						}
						$description .= "<a href=\"" . $ref_link2 . "\">" . $ref_dl2 . "</a>";
						$comma = TRUE;
					}
					if ($ref_dl3 !== "") {
						if ($comma == TRUE) {
							$description .= ", ";
						}
						$description .= "<a href=\"" . $ref_link3 . "\">" . $ref_dl3 . "</a>";
						$comma = TRUE;
					}
					$description .= ".";
				}
				$reference_items .= "      <description>" . clean_text_rss(root($description, $conn, $style_code, $res_prefix, $human)) . "</description>\n";

				$reference_items .= "    </item>\n";

				$ref_count += 1;
			}
			$ref_pos += 1;
		}
	}
}

?>
