<html>
<head>
	<title> CHAT ROOM</title>
	<style type="text/css">
		body {
			font-family: Helvetica, Sans-Serif;
			/*background-image:url('images/retina_wood.png');*/
			/*background-image:url('images/bedge_grunge.png');*/
			background-image:url('images/scribble_light.png');
		}
	
		.begin-convo {
			-moz-box-shadow:inset 0px 1px 0px 0px #97c4fe;
			-webkit-box-shadow:inset 0px 1px 0px 0px #97c4fe;
			box-shadow:inset 0px 1px 0px 0px #97c4fe;
			background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #e60000), color-stop(1, #990000) );
			background:-moz-linear-gradient( center top, #e60000 5%, #990000 100% );
			filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#e60000', endColorstr='#990000');
			background-color:#e60000;
			-moz-border-radius:6px;
			-webkit-border-radius:6px;
			border-radius:6px;
			border:1px solid #b30000;
			display:inline-block;
			color:#ffffff;
			font-family:arial;
			font-size:15px;
			font-weight:bold;
			padding:15px 80px;
			text-decoration:none;
			text-shadow:1px 1px 0px #990000;
		}.begin-convo:hover {
			background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #990000), color-stop(1, #e60000) );
			background:-moz-linear-gradient( center top, #990000 5%, #e60000 100% );
			filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#990000', endColorstr='#e60000');
			background-color:#990000;
			cursor: pointer;
		}.begin-convo:active {
			position:relative;
			top:1px;
		}
		
		h1 {
			font-size:39px;
		}
		
		table {
			width:800px;
			margin:0 auto;
		}
		
		td {
			width:50%;
			text-align:center;
		}
		
		#balloons_img {
			width:350px;
			border:3px solid #990000;
		}
		
		#intro_div {
			width: 350px;
		}
		
		#campusconvos_p {
			width: 305px;
			padding: 0;
			margin: 10px auto;
			text-align: left;
		}
		
		#bottom_section {
			font-size:11px;
			width:250px;
			margin:10px auto;
		}
		
		h1, b {
			color: #990000;
		}
		
		#bottom_section a {
			text-decoration:none;
			color:black;
		}
		
		#bottom_section a:hover {
			color:#990000;
		}
		
		.chatroom_room_a {}
		
	</style>
</head>
<body>

	<table id="intro_table">
		<tr>
			<td>
				<div id='intro_div'>
					<h1>Campus Convos</h1>
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
			</td>
			<td>
				<img id='balloons_img' src="images/balloons.jpg" alt="balloons" />
			</td>
		</tr>
	</table>
	
	<div id='bottom_section'>
		<p>
			<a class='bottom_a' href='#'>About Us </a>
			<b>|</b> 
			<a class='bottom_a' href='#'>Privacy Policy</a>
		</p>
	</div>


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
