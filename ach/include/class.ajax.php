<?php

require_once (INCLUDE_DIR.'class.api.php');

/**
 * AjaxController Class
 * A simple extension of the ApiController class that will assist in
 * providing functionality common to all Ajax call controllers. Any Ajax
 * call controller should inherit from this class in order to maintain
 * consistency.
 */
class AjaxController extends ApiController {
    function AjaxController() {
    
    }
    function staffOnly() {
        global $thisstaff;
        if(!$thisstaff || !$thisstaff->isValid()) {
            Http::response(401,'Access Denied. IP '.$_SERVER['REMOTE_ADDR']);
        }
    }
    /**
     * Convert a PHP array into a JSON-encoded string
     */
    function json_encode($what) {
        require_once (INCLUDE_DIR.'class.json.php');
        $encoder = new JsonDataEncoder();
        return $encoder->encode($what);
    }

    function encode($what) {
        return $this->json_encode($what);
    }

    function get($var, $default=null) {
        return (isset($_GET[$var])) ? $_GET[$var] : $default;
    }
}
