<?php
if(!defined('OSTCLIENTINC') || !is_object($ticket)) die('Kwaheri rafiki!');
//Please customize the message below to fit your organization speak!
?>
<div class="container-fluid">
	<div class="container" style="background-color: white">
		<div class="row">
				<div class="col-xs-12">
					<hr>
				</div>
				<div class="col-xs-12">
					<h3  style="float:right;">صاحب <?php echo Format::htmlchars($ticket->getName()); ?></h3>
				</div>
				<div class="col-xs-12">
                <p style="font-style: italic;float:right;">
                
ہمیں آپ کا سوال  موصول ہو گیا ہے اور اسے متعلقہ شعبے میں بھجوا دیا گیا ہے۔ آپ سوال نمبر <?php echo  $ticket->number; ?> اور پِن کوڈ <?php echo $ticket->ticketpin;?> کے ذریعے اپنے سوال  کے بارے میں تازہ ترین معلومات حاصل کر سکتے ہیں۔ آپ سے جلد رابطہ کر لیا جائے گا۔
<br><span style="float:right;">شکریہ</span><br>
<span style="float:right;">ایس ای سی پی سروس ڈیسک</span><br>
<span style="float:right;">یو اے این:080088008</span><br>
<span style="float:right;">ای میل:  queries@secp.gov.pk</span>
  </p>
				</div>
				<div class="col-xs-12">
						  <?php /*if($cfg->autoRespONNewTicket()){ ?>

    <p>An email with the Complaint number has been sent to <b>UCN: <?php echo  $ticket->number; ?> Pincode : <?php echo $ticket->ticketpin;?></b>.

        You'll need the Complaint number along with your email to view status and progress online. 

    </p>

    <p>

     If you wish to send additional comments or information regarding same issue, please follow the instructions on the email.

    </p>

    <?php }*/ ?>
				</div>
		</div>
	</div>
</div>