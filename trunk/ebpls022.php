<?php

//$url = "http://ebplsncc.dap.edu.ph/index.php?part=4"; // target of the redirect
$url = eBPLS_APP_URL."index.php?part=21"; // target of the redirect
$delay = "3"; // 3 second delay

echo '<meta http-equiv="refresh" content="'.$delay.';url='.$url.'">';

// In PHP versions earlier than 4.1.0, $HTTP_POST_FILES should be used instead
// of $_FILES.

require_once "includes/config.php";

$the_image ='lgu_logo.gif';
$uploaddir = '/var/www/html/dotproject/logo/';
//$uploaddir = '/var/www/html/scripts/ebpls-site/logo/';
$uploadfile = $uploaddir . $the_image;

print "<pre>";
if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
   print "<center>\n";
   print "<B>Logo successfully uploaded.</B>";
   print "</center>\n";
} else {
   print "<center>\n";
   print "<B>Image file too big. Please limit file size to 120KB below\n</B>";
   print "<br><input type='BUTTON' name='_BACK' onClick='javascript:history.go(-1)' value='B A C K'>\n";
   print "</center>\n";
}
print "</pre>";

//require_once "./includes/eBPLS_footer.php";
?> 
