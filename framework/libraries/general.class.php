<?php
/**
 * tinyMVC: General class
 * 
 * This contains general pieces of functionality made use of by the tinyMVC framework.
 * 
 * @author Ralfe Poisson <ralfepoisson@gmail.com>
 * @version 0.1
 * @license GPL3
 */

class GeneralFunctions {
	
	public static function get_client_ip() {
		if (isset($_SERVER)) {
			if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
				return $_SERVER["HTTP_X_FORWARDED_FOR"];

			if (isset($_SERVER["HTTP_CLIENT_IP"]))
				return $_SERVER["HTTP_CLIENT_IP"];

			return $_SERVER["REMOTE_ADDR"];
		}

		if (getenv('HTTP_X_FORWARDED_FOR'))
			return getenv('HTTP_X_FORWARDED_FOR');

		if (getenv('HTTP_CLIENT_IP'))
			return getenv('HTTP_CLIENT_IP');

		return getenv('REMOTE_ADDR');
	}
	
	public static function file_append($file, $line) {
		// Open file for appending
		$f = fopen($file, "a");
		
		// Write line to file
		fputs($f, $line);
		
		// Close file
		fclose($f);
	}
	
	public static function get_dir_listing($dir) {
		// Open directory for reading
		$d = opendir($dir);
		
		// Cycle through directory and compile listing array
		$listing = array();
		while ($entry = readdir($d)) {
			$listing[] = $entry;
		}
		
		// Return listing array
		return $listing;
	}
	
	public static function get_dir_filtered_listing($dir, $pattern) {
		// Get Directory listing
		$entries = GeneralFunctions::get_dir_listing($dir);
		
		// Generate filtered listing
		$listing = array();
		foreach($entries as $entry) {
			if (strstr($entry, $pattern)) {
				$listing[] = $entry;
			}
		}
		
		// Return filtered listing
		return $listing;
	}
	
	public static function auto_load($dir, $pattern) {
		// Get Listing
		$files = GeneralFunctions::get_dir_filtered_listing($dir, $pattern);
		
		// Include files
		foreach ($files as $file) {
			MVC::log("  > Including {$file}", 8);
			require_once($dir . $file);
		}
	}
	
	public static function format_plaintext($str) {
		// Replace New Lines
		$str = str_replace("\n", "<br>\n", $str);
		
		// Replace Tabs
		$str = str_replace("\t", "&nbsp;&nbsp;&nbsp;&nbsp;", $str);
		
		// Replace Spaces
		$str = str_replace(" ", "&nbsp;", $str);
		
		// Return the formatted string
		return $str;
	}
	
	public static function format_mysql_date($date) {
		$time = strtotime($date);
		return date("Y-m-d", $time);
	}
	
	public static function generate_select($name, $values, $active="", $use_key=1, $custom_tags="") {
		# Construct HTML
		$html = "<select name=\"{$name}\" id=\"{$name}\" {$custom_tags}>\n";
		$html .= "	<option value='0'>Select One</option>\n";
		foreach ($values as $key => $value) {
			$key = ($use_key)? $key : $value;
			$checked = ($key == $active)? " SELECTED" : "";
			$html .= "	<option value='$key'{$checked}>$value</option>\n";
		}
		$html .= "</select>\n";
	
		# Return HTML
		return $html;
	}
		
	public static function generate_select_values($table, $id_field, $name_field, $where="", $order_by="") {
		# Global Variables
		global $_db;
	
		# Get Data
		$where_clauses = (strlen(trim($where)))? filter_clauses($where) : "";
		$order_by = (strlen($order_by))? "ORDER BY {$order_by} " : "";
		$query = "	SELECT
						`{$id_field}` as 'id',
						`{$name_field}` as 'name'
					FROM
						`{$table}`
					WHERE
						`active` = 1
						{$where_clauses}
					{$order_by}
					";
		$data = MVC::DB()->fetch($query);
	
		# Generate Values
		$values = array();
		foreach ($data as $item) {
			$values[$item->id] = $item->name;
		}
	
		# Return Values
		return $values;
	}
	
	public static function select_box($name, $table, $id_field, $name_field, $default=0, $where="", $order_by="") {
		// Create Values
		$values = GeneralFunctions::generate_select_values($table, $id_field, $name_field, $where, $order_by);
		
		// Create Select Box
		$html = GeneralFunctions::generate_select($name, $values, $default);
		MVC::log(print_r($html, 1));
		
		// Return Select Box
		return $html;
	}
	
}

