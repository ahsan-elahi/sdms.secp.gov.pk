<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');
$qstr='';
$select='SELECT staff.*,CONCAT_WS(" ",firstname,lastname) as name,dept.dept_name as dept ';
$from='FROM '.STAFF_TABLE.' staff '.
      'LEFT JOIN '.DEPT_TABLE.' dept ON(staff.dept_id=dept.dept_id) ';
$where='WHERE staff.isvisible=1 ';

if($_REQUEST['q']) {
    $searchTerm=$_REQUEST['q'];
    if($searchTerm){
        $query=db_real_escape($searchTerm,false); //escape the term ONLY...no quotes.
        if(is_numeric($searchTerm)){
            $where.=" AND (staff.phone LIKE '%$query%' OR staff.phone_ext LIKE '%$query%' staff.mobile LIKE '%$query%') ";
        }elseif(strpos($searchTerm,'@') && Validator::is_email($searchTerm)){
            $where.=" AND staff.email='$query'";
        }else{
            $where.=" AND ( staff.email LIKE '%$query%'".
                         " OR staff.lastname LIKE '%$query%'".
                         " OR staff.firstname LIKE '%$query%'".
                        ' ) ';
        }
    }
}

if($_REQUEST['did'] && is_numeric($_REQUEST['did'])) {
    $where.=' AND staff.dept_id='.db_input($_REQUEST['did']);
    $qstr.='&did='.urlencode($_REQUEST['did']);
}

$sortOptions=array('name'=>'staff.firstname,staff.lastname','email'=>'staff.email','dept'=>'dept.dept_name',
                   'phone'=>'staff.phone','mobile'=>'staff.mobile','ext'=>'phone_ext',
                   'created'=>'staff.created','login'=>'staff.lastlogin');
$orderWays=array('DESC'=>'DESC','ASC'=>'ASC');
$sort=($_REQUEST['sort'] && $sortOptions[strtolower($_REQUEST['sort'])])?strtolower($_REQUEST['sort']):'name';
//Sorting options...
if($sort && $sortOptions[$sort]) {
    $order_column =$sortOptions[$sort];
}
$order_column=$order_column?$order_column:'staff.firstname,staff.lastname';

if($_REQUEST['order'] && $orderWays[strtoupper($_REQUEST['order'])]) {
    $order=$orderWays[strtoupper($_REQUEST['order'])];
}

$order=$order?$order:'ASC';
if($order_column && strpos($order_column,',')){
    $order_column=str_replace(','," $order,",$order_column);
}
$x=$sort.'_sort';
$$x=' class="'.strtolower($order).'" ';
$order_by="$order_column $order ";

$total=db_count('SELECT count(DISTINCT staff.staff_id) '.$from.' '.$where);
$page=($_GET['p'] && is_numeric($_GET['p']))?$_GET['p']:1;
$pageNav=new Pagenate($total, $page, PAGE_LIMIT);
$pageNav->setURL('directory.php',$qstr.'&sort='.urlencode($_REQUEST['sort']).'&order='.urlencode($_REQUEST['order']));
//Ok..lets roll...create the actual query
$qstr.='&order='.($order=='DESC'?'ASC':'DESC');
$query="$select $from $where GROUP BY staff.staff_id ORDER BY $order_by LIMIT ".$pageNav->getStart().",".$pageNav->getLimit();
//echo $query;
?>
<div class="page-header">
    <h1>Staff <small>Directory</small></h1>
</div>                  
<?php
$res=db_query($query);
if($res && ($num=db_num_rows($res)))        
    $showing=$pageNav->showing();
else
    $showing='No staff members found!';
?>
<div class="row-fluid">
            <div class="span12">                    
                <div class="head clearfix">
                    <div class="isw-grid"></div>
                    <h1>Staff Members</h1>                               
                </div>
                <div class="block-fluid table-sorting clearfix">
                    <table cellpadding="0" cellspacing="0" width="100%" class="table" id="tSortable_6">                    
                        <thead>
                            <tr>
                                <th width="15%">Name</th>
                                <th width="15%">Department</th>
                                <th width="14%">Email Address</th>
                                <th width="14%">Phone Number</th>                                  
                                <th width="14%">Phone Ext</th>
                                <th width="14%">Mobile Number</th>     
                             </tr>
                        </thead>
                        <tbody role="alert" aria-live="polite" aria-relevant="all">
                        <?php
                        if($res && db_num_rows($res)):
                        $ids=($errors && is_array($_POST['ids']))?$_POST['ids']:null;
                        $color = 1;
                        while ($row = db_fetch_array($res)) {
                        if(($color%2) == 0)
                        $bg = 'odd';
                        else
                        $bg = 'even'; ?>
                        <tr id="<?php echo $row['staff_id']; ?>" >
                        <td class="nohover">&nbsp;<?php echo Format::htmlchars($row['name']); ?></td>
                        <td class="nohover">&nbsp;<?php echo Format::htmlchars($row['dept']); ?></td>
                        <td class="nohover">&nbsp;<?php echo Format::htmlchars($row['email']); ?></td>
                        <td class="nohover">&nbsp;<?php echo Format::phone($row['phone']); ?></td>
                        <td class="nohover">&nbsp;<?php echo $row['phone_ext']; ?></td>
                        <td class="nohover">&nbsp;<?php echo Format::phone($row['mobile']); ?></td>
                        </tr>
                        <?php
                        $color ++;
                        } //end of while.
                        endif; ?>        
                        </tbody>                              
                    </table>
                </div>
     </div>                      
        </div>   
<div class="dr"><span></span></div>        
   </div><!--WorkPlace End-->  
   </div>   
