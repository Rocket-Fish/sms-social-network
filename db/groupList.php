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

	$query = "select id, ownerid, (select userid from bpusers u where u.id = g.ownerid) as owner, name, comment from bpgroups g";
	
	if (!($result=mysqli_query($con,$query)))
	{
		echo "Error description: " . mysqli_error($con) ;
	}	
	
	// Return the number of rows in result set
	$num=mysqli_num_rows($result);
	
    //var_dump ($result);
    //exit;
    
?><table border=1>	
<caption>Table GROUPS</caption>
<tr>
<td>
	id </td><td>
	ownerid </td><td>
	owner </td><td>
	name </td><td>
	description </td><td></td>
</tr>		
<?php	

	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
?>	
<tr>
	<td><?php echo $row["id"] ?></td>
	<td><?php echo $row["ownerid"] ?></td>
	<td><?php echo $row["owner"] ?></td>
	<td><?php echo $row["name"] ?></td>
	<td><?php echo $row["comment"] ?></td>
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

