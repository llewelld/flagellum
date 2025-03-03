<!DOCTYPE html SYSTEM "<?= $root ?>dtds/xhtml11-flat-disqus.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
  <title><?= $page_title ?></title>
  <link rev="Made" href="<?= $root ?>email" />
  <link rel="Start" href="<?= $res_prefix ?>" />
  <link rel="Up" href="<?= $res_prefix ?>" />
  <link rel="Prev" href="<?= $res_prefix ?>" />
  <link rel="Next" href="<?= $res_prefix ?>" />
  <link rel="Copyright" href="<?= $root ?>copyright" />
<?php
  for ($i = 0; $i < sizeof($styles); $i++) {
    if ($i == $style_val) {
      echo("  <link rel=\"stylesheet\" href=\"" . $res_prefix . "themes/" . $styles[$i] . ".css\" type=\"text/css\" title=\"" . $style_name[$i] . "\" media=\"screen\" />\n");
    }
    else {
      echo("  <link rel=\"alternate styleSheet\" href=\"" . $res_prefix . "themes/" . $styles[$i] . ".css\" type=\"text/css\" title=\"" . $style_name[$i] . "\" media=\"screen\" />\n");
    }
}
?>
  <link rel="stylesheet" href="<?= $res_prefix ?>themes/print.css" type="text/css" media="print" />

  <meta name="author" content="<?= $author ?>" />
  <meta name="description" content="<?= $description ?>" />
  <meta name="keywords" content="<?= $keywords ?>" />
  <meta name="fediverse:creator" content="<?= $fediversecreator ?>" />

  <meta property="og:url" content="<?= current_url() ?>" />
  <meta property="og:type" content="website" />
  <meta property="og:title" content="<?= $page_title ?>" />
  <meta property="og:description" content="<?= $description ?>" />
  <meta property="og:image" content="<?= $image ?>" />
  <meta property="og:author_name" content="<?= $author ?>" />

  <meta name="robots" content="all" />
  <meta name="viewport" content="initial-scale=1.0" />
<?php
if ($animate) {
  echo("<script type=\"text/javascript\" src=\"./animate/anim" . $animation . ".js\"></script>");
}
?>
  <!--[if gte IE 5.5000]>
  <script type="text/javascript" src="<?= $res_prefix ?>pngfix.js"></script>
  <![endif]-->
<?php
// Default is "shaders/wavylines.txt"
// Christmas decorations "shaders/snow.txt"

if ($shader_file_light != "") {
    if ($shader_file_light == $shader_file_dark) {
?>
  <script type="text/javascript" src="shaderback.js"></script>
  <script type="text/javascript">
  //shaderback.setDebug(true);
  shaderback.loadURL("shaders/<?= $shader_file_light ?>")
  </script>
<?php
    }
    else {
?>
  <script type="text/javascript" src="shaderback.js"></script>
  <script type="text/javascript">
  //shaderback.setDebug(true);

  const darkMode = window.matchMedia("(prefers-color-scheme:dark)");
  darkMode.addListener(changeMode);
  window.onload = changeMode(darkMode);

  function changeMode(darkMode) {
    if (darkMode.matches) {
      shaderback.loadURL("shaders/<?= $shader_file_dark ?>");
    }
    else {
      shaderback.loadURL("shaders/<?= $shader_file_light ?>");
    }
  }
  </script>
<?php
    }
}
?>
  <script type="text/javascript">
  function hoversubstitute() {
    // Do nothing
  }
  </script>
<?php
/*
  Add in the following three lines concatonated to one for snow
  <script type="text/javascript" src="<
  % = res_prefix %
  >extra/snow.js"></script>
*/
?>
  <script type="text/javascript" src="scripts/comments.js"></script>
</head>
<body>

<div class="container" onclick="hoversubstitute();">

<h1><span><?= $title ?></span></h1>

<div id="navbar">
<div class="top"><div class="bottom"><div class="left"><div class="right"><div class="topleft"><div class="topright"><div class="bottomleft"><div class="bottomright">

<h2>Location</h2>
<?= root($navbar_string, $conn, $style_code, $res_prefix, $human) ?>
<!-- <span class="hlink"><a href="H">Home</a></span> -->
</div></div></div></div></div></div></div></div>
</div>

<?php
if (sizeof($side_text) > 0) {
  echo("<div class=\"main\">");
}
else {
  echo("<div class=\"main_full\">");
}

for ($i = 0; $i < sizeof($main_text); $i++) {
?>

<div class="<?= $main_text_class[$i] ?>">
<div class="top"><div class="bottom"><div class="left"><div class="right"><div class="topleft"><div class="topright"><div class="bottomleft"><div class="bottomright">

<?= root($main_text[$i], $conn, $style_code, $res_prefix, $human) ?>

</div></div></div></div></div></div></div></div>
</div>
<?php
}
?>

</div>

<?php
if (sizeof($side_text) > 0) {
?>

<div class="sidebar">

<?php
for ($i = 0; $i < sizeof($side_text); $i++) {
?>

<div class="<?= $side_text_class[$i] ?>">
<div class="top"><div class="bottom"><div class="left"><div class="right"><div class="topleft"><div class="topright"><div class="bottomleft"><div class="bottomright">

<?= root($side_text[$i], $conn, $style_code, $res_prefix, $human) ?>

</div></div></div></div></div></div></div></div>
</div>
<?php
}
?>
</div>
<?php
}
?>
<div id="footer">
<!-- <div id="copyright">&copy; Copyright David Llewellyn-Jones 1998-2021</div> -->
</div>
</div>
<div id="animate" style="position:absolute;left:0px;top:0px;">
</div>
</body>
</html>

