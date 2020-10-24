$(document).ready(function(){       
    /* LEFT SIDE DATEPICKER */
    $("#menuDatepicker").datepicker();
    /* UI elements datepicker */        
    $("#Datepicker").datepicker(); 
	$("#Datepicker1").datepicker();    

    /* CALENDAR */
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();

    var calendar = $('#calendar').fullCalendar({
            header: {		
                    left: 'prev,next',
                    center: 'title',
                    right: ''
                    //right: 'month,agendaWeek,agendaDay'
            },
            selectable: true,
            selectHelper: true,
            select: function(start, end, allDay) {
                    var title = prompt('Event Title:');
                    if (title) {
                            calendar.fullCalendar('renderEvent',
                                    {
                                            title: title,
                                            start: start,
                                            end: end,
                                            allDay: allDay
                                    },
                                    true // make the event "stick"
                            );
                    }
                    calendar.fullCalendar('unselect');
            },
            editable: true,
            events: [
                    {
                            title: 'All Day Event',
                            start: new Date(y, m, 20),
                            end: new Date(y, m, 21)
                    },
                    {
                            title: 'Long Event',
                            start: new Date(y, m, 1),
                            end: new Date(y, m, 10)
                    }
            ]
    });

    // SELECT2       
        $("#s2_1").select2();
        $("#s2_2").select2();
        
    // CHECKBOXES AND RADIO
        $(".row-form,.row-fluid,.dialog,.loginBox,.block,.block-fluid").find("input:checkbox, input:radio, input:file").not(".skip, input.ibtn").uniform();
        
    // MASKED INPUTS
        
        $("#mask_phone").mask('99 (999) 999-99-99');
        $("#mask_credit").mask('9999-9999-9999-9999');
        $("#mask_date").mask('99/99/9999');
        $("#mask_tin").mask('99-9999999');
        $("#mask_ssn").mask('999-99-9999');
		$("#nic").mask('99999-9999999-9');
		$("#nic1").mask('99999-9999999-9');
		$("#against_cnic").mask('99999-9999999-9');
		
        
    //FORM VALIDATION

        $("#validation").validationEngine({promptPosition : "topLeft", scroll: true});        
        
    // CUSTOM SCROLLING
    
        $(".scroll").mCustomScrollbar();
    
    // ACCORDION 
    
        $(".accordion").accordion({
    collapsible:true,

    beforeActivate: function(event, ui) {
         // The accordion believes a panel is being opened
        if (ui.newHeader[0]) {
            var currHeader  = ui.newHeader;
            var currContent = currHeader.next('.ui-accordion-content');
         // The accordion believes a panel is being closed
        } else {
            var currHeader  = ui.oldHeader;
            var currContent = currHeader.next('.ui-accordion-content');
        }
         // Since we've changed the default behavior, this detects the actual status
        var isPanelSelected = currHeader.attr('aria-selected') == 'true';

         // Toggle the panel's header
        currHeader.toggleClass('ui-corner-all',isPanelSelected).toggleClass('accordion-header-active ui-state-active ui-corner-top',!isPanelSelected).attr('aria-selected',((!isPanelSelected).toString()));

        // Toggle the panel's icon
        currHeader.children('.ui-icon').toggleClass('ui-icon-triangle-1-e',isPanelSelected).toggleClass('ui-icon-triangle-1-s',!isPanelSelected);

         // Toggle the panel's content
        currContent.toggleClass('accordion-content-active',!isPanelSelected)    
        if (isPanelSelected) { currContent.slideUp(); }  else { currContent.slideDown(); }

        return false; // Cancel the default action
    }
});
    
    // PROGRESSBAR
    
    if($("#progressbar-1").length > 0)    
        $("#progressbar-1").anim_progressbar();
    
    if($("#progressbar-2").length > 0){
        var iNow = new Date().setTime(new Date().getTime() + 3 * 1000);
	var iEnd = new Date().setTime(new Date().getTime() + 20 * 1000);
	$('#progressbar-2').anim_progressbar({start: iNow, finish: iEnd, interval: 1});        
    }
    if($("#progressbar-3").length > 0)
        $('#progressbar-3').progressbar({value: 65});
    
    if($("#progressbar-4").length > 0)
        $('#progressbar-4').progressbar({value: 35});
        
    // DIALOG
    
    $("#b_popup_1").dialog({autoOpen: false});
        
        $("#popup_1").click(function(){$("#b_popup_1").dialog('open')});
        
    $("#b_popup_2").dialog({autoOpen: false, show: "blind", hide: "explode"});

        $("#popup_2").click(function(){$("#b_popup_2").dialog('open')});

    $("#b_popup_3").dialog({autoOpen: false, modal: true});
        
        $("#popup_3").click(function(){$("#b_popup_3").dialog('open')});
     //Advance Dialog   
    $("#b_popup_4").dialog({autoOpen: false, 
                            modal: true,
                            width: 640,
                            buttons: {                            
                                "Search": function() {
                                    $( '#search' ).submit();
                                },
								"Reset": function() {
									 $( '#search' ).reset();
                                },
                                Cancel: function() {
                                    $( this ).dialog( "close" );
                                }
    }});
        $("#popup_4").click(function(){$("#b_popup_4").dialog('open')});
    //Print Dialog
	 $("#ba_popup_print").dialog({autoOpen: false, 
                            modal: true,
                            width: 400,
                            buttons: {                            
                                "Print": function() {
                                    $( '#print_form_abc' ).submit();
                                },
                                Cancel: function() {
                                    $( this ).dialog( "close" );
                                }
    }});
	  $("#popup_print").click(function(){$("#ba_popup_print").dialog('open')});
	  
	 //Delete Dialog
	 $("#b_popup_action_del").dialog({autoOpen: false, 
                            modal: true,
                            width: 640,
                            buttons: {                            
                                "Yes": function() {
                                    $( '#confirm_form' ).submit();
                                },
								"Reset": function() {
									 $( '#confirm_form' ).reset();
                                },
                                Cancel: function() {
                                    $( this ).dialog( "close" );
                                }
    }});
	  $("#popup_action_del").click(function(){$("#b_popup_action_del").dialog('open')});
	  
	  //Overdue Dialog
	 $("#ba_popup_overdue").dialog({autoOpen: false, 
                            modal: true,
                            width: 640,
                            buttons: {                            
                                "Yes": function() {
                                    $( '#confirm_form' ).submit();
                                },
								"Reset": function() {
									 $( '#confirm_form' ).reset();
                                },
                                Cancel: function() {
                                    $( this ).dialog( "close" );
                                }
    }});
	  $("#popup_overdue").click( function(){$("#ba_popup_overdue").dialog('open')});
	  
	   //unassigned Dialog
	 $("#ba_popup_unassigned").dialog({autoOpen: false, 
                            modal: true,
                            width: 640,
                            buttons: {                            
                                "Yes": function() {
                                    $( '#confirm_form' ).submit();
                                },
								"Reset": function() {
									 $( '#confirm_form' ).reset();
                                },
                                Cancel: function() {
                                    $( this ).dialog( "close" );
                                }
    }});
	  $("#popup_unassigned").click( function(){$("#ba_popup_unassigned").dialog('open')});
	  
	   //Ban Dialog
	 $("#ba_popup_banemail").dialog({autoOpen: false, 
                            modal: true,
                            width: 640,
                            buttons: {                            
                                "Yes": function() {
                                    $( '#confirm_form' ).submit();
                                },
								"Reset": function() {
									 $( '#confirm_form' ).reset();
                                },
                                Cancel: function() {
                                    $( this ).dialog( "close" );
                                }
    }});
	  $("#popup_banemail").click( function(){$("#ba_popup_banemail").dialog('open')});
	  
	  //Close Dialog
	  $("#ba_btn_close").dialog({autoOpen: false, 
                            modal: true,
                            width: 640,
                            buttons: {                            
                                "Yes": function() {
                                    $( '#status-form' ).submit();
                                },
								"Reset": function() {
									 $( '#status-form' ).reset();
                                },
                                Cancel: function() {
                                    $( this ).dialog( "close" );
                                }
    }});
	  $("#btn_close").click(function(){$("#ba_btn_close").dialog('open')});
	  
	  
	  	  //Close Dialog
	  $("#ba_btn_thread").dialog({autoOpen: false, 
                            modal: true,
                            width: 640,
                            buttons: {                            
                                "Yes": function() {
                                    $( '#thread-options' ).submit();
                                },
								"Reset": function() {
									 $( '#thread-options' ).reset();
                                },
                                Cancel: function() {
                                    $( this ).dialog( "close" );
                                }
    }});
	  $(".btn_thread").click(function(){$("#ba_btn_thread").dialog('open')});
	  
	  
	 //Claim Dialog
	 $("#b_popup_action_claim").dialog({autoOpen: false, 
                            modal: true,
                            width: 640,
                            buttons: {                            
                                "Yes": function() {
                                    $( '#confirm-form' ).submit();
                                },
								"Reset": function() {
									 $( '#confirm-form' ).reset();
                                },
                                Cancel: function() {
                                    $( this ).dialog( "close" );
                                }
    }});
	  $("#popup_action_claim").click(function(){$("#b_popup_action_claim").dialog('open')});
	  
	 //Answered Dialog
	 $("#b_popup_action_answered").dialog({autoOpen: false, 
                            modal: true,
                            width: 640,
                            buttons: {                            
                                "Yes": function() {
                                    $( '#confirm_answer_form' ).submit();
                                },
								"Reset": function() {
									 $( '#confirm_answer_form' ).reset();
                                },
                                Cancel: function() {
                                    $( this ).dialog( "close" );
                                }
    }});
	  $("#popup_action_answered").click(function(){$("#b_popup_action_answered").dialog('open') });
	//UnAnswered Dialog
	$("#b_popup_action_unanswered").dialog({autoOpen: false, 
                            modal: true,
                            width: 640,
                            buttons: {                            
                                "Yes": function() {
                                    $( '#confirm-form' ).submit();
                                },
								"Reset": function() {
									 $( '#confirm-form' ).reset();
                                },
                                Cancel: function() {
                                    $( this ).dialog( "close" );
                                }
    }});
	$("#popup_action_unanswered").click(function(){ $("#b_popup_action_unanswered").dialog('open')  });
	
	
	
	$("#b_popup_action_s_m").dialog({autoOpen: false, 
                            modal: true,
                            width: 640,
                            buttons: {                            
                                "Save": function() {
		 								$( this ).dialog( "close" );
                                },
                                Cancel: function() {
                                    $( this ).dialog( "close" );
                                }
    }}).parent().appendTo("form");
	$("#popup_action_s_m").click(function(){ $("#b_popup_action_s_m").dialog('open')  });
	
	$("#b_popup_action_insurance").dialog({autoOpen: false, 
                            modal: true,
                            width: 640,
                            buttons: {                            
                                "Save": function() {
                                     $( this ).dialog( "close" );
                                },
                                Cancel: function() {
                                    $( this ).dialog( "close" );
                                }
    }}).parent().appendTo("form");
	$("#popup_action_insurance").click(function(){ $("#b_popup_action_insurance").dialog('open')  });
	
	
	$("#b_popup_action_specialized_companies").dialog({autoOpen: false, 
                            modal: true,
                            width: 640,
                            buttons: {                            
                                "Save": function() {
                                    $( this ).dialog( "close" );
                                },
                                Cancel: function() {
                                    $( this ).dialog( "close" );
                                }
    }}).parent().appendTo("form");
	$("#popup_action_specialized_companies").click(function(){ $("#b_popup_action_specialized_companies").dialog('open')  });
	
	
	$("#b_popup_action_e_services").dialog({autoOpen: false, 
                            modal: true,
                            width: 640,
                            buttons: {                            
                                "Save": function() {
                                    $( this ).dialog( "close" );
                                },
                                Cancel: function() {
                                    $( this ).dialog( "close" );
                                }
    }}).parent().appendTo("form");
	$("#popup_action_e_services").click(function(){ $("#b_popup_action_e_services").dialog('open')  });
	
	
	$("#b_popup_action_company_registration").dialog({autoOpen: false, 
                            modal: true,
                            width: 640,
                            buttons: {                            
                                "Save": function() {
                                    $( this ).dialog( "close" );
                                },
                                Cancel: function() {
                                    $( this ).dialog( "close" );
                                }
    }}).parent().appendTo("form");
	$("#popup_action_company_registration").click(function(){ $("#b_popup_action_company_registration").dialog('open')  });
	
	
	$("#b_popup_action_company_supervision").dialog({autoOpen: false, 
                            modal: true,
                            width: 640,
                            buttons: {                            
                                "Save": function() {
                                    $( this ).dialog( "close" );
                                },
                                Cancel: function() {
                                    $( this ).dialog( "close" );
                                }
    }}).parent().appendTo("form");
	$("#popup_action_company_supervision").click(function(){ $("#b_popup_action_company_supervision").dialog('open')});
	
    // SLIDER
    
        $("#slider_1").slider({
            value: 60,
            orientation: "horizontal",
            range: "min",
            animate: true,
            slide: function( event, ui ) {
                $( "#slider_1_amount" ).html( "$" + ui.value );
            }
        });
        
        $("#slider_2").slider({
            values: [ 17, 67 ],
            orientation: "horizontal",
            range: true,
            animate: true,
            slide: function( event, ui ) {
                $( "#slider_2_amount" ).html( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
            }            
        });    
            
        $("#slider_3").slider({
            orientation: "vertical",
            range: "min",
            min: 0,
            max: 100,
            value: 10,
            slide: function( event, ui ) {
                $( "#slider_3_amount" ).html( '$'+ui.value );
            }            
        }); 

        $("#slider_4").slider({
            orientation: "vertical",
            range: true,
            values: [ 17, 67 ]
        }); 

        $("#slider_5").slider({
            orientation: "vertical",            
            range: "max",
            min: 1,
            max: 10,
            value: 2
        }); 
        
        
    // TABS
    
        $( ".tabs" ).tabs();

   // TOOLTIPS

        $('.tt').qtip({ style: {name: 'aquarius' },
                        position: {
                            corner: {
                                target: 'topRight',
                                tooltip: 'bottomLeft'
                            }
                        } 
                    });
        
        $('.ttRC').qtip({ style: { name: 'aquarius' },
                        position: {
                            corner: {
                                target: 'rightMiddle',
                                tooltip: 'leftMiddle'
                            }
                        } 
                    });        

        $('.ttRB').qtip({ style: { name: 'aquarius' },
                        position: {
                            corner: {
                                target: 'bottomRight',
                                tooltip: 'topLeft'
                            }
                        } 
                    });
                    
        $('.ttLT').qtip({ style: { name: 'aquarius' },
                        position: {
                            corner: {
                                target: 'topLeft',
                                tooltip: 'bottomRight'
                            }
                        } 
                    });
        
        $('.ttLC').qtip({ style: { name: 'aquarius' },
                        position: {
                            corner: {
                                target: 'leftMiddle',
                                tooltip: 'rightMiddle'
                            }
                        } 
                    });        

        $('.ttLB').qtip({ style: { name: 'aquarius' },
                        position: {
                            corner: {
                                target: 'bottomLeft',
                                tooltip: 'topRight'
                            }
                        } 
                    });
         
         // Bootstrap tooltip
         $(".tip").tooltip({placement: 'top', trigger: 'hover'});
         $(".tipb").tooltip({placement: 'bottom', trigger: 'hover'});
         $(".tipl").tooltip({placement: 'left', trigger: 'hover'});
         $(".tipr").tooltip({placement: 'right', trigger: 'hover'});


        // SORTABLE       
            $("#sort_1").sortable({placeholder: "placeholder"});
            $("#sort_1").disableSelection();    
            
        // SELECTABLE
            $("#selectable_1").selectable();
            
            
        // WYSIWIG HTML EDITOR
            if($("#wysiwyg").length > 0){
                editor = $("#wysiwyg").cleditor({width:"100%", height:"100%"})[0].focus();                
            }                                          
            if($("#mail_wysiwyg").length > 0)
                m_editor = $("#mail_wysiwyg").cleditor({width:"100%", height:"100%",controls:"bold italic underline strikethrough | font size style | color highlight removeformat | bullets numbering | outdent alignleft center alignright justify"})[0].focus();
            
            $('#sendmail').on('shown', function () {
                m_editor.refresh();
                $(this).find('.uploader').show();
            });            
            
        // WYSIWIG HTML EDITOR    
            
         // Sortable table
         if($("#tSortable").length > 0)
         {
            $("#tSortable").dataTable({"iDisplayLength": 25,
			  "bInfo": false,
			  "tSortable_filter":false,
			"aLengthMenu": false, 
			"aoColumns": [ { "bSortable": false },null, null, null, null, null, null, null]});
		 }
		 
		 if($("#tSortable_2").length > 0)
         {
           $("#tSortable_2").dataTable({
			   "iDisplayLength": 5,
		       "sPaginationType": "full_numbers",
			   "bLengthChange": false,
			   "bFilter": false,
			   "bInfo": false,
			   "bPaginate": true,
			    "aoColumns": [ { "bSortable": false }, null, null, null, null]});
         }
         if($("#tSortable_6").length > 0)
         {
			
            $("#tSortable_6").dataTable({"iDisplayLength": 6, 
			"aLengthMenu": [5,10,15,20,25], 
			"sPaginationType": "full_numbers", 
			"aoColumns": [ { "bSortable": false },null, null, null, null, null]});
		
         }
		 
		 if($("#tSortable_7").length > 0)
         {
            $("#tSortable_7").dataTable({"iDisplayLength": 25, 
			"bInfo": false,
			"tSortable_filter":false,
			"aLengthMenu": false, 
			"bLengthChange": false,
			   "bFilter": false,
			   "bPaginate": true,
			"aoColumns": [{ "bSortable": [ "desc" ] },null, null, null, null, null, null]});
		
         }
		 if($("#tSortable_5").length > 0)
         {
            $("#tSortable_5").dataTable({"iDisplayLength": 5, 
			"aLengthMenu": [5,10,15,20,25], 
			"sPaginationType": "full_numbers", 
			"aoColumns": [ { "bSortable": false },null, null, null, null]});
         }
         
         
         //File manager
         
         if($("#filemanager").length > 0){
             $("#filemanager").elfinder({url : 'php/elfinder/connector.php'}).elfinder('instance');
         }            
         
         // File uploader
         if($("#uploader_v5").length > 0){
            $("#uploader_v5").pluploadQueue({		
                    runtimes : 'html5',
                    url : 'php/pluploader/upload.php',
                    max_file_size : '1mb',
                    chunk_size : '1mb',
                    unique_names : true,
                    dragdrop : true,

                    resize : {width : 320, height : 240, quality : 100},

                    filters : [
                            {title : "Image files", extensions : "jpg,gif,png"},
                            {title : "Zip files", extensions : "zip"}
                    ]
            });
         }
         if($("#uploader_v4").length > 0){
            $("#uploader_v4").pluploadQueue({		
                    runtimes : 'html4',
                    url : 'php/pluploader/upload.php',
                    unique_names : true,
                    filters : [
                            {title : "Image files", extensions : "jpg,gif,png"},
                            {title : "Zip files", extensions : "zip"}
                    ]
            });
         }                  
         
         /* Multiselect */
         if($("#multiselect").length > 0){
            $("#multiselect").multiSelect();
         }
         if($("#fmultiselect").length > 0){
            $("#fmultiselect").multiSelect({
                selectableHeader: "<div class='multipleselect-header'>Selectable item</div>",
                selectedHeader: "<div class='multipleselect-header'>Selected items</div>"
            });
            $('#multiselect-selectAll').click(function(){
                $('#fmultiselect').multiSelect('select_all');
                return false;
            });
            $('#multiselect-deselectAll').click(function(){
                $('#fmultiselect').multiSelect('deselect_all');
                return false;
            });
            $('#multiselect-selectIndia').click(function(){
                $('#fmultiselect').multiSelect('select', 'in');
                return false;
            });         
         }
         if($(".tags").length > 0)
            $(".tags").tagsInput({'width':'100%',
                                'height':'auto'});         
   
    // Wizard
    if($("#wizard").length > 0) $('#wizard').stepy();
    
    if($("#wizard_validate").length > 0){
        
        $("#wizard_validate").validationEngine('attach',{promptPosition : "topLeft"});
        
        $('#wizard_validate').stepy({
            back: function(index) {                                                                
                //if(!$("#wizard_validate").validationEngine('validate')) return false; //uncomment if u need to validate on back click                
            }, 
            next: function(index) {                
                if(!$("#wizard_validate").validationEngine('validate')) return false;                
            }, 
            finish: function(index) {                
                if(!$("#wizard_validate").validationEngine('validate')) return false;
            }            
        });
    }
    // eof wizard   
   
   // new selector case insensivity        
        $.expr[':'].containsi = function(a, i, m) {
            return jQuery(a).text().toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
        };        
   // 
   
    // Pnotify notifier
        $.pnotify.defaults.history = false;
        $.pnotify.defaults.delay = 3000;          
        
        
    $('.ibtn').iButton();
    
    // Scroll up plugin
     $.scrollUp({scrollText: '^'});
    // eof scroll up plugin    
});


$('.wrapper').resize(function(){

    if($("#wysiwyg").length > 0) editor.refresh();
    if($("#mail_wysiwyg").length > 0) m_editor.refresh();

    
});

function notify(title, text){
    $.pnotify({title: title, text: text, opacity: .8, addclass: 'palert'});
}
function notify_s(title,text){
    $.pnotify({title: title, text: text, opacity: .8, type: 'success'});            
}
function notify_i(title,text){
    $.pnotify({title: title, text: text, opacity: .8, type: 'info'});            
}
function notify_e(title,text){
    $.pnotify({title: title, text: text, opacity: .8, type: 'error'});            
}