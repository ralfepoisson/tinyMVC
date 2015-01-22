<?php
/**
 * Invalid Argument Exception
 * 
 * @author Ralfe Poisson <ralfepoisson@gmail.com>
 */

class InvalidArgumentException extends Exception {
    
    public $predicate;
    
    public function __construct($predicate, $message="", $code=0, Exception $previous=null) {
        $this->predicate = $predicate;
        parent::__construct($message, $code, $previous);
    }
    
}

