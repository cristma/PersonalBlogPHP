<?php

/*****************************************************************************************************************
 * UserModel
 * Created on July 11, 2013 by Matthew A. Crist.
 *
 * This class will manage the access to the information contained within the users table and manipulate it 
 * accordingly.
 *
 * CHANGE LOG:
 * ---------------------------------------------------------------------------------------------------------------
 * 20130711_0658 - Initial conception of this file.
 * 20130818_0222 - Updated the access privs to be specific to the function as opposed to a numerical weighted 
 *                 system.
 *
 *****************************************************************************************************************/

include_once($root.'lib/SqlManaged.php');

class UserModel extends SqlManaged {
	public function __construct($sqlManager) {
		parent::SetSqlManager($sqlManager);
	}	// end constructor
	
	/*
	 * HasAccess
	 * Verifies that a user has access to spcific information.
	 *
	 * @param $user The user ID that will be checked for access.
	 * @param $access The access level that is to be checked.
	 * @return boolean True if access exists, false if not.
	 */
	public function HasAccess($user, $access) {
		$sql = "SELECT `t1`.`id` FROM `user_access_rel` AS `t1` INNER JOIN `access` AS `t2` ON `t2`.`id`=`t1`.`access` WHERE `t2`.`name`=? AND `t1`.`user`=?";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		
		// Verify that we created a statement successfully.
		if($statement) {
			$statement->bind_param("si", @sprintf((string)$access), @sprintf((int)$user));
			$statement->execute();
			$statement->bind_result($id);

			if($statement->fetch()) {
				$statement->close();
				return true;
			}	// end if
			
			$statement->close();
		}	// end if
		
		return false;
	}	// end function
	
	/*
	 * GetAccessRights
	 * Acquires all the access rights for this user.
	 *
	 * @param $user The user in which we will be acquiring the rights.
	 * @return array An array of all the rights the user has.
	 */
	public function GetAccessRights($user) {
		$sql = "SELECT `t2`.`name` AS `name` FROM `user_access_rel` AS `t1` INNER JOIN `access` AS `t2` ON `t1`.`access`=`t2`.`id` WHERE `t1`.`user`=?";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		$return = array();
		
		if($statement) {
			$statement->bind_param("i", @sprintf((int)$user));
			$statement->execute();
			$statement->bind_result($name);
			
			while($statement->fetch()) {
				$return[stripslashes($name)] = true;
			}	// end while
			
			$statement->close();
		}	// end if
		
		return $return;
	}	// end function GetAccessRights
	
	/*
	 * LoginUser
	 * Logs the user inot the system and returns the ID that the user will use to track the user location and
	 * session management.  The session information is written to the table (the user's remote address).
	 *
	 * @param $username The name that the user uses to log in and verify account information.
	 * @param $password The MD5 digested password the user uses to verify account information.
	 * @return integer The id of the user in the MySQL table.
	 */
	public function LoginUser($username, $password) {
		$sql = "SELECT `id`, `rem_addr` FROM `users` WHERE `username`=? AND `password`=? AND `active`=1";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		
		if($statement) {
			$statement->bind_param("ss", @sprintf((string)$username), @sprintf((string)$password));
			$statement->execute();
			$statement->bind_result($id, $remote_addr);
			
			// Make sure something was found
			if($statement->fetch()) {
				// We need to update the remote address this user accessed.
				if($remote_addr != $_SERVER['REMOTE_ADDR']) {
					$this->WriteLocation($id, $_SERVER['REMOTE_ADDR']);
				}	// end if
				
				$statement->close();
				
				return $id;
			}	// end if
		}	// end if

		return false;
	}	// end function VerifyUser
	
	/*
	 * WriteLocation
	 * Writes the last location that the user had logged in from in order to verify the user has not suddenly
	 * jumped locations.  This will prevent "listen in" attempts to intercept user information.
	 *
	 * @param $id The id of the user that will have the address written.
	 * @param $location The location of the user that is to be updated.
	 * @return boolean The status of writing the user's location.
	 */
	public function WriteLocation($id, $location) {
		$sql = "UPDATE `users` SET `rem_addr`=? WHERE `id`=?";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		
		// Verify the statement was created successfully
		if($statement) {
			$statement->bind_param("si", @sprintf((string)$location), @sprintf((int)$id));
			$statement->execute();
			
			if($statement->affected_rows > 0) {
				$statement->close();
				return true;
			}	// end if
			
			$statement->close();
		}	// end if
		
		return false;
	}	// end function WriteLocation
	
	/*
	 * GetUserInfo
	 * Acquires the basic user information that will allow the customization of information in the
	 * website towards the user.
	 *
	 * @param $id The id of the user.
	 * @param $location The location that the validation will allow for user access.
	 * @return array An associative array containing the name, email and access levels of the user.
	 */
	public function GetUserInfo($id, $location) {
		$sql = "SELECT `t1`.`id` AS `id`, " . 
		              "`t1`.`fname` AS `fname`, " . 
					  "`t1`.`lname` AS `lname`, " . 
					  "`t1`.`email` AS `email` FROM `profiles` AS `t1` " . 
					  "INNER JOIN `users` AS `t2` ON `t2`.`id`=`t1`.`user` " . 
					  "WHERE `t1`.`user`=? AND `t2`.`rem_addr`=?";
		$statement = parent::GetSqlManager()->GetStatement($sql);
		
		if($statement) {
			$statement->bind_param("is", @sprintf((int)$id), @sprintf((string)$location));
			$statement->execute();
			$statement->bind_result($id, $fname, $lname, $email);
			
			// Verify the statement was created successfully
			if($statement->fetch()) {
				$user = array('id'    => $id, 
				              'fname' => $fname, 
							  'lname' => $lname, 
							  'email' => $email);
				$statement->close();
				return $user;
			}	// end if
			
			$statement->close();
		}	// end if
		
		return false;
	}	// end function GetUserInfo
}	// end class UserModel

?>