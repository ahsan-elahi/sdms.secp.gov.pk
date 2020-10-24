<?php
if(!defined('OSTCLIENTINC') || !is_object($ticket)) die('Kwaheri rafiki!');
//Please customize the message below to fit your organization speak!
?>
<div class="container-fluid">
	<div class="container" style="background-color: white">
		<div class="row" style="background:url(assets/images/thankyou.png);min-height: 565px;">
				<div class="col-xs-12">
					<hr>
				</div>
				<div class="col-xs-12">
					<h3>Dear <?php echo Format::htmlchars($ticket->getName()); ?></h3>
				</div>
				<div class="col-xs-12">
                <p style="font-style: italic;">
                We have received your query and forwarded it to the relevant department. You can track your query by the following query number <?php echo  $ticket->number; ?> and pincode is <?php echo $ticket->ticketpin;?>.  You should expect a response shortly.<br>
                Thank you for your patience.<br>
                Regards,<br>
                SECP Service Desk<br>
                UAN: 080088008<br>
                Email:  queries@secp.gov.pk
                </p>
				</div>
				<div class="col-xs-12" >
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