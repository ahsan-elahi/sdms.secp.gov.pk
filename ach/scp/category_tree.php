<?php 
//connect to mysql and select database 
$conn=mysql_connect("localhost","root","lastfight@secp321$");
$db=mysql_select_db("sdms_scep",$conn);
 
//call the recursive function to print category listing
category_tree(0);
 
//Recursive php function
function category_tree($catid){


	$sql_topics = "SELECT * FROM `sdms_help_topic` WHERE dept_id = '4' AND topic_pid = '".$catid."' AND isnature = '0' ";
	$res_topics = mysql_query($sql_topics);
	//$num_topics = mysql_num_rows($res_topics);
	//echo '<br>'.$num_topics.'<br>';
	
	while($row = mysql_fetch_array($res_topics)){
		$i = 0;
		if ($i == 0)
		{ 
			echo '<ul>';
		}
				echo '<li>' .$row['topic'].'('.count_complaint($row['topic_id'],1).')';
	
				if($row['topic_id'] != $catid){
				category_tree($row['topic_id']);
				}
				echo '</li>';
		$i++;
		if ($i > 0) 
		{
			echo '</ul>';
		}
	}
}


function count_complaint($topic_id,$one_time){
	global $total_complaints;
	if($one_time == 1){
	$sql_tickets = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND topic_id='".$topic_id."'";
	$res_tickets = mysql_query($sql_tickets);
	$num_tickets = mysql_num_rows($res_tickets);
	$total_complaints = $num_tickets;
	//echo '<br>Ticket default in '.$topic_id.' ='. $total_complaints;
	$one_time ++;
	}
	$sql_inner_topics = "SELECT * FROM `sdms_help_topic` WHERE dept_id = '4' AND topic_pid = '".$topic_id."' AND isnature = '0' ";
	$res_inner_topics = mysql_query($sql_inner_topics);
	//$num_inner_topics = mysql_num_rows($res_inner_topics);
	//echo '<br>'.$num_inner_topics.'<br>';
	
		while($row_inner_topics = mysql_fetch_array($res_inner_topics)){
			
		$sql_inner_tickets = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND topic_id='".$row_inner_topics['topic_id']."'";
		$res_inner_tickets = mysql_query($sql_inner_tickets);
		$num_inner_tickets = mysql_num_rows($res_inner_tickets);
		$total_complaints += $num_inner_tickets;
		//echo '<br>Ticket in '.$row_inner_topics['topic_id'].' ='. $total_complaints;	
		count_complaint($row_inner_topics['topic_id'],$one_time);
		}
	//echo '<br>Ticket Total ='. $total_complaints;	
	return $total_complaints;
	
}


/*function CategoryTree($dept_id,&$output=null, $parent=0, $indent=null){
	// conection to the database
	
	$conn=mysql_connect("localhost","root","lastfight@secp321$");
	$db=mysql_select_db("sdms_scep",$conn);
	
	// select the categories that have on the parent column the value from $parent
	$sql_topics = "SELECT * FROM `sdms_help_topic` WHERE dept_id = '".$dept_id."' AND topic_pid = '".$parent."' AND isnature = '0' ";
	$res_topics = mysql_query($sql_topics);

	// show the categories one by one
	while($c = mysql_fetch_array($res_topics)){
		$output .= '<option value=' . $c['topic_id'] . '>' . $indent . $c['topic'] . "</option>";
		if($c['topic_id'] != $parent){
			// in case the current category's id is different that $parent
			// we call our function again with new parameters
			CategoryTree($dept_id,$output, $c['topic_id'], $indent . "&nbsp;&nbsp;");
		}
	}
	// return the list of categories
	return $output;
}
// show the categories on the web page
echo "<select name='category'>
<option value='0'>Select a category</option>" . 
CategoryTree(1);
"</select>";*/

/*function CategoryTree(&$output=null, $parent=0, $indent=null){
	// conection to the database
	$db = new PDO("mysql:host=localhost;dbname=sdms_scep", 'root', 'lastfight@secp321$');
	// select the categories that have on the parent column the value from $parent
	$r = $db->prepare("SELECT topic_id, topic FROM sdms_help_topic WHERE topic_pid=:parentid");
	$r->execute(array(
		'parentid' 	=> $parent
	));
	// show the categories one by one
	while($c = $r->fetch(PDO::FETCH_ASSOC)){
		$output .= '<option value=' . $c['topic_id'] . '>' . $indent . $c['topic'] . "</option>";
		if($c['topic_id'] != $parent){
			// in case the current category's id is different that $parent
			// we call our function again with new parameters
			CategoryTree($output, $c['topic_id'], $indent . "&nbsp;&nbsp;");
		}
	}
	// return the list of categories
	return $output;
}
// show the categories on the web page
echo "<select name='category'>
<option value='0'>Select a category</option>" . 
CategoryTree();
"</select>";*/
?>