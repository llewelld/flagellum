<?php

$reference = isset($_GET["ref"]) ? sanitize($_GET["ref"]) : "";
$ref_id = isset($_GET["ref_id"]) ? (int)sanitize($_GET["ref_id"]) : -1;
$ref_num = isset($_GET["ref_num"]) ? sanitize($_GET["ref_num"]) : -1;
$ref_main = sizeof($main_text);
$comma = FALSE;
$ref_count = 0;

if (($ref_num == "") || ($ref_num == "")) {
	$ref_num = -1;
}

if (($reference != "") || ($ref_id != -1)) {
	$main_text_class[$ref_main] = "mainrefs";

	$main_text[$ref_main] = "<h2>References</h2>\n";
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

	if (sizeof($result) <= 0) {
		$main_text[$ref_main] .= "<p>Sorry. There are no references that match the search criteria provided.</p>\n";
	}
	else {
		$main_text[$ref_main] .= "<div class=\"references\"><ul class=\"references\">\n";

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

				$main_text[$ref_main] .= "<li class=\"reference\">";
				if ($ref_authors != "") {
					$main_text[$ref_main] .= "<span class=\"ref_authors\">" . $ref_authors . "</span>, \n";
				}
				if ($ref_title != "") {
					$main_text[$ref_main] .= "<span class=\"ref_title\">" . $ref_title . "</span>, \n";
				}
				if ($ref_publication != "") {
					$main_text[$ref_main] .= "<span class=\"ref_publication\">" . $ref_publication . "</span>, \n";
				}
				if ($ref_location != "") {
					$main_text[$ref_main] .= "<span class=\"ref_location\">" . $ref_location . "</span>, \n";
				}
				if ($ref_date_string != "") {
					$main_text[$ref_main] .= "<span class=\"ref_date\">" . $ref_date_string . "</span>. \n";
				}
				if ($ref_extra != "") {
					$main_text[$ref_main] .= "<span class=\"ref_extra\">" . $ref_extra . "</span>. \n";
				}

				if ($ref_page != "") {
					$main_text[$ref_main] .= " <span class=\"ref_page\"><a href=\"" . $ref_page . "\">More info...</a></span>. \n";
				}

				if (($ref_dl1 != "") || ($ref_dl2 != "") || ($ref_dl3 != "")) {
					$comma = FALSE;
					$main_text[$ref_main] .= "<br/><span class=\"ref_files\">Download: ";
					if ($ref_dl1 != "") {
						$main_text[$ref_main] .= "<a href=\"". $ref_link1 . "\">" . $ref_dl1 . "</a>";
						$comma = TRUE;
					}
					if ($ref_dl2 != "") {
						if ($comma == TRUE) {
							$main_text[$ref_main] .= ", ";
						}
						$main_text[$ref_main] .= "<a href=\"" . $ref_link2 . "\">" . $ref_dl2 . "</a>";
						$comma = TRUE;
					}
					if ($ref_dl3 != "") {
						if ($comma == TRUE) {
							$main_text[$ref_main] .= ", ";
						}
						$main_text[$ref_main] .= "<a href=\"" . $ref_link3 . "\">" . $ref_dl3 . "</a>";
						$comma = TRUE;
					}
					$main_text[$ref_main] .= ". </span>\n";
				}

				if ($user_type == "admin") {
					$main_text[$ref_main] .= "<br /><a href=\"./editref.php?ref_id=" . $ref_id . "\">[Edit]</a> <a href=\"./delref.php?ref_id=" . $ref_id . "\">[Delete]</a>.\n";
				}

				$main_text[$ref_main] .= "</li>\n";

				$ref_count += 1;
			}
			$ref_pos += 1;
		}
		$main_text[$ref_main] .= "</ul></div>\n";

	}

	if ($user_type == "admin") {
		$main_text[$ref_main] .= "<p><a href=\"./newref.php?ref=" . $reference . "\">[New]</a>\n";
	}
}

?>
