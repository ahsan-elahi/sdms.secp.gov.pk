<?php  include('../ach/includes/class.format.php'); ?>
<?php 

//database connection details
//$connect = mysql_connect('localhost','ahsan','ubuntu');
$connect = mysql_connect('localhost','root','lastfight@secp321$');
if (!$connect) {
 die('Could not connect to MySQL: ' . mysql_error());
 }
//your database name
$cid = mysql_select_db('sdms_scep',$connect);
$nums_sub_category = 0;
$sql_tickets = "Select * from sdms_ticket where 1 order by ticket_id asc LIMIT 0,2000";
$res_tickets = mysql_query($sql_tickets);
while($row_ticket = mysql_fetch_array($res_tickets)){
	
	
		$sql_ticket_threads = "Select * from sdms_ticket_thread where ticket_id='".$row_ticket['ticket_id']."' order by id asc";
		$res_ticket_threads = mysql_query($sql_ticket_threads);
		while($row_ticket_thread = mysql_fetch_array($res_ticket_threads)){

$attachments = array();

$sql_ticket_file_attachments='SELECT a.attach_id, f.id as file_id, f.size, f.hash as file_hash, f.name '
            .' FROM sdms_file f '
            .' INNER JOIN sdms_ticket_attachment a ON(f.id=a.file_id) '
            .' WHERE a.ticket_id='.$row_ticket['ticket_id']
            .' AND a.ref_id='.$row_ticket_thread['id']
            .' AND a.ref_type="'.$row_ticket_thread['thread_type'].'"';
        $res_ticket_file_attachments = mysql_query($sql_ticket_file_attachments);
		while($row_ticket_file_attachment = mysql_fetch_array($res_ticket_file_attachments)){
			if($row_ticket_file_attachment['attach_id']!=''){
			 $attachments[] = $row_ticket_file_attachment;			
			}
		}


			$str='';
        foreach($attachments as $attachment ) {
        	$file='attachment.php';
        	$target='';
        	$separator=' ';

            $hash=md5($attachment['file_id'].session_id().$attachment['file_hash']);
            $size = '';
            if($attachment['size']){
            	$bytes = $attachment['size'];


				if(!is_numeric($bytes))
				$size = $bytes;

				if($size<1024)
				$size = $bytes.' bytes';

				if($bytes <102400)
				$size = round(($bytes/1024),1).' kb';

				if($bytes >=102400)
				$size = round(($bytes/1024000),1).' mb';

            }

 if($attachment['name']){
 	$var = $attachment['name'];
             $flags = ENT_COMPAT | ENT_QUOTES;
        if (phpversion() >= '5.4.0')
            $flags |= ENT_HTML401;

        $attachment['name'] = is_array($var)
            ? array_map(array('Format','htmlencode'), $var)
            : htmlentities($var, $flags, 'UTF-8');
        }
        echo 'Ticket ID ' . $row_ticket['ticket_id'].'--';
        echo 'Thread ID ' . $row_ticket_thread['id'].'--';
   echo '<a class="Icon file" href="../ach/scp/'.$file.'?id='.$attachment['attach_id'].'&h='.$hash.'" target="'.$target.'">'.$attachment['name'].'</a>';
   echo ' ( '.$size.' '.$separator.')<br>';
        }


		}

		}
	