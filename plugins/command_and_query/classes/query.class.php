<?php
/**
 * Query Interface
 * 
 * @author Ralfe Poisson <ralfepoisson@gmail.com>
 */

interface IQuery {
    
    public function Execute();
    
    public function Validate();    
    
}

abstract class Query implements IQuery {
    
    public $Result;
    public $DB;
    public $queryExecutor;
    
    public function __construct() {
        $this->DB = MVC::DB();
        $this->queryExecutor = new QueryExecutor();
    }
    
    public function Execute() {
        return;
    }
    
    public function Validate() {
        return;
    }
    
}

