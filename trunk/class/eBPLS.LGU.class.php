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
define(EBPLS_LGU_TABLE,"ebpls_city_municipality");

// Industry Sector Data Elements Constants
define(CITY_MUNICIPALITY_CODE,"city_municipality_code");
define(CITY_MUNICIPALITY_DESC,"city_municipality_desc");
define(CITY_MUNICIPALITY_DATE_REGISTERED,"city_municipality_date_registered");
define(CITY_MUNICIPALITY_DATE_UPDATED,"city_municipality_date_updated");
define(UPDATED_BY,"updated_by");
define(UPPER,"upper");
define(BLGF_CODE,"blgf_code");

class EBPLSLGU extends DataEncapsulator {
	
	var $m_dbLink,
		$rcount,
		$out;	
	
	function EBPLSLGU( $dbLink, $bDebug = false ) {
		$this->m_dbLink = $dbLink;
		$this->setDebugMode( $bDebug );		
		
		// add data elements of class with their respective validation functions an params, used default value for 4th parameter
		// for error message pair in case validation failed 
		$this->addDataElement( CITY_MUNICIPALITY_CODE, "is_not_empty", "[VALUES]");
		$this->addDataElement( CITY_MUNICIPALITY_DESC, "is_not_empty", "[VALUES]");
		$this->addDataElement( CITY_MUNICIPALITY_DATE_REGISTERED, "is_not_empty", "[VALUES]");
		$this->addDataElement( CITY_MUNICIPALITY_DATE_UPDATED, "is_not_empty", "[VALUES]");
		$this->addDataElement( UPDATED_BY, "is_not_empty", "[VALUES]");
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
								
				
				$ret = ebpls_insert_data( $this->m_dbLink, EBPLS_LGU_TABLE, $strValues );
				
				if ( $ret < 0 ) {
				
					//$this->debug( "CREATE OCCUPNACY TYPE FAILED" );
					
					//$this->setError( $ret, get_db_error() );
					return $ret;
					
				} else {
										
					//$this->debug( "CREATE OCCUPNACY TYPE SUCCESSFULL" );
					$this->data_elems[ CITY_MUNICIPALITY_CODE ] = $ret;
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
	function view( $lgu_code ) {
							
		$strValues[$key] = "*";
		
		$strWhere[CITY_MUNICIPALITY_CODE] = $lgu_code;			
		
		$result = ebpls_select_data( $this->m_dbLink, EBPLS_LGU_TABLE, $strValues, $strWhere, NULL, $strOrderBy, "DESC", NULL );

		if ( is_array($result) ) {
		
			$this->data_elems = $result[0];
			return $result[0][CITY_MUNICIPALITY_CODE];
			
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
	function update( $lgu_code ) {

		$arrData = $this->getData();
		foreach( $arrData as $key=>$value){
		
			if ( $arrData[$key] != NULL ) {
			  
				$strValues[$key] = $value;
				
			}
		
		}
		
		if ( ( $error_num = $this->validateData(true) ) > 0 ) {
		
			
			$strWhere[CITY_MUNICIPALITY_CODE] = $lgu_code;
	
			$ret = ebpls_update_data( $this->m_dbLink, EBPLS_LGU_TABLE, $strValues, $strWhere );
			
			
			if ( $ret < 0 ) {
			
				
				
				return $ret;
				
			} else {
									
				return $ret;
				
			}					
			
		} else {
			
			return -1;
			
		}
	
	}
	
	function delete( $lgu_code ) {
		
		$strWhere[CITY_MUNICIPALITY_CODE] = $lgu_code;
		$strValues[] = "count(*) as cnt";

		// check if owner have existing transactions
			
		$result = ebpls_delete_data ( $this->m_dbLink, EBPLS_LGU_TABLE, $strWhere );
		
		if ( $result < 0 )  {
			
			$this->setError( $result, get_db_error() );
			
		}
		
		return $result;		
	
	}
	
	function search( $lgu_code = NULL, $lgu_desc = NULL ) {
		
		if ( $lgu_code != NULL and $lgu_desc == NULL) {
			$strWhere[CITY_MUNICIPALITY_CODE] = $lgu_code;
		}
		
		if ( $lgu_desc != NULL and $lgu_code == NULL) {
			$strWhere[CITY_MUNICIPALITY_DESC] = $lgu_desc;
		}

		if ( $lgu_desc != NULL and $lgu_code != NULL) {
                        $strWhere[UPPER] = $lgu_desc;
			$strWhere[CITY_MUNICIPALITY_CODE] = $lgu_code;
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
		
		$result = ebpls_select_data( $this->m_dbLink, EBPLS_LGU_TABLE, $strValues, $strWhere, NULL, NULL, NULL, NULL);	
		
		$rowcount = ebpls_select_data( $this->m_dbLink, EBPLS_LGU_TABLE, $strValues1, $strWhere, NULL, NULL, NULL, NULL);	
		
		$fetchrecord = ebpls_fetch_data( $this->m_dbLink, $result) ;
		
		$fetchcount = @mysql_fetch_row($rowcount);
		
		$this->out = $fetchrecord; 
		
		$this->rcount = $fetchcount; 
		
			
	}
	

	function pagesearch($page = 1, $maxrec = 1000000000, $orderkey = CITY_MUNICIPALITY_CODE, $is_desc = true ) {
	
		// select all columns
		$strValues1[] = "*";
		$strValues2[] = "count(*)";
		
		if ( $orderkey != NULL ) {
				
			$strOrder[$orderkey] = $orderkey;
			
		} else {
			
			$strOrder = $orderkey;
			
		}
		//echo $is_desc."Robert";
		$result = ebpls_select_data( $this->m_dbLink, EBPLS_LGU_TABLE, $strValues1, $strWhere, NULL, $strOrder, $is_desc, $maxrec, $page  );	
		
		$rowcount = ebpls_select_data( $this->m_dbLink, EBPLS_LGU_TABLE, $strValues2, $strWhere, NULL, $strOrder, $is_desc, NULL, NULL );	
		//$sqlCount = $rowcount["count_sql"];
		$this->out = $result;
		$this->rcount = $rowcount;
		//echo $rowcount."VooDoo";
		
		
	}

	function searchcomp1($lgu_code, $lgu_desc, $province) {

		$iflguexist = @mysql_query("select * from ebpls_city_municipality where (city_municipality_code = '$lgu_code' or city_municipality_desc = '$lgu_desc')");
		//$iflguexist = @mysql_query("select * from ebpls_city_municipality where upper = '$province' and (city_municipality_code = '$lgu_code' or city_municipality_desc = '$lgu_desc')");
		$ifexist = @mysql_num_rows($iflguexist);
		$this->rcount = $ifexist;
        }
	
	function searchcomp2($bbo, $lgu_code, $lgu_desc, $province) {

                $iflguexist = @mysql_query("select * from ebpls_city_municipality where city_municipality_code <> '$bbo' and (city_municipality_code = '$lgu_code' or city_municipality_desc = '$lgu_desc')");
				//$iflguexist = @mysql_query("select * from ebpls_city_municipality where upper = '$province' and city_municipality_code <> '$bbo' and (city_municipality_code = '$lgu_code' or city_municipality_desc = '$lgu_desc')");
                $ifexist = @mysql_num_rows($iflguexist);
                $this->rcount = $ifexist;
        }
}





?>
