<?php
/**
 * Project : Form Class
 * 
 * @author Ralfe Poisson <ralfe@implyit.co.za>
 * @version 1.0
 * @copyright Copyright (C) Imply Development CC 2012
 * @package Project
 * 
 */

# ==========================================================================================
# CLASS
# ==========================================================================================

class Form {
	
	# --------------------------------------------------------------------------------------
	# ATTRIBUTES
	# --------------------------------------------------------------------------------------
	
	var $action;
	var $fields;
	var $method;
	var $id;
	
	# --------------------------------------------------------------------------------------
	# METHODS
	# --------------------------------------------------------------------------------------
	
	/**
	 * Constructor
	 * 
	 * Initialise default values for Model attributes 
	 */
	function __construct($action, $method="POST", $id="item_form") {
		# Initialise Variables
		$this->id														= $id;
		$this->action													= $action;
		$this->fields													= array();
		$this->method													= $method;
	}
	
	/**
	 * Add Field
	 * 
	 * Add a Field to the Form
	 * 
	 * @param $label String
	 * @param $type String
	 * @param $name String
	 * @param $value String
	 */
	function add($label, $type, $name, $value="") {
		$this->fields[]													= array(	"label"		=> $label,
																					"type"		=> $type,
																					"name"		=> $name,
																					"value"		=> $value,
																					"options"	=> ""
																				);
	}
	
	function add_select($label, $name, $default, $values) {
		$this->fields[]													= array(	"label"		=> $label,
																					"type"		=> "select",
																					"name"		=> $name,
																					"value"		=> $default,
																					"options"	=> $values
																				);
	}
	
	/**
	 * Generate Input
	 * 
	 */
	function generate_input($type, $name, $value="", $options="") {
		if ($type														== "text") {
			return "<input class='textfield' type='text' name='{$name}' value=\"{$value}\" style='width:300px;' />";
		}
		else if ($type													== "textarea") {
			return "<textarea name='{$name}' rows='5' cols='60'>{$value}</textarea>";
		}
		else if ($type													== "hidden") {
			return "<input type='hidden' name='{$name}' value=\"{$value}\" style='width:300px;' />";
		}
		else if ($type													== "submit") {
			return "<input class='btn btn-info' type='submit' value=\"{$value}\" />";
		}
		else if ($type													== "password") {
			return "<input type='password' name=\"$name\" value=\"{$value}\" style='width:300px;' />";
		}
		else if ($type													== "checkbox") {
			return "<input type='checkbox' name='{$name}' " . is_checked($value) . " />";
		}
		else if ($type													== "date") {
			return "<input type='text' class='date' name='{$name}' value=\"{$value}\" style='width:300px;' />";
		}
		else if ($type													== "select") {
			return generate_select($name, $options, $value);
		}
		else if ($type													== "form") {
			return $this->file_link($value) . "<input type='form' name='{$name}' id='{$name}' />";
		}
		else if ($type													== "other") {
			return $name;
		}
	}
	
	private function file_link($value) {
		# Global Variables
		global $_GLOBALS;
		
		# If a value is provided, generate a link
		if ($value) {
			$url														= $_GLOBALS['upload_url'] . $value;
			return "<a href=\"{$value}\" target='_blank'>{$value}</a> <br>\n";
		}
		else {
			return "";
		}
	}
	
	/**
	 * Generate
	 * 
	 * Generate HTML of the form.
	 */
	function generate() {
		# Generate Fields
		$fields															= "";
		foreach ($this->fields as $field) {
			if ($field['type']											== "hidden") {
				$fields													.= "
					" . $this->generate_input($field['type'], $field['name'], $field['value'], $field['options']) . "
				";
			}
			else {
				$fields													.= "
				<div class=\"form-group\">
					<label for=\"{$field['name']}\">{$field['label']}</label>
					" . $this->generate_input($field['type'], $field['name'], $field['value'], $field['options']) . "
				</div>
				";
			}
		}
		
		# Generate HTML
		$html															= "
		<form role=\"form\" id=\"{$this->id}\" method=\"{$this->method}\" action=\"{$this->action}\" enctype=\"multipart/form-data\">
			{$fields}
		</form>
		";
		
		# Return HTML
		return $html;
	}
	
	public function get_str($name) {
		return (isset($_REQUEST[$name]))? htmlentities($_REQUEST[$name]) : "";
	}
	
	public function get_int($name) {
		return (isset($_REQUEST[$name]))? intval($_REQUEST[$name]) : "";
	}
	
	public function get_file($name, $id) {
		# Global Variables
		global $_GLOBALS;
		
		# Local Variables
		$file															= $_FILES[$name];
		
		# Log Activity
		logg("Form: Get File '{#name}'");
		
		# Check if file is uploaded
		if (isset($file)) {
			# Get Variables for Upload
			$ext														= substr($file['name'], strrpos($file['name'], '.'));
			$filename													= $name . "_" . $id . $ext;
			$dir														= $_GLOBALS['upload_dir'];
			$src														= $file['tmp_name'];
			$destination												= $dir . $filename;
			
			# Move the File to the correct place
			logg("Form: - Copying the file from '{$src}' to '{$destination}'.");
			if (copy($src, $destination)) {
				logg("Form: - > File copied successfully.");
				
				# Return Filename
				return $filename;
			}
			else {
				logg("Form: - > ERROR: Could not copy the file from '{$src}' to '{$destination}'.");
			}
		}
		else {
			# No File was uploaded
			logg("Form: - Nothing was uploaded.");
			return false;
		}
	}
	
}

# ==========================================================================================
# THE END
# ==========================================================================================

