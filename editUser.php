<html>
<body>
<?php
	error_reporting(E_ALL);

include("db/smssndbconn.php");
include("menu.php");
include("util.php");



	$array = getRequests();
	
	$userid = "";
	$city = "";
	$state = "";
	$country = "";
	$zip = "";
	$uiid = "";
	
	for($i = 0; $i < count($array); $i ++) 
	{
		if( false == strpos($array[$i], "="))
			continue;
	
		list($name, $value) = explode("=", $array[$i]);
		
		if($name=="FromZip") 		$zip 		 = urldecode ($value);
		if($name=="FromState") 		$state 	 	= urldecode ($value);
		if($name=="FromCity") 		$city 		 = urldecode ($value);
		if($name=="FromCountry") 	$country 	 = urldecode ($value);
		if($name=="userid")			$userid 	 = urldecode ($value);
		if($name=="id")				$uiid	 	 = urldecode ($value);
	}
	
	if($userid!="" && $uiid!="")
	{
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
	}
	
?>

<br><br>
<center>

<?php
	if($uiid == "")
	{
		echo "User Record Editor.";
	}
	else
	{
		/////////// read from Database
			
		$con = dbopen();

		$query = "select id, phone, userid, city, state, country, zip from bpusers where id=" . $uiid;
		
		if (($result=mysqli_query($con,$query)))
		{
			while($row = $result->fetch_assoc()) 
			{
				$uiid =  $row["id"];
				$phone = $row["phone"];
				$userid =  $row["userid"];
				$city = $row["city"];
				$state = $row["state"];
				$country = $row["country"];
				$zip = $row["zip"];
				break;
			}
			mysqli_free_result($result);
		}	
		dbclose($con);
		
?>
<form action="editUser.php" method="post">
	<table>
	<tr>
		<td colspan=2>
<?php
		if($userid=="")
		{
			echo "Please input a valid userid, city, state, country, or zip.";
		}
		else
		{
			echo $prompt;
		}
?>	
		</td>
		<td></td>
	</tr>
	<tr>
		<td>userid:</td><td><input type="text" name="userid" value="<?php echo $userid ?>"></td>
	</tr>
	<tr>
		<td>city:</td><td><input type="text" name="FromCity" value="<?php echo $city ?>"></td>
	</tr>
	<tr>
		<td>state:</td><td><input type="text" name="FromState" value="<?php echo $state ?>"></td>
	</tr>
	<tr>
		<td>country:</td><td><input type="text" name="FromCountry" value="<?php echo $country ?>"></td>
	</tr>
	<tr>
		<td>zip:</td><td><input type="text" name="FromZip" value="<?php echo $zip ?>"></td>
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