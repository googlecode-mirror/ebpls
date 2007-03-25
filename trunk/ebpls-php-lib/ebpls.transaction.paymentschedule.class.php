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

define("TPS_TABLE","ebpls_transaction_payment_schedule");

// define ebpls_transaction_payment_schedule data elements
define(TPS_PAYMENT_ID,"payment_id");
define(TPS_TRANS_ID,"trans_id");
define(TPS_FEE_ID,"fee_id");
define(TPS_GROUP_ID,"group_id");
define(TPS_PAYMENT_MODE,"payment_mode");
define(TPS_PAYMENT_FEE_ID,"fee_id");
define(TPS_PAYMENT_TAX_FEE_CODE,"payment_tax_fee_code");
define(TPS_PAYMENT_ACCOUNT_CODE,"payment_account_code");
define(TPS_PAYMENT_DATE,"payment_date");
define(TPS_PAYMENT_DUE_DATE,"payment_due_date");
define(TPS_PAYMENT_TOTAL_AMOUNT_DUE,"payment_total_amount_due");
define(TPS_PAYMENT_AMOUNT_PAID,"payment_amount_paid");
define(TPS_PAYMENT_PENALTY_AMOUNT,"payment_penalty_amount");
define(TPS_PAYMENT_PENALTY_AMOUNT_PAID,"payment_penalty_amount_paid");
define(TPS_PAYMENT_INTEREST_AMOUNT,"payment_interest_amount");
define(TPS_PAYMENT_INTEREST_AMOUNT_PAID,"payment_interest_amount_paid");
define(TPS_PAYMENT_TOTAL_AMOUNT_PAID,"payment_total_amount_paid");
define(TPS_PAYMENT_STATUS,"payment_status");
define(TPS_PAYMENT_LAST_UPDATED_BY,"payment_last_updated_by");
define(TPS_PAYMENT_LAST_UPDATE_TS,"payment_last_update_ts");
define(TPS_PAYMENT_REMARKS,"payment_remarks");
define(TPS_PAYMENT_SYNCH_UPDATE,"payment_synch_update");

class EBPLSTransactionPaymentSchedule extends DataEncapsulator {

	function EBPLSTransactionPaymentSchedule ( $dbLink, $bDebug = false ) {

		$this->m_dbLink = $dbLink;
		$this->setDebugMode( $bDebug );
		$this->setDebugMode( true );

		$this->addDataElement(TPS_FEE_ID, "is_valid_number", "[VALUE]", true );
		$this->addDataElement(TPS_PAYMENT_ID, "is_valid_number", "[VALUE]", true );
		$this->addDataElement(TPS_GROUP_ID, "is_valid_number", "[VALUE]", true );
		$this->addDataElement(TPS_PAYMENT_MODE, "is_valid_number", "[VALUE]", true );
		$this->addDataElement(TPS_PAYMENT_TAX_FEE_CODE, "is_not_empty", "[VALUE]", true );
		$this->addDataElement(TPS_PAYMENT_ACCOUNT_CODE, "is_not_empty", "[VALUE]", true );
		$this->addDataElement(TPS_PAYMENT_DUE_DATE, "is_valid_date", "[VALUE]", true );
		$this->addDataElement(TPS_PAYMENT_TOTAL_AMOUNT_DUE, "is_not_empty", "[VALUE]" );
		$this->addDataElement(TPS_PAYMENT_PENALTY_AMOUNT, "is_valid_number", "[VALUE]", true );
		$this->addDataElement(TPS_PAYMENT_PENALTY_AMOUNT_PAID, NULL, NULL, true );
		$this->addDataElement(TPS_PAYMENT_INTEREST_AMOUNT, "is_valid_number", "[VALUE]", true );
		$this->addDataElement(TPS_PAYMENT_INTEREST_AMOUNT_PAID, NULL, NULL, true );
		$this->addDataElement(TPS_PAYMENT_TOTAL_AMOUNT_PAID, "is_not_empty", "[VALUE]", true );
		$this->addDataElement(TPS_PAYMENT_STATUS, "is_not_empty", "[VALUE]", true );
		$this->addDataElement(TPS_PAYMENT_LAST_UPDATED_BY, "is_not_empty", "[VALUE]", true );
		$this->addDataElement(TPS_PAYMENT_LAST_UPDATE_TS, "is_valid_date", "[VALUE]" , true );
		$this->addDataElement(TPS_TRANS_ID, "is_valid_number", "[VALUE]", true  );

		$this->addDataElement(TPS_PAYMENT_DATE, NULL, "[VALUE]", true );
		$this->addDataElement(TPS_PAYMENT_REMARKS, "is_not_empty", "[VALUE]" );

		$this->addDataElement(TPS_PAYMENT_SYNCH_UPDATE, NULL, NULL );

	}

	function createPaymentSchedule( &$trans_obj, $payment_mode, $bSeparateTaxesFromFees, $bIsUpdate, $admin ) {

		$arrayDivisor = array( TRANS_PAYMENT_MODE_MONTHLY =>12,
				TRANS_PAYMENT_MODE_QUARTERLY => 4,
				TRANS_PAYMENT_MODE_SEMIANNUAL => 2,
				TRANS_PAYMENT_MODE_ANNUAL => 1 );

		$divisor = $arrayDivisor[$payment_mode];
		
		if ( $divisor == "" ) {
			
			$this->setError( -1, "Payment mode invalid $payment_mode!" );
			$this->debug("Payment mode invalid $payment_mode!");
			return -1;

		}
		
		$this->debug("createPaymentSchedule( &$trans_obj, $payment_mode, $bSeparateTaxesFromFees, $bIsUpdate, $admin )");

		$clsPayables = new EBPLSTransactionFee( $this->m_dbLink, false);
		$i = 0;

		if ( $bSeparateTaxesFromFees ) {

			// group all fee records payments as one payment
			$fee_records = $clsPayables->listFee( $trans_obj->getData( TRANS_ID ), Array(PAYABLE_TYPE_FEE,PAYABLE_TYPE_TAX) );
			$i = $this->_createPaymentSchedule( $trans_obj, $payment_mode, $fee_records, 1, $bIsUpdate );

			$this->debug("created payment sched for fees $i");

			$payable_amount = 0;

			if ( is_array($fee_records) && count($fee_records)) {

				for ( $fee_index = 0; $fee_index < count($fee_records); $fee_index++ ) {

					$payable_amount += $fee_records[$fee_index]->getData(TF_TAX_TOTAL_AMOUNT_DUE);

				}

			}

			// distribute tax payments to the opted payment schedule
			$tax_records = $clsPayables->listFee( $trans_obj->getData( TRANS_ID ), PAYABLE_TYPE_BUSTAX );
			$i += $this->_createPaymentSchedule( $trans_obj, $payment_mode, $tax_records, $divisor, $bIsUpdate );

			if ( is_array($tax_records) && count($tax_records)) {

				for ( $fee_index = 0; $fee_index < count($tax_records); $fee_index++ ) {

					$payable_amount += $tax_records[$fee_index]->getData(TF_TAX_TOTAL_AMOUNT_DUE);

				}

			}

			$this->debug("created payment sched for taxes $i, pay = $payable_amount");

		} else {

			// distribute taxes/fees payments to the opted payment schedule
			$fee_records = $clsPayables->listFee( $trans_obj->getData( TRANS_ID ) );
			$i = $this->_createPaymentSchedule( $trans_obj, $payment_mode, $fee_records, $divisor, $bIsUpdate );
						

			$payable_amount = 0;
			if ( is_array($fee_records) && count($fee_records)) {

				for ( $fee_index = 0; $fee_index < count($fee_records); $fee_index++ ) {

					$payable_amount += $fee_records[$fee_index]->getData(TF_TAX_TOTAL_AMOUNT_DUE);

				}

			}

			$this->debug("created payment sched for taxes/fees $i, pay = $payable_amount");

		}

		if ( $i >  0 ) {

			$this->debug("_setTransPaymentModeINTERNAL $payable_amount");

			// update parent table payment mode and payment mode
			$trans_obj->_setTransPaymentModeINTERNAL( $payment_mode, $payable_amount, NULL, $bSeparateTaxesFromFees, $admin );

		}

		return $i;

	}

	function _createPaymentSchedule( &$trans_obj, $payment_mode, $fee_records, $divisor, $bIsUpdate = false ) {

		$this->debug(" _createPaymentSchedule( &$trans_obj, $fee_records, $divisor, $bIsUpdate = false )");

		if ( is_array($fee_records) && count($fee_records) > 0 ) {


			// for each tax/fee distribute amount according to the opted
			// division of payment mothly,quarterly,semiannual or yearly
			for ( $fee_index = 0; $fee_index < count($fee_records); $fee_index++ ) {

				$payable_amount = $fee_records[$fee_index]->getData(TF_TAX_TOTAL_AMOUNT_DUE);
				$tax_fee_code = $fee_records[$fee_index]->getData(TF_TAX_FEE_CODE);
				$fee_id	= $fee_records[$fee_index]->getData(TF_FEE_ID);
				$account_code= $fee_records[$fee_index]->getData(TF_TAX_ACCOUNT_CODE);

				$this->debug("payable amount : $payable_amount");

				if ( $divisor < 12 ) {
					$groups = $divisor;
					$amount = round($payable_amount/$divisor,2);
					$remainder =  $payable_amount - ($amount*$divisor);
					$delta = 12/$divisor;
					$start_mo = date("m");
				} else {
					$groups = (12-date("m"));
					$amount = round($payable_amount/($groups),2);
					$remainder =  $payable_amount - ($amount*($groups));
					$delta = 1;
					$start_mo = date("m");
				}


				if ( $bIsUpdate ) {

					// total amount paid excluding surcharge and penalties (only raw payment)
					$paid_amount = $this->getTotalAmountPaid( $trans_obj->getData( TRANS_ID ), $tax_fee_code, TPS_PAYMENT_STATE_PAID );

					$this->_deleteINTERNAL( $trans_obj, $fee_id, TPS_PAYMENT_STATE_UNPAID );
					$this->_updateINTERNAL_VALUE( $trans_obj, $fee_id, $admin );

					// deduct total payable with the paid amount, resulting amount
					// is the amount we will be distributing for the payment  schedule
					$payable_amount = $payable_amount - $paid_amount;

				}

				$due_day = $this->getSystemDueDay();
				$cur_day = date("d");

				if ( $due_day < $cur_day ) {
					$due_day = $cur_day;
				}

				//echo "groups = $groups<BR>";
				for ( $i = 0, $mo = $start_mo; $i < $groups; $i++, $mo += $delta ) {

					//echo "[$i]=EXCEED cur mo = $mo, delta = $delta<BR>";
					if ( intval(date ( "Y", mktime( 23, 59, 00, $mo, $due_day, date("Y") ) )) > intval(date("Y")) ) {

						// dec 31 of the year is the last due date of all payables
						//echo "[$i]=EXCEED cur year : " . date ( "Y-m-d H:i:s", mktime( 23, 59, 00, $mo, $due_day, date("Y") ) ) . "<BR>";
						$payment_due_date = date ( "Y-m-d H:i:s", mktime( 23, 59, 00, 12, 31, date("Y") ) );

					} else {

						//echo "[$i]=EXCEED cur year : " . date ( "Y-m-d H:i:s", mktime( 23, 59, 00, $mo, $due_day, date("Y") ) ) . "<BR>";
						$payment_due_date = date ( "Y-m-d H:i:s", mktime( 23, 59, 00, $mo, $due_day, date("Y") ) );

					}



					if ( $i == $groups - 1 ) {

						$amount = $amount + $remainder;

					}

					if ( $this->_addINTERNAL( $trans_obj, ($i+1), $fee_id, $tax_fee_code, $account_code, $payment_mode, $payment_due_date, $amount, $admin ) <= 0 ) {
						
						$this->debug("_addINTERNAL( $trans_obj, ($i+1), $fee_id, $tax_fee_code, $account_code, $payment_mode, $payment_due_date, $amount, $admin ) failed!");
						return -1;

					}

				}

			}

			return $i;

		}

		return 0;

	}

	function getSystemDueDay(){

		$system_due_date = get_next_system_code( $this->m_dbLink, CODES_PAYMENT_DUE );

		if ( ( $tm = strtotime($system_due_date) ) === -1 ) {

			$due_day = 20; // default is 20th of the month depending on payment mode

		} else {

			$due_day = date("d", $tm);

		}

		return $due_day;

	}

	function updatePaymentSchedule( &$trans_obj, $payment_mode, $bSeparateTaxesFromFees, $admin ) {

		return $this->createPaymentSchedule( $trans_obj, $payment_mode, $bSeparateTaxesFromFees, true, $admin );

	}

	// only invoked internally by this class, should not be invoked outside
	function _addINTERNAL( &$trans_obj, $group_id, $fee_id, $tax_fee_code, $account_code,  $payment_mode, $date_due, $amount_due, $creator ) {

		$this->data_elems[ TPS_PAYMENT_DATE ] = null;
		$this->data_elems[ TPS_PAYMENT_REMARKS ] = "Added " . date("Y-m-d H:i:s") . " by $creator.\n";
		$this->data_elems[ TPS_FEE_ID ] = $fee_id;
		$this->data_elems[ TPS_GROUP_ID ] = $group_id;
		$this->data_elems[ TPS_PAYMENT_MODE ] = $payment_mode;
		$this->data_elems[ TPS_PAYMENT_TAX_FEE_CODE ] = $tax_fee_code;
		$this->data_elems[ TPS_PAYMENT_ACCOUNT_CODE ] = $account_code;
		$this->data_elems[ TPS_PAYMENT_DUE_DATE ] = $date_due;
		$this->data_elems[ TPS_PAYMENT_STATUS ] = TPS_PAYMENT_STATE_UNPAID;
		$this->data_elems[ TPS_PAYMENT_TOTAL_AMOUNT_DUE ] = $amount_due;
		$this->data_elems[ TPS_TRANS_ID ] = $trans_obj->getData( TRANS_ID );

		$dt = date("Y-m-d H:i:s");
		$this->data_elems[ TPS_PAYMENT_LAST_UPDATED_BY ] = $creator;
		$this->data_elems[ TPS_PAYMENT_LAST_UPDATE_TS ] = $dt;

		if ( $this->validateData() > 0 ) {

			$strValues = $this->data_elems;

			//print_r($strValues);

			$ret = ebpls_insert_data( $this->m_dbLink, TPS_TABLE, $strValues );

			if ( $ret < 0 ) {

				$this->debug("TPS ADD ERROR : $ret");
				$trans_obj->setError( $ret, get_db_error());
				return $ret;

			} else {

				$this->debug("TPS ADD OK : $ret");
				return $ret;

			}


			$this->debug("TPS ADD FEE FAILED." );
			$trans_obj->m_arrError = $this->getError();
			return -1;


		} else {

			return -1;

		}

	}

	/**
	 * Used on update payment, delete only those payment schedules which has no amount paid
	 *
	 *
	 **/
	function _deleteINTERNAL( &$trans_obj, $fee_id, $payment_status ) {

		$strWhere[TPS_TRANS_ID] = $trans_obj->getData( TRANS_ID );
		$strWhere[TPS_FEE_ID] = $fee_id;
		$strWhere[TPS_PAYMENT_STATUS] = $payment_status;
		$strWhere[TPS_PAYMENT_AMOUNT_PAID] = array(" = ", 0.0 );

		$result = ebpls_delete_data ( $this->m_dbLink, TPS_TABLE, $strWhere );

		if ( $result < 0 )  {

			$this->setError( $result, get_db_error() );

		}

		return $result;

	}


	/**
	 * used after _deleteINTERAL set amount due equal to amount paid to all partially paid tax/fees of a given tax/fee code
	 * This will balance out partial payments to scheduled tax/fees
	 **/
	function _updateINTERNAL_VALUE( &$trans_obj, $fee_id, $admin ) {

		$records = $this->listPaymentScheduleByTaxFee( $trans_obj->getData(TRANS_ID), $fee_id );

		if ( is_array($records) ) {

			$dt = date("Y-m-d H:i:s");

			for ( $i = 0 ; $i < count($records); $i++ ) {

				$strValues[ TPS_PAYMENT_LAST_UPDATED_BY ] = $admin;
				$strValues[ TPS_PAYMENT_LAST_UPDATE_TS ] = $dt;
				$strValues[ TPS_PAYMENT_TOTAL_AMOUNT_DUE ] = $records[$i]->getData(TPS_PAYMENT_AMOUNT_PAID);
				$strValues[ TPS_PAYMENT_STATUS ] = TPS_PAYMENT_STATE_UNPAID;

				$strWhere[ TPS_PAYMENT_ID ] = $records[$i]->getData(TPS_PAYMENT_ID);

				$ret = ebpls_update_data( $this->m_dbLink, TPS_TABLE, $strValues, $strWhere );

				if ( $ret < 0 ) {

					$this->debug("TPS UPDATE ERROR : $ret" );
					return $ret;

				} else {

					$this->debug("TPS UPDATE OK : $ret" );
					return $ret;

				}

			}

		}


	}


	/**
	 **/
	function _update( &$trans_obj, $payment_id, $admin ) {

		$strValues = $this->data_elems;
		$strValues[ TPS_PAYMENT_LAST_UPDATED_BY ] = $admin;
		$strValues[ TPS_PAYMENT_LAST_UPDATE_TS ] = $dt;
		$strWhere[ TPS_PAYMENT_ID ] = $payment_id;
		$strWhere[ TPS_TRANS_ID ] = $trans_obj->getData(TRANS_ID);

		$ret = ebpls_update_data( $this->m_dbLink, TPS_TABLE, $strValues, $strWhere );

		if ( $ret < 0 ) {

			$this->debug("TPS _update ERROR : $ret" );
			return $ret;

		} else {

			$this->debug("TPS _update OK : $ret" );
			return $ret;

		}
	}


	function _updateINTERNAL( &$trans_obj, $payment_id, $amount_paid, $amount_surcharge_paid, $amount_interest_paid, $payment_status, $admin ) {

		$dt = date("Y-m-d H:i:s");
		$strValues[ TPS_PAYMENT_LAST_UPDATED_BY ] = $admin;
		$strValues[ TPS_PAYMENT_LAST_UPDATE_TS ] = $dt;
		$strValues[ TPS_PAYMENT_PENALTY_AMOUNT ] = $amount_surcharge_paid;
		$strValues[ TPS_PAYMENT_INTEREST_AMOUNT ] = $amount_interest_paid;
		$strValues[ TPS_PAYMENT_AMOUNT_PAID ] = $amount_paid;

		if ( $payment_status == TPS_PAYMENT_STATE_PAID ) {

			$strValues[ TPS_PAYMENT_DATE ] = date("Y-m-d H:i:s");

		}

		$strValues[ TPS_PAYMENT_STATUS ] = $payment_status;

		$strWhere[ TPS_PAYMENT_ID ] = $payment_id;

		$ret = ebpls_update_data( $this->m_dbLink, TPS_TABLE, $strValues, $strWhere );

		if ( $ret < 0 ) {

			$this->debug("TPS UPDATE ERROR : $ret" );
			return $ret;

		} else {

			$this->debug("TPS UPDATE OK : $ret" );
			return $ret;

		}

	}
	
	function updateDueDate( $payment_id, $due_date, $remarks, $admin ) {

		$dt = date("Y-m-d H:i:s");
		$strValues[ TPS_PAYMENT_LAST_UPDATED_BY ] = $admin;
		$strValues[ TPS_PAYMENT_LAST_UPDATE_TS ] = $dt;
		$strValues[ TPS_PAYMENT_DUE_DATE ] = $due_date . " 23:59:59";
		$strValues[ TPS_PAYMENT_REMARKS ] = $remarks;
		$strWhere[TPS_PAYMENT_ID] = $payment_id;

		$ret = ebpls_update_data( $this->m_dbLink, TPS_TABLE, $strValues, $strWhere );

		if ( $ret < 0 ) {

			$this->debug("TPS UPDATE ERROR : $ret" );
			return $ret;

		} else {

			$this->debug("TPS UPDATE OK : $ret" );
			return $ret;

		}

	}


	function loadPaymentSchedule( $payment_id = NULL ) {

		$strValues[$key] = "*";

		if ( $payment_id == NULL ) {
			$strWhere[TPS_PAYMENT_ID] = $this->getData(TPS_PAYMENT_ID);
		} else {
			$strWhere[TPS_PAYMENT_ID] = $payment_id;
		}

		$result = ebpls_select_data( $this->m_dbLink, TPS_TABLE, $strValues, $strWhere, NULL, $strOrderBy, "DESC", NULL );

		if ( is_array($result) ) {

			$this->data_elems = $result[0];
			$this->debug("loadFeeCodeData ok.");
			return $result;

		} else {

			$this->setError( $result, get_db_error() );
			return -1;

		}

	}

	function listPaymentSchedule( $trans_id, $payment_state = NULL, $orderKey = NULL, $bDesc = true ) {

		$strValues[$key] = "*";

		$strWhere[TPS_TRANS_ID] = $trans_id;

		if( $payment_state ) {

			$strWhere[TPS_PAYMENT_STATUS] = $payment_state;

		}

		if ( $orderKey ) {

			$strOrderBy[$orderKey] = $orderKey;

		}

		$result = ebpls_select_data( $this->m_dbLink, TPS_TABLE, $strValues, $strWhere, NULL, $strOrderBy, $bDesc?"DESC":"ASC", NULL );

		if ( is_array($result) ) {

			for ( $i = 0 ; $i < count($result); $i++ ) {

				$records[$i] = new EBPLSTransactionPaymentSchedule( $this->m_dbLink, false );
				$records[$i]->setData(NULL, $result[$i]);


			}

			return $records;

		} else {

			$this->setError( $result, get_db_error() );
			return -1;

		}

	}
	
	function listSynchPaymentSchedule( $trans_id, $payment_state = NULL, $orderKey = NULL, $bDesc = true ) {

		$strValues[$key] = "*";

		$strWhere[TPS_TRANS_ID] = $trans_id;		
		$strWhere[TPS_PAYMENT_STATUS] = $payment_state;
		$strWhere[TPS_PAYMENT_SYNCH_UPDATE] = Array( " < ",  date("Y-m-d")  );

		if ( $orderKey ) {

			$strOrderBy[$orderKey] = $orderKey;

		}

		$result = ebpls_select_data( $this->m_dbLink, TPS_TABLE, $strValues, $strWhere, NULL, $strOrderBy, $bDesc?"DESC":"ASC", NULL );

		if ( is_array($result) ) {

			for ( $i = 0 ; $i < count($result); $i++ ) {

				$records[$i] = new EBPLSTransactionPaymentSchedule( $this->m_dbLink, false );
				$records[$i]->setData(NULL, $result[$i]);


			}

			return $records;

		} else {

			$this->setError( $result, get_db_error() );
			return -1;

		}

	}
	
	function listPaymentScheduleByTaxFee( $trans_id, $fee_id, $orderKey = TPS_PAYMENT_DUE_DATE, $bDesc = true ) {

		$strValues[$key] = "*";

		$strWhere[TPS_TRANS_ID] = $trans_id;
		$strWhere[TPS_FEE_ID ] = $fee_id;

		if ( $orderKey ) {

			$strOrderBy[$orderKey] = $orderKey;

		}

		$result = ebpls_select_data( $this->m_dbLink, TPS_TABLE, $strValues, $strWhere, NULL, $strOrderBy, $bDesc?"DESC":"ASC", NULL );

		if ( is_array($result) ) {

			for ( $i = 0 ; $i < count($result); $i++ ) {

				$records[$i] = new EBPLSTransactionPaymentSchedule( $this->m_dbLink, false );
				$records[$i]->setData(NULL, $result[$i]);

			}

			return $records;

		} else {

			$this->setError( $result, get_db_error() );
			return -1;

		}

	}

	/**
	 * Updates payments schedules accumulated interest/surcharges.
	 *
	 *
	 **/
	function updatePaymentSchedulesInterestSurcharges( &$trans_obj ) {

		// retrieve payment schedule list which are not yet fully paid
		//$tps_records = $this->listPaymentSchedule( $trans_obj->getData(TRANS_ID), TPS_PAYMENT_STATE_UNPAID, TPS_PAYMENT_DUE_DATE, false );
		$tps_records = $this->listSynchPaymentSchedule( $trans_obj->getData(TRANS_ID), TPS_PAYMENT_STATE_UNPAID, TPS_PAYMENT_DUE_DATE, false);
		
		if ( is_array($tps_records) ) {

			$tmp_tps_included_records = NULL;

			mysql_query("LOCK TABLES", $this->m_dbLink);

			$clsAccounts = new EBPLChartOfAccountsSysRef( $this->m_dbLink, false );

			$cash_account_code = $clsAccounts->getCashAccountCode();

			if ( $cash_account_code < 0 ) {

				$this->debug(-5,"Unable to load cash account code, return $cash_account_code!");
				$this->setError(-5,"Unable to load cash account code, return $cash_account_code!");
				return -5;

			}
			
			for ( $i = 0; $i < count($tps_records); $i++ ) {

				// ---- LOAD TRANSACTOPM RECORD DATA
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
				$tps_due_date = $tps_records[$i]->getData(TPS_PAYMENT_DUE_DATE);
				$tps_synch_date = $tps_records[$i]->getData(TPS_PAYMENT_SYNCH_UPDATE);

				if ( $tps_synch_date == date("Y-m-d") ) {

					// skip those whose interest/surcharges have been updated...
					continue;

				}

				$clsTaxFee = new EBPLTaxFeeSysRef( $this->m_dbLink, false );

				// ---- RETRIEVE TAX/FEE DATA
				$clsTaxFeePayable = $clsTaxFee->loadTaxFee( $tax_fee_code );
				$clsTaxFeeSurcharge = $clsTaxFee->loadTaxFee( $clsTaxFeePayable->getData(EBPLS_SURCHARGE_TAX_FEE_CODE) );
				$clsTaxFeeInterest = $clsTaxFee->loadTaxFee( $clsTaxFeePayable->getData(EBPLS_INTEREST_TAX_FEE_CODE) );

				if ( $clsTaxFeePayable == -1 || $clsTaxFeeSurcharge == -1 || $clsTaxFeeInterest== -1 ) {

					$this->debug("Unable to load tax fee code $tax_fee_code," . $clsTaxFeePayable->getData(EBPLS_SURCHARGE_TAX_FEE_CODE) . "," . $clsTaxFeePayable->getData(EBPLS_INTEREST_TAX_FEE_CODE) );
					$this->setError( -1, "Unable to load tax fee code $tax_fee_code," . $clsTaxFeePayable->getData(EBPLS_SURCHARGE_TAX_FEE_CODE) . "," . $clsTaxFeePayable->getData(EBPLS_INTEREST_TAX_FEE_CODE) );
					return -1;

				}

				// retrieve account codes
				$tax_fee_account_code = $clsTaxFeePayable->getData(EBPLS_TAX_ACCOUNT_CODE);
				$surcharge_account_code = $clsTaxFeeSurcharge->getData(EBPLS_TAX_ACCOUNT_CODE);
				$interest_account_code = $clsTaxFeeInterest->getData(EBPLS_TAX_ACCOUNT_CODE);

				// ---- LOAD FORMULA
				//$clsTaxFeePayableFormula = $clsTaxFeePayable->getData(EBPLS_TAX_FORMULA); // we dont need this, used on setting of payment mode only
				$clsTaxFeeSurchargeFormula = $clsTaxFeeSurcharge->getData(EBPLS_TAX_FORMULA);
				$clsTaxFeeInterestFormula = $clsTaxFeeInterest->getData(EBPLS_TAX_FORMULA);

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

				// ---- COMPUTE SURCHARGE / INTEREST
				$surcharge_amount = $clsTaxFeeSurchargeFormula->computeSurcharge( $total_amount_payable, $tps_due_date );
				$interest_amount = $clsTaxFeeSurchargeFormula->computeSurcharge( $total_amount_payable, $tps_due_date );

				$this->debug( "SURCHARGE $surcharge_amount, INTEREST $interest_amount");

				// add interest / surcharge to current taxes surcharge/interest balance amount
				$tps_penalty_amount += $surcharge_amount;
				$tps_interest_amount += $interest_amount;

				// --------------------------------------------------------------------------

				// ---- UPDATE PAYMENT SCHEDULE RECORD
				unset($tps_records[$i]->data_elems);
				$tps_records[$i]->data_elems[TPS_PAYMENT_AMOUNT_PAID] = $tps_amount_paid;
				$tps_records[$i]->data_elems[TPS_PAYMENT_PENALTY_AMOUNT] = $tps_penalty_amount;
				$tps_records[$i]->data_elems[TPS_PAYMENT_PENALTY_AMOUNT_PAID] = $tps_penalty_amount_paid;
				$tps_records[$i]->data_elems[TPS_PAYMENT_INTEREST_AMOUNT] = $tps_interest_amount;
				$tps_records[$i]->data_elems[TPS_PAYMENT_INTEREST_AMOUNT_PAID] = $tps_interest_amount_paid;
				$tps_records[$i]->data_elems[TPS_PAYMENT_TOTAL_AMOUNT_PAID] = $total_amount_paid;
				$tps_records[$i]->data_elems[TPS_PAYMENT_SYNCH_UPDATE] = date("Y-m-d");

				$retUpdate = $tps_records[$i]->_update( $trans_obj, $payment_id, $admin );

				if ( $retUpdate <= 0 ) {

					// invalid return, do rollback
					// call rollback
					$this->debug("Invalid return on _update on updatePaymentSchedules function, return $retUpdate!");
					$this->setError( -1, "Invalid return on _update on updatePaymentSchedules function, return $retUpdate!");
					return -1;

				}

			}

			mysql_query("UNLOCK TABLES", $this->m_dbLink);

			return 1;

		} else {

			//$this->debug("Unable to load transaction payment schedules on updatePaymentSchedules function, return $tps_records!");
			$this->setError(-1,"Unable to load transaction payment schedules on updatePaymentSchedules function, return $tps_records!");
			return -1;

		}

	}




	/*********************************************************************
	 * returns only the amount PAID or UNPAID, amount is raw amount computed given payment mode
	 * this is less the penalties and surcharges applicant have paid
	 *********************************************************************/
	function getTotalAmountPaid( $trans_id, $tax_fee_code = NULL, $payment_status = NULL ){


		/*
		if ( $payment_status != TPS_PAYMENT_STATE_PAID && $payment_status != TPS_PAYMENT_STATE_UNPAID ) {

			$this->setError(-1, "Invalid payment state passed.");
			return -1;

		}
		*/

		$strValues[$key] = " sum(payment_amount_paid) as total_amount ";
		$strWhere[TPS_TRANS_ID] = $trans_id;
		if ( $tax_fee_code != NULL ) {
			$strWhere[TPS_PAYMENT_TAX_FEE_CODE] = $tax_fee_code;
		}

		if ( $payment_status ) {

			$strWhere[TPS_PAYMENT_STATUS] = $payment_status;

		}

		$result = ebpls_select_data( $this->m_dbLink, TPS_TABLE, $strValues, $strWhere, NULL, $strOrderBy, "DESC", NULL );

		if ( is_array($result) ) {

			$this->debug("getTotalAmountDue ok.");
			return $result[0]["total_amount"];

		} else {

			$this->setError( $result, get_db_error() );
			return -1;

		}


	}

	function getTotalAmountDue( $trans_id, $tax_fee_code = NULL, $payment_status = NULL ){

		$strValues[$key] = " sum(payment_total_amount_due) as t1,sum(payment_amount_paid) as t2,sum(payment_penalty_amount) as p1,sum(payment_penalty_amount_paid) as p2,sum(payment_interest_amount) as i1,sum(payment_interest_amount_paid) as i2 ";

		$strWhere[TPS_TRANS_ID] = $trans_id;
		
		if ( $tax_fee_code != NULL ) {
			
			$strWhere[TPS_PAYMENT_TAX_FEE_CODE] = $tax_fee_code;
			
		}

		if ( $payment_status != NULL ) {

			$strWhere[TPS_PAYMENT_STATUS] = $payment_status;

		}

		$result = ebpls_select_data( $this->m_dbLink, TPS_TABLE, $strValues, $strWhere, NULL, $strOrderBy, "DESC", NULL );

		if ( is_array($result) ) {			
			
			$row = $result[0];
			$amount_due = floatval($row["t1"]) - floatval($row["t2"]);
			$amount_due += floatval($row["p1"]) - floatval($row["p2"]);
			$amount_due += floatval($row["i1"]) - floatval($row["i2"]);
			
			//$this->debug("getTotalAmountDue ok, return $amount_due.");
			
			return round($amount_due,2);

		} else {

			$this->setError( $result, get_db_error() );
			return -1;

		}


	}

}

?>
