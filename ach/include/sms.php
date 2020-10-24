<?php
header("Content-type: text/plain");

#====================*	GETTING URL PARAMETERS
$pass= 					trim(rawurldecode($_GET['pwd']));			#Any secret key for authentication.
$sender= 				trim(rawurldecode($_GET['sender']));		#Sender Number Format +923xxxxxxxxxx
$fulltext=	 			trim(rawurldecode($_GET['fulltext']));		#SMS Body text including first word.
$gsmnetwork= 			trim(rawurldecode($_GET['gsmnetwork']));	#Receiver Network Name Format Ufone-PK, Telenor-PK etc
#====================*	END	=	GETTING URL PARAMETERS



#====================*	SECRET KEY MATCHING
if($pass!="secp_g3s99321")
{
echo "Unauthorized Access!";
exit();	
}
#====================*	SECRET KEY MATCHING




echo "SMS Received";	#Echo any reply you want to be received to querier. 
?>
