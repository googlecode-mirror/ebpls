<?
/************************************************************************************

Module : eBPLS.Signatories.class.php

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
define(EBPLS_SIGNATORIES_TABLE,"global_sign");

// Industry Sector Data Elements Constants
define(SIGN_ID,"sign_id");
define(GS_NAME,"gs_name");
define(GS_POS,"gs_pos");
define(GS_OFFICE,"gs_office");

class EBPLSSign extends DataEncapsulator {
	
	var $m_dbLink,
		$rcount,
		$out;	
	
	function EBPLSSign( $dbLink, $bDebug = false ) {
		$this->m_dbLink = $dbLink;
		$this->setDebugMode( $bDebug );		
		
		// add data elements of class with their respective validation functions an params, used default value for 4th parameter
		// for error message pair in case validation failed 
		$this->addDataElement( SIGN_ID, "is_not_empty", "[VALUES]");
		$this->addDataElement( GS_NAME, "is_not_empty", "[VALUES]");
		$this->addDataElement( GS_POS, "is_not_empty", "[VALUES]");
		$this->addDataElement( GS_OFFICE, "is_not_empty", "[VALUES]");
		
	}
	
	/**
	 * Adds new Industry Sector to ebls_INDUSTRY_SECTOR table
	 *
	 */
	function add(){
			
		if ( $this->m_dbLink ) {
			if ( ( $error_num = $this->validateData() ) > 0 ) {
				
		
				$strValues = $this->data_elems;
								
				
				$ret = ebpls_insert_data( $this->m_dbLink, EBPLS_SIGNATORIES_TABLE, $strValues );
				
				if ( $ret < 0 ) {
				
					//$this->debug( "CREATE OCCUPNACY TYPE FAILED" );
					
					//$this->setError( $ret, get_db_error() );
					return $ret;
					
				} else {
										
					//$this->debug( "CREATE OCCUPNACY TYPE SUCCESSFULL" );
					$this->data_elems[ SIGN_ID ] = $ret;
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
	function view( $sign_id ) {
							
		$strValues[$key] = "*";
		
		$strWhere[SIGN_ID] = $sign_id;			
		
		$result = ebpls_select_data( $this->m_dbLink, EBPLS_SIGNATORIES_TABLE, $strValues, $strWhere, NULL, $strOrderBy, "DESC", NULL );

		if ( is_array($result) ) {
		
			$this->data_elems = $result[0];
			return $result[0][SIGN_ID];
			
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
	function update( $sign_id ) {

		$arrData = $this->getData();
		foreach( $arrData as $key=>$value){
		
			if ( $arrData[$key] != NULL ) {
			  
				$strValues[$key] = $value;
				
			}
		
		}
		
		if ( ( $error_num = $this->validateData(true) ) > 0 ) {
		
			
			$strWhere[SIGN_ID] = $sign_id;
	
			$ret = ebpls_update_data( $this->m_dbLink, EBPLS_SIGNATORIES_TABLE, $strValues, $strWhere );
			
			
			if ( $ret < 0 ) {
			
				
				
				return $ret;
				
			} else {
									
				return $ret;
				
			}					
			
		} else {
			
			return -1;
			
		}
	
	}
	
	function delete( $sign_id ) {
		
		$strWhere[SIGN_ID] = $sign_id;
		$strValues[] = "count(*) as cnt";

		// check if owner have existing transactions
			
		$result = ebpls_delete_data ( $this->m_dbLink, EBPLS_SIGNATORIES_TABLE, $strWhere );
		
		if ( $result < 0 )  {
			
			$this->setError( $result, get_db_error() );
			
		}
		
		return $result;		
	
	}
	
	function search( $sign_id = NULL, $gs_name = NULL ) {
		
		if ( $sign_id != NULL ) {
			$strWhere[SIGN_ID] = $sign_id;
		}
		
		if ( $gs_name != NULL ) {
			$strWhere[GS_NAME] = $gs_name;
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
		
		$result = ebpls_select_data( $this->m_dbLink, EBPLS_SIGNATORIES_TABLE, $strValues, $strWhere, NULL, NULL, NULL, NULL);	
		
		$fetchrecord = ebpls_fetch_data( $this->m_dbLink, $result) ;
		
		$this->out = $fetchrecord; 
		
			
	}
	

	function searchcomp( $sign_id = NULL, $gs_name = NULL) {
		
		$rowcount = @mysql_query("Select * from global_sign where gs_name = '$gs_name' and sign_id <> '$sign_id'");	
		$fetchcount = @mysql_num_rows($rowcount);
		$this->rcount = $fetchcount; 
			
	}
	
	function searchcomp1($gs_name = NULL) {
		
		$rowcount = @mysql_query("Select * from global_sign where gs_name = '$gs_name'");	
		$fetchcount = @mysql_num_rows($rowcount);
		$this->rcount = $fetchcount; 
			
	}

	function pagesearch($page = 1, $maxrec = 1000000000, $orderkey = SIGN_ID, $is_desc = true ) {
	
		// select all columns
		$strValues1[] = "*";
		$strValues2[] = "count(*)";
		
		if ( $orderkey != NULL ) {
				
			$strOrder[$orderkey] = $orderkey;
			
		} else {
			
			$strOrder = $orderkey;
			
		}
		//echo $is_desc."Robert";
		$result = ebpls_select_data( $this->m_dbLink, EBPLS_SIGNATORIES_TABLE, $strValues1, $strWhere, NULL, $strOrder, $is_desc, $maxrec, $page  );	
		
		$rowcount = ebpls_select_data( $this->m_dbLink, EBPLS_SIGNATORIES_TABLE, $strValues2, $strWhere, NULL, $strOrder, $is_desc, NULL, NULL );	
		//$sqlCount = $rowcount["count_sql"];
		$this->out = $result;
		$this->rcount = $rowcount;
		//echo $rowcount."VooDoo";
		
		
	}

        
}





?>