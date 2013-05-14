<?php
	$my_file = 'rooms.txt';
	$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
	fwrite($handle, '');	
?>