<?php
//if(!defined('OSTCLIENTINC') || !is_object($thisclient) || !$thisclient->isValid() || !$cfg->showRelatedTickets()) die('Access Denied');

$qstr='&'; //Query string collector
$status=null;
if(isset($_REQUEST['status'])) { //Query string status has nothing to do with the real status used below.
    $qstr.='status='.urlencode($_REQUEST['status']);
    //Status we are actually going to use on the query...making sure it is clean!
    switch(strtolower($_REQUEST['status'])) {
     case 'open':
     case 'closed':
        $status=strtolower($_REQUEST['status']);
        break;
     default:
        $status=''; //ignore
    }
} elseif($thisclient->getNumOpenTickets()) {
    $status='open'; //Defaulting to open
}

$sortOptions=array('id'=>'ticketID', 'name'=>'ticket.name', 'subject'=>'ticket.subject',
                    'email'=>'ticket.email', 'status'=>'ticket.status', 'dept'=>'dept_name','date'=>'ticket.created');
$orderWays=array('DESC'=>'DESC','ASC'=>'ASC');
//Sorting options...
$order_by=$order=null;
$sort=($_REQUEST['sort'] && $sortOptions[strtolower($_REQUEST['sort'])])?strtolower($_REQUEST['sort']):'date';
if($sort && $sortOptions[$sort])
    $order_by =$sortOptions[$sort];

$order_by=$order_by?$order_by:'ticket_created';
if($_REQUEST['order'] && $orderWays[strtoupper($_REQUEST['order'])])
    $order=$orderWays[strtoupper($_REQUEST['order'])];

$order=$order?$order:'ASC';
if($order_by && strpos($order_by,','))
    $order_by=str_replace(','," $order,",$order_by);

$x=$sort.'_sort';
$$x=' class="'.strtolower($order).'" ';

$qselect='SELECT ticket.ticket_id,ticket.ticketID,ticket.dept_id,ticket.location,isanswered, dept.ispublic, ticket.subject, ticket.name, ticket.email '.
           ',dept_name,ticket. status, ticket.source, ticket.created,ticket.phone_ext,ticket.phone,ticket.complaint_status ';

$qfrom='FROM '.TICKET_TABLE.' ticket '
      .' LEFT JOIN '.DEPT_TABLE.' dept ON (ticket.dept_id=dept.dept_id) ';

$qwhere =' WHERE ticket.email='.db_input($thisclient->getEmail());

if($status){
    $qwhere.=' AND ticket.status='.db_input($status);
}

$search=($_REQUEST['a']=='search' && $_REQUEST['q']);
if($search) {
    $qstr.='&a='.urlencode($_REQUEST['a']).'&q='.urlencode($_REQUEST['q']);
    if(is_numeric($_REQUEST['q'])) {
        $qwhere.=" AND ticket.ticketID LIKE '$queryterm%'";
    } else {//Deep search!
        $queryterm=db_real_escape($_REQUEST['q'],false); //escape the term ONLY...no quotes.
        $qwhere.=' AND ( '
                ." ticket.subject LIKE '%$queryterm%'"
                ." OR thread.body LIKE '%$queryterm%'"
                .' ) ';
        $deep_search=true;
        //Joins needed for search
        $qfrom.=' LEFT JOIN '.TICKET_THREAD_TABLE.' thread ON ('
               .'ticket.ticket_id=thread.ticket_id AND thread.thread_type IN ("M","R"))';
    }
}

$total=db_count('SELECT count(DISTINCT ticket.ticket_id) '.$qfrom.' '.$qwhere);
$page=($_GET['p'] && is_numeric($_GET['p']))?$_GET['p']:1;
$pageNav=new Pagenate($total, $page, PAGE_LIMIT);
$pageNav->setURL('tickets.php',$qstr.'&sort='.urlencode($_REQUEST['sort']).'&order='.urlencode($_REQUEST['order']));

//more stuff...
$qselect.=' ,count(attach_id) as attachments ';
$qfrom.=' LEFT JOIN '.TICKET_ATTACHMENT_TABLE.' attach ON  ticket.ticket_id=attach.ticket_id ';
$qgroup=' GROUP BY ticket.ticket_id';

$query="$qselect $qfrom $qwhere $qgroup ORDER BY $order_by $order LIMIT ".$pageNav->getStart().",".$pageNav->getLimit();
//echo $query;
$res = db_query($query);
$showing=($res && db_num_rows($res))?$pageNav->showing():"";
$showing.=($status)?(' '.ucfirst($status).' Complaints'):' All Complaints';
if($search)
    $showing="Search Results: $showing";

$negorder=$order=='DESC'?'ASC':'DESC'; //Negate the sorting

?>
<div class="container-fluid">
	<div class="container" style="background-color: white;color: #767271;">
			<div class="row">
				<div class="col-xs-12 padding-top-10">
				<a href="logout.php">
					<img class="img-responsive" src="assets/images/logout.jpg" style="float: right;">
				</a>
                <!--<a class="refresh" href="<?php //echo $_SERVER['REQUEST_URI']; ?>">Refresh</a>-->
					
				</div>
            <div  class="col-xs-9 padding-top-10">
            <h4>Query LISTING</h4>
 			</div>
            <div  class="col-xs-3 padding-top-10">
            <form action="tickets.php" method="get" id="ticketSearchForm">
    <input type="hidden" name="a"  value="search">
    <input type="text" name="q" size="20" value="<?php echo Format::htmlchars($_REQUEST['q']); ?>">
    <!--<select name="status">
        <option value="">&mdash; Any Status &mdash;</option>
        <option value="open"
            <?php // echo ($status=='open')?'selected="selected"':'';?>>Open (<?php echo $thisclient->getNumOpenTickets(); ?>)</option>
        <?php
       // if($thisclient->getNumClosedTickets()) {
            ?>
        <option value="closed"
            <?php //echo ($status=='closed')?'selected="selected"':'';?>>Closed (<?php echo $thisclient->getNumClosedTickets(); ?>)</option>
        <?php
        //} ?>
    </select>-->
    <input type="submit" value="Go">
</form>
            </div>
				<div class="col-xs-12">
					<div class="table-responsive">
						<div class="panel panel-success" style="max-height: inherit;padding: 0px;">
						    <div class="panel-heading">Complaint Listing</div>
						     <table class="table table-bordered">
							    <thead >
							      <tr style = "border:0px">
							        <th>Complaint #</th>
							        <th>Create Date</th>
							        <th>Status</th>
                                    <th>Subject</th>
							        <th>Department</th>
                                    <th>Phone Number</th>
							      </tr>
							    </thead>
							    <tbody>
                                 <?php
     if($res && ($num=db_num_rows($res))) {
        $defaultDept=Dept::getDefaultDeptName(); //Default public dept.
        while ($row = db_fetch_array($res)) {
            $dept=$row['ispublic']?$row['dept_name']:$defaultDept;
            $subject=Format::htmlchars(Format::truncate($row['subject'],40));
            if($row['attachments'])
                $subject.='  &nbsp;&nbsp;<span class="Icon file"></span>';

            $ticketID=$row['ticketID'];
            if($row['isanswered'] && !strcasecmp($row['status'],'open')) {
                $subject="<b>$subject</b>";
                $ticketID="<b>$ticketID</b>";
            }
            $phone=Format::phone($row['phone']);
            if($row['phone_ext'])
                $phone.=' '.$row['phone_ext'];
            ?>
            <tr id="<?php echo $row['ticketID']; ?>">
                <td class="centered"><a style="margin-left:10px;" title="<?php echo $row['email']; ?>" href="tickets.php?task=web&id=<?php echo $row['ticketID']; ?>"><?php echo $ticketID; ?></a></td>
                <td><?php echo Format::db_date($row['created']); ?></td>
                <td><?php echo ucfirst($row['complaint_status']); ?></td>
                <td><a href="tickets.php?task=web&id=<?php echo $row['ticketID']; ?>"><?php echo $subject; ?></a></td>
                <td><?php echo Format::truncate($dept,30); ?></td>
                <td><?php echo $phone; ?></td>
            </tr>
        <?php
        }

     } else {
         echo '<tr><td colspan="7">Your query did not match any records</td></tr>';
     }
    ?>
							      
							    </tbody>
                                <?php /*?><tfoot>
                                    <tr>
                                    <td colspan="3"><?php echo $showing; ?></td>
                                    <td colspan="4"><?php
if($res && $num>0) {
    echo '<div>&nbsp;Page:'.$pageNav->getPageLinks().'&nbsp;</div>';
}
?></td></tr>
                                    
                                </tfoot><?php */?>
							  </table>
					  </div>
					</div>
				</div>
			</div>	
	</div>
</div>