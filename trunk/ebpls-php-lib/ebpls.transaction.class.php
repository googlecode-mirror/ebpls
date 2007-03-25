<?
/************************************************************************************

Module : ebpls.transaction.class.php

Dependencies :
	ebpls.database.funcs.php
	ebpls.dataencapsulator.class.php
	ebpls.global.const.php
	ebpls.global.funcs.php
	ebpls.global.db.funcs.php

Description :

Created By : Stephen Lou B. Banal
Email : sbanal@yahoo.com
Date Created : 3/14/2004 3:00PM

Last Updates :
	[ DATE / TIME ] - [ PROGRAMMER ]
	- < DETAILS 1 >

Notes :

************************************************************************************/

require_once("ebpls-php-lib/ebpls.occupational.permit.class.php");
require_once("ebpls-php-lib/ebpls.motorized.permit.class.php");
require_once("ebpls-php-lib/ebpls.dataencapsulator.class.php");
require_once("ebpls-php-lib/ebpls.global.const.php");
require_once("ebpls-php-lib/ebpls.global.funcs.php");
require_once("ebpls-php-lib/ebpls.database.funcs.php");
require_once("ebpls-php-lib/ebpls.sysref.class.php");
require_once("ebpls-php-lib/ebpls.ctc.class.php");
require_once("ebpls-php-lib/ebpls.owner.class.php");
require_once("ebpls-php-lib/ebpls.businessnaturetaxfees.class.php");
require_once("ebpls-php-lib/ebpls.enterprise.class.php");
require_once("ebpls-php-lib/ebpls.enterprise.permit.class.php");
require_once("ebpls-php-lib/ebpls.franchise.permit.class.php");
require_once("ebpls-php-lib/ebpls.fishery.permit.class.php");
require_once("ebpls-php-lib/ebpls.transaction.fees.class.php");
require_once("ebpls-php-lib/ebpls.transaction.paymentschedule.class.php");
require_once("ebpls-php-lib/ebpls.transaction.paymentor.class.php");
require_once("ebpls-php-lib/ebpls.transaction.paymentcheck.class.php");
require_once("ebpls-php-lib/ebpls.transaction.requirements.class.php");

// keys for getData method
define(EBPLS_TRANSACTION_TABLE, "ebpls_transaction");

// trnsaction Data Elements Constants
define(TRANS_ID,"trans_id");
define(TRANS_TRANSACTION_DATE,"trans_transaction_date");

define(TRANS_APPLICATION_CODE,"trans_application_code");
define(TRANS_APPLICATION_DATE,"trans_application_date");
define(TRANS_APPLICATION_DATE_PROCESSED,"trans_application_date_processed");
define(TRANS_APPLICATION_OFFICER,"trans_application_officer");

define(TRANS_ASSESSMENT_CODE,"trans_assessment_code");
define(TRANS_ASSESSMENT_DATE,"trans_assessment_date");
define(TRANS_ASSESSMENT_DATE_PROCESSED,"trans_assessment_date_processed");
define(TRANS_ASSESSMENT_OFFICER,"trans_assessment_officer");

define(TRANS_PAYMENT_CODE,"trans_payment_code");
define(TRANS_PAYMENT_DATE,"trans_payment_date");
define(TRANS_PAYMENT_DATE_PROCESSED,"trans_payment_date_processed");
define(TRANS_PAYMENT_DATE_OFFICER,"trans_payment_officer");
define(TRANS_BILLING_DATE,"trans_billing_date");
define(TRANS_PAYMENT_STATUS,"trans_payment_status");
define(TRANS_PAYMENT_MODE,"trans_payment_mode");
define(TRANS_PAYMENT_TAXFEE_DIVIDED,"trans_payment_taxfee_divided");
define(TRANS_APPROVAL_CODE,"trans_approval_code");
define(TRANS_APPROVAL_DATE,"trans_approval_date");
define(TRANS_APPROVAL_DATE_PROCESSED,"trans_approval_date_processed");
define(TRANS_APPROVAL_OFFICER,"trans_approval_officer");

define(TRANS_RELEASE_CODE,"trans_release_code");
define(TRANS_RELEASE_DATE,"trans_release_date");
define(TRANS_RELEASE_DATE_PROCESSED,"trans_release_date_processed");
define(TRANS_RELEASE_OFFICER,"trans_release_officer");

define(TRANS_TRANSACTION_STATUS,"trans_status");
define(TRANS_PERMIT_TRANS_TYPE,"trans_permit_trans_type");
define(TRANS_TOTAL_AMOUNT_DUE,"trans_total_amount_due");
define(TRANS_TOTAL_AMOUNT_PAID,"trans_total_amount_paid");
define(TRANS_TYPE,"trans_type");

define(TRANS_ADMIN_USER,"last_updated_by");
define(TRANS_COMMENT,"trans_admin_user");
define(TRANS_BUSINESS_ID,"business_id");
define(TRANS_OWNER_ID,"owner_id");
define(TRANS_PERMIT_NO,"permit_id");
define(TRANS_PERMIT_CODE,"permit_code");
define(TRANS_PERMIT_TYPE,"permit_type");

define(TRANS_BUSNATURE_TABLE,"ebpls_transaction_business_nature");

define(TRANS_BUSNATURE_TRANS_ID,"trans_id");
define(TRANS_BUSNATURE_BUSINESS_ID,"business_id");
define(TRANS_BUSNATURE_OWNER_ID,"owner_id");
define(TRANS_BUSNATURE_BUSINESS_NATURE_CODE,"business_nature_code");
define(TRANS_BUSNATURE_BUSINESS_DESCRIPTION,"business_description");
define(TRANS_BUSNATURE_CAPITAL_INVESTMENT,"capital_investment");
define(TRANS_BUSNATURE_LAST_GROSS,"last_years_gross");
define(TRANS_BUSNATURE_STATUS,"status");
define(TRANS_BUSNATURE_FOR_YEAR,"application_year");
define(TRANS_BUSNATURE_TS_CREATE,"ts_create");
define(TRANS_BUSNATURE_TS_UPDATE,"ts_update");

class EBPLSTransaction extends DataEncapsulator {

	var $m_objPermit;
	var $m_arrNatureCodes;

	/**
	 * Instatiation method, set $bDebug to true to print debug messages otherwise set to false.
	 * $dbLink is a valid database connection.
	 *
	 **/
	function EBPLSTransaction( $dbLink, $bDebug = false ) {

		$this->m_dbLink = $dbLink;
		$this->setDebugMode( $bDebug );
		//$this->setDebugMode( true );

		$this->m_objPermit  = NULL;

		$this->addDataElement(TRANS_ID, "is_valid_number", "[VALUE]", true );

		$this->addDataElement(TRANS_BUSINESS_ID, "is_valid_number", "[VALUE]", true );
		$this->addDataElement(TRANS_OWNER_ID, "is_valid_number", "[VALUE]", true );

		// permit id
		$this->addDataElement(TRANS_PERMIT_NO, "is_not_empty", "[VALUE]", true );
		$this->addDataElement(TRANS_PERMIT_TYPE, "is_not_empty", "[VALUE]", true );

		// application details cols ( used on creation )
		$this->addDataElement(TRANS_TRANSACTION_DATE, "is_valid_date", "[VALUE]", true );
		$this->addDataElement(TRANS_APPLICATION_CODE, "is_not_empty", "[VALUE]", true );
		$this->addDataElement(TRANS_APPLICATION_DATE, "is_valid_date", "[VALUE]", true );
		$this->addDataElement(TRANS_APPLICATION_DATE_PROCESSED, "is_valid_date", "[VALUE]", true );

		// assessment details cols
		$this->addDataElement(TRANS_ASSESSMENT_CODE, "is_not_empty", "[VALUE]", true );
		$this->addDataElement(TRANS_ASSESSMENT_DATE, "is_valid_date", "[VALUE]", true );
		$this->addDataElement(TRANS_ASSESSMENT_DATE_PROCESSED, "is_valid_date", "[VALUE]", true );

		// payment details cols????
		$this->addDataElement(TRANS_PAYMENT_CODE, "is_valid_number", "[VALUE]", true );
		$this->addDataElement(TRANS_PAYMENT_DATE, "is_valid_date", "[VALUE]", true );
		$this->addDataElement(TRANS_PAYMENT_DATE_PROCESSED, "is_valid_date", "[VALUE]", true );
		$this->addDataElement(TRANS_BILLING_DATE, "is_valid_date", "[VALUE]", true );
		$this->addDataElement(TRANS_PAYMENT_TAXFEE_DIVIDED, NULL, NULL, true );

		// approval details cols
		$this->addDataElement(TRANS_APPROVAL_CODE, "is_valid_number", "[VALUE]", true );
		$this->addDataElement(TRANS_APPROVAL_DATE, "is_valid_date", "[VALUE]", true );
		$this->addDataElement(TRANS_APPROVAL_DATE_PROCESSED, "is_valid_date", "[VALUE]", true );

		// release details cols
		$this->addDataElement(TRANS_RELEASE_CODE, "is_valid_number", "[VALUE]", true );
		$this->addDataElement(TRANS_RELEASE_DATE, "is_valid_date", "[VALUE]", true );
		$this->addDataElement(TRANS_RELEASE_DATE_PROCESSED, "is_valid_date", "[VALUE]", true );

		// trans status and other payment details
		$this->addDataElement(TRANS_TRANSACTION_STATUS, "is_not_empty", "[VALUE]", true );
		//$this->addDataElement(TRANS_PERMIT_TRANS_TYPE, "is_valid_number", "[VALUE]", true );
		$this->addDataElement(TRANS_TOTAL_AMOUNT_DUE, "is_valid_number", "[VALUE]", true );
		$this->addDataElement(TRANS_TOTAL_AMOUNT_PAID, "is_valid_number", "[VALUE]", true );
		$this->addDataElement(TRANS_PAYMENT_MODE, "is_valid_number", "[VALUE]", true );
		$this->addDataElement(TRANS_PAYMENT_STATUS, NULL, "[VALUE]", true );
		$this->addDataElement(TRANS_TYPE, "is_not_empty", "[VALUE]", true );

		$this->addDataElement(TRANS_ADMIN_USER, "is_not_empty", "[VALUE]", true );

	}

	/*********************************************
	 *
	 * APPLICATION OFFICER FUNCTIONS
	 * Requirements Manipulation Functions
	 *
	 *********************************************/
	function newTransaction( &$permit, $creator, $user_level, $action = TRANS_TYPE_NEW ) {

		if ( $action != TRANS_TYPE_NEW && $action != TRANS_TYPE_RENEW  ) {

			$this->debug("newTransaction Failed, Action value passed $action not recognized, actions allowed are TRANS_TYPE_NEW and TRANS_TYPE_RENEW.");
			$this->setError(-1,"newTransaction Failed, Action value passed $action not recognized, actions allowed are TRANS_TYPE_NEW and TRANS_TYPE_RENEW.");
			return -1;

		}

		// NOTE : check if there exist a transaction involving the same NEW/RENEWAL of user/business of the current year
		// if exist return error
		// else resume transaction

		if ( is_a( $permit, "EBPLSPermit") ) {

			// insert to transaction table
			$this->data_elems[ TRANS_BUSINESS_ID ] = 0;
			$this->data_elems[ TRANS_TYPE ] = $action;
			$this->data_elems[ TRANS_PAYMENT_STATUS ] = TPS_PAYMENT_STATE_UNPAID;

			$this->m_objPermit = $permit;

			if ( is_a( $permit, "EBPLSMotorizedPermit") ) {
				$this->data_elems[ TRANS_OWNER_ID] = $permit->getData( MOTORIZED_OWNER_ID );
			} else if ( is_a( $permit, "EBPLSOccupationalPermit") ) {
				$this->data_elems[ TRANS_OWNER_ID] = $permit->getData( OCCUPATIONAL_OWNER_ID );
			} else if ( is_a( $permit, "EBPLSPeddlersPermit") ) {
				$this->data_elems[ TRANS_OWNER_ID] = $permit->getData( PEDDLERS_OWNER_ID );
			} else if ( is_a( $permit, "EBPLSFranchisePermit") ) {
				$this->data_elems[ TRANS_OWNER_ID] = $permit->getData( FRANCHISE_OWNER_ID );
			} else if ( is_a( $permit, "EBPLSFisheryPermit") ) {
				$this->data_elems[ TRANS_OWNER_ID] = $permit->getData( FISHERY_OWNER_ID );
			} else if ( is_a( $permit, "EBPLSEnterprisePermit") ) {

				$arrNature = $permit->getLineOfBusiness();

				if ( !is_array($arrNature) || count($arrNature) == 0 ) {

					$this->debug("Business Permit no business nature attached.");
					$this->setError(-1,"Business Permit no business nature attached.");
					return -2;

				}

				$this->data_elems[ TRANS_OWNER_ID] = $permit->getData( BE_OWNER_ID );
				$this->data_elems[ TRANS_BUSINESS_ID ] = $permit->getData( BE_BUSINESS_ID );
				
			}else{

				$this->debug("Permit class passed of unknown subclass.");
				$this->setError(-1,"Permit class passed of uknown subclass.");
				return -1;

			}

			ebpls_start_transaction( $this->m_dbLink );

			if ( $permit->newApplication( $creator, $user_level ) <= 0 )
			{

				$this->debug("Create Permit Failed.");
				$this->setError( -1, "Create Permit Failed.");
				ebpls_rollback_transaction( $this->m_dbLink );
				return -1;

			}

			$this->debug("PERMIT ID = " . $permit->getPermitNo() );
			$this->data_elems[ TRANS_PERMIT_NO ] = $permit->getPermitNo();
			$this->data_elems[ TRANS_PERMIT_TYPE ] = $permit->getPermitType();

			// get next transaction code
			$app_code  = get_next_system_code( $this->m_dbLink, CODES_APP_COL );

			$this->data_elems[ TRANS_APPLICATION_CODE ] = $app_code;
			$this->data_elems[ TRANS_TRANSACTION_DATE ] = date("Y-m-d H:i:s");
			$this->data_elems[ TRANS_APPLICATION_DATE ] = date("Y-m-d H:i:s");
			$this->data_elems[ TRANS_ADMIN_USER ] = $creator;
			$this->data_elems[ TRANS_TRANSACTION_STATUS ] = TRANS_STATUS_APPLICATION;
			$this->data_elems[ TRANS_APPLICATION_CODE ] = $app_code;

			$strValues = $this->data_elems;

			$ret = ebpls_insert_data( $this->m_dbLink, EBPLS_TRANSACTION_TABLE, $strValues );

			if ( $ret > 0 ) {

				// create default application requirements
				$req_create = create_permit_requirements ( $this->m_dbLink,  $ret, $permit->getPermitNo(), $permit->getPermitType(), $creator, "APP", $action );
				if ( $req_create < 0 ) $this->setError(-1,get_db_error());

				// create default fees
				$fee_create = create_permit_requirements ( $this->m_dbLink,  $ret, $permit->getPermitNo(), $permit->getPermitType(), $creator, "FEE", $action );
				if ( $fee_create < 0 ) $this->setError(-1,get_db_error());

				// create default payable taxes
				$tax_nature_create = create_permit_requirements ( $this->m_dbLink,  $ret, $permit->getPermitNo(), $permit->getPermitType(), $creator, "TAX", $action );

				if ( $tax_nature_create < 0 ) {

					$this->setError(-1,get_db_error());

				} else {

					// link ctc to application if CTC is one of the requirements
					if ( is_a( $permit, "EBPLSEnterprisePermit") ) {

						$clsCTC = new EBPLSCTC( $this->m_dbLink, CTC_TYPE_BUSINESS );
						$ctcRet = $clsCTC->loadCTC( $this->data_elems[ TRANS_BUSINESS_ID ] );

						$clsReq = new EBPLSTransactionRequirement( $this->m_dbLink );

					} else {

						$clsCTC = new EBPLSCTC( $this->m_dbLink, CTC_TYPE_INDIVIDUAL );
						$ctcRet = $clsCTC->loadCTC( $this->data_elems[ TRANS_OWNER_ID ] );

						$clsReq = new EBPLSTransactionRequirement( $this->m_dbLink );

					}

					// System config must have a requirement record with key CTC identifying Comm. Tax Certificate
					$clsReq->setData(TR_REQUIREMENT_CODE, "CTC" );
					$clsReq->setData(TR_TRANS_ID, $ret);
					
					// only update if ctc exist!!! 10/26/2004 9:32PM - stephen
					if ( $ctcRet > 0 ) {
						
						$clsReq->setData(TR_REF_NO, $clsCTC->getData(CTC_CODE));
						$clsReq->setData(TR_STATUS, "SUBMITTED" );
						$clsReq->update();
						
					}

				}

				$this->data_elems[TRANS_ID] = $ret;

				// create default business nature taxes
				if ( $permit->getPermitType() == PERMIT_TYPE_BUSINESS ) {

					$arrNature = $permit->getLineOfBusiness();
					for ( $i = 0 ; $i < count($arrNature) ; $i++ ) {

						// naturecode,capital,last_gross,nocommit (let this functio do the commit on db)
						$fee_nature_create = $this->addLineOfBusiness( $arrNature[$i][0], $arrNature[$i][1], $arrNature[$i][2], $creator, false );

						if ( $fee_nature_create < 0 ) {

							$this->setError($fee_nature_create, get_db_error() );
							//print_r($this->getError());
							break;

						}

					}

				} else {

					$fee_nature_create = 1;

				}

				// update application system code
				$ret_app_code = update_system_code( $this->m_dbLink, CODES_APP_COL, $app_code);

				if ( $ret_app_code < 0 ) {

					$this->setError( -1,"FATAL ERROR : failed to update app code on codes table. [ret_app_code = $ret_app_code, req_create = $req_create, fee_create= $fee_create, fee_nature_create = $fee_nature_create ]");

				}

				if ( $ret_app_code < 0 || $req_create < 0 || $fee_create < 0 || $fee_nature_create < 0 ) {

					$this->debug("FATAL ERROR : failed to create transaction child records. [ret_app_code = $ret_app_code, req_create = $req_create, fee_create= $fee_create, fee_nature_create = $fee_nature_create ]");
					$call_ret = -1;

				} else {

					$this->debug("Create transaction record ok.[trans_id=$ret]");
					$this->data_elems[TRANS_ID] = $ret;
					$call_ret = 1;

				}

			} else {

				$this->debug("FATAL ERROR : failed to create transaction record.");
				$this->setError( -1, get_db_error() ) ;
				$call_ret = -1;

			}

			if ( $call_ret < 0 ) {

				// rollback caused by error return on any of the sql executed above...
				ebpls_rollback_transaction( $this->m_dbLink );
				return $call_ret;

			} else {

				ebpls_commit_transaction( $this->m_dbLink );
				return $call_ret;

			}



		} else {

			$this->debug("Transaction create failed, $permit is not an instance of EBPLSPermit class!");
			$this->setError( -1, "Transaction create failed, $permit is not an instance of EBPLSPermit class!");
			return -1;

		}

	}

        function retireTransaction( $admin ) {

                // check if business type
                if ( !isset($this->data_elems[TRANS_ID]) ) {

                        $this->setError( -1, "Transaction not yet loaded, call loadTransaction" );
                        return $call_ret;

                } else {

                        $this->debug("Transaction create failed, $permit is not an instance of EBPLSPermit class!");
                        $this->setError( -1, "Transaction create failed, $permit is not an instance offore calling this function");
                        return -1;

                }

                return $this->_createRetireLOBTransaction( NULL, $admin, "" );

        }

	/****************************************************************
	 *
	 * Line of business functions, useful for BUSINESS permits only
	 *
	 ****************************************************************/
	function addLineOfBusiness( $nature_code, $capital, $last_gross = 0, $admin = "", $commit = true ) {

		// check if business type
		if ( !isset($this->data_elems[TRANS_ID]) ) {

			$this->setError( -1, "Transaction not yet loaded, call loadTransaction before calling this function!");
			$this->debug("Transaction not yet loaded, call loadTransaction before calling this function!");
			return -1;

		}

		// check if transaction is business type of permit
		if ( !isset($this->data_elems[TRANS_PERMIT_TYPE])  && $this->data_elems[TRANS_PERMIT_TYPE] != PERMIT_TYPE_BUSINESS ) {

			$this->setError( -4, "Can't use function for non-business enterprise type of transactions!");
			$this->debug("Can't use function for non-business enterprise type of transactions!");
			return -4;

		}

		// update transaction payment status with new line of business tax added
		$clsRef = new EBPLSSysRef( $this->m_dbLink, EBPLS_BUSINESS_NATURE );
		$result = $clsRef->select( $nature_code );

		if ( is_array( $result ) && count($result["result"]) > 0  ) {

			if ( $commit ) {

				ebpls_start_transaction( $this->m_dbLink );

			}

			// check if LOB exist already on transaction nature list
			// if exist return error else create LOB
			$clsNature = new EBPLSTransactionBusinessNature( $this->m_dbLink, false );
			$ret = $clsNature->exist( $this->data_elems[TRANS_BUSINESS_ID], $this->data_elems[TRANS_OWNER_ID], $nature_code );

			//$this->debug("exist ret = $ret");

			if ( $ret > 0 ) {

				$this->setError( -7, "addLineOfBusiness : error on creation of business nature already exist!" );
				$this->debug( "addLineOfBusiness : error on creation of business nature already exist!" );

				if ( $commit )
				{

					ebpls_rollback_transaction( $this->m_dbLink );

				}

				return -7;

			}

			// create LOB
			$clsNature->setData(TRANS_BUSNATURE_TRANS_ID, $this->data_elems[TRANS_ID] );
			$clsNature->setData(TRANS_BUSNATURE_BUSINESS_ID, $this->data_elems[TRANS_BUSINESS_ID] );
			$clsNature->setData(TRANS_BUSNATURE_OWNER_ID, $this->data_elems[TRANS_OWNER_ID] );
			$clsNature->setData(TRANS_BUSNATURE_CAPITAL_INVESTMENT, "$capital" );
			$clsNature->setData(TRANS_BUSNATURE_LAST_GROSS, "$last_gross" );
			$clsNature->setData(TRANS_BUSNATURE_STATUS, "PENDING" );
			$clsNature->setData(TRANS_BUSNATURE_BUSINESS_NATURE_CODE, $nature_code );
			$clsNature->setData(TRANS_BUSNATURE_BUSINESS_DESCRIPTION,  $result["result"][0]->getDescription() );

			$ret = $clsNature->add();

			if ( $ret < 0 ) {

				$this->setError( -5, "addLineOfBusiness : error on creation of business nature record" );
				$this->debug( "addLineOfBusiness : error on creation of business nature record" );

				if ( $commit )
				{
					ebpls_rollback_transaction( $this->m_dbLink );
				}

				return -5;

			}

			// add bus tax
			$clsLOBTax = new EBPLSTransactionFee( $this->m_dbLink, false );
			
			if ( $this->data_elems[ TRANS_TYPE ] == TRANS_TYPE_NEW ) {				
				$clsLOBTax->setData( TF_TAX_FEE_CODE , $result["result"][0]->getExtra() );
			} else if ( $this->data_elems[ TRANS_TYPE ] == TRANS_TYPE_RENEW ) {
				$clsLOBTax->setData( TF_TAX_FEE_CODE , $result["result"][0]->getExtra2() );	
			} else {
				
				if ( $commit )
				{
					ebpls_rollback_transaction( $this->m_dbLink );										
				}
				
				$this->setError( -6, "addLineOfBusiness : invalid trans type." );
				$this->debug( "addLineOfBusiness : invalid trans type." );
				return -10;
				
			}
			
			$clsLOBTax->setData( TF_TAX_BUSINESS_NATURE_CODE, $nature_code );
			$clsLOBTax->m_BusTaxCapital = $capital;
			$clsLOBTax->m_BusTaxLastGross = $last_gross;
			$this->debug("LOB Details " .  $result["result"][0]->getCode() . " = " . $result["result"][0]->getDescription() . " : " . $result["result"][0]->getExtra() . "!");

			$retAddFee = $this->addFee( $clsLOBTax, $admin, $user_level );

			if ( $retAddFee < 0 ) {

				$this->setError( -6, "addLineOfBusiness : error on creating tax payable for new line of business $nature_code." );
				$this->debug( "addLineOfBusiness : error on creating tax payable for new line of business $nature_code." );

				if ( $commit ) {

					ebpls_rollback_transaction( $this->m_dbLink );

				}

				return -6;

			}

			// attach LOB to busiess enterprise record
			$clsBus = new EBPLSEnterprise( $this->m_dbLink );

			// check first if LOB already exist, this will happen on renewals
			// if exsitent just update
			if ( $clsBus->viewBusinessNature( $this->getData(TRANS_BUSINESS_ID), $nature_code ) != -1 ) {

				// existing nature list should be updated
				$ret = $clsBus->updateBusinessNature( $nature_code, $capital, $last_gross, $admin );

			} else {

				// add line of business to business nature list
				$ret = $clsBus->addBusinessNature( $nature_code, $capital, $admin );

			}

			if ( ( $retAddFee = $clsBus->update( $this->getData(TRANS_BUSINESS_ID), $admin, $user_level ) ) < 0 ) {

				$this->debug( "addLineOfBusiness : error on creating line of business $nature_code on enterprise table." );
				$this->setError( -6, "addLineOfBusiness : error on creating line of business $nature_code on enterprise table." );

				if ( $commit  ){

					ebpls_rollback_transaction( $this->m_dbLink );

				}

				return -6;

			}

			// add linked tax/fees
			$ret = $this->_addLineOfBusinessTaxFees( $nature_code, $admin );
			
			if ( $ret < 0 ) {
				
				$this->debug("Unable to create linked tax/fees to Line of business $nature_code!");
				$this->setError( -7, "Unable to create linked tax/fees to Line of business $nature_code!");
				return -7;	
				
			}
			
			// if payment mode is set automatically recompute payment schedule
			if ( $this->isPaymentModeSet() ) {

				$this->setPaymentMode( $this->getData(TRANS_PAYMENT_MODE), $this->getData(TRANS_PAYMENT_TAXFEE_DIVIDED), $admin, $user_level );

			}

			if ( $commit ) {

				ebpls_commit_transaction( $this->m_dbLink );

			}

			return $ret;

		} else {

			$this->setError( -5, "addLineOfBusiness : unable to find linked line of business with code '$nature_code'." );
			return -5;

		}

	}
	
	// adds all tax/fees linked to a line of business to the list of payables
	function _addLineOfBusinessTaxFees( $nature_code, $admin ) {
	
	 	$clsBNatureTaxFees = new EBPLSBusinessNatureTaxes( $this->m_dbLink, false );	 	
	 	$arr_taxes = $clsBNatureTaxFees->getBusinessNatureTaxFees( $nature_code, 1, 100000000 );
	 	
	 	$ret = 0;
	 	
	 	if ( is_array($arr_taxes) ) {
	 		
	 		$result = $arr_taxes["result"];
	 		
	 		for ( $i = 0 ; $i < count($result) ; $i++ ) {
	 				 				 				 			
				$clsTransFee = new EBPLSTransactionFee( $this->m_dbLink, false );	
				$clsTransFee->setData(TF_TAX_FEE_CODE, $result[$i]->getData(EBPLS_BUSINESS_NATURE_TAXFEES_TAX_FEE_CODE) );
				$clsTransFee->setData(TF_TAX_TAXABLE_AMOUNT1, "0");
	 			$ret = $this->addFee( $clsTransFee, $admin, $user_level );	 			
	 			
	 			//log_err( "TAX FEE: ($ret) => " . $result[$i]->getData(EBPLS_BUSINESS_NATURE_TAXFEES_TAX_FEE_CODE) );
	 			
	 			if ( $ret < 0 ) {

					 // what should we do if tax already exist!!!!!
	 				 //break;
	 				 $ret = 1;
					
	 			}

	 		}
	 		
	 	}
		
		return $ret;
		
	}
	
	// can only be invoked if status is APPLICATION or ASSESSMENT and a NEW transaction
	// function should not work on RENEWAL
	function deleteLineOfBusiness( $nature_code ) {

		// check if business type
		if ( !isset($this->data_elems[TRANS_ID]) ) {

			$this->setError( -5, "Transaction not yet loaded, call loadTransaction before calling this function!");
			$this->debug("Transaction not yet loaded, call loadTransaction before calling this function!");
			return -5;

		}

		// check if transaction is business type of permit
		if ( !isset($this->data_elems[TRANS_PERMIT_TYPE])  && $this->data_elems[TRANS_PERMIT_TYPE] != PERMIT_TYPE_BUSINESS ) {

			$this->setError( -4, "Can't use function for non-business enterprise type of transactions!");
			$this->debug("Can't use function for non-business enterprise type of transactions!");
			return -4;

		}


		// check if transaction trans type is NEW
		if ( !isset($this->data_elems[TRANS_TYPE])  && $this->data_elems[TRANS_TYPE] != TRANS_TYPE_NEW ) {

			$this->setError( -8, "Can't use function for renewal type of transactions!");
			$this->debug("Can't use function for renewal type of transactions!");
			return -8;

		}

		// check if trans status is assessment or application, these are the only trans states from which deletLOB is allowed
		if ( !($this->getData(TRANS_TRANSACTION_STATUS) == "ASSESSMENT"  || $this->getData(TRANS_TRANSACTION_STATUS) == "APPLICATION"  ) ) {

			$this->setError( -5, "Can't use function on transactions if status is not assessment or application!");
			$this->debug( "Can't use function on transactions if status is not assessment or application!" );
			return -6;

		}


		// start delete transaction
		ebpls_start_transaction( $this->m_dbLink );

		$clsRef = new EBPLSSysRef( $this->m_dbLink, EBPLS_BUSINESS_NATURE );
		$result = $clsRef->select( $nature_code );

		if ( is_array( $result ) && count($result["result"]) > 0  ) {

			// check if LOB exist already on transaction nature list
			// if exist return error else create LOB
			$clsNature = new EBPLSTransactionBusinessNature( $this->m_dbLink, false );
			$ret = $clsNature->delete( $this->data_elems[TRANS_ID], $nature_code );

			if ( $ret < 0 ) {

				$this->setError( $ret, "deleteLineOfBusiness : delete failed!" );
				$this->debug( "deleteLineOfBusiness : delete failed!" );

				ebpls_rollback_transaction( $this->m_dbLink );

			} else {

				$clsLOBTax = new EBPLSTransactionFee( $this->m_dbLink, false );
				$retFee = $clsLOBTax->_delete( $this->data_elems[TRANS_ID], $result["result"][0]->getExtra(), $result["result"][0]->getCode(), $admin, $user_level );

				if ( $retFee < 0 ) {

					$this->setError( -5, "deleteLineOfBusiness : error on creating tax payable for new line of business $nature_code." );
					ebpls_rollback_transaction( $this->m_dbLink );
					return $retFee;

				}

				// delete line of business to business nature list
				$clsBus = new EBPLSEnterprise( $this->m_dbLink );
				$clsBus->deleteBusinessNature( $nature_code );

				if ( $clsBus->update( $this->getData(TRANS_BUSINESS_ID), $admin, $user_level ) < 0 ) {

					$this->debug( "deleteLineOfBusiness : error on deleting line of business $nature_code on enterprise table." );
					$this->setError( -5, "deleteLineOfBusinessdeleteLineOfBusiness : error on deleting line of business $nature_code on enterprise table." );
					ebpls_rollback_transaction( $this->m_dbLink );
					return $retAddFee;

				}

				ebpls_commit_transaction( $this->m_dbLink );

			}

			return $ret;

		} else {

			$this->setError( -5, "deleteLineOfBusiness : unable to find linked line of business with code '$nature_code'." );
			return -1;

		}

	}

	function updateLineOfBusiness( $nature_code, $capital, $last_gross = 0, $status = "PENDING" ) {

		// check if business type
		if ( !isset($this->data_elems[TRANS_ID]) ) {

			$this->setError( -5, "Transaction not yet loaded, call loadTransaction before calling this function!");
			$this->debug("Transaction not yet loaded, call loadTransaction before calling this function!");
			return -5;

		}

		// check if transaction is business type of permit
		if ( !isset($this->data_elems[TRANS_PERMIT_TYPE])  && $this->data_elems[TRANS_PERMIT_TYPE] != PERMIT_TYPE_BUSINESS ) {

			$this->setError( -4, "Can't use function for non-business enterprise type of transactions!");
			$this->debug("Can't use function for non-business enterprise type of transactions!");
			return -4;

		}

		if ( !($this->getData(TRANS_TRANSACTION_STATUS) == "ASSESSMENT"  || $this->getData(TRANS_TRANSACTION_STATUS) == "APPLICATION"  ) ) {

			$this->setError( -5, "Can't use function on transactions if status is not assessment or application!");
			$this->debug("Can't use function on transactions if status is not assessment or application!");
			return -6;

		}


		// update transaction payment status with new line of business tax added
		$clsRef = new EBPLSSysRef( $this->m_dbLink, EBPLS_BUSINESS_NATURE );
		$result = $clsRef->select( $nature_code );

		if ( is_array( $result ) && count($result["result"]) > 0  ) {

			ebpls_start_transaction( $this->m_dbLink );

			// check if LOB exist already on transaction nature list
			// if exist return error else create LOB
			$clsNature = new EBPLSTransactionBusinessNature( $this->m_dbLink, false );
			$clsNature->setData(TRANS_BUSNATURE_CAPITAL_INVESTMENT, $capital );
			$clsNature->setData(TRANS_BUSNATURE_LAST_GROSS, $last_gross );
			$clsNature->setData(TRANS_BUSNATURE_STATUS, $status );
			$ret = $clsNature->update( $this->data_elems[TRANS_ID], $nature_code );

			if ( $ret < 0 ) {

				$this->setError( $ret, "updateLineOfBusiness :  error on creation of business nature record." );
				$this->debug( "updateLineOfBusiness : error on creation of business nature record" );

				ebpls_rollback_transaction( $this->m_dbLink );

			} else {

				// delete line of business to business nature list!!!
				$clsBus = new EBPLSEnterprise( $this->m_dbLink );
				$clsBus->updateBusinessNature( $nature_code, $capital, $last_gross, $status );

				if ( $clsBus->update( $this->getData(TRANS_BUSINESS_ID), $admin, $user_level ) < 0 ) {

					$this->debug( "updateLineOfBusiness : error on deleting line of business $nature_code on enterprise table." );
					$this->setError( -5, "updateLineOfBusiness : error on deleting line of business $nature_code on enterprise table." );
					ebpls_rollback_transaction( $this->m_dbLink );
					return $retAddFee;

				}

				$clsTransFee = new EBPLSTransactionFee( $this->m_dbLink, false  );

				if ( $clsTransFee->view( $this->data_elems[TRANS_ID], $result["result"][0]->getExtra() ) > 0 ) {

					$clsLOBTax = new EBPLSTransactionFee( $this->m_dbLink, false );
					$clsLOBTax->setData( TF_TAX_FEE_CODE , $result["result"][0]->getExtra() );
					$clsLOBTax->setData( TF_TAX_BUSINESS_NATURE_CODE, $nature_code );
					$clsLOBTax->m_BusTaxCapital = $capital;
					$clsLOBTax->m_BusTaxLastGross = $last_gross;
					$this->debug("LOB Details " .  $result["result"][0]->getCode() . " = " . $result["result"][0]->getDescription() . " : " . $result["result"][0]->getExtra() . "!");

					$retUpdateFee = $this->updateFee(  $clsTransFee->getData(TF_FEE_ID), $clsLOBTax, $admin, $user_level );

					if ( $retUpdateFee < 0 ) {

						$this->debug( "updateLineOfBusiness : error on update line of business fee." );
						$this->setError( -5, "updateLineOfBusiness : error on updating line of business fee." );
						ebpls_rollback_transaction( $this->m_dbLink );
						return $retUpdateFee;

					}

					// if payment mode is set automatically recompute payment schedule
					if ( $this->isPaymentModeSet() ) {

						$this->setPaymentMode( $this->getData(TRANS_PAYMENT_MODE), $this->getData(TRANS_PAYMENT_TAXFEE_DIVIDED), $admin, $user_level );

					}

					ebpls_commit_transaction( $this->m_dbLink );

				} else {

					ebpls_rollback_transaction( $this->m_dbLink );

				}

			}

		} else {

			$this->setError( -2, "Cant load nature code $nature_code!");
			$this->debug( "Cant load nature code $nature_code!");
			return -2;

		}

		return $ret;

	}

	// calling retireLineOfBusiness with nature code parameter = NULL will retire all Line of busisness linked to
	// the current transaction...
	function retireLineOfBusiness( $nature_code, $admin = "", $user_level = "", $commit = true ) {

		// check if trans is loaded
		if ( !isset($this->data_elems[TRANS_ID]) ) {

			$this->setError( -5, "Transaction not yet loaded, call loadTransaction before calling this function!");
			$this->debug("Transaction not yet loaded, call loadTransaction before calling this function!");
			return -5;

		}

		// check if transaction is a business type of permit
		if ( !isset($this->data_elems[TRANS_PERMIT_TYPE])  && $this->data_elems[TRANS_PERMIT_TYPE] != PERMIT_TYPE_BUSINESS ) {

			$this->setError( -4, "Can't use function for non-business enterprise type of transactions!");
			$this->debug("Can't use function for non-business enterprise type of transactions!");
			return -4;

		}

		if ( $this->getData(TRANS_TRANSACTION_STATUS) == TRANS_STATUS_APPLICATION || $this->getData(TRANS_TRANSACTION_STATUS) == TRANS_STATUS_ASSESSMENT ) {

			$this->setError( -5, "Can't use function on transactions if status is assessment or application, use deleteLineOfBusiness instead!");
			$this->debug("Can't use function on transactions if status is assessment or application, use deleteLineOfBusiness instead!");
			return -6;

		}


		$clsTrans = $this->_createRetireLOBTransaction( $nature_code, $admin, $user_level );

	}

	// internally called
	function _createRetireLOBTransaction( $nature_code, $creator, $user_level ) {

		$clsTrans = new EBPLSTransaction( $this->m_dbLink );

		// insert to transaction table
		$clsTrans->data_elems[ TRANS_TYPE ] = TRANS_TYPE_RETIREMENT;
		$clsTrans->data_elems[ TRANS_PAYMENT_STATUS ] = TPS_PAYMENT_STATE_UNPAID;
		$clsTrans->data_elems[ TRANS_OWNER_ID] = $this->getData( TRANS_OWNER_ID );
		$clsTrans->data_elems[ TRANS_BUSINESS_ID ] = $this->getData( TRANS_BUSINESS_ID );
		$clsTrans->data_elems[ TRANS_PERMIT_TYPE ] = "BUS";

		$this->debug("BUS ID : " . $clsTrans->data_elems[ TRANS_BUSINESS_ID ] );

		ebpls_start_transaction( $clsTrans->m_dbLink );

		// get next transaction code
		$app_code  = get_next_system_code( $this->m_dbLink, CODES_APP_COL );

		$clsTrans->data_elems[ TRANS_APPLICATION_CODE ] = $app_code;
		$clsTrans->data_elems[ TRANS_TRANSACTION_DATE ] = date("Y-m-d H:i:s");
		$clsTrans->data_elems[ TRANS_APPLICATION_DATE ] = date("Y-m-d H:i:s");
		$clsTrans->data_elems[ TRANS_ADMIN_USER ] = $creator;
		$clsTrans->data_elems[ TRANS_TRANSACTION_STATUS ] = TRANS_STATUS_APPLICATION;
		$clsTrans->data_elems[ TRANS_APPLICATION_CODE ] = $app_code;

		$strValues = $clsTrans->data_elems;

		$ret = ebpls_insert_data( $this->m_dbLink, EBPLS_TRANSACTION_TABLE, $strValues );

		if ( $ret > 0 ) {

			$clsTrans->data_elems[TRANS_ID] = $ret;

			// create default application requirements
			$req_create = create_permit_requirements ( $this->m_dbLink,  $ret, 0, "BUS", $creator, "APP", TRANS_TYPE_RETIREMENT );
			if ( $req_create < 0 ) $this->setError(-1,get_db_error());

			// create default fees
			$fee_create = create_permit_requirements ( $this->m_dbLink,  $ret, 0, "BUS", $creator, "FEE", TRANS_TYPE_RETIREMENT );
			if ( $fee_create < 0 ) $this->setError(-1,get_db_error());

			// create default payable taxes
			$tax_nature_create = create_permit_requirements ( $this->m_dbLink,  $ret, 0, "BUS", $creator, "TAX", TRANS_TYPE_RETIREMENT );

			if ( $tax_nature_create < 0 ) {

				$this->setError( -1, get_db_error() );

			}

			

			if ( !is_array($nature_code) ) {

				$nature_code = array($nature_code);

			}

			for ( $i = 0 ; $i < count($nature_code); $i++ ) {
				
				
				// check if there are already pending retirement business nature of this kind
				$clsExistNature = new EBPLSTransactionBusinessNature( $clsTrans->m_dbLink, false );
				
				if ( $clsExistNature->exist( $this->data_elems[TRANS_BUSINESS_ID], $this->data_elems[TRANS_OWNER_ID], $nature_code[$i], "RETIREMENT" ) > 0 ) {
			
					$this->debug( "_createRetireLOBTransaction : retirement of " . $nature_code[$i] . " already exist." );
					$this->setError( -19, "_createRetireLOBTransaction : retirement of " . $nature_code[$i] . " already exist." );
					ebpls_rollback_transaction( $this->m_dbLink );
					return -19;
			
				}

				$clsNatureView = new EBPLSTransactionBusinessNature( $this->m_dbLink, false );

				if ( $clsNatureView->view( $this->data_elems[TRANS_ID], $nature_code[$i]) > 0 ) {
					
					$clsFee = new EBPLSTransactionFee( $this->m_dbLink, false);
					
					if ( $clsFee->view( $this->data_elems[TRANS_ID], NULL, $nature_code[$i]) > 0 ) {
					
						$clsPayables = new EBPLSTransactionPaymentSchedule( $this->m_dbLink, false );
						
						$payables_due = $clsPayables->getTotalAmountDue( $this->data_elems[TRANS_ID], $clsFee->getData(TF_TAX_FEE_CODE) );
						
						if ( $payables_due > 0 ) {
						
							$this->debug( "_createRetireLOBTransaction : retirement of " . $nature_code[$i] . " failed, current line of bus have pending payables." );
							$this->setError( -21, "_createRetireLOBTransaction : retirement of " . $nature_code[$i] . " failed, current line of bus have pending payables." );
							ebpls_rollback_transaction( $this->m_dbLink );
							return -21;	
							
						}
						
					} else {
						
						$this->debug( "_createRetireLOBTransaction : retirement of " . $nature_code[$i] . " failed, nature code not linked to current transaction." );
						$this->setError( -21, "_createRetireLOBTransaction : retirement of " . $nature_code[$i] . " failed, nature code not linked to current transaction." );
						ebpls_rollback_transaction( $this->m_dbLink );
						return -21;	
						
					}

					$clsNature = new EBPLSTransactionBusinessNature( $clsTrans->m_dbLink, false );
					$clsNature->setData(TRANS_BUSNATURE_TRANS_ID, $clsTrans->data_elems[TRANS_ID] );
					$clsNature->setData(TRANS_BUSNATURE_BUSINESS_ID, $clsTrans->data_elems[TRANS_BUSINESS_ID] );
					$clsNature->setData(TRANS_BUSNATURE_OWNER_ID, $clsTrans->data_elems[TRANS_OWNER_ID] );
					$clsNature->setData(TRANS_BUSNATURE_STATUS, "RETIREMENT" );
					$clsNature->setData(TRANS_BUSNATURE_CAPITAL_INVESTMENT, "0.0" );
					$clsNature->setData(TRANS_BUSNATURE_LAST_GROSS, "0.0" );
					$clsNature->setData(TRANS_BUSNATURE_BUSINESS_NATURE_CODE, $clsNatureView->getData(TRANS_BUSNATURE_BUSINESS_NATURE_CODE) );
					$clsNature->setData(TRANS_BUSNATURE_BUSINESS_DESCRIPTION, $clsNatureView->getData(TRANS_BUSNATURE_BUSINESS_DESCRIPTION) );

					$fee_nature_create = $clsNature->add();

					if ( $fee_nature_create < 0 ) {

						$this->setError( $ret, $clsNature->getError() );
						$fee_nature_create = -12;

					} else {

						$clsNatureView2 = new EBPLSTransactionBusinessNature( $this->m_dbLink, false );
						$clsNatureView2->setData(TRANS_BUSNATURE_STATUS,"RETIRED");

						if ( ( $fee_nature_create = $clsNatureView2->update( $this->data_elems[TRANS_ID], $clsNatureView->getData(TRANS_BUSNATURE_BUSINESS_NATURE_CODE) ) ) < 0 ) {

							//print_r( $clsNatureView2->getError() );

							$this->setError( $ret, $clsNatureView2->getError() );
							ebpls_rollback_transaction( $this->m_dbLink );
							$fee_nature_create = -11;

						}

						// remove LOB from enterprise nature records
						// delete line of business from business nature list
						$clsBus = new EBPLSEnterprise( $this->m_dbLink );
						$clsBus->deleteBusinessNature( $nature_code[$i] );

						if ( $clsBus->update( $this->getData(TRANS_BUSINESS_ID), $admin, $user_level ) < 0 ) {

							$this->debug( "deleteLineOfBusiness : error on deleting line of business $nature_code[$i] on enterprise table." );
							$this->setError( -15, "deleteLineOfBusinessdeleteLineOfBusiness : error on deleting line of business $nature_code[$i] on enterprise table." );
							ebpls_rollback_transaction( $this->m_dbLink );
							return $retAddFee;

						}

						// update trans
						$clsNature2 = new EBPLSTransactionBusinessNature( $clsTrans->m_dbLink, false );
						$ret1 = $clsNature2->getList( $this->data_elems[TRANS_ID], "PENDING" );
						$ret2 = $clsNature2->getList( $this->data_elems[TRANS_ID], "PROCESSED" );

						if ( $ret1 < 0 && $ret2 < 0 ) {

							$this->_setTransToRetire( $admin );

						}

					}

				} else {

					// unable to find nature code
					$this->setError(-10, "Unable to find nature code $nature_code[$i] on current transaction!");
					$fee_nature_create = -10;
					break;

				}

			}


			// update application system code
			$ret_app_code = update_system_code( $this->m_dbLink, CODES_APP_COL, $app_code);

			if ( $ret_app_code < 0 ) {

				$this->setError( -1,"FATAL ERROR : failed to update app code on codes table. [ret_app_code = $ret_app_code, req_create = $req_create, fee_create= $fee_create, fee_nature_create = $fee_nature_create ]");

			}

			if ( $ret_app_code < 0 || $req_create < 0 || $fee_create < 0 || $fee_nature_create < 0 ) {

				$this->debug("FATAL ERROR : failed to create transaction child records. [ret_app_code = $ret_app_code, req_create = $req_create, fee_create= $fee_create, fee_nature_create = $fee_nature_create ]");
				$call_ret = -1;

			} else {

				$this->debug("Create transaction record ok.[trans_id=$ret]");
				$clsTrans->data_elems[TRANS_ID] = $ret;
				$call_ret = 1;

			}

		} else {

			$this->debug("FATAL ERROR : failed to create transaction record.");
			$this->setError( -1, get_db_error() ) ;
			$call_ret = -1;

		}

		if ( $call_ret < 0 ) {

			// rollback caused by error return on any of the sql executed above...
			ebpls_rollback_transaction( $this->m_dbLink );
			return -1;

		}

		ebpls_commit_transaction( $this->m_dbLink );
		return $clsTrans;

	}


	function getLineOfBusiness()
	{

		$clsNatureList = new EBPLSTransactionBusinessNature( $this->m_dbLink, false );
		$records = $clsNatureList->getList( $this->data_elems[TRANS_ID] );

		return $records;

	}

	/**************************************************
	 * Application Requirement Functions
	 **************************************************/
	function addRequirement( &$req_obj,  $admin, $user_level ) {

		if ( $user_level != TRANS_LEVEL_APPLICATION_OFFICER && $user_level != TRANS_LEVEL_ADMIN_OFFICER && $user_level != TRANS_LEVEL_ROOT_OFFICER) {

			$this->debug("addRequirement Failed, user level $user_level of $admin is not allowed.");
			return -1;

		}

		if ( !is_numeric( $this->getData(TRANS_ID) ) ) {

			$this->debug("addRequirement Failed, transaction not loaded, load by invoking loadTransaction.");
			return -1;

		}

		if ( $this->getData( TRANS_TRANSACTION_STATUS ) != TRANS_STATUS_APPLICATION ) {

			$this->debug("addRequirement Failed, transaction status already " . $this->getData( TRANS_TRANSACTION_STATUS ) );
			return -1;
		}

		if ( is_a($req_obj,"EBPLSTransactionRequirement" ) ) {

			$req_obj->setData(TR_TRANS_ID,$this->getData(TRANS_ID));
			$req_obj->setData(TR_PERMIT_TYPE,$this->getData(TRANS_PERMIT_TYPE));
			$req_obj->setData(TR_PERMIT_ID,$this->getData(TRANS_PERMIT_NO));
			$req_obj->setData(TR_LAST_UPDATED_BY,$admin);

			$req_add_ret = $req_obj->add();

			if ( $req_add_ret < 0 ) {

				$this->debug("Create Requirement Failed.");
				$err = $req_obj->getError();
				$this->setError( -2, $err[0]["err_mesg"] );
				return -2;

			} else {

				$this->debug("Create Requirement Ok.");
				return 1;

			}

		} else {

			$this->debug("Create Requirement Failed, passed $req_obj not an instance of EBPLSTransactionRequirement.");
			$this->setError(-1,"Create Requirement Failed, passed $req_obj not an instance of EBPLSTransactionRequirement.");
			return -1;

		}

	}

	function updateRequirement( &$req_obj, $admin, $user_level ) {

		if ( $user_level != TRANS_LEVEL_APPLICATION_OFFICER && $user_level != TRANS_LEVEL_ADMIN_OFFICER && $user_level != TRANS_LEVEL_ROOT_OFFICER) {

			$this->debug("updateRequirement Failed, user level $user_level of $admin is not allowed.");
			return -1;

		}


		if ( !is_numeric( $this->getData(TRANS_ID) ) ) {

			$this->debug("updateRequirement Failed, transaction not loaded, load by invoking loadTransaction.");
			return -1;

		}

		if ( $this->getData( TRANS_TRANSACTION_STATUS ) != TRANS_STATUS_APPLICATION ) {

			$this->debug("updateRequirement Failed, transaction status already " . $this->getData( TRANS_TRANSACTION_STATUS ) );
			return -1;
		}


		if ( is_a( $req_obj, "EBPLSTransactionRequirement" )  ) {

			$req_obj->setData(TR_TRANS_ID,$this->getData(TRANS_ID));
			$req_obj->setData(TR_PERMIT_TYPE,$this->getData(TRANS_PERMIT_TYPE));
			$req_obj->setData(TR_PERMIT_ID,$this->getData(TRANS_PERMIT_NO));
			$req_obj->setData(TR_LAST_UPDATED_BY,$admin);

			$req_add_ret = $req_obj->update();

			if ( $req_add_ret < 0 ) {

				$this->debug("Update Requirement Failed.");
				return -1;

			} else {

				$this->debug("Update Requirement Ok.");
				return 1;

			}

		} else {

			$this->debug("Update Requirement Failed, passed $req_obj not an instance of EBPLSTransactionRequirement.");
			return -1;

		}

	}

	function deleteRequirement( &$req_obj, $admin, $user_level ) {

		if ( $user_level != TRANS_LEVEL_APPLICATION_OFFICER && $user_level != TRANS_LEVEL_ADMIN_OFFICER && $user_level != TRANS_LEVEL_ROOT_OFFICER) {

			$this->debug("deleteRequirement Failed, user level $user_level of $admin is not allowed.");
			return -1;

		}

		if ( !is_numeric( $this->getData(TRANS_ID) ) ) {

			$this->debug("deleteRequirement Failed, transaction not loaded, load by invoking loadTransaction.");
			return -1;

		}

		if ( $this->getData( TRANS_TRANSACTION_STATUS ) != TRANS_STATUS_APPLICATION ) {

			$this->debug("deleteRequirement Failed, transaction status already " . $this->getData( TRANS_TRANSACTION_STATUS ) );
			return -1;
		}


		if ( is_a($req_obj,"EBPLSTransactionRequirement" )  ) {

			$req_obj->setData(TR_TRANS_ID,$this->getData(TRANS_ID));
			$req_obj->setData(TR_PERMIT_TYPE,$this->getData(TRANS_PERMIT_TYPE));
			$req_obj->setData(TR_PERMIT_ID,$this->getData(TRANS_PERMIT_NO));

			$req_add_ret = $req_obj->delete();

			if ( $req_add_ret < 0 ) {

				$this->debug("Delete Requirement Failed.");
				return -1;

			} else {

				$this->debug("Delete Requirement Ok.");
				return 1;

			}

		} else {

			$this->debug("Delete Requirement Failed, passed $req_obj not an instance of EBPLSTransactionRequirement.");
			return -1;

		}

	}

	function getRequirementList( $status = NULL ) {

		if ( !is_numeric( $this->getData(TRANS_ID) ) ) {

			$this->setError(-1,"getRequirementList Failed, transaction not loaded, load by invoking loadTransaction.");
			$this->debug("getRequirementList Failed, transaction not loaded, load by invoking loadTransaction.");
			return -1;

		}

		$clsTransReq = new EBPLSTransactionRequirement($this->m_dbLink);
		$requirements = $clsTransReq->listReq($this->getData(TRANS_ID), $status );

		return $requirements;

	}

	/*********************************************
	 *
	 * ASSESSMENT OFFICER FUNCTIONS
	 *
	 *
	 *********************************************/

	 /*********************************************
	  * Tax/Fees payment Manipulation Functions
	  *********************************************/

	 /*********************************************
	  *
	  * Sets Payment mode of tax/fees payable by application, function automatically defines payment schedule of all payables of an applicant
	  *
	  * NOTE : - method can be invoked by ASSESSMENT OFFICER only if transaction state is ASSESSMENT
	  *	   - method can be invoked by PAYMENT OFFICER only if transaction state is PAYMENT
	  *
	  *********************************************/
	 function setPaymentMode( $payment_mode, $bSeparateTaxesFromFees, $admin, $user_level ) {

	 	$this->debug(" call setPaymentMode( $payment_mode, $bSeparateTaxesFromFees, $admin, $user_level )");

		if ( $this->getData(TRANS_TRANSACTION_STATUS)!= TRANS_STATUS_ASSESSMENT && $this->getData(TRANS_TRANSACTION_STATUS) != TRANS_STATUS_PAYMENT ){

			$this->debug("setPaymentMode Failed, user level $user_level of $admin is not allowed to use this function.");
			$this->setError( -1, "setPaymentMode Failed, user level $user_level of $admin is not allowed to use this function.");
			return -1;

		}

		if ( $this->getData(TRANS_TRANSACTION_STATUS) == TRANS_STATUS_ASSESSMENT && $user_level != TRANS_LEVEL_ASSESSMENT_OFFICER && $user_level != TRANS_LEVEL_ADMIN_OFFICER && $user_level != TRANS_LEVEL_ROOT_OFFICER ) {

			$this->debug("setPaymentMode Failed, user level $user_level of $admin is not allowed.");
			$this->setError( -1, "setPayment mode failed, user level $user_level not allowed.");
			return -1;

		}

		if ( $this->getData(TRANS_TRANSACTION_STATUS) == TRANS_STATUS_PAYMENT && $user_level != TRANS_LEVEL_PAYMENT_OFFICER && $user_level != TRANS_LEVEL_ADMIN_OFFICER && $user_level != TRANS_LEVEL_ROOT_OFFICER ) {

			$this->debug("setPaymentMode Failed, user level $user_level of $admin is not allowed.");
			$this->setError( -1, "setPayment mode failed, user level $user_level not allowed.");
			return -1;

		}

		if ( $payment_mode != TRANS_PAYMENT_MODE_MONTHLY &&  $payment_mode != TRANS_PAYMENT_MODE_QUARTERLY &&  $payment_mode != TRANS_PAYMENT_MODE_SEMIANNUAL &&
			 $payment_mode != TRANS_PAYMENT_MODE_ANNUAL &&  $payment_mode != TRANS_PAYMENT_MODE_OTHERS ) {

			$this->setError( -2, "Invalid payment mode passed $payment_mode.");
			return -2;

		}

		/*
		// 8/31/2004 11:59PM
		// check first if payment mode is valid given the current date of setting payment mode
		switch( $payment_mode ){
			case TRANS_PAYMENT_MODE_ANNUAL :
				{


				} break;
			case TRANS_PAYMENT_MODE_SEMIANNUAL :
				{

					if ( date("m") > 6 ) {

						$this->debug( "Payment mode $payment_mode requested not valid since current month exceeds sixth month of the year.");
						$this->setError( -3, "Payment mode $payment_mode requested not valid since current month exceeds sixth month of the year.");
						return -3;

					}

				} break;
			case TRANS_PAYMENT_MODE_QUARTERLY :
				{

					if ( date("m") > 3 ) {

						$this->debug( "Payment mode $payment_mode requested not valid since current month exceeds sixth month of the year.");
						$this->setError( -3, "Payment mode $payment_mode requested not valid since current month exceeds sixth month of the year.");
						return -3;

					}

				} break;
			case TRANS_PAYMENT_MODE_MONTHLY :
				{



				} break;

		}
		*/

		// check if payment mode is set
		if ( !$this->isPaymentModeSet( $this->getData(TRANS_ID) ) ) {

			// process the setting of payment list
			$clsTPS = new EBPLSTransactionPaymentSchedule ( $this->m_dbLink );

			echo "createPaymentSchedule( &$trans_obj, $payment_mode, $bSeparateTaxesFromFees, $bIsUpdate, $admin )<HR>";
			
			if ( $clsTPS->createPaymentSchedule( $this, $payment_mode, $bSeparateTaxesFromFees, false, $admin ) > 0 ) {

				$this->debug( "setPaymentMode create Ok1.");
				return 1;

			} else {

				$this->setError( -1, "setPaymentMode create failed1.");
				return -1;

			}

		} else {

			// process the setting of payment list
			$clsTPS = new EBPLSTransactionPaymentSchedule ( $this->m_dbLink );

			if ( $clsTPS->updatePaymentSchedule( $this, $payment_mode, $bSeparateTaxesFromFees, true, $admin ) > 0 ) {

				$this->debug( "setPaymentMode update Ok2.");
				return 1;

			} else {

				$this->setError( -1, "setPaymentMode update failed2.");
				return -1;

			}

		}

	 }

	 /*************************************************
	  *
	  * Updates payment due date of a given payment schedule.
	  * All other fields of payment schedule records are system updated and have readonly access.
	  *
	  *************************************************/

	 function updatePaymentDueDate( $payment_id, $due_date, $remarks, $admin ){

	 	$clsTPS = new EBPLSTransactionPaymentSchedule( $this->m_dbLink, false );

		if ( $clsTPS->updateDueDate( $payment_id, $due_date, $remarks, $admin ) > 0 ) {

			return 1;

		} else {

			return 0;

		}

	 }

	 /*************************************************
	  *
	  * Lists all payment schedule of this instance of transaction
	  *
	  *************************************************/
	 function getPaymentSchedule( $payment_state = NULL, $orderKey = NULL, $bDesc ) {

	 	$clsTPS = new EBPLSTransactionPaymentSchedule( $this->m_dbLink, false );

	 	return $clsTPS->listPaymentSchedule( $this->getData(TRANS_ID), $payment_state , $orderKey, $bDesc );

	 }

	 /**************************************************
	  *
	  * Tax/Fee Manipulation Functions
	  *
	  **************************************************/
	 function addFee( &$fee_obj, $admin, $user_level ) {

		if ( !is_numeric( $this->getData(TRANS_ID) ) ) {

			$this->debug("addFee Failed, transaction not loaded, load by invoking loadTransaction.");
			$this->setError(-1,"addFee Failed, transaction not loaded, load by invoking loadTransaction.");
			return -1;

		}

		/*
		// 10/19/2004 8:30AM - removed to allow addFee regardless of transaction state! - stephen
		if ( $this->getData( TRANS_TRANSACTION_STATUS ) != TRANS_STATUS_ASSESSMENT && $this->getData( TRANS_TRANSACTION_STATUS ) != TRANS_STATUS_APPLICATION ) {

			$this->debug("addFee Failed, transaction status already " . $this->getData( TRANS_TRANSACTION_STATUS ) );
			$this->setError(-1,"addFee Failed, transaction status already " . $this->getData( TRANS_TRANSACTION_STATUS ) );
			return -1;

		}
		*/

		if ( is_a($fee_obj,"EBPLSTransactionFee" ) ) {

			if ( ( $ret = $fee_obj->add( $this, $admin ) ) > 0 ) {

				// if payment mode is set automatically recompute payment schedule
				if ( $this->isPaymentModeSet() ) {

					$this->setPaymentMode( $this->getData(TRANS_PAYMENT_MODE), $this->getData(TRANS_PAYMENT_TAXFEE_DIVIDED), $admin, $user_level );

				}


				$this->debug("Add Fee Ok.[$ret]");

			} else {

				$this->debug("Add Fee Failed $ret.");
				return -1;

			}

		} else {

			$this->debug("Add Fee Failed.");
			return -1;

		}

	}

	function updateFee( $fee_id, &$fee_obj, $admin, $user_level ) {


		if ( !is_numeric( $this->getData(TRANS_ID) ) ) {

			$this->debug("updateFee Failed, transaction not loaded, load by invoking loadTransaction.");
			$this->setError(-1,"updateFee Failed, transaction not loaded, load by invoking loadTransaction.");
			return -1;

		}

		if ( $this->getData( TRANS_TRANSACTION_STATUS ) != TRANS_STATUS_APPLICATION && $this->getData( TRANS_TRANSACTION_STATUS ) != TRANS_STATUS_ASSESSMENT ) {

			$this->debug("updateFee Failed, transaction status already " . $this->getData( TRANS_TRANSACTION_STATUS ) );
			$this->setError(-1,"updateFee Failed, transaction status already " . $this->getData( TRANS_TRANSACTION_STATUS ) );
			return -1;

		}

		if ( is_a($fee_obj,"EBPLSTransactionFee" ) ) {

			$this->debug("update( $fee_id, $admin, $user_level )");

			if ( ( $ret = $fee_obj->update( $fee_id, $admin, $user_level ) ) > 0 ) {

				// if payment mode is set, automatically recompute payment schedule
				if ( $this->isPaymentModeSet() ) {

					$this->setPaymentMode( $this->getData(TRANS_PAYMENT_MODE), $this->getData(TRANS_PAYMENT_TAXFEE_DIVIDED), $admin, $user_level );

				}

				// if fee is a line of business tax then update capital investment and/or last years gross


				$this->debug("Update Fee Ok.");

			} else {

				$this->debug("Update Fee Failed $ret.");
				$this->setError($ret,"Update Fee Failed $ret.");

			}

			return $ret;

		} else {

			$this->debug("Update Fee Failed, fee_obj not instance of EBPLSTransactionFee.");
			$this->setError( -1, "Update Fee Failed, fee_obj not instance of EBPLSTransactionFee.");
			return -1;

		}

	}


	function deleteFee( $fee_id, $admin, $user_level ) {

		if ( !is_numeric( $this->getData(TRANS_ID) ) ) {

			$this->debug("deleteFee Failed, transaction not loaded, load by invoking loadTransaction.");
			$this->setError(-1,"deleteFee Failed, transaction not loaded, load by invoking loadTransaction.");

			return -1;

		}

		if (  !( $this->getData( TRANS_TRANSACTION_STATUS ) == TRANS_STATUS_APPLICATION ||
			$this->getData( TRANS_TRANSACTION_STATUS ) == TRANS_STATUS_ASSESSMENT ) )
		{

			$this->debug("deleteFee Failed, transaction status already " . $this->getData( TRANS_TRANSACTION_STATUS ) );
			$this->setError(-1,"deleteFee Failed, transaction status already " . $this->getData( TRANS_TRANSACTION_STATUS ) );

			return -1;

		}


		$this->debug("delete( $fee_id, $admin, $user_level )");

		$fee_obj = new EBPLSTransactionFee( $this->m_dbLink );

		if ( ( $ret = $fee_obj->delete( $fee_id, $admin, $user_level ) ) > 0 ) {

			// if payment mode is set automatically recompute payment schedule
			if ( $this->isPaymentModeSet() ) {

				$this->setPaymentMode( $this->getData(TRANS_PAYMENT_MODE), $this->getData(TRANS_PAYMENT_TAXFEE_DIVIDED), $admin, $user_level );

			}

			$this->debug("Update Fee Ok.");

		} else {

			$this->debug("Update Fee Failed $ret.");

		}

		return $ret;

	}

	function getFeesList( ) {

		if ( !is_numeric( $this->getData(TRANS_ID) ) ) {

			$this->debug("getFeesListt Failed, transaction not loaded, load by invoking loadTransaction.");
			$this->setError(-1,"getFeesListt Failed, transaction not loaded, load by invoking loadTransaction.");
			return -1;

		}

		$clsTransReq = new EBPLSTransactionFee($this->m_dbLink);
		$fees_list = $clsTransReq->listFee($this->getData(TRANS_ID));

		return $fees_list;

	}


	/**************************************************
	 *
	 * PAYMENT OFFICER FUNCTIONS
	 *
	 **************************************************/

	function getTotalPayableAmount( $admin, $user_level ) {

		if ( $this->getData(TRANS_TRANSACTION_STATUS) == TRANS_STATUS_PAYMENT && $user_level != TRANS_LEVEL_PAYMENT_OFFICER && $user_level != TRANS_LEVEL_ADMIN_OFFICER && $user_level != TRANS_LEVEL_ROOT_OFFICER ) {

			$this->debug("addCashPayment Failed, user level $user_level of $admin is not allowed.");
			$this->setError( -1, "addCashPayment mode failed, user level $user_level not allowed.");
			return -1;

		}

		$clsTPS = new EBPLSTransactionPaymentSchedule( $this->m_dbLink );
		$amount_paid = $clsTPS->getTotalAmountPaid( $this->getData(TRANS_ID ) );
		$amount_due = $clsTPS->getTotalAmountDue( $this->getData(TRANS_ID ) );		

		return (floatval($amount_due) - floatval($amount_paid));

	}

	function addCashPayment( $amount, $admin, $user_level, $bCommit = true ) {

		if ( $this->getData(TRANS_TRANSACTION_STATUS) == TRANS_STATUS_PAYMENT && $user_level != TRANS_LEVEL_PAYMENT_OFFICER && $user_level != TRANS_LEVEL_ADMIN_OFFICER && $user_level != TRANS_LEVEL_ROOT_OFFICER ) {

			$this->debug("addCashPayment Failed, user level $user_level of $admin is not allowed.");
			$this->setError( -1, "addCashPayment mode failed, user level $user_level not allowed.");
			return -1;

		}

		$clsOR = new EBPLSTransactionPaymentsOR( $this->m_dbLink );

		if ( ( $ret = $clsOR->addCashPayment( $this, $amount, $admin, $bCommit ) ) > 0 ) {

			return array( $clsOR->getData( TPOR_OR_NO ), $clsOR );

		} else {

			$err = $clsOR->getError();
			$this->setError( -1, $err[0]["err_mesg"] );

			return $ret;

		}

	}

	function addCheckPayment( $check_name, $check_no, $check_amount, $check_date, $admin, $user_level ) {

		if ( $this->getData(TRANS_TRANSACTION_STATUS) == TRANS_STATUS_PAYMENT && $user_level != TRANS_LEVEL_PAYMENT_OFFICER && $user_level != TRANS_LEVEL_ADMIN_OFFICER && $user_level != TRANS_LEVEL_ROOT_OFFICER ) {

			$this->debug("addCheckPayment Failed, user level $user_level of $admin is not allowed.");
			$this->setError( -1, "setPayment mode failed, user level $user_level not allowed.");
			return -1;

		}

		if ( !is_numeric( $this->getData(TRANS_ID) ) ) {

			$this->debug("addCheckPayment Failed, transaction not loaded, load by invoking loadTransaction.");
			return -1;

		}

		// add record to check table
		$clsCheck = new EBPLSTransactionPaymentsCheck( $this->m_dbLink );
		$ret = $clsCheck->addCheckPayment( $this->getData(TRANS_ID), $check_name, $check_no, $check_amount, $check_date, $admin );

		return $ret;

	}

	function setCheckPaymentStatus( $check_no, $check_status, $remark, $admin, $user_level ) {

		// if check is valid then process payment by invoking addCashPayment with type from CHECK
		if ( !is_numeric( $this->getData(TRANS_ID) ) ) {

			$this->debug("setCheckPaymentStatus Failed, transaction not loaded, load by invoking loadTransaction.");
			return -1;

		}

		if ( $this->getData(TRANS_TRANSACTION_STATUS) == TRANS_STATUS_PAYMENT && $user_level != TRANS_LEVEL_PAYMENT_OFFICER && $user_level != TRANS_LEVEL_ADMIN_OFFICER && $user_level != TRANS_LEVEL_ROOT_OFFICER ) {

			$this->debug("setCheckPaymentStatus Failed, user level $user_level of $admin is not allowed.");
			$this->setError( -1, "setCheckPaymentStatus failed, user level $user_level not allowed.");
			return -1;

		}

		if (  $check_status != CHECK_STATUS_CLEARED && $check_status != CHECK_STATUS_INVALID ) {

			$this->debug("setCheckPaymentStatus Failed, status $check_status value not allowed.");
			$this->setError( -1, "setCheckPaymentStatus failed,  status $check_status value not allowed.");
			return -1;

		}

		// add record to check table
		$clsCheck = new EBPLSTransactionPaymentsCheck( $this->m_dbLink );
		$ret = $clsCheck->setCheckStatus( $check_no, $check_status, $admin, $remark );

		return $ret;

	}

	function processCheckPayment( $check_no, $admin, $user_level ) {

		// if check is valid then process payment by invoking addCashPayment with type from CHECK
		if ( !is_numeric( $this->getData(TRANS_ID) ) ) {

			$this->debug("processCheckPayment Failed, transaction not loaded, load by invoking loadTransaction.");
			return -1;

		}

		if ( $this->getData(TRANS_TRANSACTION_STATUS) == TRANS_STATUS_PAYMENT && $user_level != TRANS_LEVEL_PAYMENT_OFFICER && $user_level != TRANS_LEVEL_ADMIN_OFFICER && $user_level != TRANS_LEVEL_ROOT_OFFICER ) {

			$this->debug("processCheckPayment Failed, user level $user_level of $admin is not allowed.");
			$this->setError( -1, "processCheckPayment failed, user level $user_level not allowed.");
			return -1;

		}

		// add record to check table
		$clsCheck = new EBPLSTransactionPaymentsCheck( $this->m_dbLink );
		$ret = $clsCheck->view( NULL, $check_no );

		if ( $ret > 0 ) {

			if (  $clsCheck->getData(TPC_CHECK_STATUS) == CHECK_STATUS_CLEARED ) {

				$or_no = $this->addCashPayment( $clsCheck->getData( TPC_CHECK_AMOUNT ), $admin, $user_level );
				$clsCheck->_updateOR( $check_no, $or_no );

				return 1;
			} else if (  $clsCheck->getData(TPC_CHECK_STATUS) == CHECK_STATUS_PROCESSED ) {

				$this->setError( -1, "Check $check_no already processed.");
				return -1;

			} else {

				$this->setError( -1, "Check $check_no not yet cleard.");
				return -1;

			}

		} else {

			$this->setError( -1, "Check $check_no not found.");
			return $ret;

		}

		return $ret;

	}


	function getPaymentList( $paymentType, $check_status = NULL ) {

		if ( !is_numeric( $this->getData(TRANS_ID) ) ) {

			$this->debug("getPaymentList Failed, transaction not loaded, load by invoking loadTransaction.");
			$this->setError( -1, "getPaymentList Failed, transaction not loaded, load by invoking loadTransaction.");
			return -1;

		}

		/*
		if ( $this->getData(TRANS_TRANSACTION_STATUS) == TRANS_STATUS_PAYMENT && $user_level != TRANS_LEVEL_PAYMENT_OFFICER && $user_level != TRANS_LEVEL_ADMIN_OFFICER && $user_level != TRANS_LEVEL_ROOT_OFFICER ) {

			$this->debug("getPaymentList Failed, user level $user_level of $admin is not allowed.");
			$this->setError( -1, "setPayment mode failed, user level $user_level not allowed.");
			return -1;

		}
		*/

		if ( $paymentType != "CASH" && $paymentType != "CHECK" ) {

			$this->debug("getPaymentList Failed, payment type passed $paymentType invalid. Valid values payment Type enum('CASH','CHECK').");
			$this->setError( -1, "getPaymentList Failed, payment type passed $paymentType invalid. Valid values payment Type enum('CASH','CHECK').");
			return -1;

		}		

		if ( $paymentType == "CASH" ) {

			$clsOR = new EBPLSTransactionPaymentsOR( $this->m_dbLink );
			$records = $clsOR->getPaymentList( $this->getData(TRANS_ID) );

		} else if ( $paymentType == "CHECK" ) {

			$clsCheck = new EBPLSTransactionPaymentsCheck( $this->m_dbLink );
			$records = $clsCheck->getCheckPaymentList( $this->getData(TRANS_ID), $check_status );

		}

		return $records;

	}

	/**************************************************
	 *
	 * GENERAL UTIL FUNCTIONS
	 *
	 **************************************************/
	function getPermitCode(){

		//return $this->getData( TRANS_PERMIT_TYPE ) . str_pad( $this->getData( TRANS_PERMIT_NO ), 15, "0", STR_PAD_LEFT) ;
		return $this->getData( TRANS_PERMIT_CODE );

	}

	function getPermitType(){


		return $this->getData( TRANS_PERMIT_TYPE ) ;

	}

	function getPermitId(){

		return $this->getData( TRANS_PERMIT_NO ) ;

	}

	// get the permit attached to this transaction
	function getPermit( ) {

		if ( !is_numeric( $this->getData(TRANS_ID) ) ) {

			$this->debug("getPermit Failed, transaction not loaded, load by invoking loadTransaction.");
			return -1;

		}

		if ( $this->m_objPermit == NULL ) {

			$permit_type = $this->getData( TRANS_PERMIT_TYPE );
			$permit_no = $this->getData( TRANS_PERMIT_NO );

			switch($permit_type){
				case "MOT":
					$this->m_objPermit = new EBPLSMotorizedPermit( $this->m_dbLink);
					$this->m_objPermit->view( $permit_no );
					break;
				case "OCC":
					$this->m_objPermit = new EBPLSOccupationalPermit( $this->m_dbLink);
					$this->m_objPermit->view( $permit_no );
					break;
				case "PED":
					$this->m_objPermit = new EBPLSPeddlersPermit( $this->m_dbLink );
					$this->m_objPermit->view( $permit_no );
					break;
				case PERMIT_TYPE_BUSINESS:
					$this->m_objPermit = new EBPLSEnterprisePermit( $this->m_dbLink );
					$this->m_objPermit->view( $permit_no );
					break;
				case "FRA":
					$this->m_objPermit = new EBPLSFranchisePermit( $this->m_dbLink );
					$this->m_objPermit->view( $permit_no );
					break;
				case "FIS":
					$this->m_objPermit = new EBPLSFisheryPermit( $this->m_dbLink );
					$this->m_objPermit->view( $permit_no );
					break;
				default:
					$this->debug("Class Permit type $permit_type not yet supported");

			}

		}

		return $this->m_objPermit;

	}

	function getOwnerInfo(){

		if ( !is_numeric( $this->getData(TRANS_ID) ) ) {

			$this->debug("getOwnerInfo Failed, transaction not loaded, load by invoking loadTransaction.");
			return -1;

		}

		if ( !is_numeric( $this->getData(TRANS_OWNER_ID) ) ) {

			$this->debug("getOwnerInfo Fatal Failed, onwer id not set.");
			return -1;

		}


		$clsOwnerGet = new EBPLSOwner( $this->m_dbLink );

		$this->debug("GET OWNER INFO ON TRANS : " . $this->getData(TRANS_OWNER_ID));

		if ( $clsOwnerGet->view(  $this->getData(TRANS_OWNER_ID) ) > 0 ) {

			return $clsOwnerGet;

		} else {

			$this->debug("getOnwerInfo Failed, cannot load owner record of owner with owner id " . $this->getData(TRANS_OWNER_ID) . ".");
			return -2;

		}

	}

	function setTransactionStatus( $trans_id, $status, $creator, $user_level ) {

		switch ( $status ) {

			case TRANS_STATUS_ASSESSMENT :
				{

					if ( $user_level != TRANS_LEVEL_APPLICATION_OFFICER && $user_level != TRANS_LEVEL_ADMIN_OFFICER && $user_level != TRANS_LEVEL_ROOT_OFFICER) {

						$this->debug("setTransactionStatus Failed, user level $user_level of $admin is not allowed.");
						$this->setError(-1,"setTransactionStatus Failed, user level $user_level of $admin is not allowed.");
						return -1;

					}

					if ( !is_numeric( $this->getData(TRANS_ID) ) ) {

						$this->debug("setTransactionStatus Failed, transaction not loaded, load by invoking loadTransaction.");
						$this->setError(-1,"setTransactionStatus Failed, transaction not loaded, load by invoking loadTransaction.");
						return -1;

					 }

					if ( $this->getData( TRANS_TRANSACTION_STATUS ) != TRANS_STATUS_APPLICATION ) {

						$this->debug("setTransactionStatus Failed, transaction status already " . $this->getData( TRANS_TRANSACTION_STATUS ) );
						$this->setError(-1,"setTransactionStatus Failed, transaction status already " . $this->getData( TRANS_TRANSACTION_STATUS ) );
						return -1;

					}

					// NOTE : additional checking should be done here if all requirements have been submitted
					$pen_req_list = $this->getRequirementList( REQUIREMENT_STATUS_PENDING );

					//print_r($pen_req_list);

					if ( $pen_req_list!=-1 && count($pen_req_list) > 0 ) {

						$this->debug("setTransactionStatus Failed, pending requirements not yet submitted." );
						$this->setError(-1,"setTransactionStatus Failed, pending requirements not yet submitted." );
						return -1;

					}

					ebpls_start_transaction( $this->m_dbLink );

					$ass_code  = get_next_system_code( $this->m_dbLink, CODES_ASS_COL );

					$strWhere[ TRANS_ID ] = $trans_id;
					$strValue[ TRANS_TRANSACTION_STATUS ] = TRANS_STATUS_ASSESSMENT;
					$strValue[ TRANS_ASSESSMENT_CODE ] = $ass_code;
					$strValue[ TRANS_ASSESSMENT_DATE ] = date("Y-m-d H:i:s");
					$strValue[ TRANS_APPLICATION_DATE_PROCESSED ] = date("Y-m-d H:i:s");
					$strValue[ TRANS_ADMIN_USER ] = $creator;

					$ret = ebpls_update_data( $this->m_dbLink, EBPLS_TRANSACTION_TABLE, $strValue, $strWhere );

					if ( $ret < 0 ) {

						$this->debug("Promotion of transaction to ASSESSMENT stage failed.");
						$this->setError(-1,get_db_error());
						ebpls_rollback_transaction( $this->m_dbLink );

					} else {

						$this->debug("Promotion of transaction to ASSESSMENT stage ok.");
						$ret_app_code = update_system_code( $this->m_dbLink, CODES_ASS_COL, $ass_code);

					}

					ebpls_commit_transaction( $this->m_dbLink );

				} break;

			case TRANS_STATUS_PAYMENT :
				{

					if ( $user_level != TRANS_LEVEL_ASSESSMENT_OFFICER && $user_level != TRANS_LEVEL_ADMIN_OFFICER && $user_level != TRANS_LEVEL_ROOT_OFFICER) {

						$this->debug("assessTransaction Failed, user level $user_level of $admin is not allowed.");
						$this->setError(-1,"assessTransaction Failed, user level $user_level of $admin is not allowed.");
						return -1;

					}

					if ( !is_numeric( $this->getData(TRANS_ID) ) ) {

						$this->debug("assessTransaction Failed, transaction not loaded, load by invoking loadTransaction.");
						$this->setError(-1,"assessTransaction Failed, transaction not loaded, load by invoking loadTransaction.");
						return -1;

					}

					if ( $this->getData( TRANS_TRANSACTION_STATUS ) != TRANS_STATUS_ASSESSMENT ) {

						$this->debug("assessTransaction Failed, transaction status already " . $this->getData( TRANS_TRANSACTION_STATUS ) );
						$this->setError(-1,"assessTransaction Failed, transaction status already " . $this->getData( TRANS_TRANSACTION_STATUS ) );
						return -1;
					}


					// NOTE : check if payment mode have beens set
					if ( !$this->isPaymentModeSet() ) {

						$this->setError(-1,"Payment not yet set, please set payment mode before setting transaction for payment status.");
						return -1;

					}

					ebpls_start_transaction( $this->m_dbLink );

					$pay_code  = get_next_system_code( $this->m_dbLink, CODES_PAY_COL );

					$strWhere[ TRANS_ID ] = $trans_id;
					$strValue[ TRANS_TRANSACTION_STATUS ] = TRANS_STATUS_PAYMENT;
					$strValue[ TRANS_PAYMENT_CODE ] = $pay_code;
					$strValue[ TRANS_PAYMENT_DATE ] = date("Y-m-d H:i:s");
					$strValue[ TRANS_ASSESSMENT_DATE_PROCESSED ] = date("Y-m-d H:i:s");
					$strValue[ TRANS_ADMIN_USER ] = $creator;

					$ret = ebpls_update_data( $this->m_dbLink, EBPLS_TRANSACTION_TABLE, $strValue, $strWhere );

					if ( $ret < 0 ) {

						$this->debug("Promotion of transaction to ASSESSMENT stage failed.");
						$this->setError(-1,get_db_error());
						ebpls_rollback_transaction( $this->m_dbLink );

					} else {

						$this->debug("Promotion of transaction to ASSESSMENT stage ok.");
						$ret_app_code = update_system_code( $this->m_dbLink, CODES_PAY_COL, $pay_code);

					}

					ebpls_commit_transaction( $this->m_dbLink );

				} break;

			case TRANS_STATUS_APPROVAL :
				{

					if ( $user_level != TRANS_LEVEL_PAYMENT_OFFICER && $user_level != TRANS_LEVEL_ADMIN_OFFICER && $user_level != TRANS_LEVEL_ROOT_OFFICER) {

						$this->debug("paymentTransaction Failed, user level $user_level of $admin is not allowed.");
						$this->setError(-1,"paymentTransaction Failed, user level $user_level of $admin is not allowed.");
						return -1;

					}

					if ( !is_numeric( $this->getData(TRANS_ID) ) ) {

						$this->debug("paymentTransaction Failed, transaction not loaded, load by invoking loadTransaction.");
						$this->setError(-1,"paymentTransaction Failed, transaction not loaded, load by invoking loadTransaction.");
						return -1;

					}

					if ( $this->getData( TRANS_TRANSACTION_STATUS ) != TRANS_STATUS_PAYMENT ) {

						$this->debug("paymentTransaction Failed, transaction status already " . $this->getData( TRANS_TRANSACTION_STATUS ) );
						$this->setError(-1,"paymentTransaction Failed, transaction status already " . $this->getData( TRANS_TRANSACTION_STATUS ) );
						return -1;
					}

					//
					ebpls_start_transaction( $this->m_dbLink );

					$apr_code  = get_next_system_code( $this->m_dbLink, CODES_APR_COL );

					$strWhere[ TRANS_ID ] = $trans_id;
					$strValue[ TRANS_TRANSACTION_STATUS ] = TRANS_STATUS_APPROVAL;
					$strValue[ TRANS_APPROVAL_CODE ] = $apr_code;
					$strValue[ TRANS_APPROVAL_DATE ] = date("Y-m-d H:i:s");
					$strValue[ TRANS_PAYMENT_DATE_PROCESSED ] = date("Y-m-d H:i:s");
					$strValue[ TRANS_ADMIN_USER ] = $creator;

					$ret = ebpls_update_data( $this->m_dbLink, EBPLS_TRANSACTION_TABLE, $strValue, $strWhere );



					if ( $ret < 0 ) {

						$this->debug("Promotion of transaction to ASSESSMENT stage failed.");
						$this->setError(-1,get_db_error());

						ebpls_rollback_transaction( $this->m_dbLink );

					} else {

						$this->debug("Promotion of transaction to ASSESSMENT stage ok.");
						$ret_app_code = update_system_code( $this->m_dbLink, CODES_APR_COL, $apr_code);

					}

					ebpls_commit_transaction( $this->m_dbLink );

				} break;

			case TRANS_STATUS_RELEASING :
				{

					if ( $user_level != TRANS_LEVEL_APPROVAL_OFFICER && $user_level != TRANS_LEVEL_ADMIN_OFFICER && $user_level != TRANS_LEVEL_ROOT_OFFICER ) {

						$this->debug("approveTransaction Failed, user level $user_level of $admin is not allowed.");
						$this->setError(-1,"approveTransaction Failed, user level $user_level of $admin is not allowed.");
						return -1;

					}

					if ( !is_numeric( $this->getData(TRANS_ID) ) ) {

						$this->debug("approveTransaction Failed, transaction not loaded, load by invoking loadTransaction.");
						$this->setError(-1,"approveTransaction Failed, transaction not loaded, load by invoking loadTransaction.");
						return -1;

					}

					if ( $this->getData( TRANS_TRANSACTION_STATUS ) != TRANS_STATUS_APPROVAL ) {

						$this->debug("setStatusForReleasing Failed, transaction status already " . $this->getData( TRANS_TRANSACTION_STATUS ) );
						$this->setError(-1,"setStatusForReleasing Failed, transaction status already " . $this->getData( TRANS_TRANSACTION_STATUS ) );
						return -1;

					}


					ebpls_start_transaction( $this->m_dbLink );

					$rel_code  = get_next_system_code( $this->m_dbLink, CODES_REL_COL );

					$strWhere[ TRANS_ID ] = $trans_id;
					$strValue[ TRANS_TRANSACTION_STATUS ] = TRANS_STATUS_RELEASING;
					$strValue[ TRANS_RELEASE_CODE ] = $rel_code;
					$strValue[ TRANS_RELEASE_DATE ] = date("Y-m-d H:i:s");
					$strValue[ TRANS_APPROVAL_DATE_PROCESSED ] = date("Y-m-d H:i:s");
					$strValue[ TRANS_ADMIN_USER ] = $creator;

					$ret = ebpls_update_data( $this->m_dbLink, EBPLS_TRANSACTION_TABLE, $strValue, $strWhere );


					if ( $ret < 0 ) {

						$this->debug("Promotion of transaction to ASSESSMENT stage failed.");
						$this->setError(-1,get_db_error());

						ebpls_rollback_transaction( $this->m_dbLink );

					} else {

						$this->debug("Promotion of transaction to ASSESSMENT stage ok.");
						$ret_app_code = update_system_code( $this->m_dbLink, CODES_REL_COL, $rel_code);
					}

					ebpls_commit_transaction( $this->m_dbLink );

				} break;

			case TRANS_STATUS_RELEASED :
			case TRANS_STATUS_REJECTED :
				{

					if ( $user_level != TRANS_LEVEL_RELEASING_OFFICER && $user_level != TRANS_LEVEL_ADMIN_OFFICER && $user_level != TRANS_LEVEL_ROOT_OFFICER) {

						$this->debug("releaseTransaction Failed, user level $user_level of $admin is not allowed.");
						$this->setError(-1,"releaseTransaction Failed, user level $user_level of $admin is not allowed.");
						return -1;

					}

					if ( !is_numeric( $this->getData(TRANS_ID) ) ) {

						$this->debug("releaseTransaction Failed, transaction not loaded, load by invoking loadTransaction.");
						$this->setError(-1,"releaseTransaction Failed, transaction not loaded, load by invoking loadTransaction.");
						return -1;

					}

					if ( $this->getData( TRANS_TRANSACTION_STATUS ) != TRANS_STATUS_RELEASING ) {

						$this->debug("releaseTransaction Failed, transaction status already " . $this->getData( TRANS_TRANSACTION_STATUS ) );
						$this->setError(-1,"releaseTransaction Failed, transaction status already " . $this->getData( TRANS_TRANSACTION_STATUS ) );
						return -1;

					}




					// get permit no
					$permit_type = $this->getPermitType();
					$permit_id = $this->getPermitId();

					switch($permit_type){

						case PERMIT_TYPE_BUSINESS :
							$key = CODES_PERMIT_BUS_COL;
							break;
						case PERMIT_TYPE_OCCUPATIONAL :
							$key = CODES_PERMIT_OCCUPATIONAL_COL;
							break;
						case PERMIT_TYPE_PEDDLER :
							$key = CODES_PERMIT_PEDDLER_COL;
							break;
						case PERMIT_TYPE_FRANCHISE :
							$key = CODES_PERMIT_FRANCHISE_BUS_COL;
							break;
						case PERMIT_TYPE_MOTORIZED :
							$key = CODES_PERMIT_MOTORIZED_COL;
							break;
						case PERMIT_TYPE_FISHERY :
							$key = CODES_PERMIT_FISHERY_COL;
							break;
						default :
							$this->debug("Invalid Transaction permit type $permit_type, can't process release of transaction.");
							return -1;
							break;
					}


					ebpls_start_transaction( $this->m_dbLink );

					$strWhere[ TRANS_ID ] = $trans_id;
					$strValue[ TRANS_TRANSACTION_STATUS ] = $status;
					$strValue[ TRANS_RELEASE_DATE_PROCESSED ] = date("Y-m-d H:i:s");
					$strValue[ TRANS_ADMIN_USER ] = $creator;

					if ( $status == TRANS_STATUS_RELEASED && ( $this->getData(TRANS_TYPE) == TRANS_TYPE_NEW || $this->getData(TRANS_TYPE) == TRANS_TYPE_RENEW ) ) {

						$permit_code  = get_next_system_code( $this->m_dbLink, $key );
						$strValue[ TRANS_PERMIT_CODE ] = $permit_code;

					}

					// use ebplsPermit as a factory class to retrieve proper class reference with the right type
					// of EBPLSPermit subclass
					$clsPermit = new EBPLSPermit( $this->m_dbLink, false );
					$clsPermitToUpdate = $clsPermit->createPermit( $this->m_dbLink, $permit_id, $permit_type, false );

					if ( $clsPermitToUpdate != null ) {

						// update transaction with permit code of permit
						$ret = ebpls_update_data( $this->m_dbLink, EBPLS_TRANSACTION_TABLE, $strValue, $strWhere );

						if ( $ret < 0 ) {

							$this->debug("Promotion of transaction to $status stage failed.");
							$this->setError(-1,get_db_error());

							ebpls_rollback_transaction( $this->m_dbLink );

						} else {

							// update permit of table
							$clsPermitToUpdate->assignPermitCode( $permit_code );

							if ( $status == TRANS_STATUS_RELEASED && ( $this->getData(TRANS_TYPE) == TRANS_TYPE_NEW || $this->getData(TRANS_TYPE) == TRANS_TYPE_RENEW ) ) {
								$ret_app_code = update_system_code( $this->m_dbLink, $key, $permit_code );
							}

							$this->debug("Promotion of transaction to $status stage ok.");

						}

						// set permit status
						if ($permit_type == PERMIT_TYPE_BUSINESS ) {

							$clsBus = new EBPLSEnterprise( $this->m_dbLink );
							$clsBus->_updateBusinessNatureApplication( $nature_code, NULL, NULL, NULL, "PROCESSED" );

						}

						ebpls_commit_transaction( $this->m_dbLink );

					} else {

						$this->debug("Promotion of transaction to $status stage failed, unable to create class permit to assigne permit code.");
						ebpls_rollback_transaction( $this->m_dbLink );

					}



				} break;

			default :
				{

					$this->debug("Unrecognized status $status value passed.");

				} break;

		}

		$this->data_elem[ TRANS_TRANSACTION_STATUS ] = $status;

		return $ret;

	}

	function isPaymentModeSet( $trans_id = NULL ){

		$strValues[TRANS_PAYMENT_MODE] = "trans_payment_mode";

		if ( $trans_id ) {

			$strWhere[TRANS_ID] = $trans_id;

		} else {

			$strWhere[TRANS_ID] = $this->getData(TRANS_ID);

		}

		$result = ebpls_select_data( $this->m_dbLink, EBPLS_TRANSACTION_TABLE, $strValues, $strWhere, NULL, "", "DESC", NULL );

		if ( is_array($result)&& $result[0][TRANS_PAYMENT_MODE]!="" ) {

			return 1;

		} else {

			return 0;

		}

	}


	function loadTransaction( $trans_id ) {

		$strValues[$key] = "*";

		$strWhere[TRANS_ID] = $trans_id;

		$result = ebpls_select_data( $this->m_dbLink, EBPLS_TRANSACTION_TABLE, $strValues, $strWhere, NULL, $strOrderBy, "DESC", NULL );

		if ( is_array($result) ) {

			$this->data_elems = $result[0];
			return $result[0][TRANS_ID];

		} else {

			$this->setError( $result, get_db_error() );
			return -1;

		}

	}

	function _searchINTERNAL( $trans_type, $permit_type, $status = NULL, $owner_id = NULL, $status_code = NULL, $page = 1, $maxrec= 10, $orderkey = TRANS_TRANSACTION_DATE, $bIsDesc = true ) {

		global $gPermitTypes, $gTransactionStatusStatus;

		if ( $permit_type!=NULL && !in_array($permit_type, $gPermitTypes ) ) {

			$this->setError(-1,"Invalid permit type passed $permit_type. Valid values " . join(",",$gPermitTypes) . ".");

			return -1;

		}


		/*
		// removed payment status checking since we are expecting an array/single element status value
		// let the query do the checking, it won't hurt anyway
		if ( $status!=NULL //&& !in_array($status, $gTransactionStatusStatus) ) {

			$this->setError(-1,"Invalid status values $status. Valid values " . join(",",$gTransactionStatusStatus) . ".");
			return -1;

		}
		*/

		if ( !is_numeric($page) ){

			$this->setError(-1,"Invalid page value, value not numeric $page");
			return -1;

		}


		if ( !is_numeric($maxrec) ){

			$this->setError(-1,"Invalid maxrec value, value not numeric $maxrec");
			return -1;

		}

		if ( $permit_type != NULL ) {

			$strWhere[TRANS_PERMIT_TYPE] = $permit_type;

		}

		if ( $status != NULL ) {

			if ( is_array($status) ) {

				$strWhere[TRANS_TRANSACTION_STATUS] = array("IN", "('" . join($status,"','") . "')" );

			} else {

				$strWhere[TRANS_TRANSACTION_STATUS] = $status;

			}

		}

		if ( $owner_id != NULL ) {

			if ( is_array($owner_id)) {

				$str_owner_id_list = " ( " . implode(",",$owner_id) . ")";
				$strWhere[TRANS_OWNER_ID] = array(" IN " , $str_owner_id_list );

			} else {

				$strWhere[TRANS_OWNER_ID] = $onwer_id;

			}

		}

		// permit code
		if ( $status_code != NULL ) {

			// depends on status provided
			switch( $status ) {

				case TRANS_STATUS_ASSESSMENT :
					{

						$strWhere[TRANS_APPLICATION_CODE] = $status_code;

					} break;
				case TRANS_STATUS_PAYMENT :
					{

						$strWhere[TRANS_ASSESSMENT_CODE] = $status_code;

					} break;
				case TRANS_STATUS_APPROVAL :
					{

						$strWhere[TRANS_PAYMENT_CODE] = $status_code;

					} break;
				case TRANS_STATUS_RELEASED :
				case TRANS_STATUS_REJECTED :
					{

						$strWhere[TRANS_APPROVAL_CODE] = $status_code;

					} break;
				default :
					{

						$this->debug("Invalid search status $status!\n");

					} break;
			}

		}

		if ( $trans_type != NULL ) {

			$strWhere[TRANS_TYPE] = $trans_type;

		}

		// select all columns
		$strValues[] = "*";

		if ( $orderkey != NULL ) {

			$strOrder[$orderkey] = $orderkey;

		} else {

			$strOrder = $orderkey;

		}		


		$result = ebpls_select_data_bypage( $this->m_dbLink, EBPLS_TRANSACTION_TABLE, $strValues, $strWhere, NULL, $strOrder, $bIsDesc?"DESC":"ASC", $page, $maxrec );

		if ( !is_array($result) && $result < 0 ) {

			$this->setError( $result, get_db_error() );
			return $result;

		} else {

			// transform result to EBPLSTransaction
			for ( $i = 0 ; $i < count($result["result"]); $i++ ) {

				$records[$i] = new EBPLSTransaction($this->m_dbLink);
				$records[$i]->setData( NULL, $result["result"][$i] );

			}

			$result["result"] = $records;

			return $result;

		}

	}

	function searchTransaction( &$owner_obj, $trans_type, $status_code = NULL, $permit_type = NULL, $status = NULL, $page = 1, $maxrec= 10, $orderkey = TRANS_TRANSACTION_DATE, $bIsDesc = true ) {

		if ( $owner_obj != NULL ) {

			$result = $owner_obj->search();

			if ( is_array($result) ) {

				for ( $i = 0; $i < count($result["result"]); $i++ ) {

					$owner_id_arr[] = $result["result"][$i]->getData(OWNER_ID);					

				}

			} else {

				//$this->setError(-1,"No match given specified owner.");
				//return $result;

			}

		}

		return $this->_searchINTERNAL( $trans_type, $permit_type, $status, $owner_id_arr, $status_code, $page, $maxrec, $orderkey , $bIsDesc );

	}


	function searchPaymentTransaction( &$owner_obj, $trans_type, $permit_code = NULL, $permit_type = NULL, $page = 1, $maxrec= 10, $orderkey = TRANS_TRANSACTION_DATE, $bIsDesc = true ) {

		if ( $owner_obj != NULL ) {

			$result = $owner_obj->search();

			if ( is_array($result) ) {

				for ( $i = 0; $i < count($result["result"]); $i++ ) {

					$owner_id_arr[] = $result["result"][$i]->getData(OWNER_ID);					

				}

			} else {

				$this->setError(-1,"No match given specified owner.");
				return $result;

			}

		}

		return $this->_searchINTERNAL( $trans_type, $permit_type, "'PAYMENT','APPROVAL','RELEASED'", $owner_id_arr, $permit_code, $page, $maxrec, $orderkey , $bIsDesc );

	}


	/******************************************
         *
         * INTERNALLY INVOKED FUNCTIONS
         *
	 ******************************************/
	function _setTransPaymentModeINTERNAL( $payment_mode, $total_due, $total_paid, $bTaxFeeSeparated, $admin ) {

		$strWhere[ TRANS_ID ] = $this->getData( TRANS_ID );

		if ( $total_due != NULL ) {

			$strValue[ TRANS_TOTAL_AMOUNT_DUE ] = $total_due;

		}

		if ( $total_paid != NULL ) {

			$strValue[ TRANS_TOTAL_AMOUNT_PAID ] = $total_paid;

		}

		$strValue[ TRANS_PAYMENT_TAXFEE_DIVIDED ] = ($bTaxFeeSeparated)?"1":"0";
		$strValue[ TRANS_PAYMENT_MODE ] = $payment_mode;
		$strValue[ TRANS_ADMIN_USER ] = $creator;

		$ret = ebpls_update_data( $this->m_dbLink, EBPLS_TRANSACTION_TABLE, $strValue, $strWhere );

		if ( $ret > 0 ) {

			return $ret;

		} else {

			$this->setError( -1, get_db_error());
			return -1;

		}

	}

	function _setTransPaymentStatusINTERNAL( $payment_code, $total_paid, $payment_status, $admin ) {

		$strWhere[ TRANS_ID ] = $this->getData( TRANS_ID );
		//$strValue[ TRANS_PAYMENT_CODE ] = $payment_code; // do we need to update?
		$strValue[ TRANS_TOTAL_AMOUNT_PAID ] = $total_paid;
		$strValue[ TRANS_TOTAL_AMOUNT_DUE ] = $total_due;
		$strValue[ TRANS_PAYMENT_STATUS ] = $payment_status;
		$strValue[ TRANS_ADMIN_USER ] = $creator;

		$ret = ebpls_update_data( $this->m_dbLink, EBPLS_TRANSACTION_TABLE, $strValue, $strWhere );

		if ( $ret > 0 ){

			return $ret;

		} else {

			$this->setError(-1,get_db_error());
			return -1;

		}

	}


	function _setTransToRetire( $creator ) {

		$strWhere[ TRANS_ID ] = $this->getData( TRANS_ID );
		$strValue[ TRANS_PAYMENT_STATUS ] = "RETIRE";
		$strValue[ TRANS_ADMIN_USER ] = $creator;

		$ret = ebpls_update_data( $this->m_dbLink, EBPLS_TRANSACTION_TABLE, $strValue, $strWhere );

		if ( $ret > 0 ){

			return $ret;

		} else {

			$this->setError(-1,get_db_error());
			return -1;

		}

	}

}


class EBPLSTransactionBusinessNature extends DataEncapsulator {

	var $m_dbLink;
	var $trans_id;
	var $owner_id;
	var $business_id;

	function EBPLSTransactionBusinessNature( $dbLink, $bDebug = false ) {

		$this->m_dbLink = $dbLink;
		$this->setDebugMode( $bDebug );

		$this->addDataElement(TRANS_BUSNATURE_TRANS_ID, "is_valid_number", "[VALUE]" );
		$this->addDataElement(TRANS_BUSNATURE_BUSINESS_ID , "is_valid_number", "[VALUE]" );
		$this->addDataElement(TRANS_BUSNATURE_OWNER_ID, "is_valid_number", "[VALUE]" );
		$this->addDataElement(TRANS_BUSNATURE_BUSINESS_NATURE_CODE, "is_not_empty", "[VALUE]" );
		$this->addDataElement(TRANS_BUSNATURE_BUSINESS_DESCRIPTION, "is_not_empty", "[VALUE]" );
		$this->addDataElement(TRANS_BUSNATURE_CAPITAL_INVESTMENT, "is_valid_number", "[VALUE]" );
		$this->addDataElement(TRANS_BUSNATURE_LAST_GROSS, "is_valid_number", "[VALUE]" );
		$this->addDataElement(TRANS_BUSNATURE_STATUS, NULL, NULL );
		$this->addDataElement(TRANS_BUSNATURE_FOR_YEAR,"is_valid_number", "[VALUE]", true );
		$this->addDataElement(TRANS_BUSNATURE_TS_CREATE, "is_valid_date", "[VALUE]", true );
		$this->addDataElement(TRANS_BUSNATURE_TS_UPDATE, "is_valid_date", "[VALUE]", true );

	}

	function add(){

		if ( $this->m_dbLink ) {

			$this->data_elems[TRANS_BUSNATURE_FOR_YEAR] = date("Y");
			$this->data_elems[TRANS_BUSNATURE_TS_CREATE] = date("Y-m-d H:i:s");
			$this->data_elems[TRANS_BUSNATURE_TS_UPDATE] = date("Y-m-d H:i:s");

			if ( ( $error_num = $this->validateData() ) > 0 ) {

				$strValues = $this->getData();

				$ret = ebpls_insert_data( $this->m_dbLink, TRANS_BUSNATURE_TABLE, $strValues );

				if ( $ret < 0 ) {

					$this->debug( "CREATE EBPLSTransactionBusinessNature FAILED [error:$ret,msg=" . get_db_error() . "]" );
					$this->setError( $ret, get_db_error() );

					return $ret;

				} else {

					$this->debug( "CREATE EBPLSTransactionBusinessNature SUCCESSFULL [$ret]" );
					return 1;

				}


			} else {

				//print_r($this->getError());
				$this->debug( "CREATE EBPLSTransactionBusinessNature FAILED [error:$ret,msg=" . get_db_error() . "]" );
				return $error_num;

			}

		} else {

			$this->debug( "CREATE EBPLSTransactionBusinessNature FAILED INVALID DB LINK $this->m_dbLink" );
			$this->setError( $ret, "Invalid Db link $this->m_dbLink" );
			return -1;

		}

	}

	function delete( $trans_id, $nature_code ) {


		$strWhere[TRANS_BUSNATURE_TRANS_ID] = $trans_id;
		$strWhere[TRANS_BUSNATURE_BUSINESS_NATURE_CODE] = $nature_code;

		$ret = ebpls_delete_data( $this->m_dbLink, TRANS_BUSNATURE_TABLE, $strWhere );

		return $ret;

	}

	function update( $trans_id, $nature_code ) {

		//print_r($this->data_elems);

		foreach( $this->data_elems as $key=>$value ) {
			if ( $value != NULL ) {
				$strValues[$key] = $value;
			}
		}

		$strWhere[TRANS_BUSNATURE_TRANS_ID] = $trans_id;
		$strWhere[TRANS_BUSNATURE_BUSINESS_NATURE_CODE] = $nature_code;

		$ret = ebpls_update_data( $this->m_dbLink, TRANS_BUSNATURE_TABLE, $strValues, $strWhere );

		return $ret;

	}

	function view( $trans_id, $nature_code ) {

		$strValues[] = "*";

		$strWhere[TRANS_BUSNATURE_TRANS_ID] = $trans_id;
		$strWhere[TRANS_BUSNATURE_BUSINESS_NATURE_CODE] = $nature_code;

		$records = ebpls_select_data( $this->m_dbLink, TRANS_BUSNATURE_TABLE, $strValues, $strWhere );

		if ( is_array($records) && count($records) > 0 ) {

			$this->setData( NULL, $records[0] );
			$this->setError( -1, "EBPLSTransactionBusinessNature found nature code = " . $strWhere[TRANS_BUSNATURE_BUSINESS_NATURE_CODE] . " Record.");
			return 1;

		} else {

			$this->setError( -1, "view EBPLSTransactionBusinessNature nature code = " . $strWhere[TRANS_BUSNATURE_BUSINESS_NATURE_CODE] . " Record not found.");
			return -1;

		}

	}

	function exist( $business_id, $owner_id, $nature_code, $status = NULL ) {

		$strValues[] = "*";


		$strWhere[TRANS_BUSNATURE_BUSINESS_ID] = $business_id;
		$strWhere[TRANS_BUSNATURE_OWNER_ID] = $owner_id;
		$strWhere[TRANS_BUSNATURE_BUSINESS_NATURE_CODE] = $nature_code;
		$strWhere[TRANS_BUSNATURE_FOR_YEAR] = date("Y");

		if ( $status != NULL ) {

			$strWhere[TRANS_BUSNATURE_STATUS] = $status;

		}

		$records = ebpls_select_data( $this->m_dbLink, TRANS_BUSNATURE_TABLE, $strValues, $strWhere );

		if ( is_array($records) && count($records) > 0 ) {

			$this->setError( -1, "EBPLSTransactionBusinessNature found nature code = " . $strWhere[TRANS_BUSNATURE_BUSINESS_NATURE_CODE] . " Record.");
			return 1;

		} else {

			$this->setError( -1, "EBPLSTransactionBusinessNature nature code (exist) = " . $strWhere[TRANS_BUSNATURE_BUSINESS_NATURE_CODE] . " Record not found.");
			return -1;

		}

	}

	function retire( $trans_id, $nature_code ) {

		$strWhere[TRANS_BUSNATURE_TRANS_ID] = $trans_id;
		$strWhere[TRANS_BUSNATURE_BUSINESS_NATURE_CODE] = $nature_code;

		$strValues[TRANS_BUSNATURE_STATUS] = "RETIRED";
		$strValues[TRANS_BUSNATURE_TS_UPDATE] = date("Y-m-d H:i:s");

		$ret = ebpls_update_data( TRANS_BUSNATURE_TABLE, $strValues, $strWhere );

		return $ret;

	}

	function getList( $trans_id, $status = NULL ) {

		$strValues[] = "*";

		$strWhere[TRANS_BUSNATURE_TRANS_ID] = $trans_id;

		if ( $status != NULL ) {

			$strWhere[TRANS_BUSNATURE_STATUS] = $status;

		}

		$records = ebpls_select_data( $this->m_dbLink, TRANS_BUSNATURE_TABLE, $strValues, $strWhere );

		if ( is_array($records) && count($records) > 0 ) {

			$this->setError( -1, "EBPLSTransactionBusinessNature found nature code = " . $strWhere[TRANS_BUSNATURE_BUSINESS_NATURE_CODE] . " Record.");

			for ( $i = 0; $i < count($records) ; $i++ ) {

				$clsRec[$i] = new EBPLSTransactionBusinessNature( $this->m_dbLink );
				$clsRec[$i]->setData( NULL, $records[$i] );

			}

			return $clsRec;

		} else {

			$this->setError( -1, "EBPLSTransactionBusinessNature nature code = " . $strWhere[TRANS_BUSNATURE_BUSINESS_NATURE_CODE] . " Record not found.");
			return -1;

		}

	}

}

?>
