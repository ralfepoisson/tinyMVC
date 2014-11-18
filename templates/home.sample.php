<?php

class Controller extends AbstractController {
	
	/**
	 * The default function called when the script loads
	 */
	function display(){
		# Global Variables
		global $_db, $_GLOBALS;
		
		# Create View
		$view_model = array();
		$view = new View("home/default.html", $view_model);
		
		# Display View
		print $view->show();
	}
	
}

