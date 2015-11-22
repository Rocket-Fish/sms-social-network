<?php

function startsWith($haystack, $needle) {
    // search backwards starting from haystack length characters from the end
	//echo "\n startsWith called \n";
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
}

function sendMessage($phone, $text)
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

function process_query($con, $user_body, $uiid)
{
	$subbody = trim(substr($user_body, 6));
	$pos = strpos($subbody, ":");

	// Note our use of ===.  Simply == would not work as expected
	// because the position of 'a' was the 0th (first) character.
	if ($pos === false) {
		$groupid=0;
		$content = $subbody;
	} 
	else 
	{
		$groupname=substr($subbody, 0, $pos);
		$content = substr($subbody, $pos+1);
		
		$groupid = intval($groupname); 
		if($groupid==0)
		{
			$sql = "select id from bpgroups where name='" . $groupname . "'";
			
			if ($result=mysqli_query($con,$sql))
			{
				if ($result->num_rows > 0) 
				{
					if($row = $result->fetch_assoc()) 
					{
						$groupid = $row["id"];
					}
				}
			}	
			mysqli_free_result($result);
		}
	}			
	
	$sql = "INSERT INTO bpquerys ( ownerid, groupid, content ) VALUES ('" . $uiid. "','".  $groupid . "','".  $content. "' )";
	if ( !mysqli_query($con,$sql) )
	{
		//echo("Error description: " . mysqli_error($con));
		$responseMessage = "query create error: " . mysqli_error($con);
	}
	else
	{
		$responseMessage = "query " .  mysqli_insert_id($con) . " created.";
	}
	return $responseMessage;
}

function process_answer($con, $user_body, $uiid)
{
			$subbody = trim(substr($user_body, 4));
			
			$pos = strpos($subbody, ":");

			// Note our use of ===.  Simply == would not work as expected
			// because the position of 'a' was the 0th (first) character.
			if ($pos === false) {
				$queryid=-1;
				$content = $subbody;
			} 
			else 
			{
				$queryid=substr($subbody, 0, $pos);
				$content = substr($subbody, $pos+1);
			}			
			
			$sql = "INSERT INTO bpanswers ( ownerid, queryid, content ) VALUES ('" . $uiid. "','".  $queryid . "','".  $content. "' )";
			if ( !mysqli_query($con,$sql) )
			{
				//echo("Error description: " . mysqli_error($con));
				$responseMessage = "answer create error: " . mysqli_error($con);
			}
			else
			{
				$responseMessage = "answer created.";
				
				
				
				//////
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
						echo " phone of queryer = " . $phone . "\n";
					}
					$i++;  	
				}
				//////
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
					}
					$i++;
				}
				//////
				
				//function sendMessage($phone, $text)

				
				
			}
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



?>
