<?php
/**
 * Base Model
 *
 * @author Ralfe Poisson <ralfepoisson@gmail.com>
 */
class BaseModel {

    /**
     * Populate current object from properties of another object.
     * @param object $obj
     */
    public function Bind($obj) {
        // Create Associative Array of Object Properties
        $arr = get_object_vars($obj);

        // Set Current Object's properties based on Object being bound from
        foreach($arr as $key => $val) {
            $this->$key = $val;
        }
    }

}
