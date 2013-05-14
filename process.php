<?php
	
	$file_name = $_POST['file_name'];
    $function = $_POST['function'];
    
    $log = array();
    
    /*
    * If this is the first time the second user enters, return true.
    * Otherwise, return false.
    */
    function secondUserEnters($cookie, $file_name) {
    	
    	$file = fopen($file_name, 'r');
    	$line = fgets($file);
    	
    	$cookie_regex = '/'.$cookie.'/';
    	
    	if (preg_match($cookie_regex, $line)) {
    		// This is User #1
        	return false;
    	}
    	
    	if (preg_match('/USER#2/', $line)) {
    		// USER#2 Cookie already defined
    		return false;
    	}
    	
    	fclose($file);
    	return true;
    }
    
    
    function writeCookie($cookie, $file_name, $first_user) {
    	
    	if ($first_user) {
    		$file = fopen($file_name, 'a');
        	fwrite($file, "USER#1:".$cookie."\n"); 
        	fclose($file);
    	} else {
    		$file = fopen($file_name, 'r+');
			$line = fgets($file);
		
			$offset = strlen($line) - 1;
			fseek($file, $offset);
			fwrite($file, ' USER#2:'.$cookie."\n");
		
			fclose($file);
    	}	
    }
    
    switch($function) {
    
    	 case('getState'):
        	 if(!file_exists($file_name)){
        	 	// This must be the first user
        	 	writeCookie($_POST['cookie'], $file_name, true);
        	 } else if (secondUserEnters($_POST['cookie'], $file_name)) {
        	 	// This must be the second user
        	 	writeCookie($_POST['cookie'], $file_name, false);
        	 }
        	 $lines = file($file_name);
             $log['state'] = count($lines); 
        	 break;	
    	
    	 case('update'):
        	$state = $_POST['state'];
        	if(file_exists($file_name)){
        	   $lines = file($file_name);
        	 }
        	 $count =  count($lines);
        	 if($state == $count) {
        		 $log['state'] = $state;
        		 $log['text'] = false;
        		 $log['end'] = false;
        	 } else {
        	 	$log['end'] = false;
        		$text= array();
        		$log['state'] = $state + count($lines) - $state;
        		foreach ($lines as $line_num => $line) {
					if($line_num >= $state){
						$text[] =  $line = str_replace("\n", "", $line);
					}
					if (preg_match('/END###/', $line)) {
						$log['end'] = true;
					}
	 
				 }
				 $log['text'] = $text; 
			}
        	  
             break;
    	 
    	 case('send'):
		  $nickname = htmlentities(strip_tags($_POST['nickname']));
			 $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
			  $message = htmlentities(strip_tags($_POST['message']));
		 if(($message) != "\n"){
        	
			 if(preg_match($reg_exUrl, $message, $url)) {
       			$message = preg_replace($reg_exUrl, '<a href="'.$url[0].'" target="_blank">'.$url[0].'</a>', $message);
				} 
			 
        	 
        	 fwrite(fopen($file_name, 'a'), "" . $message = str_replace("\n", " ", $message) . "\n"); 
		 }
        	 break;
        
        case('usersReady'):
        	$file_name = $_POST['file_name'];
        	if(file_exists($file_name)) {
				$file = fopen($file_name, 'r');
				$line = fgets($file);
				$log['ready'] = $line;
				if (preg_match('/USER#1/', $line) && preg_match('/USER#2/', $line)) {
					$log['ready'] = true;
				} else {
					$log['ready'] = false;
				}
        	} else {
        		$log['ready'] = false;
        	}
    }
    
    echo json_encode($log);

?>