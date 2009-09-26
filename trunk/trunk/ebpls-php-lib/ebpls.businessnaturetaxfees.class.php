<?
/************************************************************************************

Module : ebpls.sysref.class.php

Dependencies :
	ebpls.database.funcs.php
	ebpls.dataencapsulator.class.php
	ebpls.global.const.php
	ebpls.global.funcs.php

Description :
	- encapsulates business nature taxfees class

Created By : Stephen Lou B. Banal
Email : sbanal@yahoo.com
Date Created : 10/16/2004 10:27PM

Last Updates :
	
Notes :

************************************************************************************/

require_once("ebpls-php-lib/ebpls.dataencapsulator.class.php");
require_once("ebpls-php-lib/ebpls.global.funcs.php");
require_once("ebpls-php-lib/ebpls.database.funcs.php");
require_once("ebpls-php-lib/ebpls.taxfeeref.class.php");

// business nature tax/fees table constant and columns
define(EBPLS_BUSINESS_NATURE_TAXFEES_TABLE, "ebpls_business_nature_taxfees");
define(EBPLS_BUSINESS_NATURE_NATURE_CODE,"business_nature_code");
define(EBPLS_BUSINESS_NATURE_TAXFEES_TAX_FEE_DESC,"tax_fee_desc");
define(EBPLS_BUSINESS_NATURE_TAXFEES_TAX_FEE_CODE,"tax_fee_code");
define(EBPLS_BUSINESS_NATURE_TAXFEES_CREATE_TS,"created_ts");
define(EBPLS_BUSINESS_NATURE_TAXFEES_ADMIN,"admin");

class EBPLSBusinessNatureTaxes extends DataEncapsulator {

	var $m_dbLink;
	var $m_clsTaxFee;

	function EBPLSBusinessNatureTaxes( $dbLink, $bDebug = false ) {

		$this->m_dbLink = $dbLink;
		$this->setDebugMode($bDebug);

		$this->addDataElement( EBPLS_BUSINESS_NATURE_NATURE_CODE, "is_not_empty", "[VALUE]" );
		$this->addDataElement( EBPLS_BUSINESS_NATURE_TAXFEES_TAX_FEE_CODE, "is_not_empty", "[VALUE]" );
		$this->addDataElement( EBPLS_BUSINESS_NATURE_TAXFEES_CREATE_TS, "is_valid_date", "[VALUE]"  );
		$this->addDataElement( EBPLS_BUSINESS_NATURE_TAXFEES_ADMIN, "is_not_empty", "[VALUE]" );
		$this->clsTaxFee = NULL;

	}

	function addTaxFee( $nature_code, $tax_fee_code, $admin ){

		$strValues[EBPLS_BUSINESS_NATURE_NATURE_CODE] = $nature_code;
		$strValues[EBPLS_BUSINESS_NATURE_TAXFEES_TAX_FEE_CODE] = $tax_fee_code;		
		$strValues[EBPLS_BUSINESS_NATURE_TAXFEES_CREATE_TS] = date("Y-m-d H:i:S");
		$strValues[EBPLS_BUSINESS_NATURE_TAXFEES_ADMIN] = $admin;

		ebpls_insert_data( $this->m_dbLink, EBPLS_BUSINESS_NATURE_TAXFEES_TABLE, $strValues );

	}

	function deleteTaxFee( $nature_code, $tax_fee_code ){

		if ( $nature_code ) {
			$strWhere[EBPLS_BUSINESS_NATURE_NATURE_CODE] = $nature_code;
		}
		
		$strValues[EBPLS_BUSINESS_NATURE_TAXFEES_TAX_FEE_CODE] = $tax_fee_code;

		ebpls_delete_data( $this->m_dbLink, EBPLS_BUSINESS_NATURE_TAXFEES_TABLE, $strWhere );

	}

	function addTaxFeeList( $arr_tax_fee_code ){

		if ( is_array($arr_tax_fee_code) ) {
			
			foreach ( $arr_tax_fee_code as $key=>$nature_code ) {
				
				$strValues[EBPLS_BUSINESS_NATURE_NATURE_CODE] = $nature_code;
				$strValues[EBPLS_BUSINESS_NATURE_TAXFEES_TAX_FEE_CODE] = $tax_fee_code;
				$strValues[EBPLS_BUSINESS_NATURE_TAXFEES_CREATE_TS] = date("Y-m-d H:i:S");
				$strValues[EBPLS_BUSINESS_NATURE_TAXFEES_ADMIN] = $admin;
		
				ebpls_insert_data( $this->m_dbLink, EBPLS_BUSINESS_NATURE_TAXFEES_TABLE, $strValues );
				
			}
			
		}

	}

	function getTaxFee(){

		if ( !$this->m_clsTaxFee && $this->getData( EBPLS_BUSINESS_NATURE_TAXFEES_TAX_FEE_CODE ) ) {

			$this->clsTaxFee = new EBPLTaxFeeSysRef( $this->m_dbLink, false );
			$this->clsTaxFee->view( $this->getData( EBPLS_BUSINESS_NATURE_TAXFEES_TAX_FEE_CODE ) );

		}

		return $this->clsTaxFee;

	}

	function getBusinessNatureTaxFees( $nature_code, $page = 1, $maxrec = 20, $orderby = EBPLS_BUSINESS_NATURE_TAXFEES_CREATE_TS, $is_desc = false  ) {

		$strWhere[EBPLS_BUSINESS_NATURE_NATURE_CODE] = $nature_code;		
		$strValues[] = "*";
		$strOrder[ $orderby ] = $orderby;
		
		//return;
		//ebpls_select_data_bypage( $this->m_dbLink, EBPLS_TAX_FEE_TABLE, $strValues, $strWhere, NULL, $strOrder, $is_desc?"DESC":"ASC", $page, $maxrec );
		$result = ebpls_select_data_bypage( $this->m_dbLink, EBPLS_BUSINESS_NATURE_TAXFEES_TABLE, $strValues, $strWhere, NULL, $strOrder, $is_desc?"DESC":"ASC", $page, $maxrec );

		if ( !is_array($result) && $result < 0 ) {

			$this->setError ( $result, get_db_error());
			return $result;

		} else {

			// transform result to EBPLSBusinessNatureTaxes object
			for ( $i = 0 ; $i < count($result["result"]); $i++ ) {

				$records[$i] = new EBPLSBusinessNatureTaxes($this->m_dbLink, false );
				$records[$i]->getTaxFee();
				$records[$i]->setData( NULL, $result["result"][$i] );

			}

			$result["result"] = $records;

			return $result;

		}

		return $result;		

	}

}

?>
