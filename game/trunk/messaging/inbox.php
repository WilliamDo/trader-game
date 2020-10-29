<script>

	function showInbox() {
		$.get("messaging/get_inbox.php", function(data){
			$("#inbox_div").html(data);
		});

	}

	function showMessage(message_no) {
		$.get("messaging/get_message.php", {"message_no":message_no}, function(data) {
			$("#message_area").html(data);
		});
		// showInbox();
	}

</script>

<div id="inbox_div"></div>
