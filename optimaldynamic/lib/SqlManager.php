<?php



/******************************************************************************

 * SqlManager.php

 * Created by Matthew A. Crist on May 3, 2013.

 *

 * This file contains all the appropriate actions to connecting to a SQL 

 * server and maintaining an active connection until it is no longer 

 * necessary to do so.

 *

 * CHANGE LOG:

 * ---------------------------------------------------------------------------

 * 2013-05-03_0639 - Initial conception of this file.

 *

 *****************************************************************************/

 

 

include_once($root . "lib/Logger.php");



class SqlManager

{	
	// Connectivity parameters
	private $l_location;
	private $l_username;
	private $l_password;
	private $l_database;

	// Handlers
	private $l_connection;

	/**
	  * Default constructor for this class.
	  */
	public function __construct($l_username=null, 
	                            $l_password=null, 
					 		    $l_database=null)
	{
		$this->l_location = "localhost";
		$this->l_username = @sprintf($l_username);
		$this->l_password = @sprintf($l_password);
		$this->l_database = @sprintf($l_database);
	}	// end constructor
	
	/**
	  * Destructor for this class.
	  */
	public function __destruct()
	{
		// Ensure we have closed our connection to the server.
		$this->CloseConnection();
	}	// end destructor
	
	/**
	  * Opens a connection to the SQL server on localhost with the
	  * given database.
	  */
	private function OpenConnection()
	{
		$this->l_connection = new mysqli($this->l_location, 
										 $this->l_username, 
										 $this->l_password, 
										 $this->l_database);
		
		// Verify that our connection was correct
		if($this->l_connection->connect_errno)
		{
			Logger::LogError($GLOBALS['root'] . "logs/mysql_error.txt", Logger::$APPEND, 
				"(" . date("U") . ") Failed to connect to MySQL: (" . 
				$this->l_connection->connect_errno . 
				") " . $this->l_connection->connect_error) . '\n\r';		
			
			$this->l_connection = false;
			
			return false;
		}	// end if

		return true;
	}	// end function OpenConnection
	
	/**
	  * Closes an active connection to the SQL server.
	  */
	public function CloseConnection()
	{
		if($this->l_connection)
		{
			$this->l_connection->close();
		}	// end if

		return true;
	}	// end function CloseConnection

	/**
	  * Acquires a statement object from the active connection to execute prepared
	  * statements.
	  */
	public function GetStatement($l_sql, $l_attempts=0)
	{
		if($this->OpenConnection())
		{	
			// Verify our statement was created successfully
			if($l_statement = $this->l_connection->prepare(sprintf($l_sql)))
			{
				return $l_statement;
			}	// end if
			
			// There was an error in creating the statement
			Logger::LogError($GLOBALS['root'] . "logs/mysql_error.txt", Logger::$APPEND, 
				"(" . date("U") . ") Failed to create prepared statement: (" . 
				$this->l_connection->errno . 
				") " . $this->l_connection->error) . '\n\r';
			
			return false;
		}	// end if

		// Attempt a connection
		if($l_attempts < 3)
		{
			return $this->GetStatement($l_sql, $l_attempts++);
		}	// end if
		else
		{
			// There is not an active connection to return a statement
			// This should already be logged in the connection attempt
			return false;
		}	// end else
	}	// end function GetStatement

	/**
	  * Executes a select statement (more specifically) on the server in an 
	  * optimal way.  Prepared statements are suboptimal for these types of
	  * queries that are discretely defined.
	  */
	public function Execute($l_sql, $l_returntype='array', $l_attempts=0)
	{
		// Attempt to establish a new connection		
		if($this->OpenConnection())
		{
			$l_result = $this->l_connection->query(sprintf($l_sql));
			
			// See if we were successful
			if($this->l_connection->errno)
			{
				Logger::LogError($GLOBALS['root'] . "logs/mysql_error.txt", 
					Logger::$APPEND, 
					"(" . date("U") . ") Failed to execute statement: (" . $this->l_connection->errno . ") " . 						
						$this->l_connection->error) . '\r\n';

				return false;
			}	// end if

			// Determine the return type
			switch($l_returntype)
			{
				case 'boolean':
				case 'integer':
					return $l_result;
				default:
					$l_results = array();

					while($l_row = $l_result->fetch_assoc())
					{
						$l_results[count($l_results)] = $l_row;
					}	// end while

					$l_result->free();

					return $l_results;
			}	// end switch
		}	// end if
		else
		{
			// Attempt to establish a new connection
			if($l_attempts < 3)
			{
				return $this->Execute($l_sql, $l_returntype, $l_attempts++);
			}	// end if
			else
			{
				// Could not establish a connection.  The error should be logged
				// from OpenConnection function.
				return false;
			}	// end else
		}	// end else
	}	// end function Execute
	
	/*
	 * LastId
	 * Returns the last ID of the value that was inserted into the tables.
	 *
	 * @return integer The integer value of the key of the last insert statement.
	 */
	public function LastId() {
		if(isset($this->l_connection)) {
			return $this->l_connection->insert_id;
		} else {
			return false;
		}	// end if-else
	}	// end function LastId
}	// end class SqlManager

?>