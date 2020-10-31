<?php

	require_once("../library/loadall.php");

	$qry_leaderboard = "SELECT * FROM Ship ORDER BY points DESC";
	$res_leaderboard = pg_query($qry_leaderboard);

?>
<table align='center' width='400'>
	<tr>
		<td>Player</td>
		<td align='right' width='80'>Money</td>
	</tr>

</table>
<hr class='leaderline' />
<table align='center' width='400'>
<?php	

	while ($player = pg_fetch_assoc($res_leaderboard)) {
		$user = $player["username"];
		$points = $player["points"];
		echo "<tr>";

		echo "<td>";
		if ($user == $username) {echo "<b>";}
		echo $user;
		if ($user == $username) {echo "</b>";}
		echo "</td>";

		echo "<td align='right' width='80'>";
		if ($user == $username) {echo "<b>";}
		echo $currency . $points;
		if ($user == $username) {echo "</b>";}
		echo "</td>";
		echo "</tr>";
	}

?>
</table>
