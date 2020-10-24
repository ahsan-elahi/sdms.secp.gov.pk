<?php
require('staff.inc.php');
if($_REQUEST['id']){
$id=$_REQUEST['id'];
$sql="SELECT * FROM sdms_ticket_thread WHERE id='$id'";
$res=db_query($sql);
$row=db_fetch_array($res);

$sql_ticket="SELECT * FROM sdms_ticket WHERE ticket_id='".$row['ticket_id']."'";
$res_ticket=db_query($sql_ticket);
$row_ticket=db_fetch_array($res_ticket);

?>

<form action="tickets.php?id=<?php echo $row['ticket_id']; ?>&t_id=<?php echo $id; ?>" method="post" id="thread-options" name="thread-options">
        <?php csrf_token(); ?>
        <fieldset class="notes">
        <input type="hidden" name="a" value="edit_thread"><br>

            <label for="notes" style="width:127px;">Subject:</label>
            <input type="text" id="sub" name="sub" size="33" value="<?php echo $row_ticket['subject']; ?>" style="height:30px;font-size:12px;">
        </fieldset>
        <fieldset>
            <label for="psize" style="width:127px;">Comments:</label>
            <textarea name="comments" style="resize:none;"  cols="25" rows="5" style="font-size:12px;"><?php echo $row['body']; ?></textarea>
        </fieldset>
    
    </form>
    <div class="clear"></div>
<?php
}
?>