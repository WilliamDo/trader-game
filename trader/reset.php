<?
require_once("library/session.php");
require_once("library/database.php");

$randomport = rand(0,49);
$query = "SELECT x,y,number FROM portlocation ORDER BY RANDOM() LIMIT 1";
$result = pg_query($dbconn, $query);
$row    = pg_fetch_assoc($result);

$query = "UPDATE ship SET shiptype='Pirate', x=".$row['x'].", y=".$row['y'].", crew=1, points=1000, health=100 WHERE username='$username'";
$result = pg_query($dbconn, $query);

$query = "UPDATE inventory SET quantity=250 WHERE username='$username' and item='food'";
$result = pg_query($dbconn, $query);
$query = "UPDATE inventory SET quantity=5 WHERE username='$username' and item='weapons'";
$result = pg_query($dbconn, $query);

$query = "DELETE FROM market WHERE username='$username'";
$result = pg_query($dbconn, $query);

$query    = "INSERT INTO Messages (sender,receiver,subject,message,sent) VALUES ('System Admin','$username',' ','Welcome back! Please refer to the <a href=\"#\">help pages</a> to get started.','".date('Y-m-j H:i:s')."')";
$result   = pg_query($dbconn, $query);

header("Location: map.php");
?>