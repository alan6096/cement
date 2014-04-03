<?php
//ob_start();

$ldaphost = 'ldap.cmsb.com.my';
//$ldapport = 389;

$ds = ldap_connect($ldaphost, $ldapport)
or die("Could not connect to $ldaphost");
    ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
//ldap_set_option($ds, LDAP_OPT_DEBUG_LEVEL, 7);
if ($ds) 
{
    $username = $_POST['myusername'];
    $upasswd = $_POST['mypassword'];

    $ldapbind=ldap_bind($ds, "uid=$username,ou=users,dc=cmsb,dc=com,dc=my",$upasswd);
    
    $sr=ldap_search($ds, "dc=cmsb,dc=com,dc=my", "uid=$username");
    $info = ldap_get_entries($ds, $sr);
    
    if ($ldapbind) 
    {
        //print "Congratulations! $username is authenticated.";
        for ($i=0; $i<$info["count"]; $i++) 
        {
            //echo "dn is: " . $info[$i]["dn"] . "<br />";
            //echo "first cn entry is: " . $info[$i]["cn"][0] . "<br />";
            //echo "first email entry is: " . $info[$i]["mail"][0] . "<br /><hr />";
            $email = $info[$i]["mail"][0];
            $uid = $info[$i]["uid"][0];
            $name = $info[$i]["cn"][0];
        }

        session_register("myusername");
        session_register("mypassword");
        $_SESSION['username'] = $uid;
        $_SESSION['email'] = $email;
        $_SESSION['name'] = $name;
		$_SESSION['id'] = $uid.date("Y");
        
        //echo "Closing connection";
        ldap_close($ds);
		if($uid=="bcp")
			header("location:admin_tni.php");
		else
        header("location:index1.php");
    }
    else 
        {header("location:ldap_login.php?status=Access Denied");}

//ob_end_flush();
}
?>