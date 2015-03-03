<?php
/**
 * API Server Plugin for tinyMVC
 *
 * This plugin is used to host an API project. Simply replace the standard
 * MVC::start(); command in the project's index file with the following:
 * APIServer::start();
 *
 * @author Ralfe Poisson <ralfepoisson@gmail.com>
 */

// Include Required Scripts
GeneralFunctions::auto_load(dirname(__FILE__) . "/classes/", ".php");
