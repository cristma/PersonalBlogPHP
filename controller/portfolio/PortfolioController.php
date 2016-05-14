<?php

if(isset($_GET['action'])) {
	switch($_GET['action']) {
		case 'add':
			include($root.'view/portfolio/AddProject.php');
			break;
		case 'modify':
			include($root.'view/portfolio/ModifyProject.php');
			break;
		case 'save':
			if(isset($user)) {
				if($userModel->HasAccess($user['id'], "Create Project")) {
					if(isset($_POST['f_projectname']) && 
					   isset($_POST['f_projectexcerpt']) && 
					   isset($_POST['f_projectdescription']) && 
					   isset($_POST['f_projectpublished']) && 
					   isset($_POST['f_categories'])) {
						include_once($root.'model/ProjectModel.php');
						include_once($root.'model/CategoryModel.php');
						$projectModel = new ProjectModel($sqlManager);
						$categoryModel = new CategoryModel($sqlManager);
						
						if($id = $projectModel->AddProject(addslashes($_POST['f_projectname']), addslashes($_POST['f_projectexcerpt']), addslashes($_POST['f_projectdescription']), $_POST['f_projectpublished'])) {
							$status = true;
							foreach($_POST['f_categories'] as $category) {
								if(!$categoryModel->AddProjectRelation($category, $id)) {
									$status = false;
									break;
								}	// end if
							}	// end foreach
							
							if($status) {
								echo "Categories associated correctly.  Project added successfully.";
							} else {
								echo "Unable to associate categories correctly.  Project added successfully.";
							}	// end if-else
						} else {
							echo "Problem adding project.";
						}	// end if-else
					} else {
						// Form called incorrectly.
						echo "Form called incorrectly.";
					}	// end if-else
				} else {
					// Insufficient access
					echo "Insufficient privs";
				}	// end if-else
			} else {
				// need to log in
				echo "Need to log in.";
			}	// end if-else
			
			break;
		case 'view':
		default:
			include($root.'view/portfolio/ViewPortfolio.php');
	}	// end switch
} else {
	include($root.'view/portfolio/ViewPortfolio.php');
}	// end if-else

?>