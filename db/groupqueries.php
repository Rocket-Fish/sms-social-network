<?php
include("util.php");
include("../db/smssndbconn.php");
include("menu.php");

add_menu("../");
$con = dbopen();

	$array = getRequests();
	
	$groupid = "";
	
	for($i = 0; $i < count($array); $i ++) 
	{
		if( false == strpos($array[$i], "="))
			continue;
	
		list($name, $value) = explode("=", $array[$i]);
		
		if($name=="id")		$groupid = urldecode ($value);
	}
	
	$sql = "SELECT id, name FROM bpgroups";
	
	if ($groupid != 0)
		$sql .= " where id=". $groupid;
	
	if (!($result=mysqli_query($con,$sql)))
	{
		echo "Error description: " . mysqli_error($con) ;
	}	
	
	$num = mysqli_num_rows($result);
	
	if ($num==0)
	{
?>		
		<center>
			Group <?php echo $groupid ?> not found.
		</center>
<?php	
		return;
	}
	if($row = $result->fetch_assoc())
	{
		 $name = $row["name"];
	}
	
	$query = "select id, ownerid, "
		." (select userid from bpusers u where u.id=q.ownerid) as owner,"
		."groupid, "
		." (select name from bpgroups g where g.id=q.groupid) as groupname,"
		."content, timewhen from bpquerys q"
		." where groupid=".$groupid;
	
	if (!($result=mysqli_query($con,$query)))
	{
		echo "sql=[".$query."]";
		echo "Error description: " . mysqli_error($con) ;
	}	
?>
<center>
<br><br>
<table border=1>	
<caption><h3>Queries of Group <?php echo $groupid; ?>: <?php echo $name; ?></h3></caption>
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
		$number_of_queries = mysqli_num_rows($result);
		
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
?>	
<tr><td colspan=7>Total: <?php echo $number_of_queries ?> records.</td></tr>
<?php
	} else {
		echo "0 results";
	}
?>
</table>
</center>
<?php	
dbclose($con);
	
?>