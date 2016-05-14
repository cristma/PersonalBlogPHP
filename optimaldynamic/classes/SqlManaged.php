<?php

/******************************************************************************
  * SqlManaged.php
  * Created by Matthew A. Crist on May 4, 2013.
  *
  * This class contains the basic functions that will allow a class to
  * maintain a single SqlManager object and have it passed between classes
  * in order to minimize the footprint left by invoking the SQL server for
  * storage purpose.
  *
  * CHANGE LOG:
  * --------------------------------------------------------------------------
  * 2013-05-04_0109 - Initial conception of this class.
  *
  ****************************************************************************/

include_once(dirname(dirname(__FILE__)) . "/Configuration.php");
include_once(dirname(dirname(__FILE__)) . "/classes/SqlManager.php");

class SqlManaged
{
	// Reference to the SqlManager for SQL calls.
	private $l_sqlManager;

	/**
	  * Sets the SqlManager that will be associated with this 
	  * class.
	  */
	public function SetSqlManager($l_sqlManager)
	{
		if($l_sqlManager)
		{
			$this->l_sqlManager = $l_sqlManager;
		}	// end if
		else
		{
			$this->l_sqlManager = new SqlManager($GLOBALS['sql_username'], 
			                                     $GLOBALS['sql_password'], 
												 $GLOBALS['sql_database']);
		}	// end else
	}	// end function
	
	/**
	  * Acquires the reference to the SqlManager that is currently
	  * associated with this class.
	  */
	public function GetSqlManager()
	{
		return $this->l_sqlManager;
	}	// end function GetSqlManager
}	// end class SqlManaged

?>