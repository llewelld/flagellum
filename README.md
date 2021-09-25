# flagellum

Flagellum is a simple website content management system built around pages structured with lists.

## Overview

The key idea behind Flagellum is that each page is constructed from a main header section, followed by a series of lists.

List contents are specified using tags, so that all items matching a given tag will be displayed chronologically in the list. This allows list items to appear on multiple different pages based on the tags assigned to the list item and the page.

Each page supports three different list types:

1. Unstructured text (e.g. blog posts).
2. Software releases (e.g. app downloads).
3. References (e.g. publications or presentations).

Each page must contain a section of header text, plus any combination of the list types (including none).

Private pages, pages requiring CAPTCHA access, theming, RSS feeds and Disquis comments are also supported.

This arrangements allows complex, multi-page, hierarchical, highly-structured websites to be managed flexibly and easily.

## Configuration

All of the local configuration values can be found in `src/config/config.php`. The file looks like this in its default form, however, you should expect to change the majority of the values. A short summary of each option follows.

```
<?php
$CONFIG = array(
	'title' => 'ExampleSite',
	'root' => 'https://www.example.com/',
	'dbhost' => '127.0.0.1',
	'dbuser' => 'mysql_username',
	'dbpassword' => 'mysql_password',
	'dbname' => 'mysql_databasename',
	'botstopkey' => '0123456789ABCDEF0123456789ABCDEF',
	'botstopsalt' => 'aBcDeFgHiJkLmNo012',
	'passwordsalt' => '012AbCdEfGhIjKlMnO',
	// simplify_urls: 0 - none; 1 - reduce to page; 2 - rewrite page as folder
	'simplify_urls' => 2,
);
```

title : This is the title of your site that will appear in the site metadata amongst other places. Try to make this understandable for humans.

root: This is the web site root address. Links will redirect to this, so it's important that it matches your site's URL. It should end with a `/` character.

**`dbhost`:** This should be the hostname or IP address of your MySQL (or compatible) database server.

**`dbuser`:** This is the username which will be used to authenticate to the MySQL instance.

**`dbpassword`:** This is the password which will be used to authenticate to the MySQL instance.

**`dbnae`:** This is the name of the database to use on the MySQL server.

**`botstopkey`:** The BotStop CAPTCHA uses this key to hide the correct response in the URL. You should change it to some other random 16-byte hexidecimal number. It should be kept secret. It can be safely changed if exposed.

**`botstopsalt`:** Used by BotStop to communicate with itself via the public URL. Change it to a random string. This should be kept secret. It can be safely changed if exposed.

**`passwordsalt`:** Used as the salt for storing passwords in the database. Change it to some random string. Changing this value will cause all passwords to become invalidated.

**`simplify_urls`**: Numerical option used to simplify URLs used in the address bar. Set to 0 for the full URL to be shown; set to 1 to reduce the URL to just the page value; set to 2 to rewrite the page to appear as a root folder after the URL.

## Licence and Contact

Released under an MIT licence. See the LICENSE file.

Copyright 2006-2021 David Llewellyn-Jones

Components (some have other licences):

1. Flagellum: https://github.com/llewelld/flagelum
2. Botstop: https://www.flypig.org.uk/svn/repos/Home/Botstop
3. Shaderback: https://www.flypig.co.uk/shaderback
4. CKEditor 4: https://ckeditor.com/ckeditor-4/
5. MathJax: https://www.mathjax.org/
6. SyntaxHighlighter: https://github.com/syntaxhighlighter/syntaxhighlighter

More info: https://www.flypig.co.uk

Contact: https://www.flypig.co.uk/email

