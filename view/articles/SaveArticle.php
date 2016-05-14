<?php
$errors = array();
$errors[0] = "<strong>Category error!</strong> Article was created successfully, but we were unable to associate the article with the categories selected.  You may edit the article to see what categories where assigned and reassign categories accordingly.";
$errors[1] = "<strong>Article error!</strong> Unable to add article to the articles table.  This could be due to a liked named article or an error with your input for the article.";
$errors[2] = "<strong>Variable not present!</strong> Script was called using invalid variable input for the form to be processed.  Your attempt has been logged and will be investigated.";
$errors[3] = "<strong>No access!</strong> You have accessed this page with insufficient privledges to create an article.  Your attempt has been logged and will be investigated.";
$errors[4] = "<strong>No user present!</strong> You need to log in to access this resource.";
$errors[5] = "<strong>Invalid referral!</strong> You have called this script with an outside resource.  As a result, your attempts have been logged and will be investigated.";

if(isset($status)) {
	switch($status) {
		case 'success':
			echo '<div class="alert alert-success alert-block">';
			echo '<button type="button" class="close" data-dismiss="alert">&times;</button>';
			echo '<h3>Success!</h3>';
			echo 'Article was created successfully and the categories were associated with the article successfully!';
			echo '</div>';
			break;
		case 'failure':
			echo '<div class="alert alert-error alert-block">';
			echo '<button type="button" class="close" data-dismiss="alert">&times;</button>';
			echo '<h3>Error!</h3>';
			if(isset($error)) {
				echo $errors[$error];
			} else {
				echo "Unknown error occurred.  Check SQL and PHP logs for information.";
			}	// end if-else
			echo '</div>';
			break;
		default:
			echo "Unable to determine status.";
	}
} else {
	echo "Unable to determine status.";
}	// end if-else

?>