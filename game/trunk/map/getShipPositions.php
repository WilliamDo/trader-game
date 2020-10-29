<?php

require_once("../library/session.php");
require_once("../library/database.php");

header('Content-Type: text/xml');

$query  = "SELECT username,shiptype,x,y FROM ship";
$result = pg_query($dbconn, $query);

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n\n";
echo "<ships>\n";

while ($row = pg_fetch_row($result)) {
	if($username == $row[0] && $_GET['displayOwn'] != "true") {
		continue;
	}

	echo "\t<ship>\n";
	echo "\t<username>$row[0]</username>\n";
	echo "\t<shiptype>$row[1]</shiptype>\n";
	echo "\t<x>$row[2]</x>\n";
	echo "\t<y>$row[3]</y>\n";

	echo "\t</ship>\n";
}

// Do message checking also

$query  = "SELECT unread FROM messages WHERE receiver='$username' and unread=true";
$result = pg_query($dbconn, $query);

if (pg_num_rows($result) > 0) {
	echo "\t<unread>true</unread>\n";
}

echo "</ships>";

?>