<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    
    <title>Campus Convoz</title>
	<link rel="stylesheet" href="http://jquery-star-rating-plugin.googlecode.com/svn/trunk/jquery.rating.css" type="text/css">

	<link rel="stylesheet" href="style.css" type="text/css" />
</head>
<body>
	<div id="page-wrap">
		<h2>Campus Convoz</h2>
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
			<span id="dialog-confirm-message"></span>
			<br><br>
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
    	$("#name-area").html("");
    	
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

<body onload="updateInterval = setInterval('chat.update()', 1000)">
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script src="jquery.cookie.js"></script>
<script>
	$('#end-convo').click(function() {
		if (!usersReady) { // 1 person in chatroom, return to index
			$.ajax({
				type: "POST",
				url: "clearroom.php",
				success: function(data){
					window.location = 'index.php';
				}
			});
		} else { // 2 people in chatroom, someone hits End Conversation
			clearInterval(updateInterval);
			showRatingBox(false);
		}
	});
</script>

<script>
  window.onbeforeunload = confirmExit;
  function confirmExit()
  {
    if (usersReady) {
		$.ajax({
		   type: "POST",
		   url: "rate.php",
		   data: {  
					'chat_filename': file_name,
					'rating': $.cookie('user_id') + ':  -1'
				},
		   success: function(data){
		   
		   }
		});	
	} else {
		$.ajax({
		   type: "POST",
		   url: "clearroom.php",
		   success: function(data){	   
				// here?
		   }
		});
	}
  }
</script>
<script src="star-rating/jquery.rating.pack.js"></script>
  <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
</body>

</html>
