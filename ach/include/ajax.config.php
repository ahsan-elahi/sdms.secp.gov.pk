<?php

if(!defined('INCLUDE_DIR')) die('!');
	    
class ConfigAjaxAPI extends AjaxController {

    //config info UI might need.
    function scp() {
        global $cfg;

        $config=array(
                      'lock_time'       => ($cfg->getLockTime()*3600),
                      'max_file_uploads'=> (int) $cfg->getStaffMaxFileUploads()
                      );
        return $this->json_encode($config);
    }

    function client() {
        global $cfg;

        $config=array(
                      'file_types'      => $cfg->getAllowedFileTypes(),
                      'max_file_size'   => (int) $cfg->getMaxFileSize(),
                      'max_file_uploads'=> (int) $cfg->getClientMaxFileUploads()
                      );

        return $this->json_encode($config);
    }
}
?>
