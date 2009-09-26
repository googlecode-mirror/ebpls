<?
require_once("class/eBPLS.dataencapsulator.class.php");
require_once("lib/eBPLS.dbfuncs.php");

// keys for getData method
define(EBPLS_SURCHARGE_TABLE,"ebpls_other_penalty");

// Industry Sector Data Elements Constants
define(ID,"id");
define(RENEWALDATE,"renewaldate");
define(RATEOFPENALTY,"rateofpenalty");
define(RATEOFINTEREST,"rateofinterest");
define(INDICATOR,"indicator");
define(STATUS,"status");
define(INTYPE,"intype");
define(FEEONLY,"feeonly");
define(LATE_FILING_FEE,"late_filing_fee");
define(F_STATUS,"f_status");
define(BACKTAX,"backtax");
define(PERMIT_TYPE,"permit_type");	
define(UPDATED_BY,"updated_by");
define(DATE_UPDATED,"date_updated");

class EBPLSPenalty extends DataEncapsulator {
	
	var $m_dbLink,
		$rcount,
		$out;	
	
	function EBPLSPenalty( $dbLink, $bDebug = false ) {
		$this->m_dbLink = $dbLink;
		$this->setDebugMode( $bDebug );		
		
		// add data elements of class with their respective validation functions an params, used default value for 4th parameter
		// for error message pair in case validation failed 
		$this->addDataElement( ID, "is_not_empty", "[VALUES]");
		$this->addDataElement( RENEWALDATE, "is_not_empty", "[VALUES]");
		$this->addDataElement( RATEOFPENALTY, "is_not_empty", "[VALUES]");
		$this->addDataElement( RATEOFINTEREST, "is_not_empty", "[VALUES]");
		$this->addDataElement( INDICATOR, "is_not_empty", "[VALUES]");
		$this->addDataElement( STATUS, "is_not_empty", "[VALUES]");
		$this->addDataElement( INTYPE, "is_not_empty", "[VALUES]");
		$this->addDataElement( FEEONLY, "is_not_empty", "[VALUES]");
		$this->addDataElement( LATE_FILING_FEE, "is_not_empty", "[VALUES]");
		$this->addDataElement( F_STATUS, "is_not_empty", "[VALUES]");
		$this->addDataElement( BACKTAX, "is_not_empty", "[VALUES]");
		$this->addDataElement( PERMIT_TYPE, "is_not_empty", "[VALUES]");
		$this->addDataElement( UPDATED_BY, "is_not_empty", "[VALUES]");
		$this->addDataElement( DATE_UPDATED, "is_not_empty", "[VALUES]");
		
	}
	
	function add(){
			
		if ( $this->m_dbLink ) {
			if ( ( $error_num = $this->validateData() ) > 0 ) {
				
		
				$strValues = $this->data_elems;
								
				
				$ret = ebpls_insert_data( $this->m_dbLink, EBPLS_SURCHARGE_TABLE, $strValues );
				
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
	 *
	 */
	function update( $id ) {

		$arrData = $this->getData();
		foreach( $arrData as $key=>$value){
		
			if ( $arrData[$key] != NULL ) {
			  
				$strValues[$key] = $value;
				
			}
		
		}
		
		if ( ( $error_num = $this->validateData(true) ) > 0 ) {
		
			
			$strWhere[ID] = $id;
	
			$ret = ebpls_update_data( $this->m_dbLink, EBPLS_SURCHARGE_TABLE, $strValues, $strWhere );
			
			
			if ( $ret < 0 ) {
			
				
				
				return $ret;
				
			} else {
									
				return $ret;
				
			}					
			
		} else {
			
			return -1;
			
		}
	
	}
	
       	function searchpen($permit) {
		
		$ifexist = @mysql_query("select * from ebpls_other_penalty where permit_type = '$permit'");
		$ifexist = @mysql_fetch_assoc($ifexist);
		$this->out = $ifexist;

        }

	
}





?>
