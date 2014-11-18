<?php
/**
 * tinyMVC: View class
 * 
 * Manages the View created by the Controller
 * 
 * @author Ralfe Poisson <ralfepoisson@gmail.com>
 * @version 0.1
 * @license GPL3
 */

class View {
	
	public $file;
	
	public $vars;
	
	public $html;
	
	public function __construct($file, $vars) {
		// Initialise Attributes
		$this->file = $file;
		$this->vars = $vars;
	}
	
	public function render() {
		// Log Activity
		MVC::log(" - Rendering View from file '{$this->file}'.", 8);
		
		// Generate HTML
		$template = new Template(TINYMVC_APP_DIR . "/views/" . $this->file, $this->vars);
		$this->html = $template->toString();
	}
	
	public function show() {
		// Log Activity
		MVC::log(" [*] Showing View", 8);
		
		// Render
		$this->render();
		
		// Display HTML
		MVC::log(" - Outputting HTML to screen.");
		print $this->html;
	}
	
}

