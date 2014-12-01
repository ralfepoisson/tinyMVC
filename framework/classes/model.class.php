<?php
/**
 * Project
 * 
 * @author Ralfe Poisson <ralfepoisson@gmail.com>
 * @version 1.0
 * @package Project
 */

// ==========================================================================================
// CLASS
// ==========================================================================================

class Model {
	
	// --------------------------------------------------------------------------------------
	// ATTRIBUTES
	// --------------------------------------------------------------------------------------
	
	var $table;
	var $fields;
	var $uid;
	var $uid_field;
	var $default_uid_field = "id";
	
	// --------------------------------------------------------------------------------------
	// METHODS
	// --------------------------------------------------------------------------------------
	
	/**
	 * Constructor
	 * 
	 * Initialise default values for Model attributes 
	 */
	function __construct($uid=0) {
		// Initialise Variables
		$this->table = get_called_class();
		$this->fields = array();
		$this->uid = $uid;
		$this->uid_field = $this->default_uid_field;
		
		// Load
		if ($this->uid) {
			$this->load();
		}
	}
	
	/**
	 * Exists
	 * 
	 * Given the UID, this function checks to see if the object exists in the database
	 */
	function exists() {
		// Global Variables
		global $_db;
		
		// Confirm UID Field
		$this->uid_field = ($this->uid_field)? $this->uid_field : $this->default_uid_field;
		$id_field = $this->uid_field;
		
		// Check if Object exists in Database
		if ($this->$id_field && $this->table) {
			$query = "	SELECT
							COUNT(*)
						FROM
							`{$this->table}`
						WHERE
							`{$this->uid_field}` = '{$this->$id_field}'
						";
			$exists = MVC::DB()->fetch_single($query);
			
			// Return True if exists
			if ($exists) {
				return true;
			}
		}
		
		// Return False
		return false;
	}
	
	/**
	 * Load
	 * 
	 * Loads the object from a record in a database table
	 */
	function load() {
		// Ensure that the ID has been set
        $id_field = (isset($this->uid_field))? $this->uid_field : $this->default_uid_field;
        if ($this->$id_field < 1) {
            return;
        }

		// Confirm UID Field
		$this->uid_field = ($this->uid_field)? $this->uid_field : $this->default_uid_field;

		// Load Attributes
		$this->get_attributes();
		
		// Check if Object exists in Database
		if ($this->exists()) {
			// Get Data
			$query = "	SELECT
							*
						FROM
							`{$this->table}`
						WHERE
							`{$this->uid_field}` = '{$this->$id_field}'
						";
			$data = MVC::DB()->fetch_one($query);
			
			// Load Data
			$data_bits = get_object_vars($data);
			foreach ($data_bits as $key => $value) {
				$this->$key = $value;
			}
		}
	}
	
	function get_attributes() {
		// Global Variables
		global $_db;
		
		// Confirm UID Field
		$this->uid_field = ($this->uid_field)? $this->uid_field : $this->default_uid_field;
		
		// Get Attributes from Database Table
		if ($this->table) {
			// Get Data
			$query = "DESCRIBE `{$this->table}`";
			$data = MVC::DB()->fetch($query);
			
			// Dynamically Create Object Attributes
			$this->fields = array();
			foreach ($data as $item) {
				$key = $item->Field;
				$this->$key = (isset($this->$key))? $this->$key : "";
				$this->fields[] = $key;
			}
		}
	}
	
	/**
	 * Save
	 * 
	 * Inserts/Updates object in the database
	 */
	function save() {
		// Global Variables
		global $_db;
		
		// Confirm UID Field
		$this->uid_field = ($this->uid_field)? $this->uid_field : $this->default_uid_field;
		
		// Get Attributes
		$this->get_attributes();
		
		// Construct Associative array to pass to database object
		$data = array();
		foreach ($this->fields as $field) {
			if (!($field == $this->uid_field)) {
				$data[$field] = $this->$field;
			}
		}
		
		// If object exists in database, update it
		if ($this->exists()) {
			$id_field = $this->uid_field;
			MVC::DB()->update(
				$this->table,
				$data,
				array(
					$this->uid_field => $this->$id_field
				)
			);
		}
		
		// If the object does not exist, and the table is set, insert it
		else if ($this->table) {
			// Insert Record
			$this->uid = MVC::DB()->insert(
				$this->table,
				$data
			);
		}
	}
	
	/**
	 * Disables the row in the table by setting the active field to 0
	 */
	public function delete() {
		// Global Variables
		global $_db;
		
		// Confirm UID Field
		$this->uid_field = ($this->uid_field)? $this->uid_field : $this->default_uid_field;
		
		// Disable
		MVC::DB()->disable($this->table, $this->uid);
	}
	
	public function deactivate() {
		$this->deactivation_date = date("Y-m-d H:i:s");
		$this->save();
	}
	
	public function get($filters="", $order="", $order_direction="ASC") {
		// Global Variables
		global $_db;
		
		// Get Data
		$query = $this->prepare_filtered_query($filters, $order, $order_direction);
		$data = MVC::DB()->fetch($query);
		
		// Generate array of objects
		$objects = array();
		$class = get_class($this);
		foreach ($data as $item) {
			$obj_class = new ReflectionClass($class);
			$id_field = $this->uid_field;
			$args = array($item->$id_field);
			$obj = $obj_class->newInstanceArgs($args);
			$objects[] = $obj;
		}
		
		// Return Objects
		return $objects;
	}
	
	public function prepare_filtered_query($filters=null, $order=null, $order_direction="ASC", $fields=null) {
		// Confirm UID Field
		$this->uid_field = ($this->uid_field)? $this->uid_field : $this->default_uid_field;
		$order = ($order)? $order : $this->uid_field;
		
		// Generate Where Conditions from Filters
		$conditions = $this->filter_clauses($filters);
		
		// Determine Fields to Select
		if ($fields == null) {
			$fields = "`{$this->uid_field}`";
		}
		else {
			$field_list = "";
			foreach ($fields as $key => $value) {
				$key = (strstr($key, "("))? $key : "`{$key}`";
				$field_list .= (strlen($field_list))? ", " : "";
				$field_list .= "{$key} as '{$value}'";
			}
			$fields = $field_list;
		}
		
		// Generate Query
		$query = "	SELECT
						{$fields}
					FROM
						`{$this->table}`
					WHERE
						{$conditions}
					ORDER BY
						`{$order}` {$order_direction}
					";
		
		// Return Query
		return $query;
	}
	
	private function filter_clauses($filters) {
		// Local Variables
		$where = "";
		
		// Confirm UID Field
		$this->uid_field = ($this->uid_field)? $this->uid_field : $this->default_uid_field;
		
		// Generate Where Clause
		if (strlen($filters)) {
			$data = explode(",", trim($filters));
			foreach ($data as $item) {
				$item = trim($item);
				$components = array();
				$components[0] = substr($item, 0, strpos($item, " "));
				$pos1 = strpos($item, " ") + 1;
				$pos2 = strpos($item, " ", $pos1) + 1;
				$components[1] = substr($item, $pos1, $pos2 - $pos1);
				$components[2] = substr($item, $pos2);
				$field = "`{$components[0]}`";
				$operator = $components[1];
				$value = "{$components[2]}";
				$value = ($value == "NULL")? $value : "\"{$value}\"";
				$where .= (strlen($where))? " AND" : "";
				$where .= " {$field} {$operator} {$value} ";
			}
		}
		
		// Return Where Clause
		return $where;
	}
	
	public function upload($name) {
		// Upload the File
		$filename = Form::get_file($name, $this->uid);
		
		// Update Model
		if (!($filename == false)) {
			$this->$name = $filename;
		}
	}
	
	public static function Listing($headings, $filters=null, $order=null, $order_direction=null) {
		// Log Activity
		MVC::log(" - Generating Model Listing.", 6);
		
		// Create new Model
		$obj_class = new ReflectionClass(get_called_class());
		$obj = $obj_class->newInstanceArgs(array());
		
		// Generate Query
		$query = $obj->prepare_filtered_query($filters, $order, $order_direction, $headings);
		MVC::log(" > Query: " . $query);
		
		// Create Listing
		$listing = paginated_listing($query);
		
		// Return Listing
		return $listing;
	}
	
	public function populate_from_form() {
		// Get Form Data
		$data = $_REQUEST;
		
		// Populate fields
		$this->get_attributes();
		foreach($this->fields as $key) {
			$this->$key = (isset($_REQUEST[$key]))? $_REQUEST[$key] : $this->$key;
		}
	}
}

// ==========================================================================================
// THE END
// ==========================================================================================
