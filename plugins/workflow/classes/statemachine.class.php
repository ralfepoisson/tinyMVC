<?php
/**
 * TinyMVC Plugin: StateMachine
 *
 * @author Ralfe Poisson <ralfepoisson@gmail.com>
 */
abstract class StateMachine {

    public $Id;
    public $Name;
    public $Activities;
    public $Triggers;
    public $State;
    public $Context;

    /**
     * Constructor
     */
    public function __construct($id=0) {
        $this->Id = $id;
        $this->Activities = array();
        $this->Triggers = array();
        $this->Context = (object)array();
    }

    /**
     * Configure statemachine and return starting state
     * @return string
     */
    public abstract function Configure();

    /**
     * Add Activity State to the State Machine
     * @param Activity $activity
     * @param string $label
     */
    public function AddActivity($activity, $label) {
        $this->Activities[$label] = $activity;
    }

    /**
     * Add Trigger to the State Machine
     * @param string $trigger
     */
    public function AddTrigger($trigger) {
        $this->Triggers[$trigger] = $trigger;
    }

    /**
     * Process Trigger
     * @param string $trigger
     * @return null
     */
    public function ProcessTrigger($trigger) {
        // Get Current Activity
        $activity = $this->GetState();

        // Find what to do on this trigger
        foreach($activity->Configuration as $config => $action) {
            if ($config == $trigger) {
                return $action;
            }
        }

        // Don't know what to do next
        return null;
    }

    /**
     * Get the currently active Activity
     * @return Activity
     */
    public function GetState() {
        return $this->GetActivityByName($this->State);
    }

    /**
     * Get Activity By Name
     * @param string $name
     * @return Activity
     */
    public function GetActivityByName($name) {
        // Search for activity
        if (isset($this->Activities[$name])) {
            return $this->Activities[$name];
        }

        // None Found
        return null;
    }

    /**
     * Run the next iteration of the state machine.
     */
    public function Run() {
        // Get the current state
        $this->GetWorkflowState();

        // Run Current State
        $trigger = $this->GetState($this->Context)->Run($this->Context);

        // Get Next Activity to Run
        $this->State = $this->ProcessTrigger($trigger);

        // Save State
        $this->SaveWorkflowState();
    }

    /**
     * Get Workflow State from database
     */
    private function GetWorkflowState() {
        // Get data from database
        $data = MVC::DB()->fetch_one("SELECT * FROM `workflows` WHERE `id` = {$this->Id}");

        // Load data
        $this->State = $data->state;
        $this->Context = json_decode(html_entity_decode($data->context));
        $this->Context->WorkflowId = $this->Id;
    }

    /**
     * Save Workflow to database
     */
    private function SaveWorkflowState() {
        // Save workflow to database
        MVC::DB()->update(
            "workflows",
            array(
                "state" => $this->State,
                "context" => htmlentities(json_encode($this->Context)),
                "completion_date" => ($this->State == null)? now() : null
            ),
            array(
                "id" => $this->Id
            )
        );
    }

}