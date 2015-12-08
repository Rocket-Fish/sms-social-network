<?php
	error_reporting(E_ALL);

include("smssndbconn.php");
		$con = dbopen();

	$query=
		"create table bpqueryfollow ("
		."id int(6) NOT NULL auto_increment,"
		."uiid int(6) NOT NULL,"
		."queryid int(6),"
		."timewhen TIMESTAMP NOT NULL,"
		."PRIMARY KEY (id),UNIQUE id (id))"
		;

	if (!mysqli_query($con, $query))
	{
		echo "\nError description: " . mysqli_error($con) . "\n" ;
	}

/*
	$query=
		"create table bpgroupfollow ("
		."id int(6) NOT NULL auto_increment,"
		."uiid int(6) NOT NULL,"
		."groupid int(6),"
		."timewhen TIMESTAMP NOT NULL,"
		."PRIMARY KEY (id),UNIQUE id (id))"
		;

	if (!mysqli_query($con, $query))
	{
		echo "\nError description: " . mysqli_error($con) . "\n" ;
	}
*/	
/*
//	mysqli_query($con, "drop table bpquerys");
//	mysqli_query($con, "drop table bpanswers");
	
	$query=
		
		"create table bpquerys ("
		."id int(6) NOT NULL auto_increment,"
		."ownerid int(6) NOT NULL,"
		."groupid int(6),"
		."content varchar(160) NOT NULL,"
		."timewhen TIMESTAMP NOT NULL,"
		."PRIMARY KEY (id),UNIQUE id (id))"
		;

	if (!mysqli_query($con, $query))
	{
		echo "\nError description: " . mysqli_error($con) . "\n" ;
	}
	
	$query=
		
		"create table bpanswers ("
		."id int(6) NOT NULL auto_increment,"
		."ownerid int(6) NOT NULL,"
		."queryid int(6) NOT NULL,"
		."content varchar(160) NOT NULL,"
		."timewhen TIMESTAMP NOT NULL,"
		."numpros int(6),"
		."numcons int(6),"
		."PRIMARY KEY (id),UNIQUE id (id) )"
		;

	if (!mysqli_query($con, $query))
	{
		echo "\nError description: " . mysqli_error($con) . "\n";
	}
	*/
	
		dbclose($con);

/*	
			create table bpquerys (
		id int(6) NOT NULL auto_increment,
		ownerid int(6) NOT NULL,
		groupid int(6),
		content varchar(160) NOT NULL,
		timewhen TIMESTAMP(8) NOT NULL,
		PRIMARY KEY (id),UNIQUE id (id))
		
*/			
?>

	
				
