<?php
/**
 * Command Executor
 * 
 * @author Ralfe Poisson <ralfepoisson@gmail.com>
 */

class CommandExecutor {
    
    public static function Execute($command) {
        // Validate Command
        $command->Validate();
        
        // Execute Command
        $command->Execute();
    }
    
}

