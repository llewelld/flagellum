<?php

include "init.php";

$this_script_url = $_SERVER["SCRIPT_NAME"];

$expires = time() - 60;
setcookie("user[user]", "", $expires);
setcookie("user[pass]", "", $expires);

header("Location: " . rootredirect("//", $conn, $style_code));

include "fin.php";

?>
