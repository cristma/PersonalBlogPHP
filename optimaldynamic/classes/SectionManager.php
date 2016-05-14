<?php

/******************************************************************************
 * SectionManager.php
 * Created on May 15, 2013 by Matthew A. Crist.
 *
 * This class contains the functions for creating, deleting, modifiying and
 * acquiring sections in the SQL tables.
 *
 * CHANGE LOG:
 * ----------------------------------------------------------------------------
 *
 *****************************************************************************/

include_once(dirname(dirname(__FILE__)) . "/classes/SqlManager.php");
include_once(dirname(dirname(__FILE__)) . "/classes/SqlManaged.php");

class SectionManager extends SqlManaged
{
	/**
	  * Constructor for this class.
	  *
	  * @param $l_sqlManager A reference to an existing SqlManager, otherwise
	  *                      a null reference or default creates a new SqlManager
	  *                      instance.
	  */
	public function __construct($l_sqlManager = null)
	{
		parent::SetSqlManager($l_sqlManager);
	}	// end constructor
	
	/**
	  * Creates a new section.
	  *
	  * @param $l_name The name of this section.
	  * @param $l_image The image that represents this section.
	  * @param $l_description A description of this section.
	  *
	  * @return boolean True on successful creation, otherwise false.
	  */
	public function CreateSection($l_name, $l_image, $l_description)
	{
		// Create a prepared statement for this query
		$l_sql = 'INSERT INTO `section` (`name`, `image`, `description`) VALUES (?, ?, ?)';
		$l_statement = parent::GetSqlManager()->GetStatement($l_sql);
		
		// Verify that our statement was created successfully
		if($l_statement)
		{
			// Bind the parameters the sql statement and execute.
			$l_statement->bind_params("sss", @sprintf($l_name), 
											 @sprintf($l_image), 
											 @sprintf($l_description));
			$l_result = $l_statement->execute();
			$l_statement->close();
			
			// Error should be handled by the caller.
			return $l_result;
		}	// end if
		
		// Error should be handled in the SqlManager
		return false;
	}	// end function CreateSection
	
	/**
	  * Edits a section based on its id.
	  *
	  * @param $l_id The id that references this section tuple in the SQL database.
	  * @param $l_name The new name of the edited section (may contain the same 
	  *                previous name).
	  * @param $l_image The location of the image that will be used to represent this
	  *                 section (may contain the same previous image).
	  * @param $l_description The description of this section (may be the same 
	  *                       description as the previous description).
	  *
	  * @return True is the affect rows > 0, otherwise False.
	  */
	public function EditSection($l_id, $l_name, $l_image, $l_description)
	{
		// Create a prepared statement for this query
		$l_sql = 'UPDATE `section` SET `name`=?, `image`=?, `description`=? WHERE id=?';
		$l_statement = parent::GetSqlManager()->GetStatement($l_sql);
		
		// Verify that our statement was created successfully
		if($l_statement)
		{
			// Bind the parmeters to the SQL statement and execute it.
			$l_statement->bind_params("sssi", @sprintf($l_name), 
										      @sprintf($l_image), 
											  @sprintf($l_description), 
											  @sprintf((int)$l_id));
			$l_statement->execute($l_sql);
			$l_affected = $l_statement->affected_rows;
			$l_statement->close();
			
			if($l_affected > 0)
				return true;
			else
				return false;
		}	// end if
		
		// Error should have been handled in the SqlManager
		return false;
	}	// end function EditSection
	
	/**
	  * Deletes a section based on the ID association.
	  *
	  * @param $l_id The id that references this section tuple in the SQL
	  *              database.
	  *
	  * @return boolean True if the affected rows > 0, otherwise False.
	  */
	public function DeleteSection($l_id)
	{
		// Create a prepared statement for execution
		$l_sql = 'DELETE FROM `section` WHERE `id`=?';
		$l_statement = parent::GetSqlManager()->GetStatement($l_sql);
		
		// Verify that our statement was created successfully
		if($l_statement)
		{
			// Bind the parameters in the statement and execute
			$l_statement->bind_params("i", @sprintf((int)$l_id));
			$l_statement->execute();
			$l_affected = $l_statement->affected_rows;
			
			// Make sure our deletion actually did something
			if($l_affected > 0)
				return true;
			else
				return false;
		}	// end if
		
		// Error should have been handled in the SqlManager
		return false;
	}	// end function DeleteSection
	
	/**
	  * Acquires all available sections in long format.
	  *
	  * @return array An array of the sections where each tuple is indexed by 
	  *               an integer and their properties are associative.
	  *               $l_array[i]['id'], $l_array['name'] ...
	  */
	public function GetSections()
	{
		// Construct the SQL and execute the query (no user input for this).
		$l_sql = "SELECT `id`, `name`, `image`, `description` FROM `section`";
		
		return parent::GetSqlManager()->Execute($l_sql);
	}	// end function GetSections
	
	/**
	  * Acquires a short notation for all sections that are present.  The ideal 
	  * use is for lists or combo boxes.
	  *
	  * @return array An array such that each tuple is indexed by an integer and 
	  *               each value in that tuple is associative.
	  *               $l_array[i]['id'], $l_array[i]['name] ...
	  */
	public function GetSectionsShortNotation()
	{
		// Construct the SQL and execut the query (not user input for this).
		$l_sql = "SELECT `id`, `name` FROM `section`";
		
		return parent::GetSqlManager()->Execute($l_sql);
	}	// end function GetSectionsShortNotation
	
	/**
	  * Acquires a specific section by its SQL id.
	  *
	  * @param $l_id The id that references this tuple in the SQL database.
	  *
	  * @return array An associative array containing the section that was referenced
	  *               by the $l_id.
	  */
	public function GetSection($l_id)
	{
		// Construct the statement
		$l_section = 'SELECT `id`, `name`, `image`, `description` FROM `section` WHERE `id`=?';
		$l_statement = parent::GetSqlManager()->GetStatement($l_sql);
		
		// Verify the statement was produced correctly
		if($l_statement)
		{
			// Bind the parameters to the SQL statement and execute the query
			$l_statement->bind_params("i", @sprintf((int)$l_id));
			$l_statement->execute();
			$l_results = $l_statement->get_results();
			$l_statement->close();
			
			return $l_results->fetch_assoc();
		}	// end if
		
		// Error should be handled in the SqlManager
		return false;
	}	// end function GetSection
}	// end class SectionManager

?>