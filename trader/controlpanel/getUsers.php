#!/usr/bin/php
<?php

require_once("../library/session.php");
require_once("../library/database.php");

header('Content-Type: text/xml');

$query  = "SELECT username,online,lastseen FROM users ORDER BY username"; //WHERE username<>'$username'
$result = pg_query($dbconn, $query);

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n\n";
echo "<users>\n";

while ($row = pg_fetch_row($result)) {

	// Remove user's online flag if they have been inactive for 15 minutes
	$online = $row[1];
	
	if($online == "t") {
		$lastseen = strtotime($row[2]);
		if (($lastseen + 15*60) < time()) {
			echo "<!-- cond -->";
			$query      = "UPDATE users SET online=false WHERE username='$row[0]'";
			$new_result = pg_query($dbconn, $query);
	
			$online = "f";
		}
	}

	if($username == $row[0]) {
		if ($online == "f") {
			echo "<timedout>true</timedout>\n";
			echo "</users>";
			exit;
		}

		continue;	// Don't output own online status
	}

	echo "\t<user>\n";
	echo "\t\t<username>$row[0]</username>\n";
	echo "\t\t<online>$online</online>\n";
	echo "\t</user>\n";
}

echo "</users>";

?>