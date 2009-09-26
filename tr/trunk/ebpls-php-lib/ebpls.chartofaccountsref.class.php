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
define(EBPLS_COT_TABLE,"ebpls_chart_of_accts");

define(EBPLS_ACCT_CODE,"acct_code");
define(EBPLS_ACCT_DESC,"acct_desc");
define(EBPLS_ACCT_NATURE,"acct_nature");
define(EBPLS_ACCT_NO,"acct_no");
define(EBPLS_DEPT_CODE,"dept_code");
define(EBPLS_EXTRA_CODE,"extra_code");
define(EBPLS_ACCT_CODE_DATE_REGISTERED,"acct_code_date_registered");
define(EBPLS_ACCT_CODE_UPDATED,"acct_code_updated");
define(EBPLS_UPDATED_BY,"updated_by");
define(EBPLS_SYSTEM_DATA,"system_data");

class EBPLChartOfAccountsSysRef extends DataEncapsulator {

	

	/**
	 * Instatiation method, set $bDebug to true to print debug messages otherwise set to false.
	 * $dbLink is a valid database connection.
	 *
	 */
	function EBPLChartOfAccountsSysRef( $dbLink, $bDebug = false ) {
	
		$this->m_dbLink = $dbLink;
		$this->setDebugMode( $bDebug );		
		
		$this->addDataElement( EBPLS_ACCT_CODE, "is_not_empty", "[VALUE]" );
		$this->addDataElement( EBPLS_ACCT_DESC, "is_not_empty", "[VALUE]"  );
		$this->addDataElement( EBPLS_ACCT_NATURE, "is_not_empty", "[VALUE]" );
		$this->addDataElement( EBPLS_ACCT_NO, "is_not_empty", "[VALUE]" );
		$this->addDataElement( EBPLS_DEPT_CODE, "is_not_empty", "[VALUE]" );
		$this->addDataElement( EBPLS_EXTRA_CODE, "is_not_empty", "[VALUE]" );		
		$this->addDataElement( EBPLS_ACCT_CODE_DATE_REGISTERED, "is_valid_date", "[VALUE]", true );
		$this->addDataElement( EBPLS_ACCT_CODE_UPDATED, "is_valid_date", "[VALUE]", true );
		$this->addDataElement( EBPLS_UPDATED_BY, "is_not_empty", "[VALUE]", true );
		$this->addDataElement( EBPLS_SYSTEM_DATA, NULL, NULL );

	}


	function add( $admin ) {

		if ( $this->m_dbLink ) {			

			$this->data_elems[EBPLS_ACCT_CODE_DATE_REGISTERED] = date("Y-m-d H:i:s");
			$this->data_elems[EBPLS_ACCT_CODE_UPDATED] = date("Y-m-d H:i:s");
			$this->data_elems[EBPLS_UPDATED_BY] = $admin;
			
			if ( ( $error_num = $this->validateData() ) > 0 ) {
			
				$strValues = $this->getData();

				$ret = ebpls_insert_data( $this->m_dbLink, EBPLS_COT_TABLE, $strValues );

				if ( $ret < 0 ) {

					$this->debug( "CREATE TAX FEE SYSREF FAILED [error:$ret,msg=" . get_db_error() . "]" );

					$this->setError( $ret, get_db_error() );

					return $ret;

				} else {

					$this->debug( "CREATE TAX FEE SYSREF SUCCESSFULL [$ret]" );
					return $ret;

				}


			} else {

				$this->debug( "CREATE TAX FEE SYSREF FAILED [error:$ret,msg=" . get_db_error() . "]" );
				return $error_num;

			}

		} else {

			$this->debug( "CREATE TAX FEE SYSREF FAILED INVALID DB LINK $this->m_dbLink" );
			$this->setError( $ret, "Invalid Db link $this->m_dbLink" );
			return -1;

		}


	}

	function delete( $code  = NULL ){
		
		
		// check if fee code is being used, if currently being used then terminate delete command
		
		if ( $code ) {
		
			$strWhere[EBPLS_ACCT_CODE] = $code;
			
		} else {
		
			$strWhere[EBPLS_ACCT_CODE] = $this->data_elems[ EBPLS_ACCT_CODE ];	
		
		}

		$result = ebpls_delete_data ( $this->m_dbLink, EBPLS_COT_TABLE, $strWhere );

		if ( $result < 0 )  {

			$this->setError( $result, get_db_error() );

		}

		return $result;

	}


	function update( $admin ) {
		
		$this->data_elems[EBPLS_ACCT_CODE_UPDATED] = date("Y-m-d H:i:s");
		$this->data_elems[EBPLS_UPDATED_BY] = $admin;	
		
		$arrData = $this->getData();

		foreach( $arrData as $key=>$value){

			if ( $arrData[$key] != NULL ) {

				$strValues[$key] = $value;

			}

		}
		
		$strWhere[EBPLS_ACCT_CODE] = $this->data_elems[EBPLS_ACCT_CODE];
		

		$ret = ebpls_update_data( $this->m_dbLink, EBPLS_COT_TABLE, $strValues, $strWhere );

		if ( $ret < 0 ) {

			$this->debug( "UPDATE EBPLS_COT_TABLE FAILED [error:$ret,msg=" . get_db_error() . "]" );

			$this->setError( $ret, get_db_error() );

			return $ret;

		} else {

			$this->debug( "UPDATE EBPLS_COT_TABLE SUCCESSFULL [$ret]" );
			return $ret;

		}


	}
	
	function getCashAccountCode(){
		
		$result = $this->select( NULL, "DEBIT", NULL, NULL, NULL, "CASH" );

		if ( is_array($result) ) {

			return $result["result"][0]->getData(EBPLS_ACCT_CODE);

		} else {
			
			$this->debug("getCashAccountCode() return error!");
			$this->setError(-1,"getCashAccountCode()");
			return -1;

		}
	
	}

	function getExemptionAccountCode(){
		
		$result = $this->select( NULL, "DEBIT", NULL, NULL, NULL, "EXEMPTED" );

		if ( is_array($result) ) {

			return $result["result"][0]->getData(EBPLS_ACCT_CODE);

		} else {
			
			$this->debug("getCashAccountCode() return error!");
			$this->setError(-1,"getCashAccountCode()");
			return -1;

		}
	
	}
	
	function getCheckAccountCode(){
		
		$result = $this->select( NULL, "DEBIT", NULL, NULL, NULL, "CHECK" );

		if ( is_array($result) ) {

			return $result["result"][0]->getData(EBPLS_ACCT_CODE);

		} else {
			
			$this->debug("getCashAccountCode() return error!");
			$this->setError(-1,"getCashAccountCode()");
			return -1;

		}
	
	}
	
	function select( $code = NULL, $acct_nature = NULL, $acct_no = NULL, $dept_code = NULL, $extra_code = NULL, $desc = NULL, $page = 1, $maxrec = 10, $orderkey = EBPLS_ACCT_CODE, $is_desc = true ){

		if ( $code != NULL ) {
		
			$strWhere[EBPLS_ACCT_CODE] = $code;
		
		}
		
		if ( $acct_nature != NULL ) {
		
			$strWhere[EBPLS_ACCT_NATURE] = array("like","$acct_nature%");
		
		}
		
		if ( $acct_no != NULL ) {
		
			$strWhere[EBPLS_ACCT_NO] = array("like","$acct_no%");
		
		}
		
		if ( $extra_code != NULL ) {
		
			$strWhere[EBPLS_EXTRA_CODE] = array("like","$extra_code%");
		
		}
		
		if ( $desc != NULL ) {
		
			$strWhere[EBPLS_ACCT_DESC] = array("like","$desc%");
		
		}
		
		// select all columns
		$strValues[] = "*";

		if ( $orderkey != NULL ) {

			$strOrder[ $orderkey ] = $orderkey;

		} else {

			$strOrder[ $orderkey ] = EBPLS_ACCT_CODE;

		}

		$result = ebpls_select_data_bypage( $this->m_dbLink, EBPLS_COT_TABLE, $strValues, $strWhere, NULL, $strOrder, $is_desc?"DESC":"ASC", $page, $maxrec );

		if ( !is_array($result) && $result < 0 ) {

			$this->setError ( $result, get_db_error());
			return $result;

		} else {

			// transform result to EBPLTaxFeeSysRef object
			for ( $i = 0 ; $i < count($result["result"]); $i++ ) {

				$records[$i] = new EBPLChartOfAccountsSysRef($this->m_dbLink, false );				
				$records[$i]->setData( NULL, $result["result"][$i] );

			}

			$result["result"] = $records;

			return $result;

		}

	}


}


?>