<?php

if(!defined('INCLUDE_DIR')) die('403');

include_once(INCLUDE_DIR.'class.ticket.php');

class UsersAjaxAPI extends AjaxController {
   
    /* Assumes search by emal for now */
    function search() {

        if(!isset($_REQUEST['q'])) {
            Http::response(400, 'Query argument is required');
        }

        $limit = isset($_REQUEST['limit']) ? (int) $_REQUEST['limit']:25;
        $users=array();

        $sql='SELECT DISTINCT email, name '
            .' FROM '.TICKET_TABLE
            .' WHERE email LIKE \'%'.db_input(strtolower($_REQUEST['q']), false).'%\' '
            .' ORDER BY created '
            .' LIMIT '.$limit;
           
        if(($res=db_query($sql)) && db_num_rows($res)){
            while(list($email,$name)=db_fetch_row($res)) {
                $users[] = array('email'=>$email, 'name'=>$name, 'info'=>"$email - $name");
            }                    
        }  
        
        return $this->json_encode($users);

    }
}
?>
