<?php

$msgtemplates=Template::message_templates();

$info=Format::htmlchars(($errors && $_POST)?$_POST:$_REQUEST);

$info['tpl']=($info['tpl'] && $msgtemplates[$info['tpl']])?$info['tpl']:'ticket_autoresp';

$tpl=$msgtemplates[$info['tpl']];

$info=array_merge($template->getMsgTemplate($info['tpl']),$info);



?>

<div class="page-header"><h1>Email Template Message -<small><span><?php echo $template->getName(); ?></span></small></h1></div>  

<form method="get" action="templates.php">

    <input type="hidden" name="id" value="<?php echo $template->getId(); ?>">

    <input type="hidden" name="a" value="manage">

<!--section divided into row span-->
<div class="row-fluid">  <!--main row-fluid div start-->

<!--first section start-->
<div class="span12">
        <div class="block-fluid ucard">
            <div class="info">                                                                
                <ul class="rows">
                   <!--header start-->
                    <li class="heading"><div class="isw-users"></div><em><strong>Message Template:  Auto-response for the user.</strong></em></li>
                   <!-- header end-->
                    <li>
                        <div class="title">Message Template:</div>
                        <div class="text">
                         <select id="tpl_options" name="tpl" style="width:300px;">

        <option value="">&mdash; Select Setting Group &mdash;</option>
                          <?php

        foreach($msgtemplates as $k=>$v) {

            $sel=($info['tpl']==$k)?'selected="selected"':'';

            echo sprintf('<option value="%s" %s>%s</option>',$k,$sel,$v['name']);

        }

        ?>

    </select>

              <input type="submit" value="Go" class="btn">

    &nbsp;&nbsp;&nbsp;<font color="red"><?php echo $errors['tpl']; ?></font>
           
                        </div>
                    </li> 
                   
                   
                    
                                         
                </ul>                                                      
           </div>                        
        </div>
</div>
<!--first section End-->

</div>    <!--main row-fluid div End-->

</form>

<form action="templates.php?id=<?php echo $template->getId(); ?>" method="post" id="save">

<?php csrf_token(); ?>

<input type="hidden" name="id" value="<?php echo $template->getId(); ?>">

<input type="hidden" name="tpl" value="<?php echo $info['tpl']; ?>">

<input type="hidden" name="a" value="manage">

<input type="hidden" name="do" value="updatetpl">



<!--section divided into row span-->
<div class="row-fluid">  <!--main row-fluid div start-->

<!--first section start-->
<div class="span12">
        <div class="block-fluid ucard">
            <div class="info">                                                                
                <ul class="rows">
                   <!--header start-->
                    <li class="heading"><div class="isw-users"></div><?php echo Format::htmlchars($tpl['desc']); ?><em>Subject and body required.  <a class="tip" href="ticket_variables.txt">Supported Variables</a>.</em></li>
                   <!-- header end-->
                    <li>
                        <div class="title"><strong>Message Subject:</strong> <em>Email message subject</em> <font class="error">*&nbsp;<?php echo $errors['subj']; ?></font><br>
                        </div>
                        <div class="text">
                         <input type="text" name="subj" size="60" value="<?php echo $info['subj'];                          ?>" style="width:288px;">
                        </div>
                    </li> 
                    <li>
                        <div class="title"><strong>Message Body:</strong> <em>Email message body.</em> <font class="error">*&nbsp;<?php echo $errors['body']; ?></font><br>
                        </div>
                        <div class="text"> <textarea name="body" cols="21" rows="16" style="width:98%;" wrap="soft" ><?php echo $info['body']; ?></textarea>
</div>
                    </li>
                   
                    
                                         
                </ul>                                                      
           </div>                        
        </div>
</div>
<!--first section End-->

</div>    <!--main row-fluid div End-->


<!--footer start-->
<div class="row-fluid">  
<div class="span12">
    
    <div class="footer tar">
    <input class="btn" type="submit" name="submit" value="Save Changes">
    <input class="btn" type="reset" name="reset" value="Reset Changes">
    <input class="btn" type="button" name="cancel" value="Cancel Changes" onclick='window.location.href="templates.php?id=<?php echo $template->getId(); ?>"'>
    </div>  

</div>
</div>
<!--footer end-->
</form>
</div>
</div>
