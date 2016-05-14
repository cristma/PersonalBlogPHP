<?php 

session_cache_limiter('private');
session_start();
session_save_path('/tmp/98defd6ee70dfb1dea416cecdf391f58');

include_once('./Configuration.php');

// The user model will be used throughout the site so might as well initialize it here.
include_once($root.'model/UserModel.php');
$userModel = new UserModel($sqlManager);

/** Logins may only be performed through POST.  All GET requests for session control will be ignored. */
if(isset($_POST['action'])) {
	switch($_POST['action']) {
		case 'login':
			// Username and password will be passed with this request.
			if(isset($_POST['f_username']) && isset($_POST['f_password'])) {
				// Verify the user's information
				$logged = $userModel->LoginUser($_POST['f_username'], md5($_POST['f_password']));
				if($logged) {
					$_SESSION['user']['id'] = $logged;
					$_SESSION['user']['location'] = $_SERVER['REMOTE_ADDR'];
				} else {
					session_destroy();
				}	// end if-else
			} else {
				// Need to inform the end user that this was called incorrectly and the 
				// information has been logged.  Repeated attempts should result in an IP block.
				// Show modal with this information.
			}	// end if-else
	}	// end switch
}	// end if

// Houses basic user information
$user = NULL;

// Verifies current user information in the session control
if(isset($_SESSION['user']['id']) && isset($_SESSION['user']['location'])) {
	// We need to validate the use is using the same location
	if($_SESSION['user']['location'] != $_SERVER['REMOTE_ADDR']) {
		// Our session is invalid!
		// Need to inform the user that they must log in again as their address has changed.
		session_destroy();
	} else {
		// Let's acquire basic user information
		$user = $userModel->GetUserInfo($_SESSION['user']['id'], $_SERVER['REMOTE_ADDR']);
	}	// end if-else
} else {
	//if(session_status() == PHP_SESSION_ACTIVE) {
		@session_destroy();
	//}
}	// end if-else

/** Make sure we are not trying to logout. */
if(isset($_GET['action'])) {
	if($_GET['action'] == 'logout') {
		$_SESSION=array();
		
		if(isset($_COOKIE[session_name()])) {
			@setcookie(session_name(), '', time()-45000);
		}
		
		session_destroy();
		unset($user);
	}	// end if
}	// end if

include($root.'controller/home/ContentController.php');

?>