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



function process_list_groups($con,$uiid, $groupid)
{
	//$sql = "SELECT id, name, type, comment FROM bpgroups";
 
//	if(strlen($content)!=0)
//		$queryid = intval($content); 

	//echo "process_list_queries queryid=[" .$queryid."] content=[".$content."]";
	
	$sql = "SELECT id, name FROM bpgroups";
	
	if ($groupid != 0)
		$sql .= " where id=". $groupid;
	
	$sql .= " order by id desc "; //"fetch first 1 rows only";
	
	if (!($result=mysqli_query($con,$sql)))
	{
	    echo " sql=". $sql;
		echo "Error description: " . mysqli_error($con) ;
	}
	
	//$num = mysqli_num_rows($result);
	$id = 0;
	//$groupid = 0;
	
    //echo "query = [" . $sql . "]\n";				
    //echo "num = [" . $num . "]\n";
	
	while($row = $result->fetch_assoc()) 
	{
		$id = $row["id"];
		$name = $row["name"];

//		process_save_last_query($con, "lg", $groupid, $queryid, 0, $uiid);
		process_save_last_query($con, "lg", $id, 0, 0, $uiid);
//		$responseMessage = $id . ": " . $content;
		$responseMessage = $id . ": " .$name;
		return $responseMessage;
	}
	
	return "group not found.";	

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

function process_list_queries($con, $uiid, $groupname, $content)
{
    $groupid = 0;
	$queryid = 0;
	if(strlen($content)!=0)
		$queryid = intval($content); 

	//echo "process_list_queries queryid=[" .$queryid."] content=[".$content."]";
	
 	$groupid = getGroupId($con, $groupname); 

	
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
	
	//$num = mysqli_num_rows($result);
	$id = 0;
	$ownerid = 0;
	//$groupid = 0;
	$content = "";
	
    //echo "query = [" . $sql . "]\n";				
    //echo "num = [" . $num . "]\n";
	
	while($row = $result->fetch_assoc()) 
	{
		$id = $row["id"];
		$ownerid = $row["ownerid"];
		//$groupid = $row["groupid"];
		$content = $row["content"];

		//echo "id = [" . $id . "] content = [" . $content . "]\n";
			
		$queryid = $id;
		process_save_last_query($con, "lq", $groupid, $queryid, 0, $uiid);
			
		$responseMessage = $id . ": " . $content;
		return $responseMessage;
	}
	
	return "query not found.";
}

function process_list_answers($con, $uiid, $queryid1, $queryid2, $aid)
{
	$queryid = 0;
	if(strlen($queryid1))
		$queryid = intval($queryid1); 
		
		
	if($queryid == 0)
		if(strlen($queryid2)!=0)
			$queryid = intval($queryid2); 
		
	if($queryid == 0)
	{
		return "Please input query id";
	}

	$sql = "SELECT id, content FROM bpanswers ";
	if($aid>0)
		$sql .= "where id=" . $aid .= " order by id desc ";
	else
		$sql .= "where queryid=" . $queryid . " order by id desc ";
	
	if (!($result=mysqli_query($con,$sql)))
	{
	    echo " sql=". $sql;
		echo "Error description: " . mysqli_error($con) ;
	}	
	
	$id = 0;
	$answerid = 0;
	$content = "";
	
	while($row = $result->fetch_assoc()) 
	{
		$id = $row["id"];
		$content = $row["content"];

		//echo "id = [" . $id . "] content = [" . $content . "]\n";
		$answerid = $id;
		process_save_last_query($con, "la", 0, $queryid, $answerid, $uiid);
			
		return  $id . ": " . $content;
	}
	return "answer not found for query [".$queryid."]";
}

function get_next_queryid($con, $groupid, $queryid)
{
	$sql = "SELECT max(id) as qid from bpquerys where id< ". $queryid ;
	if($groupid!=0)
	    $sql .= " and groupid=". $groupid ;
	
	//echo "sql 1 = [".$sql."]<br>";
	
	if (!($result=mysqli_query($con,$sql)))
	{
		echo "Error description: " . mysqli_error($con) ;
	}	
	
	if($row = $result->fetch_assoc())
	{
		$qid = intval($row["qid"]);
		if($qid>0)
		{
			//echo "get_next_queryid 1 returns [".$qid."]";
			return $qid;	
		}
	}

	$sql = "SELECT max(id) as qid from bpquerys "; 
	if($groupid!=0)
	    $sql .= " where groupid=". $groupid ;
	
	//echo "sql 2 = [".$sql."]<br>";
	if (!($result=mysqli_query($con,$sql)))
	{
		echo "Error description: " . mysqli_error($con) ;
	}	
	
	if($row = $result->fetch_assoc())
	{
		//echo "get_next_queryid 1 returns [".intval($row["qid"])."]";
		return intval($row["qid"]);
	}
	
	//echo "get_next_queryid fail.<br>";
	return 0;
}

function get_next_answerid($con, $queryid, $answerid)
{
	if($queryid==0)
		return 0;
	
	$sql = "SELECT max(id) as aid from bpanswers where id< ". $answerid . " and queryid=". $queryid ;
	
	//echo "sql 1 = [".$sql."]<br>";
	
	if (!($result=mysqli_query($con,$sql)))
	{
		echo "Error description: " . mysqli_error($con) ;
	}	
	
	if($row = $result->fetch_assoc())
	{
		$aid = intval($row["aid"]);
		if($aid>0)
		{
			//echo "get_next_queryid 1 returns [".$qid."]";
			return $aid;	
		}
	}

	$sql = "SELECT max(id) as aid from bpanswers where queryid=". $queryid ;
	
	if (!($result=mysqli_query($con,$sql)))
	{
		echo "get_next_answerid 2 = [".$sql."]<br>";
		echo "Error description: " . mysqli_error($con) ;
	}	
	
	if($row = $result->fetch_assoc())
	{
		//echo "get_next_queryid 1 returns [".intval($row["qid"])."]";
		return intval($row["aid"]);
	}
	
	//echo "get_next_queryid fail.<br>";
	return 0;
}

function get_next_groupid($con, $groupid)
{
	
	$sql = "SELECT max(id) as gid from bpgroups where id< ". $groupid;
	
	//echo "sql 1 = [".$sql."]<br>";
	
	if (!($result=mysqli_query($con,$sql)))
	{
		echo "Error description: " . mysqli_error($con) ;
	}	
	
	if($row = $result->fetch_assoc())
	{
		$gid = intval($row["gid"]);
		if($gid>0)
		{
			//echo "get_next_queryid 1 returns [".$qid."]";
			return $gid;	
		}
	}

	$sql = "SELECT max(id) as gid from bpgroups";
	
	if (!($result=mysqli_query($con,$sql)))
	{
//		echo "get_next_answerid 2 = [".$sql."]<br>";
		echo "Error description: " . mysqli_error($con) ;
	}	
	
	if($row = $result->fetch_assoc())
	{
		//echo "get_next_queryid 1 returns [".intval($row["qid"])."]";
		return intval($row["gid"]);
	}
	
	//echo "get_next_queryid fail.<br>";
	return 0;
}

function process_list_next($con, $uiid)
{
	$sql = "SELECT currcmd, currgroupid, currqueryid, curranswerid FROM bpusers where id=".$uiid;
	
	if (!($result=mysqli_query($con,$sql)))
	{
		echo "Error description: " . mysqli_error($con) ;
	}	
	
	$cmd = "";
	$groupid = 0;
	$queryid = 0;
	$answerid = 0;
	
	$responseMessage = "";

	if($row = $result->fetch_assoc())
	{
		$groupid = $row["currgroupid"];
		$queryid = $row["currqueryid"];
		$answerid = $row["curranswerid"];
		$cmd = $row["currcmd"];
		
		if ( $cmd == "lq")
		{
			$queryid = get_next_queryid($con, $groupid, $queryid);
			//echo "queryid = [".$queryid."]<br>";
			return process_list_queries($con, $uiid, $groupid, $queryid);
		}			
		else if ( $cmd == "la")
		{
			$answerid = get_next_answerid($con, $queryid, $answerid);
			//echo "queryid = [".$queryid."]<br>";
			return process_list_answers($con, $uiid, $queryid, $queryid, $answerid);
		}	
		else if ( $cmd == "lg")
		{
			$groupid = get_next_groupid($con, $groupid);
			//echo "queryid = [".$queryid."]<br>";
			return process_list_groups($con, $uiid, $groupid);	
		}	
	}

	return $responseMessage;
}

function process_following_group($con, $uiid, $groupname)
{
	$groupid = getGroupId($con, $groupname); 
	if($groupid==0)
	{
		return "Please input group name.";
	}
		$sql = "SELECT id FROM bpgroupfollow where uiid=".$uiid ." and groupid=". $groupid ;
	
	if (!($result=mysqli_query($con,$sql)))
	{
		echo "Error description: " . mysqli_error($con) ;
	}	
	
	$id = 0;
	
	$responseMessage = "";

	if($row = $result->fetch_assoc())
	{
		$id = $row["id"];
	}
	
	if($id>0)
	{
		return "You have already followed the group.";
	}
	
	$sql = "INSERT INTO bpgroupfollow ( uiid, groupid ) VALUES ('" . $uiid. "','".  $groupid . "' )";
	if ( !mysqli_query($con,$sql) )
	{
		//echo("Error description: " . mysqli_error($con));
		$responseMessage = "error in follow  a group: " . mysqli_error($con);
	}
	else
	{
		$responseMessage = "following group " . $groupid . " succeed.";
	}
	return $responseMessage;    
}

function process_following_query($con, $uiid, $queryid)
{
	if($queryid==0)
	{
		return "Please input query id.";
	}
		$sql = "SELECT id FROM bpqueryfollow where uiid=".$uiid ." and queryid=". $queryid ;
	
	if (!($result=mysqli_query($con,$sql)))
	{
		echo "Error description: " . mysqli_error($con) ;
	}	
	
	$id = 0;
	
	$responseMessage = "";

	if($row = $result->fetch_assoc())
	{
		$id = $row["id"];
	}
	
	if($id>0)
	{
		return "You have already followed the query.";
	}
	
	$sql = "INSERT INTO bpqueryfollow ( uiid, queryid ) VALUES ('" . $uiid. "','".  $queryid . "' )";
	if ( !mysqli_query($con,$sql) )
	{
		//echo("Error description: " . mysqli_error($con));
		$responseMessage = "error in follow  a query: " . mysqli_error($con);
	}
	else
	{
		$responseMessage = "following query " . $queryid . " succeed.";
	}
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
