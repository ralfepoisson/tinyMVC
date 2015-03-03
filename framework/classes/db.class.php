<?php

// global variable
global $DB;

class DB {

    /**
     * Create a Database Engine instance
     * @return db_engine
     */
    public static function create() {
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

    /**
     * Generate the Singleton Database Engine based on a database technology.
     * @param string $engine
     * @return db_engine
     */
    public static function Factory($engine="mysql") {
		// Global Variables
		global $DB;
		
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

