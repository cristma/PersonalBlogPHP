<?php

/******************************************************************************
 * CategoryManager.php
 * Created by Matthew A. Crist on May 15, 2013.
 *
 * This class manages the creation, editing, deletion and aquisition of the 
 * categories that may be associated with articles.
 *
 * CHANGE LOG:
 * ----------------------------------------------------------------------------
 *
 *****************************************************************************/

include_once(dirname(dirname(__FILE__)) . "/classes/SqlManaged.php");
include_once(dirname(dirname(__FILE__)) . "/classes/SqlManager.php");

class CategoryManager extends SqlManaged
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
	  * Creates a new category based on the name and description provided.
	  *
	  * @param $l_name The name of the category.
	  * @param $l_description The description for this category.
	  *
	  * @return boolean Success state of the SQL query.
	  */
	public function CreateCategory($l_name, $l_description)
	{
		// Prepare a statement for execution.
		$l_sql = "INSERT INTO `category` (`name`, `description`) VALUES (?, ?)";
		$l_statement = parent::GetSqlManager()->GetStatement($l_sql);
		
		// Verify our statement was created successfully.
		if($l_statement)
		{
			$l_statement->bind_param("ss", @sprintf($l_name), @sprintf($l_description));
			
			// Needs to be verbous to user.  Do not handle here.
			$l_result = $l_statement->execute();
			$l_statement->close();
			
			return $l_result;
		}	// end if
		
		// Error is handled in the SqlManager
		return false;
	}	// end function CreateCategory
	
	/**
	  * Deletes a category based on the ID provided.  It is important to note that 
	  * deletion of a category will result in a cascade deletion of articles.  Be 
	  * sure that if you are deleting a category and you wish to keep your articles, 
	  * that you are referencing them to a new category before the deletion occurs.
	  * 
	  * @param $l_id The id (int 17) value that indicates the tuple key for the 
	  *              category that is to be deleted.
	  *
	  * @return boolean The success of this statement depends on the number of rows
	  *                 affected by the SQL call.  If affected > 0 then we reutrn 
	  *                 true, else we return false.
	  */
	public function DeleteCategory($l_id)
	{
		// Prepare a statement for execution.
		$l_sql = "DELETE FROM `category` WHERE `id`=?";
		$l_statement = parent::GetSqlManager()->GetStatement($l_sql);
		
		// Verify our statement was created successfully.
		if($l_statement)
		{
			$l_statement->bind_param("i", (int)@sprintf($l_id));
			
			// Needs to be verbous to user.  Do not handle here.
			$l_statement->execute();
			$l_affected = $l_statement->rows_affected;
			$l_statement->close();
			
			if($l_affected > 0)
				return true;
			else
				return false;
		}	// end if
		
		// Error is handled in the SqlManager
		return false;
	}	// end function DeleteCategory
	
	/**
	  * Modifies a category.
	  *
	  * @param $l_id The id of the category to be modified.
	  * @param $l_name The new name of the category.
	  * @param $l_description The new description of the category.
	  *
	  * @return boolean True is the update affects one or more rows, 
	  *                 False, otherwise.
	  */
	public function ModifyCategory($l_id, $l_name, $l_description)
	{
		// Prepare a statement for execution.
		$l_sql = "UPDATE `category` SET `name`=?, `description`=? WHERE `id`=?";
		$l_statement = parent::GetSqlManager()->GetStatement($l_sql);
		
		// Verify our statement was created successfully.
		if($l_statement)
		{
			$l_statement->bind_param("ssi", @sprintf($l_name), 
			                                @sprintf($l_description), 
											@sprintf((int)$l_id));
			
			// Needs to be verbous to user.  Do not handle here.
			$l_statement->execute();
			$l_affected = $l_statement->rows_affected;
			$l_statement->close();
			
			if($l_affected > 0)
				return true;
			else
				return false;
		}	// end if
		
		// Error is handled in the SqlManager
		return false;
	}	// end function ModifyCategory
	
	/**
	  * Acquires all the categories available.  This return type is an associated
	  * array that will be in the form $results[n]['id'], $results[n]['name'] ...
	  *
	  * @return array An associative array of all the values with an indexed array
	  *               pointing to multiple elements in the tuple.
	  */
	public function GetCategories()
	{
		$l_sql = "SELECT `id`, `name`, `description` FROM `category`";
		return parent::GetSqlManager()->Execute($l_sql);
	}	// end function GetCategories
	
	/**
	  * Acquires a specific category based on the ID.
	  *
	  * @param $l_id The ID that references the SQL tuple for this category.
	  *
	  * @return array An associative array of the fields from the SQL tuple.
	  */
	public function GetCategory($l_id)
	{
		// Prepare a statement for execution.
		$l_sql = "SELECT `name`, `description` FROM `category` WHERE `id`=?";
		$l_statement = parent::GetSqlManager()->GetStatement($l_sql);
		
		// Verify we have acquired a statement
		if($l_statement)
		{
			$l_statement->bind_param("i", sprintf($l_id));
			
			// Needs to be verbous to the user.  Do not handle here.
			$l_statement->execute();
			$l_result = $l_statement->get_result();
			$l_statement->close();
			
			return $l_result->fetch_assoc();
		}	// end if
		
		// The error should be handled in SqlManager
		return false;
	}	// end function GetCategory
}	// end class CategoryManager

?>