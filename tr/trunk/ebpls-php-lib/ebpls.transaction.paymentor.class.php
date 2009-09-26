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
require_once("ebpls-php-lib/ebpls.chartofaccountsref.class.php");
require_once("ebpls-php-lib/ebpls.transaction.paymentschedule.class.php");

define("TPOR_TABLE","ebpls_transaction_payment_or");

// define ebpls_transaction_fees data elements
define(TPOR_OR_NO,"or_no");
define(TPOR_PAYMENT_CODE,"payment_code");
define(TPOR_TRANS_ID,"trans_id");
define(TPOR_OR_DATE,"or_date");
define(TPOR_TOTAL_AMOUNT_PAID,"total_amount_paid");
define(TPOR_TOTAL_AMOUNT_DUE,"total_amount_due");

define(TPORD_TABLE,"ebpls_transaction_payment_or_details");
define(TPORD_OR_NO,"or_no");
define(TPORD_OR_DETAIL_ID,"or_detail_id");
define(TPORD_TRANS_ID,"trans_id");
define(TPORD_PAYMENT_ID,"payment_id");
define(TPORD_FEE_ID,"fee_id");
define(TPORD_TAX_FEE_CODE,"tax_fee_code");
define(TPORD_ACCOUNT_CODE,"account_code");
define(TPORD_ACCOUNT_NATURE,"account_nature");
define(TPORD_AMOUNT_DUE,"amount_due");
define(TPORD_ENTRY_TYPE,"or_entry_type");
define(TPORD_TS,"ts");


class EBPLSTransactionPaymentsOR extends DataEncapsulator {

	var $or_details;

	function EBPLSTransactionPaymentsOR ( $dbLink, $bDebug = false ) {

		$this->m_dbLink = $dbLink;
		$this->setDebugMode( $bDebug );

		$this->addDataElement(TPOR_OR_NO, "is_not_empty", "[VALUE]" );
		$this->addDataElement(TPOR_TRANS_ID, "is_valid_number", "[VALUE]" );
		$this->addDataElement(TPOR_OR_DATE, "is_valid_date", "[VALUE]" );
		$this->addDataElement(TPOR_TOTAL_AMOUNT_PAID, "is_valid_number", "[VALUE]" );
		$this->addDataElement(TPOR_TOTAL_AMOUNT_DUE, "is_valid_number", "[VALUE]" );		

		$this->or_details = NULL;

	}
		
	function addCashPayment( $trans_obj, $amount, $admin, $bCommit ) {

		// retrieve payment schedule list which are not yet fully paid
		$clsTPS = new EBPLSTransactionPaymentSchedule( $this->m_dbLink, false );
		$tps_records = $clsTPS->listPaymentSchedule( $trans_obj->getData(TRANS_ID), TPS_PAYMENT_STATE_UNPAID, TPS_PAYMENT_DUE_DATE, false );

		if ( is_array($tps_records) ) {

			// check first if payment exceeds the balance (code assumes that payment schedules are update!)
			$tmp_amount_due = $clsTPS->getTotalAmountDue( $trans_obj->getData( TRANS_ID ) );

			if ( $tmp_amount_due <= 0 ) {

				$this->setError( -2, "All tax/fee payables are paid.");
				return -3;

			}

			$this->debug("The current users balance is P" . number_format($tmp_amount_due,2) . " < $amount!");

			if ( round($tmp_amount_due,2) < round($amount,2) ) {

				// return error if payment amount given exceeds balance
				$this->debug("The current users balance is P" . number_format($tmp_amount_due,2) . ", please indicate specified amount as cash payment.");
				$this->setError( -2, "The current users balance is P" . number_format($tmp_amount_due,2) . ", please indicate specified amount as cash payment.");
				return -2;

			}

			$tmp_amount = $amount;
			$tmp_tps_included_records = NULL;

			// ----- START TRANSACTION HERE -----

			$or_code  = get_next_system_code( $this->m_dbLink, CODES_OR_COL );
			$payment_code  = get_next_system_code( $this->m_dbLink, CODES_PAY_COL );
			$clsAccounts = new EBPLChartOfAccountsSysRef( $this->m_dbLink, false );

			$cash_account_code = $clsAccounts->getCashAccountCode();

			if ( $cash_account_code < 0 ) {

				$this->debug( "Unable to load cash account code, return $cash_account_code!" );
				$this->setError(-5, "Unable to load cash account code, return $cash_account_code!");

				// roll back


				return -5;

			}

			$this->debug("OR CODE:$or_code, CASH ACCT CODE:$cash_account_code, PAYMENT:$amount");


			unset($this->or_details);

			for ( $i = 0; $i < count($tps_records); $i++ ) {

				// ---- LOAD TRANSACTION RECORD DATA
				$payment_id = $tps_records[$i]->getData(TPS_PAYMENT_ID);
				$fee_id = $tps_records[$i]->getData(TPS_PAYMENT_FEE_ID);
				$tax_fee_code = $tps_records[$i]->getData(TPS_PAYMENT_TAX_FEE_CODE);
				$tps_amount_due = $tps_records[$i]->getData(TPS_PAYMENT_TOTAL_AMOUNT_DUE);
				$tps_amount_paid = $tps_records[$i]->getData(TPS_PAYMENT_AMOUNT_PAID);
				$tps_penalty_amount = $tps_records[$i]->getData(TPS_PAYMENT_PENALTY_AMOUNT);
				$tps_penalty_amount_paid = $tps_records[$i]->getData(TPS_PAYMENT_PENALTY_AMOUNT_PAID);
				$tps_interest_amount = $tps_records[$i]->getData(TPS_PAYMENT_INTEREST_AMOUNT);
				$tps_interest_amount_paid = $tps_records[$i]->getData(TPS_PAYMENT_INTEREST_AMOUNT_PAID);
				$total_amount_paid = $tps_records[$i]->getData(TPS_PAYMENT_TOTAL_AMOUNT_PAID);

				// ---- RETRIEVE TAX/FEE DATA
				$clsTaxFee = new EBPLTaxFeeSysRef( $this->m_dbLink, false );
				$clsTaxFeePayable = $clsTaxFee->loadTaxFee( $tax_fee_code );
				$clsTaxFeeSurcharge = $clsTaxFee->loadTaxFee( $clsTaxFeePayable->getData(EBPLS_SURCHARGE_TAX_FEE_CODE) );
				$clsTaxFeeInterest = $clsTaxFee->loadTaxFee( $clsTaxFeePayable->getData(EBPLS_INTEREST_TAX_FEE_CODE) );

				if ( $clsTaxFeePayable == -1 || $clsTaxFeeSurcharge == -1 || $clsTaxFeeInterest== -1 ) {

					$this->debug("Unable to load tax fee code $tax_fee_code," . $clsTaxFeePayable->getData(EBPLS_SURCHARGE_TAX_FEE_CODE) . "," . $clsTaxFeePayable->getData(EBPLS_INTEREST_TAX_FEE_CODE) );
					$this->setError( -1, "Unable to load tax fee code $tax_fee_code," . $clsTaxFeePayable->getData(EBPLS_SURCHARGE_TAX_FEE_CODE) . "," . $clsTaxFeePayable->getData(EBPLS_INTEREST_TAX_FEE_CODE) );

					// roll back


					return -1;

				}

				// retrieve account codes
				$tax_fee_account_code = $clsTaxFeePayable->getData(EBPLS_TAX_ACCOUNT_CODE);
				$surcharge_account_code = $clsTaxFeeSurcharge->getData(EBPLS_TAX_ACCOUNT_CODE);
				$interest_account_code = $clsTaxFeeInterest->getData(EBPLS_TAX_ACCOUNT_CODE);

				// ---- COMPUTE BALANCE
				// formula = ( Total Amount Due = (Payable Amount - Paid Amount) + ( Surcharge - Surcharge Paid ) + ( Interest - Interest Paid ) - ( Total Paid Amount )
				// compute raw tax mount due less the raw tax amount paid
				$raw_tax_difference = ( $tps_amount_due - $tps_amount_paid );
				$total_amount_due = $raw_tax_difference;

				$this->debug("COMPUTE BALANCE : $raw_tax_difference = ( $tps_amount_due - $tps_amount_paid )");

				// compute penalty incurred not yet paid on previous payment
				$penalty_tax_difference = ( $tps_penalty_amount - $tps_penalty_amount_paid );
				$total_amount_due += $penalty_tax_difference;

				$this->debug("COMPUTE PENALTY : $penalty_tax_difference = ( $tps_penalty_amount - $tps_penalty_amount_paid )");

				// compute interest incurred not yet paid on previous payment
				$interest_tax_difference = ( $tps_interest_amount - $tps_interest_amount_paid );
				$total_amount_due += $interest_tax_difference;

				$this->debug("COMPUTE INTEREST : $interest_tax_difference = ( $tps_interest_amount - $tps_interest_amount_paid )");

				// compute total balance payable
				// this will be used to calculate interest/surcharge, since these items requires balance amount
				$total_amount_payable = $total_amount_due - $total_amount_paid;

				//$this->debug("COMPUTE TOTAL : $total_amount_payable = $total_amount_due - $total_amount_paid");

				// --------------------------------------------------------------------------

				// ---- PROCESS PAYMENT

				// pay balance on raw tax amount
				if ( $raw_tax_difference > 0 ) {

					if (  $amount > $raw_tax_difference ) {

						// deduct cash in hand with the raw tax amount paid						
						$amount -= $raw_tax_difference;
						$tax_paid_set = $raw_tax_difference;						
						$tps_amount_paid += $raw_tax_difference;

					} else {

						// deduct cash in hand with the raw tax amount paid
						$tps_amount_paid += $amount;
						$tax_paid_set = $amount;
						$amount = 0;

					}

					

				}

				// pay surcharge
				if ( $penalty_tax_difference > 0 && $amount > 0 ) {

					if ( $amount > $penalty_tax_difference ) {

						// deduct cash in hand with the penalty tax unpaid
						$amount -= $penalty_tax_difference;
						$surcharge_paid_set = $penalty_tax_difference;
						$tps_penalty_amount_paid += $penalty_tax_difference;

					} else {

						// add penalty to tps_penalty_amount
						$tps_penalty_amount_paid += $amount;
						$surcharge_paid_set = $amount;
						$amount = 0;

					}
				
					$tps_penalty_amount_paid += $surcharge_paid_set;

				}


				// pay interest
				if ( $interest_tax_difference > 0 && $amount > 0 ) {

					if ( $amount > $interest_tax_difference ) {

						// deduct cash in hand with the penalty tax unpaid
						$amount -= $interest_tax_difference;
						$interest_paid_set = $interest_tax_difference;
						$tps_interest_amount_paid += $interest_tax_difference;

					} else {

						// add interest to tps_interest_amount
						$tps_interest_amount_paid += $amount;
						$interest_paid_set = $amount;
						$amount = 0;

					}
									
					
				}

				// --------------------------------------------------------------------------

				// ---- UPDATE PAYMENT SCHEDULE RECORD
				unset($tps_records[$i]->data_elems);
				$tps_records[$i]->data_elems[TPS_PAYMENT_AMOUNT_PAID] = $tps_amount_paid;				
				$tps_records[$i]->data_elems[TPS_PAYMENT_PENALTY_AMOUNT_PAID] = $tps_penalty_amount_paid;				
				$tps_records[$i]->data_elems[TPS_PAYMENT_INTEREST_AMOUNT_PAID] = $tps_interest_amount_paid;
				$tps_records[$i]->data_elems[TPS_PAYMENT_TOTAL_AMOUNT_PAID] = $tps_amount_paid + $tps_penalty_amount_paid + $tps_interest_amount_paid;
				$tps_records[$i]->data_elems[TPS_PAYMENT_LAST_UPDATED_BY] = $admin;
				$tps_records[$i]->data_elems[TPS_PAYMENT_LAST_UPDATE_TS] = date("Y-m-d H:i:s");
								
				$this->debug("$tps_amount_paid == $tps_amount_due && $tps_penalty_amount == $tps_penalty_amount_paid && $tps_interest_amount == $tps_interest_amount_paid ");
				
				if ( $tps_amount_paid == $tps_amount_due &&
					$tps_penalty_amount == $tps_penalty_amount_paid &&
					$tps_interest_amount == $tps_interest_amount_paid ) {

					$tps_records[$i]->data_elems[TPS_PAYMENT_STATUS] = TPS_PAYMENT_STATE_PAID;
					$tps_records[$i]->data_elems[TPS_PAYMENT_DATE] = date("Y-m-d H:i:s");

				}

				if ( $bCommit ) {

					$retUpdate = $tps_records[$i]->_update( $trans_obj, $payment_id, $admin );

				} else {

					$retUpdate = 1;

				}

				if ( $retUpdate > 0 ) {

					$this->debug("Adding OR Details [tps=$tax_paid_set,sps=$surcharge_paid_set,ips=$interest_paid_set]");

					if ( $tax_paid_set > 0 ) {

						$ret1 = $this->addORDetails( $or_code, $trans_obj->getData(TRANS_ID), $payment_id, $fee_id, $tax_fee_code, $cash_account_code, $tax_fee_account_code, $tax_paid_set, "CASH", $bCommit );

						if ( $ret1 < 0 ) {

							$this->setError(-1, "error on execute of addORDetails params( $or_code, " . $trans_obj->getData(TRANS_ID) . ", $payment_id, $fee_id, $tax_fee_code, $cash_account_code, $tax_fee_account_code, $tax_paid_set, CASH, $bCommit )!");

							// roll back here

							return -1;

						}

					} else {

						$ret2 = 1;

					}


					if ( $surcharge_paid_set > 0 ) {

						$ret2 = $this->addORDetails( $or_code, $trans_obj->getData(TRANS_ID), $payment_id, $fee_id, $tax_fee_code, $cash_account_code, $interest_account_code, $surcharge_paid_set, "SURCHARGE", $bCommit );

						if ( $ret2 < 0 ) {

							$this->setError(-2, "error on execute of addORDetails params( $or_code, " . $trans_obj->getData(TRANS_ID) . ", $payment_id, $fee_id, $tax_fee_code, $cash_account_code, $interest_account_code, $surcharge_paid_set, SURCHARGE, $bCommit )!");

							// roll back here

							return -2;
						}

					} else {

						$ret2 = 1;

					}

					if ( $interest_paid_set > 0 ) {

						$ret3 = $this->addORDetails( $or_code, $trans_obj->getData(TRANS_ID), $payment_id, $fee_id, $tax_fee_code, $cash_account_code, $surcharge_account_code, $interest_paid_set, "INTEREST", $bCommit );


						if ( $ret3 < 0 ) {

							$this->setError(-3, "error on execute of addORDetails params( $or_code, " . $trans_obj->getData(TRANS_ID) . ", $payment_id, $fee_id, $tax_fee_code, $cash_account_code, $surcharge_account_code, $interest_paid_set, INTEREST, $bCommit )!");

							// roll back here

							return -3;

						}

					} else {

						$ret3 = 1;

					}


					$this->debug("Adding OR Details [r1=$ret1,r2=$ret2,r3=$ret3]");


				}

				if ( $amount <= 0 ) {

					$this->debug("Payment done!");					

					break;

				}

			}
			
			$this->debug("CREATE OR Payment done LEFT = $amount!");
			
			if ( $bCommit ) {
				
				$retCreateOR = $this->create( $or_code, $payment_code, $trans_obj->getData(TRANS_ID), $tmp_amount, $bCommit );
	
				if ( $retCreateOR < 0 ) {
	
					// roll back here
	
					$this->debug("Error on create of OR header, return $retCreateOR!");
					$this->setError(-6,"Error on create of OR header, return $retCreateOR!");
					return -6;
	
				}
				$ret_or_code = update_system_code( $this->m_dbLink, CODES_OR_COL, $or_code);
				$ret_payment_code  = update_system_code( $this->m_dbLink, CODES_PAY_COL, $payment_code );
	
				$tmp_amount_due = $clsTPS->getTotalAmountDue( $trans_obj->getData( TRANS_ID ) );
				
				if ( $tmp_amount_due > 0 ) {
					
					$trans_obj->_setTransPaymentStatusINTERNAL( $payment_code, $amount, 'PARTIAL', $admin );
					
				} else {
					
					$trans_obj->_setTransPaymentStatusINTERNAL( $payment_code, $amount, 'PAID', $admin );
						
				}

			}
		
			// commit here
			

			return 1;

		} else {

			$this->setError(-1,"All payables have been paid.");
			return -1;

		}


	}

	function create( $or_code, $payment_code, $trans_id, $amount, $bCommit = true ) {

		$this->setData( TPOR_OR_NO, $or_code );
		$this->setData( TPOR_PAYMENT_CODE, $payment_code );
		$this->setData( TPOR_TRANS_ID, $trans_id );	
		$this->setData( TPOR_TOTAL_AMOUNT_DUE, $amount );
		$this->setData( TPOR_TOTAL_AMOUNT_PAID, $amount );
		$this->setData( TPOR_OR_DATE, date("Y-m-d H:i:s") );

		if ( $bCommit ) {

			$strValues[ TPOR_OR_NO ] = $or_code;
			$strValues[ TPOR_PAYMENT_CODE ] = $payment_code;
			$strValues[ TPOR_TRANS_ID ] = $trans_id;
			$strValues[ TPOR_TOTAL_AMOUNT_DUE ] = $amount;
			$strValues[ TPOR_TOTAL_AMOUNT_PAID ] = $amount;
			$strValues[ TPOR_OR_DATE ] = date("Y-m-d H:i:s");

			$ret = ebpls_insert_data( $this->m_dbLink, TPOR_TABLE, $strValues );

		}

		return $ret;

	}
	
	function addORDetails( $or_no, $trans_id, $payment_id, $fee_id, $tax_fee_code, $debit_account_code, $credit_account_code, $amount, $entry_type, $bCommit = true ) {

		$this->debug("addORDetails( $or_no, $trans_id, $payment_id, $tax_fee_code, $debit_account_code, $credit_account_code, $amount, $entry_type, $bCommit )");

		$clsTPORDDebit = new EBPLSTransactionPaymentsORDetails( $this->m_dbLink );
		$clsTPORDDebit->setData(TPORD_OR_NO, $or_no );
		$clsTPORDDebit->setData(TPORD_TRANS_ID, $trans_id );
		$clsTPORDDebit->setData(TPORD_PAYMENT_ID, $payment_id );
		$clsTPORDDebit->setData(TPORD_FEE_ID, $fee_id );
		$clsTPORDDebit->setData(TPORD_TAX_FEE_CODE, $tax_fee_code );
		$clsTPORDDebit->setData(TPORD_ACCOUNT_CODE, $debit_account_code );
		$clsTPORDDebit->setData(TPORD_ACCOUNT_NATURE, ACCOUNT_CODE_NATURE_DEBIT );
		$clsTPORDDebit->setData(TPORD_AMOUNT_DUE, "$amount" );
		$clsTPORDDebit->setData(TPORD_ENTRY_TYPE, $entry_type);

		if ( $bCommit ) {

			$this->debug("Commit addORDetails DEBIT");
			$clsTPORDDebit->add();

		}

		$this->or_details[] = $clsTPORDDebit;

		$clsTPORDCredit = new EBPLSTransactionPaymentsORDetails( $this->m_dbLink );
		$clsTPORDCredit->setData(TPORD_OR_NO, $or_no );
		$clsTPORDCredit->setData(TPORD_TRANS_ID, $trans_id );
		$clsTPORDCredit->setData(TPORD_PAYMENT_ID, $payment_id );
		$clsTPORDCredit->setData(TPORD_FEE_ID, $fee_id );
		$clsTPORDCredit->setData(TPORD_TAX_FEE_CODE, $tax_fee_code );
		$clsTPORDCredit->setData(TPORD_ACCOUNT_CODE, $credit_account_code );
		$clsTPORDCredit->setData(TPORD_ACCOUNT_NATURE, ACCOUNT_CODE_NATURE_CREDIT );
		$clsTPORDCredit->setData(TPORD_AMOUNT_DUE, "$amount" );
		$clsTPORDCredit->setData(TPORD_ENTRY_TYPE, $entry_type);

		if ( $bCommit ) {

			$this->debug("Commit addORDetails CREDIT");
			$clsTPORDCredit->add();

		}

		$this->or_details_credit[] = $clsTPORDDebit;

	}

	function view( $or_no = NULL ) {

		$strValues[] = "*";

		if ( $or_no == NULL ) {

			$strWhere[TPOR_OR_NO] = $this->getData( TPOR_OR_NO );

		} else {

			$strWhere[TPOR_OR_NO] = $or_no;

		}

		$records = ebpls_select_data( $this->m_dbLink, TPOR_TABLE, $strValues, $strWhere );

		if ( is_array($records) ) {

			$this->setData( NULL, $records[0] );
			$this->getPaymentDetails( $strWhere[TPOR_OR_NO] );
			return 1;

		} else {

			$this->setError( -1, "Unable to find OR # " . $strWhere[TPOR_OR_NO] . " Record.");
			return -1;

		}

	}

	function getPaymentDetails( $or_no = NULL, $acct_nature = ACCOUNT_CODE_NATURE_DEBIT ) {

		if ( $this->or_details == NULL ) {

			$this->debug("getPaymentDetails invoked, or_details var uninitialized");

			$strValues[] = "*";

			if ( $or_no == NULL ) {

				$strWhere[TPORD_OR_NO] = $this->getData( TPORD_OR_NO );

			} else {

				$strWhere[TPORD_OR_NO] = $or_no;

			}

			$strWhere[TPORD_ACCOUNT_NATURE] = $acct_nature;

			$records = ebpls_select_data( $this->m_dbLink, TPORD_TABLE, $strValues, $strWhere );

			for ( $i=0 ; $i< count($records); $i++ ) {

				$clsTPORDDebit = new EBPLSTransactionPaymentsORDetails( $this->m_dbLink );
				$clsTPORDDebit->setData( NULL, $records[$i] );
				$this->or_details[] = $clsTPORDDebit;

			}

		} else {

			$this->debug("getPaymentDetails invoked, or_details var already initialized");

		}

		return $this->or_details;

	}

	function getPaymentList( $trans_id ) {

		$strValues[] = "*";

		if ( $trans_id == NULL ) {

			$strWhere[TPOR_TRANS_ID] = $this->getData( TPOR_TRANS_ID );

		} else {

			$strWhere[TPOR_TRANS_ID] = $trans_id;

		}

		$records = ebpls_select_data( $this->m_dbLink, TPOR_TABLE, $strValues, $strWhere );

		if ( is_array($records) ) {

			for ( $i=0;$i<count($records);$i++){

				$clsOR = new EBPLSTransactionPaymentsOR( $this->m_dbLink );
				$clsOR->setData( NULL, $records[$i] );
				$clsOR->getPaymentDetails( $strWhere[TPOR_OR_NO] );
				$or_records[] = $clsOR;

			}

			return $or_records;

		} else {

			$this->setError( -1, "Unable to find OR # " . $strWhere[TPOR_OR_NO] . " Record.");
			return -1;

		}

	}

}

class EBPLSTransactionPaymentsORDetails extends DataEncapsulator  {

	function EBPLSTransactionPaymentsORDetails ( $dbLink, $bDebug = false ) {

		$this->m_dbLink = $dbLink;
		$this->setDebugMode( $bDebug );

		$this->addDataElement(TPORD_OR_NO, "is_not_empty", "[VALUE]" );
		$this->addDataElement(TPORD_OR_DETAIL_ID, NULL, NULL, true);
		$this->addDataElement(TPORD_TRANS_ID, "is_valid_number", "[VALUE]" );
		$this->addDataElement(TPORD_PAYMENT_ID, "is_not_empty", "[VALUE]" );
		$this->addDataElement(TPORD_FEE_ID, "is_not_empty", "[VALUE]" );
		$this->addDataElement(TPORD_TAX_FEE_CODE, "is_not_empty", "[VALUE]" );
		$this->addDataElement(TPORD_ACCOUNT_CODE, "is_not_empty", "[VALUE]" );
		$this->addDataElement(TPORD_ACCOUNT_NATURE, "is_not_empty", "[VALUE]" );
		$this->addDataElement(TPORD_AMOUNT_DUE, "is_not_empty", "[VALUE]" );
		$this->addDataElement(TPORD_ENTRY_TYPE, "is_not_empty", "[VALUE]" );
		$this->addDataElement(TPORD_TS, "is_valid_date", "[VALUE]", true );

	}

	function add(){

		$dt = date("Y-m-d H:i:s");
		$this->data_elems[ TPORD_TS ] = $dt;

		$strValues = $this->data_elems;
		//print_r($strValues);

		if ( $this->validateData() > 0 ) {

			$ret = ebpls_insert_data( $this->m_dbLink, TPORD_TABLE, $strValues );

			if ( $ret < 0 ) {

				$this->debug("TPORD ADD DETAIL ERROR : $ret");
				return $ret;

			} else {

				$this->debug("TPORD ADD DETAIL OK : $ret");
				$this->data_elems[ TPORD_OR_DETAIL_ID ] = $ret;
				return $ret;

			}

		} else {

			$this->debug("TPORD ADD DETAIL FAILED." );
			$trans_obj->m_arrError = $this->getError();
			return -1;

		}

	}

}

?>
