<?
/************************************************************************************

Module : etoms.fund.class.php

Dependencies : 
	etoms.dbfuncs.php
	etoms.dataencapsulator.class.php
	
Description : 
	- encapsulates fund

Created By : Robert M. Verzosa
Email : rmv71279@yahoo.com, verzosar@dap.edu.ph
Date Created : 9/7/2005

	
************************************************************************************/

require_once("class/etoms.dataencapsulator.class.php");
require_once("lib/etoms.dbfuncs.php");

// keys for getData method
define(ETOMS_PAYEE_TABLE,"etoms_payee");

// Payee/Claimant Data Elements Constants
define(PAYEE_ID,"payee_id");
define(PAYEE_CODE,"payeecode");
define(PAYEE_NAME,"payeename");
define(UPDATED,"updated");
define(EDIT_COMP,"editcomp");
define(STATUS,"status");

class ETOMSPayee extends DataEncapsulator {
	
	var $m_dbLink,
		$rcount,
		$out;	
	
	/**
	 * Instatiation method, set $bDebug to true to print debug messages otherwise set to false.
	 * $dbLink is a valid database connection.
	 *
	 */
	function ETOMSPayee( $dbLink, $bDebug = false ) {
		$this->m_dbLink = $dbLink;
		$this->setDebugMode( $bDebug );		
		
		// add data elements of class with their respective validation functions an params, used default value for 4th parameter
		// for error message pair in case validation failed 
		$this->addDataElement( PAYEE_ID, "is_not_empty", "[VALUES]");
		$this->addDataElement( PAYEE_CODE, "is_not_empty", "[VALUES]");
		$this->addDataElement( PAYEE_NAME, "is_not_empty", "[VALUES]" );
		$this->addDataElement( UPDATED, "is_not_empty", "[VALUES]" );
		$this->addDataElement( EDIT_COMP, "is_not_empty", "[VALUES]" );
		$this->addDataElement( STATUS, "is_not_empty", "[VALUES]" );
		
	}
	
	/**
	 * Adds new owner to ebls_owner table
	 *
	 */
	function add(){
			
		if ( $this->m_dbLink ) {
			if ( ( $error_num = $this->validateData() ) > 0 ) {
				
		
				$strValues = $this->data_elems;
								
				
				$ret = etoms_insert_data( $this->m_dbLink, ETOMS_PAYEE_TABLE, $strValues );
				
				if ( $ret < 0 ) {
				
					//$this->debug( "CREATE PAYEE/CLAIMANT FAILED" );
					
					//$this->setError( $ret, get_db_error() );
					return $ret;
					
				} else {
										
					//$this->debug( "CREATE PAYEE/CLAIMANT SUCCESSFULL" );
					$this->data_elems[ OWNER_ID ] = $ret;
					return $ret;
					
				}
								
				
			} else {
			
				$this->debug( "CREATE PAYEE/CLAIMANT FAILED" );
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
		
		$result = etoms_select_data( $this->m_dbLink, ETOMS_PAYEE_TABLE, $strValues, $strWhere, NULL, $strOrderBy, "DESC", NULL );

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
	function update( $payee_id ) {

		$arrData = $this->getData();
		foreach( $arrData as $key=>$value){
		
			if ( $arrData[$key] != NULL ) {
			  
				$strValues[$key] = $value;
				
			}
		
		}
		
		if ( ( $error_num = $this->validateData(true) ) > 0 ) {
		
			
			$strWhere[PAYEE_ID] = $payee_id;
	
			$ret = etoms_update_data( $this->m_dbLink, ETOMS_PAYEE_TABLE, $strValues, $strWhere );
			
			
			if ( $ret < 0 ) {
			
				
				
				return $ret;
				
			} else {
									
				return $ret;
				
			}					
			
		} else {
			
			return -1;
			
		}
	
	}
	
	function delete( $owner_id ) {
		
		$strWhere[OWNER_ID] = $owner_id;
		$strValues[] = "count(*) as cnt";

		// check if owner have existing transactions
		$result = etoms_select_data( $this->m_dbLink, ETOMS_TRANSACTION_TABLE, $strValues, $strWhere, NULL, NULL, NULL, NULL );
		
		if ( ( is_array( $result ) && $result[0]["cnt"] > 0 ) ) {
		
			$this->debug( "DELETE OWNER FAILED, OWNER HAVE AN EXISTING TRANSACTION");
			return -1;
			
		} else if ( $result < 0 ) {
		
			$this->debug( "DELETE OWNER FAILED [error:$ret,msg=" . get_db_error() . "]");
			return -1;	
		}
			
		$result = etoms_delete_data ( $this->m_dbLink, ETOMS_PAYEE_TABLE, $strWhere );
		
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
		$result = etoms_select_data( $this->m_dbLink, ETOMS_PAYEE_TABLE, $strValues, $strWhere, NULL, NULL, NULL, NULL );
		
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
	function search( $payeeid = NULL, $payeecode = NULL ) {
		
		if ( $payeeid != NULL ) {
			$strWhere[PAYEE_ID] = $payeeid;
		}
		
		if ( $payeecode != NULL ) {
			$strWhere[PAYEE_CODE] = $payeecode;
		}
		
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
		
		$result = etoms_select_data( $this->m_dbLink, ETOMS_PAYEE_TABLE, $strValues, $strWhere, NULL, NULL, NULL, NULL);	
		
		$fetchrecord = etoms_fetch_data( $this->m_dbLink, $result) ;
		
		$this->out = $fetchrecord; 
		
			
	}
	
	function numrows($result)
        {
                $this->outnumrow = mysql_num_rows($result);
        }
				

	function pagesearch($page = 1, $maxrec = 1000000000, $orderkey = PAYEE_CODE, $is_desc = true ) {
	
		// select all columns
		$strValues1[] = "*";
		$strValues2[] = "count(*)";
		
		if ( $orderkey != NULL ) {
				
			$strOrder[$orderkey] = $orderkey;
			
		} else {
			
			$strOrder = $orderkey;
			
		}
		
		$result = etoms_select_data( $this->m_dbLink, ETOMS_PAYEE_TABLE, $strValues1, $strWhere, NULL, $strOrder, $is_desc?"DESC":"ASC", $maxrec, $page  );	
		
		$rowcount = etoms_select_data( $this->m_dbLink, ETOMS_PAYEE_TABLE, $strValues2, $strWhere, NULL, $strOrder, $is_desc?"DESC":"ASC", NULL, NULL );	
		$sqlCount = $rowcount["count_sql"];
		$this->out = $result;
		$this->rcount = $sqlCount;
		//echo $rowcount."VooDoo";
		
		
	}

        
}





?>