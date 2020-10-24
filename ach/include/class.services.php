<?php
class Service {
    var $id;
 
    var $ht;
    var $parent;
    
    function Service($id) {
        $this->id=0;
        $this->load($id);
    }

    function load($id=0) {

        if(!$id && !($id=$this->getId()))
            return false;
        $sql='SELECT service.* '
            .' FROM '.SERVICE_TABLE.' service '
            .' WHERE service.service_id='.db_input($id);

        if(!($res=db_query($sql)) || !db_num_rows($res))
            return false;

        $this->ht = db_fetch_array($res);
        $this->id=$this->ht['service_id'];
    
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
        return $this->ht['service_title'];
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

        $sql='DELETE FROM '.SERVICE_TABLE.' WHERE topic_id='.db_input($this->getId()).' LIMIT 1';
        if(db_query($sql) && ($num=db_affected_rows())) {
            db_query('UPDATE '.SERVICE_TABLE.' SET topic_pid=0 WHERE topic_pid='.db_input($this->getId()));
            db_query('UPDATE '.SERVICE_TABLE.' SET topic_id=0 WHERE topic_id='.db_input($this->getId()));
            db_query('DELETE FROM '.SERVICE_TABLE.' WHERE topic_id='.db_input($this->getId()));
        }

        return $num;
    }
    /*** Static functions ***/
    function create($vars,&$errors) { 
        return self::save(0, $vars, $errors);
    }

    function getService($publicOnly=false) {
	// topic_id 	topic_pid 
        $service=array();
		
		$sql='SELECT service.service_id, service.service_title as name '
            .' FROM '.SERVICE_TABLE. ' service ';
        $sql.=' ORDER BY service_id';
        if(($res=db_query($sql)) && db_num_rows($res))
            while(list($id, $name)=db_fetch_row($res))
                $service[$id]=$name;

        return $service;
    }

    function getPublicHelpTopics() {
        return self::getHelpTopics(true);
    }
	
	//-----------------------------------------------------------------------------------------------------------------------------------------//
    function getIdByName($name, $pid=0) {			
        $sql='SELECT status_id FROM '.SERVICE_TABLE
            .' WHERE status_title='.db_input($name);
        if(($res=db_query($sql)) && db_num_rows($res))
            list($id) = db_fetch_row($res);

        return $id;
    }

    function lookup($id) {
        return ($id && is_numeric($id) && ($t= new Service($id)) && $t->getId()==$id)?$t:null;
    }

    function save($id, $vars, &$errors) {
        $vars['service']=Format::striptags(trim($vars['service']));

        if($id && $id!=$vars['id'])
            $errors['err']='Internal error. Try again';

        if(!$vars['service'])
            $errors['service']='Compalint service required';
		elseif(strlen($vars['service'])<3)
            $errors['service']='Service is too short. 3 chars minimum';
        elseif(($tid=self::getIdByName($vars['service'], $vars['pid'])) && $tid!=$id)
            $errors['service']='Service already exists';

        if($errors) return false;

        $sql=' updated=NOW() '
            .',service_title='.db_input($vars['service'])
            .',notes='.db_input($vars['notes']);
		          
        if($id) {
            $sql='UPDATE '.SERVICE_TABLE.' SET '.$sql.' WHERE service_id 	='.db_input($id);
            if(db_query($sql))
                return true;

            $errors['err']='Unable to update service. Internal error occurred';
        } else {
            $sql='INSERT INTO '.SERVICE_TABLE.' SET '.$sql.',created=NOW()';
            if(db_query($sql) && ($id=db_insert_id()))
                return $id;
            
            $errors['err']='Unable to create the service. Internal error';
        }
        
        return false;
    }
}
?>