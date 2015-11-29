<?php

function add_menu($prefix)
{
?>	
	<table>
	<tr>
	<td><a href="<?php echo $prefix ?>index.php">Home</a></td>
	<td><a href="<?php echo $prefix ?>db/users.php">Users</a></td>
	<td><a href="<?php echo $prefix ?>db/insert.php">Insert</a></td>
	</tr>
	</table>
<?php
}
?>