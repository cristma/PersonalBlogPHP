<?php 

session_cache_limiter('nocache');
session_start();
session_save_path('/home/immuta5/tmp/98defd6ee70dfb1dea416cecdf391f58/');

include_once('./Configuration.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Immutable Productions</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
<meta name="robots" content="noarchive">
<link rel="pingback" href="<?php echo $url; ?>">
<link rel="stylesheet" href="<?php echo $url; ?>css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo $url; ?>css/bootstrap.min.css" type="text/css">
<link rel="stylesheet" href="<?php echo $url; ?>css/bootstrap-responsive.min.css" type="text/css">
<script src="<?php echo $url; ?>js/jquery-2.0.2.min.js" type="text/javascript"></script>
<script src="<?php echo $url; ?>js/bootstrap.min.js" type="text/javascript"></script>
<?php
// We do not need to include tinyMCE unless we are editing or adding something...
if(isset($_GET['action'])) {
	if($_GET['action'] == 'add' || $_GET['action'] == 'modify') {
?>
<script src="<?php echo $url; ?>js/tinymce/tinymce.min.js" type="text/javascript"></script>
<script>
	tinymce.init({
		selector: "textarea", 
		plugins: [
			"advlist autolink lists link image charmap print preview anchor", 
			"searchreplace visualblocks code fullscreen", 
			"insertdatetime media table contextmenu paste"
		], 
		toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image", 
		autosave_ask_before_unload: false, 
		image_advtab: true 
	});
</script>
<?php
	}
}

?>
<?php

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
		$user=NULL;
				
		if(ini_get("session.use_cookies")) {
    			$params = session_get_cookie_params();
			@setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
		}	// end if
		
		session_destroy();
	}	// end if
}	// end if

?>
</head>
<body>
<?php include($root.'view/ViewHeader.php'); ?>
<?php include($root.'controller/home/ContentController.php'); ?>
<?php include($root.'view/ViewFooter.php'); ?>
</body>
</html>