<?php

require_once("../library/database.php");

header('Content-Type: text/xml');

$query  = "SELECT number,name,x,y FROM port";
$result = pg_query($dbconn, $query);

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n\n";
echo "<ports>\n";

while ($row = pg_fetch_row($result)) {
	echo "\t<port>\n";
	echo "\t<portnumber>$row[0]</portnumber>\n";
	echo "\t<portname>$row[1]</portname>\n";
	echo "\t<x>$row[2]</x>\n";
	echo "\t<y>$row[3]</y>\n";
	echo "\t</port>\n";
}

echo "</ports>";
?>