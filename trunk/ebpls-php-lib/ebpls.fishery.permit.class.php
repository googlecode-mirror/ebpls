<?
/************************************************************************************
Module : ebpls.fisheries.permit.class.php

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
define(FISHERY_PERMIT_TABLE,"ebpls_fishery_permit");

define(FISHERY_ID,"ebpls_fishery_id");
define(FISHERY_PERMIT_CODE,"ebpls_fishery_permit_code");
define(FISHERY_OWNER_ID,"owner_id");
define(FISHERY_PERMIT_APPLICATION_DATE,"ebpls_fishery_permit_application_date");
define(FISHERY_LOCAL_NAME_FISHING_GEAR,"ebpls_fishery_local_name_fishing_gear");
define(FISHERY_IN_ENGLISH,"ebpls_fishery_in_english");
define(FISHERY_NO_OF_UNITS,"ebpls_fishery_no_of_units");
define(FISHERY_ASSESS_VALUE_FISHING_GEAR,"ebpls_fishery_assess_value_fishing_gear");
define(FISHERY_FISHING_GEAR_SIZE,"ebpls_fishery_fishing_gear_size");
define(FISHERY_AREA_SIZE,"ebpls_fishery_area_size");
define(FISHERY_NO_OF_CREW,"ebpls_fishery_no_of_crew");
define(FISHERY_MOTORIZED,"ebpls_fishery_motorized");
define(FISHERY_REGISTERED,"ebpls_fishery_registered");
define(FISHERY_BOAT_NAME,"ebpls_fishery_boat_name");
define(FISHERY_REGISTRATION_NO,"ebpls_fishery_registration_no");
define(FISHERY_AVE_FISH_CATCH_PRESENT,"ebpls_fishery_ave_fish_catch_present");
define(FISHERY_AVE_FISH_CATCH_2YRS_AGO,"ebpls_fishery_ave_fish_catch_2yrs_ago");
define(FISHERY_LOCATION,"ebpls_fishery_location");
define(FISHERY_RC_NO,"ebpls_fishery_rc_no");
define(FISHERY_RC_ISSUED_AT,"ebpls_fishery_rc_issued_at");
define(FISHERY_RC_ISSUED_ON,"ebpls_fishery_rc_issued_on");

class EBPLSFisheryPermit extends EBPLSPermit {
	
	/**
	 * Instatiation method, set $bDebug to true to print debug messages otherwise set to false.
	 * $dbLink is a valid database connection.
	 *
	 **/
	function EBPLSFisheryPermit( $dbLink, $bDebug = false ) {
	
		$this->EBPLSPermit( $dbLink, $bDebug );		
		
		// add data elements of class with their respective validation functions an params, used default value for 4th parameter
		// for error message pair in case validation failed
		
		$this->addDataElement (FISHERY_ID, "is_not_empty", "[VALUE]", true );
		$this->addDataElement (FISHERY_PERMIT_CODE, "is_not_empty", "[VALUE]", true );
		$this->addDataElement (FISHERY_OWNER_ID, "is_not_empty", "[VALUE]");
		$this->addDataElement (FISHERY_PERMIT_APPLICATION_DATE, "is_valid_date", "[VALUE]", true );
		
		//$this->addDataElement (FISHERY_LOCAL_NAME_FISHING_GEAR, "is_not_empty", "[VALUE]" );
		//$this->addDataElement (FISHERY_IN_ENGLISH, "is_not_empty", "[VALUE]" );
		//$this->addDataElement (FISHERY_NO_OF_UNITS, "is_not_empty", "[VALUE]" );
		//$this->addDataElement (FISHERY_ASSESS_VALUE_FISHING_GEAR, "is_not_empty", "[VALUE]" );
		//$this->addDataElement (FISHERY_FISHING_GEAR_SIZE, "is_not_empty", "[VALUE]" );
		//$this->addDataElement (FISHERY_AREA_SIZE, "is_not_empty", "[VALUE]" );
		//$this->addDataElement (FISHERY_NO_OF_CREW, "is_valid_number", "[VALUE]" );
		//$this->addDataElement (FISHERY_MOTORIZED, "is_not_empty", "[VALUE]" );
		//$this->addDataElement (FISHERY_REGISTERED, "is_not_empty", "[VALUE]" );
		//$this->addDataElement (FISHERY_BOAT_NAME, "is_not_empty", "[VALUE]" );
		//$this->addDataElement (FISHERY_REGISTRATION_NO, "is_not_empty", "[VALUE]" );
		//$this->addDataElement (FISHERY_AVE_FISH_CATCH_PRESENT, "is_valid_number", "[VALUE]" );
		//$this->addDataElement (FISHERY_AVE_FISH_CATCH_2YRS_AGO, "is_valid_number", "[VALUE]" );
		//$this->addDataElement (FISHERY_LOCATION, "is_not_empty", "[VALUE]" );
		//$this->addDataElement (FISHERY_RC_NO, "is_not_empty", "[VALUE]" );
		//$this->addDataElement (FISHERY_RC_ISSUED_AT, NULL, "[VALUE]", true );
		//$this->addDataElement (FISHERY_RC_ISSUED_ON, NULL, "[VALUE]", true );
		
		$this->setPermitType( PERMIT_TYPE_FISHERY );

	}
	
	/**
	 * Adds new occ permit application, creates an instance of EBPLSTransaction class, accessible using getTransaction of EBPLSPermit class.
	 * Instance of EBPLSTransaction class identifies the Permit application status and requirements status in the system.
	 *
	 */
	function newApplication( $creator, $user_level ) {
			
		if ( $this->m_dbLink ) {
					
			$clsOwner = new EBPLSOwner ( $this->m_dbLink );
			
			$owner_id = $this->data_elems[ FISHERY_OWNER_ID ];
									
			if ( $clsOwner->view( $owner_id ) <= 0 )  {
			
				$this->debug("Onwer with owner id $owner_id not found.");
				$this->setError( -1, "Onwer with owner id $owner_id not found.");
				print_r($this->getError());
				return -1;

			}

			$this->setOwner( $clsOwner );
			
			if ( ( $error_num = $this->validateData() ) > 0 ) {
				
				// create reg ts				
				$nYear = date("Y");
				$dt = date("Y-m-d H:i:s", $ts);
				$this->data_elems[ FISHERY_PERMIT_APPLICATION_DATE ] = $dt;				
				
				$this->setPermitRegistrationDates( $dt, $dt, $nYear );
												
				$strValues = $this->data_elems;

				// create permit
				$ret = ebpls_insert_data( $this->m_dbLink, FISHERY_PERMIT_TABLE, $strValues );
				
				
				if ( $ret < 0 ) {
													
					$this->debug( "CREATE PEDDLERS PERMIT FAILED [error:$ret,msg=" . get_db_error() . "]" );
					$this->setError( $ret, get_db_error() );										

				} else {

					$this->debug("CREAT PEDDLER OK $ret");
					$this->data_elems[ FISHERY_PERMIT_ID ] = $ret;									
					$this->m_strPermitNo = $ret;
					$this->m_strPermitType = PERMIT_TYPE_FISHERY;

				}											
								
				return $ret;
				
			} else {
			
				$this->debug( "CREATE FISHERY PERMIT FAILED [error:$ret,msg=" . get_db_error() . "]" );
				$this->setError( -1, "CREATE FISHERY PERMIT FAILED [error:$ret,msg=" . get_db_error() . "]" );
				print_r($this->getError());
				return $error_num;
			
			}
			
		} else {
		
			$this->debug( "CREATE FISHERY FAILED INVALID DB LINK $this->m_dbLink" );
			$this->setError( $ret, "Invalid Db link $this->m_dbLink" );
			print_r($this->getError());
			return -1;
			
		}
	
	}
		
	
	/**
	 * View owner data, loads data using owner id as param
	 *
	 */
	function view( $fish_id ) {
							
		$strValues[$key] = "*";
		
		$strWhere[FISHERY_ID] = $fish_id;
		
		$result = ebpls_select_data( $this->m_dbLink, FISHERY_PERMIT_TABLE, $strValues, $strWhere, NULL, $strOrderBy, "DESC", NULL );

		if ( is_array($result) ) {
		
			
			$this->m_nOwnerId = $result[0][FISHERY_OWNER_ID];						
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
									
		$strWhere[FISHERY_ID] = $ped_id;

		$ret = ebpls_update_data( $this->m_dbLink, FISHERY_PERMIT_TABLE, $strValues, $strWhere );
		
		
		if ( $ret < 0 ) {
		
			$this->debug( "UPDATE PEDDLERS PERMIT FAILED [error:$ret,msg=" . get_db_error() . "]" );
			
			$this->setError( $ret, get_db_error() );
			
			return $ret;
			
		} else {
								
			$this->debug( "UPDATE PEDDLERS PERMIT SUCCESSFULL [$ret]" );
			return $ret;
			
		}										
	
	}
	
	function delete( $mot_id ) {
		
		$this->debu("MotPermit delete method not yet supported.");
		result -1;
		
		
	}
		
	function assignPermitCode( $code ) {
		
		$strValues[ FISHERY_PERMIT_CODE ] = $code;
		$strWhere[ FISHERY_PERMIT_ID ] = $this->data_elems[ FISHERY_PERMIT_ID ];

		$ret = ebpls_update_data( $this->m_dbLink, FISHERY_PERMIT_TABLE, $strValues, $strWhere );		
		
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