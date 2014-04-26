<?php
/**
 * Application Index script
 * 
 * This is the default script which is to be called by the web server, and which is used
 * to build the web responses.
 * 
 * @author Ralfe Poisson <ralfepoisson@gmail.com>
 * @version 0.1
 * @license GPL3
 */

// Set the Application Directory
define("TINYMVC_APP_DIR", dirname(__FILE__));

// Include the configuration for the application
require_once("config.php");

// Include the tinyMVC framework
require_once("tinymvc/tinyMVC.php");

// Start the MVC
MVC::start();

