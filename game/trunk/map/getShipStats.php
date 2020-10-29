<?php

require_once("../library/database.php");

header('Content-Type: text/xml');

$query  = "SELECT shipname,shiptype,crew,number,health FROM ship WHERE username='".$_GET['user']."'";
$result = pg_query($dbconn, $query);
$row = pg_fetch_row($result);

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n\n";
echo "<stats>\n";
echo "\t<shipname>$row[0]</shipname>\n";
echo "\t<owner>".$_GET['user']."</owner>\n";
echo "\t<shiptype>$row[1]</shiptype>\n";
echo "\t<crew>$row[2]</crew>\n";
echo "\t<health>$row[4]</health>\n";

if($row[3] != null) {
	$query  = "SELECT name FROM port WHERE number=$row[3]";
	$result = pg_query($dbconn, $query);
	$row = pg_fetch_row($result);
	echo "\t<port>$row[0]</port>\n";
}
else {
	echo "\t<port>none</port>\n";
}

echo "</stats>";

?>