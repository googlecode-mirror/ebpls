<?php
//	eBPLS_PAGE_LOGOUT : this page allows user to logout
//if (isset($ThUserData)) setUserLogout();
//setcookie("godmode",md5("iamgod"),"1");

if ($GLOBALS['watbrowser']=='msie') {
		
			$strQuery =mysql_query("UPDATE ebpls_user SET login = NOW(), logout = NOW() WHERE id = '$ThUserData[id]'") 
						or die (mysql_error());
						setUserLogout();
		} else {
		setUserLogout();
		}

$delses = mysql_query("delete from user_session where ip_add='$remoteip' and user_id='$ThUserData[id]'");
header("Location: index.php?logout=1");
?>
