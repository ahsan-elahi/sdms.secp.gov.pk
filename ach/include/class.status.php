<?php
include_once(INCLUDE_DIR.'class.staff.php');
class Status {
    var $id;
 
    var $ht;
    var $parent;
    
    function Status($id) {
        $this->id=0;
        $this->load($id);
    }

    function load($id=0) {

        if(!$id && !($id=$this->getId()))
            return false;
        $sql='SELECT status.* '
            .' FROM '.STATUS_TABLE.' status '
            .' WHERE status.status_id='.db_input($id);

        if(!($res=db_query($sql)) || !db_num_rows($res))
            return false;

        $this->ht = db_fetch_array($res);
        $this->id=$this->ht['status_id'];
    
        return true;
    }
  
    function reload() {
        return $this->load();
    }

    function asVar() {
        return $this->getName();
    }
    
    function getId() {
        return $this->id;
    }

    function getName() {
        return $this->ht['status_title'];
    }
    function getHashtable() {
        return $this->ht;
    }

    function getInfo() {
        return $this->getHashtable();
    }

    function update($vars, &$errors) {
        if(!$this->save($this->getId(), $vars, $errors))
        return false;
			
        $this->reload();
        return true;
    }

    function delete() {
        $sql='DELETE FROM '.STATUS_TABLE.' WHERE topic_id='.db_input($this->getId()).' LIMIT 1';
        if(db_query($sql) && ($num=db_affected_rows())) {
            db_query('UPDATE '.STATUS_TABLE.' SET topic_pid=0 WHERE topic_pid='.db_input($this->getId()));
            db_query('UPDATE '.STATUS_TABLE.' SET topic_id=0 WHERE topic_id='.db_input($this->getId()));
            db_query('DELETE FROM '.STATUS_TABLE.' WHERE topic_id='.db_input($this->getId()));
        }

        return $num;
    }
    /*** Static functions ***/
    function create($vars,&$errors) { 
        return self::save(0, $vars, $errors);
    }
function getParentStatus($publicOnly=false){
	// topic_id 	topic_pid 
        $status=array();
		$sql='SELECT status.status_id, status.status_title as name '
            .' FROM '.STATUS_TABLE. ' status WHERE p_id = 0';
			
        $sql.=' ORDER BY status_id';
		
		if(($res=db_query($sql)) && db_num_rows($res))
            while(list($id, $name)=db_fetch_row($res))
                $status[$id]=$name;

        return $status;
    
}

function getParentStatus_Query($publicOnly=false){
	// topic_id 	topic_pid 
        $status=array();
		$sql='SELECT status.status_id, status.status_title as name '
            .' FROM '.STATUS_TABLE. ' status WHERE p_id = 0 AND status_access = "0" ';
			
        $sql.=' ORDER BY status_id';
	
		
		if(($res=db_query($sql)) && db_num_rows($res))
            while(list($id, $name)=db_fetch_row($res))
                $status[$id]=$name;

        return $status;
    
}
    function getStatus($isadmin=false,$publicOnly=false) {
	// topic_id 	topic_pid 
        $status=array();
		$sql='SELECT status.status_id, status.status_title as name '
            .' FROM '.STATUS_TABLE. ' status WHERE status.status_access=1 ';
			
		if($isadmin)
        $sql.=' OR status.status_access=0';
			
        $sql.=' ORDER BY status_id';
		
		
		if(($res=db_query($sql)) && db_num_rows($res))
            while(list($id, $name)=db_fetch_row($res))
                $status[$id]=$name;

        return $status;
    }

    function getAllStatus($isadmin=false,$publicOnly=false) {
	// topic_id 	topic_pid 
        $status=array();
		$sql='SELECT status.status_id, status.status_title as name '
            .' FROM '.STATUS_TABLE. ' status WHERE p_id = 0 ';
			
		if($isadmin)
        $sql.=' OR status.status_access=0';
			
        $sql.=' ORDER BY status_id';
		
		
		if(($res=db_query($sql)) && db_num_rows($res))
            while(list($id, $name)=db_fetch_row($res))
                $status[$id]=$name;

        return $status;
    }

    function getPublicHelpTopics() {
        return self::getHelpTopics(true);
    }
	
	//-----------------------------------------------------------------------------------------------------------------------------------------//
    function getIdByName($name, $pid=0) {			
        $sql='SELECT status_id FROM '.STATUS_TABLE
            .' WHERE status_title='.db_input($name);
        if(($res=db_query($sql)) && db_num_rows($res))
            list($id) = db_fetch_row($res);

        return $id;
    }
	
	function getNameById($id, $pid=0) {			
        $sql='SELECT * FROM '.STATUS_TABLE
            .' WHERE status_id='.db_input($id);
	
        if(($res=db_query($sql)))
            $row_status_titile = db_fetch_array($res);
            $status_titile = $row_status_titile['status_title'];

        return $status_titile;
    }

    function lookup($id) {
        return ($id && is_numeric($id) && ($t= new Status($id)) && $t->getId()==$id)?$t:null;
    }

    function save($id, $vars, &$errors) {
        $vars['status']=Format::striptags(trim($vars['status']));

        if($id && $id!=$vars['id'])
            $errors['err']='Internal error. Try again';

        if(!$vars['status'])
            $errors['status']='Compalint Status required';
		elseif(strlen($vars['status'])<3)
            $errors['status']='Status is too short. 3 chars minimum';
        elseif(($tid=self::getIdByName($vars['status'], $vars['pid'])) && $tid!=$id)
          //  $errors['status']='Status already exists';
			
        if($errors) return false;

        $sql=' updated=NOW() '
            .',status_title='.db_input($vars['status'])
			.',p_id='.db_input($vars['p_id'])
            .',notes='.db_input($vars['notes']);
		          
        if($id) {
            $sql='UPDATE '.STATUS_TABLE.' SET '.$sql.' WHERE status_id 	='.db_input($id);
            if(db_query($sql))
                return true;

            $errors['err']='Unable to update status. Internal error occurred';
        } else {
            $sql='INSERT INTO '.STATUS_TABLE.' SET '.$sql.',created=NOW()';
            if(db_query($sql) && ($id=db_insert_id()))
                return $id;
            
            $errors['err']='Unable to create the status. Internal error';
        }
        
        return false;
    }
}
?>