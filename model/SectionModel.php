<?php

include_once($root . "lib/SqlManaged.php");

class SectionModel extends SqlManaged
{
	/*
	 * Constructor for this class.
	 * $l_sqlManager - reference to an active SqlManager.  If none is present, 
	 *                 a new one will be created in SqlManaged.
	 */
	public function __construct($l_sqlManager = null)
	{
		parent::SetSqlManager($l_sqlManager);
	}	// end function __construct
	
	/*
	 * Acquires sections based on the security level specified.  The security
	 * level is based on the weight applied in the access table.
	 */
	public function GetSections($security = 0, $active = 1)
	{
		$sql = "select id, name from sections where active=? and access>=?";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		
		if($statement)
		{
			$statement->bind_param("ii", @sprintf((int)$active), @sprintf((int)$security));
			$statement->execute();
			$results = $statement->get_result();
			
			$return = array();
			
			while($row = $results->fetch_assoc())
			{
				$return[count($return)] = $row;
			}	// end while
			
			$statement->free_result();
			$statement->close();
			
			return $return;
		}	// end if
		else
		{
			return false;
		}	// end else
	}	// end function GetSections
	
	/*
	 * Aquires a single section and the information contained within.
	 */
	public function GetSection($id)
	{
		$sql = "select name, description, active, access from sections where id=?";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		
		if($statement)
		{
			$statement->bind_param("i", @sprintf((int)$id));
			$statement->execute();
			
			$result = $statement->get_result();
			
			if($return = $result->fetch_assoc())
			{
				$statement->free_result();
				$statement->close();
				
				return $return;
			}	// end if
			else
			{
				// No results
				return false;
			}	// end else
			// There should be ONLY one result for this action.
		}	// end if
		else
		{
			return false;
		}	// end else
	}	// end function GetSection
}	// end class SectionModel

?>