<?php

if(isset($_GET['action'])) {
	switch($_GET['action']) {
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Responses using JSON that will be interpreted from AJAX calls.
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		case 'modify':
			$response="";		
			if(isset($user)) {
				if($userModel->HasAccess($user['id'], 'Modify Article')) {
					if(isset($_POST['f_articletitle']) && 
					   isset($_POST['f_articleexcerpt']) &&
					   isset($_POST['f_articlecontent']) &&
					   isset($_POST['f_articleid'])) {
						include_once($root.'model/ArticleModel.php');
						$articleModel = new ArticleModel($sqlManager);
						
						// Verify that our previous page was called from the correct referral
						if($_SERVER['HTTP_REFERER'] != $url."index.php?content=articles&action=view") {
							exit("You have called this script from an outside resource and your attempt has been logged.  A trace will be performed to see where this call originated from and if appropriate, the proper parties will be notified of your attempts.");
						}
						
						if($articleModel->ModifyArticle($_POST['f_articleid'], addslashes($_POST['f_articletitle']), addslashes($_POST['f_articleexcerpt']), addslashes($_POST['f_articlecontent']))) {
							$response = '{"status" : "success", "message" : "The article was successfully modified."}';
						} else {
							// Modify error
							$response = '{"status" : "failure", "message" : "Article was not modified.  This could be because nothing was modified or there is a problem with your input."}';
						}	// end if-else
				   } else {
					   // Incorrect parameters
					   $response = '{"status" : "failure", "message" : "Incorrect parameters were passed to this script.  Your attempt has been logged and will be investigated."}';
				   }	// end if-else
				} else {
					// Insufficient privs
					$response = '{"status" : "failure", "message" : "You do not have sufficient access to use this resource.  A traceback will be performed to determine how you arrived here."}';
				}	// end if-else
			} else {
				// No logged in
				$response = '{"status" : "failure", "message" : "You must be logged in to access this resource."}';
			}	// end if-else
			
			echo $response;
			break;
		case 'save':
			$response = "";
			if(isset($user)) {
				if($userModel->HasAccess($user['id'], "Create Article")) {					
					if(isset($_POST['f_articletitle'])    &&
					   isset($_POST['f_categories'])     &&
					   isset($_POST['f_articleexcerpt']) &&
					   isset($_POST['f_articlecontent']) &&
					   isset($_POST['f_articlepublished'])) {
					    include_once($root.'model/ArticleModel.php');
						include_once($root.'model/CategoryModel.php');
						$articleModel = new ArticleModel($sqlManager);
						$categoryModel = new CategoryModel($sqlManager);
						
						if($id = $articleModel->addArticle(
							                         addslashes($_POST['f_articletitle']), 
													 addslashes($_POST['f_articleexcerpt']), 
													 addslashes($_POST['f_articlecontent']), 
													 $user['id'], 
													 $_POST['f_articlepublished'])) {
							$status = true;
							
							foreach($_POST['f_categories'] as $category) {
								if(!$categoryModel->AddArticleRelation($category, $id)) {
									$status = false;
									break;
								}	// end if
							}	// end foreach
							
							if($status) {
								$response = '{"status" : "success", "message" : "Article was created successfully.", "article" : {"id" : "' . $id . '", "name" : "' . addslashes($_POST['f_articlename']) . '"}}';
							} else {
								$response = '{"status" : "failure", "message" : "Article was creates successfully, but we were unable to assign all the categories to the article.", "article" : {"id" : "' . $id . '", "name" : "' . addslashes($_POST['f_articlename']) . '"}}';
							}	// end if-else
						} else {
							// Unable to add article to the table.
							$response = '{"status" : "failure", "message" : "Unable to add the article to the articles table.  This could be because a liked named article already exists or you are missing input in a field.", "article" : {"id" : null, "name" : null}}';
						}	// end if-else
					} else {
						// Invalid variables
						$response = '{"status" : "failure", "message" : "The script was called invalidly.  Your attempt has been logged and will be investigated.", "article" : {"id" : null, "name" : null}}';
					}	// end if-else
				} else {
					// invalid access
					$response = '{"status" : "failure", "message" : "You do not have sufficient access to this resource.  A traceback will be performed to gather information on how you accessed this resource.", "article" : {"id" : null, "name" : null}}';
				}	// end if-else
			} else {
				// need logged in
				$response = '{"status" : "failure", "message" : "You need to be logged in to access this feature.", "article" : {"id" : null, "name" : null}}';
			}	// end if-else
			
			echo $response;
			
			break;
		case 'delete':
			if(isset($user)) {
				if($user['sec_level'] >= $article_delete_level) {
					if(isset($_GET['article'])) {
						include_once($root.'model/ArticleModel.php');
						$articleModel = new ArticleModel($sqlManager);
						
						if($articleModel->DeleteArticle($_GET['article'])) {
							$response = '{"status" : "success", "message" : "Article was deleted successfully."}';
						} else {
							$response = '{"status" : "failure", "message" : "Unable to delete article.  Possible that an incorrect article ID was used."}';
						}	// end if-else
					} else {
						// Incorrect parameters
						$response = '{"status" : "failure", "message" : "This script was called incorrectly.  You attempt has been logged and will be investigated."}';
					}	// end if-else
				} else {
					// Insufficient privs
					$response = '{"status" : "failure", "message" : "You do not have sufficient privledges to access this resource.  A traceback will be performed to determine how you got here and it will be investigated."}';
				}	// end if-else
			} else {
				// Need logged in
				$response = '{"status" : "failure", "message" : "You need to be logged in to access this resource."}';
			}	// end if-else
			
			echo $response;
			break;
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Controller components that will initialize a view.
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		case 'add':
			include($root.'view/articles/AddArticle.php');
			break;
		case 'view':
		default:
			include($root.'view/articles/ViewArticle.php');
			break;
	}	// end switch
} else {
	include($root.'view/articles/ViewArticle.php');
}	// end if-else

?>