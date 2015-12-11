<?php

function bpwelcome($con, $uiid, $userid, $user_body, $user_phone, $user_city, $user_state, $user_country, $user_zip)
{
	$responseMessage = "";

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
		if (!mysqli_query($con,$query))
		{
			$fh = fopen("bplog.txt", 'a+') or die("can't open file");
			fwrite($fh, date(DATE_ATOM)."Error in query [".$query."]: " . mysqli_error($con).PHP_EOL);
			fclose($fh);	
		}
			
		$responseMessage = "What is your name?";
	}
	else if($userid == "")
	{
		{
			$query = 
				"Update bpusers set "
				."`phone`  ="."'".$user_phone 	."',"
				."`city`   ="."'".$user_city 	."',"
				."`state`  ="."'".$user_state 	."',"
				."`country`="."'".$user_country ."',"
				."`zip`    ="."'".$user_zip 	."' where `id` = " . $uiid; 
				;
				
			if (strlen($query)>0 )
			{
				if (!mysqli_query($con,$query))
				{
					$fh = fopen("bplog.txt", 'a+') or die("can't open file");
					fwrite($fh, date(DATE_ATOM)."Error in query [".$query."]: " . mysqli_error($con).PHP_EOL);
					fclose($fh);	
				}
			}
		}
		if(strlen($user_body) > 2 && strlen($user_body) < 20 )
		{
			$responseMessage = "Is your name '" . $user_body . "' ?";
			$tmp_name = "]" . $user_body;
			$query = "Update bpusers set `userid`="."'".$tmp_name ."' where `id` = " . $uiid ;
			if (!mysqli_query($con,$query))
			{
				$fh = fopen("bplog.txt", 'a+') or die("can't open file");
				fwrite($fh, date(DATE_ATOM)."Error in query [".$query."]: " . mysqli_error($con).PHP_EOL);
				fclose($fh);	
			}
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
			if (!mysqli_query($con,$query))
			{
				$fh = fopen("bplog.txt", 'a+') or die("can't open file");
				fwrite($fh, date(DATE_ATOM)."Error in query [".$query."]: " . mysqli_error($con).PHP_EOL);
				fclose($fh);	
			}
			$responseMessage = "Welcome " . $tmp_name . " !";
		}
		else
		{
			$query = "Update bpusers set `userid`='' where `id` = " . $uiid ;
			if (!mysqli_query($con,$query))
			{
				$fh = fopen("bplog.txt", 'a+') or die("can't open file");
				fwrite($fh, date(DATE_ATOM)."Error in query [".$query."]: " . mysqli_error($con).PHP_EOL);
				fclose($fh);	
			}
			$responseMessage = "What is your name?";
		}
	}
	return $responseMessage;
}

?>
