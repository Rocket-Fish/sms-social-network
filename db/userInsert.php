<html>
<body>
<?php
	error_reporting(E_ALL);

include("smssndbconn.php");
include("menu.php");
include("util.php");
	add_menu("../");

	$array = getRequests();
	
	$phone = "";
	
	for($i = 0; $i < count($array); $i ++) 
	{
		if( false == strpos($array[$i], "="))
			continue;
	
		list($name, $value) = explode("=", $array[$i]);
		
		if($name=="From")
		{
			$phone = urldecode ($value);
		}
	}
	
?>

<br><br>
<center>
<form action="insert.php" method="post">
	<table>
	<tr>
		<td colspan=2>
<?php		
	if($phone=="")
	{
		echo "Please input a valid telephone number.";
	}
	else
	{
		$con = dbopen();
		
			$sql = "INSERT INTO bpusers ( phone ) VALUES ('" . $phone 	. "' )";
			if ( !mysqli_query($con,$sql) )
			{
				echo "Error in add phone " . $phone . " into database: " . mysqli_error($con);
			}
			else
			{
				echo "Phone " . $phone . " has been added into database";
			}
			$query = "";
		
		dbclose($con);
	}
?>	
		</td>
		<td></td>
	</tr>
	<tr>
		<td>Phone:</td>
		<td><input type="text" name="From" value=""></td>
	</tr>
	<tr>
		<td></td>
		<td><input type="submit" value="Submit"></td>
	</tr>
	</table>
</form> 
</center>

</body></html>