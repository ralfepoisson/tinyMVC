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
        $tables = MVC::DB()->get_tables();

        // Check if Workflow Table exists
        foreach($tables as $table) {
            if ($table->Tables_in_need_it == "workflows") {
                return;
            }
        }

        // Create missing Tables
        MVC::log("WorkflowServer: Creating missing `workflows` table.", 3);
        MVC::DB()->query("
        CREATE TABLE `workflows` (
            `id` int(11) auto_increment,
            `type` varchar(100),
            `state` varchar(100),
            `retries` int(10) NOT NULL default 0,
            `creation_date` datetime,
            `deactivation_date` datetime,
            `completion_date` datetime,
            `suspended_date` datetime,
            `updated_on` datetime,
            `context` text,
            `exception` text,
            PRIMARY KEY (`id`)
        )
        ");
        MVC::log("WorkflowServer: - Done.", 3);
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
                `completion_date` IS NULL AND
                `suspended_date` IS NULL
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
            $workflow = $obj->newInstanceArgs(array($item->id));

            // Decode Context
            $workflow->Context = json_decode(html_entity_decode($workflow->context));

            // Configure
            $workflow->Configure();

            // Add to List of Workflows
            $this->Workflows[] = $workflow;
        }
    }

}
