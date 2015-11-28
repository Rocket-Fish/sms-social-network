<?php
	error_reporting(E_ALL);

include("dbbpconnect.php");
	
	//$query = "INSERT INTO users ( `uiid`, `userid`, `phone`, `city`, `country` ) VALUES (3, 'John','991234','calgary','canada')";
	//$query="CREATE TABLE contacts (id int(6) NOT NULL auto_increment,first varchar(15) NOT NULL,last varchar(15) NOT NULL,phone varchar(20) NOT NULL,mobile varchar(20) NOT NULL,fax varchar(20) NOT NULL,email varchar(30) NOT NULL,web varchar(30) NOT NULL,PRIMARY KEY (id),UNIQUE id (id),KEY id_2 (id))";
	//$query="CREATE TABLE bpgenerator (id int(6) NOT NULL auto_increment,first varchar(15) NOT NULL,last varchar(15) NOT NULL,phone varchar(20) NOT NULL,mobile varchar(20) NOT NULL,fax varchar(20) NOT NULL,email varchar(30) NOT NULL,web varchar(30) NOT NULL,
	//PRIMARY KEY (id),UNIQUE id (id),KEY id_2 (id))";
	/*
	$query=
		
		"create table bpgroups ("
		."id int(6) NOT NULL auto_increment,"
		."ownerid int(6) NOT NULL,"
		."name varchar(20) NOT NULL,"
		."comment varchar(100),"
		."type int(6),"
		."PRIMARY KEY (id),UNIQUE id (id),UNIQUE idname (name) )"
		;

	if (!mysqli_query($con, $query))
	{
		echo "Error description: " . mysqli_error($con) ;
	}
	*/

	$query=
		"create table bpusers ("
		."id int(6) NOT NULL auto_increment,"
		."phone varchar(20) NOT NULL,"
		."userid varchar(20),"
		."city varchar(80),"
		."state varchar(20),"
		."country varchar(20),"
		."zip varchar(15),"
		."currcmd varchar(20),"
		."currgroupid int(6),"
		."currqueryid int(6),"
		."curranswerid int(6),"
		."PRIMARY KEY (id),UNIQUE id (id),UNIQUE idphone (phone) )"
		;

	if (!mysqli_query($con, $query))
	{
		echo "Error description: " . mysqli_error($con) ;
	}
	
	include("dbbpclose.php");
	
/*
		

		create table bpgroups ( 	
		id int(6) NOT NULL auto_increment,
		name varchar(20) NOT NULL,
		comment varchar(100),
		type int(6),
		PRIMARY KEY (id),UNIQUE id (id),UNIQUE idname (name) )
*/	
?>

	
				