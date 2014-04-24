<?php

// global variable
global $DB;

class DB {
	
	public static function create() {
		// Log Activity
		MVC::log(" - Constructing new database engine.", 8);
		
		// Database Engine
		$db = new db_engine(	MVC::config('db_host'),
								MVC::config('db_user'),
								MVC::config('db_pass'),
								MVC::config('db_db'),
								MVC::config('debug')
							);
		
		// Establish Connection
		$db->db_connect();
		
		// Return Database Object
		return $db;
	}
	
	public static function Factory($engine="mysql") {
		// Global Variables
		global $DB;
		
		// Log Activity
		MVC::log(" [*] DB Factory: {$engine}", 8);
		
		// Check for singleton
		if (is_object($DB)) {
			return $DB;
		}
		
		// If not, create object
		$DB = DB::create();
		
		// Return Database Object
		return $DB;
	}
	
}

