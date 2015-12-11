<?php
header("content-type: text/xml");

include("../db/smssndbconn.php");
include("../db/util.php");
include("../db/dbutil.php");
include("bpwelcome.php");
include("bpprocesses.php");

	// header("content-type: text/html");
	error_reporting(E_ALL);

	$stringData = file_get_contents("php://input");
	//echo $stringData . "\n";

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

	for($i = 0; $i < count($splittedString); $i ++) {
	
		if( false == strpos($array[$i], "="))
			continue;
	
		list($name, $value) = explode("=", $array[$i]);
		
		if($name=="FromZip") 		$user_zip 		 = urldecode ($value);
		if($name=="FromState") 		$user_state 	 = urldecode ($value);
		if($name=="FromCity") 		$user_city 		 = urldecode ($value);
		if($name=="FromCountry") 	$user_country 	 = urldecode ($value);
		if($name=="From") 			$user_phone 	 = urldecode ($value);
		if($name=="Body") 			$user_body 		 = urldecode ($value);
	}
	
	date_default_timezone_set("America/Toronto");
	
	$fh = fopen($myFile, 'a+') or die("can't open file");
	fwrite($fh, date(DATE_ATOM)." ".$stringData.PHP_EOL);
	fclose($fh);	
	
	$con = dbopen();

    $query = "SELECT id, userid FROM bpusers where phone='".$user_phone."'";
	if (!($result=mysqli_query($con,$query)))
	{
		echo "Error description: " . mysqli_error($con) ;
	}	
    
	$uiid = -1;
	$userid = "";
	if($row = $result->fetch_assoc())
	{
		$uiid=$row["id"];
		$userid=$row["userid"];
		//echo "<b>$uiid $userid</b><br>Phone: $phone<br>City: $city<br>Country: $country<br><hr><br>";
	}
	
	$fh = fopen($myFile, 'a+') or die("can't open file");
	fwrite($fh, date(DATE_ATOM)." uiid=".$uiid.", userid=[".$userid."]".PHP_EOL);
	fclose($fh);	

	$responseMessage = bpwelcome($con, $uiid, $userid, $user_body, $user_phone, $user_city, $user_state, $user_country, $user_zip);
	
	if ( strlen($responseMessage) == 0 )
	{
		$ref = ""; 
		$content = "";
		$cmd = parseCommand($user_body, $ref, $content);
		$responseMessage = "parseCommand returns [". $cmd ."] ref=[".$ref."] content=[".$content."]"; 
		
		$con = dbopen();
		
		if($cmd == "lg") // list groups
		{
			$responseMessage = process_list_groups($con, $uiid,0);
		}
		else if($cmd == "query")
		{
			$responseMessage = process_query($con, $uiid, $ref, $content);
		}
		else if($cmd == "ans")
		{
			$responseMessage = process_answer($con, $uiid, $ref, $content);
		}
		else if($cmd == "lq")
		{
			$responseMessage = process_list_queries($con, $uiid, $ref, $content); // ref for groupId, content for queryId
		}
		else if($cmd == "la")
		{
			$responseMessage = process_list_answers($con, $uiid, $ref, $content, 0);
		}
		else if($cmd == "next")
		{
			$responseMessage = process_list_next($con, $uiid);
		}
		else if($cmd == "fg")
		{
			$responseMessage = process_following_group($con, $uiid, $ref);
		}
		else if($cmd == "fq")
		{
			$responseMessage = process_following_query($con, $uiid, $ref);
		}
		else if($cmd == "cg") // create group
		{
			$responseMessage = process_create_group($con, $uiid, $ref, $content);
		}
		else // help
		{
			$responseMessage = "Please input any of the following comments such as '*lq*','*lg*', '*query*', '*cg*' ";
		}
		dbclose($con);
	}
?><Response>
<?php

	if ( strlen($responseMessage) != 0 )
	{
		echo  "<Message>" . $responseMessage . "</Message>";
		//echo  "<query>" . $query . "</query>";
	}
?>
</Response>