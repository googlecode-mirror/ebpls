<?php
switch ($orderbyasdes) {
	case 1:
		$orderbyasdes=0;
        	$ascdesc='asc';	
	break;
	case 0:
		$orderbyasdes=1;
                $ascdesc='desc';
	break;
}
if ($ascdesc1=='') {
        $ascdesc1=$ascdesc;
} else {
        $ascdesc=$ascdesc1;
}

switch ($action_){
	case 0:
	$tbl_current=$DBprefix.""."_nature";	
	break;

	case 1:
	$tbl_current=$DBprefix.""."_nature";	
	break;
	
	case 2:
	$tbl_current=$DBprefix.""."_requirements";	
	break;

	case 21:
	$tbl_current=$DBprefix.""."_requirements";	
	break;

	case 3:
	$tbl_current=$DBprefix.""."_tfo";	
	break;
	
	case 5:
	$tbl_current=$DBprefix.""."_tfo";	
	break;

	case 6:
	$tbl_current=$DBprefix.""."_preference";	
	break;

	case 7:
	$tbl_current=$DBprefix.""."_penalty";	
	break;

	case 9:
	$tbl_current=$DBprefix.""."_requirements";	
	break;

	case 91:
	$tbl_current=$DBprefix.""."_requirements";	
	break;

}

//define constant here

define("MODTITLE", "Business Permit Licensing System Setup");
define("MODTITLE1", "REFERENCES");
define("MODTITLESTYLE1","<font size=+1><b><i>");
define("MODTITLESTYLE2","</i></b></font>");

/* ******************** */
/* ******************** */
/* ******************** */

// display error message
function displayErrorMsg($message)
{
	echo "<p align=center><font color=#ffffff size=+1><b><i>$message</i></b></font></p>";	
}

//delete old cookies
function deleteCookies()
{
	for ($ictr=0; $ictr<$total_ictr; $ictr++)
	{
		setcookie("ThUserData[$ictr]", "");
	}
	setcookie("ThUserData");
}


?>
