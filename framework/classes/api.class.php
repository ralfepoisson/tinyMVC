<?php
/**
 * API Server
 * 
 * @author Ralfe Poisson <ralfepoisson@gmail.com>
 */

class APIServer {
    
    public static function Return($obj) {
        $json = json_encode($obj);
        print $json;
    }
    
}

