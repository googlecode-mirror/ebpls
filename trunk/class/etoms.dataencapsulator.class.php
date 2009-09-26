<?

/************************************************************************************

Module : ebpls.dataencasulator.class.php

Dependencies :
	- none

Description :
	- encapsulates any form of data into a managable keyed array enclosed in a class
	- elements are stored in an array having a unique key as ID

Created By : Stephen Lou B. Banal
Email : sbanal@yahoo.com
Date Created : 3/3/2004 6:24AM

Last Updates :
	- added addDataElement( <KEY>, <VALIDATE FUNC>, "[VALUE]", <ERROR CODE AND MSG PAIR> ) to add only data elements recognized by childrens
	  of this class
	- added validateData function to provide an automatic data validation function, validation function used are those set
	  using the addDataElement method.
	- added detailed error messaging per data element
	- getError returns an array of err_code and err_msg pair


Notes :
	- when using addDataElement always make sure to pass a user defined function

************************************************************************************/



class DataEncapsulator {

	var $data_elems;
	var $data_elems_validate_bool;
	var $data_elems_validate_funcs;
	var $data_elems_validate_params;
	var $data_elems_validate_readonly;
	var $data_elems_validate_error;
	var $m_arrError;
	var $m_bDebug;


	/**
	 * Validates all the data elements using the provided validate functions set using addDataElement method
	 *
	 *
	 **/
	function validateData( $bIsUpdate = false ){
		
		$bValidateBool = true;

		// validate each data set using the user provided validate functions and params
			foreach ( $this->data_elems_validate_funcs as $key=>$value ) {

			$arrValidateFuncs = $this->data_elems_validate_funcs[$key];
			$arrValidateFuncsParams = $this->data_elems_validate_params[$key];

			// transform into an array
			if ( $arrValidateFuncs!= NULL && !is_array($arrValidateFuncs) ) {

				$arrValidateFuncs = array( $arrValidateFuncs );
				$arrValidateFuncsParams = array( $arrValidateFuncsParams );

			}

			if ( false && $this->m_bDebug ) {

				print_r($arrValidateFuncs);
				print_r($arrValidateFuncsParams);

			}

			// always skip data which are not modified if data elem is not set and validate function is called for update
			if ( $bIsUpdate && $this->data_elems[$key] == NULL ){

				continue;

			}

			// no check each paramter per validation function and param set
			// AND condition on all function call

			$bValid = true;

			if ( $arrValidateFuncs != NULL && !$this->data_elems_validate_readonly[$key] )  { // skip those fields which have empty validation functions and are readonly

				for ( $i = 0; $i < count($arrValidateFuncs); $i++ ) {

					$arrValidateFuncsParams[$i] = str_replace("[VALUE]", $this->getData($key), $arrValidateFuncsParams[$i]);
					
					$ret = call_user_func_array ( $arrValidateFuncs[$i], $arrValidateFuncsParams[$i] );

					if ( $ret ) {

						$bValid = 1;

					} else {

						$err_msg = str_replace("[KEY]", $key, $this->data_elems_validate_error[$key]["err_mesg"]);
						$err_msg = str_replace("[VALUE]", $this->getData($key), $err_msg);
						$this->setError( $this->data_elems_validate_error[$key]["err_code"] , $err_msg);
						$bValid = 0;

						break;

					}

				}

			}

			$this->data_elems_validate_bool[$key] = $bValid;
			//$this->debug( "VALIDATEDATA $key : $bValid");
			$bValidateBool =  $bValidateBool && $bValid;

		}

		//$this->debug( "VALIDATEDATA RET : $bValidateBool");
		if ( $bValidateBool){

			return 1;

		} else {

			return -1;

		}

	}

	/**
	 * Adds a new data storage element having key $key, and validate function checker $validateFuncs.
	 * $validateFuncs can either be an array or just a string name user callback function.
	 * $params are the respective parameters of each $validateFuncs provided, multidimensional if $validateFuncs have more than 1 element.
	 * $error is the error key and message pair returned if value of a data element is invalid.
	 *
	 *
	 *
	 **/
	function addDataElement( $key, $validateFuncs = array("is_empty"), $params = array(array("[VALUE]")), $bReadOnly = false, $error = array("err_code"=>-1,"err_mesg"=>"Invalid value for data element [KEY] = [VALUE].")  ) {

		$this->data_elems_validate_funcs[$key] = $validateFuncs;
		$this->data_elems_validate_params[$key] = $params;
		$this->data_elems_validate_error[$key] = $error;
		$this->data_elems_validate_readonly[$key] = $bReadOnly;
		$this->data_elems[$key] = NULL;
		
	}

	/**
	 * Sets debug logs turned on or off, set $bDebug to true to activate debug printing otherwise false.
	 *
	 **/
	function setDebugMode( $bDebug ) {

		 $this->m_bDebug = $bDebug;

	}


	/**
	 * Prints debug strings. use setDebugMode ( true ) to activate.
	 *
	 **/
	function debug ( $str ) {

		if ( $this->m_bDebug ) {

			echo "[" . date("Y-m-d H:i:s") . "] : " . htmlentities($str) . "<BR>";

		}

	}

	/**
	 * Clears all data elements
	 *
	 **/
	function resetData( $key = NULL ){

		if ( $key = NULL ) {

			unset($data_elems);

		} else {

			unset($data_elems["$key"]);

		}

	}

	/**
	 * Sets data value of a data element having key $key. Returns true if key exist and value is set otherwise returns false.
	 *
	 *
	 **/
	function setData( $key, $value = NULL ) {

		if ( $this->data_elems_validate_readonly[$key] ) {

			$this->debug("Data elem with $key is readonly");
			return false;

		}

		if ( $key == NULL && $value != NULL ) {

			$this->data_elems = $value;

		} else if ( is_array($this->data_elems_validate_params) && $key != NULL && $value != NULL && in_array( $key, array_keys($this->data_elems_validate_params) ) ) {

			$this->data_elems[$key] = $value;

		} else {

			$this->debug("Data elem with $key not available");
			return false;

		}

		return true;

	}

	/**
	 * Returns value of a data element having key $key, if $key passed is NULL value returned is the data elements array.
	 *
	 *
	 **/
	function getData( $key = NULL ) {

		if ( $key == NULL ) {

			return $this->data_elems;

		} else {

			return $this->data_elems[$key];

		}

	}

	/**
	 * Returns error of a failed create, load or find invocation.
	 * Returns a two dimensional array having elments err_code and err_mesg.
	 *
	 */
	function getError( ) {

		// always unset after get

		$err = $this->m_arrError;

		unset ( $this->m_arrError );

		return $err;

	}


	/**
	 * Set error of data set on this instance, usefull when validating data set and found error on data.
	 *
	 **/
	function setError( $err_code, $err_mesg ) {

		$this->m_arrError[] = array("err_code"=>$err_code, "err_mesg"=>$err_mesg );

	}

	function printError(){

		//print_r($this->m_arrError);
		echo "<p align=center><font class='error'>";
		
		for ( $i=0; $i< count($this->m_arrError); $i++){

			echo "<li>" . $this->m_arrError[$i]["err_mesg"] . "<BR>";

		}

		echo "</font></p>";

	}
	
	function toStringError(){

		//print_r($this->m_arrError);
		$str .="<p align=center>";
		
		for ( $i=0; $i< count($this->m_arrError); $i++){

			$str .= "<li>" . $this->m_arrError[$i]["err_mesg"] . "<BR>";

		}

		$str .= "</p>";
		
		return $str;

	}

}

?>