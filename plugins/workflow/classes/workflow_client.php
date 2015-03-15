<?php
/**
 * TinyMVC Plugin: Workflow Client Class
 *
 * @author Ralfe Poisson <ralfepoisson@gmail.com>
 */
class WorkflowClient {

    /**
     * Launch an instance of a state machine workflow.
     * @param StateMachine $stateMachine
     * @return int
     */
    public function StartWorkflow($stateMachine) {
        // Get Starting State
        $stateMachine->State = $stateMachine->Configure();

        // Insert into Database
        $id = MVC::DB()->insert(
            "workflows",
            array(
                "type" => get_class($stateMachine),
                "state" => $stateMachine->State,
                "creation_date" => now(),
                "updated_on" => now()
            )
        );

        // Return Workflow Id
        return $id;
    }

}
