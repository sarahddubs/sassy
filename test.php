<!doctype html>
 
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>jQuery UI Dialog - Basic modal</title>
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
  <link rel="stylesheet" href="http://jquery-star-rating-plugin.googlecode.com/svn/trunk/jquery.rating.css" type="text/css">
  <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
  <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script src="star-rating/jquery.rating.pack.js"></script>
  <script>
  $(function() {
    $( "#dialog-confirm" ).dialog({
      height: 140,
      modal: true,
      buttons: {
        "Delete all items": function() {
          $( this ).dialog( "close" );
        },
        Cancel: function() {
          $( this ).dialog( "close" );
        }
      }
    });
  });
  </script>
</head>
<body>
 
<div id="dialog-confirm">
	<p>
		<span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
		<span id="dialog-confirm-message">Closing</span>
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
 
<p>Sed vel diam id libero <a href="http://example.com">rutrum convallis</a>. Donec aliquet leo vel magna. Phasellus rhoncus faucibus ante. Etiam bibendum, enim faucibus aliquet rhoncus, arcu felis ultricies neque, sit amet auctor elit eros a lectus.</p>
 
 
</body>
</html>