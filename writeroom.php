<?php
	$my_file = 'rooms.txt';
	$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
	$data = $_POST['chatroom_id'];
	fwrite($handle, $data); 
?>
