<?php
session_start();

$status = $_GET['status'];

if($status=="success")
{
    if(!session_is_registered(myusername))
    header("location:ldap_login.php");
    
echo "
<html>
<body>
Login Successful
<a href='login_status.php?status=logout'>Logout</a>
</body>
</html>
";
}

if($status=="logout")
{
    session_start();
    session_destroy();
    header("location:ldap_login.php");
}
?>