<?php
	error_reporting(E_ALL);

include("dbbpconnect.php");
	
	$query = "drop table bpgroups";

	if (!mysqli_query($con, $query))
	{
		echo "Error description: " . mysqli_error($con) ;
	}
	
	include("dbbpclose.php");
	
/*
		create table bpusers ( 	
		id int(6) NOT NULL auto_increment,
		phone varchar(15) NOT NULL,
		userid varchar(15),
		city varchar(15),
		state varchar(15),
		country varchar(15),
		zip varchar(15),
		PRIMARY KEY (id),UNIQUE id (id),UNIQUE idphone (phone) )
		

		create table bpgroups ( 	
		id int(6) NOT NULL auto_increment,
		name varchar(20) NOT NULL,
		comment varchar(100),
		type int(6),
		PRIMARY KEY (id),UNIQUE id (id),UNIQUE idname (name) )
*/	
?>

	
				