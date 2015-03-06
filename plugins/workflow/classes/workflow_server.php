<?php
/**
 * TinyMVC Plugin: Workflow Server
 *
 * @author Ralfe Poisson <ralfepoisson@gmail.com>
 */
class WorkflowServer {

    private $Workflows;

    /**
     * Constructor
     */
    public function __construct() {
        // Check the tables
        $this->checkTables();
        $this->Workflows = array();
    }

    public function Run() {
        // Get Active Workflows
        $this->getActiveWorkflows();

        // Run Workflows
        foreach($this->Workflows as $workflow) {
            $workflow->Run();
        }
    }

    /**
     * Check that the required tables are
     */
    private function checkTables() {
        // Check Tables
    }

    /**
     * Get all Active Workflows
     */
    private function getActiveWorkflows() {
        // Get Workflow data
        $data = MVC::DB()->fetch("
            SELECT
                *
            FROM
                `workflows`
            WHERE
                `deactivation_date` IS NULL AND
                `completion_date` IS NULL
        ");

        // Check if we retrieved anyting
        if ($data == null || sizeof($data) == 0) {
            return;
        }

        // Load Workflow objects
        $this->Workflows = array();
        foreach($data as $item) {
            // Create the Workflow instance
            $obj = new ReflectionClass($item->type);
            $this->Workflow[] = $obj->newInstanceArgs(array($item->id));
        }
    }

}
