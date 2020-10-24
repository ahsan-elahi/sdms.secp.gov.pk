<?php
require('client.inc.php');
define('SOURCE','Web');
$sql="Select * from sdms_companis where company_title = '".$_REQUEST['company_title']."'";
$res = db_query($sql);
if(db_num_rows($res)>0){
$row_result = db_fetch_row($res);?>
<input type="text" name="cr_cro" class="form-control backrnd" value="<?php echo $row_result[1]; ?>" required  readonly>
<?php
}else{?>
<input type="text" name="cr_cro" class="form-control backrnd" value=""  readonly>
<?php }?>