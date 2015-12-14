<?php

function getRequests()
{
	$stringData = file_get_contents("php://input");
	$queryString = $_SERVER['QUERY_STRING'];	

	//echo "request = " . $stringData . "\n";
	//echo "query string=".$_SERVER['QUERY_STRING'] . "<br>";	

	$arrayOfPostData = explode('&', $stringData);
	$arrayOfGetData = explode('&', $queryString);
	
	$array = array_merge($arrayOfPostData, $arrayOfGetData);
	
	return $array;
}

?>