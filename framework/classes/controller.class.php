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
	public $queryExecutor;
	public $commandExecutor;
	
	# --------------------------------------------------------------------------------------
	# METHODS
	# --------------------------------------------------------------------------------------
	
	public function __construct() {
		// Global Variables
		global $configuration;
		
		// Set Public Variables
		$this->cur_page = (isset($_GET['p']))? "?p={$_GET['p']}" : "?p=" . $configuration->default_page;
		$this->action = (isset($_GET['action']))? "?p={$_GET['action']}" : "?p=" . $configuration->default_action;
		$this->commandExecutor = new CommandExecutor();
		$this->queryExecutor = new QueryExecutor();
	}
	
	public function redirect($function, $controller="") {
		$controller = ($controller)? $controller : $this->cur_page;
		redirect($controller . "&action=" . $function);
	}
	
}

