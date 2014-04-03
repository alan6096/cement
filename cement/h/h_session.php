<?php 
session_start();
//ob_start();

// Authorization check
if(!session_is_registered(myusername))
{
    header("Location: ldap_login.php");
    //exit();
}
//ob_end_flush();

?>
