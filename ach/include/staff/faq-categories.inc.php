<?php
if(!defined('OSTSTAFFINC') || !$thisstaff) die('Access Denied');
?>




<div class="page-header">
<h1>Frequently Asked <small>Questions And Answers</small></h1>
</div> 

<?php /*?><div class="row-fluid">
<div class="span12">
<div class="head clearfix">
<div class="isw-grid"></div>
<h1>Search</h1>
</div>
<div class="block-fluid table-sorting clearfix">
<form id="kbSearch" action="kb.php" method="post">
   <input type="hidden" name="a" value="search">
<table class="table" width="100%" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<th style="padding-top:12px;" width="12%">Keyword</th>
<td width="20%">
<input id="query" type="text" size="20" name="q" value="<?php echo Format::htmlchars($_REQUEST['q']); ?>">
</td>
<th style="padding-top:12px;" width="10%">Categories</th>
<td style="background-color:white;" width="20%">
<select name="cid" id="cid">
            <option value="">&mdash; All Categories &mdash;</option>
            <?php
            $sql='SELECT category_id, name, count(faq.category_id) as faqs '
                .' FROM '.FAQ_CATEGORY_TABLE.' cat '
                .' LEFT JOIN '.FAQ_TABLE.' faq USING(category_id) '
                .' GROUP BY cat.category_id '
                .' HAVING faqs>0 '
                .' ORDER BY cat.name DESC ';
            if(($res=db_query($sql)) && db_num_rows($res)) {
                while($row=db_fetch_array($res))
                    echo sprintf('<option value="%d" %s>%s (%d)</option>',
                            $row['category_id'],
                            ($_REQUEST['cid'] && $row['category_id']==$_REQUEST['cid']?'selected="selected"':''),
                            $row['name'],
                            $row['faqs']);
            }
            ?>
        </select>
</td>
<th style="padding-top:12px;" width="10%">Help Topics</th>
<td style="background-color:white;" width="20%">
<select name="topicId" id="topic-id">
            <option value="">&mdash; All Help Topics &mdash;</option>
            <?php
            $sql='SELECT ht.topic_id, CONCAT_WS(" / ", pht.topic, ht.topic) as helptopic, count(faq.topic_id) as faqs '
                .' FROM '.TOPIC_TABLE.' ht '
                .' LEFT JOIN '.TOPIC_TABLE.' pht ON (pht.topic_id=ht.topic_pid) '
                .' LEFT JOIN '.FAQ_TOPIC_TABLE.' faq ON(faq.topic_id=ht.topic_id) '
                .' GROUP BY ht.topic_id '
                .' HAVING faqs>0 '
                .' ORDER BY helptopic';
            if(($res=db_query($sql)) && db_num_rows($res)) {
                while($row=db_fetch_array($res))
                    echo sprintf('<option value="%d" %s>%s (%d)</option>',
                            $row['topic_id'],
                            ($_REQUEST['topicId'] && $row['topic_id']==$_REQUEST['topicId']?'selected="selected"':''),
                            $row['helptopic'], $row['faqs']);
            }
            ?>
        </select>
</td>
</tr>
<tr>
<tr>
<tr>
<td style="background-color: #FFFFFF;text-align: right;" colspan="6" align="right">
<input class="btn" id="searchSubmit" type="submit" value="Search">
</td>
</tr>
</tbody>
</table>
</form>
</div>
</div>
</div><?php */?>

<div class="row-fluid">

                    <div class="span12">                    
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Sortable table</h1>                               
                        </div>
                        <div class="block-fluid table-sorting clearfix">
                            <table cellpadding="0" cellspacing="0" width="100%" class="table" id="tSortable">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" name="checkall"/></th>
                                        <th width="25%">ID</th>
                                        <th width="25%">Category (Type)</th>
                                        <th width="25%">Description</th>
                                        <th width="25%">Action</th>                                    
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
if($_REQUEST['q'] || $_REQUEST['cid'] || $_REQUEST['topicId']) { //Search.
    $sql='SELECT faq.faq_id, question, ispublished, count(attach.file_id) as attachments, count(ft.topic_id) as topics '
        .' FROM '.FAQ_TABLE.' faq '
        .' LEFT JOIN '.FAQ_TOPIC_TABLE.' ft ON(ft.faq_id=faq.faq_id) '
        .' LEFT JOIN '.FAQ_ATTACHMENT_TABLE.' attach ON(attach.faq_id=faq.faq_id) '
        .' WHERE 1 ';

    if($_REQUEST['cid'])
        $sql.=' AND faq.category_id='.db_input($_REQUEST['cid']);

    if($_REQUEST['topicId'])
        $sql.=' AND ft.topic_id='.db_input($_REQUEST['topicId']);

    if($_REQUEST['q']) {
        $sql.=" AND question LIKE ('%".db_input($_REQUEST['q'],false)."%') 
                 OR answer LIKE ('%".db_input($_REQUEST['q'],false)."%') 
                 OR keywords LIKE ('%".db_input($_REQUEST['q'],false)."%') ";
    }

    $sql.=' GROUP BY faq.faq_id';

    echo "<div><strong>Search Results</strong></div><div class='clear'></div>";
    if(($res=db_query($sql)) && db_num_rows($res)) {
       ?>
   
    <?php 
        while($row=db_fetch_array($res)) {
			?>
     <tr>
     <td><input type="checkbox" name="checkbox"/></td>
    <td><?php echo $row['category_id']; ?></td>
    <td><a href="kb.php?cid=<?php echo $row['category_id']; ?>"><?php echo $row['name'].'('.$row['faqs'].')'.($row['ispublic']?'Public':'Internal'); ?></a></td>
    <td><?php echo Format::safe_html($row['description']); ?></td>
    <td></td>
    </tr>
    <?php
        }?>
    
    <?php 
    } else {
        echo '<strong class="faded">The search did not match any FAQs.</strong>';
    }
} else { //Category Listing.
    $sql='SELECT cat.category_id, cat.name, cat.description, cat.ispublic, count(faq.faq_id) as faqs '
        .' FROM '.FAQ_CATEGORY_TABLE.' cat '
        .' LEFT JOIN '.FAQ_TABLE.' faq ON(faq.category_id=cat.category_id) '
        .' GROUP BY cat.category_id '
        .' ORDER BY cat.name';
    if(($res=db_query($sql)) && db_num_rows($res)) { ?>
    
    <?php    while($row=db_fetch_array($res)) { ?>
    <tr>
    <td><input type="checkbox" name="checkbox"/></td>
    <td><?php echo $row['category_id']; ?></td>
    <td><a href="kb.php?cid=<?php echo $row['category_id']; ?>"><?php echo $row['name'].'('.$row['faqs'].')'.($row['ispublic']?'Public':'Internal'); ?></a></td>
    <td><?php echo Format::safe_html($row['description']); ?></td>
    <td></td>
    </tr>
    <?php }?>
    
    <?php 
	} 
	else {
        echo 'NO FAQs found';
    }
}
?>                                </tbody>
                            </table>
                        </div>
                    </div>                                
                </div>
               </div></div>