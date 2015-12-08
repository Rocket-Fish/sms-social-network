<html>
<body>
<?php
	error_reporting(E_ALL);
	
include("menu.php");
include("smssndbconn.php");

	add_menu("../");
	
?>
<br><br>
<center>
<?php


	$con = dbopen();

	///////////////////////
	
	$query = "select id, uiid, (select userid from bpusers u where u.id = f.uiid) as owner, "
	                ."groupid,(select name from bpgroups g where g.id = f.groupid) as groupname, timewhen from bpgroupfollow f";
	
	if (!($result=mysqli_query($con,$query)))
		echo "Error description: " . mysqli_error($con) ;
	$num=mysqli_num_rows($result);
	
?><table border=1>	
<caption>Table GROUP FOLLOWERS</caption>
<tr>
<td>
	id </td><td>
	uiid </td><td>
	owner </td><td>
	group id </td><td>
	group name </td><td>
	timewhen </td><td></td>
</tr>		
<?php	
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
?>	
<tr>
	<td><?php echo $row["id"] ?></td>
	<td><?php echo $row["uiid"] ?></td>
	<td><?php echo $row["owner"] ?></td>
	<td><?php echo $row["groupid"] ?></td>
	<td><?php echo $row["groupname"] ?></td>
	<td><?php echo $row["timewhen"] ?></td>
</tr>		
<?php	
		}
	} else {
		echo "0 results";
	}
?>
</table>

<?php	

    echo "<br> number of records = ";
    echo $num;

	// Free result set
	mysqli_free_result($result);

	///////////////////////

	echo "<br><br><br>";

	$query = "select id, uiid, (select userid from bpusers u where u.id = f.uiid) as owner, "
	                ."queryid,(select content from bpquerys q where q.id = f.queryid) as content, timewhen from bpqueryfollow f";
	
	if (!($result=mysqli_query($con,$query)))
		echo "Error description: " . mysqli_error($con) ;
	$num=mysqli_num_rows($result);
	
?><table border=1>	
<caption>Table QUERY FOLLOWERS</caption>
<tr>
<td>
	id </td><td>
	uiid </td><td>
	owner </td><td>
	query id </td><td>
	content </td><td>
	timewhen </td><td></td>
</tr>		
<?php	
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
?>	
<tr>
	<td><?php echo $row["id"] ?></td>
	<td><?php echo $row["uiid"] ?></td>
	<td><?php echo $row["owner"] ?></td>
	<td><?php echo $row["queryid"] ?></td>
	<td><?php echo $row["content"] ?></td>
	<td><?php echo $row["timewhen"] ?></td>
</tr>		
<?php	
		}
	} else {
		echo "0 results";
	}
?>
</table>


<?php	

    echo "<br> number of records = ";
    echo $num;

	// Free result set
	mysqli_free_result($result);

	
	dbclose($con);
?>
<br>
</center>
</html>
</body>  

