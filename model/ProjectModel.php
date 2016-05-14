<?php

include_once($root.'lib/SqlManaged.php');

class ProjectModel extends SqlManaged {
	/*
	 * Constructor for this class.
	 *
	 * @param $sqlManager The SqlManager (if initialized) that will handle db calls.
	 */
	public function __construct($sqlManager = NULL) {
		parent::SetSqlManager($sqlManager);
	}	// end constructor
	
	/*
	 * Destructor for this class.
	 */
	public function __destruct() {
	}	// end destructor
	
	/*
	 * AddRating
	 * Adds a rating for this project.
	 *
	 * @param $project The project that is to be rated.
	 * @param $user The user that is rating the project.
	 * @param $value The value of the rating.
	 * @return boolean The successful insertion of the rating into the table.
	 */
	public function AddRating($project, $user, $value) {
		$sql = "INSERT INTO `project_ratings` (`project`, `user`, `value`) VALUES (?, ?, ?)";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		
		// Verify that the statement has been created.
		if($statement) {
			$statement->bind_param("iii", @sprintf((int)$project), 
			                              @sprintf((int)$user), 
										  @sprintf((int)$value));
			$statement->execute();
			
			// Verify that we have changed something
			if($statement->affected_rows > 0) {
				$statement->close();
				
				return true;
			}	// end if
			
			$statement->close();
		}	// end if
		
		return false;
	}	// end function AddRating
	
	/*
	 * GetRatingsByProject
	 * Acquires the ratings that belong to a project.
	 *
	 * @param $project The project in which the ratings belong.
	 * @return array A list of the ratings that belong to this project.
	 */
	public function GetRatingsByProject($project) {
		$sql = "SELECT `t1`.`id` AS `id`, " . 
		              "`t1`.`user` AS `user_id`, " . 
					  "(SELECT `t2`.`name` FROM `profiles` AS `t2` WHERE `t2`.`user`=`t1`.`user`) AS `user`, " . 
					  "`t1`.`value` AS `value` FROM `ratings` WHERE `t1`.`project`=?";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		$results   = array();
		
		// Verify that the statement was created successfully.
		if($statement) {
			$statement->bind_param("i", @sprintf((int)$project));
			$statement->excecute();
			$statement->bind_result($id, $user_id, $user, $value);
			
			// Use all the results pulled.
			while($statement->fetch()) {
				$results[count($results)] = array('id'      => $id, 
											      'user_id' => $user_id, 
												  'user'    => $user, 
												  'value'   => $value);
			}	// end while
			
			$statement->close();
		}	// end if
		
		return $results;
	}	// end function GetRatingsByProject
	
	/*
	 * GetRatingsByUser
	 * Acquires the ratings that a user has submitted for projects.
	 *
	 * @param $user The id of the user for the ratings.
	 * @return array A list of the ratings that belong to this user.
	 */
	public function GetRatingsByUser($user) {
		$sql = "SELECT `t1`.`id` AS `id`, " . 
		              "`t1`.`project` AS `project_id`, " . 
					  "(SELECT `t2`.`name` FROM `projects` AS `t2` WHERE `t2`.`id`=`t1`.`project`) AS `project`, " . 
					  "`t1`.`value` AS `value` FROM `project_ratings` WHERE `t1`.`user`=?";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		$results   = array();
		
		// Verify the statement was created successfully.
		if($statement) {
			$statement->bind_param("i", @sprintf((int)$user));
			$statement->execute();
			$statement->bind_result($id, $project_id, $project, $value);
			
			// Use all the results in the result set
			while($statement->fetch()) {
				$results[count($results)] = array('id'         => $id, 
				                                  'project_id' => $project_id, 
												  'project'    => $project, 
												  'value'      => $value);
			}	// end while
			
			$statement->close();
		}	// end if
		
		return $results;
	}	// end function GetRatingsByUser
	
	/*
	 * ModifyProject
	 * Edits the values of a project.
	 *
	 * @param $id The id of the project.
	 * @param $name The name of the project.
	 * @param $excerpt The excerpt of the project.
	 * @param $description The description of the project.
	 * @return boolean The status of editing the project.
	 */
	public function ModifyProject($id, $name, $excerpt, $description) {
		$sql = "UPDATE `projects` SET `name`=?, `excerpt`=?, `description`=? WHERE `id`=?";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		
		// Verify the statement was created successfully.
		if($statement) {
			$statement->bind_param("sssi", @sprintf((string)$name), 
			                               @sprintf((string)$excerpt), 
										   @sprintf((string)$description), 
										   @sprintf((int)$id));
			$statement->execute();
			
			// Verify that a row was edited.
			if($statement->affected_rows > 0) {
				$statement->close();
				return true;
			}	// end if
		}	// end if
		
		return false;
	}	// end function ModifyProject
	
	/*
	 * DeleteProject
	 * Deletes a project from the project table.
	 *
	 * @param $project The ID of the project in the projects table.
	 * @param boolean The status of the deletion of the project.
	 */
	public function DeleteProject($project) {
		$sql = "DELETE FROM `projects` WHERE `id`=?";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		
		// Verify the statement was created successfully.
		if($statement) {
			$statement->bind_param("i", @sprintf((int)$project));
			$statement->execute();
			
			// Verify that something changed.
			if($statement->affected_rows > 0) {
				return true;
			}	// end if
		}	// end if
		
		return false;
	}	// end function DeleteProject
	
	/*
	 * AddProject
	 * Adds a project to the projects table.
	 *
	 * @param $name The name of the project.
	 * @param $excerpt A short description of the project.
	 * @param $description A long description of the project.
	 * @return integer The ID associated with this project.  False on failure.
	 */
	public function AddProject($name, $excerpt, $description) {
		$sql = "INSERT INTO `projects` (`name`, `excerpt`, `description`) VALUES (?, ?, ?)";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		
		$excerpt = str_replace("|", "&#124", $excerpt);
		$description = str_replace("|", "&#124", $description);
		
		// Verify that was have initialized a statement.		
		if($statement) {
			$statement->bind_param("sss", @sprintf((string)$name), 
			                              @sprintf((string)$excerpt), 
										  @sprintf((string)$description));
			$statement->execute();
			
			// Verify that we have added something
			if($statement->affected_rows > 0) {
				$statement->close();
				return parent::GetSqlManager()->LastId();
			}	// end if
		}	// end if
		
		return false;
	}	// end function AddProject
	
	/*
	 * GetProjects
	 * Acquires a list of projects filtered by the category and limited by the values passed.
	 *
	 * @param $category The category in which we will filter the results.
	 * @param $limit The number of results to limit.
	 * @param @offset The number of results to offset by.
	 * @return array An array of projects containing the id, name and excerpt.
	 */
	public function GetProjects($category = NULL, $limit = 5, $offset = 0) {
		$sql = "SELECT `t1`.`id` AS `id`, " .
		       "`t1`.`name` AS `name`, " . 
			   "`t1`.`excerpt` AS `excerpt` " . 
			   "FROM `projects` AS `t1` ";
		$statement = NULL;
		$return = array();
		
		// See if we are filtering by category
		if($category) {
			$sql .= "WHERE t1.id IN (SELECT `t2`.`project` FROM `project_category_rel` AS `t2` WHERE `t2`.`category`=?) " .
			        "ORDER BY `t1`.`id` DESC LIMIT ?,? ";
			$statement = parent::GetSqlManager()->GetStatement($sql);
			
			if($statement) {
				$statement->bind_param("iii", @sprintf((int)$category), @sprintf((int)$offset), @sprintf((int)$limit));
			}	// end if
		} else {
			$sql .= "ORDER BY `t1`.`id` DESC LIMIT ?,? ";
			$statement = parent::GetSqlManager()->GetStatement($sql);
			
			if($statement) {
				$statement->bind_param("ii", @sprintf((int)$offset), @sprintf((int)$limit));
			}	// end if
		}	// end if-else
		
		// Verify the statement was created successfully.
		if($statement) {
			$statement->execute();
			$statement->bind_result($id, $name, $excerpt);
			
			// Acquire all results from the query
			while($statement->fetch()) {
				$return[count($return)] = array('id'      => $id, 
												'name'    => $name, 
												'excerpt' => $excerpt);
			}	// end while
			
			$statement->close();
		}	// end if
		
		return $return;
	}	// end function GetProjects
	
	/*
	 * GetProject
	 * Acquires a project based on the ID.
	 *
	 * @param $project The project that will be acquired.
	 * @return array An array containing the project information.
	 */
	public function GetLastProject() {
		$sql = "SELECT `t1`.`id` AS `id`, " .
		       "`t1`.`name` AS `name`, " . 
			   "`t1`.`excerpt` AS `excerpt` " . 
			   "FROM `projects` AS `t1` ORDER BY `t1`.`id` DESC LIMIT 1";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		$return = false;
		
		// Verify the statement was created successfully.
		if($statement) {
			$statement->execute();
			$statement->bind_result($id, $name, $excerpt);
			
			// Should only acquire one result.
			if($statement->fetch()) {
				$return = array('id'          => $id, 
				                'name'        => $name, 
								'excerpt'     => $excerpt);
			}	// end while
			
			$statement->close();
		}	// end if
		
		return $return;
	}	// end function GetLastProject
	
	/*
	 * GetProject
	 * Acquires a project based on the ID.
	 *
	 * @param $project The project that will be acquired.
	 * @return array An array containing the project information.
	 */
	public function GetProject($project) {
		$sql = "SELECT `t1`.`id` AS `id`, " .
		       "`t1`.`name` AS `name`, " . 
			   "`t1`.`excerpt` AS `excerpt`, " . 
			   "`t1`.`description` AS `description` " . 
			   "FROM `projects` AS `t1` WHERE id=?";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		$return = false;
		
		// Verify the statement was created successfully.
		if($statement) {
			$statement->bind_param("i", @sprintf((int)$project));
			$statement->execute();
			$statement->bind_result($id, $name, $excerpt, $description);
			
			// Should only acquire one result.
			if($statement->fetch()) {
				$return = array('id'          => $id, 
				                'name'        => $name, 
								'excerpt'     => $excerpt, 
								'description' => $description);
			}	// end while
			
			$statement->close();
		}	// end if
		
		return $return;
	}	// end function GetProjects
}	// end class ProjectModel

?>