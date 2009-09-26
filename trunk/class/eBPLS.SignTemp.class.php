<?
/************************************************************************************

Module : eBPLS.SignTemp.class.php

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
define(EBPLS_REPORT_SIGN_TABLE,"report_signatories");

// Industry Sector Data Elements Constants
define(RS_ID,"rs_id");
define(REPORT_FILE,"report_file");
define(SIGN_ID,"sign_id");
define(DATE_UPDATED,"date_updated");
define(UPDATED_BY,"updated_by");
define(SIGN_TYPE,"sign_type");

class EBPLSSignTemp extends DataEncapsulator {
	
	var $m_dbLink,
		$rcount,
		$out;	
	
	function EBPLSSignTemp( $dbLink, $bDebug = false ) {
		$this->m_dbLink = $dbLink;
		$this->setDebugMode( $bDebug );		
		
		// add data elements of class with their respective validation functions an params, used default value for 4th parameter
		// for error message pair in case validation failed 
		$this->addDataElement( RS_ID, "is_not_empty", "[VALUES]");
		$this->addDataElement( REPORT_FILE, "is_not_empty", "[VALUES]");
		$this->addDataElement( SIGN_ID, "is_not_empty", "[VALUES]");
		$this->addDataElement( DATE_UPDATED, "is_not_empty", "[VALUES]");
		$this->addDataElement( UPDATED_BY, "is_not_empty", "[VALUES]");
		$this->addDataElement( SIGN_TYPE, "is_not_empty", "[VALUES]");
		
	}
	
	/**
	 * Adds new Industry Sector to ebls_INDUSTRY_SECTOR table
	 *
	 */
	function add(){
			
		if ( $this->m_dbLink ) {
			if ( ( $error_num = $this->validateData() ) > 0 ) {
				
		
				$strValues = $this->data_elems;
								
				
				$ret = ebpls_insert_data( $this->m_dbLink, EBPLS_REPORT_SIGN_TABLE, $strValues );
				
				if ( $ret < 0 ) {
				
					//$this->debug( "CREATE OCCUPNACY TYPE FAILED" );
					
					//$this->setError( $ret, get_db_error() );
					return $ret;
					
				} else {
										
					//$this->debug( "CREATE OCCUPNACY TYPE SUCCESSFULL" );
					$this->data_elems[ RS_ID ] = $ret;
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
	function view( $rs_id ) {
							
		$strValues[$key] = "*";
		
		$strWhere[RS_ID] = $rs_id;			
		
		$result = ebpls_select_data( $this->m_dbLink, EBPLS_REPORT_SIGN_TABLE, $strValues, $strWhere, NULL, $strOrderBy, "DESC", NULL );

		if ( is_array($result) ) {
		
			$this->data_elems = $result[0];
			return $result[0][RS_ID];
			
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
	function update( $rs_id ) {

		$arrData = $this->getData();
		foreach( $arrData as $key=>$value){
		
			if ( $arrData[$key] != NULL ) {
			  
				$strValues[$key] = $value;
				
			}
		
		}
		
		if ( ( $error_num = $this->validateData(true) ) > 0 ) {
		
			
			$strWhere[RS_ID] = $rs_id;
	
			$ret = ebpls_update_data( $this->m_dbLink, EBPLS_REPORT_SIGN_TABLE, $strValues, $strWhere );
			
			
			if ( $ret < 0 ) {
			
				
				
				return $ret;
				
			} else {
									
				return $ret;
				
			}					
			
		} else {
			
			return -1;
			
		}
	
	}
	
	function delete( $rs_id ) {
		
		$strWhere[RS_ID] = $rs_id;
		$strValues[] = "count(*) as cnt";

		// check if owner have existing transactions
			
		$result = ebpls_delete_data ( $this->m_dbLink, EBPLS_REPORT_SIGN_TABLE, $strWhere );
		
		if ( $result < 0 )  {
			
			$this->setError( $result, get_db_error() );
			
		}
		
		return $result;		
	
	}
	
	function search( $rs_id = NULL, $report_file = NULL ) {
		
		if ( $rs_id != NULL and $report_file == NULL ) {
			$strWhere[RS_ID] = $rs_id;
		}
		
		if ( $rs_id == NULL and $report_file != NULL ) {
			$strWhere[REPORT_FILE] = $report_file;
		}
		if ( $rs_id != NULL and $report_file != NULL ) {
			$strWhere[REPORT_FILE] = $report_file;
			$strWhere[SIGN_ID] = $rs_id;
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
		
		$result = ebpls_select_data( $this->m_dbLink, EBPLS_REPORT_SIGN_TABLE, $strValues, $strWhere, NULL, NULL, NULL, NULL);	
		
		$fetchrecord = ebpls_fetch_data( $this->m_dbLink, $result) ;
		
		$this->out = $fetchrecord; 
		
			
	}
	
	function searchcomp($report_file = NULL, $sign_id = NULL, $type = NULL) {
		
		$ifexist = @mysql_query("select * from report_signatories where report_file = '$report_file' and sign_id = '$sign_id' and sign_type = '$type'");
		$fetchrecord = @mysql_num_rows($ifexist) ;
		$this->rcount = $fetchrecord; 
		
	}
	
	function searchcomp1($bbo, $report_file = NULL, $sign_id = NULL, $type = NULL) {
		
		$ifexist = @mysql_query("select * from report_signatories where report_file = '$report_file' and sign_id = '$sign_id' and sign_type = '$type' and rs_id <> '$bbo'");
		$fetchrecord = @mysql_num_rows($ifexist) ;
		$this->rcount = $fetchrecord; 
		
	}
	

	function pagesearch($page = 1, $maxrec = 1000000000, $orderkey = RS_ID, $is_desc = true ) {
	
		// select all columns
		$strValues1[] = "*";
		$strValues2[] = "count(*)";
		
		if ( $orderkey != NULL ) {
				
			$strOrder[$orderkey] = $orderkey;
			
		} else {
			
			$strOrder = $orderkey;
			
		}
		//echo $is_desc."Robert";
		$result = ebpls_select_data( $this->m_dbLink, EBPLS_REPORT_SIGN_TABLE, $strValues1, $strWhere, NULL, $strOrder, $is_desc, $maxrec, $page  );	
		
		$rowcount = ebpls_select_data( $this->m_dbLink, EBPLS_REPORT_SIGN_TABLE, $strValues2, $strWhere, NULL, $strOrder, $is_desc, NULL, NULL );	
		//$sqlCount = $rowcount["count_sql"];
		$this->out = $result;
		$this->rcount = $rowcount;
		//echo $rowcount."VooDoo";
		
		
	}

        
}





?>