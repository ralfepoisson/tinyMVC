<?php
/**
 * Preconditions
 * 
 * @author Ralfe Poisson <ralfepoisson@gmail.com>
 */

public class Preconditions {
    
    public static function CheckNotBlank($obj, $name) {
        if (strlen($obj) < 1) {
            throw new InvalidArugmentException($obj, "Invalid Argument: \${$name} cannot be blank.");
        }
    }
    
    public static function CheckNotNull($obj, $name) {
        if ($obj == null) {
            throw new InvalidArgumentException($obj, "Invalid Argument: \${$name} cannot be null.");
        }
    }
    
    public static function CheckIsNull($obj, $name) {
        if ($obj != null) {
            throw new InvalidArgumentException($obj, "Invalid Argument: \${$name} should be null.");
        }
    }
    
    public static function CheckKeyIsSet($obj, $name) {
        if ($obj < 1) {
            throw new InvalidArgumentException($obj, "Invalid Argument: \${$name} is an invalid key.");
        }
    }
    
    public static function CheckMinLength($obj, $min, $name) {
        if (strlen($obj) < $min) {
            throw new InvalidArgumentException($obj, "Invalid Argument: \${$name} is less than the minimum length, {$min}.");
        }
    }
    
}

