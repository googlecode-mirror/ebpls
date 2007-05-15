<?
require_once("class/eBPLS.dataencapsulator.class.php");
require_once("lib/eBPLS.dbfuncs.php");

// keys for getData method
define(EBPLS_TFO_TABLE,"ebpls_buss_tfo");

// Industry Sector Data Elements Constants
define(TFOID,"tfoid");
define(TFODESC,"tfodesc");
define(TFOSTATUS,"tfostatus");
define(TFOINDICATOR,"tfoindicator");
define(TAXFEETYPE,"taxfeetype");
define(DATECREATED,"datecreated");
define(DEFAMT,"defamt");
define(OR_PRINT,"or_print");
define(COUNTER,"counter");

class EBPLSTFO extends DataEncapsulator {
	
	var $m_dbLink,
		$rcount,
		$out;	
	
	function EBPLSTFO( $dbLink, $bDebug = false ) {
		$this->m_dbLink = $dbLink;
		$this->setDebugMode( $bDebug );		
		
		// add data elements of class with their respective validation functions an params, used default value for 4th parameter
		// for error message pair in case validation failed 
		$this->addDataElement( TFOID, "is_not_empty", "[VALUES]");
		$this->addDataElement( TFODESC, "is_not_empty", "[VALUES]");
		$this->addDataElement( TFOSTATUS, "is_not_empty", "[VALUES]");
		$this->addDataElement( TFOINDICATOR, "is_not_empty", "[VALUES]");
		$this->addDataElement( TAXFEETYPE, "is_not_empty", "[VALUES]");
		$this->addDataElement( DATECREATED, "is_not_empty", "[VALUES]");
		$this->addDataElement( DEFAMT, "is_not_empty", "[VALUES]");
		$this->addDataElement( OR_PRINT, "is_not_empty", "[VALUES]");
		$this->addDataElement( COUNTER, "is_not_empty", "[VALUES]");
		
	}
	
	/**
	 * Adds new Industry Sector to ebls_INDUSTRY_SECTOR table
	 *
	 */
	function add(){
			
		if ( $this->m_dbLink ) {
			if ( ( $error_num = $this->validateData() ) > 0 ) {
				
		
				$strValues = $this->data_elems;
								
				
				$ret = ebpls_insert_data( $this->m_dbLink, EBPLS_TFO_TABLE, $strValues );
				
				if ( $ret < 0 ) {
				
					//$this->debug( "CREATE OCCUPNACY TYPE FAILED" );
					
					//$this->setError( $ret, get_db_error() );
					return $ret;
					
				} else {
										
					//$this->debug( "CREATE OCCUPNACY TYPE SUCCESSFULL" );
					$this->data_elems[ TFOID ] = $ret;
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
	function view( $tfoid ) {
							
		$strValues[$key] = "*";
		
		$strWhere[TFOID] = $tfoid;			
		
		$result = ebpls_select_data( $this->m_dbLink, EBPLS_TFO_TABLE, $strValues, $strWhere, NULL, $strOrderBy, "DESC", NULL );

		if ( is_array($result) ) {
		
			$this->data_elems = $result[0];
			return $result[0][TFOID];
			
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
	function update( $tfoid ) {

		$arrData = $this->getData();
		foreach( $arrData as $key=>$value){
		
			if ( $arrData[$key] != NULL ) {
			  
				$strValues[$key] = $value;
				
			}
		
		}
		if ( ( $error_num = $this->validateData(true) ) > 0 ) {
		
			
			$strWhere[TFOID] = $tfoid;
	
			$ret = ebpls_update_data( $this->m_dbLink, EBPLS_TFO_TABLE, $strValues, $strWhere );
			
			
			if ( $ret < 0 ) {
			
				
				
				return $ret;
				
			} else {
									
				return $ret;
				
			}					
			
		} else {
			
			return -1;
			
		}
	
	}
	
	function delete( $tfoid ) {
		
		$strWhere[TFOID] = $tfoid;
		$strValues[] = "count(*) as cnt";

		// check if owner have existing transactions
			
		$result = ebpls_delete_data ( $this->m_dbLink, EBPLS_TFO_TABLE, $strWhere );
		
		if ( $result < 0 )  {
			
			$this->setError( $result, get_db_error() );
			
		}
		
		return $result;		
	
	}
	
	function checksearch( $tfoid ) {
		
		$result = @mysql_query("select * from ebpls_buss_taxfeeother where tfo_id = '$tfoid'");
		$get = @mysql_num_rows($result);
		$this->rcount = $get;
		
	}
	
	function search( $tfoid = NULL, $tfodesc = NULL ) {
		
		if ( $tfoid != NULL ) {
			$strWhere[TFOID] = $tfoid;
		}
		
		if ( $reqdesc != NULL ) {
			$strWhere[TFODESC] = $reqdesc;
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
		
		$result = ebpls_select_data( $this->m_dbLink, EBPLS_TFO_TABLE, $strValues, $strWhere, NULL, NULL, NULL, NULL);	
		
		$fetchrecord = ebpls_fetch_data( $this->m_dbLink, $result) ;
		
		$this->out = $fetchrecord; 
		
			
	}
	

	function searchcomp( $tfoid = NULL, $tfodesc = NULL ) {
		
		
		$rowcount = @mysql_query("Select count(*) from ebpls_buss_tfo where tfodesc = '$tfodesc' and tfoid <> '$tfoid'");
		
		
		$fetchcount = @mysql_fetch_row($rowcount);
		
		
		$this->rcount = $fetchcount; 
		
			
	}
	

	function pagesearch($page = 1, $maxrec = 1000000000, $orderkey = TFOID, $is_desc = true ) {
	
		// select all columns
		$strValues1[] = "*";
		$strValues2[] = "count(*)";
		
		if ( $orderkey != NULL ) {
				
			$strOrder[$orderkey] = $orderkey;
			
		} else {
			
			$strOrder = $orderkey;
			
		}
		//echo $is_desc."Robert";
		$result = ebpls_select_data( $this->m_dbLink, EBPLS_TFO_TABLE, $strValues1, $strWhere, NULL, $strOrder, $is_desc, $maxrec, $page  );	
		
		$rowcount = ebpls_select_data( $this->m_dbLink, EBPLS_TFO_TABLE, $strValues2, $strWhere, NULL, $strOrder, $is_desc, NULL, NULL );	
		//$sqlCount = $rowcount["count_sql"];
		$this->out = $result;
		$this->rcount = $rowcount;
		//echo $rowcount."VooDoo";
		
		
	}

        
}





?>
