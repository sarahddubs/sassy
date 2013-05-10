<html>
<head>
	<title> CHAT ROOM</title>
</head>
<body>

<a id="chat-link">Go to chatroom</a>



<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script src="jquery.cookie.js"></script>

<script>
	var userID = "<?php echo uniqid(); ?>";
	
	$.cookie("user_id", userID);

	$('#chat-link').click(function() {
	// get empty chatroom ID from database
	// if chatroom does not exist, create and forward
	// else forward
	
		var chatroomID = "<?php echo uniqid('chat_'); ?>";
		var link = 'chat.php?id=' + chatroomID;
		window.location = link;
	});

</script>
</body>
</html>
