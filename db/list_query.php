<html>
<body>

<?php
	error_reporting(E_ALL);
	
include("smssndbconn.php");
include("menu.php");

	add_menu("../");

	$con = dbopen();
	
?>
<center><table><tr><td>
<?php	
	
//////////////////////////////////
	echo "Groups: \n";

	$query = "select * from bpgroups";
	
	if (!($result=mysqli_query($con,$query)))
	{
		echo "Error description: " . mysqli_error($con) ;
	}	
	
	// Return the number of rows in result set
	$num=mysqli_num_rows($result);

    echo "<br> number of records = ". $num;
    
    $i=0;
?><table border=1>	
<tr>
<td>
	id </td><td>
	ownerid </td><td>
	name </td><td>
	type </td><td>
	comment </td><td>
</td>
</tr>		
<?php	
	if ($result->num_rows > 0) 
	{
		// output data of each row
		while($row = $result->fetch_assoc()) {
?>	
<tr><td><?php echo $row["id"]; ?>
</td><td><?php echo $row["ownerid"]; ?>
</td><td><?php echo $row["name"]; ?>
</td><td><?php echo $row["type"]; ?>
</td><td><?php echo $row["comment"]; ?>
</td></tr>		
<?php	
		  //$i++;  	
		}
	} else {
		echo "0 results";
	}
?>
</table>
<?php	

	// Free result set
	mysqli_free_result($result);
	
	
//////////////////////////////////
	echo "combined table: \n";

	$query = "select id, ownerid, "
		. "(select userid from bpusers b where b.id=a.ownerid) as owner ,"
		. "(select name from bpgroups where id in (select groupid from bpquerys c where c.id=a.queryid)) as groupname , "
		. "queryid, content, timewhen from bpanswers a";
	
	if (!($result=mysqli_query($con,$query)))
	{
		echo "Error description: " . mysqli_error($con) ;
	}	
	
	$num=mysqli_num_rows($result);

    echo "<br> number of records = ". $num;
    
    $i=0;
?><table border=1>	
<tr>
<td>
	id </td><td>
	ownerid </td><td>
	owner </td><td>
	groupname </td><td>
	queryid </td><td>
	content </td><td>
	timewhen </td><td>
</td>
</tr>		
<?php	
	if ($result->num_rows > 0) 
	{
		// output data of each row
		while($row = $result->fetch_assoc()) {
?>	
<tr><td><?php echo $row["id"]; ?>
</td><td><?php echo $row["ownerid"]; ?>
</td><td><?php echo $row["owner"]; ?>
</td><td><?php echo $row["groupname"]; ?>
</td><td><?php echo $row["queryid"]; ?>
</td><td><?php echo $row["content"]; ?>
</td><td><?php echo $row["timewhen"]; ?>
</td></tr>		
<?php	
		  //$i++;  	
		}
	} else {
		echo "0 results";
	}
?>
</table>
<?php	

	// Free result set
	mysqli_free_result($result);
	


	
//////////////////////////////////////////////////
	
	$query = "select id, ownerid, "
		." (select userid from bpusers u where u.id=q.ownerid) as owner,"
		."groupid, "
		." (select name from bpgroups g where g.id=q.groupid) as groupname,"
		."content, timewhen from bpquerys q";
	
	if (!($result=mysqli_query($con,$query)))
	{
		echo "sql=[".$query."]";
		echo "Error description: " . mysqli_error($con) ;
	}	
	
	$num=mysqli_num_rows($result);

    
    $i=0;
?>
<br><br>
<table border=1>	
<caption>Queries</caption>
<tr>
<td>
	id </td><td>
	ownerid </td><td>
	owner </td><td>
	groupid </td><td>
	group </td><td>
	content </td><td>
	timewhen </td><td>
</td>
</tr>		
<?php	
	if ($result->num_rows > 0) 
	{
		// output data of each row
		while($row = $result->fetch_assoc()) {
?>	
<tr><td><?php echo $row["id"]; ?>
</td><td><?php echo $row["ownerid"]; ?>
</td><td><?php echo $row["owner"]; ?>
</td><td><?php echo $row["groupid"]; ?>
</td><td><?php echo $row["groupname"]; ?>
</td><td><?php echo $row["content"]; ?>
</td><td><?php echo $row["timewhen"]; ?>
</td></tr>		
<?php	
		  //$i++;  	
		}
	} else {
		echo "0 results";
	}
?>
<tr><td colspan=7>
<?php    echo "Total number of records: ". $num; ?>
</td></tr>
</table>
<?php	

	// Free result set
	mysqli_free_result($result);

//////////////////////////////////

	$query = "select * from bpanswers";
	
	if (!($result=mysqli_query($con,$query)))
	{
		echo "Error description: " . mysqli_error($con) ;
	}	
	
	$num=mysqli_num_rows($result);

    echo "<br> number of records = ". $num;
    
    $i=0;
?><table border=1>	
<tr>
<td>
	id </td><td>
	ownerid </td><td>
	queryid </td><td>
	content </td><td>
	timewhen </td><td>
</td>
</tr>		
<?php	
	if ($result->num_rows > 0) 
	{
		// output data of each row
		while($row = $result->fetch_assoc()) {
?>	
<tr><td><?php echo $row["id"]; ?>
</td><td><?php echo $row["ownerid"]; ?>
</td><td><?php echo $row["queryid"]; ?>
</td><td><?php echo $row["content"]; ?>
</td><td><?php echo $row["timewhen"]; ?>
</td></tr>		
<?php	
		  //$i++;  	
		}
	} else {
		echo "0 results";
	}
?>
</table>
<?php	

	// Free result set
	mysqli_free_result($result);
	
	dbclose($con);
?>

<br>
end    
</td></tr></table></center>

</html>
</body>  