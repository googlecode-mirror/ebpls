<!---- Writes Color Scheme Preferences from colorScheme.php to menu_pref.js file --->

<?php
require_once "includes/config.php";
$url = eBPLS_APP_URL."index.php?part=23"; // target of the redirect
$delay = "2"; // 2 second delay

echo '<meta http-equiv="refresh" content="'.$delay.';url='.$url.'">';

//require_once "./includes/eBPLS_header.php";


$prefFile = './includes/menu_pref.js';

$ebpls_bgColor = $_POST['ebpls_bgColor'];
$ebpls_fontSize = $_POST['ebpls_fontSize'];
$ebpls_fontWeight = $_POST['ebpls_fontWeight'];
$ebpls_fontFamily = $_POST['ebpls_fontFamily'];
$ebpls_fontColor = $_POST['ebpls_fontColor'];
$ebpls_fontColorHilite = $_POST['ebpls_fontColorHilite'];
$ebpls_bgColorBorder = $_POST['ebpls_bgColorBorder'];
$ebpls_menuBorder = $_POST['ebpls_menuBorder'];
$ebpls_menuItemBorder = $_POST['ebpls_menuItemBorder'];
$ebpls_menuItemBgColor = $_POST['ebpls_menuItemBgColor'];
$ebpls_menuHiliteBgColor = $_POST['ebpls_menuHiliteBgColor'];
$butt = $_POST['butt'];

if ($butt == 'Load Default Values') {
	$somecontent = "function menuScheme(x) { var scheme = new Array(\"blue\",12,\"plain\",\"arial\",\"white\",\"black\",\"black\",1,1,\"#8080FF\",\"#C0C0FF\"); return scheme[x]; }";
} else {
	$somecontent = "function menuScheme(x) { var scheme = new Array(\"$ebpls_bgColor\",$ebpls_fontSize,\"$ebpls_fontWeight\",\"$ebpls_fontFamily\",\"$ebpls_fontColor\",\"$ebpls_fontColorHilite\",\"$ebpls_bgColorBorder\",$ebpls_menuBorder,$ebpls_menuItemBorder,\"$ebpls_menuItemBgColor\",\"$ebpls_menuHiliteBgColor\"); return scheme[x]; }";
}


// Let's make sure the file exists and is writable first.
if (is_writable($prefFile)) {

   if (!$handle = fopen($prefFile, 'w')) {
         echo "Cannot open file ($prefFile)";
         exit;
   }

   // Write $somecontent to our opened file.
   if (fwrite($handle, $somecontent) === FALSE) {
       echo "<center><br><br><h3>Cannot write to file ($filename)</h3><br>\n";
       exit;
   }
  
   echo "<center><br><br><h3>Color Scheme Changed!</h3><br>\n";

  
   fclose($handle);
                  
} else {
   echo "The file $filename is not writable";
}

//require_once "./includes/eBPLS_header.php";
?> 
