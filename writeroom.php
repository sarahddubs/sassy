<?php
	switch ($_SERVER['HTTP_ORIGIN']) {
	    case 'http://campusconvoz.com': case 'campusconvoz.com':
	    header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
	    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
	    header('Access-Control-Max-Age: 1000');
	    header('Access-Control-Allow-Headers: Content-Type');
	    break;
	}
	
	$my_file = 'rooms.txt';
	$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
	$data = $_POST['chatroom_id'];
	fwrite($handle, $data); 
?>
