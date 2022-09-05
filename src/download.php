<?php

$software = isset($_GET["dnload"]) ? sanitize($_GET["dnload"]) : "";
$sw_id = isset($_GET["dl_id"]) ? (int)sanitize($_GET["dl_id"]) : -1;
$sw_main = sizeof($main_text);
$comma = false;

if (($software != "") || ($sw_id >= 0)) {
  $main_text_class[$sw_main] = "maindownloads";

  $main_text[$sw_main] = "<h2>Download</h2>\n";
  if ($software != "") {
    $sql = $conn->prepare("SELECT * FROM Software where software_keywords LIKE :software ORDER BY software_date DESC");
    $software_wildcard = "%" . $software . "%";
		$sql->bindParam(':software', $software_wildcard);
  }
  else {
    $sql = $conn->prepare("SELECT * FROM Software where software_id = :sw_id");
		$sql->bindParam(':sw_id', $sw_id);
  }
	$sql->execute();
	$result = $sql->fetchAll();

  if (sizeof($result) <= 0) {
    $main_text[$sw_main] .= "<p>Sorry. There is no software that matches the search criteria provided.</p>\n";
  }
  else {
    $main_text[$sw_main] .= "<div class=\"software\"><ul class=\"software\">\n";

		$sw_pos = 0;
    while ($sw_pos < sizeof($result)) {
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

      $main_text[$sw_main] .= "<li class=\"download\"><span class=\"dl_title\">" . $sw_title . "</span>\n";
      if ($sw_icon != "") {
        $main_text[$sw_main] .= "<img alt=\"" . $sw_title . "\" src=\"" . $sw_icon . "\" class=\"float_right\"/>\n";
      }
      if (($sw_version != "") || ($sw_date_string != "")) {
        $main_text[$sw_main] .= "<br/><span class=\"dl_version\">Version " . $sw_version . " (" . $sw_date_string . ")";
        if ($sw_os != "") {
          $main_text[$sw_main] .= " for " . $sw_os;
        }
        $main_text[$sw_main] .= ".</span>\n";
      }
      else {
        if ($sw_os != "") {
          $main_text[$sw_main] .= "<br/>For " . $sw_os . ".</span>\n";
        }
      }

      $main_text[$sw_main] .= "<br/><span class=\"description\">" . $sw_description;
      if ($sw_page != "") {
        $main_text[$sw_main] .= " <a href=\"" . $sw_page . "\">More info...</a>\n";
      }
      $main_text[$sw_main] .= "</span>\n";

      if (($sw_bin != "") || ($sw_src != "") || ($sw_screenshot != "")) {
        $comma = FALSE;
        $main_text[$sw_main] .= "<br/><span class=\"dl_files\">Download: ";
        if ($sw_bin != "") {
          $main_text[$sw_main] .= "<a href=\"" . $sw_bin . "\">binary</a>";
          $comma = TRUE;
        }
        if ($sw_src != "") {
          if ($comma == TRUE) {
            $main_text[$sw_main] .= ", ";
          }
          $main_text[$sw_main] .= "<a href=\"" . $sw_src . "\">source</a>";
          $comma = TRUE;
        }
        if ($sw_screenshot != "") {
          if ($comma == TRUE) {
            $main_text[$sw_main] .= ", ";
          }
          $main_text[$sw_main] .= "<a href=\"" . $sw_screenshot . "\">screenshot</a>";
          $comma = TRUE;
        }
        $main_text[$sw_main] .= ". </span>\n";
      }

      if ($user_type == "admin") {
        $main_text[$sw_main] .= "<br /><a href=\"./editdl.php?dl_id=" . $sw_id . $append . "\">[Edit]</a> <a href=\"./deldl.php?dl_id=" . $sw_id . $append . "\">[Delete]</a>.\n";
      }

      $main_text[$sw_main] .= "</li>\n";
      $sw_pos++;
    }
    $main_text[$sw_main] .= "</ul></div>\n";

  }

  if ($user_type == "admin") {
    $main_text[$sw_main] .= "<p><a href=\"./newdl.php?dnload=" . $software . $append . "\">[New]</a>\n";
  }
}

?>

