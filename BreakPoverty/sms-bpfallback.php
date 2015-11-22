<?php

	$myFile = "testFile.txt";
	$fh = fopen($myFile, 'w') or die("can't open file");
	
	$stringData = file_get_contents("php://input");
	fwrite($fh, $stringData.PHP_EOL);

	$splittedString = explode('&', $stringData);
	fwrite($fh, print_r($splittedString, true).PHP_EOL);
	
	$array = array_values($splittedString);
	for($i = 0; $i < count($splittedString); $i ++) {
	
		fwrite($fh, $array[$i].":: ");
		list($name, $value) = explode("=", $array[$i]);
		fwrite($fh, $name.": ");
		fwrite($fh, $value.PHP_EOL);
	}

	fclose($fh);
	
	header("content-type: text/xml");
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<Response>
	<Message>Hello, Monkeys 2.1</Message>
</Response>