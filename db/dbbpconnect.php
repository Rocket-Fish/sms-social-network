<?php

    $host_name  = "db602178330.db.1and1.com";
    $database   = "db602178330";
    $user_name  = "dbo602178330";
    $password   = "breakpoverty";

//	mysql_connect($host_name,$user_name,$password);
//	@mysql_select_db($database); //or die( "Unable to select database");

	$con=mysqli_connect($host_name,$user_name,$password,$database);
	// Check connection
	if (mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	// Perform a query, check for error
//	if (!mysqli_query($con,"INSERT INTO Persons (FirstName) VALUES ('Glenn')"))
//	{
//		echo("Error description: " . mysqli_error($con));
//	}


?>