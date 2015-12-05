<html>
<body>
<?php
	error_reporting(E_ALL);

include("smssndbconn.php");
include("menu.php");
include("util.php");
include("dbutil.php");
include("../BreakPoverty/bpprocesses.php");

	add_menu("../");


	$array = getRequests();
	
	$uiid = "";
	$message = "";
	
	for($i = 0; $i < count($array); $i ++) 
	{
		if( false == strpos($array[$i], "="))
			continue;
	
		list($name, $value) = explode("=", $array[$i]);
		
		if($name=="message")		$message 	 = urldecode ($value);
		if($name=="id")				$uiid	 	 = urldecode ($value);
	}
	
	//$prompt = "";
	$responseMessage = "";
	if($message!="" && $uiid!="")
	{
		$ref = ""; 
		$content = "";
		$cmd = parseCommand($message, $ref, $content);
		$responseMessage = "parseCommand returns [". $cmd ."] ref=[".$ref."] content=[".$content."]"; 
		
		$con = dbopen();
		
		if($cmd == "lg") // list groups
		{
			$responseMessage = process_group($con);
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
			$responseMessage = process_following_group($con, $uiid, $ref, $content);
		}
		else if($cmd == "fq")
		{
			$responseMessage = process_following_query($con, $uiid, $ref, $content);
		}
		else if($cmd == "cg") // create group
		{
			$responseMessage = process_create_group($con, $uiid, $ref, $content);
		}
		
		dbclose($con);
		
		
		/*
		$con = dbopen();
	
		$sql = "UPDATE bpusers set "
			.  " userid='".$userid."',"
			.  " city='".$city."',"
			.  " state='".$state."',"
			.  " country='".$country."',"
			.  " zip='".$zip."' "
			.  " where id=".$uiid
			;
			
		//echo "<br>sql = [".$sql."]<br>";
		
		if ( !mysqli_query($con,$sql) )
		{
			$prompt = "Error in add userid, city, state, country, or zip " . $userid . " into database: " . mysqli_error($con);
		}
		else
		{
			$prompt = "userid, city, state, country, or zip " . $userid . " has been added into database";
		}
		dbclose($con);
		*/
	}
	
?>

<br><br>
<center>

<?php
	if($uiid == "")
	{
		echo "Page for Send Message.";
	}
	else
	{
?>
<form action="userMessage.php" method="post">
	<table>
	<tr>
		<td colspan=2>
<?php
		if($message=="")
		{
			echo "Please input a message.";
		}
		else
		{
			//$prompt = "Message received";
			echo $responseMessage;
		}
?>	
		</td>
		<td></td>
	</tr>
	<tr>
		<td>message:</td><td><input type="text" name="message" value="" maxlength="160" size="100"></td>
	</tr>
	<tr>
		<td><input type="hidden" name="id" value="<?php echo $uiid ?>"></td>
		<td><input type="submit" value="Submit"></td>
	</tr>
	</table>
</form> 
<?php
	}
?>
</center>

</body></html>