<?php
$to = $_REQUEST['teacheremail'];
$subject = 'Enquiry :- '.$_REQUEST['subject'];
$txt = $_REQUEST['message'];
$headers = "From: ".$_REQUEST['useremail'];

mail($to,$subject,$txt,$headers);
?>