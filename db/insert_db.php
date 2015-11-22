<?php

    $host_name  = "db602178330.db.1and1.com";
    $database   = "db602178330";
    $user_name  = "dbo602178330";
    $password   = "breakpoverty";

	mysql_connect($host_name,$user_name,$password);
	@mysql_select_db($database); //or die( "Unable to select database");
	
	$query = "INSERT INTO users ( `uiid`, `userid`, `phone`, `city`, `country` ) VALUES (3, 'John','991234','calgary','canada')";
	
	//$query="CREATE TABLE contacts (id int(6) NOT NULL auto_increment,first varchar(15) NOT NULL,last varchar(15) NOT NULL,phone varchar(20) NOT NULL,mobile varchar(20) NOT NULL,fax varchar(20) NOT NULL,email varchar(30) NOT NULL,web varchar(30) NOT NULL,PRIMARY KEY (id),UNIQUE id (id),KEY id_2 (id))";
	mysql_query($query);
	
	mysql_close();

?>