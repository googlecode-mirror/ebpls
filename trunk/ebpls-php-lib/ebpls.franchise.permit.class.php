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
define(EBPLS_FRANCHISE_PERMIT_TABLE,"ebpls_franchise_permit");

// Peddlers Permit Owner Data Elements Constants
define(FRANCHISE_PERMIT_ID,"franchise_permit_id");
define(FRANCHISE_PERMIT_CODE,"franchise_permit_code");
define(FRANCHISE_OWNER_ID,"owner_id");
define(FRANCHISE_RETIREMENT_CODE,"retirement_code");
define(FRANCHISE_RETIREMENT_DATE,"retirement_date");
define(FRANCHISE_RETIREMENT_DATE_PROCESSED,"retirement_date_processed");
define(FRANCHISE_APPLICATION_DATE,"application_date");
define(FRANCHISE_FOR_YEAR,"for_year");

class EBPLSFranchisePermit extends EBPLSPermit {
		
	/**
	 * Instatiation method, set $bDebug to true to print debug messages otherwise set to false.
	 * $dbLink is a valid database connection.
	 *
	 */
	function EBPLSFranchisePermit( $dbLink, $bDebug = false ) {
	
		$this->EBPLSPermit( $dbLink, $bDebug );		
		
		// add data elements of class with their respective validation functions an params, used default value for 4th parameter
		// for error message pair in case validation failed
		$this->addDataElement( FRANCHISE_PERMIT_ID, "is_valid_number", "[VALUE]", true );
		$this->addDataElement( FRANCHISE_PERMIT_CODE, "is_valid_number", "[VALUE]" , true );
		$this->addDataElement( FRANCHISE_OWNER_ID, "is_valid_number", "[VALUE]" );
		$this->addDataElement( FRANCHISE_RETIREMENT_CODE, "is_not_empty", "[VALUE]", true );
		$this->addDataElement( FRANCHISE_RETIREMENT_DATE_PROCESSED, "is_valid_date", "[VALUE]", true );
		$this->addDataElement( FRANCHISE_RETIREMENT_DATE, "is_valid_date", "[VALUE]", true );
		$this->addDataElement( FRANCHISE_APPLICATION_DATE, "is_valid_date", "[VALUE]", true );
		$this->addDataElement( FRANCHISE_FOR_YEAR, "is_valid_number", "[VALUE]", true );
		
		$this->setPermitType( PERMIT_TYPE_FRANCHISE );

	}
	
	/**
	 * Adds new occ permit application, creates an instance of EBPLSTransaction class, accessible using getTransaction of EBPLSPermit class.
	 * Instance of EBPLSTransaction class identifies the Permit application status and requirements status in the system.
	 *
	 */
	function newApplication( $creator, $user_level ) {
			
		if ( $this->m_dbLink ) {
					
			$clsOwner = new EBPLSOwner ( $this->m_dbLink );
			
			$owner_id = $this->data_elems[ FRANCHISE_OWNER_ID ];
									
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
				$this->data_elems[ FRANCHISE_APPLICATION_DATE ] = $dt;
				$this->data_elems[ FRANCHISE_FOR_YEAR ] = $nYear;
				
				$this->setPermitRegistrationDates( $dt, $dt, $nYear );
												
				$strValues = $this->data_elems;

				// create permit
				$ret = ebpls_insert_data( $this->m_dbLink, EBPLS_FRANCHISE_PERMIT_TABLE, $strValues );
				
				
				if ( $ret < 0 ) {
													
					$this->debug( "CREATE PEDDLERS PERMIT FAILED [error:$ret,msg=" . get_db_error() . "]" );
					$this->setError( $ret, get_db_error() );										

				} else {

					$this->debug("CREAT PEDDLER OK $ret");
					$this->data_elems[ FRANCHISE_PERMIT_ID ] = $ret;									
					$this->m_strPermitNo = $ret;
					$this->m_strPermitType = PERMIT_TYPE_FRANCHISE;

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
		
		$strWhere[FRANCHISE_PERMIT_ID] = $ped_code;
		
		$result = ebpls_select_data( $this->m_dbLink, EBPLS_FRANCHISE_PERMIT_TABLE, $strValues, $strWhere, NULL, $strOrderBy, "DESC", NULL );

		if ( is_array($result) ) {
		
			
			$this->m_nOwnerId = $result[0][FRANCHISE_OWNER_ID];						
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
		
			
			$strWhere[FRANCHISE_PERMIT_ID] = $ped_id;
	
			$ret = ebpls_update_data( $this->m_dbLink, EBPLS_FRANCHISE_PERMIT_TABLE, $strValues, $strWhere );
			
			
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
	
	function delete( $mot_id ) {
		
		$this->debu("MotPermit delete method not yet supported.");
		result -1;
		
		
	}
		
	function assignPermitCode( $code ) {
		
		$strValues[ FRANCHISE_PERMIT_CODE ] = $code;
		$strWhere[ FRANCHISE_PERMIT_ID ] = $this->data_elems[ FRANCHISE_PERMIT_ID ];

		$ret = ebpls_update_data( $this->m_dbLink, EBPLS_FRANCHISE_PERMIT_TABLE, $strValues, $strWhere );		
		
		if ( $ret < 0 ) {
		
			$this->setError( $ret, $str = get_db_error() );
			$this->debug( "UPDATE FRA PERMIT FAILED [error:$ret,msg=" . $str . "]" );
			
			return $ret;
			
		} else {
								
			$this->debug( "UPDATE FRA PERMIT SUCCESSFULL [$ret]" );
			return $ret;
			
		}					
		
	
	}

}





?>