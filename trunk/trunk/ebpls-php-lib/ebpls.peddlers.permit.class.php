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
Date Created : 3/20/2004 12:09PM

Last Updates :
	[ DATE / TIME ] - [ PROGRAMMER ]
	- < DETAILS 1 >
	- < DETAILS 2 >.
	- < DETAILS 3 >

Notes :

************************************************************************************/

require_once("ebpls-php-lib/ebpls.transaction.class.php");
require_once("ebpls-php-lib/ebpls.permit.class.php");
require_once("ebpls-php-lib/ebpls.owner.class.php");
require_once("ebpls-php-lib/ebpls.global.funcs.php");
require_once("ebpls-php-lib/ebpls.dataencapsulator.class.php");

// keys for getData method
define(EBPLS_PEDDLERS_PERMIT_TABLE,"ebpls_peddlers_permit");

// Peddlers Permit Owner Data Elements Constants
define(PEDDLERS_PERMIT_ID,"peddlers_permit_id");
define(PEDDLERS_PERMIT_CODE,"peddlers_permit_code");
define(PEDDLERS_MERCHANDISE_SOLD,"merchandise_sold");
define(PEDDLERS_OWNER_ID,"owner_id");
define(PEDDLERS_RETIREMENT_CODE,"retirement_code");
define(PEDDLERS_RETIREMENT_DATE,"retirement_date");
define(PEDDLERS_RETIREMENT_DATE_PROCESSED,"retirement_date_processed");
define(PEDDLERS_APPLICATION_DATE,"application_date");
define(PEDDLERS_FOR_YEAR,"for_year");

class EBPLSPeddlersPermit extends EBPLSPermit {


	/**
	 * Instatiation method, set $bDebug to true to print debug messages otherwise set to false.
	 * $dbLink is a valid database connection.
	 *
	 */
	function EBPLSPeddlersPermit( $dbLink, $bDebug = false ) {

		$this->EBPLSPermit( $dbLink, true );

		// add data elements of class with their respective validation functions an params, used default value for 4th parameter
		// for error message pair in case validation failed
		$this->addDataElement( PEDDLERS_PERMIT_ID, "is_valid_number", "[VALUE]", true );
		$this->addDataElement( PEDDLERS_PERMIT_CODE, "is_valid_number", "[VALUE]" , true );
		$this->addDataElement( PEDDLERS_MERCHANDISE_SOLD, "is_not_empty", "[VALUE]" );
		$this->addDataElement( PEDDLERS_OWNER_ID, "is_valid_number", "[VALUE]" );
		$this->addDataElement( PEDDLERS_RETIREMENT_CODE, "is_not_empty", "[VALUE]", true );
		$this->addDataElement( PEDDLERS_RETIREMENT_DATE_PROCESSED, "is_valid_date", "[VALUE]", true );
		$this->addDataElement( PEDDLERS_RETIREMENT_DATE, "is_valid_date", "[VALUE]", true );
		$this->addDataElement( PEDDLERS_APPLICATION_DATE, "is_valid_date", "[VALUE]", true );
		$this->addDataElement( PEDDLERS_FOR_YEAR, "is_valid_number", "[VALUE]", true );

		$this->setPermitType( PERMIT_TYPE_PEDDLER );

	}

	/**
	 * Adds new occ permit application, creates an instance of EBPLSTransaction class, accessible using getTransaction of EBPLSPermit class.
	 * Instance of EBPLSTransaction class identifies the Permit application status and requirements status in the system.
	 *
	 */
	function newApplication( $creator, $user_level ) {

		if ( $this->m_dbLink ) {

			$clsOwner = new EBPLSOwner ( $this->m_dbLink );

			$owner_id = $this->data_elems[ PEDDLERS_OWNER_ID ];

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
				$this->data_elems[ PEDDLERS_APPLICATION_DATE ] = $dt;
				$this->data_elems[ PEDDLERS_FOR_YEAR ] = $nYear;

				$this->setPermitRegistrationDates( $dt, $dt, $nYear );

				$strValues = $this->data_elems;

				// create permit
				$ret = ebpls_insert_data( $this->m_dbLink, EBPLS_PEDDLERS_PERMIT_TABLE, $strValues );


				if ( $ret < 0 ) {

					$this->debug( "CREATE PEDDLERS PERMIT FAILED [error:$ret,msg=" . get_db_error() . "]" );
					$this->setError( $ret, get_db_error() );

				} else {

					$this->debug("CREAT PEDDLER OK $ret");
					$this->data_elems[ PEDDLERS_PERMIT_ID ] = $ret;
					$this->m_strPermitNo = $ret;
					$this->m_strPermitType = PERMIT_TYPE_PEDDLER;

				}

				return $ret;

			} else {

				$this->debug( "CREATE PEDDLERS PERMIT FAILED [error:$ret,msg=" . get_db_error() . "]" );
				return $error_num;

			}

		} else {

			$this->debug( "CREATE PEDDLERS FAILED INVALID DB LINK $this->m_dbLink" );
			$this->setError( $ret, "Invalid Db link $this->m_dbLink" );
			return -1;

		}

	}


	/**
	 * View owner data, loads data using owner id as param
	 *
	 */
	function view( $ped_code ) {

		$strValues[$key] = "*";

		$strWhere[PEDDLERS_PERMIT_ID] = $ped_code;

		$result = ebpls_select_data( $this->m_dbLink, EBPLS_PEDDLERS_PERMIT_TABLE, $strValues, $strWhere, NULL, $strOrderBy, "DESC", NULL );

		if ( is_array($result) ) {

			$owner = new EBPLSOwner( $this->m_dbLink );

			//$owner->view( $result[0][MOTORIZED_OPERATOR_PERMIT_ID] );

			$owner->view( $result[0][PEDDLERS_OWNER_ID] );

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
	function update( $ped_id ) {

		$arrData = $this->getData();
		foreach( $arrData as $key=>$value){

			if ( $arrData[$key] != NULL ) {

				$strValues[$key] = $value;

			}

		}

		if ( ( $error_num = $this->validateData(true) ) > 0 ) {


			$strWhere[PEDDLERS_PERMIT_ID] = $ped_id;

			$ret = ebpls_update_data( $this->m_dbLink, EBPLS_PEDDLERS_PERMIT_TABLE, $strValues, $strWhere );


			if ( $ret < 0 ) {

				$this->debug( "UPDATE PEDDLERS PERMIT FAILED [error:$ret,msg=" . get_db_error() . "]" );

				$this->setError( $ret, get_db_error() );

				return $ret;

			} else {

				$this->debug( "UPDATE PEDDLERS PERMIT SUCCESSFULL [$ret]" );
				return $ret;

			}

		} else {

			$this->debug( "UPDATE PEDDLERS PERMIT FAILED [error:$ret,msg=" . get_db_error() . "]" );
			return -1;

		}

	}

	function delete( $ped_id ) {

		$this->debug("MotPermit delete method not yet supported.");
		result -1;

	}


	function assignPermitCode( $code ) {

		$strValues[ PEDDLERS_PERMIT_CODE ] = $code;
		$strWhere[ PEDDLERS_PERMIT_ID ] = $this->data_elems[ PEDDLERS_PERMIT_ID ];

		$ret = ebpls_update_data( $this->m_dbLink, EBPLS_PEDDLERS_PERMIT_TABLE, $strValues, $strWhere );

		if ( $ret < 0 ) {

			$this->setError( $ret, $str = get_db_error() );
			$this->debug( "UPDATE PED PERMIT FAILED [error:$ret,msg=" . $str . "]" );

			return $ret;

		} else {

			$this->debug( "UPDATE PED PERMIT SUCCESSFULL [$ret]" );
			return $ret;

		}


	}

}





?>