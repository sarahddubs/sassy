<?php
	$my_file = $_POST['chat_filename'];
	$rating = $_POST['rating'];
	$file = fopen($my_file, 'a');
        	fwrite($file, "END###:".$rating."\n"); 
        	fclose($file);
?>