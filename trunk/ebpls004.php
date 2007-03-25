<?php
if (isset($iSubmitSearch)){
	$itxt_Search=strip_tags($iSearch);
}

//	This page displays the appropriate Menu for the current user.
//global $HTTP_SERVER_VARS;



if (getenv(HTTP_X_FORWARDED_FOR)) {							
    $remoteip = getenv(HTTP_X_FORWARDED_FOR); 
} else { 
    $remoteip = getenv(REMOTE_ADDR);
}	



include'includes/eBPLS_menus-inc.php';
?>
