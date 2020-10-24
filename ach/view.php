<?php
require_once('client.inc.php');
/*if( $_SESSION['_client']['token'])
{
//unset( $_SESSION['_client']['token']);
@header('Location: tickets.php?task=web&id='.$_SESSION['_client']['key']);
}*/
//unset($_SESSION['_client']['userID']);
//If the user is NOT logged in - try auto-login (if params exists).
if(!$thisclient || !$thisclient->isValid()) {
    // * On login Client::login will redirect the user to tickets.php view.
    // * See TODO above for planned multi-view.
    $user = null;
    if($_GET['t'] && $_GET['e'] && $_GET['a'])
    $user = Client::login($_GET['t'], $_GET['e'], $_GET['a'], $errors);
    //XXX: For now we're assuming the user is the ticket owner
    // (multi-view based on auth token will come later).
    if($user && $user->getTicketID()==trim($_GET['t']))
        @header('Location: tickets.php?id='.$user->getTicketID());
}

require('tickets.php');
?>
