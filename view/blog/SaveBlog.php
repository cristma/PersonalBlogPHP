<?php

if(isset($_GET['status'])) {
	switch($_GET['status']) {
		case 'success':
			echo "Post was successfully saved!";
			break;
		case 'failure':
			echo "There was a failure in saving your post...";
			break;
		default:
			echo "Unknown status.";
	}	// end switch
}	// end if

?>