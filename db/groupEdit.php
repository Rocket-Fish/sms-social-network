<html>
<body>
<?php
	error_reporting(E_ALL);

include("smssndbconn.php");
include("menu.php");
include("util.php");
include("dbutil.php");

	add_menu("../");


	$array = getRequests();
	
	$uiid = "";
	$id = 0;
	$ownerid = 0;
	$existComment = "";
	$name = "";
	$comment = "";
	$userid = "";
	
	for($i = 0; $i < count($array); $i ++) 
	{
		if( false == strpos($array[$i], "="))
			continue;
	
		list($name_, $value) = explode("=", $array[$i]);
		
		if($name_=="name") 		$name	 = urldecode ($value);
		if($name_=="comment") 	$comment = urldecode ($value);
		if($name_=="ownerid")	$uiid	 = urldecode ($value);
	}

	if($uiid!="")
	{
		$userid = getUserId($uiid);
		
		if($name=="" )
		{
			$prompt = "Please input group name and descriptions.";
		}
		else
		{
			$con = dbopen();
		
			{  // check if the group already exist
				$query = "select id, ownerid, name, comment from bpgroups where name=" . $name;
				
				if (($result=mysqli_query($con,$query)))
				{
					while($row = $result->fetch_assoc()) 
					{
						$id =  $row["id"];
						$ownerid = $row["ownerid"];
						$existComment = $row["comment"];
						break;
					}
					mysqli_free_result($result);
				}	
			}
			
			if($id!=0)
			{
				if($ownerid!=$uiid)
				{
					$prompt = "Same name group already exist";
				}
				else
				{  // update group
					$sql = "UPDATE bpgroups set "
						.  " name='".$name."',"
						.  " comment='".$comment."',"
						.  " where id=".$id
						;
						
					//echo "<br>sql = [".$sql."]<br>";
					
					if ( !mysqli_query($con,$sql) )
					{
						$prompt = "Error in updating group '" . $name. "': ". mysqli_error($con);
					}
					else
					{
						$prompt = "Successfully updated group '" . $name . "'.";
					}
				}
			}
			else // id == 0
			{  // insert a new group
				$sql = "INSERT into bpgroups (ownerid, name, comment ) values( " 
					. $uiid . ",'".  $name ."','".$comment."')";
					
				//echo "<br>sql = [".$sql."]<br>";
				
				if ( !mysqli_query($con,$sql) )
				{
					$prompt = "Error in creating group '" . $name. "': ". mysqli_error($con);
				}
				else
				{
					$prompt = "Successfully created group '" . $name . "'.";
				}
			}
			dbclose($con);
		}
	}
	
?>

<br><br>
<center>

<?php
	if($uiid == "")
	{
		echo "Group Editor.";
	}
	else
	{
?>
<form action="groupEdit.php" method="post">
	<table>
	<tr>
		<td colspan=2>
<?php
		echo $prompt;
?>	
		</td>
		<td></td>
	</tr>
	<tr>
		<td>Owner:</td><td><?php echo $userid ?></td>
	</tr>
	<tr>
		<td>Name:</td><td><input type="text" name="name" value="<?php echo $name ?>"></td>
	</tr>
	<tr>
		<td>Description:</td><td><textarea rows="4" cols="50" name="comment" ><?php echo $comment ?></textarea></td>
	</tr>
	<tr>
		<td><input type="hidden" name="ownerid" value="<?php echo $uiid ?>"></td>
		<td><input type="submit" value="Submit"></td>
	</tr>
	</table>
</form> 
<?php
	}
?>
</center>

</body></html>