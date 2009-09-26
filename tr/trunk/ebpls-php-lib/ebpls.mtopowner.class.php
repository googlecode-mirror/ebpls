<?
/************************************************************************************

Module : ebpls.owner.class.php

Dependencies : 
	ebpls.database.funcs.php
	ebpls.dataencapsulator.class.php
	
Description : 
	- encapsulates owner of a permit
	- permits can either of type business, occupational, peddler, motorized
	
	
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

require_once("ebpls-php-lib/ebpls.dataencapsulator.class.php");
require_once("ebpls-php-lib/ebpls.global.funcs.php");
require_once("ebpls-php-lib/ebpls.database.funcs.php");

// keys for getData method
define(EBPLS_OWNER_TABLE,"ebpls_owner");
define(EBPLS_TRANSACTION_TABLE, "ebpls_transaction");

// Permit Owner Data Elements Constants
define(OWNER_ID,"owner_id");
define(OWNER_FIRST_NAME,"owner_first_name");
define(OWNER_MIDDLE_NAME,"owner_middle_name");
define(OWNER_LAST_NAME,"owner_last_name");
define(OWNER_HOUSE_NO,"owner_house_no");
define(OWNER_STREET,"owner_street");
define(OWNER_BARANGAY_CODE,"owner_barangay_code");
define(OWNER_ZONE_CODE,"owner_zone_code");
define(OWNER_DISTRICT_CODE,"owner_district_code");
define(OWNER_CITY_CODE,"owner_city_code");
define(OWNER_ICR_NO,"owner_icr_no");
define(OWNER_PROVINCE_CODE,"owner_province_code");
define(OWNER_ZIP_CODE,"owner_zip_code");
define(OWNER_CITIZENSHIP,"owner_citizenship");
define(OWNER_CIVIL_STATUS,"owner_civil_status");
define(OWNER_GENDER,"owner_gender");
define(OWNER_TIN_NO,"owner_tin_no");
define(OWNER_PHONE_NO,"owner_phone_no");
define(OWNER_GSM_NO,"owner_gsm_no");
define(OWNER_EMAIL_ADDRESS,"owner_email_address");
define(OWNER_OTHERS,"owner_others");
define(OWNER_BIRTH_DATE,"owner_birth_date");
define(OWNER_REG_DATE,"owner_reg_date");
define(OWNER_LASTUPDATED,"owner_lastupdated");
define(OWNER_LASTUPDATED_BY,"owner_lastupdated_by");

define(OWNER_INCOMPLETEDATA,-300001);

class EBPLSOwner extends DataEncapsulator {
	
	var $m_dbLink;	
	
	/**
	 * Instatiation method, set $bDebug to true to print debug messages otherwise set to false.
	 * $dbLink is a valid database connection.
	 *
	 */
	function EBPLSOwner( $dbLink, $bDebug = false ) {
	
		$this->m_dbLink = $dbLink;
		$this->setDebugMode( $bDebug );		
		
		// add data elements of class with their respective validation functions an params, used default value for 4th parameter
		// for error message pair in case validation failed 
		$this->addDataElement( OWNER_ID, "is_valid_number", "[VALUE]", true );
		$this->addDataElement( OWNER_FIRST_NAME, "is_not_empty", "[VALUE]" );
		$this->addDataElement( OWNER_LAST_NAME, "is_not_empty", "[VALUE]" );
		$this->addDataElement( OWNER_MIDDLE_NAME, "is_not_empty", "[VALUE]" );
		$this->addDataElement( OWNER_HOUSE_NO, "is_not_empty", "[VALUE]" );		
		$this->addDataElement( OWNER_STREET, "is_not_empty", "[VALUE]" );
		$this->addDataElement( OWNER_BARANGAY_CODE, "is_not_empty", "[VALUE]" );
		$this->addDataElement( OWNER_ZONE_CODE, "is_not_empty", "[VALUE]" );
		$this->addDataElement( OWNER_DISTRICT_CODE, "is_not_empty", "[VALUE]" );
		$this->addDataElement( OWNER_CITY_CODE, "is_not_empty", "[VALUE]" );
		$this->addDataElement( OWNER_PROVINCE_CODE, "is_not_empty", "[VALUE]" );
		$this->addDataElement( OWNER_ZIP_CODE, "is_not_empty", "[VALUE]" );
		$this->addDataElement( OWNER_CITIZENSHIP, "is_not_empty", "[VALUE]" );
		$this->addDataElement( OWNER_CIVIL_STATUS, "is_valid_marital_status", "[VALUE]" );
		$this->addDataElement( OWNER_GENDER, "is_valid_gender", "[VALUE]" );
		$this->addDataElement( OWNER_TIN_NO, "is_not_empty", "[VALUE]" );
		$this->addDataElement( OWNER_ICR_NO, NULL, NULL );
		$this->addDataElement( OWNER_PHONE_NO, "is_not_empty", "[VALUE]" );
		$this->addDataElement( OWNER_GSM_NO, "is_not_empty", "[VALUE]" );
		$this->addDataElement( OWNER_EMAIL_ADDRESS, NULL, NULL );
		$this->addDataElement( OWNER_OTHERS, "is_not_empty", "[VALUE]" );
		$this->addDataElement( OWNER_BIRTH_DATE, "is_valid_date", "[VALUE]" );
		$this->addDataElement( OWNER_REG_DATE, "is_valid_date", "[VALUE]", true );
		$this->addDataElement( OWNER_LASTUPDATED, "is_valid_date", "[VALUE]" );
		$this->addDataElement( OWNER_LASTUPDATED_BY, "is_not_empty", "[VALUE]" );

	}
	
	/**
	 * Adds new owner to ebls_owner table
	 *
	 */
	function add( ){
			
		if ( $this->m_dbLink ) {
			
			if ( ( $error_num = $this->validateData() ) > 0 ) {
				
				ebpls_start_transaction( $this->m_dbLink );
				
				$this->data_elems[ OWNER_REG_DATE ] = date("Y-d-m H:i:s");
				$this->data_elems[ OWNER_LASTUPDATED ] = date("Y-d-m H:i:s");
				$strValues = $this->data_elems;
								
				$this->debug("DATE : " . $this->data_elems[ OWNER_REG_DATE ]);
				
				$ret = ebpls_insert_data( $this->m_dbLink, EBPLS_OWNER_TABLE, $strValues );
				
				if ( $ret < 0 ) {
				
					$this->debug( "CREATE OWNER FAILED [error:$ret,msg=" . get_db_error() . "]" );
					
					$this->setError( $ret, get_db_error() );
					ebpls_rollback_transaction( $this->m_dbLink );
					return $ret;
					
				} else {
										
					$this->debug( "CREATE OWNER SUCCESSFULL [$ret]" );
					$this->data_elems[ OWNER_ID ] = $ret;
					ebpls_commit_transaction( $this->m_dbLink );
					return $ret;
					
				}
								
				
			} else {
			
				$this->debug( "CREATE OWNER FAILED [error:$ret,msg=" . get_db_error() . "]" );
				return $error_num;
			
			}
			
		} else {
		
			$this->debug( "CREATE OWNER FAILED INVALID DB LINK $this->m_dbLink" );
			$this->setError( $ret, "Invalid Db link $this->m_dbLink" );
			return -1;
			
		}
	
	}
		
	
	/**
	 * View owner data, loads data using owner id as param
	 *
	 */
	function view( $owner_id ) {
							
		$strValues[$key] = "*";
		
		$strWhere[OWNER_ID] = $owner_id;			
		
		$result = ebpls_select_data( $this->m_dbLink, EBPLS_OWNER_TABLE, $strValues, $strWhere, NULL, $strOrderBy, "DESC", NULL );

		if ( is_array($result) ) {
		
			$this->data_elems = $result[0];
			return $result[0][OWNER_ID];
			
		} else {
		
			$this->setError( $result, get_db_error() );
			return -1;
		
		}
	
	}
	
	/**
	 * Update owner data
	 *
	 *
	 **/
	function update( $owner_id ) {
		
		$this->data_elems[ OWNER_LASTUPDATED ] = date("Y-d-m H:i:s");
		
		$arrData = $this->getData();
		foreach( $arrData as $key=>$value){
		
			if ( $arrData[$key] != NULL ) {
			  
				$strValues[$key] = $value;
				
			}
		
		}
		
		if ( ( $error_num = $this->validateData(true) ) > 0 ) {
		
			
			$strWhere[OWNER_ID] = $owner_id;
	
			$ret = ebpls_update_data( $this->m_dbLink, EBPLS_OWNER_TABLE, $strValues, $strWhere );
			
			
			if ( $ret < 0 ) {
			
				$this->debug( "UPDATE OWNER FAILED [error:$ret,msg=" . get_db_error() . "]" );
				
				$this->setError( $ret, get_db_error() );
				
				return $ret;
				
			} else {
									
				$this->debug( "UPDATE OWNER SUCCESSFULL [$ret]" );
				return $ret;
				
			}					
			
		} else {
			
			$this->debug( "CREATE OWNER FAILED [error:$ret,msg=" . get_db_error() . "]" );
			return -1;
			
		}
	
	}
	
	function delete( $owner_id ) {
		
		$strWhere[OWNER_ID] = $owner_id;
		$strValues[] = "count(*) as cnt";

		// check if owner have existing transactions
		$result = ebpls_select_data( $this->m_dbLink, EBPLS_TRANSACTION_TABLE, $strValues, $strWhere, NULL, NULL, NULL, NULL );
		
		if ( ( is_array( $result ) && $result[0]["cnt"] > 0 ) ) {
		
			$this->debug( "DELETE OWNER FAILED, OWNER HAVE AN EXISTING TRANSACTION");
			return -1;
			
		} else if ( $result < 0 ) {
		
			$this->debug( "DELETE OWNER FAILED [error:$ret,msg=" . get_db_error() . "]");
			return -1;	
		}
			
		$result = ebpls_delete_data ( $this->m_dbLink, EBPLS_OWNER_TABLE, $strWhere );
		
		if ( $result < 0 )  {
			
			$this->setError( $result, get_db_error() );
			
		}
		
		return $result;		
	
	}
	
	/*
	function view( $owner_id ) {
		
		
		$strWhere[OWNER_ID] = $owner_id;
		$strValues[] = "*";

		// check if owner have existing transactions
		$result = ebpls_select_data( $this->m_dbLink, EBPLS_OWNER_TABLE, $strValues, $strWhere, NULL, NULL, NULL, NULL );
		
		if (  is_array( $result )  ) {
		
			$this->debug( "LOAD OWNER OK");
			$this->setData( NULL, $result[0] );
			return 1;
			
		} else {
		
			$this->debug( "LOAD OWNER FAILED [error:$ret,msg=" . get_db_error() . "]");
			return -1;	
			
		}
		
	}
	*/
	
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
	function search( $fname = NULL, $mname = NULL, $lname = NULL, $email = NULL, $bdate = NULL, $page = 1, $maxrec = 1000000000, $orderkey = OWNER_REG_DATE, $is_desc = true ) {
	
		if ( $fname != NULL )
			$strWhere[OWNER_FIRST_NAME] = array("like", "$fname%");
		else  if ( $this->data_elems[OWNER_FIRST_NAME] != "" ) 
			$strWhere[OWNER_FIRST_NAME] = array("like", $this->data_elems[OWNER_FIRST_NAME] ."%");
		
		if ( $mname != NULL ) 
			$strWhere[OWNER_MIDDLE_NAME] = array("like", "$mname%");
		else  if ( $this->data_elems[OWNER_MIDDLE_NAME] != "" ) 
			$strWhere[OWNER_MIDDLE_NAME] = array("like", $this->data_elems[OWNER_MIDDLE_NAME] . "%" );
			
		if ( $lname != NULL ) 
			$strWhere[OWNER_LAST_NAME] = array("like", "$lname%");
		else if ( $this->data_elems[OWNER_LAST_NAME] != "" ) 
			$strWhere[OWNER_LAST_NAME] = array("like", $this->data_elems[OWNER_LAST_NAME] . "%");
			
		if ( $address != NULL ) 
			$strWhere[OWNER_EMAIL_ADDRESS] = array("like", "$email%");
		else if ( $this->data_elems[OWNER_EMAIL_ADDRESS] != "" ) 
			$strWhere[OWNER_EMAIL_ADDRESS] = array("like", $this->data_elems[OWNER_EMAIL_ADDRESS] . "%");
				
		if ( $bdate != NULL ) 	
			$strWhere[OWNER_BIRTH_DATE] = "$bdate";			
		else if ( $this->data_elems[OWNER_BIRTH_DATE] != "" ) 
			$strWhere[OWNER_BIRTH_DATE] = $this->data_elems[OWNER_BIRTH_DATE];

		// select all columns
		$strValues[] = "*";
		
		if ( $orderkey != NULL ) {
				
			$strOrder[$orderkey] = $orderkey;
			
		} else {
			
			$strOrder = $orderkey;
			
		}
		
		if ( count($strWhere) <= 0 ) {
			
			$this->setError ( -1, "No search parameters." );
			return -1;	
			
		}
		
		$result = ebpls_select_data_bypage( $this->m_dbLink, EBPLS_OWNER_TABLE, $strValues, $strWhere, NULL, $strOrder, $is_desc?"DESC":"ASC", $page, $maxrec );	
	
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
				

}





?>
