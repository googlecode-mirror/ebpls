<?
/************************************************************************************

Module : ebpls.transaction.fees.class.php

Dependencies :
	ebpls.database.funcs.php
	ebpls.dataencapsulator.class.php
	ebpls.global.const.php
	ebpls.global.funcs.php
	ebpls.global.db.funcs.php

Description :
	- encapsulates transactions of EBPLS

Created By : Stephen Lou B. Banal
Email : sbanal@yahoo.com
Date Created : 3/14/2004 3:00PM

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
require_once("ebpls-php-lib/ebpls.taxfeeformula.class.php");
require_once("ebpls-php-lib/ebpls.taxfeeref.class.php");

define("TAX_FEE_CODE_TABLE","ebpls_tax_fee_table");

define("TF_TABLE","ebpls_transaction_payables");

// define ebpls_transaction_fees data elements
define("TF_FEE_ID","fee_id");
define("TF_TRANS_ID","trans_id");
define("TF_PERMIT_TYPE","permit_type");
define("TF_PERMIT_ID","permit_id");
define("TF_TAX_FEE_CODE","tax_fee_code");
define("TF_TAX_FEE_TYPE","tax_fee_type");
define("TF_TAX_BUSINESS_NATURE_CODE","tax_business_nature_code");
define("TF_TAX_FEE_DESC","tax_fee_desc");
define("TF_TAX_ACCOUNT_CODE","tax_account_code");

define("TF_TAX_MAX_PARAMS",23);

for ( $i=1;$i<=TF_TAX_MAX_PARAMS;$i++){

	define("TF_TAX_TAXABLE_AMOUNT$i","tax_taxable_amount$i");
	
}

define("TF_TAX_FORMULA","tax_formula");
define("TF_TAX_TOTAL_AMOUNT_DUE","tax_total_amount_due");
define("TF_TS_CREATE","ts_create");
define("TF_LAST_UPDATED_TS","last_updated_ts");
define("TF_LAST_UPDATED_BY","last_updated_by");

class EBPLSTransactionFee extends DataEncapsulator {

	var $m_BusTaxCapital;
	var $m_BusTaxLastGross;

	function EBPLSTransactionFee ( $dbLink, $bDebug = false ) {

		$this->m_dbLink = $dbLink;
		$this->setDebugMode( $bDebug );		
		//$this->setDebugMode( true );		

		$this->addDataElement(TF_TAX_FEE_CODE, "is_not_empty", "[VALUE]" );

		for ( $i=1;$i<=TF_TAX_MAX_PARAMS;$i++){

			$this->addDataElement(constant("TF_TAX_TAXABLE_AMOUNT$i"), NULL, NULL );

		}

		$this->addDataElement(TF_FEE_ID, "is_valid_number", "[VALUE]", true );
		$this->addDataElement(TF_TRANS_ID, "is_valid_number", "[VALUE]", true );
		$this->addDataElement(TF_PERMIT_TYPE, "is_valid_number", "[VALUE]", true );
		$this->addDataElement(TF_PERMIT_ID, "is_not_empty", "[VALUE]", true );
		$this->addDataElement(TF_TAX_BUSINESS_NATURE_CODE, "is_not_empty", "[VALUE]", false );
		$this->addDataElement(TF_TAX_FEE_DESC, "is_not_empty", "[VALUE]", true );
		$this->addDataElement(TF_TAX_ACCOUNT_CODE, "is_not_empty", "[VALUE]", true );
		$this->addDataElement(TF_TAX_FEE_TYPE,"is_not_empty", "[VALUE]" );
		$this->addDataElement(TF_TAX_TOTAL_AMOUNT_DUE, "is_not_empty", "[VALUE]", true );
		$this->addDataElement(TF_TS_CREATE, "is_valid_date", "[VALUE]" , true );
		$this->addDataElement(TF_LAST_UPDATED_TS, "is_valid_date", "[VALUE]", true );
		$this->addDataElement(TF_LAST_UPDATED_BY, "is_not_empty", "[VALUE]", true );

	}

	function computeTaxTotalAmounDue( $depth = 0 ){
		
		if ( $depth == 1 ) return 0; // terminate recursive call on comutetax

		// load the tax/fee record with its attached formula
		$clsTaxFeeRef = new EBPLTaxFeeSysRef( $this->m_dbLink, false );
		$res_formula = $clsTaxFeeRef->select( $this->getData(TF_TAX_FEE_CODE) );		

		if ( is_array($res_formula) ) {						

			$clsTaxFormula = $res_formula["result"][0]->getData(EBPLS_TAX_FORMULA);
			
			if ( $clsTaxFormula ) { 
				
				// pass paramters to formula before computation
				for ( $i=1; $i<=TF_TAX_MAX_PARAMS; $i++ ) {
				
					$clsTaxFormula->setParameterValue( $i, $this->getData(constant("TF_TAX_TAXABLE_AMOUNT$i")) + 0.0 );
					
				}
				
				// process prerequisite tax/fees if there are any
				$arr_parameters = $clsTaxFormula->getParameterDescription( null, true );
				
				$this->debug("param cnt : " . count($arr_parameters));
				
				foreach ( $arr_parameters as $key=>$value ) {
					
					if ( eregi( "^\[TF=(.*)\]$", $value, $arrTaxFeeCode ) && eregi("^x([0-9]+)$", $key, $arrIndex) ) {
												
						
						$clsTaxFeeTmp = new EBPLSTransactionFee( $this->m_dbLink, false );
						$res_formula = $clsTaxFeeTmp->view( $this->getData(TF_TRANS_ID), $arrTaxFeeCode[1] );

						if ( $res_formula ) {
											
							$clsTaxFeeTmp->computeTaxTotalAmounDue( 1 ); 
							$this->debug("Tax/Fee Amount: "  .  $clsTaxFeeTmp->getData(TF_TAX_TOTAL_AMOUNT_DUE) . "!");
							$this->setData( constant("TF_TAX_TAXABLE_AMOUNT" . $arrIndex[1]), $clsTaxFeeTmp->getData(TF_TAX_TOTAL_AMOUNT_DUE) );
							$clsTaxFormula->setParameterValue( $arrIndex[1], floatval($clsTaxFeeTmp->getData(TF_TAX_TOTAL_AMOUNT_DUE)) );
							
						} else {

							$this->setError(-6,"Unable to load prerequisite tax/fee "  . $arrTaxFeeCode[1]  . "!");
							$this->debug("Unable to load prerequisite tax/fee "  . $arrTaxFeeCode[1]  . "!");						
							return -6;
							
						}
						
					} else {
						
						$this->debug("no match on $value");
							
					}
					
				}
				
				
				
				$clsTaxFormula->setParameterTagValue( "[CAPITAL]", $this->m_BusTaxCapital );
				$clsTaxFormula->setParameterTagValue( "[LAST_GROSS]", $this->m_BusTaxLastGross );

				$index1 = $clsTaxFormula->getParameterTagValueIndex( "[CAPITAL]");
				if ( $index1 ) {
					$this->setData(constant("TF_TAX_TAXABLE_AMOUNT" . $index1), $this->m_BusTaxCapital );
				}
				
				$index2 = $clsTaxFormula->getParameterTagValueIndex( "[LAST_GROSS]"); 			
				if ( $index2 ) { 
					$this->setData(constant("TF_TAX_TAXABLE_AMOUNT" . $index2), $this->m_BusTaxLastGross );
				}
												
				$this->debug("FORMULA $index1=" . $this->m_BusTaxCapital . ",$index2=" . $this->m_BusTaxLastGross . "<BR>");
				
				
				
				// compute
				$tax_amount = $clsTaxFormula->computeTax( $this->m_BusTaxCapital, $this->m_BusTaxLastGross );
												
				if ( $tax_amount >=0 ) {
					
					$this->data_elems[ TF_TAX_TOTAL_AMOUNT_DUE ] = $tax_amount;		
					$this->debug("FORMULA compuation OK<BR>");
					return 1;
					
				} else {
										
					$this->setError( -1, $clsTaxFormula->getError() );
					return $tax_amount;
					
				}
				
			} else {
				
				$this->setError(-5,"Unable to load formula of tax/fee "  . $this->getData(TF_TAX_FEE_CODE)  . "!");
				$this->debug("Unable to load formula of tax/fee "  . $this->getData(TF_TAX_FEE_CODE)  . "!");
				return -5;
				
			}

		} else {

			$this->data_elems[ TF_TAX_TOTAL_AMOUNT_DUE ] = 0;
			$this->debug("FORMULA ERROR (cant find tax/fee code : " . $this->getData(TF_TAX_FEE_CODE) . "<BR>");
			return -4;

		}

		

	}

	function addLOB( &$trans_obj, $capital, $last_gross, $creator ) {
		
		$this->m_BusTaxCapital = $capital;
		$this->m_BusTaxLastGross = $last_gross;
		
		return $this->add( $this, $creator );
		
	}

	function updateLOB( &$trans_obj, $capital, $last_gross, $creator ) {
		
		$this->m_BusTaxCapital = $capital;
		$this->m_BusTaxLastGross = $last_gross;
		
		return $this->update( $this, $creator );
		
	}

	function add( &$trans_obj, $creator ) {

		if ( $this->loadFeeCodeData() < 0 ) {

			$trans_obj->setError(-1,"Unable to load fee data with feecode : " . $trans_obj->getData( TRANS_FEE_ID ));
			return -1;

		}

		if ( $this->validateData() > 0 ) {

			$ts = time();
			$dt = date("Y-m-d H:i:s", $ts);
			$this->data_elems[ TF_LAST_UPDATED_TS ] = $dt;
			$this->data_elems[ TF_TS_CREATE ] = $dt;
			$this->data_elems[ TF_TRANS_ID ] = $trans_obj->getData( TRANS_ID );
			$this->data_elems[ TF_PERMIT_TYPE ] = $trans_obj->getPermitType();
			$this->data_elems[ TF_PERMIT_ID ] = $trans_obj->getPermitId();
			$this->data_elems[ TF_LAST_UPDATED_BY ] = $creator;

			if ( ( $computeRet = $this->computeTaxTotalAmounDue() ) < 0 ) {
				
				$trans_obj->setError(-1,"computeTaxTotalAmounDue failed with feecode : " . $this->getData(TF_TAX_FEE_CODE) . ", return $computeRet!" );
				return -2;
				
			}

			unset( $this->data_elems[ TF_TAX_FORMULA ] );

			$strValues = $this->data_elems;

			$ret = ebpls_insert_data( $this->m_dbLink, TF_TABLE, $strValues );

			if ( $ret < 0 ) {

				$this->debug("TF ADD ERROR : $ret");
				$trans_obj->setError( $ret, get_db_error());
				return $ret;

			} else {

				$this->debug("TF ADD OK : $ret");
				$this->data_elems[ TF_FEE_ID ] = $ret;
				return $ret;

			}

		} else {

			$this->debug("TF ADD FEE FAILED." );
			$trans_obj->m_arrError = $this->getError();
			return -1;


		}

	}

	function update( $fee_id, $admin, $user_level ) {


		$this->debug("update( $fee_id, $admin, $user_level )");

		if ( $this->loadFeeCodeData() < 0 ) {

			$this->setError(-1,"Unable to load fee data with feecode : " . $this->getData( TRANS_FEE_ID ));
			$this->debug( "Unable to load fee data with feecode : " . $this->getData( TRANS_FEE_ID ) );
			return -1;

		}

		if ( $this->validateData(true) > 0 ) {

			$ts = time();
			$dt = date("Y-m-d H:i:s", $ts);
			$this->data_elems[ TF_LAST_UPDATED_TS ] = $dt;
			$this->data_elems[ TF_LAST_UPDATED_BY ] = $creator;

			$this->computeTaxTotalAmounDue();

			unset( $this->data_elems[ TF_TAX_FORMULA ] );

			$arrData = $this->getData();

			foreach( $arrData as $key=>$value){

				if ( $arrData[$key] != NULL ) {

					$strValues[$key] = $value;

				}

			}

			$strWhere[TF_FEE_ID] = $fee_id;

			$ret = ebpls_update_data( $this->m_dbLink, TF_TABLE, $strValues, $strWhere );

			if ( $ret < 0 ) {

				$this->debug("TF UPDATE ERROR : $ret" );
				$this->setError( -1, "TF UPDATE ERROR : $ret" );
				return $ret;

			} else {

				$this->debug("TF UPDATE OK : $ret" );
				return $ret;

			}

		} else {

			$this->debug("TF UPDATE TRANS REQ FAILED : $ret" );
			$this->setError( -1, "TF UPDATE TRANS REQ FAILED : $ret" );
			return -1;

		}

	}

	function delete( $fee_id, $admin, $user_level ) {

		$strWhere[TF_FEE_ID] = $fee_id;

		$result = ebpls_delete_data ( $this->m_dbLink, TF_TABLE, $strWhere );

		if ( $result < 0 )  {

			$this->setError( $result, get_db_error() );

		}

		return $result;

	}

	function _delete( $trans_id, $tax_fee_code, $nature_code, $admin, $user_level ) {

		$strWhere[TF_TRANS_ID] = $trans_id;
		$strWhere[TF_TAX_FEE_CODE] = $tax_fee_code;
		$strWhere[TF_TAX_BUSINESS_NATURE_CODE] = $nature_code;

		$result = ebpls_delete_data ( $this->m_dbLink, TF_TABLE, $strWhere );

		if ( $result < 0 )  {

			$this->setError( $result, get_db_error() );

		}

		return $result;

	}
	
	function view( $trans_id, $tax_fee_code = NULL, $nature_code = NULL ) {

		$strValues[] = "*";
		
		if ( $tax_fee_code ) {
			$strWhere[TF_TAX_FEE_CODE] = $tax_fee_code;
		}
		
		if ( $nature_code ) {
			
			$strWhere[TF_TAX_BUSINESS_NATURE_CODE] = $nature_code;
			
		}
		
		$strWhere[TF_TRANS_ID] = $trans_id;

		$result = ebpls_select_data( $this->m_dbLink, TF_TABLE, $strValues, $strWhere );				

		if ( is_array($result) ) {
						
			$this->setData( NULL, $result[0] );
			
			return 1;

		} else {
			
			$this->setError( $result, get_db_error() );			
			return -1;

		}

	}

	
	function loadFeeCodeData( $fee_code = NULL ) {

		$strValues[$key] = "*";

		if ( $fee_code == NULL ) {
			$strWhere[TF_TAX_FEE_CODE] = $this->getData(TF_TAX_FEE_CODE);
		} else {
			$strWhere[TF_TAX_FEE_CODE] = $fee_code;
		}

		$result = ebpls_select_data( $this->m_dbLink, TAX_FEE_CODE_TABLE, $strValues, $strWhere, NULL, $strOrderBy, "DESC", NULL );

		if ( is_array($result) ) {

			$this->data_elems[TF_TAX_FEE_CODE ] = $result[0][TF_TAX_FEE_CODE];
			$this->data_elems[TF_TAX_FEE_DESC ] = $result[0][TF_TAX_FEE_DESC];
			$this->data_elems[TF_TAX_ACCOUNT_CODE ] = $result[0][TF_TAX_ACCOUNT_CODE];
			$this->data_elems[TF_TAX_FEE_TYPE ] = $result[0][TF_TAX_FEE_TYPE];

			switch($this->data_elems[TF_TAX_FEE_TYPE ]){
				case PAYABLE_TYPE_SITAX :
					{

						$this->setError( -1, "Invalid tax fee type on loadFeeCodeData, value = " . $result[0][TF_TAX_FEE_TYPE] . ", of tax fee code #" .  $result[0][TF_TAX_FEE_CODE] );
						return -1;

					} break;
				case PAYABLE_TYPE_BUSTAX :
					{

					} break;
				case PAYABLE_TYPE_TAX :
				case PAYABLE_TYPE_FEE :
					{

						$this->data_elems[TF_TAX_BUSINESS_NATURE_CODE ] = "NONE";

					};
			}

			$this->data_elems[TF_TAX_FORMULA ] = $result[0][TF_TAX_FORMULA];

			$this->debug("loadFeeCodeData ok.");

			return $result;

		} else {

			$this->setError( $result, get_db_error() );
			return -1;

		}

	}

	function loadFeeIdData( $fee_id ) {

		$strValues[$key] = "*";

		$strWhere[TF_FEE_ID] = $fee_id;

		$result = ebpls_select_data( $this->m_dbLink, TF_TABLE, $strValues, $strWhere, NULL, $strOrderBy, "DESC", NULL );

		if ( is_array($result) ) {

			$this->data_elems[TF_TAX_FEE_CODE ] = $result[0][TF_TAX_FEE_CODE];
			$this->data_elems[TF_TAX_BUSINESS_NATURE_CODE ] = $result[0][TF_TAX_BUSINESS_NATURE_CODE];
			$this->data_elems[TF_TAX_FEE_DESC ] = $result[0][TF_TAX_FEE_DESC];
			$this->data_elems[TF_TAX_ACCOUNT_CODE ] = $result[0][TF_TAX_ACCOUNT_CODE];
			$this->data_elems[TF_TAX_FEE_TYPE ] = $result[0][TF_TAX_FEE_TYPE];
			$this->data_elems[TF_TAX_FORMULA ] = $result[0][TF_TAX_FORMULA];

			$this->debug("loadFeeCodeData ok.");


			return $result;

		} else {

			$this->setError( $result, get_db_error() );
			return -1;

		}

	}

	function listFee( $trans_id, $tax_fee_type = NULL ) {

		$strValues[$key] = "*";

		if ( $tax_fee_type != NULL ) {

			if ( is_array($tax_fee_type) ) {

				$strWhere[TF_TAX_FEE_TYPE] = Array(" IN ", "('" . join("','",$tax_fee_type) . "')"  );

			} else {

				$strWhere[TF_TAX_FEE_TYPE] = $tax_fee_type;

			}

		}

		$strWhere[TF_TRANS_ID] = $trans_id;

		$result = ebpls_select_data( $this->m_dbLink, TF_TABLE, $strValues, $strWhere, NULL, $strOrderBy, "DESC", NULL );

		if ( is_array($result) ) {

			for ( $i = 0 ; $i < count($result); $i++ ) {

				$fee[$i] = new EBPLSTransactionFee( $this->m_dbLink );
				$fee[$i]->setData(NULL, $result[$i]);

			}

			return $fee;

		} else {

			$this->setError( $result, get_db_error() );
			return -1;

		}

	}

	function computeTransactionTotalTaxFeeDue( $trans_id ) {

		$strValues[$key] = "sum(tax_total_amount_due)";

		$strWhere[TF_TRANS_ID] = $trans_id;

		$result = ebpls_select_data( $this->m_dbLink, TF_TABLE, $strValues, $strWhere, NULL, $strOrderBy, "DESC", NULL );


		if ( is_array($result) ) {

			return $result[0][0];

		} else {

			$this->setError( $result, get_db_error() );
			return -1;

		}

	}

}

?>
