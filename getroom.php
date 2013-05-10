<?php
	$my_file = 'rooms.txt';
	$handle = fopen($my_file, 'r');
	$data = fread($handle,100);
	$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
	fwrite($handle, '');	
	
	echo $data;
?>
