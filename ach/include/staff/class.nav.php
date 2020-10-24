<?php
class StaffNav {
    var $tabs=array();
    var $submenus=array();
    var $activetab;
    var $activemenu;
    var $panel;
    var $staff;
    function StaffNav($staff, $panel='staff'){
        $this->staff=$staff;
        $this->panel=strtolower($panel);
        $this->tabs=$this->getTabs();
        $this->submenus=$this->getSubMenus();
    }
    function getPanel(){
        return $this->panel;
    }
    function isAdminPanel(){
        return (!strcasecmp($this->getPanel(),'admin'));
    }
    function isStaffPanel() {
        return (!$this->isAdminPanel());
    }
    function setTabActive($tab, $menu=''){
        if($this->tabs[$tab]){
            $this->tabs[$tab]['active']=true;
            if($this->activetab && $this->activetab!=$tab && $this->tabs[$this->activetab])
                 $this->tabs[$this->activetab]['active']=false;
            $this->activetab=$tab;
            if($menu) $this->setActiveSubMenu($menu, $tab);
            return true;
        }
        return false;
    }
    function setActiveTab($tab, $menu=''){
        return $this->setTabActive($tab, $menu);
    }
    function getActiveTab(){
        return $this->activetab;
    }
    function setActiveSubMenu($mid, $tab='') {
        if(is_numeric($mid))
            $this->activeMenu = $mid;
        elseif($mid && $tab && ($subNav=$this->getSubNav($tab))) {
            foreach($subNav as $k => $menu) {
                if(strcasecmp($mid, $menu['href'])) continue;
                $this->activeMenu = $k+1;
                break;
            }
        }
    }
    function getActiveMenu() {
        return $this->activeMenu;
    }
    function addSubMenu($item,$active=false){
        $this->submenus[$this->getPanel().'.'.$this->activetab][]=$item;
        if($active)
            $this->activeMenu=sizeof($this->submenus[$this->getPanel().'.'.$this->activetab]);
    }
    function getTabs(){
        if(!$this->tabs) {
            $this->tabs=array();
			$chairman= new Staff($_SESSION['_staff']['userID']);
			if($chairman->isFocalPerson()==1 || $chairman->onChairman()==1)
			{
			$this->tabs['admin_dashboard']=array('desc'=>'Dashboard','href'=>'admin_dashboard.php','title'=>'Staff Dashboard');
			}
			else if($chairman->onChairman()!=1)
			{
			$this->tabs['dashboard']=array('desc'=>'Dashboard','href'=>'dashboard.php','title'=>'Staff Dashboard');
			//$this->tabs['report']=array('desc'=>'Reports','href'=>'report.php','title'=>'Reports');
			}
			//$this->tabs['tickets']=array('desc'=>'Complaint','href'=>'tickets.php?status=assigned','title'=>'Ticket Queue','class'=>'ibw-edit');
	
			if($chairman->getGroupId()!= 9){
		$this->tabs['tickets']=array('desc'=>'Complaint','href'=>'tickets.php','title'=>'Ticket Queue','class'=>'ibw-edit');
		$this->tabs['primary_status']=array('desc'=>'Complaint Status Summary','href'=>'primary_status.php','title'=>'Complaint Status Summary');
		$this->tabs['queries']=array('desc'=>'Query','href'=>'queries.php','title'=>'Ticket Query','class'=>'ibw-edit');
		$this->tabs['primary_status_queries']=array('desc'=>'Queries Status Summary','href'=>'primary_status_queries.php','title'=>'Queries Status Summary');
			}
		$this->tabs['reports_new']=array('desc'=>'SDMS Reports','href'=>'reports_new.php','title'=>'Reports');
		$this->tabs['query_dept_status_report']=array('desc'=>'Query Reports','href'=>'query_dept_status_report.php','title'=>'Reports');
		
		if($chairman->getGroupId()!= 9){
		$this->tabs['kb']=array('desc'=>'Knowledgebase','href'=>'kb.php','title'=>'Knowledgebase');
		}
			
		}
        return $this->tabs;
    }
    function getSubMenus(){ //Private.ssss
        $staff = $this->staff;
		
		$stats= $staff->getTicketsStats();
		$stats_query= $staff->getQueryStats();
        $submenus=array();
        foreach($this->getTabs() as $k=>$tab){
            $subnav=array();
            switch(strtolower($k)){
				case 'tickets':
				/*$subnav[]=array('desc'=>'Tickets','href'=>'tickets.php','iconclass'=>'Ticket', 'class'=>'ibw-edit' );*/
					if($staff->canCreateTickets() && !$staff->isAdmin()) {
    $subnav[]=array('desc'=>'New Complaints','title'=>'New Complaints','href'=>'tickets.php?a=open','iconclass'=>'newTicket','class'=>'ibw-new_Complaint');
	$subnav[]= array('desc'=>'All Complaints','title'=>'All Complaints','href'=>'tickets.php','iconclass'=>'Ticket','class'=>'ibw-all-complaints');	
	$subnav[] = array('desc'=>'Deny Complaint ('.number_format($stats['deny']).')','title'=>'Deny Complaint','href'=>'tickets.php?status=deny','iconclass'=>'assignedTickets','class'=>'ibw-Assigned_Complaints');																
}
elseif($staff->isAdmin())
{
	$subnav[] = array('desc'=>'All Complaints','title'=>'All Complaints','href'=>'tickets.php','iconclass'=>'Ticket','class'=>'ibw-Open_Complaints');		
	$subnav[] = array('desc'=>'Deny Complaint ('.number_format($stats['deny']).')','title'=>'Deny Complaint','href'=>'tickets.php?status=deny','iconclass'=>'assignedTickets','class'=>'ibw-Assigned_Complaints');
	//$subnav[] =  array('desc'=>'Nearing Overdue','title'=>'Nearing Overdue Complaints','href'=>'tickets.php?status=nearoverdue','iconclass'=>'overdueTickets','class'=>'ibw-Stale_Complaints');	
	}
else{

	
if($stats['lodged']) {
	/*if(!$ost->getWarning() && $stats['lodged']>1)
	$ost->setWarning('You have <bold>'.$stats['lodged'].'</bold> Newly Lodged Complaints.');*/
    $subnav[] = array('desc'=>'Lodged ('.number_format($stats['lodged']).')',
                           'title'=>'Lodged',
                           'href'=>'tickets.php?status=lodged',
                           'iconclass'=>'assignedTickets',
						   'class'=>'ibw-Assigned_Complaints');
}
/*if($cfg->showAssignedTickets() && !$staff->isFocalPerson()) {
    $subnav[] = array('desc'=>'Accepted ('.number_format($stats['assigned']).')',
                            'title'=>'Accepted',
                            'href'=>'tickets.php?status=assigned',
                            'iconclass'=>'Ticket',
							'class'=>'ibw-Open_Complaints');
						} */
//else {
    if($stats) {
        $subnav[] = array('desc'=>'Accepted ('.number_format($stats['open']).')','title'=>'Accepted ','href'=>'tickets.php','iconclass'=>'Ticket','class'=>'ibw-Open_Complaints');
		}

//	}
if($stats['overdue']) {
	$subnav[] =  array('desc'=>'Overdue ('.number_format($stats['overdue']).')',

                           'title'=>'Stale Complaints',

                           'href'=>'tickets.php?status=overdue',

                           'iconclass'=>'overdueTickets',

						   'class'=>'ibw-Stale_Complaints');
}
if($staff->showAssignedOnly() && $stats['closed']) {
    $subnav[] =  array('desc'=>'My Closed Complaints ('.number_format($stats['closed']).')',
                           'title'=>'My Closed Complaints',
                           'href'=>'tickets.php?status=closed',
                           'iconclass'=>'closedTickets',
						   'class'=>'ibw-Closed_Complaints');
						   } else {
    $subnav[] = array('desc'=>'Resolved/Closed ('.number_format($stats['closed']).')',
                           'title'=>'Resolved/<br>Closed',
                           'href'=>'tickets.php?status=closed',
                           'iconclass'=>'closedTickets',
						   'class'=>'ibw-Closed_Complaints');
				}
	$subnav[] =  array('desc'=>'Nearing Overdue','title'=>'Nearing Overdue Complaints','href'=>'tickets.php?status=nearoverdue','iconclass'=>'overdueTickets','class'=>'ibw-Stale_Complaints');	
}
				
				if($staff) {
				if(($assigned=$staff->getNumAssignedTickets()))
				$subnav[]=array('desc'=>"My&nbsp;Tickets ($assigned)",
				'href'=>'tickets.php?status=assigned',
				'iconclass'=>'assignedTickets',
				'class'=>'ibw-edit',
				'droponly'=>true);
				
				if($staff->canCreateTickets())
				$subnav[]=array('desc'=>'New&nbsp;Ticket',
				'href'=>'tickets.php?a=open',
				'class'=>'ibw-edit',
				'iconclass'=>'newTicket',											
				'droponly'=>true);
				}					
				break;	
				
				case 'queries':
				if($staff->canCreateTickets() && !$staff->isAdmin()) {
				$subnav[]=array('desc'=>'New Query','title'=>'New Query','href'=>'queries.php?a=open&action=query','iconclass'=>'Query', 'class'=>'ibw-new-query');
				$subnav[]=array('desc'=>'All Queries','title'=>'All Queries','href'=>'queries.php','iconclass'=>'Query', 'class'=>'ibw-all-queries');
				$subnav[]=array('desc'=>'Deny Queries ('.number_format($stats_query['deny']).')','title'=>'Deny Queries','href'=>'queries.php?status=deny',
				'iconclass'=>'assignedTickets','class'=>'ibw-Assigned_Complaints');
				}elseif($staff->isAdmin())
				{
				$subnav[]=array('desc'=>'All Queries','title'=>'All Queries','href'=>'queries.php','iconclass'=>'Ticket','class'=>'ibw-Open_Complaints');	
				$subnav[]=array('desc'=>'Deny Queries ('.number_format($stats_query['deny']).')','title'=>'Deny Queries','href'=>'queries.php?status=deny',
				'iconclass'=>'assignedTickets','class'=>'ibw-Assigned_Complaints');									
				}
				else{
				if($stats_query['lodged']) {
				$subnav[] = array('desc'=>'Lodged ('.number_format($stats_query['lodged']).')','title'=>'Lodged','href'=>'queries.php?status=lodged','iconclass'=>'assignedTickets', 'class'=>'ibw-Assigned_Complaints');
				}
				if($stats_query){
				$subnav[] = array('desc'=>'Accepted ('.number_format($stats_query['open']).')','title'=>'Accepted ','href'=>'queries.php','iconclass'=>'Ticket','class'=>'ibw-Open_Complaints');
				}
				if($stats_query['answered']) {
				//$subnav[]=array('desc'=>'Answered ('.number_format($stats_query['answered']).')','title'=>'Answered Complaints','href'=>'queries.php?status=answered','iconclass'=>'answeredTickets','class'=>'ibw-Answered_Complaints');
				}
				
				if($staff->showAssignedOnly() && $stats_query['closed']) {
				$subnav[]=array('desc'=>'My Closed Complaints ('.number_format($stats_query['closed']).')',
				'title'=>'My Closed Complaints',
				'href'=>'queries.php?status=closed',
				'iconclass'=>'closedTickets',
				'class'=>'ibw-Closed_Complaints');
				} else {
				$subnav[]=array('desc'=>'CLosed ('.number_format($stats_query['closed']).')',
				'title'=>'Closed',
				'href'=>'queries.php?status=closed',
				'iconclass'=>'closedTickets',
				'class'=>'ibw-Closed_Complaints');}
				}
				break;
				
                case 'dashboard':
                    $subnav[]=array('desc'=>'Dashboard','href'=>'dashboard.php','iconclass'=>'logs' ,'title'=>'Dashboard','class'=>'ibw-edit');					
				      if($staff->canViewStaffStats())
					  $subnav[]=array('desc'=>'Staff&nbsp;Directory','href'=>'directory.php','iconclass'=>'teams' ,'title'=>'Staff&nbsp;Directory','class'=>'ibw-staff_directory');
                    $subnav[]=array('desc'=>'My Profile','href'=>'profile.php','iconclass'=>'users' ,'title'=>'My Profile','class'=>'ibw-folder');
                    
					break;
				case 'admin_dashboard':
                    $subnav[]=array('desc'=>'Complaint Dashboard','href'=>'admin_dashboard.php','iconclass'=>'logs' ,'title'=>'Complaint Dashboard','class'=>'ibw-edit');	
					$subnav[]=array('desc'=>'Query Dashboard','href'=>'admin_dashboard_query.php','iconclass'=>'logs' ,'title'=>'Query Dashboard','class'=>'ibw-edit');	
									
                    $subnav[]=array('desc'=>'My Profile','href'=>'profile.php','iconclass'=>'users' ,'title'=>'My Profile','class'=>'ibw-folder');
                    break;
				
				case 'report_viewer':
				    $subnav[] = array('desc'=>'Higher Dashboard','href'=>'report_viewer.php','iconclass'=>'logs' ,'title'=>'Dashboard','class'=>'ibw-edit');					
                    $subnav[] = array('desc'=>'Dashboard','href'=>'admin_dashboard.php','iconclass'=>'logs' ,'title'=>'Dashboard','class'=>'ibw-edit');
					$subnav[] = array('desc'=>'My Profile','href'=>'profile.php','iconclass'=>'users' ,'title'=>'My Profile','class'=>'ibw-folder');
					break;	
					
                case 'kb':				
                    $subnav[] = array('desc'=>'FAQs','href'=>'kb.php', 'urls'=>array('faq.php'), 'iconclass'=>'kb','title'=>'FAQs','class'=>'ibw-kb');
                    $subnav[] = array('desc'=>'Categories','href'=>'categories.php','iconclass'=>'faq-categories','title'=>'Categories','class'=>'ibw-folder');
                   break;
				   
				  case 'primary_status':				
                    $subnav[]=array('desc'=>'In process','href'=>'primary_status.php?status=in_process', 'urls'=>array('primary_status.php'), 'iconclass'=>'kb','title'=>'In process','class'=>'ibw-in-process');
					$subnav[]=array('desc'=>'Third party referred','href'=>'primary_status.php?status=referred_to_third_party', 'urls'=>array('primary_status.php'), 'iconclass'=>'kb','title'=>'Third party referred','class'=>'ibw-third-party-icon');
					$subnav[]=array('desc'=>'Subjudice','href'=>'primary_status.php?status=sub_judice', 'urls'=>array('primary_status.php'), 'iconclass'=>'kb','title'=>'Subjudice','class'=>'ibw-judice-icon');
					$subnav[]=array('desc'=>'Closed','href'=>'primary_status.php?status=closed', 'urls'=>array('primary_status.php'), 'iconclass'=>'kb','title'=>'Closed','class'=>'ibw-closed-icon');
					$subnav[]=array('desc'=>'Resolved','href'=>'primary_status.php?status=resolved', 'urls'=>array('primary_status.php'), 'iconclass'=>'kb','title'=>'Resolved','class'=>'ibw-resolved-icon');
                   break; 
				   
				    case 'primary_status_queries':				
                    $subnav[]=array('desc'=>'In process','href'=>'primary_status_queries.php?status=in_process', 'urls'=>array('primary_status_queries.php'), 'iconclass'=>'kb','title'=>'In process','class'=>'ibw-in-process');
					$subnav[]=array('desc'=>'Closed','href'=>'primary_status_queries.php?status=closed', 'urls'=>array('primary_status_queries.php'), 'iconclass'=>'kb','title'=>'Closed','class'=>'ibw-closed-icon');
                   break;  
		
				case 'report':  			          
				$subnav[]=array('desc'=>'Complaint&nbsp;Summary','href'=>'report.php','iconclass'=>'users','title'=>'Complaint&nbsp;Summary','class'=>'ibw-report');
				$subnav[]=array('desc'=>'Complaint&nbsp;Source','href'=>'complaintsource.php','iconclass'=>'groups','title'=>'Complaint&nbsp;Source','class'=>'ibw-source_r');
				$subnav[]=array('desc'=>'Area&nbsp;Report','href'=>'aera.php','iconclass'=>'groups','title'=>'Area&nbsp;Report','class'=>'ibw-area_r');
				$subnav[]=array('desc'=>'Department','href'=>'complaintdepartment.php','iconclass'=>'teams','title'=>'Department','class'=>'ibw-deprt_r');
				$subnav[]=array('desc'=>'Staff Complaints','href'=>'comlaintstaff.php','iconclass'=>'departments','title'=>'Staff Complaints','class'=>'ibw-staff_r');
				$subnav[]=array('desc'=>'Complaints Topics','href'=>'comlainttopics.php','iconclass'=>'departments','title'=>'Complaints Topics','class'=>'ibw-topics_r');
				$subnav[]=array('desc'=>'Complaint Status','href'=>'comlaintstatus_new.php','iconclass'=>'departments','title'=>'Complaint Status','class'=>'ibw-geog_r');	
				$subnav[]=array('desc'=>'Inquiry Report','href'=>'complaint_inquiry.php','iconclass'=>'departments','title'=>'Inquiry Report','class'=>'ibw-geog_r');
				$subnav[]=array('desc'=>'Department Type','href'=>'complaintdepartment_type_new.php','iconclass'=>'departments','title'=>'Department Type','class'=>'ibw-geog_r');	
				$subnav[]=array('desc'=>'Status Summary','href'=>'comlaintstatus_summary.php','iconclass'=>'departments','title'=>'Status Summary','class'=>'ibw-geog_r');	
				$subnav[]=array('desc'=>'Staff CCR','href'=>'comlaintstaff_ccr_new.php','iconclass'=>'departments','title'=>'Staff CCR','class'=>'ibw-geog_r');	
				$subnav[]=array('desc'=>'User Activity','href'=>'user_activity_new.php','iconclass'=>'departments','title'=>'User Activity','class'=>'ibw-geog_r');	
				$subnav[]=array('desc'=>'Role Activity','href'=>'role_activity_new.php','iconclass'=>'departments','title'=>'Role Activity','class'=>'ibw-geog_r');			
				  break; 
		
				case 'query_dept_status_report':  
$subnav[]=array('desc'=>'Queries Status','href'=>'query_dept_status_report.php','iconclass'=>'departments','title'=>'Queries Status','class'=>'ibw-r_new');
$subnav[]=array('desc'=>'Queries Ageing','href'=>'query_dept_ageing_report.php','iconclass'=>'departments','title'=>'Queries Ageing','class'=>'ibw-r_new');
$subnav[]=array('desc'=>'Queries Source','href'=>'query_source_report.php','iconclass'=>'groups','title'=>'Queries Source','class'=>'ibw-r_new');
$subnav[]=array('desc'=>'Status Ageing detail','href'=>'query_status_report.php','iconclass'=>'departments','title'=>'Status Ageing detail','class'=>'ibw-r_new');
$subnav[]=array('desc'=>'Nature of Queries','href'=>'query_category_report.php','iconclass'=>'departments','title'=>'Nature of Queries','class'=>'ibw-r_new');
$subnav[]=array('desc'=>'Query Escalation','href'=>'query_escalation_report.php','iconclass'=>'departments','title'=>'Query Escalation','class'=>'ibw-r_new');
	  break; 
		
				case 'reports_new':  
$subnav[]=array('desc'=>'Complaints Status','href'=>'departmental_summary_report.php','iconclass'=>'departments','title'=>'Departmental Summary','class'=>'ibw-r_new');
$subnav[]=array('desc'=>'Complaints Ageing','href'=>'comlaintsdepartment_new.php','iconclass'=>'departments','title'=>'Complaints Department','class'=>'ibw-r_new');
$subnav[]=array('desc'=>'Complaints Source','href'=>'complaintsource_new.php','iconclass'=>'groups','title'=>'Complaint&nbsp;Source','class'=>'ibw-r_new');
$subnav[]=array('desc'=>'Status Ageing detail','href'=>'comlaintstatus_new.php','iconclass'=>'departments','title'=>'Complaints Status','class'=>'ibw-r_new');
$subnav[]=array('desc'=>'Nature of complaints','href'=>'comlaintscategory_new.php','iconclass'=>'departments','title'=>'Complaints Categories','class'=>'ibw-r_new');
$subnav[]=array('desc'=>'POC/Sub POC Performance','href'=>'poc_status_report.php','iconclass'=>'departments','title'=>'POC/Sub POC Performance','class'=>'ibw-r_new');
$subnav[]=array('desc'=>'POC/Sub POC Ageing','href'=>'poc_ageing_report.php','iconclass'=>'departments','title'=>'POC/Sub POC Ageing','class'=>'ibw-r_new');
$subnav[]=array('desc'=>'Feedback Summary','href'=>'feedback_summary_report.php','iconclass'=>'departments','title'=>'Feedback Summary','class'=>'ibw-r_new');

if( $staff->isAdmin() ){
$subnav[]=array('desc'=>'Insurance Complaints','href'=>'insurance_report.php','iconclass'=>'departments','title'=>'Insurance Complaints','class'=>'ibw-r_new');
$subnav[]=array('desc'=>'Capital Market Complaints','href'=>'capital_market_report.php','iconclass'=>'departments','title'=>'Capital Market Complaints','class'=>'ibw-r_new');
$subnav[]=array('desc'=>'Company Registration/Compliance','href'=>'company_complaints_report.php','iconclass'=>'departments','title'=>'Company Registration/Compliance','class'=>'ibw-r_new');
$subnav[]=array('desc'=>'Specialized Companies Division','href'=>'scd_report.php','iconclass'=>'departments','title'=>'Specialized Companies Division','class'=>'ibw-r_new');
$subnav[]=array('desc'=>'Complaints Escalation','href'=>'complaint_escalation_report.php','iconclass'=>'departments','title'=>'Complaints Escalation','class'=>'ibw-r_new');
$subnav[]=array('desc'=>'Staff Logins','href'=>'staff_login_report.php','iconclass'=>'departments','title'=>'Staff Logins','class'=>'ibw-r_new');
$subnav[]=array('desc'=>'HOD Logins','href'=>'hod_login_report.php','iconclass'=>'departments','title'=>'Staff Logins','class'=>'ibw-r_new');

$subnav[]=array('desc'=>'Audit Sample Based','href'=>'audit_report.php','iconclass'=>'departments','title'=>'Audit Sample Based','class'=>'ibw-r_new');
$subnav[]=array('desc'=>'Geographical View','href'=>'status_summary_report.php','iconclass'=>'departments','title'=>'Audit Sample Based','class'=>'ibw-r_new');

//$subnav[]=array('desc'=>'Main&nbsp;SDMS','href'=>'sdms_summary.php','iconclass'=>'departments','title'=>'SDMS','class'=>'ibw-geog_r');
}

/*
$subnav[]=array('desc'=>'Complaint&nbsp;Summary','href'=>'report_new.php','iconclass'=>'users','title'=>'Complaint&nbsp;Summary','class'=>'ibw-r_new');
$subnav[]=array('desc'=>'Complaints Topics','href'=>'comlainttopics_new.php','iconclass'=>'departments','title'=>'Complaints Topics','class'=>'ibw-r_new');				
$subnav[]=array('desc'=>'Complaint&nbsp;Source','href'=>'complaintsource_new.php','iconclass'=>'groups','title'=>'Complaint&nbsp;Source','class'=>'ibw-r_new');									
$subnav[]=array('desc'=>'Department Type','href'=>'complaintdepartment_type_new.php','iconclass'=>'teams','title'=>'Department Type','class'=>'ibw-r_new');
$subnav[]=array('desc'=>'Department','href'=>'complaintdepartment_new.php','iconclass'=>'teams','title'=>'Department','class'=>'ibw-r_new');
$subnav[]=array('desc'=>'Complaint Overall Summary','href'=>'aera_new.php','iconclass'=>'groups','title'=>'Overall Complaints SummaryArea&nbsp;Report','class'=>'ibw-r_new');
$subnav[]=array('desc'=>'Compalint Status Summary','href'=>'comlaintstatus_summary.php','iconclass'=>'departments','title'=>'Compalint Status Summary','class'=>'ibw-r_new');
$subnav[]=array('desc'=>'Overall District Wise','href'=>'report_districtwise.php','iconclass'=>'groups','title'=>'Overall District &nbsp;Wise','class'=>'ibw-r_new');
$subnav[]=array('desc'=>'Complaint Category Wise','href'=>'aera_topic_new.php','iconclass'=>'groups','title'=>'Overall Complaints SummaryArea&nbsp;Report','class'=>'ibw-r_new');
$subnav[]=array('desc'=>'Activity Report','href'=>'activity_report.php','iconclass'=>'groups','title'=>'Activity&nbsp;Report','class'=>'ibw-r_new');
$subnav[]=array('desc'=>'Role Activity Report','href'=>'role_activity_new.php','iconclass'=>'groups','title'=>'Role&nbsp;Activity&nbsp;Report','class'=>'ibw-r_new');
//need a graph report here 
$subnav[]=array('desc'=>'Staff CHO','href'=>'comlaintstaff_new.php','iconclass'=>'departments','title'=>'Staff CHO','class'=>'ibw-r_new');
$subnav[]=array('desc'=>'Staff CCR','href'=>'comlaintstaff_ccr_new.php','iconclass'=>'departments','title'=>'Staff CCR','class'=>'ibw-r_new');			
$subnav[]=array('desc'=>'User Activity','href'=>'user_activity_new.php','iconclass'=>'departments','title'=>'User Activity','class'=>'ibw-r_new');
$subnav[]=array('desc'=>'Role Activity','href'=>'role_activity_new.php','iconclass'=>'departments','title'=>'Role Activity','class'=>'ibw-r_new');
$subnav[]=array('desc'=>'Report &nbsp;Summary','href'=>'report_sdms_summary.php','iconclass'=>'users','title'=>'report_sdms_summary','class'=>'ibw-sdms');
$subnav[]=array('desc'=>'District&nbsp;Wise','href'=>'report_districtwise.php','iconclass'=>'groups','title'=>'District&nbsp;Wise','class'=>'ibw-sdms');
$subnav[]=array('desc'=>'Category&nbsp;Wise','href'=>'report_categorywise.php','iconclass'=>'groups','title'=>'Category&nbsp;Wise','class'=>'ibw-sdms');
$subnav[]=array('desc'=>'Monthly&nbsp;Category Wise','href'=>'report_monthly_categorywise.php','iconclass'=>'teams','title'=>'Monthly&nbsp;Category Wise','class'=>'ibw-sdms');
$subnav[]=array('desc'=>'Monthly&nbsp;District Wise','href'=>'report_monthly_districtwise.php','iconclass'=>'departments','title'=>'Monthly&nbsp;District Wise','class'=>'ibw-sdms');
$subnav[]=array('desc'=>'Monthly &nbsp;Location Wise','href'=>'report_monthly_locationwise.php','iconclass'=>'departments','title'=>'Monthly&quot;Location Wise','class'=>'ibw-sdms');
$subnav[]=array('desc'=>'Year & Monthly Wise','href'=>'report_year_month.php','iconclass'=>'departments','title'=>'Year & Monthly Wise','class'=>'ibw-sdms');
$subnav[]=array('desc'=>'Monthlywise&nbsp;Gender report','href'=>'gender_wise.php','iconclass'=>'departments','title'=>'Monthlywise&nbsp;Gender report','class'=>'ibw-sdms');
$subnav[]=array('desc'=>'Tehsil Wise&nbsp;Monthly report','href'=>'report_tehsil_month.php','iconclass'=>'departments','title'=>'Tehsil wise&nbsp;Monthlyn report','class'=>'ibw-sdms');
$subnav[]=array('desc'=>'Complaint&nbsp;Summary Gender wise','href'=>'genderwise_complaint_summary.php','iconclass'=>'departments','title'=>'Complaint&nbsp;Summary&nbsp;Gender wise','class'=>'ibw-sdms');
$subnav[]=array('desc'=>'Reports&nbsp;Based on Threads','href'=>'report_thred.php','iconclass'=>'departments','title'=>'Reports&nbsp;Based on Threads','class'=>'ibw-sdms');
$subnav[]=array('desc'=>'Complaint Source&nbsp;Reports','href'=>'report_complaint_source.php','iconclass'=>'departments','title'=>'Complaint Source&nbsp;Reports','class'=>'ibw-sdms');
$subnav[]=array('desc'=>'Pirority Wise &nbsp;Reports','href'=>'report_prioritywise.php','iconclass'=>'departments','title'=>'Pirority Wise &nbsp;Reports','class'=>'ibw-sdms');
$subnav[]=array('desc'=>'Performance &nbsp;Reports','href'=>'performance_report.php','iconclass'=>'departments','title'=>'Performance &nbsp;Reports','class'=>'ibw-sdms');
$subnav[]=array('desc'=>'SMS&nbsp;Reports','href'=>'sms_report.php','iconclass'=>'departments','title'=>'SMS&nbsp;Reports','class'=>'ibw-sdms');
$subnav[]=array('desc'=>'Applicant SMS&nbsp; Log Reports','href'=>'applicant_sms_log.php','iconclass'=>'departments','title'=>'Applicant SMS&nbsp; Log Reports','class'=>'ibw-sdms');
$subnav[]=array('desc'=>'Main&nbsp;SDMS','href'=>'sdms_summary.php','iconclass'=>'departments','title'=>'SDMS','class'=>'ibw-geog_r');
*/										
				    break;   
				   
            }
			
            if($subnav)
                $submenus[$this->getPanel().'.'.strtolower($k)]=$subnav;
        }
        return $submenus;
    }	
	
    function getSubMenu($tab=null){
		$tab=$tab?$tab:$this->activetab;
        return $this->submenus[$this->getPanel().'.'.$tab];
    }
    function getSubNav($tab=null){
        return $this->getSubMenu($tab);
    }
}

class AdminNav extends StaffNav{
    function AdminNav($staff){
        parent::StaffNav($staff, 'admin');
    }
    function getTabs(){
        if(!$this->tabs){
            $tabs=array();
           // $tabs['admin_dashboard']=array('desc'=>'Dashboard','href'=>'admin_dashboard.php','title'=>'Admin Dashboard');
            $tabs['settings']=array('desc'=>'Settings','href'=>'settings.php','title'=>'System Settings');
            $tabs['helptopics']=array('desc'=>'Manage','href'=>'helptopics.php','title'=>'Manage Options');
            $tabs['emails']=array('desc'=>'Emails','href'=>'emails.php','title'=>'Email Settings');
            $tabs['staff']=array('desc'=>'Staff','href'=>'staff.php','title'=>'Manage Staff');
			//$tabs['report']=array('desc'=>'Reports','href'=>'report.php','title'=>'Reports');//////////mine code
			//$tabs['reports_new']=array('desc'=>'SDMS Reports','href'=>'reports_new.php','title'=>'Reports');
			//$tabs['logs']=array('desc'=>'LOGS','href'=>'logs.php','title'=>'Logs File');
            $this->tabs=$tabs;
        }
        return $this->tabs;
    }
    function getSubMenus(){
        $submenus=array();
        foreach($this->getTabs() as $k=>$tab){
            $subnav=array();
            switch(strtolower($k)){
			   case 'admin_dashboard':
                    $subnav[]=array('desc'=>'Dashboard','href'=>'admin_dashboard.php','iconclass'=>'users','class'=>'ibw-admin_dashboard');
					$subnav[]=array('desc'=>'Complaint&nbsp;Summary','href'=>'report.php','iconclass'=>'users','title'=>'Complaint&nbsp;Summary','class'=>'ibw-report');
                    $subnav[]=array('desc'=>'Complaint&nbsp;Source','href'=>'complaintsource.php','iconclass'=>'groups','title'=>'Complaint&nbsp;Source','class'=>'ibw-source_r');
					$subnav[]=array('desc'=>'Area&nbsp;Report','href'=>'aera.php','iconclass'=>'groups','title'=>'Area&nbsp;Report','class'=>'ibw-area_r');
					$subnav[]=array('desc'=>'Department','href'=>'complaintdepartment.php','iconclass'=>'teams','title'=>'Department','class'=>'ibw-deprt_r');
                    $subnav[]=array('desc'=>'Staff Complaints','href'=>'comlaintstaff.php','iconclass'=>'departments','title'=>'Staff Complaints','class'=>'ibw-staff_r');
					$subnav[]=array('desc'=>'Complaints Categories','href'=>'comlainttopics.php','iconclass'=>'departments','title'=>'Complaints Categories','class'=>'ibw-topics_r');
					$subnav[]=array('desc'=>'Geographical','href'=>'geographical.php','iconclass'=>'departments','title'=>'Geographical','class'=>'ibw-geog_r');
                    break;
               case 'settings':
                    $subnav[]=array('desc'=>'System&nbsp;Preferences','href'=>'settings.php?t=system','iconclass'=>'preferences','title'=>'System&nbsp;Preferences','class'=>'ibw-system');
                    $subnav[]=array('desc'=>'Complaints','href'=>'settings.php?t=tickets','iconclass'=>'ticket-settings','title'=>'Complaints','class'=>'ibw-tickets');
                    $subnav[]=array('desc'=>'Emails','href'=>'settings.php?t=emails','iconclass'=>'email-settings','title'=>'Emails','class'=>'ibw-emails');
                    $subnav[]=array('desc'=>'Autoresponder','href'=>'settings.php?t=autoresp','iconclass'=>'email-autoresponders','title'=>'Autoresponder','class'=>'ibw-autoresp');
                   // $subnav[]=array('desc'=>'Alerts&nbsp;&amp;&nbsp;Notices','href'=>'settings.php?t=alerts','iconclass'=>'alert-settings','title'=>'Alerts&nbsp;&amp;&nbsp;Notices','class'=>'ibw-alerts');
                    break;
			   case 'helptopics':
                    $subnav[]=array('desc'=>'Complaint&nbsp;Categories','href'=>'helptopics.php','iconclass'=>'helpTopics','title'=>'Complaint&nbsp;Topics','class'=>'ibw-Complaint_Status');
					$subnav[]=array('desc'=>'Complaint&nbsp;Status','href'=>'status.php','iconclass'=>'helpTopics','title'=>'Complaint&nbsp;Status','class'=>'ibw-helptopics');
                    $subnav[]=array('desc'=>'Complaint&nbsp;Filters','href'=>'filters.php',
                                        'title'=>'Ticket&nbsp;Filters','iconclass'=>'ticketFilters','class'=>'ibw-filters');
                    $subnav[]=array('desc'=>'SLA&nbsp;Plans','href'=>'slas.php','iconclass'=>'sla','title'=>'SLA&nbsp;Plans','class'=>'ibw-slas');
                    break;
               case 'emails':
                    $subnav[]=array('desc'=>'Emails','href'=>'emails.php', 'title'=>'Email Addresses', 'iconclass'=>'emailSettings','class'=>'ibw-email');
                    $subnav[]=array('desc'=>'Banlist','href'=>'banlist.php',
                                        'title'=>'Banned&nbsp;Emails','iconclass'=>'emailDiagnostic','class'=>'ibw-Banlist');
                    $subnav[]=array('desc'=>'Templates','href'=>'templates.php','title'=>'Email Templates','iconclass'=>'emailTemplates','class'=>'ibw-Templates');
                    $subnav[]=array('desc'=>'Diagnostic','href'=>'emailtest.php', 'title'=>'Email Diagnostic', 'iconclass'=>'emailDiagnostic','class'=>'ibw-Diagnostic');
				    $subnav[]=array('desc'=>'Canned&nbsp;Responses','href'=>'canned.php','iconclass'=>'canned','title'=>'Canned&nbsp;Responses','class'=>'ibw-alerts');
                    break;
               case 'staff':
                    $subnav[]=array('desc'=>'Staff&nbsp;Members','href'=>'staff.php','iconclass'=>'users','title'=>'Staff&nbsp;Members','class'=>'ibw-staff');
                    $subnav[]=array('desc'=>'Departments','href'=>'departments.php','iconclass'=>'departments','title'=>'Departments&nbsp;Members','class'=>'ibw-staff_directory');
                    $subnav[]=array('desc'=>'Groups','href'=>'groups.php','iconclass'=>'groups','title'=>'Groups','class'=>'ibw-group');
					//$subnav[]=array('desc'=>'Teams','href'=>'teams.php','iconclass'=>'teams','title'=>'Teams','class'=>'ibw-team');
                    break;
               case 'report':                                          //////////////////////////////mine code
                    $subnav[]=array('desc'=>'Complaint&nbsp;Summary','href'=>'report.php','iconclass'=>'users','title'=>'Complaint&nbsp;Summary','class'=>'ibw-report');
                    $subnav[]=array('desc'=>'Complaint&nbsp;Source','href'=>'complaintsource.php','iconclass'=>'groups','title'=>'Complaint&nbsp;Source','class'=>'ibw-source_r');
					$subnav[]=array('desc'=>'Area&nbsp;Report','href'=>'aera.php','iconclass'=>'groups','title'=>'Area&nbsp;Report','class'=>'ibw-area_r');
					$subnav[]=array('desc'=>'Department','href'=>'complaintdepartment.php','iconclass'=>'teams','title'=>'Department','class'=>'ibw-deprt_r');
                    $subnav[]=array('desc'=>'Staff Complaints','href'=>'comlaintstaff.php','iconclass'=>'departments','title'=>'Staff Complaints','class'=>'ibw-staff_r');
					$subnav[]=array('desc'=>'Complaints Topics','href'=>'comlainttopics.php','iconclass'=>'departments','title'=>'Complaints Topics','class'=>'ibw-topics_r');
					$subnav[]=array('desc'=>'Geographical','href'=>'geographical.php','iconclass'=>'departments','title'=>'Geographical','class'=>'ibw-geog_r');
					$subnav[]=array('desc'=>'Main&nbsp;SDMS','href'=>'sdms_summary.php','iconclass'=>'departments','title'=>'SDMS','class'=>'ibw-geog_r');
                    break;
				case 'reports_new':                  
     $subnav[]=array('desc'=>'Complaint&nbsp;Summary','href'=>'report_new.php','iconclass'=>'users','title'=>'Complaint&nbsp;Summary','class'=>'ibw-sdms');
$subnav[]=array('desc'=>'Complaints Status','href'=>'comlaintstatus_new.php','iconclass'=>'departments','title'=>'Complaints Status','class'=>'ibw-geog_r');
//$subnav[]=array('desc'=>'Inquiry Report','href'=>'complaint_query_info_new.php','iconclass'=>'users','title'=>'Inquiry Report','class'=>'ibw-geog_r');
$subnav[]=array('desc'=>'Complaints Topics','href'=>'comlainttopics_new.php','iconclass'=>'departments','title'=>'Complaints Topics','class'=>'ibw-geog_r');				
$subnav[]=array('desc'=>'Complaint&nbsp;Source','href'=>'complaintsource_new.php','iconclass'=>'groups','title'=>'Complaint&nbsp;Source','class'=>'ibw-geog_r');									
$subnav[]=array('desc'=>'Area&nbsp;Report','href'=>'aera_new.php','iconclass'=>'groups','title'=>'Area&nbsp;Report','class'=>'ibw-geog_r');					
$subnav[]=array('desc'=>'Department Type','href'=>'complaintdepartment_type_new.php','iconclass'=>'teams','title'=>'Department Type','class'=>'ibw-geog_r');
$subnav[]=array('desc'=>'Department','href'=>'complaintdepartment_new.php','iconclass'=>'teams','title'=>'Department','class'=>'ibw-geog_r');
//need an follow up reports here
$subnav[]=array('desc'=>'Status Summary','href'=>'comlaintstatus_summary.php','iconclass'=>'departments','title'=>'Status Summary','class'=>'ibw-geog_r');
//need a graph report here 
$subnav[]=array('desc'=>'Staff CHO','href'=>'comlaintstaff_new.php','iconclass'=>'departments','title'=>'Staff CHO','class'=>'ibw-geog_r');
$subnav[]=array('desc'=>'Staff CCR','href'=>'comlaintstaff_ccr_new.php','iconclass'=>'departments','title'=>'Staff CCR','class'=>'ibw-geog_r');			
$subnav[]=array('desc'=>'User Activity','href'=>'user_activity_new.php','iconclass'=>'departments','title'=>'User Activity','class'=>'ibw-geog_r');
$subnav[]=array('desc'=>'Role Activity','href'=>'role_activity_new.php','iconclass'=>'departments','title'=>'Role Activity','class'=>'ibw-geog_r');
                    break;
			
			}
            if($subnav)
                $submenus[$this->getPanel().'.'.strtolower($k)]=$subnav;
        }
        return $submenus;
    }
}


class UserNav {
    var $navs=array();
    var $activenav;
    var $user;
    function UserNav($user=null, $active=''){
        $this->user=$user;
        $this->navs=$this->getNavs();
        if($active)
            $this->setActiveNav($active);
    }
    function setActiveNav($nav){
        if($nav && $this->navs[$nav]){
            $this->navs[$nav]['active']=true;
            if($this->activenav && $this->activenav!=$nav && $this->navs[$this->activenav])
                 $this->navs[$this->activenav]['active']=false;
            $this->activenav=$nav;
            return true;
        }
        return false;
    }
	
    function getNavLinks(){
        global $cfg;
        //Paths are based on the root dir.
        if(!$this->navs){
            $navs = array();
            $user = $this->user;
            $navs['home']=array('desc'=>'Support&nbsp;Center&nbsp;Home','href'=>'index.php','title'=>'');
            if($cfg && $cfg->isKnowledgebaseEnabled())
                $navs['kb']=array('desc'=>'Knowledgebase','href'=>'kb/index.php','title'=>'');
            $navs['new']=array('desc'=>'Open&nbsp;New&nbsp;Ticket','href'=>'open.php','title'=>'');
            if($user && $user->isValid()) {
                if($cfg && $cfg->showRelatedTickets()) {
                    $navs['tickets']=array('desc'=>sprintf('My&nbsp;Tickets&nbsp;(%d)',$user->getNumTickets()),
                                           'href'=>'tickets.php',
                                            'title'=>'Show all tickets',
											'class'=>'ibw-edit');
                } else {
                    $navs['tickets']=array('desc'=>'View&nbsp;Ticket&nbsp;Thread',
                                           'href'=>sprintf('tickets.php?id=%d',$user->getTicketID()),
                                           'title'=>'View ticket status',
											'class'=>'ibw-edit');
                }
            } else {
                $navs['status']=array('desc'=>'Check Ticket Status','href'=>'view.php','title'=>'');
            }
            $this->navs=$navs;
        }
        return $this->navs;
    }
    function getNavs(){
        return $this->getNavLinks();
    }
}
?>