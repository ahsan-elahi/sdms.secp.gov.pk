<?php

class Timezone {

    var $id;
    var $ht;

    function Timezone($id){
        $this->id=0;
        return $this->load($id);
    }

    function load($id=0) {

        if(!$id && !($id=$this->getId()))
            return false;

        $sql='SELECT * FROM '.TIMEZONE_TABLE.' WHERE id='.db_input($id);
        if(!($res=db_query($sql)) || !db_num_rows($res))
            return false;

        $this->ht=db_fetch_array($res);
        $this->id=$this->ht['id'];
        
        return $this->id;
    }

    function reload() {
        return $this->load();
    }

    function getId() { 
        return $this->id;
    }
        
    function getOffset() {
        return $this->ht['offset'];    
    }

    function getName() {
        return $this->info['timezone'];
    }

    function getDesc() {
        return $this->getName();
    }

    /* static functions */
    function lookup($id) {
        return ($id && is_numeric($id) && ($tz= new Timezone($id)) && $tz->getId()==$id)?$tz:null;
    }

    function getOffsetById($id) {
        return ($tz=Timezone::lookup($id))?$tz->getOffset():0;
    }
}
?>
