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
require_once("ebpls-php-lib/ebpls.taxfeeformula.class.php");
require_once("ebpls-php-lib/ebpls.database.funcs.php");

// define system reference table keys
define(EBPLS_TAX_FEE_TABLE,"ebpls_tax_fee_table");

define(EBPLS_TAX_FEE_CODE, "tax_fee_code");
define(EBPLS_TAX_FEE_DESC, "tax_fee_desc");
define(EBPLS_TAX_ACCOUNT_CODE,"tax_account_code");
define(EBPLS_SURCHARGE_TAX_FEE_CODE,"surcharge_tax_fee_code");
define(EBPLS_INTEREST_TAX_FEE_CODE,"interest_tax_fee_code");
define(EBPLS_IGNORE_DECIMAL,"ignore_decimal");
define(EBPLS_TAX_FEE_DATE_REGISTERED,"tax_fee_date_registered");
define(EBPLS_TAX_FEE_DATE_UPDATED,"tax_fee_date_updated");
define(EBPLS_TAX_FEE_TYPE,"tax_fee_type");
define(EBPLS_TAX_FORMULA,"tax_formula");
define(EBPLS_TAX_FORMULA_ID,"tax_formula_id");
define(EBPLS_TAX_FORMULA_TYPE,"tax_formula_type");
define(EBPLS_TAX_SYSTEMDATA,"system_data");

define(EBPLS_PDR_FEE_TABLE_REF,"ebpls_permit_fee_requirements");
define(EBPLS_PDR_TAX_TABLE_REF,"ebpls_permit_tax_requirements");
define(EBPLS_PAYABLES_REF,"ebpls_transaction_payables");

class EBPLTaxFeeSysRef extends DataEncapsulator {



	/**
	 * Instatiation method, set $bDebug to true to print debug messages otherwise set to false.
	 * $dbLink is a valid database connection.
	 *
	 */
	function EBPLTaxFeeSysRef( $dbLink, $bDebug = false ) {

		$this->m_dbLink = $dbLink;
		$this->setDebugMode( $bDebug );

		$this->addDataElement( EBPLS_TAX_FEE_CODE, "is_not_empty", "[VALUE]" );
		$this->addDataElement( EBPLS_TAX_FEE_DESC, NULL, "[VALUE]"  );
		$this->addDataElement( EBPLS_TAX_FORMULA_ID, "is_valid_number", "[VALUE]" );
		$this->addDataElement( EBPLS_TAX_FORMULA_TYPE, "is_not_empty", "[VALUE]" );
		$this->addDataElement( EBPLS_TAX_ACCOUNT_CODE, "is_not_empty", "[VALUE]" );
		$this->addDataElement( EBPLS_TAX_FEE_TYPE, "is_not_empty", "[VALUE]" );
		$this->addDataElement( EBPLS_SURCHARGE_TAX_FEE_CODE, "is_not_empty", "[VALUE]" );
		$this->addDataElement( EBPLS_INTEREST_TAX_FEE_CODE, "is_not_empty", "[VALUE]" );
		$this->addDataElement( EBPLS_TAX_FEE_DATE_REGISTERED, "is_valid_date", "[VALUE]", true );
		$this->addDataElement( EBPLS_TAX_FEE_DATE_UPDATED, "is_valid_date", "[VALUE]", true );
		$this->addDataElement( EBPLS_TAX_SYSTEMDATA, NULL, NULL);

	}


	function add( ) {

		if ( $this->m_dbLink ) {

			$this->data_elems[EBPLS_TAX_FEE_DATE_REGISTERED] = date("Y-m-d H:i:s");
			$this->data_elems[EBPLS_TAX_FEE_DATE_UPDATED] = date("Y-m-d H:i:s");

			if ( ( $error_num = $this->validateData() ) > 0 ) {

				$strValues = $this->getData();

				$ret = ebpls_insert_data( $this->m_dbLink, EBPLS_TAX_FEE_TABLE, $strValues );

				if ( $ret < 0 ) {

					$this->debug( "CREATE TAX FEE SYSREF FAILED [error:$ret,msg=" . get_db_error() . "]" );

					$this->setError( $ret, get_db_error() );

					return $ret;

				} else {

					$this->debug( "CREATE TAX FEE SYSREF SUCCESSFULL [$ret]" );
					return 1;

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

		if ( $code ) {

			$strWhere[EBPLS_TAX_FEE_CODE] = $code;

		} else {

			$strWhere[EBPLS_TAX_FEE_CODE] = $this->data_elems[ EBPLS_TAX_FEE_CODE ];

		}


		// let the InnoDB foreign keys do the validation of usage - 8/30/2004 1:47AM - stephen
		// check if fee code is being used, if currently being used then terminate delete command
		$strColumns[EBPLS_TAX_FEE_CODE] = "*";
		$result1 = ebpls_select_data( $this->m_dbLink, EBPLS_PDR_FEE_TABLE_REF, $strColumns, $strWhere );
		$result2 = ebpls_select_data( $this->m_dbLink, EBPLS_PDR_TAX_TABLE_REF, $strColumns, $strWhere );
		$result3 = ebpls_select_data( $this->m_dbLink, EBPLS_PAYABLES_REF, $strColumns, $strWhere );

		if ( is_array($result1) || is_array($result2) || is_array($result3) ) {

			$this->setError ( -1, "Tax/Fee Code already in use, can't delete.");
			return -1;

		}

		$result = ebpls_delete_data ( $this->m_dbLink, EBPLS_TAX_FEE_TABLE, $strWhere );

		if ( $result < 0 )  {

			$this->setError( $result, get_db_error() );

		}

		return $result;

	}

	function isEditable( $code = NULL ) {

		$strWhere[EBPLS_TAX_FEE_CODE] = ($code)?$code:$this->data_elems[ EBPLS_TAX_FEE_CODE ];

		// check if fee code is being used, if currently being used then terminate delete command
		$strColumns[EBPLS_TAX_FEE_CODE] = "*";
		$result1 = ebpls_select_data( $this->m_dbLink, EBPLS_PDR_FEE_TABLE_REF, $strColumns, $strWhere );
		$result2 = ebpls_select_data( $this->m_dbLink, EBPLS_PDR_TAX_TABLE_REF, $strColumns, $strWhere );
		$result3 = ebpls_select_data( $this->m_dbLink, EBPLS_PAYABLES_REF, $strColumns, $strWhere );

		if ( ( is_array($result1) || is_array($result2) || is_array($result3) ) ) {

			return false;

		}

		return true;

	}

	function view( $code ) {

		$strColumns[] = "*";

		if ( $code ) {

			$strWhere[EBPLS_TAX_FEE_CODE] = $code;

		} else {

			$strWhere[EBPLS_TAX_FEE_CODE] = $this->data_elems[ EBPLS_TAX_FEE_CODE ];

		}

		$ret = ebpls_select_data( $this->m_dbLink, EBPLS_TAX_FEE_TABLE, $strColumns, $strWhere );

		if ( $ret < 0 ) {

			$this->debug( "VIEW EBPLS_TAX_FEE_TABLEFAILED [error:$ret,msg=" . get_db_error() . "]" );
			$this->setError( $ret, get_db_error() );

			return $ret;

		} else {

			$this->debug( "VIEW EBPLS_TAX_FEE_TABLE SUCCESSFULL [$ret]" );

			$clsFormula = new TaxFeeFormula( $this->m_dbLink, false );
			$clsFormula->view( $ret[0][EBPLS_TAX_FORMULA_ID] );
			
			$ret[0][EBPLS_TAX_FORMULA] = unserialize($clsFormula->getData(EBPLS_FORMULAS_FORMULA_CLASS));

			if ( $ret[0][EBPLS_TAX_FORMULA] ) {
				
				//print_r($ret[0][EBPLS_TAX_FORMULA]->getData());	
				$this->setData( NULL, $ret[0] );	
				return $ret;
				
			} else {
				
				$this->setError(-2,"Invalid formula on unserialize : " . $ret[0][EBPLS_TAX_FORMULA_ID] . "!");				
				$this->debug("Invalid formula on unserialize : " . $ret[0][EBPLS_TAX_FORMULA_ID] . "!");				
				return -2;	
				
			}

		}

	}

	function loadTaxFee( $tf_code ) {

		$clsTaxFee = new EBPLTaxFeeSysRef( $this->m_dbLink, false );
		$this->debug( "REF : " . get_class($clsTaxFee) );
		$retTaxFee = $clsTaxFee->view(  $tf_code );

		if ( $retTaxFee > 0 ) {

			return $clsTaxFee;

		} else {

			$this->setError(-1,"loadTaxFee Failed, unable to load tax/fee code : $tf_code.");
			return -1;

		}

	}

	function update( $code = NULL ) {

		$this->data_elems[EBPLS_TAX_FEE_DATE_REGISTERED] = date("Y-m-d H:i:s");
		$this->data_elems[EBPLS_TAX_FEE_DATE_UPDATED] = date("Y-m-d H:i:s");

		foreach($this->data_elems as $key=>$value ) {
			
			if( $value!= "" ) {
			
				$strValues[$key] = $value;
				
			}
			
		}

		if ( $code ) {

			$strWhere[EBPLS_TAX_FEE_CODE] = $code;

		} else {

			$strWhere[EBPLS_TAX_FEE_CODE] = $this->data_elems[EBPLS_TAX_FEE_CODE];

		}

		$ret = ebpls_update_data( $this->m_dbLink, EBPLS_TAX_FEE_TABLE, $strValues, $strWhere );

		if ( $ret < 0 ) {

			$this->debug( "UPDATE EBPLS_TAX_FEE_TABLEFAILED [error:$ret,msg=" . get_db_error() . "]" );


			$this->setError( $ret, get_db_error() );

			return $ret;

		} else {

			$this->debug( "UPDATE EBPLS_TAX_FEE_TABLE SUCCESSFULL [$ret]" );
			return $ret;

		}


	}

	
	function select( $code = NULL, $desc = NULL, $type = NULL, $page = 1, $maxrec = 10, $orderkey = EBPLS_TAX_FEE_CODE, $is_desc = true ) {
			
		return $this->selectWithSystemData( $code, $desc, $type, NULL, $page, $maxrec, $orderkey, $is_desc );	
		
	}
	
	function selectWithSystemData( $code = NULL, $desc = NULL, $type = NULL, $system_data = NULL,  $page = 1, $maxrec = 1, $orderkey = EBPLS_TAX_FEE_CODE, $is_desc = true ) {
	
	
		if ( $code != NULL && $code != "" )
			$strWhere[EBPLS_TAX_FEE_CODE] = $code;

		if ( $desc != NULL && $desc != "" )
			$strWhere[EBPLS_TAX_FEE_DESC] = array("like", "$desc%");

		if ( $type != NULL ) {
			
			if ( is_array($type) ) {
				$strWhere[EBPLS_TAX_FEE_TYPE] = array( "IN" , " ( '" . join("','", $type) . "')" );
			} else {
				$strWhere[EBPLS_TAX_FEE_TYPE] = $type;
			}
		
		
		}
	
		if ( !is_null($system_data) && ( $system_data == "0" || $system_data == "1" ) ) {
			
			$strWhere[EBPLS_TAX_SYSTEMDATA] = $system_data;
			
		}
	

		// select all columns
		$strValues[] = "*";

		if ( $orderkey != NULL ) {

			$strOrder[ $orderkey ] = $orderkey;

		} else {

			$strOrder[ $orderkey ] = EBPLS_TAX_FEE_CODE;

		}

		$result = ebpls_select_data_bypage( $this->m_dbLink, EBPLS_TAX_FEE_TABLE, $strValues, $strWhere, NULL, $strOrder, $is_desc?"DESC":"ASC", $page, $maxrec );

		if ( !is_array($result) && $result < 0 ) {
			$this->setError ( $result, get_db_error() );
			return $result;

		} else {
			for ( $i = 0 ; $i < count($result["result"]); $i++ ) {

				$records[$i] = new EBPLTaxFeeSysRef($this->m_dbLink, false );

				$clsFormula = new TaxFeeFormula( $this->m_dbLink, false );

				$ret = $clsFormula->view( $result["result"][$i][EBPLS_TAX_FORMULA_ID] );
				if ( $ret > 0 ) {
					$result["result"][$i][EBPLS_TAX_FORMULA] = unserialize($clsFormula->getData(EBPLS_FORMULAS_FORMULA_CLASS));
										
					if ( !($result["result"][$i][EBPLS_TAX_FORMULA]) ) {
						
						$this->debug("<HR>Error loading formula : " . $result["result"][$i][EBPLS_TAX_FORMULA_ID] . "!<HR>");
						$this->setError(-2,"Invalid formula on unserialize : " . $result["result"][$i][EBPLS_TAX_FORMULA_ID] . "!");
						$this->debug("Invalid formula on unserialize : " . $result["result"][$i][EBPLS_TAX_FORMULA_ID] . "!");
						return -2;

					}	
														
					$result["result"][$i][EBPLS_TAX_FORMULA]->setData( NULL, $clsFormula->getData());
					$records[$i]->setData( NULL, $result["result"][$i] );

				} else {
					echo 'ID: '.$result["result"][$i][EBPLS_TAX_FORMULA_ID];
					return $ret;

				}

			}

			$result["result"] = $records;

			return $result;

		}

	}

}


?>
