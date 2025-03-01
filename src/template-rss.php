<rss version="2.0"><?php
$description = $list_description;
if ($reference_description !== "") {
	if ($description !== "") {
		if ($download_description !== "") {
			$description .= ", ";
		}
		else {
			$description .= " and ";
		}
	}
	$description .= $reference_description;
}

if ($download_description !== "") {
	if ($description !== "") {
		$description .= " and ";
	}
	$description .= $download_description;
}
$description .= " from flypig\'s website.";
$description = clean_text_rss(root($description, $conn, $style_code, $res_prefix, $human));
?>
	<channel>
		<title><?= $title ?> - <?= root($page_title, $conn, $style_code, $res_prefix, $human) ?></title>
		<link><?= rootprefix("//" . $page_name, $conn, $style_code, $res_prefix) ?></link>
		<description><?= $description ?></description>
		<language>en-gb</language>
		<webMaster><?= $root ?>?page=email</webMaster>
		<managingEditor><?= $root ?>?page=email</managingEditor>
		<generator>Flagellum</generator>
<?= $list_items . $reference_items . $download_items ?></channel>
</rss>

