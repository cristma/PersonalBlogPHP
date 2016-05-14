<?php

/*
 * CategoryModel
 * Created on June 24, 2013 by Matthew A. Crist.
 *
 * This class will house the information for the creation and the acquisition of the category
 * information that is available on the website.
 *
 */

include_once($root . "lib/SqlManaged.php");

class CategoryModel extends SqlManaged {
	/*
	 * Constructor for this class.
	 * @param $l_sqlManager A reference to an existing SqlManager.
	 */
	public function __construct($l_sqlManager = null) {
		parent::SetSqlManager($l_sqlManager);
	}	// end function __construct
	
	/*
	 * Destructor for this class.
	 */
	public function __destruct() {
	}	// end function __destruct
	
	/*
	 * AddArticleRelation
	 * Adds a category-article relation.
	 *
	 * @param $category The category.
	 * @param $article The article.
	 * @param boolean The status of the addition to the table.
	 */
	public function AddArticleRelation($category, $article) {
		$sql = "INSERT INTO `article_category_rel` (`category`, `article`) VALUES (?, ?)";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		
		// Verify that a statement was created successfully.
		if($statement) {
			$statement->bind_param("ii", @sprintf((int)$category), @sprintf((int)$article));
			$statement->execute();
			
			// Verify that a row was added.
			if($statement->affected_rows > 0) {
				$statement->close();
				return true;
			}	// end if
		}	// end if
		
		return false;
	}	// end function AddPostRelation 
	
	/*
	 * AddPostRelation
	 * Adds a category-post relation.
	 *
	 * @param $category The category.
	 * @param $post The post.
	 * @param boolean The status of the addition to the table.
	 */
	public function AddPostRelation($category, $post) {
		$sql = "INSERT INTO `post_category_rel` (`category`, `post`) VALUES (?, ?)";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		
		// Verify that a statement was created successfully.
		if($statement) {
			$statement->bind_param("ii", @sprintf((int)$category), @sprintf((int)$post));
			$statement->execute();
			
			// Verify that a row was added.
			if($statement->affected_rows > 0) {
				return true;
			}	// end if
		}	// end if
		
		return false;
	}	// end function AddPostRelation 
	
	/*
	 * AddProjectRelation
	 * Adds a category-project relation.
	 *
	 * @param $category The category.
	 * @param $project The project.
	 * @param boolean The status of the addition to the table.
	 */
	public function AddProjectRelation($category, $project) {
		$sql = "INSERT INTO `project_category_rel` (`category`, `project`) VALUES (?, ?)";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		
		// Verify that a statement was created successfully.
		if($statement) {
			$statement->bind_param("ii", @sprintf((int)$category), @sprintf((int)$project));
			$statement->execute();
			
			// Verify that a row was added.
			if($statement->affected_rows > 0) {
				return true;
			}	// end if
		}	// end if
		
		return false;
	}	// end function AddProjectRelation
	
	/*
	 * ModifyCategory
	 * Edits the category information for a category.
	 *
	 * @param $id The id of the category to edit.
	 * @param $name The name of this category.
	 * @param $description The description of this category.
	 * @param $active The active state of the category.
	 * @param $access The minimum access level for this category.
	 * @return boolean The status of successful editing.
	 */
	public function ModifyCategory($id, $name, $description, $active, $access) {
		$sql = "UPDATE `categories` SET `name`=?, `description`=?, `active`=?, `access`=? WHERE `id`=?";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		
		// Verify the statement was created successfully.
		if($statement) {
			$statement->execute();
			
			// Verify that ws have changed a row in the table.
			if($statement->affected_rows) {
				$statement->close();
				return true;
			}	// end if
		}	// end if
		
		return false;
	}	// end function ModifyCategory
	
	/*
	 * DeleteCategory
	 * Deletes a category from the category table.
	 * 
	 * @param $category The ID of the category to be deleted.
	 * @param boolean The successful deletion of the category.
	 */
	public function DeleteCategory($category) {
		$sql = "DELETE FROM `categories` WHERE `id`=?";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		
		// Verify the statement was created successfully
		if($statement) {
			$statement->execute($sql);
			
			// Make sure something was actually deleted.
			if($statement->affected_rows > 0) {
				$statement->close();
				return true;
			}	// end if
		}	// end if
		
		return false;
	}	// end function DeleteCategory
	
	/*
	 * AddCategory
	 * Adds a category to the categories table.
	 *
	 * @param $name The name of the category.
	 * @param $description The description of the category.
	 * @param $active The active state of the category.
	 * @param $access the access level required to view the category.
	 * @return integer The ID on a successful insertion, false on failure.
	 */
	public function AddCategory($name, $description, $active, $access) {
		$sql = "INSERT INTO `categories` (`name`, `description`, `active`, `access`) VALUES (?, ?, ?, ?)";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		
		// Verify that our statement was created successfully.
		if($statement) {
			$statement->bind_param("ssii", @sprintf((string)$name), 
			                               @sprintf((string)$description), 
										   @sprintf((int)$active), 
										   @sprintf((int)$access));
			$statement->execute();
			
			if($statement->affected_rows > 0) {
				$statement->close();
				return parent::GetSqlManager()->LastId();
			}	// end if
		}	// end if
		
		return false;
	}	// end function AddCategory
	
	/*
	 * GetCategories
	 * Acquires a list of categories that are available based on the security setting.
	 *
	 * @param $l_security The security level which must be met by the category acquisition.
	 * @return array The tuple of categories such that x(id, name, description) 
	 *               foreach x in the series.
	 */
	public function GetCategories() {
		$sql = "SELECT categories.id AS id, categories.name AS name, categories.description AS description, " .
		       "(SELECT count(id) FROM article_category_rel WHERE `article_category_rel`.`category`=`categories`.`id`) AS `article_count`, " . 
		       "(SELECT count(id) FROM project_category_rel WHERE `project_category_rel`.`category`=`categories`.`id`) AS `project_count`, " . 
		       "(SELECT count(id) FROM post_category_rel WHERE `post_category_rel`.`category`=`categories`.`id`) AS `post_count` " . 
		       "FROM categories ORDER BY categories.name";
				
		$statement = parent::GetSqlManager()->GetStatement($sql);
		$return = array();
		
		// Verify that our statement was initialized
		if($statement) {
			$statement->execute();
			$statement->bind_result($id, $name, $description, $article_count, $project_count, $post_count);
			
			// Make sure we have results to process
			while($statement->fetch())
			{
				$return[count($return)] = array('id'=>$id, 'name'=>$name, 'description'=>$description, 'articles'=>$article_count, 'projects'=>$project_count, 'posts'=>$post_count);
			}	// end while
			
			$statement->close();
		}	// end if
		
		return $return;
	}	// end function GetCategories
	
	/*
	 * GetArticleCategories
	 * Acquires the categories that are associated with an article.
	 *
	 * @param $article The article that is associate with the categories.
	 * @return array The list of categories associated with this article.
	 */
	public function GetArticleCategories($article) {
		$sql = "SELECT `t1`.`id` AS `id`, `t1`.`name` AS `name` FROM `categories` AS `t1` INNER JOIN `article_category_rel` AS `t2` ON `t2`.`category`=`t1`.`id` WHERE `t2`.`article`=? ORDER BY `t1`.`name`";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		$results = array();
		
		if($statement) {
			$statement->bind_param("i", @sprintf((int)$article));
			$statement->execute();
			$statement->bind_result($id, $name);
			
			while($statement->fetch()) {
				$results[count($results)] = array('id'=>$id, 'name'=>$name);
			}	// end while
		}
		
		return $results;
	}	// end function
	
	/*
	 * GetCategoriesWithArticles
	 * Acquires all categories that currently have an article associated with it.
	 *
	 * @return array The array of categories with an id and name that currently are associate with an article in the article_category_rel table.
	 */
	public function GetCategoriesWithArticles() {
		$sql = "SELECT DISTINCT `t1`.`id` AS `id`, `t1`.`name` AS `name`, " . 
		       "(SELECT COUNT(`id`) FROM `article_category_rel` WHERE `article_category_rel`.`category`=`t1`.`id`) AS `count`" .
		       " FROM `categories` AS `t1` INNER JOIN `article_category_rel` AS `t2` ON `t1`.`id`=`t2`.`category` ORDER BY `t1`.`name`";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		$return = array();
		
		// Verify that our statement was initialized
		if($statement) {
			$statement->execute();
			$statement->bind_result($id, $name, $article_count);
			
			while($statement->fetch()) {
				$return[count($return)] = array('id'=>$id, 'name'=>$name, 'articles'=>$article_count);
			}	// end while
			
			$statement->close();
		}	// end if
		
		return $return;
	}	// end function GetCategoriesWithArticles
	
	/*
	 * GetProjectCategories
	 * Acquires the categories that are associated with a project.
	 *
	 * @param $project The project that is associate with the categories.
	 * @return array The list of categories associated with this project.
	 */
	public function GetProjectCategories($project) {
		$sql = "SELECT `t1`.`id` AS `id`, `t1`.`name` AS `name` FROM `categories` AS `t1` INNER JOIN `project_category_rel` AS `t2` ON `t2`.`category`=`t1`.`id` WHERE `t2`.`project`=? ORDER BY `t1`.`name`";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		$results = array();
		
		if($statement) {
			$statement->bind_param("i", @sprintf((int)$project));
			$statement->execute();
			$statement->bind_result($id, $name);
			
			while($statement->fetch()) {
				$results[count($results)] = array('id'=>$id, 'name'=>$name);
			}	// end while
			
			$statement->close();
		}	// end if
		
		return $results;
	}	// end function
	
	/*
	 * GetCategoriesWithProjects
	 * Acquires all categories that currently have a project associated with it.
	 *
	 * @return array The array of categories with id and name that currently are associated with a project in the project_category_rel table.
	 */
	public function GetCategoriesWithProjects() {
		$sql = "SELECT DISTINCT `t1`.`id` AS `id`, `t1`.`name` AS `name`, (SELECT COUNT(`id`) FROM `project_category_rel` WHERE `project_category_rel`.`category`=`t1`.`id`) AS `projects` FROM `categories` AS `t1` INNER JOIN `project_category_rel` AS `t2` ON `t1`.`id`=`t2`.`category` ORDER BY `t1`.`name`";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		$return = array();
		
		// Verify the statement was created
		if($statement) {
			$statement->execute();
			$statement->bind_result($id, $name, $project_count);
			
			while($statement->fetch()) {
				$return[count($return)] = array('id'=>$id, 'name'=>$name, 'projects'=>$project_count);
			}	// end while
			
			$statement->close();
		}	// end if
		
		return $return;
	}	// end function GetCategoriesWithProjects
	
	/*
	 * GetPostCategories
	 * Acquires the categories that are associated with a post.
	 *
	 * @param $post The post that is associate with the categories.
	 * @return array The list of categories associated with this post.
	 */
	public function GetPostCategories($post) {
		$sql = "SELECT `t1`.`id` AS `id`, `t1`.`name` AS `name` FROM `categories` AS `t1` INNER JOIN `post_category_rel` AS `t2` ON `t2`.`category`=`t1`.`id` WHERE `t2`.`post`=? ORDER BY `t1`.`name`";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		$results = array();
		
		if($statement) {
			$statement->bind_param("i", @sprintf((int)$post));
			$statement->execute();
			$statement->bind_result($id, $name);
			
			while($statement->fetch()) {
				$results[count($results)] = array('id'=>$id, 'name'=>$name);
			}	// end while
			
			$statement->close();
		}	// end if
		
		return $results;
	}	// end function
	
	/*
	 * GetCategoriesWithPosts
	 * Acquires all the categories that currently have posts associated with them.
	 *
	 * @return array The array of categories with id and name that currently are associate with a post in the post_category_rel table.
	 */
	public function GetCategoriesWithPosts() {
		$sql = "SELECT DISTINCT `t1`.`id` AS `id`, `t1`.`name` AS `name`, " .
		       "(SELECT COUNT(`id`) FROM `post_category_rel` WHERE `post_category_rel`.`category`=`t1`.`id`) as `count`" .
		       " FROM `categories` AS `t1` INNER JOIN `post_category_rel` AS `t2` ON `t1`.`id`=`t2`.`category` ORDER BY `t1`.`name`";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		$return = array();
		
		// Make sure that our statement was initialized
		if($statement) {
			$statement->execute();
			$statement->bind_result($id, $name, $post_count);
			
			while($statement->fetch()) {
				$return[count($return)] = array('id' => $id, 'name' => $name, 'posts'=>$post_count);
			}	// end while
			
			$statement->close();
		}	// end if
		
		return $return;
	}	// end function GetCategoriesWithPosts
	
	/*
	 * GetCategory
	 * Acquires the information for a single category.
	 *
	 * @return array The values that are associated with this category(id, name, description)
	 */
	public function GetCategory($category) {
		$sql = "SELECT `name`, `description` FROM `categories` WHERE `id`=?";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		
		if($statement) {
			$statement->bind_param("i", @sprintf((int)$category));
			$statement->execute();
			$statement->bind_result($name, $description);
			
			if($statement->fetch()) {
				return array("id"=>$category, "name"=>$name, "description"=>$description);
			}	// end if
		}	// end if
		
		return false;
	}	// end function GetCategory
}	// end class CategoryModel

?>