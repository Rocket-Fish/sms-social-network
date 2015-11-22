<?php

include("bpprocesses.php");

/*
function startsWith($haystack, $needle) 
{
	$neddle_len = strlen($neddle);
	if($needle_len == 0)
		return false;
	echo "haystack=" . $haystack . "\n";
	echo "needle_len=" . $needle_len . "\n";
	echo "substr=" . $substr($haystack, 0, $needle_len ) . "\n";;
	
    return substr($haystack, 0, $needle_len ) == $neddle;
}
*/

	header("content-type: text/xml");
	error_reporting(E_ALL);
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
?>
<?php


	$stringData = file_get_contents("php://input");
	echo $stringData . "\n";

	$splittedString = explode('&', $stringData);
	$array = array_values($splittedString);
	
	$user_from 		= "";
	$user_zip 		= "";
	$user_state 	= "";
	$user_city 		= "";
	$user_country 	= "";
	$user_phone 	= "";
	$user_body 		= "";

	$myFile = "bplog.txt";
	
	$responseMessage = "";

//    [5] =>  FromZip    =
//    [7] =>  FromState  =ON
//    [9] =>  FromCity   =AJAX+PICKERING
//    [11] => FromCountry=CA
//    [17] => From       =%2B12899239409
//    [10] => Body       =Vdbdbdbnfdh
	
	for($i = 0; $i < count($splittedString); $i ++) {
	
		list($name, $value) = explode("=", $array[$i]);
		
		if($name=="FromZip") 		$user_zip 		 = urldecode ($value);
		if($name=="FromState") 		$user_state 	 = urldecode ($value);
		if($name=="FromCity") 		$user_city 		 = urldecode ($value);
		if($name=="FromCountry") 	$user_country 	 = urldecode ($value);
		if($name=="From") 			$user_phone 	 = urldecode ($value);
		if($name=="Body") 			$user_body 		 = urldecode ($value);
	}

	$fh = fopen($myFile, 'a+') or die("can't open file");
	fwrite($fh, "2222".PHP_EOL);
	fclose($fh);	
	
include("dbbpconnect.php");

    $query = "SELECT id, userid FROM bpusers where phone='".$user_phone."'";
	if (!($result=mysqli_query($con,$query)))
	{
		echo "Error description: " . mysqli_error($con) ;
	}	
    
	$num = mysqli_num_rows($result);
    $i=0;
	$uiid = -1;
	$userid = "";
	while ($i < $num) 
	{
		if($row = $result->fetch_assoc())
		{
			$uiid=$row["id"];
			$userid=$row["userid"];
			//echo "<b>$uiid $userid</b><br>Phone: $phone<br>City: $city<br>Country: $country<br><hr><br>";
		}
		$i++;  	
	}

		$responseMessage = "";
	
include("bpwelcome.php");

	if ( strlen($responseMessage) == 0 )
	{
		echo "\n user_body = " . $user_body . "\n";
		
		if(startsWith($user_body, "lg:"))
		{
			$responseMessage = process_group($con);
		}
		else if(startsWith($user_body, "query:"))
		{
			$responseMessage = process_query($con, $user_body, $uiid);
		}
		else if(startsWith($user_body, "ans:"))
		{
			$responseMessage = process_answer($con, $user_body, $uiid);
		}
		else if(startsWith($user_body, "lq:"))
		{
			$responseMessage = process_list_queries($con, $user_body, $uiid);
		}
		else if(startsWith($user_body, "la:"))
		{
			$responseMessage = process_list_answers($con, $user_body, $uiid);
		}
		else if(startsWith($user_body, "next:"))
		{
			$responseMessage = process_list_next($con, $user_body, $uiid);
		}
		else if(startsWith($user_body, "fg:"))
		{
			$responseMessage = process_following_group($con, $user_body, $uiid);
		}
		else if(startsWith($user_body, "fq:"))
		{
			$responseMessage = process_following_query($con, $user_body, $uiid);
		}
		else if(startsWith($user_body, "create:"))
		{
			$groupName = trim(substr($user_body, 7));
			
			$query = "INSERT INTO bpgroups ( `name`, ownerid ) VALUES ('" . $groupName 	. "','".  $uiid . "' )";
			if ( !mysqli_query($con,$query) )
			{
				//echo("Error description: " . mysqli_error($con));
				$responseMessage = "group '" . $groupName . "' create error: " . mysqli_error($con);
			}
			else
			{
				
				$responseMessage = "group '" . $groupName . "' created.";
			}
			$query = "";
				
			
		}
		else // help
		{
			$responseMessage = "Please input any of the following comments such as 'group:', 'query:', 'create:' ";
		}
			
		$query = 
			"Update bpusers set "
			."`phone`  ="."'".$user_phone 	."',"
			."`city`   ="."'".$user_city 	."',"
			."`state`  ="."'".$user_state 	."',"
			."`country`="."'".$user_country ."',"
			."`zip`    ="."'".$user_zip 	."' where `id` = " . $uiid; 
			;
	}
	
	//echo $query;
	$fh = fopen($myFile, 'a+') or die("can't open file");
	fwrite($fh, "4444".PHP_EOL);
	fclose($fh);	
	
	if (strlen($query)>0 )
	{
		//mysql_query($query);
		if (!mysqli_query($con,$query))
		{
			//echo("Error description: " . mysqli_error($con));
		}
	}
	
	
	
include("dbbpclose.php");
	
	$fh = fopen($myFile, 'a+') or die("can't open file");
	fwrite($fh, "uiid=(".$uiid.") query=(".$query.")".PHP_EOL);
	fclose($fh);	
	
?>
<Response>
<?php

	if ( strlen($responseMessage) != 0 )
	{
		echo  "<Message>" . $responseMessage . "</Message>";
		echo  "<query>" . $query . "</query>";
	}
?>
</Response>