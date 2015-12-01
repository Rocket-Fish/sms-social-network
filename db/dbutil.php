<?php

function getUserId($uiid)
{
	$userid="";
	$con = dbopen();

	$query = "select userid from bpusers where id=" . $uiid;
	
	if (($result=mysqli_query($con,$query)))
	{
		while($row = $result->fetch_assoc()) 
		{
			$userid =  $row["userid"];
			break;
		}
		mysqli_free_result($result);
	}	
	dbclose($con);
	
	return $userid;
}

?>