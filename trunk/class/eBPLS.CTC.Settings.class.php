<?
/************************************************************************************

Module : eBPLS.Province.class.php

Description : encapsulates Province

Created By : Robert M. Verzosa
Email : rmv71279@yahoo.com, verzosar@dap.edu.ph
Date Created : 12/12/2005
	
************************************************************************************/

require_once("class/eBPLS.dataencapsulator.class.php");
require_once("lib/eBPLS.dbfuncs.php");

// keys for getData method
define('EBPLS_CTC_SETTINGS_TABLE',"ebpls_ctc_interest");

// Industry Sector Data Elements Constants
define('ID',"id");
define('CTC_TYPE',"ctc_type");
define('INTEREST_RATE',"interest_rate");
define('CEILING_RATE',"ceiling_rate");
define('PENALTY_DATE',"penalty_date");
define('MODIFIED_DATE',"modified_date");
define('UPDATED_BY',"updated_by");

class EBPLSCTCSettings extends DataEncapsulator {
	
var $m_dbLink, $rcount, $out;	
	
function EBPLSCTCSettings( $dbLink, $bDebug = false ) {
		$this->m_dbLink = $dbLink;
		$this->setDebugMode( $bDebug );		
		
		// add data elements of class with their respective validation functions an params, used default value for 4th parameter
		// for error message pair in case validation failed 
		$this->addDataElement( ID, "is_not_empty", "[VALUES]");
		$this->addDataElement( CTC_TYPE, "is_not_empty", "[VALUES]");
		$this->addDataElement( INTEREST_RATE, "is_not_empty", "[VALUES]");
		$this->addDataElement( CEILING_RATE, "is_not_empty", "[VALUES]");
		$this->addDataElement( PENALTY_DATE, "is_not_empty", "[VALUES]");
		$this->addDataElement( MODIFIED_DATE, "is_not_empty", "[VALUES]");
		$this->addDataElement( UPDATED_BY, "is_not_empty", "[VALUES]");
		
	}
	
	/**
	 * Adds new Industry Sector to ebls_INDUSTRY_SECTOR table
	 */
function add(){
			
		if ( $this->m_dbLink ) {
			if ( ( $error_num = $this->validateData() ) > 0 ) {
				$strValues = $this->data_elems;
				$ret = ebpls_insert_data( $this->m_dbLink, EBPLS_CTC_SETTINGS_TABLE, $strValues );
				if ( $ret < 0 ) {
					//$this->debug( "CREATE OCCUPNACY TYPE FAILED" );
					//$this->setError( $ret, get_db_error() );
					return $ret;
				} else {
					//$this->debug( "CREATE OCCUPNACY TYPE SUCCESSFULL" );
					$this->data_elems[ ID ] = $ret;
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
	 */
function view( $id ) {
							
		$strValues[$key] = "*";
		$strWhere[ID] = $id;			
		$result = ebpls_select_data( $this->m_dbLink, EBPLS_CTC_SETTINGS_TABLE, $strValues, $strWhere, NULL, $strOrderBy, "DESC", NULL );

		if ( is_array($result) ) {
			$this->data_elems = $result[0];
			return $result[0][ID];
		} else {
			$this->setError( $result, get_db_error() );
			return -1;
		}
	}
	
	/**
	 * Update owner data
	 **/
function update( $id ) {

		$arrData = $this->getData();
		foreach( $arrData as $key=>$value){
			if ( $arrData[$key] != NULL ) {
				$strValues[$key] = $value;
			}
		}
		
		if ( ( $error_num = $this->validateData(true) ) > 0 ) {
			$strWhere[ID] = $id;
			$ret = ebpls_update_data( $this->m_dbLink, EBPLS_CTC_SETTINGS_TABLE, $strValues, $strWhere );
			if ( $ret < 0 ) {
				return $ret;
			} else {
				return $ret;
			}					
		} else {
			return -1;
		}
	}
	
function delete( $id ) {
		
		$strWhere[ID] = $id;
		$strValues[] = "count(*) as cnt";

		// check if owner have existing transactions
			
		$result = ebpls_delete_data ( $this->m_dbLink, EBPLS_CTC_SETTINGS_TABLE, $strWhere );
		
		if ( $result < 0 )  {
			$this->setError( $result, get_db_error() );
		}
		return $result;		
	}
	
function search( $id = NULL, $ctc_type = NULL ) {
		
		if ( $id != NULL ) {
			$strWhere[ID] = $id;
		}
		
		if ( $ctc_type != NULL ) {
			$strWhere[CTC_TYPE] = $ctc_type;
		}
		// select all columns
		$strValues[] = "*";
		
		if ( isset($orderkey) ) { //2008.05.13 RJC
			$strOrder[$orderkey] = $orderkey;
		} else {
//			$strOrder = $orderkey;
			$strOrder = ''; //2008.05.13 RJC
		}
		
		if ( count($strWhere) <= 0 ) {
			$this->setError ( -1, "No search parameters." );
			return -1;	
		}
		
		$result = ebpls_select_data( $this->m_dbLink, EBPLS_CTC_SETTINGS_TABLE, $strValues, $strWhere, NULL, NULL, NULL, NULL);	
		$fetchrecord = ebpls_fetch_data( $this->m_dbLink, $result) ;
		$this->out = $fetchrecord; 
	}
	

function pagesearch($page = 1, $maxrec = 1000000000, $orderkey = ID, $is_desc = true ) {
	
		// select all columns
		$strValues1[] = "*";
		$strValues2[] = "count(*)";
		
		if ( $orderkey != NULL ) {
			$strOrder[$orderkey] = $orderkey;
		} else {
			$strOrder = $orderkey;
		}
		//echo $is_desc."Robert";
		$strWhere = isset($strWhere) ? $strWhere : '';
		$result = ebpls_select_data( $this->m_dbLink, EBPLS_CTC_SETTINGS_TABLE, $strValues1, $strWhere, NULL, $strOrder, $is_desc, $maxrec, $page  );	
		
		$rowcount = ebpls_select_data( $this->m_dbLink, EBPLS_CTC_SETTINGS_TABLE, $strValues2, $strWhere, NULL, $strOrder, $is_desc, NULL, NULL );	
		//$sqlCount = $rowcount["count_sql"];
		$this->out = $result;
		$this->rcount = $rowcount;
		//echo $rowcount."VooDoo";
	}
}
?>
