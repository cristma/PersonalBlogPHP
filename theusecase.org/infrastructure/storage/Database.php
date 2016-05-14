<?php

interface Database {
	public function OpenConnection($username, $password, $location, $database);
	public function CloseConnection();
	public function Write($object);
	public function Read($object);
}	// end interface Database

?>