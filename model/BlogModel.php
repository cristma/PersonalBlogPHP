<?php

/********************************************************************************************
 * BlogModel.php
 * This class contains the functions that will be used to acquire and manipulate the data ]
 * that relates to the "blog entries" known as posts.
 *
 * CHANGE LOG:
 * ------------------------------------------------------------------------------------------
 * 20130628_0234 - Initial conception of this file.
 * 20130819_1414 - Added rating functionality to the model.
 ********************************************************************************************/

include_once($root.'lib/SqlManaged.php');

class BlogModel extends SqlManaged {
	/*
	 * Constructor for this class.
	 * @param $sqlManager The sql manager that will be used to fetch results from the server.
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
	 * Search
	 * Searches the posts for relavent information based on the query provided.
	 *
	 * @param $query The term that will be sought.
	 * @return array An array of results from the query.
	 */
	public function Search($query) {
		$sql = "SELECT `t1`.`id` AS `id`, " . 
		              "`t1`.`title` AS `title`, " . 
					  "`t1`.`content` AS `content`, " . 
					  "`t1`.`date` AS `date`, " .
					  "(SELECT `t2`.`fname` FROM `profiles` AS `t2` WHERE `t1`.`author`=`t2`.`user`) AS `author`, " . 
					  "`t1`.`author` AS `author_id` " . 
					  "FROM `posts` AS `t1` WHERE `t1`.`title` LIKE ? OR `t1`.`content` LIKE ?";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		$return    = array();
		$value     = "%" . @sprintf((string)$query) . "%";
		
		// Make sure our query is valid
		if($statement) {
			$statement->bind_param("ss", $value, $value);
			$statement->execute();
			$statement->bind_result($id, $title, $content, $date, $author, $author_id);
			
			while($statement->fetch()) {
				$return[count($return)] = array("id"        => $id, 
				                                "title"     => $title, 
												"content"   => $content, 
												"date"      => $date, 
												"author"    => $author, 
												"author_id" => $author_id);
			}	// end while
			
			return $return;
		} else {
			return $return;
		}	// end if-else
	}	// end function Search
	
	/*
	 * AddRating
	 * Adds a rating for a post.
	 *
	 * @param $post The post in which the rating relates.
	 * @param $user The user that made the rating.
	 * @param $value The weight of the rating.
	 * @return boolean Status of adding the value to the table.
	 */
	public function AddRating($post, $user, $value) {
		$sql = "INSERT INTO `post_ratings` (`post`, `user`, `value`) VALUES (?, ?, ?)";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		
		// Verify the statement was created succesfully.
		if($statement) {
			$statement->bind_param("iii", @sprintf((int)$post), 
			                              @sprintf((int)$user), 
										  @sprintf((int)$value));
			$statement->execute();
			
			// Verify we actally made a change...
			if($statement->affected_rows > 0) {
				$statement->close();
				return true;
			}	// end if
			
			$statement->close();
		}	// end if
		
		return false;
	}	// end function AddRating
	
	/*
	 * GetRatings
	 * Acquires the ratings for a post.
	 *
	 * @param $post The post in which the ratings relate.
	 * @return array A list of the ratings for the post.
	 */
	public function GetRatingsByPost($post) {
		$sql = "SELECT `t1`.`id` AS `id`, " . 
		              "`t1`.`user` AS `user_id`, " . 
					  "(SELECT `t2`.`fname` FROM `profiles` AS `t2` WHERE `t2`.`user`=`t1`.`user`) AS `user`, " . 
					  "`t1`.`value` AS `value` FROM `post_ratings` AS `t1` WHERE `t1`.`post`=? ORDER BY `t1`.`id` DESC";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		$results   = array();
		
		// Verify the statement was created successfully.
		if($statement) {
			$statement->bind_param("i", @sprintf((int)$post));
			$statement->execute();
			$statement->bind_result($id, $user_id, $user, $value);
			
			// Use all the rows from the result set.
			while($statement->fetch()) {
				$results[count($results)] = array('id'      => $id, 
				                                  'user_id' => $user_id, 
												  'user'    => $user, 
												  'value'   => $value);
			}	// end while
			
			$statement->close();
		}	// end if
		
		return $results;
	}	// end function GetRatingsByPost
	
	/*
	 * GetRatingsValue
	 * Acquires the summation of the ratings of this post.
	 *
	 * @return integer The summation of the values for a rating on a post.
	 */
	public function GetRatingsValue($post) {
		$sql = "SELECT SUM(`t1`.`value`) AS `rating` FROM `post_ratings` AS `t1` WHERE `t1`.`post`=?";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		
		if($statement) {
			$statement->bind_param("i", @sprintf((int)$post));
			$statement->execute();
			$statement->bind_result($value);
			
			if($statement->fetch()) {
				$statement->close();
				return $value;
			}	// end if
		}	// end if
		
		return 0;
	}	// end function GetRatingsValue
	
	/*
	 * GetRatingsByUser
	 * Acquires the ratings from a user.
	 *
	 * @param $user The user in which the ratings relate.
	 * @return array The list of ratings of posts for this user.
	 */
	public function GetRatingsByUser($user) {
		$sql = "SELECT `t1`.`id` AS `id`, " . 
		              "`t1`.`post` AS `post_id`, " .
					  "(SELECT `t2`.`title` FROM `posts` AS `t2` WHERE `t2`.`id`=`t1`.`post`) AS `post`, " . 
					  "`t1`.`value` AS `value` FROM `post_ratings` AS `t1` WHERE `t1`.`user`=? ORDER BY `t1`.`id` DESC";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		$results   = array();
		
		// Verify the statement was created successfully.
		if($statement) {
			$statement->bind_param("i", @sprintf((int)$user));
			$statement->execute();
			$statement->bind_result($id, $post_id, $post, $value);
			
			// Use all the rows from the result set.
			while($statement->fetch()) {
				$results[count($results)] = array('id'      => $id, 
				                                  'post_id' => $post_id, 
												  'post'    => $post, 
												  'value'   => $value);
			}	// end while
			
			$statement->close();
		}	// end if
		
		return $results;
	}	// end function GetRatingsByUser
	
	/*
	 * ModifyPost
	 * Modifies a post with the supplied information.
	 *
	 * @param $id The id of the post to be modified.
	 * @param $title The title of the post.
	 * @param $content The content of the author.
	 * @return boolean The state of a modified post.
	 */
	public function ModifyPost($id, $title, $content) {
		$sql = "UPDATE `posts` SET `title`=?, `content`=? WHERE `id`=?";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		$return = false;
		
		// Verify that the statement was created successfully.
		if($statement) {
			$statement->bind_param("ssi", @sprintf((string)$title), 
			                              @sprintf((string)$content), 
										  @sprintf((int)$id));
			$statement->execute();
			
			if($statement->affected_rows > 0) {
				$return = true;
			}	// end if
			
			$statement->close();
		}	// end if
	}	// end function ModifyPost
	
	/*
	 * DeletePost
	 * Deletes a post from the blog entries table.
	 *
	 * @param $post The id of the post that will be deleted.
	 * @return boolean The deletion success state.
	 */
	public function DeletePost($post) {
		$sql = "DELETE FROM `posts` WHERE `id`=?";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		$return = false;
		
		// Verify that our statement was created successfully.
		if($statement) {
			$statement->bind_param("i", @sprintf((int)$post));
			$statement->execute();
			
			if($statement->affected_rows > 0) {
				$return = true;
			}	// end if
			
			$statement->close();
		}	// end if
		
		return $return;
	}	// end function DeletePost
	
	/*
	 * AddPost
	 * Adds a post into the blog entries table.
	 *
	 * @param $title The title of the blog entry.
	 * @param $content The content of the blog entry.
	 * @param $categories The categories this post will be associated with.
	 * @param $author The id of the author.
	 * @return integer Successful addition of this blog post returns id, else false.
	 */
	public function AddPost($title, $content, $author) {
		$sql = "INSERT INTO `posts` (`title`, `content`, `author`) VALUES (?, ?, ?)";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		
		// Verify the statement was created successfully.
		if($statement) {
			$statement->bind_param("ssi", @sprintf((string)$title), @sprintf((string)$content), @sprintf((int)$author));
			$statement->execute();
			
			// Verify that we added something to the table.
			if($statement->affected_rows > 0) {
				$statement->close();
				return parent::GetSqlManager()->LastId();
			}	// end if
		} // end if
		
		return false;
	}	// end function AddPost
	
	/*
	 * GetLastPost
	 * Acquires the last post that was made to the blog entries.
	 *
	 * @return array An array of posts with an id, title, content, author, date and categories.
	 */
	public function GetLastPost() {
		$sql = "SELECT `t1`.`id` AS `id`, " . 
			          "`t1`.`title` AS `title`, " . 
					  "`t1`.`content` AS `content`, " . 
					  "(SELECT `t2`.`fname` FROM `profiles` AS `t2` WHERE `t2`.`user`=`t1`.`author`) AS `author`, " . 
					  "`t1`.`author` AS `author_id`, " .
					  "`date` AS `date` FROM `posts` AS `t1` ORDER BY `t1`.`date` DESC LIMIT 1";
					  
		$statement = parent::GetSqlManager()->GetStatement($sql);
		$return = false;
		
		//  Verify that the statement was created successfully
		if($statement) {
			$statement->execute();
			$statement->bind_result($id, $title, $content, $author, $author_id, $date);
			
			// Make sure we have a result to return
			if($statement->fetch()) {
				$return = array(
					'id' => $id,
					'title' => $title,
					'content' => $content,
					'author' => $author,
					'author_id' => $author_id,
					'date' => $date);
			}	// end while
			
			$statement->close();
		}	// end if
		
		return $return;
	}	// end function GetLastPost
	
	/*
	 * GetPosts
	 * Acquires a list of posts based on the category or all posts if category is NULL.  Supports pagination.
	 *
	 * @param $category The category that will be used to filter the results.
	 * @param $limit The number of posts to retrieve.
	 * @param $offset The offset in the table to use for pagination.
	 * @return array An array of posts with an id, title, content, author and categories meeting the parameter criteria.
	 */
	public function GetPosts($limit=5, $offset=0) {
		$sql = "SELECT `t1`.`id` AS `id`, " .
			   "`t1`.`title` AS `title`, " . 
		       "`t1`.`content` AS `content`, " . 
			   "(SELECT `t2`.`fname` FROM `profiles` as `t2` WHERE `t2`.`user`=`t1`.`author`) AS `author`, " . 
			   "`t1`.`author` AS `author_id`, " . 
			   "`date` AS `date` FROM `posts` AS `t1` ORDER BY `t1`.`date` DESC LIMIT ?,?";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		$return = array();
		
		// Verify the statement was initalized successfully.
		if($statement) {
			$statement->bind_param("ii", @sprintf((int)$offset), @sprintf((int)$limit));
			$statement->execute();
			$statement->bind_result($id, $title, $content, $author, $author_id, $date);

			// Make sure we have some results to return
			while($statement->fetch()) {
				$return[count($return)] = array('id'         => $id, 
				                                'title'      => $title, 
												'content'    => $content, 
												'author'     => $author, 
												'author_id'  => $author_id, 
												'date'       => $date);
			}	// end while
			
			$statement->close();
		}	// end if
		
		return $return;
	}	// end function GetPosts
	
	/*
	 * GetAllPosts
	 * Acquires a list of posts based on the category or all posts if category is NULL.  Supports pagination.
	 *
	 * @return array An array of posts with an id, title, content, author and categories meeting the parameter criteria.
	 */
	public function GetAllPosts() {
		$sql = "SELECT `t1`.`id` AS `id`, " .
			   "`t1`.`title` AS `title`, " .  
			   "(SELECT `t2`.`fname` FROM `profiles` as `t2` WHERE `t2`.`user`=`t1`.`author`) AS `author`, " . 
			   "`t1`.`author` AS `author_id`, " . 
			   "`date` AS `date` FROM `posts` AS `t1` ORDER BY `t1`.`date` DESC";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		$return = array();
		
		// Verify the statement was initalized successfully.
		if($statement) {
			$statement->execute();
			$statement->bind_result($id, $title, $author, $author_id, $date);

			// Make sure we have some results to return
			while($statement->fetch()) {
				$return[count($return)] = array('id'         => $id, 
				                                'title'      => $title,  
												'author'     => $author, 
												'author_id'  => $author_id, 
												'date'       => $date);
			}	// end while
			
			$statement->close();
		}	// end if
		
		return $return;
	}	// end function GetPosts
	
	/*
	 * GetPostsInCategory
	 * Acquires all posts that are part of a specific category.
	 *
	 * @param $category The category in which the posts belong.
	 * @param $limit The amounts of results to return.
	 * @param $offset The row offset to start the row limitation.
	 * @return array A list of the posts in the category.
	 */
	public function GetPostsInCategory($category, $limit=5, $offset=0) {
		$sql = "SELECT `t1`.`id` AS `id`, " .
			   "`t1`.`title` AS `title`, " . 
		       "`t1`.`content` AS `content`, " . 
			   "(SELECT `t2`.`fname` FROM `profiles` as `t2` WHERE `t2`.`user`=`t1`.`author`) AS `author`, " . 
			   "`t1`.`author` AS `author_id`, " . 
			   "`date` AS `date` FROM `posts` AS `t1` " . 
			   "WHERE `t1`.`id` IN (SELECT `t3`.`post` FROM `post_category_rel` AS `t3` WHERE `t3`.`category`=?) " .
			   "ORDER BY `t1`.`date` DESC LIMIT ?,?";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		$results   = array();
		
		// Make sure our statement was created successfully
		if($statement) {
			$statement->bind_param("iii", @sprintf((int)$category), 
			                              @sprintf((int)$offset), 
										  @sprintf((int)$limit));
			$statement->execute();
			$statement->bind_result($id, $title, $content, $author, $author_id, $date);
			
			// Cycle through all the results.
			while($statement->fetch()) {
				$results[count($results)] = array('id'        => $id, 
				                                  'title'     => $title, 
												  'content'   => $content, 
												  'author'    => $author, 
												  'author_id' => $author_id, 
												  'date'      => $date);
			}	// end while
			
			$statement->close();
		}	// end if
		
		return $results;
	}	// end function GetPostsInCategory
	
	/*
	 * GetPostsByAuthor
	 * Acquires all posts that have been made by the specified author.
	 *
	 * @param $author_id The author of the posts to seek.
	 * @param $limit The number of posts to retrieve.
	 * @param $offset The row offset in the SQL table.
	 * @return array A list of the posts made by the author.
	 */
	public function GetPostsByAuthor($author_id, $limit=5, $offset=0) {
		$sql = "SELECT `t1`.`id` AS `id`, " .
			   "`t1`.`title` AS `title`, " . 
		       "`t1`.`content` AS `content`, " . 
			   "(SELECT `t2`.`fname` FROM `profiles` as `t2` WHERE `t2`.`id`=`user`.`author`) AS `author`, " . 
			   "`date` AS `date` FROM `posts` AS `t1` " . 
			   "WHERE `t1`.`author`=? ORDER BY `t1`.`date` LIMIT ?,?";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		$results   = array();
		
		// Verify that our statement was created successfully.
		if($statement) {
			$statement->bind_param("iii", @sprintf((int)$author_id), 
			                              @sprintf((int)$offset), 
										  @sprintf((int)$limit));
			$statement->execute();
			$statement->bind_result($id, $title, $content, $author, $date);
			
			// Acquire all the results.
			while($statement->fetch()) {
				$results[count($results)] = array('id'        => $id, 
				                                  'title'     => $title, 
												  'content'   => $content, 
												  'author'    => $author, 
												  'author_id' => (int)$author_id, 
												  'date'      => $date);
			}	// end while
			
			$statement->close();
		}	// end if
		
		return $results;
	}	// end function GetPostsByAuthor
	
	/*
	 * GetPostCount
	 * Acquires the total numbers of posts that are available.
	 *
	 * @param $category The category in which to count the available posts.
	 * @return integer The number of posts that are contained in the posts table.
	 */
	public function GetPostCount($category = NULL) {
		$sql = "SELECT COUNT(`t1`.`id`) AS `total` FROM `posts` AS `t1`";
		
		$statement = NULL;
		
		if(isset($category)) {
			$sql .= " INNER JOIN post_category_rel AS t2 ON t1.id=t2.post WHERE t2.category=?";
			$statement = parent::GetSqlManager()->GetStatement($sql);
			
			if($statement) {
				$statement->bind_param("i", @sprintf((int)$category));
			} else {
				return 0;
			}	// end if-else
		} else {
			$statement = parent::GetSqlManager()->GetStatement($sql);
		}	// end if-else
		
		$return = 0;
		
		// Verify the statement was created successfully.
		if($statement) {
			$statement->execute();
			$statement->bind_result($total);
			
			// Make sure we have something to return
			if($statement->fetch()) {
				$return = $total;
			}	// end if
			
			$statement->close();
		}	// end if
		
		return $return;
	}	// end function GetTotalPosts
	
	/**
	  * GetPost
	  * Acquires the specified post.
	  *
	  * @param $id The id of the post that will be acquired.
	  * @return array The id, title, content, author, author_id, and date of this post.
	  */
	public function GetPost($id) {
		$sql = "SELECT `t1`.`title` AS `title`, " . 
		       "`t1`.`content` AS `content`, " . 
			   "(SELECT `t2`.`fname` FROM `profiles` as `t2` WHERE `t2`.`user`=`t1`.`author`) AS `author`, " . 
			   "`t1`.`author` AS `author_id`, " . 
			   "`date` AS `date` FROM `posts` AS `t1` WHERE `t1`.`id`=?";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		$return    = array();
		
		if($statement) {
			$statement->bind_param("i", @sprintf((int)$id));
			$statement->execute();
			$statement->bind_result($title, $content, $author, $author_id, $date);
			
			if($statement->fetch()) {
				$return['id'] = @sprintf((int)$id);
				$return['title'] = $title;
				$return['content'] = $content;
				$return['author'] = $author;
				$return['author_id'] = $author_id;
				$return['date'] = $date;
			}	// end if
			
			$statement->close();
		}	// end if
		
		return $return;
	}	// end function GetPost
}	// end class BlogModel

?>