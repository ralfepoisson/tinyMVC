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

