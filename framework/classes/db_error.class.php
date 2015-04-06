<?php
/**
 * Database Error Class
 *
 * @author Ralfe Poisson <ralfepoisson@gmail.com>
 * @copyright Copyright (c) Ralfe Poisson 2014
 */

class DatabaseError {

    // -----------------------------------------------------------
    // Attributes
    // -----------------------------------------------------------

    var $db;
    var $query;
    var $error;
    var $trace;

    // -----------------------------------------------------------
    // Functions
    // -----------------------------------------------------------

    public function toHTML($showTrace=false) {
        // Determine what to show
        $trace = ($showTrace)? "<hr><strong>Stack Trace</strong><br><i>{$this->trace}</i>" : "";

        // Generate HTML
        $html = "
            <div class='panel panel-error'>
                <div class='panel-heading'>
                    <h3>Database Error Occurred</h3>
                </div>
                <div class='panel-body'>
                    <strong>[{$this->db}] : \"{$this->query}\"</strong>
                    <hr>
                    {$this->error}
                    {$this->trace}
                </div>
            </div>
        ";

        // Return HTML
        return $html;
    }

}