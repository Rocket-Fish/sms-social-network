<html>
<body>

<?php
	error_reporting(E_ALL);
	include("dbbpconnect.php");

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
	
	include("dbbpclose.php");

   
//echo "<br><br>============================<br>";
/*    
$txt1 = "Learn PHP";
$txt2 = "W3Schools.com";
$x = 5;
$y = 4;

echo "<h2>$txt1</h2>";
echo "Study PHP at $txt2<br>";

echo $x + $y;

echo "num=[";  
echo $num ;
echo "]";
    
echo "<br><br>============================<br>";
*/    

?>

<br>
end    

</html>
</body>  