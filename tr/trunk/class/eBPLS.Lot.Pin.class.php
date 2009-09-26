<?
/************************************************************************************

Module : eBPLS.Lot.Pin.class.php

Dependencies : 
	eBPLS.dbfuncs.php
	eBPLS.dataencapsulator.class.php
	
Description : 
	- encapsulates Lot PIN

Created By : Robert M. Verzosa
Email : rmv71279@yahoo.com, verzosar@dap.edu.ph
Date Created : 12/12/2005

	
************************************************************************************/

require_once("class/eBPLS.dataencapsulator.class.php");
require_once("lib/eBPLS.dbfuncs.php");

// keys for getData method
define(EBPLS_LOTPIN_TABLE,"lot_pin");

// Industry Sector Data Elements Constants
define(EAID,"eaid");
define(ANNOUNCEMENT,"announcements");
define(ANNOUNCED_BY,"announced_by");
define(DATE_MODIFIED,"date_modified");
define(MODIFIED_BY,"modified_by");
define(SMS_SEND,"sms_send");

class EBPLSAnnouncement extends DataEncapsulator {
	
	var $m_dbLink,
		$rcount,
		$out;	
	
	function EBPLSAnnouncement( $dbLink, $bDebug = false ) {
		$this->m_dbLink = $dbLink;
		$this->setDebugMode( $bDebug );		
		
		// add data elements of class with their respective validation functions an params, used default value for 4th parameter
		// for error message pair in case validation failed 
		$this->addDataElement( EAID, "is_not_empty", "[VALUES]");
		$this->addDataElement( ANNOUNCEMENT, "is_not_empty", "[VALUES]");
		$this->addDataElement( ANNOUNCED_BY, "is_not_empty", "[VALUES]");
		$this->addDataElement( DATE_MODIFIED, "is_not_empty", "[VALUES]");
		$this->addDataElement( MODIFIED_BY, "is_not_empty", "[VALUES]");
		$this->addDataElement( SMS_SEND, "is_not_empty", "[VALUES]");
		
	}
	
	function add(){
			
		if ( $this->m_dbLink ) {
			if ( ( $error_num = $this->validateData() ) > 0 ) {
				
		
				$strValues = $this->data_elems;
								
				
				$ret = ebpls_insert_data( $this->m_dbLink, EBPLS_LOTPIN_TABLE, $strValues );
				
				if ( $ret < 0 ) {
				
					//$this->debug( "CREATE OCCUPNACY TYPE FAILED" );
					
					//$this->setError( $ret, get_db_error() );
					return $ret;
					
				} else {
										
					//$this->debug( "CREATE OCCUPNACY TYPE SUCCESSFULL" );
					$this->data_elems[ EAID ] = $ret;
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
	function update( $eaid ) {

		$arrData = $this->getData();
		foreach( $arrData as $key=>$value){
		
			if ( $arrData[$key] != NULL ) {
			  
				$strValues[$key] = $value;
				
			}
		
		}
		
		if ( ( $error_num = $this->validateData(true) ) > 0 ) {
		
			
			$strWhere[EAID] = $eaid;
	
			$ret = ebpls_update_data( $this->m_dbLink, EBPLS_LOTPIN_TABLE, $strValues, $strWhere );
			
			
			if ( $ret < 0 ) {
			
				
				
				return $ret;
				
			} else {
									
				return $ret;
				
			}					
			
		} else {
			
			return -1;
			
		}
	
	}
	
	function delete( $eaid ) {
		
		$strWhere[EAID] = $eaid;
		$strValues[] = "count(*) as cnt";

		// check if owner have existing transactions
			
		$result = ebpls_delete_data ( $this->m_dbLink, EBPLS_LOTPIN_TABLE, $strWhere );
		
		if ( $result < 0 )  {
			
			$this->setError( $result, get_db_error() );
			
		}
		
		return $result;		
	
	}
	
	function search( $eaid = NULL, $announcement = NULL ) {
		
		if ( $eaid != NULL ) {
			$strWhere[EAID] = $eaid;
		}
		
		if ( $announcement != NULL ) {
			$strWhere[ANNOUNCEMENT] = $announcement;
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
		
		$result = ebpls_select_data( $this->m_dbLink, EBPLS_LOTPIN_TABLE, $strValues, $strWhere, NULL, NULL, NULL, NULL);	
		
		$rowcount = ebpls_select_data( $this->m_dbLink, EBPLS_LOTPIN_TABLE, $strValues1, $strWhere, NULL, NULL, NULL, NULL);	
		
		$fetchrecord = ebpls_fetch_data( $this->m_dbLink, $result) ;
		
		$fetchcount = @mysql_fetch_row($rowcount);
		
		$this->out = $fetchrecord; 
		
		$this->rcount = $fetchcount; 
		
			
	}
	

	function pagesearch($page = 1, $maxrec = 1000000000, $orderkey = EAID, $is_desc = true ) {
	
		// select all columns
		$strValues1[] = "*";
		$strValues2[] = "count(*)";
		
		if ( $orderkey != NULL ) {
				
			$strOrder[$orderkey] = $orderkey;
			
		} else {
			
			$strOrder = $orderkey;
			
		}
		//echo $is_desc."Robert";
		$result = ebpls_select_data( $this->m_dbLink, EBPLS_LOTPIN_TABLE, $strValues1, $strWhere, NULL, $strOrder, $is_desc, $maxrec, $page  );	
		
		$rowcount = ebpls_select_data( $this->m_dbLink, EBPLS_LOTPIN_TABLE, $strValues2, $strWhere, NULL, $strOrder, $is_desc, NULL, NULL );	
		//$sqlCount = $rowcount["count_sql"];
		$this->out = $result;
		$this->rcount = $rowcount;
		//echo $rowcount."VooDoo";
		
		
	}

       	function searchcomp($bbo, $announcement) {
		
		$ifexist = @mysql_query("select * from ebpls_announcement where announcements = '$announcement' and eaid <> '$bbo'");
		//echo "select * from ebpls_announcement where announcements = '$announcement' and eaid <> '$bbo'";
		$ifexist = @mysql_num_rows($ifexist);
		$this->rcount = $ifexist;

        }
		
		function getLgu() {
		
		$getlgu = @mysql_query("select * from ebpls_buss_preference");
		//echo "select * from ebpls_announcement where announcements = '$announcement' and eaid <> '$bbo'";
		$getlgu = @mysql_fetch_assoc($getlgu);
		$this->out = $getlgu;

        }
		

	
}





?>
