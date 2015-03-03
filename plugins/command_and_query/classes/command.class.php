<?php
/**
 * Command Interface
 * 
 * @author Ralfe Poisson <ralfepoisson@gmail.com>
 */

interface ICommand {
    
    public function Execute();
    
    public function Validate();    
    
}

abstract class Command implements ICommand {
    
    public $Result;
    public $DB;
    public $commandExecutor;
    public $queryExecutor;
    
    public function __construct() {
        $this->DB = MVC::DB();
        $this->commandExecutor = new CommandExecutor();
        $this->queryExecutor = new QueryExecutor();
    }
    
    public function Execute() {
        return;
    }
    
    public function Validate() {
        return;
    }
    
}

