<?

require_once("ebpls-php-lib/ebpls.global.const.php");
require_once("ebpls-php-lib/ebpls.global.db.funcs.php");

/********************************************************

Data validation functions definition.

Serves as global functions for data checking before storing to database.

*********************************************************/


function is_valid_gender ( $value ) {

	global $gGenderValues;
	$value = strtoupper($value);
	if ( isset( $gGenderValues[$value]  ) ) {

		return true;

	}

	return false;

}

function get_gender_values (  ) {

	global $gGenderValues;

	return $gGenderValues;

}

function is_valid_marital_status ( $value ) {

	global $gMaritalStatusValues;

	$value = strtoupper($value);

	if ( in_array($value, $gMaritalStatusValues ) ) {

		return true;

	}

	return false;

}

function get_marital_status ( ) {

	global $gMaritalStatusValues;

	return $gMaritalStatusValues;

}


/*
function is_valid_date ( $str_date ) {

	$timestamp = strtotime($str_date);
	echo "<hr>bday $timestamp:  $str_date<hr>";
	if ( $timestamp === -1 ) {

		return 0;

	} else {

		$nYear = @date("Y", $timestamp);
		$nDay = @date("d", $timestamp);
		$nMonth = @date("m", $timestamp);

		echo "$nYear,$nDay,$nMonth";

		$bValid = checkdate($nMonth,$nDay,$nYear);

	}

	if ( $bValid ) {

		return 1;

	} else {

		return 0;

	}

}
*/

function is_valid_date ( $str_date ) {

	// check date

	if ( $str_date == null )  return 1;


	if ( !ereg("([0-9]{4})-([0-9]{2})-([0-9]{2})", $str_date, $date_arr) ) {

		return 0;

	}

	return 1;

	//print_r($date_arr);
	// check month
	$mo_val = intval($date_arr[2]);
	if ( $mo_val < 0 && $mo_val > 12 ) {

		return 0;

	}


	// check day range
	$lastday = strftime("%d", mktime (0,0,0,$mo_val+1,0,2000) );
	$day_val = intval($date_arr[3]);

	if ( $day_val<=0 || $day_val>$lastday ) {

		//echo "<HR>invalid day range $day_val<HR>";
		return 0;

	}

	// year
	$yr_val = intval($dat_arr[4]);
	if ( $yr_val < 1900 &&  $yr_val > date("Y") ) {

		//echo "<HR>invalid yr range $yr_val<HR>";
		return 0;

	}

	$timestamp = strtotime($str_date);
	if ( $timestamp === -1 ) {

		return 0;

	} else {

		$nYear = @date("Y", $timestamp);
		$nDay = @date("d", $timestamp);
		$nMonth = @date("m", $timestamp);

		$bValid = checkdate($nMonth,$nDay,$nYear);

	}

	if ( $bValid ) {

		return 1;

	} else {

		return 0;

	}

}



// ctc callback functions
function is_valid_ctc_code ( $value ) {

	return eregi("CTC[0-9]{13}", $value );

}

function is_not_empty( $value ) {

	if ( $value!=NULL && $value!="" ) {
		return 1;
	}

	return 0;

}

function is_valid_number ( $value ) {

	if ( $value!=NULL && is_numeric($value) ) {

		return 1;
	}

	return 0;

}

function is_valid_email( $value ) {


	if ( eregi("(.*)@(.*)", $value ) ) {

		return 1;

	}

	return 0;

}




function is_valid_ctc_type ( $value ) {

	global $gValidCTCTypes;

	$value = strtoupper($value);

	if ( in_array( $value, $gValidCTCTypes) ) {

		return true;

	}

	return false;

}


?>
