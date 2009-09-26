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

// define ebpls_transaction_requirements data elements
define("TR_TABLE","ebpls_transaction_requirements");
define("TR_REQ_ID","req_id");
define("TR_TRANS_ID","trans_id");
define("TR_PERMIT_ID","permit_id");
define("TR_PERMIT_TYPE","permit_type");
define("TR_REQUIREMENT_CODE","requirement_code");
define("TR_DESC","business_requirement_desc");
define("TR_REF_NO","reference_no");
define("TR_STATUS","status");
define("TR_TS_SUBMITTED","ts_submitted");
define("TR_TS_CREATE","ts_create");
define("TR_TS_UPDATE","ts_update");
define("TR_COMMENT","comment");
define("TR_LAST_UPDATED_BY","last_updated_by");


class EBPLSTransactionRequirement extends DataEncapsulator {


	var $m_transId;

	function EBPLSTransactionRequirement ( $dbLink, $bDebug = false ) {

		$this->m_dbLink = $dbLink;
		$this->m_transId = $trans_id;
		$this->setDebugMode( $bDebug );

		$this->addDataElement(TR_REQ_ID, "is_valid_number", "[VALUE]" );
		$this->addDataElement(TR_TRANS_ID, "is_valid_number", "[VALUE]" );
		$this->addDataElement(TR_PERMIT_ID, "is_valid_number", "[VALUE]" );
		$this->addDataElement(TR_PERMIT_TYPE, "is_not_empty", "[VALUE]" );
		$this->addDataElement(TR_REQUIREMENT_CODE, "is_not_empty", "[VALUE]" );
		$this->addDataElement(TR_STATUS, "is_not_empty", "[VALUE]" );
		$this->addDataElement(TR_REF_NO, NULL, NULL );
		$this->addDataElement(TR_TS_SUBMITTED, "is_valid_date", "[VALUE]", true );
		$this->addDataElement(TR_TS_UPDATE, "is_valid_date", "[VALUE]", true );
		$this->addDataElement(TR_DESC, "is_not_empty", "[VALUE]", true );
		$this->addDataElement(TR_COMMENT, NULL, NULL );
		$this->addDataElement(TR_LAST_UPDATED_BY, "is_not_empty", "[VALUE]" );

	}

	function add( ) {

		if ( $this->validateData() > 0 ) {

			$ts = time();
			$nYear = date("Y", $ts);
			$dt = date("Y-m-d H:i:s", $ts);
			$this->data_elems[ TR_TS_SUBMITTED ] = $dt;
			$this->data_elems[ TR_TS_UPDATE ] = $dt;
			$this->data_elems[ TR_TS_CREATE ] = $dt;

			$strValues = $this->data_elems;

			$ret = ebpls_insert_data( $this->m_dbLink, TR_TABLE, $strValues );

			if ( $ret < 0 ) {

				$this->debug("TR ADD ERROR : $ret" );
				$this->setError( -1, get_db_error() );
				return $ret;

			} else {

				$this->debug("TR ADD OK : $ret" );

				return $ret;

			}

		} else {

			$this->debug("TR ADD TRANS REQ FAILED : $ret" );
			$this->setError(-1,"TR ADD TRANS REQ FAILED validate data failed : $ret" );
			return -1;

		}

	}


	function delete( ) {

		$strWhere[TR_TRANS_ID] = $this->getData(TR_TRANS_ID);
		$strWhere[TR_REQUIREMENT_CODE] = $this->getData(TR_REQUIREMENT_CODE);

		$result = ebpls_delete_data ( $this->m_dbLink, TR_TABLE, $strWhere );

		if ( $result < 0 )  {

			$this->setError( $result, get_db_error() );

		}

		return $result;

	}

	function update( ) {

		if ( $this->validateData(true) > 0 ) {
			
			$dt = date("Y-m-d H:i:s");
			$this->data_elems[ TR_REQ_ID ] = NULL;
			$this->data_elems[ TR_TS_UPDATE ] = $dt;

			if ( $this->getData(TR_STATUS) == "SUBMITTED" ) {

				$this->data_elems[ TR_TS_SUBMITTED ] = $dt;

			}

			$arrData = $this->getData();
			foreach( $arrData as $key=>$value){

				if ( $arrData[$key] != NULL ) {

					$strValues[$key] = $value;

				}

			}

			$strWhere[TR_TRANS_ID] = $this->getData(TR_TRANS_ID);
			$strWhere[TR_REQUIREMENT_CODE] = $this->getData(TR_REQUIREMENT_CODE);

			$ret = ebpls_update_data( $this->m_dbLink, TR_TABLE, $strValues, $strWhere );

			if ( $ret < 0 ) {

				$this->debug("TR UPDATE ERROR : $ret" );
				return $ret;

			} else {

				$this->debug("TR UPDATE OK : $ret" );
				return $ret;

			}

		} else {

			$this->debug("TR UPDATE TRANS REQ FAILED : $ret" );
			return -1;

		}

	}


	function listReq( $trans_id, $status = NULL ) {

		$strValues[$key] = "*";

		$strWhere[TR_TRANS_ID] = $trans_id;

		if ( $status != NULL ) {

			$strWhere[TR_STATUS] = $status;

		}
		
		$result = get_application_requirement_list ( $this->m_dbLink, $trans_id, $status);

		if ( is_array($result) ) {


			for ( $i = 0 ; $i < count($result); $i++ ) {

				$req[$i] = new EBPLSTransactionRequirement( $this->m_dbLink );
				$req[$i]->setData(NULL, $result[$i]);

			}

			return $req;

		} else {

			$this->setError( $result, get_db_error() );
			return -1;

		}

	}


}


?>
