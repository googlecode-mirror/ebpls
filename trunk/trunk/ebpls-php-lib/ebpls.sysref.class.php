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

define(EBPLS_BUSINESS_CATEGORY , "business_category");
define(EBPLS_BUSINESS_CATEGORY_OFFC , "business_category");
define(EBPLS_BUSINESS_NATURE,"business_nature");
define(EBPLS_BUSINESS_REQUIREMENT,"business_requirement");
define(EBPLS_BUSINESS_STATUS,"business_status");
define(EBPLS_BUSINESS_TYPE,"business_type");
define(EBPLS_CITY_MUNICIPALITY,"city_municipality");
define(EBPLS_BARANGAY,"barangay");
define(EBPLS_DISTRICT,"district");
define(EBPLS_PROVINCE,"province");
define(EBPLS_ZIP,"zip");
define(EBPLS_ZONE,"zone");
define(EBPLS_INDUSTRY_SECTOR,"industry_sector");
define(EBPLS_OCCUPANCY_TYPE,"occupancy_type");



$gSysRefTableKeys = array( EBPLS_BUSINESS_CATEGORY, EBPLS_BUSINESS_CATEGORY_OFFC, EBPLS_BUSINESS_NATURE, EBPLS_BUSINESS_REQUIREMENT,
			   EBPLS_BUSINESS_STATUS, EBPLS_BUSINESS_STATUS, EBPLS_BUSINESS_TYPE, EBPLS_BUSINESS_TYPE, EBPLS_CITY_MUNICIPALITY,
			   EBPLS_BARANGAY, EBPLS_DISTRICT, EBPLS_PROVINCE, EBPLS_ZIP, EBPLS_ZONE, EBPLS_INDUSTRY_SECTOR, EBPLS_OCCUPANCY_TYPE, EBPLS_OCCUPANCY_TYPE);


define(SYSREF_CODE,"_code");
define(SYSREF_DESC,"_desc");
define(SYSREF_CREATE_TS,"_date_registered");
define(SYSREF_UPDATE_TS,"_date_updated");
define(SYSREF_ADMIN,"updated_by");
define(SYSREF_NATURE_TAX_FEE_CODE,"tax_fee_code");
define(SYSREF_NATURE_TAX_FEE_CODE2,"renew_tax_fee_code");
define(SYSREF_SYSTEMDATA,"system_data");


class EBPLSSysRef extends DataEncapsulator {

	var $m_dbLink;
	var $m_strTableKey;
	var $m_strCodeKey;
	var $m_strDescKey;
	var $m_strCreateKey;
	var $m_strUpdateKey;
	var $m_strAdminKey;

	var $m_strTableKeyConst;

	var $m_strCode;
	var $m_strDesc;
	var $m_tsCreate;
	var $m_tsUpdate;
	var $m_tsAdmin;


	/**
	 * Instatiation method, set $bDebug to true to print debug messages otherwise set to false.
	 * $dbLink is a valid database connection.
	 *
	 */
	function EBPLSSysRef( $dbLink, $strTableKey, $bDebug = false ) {

		global $gSysRefTableKeys;

		$this->m_dbLink = $dbLink;

		if ( !in_array( $strTableKey, $gSysRefTableKeys) ) {

			return NULL;

		}

		$this->m_strTableKeyConst = $strTableKey;

		$this->m_strTableKey = "ebpls_$strTableKey";

		$this->setDebugMode( $bDebug );

		$this->m_strCodeKey = $strTableKey . SYSREF_CODE;
		$this->m_strDescKey = $strTableKey . SYSREF_DESC;
		$this->m_strCreateKey = $strTableKey . SYSREF_CREATE_TS;
		$this->m_strUpdateKey = $strTableKey . SYSREF_UPDATE_TS;				
		$this->m_strAdminKey = SYSREF_ADMIN;

		$this->addDataElement( $this->m_strCodeKey, "is_not_empty", "[VALUE]" );
		$this->addDataElement( $this->m_strDescKey, "is_not_empty", "[VALUE]" );
		$this->addDataElement( $this->m_strCreateKey, "is_valid_date", "[VALUE]" , true );
		$this->addDataElement( $this->m_strUpdateKey, "is_valid_date", "[VALUE]" , true );
		$this->addDataElement( $this->m_strAdminKey, "is_not_empty", "[VALUE]" );
				
		
		if ( EBPLS_BUSINESS_NATURE == $strTableKey ) {

			$this->addDataElement( SYSREF_NATURE_TAX_FEE_CODE, "is_not_empty", "[VALUE]" );
			$this->addDataElement( SYSREF_NATURE_TAX_FEE_CODE2, "is_not_empty", "[VALUE]" );
			$this->addDataElement( SYSREF_SYSTEMDATA, NULL, NULL );

		}

	}

	function add( $code, $desc, $user ){

		return $this->addExtra( $code, $desc, NULL, $user );

	}

	function addExtra( $code, $desc, $tf_code, $user ){

		if ( $this->m_dbLink ) {

			$this->setData($this->m_strCodeKey,$code);
			$this->setData($this->m_strDescKey,$desc);
			$this->setData($this->m_strAdminKey,$user);
			$this->data_elems[$this->m_strCreateKey] = date("Y-m-d H:i:s");
			$this->data_elems[$this->m_strUpdateKey] = date("Y-m-d H:i:s");

			if ( $this->m_strTableKeyConst == EBPLS_BUSINESS_NATURE ) {

				$this->data_elems[ SYSREF_NATURE_TAX_FEE_CODE ] = $tf_code[0];
				$this->data_elems[ SYSREF_NATURE_TAX_FEE_CODE2 ] = $tf_code[1];

			}

			$strValues = $this->getData();

			if ( ( $error_num = $this->validateData() ) > 0 ) {

				$ret = ebpls_insert_data( $this->m_dbLink, $this->m_strTableKey, $strValues );

				if ( $ret < 0 ) {

					$this->debug( "CREATE SYSREF $this->m_strTableKey FAILED [error:$ret,msg=" . get_db_error() . "]" );

					$this->setError( $ret, get_db_error() );

					return $ret;

				} else {

					$this->debug( "CREATE $this->m_strTableKey SUCCESSFULL [$ret]" );
					return $ret;

				}


			} else {

				$this->debug( "CREATE $this->m_strTableKey FAILED [error:$ret,msg=" . get_db_error() . "]" );
				return $error_num;

			}

		} else {

			$this->debug( "CREATE $this->m_strTableKey FAILED INVALID DB LINK $this->m_dbLink" );
			$this->setError( $ret, "Invalid Db link $this->m_dbLink" );
			return -1;

		}


	}

	function delete( $code ){

		$strWhere[$this->m_strCodeKey] = $code;

		$result = ebpls_delete_data ( $this->m_dbLink, $this->m_strTableKey, $strWhere );

		if ( $result < 0 )  {

			$this->setError( $result, get_db_error() );

		}

		return $result;

	}

	function updateExtra( $code, $desc, $tf_code, $user ) {


		$this->setData($this->m_strCodeKey,$code);
		$this->setData($this->m_strDescKey,$desc);
		$this->setData($this->m_strAdminKey,$user);

		if ( $this->m_strTableKeyConst == EBPLS_BUSINESS_NATURE ) {

			$this->data_elems[ SYSREF_NATURE_TAX_FEE_CODE ] = $tf_code[0];
			$this->data_elems[ SYSREF_NATURE_TAX_FEE_CODE2 ] = $tf_code[1];

		}

		if ( ( $error_num = $this->validateData(true) ) > 0 ) {


			$arrData = $this->getData();

			foreach( $arrData as $key=>$value){

				if ( $arrData[$key] != NULL ) {

					$strValues[$key] = $value;

				}

			}

			$this->setData($this->m_strUpdateKey,date("Y-m-d H:i:s"));
			$strWhere[$this->m_strCodeKey] = $code;

			$ret = ebpls_update_data( $this->m_dbLink, $this->m_strTableKey, $strValues, $strWhere );


			if ( $ret < 0 ) {

				$this->debug( "UPDATE $this->m_strTableKey FAILED [error:$ret,msg=" . get_db_error() . "]" );

				$this->setError( $ret, get_db_error() );

				return $ret;

			} else {

				$this->debug( "UPDATE $this->m_strTableKey SUCCESSFULL [$ret]" );
				return $ret;

			}

		} else {

			$this->debug( "CREATE $this->m_strTableKey FAILED [error:$ret,msg=" . get_db_error() . "]" );
			return -1;

		}

	}

	function select( $code = NULL,  $page = 1, $maxrec = 100000000, $orderkey = SYSREF_CODE, $is_desc = true ){

		if ( $code != NULL )
			$strWhere[$this->m_strCodeKey] = $code;


		if ( $orderkey != SYSREF_CODE &&  $orderkey != SYSREF_DESC && $orderkey != SYSREF_CREATE_TS &&
			$orderkey != SYSREF_UPDATE_TS && $orderkey != SYSREF_ADMIN ) {

			$this->setError( -1, "Invalid order key value $orderkey.");
			return -1;

		}

		// select all columns
		$strValues[] = "*";

		if ( $orderkey != NULL ) {

			$strOrder[ $this->m_strTableKeyConst . $orderkey ] = $this->m_strTableKeyConst . $orderkey;

		} else {

			$strOrder = $this->m_strCodeKey;

		}

		$result = ebpls_select_data_bypage( $this->m_dbLink, $this->m_strTableKey, $strValues, $strWhere, NULL, $strOrder, $is_desc?"DESC":"ASC", $page, $maxrec );

		if ( !is_array($result) && $result < 0 ) {

			$this->setError ( $result, get_db_error());
			return $result;

		} else {

			// transform result to EBPLSSysRef object
			for ( $i = 0 ; $i < count($result["result"]); $i++ ) {

				$records[$i] = new EBPLSSysRef($this->m_dbLink, $this->m_strTableKey );
				$records[$i]->setData( NULL, $result["result"][$i] );

				$records[$i]->m_strCode = $records[$i]->getData($this->m_strCodeKey);
				$records[$i]->m_strDesc = $records[$i]->getData($this->m_strDescKey);
				$records[$i]->m_tsCreate = $records[$i]->getData($this->m_strCreateKey);
				$records[$i]->m_tsUpdate = $records[$i]->getData($this->m_strUpdateKey);
				$records[$i]->m_strAdmin = $records[$i]->getData($this->m_strAdminKey);				

			}

			$result["result"] = $records;

			return $result;

		}

	}

	function getCode(){

		return $this->m_strCode;

	}

	function getDescription(){


		return $this->m_strDesc;

	}

	function getCreateTimeStamp(){

		return $this->m_tsCreate;

	}

	function getUpdateTimeStamp(){

		return $this->m_tsUpdate;

	}

	function getExtra(){

		return $this->data_elems[ SYSREF_NATURE_TAX_FEE_CODE ];

	}

	function getExtra2(){

		return $this->data_elems[ SYSREF_NATURE_TAX_FEE_CODE2 ];

	}

	function getAdmin(){

		return $this->m_strAdmin;

	}

	

}


?>