<?php
$this_script_url = $_SERVER["SCRIPT_NAME"];
$res_prefix = './';
$style_code = isset($_GET["style"]) ? $_GET["style"] : "";
$style_val = intval($style_code);
$styles = array("bokeh", "crisp", "print", "ljmu-dream-plus", "ljmu-plan-plus", "ljmu-achieve-plus", "nodiad", "ljmu-dream", "ljmu-plan", "ljmu-achieve", "nistl", "style", "onyx", "serenitatis", "sketch", "sketch-constrained", "bokeh");
$style_name = array("Default (Bokeh)", "Crisp", "Print", "LJMU Plus Dream", "LJMU Plus Plan", "LJMU Plus Achieve", "Nodiad", "LJMU Dream", "LJMU Plan", "LJMU Achieve", "NISTL", "Embossed", "Onyx", "Serenitatis", "Sketch", "Sketch Constrained", "Bokeh");
$animate = FALSE;
$append = "";
$appendinit = "";
$appendpost = "";
$title = $CONFIG['title'];
$root = $CONFIG['root'];

$user_username = "";
$user_password = "";
$user_name = "";
$user_email = "";
$user_type = "none";
$user_uid = "";
$ses_username = isset($_COOKIE["user"]["user"]) ? $_COOKIE["user"]["user"] : "";
$ses_password = isset($_COOKIE["user"]["pass"]) ? $_COOKIE["user"]["pass"] : "";
$human = FALSE;
$human_salt = $CONFIG['botstopsalt'];
$human_ipadd = "";
$code_salt = 'slkdf34gj';
$animation = 0;
$shaderback = 0;
$shaders = array("", "wavylines.txt", "bokeh.txt");
//$shaders = array("", "wavylines.txt", "snowflakes.txt");
//$shaders = array("", "wavylines.txt", "blink.txt");
//$shaders = array("", "wavylines.txt", "fireworks.txt");
$shader_file = "";
//$today = date();

$human_ipadd = $_SERVER['REMOTE_ADDR'];
if (isset($_COOKIE["botstop"]["human"]) && $_COOKIE["botstop"]["human"] == botstop_hash($human_ipadd . $human_salt)) {
	$human = TRUE;
}

if (!check_number($style_code)) {
	$style_val = 0;
	$style_code = 0;
}

if ($style_val < 0) {
	$style_val = 0;
	$style_code = 0;
}

$animate = FALSE;
// The following 1 line is the standard animation code
//$animation = (0 + round(time() / (17 * 60 * 60 * 1000))) % 10;

// The following 4 lines are for producing the Christmas animations
$animation = ((0 + floor((rand() / getrandmax()) * 4)) + 1) * 2;
if ($animation == 8) {
	$animation = 10;
}
// End of Christmas animation code

if ($style_val & 0x200) {
	$style_val &= ~0x200;
}
else {
	switch ($style_val) {
	case 0: // Default style
		$shaderback = 2;
		break;
	case 12: // Onyx
	case 13: // Serenitatis
		$animate = TRUE;
		break;
	case 14: // Sketch
	case 15: // Sketch Fixed Width
		$shaderback = 1;
		break;
	case 16: // Bokeh
		$shaderback = 2;
		break;
	}
}

$shader_file = $shaders[$shaderback];

// The following 4 lines are for producing the Halloween animations
//if (($style_val == 0) && ($animate == FALSE)) {
//	$animation = 11;
//	$animate = TRUE;
//}
// End of Halloween animation code

if ($style_val >= sizeof($styles)) {
	$style_val = 0;
	$style_code = 0;
}

$style = $styles[$style_val];

if ($style_code > 0) {
	$append .= "&amp;style=" . $style_code;
	$appendinit .= "?style=" . $style_code;
	$appendpost .= "style=" . $style_code;
}

// Prepare SQL and bind paramters
$stmt = $conn->prepare("SELECT * FROM Users where user_username = :username");
$stmt->bindParam(':username', $ses_username);
$stmt->execute();
$result = $stmt->fetchAll();

if (sizeof($result) > 0) {
	if ($ses_password == $result[0]["user_password"]) {
		$user_username = $result[0]["user_username"];
		$user_password = $result[0]["user_password"];
		$user_name = $result[0]["user_name"];
		$user_email = $result[0]["user_email"];
		$user_type = $result[0]["user_type"];
		$user_uid = $result[0]["user_uid"];
	}
}

// The following section is commented out until CSS for XHTML proper is 
// sorted out
/*
if ($_SERVER["HTTP_ACCEPT"].strpos('application\/xhtml+xml') != FALSE) {
  Response.ContentType = "application/xhtml+xml";

	header('Content-type:application/xhtml+xml;charset=utf-8');
}
else {
	header('Content-type:text/html;charset=utf-8');
}
*/

header('Permissions-Policy: interest-cohort=()');

?>
