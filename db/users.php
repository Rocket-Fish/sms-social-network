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

	$query = "select * from bpusers";
	
	if (!($result=mysqli_query($con,$query)))
	{
		echo "Error description: " . mysqli_error($con) ;
	}	
	
	// Return the number of rows in result set
	$num=mysqli_num_rows($result);
	
    //var_dump ($result);
    //exit;

    
    $i=0;
?><table border=1>	
<caption>Table USERS</caption>
<tr>
<td>
	uiid </td><td>
	phone </td><td>
	userid </td><td>
	city </td><td>
	state </td><td>
	country </td><td>
	zip </td><td>
	currcmd </td><td>
	currgroupid </td><td>
	currqueryid </td><td>
	curranswerid </td><td></td>
</tr>		
<?php	

	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
?>	
<tr>
	<td><?php echo $row["id"] ?></td>
	<td><?php echo $row["phone"] ?></td>
	<td><?php echo $row["userid"] ?></td>
	<td><?php echo $row["city"] ?></td>
	<td><?php echo $row["state"] ?></td>
	<td><?php echo $row["country"] ?></td>
	<td><?php echo $row["zip"] ?></td>
	<td><?php echo $row["currcmd"] ?></td>
	<td><?php echo $row["currgroupid"] ?></td>
	<td><?php echo $row["currqueryid"] ?></td>
	<td><?php echo $row["curranswerid"] ?></td>
	<td><a href="editUser.php?id=<?php echo $row["id"] ?>">Edit</a></td>
</tr>		
<?php	
		  //$i++;  	
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