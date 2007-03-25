<?
/************************************************************************************

Module : eBPLS.permit.format.class.php

Dependencies : 
	eBPLS.dbfuncs.php
	eBPLS.dataencapsulator.class.php
	
Description : 
	- encapsulates citizenship

Created By : Robert M. Verzosa
Email : rmv71279@yahoo.com, verzosar@dap.edu.ph
Date Created : 9/29/2005

	
************************************************************************************/

require_once("class/eBPLS.dataencapsulator.class.php");
require_once("lib/eBPLS.dbfuncs.php");

// keys for getData method
define(EBPLS_PERMIT_TEMPLATES_TABLE,"permit_templates");

// Industry Sector Data Elements Constants
define(TEMP_ID,"tempid");
define(PERMIT_TYPE,"permit_type");
define(USER,"user");
define(DATE_ENTERED,"date_entered");
define(PERMIT_HEADER,"permit_header");
define(PERMIT_DATE,"permit_date");
define(PERMIT_SEQUENCE,"permit_sequence");

class EBPLSPermitFormat extends DataEncapsulator {
	
	var $m_dbLink,
		$rcount,
		$out;	
	
	/**
	 * Instatiation method, set $bDebug to true to print debug messages otherwise set to false.
	 * $dbLink is a valid database connection.
	 *
	 */
	function EBPLSPermitFormat( $dbLink, $bDebug = false ) {
		$this->m_dbLink = $dbLink;
		$this->setDebugMode( $bDebug );		
		
		// add data elements of class with their respective validation functions an params, used default value for 4th parameter
		// for error message pair in case validation failed 
		$this->addDataElement( TEMP_ID, "is_not_empty", "[VALUES]");
		$this->addDataElement( PERMIT_TYPE, "is_not_empty", "[VALUES]");
		$this->addDataElement( USER, "is_not_empty", "[VALUES]");
		$this->addDataElement( DATE_ENTERED, "is_not_empty", "[VALUES]");
		$this->addDataElement( PERMIT_HEADER, "is_not_empty", "[VALUES]");
		$this->addDataElement( PERMIT_DATE, "is_not_empty", "[VALUES]");
		$this->addDataElement( PERMIT_SEQUENCE, "is_not_empty", "[VALUES]");
		
	}
	
	/**
	 * Adds new Industry Sector to ebls_INDUSTRY_SECTOR table
	 *
	 */
	function add(){
			
		if ( $this->m_dbLink ) {
			if ( ( $error_num = $this->validateData() ) > 0 ) {
				
		
				$strValues = $this->data_elems;
								
				
				$ret = ebpls_insert_data( $this->m_dbLink, EBPLS_PERMIT_TEMPLATES_TABLE, $strValues );
				
				if ( $ret < 0 ) {
				
					//$this->debug( "CREATE OCCUPNACY TYPE FAILED" );
					
					//$this->setError( $ret, get_db_error() );
					return $ret;
					
				} else {
										
					//$this->debug( "CREATE OCCUPNACY TYPE SUCCESSFULL" );
					$this->data_elems[ OWNER_ID ] = $ret;
					return $ret;
					
				}
								
				
			} else {
			
				//$this->debug( "CREATE INDUSTRY FAILED" );
				return $error_num;
			
			}
			
		} else {
		
			//$this->debug( "CREATE OWNER FAILED INVALID DB LINK $this->m_dbLink" );
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
		
		$result = ebpls_select_data( $this->m_dbLink, EBPLS_PERMIT_TEMPLATES_TABLE, $strValues, $strWhere, NULL, $strOrderBy, "DESC", NULL );

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
	function update( $temp_id ) {

		$arrData = $this->getData();
		foreach( $arrData as $key=>$value){
		
			if ( $arrData[$key] != NULL ) {
			  
				$strValues[$key] = $value;
				
			}
		
		}
		
		if ( ( $error_num = $this->validateData(true) ) > 0 ) {
		
			
			$strWhere[TEMP_ID] = $temp_id;
	
			$ret = ebpls_update_data( $this->m_dbLink, EBPLS_PERMIT_TEMPLATES_TABLE, $strValues, $strWhere );
			
			
			if ( $ret < 0 ) {
			
				
				
				return $ret;
				
			} else {
									
				return $ret;
				
			}					
			
		} else {
			
			return -1;
			
		}
	
	}
	
	function delete( $temp_id ) {
		
		$strWhere[TEMP_ID] = $temp_id;
		$strValues[] = "count(*) as cnt";

			
		$result = ebpls_delete_data ( $this->m_dbLink, EBPLS_PERMIT_TEMPLATES_TABLE, $strWhere );
		
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
		$result = ebpls_select_data( $this->m_dbLink, EBPLS_PERMIT_TEMPLATES_TABLE, $strValues, $strWhere, NULL, NULL, NULL, NULL );
		
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
	function search( $temp_id = NULL, $permit_type = NULL ) {
		
		if ( $temp_id != NULL and $permit_type == NULL) {
			$strWhere[TEMP_ID] = $temp_id;
		}
		
		if ( $permit_type != NULL and $temp_id == NULL) {
			$strWhere[PERMIT_TYPE] = $permit_type;
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
		
		$result = ebpls_select_data( $this->m_dbLink, EBPLS_PERMIT_TEMPLATES_TABLE, $strValues, $strWhere, NULL, NULL, NULL, NULL);	
		
		$fetchrecord = ebpls_fetch_data( $this->m_dbLink, $result) ;
		
		$this->out = $fetchrecord; 
		
			
	}
	
	function searchcomp( $tempid = NULL, $permit_type = NULL) {
		
		
		$rowcount = @mysql_query("Select count(*) from permit_templates where permit_type = '$permit_type' and tempid <> '$tempid'");	
		
		
		$fetchcount = @mysql_fetch_row($rowcount);
		
		
		$this->rcount = $fetchcount; 
		
			
	}
	

	function numrows($result)
        {
                $this->outnumrow = mysql_num_rows($result);
        }
				

	function pagesearch($page = 1, $maxrec = 1000000000, $orderkey = TEMP_ID, $is_desc = true ) {
	
		// select all columns
		$strValues1[] = "*";
		$strValues2[] = "count(*)";
		
		if ( $orderkey != NULL ) {
				
			$strOrder[$orderkey] = $orderkey;
			
		} else {
			
			$strOrder = $orderkey;
			
		}
		//echo $is_desc."Robert";
		$result = ebpls_select_data( $this->m_dbLink, EBPLS_PERMIT_TEMPLATES_TABLE, $strValues1, $strWhere, NULL, $strOrder, $is_desc, $maxrec, $page  );	
		
		$rowcount = ebpls_select_data( $this->m_dbLink, EBPLS_PERMIT_TEMPLATES_TABLE, $strValues2, $strWhere, NULL, $strOrder, $is_desc, NULL, NULL );	
		//$sqlCount = $rowcount["count_sql"];
		$this->out = $result;
		$this->rcount = $rowcount;
		//echo $rowcount."VooDoo";
		
		
	}

        
}





?>