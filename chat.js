/* 
Created by: Kenrick Beckett

Name: Chat Engine
*/

var instanse = false;
var state;
var mes;
var file;

var unique_id = GetURLParameter('id');
var file_name = 'conversations/' + unique_id + '.txt';
var usersReady = false;

var usersReadyInterval = setInterval(
							function(){
								areUsersReady()
							},1000);

function Chat () {
    this.update = updateChat;
    this.send = sendChat;
	this.getState = getStateOfChat;
}

//gets the state of the chat
function getStateOfChat(){
	if(!instanse){
		 instanse = true;
		 $.ajax({
			   type: "POST",
			   url: "process.php",
			   data: {  
			   			'function': 'getState',
						'file': file,
						'file_name':file_name,
						'cookie': $.cookie("user_id")
						},
			   dataType: "json",
			
			   success: function(data){
				   state = data.state;
				   instanse = false;
			   },
			});
	}	 
}

// Replace the following special chars: ' " \
function escapeSpecialChars(message) {
	message = message.replace(/\\'/g, "'");
	message = message.toString().replace(/\\&quot;/g, '"');
	message = message.replace(/\\\\/g, '\\');
	return message
}

//Updates the chat
function updateChat(){
	 if(!instanse){
		 instanse = true;
	     $.ajax({
			   type: "POST",
			   url: "process.php",
			   data: {  
			   			'function': 'update',
						'state': state,
						'file': file,
						'file_name':file_name
						},
			   dataType: "json",
			   error: function(data){
					//console.log(data);
			   },
			   success: function(data){
				   if(data.text){
				   		if (data.end) {
				   			showRatingBox(true);
				   			clearInterval(updateInterval);
				   		} else {
							for (var i = 0; i < data.text.length; i++) {							
								var first_space = data.text[i].indexOf(' ');
								var second_space = data.text[i].indexOf(' ', first_space + 1);
								var timestamp = data.text[i].substring(0, first_space);
								var sent_by = data.text[i].substring(first_space + 1 , second_space);
								var message = data.text[i].substring(second_space + 1);
								message = escapeSpecialChars(message);
								var person = '';
								if ($.cookie('user_id') == sent_by) person = 'You';
								else person = 'Stranger';
								$('#chat-area').append($("<p><span>" + person + '</span>' + message +"</p>")); // TEXT OF USER
								$('title').html(message);
							}
							document.getElementById('chat-area').scrollTop = document.getElementById('chat-area').scrollHeight;			
						}
				   }
				   instanse = false;
				   state = data.state;
			   },
			});
	 }
	 else {
		 setTimeout(updateChat, 1500);
	 }
}

//send the message
function sendChat(message, nickname){       
    updateChat();
     $.ajax({
		   type: "POST",
		   url: "process.php",
		   data: {  
		   			'function': 'send',
					'message': message,
					'nickname': nickname,
					'file': file,
					'file_name':file_name
				 },
		   dataType: "json",
		   success: function(data){
			   updateChat();
		   },
		});
}

function areUsersReady() {
	if (!usersReady) {
		$.ajax({
			type: "POST",
			url: "process.php",
			data: {
				'function': 'usersReady',
				'file_name':file_name
			},
			dataType: "json",
			success: function (data){
				if (!usersReady && data.ready) {
					unlockChat();
					usersReady = true;
					clearInterval(usersReadyInterval);
					$('#chat-area').append($("<p>You have been connected!  Say 'hello' to your conversation partner.</p>"));
				}
			},
		});
	}
}

// get a parameter from the url
function GetURLParameter(sParam)
{
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++)
    {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam)
        {
            return sParameterName[1];
        }
    }
}

function showRatingBox(partnerDisconnected) {

	if (partnerDisconnected) {
		var disconnectMsg = "Your partner has disconnected.";
		$("#dialog-confirm-message").append(disconnectMsg);
	}
	var rateMsg = "On a scale from 1-5, how meaningful was this conversation to you?";
	$("#dialog-confirm-message").append(rateMsg);
	
	$("#dialog-confirm").css("border-bottom", "none");

	$( "#dialog-confirm" ).dialog({
		resizable: false,
		height:100,
		modal: true,
		buttons: {
			"Rate and close": function() {
				// TODO: Add notification of disconnect.
				var box = this;
				var rating = $('#survey').serialize();
				if (rating == '') {
					alert('Please rate this conversation.');
					return;
				} else {
					var num = rating.substring(7);
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
	
	if (partnerDisconnected) {
		$(".ui-button-text-only").css("margin-top", "70px");
	}

	
}

/*
* Stops the user from typing in the chat box and displays a
* waiting message.  Call this on initialization. 
* To stop this, call unlockChat().
*/
function lockChat() {
	// Initialization - don't allow for user to type messages
	$("#sendie").prop('disabled', true);
	
	// Display waiting message
	$("#chat-area").css("background-color", "#efefef");
	var waitMsg = "<div id='wait-msg'>Please wait while we connect you with a partner :)</div>";
	waitMsg +=	"<div id='ballsWaveG'>"
	waitMsg +=		"<div id='ballsWaveG_1' class='ballsWaveG'></div>";
	waitMsg +=		"<div id='ballsWaveG_2' class='ballsWaveG'></div>";
	waitMsg +=		"<div id='ballsWaveG_3' class='ballsWaveG'></div>";
	waitMsg +=		"<div id='ballsWaveG_4' class='ballsWaveG'></div>";
	waitMsg +=		"<div id='ballsWaveG_5' class='ballsWaveG'></div>";
	waitMsg +=		"<div id='ballsWaveG_6' class='ballsWaveG'></div>";
	waitMsg +=		"<div id='ballsWaveG_7' class='ballsWaveG'></div>";
	waitMsg +=		"<div id='ballsWaveG_8' class='ballsWaveG'></div>";
	waitMsg +=	"</div>";
	$("#chat-area").html(waitMsg);
}

/*
* Allow the user to type in the chat box and clears the waiting 
* message.  Call this when the second user has entered the room.
*/
function unlockChat() {
	// Initialization - don't allow for user to type messages
	$("#sendie").prop('disabled', false);
	
	// Display waiting message
	$("#chat-area").css("background-color", "white");
	$("#chat-area").html("");
}

