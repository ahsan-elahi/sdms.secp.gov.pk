<?php
if(!defined('OSTSCPINC') || !$thisstaff ) die('Access Denied');
$sql_sub_topics='SELECT ht.topic_id, ht.topic as name '
.' FROM '.TOPIC_TABLE. ' ht '
.' WHERE ht.isactive=1 AND ht.topic_pid="'.$_REQUEST['cat_id'].'"  AND ht.ispublic=1 AND ht.isnature='.$_REQUEST['isnature'].'  ORDER BY name';
//echo $sql_sub_topics;
if(($res=db_query($sql_sub_topics)) && db_num_rows($res))
{
?>
<div class="span3">Sub Category:</div>
<div class="span9">
<select onChange="get_subcategory(this.value,this.id);" id="select_<?php echo $_REQUEST['next_tiers'] ?>" required>
<option value="">--Select Category--</option>
<?php
            while(list($id, $name)=db_fetch_row($res))
               echo sprintf('<option value="%d" >%s</option>',$id,$name);
 
				//$sub_topics[$id]=$name;
		
i/*f($sub_depart=Topic::getSubPublicHelpTopics($_REQUEST['cat_id'])) {
foreach($sub_depart as $id =>$name) {
echo sprintf('<option value="%d" %s>%s</option>',$id, ($info['deptId']==$id)?'selected="selected"':'',$name);
}
}*/
?>
</select></div>
<?php }?>