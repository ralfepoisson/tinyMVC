<?php
/**
 * Query Executor
 * 
 * @author Ralfe Poisson <ralfepoisson@gmail.com>
 */

class QueryExecutor {
    
    public static function Execute($command) {
        // Validate Command
        $command->Validate();
        
        // Execute Command
        $command->Execute();
    }
    
}

