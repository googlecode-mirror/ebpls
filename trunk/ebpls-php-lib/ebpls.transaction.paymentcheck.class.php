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
require_once("ebpls-php-lib/ebpls.transaction.paymentschedule.class.php");

define(TPC_TABLE,"ebpls_transaction_payment_check");
define(TPC_TRANS_ID,"trans_id");
define(TPC_CHECK_ID,"check_id");
define(TPC_CHECK_NO,"check_no");
define(TPC_OR_NO,"or_no");
define(TPC_CHECK_NAME,"check_name");
define(TPC_CHECK_AMOUNT,"check_amount");
define(TPC_CHECK_ISSUE_DATE,"check_issue_date");
define(TPC_CHECK_STATUS,"check_status");
define(TPC_TS_CREATE,"ts_create");
define(TPC_ADMIN,"admin");
define(TPC_REMARK,"remark");
define(TPC_TS_LAST_UPDATE,"ts_last_update");



class EBPLSTransactionPaymentsCheck extends DataEncapsulator {	

	function EBPLSTransactionPaymentsCheck ( $dbLink, $bDebug = false ) {

		$this->m_dbLink = $dbLink;
		$this->setDebugMode( $bDebug );

		$this->addDataElement(TPC_CHECK_ID, "is_valid_number", "[VALUE]", true );
		$this->addDataElement(TPC_TRANS_ID, "is_valid_number", "[VALUE]" );
		$this->addDataElement(TPC_CHECK_NO, "is_not_empty", "[VALUE]" );
		$this->addDataElement(TPC_CHECK_NAME, "is_not_empty", "[VALUE]" );
		$this->addDataElement(TPC_CHECK_STATUS, "is_not_empty", "[VALUE]" );
		$this->addDataElement(TPC_CHECK_AMOUNT, "is_valid_number", "[VALUE]" );
		$this->addDataElement(TPC_CHECK_ISSUE_DATE, "is_valid_number", "[VALUE]" );		
		$this->addDataElement(TPC_TS_CREATE, "is_valid_date", "[VALUE]" );		
		$this->addDataElement(TPC_ADMIN, "is_not_empty", "[VALUE]" );
		$this->addDataElement(TPC_REMARK, NULL, "[VALUE]" );
		$this->addDataElement(TPC_TS_LAST_UPDATE, "is_valid_date", "[VALUE]" );
		
		$this->or_details = NULL;

	}
	
	function addCheckPayment( $trans_id, $check_name,  $check_no, $check_amount, $check_date, $admin ) {
	
		$this->data_elems[ TPC_TRANS_ID ] = $trans_id;
		$this->data_elems[ TPC_CHECK_NO ] = $check_no;		
		$this->data_elems[ TPC_CHECK_NAME ] = $check_name;
		$this->data_elems[ TPC_CHECK_STATUS ] = CHECK_STATUS_PENDING;
		$this->data_elems[ TPC_CHECK_AMOUNT ] = $check_amount;
		$this->data_elems[ TPC_CHECK_ISSUE_DATE ] = $check_date;
		$this->data_elems[ TPC_TS_CREATE ] = date("Y-m-d H:i:s");
		$this->data_elems[ TPC_ADMIN ] = $admin;
		$this->data_elems[ TPC_TS_LAST_UPDATE ] = date("Y-m-d H:i:s");
		$ret = ebpls_insert_data( $this->m_dbLink, TPC_TABLE, $this->data_elems );
		
		if ( $ret > 0 ) {
			
			$this->data_elems[ TPC_CHECK_ID ] = $ret;
						
		}
		
		return $ret;
		
	}
	
	function _updateOR( $check_no, $or_no ) {
						
		if ( $or_no == NULL ) {

			$strWhere[TPC_CHECK_NO] = $this->getData( TPC_CHECK_NO );
		
		} else {

			$strWhere[TPC_CHECK_NO] = $check_no;

		}
		$strCols[ TPC_CHECK_STATUS ] = CHECK_STATUS_PROCESSED;
		$strCols[ TPC_OR_NO ] = $or_no;
		$strCols[ TPC_TS_LAST_UPDATE ] = date("Y-m-d H:i:s");
		
		$ret = ebpls_update_data( $this->m_dbLink, TPC_TABLE, $strCols, $strWhere );

		return $ret;			

	}
	
	function setCheckStatus( $check_no, $check_status, $admin, $remark ) {		
		
		if ( $check_status!=CHECK_STATUS_BOUNCED && $check_status!=CHECK_STATUS_PENDING && $check_status!=CHECK_STATUS_CLEARED ) {
		
			$this->setError( -1, "Invalid check status value $check_status.");
			return -1;
			
		}
		
		if ( $check_no == NULL ) {

			$strWhere[TPC_CHECK_NO] = $this->getData( TPC_CHECK_NO );

		} else {

			$strWhere[TPC_CHECK_NO] = $check_no;

		}
		
		$strWhere[TPC_CHECK_STATUS] = CHECK_STATUS_PENDING;
		
		$strCols[ TPC_CHECK_STATUS ] = $check_status;
		$strCols[ TPC_ADMIN ] = $admin;
		$strCols[ TPC_REMARK ] = $remark;
		$strCols[ TPC_TS_LAST_UPDATE ] = date("Y-m-d H:i:s");
		
		$ret = ebpls_update_data( $this->m_dbLink, TPC_TABLE, $strCols, $strWhere );

		return $ret;
	
	}
	

	function view( $check_id = NULL, $check_no = NULL ) {

		$strValues[] = "*";

		if ( $check_id != NULL ) {

			$strWhere[TPC_CHECK_ID] = $check_id;

		}
		
		if ( $check_no != NULL ) {

			$strWhere[TPC_CHECK_NO] = $check_no;

		}

		$records = ebpls_select_data( $this->m_dbLink, TPC_TABLE, $strValues, $strWhere );

		if ( is_array($records) ) {
						
			$this->setData( NULL, $records[0]);
			return 1;
			
		} else {
		
			$this->setError( -1, "Unable to find Check # " . $strWhere[TPC_CHECK_ID] . " Record.");
			return -1;
			
		}		
	
	}
	
	function getCheckPaymentList( $trans_id, $check_status = NULL){
		
		
		$strValues[] = "*";

		if ( $trans_id == NULL ) {

			$strWhere[TPC_TRANS_ID] = $this->getData( TPC_TRANS_ID );

		} else {

			$strWhere[TPC_TRANS_ID] = $trans_id;

		}
		
		if ( $check_status != NULL ) {
		
			$strWhere[TPC_CHECK_STATUS] = $check_status;
			
		}

		$records = ebpls_select_data( $this->m_dbLink, TPC_TABLE, $strValues, $strWhere );

		if ( is_array($records) ) {

			for ( $i=0;$i<count($records);$i++){
			
				$clsCheck = new EBPLSTransactionPaymentsCheck( $this->m_dbLink );
				$clsCheck->setData( NULL, $records[$i] );
				$check_records[$i] = $clsCheck;
				
			}
			
			return $check_records;

		} else {

			$this->setError( -1, "Unable to find Check Payment list with trans id # " . $strWhere[TPC_TRANS_ID] . " Record.");
			return -1;

		}
	
	}

}

?>