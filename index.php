<html>
<head>
	<title> CHAT ROOM</title>
</head>
<body>

<a href="chat.php">Go to chatroom</a>



<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script src="jquery.cookie.js"></script>

<script>
	var userID = "<?php echo uniqid(); ?>";
	alert(userID);
	
	$.cookie("user_id", userID);

// Ajax Request to PHP to get uniq id
// set cookie unique id to what is returned
// chat.php onclick: ajax call to php that returns chatroom id {later: add matching}
// redirect user to chatroom with chatroom id

</script>
</body>
</html>
