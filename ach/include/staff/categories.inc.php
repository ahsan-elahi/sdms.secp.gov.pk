<?php
if(!defined('OSTSCPINC') || !$thisstaff) die('Access Denied');

$qstr='';
$sql='SELECT cat.category_id, cat.name, cat.ispublic, cat.updated, count(faq.faq_id) as faqs '.
     ' FROM '.FAQ_CATEGORY_TABLE.' cat '.
     ' LEFT JOIN '.FAQ_TABLE.' faq ON (faq.category_id=cat.category_id) ';
$sql.=' WHERE 1';
$sortOptions=array('name'=>'cat.name','type'=>'cat.ispublic','faqs'=>'faqs','updated'=>'cat.updated');
$orderWays=array('DESC'=>'DESC','ASC'=>'ASC');
$sort=($_REQUEST['sort'] && $sortOptions[strtolower($_REQUEST['sort'])])?strtolower($_REQUEST['sort']):'name';
//Sorting options...
if($sort && $sortOptions[$sort]) {
    $order_column =$sortOptions[$sort];
}
$order_column=$order_column?$order_column:'cat.name';

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

$total=db_count('SELECT count(*) FROM '.FAQ_CATEGORY_TABLE.' cat ');
$page=($_GET['p'] && is_numeric($_GET['p']))?$_GET['p']:1;
$pageNav=new Pagenate($total, $page, PAGE_LIMIT);
$pageNav->setURL('categories.php',$qstr.'&sort='.urlencode($_REQUEST['sort']).'&order='.urlencode($_REQUEST['order']));
$qstr.='&order='.($order=='DESC'?'ASC':'DESC');
$query="$sql GROUP BY cat.category_id ORDER BY $order_by LIMIT ".$pageNav->getStart().",".$pageNav->getLimit();
$res=db_query($query);
if($res && ($num=db_num_rows($res)))
    $showing=$pageNav->showing().' categories';
else
    $showing='No FAQ categories found!';

?>

<div class="page-header"><h1>FAQ <small>Categories</small></h1></div> 
                      
<form action="categories.php" method="POST" name="cat">
 <?php csrf_token(); ?>
 <input type="hidden" name="do" value="mass_process" >
 <input type="hidden" id="action" name="a" value="" >
 
    <div class="row-fluid">
<div class="span3" style="float:right">
    <p align="right" style="float:right;">
     <a href="categories.php?a=add" class="Icon newCategory">
     <button class="btn" type="button"><i class="icon-plus-sign"></i>Add New Category</button></a>                              
    </p>             
</div>
</div>
    <div class="row-fluid">
         <div class="span12">                    
            <div class="head clearfix">
                <div class="isw-grid"></div>
                <h1><?php echo 'FAQ Categories'; ?></h1>                               
            </div>
            
            <div class="block-fluid table-sorting clearfix">
                <table cellpadding="0" cellspacing="0" width="100%" class="table" id="tSortable_5">
                    <thead>
                        <tr>
                        <th><input type="checkbox" name="checkall"/></th>
                        <th>Name</th>                          
                        <th>Type</th>
                        <th>FAQs</th>
                        <th>Last Updated</th>     
                        </tr>
                    </thead>
                    <tbody role="alert" aria-live="polite" aria-relevant="all">
                    <?php
        $total=0;
        $ids=($errors && is_array($_POST['ids']))?$_POST['ids']:null;
        if($res && db_num_rows($res)):
		$color = 1;
            while ($row = db_fetch_array($res)) {
				if(($color%2) == 0)
				$bg = 'odd';
				else
				$bg = 'even';
                $sel=false;
                if($ids && in_array($row['category_id'],$ids))
                    $sel=true;
                
                $faqs=0;
                if($row['faqs'])
                    $faqs=sprintf('<a href="faq.php?cid=%d">%d</a>',$row['category_id'],$row['faqs']);
                ?>
             <tr id="<?php echo $row['category_id']; ?>">
                <td align="center" class="nohover">
                  <input type="checkbox" name="ids[]" value="<?php echo $row['category_id']; ?>" class="ckb" <?php echo $sel?'checked="checked"':''; ?>>
                </td>
                <td><a href="categories.php?id=<?php echo $row['category_id']; ?>"><?php echo Format::truncate($row['name'],200); ?></a>&nbsp;</td>
                <td><?php echo $row['ispublic']?'<b>Public</b>':'Internal'; ?></td>
                <td style="text-align:right;padding-right:25px;"><?php echo $faqs; ?></td>
                <td>&nbsp;<?php echo Format::db_datetime($row['updated']); ?></td>
            </tr>
            <?php
			$color++;
            } //end of while.
        endif; ?>
                    </tbody>                              
                </table>
            </div>
         </div>                      
    </div>   
    <div class="row-fluid"> 
        <div class="span6" style="width:96.718%;">
         <?php if($res && $num):?>                
            <p style="text-align:center;" id="actions">
                
                <input class="btn btn-primary" type="submit" name="make_public" value="Make Public" id="button">
                
                <input class="btn btn-inverse" type="submit" name="make_private" value="Make Internal" id="button" >                    
                
                <input class="btn btn-danger" type="submit" name="delete" value="Delete" id="button">
                      
            </p>   
            <?php endif;?>
        </div> 
    </div>                     
<div class="dr"><span></span></div>   
</form>
              
      <div style="display:none;" class="dialog" id="confirm-action">
    <h3>Please Confirm</h3>
    <a class="close" href="">&times;</a>
    <hr/>
    <p class="confirm-action" style="display:none;" id="make_public-confirm">
        Are you sure want to make selected categories <b>public</b>?
    </p>
    <p class="confirm-action" style="display:none;" id="make_private-confirm">
        Are you sure want to make selected categories <b>private</b> (internal)?
    </p>
    <p class="confirm-action" style="display:none;" id="delete-confirm">
        <font color="red"><strong>Are you sure you want to DELETE selected categories?</strong></font>
        <br><br>Deleted entries CANNOT be recovered, including any associated FAQs.
    </p>
    <div>Please confirm to continue.</div>
    <hr style="margin-top:1em"/>
    <p class="full-width">
        <span class="buttons" style="float:left">
            <input type="button" value="No, Cancel" class="close">
        </span>
        <span class="buttons" style="float:right">
            <input type="button" value="Yes, Do it!" class="confirm">
        </span>
     </p>
    <div class="clear"></div>
</div>           
   </div><!--WorkPlace End-->  
   </div> 



