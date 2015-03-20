<?php
/**
 * TinyMVC Plugin: StateMachine
 *
 * @author Ralfe Poisson <ralfepoisson@gmail.com>
 */
abstract class StateMachine extends Model {

    public $id;
    public $type;
    public $state;
    public $retries;
    public $creation_date;
    public $deactivation_date;
    public $completion_date;
    public $suspended_date;
    public $updated_on;
    public $context;

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
        $this->table = "workflows";
        $this->id = $id;
        $this->Id = $id;
        $this->Activities = array();
        $this->Triggers = array();
        $this->Context = (object)array();
        $this->load();
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

        // Log Activity
        MVC::log("Workflow: Running workflow #{$this->Id}.");
        MVC::log("Workflow: - State: " . $this->State);

        try {
            // Run Current State
            $trigger = $this->GetState($this->Context)->Run($this->Context);
            MVC::log("Workflow: - Trigger: " . $trigger);

            // Get Next Activity to Run
            $this->State = $this->ProcessTrigger($trigger);
            MVC::log("Workflow: - Moving to State: " . $this->State);

            // Save State
            MVC::log("Workflow: - Saving Workflow.");
            $this->SaveWorkflowState();
        }
        catch(Exception $e) {
            // Handle Error
            $error = str_replace('"', "'", json_encode($e));
            MVC::log("Workflow: - Error: " . $error);
            $this->HandleError($error);
        }
    }

    private function HandleError($error) {
        // Increment Retries
        $this->retries++;
        MVC::log("Workflow: - Retries: " . $this->retries);

        // Check for Suspension Criteria
        // TODO: This limit should be set in the configuration
        if ($this->retries >= 5) {
            MVC::log("Workflow: - Suspending workflow.");
            $this->suspended_date = now();
        }

        // Update Workflow Record
        MVC::DB()->update(
            "workflows",
            array(
                "retries" => $this->retries,
                "suspended_date" => $this->suspended_date,
                "exception" => $error
            ),
            array(
                "id" => $this->id
            )
        );
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
