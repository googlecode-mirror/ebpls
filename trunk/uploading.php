<?php

//$url = "http://ebplsncc.dap.edu.ph/index.php?part=4"; // target of the redirect
$url = "http://192.168.1.224/scripts/ebpls-site/index.php?part=4"; // target of the redirect
$delay = "3"; // 3 second delay

echo '<meta http-equiv="refresh" content="'.$delay.';url='.$url.'">';

// In PHP versions earlier than 4.1.0, $HTTP_POST_FILES should be used instead
// of $_FILES.


require_once "./includes/eBPLS_header.php";

$the_image ='lgu_logo.gif';
//$uploaddir = '/var/www/html/dotproject/logo/';
$uploaddir = '/var/www/html/scripts/ebpls-site/logo/';
$uploadfile = $uploaddir . $the_image;

print "<pre>";
if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
   print "Logo successfully uploaded. ";
} else {
   print "<center>\n";
   print "Image file too big. Please limit file size to 120KB below\n";
   print "<br><input type='BUTTON' name='_BACK' onClick='javascript:history.go(-1)' value='B A C K'>\n";
   print "</center>\n";
}
print "</pre>";

require_once "./includes/eBPLS_footer.php";
?> 