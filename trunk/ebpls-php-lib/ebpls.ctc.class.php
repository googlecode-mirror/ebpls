<?
/************************************************************************************

Module : ebpls.ctc.class.php

Dependencies :
	ebpls.database.funcs.php
	ebpls.dataencapsulator.class.php

Description :
	- encapsulates Community Tax Certificate creation, viewing and searching


Created By : Stephen Lou B. Banal
Email : sbanal@yahoo.com
Date Created : 3/3/2004 6:24AM

Last Updates :
	3/5/2004 1:11AM - stephen
	- added returns error value on create, load, find functions
	- added getError function, a two dimensional array having elements err_code and err_mesg;
	- added comments on methods

	3/6/2004 11:56PM - stephen
	- transfered debug method to DataEncasulator class

Notes :
	- use constants	below to retrieve data from class using getData method
	- to print debug statements create class EBLSCTC by setting second parameter to true, e.g. $ctc = new EBPLSCTC( $dbLink, true );
	- always pass a valid databas link, otherwise all db query will fail
	- users of this class can invoke the setData and getData methods inherited from DataEncapsulator

************************************************************************************/

require_once("ebpls-php-lib/ebpls.dataencapsulator.class.php");
require_once("ebpls-php-lib/ebpls.global.funcs.php");
require_once("ebpls-php-lib/ebpls.database.funcs.php");
require_once("ebpls-php-lib/ebpls.taxfeeformula.class.php");
require_once("ebpls-php-lib/ebpls.taxfeeref.class.php");

// keys for getData method
define(CTC_INDIVIDUAL_TABLE,"ebpls_ctc_individual");
define(CTC_BUSINESS_TABLE,"ebpls_ctc_business");

// CTC Data Elements Constants
// elements found on both types of ctc
define(CTC_CODE,"ctc_code");
define(CTC_BASIC_TAX,"ctc_basic_tax");
define(CTC_ADDITIONAL_TAX1,"ctc_additional_tax1");
define(CTC_ADDITIONAL_TAX2,"ctc_additional_tax2");
define(CTC_TAX_INTEREST,"ctc_tax_interest");
define(CTC_TAX_DUE,"ctc_tax_due");
define(CTC_ACCT_CODE,"ctc_acct_code");
define(CTC_PLACE_ISSUED,"ctc_place_issued");
define(CTC_DATE_ISSUED,"ctc_date_issued");

// individual type of ctc
define(CTC_OWNER_ID,"ctc_owner_id");
define(CTC_FIRST_NAME,"ctc_first_name");
define(CTC_MIDDLE_NAME,"ctc_middle_name");
define(CTC_LAST_NAME,"ctc_last_name");
define(CTC_BIRTH_DATE,"ctc_birth_date");
define(CTC_ADDRESS,"ctc_address");
define(CTC_GENDER,"ctc_gender");
define(CTC_CIVIL_STATUS,"ctc_civil_status");
define(CTC_CITIZENSHIP,"ctc_citizenship");
define(CTC_OCCUPATION,"ctc_occupation");
define(CTC_TINNO,"ctc_tin_no");
define(CTC_HEIGHT,"ctc_height");
define(CTC_WEIGHT,"ctc_weight");
define(CTC_ICR_NO,"ctc_icr_no");
define(CTC_PLACE_OF_BIRTH,"ctc_place_of_birth");
define(CTC_ADDITIONAL_TAX3,"ctc_additional_tax3");
define(CTC_TAX_EXEMPTED,"ctc_tax_exempted");

// business type of ctc
define(CTC_BUSINESS_ID,"ctc_business_id");
define(CTC_COMPANY,"ctc_company");
define(CTC_TIN_NO,"ctc_tin_no");
define(CTC_ORGANIZATION_TYPE,"ctc_organization_type");
define(CTC_PLACE_OF_INCORPORATION,"ctc_place_of_incorporation");
define(CTC_BUSINESS_NATURE,"ctc_business_nature");
define(CTC_COMPANY_ADDRESS,"ctc_company_address");
define(ERROR_CTC_INCOMPLETEDATA,-200001);


class EBPLSCTC extends DataEncapsulator {

	var $m_dbLink;
	var $m_bDebug;
	var $m_ctcType;
	var $m_strTable;
	var $m_fTotalTaxDue;



	/**
	 * Instatiation method, set $bDebug to true to print debug messages otherwise set to false.
	 * $dbLink is a valid database connection.
	 *
	 */
	function EBPLSCTC( $dbLink, $ctc_type, $bDebug = false ) {

		$this->m_dbLink = $dbLink;
		$this->setDebugMode( $bDebug );

		$this->m_ctcType = $ctc_type;


		$this->addDataElement(CTC_CODE,"is_not_empty", "[VALUE]" , true );
		$this->addDataElement(CTC_BASIC_TAX,"is_valid_number", "[VALUE]", true);
		$this->addDataElement(CTC_TAX_INTEREST,"is_valid_number", "[VALUE]", true );
		$this->addDataElement(CTC_TAX_DUE,"is_valid_number", "[VALUE]", true );
		$this->addDataElement(CTC_ACCT_CODE,"is_not_empty", "[VALUE]", true );
		$this->addDataElement(CTC_DATE_ISSUED,"is_valid_date", "[VALUE]", true );

		$this->addDataElement(CTC_ADDITIONAL_TAX1,"is_valid_number", "[VALUE]");
		$this->addDataElement(CTC_ADDITIONAL_TAX2,"is_valid_number", "[VALUE]");
		$this->addDataElement(CTC_PLACE_ISSUED,"is_not_empty", "[VALUE]");
		$this->addDataElement( CTC_TINNO, NULL, "[VALUE]" );

		if ( $ctc_type == CTC_TYPE_INDIVIDUAL ) {

			$this->m_strTable = CTC_INDIVIDUAL_TABLE;

			// individual type of paramters
			$this->addDataElement( CTC_OWNER_ID, "is_not_empty", "[VALUE]" );
			$this->addDataElement( CTC_FIRST_NAME, "is_not_empty", "[VALUE]" );
			$this->addDataElement( CTC_MIDDLE_NAME, "is_not_empty", "[VALUE]" );
			$this->addDataElement( CTC_LAST_NAME, "is_not_empty", "[VALUE]" );
			$this->addDataElement( CTC_BIRTH_DATE, "is_not_empty", "[VALUE]" );
			$this->addDataElement( CTC_ADDRESS, "is_not_empty", "[VALUE]" );
			$this->addDataElement( CTC_GENDER, "is_valid_gender", "[VALUE]" );
			$this->addDataElement( CTC_CIVIL_STATUS, "is_valid_marital_status", "[VALUE]" );
			$this->addDataElement( CTC_CITIZENSHIP, NULL, "[VALUE]" );
			$this->addDataElement( CTC_OCCUPATION, NULL, "[VALUE]" );
			$this->addDataElement( CTC_HEIGHT, NULL, "[VALUE]" );
			$this->addDataElement( CTC_WEIGHT, NULL, "[VALUE]" );
			$this->addDataElement( CTC_ICR_NO, NULL, "[VALUE]" );
			$this->addDataElement( CTC_PLACE_OF_BIRTH, NULL, "[VALUE]" );
			$this->addDataElement( CTC_ADDITIONAL_TAX3,"is_valid_number", "[VALUE]");
			$this->addDataElement( CTC_TAX_EXEMPTED,"is_valid_number", "[VALUE]" );

		} else if ( $ctc_type == CTC_TYPE_BUSINESS ) {

			$this->m_strTable = CTC_BUSINESS_TABLE;

			// business type of ctc
			$this->addDataElement( CTC_BUSINESS_ID, "is_not_empty", "[VALUE]" );
			$this->addDataElement(CTC_COMPANY,"is_not_empty", "[VALUE]");
			$this->addDataElement(CTC_TIN_NO,NULL, "[VALUE]");
			$this->addDataElement(CTC_ORGANIZATION_TYPE,"is_not_empty", "[VALUE]");
			$this->addDataElement(CTC_PLACE_OF_INCORPORATION,"is_not_empty", "[VALUE]");
			$this->addDataElement(CTC_COMPANY_ADDRESS,"is_not_empty", "[VALUE]");
			$this->addDataElement(CTC_BUSINESS_NATURE,"is_not_empty", "[VALUE]");

		}

		$this->m_fTotalTaxDue = 0.0;

	}


	/**
	 * Creats a CTC entry, will automatically validate data set ( data is set using setData( key, value) method ).
	 *
	 */
	function create(){

		if ( $this->m_dbLink ) {

			mysql_query("LOCK TABLES", $this->m_dbLink);

			$this->computeTax();

			if ( ( $error_num = $this->validateData() ) > 0 ) {

				if ( $this->m_ctcType == CTC_TYPE_INDIVIDUAL ) {

					$m_strLastCode  = get_next_system_code( $this->m_dbLink, CODES_CTC_IND_COL );

				} else {

					$m_strLastCode  = get_next_system_code( $this->m_dbLink, CODES_CTC_BUS_COL );

				}

				$this->data_elems[ CTC_CODE ] = $m_strLastCode;
				$this->data_elems[ CTC_DATE_ISSUED ] = date("Y-m-d H:i:s");
				$this->data_elems[ CTC_ACCT_CODE ] = "CTC PAYMENT";

				$strValues = $this->data_elems;
				$ret = ebpls_insert_data( $this->m_dbLink, $this->m_strTable, $strValues );

				if ( $ret < 0 ) {

					$this->debug( "CREATE CTC FAILED [error:$ret,msg=" . get_db_error() . "]" );

					$this->m_arrError["err_code"] = $ret;
					$this->m_arrError["err_mesg"] = get_db_error();

				} else {

					$this->debug( "CREATE CTC SUCCESSFULL [$m_strLastCode]" );
					$this->data_elems[CTC_CODE] = $m_strLastCode;

					if ( $this->m_ctcType == CTC_TYPE_INDIVIDUAL ) {
						$ret_ctc_code = update_system_code( $this->m_dbLink, CODES_CTC_IND_COL, $m_strLastCode);
					} else {
						$ret_ctc_code = update_system_code( $this->m_dbLink, CODES_CTC_BUS_COL, $m_strLastCode);
					}

				}

			} else {

				$this->debug( "CREATE CTC FAILED DUE TO INVALID DATA INPUT" );
				$ret =  $error_num;

			}

			mysql_query("UNLOCK TABLES", $this->m_dbLink);

			return $ret;

		} else {

			$this->setError(-1, "CREATE CTC FAILED DUE TO INVALID DB LINK" );
			$this->debug( "CREATE CTC FAILED DUE TO INVALID DB LINK" );
			return -1;

		}

	}


	/**
	 * Loads a CTC using CTC Code
	 *
	 */
	function load( $ctc_code ) {

		$strValues[$key] = "*";

		$strWhere[CTC_CODE] = $ctc_code;

		$result = ebpls_select_data( $this->m_dbLink, $this->m_strTable, $strValues, $strWhere, NULL, $strOrderBy, "DESC", NULL );

		if ( is_array($result) ) {

			$this->data_elems = $result[0];
			return $result[0][CTC_CODE];

		} else {

			$this->m_arrError["err_code"] = $result;
			$this->m_arrError["err_mesg"] = get_db_error();
			return -1;

		}

	}

	function computeTax() {

		if ( $ctc_type == CTC_TYPE_INDIVIDUAL ) {

			$clsCTC_A_EX = new EBPLTaxFeeSysRef( $this->m_dbLink, false );
			$clsCTC_A_EXRet = $clsCTC_A_EX->select( "CTC_A_EX" );

			$clsCTC_A_NEX = new EBPLTaxFeeSysRef( $this->m_dbLink, false );
			$clsCTC_A_NEXRet = $clsCTC_A_NEX->select( "CTC_A_NEX" );

			$clsCTC_B1 = new EBPLTaxFeeSysRef( $this->m_dbLink, false );
			$clsCTC_B1Ret = $clsCTC_B1->select( "CTC_B1" );

			$clsCTC_B2 = new EBPLTaxFeeSysRef( $this->m_dbLink, false );
			$clsCTC_B2Ret = $clsCTC_B2->select( "CTC_B2" );

			$clsCTC_B3 = new EBPLTaxFeeSysRef( $this->m_dbLink, false );
			$clsCTC_B3Ret = $clsCTC_B3->select( "CTC_B3" );

			$clsCTC_TTL_TAX = new EBPLTaxFeeSysRef( $this->m_dbLink, false );
			$clsCTC_TTL_TAXRet = $clsCTC_TTL_TAX->select( "CTC_TTL_TAX" );

			$clsCTC_TAX_INT = new EBPLTaxFeeSysRef( $this->m_dbLink, false );
			$clsCTC_TAX_INTRet = $clsCTC_TAX_INT->select( "CTC_TAX_INT" );

			$clsCTC_TAX_DUE = new EBPLTaxFeeSysRef( $this->m_dbLink, false );
			$clsCTC_TAX_DUERet = $clsCTC_TAX_DUE->select( "CTC_TAX_DUE" );

			if ( !(is_array($clsCTC_A_EXRet) && is_array($clsCTC_A_NEXRet) && is_array($clsCTC_B1Ret) && is_array($clsCTC_B2Ret) && is_array($clsCTC_B3Ret) && is_array($clsCTC_TAX_INTRet) && is_array($clsCTC_TAX_DUERet) )  ) {

				$this->setError(-1,"System Configuration Error : One of the requiered CTC Records is nonexistent, Please check of Fee with code CTC_A_EX, CTC_A_NEX, CTC_B1, CTC_B2, CTC_B3 and CTC_B1 exist on tax/fee manager");
				return -1;

			} else {

				$clsCTC_A_EX_Formula = $clsCTC_A_EXRet["result"][0]->getData(EBPLS_TAX_FORMULA);
				$clsCTC_B1_Formula = $clsCTC_B1Ret["result"][0]->getData(EBPLS_TAX_FORMULA);
				$clsCTC_B2_Formula = $clsCTC_B2Ret["result"][0]->getData(EBPLS_TAX_FORMULA);
				$clsCTC_B3_Formula = $clsCTC_B3Ret["result"][0]->getData(EBPLS_TAX_FORMULA);
				$clsCTC_TTL_TAX_Formula = $clsCTC_TTL_TAXRet["result"][0]->getData(EBPLS_TAX_FORMULA);
				$clsCTC_TAX_INT_Formula = $clsCTC_TAX_INTRet["result"][0]->getData(EBPLS_TAX_FORMULA);
				$clsCTC_TAX_DUE_Formula = $clsCTC_TAX_DUERet["result"][0]->getData(EBPLS_TAX_FORMULA);

				if ( $this->getData(CTC_TAX_EXEMPTED) ) {
					$clsCTC_A_EX_Formula->setParameterValue(1,0);
				}else {
					$clsCTC_A_EX_Formula->setParameterValue(1,1);
				}

				$this->data_elems[ CTC_BASIC_TAX ] = $clsCTC_A_EX_Formula->getAmount();

				$total_tax_amount = 0;
				$clsCTC_B1_Formula->setParameterValue( 2, $this->getData(CTC_ADDITIONAL_TAX1) );
				$total_tax_amount = $clsCTC_B1_Formula->getAmount();

				$clsCTC_B2_Formula->setParameterValue( 2, $this->getData(CTC_ADDITIONAL_TAX2) );
				$total_tax_amount +=  $clsCTC_B2_Formula->getAmount();

				$clsCTC_B3_Formula->setParameterValue( 2, $this->getData(CTC_ADDITIONAL_TAX3) );
				$total_tax_amount +=  $clsCTC_B3_Formula->getAmount();

				$total_tax_amount = $total_tax_amount + $this->data_elems[ CTC_BASIC_TAX ];

				$clsCTC_TAX_INT_Formula->setParameterValue(2,$total_tax_amount);

				$this->data_elems[CTC_TAX_INTEREST] = $clsCTC_TAX_INT_Formula->getAmount( $total_tax_amount );
				$this->data_elems[CTC_TAX_DUE] = $this->data_elems[CTC_TAX_INTEREST] + $this->data_elems[CTC_TAX_INTEREST];

			}

		} else {

			$clsCTCB_A_EX = new EBPLTaxFeeSysRef( $this->m_dbLink, false );
			$clsCTCB_A_EXRet = $clsCTCB_A_EX->select( "CTCB_BASIC_TAX" );

			$clsCTCB_B1 = new EBPLTaxFeeSysRef( $this->m_dbLink, false );
			$clsCTCB_B1Ret = $clsCTCB_B1->select( "CTCB_B1" );

			$clsCTCB_B2 = new EBPLTaxFeeSysRef( $this->m_dbLink, false );
			$clsCTCB_B2Ret = $clsCTCB_B2->select( "CTCB_B2" );

			$clsCTC_TTL_TAX = new EBPLTaxFeeSysRef( $this->m_dbLink, false );
			$clsCTC_TTL_TAXRet = $clsCTC_TTL_TAX->select( "CTCB_TTL_TAX" );

			$clsCTC_TAX_INT = new EBPLTaxFeeSysRef( $this->m_dbLink, false );
			$clsCTC_TAX_INTRet = $clsCTC_TAX_INT->select( "CTCB_TAX_INT" );

			$clsCTC_TAX_DUE = new EBPLTaxFeeSysRef( $this->m_dbLink, false );
			$clsCTC_TAX_DUERet = $clsCTC_TAX_DUE->select( "CTC_TAX_DUE" );

			if ( !(is_array($clsCTCB_A_EXRet) && is_array($clsCTCB_B1Ret) && is_array($clsCTCB_B2Ret) && is_array($clsCTC_TAX_INTRet) && is_array($clsCTC_TAX_DUERet) )  ) {

				$this->setError(-1,"System Configuration Error : One of the requiered CTC Records is nonexistent, Please check of Fee with code CTCB_A_EX, CTCB_B1, CTCB_B2 exist on tax/fee manager");
				return -1;

			} else {

				$clsCTCB_A_EX_Formula = $clsCTCB_A_EXRet["result"][0]->getData(EBPLS_TAX_FORMULA);
				$clsCTCB_B1_Formula = $clsCTCB_B1Ret["result"][0]->getData(EBPLS_TAX_FORMULA);
				$clsCTCB_B2_Formula = $clsCTCB_B2Ret["result"][0]->getData(EBPLS_TAX_FORMULA);
				$clsCTC_TTL_TAX_Formula = $clsCTC_TTL_TAXRet["result"][0]->getData(EBPLS_TAX_FORMULA);
				$clsCTC_TAX_INT_Formula = $clsCTC_TAX_INTRet["result"][0]->getData(EBPLS_TAX_FORMULA);
				$clsCTC_TAX_DUE_Formula = $clsCTC_TAX_DUERet["result"][0]->getData(EBPLS_TAX_FORMULA);

				$this->data_elems[ CTC_BASIC_TAX ] = $clsCTCB_A_EX_Formula->getAmount();

				$total_tax_amount = 0;
				$clsCTCB_B1_Formula->setParameterValue( 2, $this->getData(CTC_ADDITIONAL_TAX1) );
				$total_tax_amount = $clsCTCB_B1_Formula->getAmount();

				$clsCTCB_B2_Formula->setParameterValue( 2, $this->getData(CTC_ADDITIONAL_TAX2) );
				$total_tax_amount +=  $clsCTCB_B2_Formula->getAmount();

				$total_tax_amount = $total_tax_amount + $this->data_elems[ CTC_BASIC_TAX ];

				$clsCTC_TAX_INT_Formula->setParameterValue( 2,$total_tax_amount);

				$this->data_elems[CTC_TAX_INTEREST] = $clsCTC_TAX_INT_Formula->getAmount();
				$this->data_elems[CTC_TAX_DUE] = $this->data_elems[CTC_TAX_INTEREST] + $this->data_elems[CTC_TAX_INTEREST];

			}

		}

		/*
		if ( $this->m_ctcType == CTC_TYPE_INDIVIDUAL ) {

			if ( $this->getData(CTC_TAX_EXEMPTED) ) {
				$this->data_elems[ CTC_BASIC_TAX ] = 1.0;
			}else {
				$this->data_elems[ CTC_BASIC_TAX ] = 5.0;
			}

			$amount = $this->getData( CTC_ADDITIONAL_TAX1 ) + $this->getData( CTC_ADDITIONAL_TAX2 ) + $this->getData( CTC_ADDITIONAL_TAX3 );
			$total_tax_amount = round($amount/1000,2);

		} else if ( $this->m_ctcType == CTC_TYPE_BUSINESS ) {

			$this->data_elems[ CTC_BASIC_TAX ] = 500.0;
			$amount = $this->getData( CTC_ADDITIONAL_TAX1 ) + $this->getData( CTC_ADDITIONAL_TAX2 );
			$total_tax_amount = round(2*($amount/5000),2);

		}

		$total_tax_amount = $total_tax_amount + $this->data_elems[ CTC_BASIC_TAX ];


		// april to dec CTC payment have a penalty at 2% increase per month starting at 8% on april
		$mo = date("m");

		if ( $mo >= 4 ) {

			$percent = (0.08 + 0.02*($mo - 4));
			$this->data_elems[CTC_TAX_INTEREST] = round($amount*$percent,2);

		}

		$this->data_elems[CTC_TAX_DUE] = $this->data_elems[CTC_TAX_INTEREST] + $amount;
		*/
	}


	function printIndividualCTCScript( $ctc_type,$basic_tax_field,$tax_a1_fields,$tax_a2_fields,$tax_a3_fields,$out_total_amount_due,$out_total_interest_due,$out_total_paid_due ) {

			if ( $ctc_type == CTC_TYPE_INDIVIDUAL ) {

				$clsCTC_A_EX = new EBPLTaxFeeSysRef( $this->m_dbLink, false );
				$clsCTC_A_EXRet = $clsCTC_A_EX->select( "CTC_A_EX" );

				$clsCTC_A_NEX = new EBPLTaxFeeSysRef( $this->m_dbLink, false );
				$clsCTC_A_NEXRet = $clsCTC_A_NEX->select( "CTC_A_NEX" );

				$clsCTC_B1 = new EBPLTaxFeeSysRef( $this->m_dbLink, false );
				$clsCTC_B1Ret = $clsCTC_B1->select( "CTC_B1" );

				$clsCTC_B2 = new EBPLTaxFeeSysRef( $this->m_dbLink, false );
				$clsCTC_B2Ret = $clsCTC_B2->select( "CTC_B2" );

				$clsCTC_B3 = new EBPLTaxFeeSysRef( $this->m_dbLink, false );
				$clsCTC_B3Ret = $clsCTC_B3->select( "CTC_B3" );

				$clsCTC_TTL_TAX = new EBPLTaxFeeSysRef( $this->m_dbLink, false );
				$clsCTC_TTL_TAXRet = $clsCTC_TTL_TAX->select( "CTC_TTL_TAX" );

				$clsCTC_TAX_INT = new EBPLTaxFeeSysRef( $this->m_dbLink, false );
				$clsCTC_TAX_INTRet = $clsCTC_TAX_INT->select( "CTC_TAX_INT" );

				$clsCTC_TAX_DUE = new EBPLTaxFeeSysRef( $this->m_dbLink, false );
				$clsCTC_TAX_DUERet = $clsCTC_TAX_DUE->select( "CTC_TAX_DUE" );

				if ( !(is_array($clsCTC_A_EXRet) && is_array($clsCTC_A_NEXRet) && is_array($clsCTC_B1Ret) && is_array($clsCTC_B2Ret) && is_array($clsCTC_B3Ret) && is_array($clsCTC_TAX_INTRet) && is_array($clsCTC_TAX_DUERet) )  ) {

					$this->setError(-1,"System Configuration Error : One of the requiered CTC Records is nonexistent, Please check of Fee with code CTC_A_EX, CTC_A_NEX, CTC_B1, CTC_B2, CTC_B3 and CTC_B1 exist on tax/fee manager");
					return -1;

				} else {

					$clsCTC_A_EX_Formula = $clsCTC_A_EXRet["result"][0]->getData(EBPLS_TAX_FORMULA);
					$clsCTC_B1_Formula = $clsCTC_B1Ret["result"][0]->getData(EBPLS_TAX_FORMULA);
					$clsCTC_B2_Formula = $clsCTC_B2Ret["result"][0]->getData(EBPLS_TAX_FORMULA);
					$clsCTC_B3_Formula = $clsCTC_B3Ret["result"][0]->getData(EBPLS_TAX_FORMULA);
					$clsCTC_TTL_TAX_Formula = $clsCTC_TTL_TAXRet["result"][0]->getData(EBPLS_TAX_FORMULA);
					$clsCTC_TAX_INT_Formula = $clsCTC_TAX_INTRet["result"][0]->getData(EBPLS_TAX_FORMULA);
					$clsCTC_TAX_DUE_Formula = $clsCTC_TAX_DUERet["result"][0]->getData(EBPLS_TAX_FORMULA);

					echo "\n" . $clsCTC_A_EX_Formula->createAutocompleteJScript(Array("x1"=>$basic_tax_field["in"][0]),$basic_tax_field["out"], "checked" );
					echo "\n" . $clsCTC_B1_Formula->createAutocompleteJScript(Array("x1"=>$tax_a1_fields["in"][0]),$tax_a1_fields["out"]);
					echo "\n" . $clsCTC_B2_Formula->createAutocompleteJScript(Array("x1"=>$tax_a2_fields["in"][0]),$tax_a2_fields["out"]);
					echo "\n" . $clsCTC_B3_Formula->createAutocompleteJScript(Array("x1"=>$tax_a3_fields["in"][0]),$tax_a3_fields["out"]);
					echo "\n" . $clsCTC_TTL_TAX_Formula->createAutocompleteJScript($out_total_amount_due["in"],$out_total_amount_due["out"]);
					echo "\n" . $clsCTC_TAX_INT_Formula->createAutocompleteJScript($out_total_interest_due["in"],$out_total_interest_due["out"]);
					echo "\n" . $clsCTC_TAX_DUE_Formula->createAutocompleteJScript($out_total_paid_due["in"],$out_total_paid_due["out"]);

				}

			} else {

				$clsCTCB_A_EX = new EBPLTaxFeeSysRef( $this->m_dbLink, false );
				$clsCTCB_A_EXRet = $clsCTCB_A_EX->select( "CTCB_BASIC_TAX" );

				$clsCTCB_B1 = new EBPLTaxFeeSysRef( $this->m_dbLink, false );
				$clsCTCB_B1Ret = $clsCTCB_B1->select( "CTCB_B1" );

				$clsCTCB_B2 = new EBPLTaxFeeSysRef( $this->m_dbLink, false );
				$clsCTCB_B2Ret = $clsCTCB_B2->select( "CTCB_B2" );

				$clsCTC_TTL_TAX = new EBPLTaxFeeSysRef( $this->m_dbLink, false );
				$clsCTC_TTL_TAXRet = $clsCTC_TTL_TAX->select( "CTCB_TTL_TAX" );

				$clsCTC_TAX_INT = new EBPLTaxFeeSysRef( $this->m_dbLink, false );
				$clsCTC_TAX_INTRet = $clsCTC_TAX_INT->select( "CTCB_TAX_INT" );

				$clsCTC_TAX_DUE = new EBPLTaxFeeSysRef( $this->m_dbLink, false );
				$clsCTC_TAX_DUERet = $clsCTC_TAX_DUE->select( "CTC_TAX_DUE" );

				if ( !(is_array($clsCTCB_A_EXRet) && is_array($clsCTCB_B1Ret) && is_array($clsCTCB_B2Ret) && is_array($clsCTC_TAX_INTRet) && is_array($clsCTC_TAX_DUERet) )  ) {

					$this->setError(-1,"System Configuration Error : One of the requiered CTC Records is nonexistent, Please check of Fee with code CTCB_A_EX, CTCB_B1, CTCB_B2 exist on tax/fee manager");
					return -1;

				} else {

					$clsCTCB_A_EX_Formula = $clsCTCB_A_EXRet["result"][0]->getData(EBPLS_TAX_FORMULA);
					$clsCTCB_B1_Formula = $clsCTCB_B1Ret["result"][0]->getData(EBPLS_TAX_FORMULA);
					$clsCTCB_B2_Formula = $clsCTCB_B2Ret["result"][0]->getData(EBPLS_TAX_FORMULA);
					$clsCTC_TTL_TAX_Formula = $clsCTC_TTL_TAXRet["result"][0]->getData(EBPLS_TAX_FORMULA);
					$clsCTC_TAX_INT_Formula = $clsCTC_TAX_INTRet["result"][0]->getData(EBPLS_TAX_FORMULA);
					$clsCTC_TAX_DUE_Formula = $clsCTC_TAX_DUERet["result"][0]->getData(EBPLS_TAX_FORMULA);

					//echo "\n" . $clsCTCB_A_EX_Formula->createAutocompleteJScript(Array("x1"=>$basic_tax_field["in"][0]),$basic_tax_field["out"]);
					echo "\n" . $clsCTCB_A_EX_Formula->createAutocompleteJScript(NULL,$basic_tax_field["out"]);
					echo "\n" . $clsCTCB_B1_Formula->createAutocompleteJScript(Array("x1"=>$tax_a1_fields["in"][0]),$tax_a1_fields["out"]);
					echo "\n" . $clsCTCB_B2_Formula->createAutocompleteJScript(Array("x1"=>$tax_a2_fields["in"][0]),$tax_a2_fields["out"]);
					echo "\n" . $clsCTC_TTL_TAX_Formula->createAutocompleteJScript($out_total_amount_due["in"],$out_total_amount_due["out"]);
					echo "\n" . $clsCTC_TAX_INT_Formula->createAutocompleteJScript($out_total_interest_due["in"],$out_total_interest_due["out"]);
					echo "\n" . $clsCTC_TAX_DUE_Formula->createAutocompleteJScript($out_total_paid_due["in"],$out_total_paid_due["out"]);

				}

			}

	}

	/**
	 * Find function searches CTC table for users having exact values for firstname, lastname, middlename and address.
	 *
	 * Set a NULL value to any of the parameters a users wishes not included on the search function.
	 *
	 * Search uses AND on query on all of the non-NULL parameters provided. Exact string match is implemented.
	 *
	 * Search result can be ordered by setting orderkey as any of the pre-defined data elements constants defined above,
	 * set $is_desc to true to use DESC otherwise set to false.
	 *
	 * Paging is automatically provided by letting users of this method provide the page number and the max records per page.
	 * Page result are automaticallly selected give these information, by rule $maxrec should be > 0 and $page should be > 1 and < maxpages
	 *
	 * Result of this method is a 2-dim array, having keys "page_info" and "result"
	 * First element of result having key "page_info" contains all the information regarding the query
	 * 		total = number of total records of search
	 *		max_pages = number of pages in search
	 *		count = number of records on current page
	 *		page = current page selected
	 * Second element of array having key "result" contains result of the search. "result" search value is an array of EBLPSCTC objects
	 *
	 *
	 */
	function findIndividualCTC( $ctc_code, $fname, $mname, $lname, $address, $date_issued, $page, $maxrec = 10, $orderkey = CTC_DATE_ISSUED, $is_desc = true ) {

		if ( $ctc_code != NULL )
			$strWhere[CTC_CODE] = $ctc_code;

		if ( $fname != NULL )
			$strWhere[CTC_FIRST_NAME] = array("like", "$fname%");

		if ( $mname != NULL )
			$strWhere[CTC_MIDDLE_NAME] = array("like", "$mname%");

		if ( $lname != NULL )
			$strWhere[CTC_LAST_NAME] = array("like","$lname%");

		if ( $address != NULL )
			$strWhere[CTC_ADDRESS] = array("like","$address%");

		if ( $date_issued != NULL ) {

			$strWhere[CTC_DATE_ISSUED] = array("regexp","$date_issued");

		}

		// select all columns
		$strValues[] = "*";

		$strOrder[$orderkey] = $orderkey;

		$result = ebpls_select_data_bypage( $this->m_dbLink, $this->m_strTable, $strValues, $strWhere, NULL, $strOrder, $is_desc?"DESC":"ASC", $page, $maxrec );

		if ( !is_array($result) && $result < 0 ) {

			$this->m_arrError["err_code"] = $result;
			$this->m_arrError["err_mesg"] = get_db_error();
			return $result;

		} else {

			// transform result to EBPLCTC object
			for ( $i = 0 ; $i < count($result["result"]); $i++ ) {
				$records[$i] = new EBPLSCTC($this->m_dbLink, CTC_TYPE_INDIVIDUAL );
				$records[$i]->setData( NULL, $result["result"][$i] );
			}

			$result["result"] = $records;

			return $result;

		}

	}

	function loadCTC( $id ) {

		if ( $this->m_ctcType == CTC_TYPE_BUSINESS ) {

			$strWhere[CTC_BUSINESS_ID] = $id;

		} else {

			$strWhere[CTC_OWNER_ID] = $id;

		}

		$strWhere[CTC_DATE_ISSUED] = Array(" like ", date("Y") . "%" );
		$strValues[] = "*";

		$result = ebpls_select_data( $this->m_dbLink, $this->m_strTable, $strValues, $strWhere );

		if ( !is_array($result) && $result < 0 ) {

			$this->m_arrError["err_code"] = $result;
			$this->m_arrError["err_mesg"] = get_db_error();
			return $result;

		} else {

			// transform result to EBPLCTC object
			$this->setData( NULL, $result[0] );
			return $result;

		}

	}


	function findBusinessCTC( $ctc_code, $company, $address, $org_type, $bus_nature, $date_issued, $page, $maxrec = 10, $orderkey = CTC_DATE_ISSUED, $is_desc = true ) {

		if ( $ctc_code != NULL )
			$strWhere[CTC_CODE] = $ctc_code;

		if ( $company != NULL )
			$strWhere[CTC_COMPANY] = array("like", "$company%");

		if ( $address != NULL )
			$strWhere[CTC_COMPANY_ADDRESS] = array("like", "$address%");

		if ( $org_type != NULL )
			$strWhere[CTC_ORGANIZATION_TYPE] = $org_type;

		if ( $bus_nature != NULL )
			$strWhere[CTC_BUSINESS_NATURE] = array("like","$bus_nature%");

		if ( $date_issued != NULL )
			$strWhere[CTC_DATE_ISSUED] = array("regexp","$date_issued");

		$strValues[] = "*";

		$strOrder[$orderkey] = $orderkey;

		$result = ebpls_select_data_bypage( $this->m_dbLink, $this->m_strTable, $strValues, $strWhere, NULL, $strOrder, $is_desc?"DESC":"ASC", $page, $maxrec );

		if ( !is_array($result) && $result < 0 ) {

			$this->m_arrError["err_code"] = $result;
			$this->m_arrError["err_mesg"] = get_db_error();
			return $result;

		} else {

			// transform result to EBPLCTC object
			for ( $i = 0 ; $i < count($result["result"]); $i++ ) {
				$records[$i] = new EBPLSCTC($this->m_dbLink, CTC_TYPE_INDIVIDUAL );
				$records[$i]->setData( NULL, $result["result"][$i] );
			}

			$result["result"] = $records;

			return $result;

		}

	}

}

?>