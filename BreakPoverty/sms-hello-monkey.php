<?php
	$stringData = file_get_contents("php://input");

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
	$fh = fopen($myFile, 'a+') or die("can't open file");
	fwrite($fh, "1111".PHP_EOL);
	fclose($fh);	

//    [5] =>  FromZip    =
//    [7] =>  FromState  =ON
//    [9] =>  FromCity   =AJAX+PICKERING
//    [11] => FromCountry=CA
//    [17] => From       =%2B12899239409
//    [10] => Body       =Vdbdbdbnfdh
	
	for($i = 0; $i < count($splittedString); $i ++) {
	
		list($name, $value) = explode("=", $array[$i]);
		
		if($name=="FromZip") 		$user_zip 		 = $value;
		if($name=="FromState") 		$user_state 	 = $value;
		if($name=="FromCity") 		$user_city 		 = $value;
		if($name=="FromCountry") 	$user_country 	 = $value;
		if($name=="From") 			$user_phone 	 = $value;
		if($name=="Body") 			$user_body 		 = $value;
	}

	$fh = fopen($myFile, 'a+') or die("can't open file");
	fwrite($fh, "2222".PHP_EOL);
	fclose($fh);	
	
include("dbbpconnect.php");

    $query = "SELECT id FROM bpusers where phone='".$user_phone."'";
    
    $result = mysql_query($query);
    $num = mysql_numrows($result);
    $i=0;
	$uiid = -1;
	while ($i < $num) {
		$uiid=mysql_result($result,$i,"id");
		//echo "<b>$uiid $userid</b><br>Phone: $phone<br>City: $city<br>Country: $country<br><hr><br>";
		$i++;  	
	}

	$fh = fopen($myFile, 'a+') or die("can't open file");
	fwrite($fh, "33333".PHP_EOL);
	fclose($fh);	
	
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
	}
	else
	{
		$query = 
			"Update bpusers set "
			."`phone`  ="."'".$user_phone 	."',"
			."`city`   ="."'".$user_city 	."',"
			."`state`  ="."'".$user_state 	."',"
			."`country`="."'".$user_country ."',"
			."`zip`    ="."'".$user_zip 	."'" 
			;
	}
	
	$fh = fopen($myFile, 'a+') or die("can't open file");
	fwrite($fh, "4444".PHP_EOL);
	fclose($fh);	
	
	mysql_query($query);
include("dbbpclose.php");
	
	$fh = fopen($myFile, 'a+') or die("can't open file");
	fwrite($fh, "uiid=(".$uiid.") query=(".$query.")".PHP_EOL);
	fclose($fh);	
	
	header("content-type: text/xml");
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>

<Response>
</Response>