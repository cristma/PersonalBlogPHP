<?php

/**
  * UserManager.php
  * Created on May 11, 2013 by Matthew A. Crist.
  *
  * This class handles all the exchanges with the SQL server that will be
  * responsible for storing and manipulating user information.
  *
  * CHANGE LOG:
  * ---------------------------------------------------------------------------
  *
  */

include_once(dirname(dirname(__FILE__)) . "/classes/SqlManager.php");
include_once(dirname(dirname(__FILE__)) . "/classes/SqlManaged.php");

class UserManager extends SqlManaged
{
	/**
	  * Constructor for this class.
	  *
	  * @param $l_sqlManager The reference to any active SqlManager.  If null 
	  *                      is passed, a new SqlManager will be created for 
	  *                      this class.
	  */
	public function __construct($l_sqlManager = null)
	{
		parent::SetSqlManager($l_sqlManager);
	}	// end constructor
	
	/**
	  * Creates a new user.
	  */
	public function CreateUser($l_username, $l_password, $l_name, $l_email)
	{
		// Create a SQL statement in preparation for the creation of the user
		$l_sql = 'INSERT INTO `user` (`username`, `password`, `name`, `email`) ' .
		         'VALUES (?, ?, ?, ?)';
		$l_statement = parent::GetSqlManager()->GetStatement($l_sql);
		
		// Check to make sure our statement was created succesfully
		if($l_statement)
		{
			// Bind the parameters to the prepared statement
			$l_statement->bind_param("ssss", @sprintf($l_username), 
			                                 md5(@sprintf($l_password)), 
											 @sprintf($l_name), 
											 @sprintf($l_email));
			$l_result = $l_statement->execute();
			
			// Should we have an error, we need to pass it to the caller.
			return $l_result;
		}	// end if
		
		// Error should be handled in the SqlManager
		return false;
	}	// end function CreateUser
	
	/**
	  * Modifies user information.
	  */
	public function EditUser($l_id, $l_password, $l_name, $l_email)
	{
		// Create a SQL statement in preparation for the editingof the user.
		$l_sql = 'UPDATE `user` SET `password`=?, `name`=?, `email`=? WHERE `id`=?';
		$l_statement = parent::GetSqlManager()->GetStatement($l_sql);
		
		// Check to make sure our statement was created successfully.
		if($l_statement)
		{
			// Bind the parameters in the prepared statement and execute
			$l_statement->bind_param("sssi", md5(@sprintf($l_password)), 
									         @sprintf($l_name), 
											 @sprintf($l_email), 
											 (int)@sprintf($l_id));
			$l_result = $l_statement->execute();
			
			// Error should be verbous in the caller - handle it there
			return $l_result;
		}	// end if
		
		// Error should have been handled in the SqlManager
		return false;
	}	// end function EditUser
	
	/**
	  * Deletes a user.
	  */
	public function DeleteUser($l_id)
	{
		// Create a prepared statement for execution
		$l_sql = 'DELETE FROM `user` WHERE `id`=?';
		$l_statement = parent::GetSqlManager()->GetStatement($l_sql);
		
		// Verify that our statement was created
		if($l_statement)
		{
			// Bind the parameters of the prepared statement and execute
			$l_statement->bind_param("i", (int)@sprintf($l_id));
			$l_result = $l_statement->execute();
			
			// Error should be verbous in the caller
			return $l_result;
		}	// end if
		
		// Error should be handled in the SqlManager
		return false;
	}	// end function DeleteUser
	
	/**
	  * Verifies a user's login information.
	  */
	public function VerifyUser($l_username, $l_password)
	{
		// Prepare the statement for execution
		$l_sql = 'SELECT `name`, `email` FROM `user` WHERE `username`=? AND `password`=?';
		$l_statement = parent::GetSqlManager()->GetStatement($l_sql);
		
		// Verify that our statement was created successfully.
		if($l_statement)
		{
			// Bind the parameters to the prepared statement and execute the statement
			$l_statement->bind_param("ss", @sprintf($l_username), 
										   @sprintf(md5($l_password)));
			$l_statement->execute();
			$l_result = $l_statement->get_result();
			$l_statement->close();
			
			// Error should be handled by the caller
			return $l_result->fetch_assoc();
		}	// end if
		
		// This error is handled in the SqlManager
		return false;
	}	// end function VerifyUser
	
	/** I am unsure, at this point, if I should acquire users, thus these acquire
	    functions have been omitted. */
}	// end class UserManager

?>