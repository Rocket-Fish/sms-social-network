<?php

function startsWith($haystack, $needle) {
    // search backwards starting from haystack length characters from the end
	//echo "\n startsWith called \n";
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
}

function sendMessage($phone, $text)
{
	if(substr($phone, 0,1)=="+")
	{
	// Install the library via PEAR or download the .zip file to your project folder.
	// This line loads the library
	require '../Twilio-Server/Services/Twilio.php';

	$sid = "ACd4dbf05b911868c0d3b7517bc5b2aee5"; // Your Account SID from www.twilio.com/user/account
	$token = "1f59fcb52b10490032e3587917f39b2a"; // Your Auth Token from www.twilio.com/user/account

	$client = new Services_Twilio($sid, $token);
	$message = $client->account->messages->sendMessage(
  		'16479553883', // From a valid Twilio number
  		$phone, //'12899239409', // Text this number
  		$text //"Hello RECEIVER!"
	);
	}

//echo '<h2> Sending Messages with Twilio </h2>';
//print $message->sid;

}



function process_group($con)
{
	$sql = "SELECT id, name, type, comment FROM bpgroups";
	
	if (!($result=mysqli_query($con,$sql)))
	{
		echo "Error description: " . mysqli_error($con) ;
	}	
	
	$num = mysqli_num_rows($result);
	$i=0;
	$id = -1;
	$name = "";
	$type = -1;
	$comment = "";
	
//$responseMessage = "Which of the following will you like to check. send 'group:groupname' to see detail: ";
	$responseMessage = "";

	while ($i < $num) 
	{
		
		if($row = $result->fetch_assoc())
		{
			$id = $row["id"];
			$name = $row["name"];
			$type = $row["type"];
			$comment = $row["comment"];
			
			if($i!=0)
				$responseMessage .= ", ";
			
			$responseMessage .= $id . " (" . $name . ")";
		}
		
		$i++;  	
	}
	return $responseMessage;
}

function process_query($con, $uiid, $groupname, $description)
{
	$groupid = getGroupId($con, $groupname); 
	
	if($groupid==0)
	{
		return "Failed in inserting query: group [".$groupname."] not found.";
	}
	if($uiid==0)
	{
		return "Failed in inserting query: unknown user id [".$uiid."].";
	}
	if(strlen($description)==0)
	{
		return "Failed in inserting query: empty description.";
	}
	
	$sql = "INSERT INTO bpquerys ( ownerid, groupid, content ) VALUES "
			."('" . $uiid. "','".  $groupid . "','".  $description. "' )";
	if ( !mysqli_query($con,$sql) )
	{
		//echo("Error description: " . mysqli_error($con));
		$responseMessage = "Query create error: " . mysqli_error($con);
	}
	else
	{
		$responseMessage = "Query [" .  mysqli_insert_id($con) . "] created in group [".$groupid."].";
	}
	return $responseMessage;
}

function process_answer($con, $uiid, $ref, $content)
{
	$queryid=$ref;
	if($queryid=="" || $queryid==0)
	{
		return "Please specilfy query number as @query number@: e.g. @123@ ";
	}
	
	$sql = "INSERT INTO bpanswers ( ownerid, queryid, content ) VALUES "
			. "('" . $uiid. "','".  $queryid . "','".  $content. "' )";
	if ( !mysqli_query($con,$sql) )
	{
		return "answer create error: " . mysqli_error($con);
	}
	
	$responseMessage = "answer created.";
	
	/*
	////// post message to questioner ////
	$sql = "select phone from bpusers where id in (SELECT ownerid from bpquerys where id=" 
		. $queryid . ") ";

	if (!($result=mysqli_query($con,$sql)))
	{
		echo "Error description: " . mysqli_error($con) ;
	}	

	$num = mysqli_num_rows($result);
	$i=0;
	$phone = "";

	while ($i < $num) 
	{
		if($row = $result->fetch_assoc())
		{
			$phone = $row["phone"];
			//echo " phone of queryer = " . $phone . "\n";

			sendMessage($phone, $content); 

		}
		$i++;  	
	}
	////// post message to group followers ////
	
	$sql = "select phone from bpusers where id in (SELECT uiid from bpgroupfollow where groupid in "
		   . " ( select groupid from bpquerys where id=" . $queryid . " ) )" ;

	if (!($result=mysqli_query($con,$sql)))
	{
		echo "Error description: " . mysqli_error($con) ;
	}	

	$num = mysqli_num_rows($result);
	$i=0;
	$phone = "";

	while ($i < $num) 
	{
		if($row = $result->fetch_assoc())
		{
			$phone = $row["phone"];
			//echo " phone of group = " . $phone . "\n";
			sendMessage($phone, $content); 
		}
		$i++;
	}
	//////
	
	//function sendMessage($phone, $text)
	*/
	
	return $responseMessage;
}

function find_group_id($con, $groupname)
{
	$sql = "select id from bpgroups where name='" . $groupname . "'";
	
	if ($result=mysqli_query($con,$sql))
	{
		if ($result->num_rows > 0) 
		{
			if($row = $result->fetch_assoc()) 
			{
				$groupid = $row["id"];
				return $groupid;
			}
		}
	}	
	mysqli_free_result($result);
	return 0;
}			

function process_save_last_query($con, $cmd, $groupid, $queryid, $answerid, $uiid)
{
	$sql = "update bpusers set currcmd='".$cmd."'"
			.", currgroupid=".$groupid
			.", currqueryid=".$queryid
			.", curranswerid=".$answerid
			." where id=".$uiid;
			
	if ( !mysqli_query($con,$sql) )
	{
	    echo "sql=" . $sql . "\n";
		echo "Error description: " . mysqli_error($con). "\n";
		//$responseMessage = "query create error: " . mysqli_error($con);
	}
}

function process_list_queries($con, $user_body, $uiid)
{
	$subbody = trim(substr($user_body, 3));
	$pos = strpos($subbody, ":");

    $groupid = 0;
    $queryid = 0;
    
	if ($pos === false) {
		$groupid = $subbody;
		$queryid = 0;
	} 
	else 
	{
		$groupname=substr($subbody, 0, $pos);
		$queryid = intval(substr($subbody, $pos+1));
		if(strlen($groupname))
		{
			$groupid = intval($groupname); 
			
			if($groupid==0)
			{
				$groupid = find_group_id($con, $groupname);
			}
		}
	}

	process_save_last_query($con, "lq", $groupid, $queryid, 0, $uiid);
	
	$sql = "SELECT id, ownerid, groupid, content FROM bpquerys";
	if($queryid>0)
		$sql .= " where id=" . $queryid;
	else if($groupid>0)
		$sql .= " where groupid=" . $groupid;
		
	$sql .= " order by id desc "; //"fetch first 1 rows only";
	
	if (!($result=mysqli_query($con,$sql)))
	{
	    echo " sql=". $sql;
		echo "Error description: " . mysqli_error($con) ;
	}	
	
	$num = mysqli_num_rows($result);
	$i=0;
	$id = 0;
	$ownerid = 0;
	$groupid = 0;
	$content = "";
	
	$responseMessage = "";

    //echo "query = [" . $sql . "]\n";				
    //echo "num = [" . $num . "]\n";				
	
	while ($i < $num) 
	{
		if($row = $result->fetch_assoc())
		{
			$id = $row["id"];
			$ownerid = $row["ownerid"];
			$groupid = $row["groupid"];
			$content = $row["content"];

			//echo "id = [" . $id . "] content = [" . $content . "]\n";
			
			$responseMessage = $id . ": " . $content;
			return $responseMessage;
		}
		$i++;  	
	}
	return $responseMessage;
}

function process_list_answers($con, $user_body, $uiid)
{
	return $responseMessage;
}

function get_next_queryid($con, $groupid, $queryid)
{
	$sql = "SELECT max(id) as qid from bpquerys where id< ". $queryid ;
	if($groupid!=0)
	    $sql .= " and groupid=". $groupid ;
	
	if (!($result=mysqli_query($con,$sql)))
	{
		echo "Error description: " . mysqli_error($con) ;
	}	
	
	$num = mysqli_num_rows($result);
	$i=0;
	$qid = 0;
	
	while ($i < $num) 
	{
		if($row = $result->fetch_assoc())
		{
			$qid = intval($row["qid"]);
			if($qid>0)
				return $qid;	
		}
		$i++;
	}

	$sql = "SELECT max(id) as qid from bpquerys "; 
	if($groupid!=0)
	    $sql .= " where groupid=". $groupid ;
	
	if (!($result=mysqli_query($con,$sql)))
	{
		echo "Error description: " . mysqli_error($con) ;
	}	
	
	$num = mysqli_num_rows($result);
	$i=0;
	$qid = 0;
	
	while ($i < $num) 
	{
		if($row = $result->fetch_assoc())
		{
			return intval($row["qid"]);
		}
		$i++;  	
	}
	return 0;
}

function process_list_next($con, $user_body, $uiid)
{
	$sql = "SELECT currcmd, currgroupid, currqueryid, curranswerid FROM bpusers where id=".$uiid;
	
	if (!($result=mysqli_query($con,$sql)))
	{
		echo "Error description: " . mysqli_error($con) ;
	}	
	
	$num = mysqli_num_rows($result);	
	$i=0;
	$cmd = "";
	$groupid = 0;
	$queryid = 0;
	$answerid = 0;
	
	$responseMessage = "";

	while ($i < $num) 
	{
		if($row = $result->fetch_assoc())
		{
			$groupid = $row["currgroupid"];
			$queryid = $row["currqueryid"];
			$answerid = $row["curranswerid"];
			$cmd = $row["currcmd"];
			
			if ( $cmd == "lq")
			{
				$queryid = get_next_queryid($con, $groupid, $queryid);
				$cmd .= ":" . $groupid . ":" . $queryid;
				//echo " cmd = " . $cmd . "\n";
				return process_list_queries($con, $cmd, $uiid);
			}			
		}
		$i++;  	
	}

	return $responseMessage;
}

function process_following_group($con, $user_body, $uiid)
{
	$groupid = intval(trim(substr($user_body, 3)));
	if($groupid==0)
		return "";
	
	$sql = "INSERT INTO bpgroupfollow ( uiid, groupid ) VALUES ('" . $uiid. "','".  $groupid . "' )";
	if ( !mysqli_query($con,$sql) )
	{
		//echo("Error description: " . mysqli_error($con));
		$responseMessage = "query create error: " . mysqli_error($con);
	}
	else
	{
		$responseMessage = "following group " . $groupid . " succeed.";
	}
	return $responseMessage;    
}

function process_following_query($con, $user_body, $uiid)
{
	return $responseMessage;
}

function process_create_group($con, $uiid, $groupName, $description)
{
	$name = trim($groupName);
	if(strlen($name) == 0)
		return "Can not create a group without name.";
	
	$id = 0;
	$ownerid = 0;
	
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
			return "Same name group already exist";
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
				return "Error in updating group '" . $name. "': ". mysqli_error($con);
			}
			else
			{
				return "Successfully updated group '" . $name . "'.";
			}
		}
	}
	else // id == 0
	{  // insert a new group
		$sql = "INSERT into bpgroups (ownerid, name, comment ) values( " 
			. $uiid . ",'".  $name ."','".$description."')";
			
		//echo "<br>sql = [".$sql."]<br>";
		
		if ( !mysqli_query($con,$sql) )
		{
			return "Error in creating group '" . $name. "': ". mysqli_error($con);
		}
		else
		{
			return "Successfully created group '" . $name . "'.";
		}
	}
	
	return "Unknown error.";
}

?>
