<?php
/**
 * tinyMVC: MVC class
 * 
 * The main class which oversees the MVC framework.
 * 
 * @author Ralfe Poisson <ralfepoisson@gmail.com>
 * @version 0.1
 * @license GPL3
 */

class MVC {
	
	public $App;
	
	public $Routes;
	
	public $AppConfig;
	
	public $General;

    /**
     * Constructor
     */
    public function __construct() {
		// Global Variables
		global $configuration;
		
		// Log Activity
		MVC::log(" [*] MVC Initialising", 4);
        MVC::log(" - Initializing attributes", 4);
		
		// Initialize Attributes
		$this->General = new GeneralFunctions();

        // Handle OPTIONS Requests
        $this->General->HandleOptionsRequest();

		// Load App Configuration
		$this->AppConfig = $configuration;

		// Load Framework
        MVC::log(" - Loading Framework", 4);
		$this->load_framework();
	}

    /**
     * MVC Factory
     * @return MVC
     */
    public static function Factory() {
		// Global Variables
		global $MVC;
		
		// Check if the Singleton already exists
		if (is_object($MVC)) {
			// Return the singleton
			return $MVC;
		}
		
		// If not, create the Singleton
		$MVC = new MVC();
		
		// Return the singleton
		return $MVC;
    }

    /**
     * Alias for the MVC::start() function with the headerless parameter
     * set to true.
     */
    public static function headless_start() {
		// Headerless Start
        MVC::start(true);
	}
	
	public static function start($headerless=false) {
		// Log Activity
		MVC::log(" [*] MVC Starting...");
		
		// Store latest page request
		$_SESSION['accessing_page'] = (isset($_SERVER['REQUEST_URI']))? $_SERVER['REQUEST_URI'] : "./";
		
		// Get Singleton
		$mvc = MVC::Factory();
		
		// Create Application Object
		MVC::log(" - Creating Application", 8);
		$mvc->App = Application::Factory($mvc->AppConfig);

		if (!$headerless) {
			// Get Current User
			$mvc->App->user = new User(get_user_uid());
		
			// Construct Page
			MVC::log(" - Constructing Page", 8);
			$mvc->App->draw_page();
		}
	}
	
	public function load_framework() {
		// Load Classes
		$this->General->auto_load(TINYMVC_LOCATION . "classes/", ".php");
		
		// Load Libraries
		$this->General->auto_load(TINYMVC_LOCATION . "libraries/", ".php");

        // Load Plugins
        $this->load_plugins();
	}

    public function load_plugins() {
        // Get plugins
        $plugin_dir = dirname(TINYMVC_LOCATION) . "/plugins/";
        $folders = GeneralFunctions::get_dir_filtered_listing($plugin_dir, "");

        // Attempt to Load Plugins
        foreach ($folders as $folder) {
            $plugin_script = $plugin_dir . $folder . "/plugin.php";
            if (file_exists($plugin_script)) {
                require_once($plugin_script);
            }
        }
    }
	
	public static function log($message, $log_level=3) {
		// Only log configured log level or lower
		if ($log_level > TINYMVC_LOG_LEVEL) {
			return;
		}
		
		// Compile Message
		$message = date("Y-m-d H:i:s") . " " . GeneralFunctions::get_client_ip() . " " . "[{$log_level}]" . " " . $message . "\n";
		
		// Append to Log
		GeneralFunctions::file_append(TINYMVC_LOG, $message);
	}
	
	public static function get_app_dir() {
		return TINYMVC_APP_DIR;
	}

    /**
     * Return the Singleton Database Engine
     * @return db_engine
     */
    public static function DB() {
		$db = DB::Factory();
		return $db;
	}
	
	public static function config($var) {
		$mvc = MVC::Factory();
		return $mvc->App->conf($var);
	}
	
	public static function General() {
		$mvc = MVC::Factory();
		return $mvc->General;
	}
	
	public static function Redirect($controller, $action, $params) {
		// Compile URL
		$url = "?p=" . $controller . "&action=" . $action . $params;
		
		// Redirect
		print "<script>window.location.href = '{$url}';</script>"; 
		die();
	}

    public static function RequestHeaders() {
        return getallheaders();
    }

}

