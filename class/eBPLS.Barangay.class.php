<?
/************************************************************************************

Module : eTOMS.Province.class.php

Dependencies : 
	eBPLS.dbfuncs.php
	eBPLS.dataencapsulator.class.php
	
Description : 
	- encapsulates Province

Created By : Robert M. Verzosa
Email : rmv71279@yahoo.com, verzosar@dap.edu.ph
Date Created : 12/12/2005

	
************************************************************************************/

require_once("class/eBPLS.dataencapsulator.class.php");
require_once("lib/eBPLS.dbfuncs.php");

// keys for getData method
define(EBPLS_BARANGAY_TABLE,"ebpls_barangay");

// Industry Sector Data Elements Constants
define(BARANGAY_CODE,"barangay_code");
define(BARANGAY_DESC,"barangay_desc");
define(BARANGAY_DATE_REGISTERED,"barangay_date_registered");
define(BARANGAY_DATE_UPDATED,"barangay_date_updated");
define(UPDATED_BY,"updated_by");
define(G_ZONE,"g_zone");
define(UPPER,"upper");
define(BLGF_CODE,"blgf_code");

class EBPLSBarangay extends DataEncapsulator {
	
	var $m_dbLink,
		$rcount,
		$out;	
	
	function EBPLSBarangay( $dbLink, $bDebug = false ) {
		$this->m_dbLink = $dbLink;
		$this->setDebugMode( $bDebug );		
		
		// add data elements of class with their respective validation functions an params, used default value for 4th parameter
		// for error message pair in case validation failed 
		$this->addDataElement( BARANGAY_CODE, "is_not_empty", "[VALUES]");
		$this->addDataElement( BARANGAY_DESC, "is_not_empty", "[VALUES]");
		$this->addDataElement( BARANGAY_DATE_REGISTERED, "is_not_empty", "[VALUES]");
		$this->addDataElement( BARANGAY_DATE_UPDATED, "is_not_empty", "[VALUES]");
		$this->addDataElement( UPDATED_BY, "is_not_empty", "[VALUES]");
		$this->addDataElement( G_ZONE, "is_not_empty", "[VALUES]");
		$this->addDataElement( UPPER, "is_not_empty", "[VALUES]");
		$this->addDataElement( BLGF_CODE, "is_not_empty", "[VALUES]");
		
	}
	
	/**
	 * Adds new Industry Sector to ebls_INDUSTRY_SECTOR table
	 *
	 */
	function add(){
			
		if ( $this->m_dbLink ) {
			if ( ( $error_num = $this->validateData() ) > 0 ) {
				
		
				$strValues = $this->data_elems;
								
				
				$ret = ebpls_insert_data( $this->m_dbLink, EBPLS_BARANGAY_TABLE, $strValues );
				
				if ( $ret < 0 ) {
				
					//$this->debug( "CREATE OCCUPNACY TYPE FAILED" );
					
					//$this->setError( $ret, get_db_error() );
					return $ret;
					
				} else {
										
					//$this->debug( "CREATE OCCUPNACY TYPE SUCCESSFULL" );
					$this->data_elems[ BARANGAY_CODE ] = $ret;
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
	function view( $barangay_code ) {
							
		$strValues[$key] = "*";
		
		$strWhere[BARANGAY_CODE] = $barangay_code;			
		
		$result = ebpls_select_data( $this->m_dbLink, EBPLS_BARANGAY_TABLE, $strValues, $strWhere, NULL, $strOrderBy, "DESC", NULL );

		if ( is_array($result) ) {
		
			$this->data_elems = $result[0];
			return $result[0][BARANGAY_CODE];
			
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
	function update( $barangay_code ) {

		$arrData = $this->getData();
		foreach( $arrData as $key=>$value){
		
			if ( $arrData[$key] != NULL ) {
			  
				$strValues[$key] = $value;
				
			}
		
		}
		
		if ( ( $error_num = $this->validateData(true) ) > 0 ) {
		
			
			$strWhere[BARANGAY_CODE] = $barangay_code;
	
			$ret = ebpls_update_data( $this->m_dbLink, EBPLS_BARANGAY_TABLE, $strValues, $strWhere );
			
			
			if ( $ret < 0 ) {
			
				
				
				return $ret;
				
			} else {
									
				return $ret;
				
			}					
			
		} else {
			
			return -1;
			
		}
	
	}
	
	function delete( $barangay_code ) {
		
		$strWhere[BARANGAY_CODE] = $barangay_code;
		$strValues[] = "count(*) as cnt";

		// check if owner have existing transactions
			
		$result = ebpls_delete_data ( $this->m_dbLink, EBPLS_BARANGAY_TABLE, $strWhere );
		
		if ( $result < 0 )  {
			
			$this->setError( $result, get_db_error() );
			
		}
		
		return $result;		
	
	}
	
	function search( $barangay_code = NULL, $barangay_desc = NULL ) {
		
		if ( $barangay_code != NULL ) {
			$strWhere[BARANGAY_CODE] = $barangay_code;
		}
		
		if ( $barangay_desc != NULL ) {
			$strWhere[BARANGAY_DESC] = $barangay_desc;
		}
		
		// select all columns
		$strValues[] = "*";
		$strValues1[] = "count(*)";
		
		if ( $orderkey != NULL ) {
				
			$strOrder[$orderkey] = $orderkey;
			
		} else {
			
			$strOrder = $orderkey;
			
		}
		
		if ( count($strWhere) <= 0 ) {
			
			$this->setError ( -1, "No search parameters." );
			return -1;	
			
		}
		
		$result = ebpls_select_data( $this->m_dbLink, EBPLS_BARANGAY_TABLE, $strValues, $strWhere, NULL, NULL, NULL, NULL);	
		
		$rowcount = ebpls_select_data( $this->m_dbLink, EBPLS_BARANGAY_TABLE, $strValues1, $strWhere, NULL, NULL, NULL, NULL);	
		
		$fetchrecord = ebpls_fetch_data( $this->m_dbLink, $result) ;
		
		$fetchcount = @mysql_fetch_row($rowcount);
		
		$this->out = $fetchrecord; 
		
		$this->rcount = $fetchcount; 
		
			
	}
	
	function searchcomp( $barangay_code = NULL, $barangay_desc = NULL, $upper = NULL ) {
		
		
		$rowcount = @mysql_query("Select * from ebpls_barangay where barangay_desc = '$barangay_desc' and upper = '$upper' and barangay_code <> '$barangay_code'");	
		$fetchcount = @mysql_num_rows($rowcount);
		$this->rcount = $fetchcount; 
		
			
	}
	
	function searchcomp1( $upper = NULL, $barangay_desc = NULL) {
		
		$rowcount = @mysql_query("Select * from ebpls_barangay where barangay_desc = '$barangay_desc' and upper = '$upper'");	
		$fetchcount = @mysql_num_rows($rowcount);
		$this->rcount = $fetchcount; 
			
	}
	

	function pagesearch($page = 1, $maxrec = 1000000000, $orderkey = BARANGAY_CODE, $is_desc = true ) {
	
		// select all columns
		$strValues1[] = "*";
		$strValues2[] = "count(*)";
		
		if ( $orderkey != NULL ) {
				
			$strOrder[$orderkey] = $orderkey;
			
		} else {
			
			$strOrder = $orderkey;
			
		}
		//echo $is_desc."Robert";
		$result = ebpls_select_data( $this->m_dbLink, EBPLS_BARANGAY_TABLE, $strValues1, $strWhere, NULL, $strOrder, $is_desc, $maxrec, $page  );	
		
		$rowcount = ebpls_select_data( $this->m_dbLink, EBPLS_BARANGAY_TABLE, $strValues2, $strWhere, NULL, $strOrder, $is_desc, NULL, NULL );	
		//$sqlCount = $rowcount["count_sql"];
		$this->out = $result;
		$this->rcount = $rowcount;
		//echo $rowcount."VooDoo";
		
		
	}

        
}





?>