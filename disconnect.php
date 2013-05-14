<?php
	$my_file = $_POST['chat_filename'];
	$file = fopen($my_file, 'r+');
	$line = fgets($file);
	$offset = strlen($line) - 1;
	fseek($file, $offset);
	fwrite($file, ' USER_DISCONNECTED'."\n");
    fclose($file);
?>