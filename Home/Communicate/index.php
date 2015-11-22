<!DOCTYPE html>
<html>
	<head>
		<title>Forum</title>
	</head>

	<body>
	
		<?php 
			$selectionERR = $selectedID = "";

			if ($_SERVER["REQUEST_METHOD"] == "POST") {

				if (empty($_POST["selection"])) {
					$selectionERR = "selection is required";
			    } else {
					$selectedID = test_input($_POST["selection"]);
					// check if input only contains numbers
					if (!is_numeric($selectedID)) {
						$selectionERR = "Only whole numbers are allowd";
						$selectedID = "";
					}
			    }
			}

			function test_input($data) {
			   $data = trim($data);
			   $data = stripslashes($data);
			   $data = htmlspecialchars($data);
			   return $data;
			}

		?>

		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			Selection: <input type="text" name="selection" value="<?php echo $selectedID;?>">
			<span class="error">* <?php echo $selectionERR;?></span>
			<br><br>
			<input type="submit" name="submit" value="Select">
		</form>
		
		<?php
			error_reporting(E_ALL);

			include("dbbpconnect.php");

//			$query = "select * from bpusers";
			$query = "select id, ownerid, (select userid from bpusers b where b.id = a.ownerid) as owner,"
			."(select name from bpgroups where id in (select groupid from bpquerys c where c.id=a.id)) as groupname, content, timewhen from bpquerys a";
			
			if (!($result=mysqli_query($con,$query)))
			{
				echo "Error description: " . mysqli_error($con) ;
			}	

			$num=mysqli_num_rows($result);
			echo "<br> number of records = ";
			echo $num;
		?>
		<table border = "1">
		
		<tr>
			<td>

		<?php	

		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
			
			if($row["id"] == $selectedID || $selectedID == "") {
		?>	
		<tr>
			<td>
			<?php echo $row["id"]; ?> 
			<?php echo $row["owner"]; if($row["owner"] == "") {echo $row["ownerid"];}?>  <br />
			<?php echo $row["groupname"]; ?></td>
			<td>
				<?php echo $row["content"]; ?>
			</td>
			<td>
				<?php echo $row["timewhen"]; ?>
			</td>
		</tr>		
		<?php	
				}
				}
			} else {
				echo "0 results";
			}
		?>
		</table>

		<?php	

			// Free result set
			mysqli_free_result($result);
			
			if($selectedID != "") {
//			$query = "select * from bpanswers";
			$query = "select id, ownerid, "
			. "(select userid from bpusers b where b.id=a.ownerid) as owner ,"
			. "(select name from bpgroups where id in (select groupid from bpquerys c where c.id=a.queryid)) as groupname , "
			. "queryid, content, timewhen from bpanswers a";

			
			if (!($result=mysqli_query($con,$query)))
			{
				echo "Error description: " . mysqli_error($con) ;
			}	
			
			$num=mysqli_num_rows($result);

			echo "<br> number of records = ". $num;
			
			$i=0;
		?><table border=1>	
		<tr>
		<td>
		<?php	
			if ($result->num_rows > 0) 
			{
				// output data of each row
				while($row = $result->fetch_assoc()) {
				
				if($row["queryid"] == $selectedID) {
		?>	
		<tr>
			<td>
			<?php echo $row["id"]; ?> 
			<?php echo $row["owner"]; ?> <br />
			<?php echo $row["groupname"]; ?></td>
			<td>
				<?php echo $row["content"]; ?>
			</td>
			<td>
				<?php echo $row["timewhen"]; ?>
			</td>
		</tr>		
		<?php	
				}
				  //$i++;  	
				}
			} else {
				echo "0 results";
			}
		?>
		</table>
		
		<?php	

			// Free result set
			mysqli_free_result($result);
			}
			include("dbbpclose.php");
		?>

		<?php
			$name = $nameErr = $phone = $phoneErr = $comment = $type = $typeErr = "";

			if ($_SERVER["REQUEST_METHOD"] == "POST") {

				if (empty($_POST["name"])) {
					$nameErr = "Name is required";
			    } else {
					$name = test_input($_POST["name"]);
					// check if name only contains letters and whitespace
					if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
					$nameErr = "Only letters and white space allowed";
					}
			    }

				if (empty($_POST["phone"])) {
					$phoneErr = "Name is required";
			    } else {
					$phone = test_input($_POST["phone"]);
					// check if name only contains letters and whitespace
					if (!is_numeric($phone)) {
					$phoneErr = "Only numbers allowed";
					}
			    }
				
			   if (empty($_POST["type"])) {
				 $typeErr = "type is required";
			   } else {
				 $type = test_input($_POST["type"]);
			   }
			   
				if (empty($_POST["comment"])) {
				 $comment = "";
				} else {
				 $comment = test_input($_POST["comment"]);
				}
				
				if($nameErr == "" &&  $phoneErr == "" && $typeErr == "") {
				include("dbbpconnect.php");
				
				$groupid = 1;
				if($type == "education")
					$groupid = 1;
				if($type == "agriculture")
					$groupid = 2;
				if($type == "health")
					$groupid = 3;
				
				insert_query($con, $phone, 1, $comment);

				include("dbbpclose.php");
				}
			}

			
function insert_query($con, $uiid, $groupid, $content)
{
	$sql = "INSERT INTO bpquerys ( ownerid, groupid, content ) VALUES ('" . $uiid. "','".  $groupid . "','".  $content. "' )";
	if ( !mysqli_query($con,$sql) )
	{
		$responseMessage = "query create error: " . mysqli_error($con);
	}
	else
	{
		$responseMessage = "query " .  mysqli_insert_id($con) . " created.";
	}
}

		?>
		
		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			Name: <input type="text" name="name" value="<?php echo $name;?>">
			<span class="error">* <?php echo $nameErr;?></span>
			<br><br>
			PhoneNumber: <input type="text" name="phone" value="<?php echo $phone;?>">
			<span class="error">* <?php echo $phoneErr;?></span>
			<br><br>
			Type:
			<input type="radio" name="type" <?php if (isset($type) && $type=="education") echo "checked";?>  value="education">Education
		    <input type="radio" name="type" <?php if (isset($type) && $type=="agriculture") echo "checked";?>  value="agriculture">Agriculture
		    <input type="radio" name="type" <?php if (isset($type) && $type=="health") echo "checked";?>  value="health">Health
		    <span class="error">* <?php echo $typeErr;?></span>
			<br><br>
			Comment: <br> <textarea name="comment" rows="5" cols="40"><?php echo $comment;?></textarea>
			<br><br>
			<input type="submit" name="submit" value="Post">
		</form>

	</body>
</html>
