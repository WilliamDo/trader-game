#!/usr/bin/php
<?php

include("../library/session.php");
include("../library/database.php");

$query  = "SELECT x,y FROM ship WHERE username='$username'";
$result = pg_query($dbconn, $query);
$row = pg_fetch_row($result);

$x = $row[0];
$y = $row[1];

$query  = "SELECT * FROM battles WHERE opponent='$username' and challenger='".$_GET['challenge']."'";
$result = pg_query($dbconn, $query);
if (pg_num_rows($result) > 0) {
	echo "{$_GET['challenge']} has beaten you to it! {$_GET['challenge']} challenges YOU!";
	exit;
}

$query  = "INSERT into battles VALUES ('$username', '".$_GET['challenge']."', $x, $y)";
$result = pg_query($dbconn, $query);

$query  = "SELECT shipname FROM ship WHERE username='".$_GET['challenge']."'";
$result = pg_query($dbconn, $query);
$row = pg_fetch_row($result);

$ship = $row[0];

?>
<p>You have challenged <?=$_GET['challenge'];?>'s <?=$ship;?>.</p>
<p>A battle request has been sent to your opponent.</p>
<ul>
<li>if the captain accepts, the winner of the battle will take 60% of the loser's food and weapons, and 50% of their balance</li>
<li>if the captain declines, he/she can bribe you to withdraw your challenge with 30% of their food and weapons, and 25% of their balance</li>
</ul>

<p>If you move away from your current location, the challenge will be withdrawn.</p>