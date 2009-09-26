<?
/************************************************************************************

Module : ebpls.sysref.class.php

Dependencies :
	ebpls.database.funcs.php
	ebpls.dataencapsulator.class.php
	ebpls.global.const.php
	ebpls.global.funcs.php

Description :
	- encapsulates all system reference tables

Created By : Stephen Lou B. Banal
Email : sbanal@yahoo.com
Date Created : 3/9/2004 9:04PM

Last Updates :
	[ DATE / TIME ] - [ PROGRAMMER ]
	- < DETAILS 1 >
	- < DETAILS 2 >
	- < DETAILS 3 >


Notes :


************************************************************************************/

require_once("ebpls-php-lib/ebpls.dataencapsulator.class.php");
require_once("ebpls-php-lib/ebpls.global.funcs.php");
require_once("ebpls-php-lib/ebpls.database.funcs.php");

// define system reference table keys
define(EBPLS_TAX_FEE_TABLE,"ebpls_tax_fee_table");

define(EBPLS_PAR_PR_ID, "par_id");
define(EBPLS_PAR_PERMIT_TYPE, "permit_type");
define(EBPLS_PAR_DESC, "par_desc");
define(EBPLS_PAR_REQUIREMENT_CODE, "requirement_code");
define(EBPLS_PAR_PAR_TS_SUBMITTED,"par_ts_submitted");
define(EBPLS_PAR_PAR_SUBMITTED_BY,"par_submitted_by");
define(EBPLS_PAR_TRANS_TYPE,"par_trans_type");

define(EBPLS_PFR_PR_ID, "pfr_id");
define(EBPLS_PFR_PERMIT_TYPE, "permit_type");
define(EBPLS_PFR_DESC, "pfr_desc");
define(EBPLS_PFR_TAX_FEE_CODE, "tax_fee_code");
define(EBPLS_PFR_PFR_TS_SUBMITTED,"pfr_ts_submitted");
define(EBPLS_PFR_PFR_SUBMITTED_BY,"pfr_submitted_by");
define(EBPLS_PFR_TRANS_TYPE,"pfr_trans_type");

define(EBPLS_PTR_PR_ID, "ptr_id");
define(EBPLS_PTR_PERMIT_TYPE, "permit_type");
define(EBPLS_PTR_DESC, "ptr_desc");
define(EBPLS_PTR_TAX_FEE_CODE, "tax_fee_code");
//define(EBPLS_PTR_BUSINESS_NATURE_CODE, "business_nature_code");
define(EBPLS_PTR_PTR_TS_SUBMITTED,"ptr_ts_submitted");
define(EBPLS_PTR_PTR_SUBMITTED_BY,"ptr_submitted_by");
define(EBPLS_PTR_TRANS_TYPE,"ptr_trans_type");

define(EBPLS_PDR_APP_TABLE,"ebpls_permit_app_requirements");
define(EBPLS_PDR_FEE_TABLE,"ebpls_permit_fee_requirements");
define(EBPLS_PDR_TAX_TABLE,"ebpls_permit_tax_requirements");

define(EBPLS_PDR_PR_TYPE_FEE,"FEE");
define(EBPLS_PDR_PR_TYPE_TAX,"TAX");
define(EBPLS_PDR_PR_TYPE_APP,"APP");

class EBPLSPermitDefaultRequirements extends DataEncapsulator {

	var $m_strTable;
	var $m_strPrimaryKey;
	var $m_strTimeStamp;
	var $m_strAdminKey;
	var $m_strType;

	/**
	 * Instatiation method, set $bDebug to true to print debug messages otherwise set to false.
	 * $dbLink is a valid database connection.
	 *
	 */
	function EBPLSPermitDefaultRequirements( $dbLink, $key, $bDebug = false ) {

		$this->m_dbLink = $dbLink;
		$this->setDebugMode( $bDebug );

		$this->m_strType = $key;

		if ( EBPLS_PDR_PR_TYPE_APP == $key ) {

			$this->m_strTable = EBPLS_PDR_APP_TABLE;
			$this->m_strPrimaryKey = EBPLS_PAR_PR_ID;
			$this->m_strTimeStamp = EBPLS_PAR_PAR_TS_SUBMITTED;
			$this->m_strAdminKey = EBPLS_PAR_PAR_SUBMITTED_BY;

			$this->addDataElement( EBPLS_PAR_PR_ID, "is_valid_number", "[VALUE]", true );
			$this->addDataElement( EBPLS_PAR_PERMIT_TYPE, "is_not_empty", "[VALUE]"  );
			$this->addDataElement( EBPLS_PAR_DESC, "is_not_empty", "[VALUE]" );
			$this->addDataElement( EBPLS_PAR_REQUIREMENT_CODE, "is_not_empty", "[VALUE]"  );
			$this->addDataElement( EBPLS_PAR_PAR_TS_SUBMITTED, "is_valid_data", "[VALUE]" , true );
			$this->addDataElement( EBPLS_PAR_PAR_SUBMITTED_BY, "is_not_empty", "[VALUE]" );
			$this->addDataElement( EBPLS_PAR_TRANS_TYPE, "is_not_empty", "[VALUE]" );

		} else if ( EBPLS_PDR_PR_TYPE_TAX == $key ) {

			$this->m_strTable = EBPLS_PDR_TAX_TABLE;
			$this->m_strPrimaryKey = EBPLS_PTR_PR_ID;
			$this->m_strTimeStamp = EBPLS_PTR_PTR_TS_SUBMITTED;
			$this->m_strAdminKey = EBPLS_PTR_PTR_SUBMITTED_BY;

			$this->addDataElement( EBPLS_PTR_PR_ID, "is_valid_number", "[VALUE]", true );
			$this->addDataElement( EBPLS_PTR_PERMIT_TYPE, "is_not_empty", "[VALUE]"  );
			$this->addDataElement( EBPLS_PTR_DESC, "is_not_empty", "[VALUE]" );
			$this->addDataElement( EBPLS_PTR_TAX_FEE_CODE, "is_not_empty", "[VALUE]"  );
			//$this->addDataElement( EBPLS_PTR_BUSINESS_NATURE_CODE, "is_not_empty", "[VALUE]"  );
			//$this->addDataElement( EBPLS_PTR_BUSINESS_NATURE_CODE, NULL, NULL );
			$this->addDataElement( EBPLS_PTR_PTR_TS_SUBMITTED, "is_valid_data", "[VALUE]" , true );
			$this->addDataElement( EBPLS_PTR_PTR_SUBMITTED_BY, "is_not_empty", "[VALUE]" );
			$this->addDataElement( EBPLS_PTR_TRANS_TYPE, "is_not_empty", "[VALUE]" );

		} else if ( EBPLS_PDR_PR_TYPE_FEE == $key ) {

			$this->m_strTable = EBPLS_PDR_FEE_TABLE;
			$this->m_strPrimaryKey = EBPLS_PFR_PR_ID;
			$this->m_strTimeStamp = EBPLS_PFR_PFR_TS_SUBMITTED;
			$this->m_strAdminKey = EBPLS_PFR_PFR_SUBMITTED_BY;

			$this->addDataElement( EBPLS_PFR_PR_ID, "is_valid_number", "[VALUE]", true );
			$this->addDataElement( EBPLS_PFR_PERMIT_TYPE, "is_not_empty", "[VALUE]"  );
			$this->addDataElement( EBPLS_PFR_DESC, "is_not_empty", "[VALUE]" );
			$this->addDataElement( EBPLS_PFR_TAX_FEE_CODE, "is_not_empty", "[VALUE]"  );
			$this->addDataElement( EBPLS_PFR_PFR_TS_SUBMITTED, "is_valid_data", "[VALUE]" , true );
			$this->addDataElement( EBPLS_PFR_PFR_SUBMITTED_BY, "is_not_empty", "[VALUE]" );
			$this->addDataElement( EBPLS_PFR_TRANS_TYPE, "is_not_empty", "[VALUE]" );

		}

	}

	function add(  ){

		if ( $this->m_dbLink ) {

			$this->data_elems[$this->m_strTimeStamp] = date("Y-m-d H:i:s");

			if ( ( $error_num = $this->validateData() ) > 0 ) {

				$strValues = $this->getData();

				$ret = ebpls_insert_data( $this->m_dbLink, $this->m_strTable, $strValues );

				if ( $ret < 0 ) {

					$this->debug( "CREATE PDF SYSREF FAILED [error:$ret,msg=" . get_db_error() . "]" );

					$this->setError( $ret, get_db_error() );

					return $ret;

				} else {

					//$this->data_elems[EBPLS_PDR_PR_ID] = $ret;
					$this->debug( "CREATE PDF SYSREF SUCCESSFULL [$ret]" );
					return $ret;

				}


			} else {

				$this->debug( "CREATE PDF SYSREF FAILED [error:$ret,msg=" . get_db_error() . "]" );
				return $error_num;

			}

		} else {

			$this->debug( "CREATE PDF SYSREF FAILED INVALID DB LINK $this->m_dbLink" );
			$this->setError( $ret, "Invalid Db link $this->m_dbLink" );
			return -1;

		}


	}

	function delete( $id  = NULL ){


		// check if fee code is being used, if currently being used then terminate delete command
		if ( $id ) {

			$strWhere[$this->m_strPrimaryKey] = $id;

		} else {

			$strWhere[$this->m_strPrimaryKey] = $this->data_elems[ $this->m_strPrimaryKey ];

		}

		$result = ebpls_delete_data ( $this->m_dbLink, $this->m_strTable, $strWhere );

		if ( $result < 0 )  {

			$this->setError( $result, get_db_error() );

		}

		return $result;

	}


	function update( $code = NULL ) {

		$this->data_elems[$this->m_strTimeStamp] = date("Y-m-d H:i:s");
		$arrData = $this->getData();

		foreach( $arrData as $key=>$value){

			if ( $arrData[$key] != NULL ) {

				$strValues[$key] = $value;

			}

		}

		$this->debug( "ID : " . EBPLS_PAR_PR_ID . ", $this->m_strPrimaryKey = $code<BR>" );

		if ( $code ) {

			$strWhere[$this->m_strPrimaryKey] = $code;

		} else {

			$strWhere[$this->m_strPrimaryKey] = $this->data_elems[ $this->m_strPrimaryKey ];

		}

		$ret = ebpls_update_data( $this->m_dbLink, $this->m_strTable, $strValues, $strWhere );

		if ( $ret < 0 ) {

			$this->debug( "UPDATE PDF SYSREF [error:$ret,msg=" . get_db_error() . "]" );

			$this->setError( $ret, get_db_error() );

			return $ret;

		} else {

			$this->debug( "UPDATE PDF SYSREF SUCCESSFULL [$ret]" );
			return $ret;

		}

	}

	function select( $permit_type = NULL, $trans_type = NULL, $page = 1, $maxrec = 10, $orderkey = "", $is_desc = true ){		

		// either MOT,PED,BUS,OCC etc...
		if ( $permit_type != NULL ) {

			if ( EBPLS_PDR_PR_TYPE_APP == $this->m_strType ) {

				$strWhere[EBPLS_PAR_PERMIT_TYPE] = $permit_type;

				if ( $trans_type != NULL ) {
					$strWhere[EBPLS_PAR_TRANS_TYPE] = $trans_type;
				}
				
				if ( $permit_type != NULL ) {

					$strWhere[ EBPLS_PAR_PERMIT_TYPE ] = $permit_type;
		
				}							

			} else if ( EBPLS_PDR_PR_TYPE_TAX == $this->m_strType ) {

				$strWhere[EBPLS_PTR_PERMIT_TYPE] = $permit_type;

				if ( $trans_type != NULL ) {
					$strWhere[EBPLS_PTR_TRANS_TYPE] = $trans_type;
				}
				
				if ( $permit_type != NULL ) {

					$strWhere[ EBPLS_PTR_PERMIT_TYPE ] = $permit_type;
		
				}								

			} else if ( EBPLS_PDR_PR_TYPE_FEE == $this->m_strType ) {

				$strWhere[EBPLS_PFR_PERMIT_TYPE] = $permit_type;

				if ( $trans_type != NULL ) {
					$strWhere[EBPLS_PFR_TRANS_TYPE] = $trans_type;
				}
				
				if ( $permit_type != NULL ) {

					$strWhere[ EBPLS_PFR_PERMIT_TYPE ] = $permit_type;
		
				}

			}

		}
		
		if ( $orderkey != NULL ) {

			$strOrder[ $orderkey ] = $orderkey;

		} else {

			$strOrder[ $this->m_strPrimaryKey ] = $this->m_strPrimaryKey;

		}

		$strValues[] = "*";

		$result = ebpls_select_data_bypage( $this->m_dbLink, $this->m_strTable, $strValues, $strWhere, NULL, $strOrder, $is_desc?"DESC":"ASC", $page, $maxrec );

		if ( !is_array($result) && $result < 0 ) {

			$this->setError ( $result, get_db_error());
			return $result;

		} else {

			// transform result to EBPLSPermitDefaultRequirements object
			for ( $i = 0 ; $i < count($result["result"]); $i++ ) {

				$records[$i] = new EBPLSPermitDefaultRequirements($this->m_dbLink, false );
				//print_r($result["result"][$i]);
				$records[$i]->setData( NULL, $result["result"][$i] );

			}

			$result["result"] = $records;

			return $result;

		}

	}

	
	function existOnDefaultRequirements( $tax_fee_code  ){
		
		if ( EBPLS_PDR_PR_TYPE_TAX == $this->m_strType ) {
			$strWhere[EBPLS_PTR_TAX_FEE_CODE] = $tax_fee_code;
		} else if ( EBPLS_PDR_PR_TYPE_FEE == $this->m_strType ) {
			$strWhere[EBPLS_PFR_TAX_FEE_CODE] = $tax_fee_code;
		}else{
			
			return false;
		}
		
		$strValues[] = "*";
		$result = ebpls_select_data( $this->m_dbLink, $this->m_strTable, $strValues, $strWhere );

		if ( is_array($result) && count($result) > 0 ) {
			
			return true;

		} else {
			
			return false;

		}
		
		
	}
	
}


?>