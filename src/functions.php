<?php

$email_address = "//email";
$mangle_const_email = 347611;
$mangle_const_activate = 82801;

function sanitize($input) {
	return preg_replace("/[^a-zA-Z0-9@.\-_+! ]/", "", $input);
}

function convert_date($date) {
	if (isset($date) && ($date != "")) {
		$date_obj = strtotime($date);
		if ($date_obj != FALSE) {
			# 2020/01/31
			$date_ret = date("Y/m/d", $date_obj);
			return $date_ret;
		}
	}
	return "----/--/--";
}

function convert_time($date) {
	if (isset($date) && ($date != "")) {
		$date_obj = strtotime($date);
		if ($date_obj != FALSE) {
			# 2020/01/31 19:00:01
			$date_ret = date("Y/m/d H:i:s", $date_obj);
			return $date_ret;
		}
	}
	return "----/--/-- --:--:--";
}

// $date is epoch
function short_date($date) {
	# 1 Jan 2020
	return date("j M Y", $date);
}

// $date is epoch
function full_datetime($datetime) {
	# RFC 2822 formatted date
	# Sat Jan 25 18:11:42 UTC 2020
	return date("r", $datetime);
}

function expires_date($date) {
	if (isset($date) && ($date != "")) {
		$date_obj = strtotime($date);
		if ($date_obj != FALSE) {
			# Jan 1 2020
			$date_ret = date("M j Y", $date_obj);
			return $date_ret;
		}
	}
	return "";
}

function long_date($date) {
	if (isset($date) && ($date != "")) {
		$date_obj = strtotime($date);
		if ($date_obj != FALSE) {
			# 1 January 2020
			$date_ret = date("j F Y", $date_obj);
			return $date_ret;
		}
	}
	return "";
}

function epoch_to_db_date($epoch) {
	if (isset($epoch) && is_numeric($epoch)) {
		# 2020/01/31
		$date_ret = date("Y/m/d", $epoch);
		return $date_ret;
	}
	return "----/--/--";
}

function epoch_to_db_datetime($epoch) {
	if (isset($epoch) && is_numeric($epoch)) {
		# 2020/01/31 15:36:12
		$datetime_ret = date("Y/m/d H:i:s", $epoch);
		return $datetime_ret;
	}
	return "----/--/-- --:--:--";
}

function clean_text($text) {
	$result = $text;

	$result = preg_replace("/\'/", "''", $result);
	$result = preg_replace("/</", "&lt;", $result);
	$result = preg_replace("/>/", "&gt;", $result);

	return $result;
}

function clean_text_for_input($text) {
	$result = $text;

	$result = preg_replace("/&/", "&amp;", $result);
	$result = preg_replace("/\"/", "&quot;", $result);
	$result = preg_replace("/</", "&lt;", $result);
	$result = preg_replace("/>/", "&gt;", $result);

	return $result;
}

function clean_text_rss($text) {
	$result = $text;

	$result = preg_replace("/&/", "&amp;", $result);
	$result = preg_replace("/</", "&lt;", $result);
	$result = preg_replace("/>/", "&gt;", $result);

	return $result;
}

function message_display($text) {
	$result = $text;

	$result = preg_replace("/\n/", "<br>", $result);
	$result = preg_replace("/(http|https|ftp):\/\/(\S*.\S*)/g", "<a href=\"$1:\/\/$2\">$1:\/\/$2<\/a>", $result);
	$result = preg_replace("/(\s)www.(\S*.\S*)/g", "$1<a href=\"http://www.$2\">www.$2<\/a>", $result);

	return $result;
}

function mangle_id($id, $constant) {
	return (($id * 10) + $constant) ^ 8236;
}

function demangle_id($id, $constant) {
	return (($id ^ 8236) - $constant) / 10;
}

function guess_date($date) {

	$date_obj = FALSE;
	if (isset($date) && ($date != "")) {
		$date_obj = strtotime($date);

		if ($date_obj == FALSE) {
			$date_obj = time();
			$month = array("jan", "feb", "mar", "apr", "may", "jun", "jul", "aug", "sep", "oct", "nov", "dec");
			$date_month = (int)date("n", $date_obj);
			$date_year = (int)date("Y", $date_obj);
			$date_day = (int)date("j", $date_obj);
			$date_hour = (int)date("H", $date_obj);
			$date_minute = (int)date("i", $date_obj);
			$date_second = (int)date("s", $date_obj);

			$date = strtolower($date);

			for ($i = 0; $i < sizeof($month); $i++) {
				if (strpos($date, $month[$i]) != FALSE) {
					$date_month = $i + 1;
				}
			}

			if (preg_match("/\s\d\d\d\d/", $date, $matches) != FALSE) {
				$date_year = (int)($matches[0]);
			}

			if (preg_match("/\d\s|\d\d\s/", $date, $matches) != FALSE) {
				$date_day = (int)($matches[0]);
			}

			$date_obj = mktime($date_hour, $date_minute, $date_second, $date_month, $date_day, $date_year);
		}
	}

	return $date_obj;
}

function archive_date_to_string($year, $month) {
	return sprintf("%04d%02d", $year, $month);
}

/* Converts internal URLs in some HTML into external URLs
 *
 * This parses a string (for example, pulled from the database) and converts
 * links it contains in the internal format into links that can be used on the
 * HTML of the site.
 *
 * It searches for strings in the following form
 * <a href="<link>"
 * action="<link>"
 * src="<link>"
 *
 * Where <link> is of the form:
 * 1. //<path>
 * 2. /root<path>
 * 3. /<page><parameters>
 *
 * Cases 1 and 2 convert into <domain><path>. For example <domain> could be
 * http://www.flypig.co.uk and <path> could be /one/two/three.html
 *
 * Case 3 converts into <domain>?page=<page>&amp;a=b&amp;c=d.. as taken from
 * the Pages database and <parameters> is given and of the form
 * &amp;e=f&amp;g=h...
 *
*/
function root($href, $conn, $style_code, $res_prefix, $human) {
	global $CONFIG;

	$result = "";
	$index = 0;
	$element_len = 0;
	$previndex = 0;
	$subindex = 0;
	$i = 0;
	$page = "";
	$page_full = "";
	$url_rel = "";
	$url_extra = "";

	$elements = array(
		"<a href=\"//",
		" action=\"//",
		" src=\"//"
	);

	while ($index !== FALSE) {
		$index_next = FALSE;
		$element_len = 0;
		for ($element = 0; $element < sizeof($elements); $element++) {
			$pos = strpos($href, $elements[$element], $index);
			if (($pos !== FALSE) && ($index_next === FALSE || $pos < $index_next)) {
				$index_next = $pos;
				$element_len = strlen($elements[$element]);
			}
		}
		$index = $index_next;

		if ($index !== FALSE) {
			// We've found the start
			$result .= substr($href, $previndex, $index + $element_len - 2 - $previndex);
			// Now find the end
			$i = $index + $element_len;
			while (($i < strlen($href)) && (ord($href[$i]) != 39)
				&& (ord($href[$i]) != 34)) {
				$i++;
			}
			$page_full = substr($href, $index + $element_len, $i - ($index + $element_len));

			$i = $index + $element_len;
			while (($i < strlen($href)) && (ord($href[$i]) > 47)) {
				$i++;
			}
			$page = substr($href, $index + $element_len, $i - ($index + $element_len));

			// Add extra URL elements
			if (($style_code != 0) && (stripos($page_full, "style=") == FALSE)) {
				$url_extra = "style=" . $style_code . "&amp;";
			}
			else {
				$url_extra = "";
			}

			if ($page === "" || $page === "root") {
				$result .= $res_prefix;
				if (ord($href[$i]) == 47) {
					$i += 1;
				}
			}
			else {
				// Now find the URL for this page
				$root_sql = $conn->prepare("SELECT page_url_rel,page_botstop FROM Pages where page_name = :page");
				$root_sql->bindParam(':page', $page);
				$root_sql->execute();
				$root_rs = $root_sql->fetchAll();

				if (sizeof($root_rs) > 0) {
					if (($root_rs[0]["page_botstop"] == TRUE) && ($human != TRUE)) {
						$result .= $res_prefix . "botstop.php?" . $url_extra . "page=" . $page;
					}
					else {
						$url_rel = $root_rs[0]["page_url_rel"];
						if (substr($url_rel, 0, 2) == "//") {
							switch ($CONFIG['simplify_urls']) {
								case 1:
									// Don't rewrite the URL in full
									$result .= $res_prefix . "?to=" . $page;
									if ($url_extra != "" || $page != $page_full) {
										$result .= "&" . $url_extra;
									}
									break;
								case 2:
									// Use folders instead of pages
									$result .= $res_prefix;
									if ($page != "home") {
										$result .= $page;
									}
									if ($url_extra != "" || $page != $page_full) {
										$result .= "?" . $url_extra;
									}
									break;
								default:
									// Rewrite the URL in full
									$result .= $res_prefix . "?" . $url_extra . "page=" . substr($url_rel, 2);
									break;
							}
						}
						else {
							$result .= $url_rel;
							if ((substr($url_rel, 0, 2) == "./") && (stripos($page_full, "style=") != FALSE) && ($style_code != 0)) {
								if (strpos($page, "?") == FALSE) {
									$result .= "?";
								}
								else {
									$result .= "&amp;";
								}
								$result .= "style=" + $style_code;
							}
						}
					}
				}
				else {
					$result .= $res_prefix . "?" . $url_extra . "page=" . $page;
				}
			}

			$index = $i;
			$previndex = $index;
		}
	}
	$result .= substr($href, $previndex);

	//$result = preg_replace ("/<a href=\"\/\//g", "<a href=\"./?page=", $href);
	return $result;
}

function rootredirect($href, $conn, $style_code) {
	$result = "";
	$i = 0;
	$page = "";
	$url_rel = "";

	if ($href == "//") {
		$result = "//home";
	}
	else {
		$result = $href;
	}

	if (($style_code != 0) && (stripos($result, "style=") == FALSE)) {
		$result .= "&amp;style=" . $style_code;
	}

	if (substr($result, 0, 2) == "//") {
		$i = 2;
		while (($i < strlen($result)) && (ord($result[$i]) > 39)) {
			$i++;
		}
		$page = substr($result, 2, $i - 2);
		$tail = substr($result, $i);

		// Now find the URL for this page
		$root_sql = $conn->prepare("SELECT page_url_rel FROM Pages where page_name = :page");
		$root_sql->bindParam(':page', $page);
		$root_sql->execute();
		$root_rs = $root_sql->fetchAll();

		if (sizeof($root_rs) > 0) {
			$url_rel = $root_rs[0]["page_url_rel"];
			if (substr($url_rel, 0, 2) == "//") {
				$result = "./?page=" . substr($url_rel, 2) . substr($result, $i);
			}
			else {
				$result = $url_rel . substr($result, $i);
			}
		}
		else {
			$result = "./?page=" . $result;
		}
	}

	$result = preg_replace ("/&amp;/", "&", $result);

	return $result;
}

function botstop_hash($string) {
	return sha1($string, FALSE);
}

function pass_hash($string) {
	return sha1($string, FALSE);
}

function rootprefix($href, $conn, $style_code, $res_prefix) {
	global $CONFIG;

	$result = "";
	$i = 0;
	$page = "";
	$url_rel = "";

	if ($href == "//") {
		$result = "//home";
	}
	else {
		$result = $href;
	}

	if (($style_code != 0) && (stripos($result, "style=") == FALSE)) {
		$result .= "&amp;style=" . $style_code;
	}

	if (substr($href, 0, 2) == "//") {
		$i = 2;
		while (($i < strlen($href)) && (ord($href[$i]) > 47)) {
			$i++;
		}
		$page = substr($result, 2, $i - 2);
		$tail = substr($result, $i);

		// Now find the URL for this page
		$root_sql = $conn->prepare("SELECT page_url_rel FROM Pages where page_name = :page");
		$root_sql->bindParam(':page', $page);
		$root_sql->execute();
		$root_rs = $root_sql->fetchAll();

		if (sizeof($root_rs) > 0) {
			$url_rel = $root_rs[0]["page_url_rel"];
			if (substr($url_rel, 0, 2) == "//") {
				switch ($CONFIG['simplify_urls']) {
					case 1:
						// Don't rewrite the URL in full
						$result = $res_prefix . "?to=" . $page . substr($result, $i);
						break;
					case 2:
						// Use folders instead of pages
						$result = $res_prefix . $page . "?" . substr($result, $i + 5);
						break;
					default:
						// Rewrite the URL in full
						$result = $res_prefix . "?page=" . substr($url_rel, 2) . substr($result, $i);
						break;
				}
			}
			else {
				$result = $url_rel . substr($result, $i);
			}
		}
		else {
			$result = $res_prefix . "?page=" . $page;
		}
	}

	return $result;
}

function parse_email_address($address) {
	$result = FALSE;

	$atpos = strpos($address, "@");
	if (($atpos < 1) || (strpos($address, ".", $atpos) < ($atpos + 2))) {
		$result = TRUE;
	}

	return $result;
}

function check_number($test) {
	return is_numeric($test);
}

function current_url() {
	$protocol = "";
	$port = "";
	$post = "";
	$url = "";
	$query = "";
	$script = "";
	$server = "";

	$protocol = strtolower($_SERVER["SERVER_PROTOCOL"]);
	$protocol = substr($protocol, 0, strpos($protocol, "/"));

	if (isset($_SERVER["HTTPS"])) {
		$protocol .= "s";
	}
	$port = $_SERVER["SERVER_PORT"];
	if ($port == "80") {
		$port = "";
	}
	else {
		$port = ":" . $port;
	}

	$query = $_SERVER["QUERY_STRING"];
	if ($query != "") {
		$query = "?" . $query;
	}

	$script = $_SERVER["SCRIPT_NAME"];
	if ($script == "/index.php") {
		$script = "/";
	}

	$server = $_SERVER["SERVER_NAME"];

	$url = $protocol . "://" . $server . $port . $script . $query;

	return $url;
}

function generate_id($page, $list, $list_id, $ref, $ref_id, $dnload, $dnload_id) {
	$id = "";

	$id = "page=" . $page;
	$id .= append_defined ("list", $list);
	$id .= append_defined ("list_id", $list_id);
	$id .= append_defined ("ref", $ref);
	$id .= append_defined ("ref_id", $ref_id);
	$id .= append_defined ("dnload", $dnload);
	$id .= append_defined ("dnload_id", $dnload_id);

	return $id;
}

function append_defined($name, $value) {
	$append = "";

	if (($value != "") && ($value != -1)) {
		$append = '&amp;' . $name . "=" . $value;
	}

	return $append;
}

?>
