<?php
/**
 * Preconditions
 * 
 * @author Ralfe Poisson <ralfepoisson@gmail.com>
 */

class Preconditions {
    
    public static function CheckNotBlank($obj, $name) {
        if (strlen($obj) < 1) {
            $msg = "Invalid Argument: \${$name} cannot be blank.";
            Preconditions::ThrowError($obj, $msg);
        }
    }
    
    public static function CheckNotNull($obj, $name) {
        if ($obj == null) {
            $msg = "Invalid Argument: \${$name} cannot be null.";
            Preconditions::ThrowError($obj, $msg);
        }
    }
    
    public static function CheckIsNull($obj, $name) {
        if ($obj != null) {
            $msg = "Invalid Argument: \${$name} should be null.";
            Preconditions::ThrowError($obj, $msg);
        }
    }
    
    public static function CheckKeyIsSet($obj, $name) {
        if ($obj < 1) {
            $msg = "Invalid Argument: \${$name} is an invalid key.";
            Preconditions::ThrowError($obj, $msg);
        }
    }
    
    public static function CheckMinLength($obj, $min, $name) {
        if (strlen($obj) < $min) {
            $msg = "Invalid Argument: \${$name} is less than the minimum length, {$min}.";
            Preconditions::ThrowError($obj, $msg);
        }
    }

    public static function ThrowError($obj, $msg) {
        MVC::log("Precondition Failed: " . $msg);
        MVC::log(" - Value: \"" . print_r($obj, true) . "\"");
        throw new PreconditionFailureException($obj, $msg);
    }
    
}

