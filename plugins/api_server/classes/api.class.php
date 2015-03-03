<?php
/**
 * API Server
 * 
 * @author Ralfe Poisson <ralfepoisson@gmail.com>
 */

class APIServer {

    /**
     * Output the API response in JSON format
     * @param {object} $obj
     */
    public static function APIReturn($obj) {
        $json = json_encode($obj);
        print $json;
    }

    /**
     * Start the API Server and process API Request.
     */
    public static function start() {
        // Start the MVC Server in Headerless mode
        MVC::start(true);

        $json = json_decode(file_get_contents('php://input'));

        // Determine the Controller and Action
        $app = MVC::Factory()->App;
        $controller = $app->get_page();
        $action = $app->get_action();

        // Run the Action
        $controller->$action($json);
    }
    
}

