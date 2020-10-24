<?php

class Districts {
    
    var $id;
    var $ht;

    function Districts($id){
        
        $this->id =0;
        $this->load($id);
    }

    function load($id) {
        if(!$id && !($id=$this->getId()))
            return false;


        $sql='SELECT *  FROM '.DISTRICTS_TABLE
            .' WHERE District_ID='.db_input($id);
        if(!($res=db_query($sql)) || !db_num_rows($res))
            return false;

        $this->ht= db_fetch_array($res);
        $this->id= $this->ht['District_ID'];

        return true;;
    }

    function getId() {
        return $this->id;
    }

    function getTag() {
        return $this->ht['District'];
    }

    /* ------------- Static ---------------*/
    function lookup($id) {
        return ($id && is_numeric($id) && ($p=new Districts($id)) && $p->getId()==$id)?$p:null;
    }

    function getDistricts( $publicOnly=false) {

        $districts=array();
        $sql ='SELECT District_ID ,District FROM '.DISTRICTS_TABLE;
  

        if(($res=db_query($sql)) && db_num_rows($res)) {
            while(list($id, $name)=db_fetch_row($res))
                $districts[$id] = $name;
        }

        return $districts;
    }

    function getPublicDistricts() {
        return self::getDistricts(true);
    }
	
		      function getTehsils($complaint_id) {
        return self::getSubTehsils(true,$complaint_id);
    }
		
		
		function getSubTehsils($publicOnly=false,$district_id) {
        $sub_tehsils=array();
		if($district_id!='')
		{
		$sql_sub_tehsils='SELECT ht.Tehsil_ID, ht.Tehsil_Name as name ' 	
            .' FROM '.TEHSILS_TABLE. ' ht '
            .' WHERE ht.District_ID="'.$district_id.'" ';

        $sql_sub_tehsils.=' ORDER BY name';
        if(($res=db_query($sql_sub_tehsils)) && db_num_rows($res))
            while(list($id, $name)=db_fetch_row($res))
                $sub_tehsils[$id]=$name;
		}
        return $sub_tehsils;
    }
	
	//GET AGENCY TEHSIL
		      function getAgencyTehsil($tehsil_id) {
        return self::getAgencyTehsils(true,$tehsil_id);
    }
		
		
		function getAgencyTehsils($publicOnly=false,$tehsil_id) {
        $agency_tehsils=array();
		if($tehsil_id!='')
		{
		$sql_sub_tehsils='SELECT ht.AgencyTehsil_ID, ht.AgencyTehsil_Name as name ' 	
            .' FROM '.AGENCY_TEHSIL. ' ht '
            .' WHERE ht.Tehsil_ID="'.$tehsil_id.'" ';

        $sql_sub_tehsils.=' ORDER BY name';
        if(($res=db_query($sql_sub_tehsils)) && db_num_rows($res))
            while(list($id, $name)=db_fetch_row($res))
                $agency_tehsils[$id]=$name;
		}
        return $agency_tehsils;
    }
	
}
?>
