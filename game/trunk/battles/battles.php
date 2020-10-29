<?php

$query  = "SELECT challenger,opponent FROM battles WHERE challenger='$username' or opponent='$username'";
$result = pg_query($dbconn, $query);

if(pg_num_rows($result) == 0) {
	echo "<p>You have no pending challenges.</p>";

	$query  = "SELECT x,y FROM ship WHERE username='$username' and number is null";
	$result = pg_query($dbconn, $query);
	
	if(pg_num_rows($result) == 0) {
		echo "<p>Port security prevents you from battling another ship whilst in a port.</p>";
	}

	else { // not in a port
		// show near ships
		$row = pg_fetch_row($result);
		$x = $row[0];
		$y = $row[1];
		
		$query  = "SELECT username,shipname FROM ship WHERE username<>'$username' and $x-3<x and x<$x+3 and $y-3<y and y<$y+3 ORDER BY username";
		$result = pg_query($dbconn, $query);
	
		if(pg_num_rows($result) == 0) {
			echo "<p>There are no ships in your vicinity to challenge.</p>";	
		}
		else {
			echo "<p>The following ships are in your vicinity:</p>";
		
			echo "<ul>";
			while ($row = pg_fetch_row($result)) {
				$user_s = $row[0]."'s ";
				$ship = $user_s.$row[1];
		
				$new_query  = "SELECT x FROM battles WHERE challenger='$row[0]' or opponent='$row[0]'";
				$new_result = pg_query($dbconn, $new_query);
		
				if(pg_num_rows($new_result) == 0) {	// not currently in a battle
					$new_query  = "SELECT number FROM ship WHERE username='$row[0]' and number is not null";
					$new_result = pg_query($dbconn, $new_query);
					
					if(pg_num_rows($new_result) == 0) { // not in a port
						echo "<li><a href=\"battles/challenge.php?challenge=$row[0]\" rel=\"facebox\">Challenge</a> $user_s $row[1] to a battle</li>";
					}
					else {
						echo "<li>$ship is in the security of a port</li>";
					}
				}
				else {
					echo "<li>$ship is already involved in a battle</li>";
				}
			}
			echo "</ul>";
		}
	}
}

else {
	echo "<p>Pending challenges:</p>";
	echo "<ul>";
	while ($row = pg_fetch_row($result)) {
		if($row[0] == $username) {
			$new_query  = "SELECT shipname FROM ship WHERE username='$row[1]'";
			$new_result = pg_query($dbconn, $new_query);
			$new_row = pg_fetch_row($new_result);
			$ship = $row[1]."'s ".$new_row[0];

			echo "<li>You have challenged $ship to a battle. Waiting for response.</li>";
		}
		else {
			$new_query  = "SELECT shipname FROM ship WHERE username='$row[0]'";
			$new_result = pg_query($dbconn, $new_query);
			$new_row = pg_fetch_row($new_result);
			$ship = $row[0]."'s ".$new_row[0];

			echo "<li>$ship challenges you to a battle. Do you wish to <a href=\"battles/response.php?r=accept\" rel=\"facebox\">accept</a> or <a href=\"battles/response.php?r=decline\" rel=\"facebox\">decline</a>?</li>";
		}
	}
	echo "</ul>";
}

?>