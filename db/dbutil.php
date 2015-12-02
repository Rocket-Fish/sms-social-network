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

function parseCommand($message, &$ref, &$content)
// input: message
// output: groupId, groupName, questionId, content
// return: command
{
	$content = $message;
	$ref = "";
	$first_star = strcspn($message, "*",0); 
	
	if($first_star == strlen($message))
		return "";
	
	$second_star = strcspn($message, "*",$first_star+1); 
	
	if($second_star == strlen($message) - $first_star - 1)
		$second_star = -1;

	if($second_star ==-1)
		return "";
	
	$cmd = substr($message, $first_star+1, $second_star);
	
	/////////////////////
	
	if($first_star>0)
		$rest = substr($message, 0, $first_star);
	else
		$rest = "";
	
	$rest = $rest . substr($message, $first_star + 1 + $second_star + 1);

	/////////////////////
	
	
	$content = $rest;
	$first_at = strcspn($rest, "@"); 
	if($first_at == strlen($rest))
	{
		return $cmd;
		//$msg = "first @ at [". $first_at . "], second @ at [". $second_at ."] cmd=[".$cmd."] ref=[".$ref."] content=[".$rest."]";
		//return $msg;
	}
	$second_at = strcspn($rest, "@",$first_at+1); 
	
	if($second_at == strlen($rest) - $first_at - 1)
	{
		return $cmd;
		//$msg = "first @ at [". $first_at . "], second @ at [". $second_at ."] cmd=[".$cmd."] ref=[".$ref."] content=[".$rest."]";
		//return $msg;
	}
	
	$ref = substr($rest, $first_at+1, $second_at);
	
	if($first_at>0)
		$content = substr($rest, 0, $first_at);
	else
		$content = "";
	
	$content = $content . substr($rest, $first_at + 1 + $second_at + 1);
	
//	$msg = "first * at [". $first_star . "], second * at [". $second_star ."] cmd=[".$cmd."] content=[".$rest."]";

	$msg = "cmd=[".$cmd."] ref= [".$ref."] content=[".$content."]";
//	echo "Inside the function: ".$msg;
//	return $msg;
	return $cmd;
}

?>