<?php
/**
 * Project
 * 
 * @author Ralfe Poisson <ralfepoisson@gmail.com>
 * @version 2.0
 * @package Project
 */

# ==========================================================================================
# AbstractPage CLASS
# ==========================================================================================

abstract class AbstractController {
	
	# --------------------------------------------------------------------------------------
	# ATTRIBUTES
	# --------------------------------------------------------------------------------------
	
	public $cur_page;
	public $action;
	
	# --------------------------------------------------------------------------------------
	# METHODS
	# --------------------------------------------------------------------------------------
	
	public function __construct() {
		# Global Variables
		global $_GLOBALS;
		
		# Set Public Variables
		$this->cur_page													= (isset($_GET['p']))? 		"?p={$_GET['p']}"		: "?p={$_GLOBALS['default_page']}";
		$this->action													= (isset($_GET['action']))?	"?p={$_GET['action']}"	: "?p={$_GLOBALS['default_action']}";
	}
	
}

