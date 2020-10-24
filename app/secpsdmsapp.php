<?php
$message=$_REQUEST['tag'].'<br>'. $_POST['token'];
require('../ach/client.inc.php');
define('SOURCE','Web'); //Ticket source.
require_once(INCLUDE_DIR.'class.ticket.php');
require_once(INCLUDE_DIR.'class.dept.php');
require_once(INCLUDE_DIR.'class.status.php');
require_once(INCLUDE_DIR.'class.filter.php');
require_once(INCLUDE_DIR.'class.canned.php');
$errors=array();
$vars=array();
$_REQUEST['tag']=='nosearch';


	  
   $file_path = "app_uploads/";  
   $file_path = $file_path . basename( $_FILES['uploaded_file']['name']);
   if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $file_path)) {
       $name =  $_FILES['uploaded_file']['name'];
   } else{
      $name = 'no file';
   }
   
if(isset($_POST['image']))
{
$signs=$_POST['image'];
$name=$_POST['name'];
$sig=base64_decode($signs);
header('Content-Type: bitmap; charset=utf-8');
$file = fopen('app_uploads/'.$name.'.jpg', 'wb');
fwrite($file, $sig);
fclose($file);  
}

if (isset($_POST['name'])) 
{	
	$vars = $_POST;
		
		
if(($ticket=Ticket::create($vars, $errors, SOURCE))){

        $b=array();

		$b["token_no"]=$ticket->getId();
		
		//$b["token_no"]='239432894';

		echo json_encode($b);

	 }
}

elseif($_REQUEST['tag']=='Search')
{	

if(isset($_POST['token']))
  {

	$complaint_nos = $_POST['token'];

	$result= "Select a.*,b.* from hrd_ticket a INNER JOIN hrd_status b ON (a.complaint_status=b.status_id) AND a.ticket_Id='".$complaint_nos."'";

	$res_status=mysql_query($result);

	 if(mysql_num_rows($res_status)>0)

         {

          $response["Search"]=array();

          while($row=mysql_fetch_array($res_status))

          {

           $dept=array();

           $dept["token"]=$complaint_nos;  

           $dept["sqlticket"]=$row["ticket_Id"];

		   $dept["sqlticket"]=$row["status_title"];

           array_push($response["Search"],$dept);

           

          }

          $response["success"]=1;

          echo json_encode($response);

          

         }

         else 

         {

       // no products found

        $response["success"] = 0;

        $response["message"] = "Please Enter Correct Token Number";



       // echo no users JSON

        echo json_encode($response);

            }

  }
}

elseif($_REQUEST['tag']=='Missing')
{	

if(isset($_POST['nic']))
{
/*$string = $_POST['nic'];

$string = implode("-", str_split($string, 5));

$string[9] = '';*/



	$result= "Select * from hrd_ticket where nic='".$_POST['nic']."'";

	$res_status=mysql_query($result);

	 if(mysql_num_rows($res_status)>0)

         {

          $response["Missing"]=array();

          while($row=mysql_fetch_array($res_status))

          {

			

			//send nic email code to client 

           $dept=array();

           $dept["token"]='Tocken Send to you Email Addres.';

           array_push($response["Missing"],$dept);

           

          }

          $response["success"]=1;

          echo json_encode($response);

          

         }

         else 

         {

       // no products found

       $response["success"] = 0;

       $response["message"] = "Please Enter Correct NIC Number";



       // echo no users JSON

       echo json_encode($response);

            }

  }

  else

  {

       $response["success"] = 0;

       $response["message"] = "Please Enter NIC Number";



       // echo no users JSON

       echo json_encode($response);

  }

}

elseif($_REQUEST['tag']=='dept') 
{

          $result=mysql_query("SELECT * FROM hrd_department") or die(mysql_errno());

   

         if(mysql_num_rows($result)>0)

         {

          $response["departmwnt"]=array();

          while($row=mysql_fetch_array($result))

          {

           $dept=array();

           $dept["id"]=$row["dept_id"];  

           $dept["dept_name"]=$row["dept_name"];

           array_push($response["departmwnt"],$dept);

           

          }

          $response["success"]=1;

          echo json_encode($response);

          

         }

         else 

         {

       // no products found

       $response["success"] = 0;

       $response["message"] = "No Department found";



       // echo no users JSON

       echo json_encode($response);

            }

   

         }

elseif($_REQUEST['tag']=='type') 
{

	          $result=mysql_query("SELECT * FROM hrd_help_topic where topic_pid ='0'") or die(mysql_errno());

   

         if(mysql_num_rows($result)>0)

         {

          $response["complaint_type"]=array();

          while($row=mysql_fetch_array($result))

          {

           $dept=array();

           $dept["type_id"]=$row["topic_id"];  

           $dept["type_name"]=$row["topic"];

           array_push($response["complaint_type"],$dept);

           

          }

          $response["success"]=1;

          echo json_encode($response);

          

         }

         else 

         {

       // no products found

       $response["success"] = 0;

       $response["message"] = "No Type found";



       // echo no users JSON

       echo json_encode($response);

            }

}

 else {

       $response["success"] = 0;

       $response["message"] = "Required field(s) is missing";

       echo json_encode($response);

      }		 

?>