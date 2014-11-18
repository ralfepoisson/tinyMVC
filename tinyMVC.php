<?php
/**
 * tinyMVC
 * 
 * This script is used to give access to the tinyMVC framework to the various applications
 * which make use of it.
 * 
 * @author Ralfe Poisson <ralfepoisson@gmail.com>
 * @version 0.1
 * @license GPL3
 */

// Determine the absolute path of the tinyMVC framework.
define("TINYMVC_LOCATION", dirname(__FILE__) . "/framework/");

// Global Variables
global $MVC;

// Include the required scripts for the tinyMVC framework
require_once(TINYMVC_LOCATION . "config.php");
require_once(TINYMVC_LOCATION . "classes/mvc.class.php");
require_once(TINYMVC_LOCATION . "libraries/general.class.php");

// Create MVC Singleton
$MVC = MVC::Factory();

