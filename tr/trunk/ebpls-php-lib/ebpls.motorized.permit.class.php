<?
/************************************************************************************

Module : ebpls.motorized.permit.class.php

Dependencies :
	ebpls.permit.class.php
	ebpls.owner.class.php
	ebpls.global.funcs.php
	ebpls.dataencapsulator.class.php

Description :
	- encapsulates motorized permit


Created By : Stephen Lou B. Banal
Email : sbanal@yahoo.com
Date Created : 3/6/2004 5:51PM

Last Updates :
	[ DATE / TIME ] - [ PROGRAMMER ]
	- < DETAILS 1 >
	- < DETAILS 2 >
	- < DETAILS 3 >

Notes :


************************************************************************************/

require_once("ebpls-php-lib/ebpls.transaction.class.php");
require_once("ebpls-php-lib/ebpls.permit.class.php");
require_once("ebpls-php-lib/ebpls.owner.class.php");
require_once("ebpls-php-lib/ebpls.global.funcs.php");
require_once("ebpls-php-lib/ebpls.dataencapsulator.class.php");

// keys for getData method
define(EBPLS_MOTORIZED_OPERATOR_PERMIT_TABLE,"ebpls_motorized_operator_permit");

// Occ. Permit Owner Data Elements Constants
define(MOTORIZED_OPERATOR_PERMIT_ID,"motorized_operator_permit_id");
define(MOTORIZED_OPERATOR_PERMIT_CODE,"motorized_permit_code");
define(MOTORIZED_OPERATOR_ID,"motorized_operator_id");
define(MOTORIZED_OWNER_ID,"owner_id");
define(MOTORIZED_OPERATOR_PERMIT_APPLICATION_DATE,"motorized_operator_permit_application_date");
define(MOTORIZED_RETIREMENT_DATE,"motorized_retirement_date");
define(MOTORIZED_RETIREMENT_DATE_PROCESSED,"motorized_retirement_date_processed");
define(MOTORIZED_REQUIREMENT_CODE,"requirement_code");
define(MOTORIZED_FOR_YEAR,"for_year");

class EBPLSMotorizedPermit extends EBPLSPermit {

	/**
	 * Instatiation method, set $bDebug to true to print debug messages otherwise set to false.
	 * $dbLink is a valid database connection.
	 *
	 */
	function EBPLSMotorizedPermit( $dbLink, $bDebug = false ) {

		$this->EBPLSPermit( $dbLink, $bDebug );

		// add data elements of class with their respective validation functions an params, used default value for 4th parameter
		// for error message pair in case validation failed
		$this->addDataElement( MOTORIZED_OPERATOR_PERMIT_ID, "is_valid_number", "[VALUE]", true );
		$this->addDataElement( MOTORIZED_OPERATOR_ID, "is_valid_number", "[VALUE]" );
		$this->addDataElement( MOTORIZED_OWNER_ID, "is_valid_number", "[VALUE]" );
		$this->addDataElement( MOTORIZED_OPERATOR_PERMIT_APPLICATION_DATE, "is_valid_date", "[VALUE]", true );
		$this->addDataElement( MOTORIZED_RETIREMENT_DATE, "is_valid_date", "[VALUE]", true );
		$this->addDataElement( MOTORIZED_RETIREMENT_DATE_PROCESSED, "is_valid_date", "[VALUE]", true );
		$this->addDataElement( MOTORIZED_FOR_YEAR, "is_valid_number", "[VALUE]", true );

		$this->setPermitType( PERMIT_TYPE_MOTORIZED );

	}

	/**
	 * Adds new occ permit application, creates an instance of EBPLSTransaction class, accessible using getTransaction of EBPLSPermit class.
	 * Instance of EBPLSTransaction class identifies the Permit application status and requirements status in the system.
	 *
	 */
	function newApplication( $creator, $user_level ) {

		if ( $this->m_dbLink ) {

			$clsOwner = new EBPLSOwner ( $this->m_dbLink );

			$owner_id = $this->data_elems[ MOTORIZED_OWNER_ID ];

			if ( $clsOwner->view( $owner_id ) <= 0 )  {

				$this->debug("Onwer with owner id $owner_id not found.");
				return -1;

			}

			$this->setOwner( $clsOwner );

			if ( ( $error_num = $this->validateData() ) > 0 ) {

				// create reg ts
				$ts = time();
				$nYear = date("Y", $ts);
				$dt = date("Y-m-d H:i:s", $ts);
				$this->data_elems[ MOTORIZED_OPERATOR_PERMIT_APPLICATION_DATE ] = $dt;
				$this->data_elems[ MOTORIZED_FOR_YEAR ] = $nYear;

				$this->setPermitRegistrationDates( $dt, $dt, $nYear );

				$strValues = $this->data_elems;

				// create permit
				$ret = ebpls_insert_data( $this->m_dbLink, EBPLS_MOTORIZED_OPERATOR_PERMIT_TABLE, $strValues );

				if ( $ret < 0 ) {

					$this->debug( "CREATE MOT PERMIT FAILED1 [error:$ret,msg=" . get_db_error() . "]" );
					$this->setError( $ret, get_db_error() );

				} else {

					$this->data_elems[ MOTORIZED_OPERATOR_PERMIT_ID ] = $ret;
					$this->m_strPermitNo = $ret;
					$this->m_strPermitType = PERMIT_TYPE_MOTORIZED;
					
					return $ret;


				}

				return $ret;

			} else {

				$this->debug( "CREATE MOT PERMIT FAILED2 [error:$ret,msg=" . $this->toStringError() . "]" );
				return $error_num;

			}

		} else {

			$this->debug( "CREATE MOT FAILED INVALID DB LINK $this->m_dbLink" );
			$this->setError( $ret, "Invalid Db link $this->m_dbLink" );
			return -1;

		}

	}


	function renewApplication ( $occ_code ) {

		// load occ. permit
		if ( $this->view( $occ_code ) < 0 ) {

			// create new occ. permit



		} else {

			$this->debug("Invalid occ code $occ_code, renewal failed.");
			return NULL;

		}

	}

	/**
	 * View owner data, loads data using owner id as param
	 *
	 */
	function view( $mot_code ) {

		$strValues[$key] = "*";

		$strWhere[MOTORIZED_OPERATOR_PERMIT_ID] = $mot_code;

		//echo "<hr>code : $mot_code <hr>";
		$result = ebpls_select_data( $this->m_dbLink, EBPLS_MOTORIZED_OPERATOR_PERMIT_TABLE, $strValues, $strWhere, NULL, $strOrderBy, "DESC", NULL );

		if ( is_array($result) ) {

			$owner = new EBPLSOwner( $this->m_dbLink );
			//$owner->load( $result[0][MOTORIZED_OPERATOR_PERMIT_ID] );
			$owner->view( $result[0][MOTORIZED_OWNER_ID] );
			$this->setOwner( $owner );
			$this->data_elems = $result[0];

			return 1;

		} else {

			$this->setError( $result, get_db_error() );
			return -1;

		}

	}

	/**
	 * Update mot permit data
	 *
	 *
	 **/
	function update( $mot_id ) {

		$arrData = $this->getData();
		foreach( $arrData as $key=>$value){

			if ( $arrData[$key] != NULL ) {

				$strValues[$key] = $value;

			}

		}

		if ( ( $error_num = $this->validateData(true) ) > 0 ) {


			$strWhere[MOTORIZED_OPERATOR_PERMIT_ID] = $mot_id;

			$ret = ebpls_update_data( $this->m_dbLink, EBPLS_MOTORIZED_OPERATOR_PERMIT_TABLE, $strValues, $strWhere );


			if ( $ret < 0 ) {

				$this->debug( "UPDATE MOT PERMIT FAILED [error:$ret,msg=" . get_db_error() . "]" );

				$this->setError( $ret, get_db_error() );

				return $ret;

			} else {

				$this->debug( "UPDATE MOT PERMIT SUCCESSFULL [$ret]" );
				return $ret;

			}

		} else {

			$this->debug( "UPDATE MOT PERMIT FAILED [error:$ret,msg=" . get_db_error() . "]" );
			return -1;

		}

	}

	function delete( $mot_id ) {

		$this->debu("MotPermit delete method not yet supported.");
		result -1;

	}

	/**
	 * Find function searches Owner table for users having exact values for firstname, lastname, middlename, email address, birthdate.
	 *
	 * Set a NULL value to any of the parameters a users wishes not included on the search function.
	 *
	 * Search uses AND on query on all of the non-NULL parameters provided. Exact string match is implemented.
	 *
	 * Search result can be order by setting orderkey as any of the pre-defined data elements constants defined above,
	 * set $is_desc to true to use DESC otherwise set to false.
	 *
	 * Paging is automatically provided by letting users of this method provide the page number and the max records per page.
	 * Page result are automaticallly selected give these information, by rule $maxrec should be > 0 and $page should be > 1 and < maxpages
	 *
	 * Result of this method is a 2-dim array, having keys "page_info" and "result"
	 * First element of result having key "page_info" contains all the information regarding the query
	 * 		total = number of total records of search
	 *		max_pages = number of pages in search
	 *		count = number of records on current page
	 *		page = current page selected
	 * Second element of array having key "result" contains result of the search. "result" search value is an array of EBLPSCTC objects
	 *
	 *
	 */
	function search( $fname, $mname, $lname, $email, $bdate , $page, $maxrec = 10, $orderkey = OWNER_REG_DATE, $is_desc = true ) {


		if ( $fname != NULL )
			$strWhere[OWNER_FIRST_NAME] = "$fname";

		if ( $mname != NULL )
			$strWhere[OWNER_MIDDLE_NAME] = "$mname";

		if ( $lname != NULL )
			$strWhere[OWNER_LAST_NAME] = "$lname";

		if ( $address != NULL )
			$strWhere[OWNER_EMAIL_ADDRESS] = "$email";

		if ( $bdate != NULL )
			$strWhere[OWNER_BIRTH_DATE] = "$bdate";

		// select all columns
		$strValues[] = "*";

		if ( $orderkey != NULL ) {

			$strOrder[$orderkey] = $orderkey;

		} else {

			$strOrder = $orderkey;

		}

		$result = ebpls_select_data_bypage( $this->m_dbLink, EBPLS_MOTORIZED_OPERATOR_PERMIT_TABLE, $strValues, $strWhere, NULL, $strOrder, $is_desc?"DESC":"ASC", $page, $maxrec );

		if ( !is_array($result) && $result < 0 ) {


			$this->setError ( $result, get_db_error());
			return $result;

		} else {

			// transform result to EBPLSOwner object
			for ( $i = 0 ; $i < count($result["result"]); $i++ ) {
				$records[$i] = new EBPLSOwner($this->m_dbLink);
				$records[$i]->setData( NULL, $result["result"][$i] );
			}

			$result["result"] = $records;

			return $result;

		}

	}


	function assignPermitCode( $code ) {

		$strValues[MOTORIZED_OPERATOR_PERMIT_CODE] = $code;
		$strWhere[ MOTORIZED_OPERATOR_PERMIT_ID ] = $this->data_elems[ MOTORIZED_OPERATOR_PERMIT_ID ];

		$ret = ebpls_update_data( $this->m_dbLink, EBPLS_MOTORIZED_OPERATOR_PERMIT_TABLE, $strValues, $strWhere );

		if ( $ret < 0 ) {

			$this->debug( "UPDATE MOT PERMIT FAILED [error:$ret,msg=" . get_db_error() . "]" );

			$this->setError( $ret, get_db_error() );

			return $ret;

		} else {

			$this->debug( "UPDATE MOT PERMIT SUCCESSFULL [$ret]" );
			return $ret;

		}


	}


}





?>
