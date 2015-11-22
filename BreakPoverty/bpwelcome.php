<?php

	if($uiid == -1)
	{
		// new telephone number
		$query = 
		
			"INSERT INTO bpusers ( `phone`, `city`, `state`, `country`, `zip` ) VALUES (" 
			. "'" . $user_phone 	. "', "
			. "'" . $user_city 		. "', "
			. "'" . $user_state 	. "', "
			. "'" . $user_country 	. "', "
			. "'" . $user_zip 		. "' "
			. ")";
			
		$responseMessage = "What is your name?";
	}
	else if($userid == "")
	{
		if(strlen($user_body) > 2 && strlen($user_body) < 20 )
		{
			$responseMessage = "Is your name '" . $user_body . "' ?";
			$tmp_name = "]" . $user_body;
			$query = "Update bpusers set `userid`="."'".$tmp_name ."' where `id` = " . $uiid ;
		}
		else
		{
			$responseMessage = "What is your name?";
		}
	}
	else if( substr($userid, 0 , 1)==']')
	{
		if(substr($user_body,0,1)=='Y' || substr($user_body,0,1)=='y')
		{
			$tmp_name = substr($userid, 1 );
			
			$query = "Update bpusers set `userid`="."'".$tmp_name ."' where `id` = " . $uiid ;
			$responseMessage = "Welcome " . $tmp_name . " !";
		}
		else
		{
			$query = "Update bpusers set `userid`='' where `id` = " . $uiid ;
			$responseMessage = "What is your name?";
		}
	}

?>
