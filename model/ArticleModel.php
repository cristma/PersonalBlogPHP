<?php

include_once($root.'lib/SqlManaged.php');

class ArticleModel extends SqlManaged {
	/*
	 * Default constructor for this class.
	 */
	public function __construct($sqlManager = NULL)	{
		parent::SetSqlManager($sqlManager);
	}	// end constructor

	/*
	 * The destructor for this class.
	 */
	public function __destruct() {
	}

	/*
	 * AddRating
	 * Adds a rating to the article..
	 *
	 * @param $article The article that is to be rated.
	 * @param $user The user who is rating the article.
	 * @param $value The weight of the rating the user is giving the article.
	 * @return boolean The successful submission of a rating.
	 */
	public function AddRating($article, $user, $rating) {
		$sql = "INSERT INTO `article_ratings` (`article`, `user`, `value`) VALUES (?, ?, ?)";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		
		// Verify the statement was created successfully.
		if($statement) {
			$statement->bind_param("iii", @sprintf((int)$article), 
			                              @sprintf((int)$user), 
										  @sprintf((int)$rating));
			$statement->execute();
			
			if($statement->rows_affected > 0) {
				$statement->close();
				return true;
			}	// end if
			
			$statement->close();
		}	// end if
		
		return false;
	}	// end function AddRating

	/*
	 * GetRatings
	 * Acquires the ratings for an article.
	 *
	 * @param $article The article in which the ratings are to be acquired.
	 * @return array A list of the ratings for the article.
	 */
	public function GetRatings($article) {
		$sql = "SELECT `t1`.`id` AS `id`, " . 
		              "(SELECT `t2`.`fname` FROM `profiles` AS `t2` WHERE `t2`.`user`=`t1`.`user`) AS `user`, " . 
					  "`t1`.`user` AS `user_id`, " . 
					  "`t1`.`value` AS `value` FROM `article_ratings` AS `t1` WHERE `t1`.`article`=? ORDER BY `t1`.`id` DESC";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		$results   = array();
		
		// Verify the statement was created successfully.
		if($statement) {
			$statement->bind_param("i", @sprintf((int)$article));
			$statement->execute();
			$statement->bind_result($id, $user, $user_id, $value);
			
			// Use all the results from the result set
			while($statement->fetch()) {
				$results[count($results)] = array('id'      => $id, 
				                                  'user'    => $user, 
												  'user_id' => $user_id, 
												  'value'   => $value);
			}	// end while
			
			$statement->close();
		}	// end if
		
		return $results;
	}	// end function GetRatings
	
	/*
	 * GetRatingsByUser
	 * Acquires the ratings by user.
	 *
	 * @param $user The user in which you are acquiring ratings.
	 * @return array A list of the ratings the user has submitted.
	 */
	public function GetRatingsByUser($user) {
		$sql = "SELECT `t1`.`id` AS `id`, " . 
		              "`t1`.`article` AS `article_id`, " . 
					  "(SELECT `t2`.`title` FROM `articles` AS `t2` WHERE `t2`.`id`=`t1`.`article`) AS `article`, " .
					  "`t1`.`value` AS `value` FROM `article_ratings` AS `t2` WHERE `t1`.`user`=? ORDER BY `t1`.`id` DESC";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		$results   = array();
		
		// Verify the statement was created successfully.
		if($statement) {
			$statement->bind_param("i", @sprintf((int)$user));
			$statement->execute();
			$statement->bind_result($id, $article_id, $article, $value);
			
			// Use all the rows in the result set.
			while($statement->fetch()) {
				$results[count($results)] = array('id'         => $id, 
				                                  'article_id' => $article_id, 
												  'article'    => $article, 
												  'value'      => $value);
			}	// end while
			
			$statement->close();
		}	// end if
		
		return $results;
	}	// end function GetRatingsByUser

	/*
	 * PublishArticle
	 * Updates the published state of the article.
	 *
	 * @param $article The article that will have its status changed.
	 * @param $state The publish state in which to change the article.
	 * @return The boolean state of the status update.
	 */
	public function PublishArticle($article, $status) {
		$sql = "UPDATE `articles` SET `published`=? WHERE `id`=?";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		
		// Determine if we were able to create a statement
		if($statement) {
			$statement->bind_param("ii", @sprintf((int)$status), @sprintf((int)$article));
			$statement->execute();
			
			// See if anything actually changed.
			if($statement->affected_rows > 0) {
				$statement->close();
				return true;
			}	// end if
			
			$statement->close();
		} // end if
		
		return false;
	}	// end function PublishArticle
	
	/*
	 * ModifyArticle
	 * Updates an article's information in the database.
	 *
	 * @param $id The id of the article in the articles table.
	 * @param $title The new title of the article.
	 * @param $excerpt The excerpt for the article.
	 * @param $content The content for the article.
	 * @param $author The author of the article.
	 * @param $published The published state of the article.
	 * @return boolean The status of updating the entry.
	 */
	public function ModifyArticle($id, $title, $excerpt, $content, $published) {
		$sql = "UPDATE `articles` SET `title`=?, `excerpt`=?, `content`=?, `published`=? WHERE `id`=?";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		$return = false;
		
		// Verify the statement was created successfully.
		if($statement) {
			$statement->bind_param("sssii", @sprintf((string)$title), 
			                                @sprintf((string)$excerpt), 
										    @sprintf((string)$content), 
										    @sprintf((string)$published), 
										    @sprintf((int)$id));
			$statement->execute();
			
			// Verify that something was modified
			if($statement->affected_rows > 0) {
				$return = true;
			}	// end if
			
			$statement->close();
		}	// end if
		
		return $return;
	}	// end function ModifyArticle

	/*
	 * DeleteArticle
	 * This function will delete an article from the database (and since the section and 
	 * category relations are cascade on delete, the entries will be removed from there 
	 * as well.
	 *
	 * @param $article The article that is to be deleted.
	 * @param boolean The status of deletion of the article.
	 */
	public function DeleteArticle($article) {
		$sql = "DELETE FROM `articles` where `id`=?";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		$return = false;
		
		// Verify that our statement was created successfully.
		if($statement) {
			$statement->bind_param("i", @sprintf((int)$article));
			$statement->execute();
			
			if($statement->affected_rows > 0) {
				$return = true;
			}	// end if
			
			$statement->close();
		}	// end if
		
		return $return;
	}	// end function

	/*
	 * AddArticle
	 * Adds an article to the articles table and adds the relations to the categories.
	 *
	 * @param $title The title of the article.
	 * @param $excerpt The short summary of the article.
	 * @param $content The "meat" of the article.
	 * @param $author The id of the author who submitted this article.
	 * @param $published The published state of the article.
	 * @return integer The successful addition of the article will return the id, failure is false.
	 */
	public function AddArticle($title, $excerpt, $content, $author, $published) {
		$sql = "INSERT INTO `articles` (`title`, `excerpt`, `content`, `author`, `published`) VALUES (?, ?, ?, ?, ?)";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		
		// Verify the statement was initialized properly
		if($statement) {
			$statement->bind_param("sssii", @sprintf((string)$title), 
			                               @sprintf((string)$excerpt), 
										   @sprintf((string)$content), 
										   @sprintf((int)$author), 
										   @sprintf((int)$published));
			$statement->execute();
			
			// Verify that we have actually added to the table.
			if($statement->affected_rows > 0) {
				$statement->close();
				return parent::GetSqlManager()->LastId();
			}	// end if
		}	// end if
		
		return false;
	}	// end function AddArticle

	/**
	  * GetArticleCount
	  * Acquires the total count of articles either in a given category or overall count.
	  *
	  * @param $category The ID of the category from the categories table that is associated with the
	  *                       category in the articles_category_rel table.
	  *
	  * @return integer The total number of articles or the total number of articles in a category if
	  *                 the category is not NULL.
	  */
	public function GetArticleCount($category=NULL, $unpublished=false) {
		// Acquisition of the count of all articles.
		$sql = "SELECT COUNT(`t1`.`id`) AS `total` FROM `articles` AS `t1` "; 
		$statement = NULL;
		$return = 0;

		// If there is a category ID passed, we trim our query to those that are assigned to the category.
		if($category) {
			$sql .= "INNER JOIN `article_category_rel` AS `t2` ON `t1`.`id`=`t2`.`article` WHERE `t2`.`category`=? ";
			
			if(!$unpublished) {
				$sql .= "AND `t1`.`published`=1";
			}	// end if
			
			$statement = parent::GetSqlManager()->GetStatement($sql);
			
			// Make sure our statement was initialized
			if($statement) {
				$statement->bind_param("i", @sprintf((int)$category));
			}	// end if
		} else {
			if(!$unpublished) {
				$sql .= "WHERE `t1`.`published`=1";
			}	// end if
			
			$statement = parent::GetSqlManager()->GetStatement($sql);
		}	// end if-else

		// Verify our statement was created successfully.
		if($statement) {
			$statement->execute();
			$statement->bind_result($total);
			
			// Make sure we have a result
			if($statement->fetch()) {
				$return = $total;
				$statement->close();
			}	// end if
		}	// end if
		
		return $total;
	}	// end function GetArticleCount
	
	/**
	  * GetArticle
	  * Acquires a single article and the information associated with it.  It does not include the excerpt.
	  *
	  * @param $article The article that will be selected from the table.
	  * @param $published Used to determine if we can access the article if it is unpublished.
	  * @return array An array containing the id, content, date author_id, author from the articles table.
	  */
	public function GetArticle($article, $unpublished=false) {
		$sql = "SELECT `t1`.`id` AS `id`, " . 
		              "`t1`.`title` AS `title`, " . 
					  "`t1`.`content` AS `content`, " . 
					  "`t1`.`timestamp` AS `date`, " . 
					  "`t1`.`author` AS `author_id`, " .
		              "(SELECT `t2`.`fname` FROM `profiles` AS `t2` WHERE `t2`.`user`=`t1`.`author`) AS `author`, " . 
					  "`t1`.`published` AS `published` " . 
					  "FROM `articles` AS `t1` WHERE `t1`.`id`=?";

		// Determine if we can fetch an unpublished article
		if(!$unpublished) {
			$sql .= " AND `t1`.`published`=1";
		}	// end if
		
		$sql .= " ORDER BY `t1`.`timestamp` DESC";
		
		$statement = parent::GetSqlManager()->GetStatement($sql);
		$result = array();
		
		// Verify that our statement is initialized
		if($statement) {
			$statement->bind_param("i", @sprintf((int)$article));
			$statement->execute();
			$statement->bind_result($id, $title, $content, $date, $author_id, $author, $published);
			
			// Verify that we have a result
			if($statement->fetch()) {
				$result = array('id'        => $id, 
				                'title'     => $title, 
								'content'   => $content, 
								'date'      => $date, 
								'author_id' => $author_id, 
								'author'    => $author, 
								'published' => $published);
			}	// end if

			$statement->close();
		}	// end if
		
		return $result;
	}	// end function

	/*
	 * GetLastArticle
	 * Acquires the latest article that was published.
	 *
	 * @return array An array of the fields that are relavent to the article.
	 */
	public function GetLastArticle() {
		$sql = "SELECT `t1`.`id` AS `id`, " .
		              "`t1`.`title` AS `title`, " .
					  "`t1`.`excerpt` AS `excerpt`, " . 
					  "`t1`.`timestamp` AS `date`, " . 
					  "`t1`.`author` AS `author_id`, " . 
					  "(SELECT `t2`.`fname` FROM `profiles` AS `t2` WHERE `t2`.`user`=`t1`.`author`) AS `author` " . 
					  "FROM `articles` AS `t1` WHERE `t1`.`published`=1 ORDER BY `t1`.`timestamp` DESC LIMIT 1";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		$return = false;
		
		// Verify the statement was created successfully.
		if($statement) {
			$statement->execute();
			$statement->bind_result($id, $title, $excerpt, $date, $author_id, $author);
			
			// Make sure we acquired a result
			if($statement->fetch()) {
				$return = array('id' => $id, 
				                'title' => $title, 
								'excerpt' => $excerpt, 
								'date' => $date, 
								'author_id' => $author_id, 
								'author' => $author);
			}	// end if
			
			$statement->close();
		}	// end if
		
		return $return;
	}	// end function GetLastArticle

	/*
	 * GetArticles
	 * Acquires all the articles of a section and category.  If either of these are 
	 * NULL, then all article will be fetched (filtered by the one that is not NULL.
	 *
	 * @param $category The category that will be used to filter the results.
	 * @param $limit The number of articles to acquire.
	 * @param $offset The number of entries to offset by in the SQL table (used for pagination).
	 * @return array-array An array of articles of size $limit with the id, title, excerpt
	 */
	public function GetArticles($limit = 5, $offset = 0, $unpublished=false) {
		$sql = "SELECT `t1`.`id` AS `id`, " .
			   "`t1`.`title` AS `title`, " . 
			   "`t1`.`excerpt` AS `excerpt`, " .  
			   "`t1`.`timestamp` AS `date`, " . 
			   "`t1`.`author` AS `author_id`, " .
			   "(SELECT `t2`.`fname` FROM `profiles` AS `t2` WHERE `t2`.`user`=`t1`.`author`) AS `author`, " . 
			   "`t1`.`published` AS `published` " .
			   "FROM `articles` AS `t1`";
		$statement = NULL;
		$return = array();

		if(!$unpublished) {
			$sql .= " WHERE `t1`.`published`=1";
		}	// end if
					
		$sql .= " ORDER BY `t1`.`timestamp` DESC LIMIT ?,?";
		$statement = parent::GetSqlManager()->GetStatement($sql);

		if($statement) {
			$statement->bind_param("ii", @sprintf((int)$offset), 
										  @sprintf((int)$limit));
			$statement->execute();
			$statement->bind_result($id, $title, $excerpt, $date, $author_id, $author, $published);
			
			// Make sure we have some results to return.
			while($statement->fetch()) {
				$return[count($return)] = array('id'        => $id, 
				                                'title'     => $title, 
												'excerpt'   => $excerpt, 
												'date'      => $date, 
												'author_id' => $author_id, 
												'author'    => $author,
												'published' => $published);
			}	// end while
			
			$statement->close();
		}	// end if
		
		return $return;
	}	// end function GetArticles
	
	/*
	 * GetArticlesInCategory
	 * Acquires all articles that belong to a specific category.
	 * 
	 * @param $category The category in which the articles exist.
	 * @param $limit The number of articles to limit for retrieval.
	 * @param $offset The offset of rows in the SQL table.
	 * @param $unpublished Retrieval of unpublished articles.
	 * @return array An array of all the articles that meet the criteria.
	 */
	public function GetArticlesInCategory($category, $limit=5, $offset=0, $unpublished=false) {
		$sql = "SELECT `t1`.`id` AS `id`, " .
			   "`t1`.`title` AS `title`, " . 
			   "`t1`.`excerpt` AS `excerpt`, " .  
			   "`t1`.`timestamp` AS `date`, " . 
			   "`t1`.`author` AS `author_id`, " .
			   "(SELECT `t2`.`fname` FROM `profiles` AS `t2` WHERE `t2`.`user`=`t1`.`author`) AS `author`, " . 
			   "`t1`.`published` AS `published` " .
			   "FROM `articles` AS `t1` " . 
			   "WHERE `t1`.`id` IN (SELECT `t3`.`article` FROM `article_category_rel` AS `t3` WHERE `t3`.`category`=?)";
		
		// Determine if we are to acquire unpublished articles as well.
		if(!$unpublished) {
			$sql .= " AND `t1`.`published`=1";
		}	// end if
		
		$sql .= " ORDER BY `t1`.`timestamp` LIMIT ?,?";
		
		$statement = parent::GetSqlManager()->GetStatement($sql);
		$results   = array();
		
		// Verify that our statement was created successfully.
		if($statement) {
			$statement->bind_param("iii", @sprintf((int)$category), @sprintf((int)$offset), @sprintf((int)$limit));
			$statement->execute();
			$statement->bind_result($id, $title, $excerpt, $date, $author_id, $author, $published);
			
			while($statement->fetch()) {
				$results[count($results)] = array('id'        => $id, 
				                                  'title'     => $title, 
												  'excerpt'   => $excerpt, 
												  'date'      => $date, 
												  'author_id' => $author_id, 
												  'author'    => $author, 
												  'published' => $published);
			}	// end while
			
			$statement->close();
		}	// end if
		
		return $results;
	}	// end function GetArticlesInCategory

	/*
	 * GetArticlesByAuthor
	 * Acquires all articles that belong to a specific author.
	 * 
	 * @param $author The author in which to locate articles.
	 * @param $limit The limit to the number of entries returned by the query.
	 * @param $offset The offset in the SQL table to acquire results from.
	 * @param $unpublished The retrieval of unpublished articles.
	 * @return array A list of articles for the given author.
	 */
	public function GetArticlesByAuthor($author, $limit=5, $offset=0, $unpublished=false) {
		$sql = "SELECT `t1`.`id` AS `id`, " .
			   "`t1`.`title` AS `title`, " . 
			   "`t1`.`excerpt` AS `excerpt`, " .  
			   "`t1`.`timestamp` AS `date`, " . 
			   "`t1`.`author` AS `author_id`, " .
			   "(SELECT `t2`.`fname` FROM `profiles` AS `t2` WHERE `t2`.`user`=`t1`.`author`) AS `author`,  " . 
			   "`t1`.`published` AS `published` " . 
			   "FROM `articles` AS `t1` WHERE `t1`.`author`=? ";
		
		// Determine if we are to fetch unpublished articles.
		if(!$unpublished) {
			$sql .= "AND `t1`.`published`=1 ";
		}	// end if
			   
		$sql .= "ORDER BY `t1`.`timestamp` LIMIT ?,?";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		$results   = array();
		
		// Determine if we created a statement successfully.
		if($statement) {
			$statement->bind_param("iii", @sprintf((int)$author), @sprintf((int)$offset), @sprintf((int)$limit));
			$statement->execute();
			$statement->bind_result($id, $title, $excerpt, $date, $author_id, $author, $published);
			
			// Acquire all results and push them into an array.
			while($statement->fetch()) {
				$results[count($results)] = array('id'        => $id, 
				                                  'title'     => $title, 
												  'excerpt'   => $excerpt, 
												  'date'      => $date, 
												  'author_id' => $author_id, 
												  'author'    => $author, 
												  'published' => $published);
			}	// end while
			
			$statement->close();
		}	// end if
		
		return $results;
	}	// end function GetArticlesByAuthor
}	// end class ArticleModel

?>