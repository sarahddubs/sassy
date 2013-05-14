<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    
    <title>Chat</title>
	<style type="text/css">
		.end-convo {
			-moz-box-shadow:inset 0px 1px 0px 0px #97c4fe;
			-webkit-box-shadow:inset 0px 1px 0px 0px #97c4fe;
			box-shadow:inset 0px 1px 0px 0px #97c4fe;
			background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #3d94f6), color-stop(1, #1e62d0) );
			background:-moz-linear-gradient( center top, #3d94f6 5%, #1e62d0 100% );
			filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#3d94f6', endColorstr='#1e62d0');
			background-color:#3d94f6;
			-moz-border-radius:6px;
			-webkit-border-radius:6px;
			border-radius:6px;
			border:1px solid #337fed;
			display:inline-block;
			color:#ffffff;
			font-family:arial;
			font-size:15px;
			font-weight:bold;
			padding:6px 24px;
			text-decoration:none;
			text-shadow:1px 1px 0px #1570cd;
		}.end-convo:hover {
			background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #1e62d0), color-stop(1, #3d94f6) );
			background:-moz-linear-gradient( center top, #1e62d0 5%, #3d94f6 100% );
			filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#1e62d0', endColorstr='#3d94f6');
			background-color:#1e62d0;
		}.end-convo:active {
			position:relative;
			top:1px;
		}
		
		#dialog-confirm {
			display: none;
			background-color: white;
		}
		.ui-dialog-titlebar-close {
			visibility: hidden;
		}
		
		.ui-button-text {
			left: 145px;
			position: absolute;
			-moz-box-shadow:inset 0px 1px 0px 0px #f29c93;
			-webkit-box-shadow:inset 0px 1px 0px 0px #f29c93;
			box-shadow:inset 0px 1px 0px 0px #f29c93;
			background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #fe1a00), color-stop(1, #ce0100) );
			background:-moz-linear-gradient( center top, #fe1a00 5%, #ce0100 100% );
			filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#fe1a00', endColorstr='#ce0100');
			background-color:#fe1a00;
			-moz-border-radius:6px;
			-webkit-border-radius:6px;
			border-radius:6px;
			border:1px solid #d83526;
			display:inline-block;
			color:#ffffff;
			font-family:arial;
			font-size:15px;
			font-weight:bold;
			padding:6px 24px;
			text-decoration:none;
			text-shadow:1px 1px 0px #b23e35;
		}.ui-button-text:hover {
			background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #ce0100), color-stop(1, #fe1a00) );
			background:-moz-linear-gradient( center top, #ce0100 5%, #fe1a00 100% );
			filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ce0100', endColorstr='#fe1a00');
			background-color:#ce0100;
		}.ui-button-text:active {
			position:relative;
			top:1px;
		}	
		#survey {
			position: absolute;
			left: 100px;
		}
	</style>
    
	<link rel="stylesheet" href="http://jquery-star-rating-plugin.googlecode.com/svn/trunk/jquery.rating.css" type="text/css">

	<link rel="stylesheet" href="style.css" type="text/css" />
</head>
<body>
	<div id="page-wrap">
		<h2>We need a good title.</h2>
        <p id="name-area"></p>
        
        <div id="chat-wrap"><div id="chat-area"></div></div>
        
        <form id="send-message-area">
            <p>Your message: </p>
            <textarea id="sendie" maxlength = '100' ></textarea>
        </form>
		<br><br><br><br><br><br><br>
		<button class="end-convo" id="end-convo">End Conversation</button>
    
	</div>
	 
	<div id="dialog-confirm">
		<p>
			<span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
			On a scale from 1-5, how meaningful was this conversation to you? <br><br>
			<form id="survey">
				<input name="rating" type="radio" class="star {split:4}" value="1"/>
				<input name="rating" type="radio" class="star {split:4}" value="2"/>
				<input name="rating" type="radio" class="star {split:4}" value="3"/>
				<input name="rating" type="radio" class="star {split:4}" value="4"/>
				<input name="rating" type="radio" class="star {split:4}" value="5"/>
			</form>
		</p>
	</div>
	
	
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <script type="text/javascript" src="chat.js"></script>
    <script type="text/javascript">
    
        // ask user for name with popup prompt    
        var name = 'You';
    	
    	// strip tags
    	name = name.replace(/(<([^>]+)>)/ig,"");
    	
    	// display name on page
    	$("#name-area").html("Some welcoming text here.");
    	
    	// kick off chat
        var chat =  new Chat();
    	$(function() {
    	
    		 chat.getState(); 
    		 
    		 // watch textarea for key presses
             $("#sendie").keydown(function(event) {  
             
                 var key = event.which;  
           
                 //all keys including return.  
                 if (key >= 33) {
                   
                     var maxLength = $(this).attr("maxlength");  
                     var length = this.value.length;  
                     
                     // don't allow new content if length is maxed out
                     if (length >= maxLength) {  
                         event.preventDefault();  
                     }  
                  }  
    		 																																																});
    		 // watch textarea for release of key press
    		 $('#sendie').keyup(function(e) {	
    		 					 
    			  if (e.keyCode == 13) { 
    			  
                    var text = new Date().getTime() + ' ' + $.cookie('user_id') + ' ' + $(this).val(); // timestamp, usercookie, message
    				var maxLength = $(this).attr("maxlength");  
                    var length = text.length; 
                     
                    // send 
                    if (length <= maxLength + 1) { 
                     
    			        chat.send(text, name);	
    			        $(this).val("");
    			        
                    } else {
                    
    					$(this).val(text.substring(0, maxLength));
    					
    				}	
    				
    				
    			  }
             });
            
    	});
    </script>

<body onload="setInterval('chat.update()', 1000)">
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script src="jquery.cookie.js"></script>
<script>
	$('#end-convo').click(function() {
		if (!usersReady) {
			// 1 person, Sarah
		}
	
	
		$( "#dialog-confirm" ).dialog({
			resizable: false,
			height:140,
			modal: true,
			buttons: {
				"Rate and close": function() {
					// check if there is a rating. If not, alert user and return.
					// If there is a rating, send to php function. 
					var box = this;
					var rating = $('#survey').serialize();
					if (rating == '') {
						alert('Please rate this conversation.');
						return;
					} else {
						var num = rating.substring(7);
						// TODO: Send to php form to write rating in
						$.ajax({
						   type: "POST",
						   url: "rate.php",
						   data: {  
									'chat_filename': file_name,
									'rating': $.cookie('user_id') + ': ' + num
								},
						   success: function(data){	   
								$(box).dialog('close');
								window.location = 'index.php';
						   }
						});
						
					}
					
				}
			}
		});
	});
	$(function(){ // wait for document to load
		$('input.star').rating();
	});
</script>
<script src="star-rating/jquery.rating.pack.js"></script>
  <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
</body>

</html>
