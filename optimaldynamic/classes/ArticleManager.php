<?php

include_once(dirname(dirname(__FILE__)) . '/classes/SqlManaged.php');
include_once(dirname(dirname(__FILE__)) . '/classes/SqlManager.php');

class ArticleManager extends SqlManaged
{
	/**
	  * Constructor for this class.
	  *
	  * @param $l_sqlManager A reference to an existing SqlManager, otherwise
	  *                      a null reference or default creates a new SqlManager
	  *                      instance.
	  */
	public function __construct($l_sqlManager=null)
	{
		parent::SetSqlManager($l_sqlManager);
	}	// end constructor
	
	/**
	  * Creates a new article and saves it to the database.
	  */
	public function CreateArticle($l_name, $l_content, $l_category, $l_author)
	{
		// Prepare a statement for execution
		$l_sql = "INSERT INTO `article` (`name`, `content`, `category`, `author`) VALUES (?, ?, ?, ?)";
		$l_statement = parent::GetSqlManager()->GetStatement($l_sql);
		
		// Verify our statement was created successfully
		if($l_statement)
		{
			$l_statement->bind_param('ssii', @sprintf($l_name), 
			                                 @sprintf($l_content), 
											 @sprintf((int)$l_category), 
											 @sprintf((int)$l_author));
			
			// Needs to be verbous to the user.  Do not handle here.
			$l_statement->execute();
			$l_result = $l_statement->insert_id;
			$l_statement->close();
			
			return $l_result;
		}	// end if
		
		// Error is handled in the SqlManager
		return false;
	}	// end function CreateArticle
	
	/**
	  * Associates an article with a list of sections.
	  *
	  * @param $l_sections The list of sections in which this article will be associated.
	  * @param $l_id The id of the article to associate to sections.
	  *
	  * @return boolean True on successful creation, otherwise False
	  */
	public function AssociateToSections($l_id, $l_sections)
	{
		// Execute an insertion for each section.
		foreach($l_sections as $l_section)
		{
			$l_sql = 'INSERT INTO `section_article_relation` (`section`, `article`) VALUES (?, ?)';
			$l_statement = parent::GetSqlManager()->GetStatement($l_sql);
			
			// Verify that the statement was executed properly.
			if($l_statement)
			{
				// We are going to assume the statement executed properly
				$l_statement->bind_params("ii", @sprintf((int)$l_section), 
				                                @sprintf((int)$l_id));
				$l_statement->execute();
				$l_statement->close();
			}	// end if
			else
			{
				// Error should have been handled in the SqlManager
				return false;
			}	// end if
		}	// end foreach
		
		// Success
		return true;
	}	// end function AssociateToSections
	
	/**
	  * Deletes an article.
	  */
	public function DeleteArticle($l_article)
	{
		// Prepare a statement for execution
		$l_sql = "DELETE FROM `article` WHERE `id`=?";
		$l_statement = parent::GetSqlManager()->GetStatement($l_sql);
		
		// Verify that we have created a statement
		if($l_statement)
		{
			$l_statement->bind_param("i", @sprintf((int)$l_article));
			
			// Needs to be verbous to user.  Do not handle here.
			$l_statement->execute();
			$l_affected = $l_statement->affected_rows;
			$l_statement->close();
			
			if($l_affected > 0)
				return true;
			else
				return false;
		}	// end if
		
		// Error should be handled in the SqlManager
		return false;
	}	// end function DeleteArticle
	
	/**
	  * Modifies an article.
	  */
	public function ModifyArticle($l_id, $l_name, $l_content, $l_category)
	{
		// Prepare a statement for execution
		$l_sql = 'UPDATE `article` SET `name`=?, `content`=?, `category`=? WHERE `id`=?';
		$l_statement = parent::GetSqlManager()->GetStatement();
		
		// Verify the statement was created successfully
		if($l_statement)
		{
			$l_statement->bind_param("ssii", @sprintf($l_name), 
											 @sprintf($l_content), 
											 (int)@sprintf($l_category), 
											 (int)@sprintf($l_id));
			
			// Needs to be verbous to user.  Do not handle here.
			$l_return = $l_statement->execute();
			$l_statement->close();
			
			return $l_return;
		}	// end if
		
		// Error should be handled in the SqlManager
		return false;
	}	// end function ModifyArticle
	
	/**
	  * Acquires a single article.
	  */
	public function GetArticle($l_id)
	{
		// Prepare a statement for execution
		$l_sql = 'SELECT `article`.`id` AS `id`, ' .
				 '`article`.`name` AS `name`, ' .
		         '`article`.`content` AS `content`, ' . 
				 '`article`.`date` AS `date`, ' . 
				 '`t1`.`name` AS `author`, ' . 
				 '`t2`.`name` AS `category`' .
 		         'FROM `article` INNER JOIN `user` `t1` ON `t1`.`id`=`article`.`author` ' .
				 'INNER JOIN `category` `t2` ON `t2`.`id`=`article`.`category` ' . 
				 'WHERE `article`.`id`=?';
		$l_statement = parent::GetSqlManager()->GetStatement($l_sql);
		
		// Verify that our statement was created successfully
		if($l_statement)
		{
			$l_statement->bind_param("i", @sprintf((int)$l_id));
			
			// Needs to be verbous to user.  Do not handle here.
			$l_statement->execute();
			$l_result = $l_statement->get_result();
			$l_statement->close();
			
			return $l_result->fetch_assoc();
		}	// end if
		
		// Should be handled in the SqlManager
		return false;
	}	// end function GetArticle
	
	/**
	  * Acquires all articles in a specific category.
	  */
	/**
	  * Acquires a single article.
	  */
	public function GetArticlesInCategory($l_category)
	{
		// Prepare a statement for execution
		$l_sql = 'SELECT `article`.`id` AS `id`, ' .
				 '`article`.`name` AS `name`, ' .
		         '`article`.`content` AS `content`, ' . 
				 '`t1`.`name` AS `author`, ' . 
				 '`t2`.`name` AS `category`' .
 		         'FROM `article` INNER JOIN `user` `t1` ON `t1`.`id`=`article`.`author` ' .
				 'INNER JOIN `category` `t2` ON `t2`.`id`=`article`.`category` ' . 
				 'WHERE `article`.`category`=?';
		$l_statement = parent::GetSqlManager()->GetStatement();
		
		// Verify that our statement was created successfully
		if($l_statement)
		{
			$l_statement->bind_param("i", (int)@sprintf($l_category));
			
			// Needs to be verbous to user.  Do not handle here.
			$l_statement->execute();
			$l_results = $l_statement->get_results();
			$l_statement->close();
			
			return $l_results->fetch_assoc();
		}	// end if
		
		// Should be handled in the SqlManager
		return false;
	}	// end function GetArticlesInCategory
	
	/**
	  * Acquires all articles.
	  */
	public function GetArticles()
	{
		$l_sql = 'SELECT `article`.`id` AS `id`, ' .
				 '`article`.`name` AS `name`, ' .
		         '`article`.`content` AS `content`, ' . 
				 '`article`.`date` AS `date`, ' . 
				 '`t1`.`name` AS `author`, ' . 
				 '`t2`.`name` AS `category` ' .
 		         'FROM `article` INNER JOIN `user` `t1` ON `t1`.`id`=`article`.`author` ' .
				 'INNER JOIN `category` `t2` ON `t2`.`id`=`article`.`category`';
				 
		return parent::GetSqlManager()->Execute($l_sql);
	}	// end function GetArticles
	
	/**
	  * Acquires a specified number of articles by section.
	  *
	  * @param $l_section The section in which the articles must be associated.
	  * @param $l_num The number of recent articles to acquire from a section.
	  *
	  * @return array An array of articles indexed numerically with associative properties.
	  */
	public function GetArticlesInSection($l_section, $l_num = 10)
	{
		// Prepare the SQL statement and acquire a prepared statement
		$l_sql = 'SELECT `t1`.`name` AS `article_name`, ' . 
		         '`t1`.`id` AS `article_id`, ' . 
				 'LEFT(`article`.`content`, 255) AS `article_content`, ' . 
				 '`t2`.`id` AS `section_id`, ' . 
				 '`t2`.`name` AS `section_name`, ' . 
				 '`t3`.`id` AS `author_id`, ' . 
				 '`t3`.`name` AS `author_name` ' . 
				 'FROM `section_article` INNER JOIN `article` `t1` ON `section_article`.`article`=`t1`.`id` ' . 
				 'INNER JOIN `section` `t2` ON `section_article`.`section`=`t2`.`id` ' .
				 'INNER JOIN `user` `t3` ON `t1`.`author`=`t3`.`id` ' . 
				 'WHERE `section_article`.`section`=?' . 
				 'ORDER BY `section_article`.`id` LIMIT ?';
		$l_statement = parent::GetSqlManager()->GetStatement($l_sql);
		
		// Verify that we have acquired a prepared statement
		if($l_statement)
		{
			// Bind the parameters and complete the SQL query
			$l_statement->bind_params("ii", @sprintf((int)$l_section), @sprintf((int)$l_num));
			$l_statement->execute();
			$l_results = $l_statement->get_results();
			$l_statement->close();
			
			return $l_results->fetch_assoc();
		}	// end if
		
		// Error should have been handled by the SqlManager
		return false;
	}	// end function GetArticlesInSection
	
	/**
	  * Acquires the last X number of articles.
	  */
	public function GetRecentArticles($l_num = 4)
	{
		// Create a prepared statement for execution
		$l_sql = 'SELECT `article`.`id` AS `id`, ' .
		         '`article`.`name` AS `name`, ' .
			     '`article`.`date` AS `date`, ' . 
				 'LEFT(`article`.`content`, 255) AS `content`, ' . 
				 '`t1`.`name` AS `author`, ' . 
				 '`t2`.`name` AS `category` ' . 
				 'FROM `article` ' . 
				 'INNER JOIN `user` `t1` ON `t1`.`id`=`article`.`author` ' . 
				 'INNER JOIN `category` `t2` ON `t2`.`id`=`article`.`category` ' .
				 'ORDER BY `article`.`date` DESC LIMIT ?';
		$l_statement = parent::GetSqlManager()->GetStatement($l_sql);
		
		// Verify that our statement was created successfully.
		if($l_statement)
		{
			// Bind the parameters and execute the sql query
			$l_statement->bind_param("i", @sprintf((int)$l_num));
			$l_statement->execute();
			$l_results = $l_statement->get_result();
			
			$l_return = array();
			
			while($l_row = $l_results->fetch_assoc())
			{
				$l_return[count($l_return)] = $l_row;
			}	// end while

			$l_results->close();
			$l_statement->close();
				
			return $l_return;
		}	// end if

		// Error should be handled in the SqlManager
		return false;
	}	// end function GetRecentArticles
}	// end class ArticleManager

?>