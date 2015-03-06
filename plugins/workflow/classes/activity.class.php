<?php
/**
 * TinyMVC Plugin: Activity Class
 *
 * @author Ralfe Poisson <ralfepoisson@gmailcom>
 */
abstract class Activity {

    public $Name;
    public $Configuration;

    /**
     * Constructor
     * @param string $name
     */
    public function __construct($name="") {
        $this->Name = $name;
        $this->Configuration = array();
    }

    /**
     * Configure State Machine Transitions
     * @param {string} $trigger_name
     * @param {string} $activity_name
     */
    public function When($trigger_name, $activity_name) {
        // Add Configuration
        $this->Configuration[$trigger_name] = $activity_name;
    }

    /**
     * Run State Action
     * @param object $context
     * @return Trigger
     */
    public abstract function Run($context);

}
