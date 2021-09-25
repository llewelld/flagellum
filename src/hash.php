<?php

include "functions.php";

$to_hash = isset($_POST["tohash"]) ? sanitize($_POST["tohash"]) : "";

?>

<?xml version="1.0"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
  <title>Text hashing tool</title>
  <link rev="Made" href="http://www.flypig.co.uk/?page=email"/>
  <link rel="Copyright" href="http://www.flypig.co.uk/?page=copyright"/>
  <meta name="author" content="David Llewellyn-Jones"/>
  <meta name="description" content="A hash testing tool for creating hashes of text"/>
  <meta name="keywords" content="test, hash, sha-1, text"/>
  <meta name="robots" content="all" />
</head>
<body>

<div class="container">

<h1>Hash testing tool</h1>

<p/><h2>Result data</h2>

<p/>Result hex = <?= sha1($to_hash, FALSE) ?>
<p/>Result b64 = <?= base64_encode(sha1($to_hash, TRUE)) ?>
<p/>Result str = <?= sha1($to_hash, TRUE) ?>
<p/>Result in use = <?= pass_hash($to_hash) ?>

<p/><h2>Enter data</h2>
<form action="hash.php" method="post">

<p/>Text to hash: <input class="text" type=text name=tohash value="" size="40" maxlength="255"/>
<p><input class="submit" type=submit name="toss" value="Submit">
</form>

</body>
</html>

