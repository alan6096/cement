<?php
	$dbname = "sapweb";

	$link = mysql_connect('localhost', '', '');
	if (!$link) {
	    die('Could not connect to MySQL server: ' . mysql_error());
	}
	
	$db_selected = mysql_select_db($dbname, $link);
	if (!$db_selected) {
	    die("Could not set $dbname: " . mysql_error());
	}
?>