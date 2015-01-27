<?php
/**
 * API Server
 * 
 * @author Ralfe Poisson <ralfepoisson@gmail.com>
 */

class APIServer {
    
    public static function APIReturn($obj) {
        $json = json_encode($obj);
        print $json;
    }
    
}

