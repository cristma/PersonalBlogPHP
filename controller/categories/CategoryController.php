<?php

include_once($root.'model/UserModel.php');
$userModel = new UserModel($sqlManager);

if(isset($_GET['action'])) {
	switch($_GET['action']) {
		case 'add':
			include($root.'view/users/AddCategory.php');
			break;
		case 'modify':
			$response = "";
			
			if(isset($user)) {
				if($userModel->HasAccess($user['id'], "Modify Category")) {
					if(isset($_POST['f_categoryid']) && isset($_POST['f_categoryname']) && isset($_POST['f_categorydescription'])) {
					} else {
					}	// end if-else
				} else {
				}	// end if-else
			} else {
			}	// end if-else
			
			echo $response;
			break;
		case 'delete':
			$response = "";
			
			if(isset($user)) {
				if($userModel->HasAccess($user['id'], "Delete Category")) {
					if(isset($_POST['f_categoryid'])) {
						include_once($root.'model/CategoryModel.php');
						$categoryModel = new CategoryModel($sqlManager);
						
						if($categoryModel->DeleteCategory($_POST['f_categoryid'])) {
							$response = '{"status" : "success", "message" : "Category was deleted successfully."}';
						} else {
							$response = '{"status" : "failure", "message" : "Could not delete category successfully.  Could be that the ID was incorrect."}';
						}	// end if-else
					} else {
						$response = '{"status" : "failure", "message" : "Script was called incorrectly.  Your attempt has been logged and will be investigated."}';
					}	// end if-else
				} else {
					$response = '{"status" : "failure", "message" : "You do not have sufficient privledges to access this resource.  A traceback will be performed and investigated regarding this matter."}';
				}	// end if-else
			} else {
				$response = '{"status" : "failure", "message" : "You need to be logged in to access this resource."}';
			}	// end if-else
			
			echo $response;
			
			break;
		case 'save':
			$response = "";
			if(isset($user)) {
				if($userModel->HasAccess($user['id'], "Create Category")) {
					if(isset($_POST['f_categoryname']) && isset($_POST['f_categorydescription'])) {
						include_once($root.'model/CategoryModel.php');
						$categoryModel = new CategoryModel($sqlManager);
						
						if($id = $categoryModel->AddCategory(addslashes($_POST['f_categoryname']), addslashes($_POST['f_categorydescription']), 1, 1)) {
							$response = '{"status" : "success", "message" : "Category was created successfully.", "category" : {"id" : "' . $id . '", "name" : "' . addslashes($_POST['f_categoryname']) . '", "description" : "' . addslashes($_POST['f_categorydescription']) . '"}}';
						} else {
							$response = '{"status" : "failure", "message" : "There was an error in adding the category to the SQL table.  Please see SQL logs for details.", "category" : {"id" : null, "name" : null, "description" : null}}';
						}	// end if-else
					} else {
						$response = '{"status" : "failure", "message" : "The incorrect parameters were called for this script.", "category" : {"id" : null, "name" : null, "description" : null}}';
					}	// end if-else
				} else {
					$response = '{"status" : "failure", "message" : "Insufficient privledges to post to SQL database.", "category" : {"id" : null, "name" : null, "description" : null}}';
				}	// end if-else
			} else {
				$response = '{"status" : "failure", "message" : "You need to be logged in to create a category.", "category" : {"id" : null, "name" : null, "description" : null}}';
			}	// end if-else
			
			echo $response;
			
			break;
		case 'view':
		default:
			include($root.'view/categories/ViewCategory.php');
	}	// end switch
} else {
	include($root.'view/categories/ViewCategory.php');
}	// end if-else

?>