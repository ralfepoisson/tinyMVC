<?php
/**
 * Query Interface
 * 
 * @author Ralfe Poisson <ralfepoisson@gmail.com>
 */

public interface IQuery {
    
    public function Execute();
    
    public function Validate();    
    
}

abstract class Query implements IQuery {
    
    public $Result;
    public $DB;
    
    public function __construct() {
        $this->DB = MVC::DB();
    }
    
    public function Execute() {
        return;
    }
    
    public function Validate() {
        return;
    }
    
}

