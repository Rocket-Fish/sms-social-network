<?php

function add_menu($prefix)
{
?>	
	<table>
	<tr>
		<td><a href="<?php echo $prefix ?>index.php">Home</a></td>
	</tr>
	<tr>
		<td>Users</td>
		<td><a href="<?php echo $prefix ?>db/userList.php">List</a></td>
		<td><a href="<?php echo $prefix ?>db/userInsert.php">Insert</a></td>
	</tr>
	<tr>
		<td>Groups</td>
		<td><a href="<?php echo $prefix ?>db/groupList.php">List</a></td>
		<td><a href="<?php echo $prefix ?>db/groupEdit.php">Insert</a></td>
	</tr>
	
	<tr>
		<td>Queries</td>
		<td><a href="<?php echo $prefix ?>db/list_query.php">List</a></td>
	</tr>
	
	</table>
<?php
}
?>