<?php

/******************************************************************************
 * Logger.php
 * Created by Matthew A. Crist on May 3, 2013.
 *
 * This file contains all the appropriate actions for logging errors and other
 * issues that occur on the server.
 *
 * CHANGE LOG:
 * ---------------------------------------------------------------------------
 * 2013-05-03_0705 - Initial conception of this file.
 *
 *****************************************************************************/

class Logger
{
	public static $APPEND = "a";

	/*
	 * Logs the error to the log file based on the writing type (globals).
	 *
	 * $l_logfile - the file that the error in which the error will be written.
	 * $l_writetype - the type of write to perform.
	 * $l_error - the error that will be written to the log.
	 */
	public static function LogError($l_logfile, $l_writetype, $l_error)
	{
		if($f_handle = fopen($l_logfile, $l_writetype))
		{
			fwrite($f_handle, $l_error);
			fclose($f_handle);
		}	// end if
	}	// end function LogError
}	// end class

?>