<?php

include_once($root.'model/UserModel.php');

if(!isset($userModel)) {
	$userModel = new UserModel($sqlManager);
}


/**
  * Determines the action to be performed on the Blog item.
  */
if(isset($_GET['action'])) {
	switch($_GET['action']) {
		case 'add':
			include($root.'view/blog/AddBlog.php');
			break;
		case 'modify':
			include($root.'view/blog/ModifyBlog.php');
			break;
		case 'save':
			if(isset($user)) {
				if($userModel->HasAccess($user['id'], "Create Post")) {
					if(isset($_POST['f_blogtitle']) && 
					   isset($_POST['f_categories']) &&
					   isset($_POST['f_blogcontent'])) {
						    include_once($root.'model/BlogModel.php');
						    $blogModel = new BlogModel($sqlManager);
						   
						    if($id = $blogModel->AddPost(addslashes($_POST['f_blogtitle']), 
						                          addslashes($_POST['f_blogcontent']),  
											      $user['id'])) {
								include_once($root.'model/CategoryModel.php');
								$categoryModel = new CategoryModel($sqlManager);
								$success = false;
								
								foreach($_POST['f_categories'] as $category) {
									if(!($success = $categoryModel->AddPostRelation($category, $id))) {
										break;
									}	// end if
								}	// end foreach
								
								if($success) {
									include($root.'view/blog/AddBlogSuccess.php');
								} else {
									include($root.'view/blog/AddBlogFailure.php');
								}	// end if-else
							} else {
								include($root.'view/blog/AddBlogError.php');
							}
					} else {
						include($root.'view/InvalidVariables.php');
					}	// end if-else
				} else {
					include($root.'view/InvalidAccess.php');
				}	// end if-else
			} else {
				include($root.'view/MustBeLoggedIn.php');
			}	// end if-else
			
			break;
		case 'delete':
			include($root.'view/blog/DeleteBlog.php');
			break;
		case 'addrating':
			$voter_id = 0;
			if(isset($user)) {
				$voter_id = $user['id'];
			}
			
			if(isset($_POST['item']) && isset($_POST['value'])) {
				include_once($root.'model/BlogModel.php');
				$blogModel = new BlogModel($sqlManager);
				if($blogModel->AddRating($_POST['item'], $voter_id, $_POST['value'])) {
					echo "Vote tallied!";
				} else {
					echo "Unable to add vote to database.";
				}
			} else {
				echo "Script called incorrectly.";
			}
			
			break;
		case 'view':
		default:
			include($root.'view/blog/ViewBlog.php');
	}	// end switch
} else {
	include($root.'view/blog/ViewBlog.php');
}	// end if-else

?>