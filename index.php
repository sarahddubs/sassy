<html>
<head>
	<title>Campus Convoz</title>
	<link rel="stylesheet" href="style.css" type="text/css" />
</head>
<body>

	<table id="intro_table">
		<tr>
			<td>
				<div id='intro_div'>
					<h1>Campus Convoz</h1>
					<p id='campusconvos_p'>
						Need someone to talk to?  We can help.
						Campus Convos is here to connect you to someone
						on your campus so you can have a meaningful 
						conversation with someone without revealing your
						identity.
						<br /><br />
						Someone out there will listen.
						<b>Be heard.</b>
					</p>
					<button class="begin-convo chatroom_room_a" id="begin-convo"><a class='chatroom_room_a' id="chat-link">Begin Conversation</a></button>
				</div>
				<div id='bottom_section'>
					<p>
						<a class='bottom_a' href='#'>About Us </a>
						<b>|</b> 
						<a class='bottom_a' href='#'>Privacy Policy</a>
					</p>
				</div>
			</td>
			<td>
				<img id='balloons_img' src="images/balloons.jpg" alt="balloons" />
			</td>
		</tr>
	</table>
	


<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script src="jquery.cookie.js"></script>

<script>
	var userID = "<?php echo uniqid(); ?>";
	
	$.cookie("user_id", userID);

	$('.chatroom_room_a').click(function() {
		// TODO: Some kind of stalling function so that it doesn't send you to a chatroom by yourself.
		$.ajax({
		    type: 'GET',
		    url: 'getroom.php',
		    success: function(room){
			if (room == '') { // No chatroom exists
				var chatroomID = "<?php echo uniqid('chat_'); ?>";
				var link = 'chat.php?id=' + chatroomID;
				$.ajax({
				    type: 'POST',
				    url: 'writeroom.php',
				    data: { 
					'chatroom_id': chatroomID
				    },
				    success: function(msg){
					window.location = link;
				    }
				});
			} else { // chatroom exists already
				var link = 'chat.php?id=' + room;
				window.location = link;
			}
		    }
		});
	});

</script>
</body>
</html>
