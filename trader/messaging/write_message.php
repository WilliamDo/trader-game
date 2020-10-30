#!/usr/bin/php
<?php

	require_once("../library/loadall.php");

	/*
	 * Retrieve list of users to send messages to
	 */
	$qry_users = "SELECT * FROM Users WHERE username <> '$username'";
	$users = db_exec_query($qry_users);
	
?>

<script>
function send() {
	$.post("messaging/send.php", {username:document.messages.username.value, receiver:document.messages.receiver.value, subject:document.messages.subject.value, message:document.messages.message.value}, function(data) {
		$.facebox(data);
	});	

	return false;
}
</script>
	
<form name='messages' action='#' onsubmit="send();return false" method='post'>

	<p><input type='hidden' name='username' value="<?=$username;?>" /></p>
	
	<p>To:	<?=$_GET['to'];?></p>
	<input type='hidden' name='receiver' value="<?=$_GET['to'];?>" />
<?php
/*
			if ($_GET["receiver"]) {
				$receiver = $_GET["receiver"];
				echo($_GET["receiver"]);
				echo "<input type='hidden' name='receiver' value='$receiver' />";
			} else {

		

			echo "<select name='receiver'>";
			
				// Populate list of receivers box
				while($user = pg_fetch_assoc($users)) {
					echo "<option value='" . $user["username"] . "'>";
					echo $user["username"];
					echo "</option>\n";
				}
					
		
					
			echo "</select>";
			}
*/
?>	
	</p>

	<!-- <p>
		Subject:
		<input type='text' name='subject' />
	</p> -->
	<input type='hidden' name='subject' value="" />
	<p>
		Message:<br />
		<textarea name='message' rows='10' cols='40'></textarea>			
			
	</p>
			
	<p>		
		<input type='submit' value='Send' />

	</p>
			
		
</form>
