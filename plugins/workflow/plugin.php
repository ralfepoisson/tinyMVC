<?php
/**
 * Workflow Engine for tinyMVC
 *
 * The Workflow Engine implements a state machine which runs
 * asynchronously, and should be implemented using a daemon.
 *
 * @author Ralfe Poisson <ralfepoisson@gmail.com>
 */

// Include Required Scripts
GeneralFunctions::auto_load(dirname(__FILE__) . "/classes/", ".php");
