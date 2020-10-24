<?php
//TODO: Make it DB based!
class Cron {

    function MailFetcher() {
        require_once(INCLUDE_DIR.'class.mailfetch.php');
        MailFetcher::run(); //Fetch mail..frequency is limited by email account setting.
    }

    function TicketMonitor() {
        require_once(INCLUDE_DIR.'class.ticket.php');
        require_once(INCLUDE_DIR.'class.lock.php');
        Ticket::checkOverdue(); //Make stale tickets overdue
        TicketLock::cleanup(); //Remove expired locks 
    }

    function PurgeLogs() {
        global $ost;
        if($ost) $ost->purgeLogs();
    }

    function CleanOrphanedFiles() {
        require_once(INCLUDE_DIR.'class.file.php');
        AttachmentFile::deleteOrphans();
    }

    function run(){ //called by outside cron NOT autocron
        self::MailFetcher();
        self::TicketMonitor();
        self::PurgeLogs();
        self::CleanOrphanedFiles();
    }
}
?>
