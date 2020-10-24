<?php

if(!defined('OSTCLIENTINC') || !is_object($ticket)) die('Kwaheri rafiki!');

//Please customize the message below to fit your organization speak!

?>



    <?php echo Format::htmlchars($ticket->getName()); ?>,<br>

    <p>

     Thank you for contacting us.<br>

     A support Complaint request has been created and a representative will be getting back to you shortly if necessary.</p>

          

    <?php if($cfg->autoRespONNewTicket()){ ?>

    <p>An email with the Complaint number has been sent to <b>UCN: <?php echo  $ticket->number; ?> Pincode : <?php echo $ticket->ticketpin;?></b>.

        You'll need the Complaint number along with your email to view status and progress online. 

    </p>

    <p>

     If you wish to send additional comments or information regarding same issue, please follow the instructions on the email.

    </p>

    <?php } ?>

    <p>Support Team </p>
    <br />
<img src="images/thank.png" width="100" height="100" />
