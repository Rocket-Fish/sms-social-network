<?php

	$filename = "smssn.ini";
	$fh = fopen($filename, 'r') or die("can't open file");
	$config = fread($fh, filesize($filename));
	fclose($fh);
	
	$splitted_String = explode('\n', $stringData);
	$array_ = array_values($splitted_String);

    $hostname  = "";
    $database  = "";
    $username  = "";
    $password  = "";
	
	for($i = 0; $i < count($splittedString); $i ++) {
		list($name, $value) = explode("=", $array[$i]);
		
		if($name=="hostname") 			$hostname 	 = $value;
		else if($name=="database") 		$database 	 = $value;
		else if($name=="username") 		$username 	 = $value;
		else if($name=="password") 		$password 	 = $value;
	}

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