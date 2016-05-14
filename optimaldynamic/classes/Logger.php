<?php
class Logger
{
	public static $APPEND = "a";

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