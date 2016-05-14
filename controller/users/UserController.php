<?php

if(isset($_GET['action'])) {
	switch($_GET['action']) {
		case 'add':
			include($root.'view/users/AddUser.php');
			break;
		case 'modify':
			include($root.'view/users/ModifyUser.php');
			break;
		case 'delete':
			include($root.'view/users/DeleteUser.php');
			break;
		case 'view':
		default:
			include($root.'view/users/ViewUser.php');
	}	// end switch
} else {
	include($root.'view/user/ViewUser.php');
}	// end if-else

?>