#!/usr/bin/php
<?php

require_once("../library/database.php");

header('Content-Type: text/xml');

$query  = "SELECT name,food,upgrade,repair,weapons FROM port WHERE number='".$_GET['port']."'";
$result = pg_query($dbconn, $query);
$row = pg_fetch_row($result);

$services = "";

if ($row[1] == "t") { $services .= "buy food for your crew, "; }
if ($row[2] == "t") { $services .= "upgrade your ship, "; }
if ($row[3] == "t") { $services .= "repair your ship, "; }
if ($row[4] == "t") { $services .= "buy weapons for defense in battle, "; }

$services = substr($services, 0, -2);

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n\n";
echo "<port>\n";
echo "\t<name>$row[0]</name>\n";
echo "\t<services>$services</services>\n";
echo "</port>";

?>