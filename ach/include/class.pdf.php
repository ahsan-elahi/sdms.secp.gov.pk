<?php 
define('THIS_DIR', str_replace('\\\\', '/', realpath(dirname(__FILE__))) . '/'); //Include path..
define('FPDF_DIR', THIS_DIR . 'fpdf/');
define('FPDF_FONTPATH', FPDF_DIR . 'font/'); //fonts directory.
require (FPDF_DIR . 'fpdf.php');
class Ticket2PDF extends FPDF
{
	var $includenotes = false;
	var $pageOffset = 0;
    var $ticket = null;
	
	function Ticket2PDF($ticket, $psize='Letter', $notes=false) {
        global $thisstaff;
        parent::FPDF('P', 'mm', $psize);
        $this->ticket = $ticket;
        $this->includenotes = $notes;
        $this->SetMargins(10,10,10);
		$this->AliasNbPages();
		$this->AddPage();
		$this->cMargin = 3;
        $this->_print();
	}

    function getTicket() {
        return $this->ticket;
    }

	//report header...most stuff are hard coded for now...
	function Header() {
        global $cfg;
		//Common header
        $this->Ln(2);
		$this->SetFont('Times', 'B', 16);
		$this->Image(FPDF_DIR . 'print-logo.jpg', null, 10, 0, 20);
		$this->SetX(200, 15);
		$this->Cell(0, 15, '', 0, 1, 'R', 0);
		//$this->SetY(40);
        $this->SetX($this->lMargin);
        $this->Cell(0, 3, '', "B", 2, 'L');
        $this->SetFont('Arial', 'I',10);
        //$this->Cell(0, 5, 'Generated on '.Format::date($cfg->getDateTimeFormat(), Misc::gmtime(), $_SESSION['TZ_OFFSET'], $_SESSION['TZ_DST']), 0, 0, 'L');
       // $this->Cell(0, 5, 'Date & Time based on GMT '.$_SESSION['TZ_OFFSET'], 0, 1, 'R');
	     $this->Cell(0, 0, '', 0, 1, 'R');
		$this->Ln(5);
	}

	//Page footer baby
	function Footer() {
        global $thisstaff;

		$this->SetY(-15);
        $this->Cell(0, 2, '', "T", 2, 'L');
		$this->SetFont('Arial', 'I', 9);
		//$this->Cell(0, 7, 'Complaint #'.$this->getTicket()->getNumber().' printed by '.$thisstaff->getUserName().' on '.date('r'), 0, 0, 'L');
		$this->Cell(0, 7, 'Complaint #'.$this->getTicket()->getNumber().' printed by '.$thisstaff->getUserName().'', 0, 0, 'L');
		$this->Cell(0, 7, 'Page ' . ($this->PageNo() - $this->pageOffset), 0, 0, 'R');
	}

    function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='') {
        parent::Cell($w, $h, $this->_utf8($txt), $border, $ln, $align, $fill, $link);
    }

    function WriteText($w, $text, $border) {

        $this->SetFont('Times','',11);
        $this->MultiCell($w, 5, $text, $border, 'L');

    }

    function _utf8($text) {

        if(function_exists('iconv'))
            return iconv('UTF-8', 'windows-1252', $text);

        return utf8_encode($text);
    }

    function _print() {
		global $thisstaff;
        if(!($ticket=$this->getTicket()))
            return;

        $w =(($this->w/2)-$this->lMargin);
        $l = 35;
        $c = $w-$l;
		
$x = $this->GetX();
$y = $this->GetY();
//Subject Section and Complaint #0000 
$this->SetFont('Arial', 'B', 11);
$this->Cell($l-5, 5, 'Complaint Title:', 0, 0, 'L');
$this->SetTextColor(10, 86, 142);
$col1=$ticket->getSubject();
$this->MultiCell($c+55, 5, $col1, 0, 'L');
$this->SetXY($x + $c+85, $y);

$this->SetFont('Arial', 'B', 11);
$this->SetTextColor(0);
$this->Cell($l-10, 5, 'Complaint #', 0, 0, 'L');
$this->SetTextColor(10, 86, 142);
$col2=$ticket->getNumber();
$this->MultiCell($c, 5, $col2, 0,'L');		
		
		$this->Ln(10);
		//Complainant Profile  and  ComplaintParticulars
		$this->SetDrawColor(220, 220, 220);
		$this->SetFillColor(210, 226, 139);
		$this->SetFont('Arial', 'B', 11);
		$this->cMargin = 3;
		$this->SetTextColor('');
		$this->Cell($w-1, 7,'Applicant Info', 1, 0, 'L',true);
		$this->SetTextColor(0);
		
		$this->Cell(2, 7, ' ', 0, 0, 'L');
		
		$this->SetFont('Arial', 'B', 11);
		$this->SetTextColor('');
		$this->Cell($w-1, 7,'Complaint Info', 1, 0, 'L',true);
		$this->Ln(7);
		$this->cMargin = 3;
		$this->SetTextColor(0);
		$this->SetDrawColor(220, 220, 220);
		$this->SetFillColor(244, 250, 255);			
		//Name and Status
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l, 7, 'Name', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c-1, 7, $ticket->getName(), 1, 0, 'L', true);
		
		$this->Cell(2, 7, ' ', 0, 0, 'L');
		
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l-1, 7, 'Status', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c, 7,  $ticket->complaint_status(), 1, 1, 'L', true);
		//CNIC and Priority
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l, 7, 'CNIC/NICOP/PSPT', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c-1, 7,  $ticket->getNic(), 1, 0, 'L', true);
		
		$this->Cell(2, 7, ' ', 0, 0, 'L');
		
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l-1, 7, 'Priority', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c, 7, $ticket->getPriority(), 1, 1, 'L', true);
		//Mobile and Complaint Date and CNIC
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l, 7, 'Mobile', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c-1, 7, $ticket->getPhoneNumber(), 1, 0, 'L', true);	
		
		$this->Cell(2, 7, ' ', 0, 0, 'L');			
		
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l-1, 7, 'Dept', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c, 7, $ticket->getDeptName(), 1, 1, 'L', true);
		//Email and Lodged Date/Time
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l, 7, 'Email', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c-1, 7, $ticket->getEmail(), 1, 0, 'L', true);		
		
		$this->Cell(2, 7, ' ', 0, 0, 'L');			
		
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l-1, 7, 'Lodged Date/Time', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c, 7, Format::db_datetime($ticket->getCreateDate()), 1, 1, 'L', true);
		//Country and Assigned To
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l, 7, 'Country', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c-1, 7, $ticket->getDistrict(), 1, 0, 'L', true);
		
		$this->Cell(2, 7, ' ', 0, 0, 'L');			
		
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l-1, 7, 'Assigned To', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c, 7, Format::htmlchars(implode('/', $ticket->getAssignees())), 1, 1, 'L', true);
		//Province and Complaint Category:
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l, 7, 'Province', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c-1, 7, $ticket->getTehsil(), 1, 0, 'L', true);		
		
		$this->Cell(2, 7, ' ', 0, 0, 'L');			
		
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l-1, 7, 'Complaint Category', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c, 7, $ticket->getHelpTopic(), 1, 1, 'L', true);
		//City and Source
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l, 7, 'City', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c-1, 7, $ticket->getAgencyTehsilTitle(), 1, 0, 'L', true);		
		
		$this->Cell(2, 7, ' ', 0, 0, 'L');			
		
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l-1, 7, 'Source', 1, 0, 'L', true);
		$this->SetFont('');
		$source = ucfirst($ticket->getSource());
		if($ticket->getIP())
		$source.='  ('.$ticket->getIP().')'; 
		$this->Cell($c, 7,$source , 1, 1, 'L', true);
		//Address and Null
		$x = $this->GetX();
		$y = $this->GetY();
		$this->SetFont('Arial', 'B', 11);
		$this->MultiCell($l, 7, 'Postal Address', 1, 'L', true);
		$this->SetXY($x + $c-25, $y);
		$this->SetFont('');
		$this->MultiCell($c-1 , 7, $ticket->getApplicant_Address(), 1, 'L', true);
		
	
		
    if($ticket->getDeptId()==2){
			$this->Ln(5);
		//Department Info
		$this->SetFont('Arial', 'B', 11);
		$this->cMargin = 3;
		$this->SetTextColor(0);
		$this->SetDrawColor(220, 220, 220);
        $this->SetFillColor(210, 226, 139);
		$this->Cell($w-1, 7,'Department Info', 1, 1, 'L',true);
		$this->SetTextColor(0);
		$this->SetDrawColor(220, 220, 220);
		$this->SetFillColor(244, 250, 255);
		
		$this->cMargin = 3;
		
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l, 7, 'Department', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c-1, 7, Format::htmlchars($ticket->getDeptName()) , 1, 1, 'L', true);
		
		$this->cMargin = 3;
		
		
    $sql_securities_markets="Select * from sdms_ticket_capital_markets where complaint_id ='".$ticket->getId()."'";
    $res_securities_markets=mysql_query($sql_securities_markets);
    $row_securities_markets=mysql_fetch_array($res_securities_markets);
   
		if($row_securities_markets['cm_type']!=''){	
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l, 7, 'Type', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c-1, 7,  $row_securities_markets['cm_type'], 1, 1, 'L', true);
		$this->cMargin = 3;
		}
		if($row_securities_markets['cm_broker_title']!=''){
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l, 7, 'Brokers List', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c-1, 7,  $row_securities_markets['cm_broker_title'], 1, 1, 'L', true);
		$this->cMargin = 3;	
		} 
		if($row_securities_markets['cm_broker_agent']!=''){ 
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l, 7, 'Agent List', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c-1, 7,  $row_securities_markets['cm_broker_agent'], 1, 1, 'L', true);
		$this->cMargin = 3;	
		}
		if($row_securities_markets['cm_folio_no']!=''){
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l, 7, 'Folio No', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c-1, 7,  $row_securities_markets['cm_folio_no'], 1, 1, 'L', true);
		$this->cMargin = 3;	
		} 
		if($row_securities_markets['cm_cdc_ac_no']!=''){
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l, 7, 'CDC A/C No', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c-1, 7,  $row_securities_markets['cm_cdc_ac_no'], 1, 1, 'L', true);
		$this->cMargin = 3;	
		}		
		if($row_securities_markets['cm_no_of_shares']!=''){
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l, 7, 'No of Shares', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c-1, 7,  $row_securities_markets['cm_no_of_shares'], 1, 1, 'L', true);
		$this->cMargin = 3;	
		
		}
		}
    else if($ticket->getDeptId()==3){
			$this->Ln(5);
		//Department Info
		$this->SetFont('Arial', 'B', 11);
		$this->cMargin = 3;
		$this->SetTextColor(0);
		$this->SetDrawColor(220, 220, 220);
        $this->SetFillColor(210, 226, 139);
		$this->Cell($w-1, 7,'Department Info', 1, 1, 'L',true);
		$this->SetTextColor(0);
		$this->SetDrawColor(220, 220, 220);
		$this->SetFillColor(244, 250, 255);
		
		$this->cMargin = 3;
		
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l, 7, 'Department', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c-1, 7, Format::htmlchars($ticket->getDeptName()) , 1, 1, 'L', true);
		
		$this->cMargin = 3;
		
		
    $sql_insurance="Select * from sdms_ticket_insurance where complaint_id ='".$ticket->getId()."'";
    $res_insurance=mysql_query($sql_insurance);
    $row_insurance=mysql_fetch_array($res_insurance);
   
		if($row_insurance['i_type']!=''){	
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l, 7, 'Type', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c-1, 7,  $row_insurance['i_type'], 1, 1, 'L', true);
		$this->cMargin = 3;
		}
		if($row_insurance['i_broker_title']!=''){
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l, 7, 'Company List', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c-1, 7,  $row_insurance['i_broker_title'], 1, 1, 'L', true);
		$this->cMargin = 3;	
		} 
		if($row_insurance['i_broker_agent']!=''){ 
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l, 7, 'Agent List', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c-1, 7,  $row_insurance['i_broker_agent'], 1, 1, 'L', true);
		$this->cMargin = 3;	
		}
		if($row_insurance['i_policy_no']!=''){
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l, 7, 'Policy No', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c-1, 7,  $row_insurance['i_policy_no'], 1, 1, 'L', true);
		$this->cMargin = 3;	
		} 
		if($row_insurance['i_sum_assured']!=''){
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l, 7, 'Sum Assured', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c-1, 7,  $row_insurance['i_sum_assured'], 1, 1, 'L', true);
		$this->cMargin = 3;	
		}		
		if($row_insurance['i_claim_amount']!=''){
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l, 7, 'Claim Amount', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c-1, 7,  $row_insurance['i_claim_amount'], 1, 1, 'L', true);
		$this->cMargin = 3;	
		}
		if($row_insurance['i_folio_no']!=''){
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l, 7, 'Folio No', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c-1, 7,  $row_insurance['i_folio_no'], 1, 1, 'L', true);
		$this->cMargin = 3;	
		}
		if($row_insurance['i_no_of_shares']!=''){
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l, 7, 'No of Shares', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c-1, 7,  $row_insurance['i_no_of_shares'], 1, 1, 'L', true);
		$this->cMargin = 3;	
		}
		}
    else if($ticket->getDeptId()==4){}
    else if($ticket->getDeptId()==5){}
    else if($ticket->getDeptId()==6){}
    else if($ticket->getDeptId()==18){} 
		
		
		
		$this->Ln(10);
		
		 if(!$this->includenotes )
		 {	
		//Complaint Description 
		$this->SetFont('Arial', 'B', 11);
		$this->cMargin = 3;
		$this->SetTextColor(0);
		$this->SetDrawColor(220, 220, 220);
        $this->SetFillColor(210, 226, 139);
		$this->Cell($w+$l+$c, 7,'Complaint Description', 1, 0, 'L',true);
		$this->SetTextColor(0);
		$this->SetDrawColor(220, 220, 220);
		$this->SetFillColor(244, 250, 255);
			
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($w, 7,'', 0, 0, 'L');
		$this->Ln(7);
		$this->cMargin = 3;
		
		$this->SetTextColor(0);
		$this->SetFont('');
		$this->MultiCell(190, 7, $ticket->getComplaintDetials(), 1,  'J', true);
		$this->SetFont('');
  		$this->Ln(3);
		 }
		
//================================================================================================================================================		
		//Previous Complaint  and Respondent Info
	
        if($this->includenotes)
		{
			//Subject Section 
        $this->SetFont('Arial', 'B', 11);
        $this->cMargin = 0;
        $this->SetTextColor(10, 86, 142);
        $this->Cell($w, 7,trim($ticket->getSubject()), 0, 0, 'L');
        $this->Ln(7);
        $this->SetTextColor(0);
        $this->cMargin = 3;

        //Table header colors (RGB)
        $colors = array('M'=>array(195, 217, 255),
                        'R'=>array(255, 224, 179),
                        'N'=>array(250, 250, 210),
						'T'=>array(250, 250, 210),
						'C'=>array(250, 250, 210));
        //Get ticket thread
			$types = array('M', 'R');
            $types[] = 'N';
			$types[] = 'T';
			$types[] = 'C';
			

        if(($entries = $ticket->getThreadEntries($types))) {
            foreach($entries as $entry) {
                $color = $colors[$entry['thread_type']];
                $this->SetFillColor($color[0], $color[1], $color[2]);
                $this->SetFont('Arial', 'B', 11);
                $this->Cell($w/2, 7, Format::db_datetime($entry['created']), 'LTB', 0, 'L', true);
                $this->SetFont('Arial', '', 10);
                $this->Cell($w, 7, $entry['title'], 'TB', 0, 'L', true);
                $this->Cell($w/2, 7, $entry['poster'], 'TBR', 1, 'L', true);
                $this->SetFont('');
                $text= $entry['body'];
                if($entry['attachments']
                        && ($tentry=$ticket->getThreadEntry($entry['id']))
                        && ($attachments = $tentry->getAttachments())) {
                    foreach($attachments as $attachment)
                        $files[]= $attachment['name'];
                    $text.="\nFiles Attached: [".implode(', ',$files)."]\n";
                }
                $this->WriteText($w*2, $text, 1);
                $this->Ln(5);
            }
        }
		
		}

    }
}
class Query2PDF extends FPDF
{
	var $includenotes = false;
	var $pageOffset = 0;
    var $ticket = null;
	
	function Query2PDF($ticket, $psize='Letter', $notes=false) {
        global $thisstaff;
        parent::FPDF('P', 'mm', $psize);
        $this->ticket = $ticket;
        $this->includenotes = $notes;
        $this->SetMargins(10,10,10);
		$this->AliasNbPages();
		$this->AddPage();
		$this->cMargin = 3;
        $this->_print();
	}

    function getTicket() {
        return $this->ticket;
    }

	//report header...most stuff are hard coded for now...
	function Header() {
        global $cfg;
		//Common header
        $this->Ln(2);
		$this->SetFont('Times', 'B', 16);
		$this->Image(FPDF_DIR . 'print-logo.jpg', null, 10, 0, 20);
		$this->SetX(200, 15);
		$this->Cell(0, 15, '', 0, 1, 'R', 0);
		//$this->SetY(40);
        $this->SetX($this->lMargin);
        $this->Cell(0, 3, '', "B", 2, 'L');
        $this->SetFont('Arial', 'I',10);
        //$this->Cell(0, 5, 'Generated on '.Format::date($cfg->getDateTimeFormat(), Misc::gmtime(), $_SESSION['TZ_OFFSET'], $_SESSION['TZ_DST']), 0, 0, 'L');
       // $this->Cell(0, 5, 'Date & Time based on GMT '.$_SESSION['TZ_OFFSET'], 0, 1, 'R');
	     $this->Cell(0, 0, '', 0, 1, 'R');
		$this->Ln(5);
	}

	//Page footer baby
	function Footer() {
        global $thisstaff;

		$this->SetY(-15);
        $this->Cell(0, 2, '', "T", 2, 'L');
		$this->SetFont('Arial', 'I', 9);
		//$this->Cell(0, 7, 'Query #'.$this->getTicket()->getNumber().' printed by '.$thisstaff->getUserName().' on '.date('r'), 0, 0, 'L');
		$this->Cell(0, 7, 'Query #'.$this->getTicket()->getNumber().' printed by '.$thisstaff->getUserName().'', 0, 0, 'L');
		$this->Cell(0, 7, 'Page ' . ($this->PageNo() - $this->pageOffset), 0, 0, 'R');
	}

    function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='') {
        parent::Cell($w, $h, $this->_utf8($txt), $border, $ln, $align, $fill, $link);
    }

    function WriteText($w, $text, $border) {

        $this->SetFont('Times','',11);
        $this->MultiCell($w, 5, $text, $border, 'L');

    }

    function _utf8($text) {

        if(function_exists('iconv'))
            return iconv('UTF-8', 'windows-1252', $text);

        return utf8_encode($text);
    }

    function _print() {
		global $thisstaff;
        if(!($ticket=$this->getTicket()))
            return;

        $w =(($this->w/2)-$this->lMargin);
        $l = 35;
        $c = $w-$l;
		
$x = $this->GetX();
$y = $this->GetY();
//Subject Section and Query #0000 
$this->SetFont('Arial', 'B', 11);
$this->Cell($l-5, 5, 'Query Title:', 0, 0, 'L');
$this->SetTextColor(10, 86, 142);
$col1=$ticket->getSubject();
$this->MultiCell($c+55, 5, $col1, 0, 'L');
$this->SetXY($x + $c+85, $y);

$this->SetFont('Arial', 'B', 11);
$this->SetTextColor(0);
$this->Cell($l-10, 5, 'Query #', 0, 0, 'L');
$this->SetTextColor(10, 86, 142);
$col2=$ticket->getNumber();
$this->MultiCell($c, 5, $col2, 0,'L');		
		
		$this->Ln(10);
		//Complainant Profile  and  QueryParticulars
		$this->SetDrawColor(220, 220, 220);
		$this->SetFillColor(210, 226, 139);
		$this->SetFont('Arial', 'B', 11);
		$this->cMargin = 3;
		$this->SetTextColor('');
		$this->Cell($w-1, 7,'Applicant Info', 1, 0, 'L',true);
		$this->SetTextColor(0);
		
		$this->Cell(2, 7, ' ', 0, 0, 'L');
		
		$this->SetFont('Arial', 'B', 11);
		$this->SetTextColor('');
		$this->Cell($w-1, 7,'Query Info', 1, 0, 'L',true);
		$this->Ln(7);
		$this->cMargin = 3;
		$this->SetTextColor(0);
		$this->SetDrawColor(220, 220, 220);
		$this->SetFillColor(244, 250, 255);			
		//Name and Status
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l, 7, 'Name', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c-1, 7, $ticket->getName(), 1, 0, 'L', true);
		
		$this->Cell(2, 7, ' ', 0, 0, 'L');
		
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l-1, 7, 'Status', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c, 7,  $ticket->complaint_status(), 1, 1, 'L', true);
		//CNIC and Priority
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l, 7, 'CNIC/NICOP/PSPT', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c-1, 7,  $ticket->getNic(), 1, 0, 'L', true);
		
		$this->Cell(2, 7, ' ', 0, 0, 'L');
		
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l-1, 7, 'Priority', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c, 7, $ticket->getPriority(), 1, 1, 'L', true);
		//Mobile and Query Date and CNIC
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l, 7, 'Mobile', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c-1, 7, $ticket->getPhoneNumber(), 1, 0, 'L', true);	
		
		$this->Cell(2, 7, ' ', 0, 0, 'L');			
		
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l-1, 7, 'Dept', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c, 7, $ticket->getDeptName(), 1, 1, 'L', true);
		//Email and Lodged Date/Time
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l, 7, 'Email', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c-1, 7, $ticket->getEmail(), 1, 0, 'L', true);		
		
		$this->Cell(2, 7, ' ', 0, 0, 'L');			
		
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l-1, 7, 'Lodged Date/Time', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c, 7, Format::db_datetime($ticket->getCreateDate()), 1, 1, 'L', true);
		//Country and Assigned To
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l, 7, 'Country', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c-1, 7, $ticket->getDistrict(), 1, 0, 'L', true);
		
		$this->Cell(2, 7, ' ', 0, 0, 'L');			
		
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l-1, 7, 'Assigned To', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c, 7, Format::htmlchars(implode('/', $ticket->getAssignees())), 1, 1, 'L', true);
		//Province and Query Category:
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l, 7, 'Province', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c-1, 7, $ticket->getTehsil(), 1, 0, 'L', true);		
		
		$this->Cell(2, 7, ' ', 0, 0, 'L');			
		
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l-1, 7, 'Query Category', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c, 7, $ticket->getHelpTopic(), 1, 1, 'L', true);
		//City and Source
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l, 7, 'City', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c-1, 7, $ticket->getAgencyTehsilTitle(), 1, 0, 'L', true);		
		
		$this->Cell(2, 7, ' ', 0, 0, 'L');			
		
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l-1, 7, 'Source', 1, 0, 'L', true);
		$this->SetFont('');
		$source = ucfirst($ticket->getSource());
		if($ticket->getIP())
		$source.='  ('.$ticket->getIP().')'; 
		$this->Cell($c, 7,$source , 1, 1, 'L', true);
		//Address and Null
		$x = $this->GetX();
		$y = $this->GetY();
		$this->SetFont('Arial', 'B', 11);
		$this->MultiCell($l, 7, 'Postal Address', 1, 'L', true);
		$this->SetXY($x + $c-25, $y);
		$this->SetFont('');
		$this->MultiCell($c-1 , 7, $ticket->getApplicant_Address(), 1, 'L', true);
		
	
		
    if($ticket->getDeptId()==2){
			$this->Ln(5);
		//Department Info
		$this->SetFont('Arial', 'B', 11);
		$this->cMargin = 3;
		$this->SetTextColor(0);
		$this->SetDrawColor(220, 220, 220);
        $this->SetFillColor(210, 226, 139);
		$this->Cell($w-1, 7,'Department Info', 1, 1, 'L',true);
		$this->SetTextColor(0);
		$this->SetDrawColor(220, 220, 220);
		$this->SetFillColor(244, 250, 255);
		
		$this->cMargin = 3;
		
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l, 7, 'Department', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c-1, 7, Format::htmlchars($ticket->getDeptName()) , 1, 1, 'L', true);
		
		$this->cMargin = 3;
		
		
    $sql_securities_markets="Select * from sdms_ticket_capital_markets where complaint_id ='".$ticket->getId()."'";
    $res_securities_markets=mysql_query($sql_securities_markets);
    $row_securities_markets=mysql_fetch_array($res_securities_markets);
   
		if($row_securities_markets['cm_type']!=''){	
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l, 7, 'Type', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c-1, 7,  $row_securities_markets['cm_type'], 1, 1, 'L', true);
		$this->cMargin = 3;
		}
		if($row_securities_markets['cm_broker_title']!=''){
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l, 7, 'Brokers List', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c-1, 7,  $row_securities_markets['cm_broker_title'], 1, 1, 'L', true);
		$this->cMargin = 3;	
		} 
		if($row_securities_markets['cm_broker_agent']!=''){ 
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l, 7, 'Agent List', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c-1, 7,  $row_securities_markets['cm_broker_agent'], 1, 1, 'L', true);
		$this->cMargin = 3;	
		}
		if($row_securities_markets['cm_folio_no']!=''){
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l, 7, 'Folio No', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c-1, 7,  $row_securities_markets['cm_folio_no'], 1, 1, 'L', true);
		$this->cMargin = 3;	
		} 
		if($row_securities_markets['cm_cdc_ac_no']!=''){
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l, 7, 'CDC A/C No', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c-1, 7,  $row_securities_markets['cm_cdc_ac_no'], 1, 1, 'L', true);
		$this->cMargin = 3;	
		}		
		if($row_securities_markets['cm_no_of_shares']!=''){
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l, 7, 'No of Shares', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c-1, 7,  $row_securities_markets['cm_no_of_shares'], 1, 1, 'L', true);
		$this->cMargin = 3;	
		
		}
		}
    else if($ticket->getDeptId()==3){
			$this->Ln(5);
		//Department Info
		$this->SetFont('Arial', 'B', 11);
		$this->cMargin = 3;
		$this->SetTextColor(0);
		$this->SetDrawColor(220, 220, 220);
        $this->SetFillColor(210, 226, 139);
		$this->Cell($w-1, 7,'Department Info', 1, 1, 'L',true);
		$this->SetTextColor(0);
		$this->SetDrawColor(220, 220, 220);
		$this->SetFillColor(244, 250, 255);
		
		$this->cMargin = 3;
		
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l, 7, 'Department', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c-1, 7, Format::htmlchars($ticket->getDeptName()) , 1, 1, 'L', true);
		
		$this->cMargin = 3;
		
		
    $sql_insurance="Select * from sdms_ticket_insurance where complaint_id ='".$ticket->getId()."'";
    $res_insurance=mysql_query($sql_insurance);
    $row_insurance=mysql_fetch_array($res_insurance);
   
		if($row_insurance['i_type']!=''){	
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l, 7, 'Type', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c-1, 7,  $row_insurance['i_type'], 1, 1, 'L', true);
		$this->cMargin = 3;
		}
		if($row_insurance['i_broker_title']!=''){
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l, 7, 'Company List', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c-1, 7,  $row_insurance['i_broker_title'], 1, 1, 'L', true);
		$this->cMargin = 3;	
		} 
		if($row_insurance['i_broker_agent']!=''){ 
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l, 7, 'Agent List', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c-1, 7,  $row_insurance['i_broker_agent'], 1, 1, 'L', true);
		$this->cMargin = 3;	
		}
		if($row_insurance['i_policy_no']!=''){
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l, 7, 'Policy No', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c-1, 7,  $row_insurance['i_policy_no'], 1, 1, 'L', true);
		$this->cMargin = 3;	
		} 
		if($row_insurance['i_sum_assured']!=''){
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l, 7, 'Sum Assured', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c-1, 7,  $row_insurance['i_sum_assured'], 1, 1, 'L', true);
		$this->cMargin = 3;	
		}		
		if($row_insurance['i_claim_amount']!=''){
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l, 7, 'Claim Amount', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c-1, 7,  $row_insurance['i_claim_amount'], 1, 1, 'L', true);
		$this->cMargin = 3;	
		}
		if($row_insurance['i_folio_no']!=''){
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l, 7, 'Folio No', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c-1, 7,  $row_insurance['i_folio_no'], 1, 1, 'L', true);
		$this->cMargin = 3;	
		}
		if($row_insurance['i_no_of_shares']!=''){
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($l, 7, 'No of Shares', 1, 0, 'L', true);
		$this->SetFont('');
		$this->Cell($c-1, 7,  $row_insurance['i_no_of_shares'], 1, 1, 'L', true);
		$this->cMargin = 3;	
		}
		}
    else if($ticket->getDeptId()==4){}
    else if($ticket->getDeptId()==5){}
    else if($ticket->getDeptId()==6){}
    else if($ticket->getDeptId()==18){} 
		
		
		
		$this->Ln(10);
		
		 if(!$this->includenotes )
		 {	
		//Query Description 
		$this->SetFont('Arial', 'B', 11);
		$this->cMargin = 3;
		$this->SetTextColor(0);
		$this->SetDrawColor(220, 220, 220);
        $this->SetFillColor(210, 226, 139);
		$this->Cell($w+$l+$c, 7,'Query Description', 1, 0, 'L',true);
		$this->SetTextColor(0);
		$this->SetDrawColor(220, 220, 220);
		$this->SetFillColor(244, 250, 255);
			
		$this->SetFont('Arial', 'B', 11);
		$this->Cell($w, 7,'', 0, 0, 'L');
		$this->Ln(7);
		$this->cMargin = 3;
		
		$this->SetTextColor(0);
		$this->SetFont('');
		$this->MultiCell(190, 7, $ticket->getComplaintDetials(), 1,  'J', true);
		$this->SetFont('');
  		$this->Ln(3);
		 }
		
//================================================================================================================================================		
		//Previous Complaint  and Respondent Info
	
        if($this->includenotes)
		{
			//Subject Section 
        $this->SetFont('Arial', 'B', 11);
        $this->cMargin = 0;
        $this->SetTextColor(10, 86, 142);
        $this->Cell($w, 7,trim($ticket->getSubject()), 0, 0, 'L');
        $this->Ln(7);
        $this->SetTextColor(0);
        $this->cMargin = 3;

        //Table header colors (RGB)
        $colors = array('M'=>array(195, 217, 255),
                        'R'=>array(255, 224, 179),
                        'N'=>array(250, 250, 210));
        //Get ticket thread
			$types = array('M', 'R');
            $types[] = 'N';

        if(($entries = $ticket->getThreadEntries($types))) {
            foreach($entries as $entry) {
                $color = $colors[$entry['thread_type']];
                $this->SetFillColor($color[0], $color[1], $color[2]);
                $this->SetFont('Arial', 'B', 11);
                $this->Cell($w/2, 7, Format::db_datetime($entry['created']), 'LTB', 0, 'L', true);
                $this->SetFont('Arial', '', 10);
                $this->Cell($w, 7, $entry['title'], 'TB', 0, 'L', true);
                $this->Cell($w/2, 7, $entry['poster'], 'TBR', 1, 'L', true);
                $this->SetFont('');
                $text= $entry['body'];
                if($entry['attachments']
                        && ($tentry=$ticket->getThreadEntry($entry['id']))
                        && ($attachments = $tentry->getAttachments())) {
                    foreach($attachments as $attachment)
                        $files[]= $attachment['name'];
                    $text.="\nFiles Attached: [".implode(', ',$files)."]\n";
                }
                $this->WriteText($w*2, $text, 1);
                $this->Ln(5);
            }
        }
		
		}

    }
}
class Ticket2PDF_Depart extends FPDF
{
	var $includenotes = false;
	var $pageOffset = 0;
    var $ticket = null;
	function Ticket2PDF_Depart($ticket, $psize='Letter', $notes=false,$responce) {
        global $thisstaff;
        parent::FPDF('P', 'mm', $psize);
        $this->ticket = $ticket;
        $this->includenotes = $notes;
        $this->SetMargins(10,10,10);
		$this->AliasNbPages();
		$this->AddPage();
		$this->cMargin = 3;
        $this->_print($responce);
	}
    function getTicket() {
        return $this->ticket;
    }
	//report header...most stuff are hard coded for now...
	function Header() {
        global $cfg;
		//Common header
        $this->Ln(2);
		$this->SetFont('Times', 'B', 16);
		$this->Image(FPDF_DIR . 'print-logo.png', null, 10, 0, 20);
		$this->SetX(200, 15);
		$this->Cell(0, 15, $cfg->getTitle(), 0, 1, 'R', 0);
		//$this->SetY(40);
        $this->SetX($this->lMargin);
        $this->Cell(0, 3, '', "B", 2, 'L');
        $this->SetFont('Arial', 'I',10);
        $this->Cell(0, 5, 'Generated on '.Format::date($cfg->getDateTimeFormat(), Misc::gmtime(), $_SESSION['TZ_OFFSET'], $_SESSION['TZ_DST']), 0, 0, 'L');
        $this->Cell(0, 5, 'Date & Time based on GMT '.$_SESSION['TZ_OFFSET'], 0, 1, 'R');
		$this->Ln(10);
	}
	//Page footer baby
	function Footer() {
        global $thisstaff;
		$this->SetY(-15);
        $this->Cell(0, 2, '', "T", 2, 'L');
		$this->SetFont('Arial', 'I', 9);
		$this->Cell(0, 7, 'Complaint #'.$this->getTicket()->getNumber().' printed by '.$thisstaff->getUserName().' on '.date('r'), 0, 0, 'L');
		//$this->Cell(0,10,'Page '.($this->PageNo()-$this->pageOffset).' of {nb} '.$this->pageOffset.' '.$this->PageNo(),0,0,'R');
		$this->Cell(0, 7, 'Page ' . ($this->PageNo() - $this->pageOffset), 0, 0, 'R');
	}
    function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='') {
        parent::Cell($w, $h, $this->_utf8($txt), $border, $ln, $align, $fill, $link);
    }
    function WriteText($w, $text, $border) {

        $this->SetFont('Times','',11);
        $this->MultiCell($w, 5, $text, $border, 'L');

    }
    function _utf8($text) {

        if(function_exists('iconv'))
            return iconv('UTF-8', 'windows-1252', $text);

        return utf8_encode($text);
    }
    function _print($responce) {
        if(!($ticket=$this->getTicket()))
            return;
        $w =(($this->w/2)-$this->lMargin);
        $l = 35;
        $c = $w-$l;
                $this->WriteText($w*2, $responce, 1);
                $this->Ln(5);
    }
}?>