<html>
<body>

<?php
	error_reporting(E_ALL);

	include("dbbpconnect.php");

	$query = "select * from bpusers";
	
	
	if (!($result=mysqli_query($con,$query)))
	{
		echo "Error description: " . mysqli_error($con) ;
	}	
	
	
	// Return the number of rows in result set
	$num=mysqli_num_rows($result);
	
    //var_dump ($result);
    //exit;

    echo "<br> number of records = ";
    echo $num;
    
	
    
    $i=0;
?><table border=1>	
<tr>
<td>
	uiid </td><td>
	userid </td><td>
	phone </td><td>
	city </td><td>
	state </td><td>
	country </td><td>
	zip </td><td>
</td>
</tr>		
<?php	

	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			
	//while ($i < $num) {
	
		//$uiid=mysql_result($result,$i,"id");
		//$userid=mysql_result($result,$i,"userid");
		//$phone=mysql_result($result,$i,"phone");
		//$city=mysql_result($result,$i,"city");
		//$state=mysql_result($result,$i,"state");
		//$country=mysql_result($result,$i,"country");
		//$zip=mysql_result($result,$i,"zip");
		
		$uiid=$row["id"];
		$userid=$row["userid"];
		$phone=$row["phone"];
		$city=$row["city"];
		$state=$row["state"];
		$country=$row["country"];
		$zip=$row["zip"];
?>	
<tr>
<td>
	<?php echo $uiid ?></td><td>
	<?php echo $userid ?></td><td>
	<?php echo $phone ?></td><td>
	<?php echo $city ?></td><td>
	<?php echo $state ?></td><td>
	<?php echo $country ?></td><td>
	<?php echo $zip ?></td><td>
</td>
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