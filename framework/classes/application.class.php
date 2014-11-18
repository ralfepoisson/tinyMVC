<?php
/**
 * Project
 * 
 * @author Ralfe Poisson <ralfepoisson@gmail.com>
 * @version 2.0
 * @package Project
 */

# ==========================================================================================
# Application CLASS
# ==========================================================================================

class Application {
	
	# --------------------------------------------------------------------------------------
	# ATTRIBUTES
	# --------------------------------------------------------------------------------------
	
	public $config;
	public $page;
	public $template;
	public $user;
	public $p;
	
	# --------------------------------------------------------------------------------------
	# METHODS
	# --------------------------------------------------------------------------------------
	
	/**
	 * Constructor
	 * 
	 * Initialise default values for Model attributes 
	 */
	function __construct($config) {
		# Global Variables
		global $app, $_GLOBALS;
		
		# Ensure Singleton
		if (is_object($app)) {
			return $app;
		}
		
		# Include Models
		$this->include_models();
		$this->include_helpers();
		
		# Check Installation
		//check_installation();
		
		# Initialise Variables
		$this->config												= $config;
		$this->template												= new Template(TINYMVC_APP_DIR . "/views/" . $this->config->template);
		
		# Sanitize all User Input
		$this->sanitize();
	}
	
	public function include_models() {
		$dir														= MVC::get_app_dir() . "/models/";
		$d															= opendir($dir);
		while ($entry												= readdir($d)) {
			if (strstr($entry, ".class.php")) {
				include_once($dir . $entry);
			}
		}
	}
	
	public function include_helpers() {
		$dir														= MVC::get_app_dir() . "/helpers/";
		$d															= opendir($dir);
		while ($entry												= readdir($d)) {
			if (strstr($entry, ".inc.php")) {
				include_once($dir . $entry);
			}
		}
	}
	
	public function Factory($config) {
		# Global Variables
		global $app;
		
		# Ensure Singleton
		if (is_object($app)) {
			return $app;
		}
		else {
			return new Application($config);
		}
	}
	
	public function conf($var) {
		if (isset($this->config->$var)) {
			return $this->config->$var;
		}
		else {
			return false;
		}
	}
	
	public function draw_page() {
		// Log Activity
		MVC::log(" [*] Constructing Page", 8);
		
		# Authenticate User
		if($this->conf("requires_login")) {
			$this->authenticate();
		}
		
		# Get Page
		$this->page														= $this->get_page();
		
		# Get Action
		$action															= $this->get_action();
		
		# Log Activity
		MVC::log(" - Calling Controller Action: {$action}().", 8);
		
		# Draw the Top section of the template
		$this->template->draw_top();
		
		# Run the page's action
		$this->page->$action();
		
		# Draw the Bottom section of the template
		$this->template->draw_bottom();
	}
	
	public function get_page() {
		# Get the 'p' GET variable and sanitize it
		$p																= (isset($_GET['p']))? preg_replace('@[^a-zA-Z0-9_]@', '', $_GET['p']) : $this->config->default_page;
		$this->p														= $p;
		
		# Determine the file to include from the content directory
		$dir															= MVC::get_app_dir() ."/controllers/";
		$p																= $dir . $p . ".php";
		$p																= (file_exists($p))? $p : $dir . "error.php";
		
		# Include the content file and create a page object
		MVC::log(" - Loading Controller: " . $p, 8);
		include_once($p);
		$page															= new Controller();
		
		# Return Page
		return $page;
	}
	
	public function get_action() {
		# Get the 'p' GET variable and sanitize it
		$action															= (isset($_GET['action']))? preg_replace('@[^a-zA-Z0-9_]@', '', $_GET['action']) : $this->config->default_action;
		
		# Return the Action
		return $action;
	}
	
	public function sanitize() {
		foreach ($_POST as $key => $value) {
			$_POST[$key]												= htmlentities($value);
		}
		foreach ($_GET as $key => $value) {
			$_POST[$key]												= htmlentities($value);
		}
		foreach ($_REQUEST as $key => $value) {
			$_POST[$key]												= htmlentities($value);
		}
	}
	
	public function authenticate() {
		if (!$this->user->uid) {
			redirect("login.php");
			die();
		}
	}
	
	public function api_request() {
		# Create API Object
		$api															= new API();
		
		# Get GET Data
		$action															= Form::get_str("action");
		$params															= $_GET;
		
		# Log Activity
		MVC::log("API: Action: " . $action, 6);
		MVC::log(print_r($params, true), 10);
		
		# Process API Request
		$api->process_request($action, $params);
	}
	
}

# ==========================================================================================
# THE END
# ==========================================================================================
