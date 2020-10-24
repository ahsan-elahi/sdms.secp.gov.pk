<?php
if(!defined('OSTSTAFFINC') || !$category || !$thisstaff) die('Access Denied');

?>

<div class="page-header">
  <h1><?php echo $category->getName() ?> <small>FAQ</small></h1>
</div>
<div class="row-fluid">
<div class="span3" style="float:right">
    <p align="right" style="float:right;">
     <a href="faq.php?cid=<?php echo $_REQUEST['cid']; ?>&a=add" class="Icon newCategory">
     <button class="btn" type="button"><i class="icon-plus-sign"></i>Add New FAQ</button></a>                              
    </p>             
</div>
</div>
<div class="row-fluid">
<div class="span12"> 
<div class="head clearfix">
      <div class="isw-grid"></div>
      <h1><?php echo Format::safe_html($category->getDescription()); ?></h1>
    </div>    
   <!--<div class="headInfo">
        <div class="input-append">
        <input type="text" name="text" placeholder="Keyword.." id="widgetInputMessage" class="faqSearchKeyword"/>
        <button class="btn btn-success" id="faqSearch" type="button">Search</button>
        </div>                                           
    	<div class="arrow_down"></div>
    </div>-->
    <div class="block-fluid">
        <!--<div class="toolbar clearfix">
            <div class="left">
            <div id="faqSearchResult" class="note"></div>
            </div>
            <div class="right">
                    <div class="btn-group">
                    <button class="btn btn-small tip" id="faqOpenAll" title="Open all"><span class="icon-chevron-down icon-white"></span></button>
                    <button class="btn btn-small tip" id="faqCloseAll" title="Close all"><span class="icon-chevron-up icon-white"></span></button>
                    <button class="btn btn-small tip" id="faqRemoveHighlights" title="Remove highlights"><span class="icon-remove icon-white"></span></button>
                    </div>
                </div>
            </div>-->                                                       
        <div class="faq">
                <?php 
		$sql='SELECT faq.faq_id, question,answer, ispublished, count(attach.file_id) as attachments '
    .' FROM '.FAQ_TABLE.' faq '
    .' LEFT JOIN '.FAQ_ATTACHMENT_TABLE.' attach ON(attach.faq_id=faq.faq_id) '
    .' WHERE faq.category_id='.db_input($category->getId())
    .' GROUP BY faq.faq_id';
if(($res=db_query($sql)) && db_num_rows($res)) {
    while($row=db_fetch_array($res)) {
		?>
        <div class="item" id="faq-<?php echo $row['faq_id']; ?>">
                <div class="title"><?php echo $row['question']; ?> (<?php echo $row['ispublished']?'Published':'Internal'; ?>)</div>
                <div class="text"><p><?php echo $row['answer'];?></p></div>
                </div>
        <?php
	 }
}else {
    echo '<strong>Category does not have FAQs</strong>';
}
		?>
                
                
                </div>                            
            </div>
    </div>
</div>                    

</div></div>