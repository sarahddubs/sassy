<?php
	$my_file = $_POST['chat_filename'];
	$rating = $_POST['rating'];
	$file = fopen($my_file, 'r+');
	$line = fgets($file);
	$offset = strlen($line) - 1;
	fseek($file, $offset);
	fwrite($file, ' END###:'.$rating."\n");
    fclose($file);
?>