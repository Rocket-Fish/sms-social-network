<?php

function dbclose($con)
{
	mysqli_close($con);
}

function dbopen()
{
	$filename = "smssn.ini";
	$fh = fopen($filename, 'r') or die("can't open file");
	$config = fread($fh, filesize($filename));
	fclose($fh);
	
//	echo " config = [" . $config . "] <br>" .
	
	$splitted_String = explode("\n", $config);
	$array_ = array_values($splitted_String);

    $hostname  = "";
    $database  = "";
    $username  = "";
    $password  = "";
	
	
	for($i = 0; $i < count($splitted_String); $i ++) 
	{
		if( false == strpos($array_[$i], "="))
			continue;

//		echo " array = [" . $i . "] = [" . $array_[$i] ."]<br>";
		
		list($name, $value) = explode("=", $array_[$i]);
		
		if($name=="hostname") 			$hostname 	 = trim($value);
		else if($name=="database") 		$database 	 = trim($value);
		else if($name=="username") 		$username 	 = trim($value);
		else if($name=="password") 		$password 	 = trim($value);
	}
/*	
	echo 
		" hostname = [" . $hostname . "] <br>" .
		" database = [" . $database . "] <br>" .
		" username = [" . $username . "] <br>" .
		" password = [" . $password . "] <br>" ;
*/	
	$con=mysqli_connect($hostname,$username,$password,$database);
	
	if (mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		return 0;
	}
	return $con;
}
?>