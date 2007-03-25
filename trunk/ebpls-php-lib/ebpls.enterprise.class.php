<?
/************************************************************************************
Module : ebpls.enterprise.class.php

Dependencies :
	ebpls.permit.class.php
	ebpls.owner.class.php
	ebpls.global.funcs.php
	ebpls.dataencapsulator.class.php

Description :
	- encapsulates motorized permit

Created By : Stephen Lou B. Banal
Email : sbanal@yahoo.com
Date Created : 3/20/2004 11:40PM

Last Updates :
	[ DATE / TIME ] - [ PROGRAMMER ]
	- < DETAILS 1 >
	- < DETAILS 2 >.
	- < DETAILS 3 >

Notes :

************************************************************************************/

require_once("ebpls-php-lib/ebpls.transaction.class.php");
require_once("ebpls-php-lib/ebpls.permit.class.php");
require_once("ebpls-php-lib/ebpls.owner.class.php");
require_once("ebpls-php-lib/ebpls.global.funcs.php");
require_once("ebpls-php-lib/ebpls.dataencapsulator.class.php");

// keys for getData method
define(EBPLS_BUSINESS_TABLE,"ebpls_business_enterprise");

// Peddlers Permit Owner Data Elements Constants
define(BUSINESS_ID,"business_id");
define(BUSINESS_OWNER_ID,"owner_id");
define(BUSINESS_NAME,"business_name");
define(BUSINESS_BRANCH,"business_branch");
define(BUSINESS_PERMIT_TRANS_TYPE,"business_permit_trans_type");
define(BUSINESS_LOT_NO,"business_lot_no");
define(BUSINESS_STREET,"business_street");
define(BUSINESS_BARANGAY_CODE,"business_barangay_code");
define(BUSINESS_ZONE_CODE,"business_zone_code");
define(BUSINESS_BARANGAY_NAME,"business_barangay_name");
define(BUSINESS_DISTRICT_CODE,"business_district_code");
define(BUSINESS_CITY_CODE,"business_city_code");
define(BUSINESS_PROVINCE_CODE,"business_province_code");
define(BUSINESS_ZIP_CODE,"business_zip_code");
define(BUSINESS_CONTACT_NO,"business_contact_no");
define(BUSINESS_FAX_NO,"business_fax_no");
define(BUSINESS_EMAIL_ADDRESS,"business_email_address");
define(BUSINESS_URL,"business_url");
define(BUSINESS_LOCATION_DESC,"business_location_desc");
define(BUSINESS_BUILDING_NAME,"Business_Building_Name");
define(BUSINESS_PHONE_NO,"business_phone_no");
define(BUSINESS_CATEGORY_CODE,"business_category_code");
define(BUSINESS_DOT_ACR_NO,"business_dot_acr_no");
define(BUSINESS_SEC_REG_NO,"business_sec_reg_no");
define(BUSINESS_TIN_REG_NO,"business_tin_reg_no");
define(BUSINESS_DTI_REG_NO,"business_dti_reg_no");
define(BUSINESS_DTI_REG_DATE,"business_dti_reg_date");
define(BUSINESS_DATE_ESTABLISHED,"business_date_established");
define(BUSINESS_START_DATE,"business_start_date");
define(BUSINESS_OCCUPANCY_CODE,"business_occupancy_code");
define(BUSINESS_OFFC_CODE,"business_offc_code");
define(BUSINESS_MAIN_OFFC_NAME,"business_main_offc_name");
define(BUSINESS_MAIN_OFFC_LOT_NO,"business_main_offc_lot_no");
define(BUSINESS_MAIN_OFFC_STREET,"business_main_offc_street");
define(BUSINESS_MAIN_OFFC_BARANGAY_NAME,"business_main_offc_barangay_name");
define(BUSINESS_MAIN_OFFC_BARANGAY_CODE,"business_main_offc_barangay_code");
define(BUSINESS_MAIN_OFFC_ZONE_CODE,"business_main_offc_zone_code");
define(BUSINESS_MAIN_OFFC_DISTRICT_CODE,"business_main_offc_district_code");
define(BUSINESS_MAIN_OFFC_CITY_CODE,"business_main_offc_city_code");
define(BUSINESS_MAIN_OFFC_ZIP_CODE,"business_main_offc_zip_code");
define(BUSINESS_MAIN_OFFC_TIN_NO,"business_main_offc_tin_no");
define(BUSINESS_CAPITAL_INVESTMENT,"business_capital_investment");
define(BUSINESS_NO_EMPLOYEES,"business_no_employees");
define(BUSINESS_NO_DEL_VEHICLES,"business_no_del_vehicles");
define(BUSINESS_PAYMENT_MODE,"business_payment_mode");
define(BUSINESS_EXEMPTION_CODE,"business_exemption_code");
define(BUSINESS_TYPE_CODE,"business_type_code");
define(BUSINESS_NSO_ASSIGNED_NO,"business_nso_assigned_no");
define(BUSINESS_NSO_ESTAB_ID,"business_nso_estab_id");
define(BUSINESS_INDUSTRY_SECTOR_CODE,"business_industry_sector_code");
define(BUSINESS_REMARKS,"business_remarks");
define(BUSINESS_STATUS_CODE,"business_status_code");
define(BUSINESS_STATUS_REMARKS,"business_status_remarks");
define(BUSINESS_APPLICATION_STATUS,"business_application_status");
define(BUSINESS_APPLICATION_STATUS_REM,"business_application_status_rem");
define(BUSINESS_LAST_YRS_CAP_INVEST,"business_last_yrs_cap_invest");
define(BUSINESS_LAST_YRS_NO_EMPLOYEES,"business_last_yrs_no_employees");
define(BUSINESS_LAST_YRS_DEC_GROSS_SALES,"business_last_yrs_dec_gross_sales");
define(BUSINESS_RETIREMENT_DATE,"business_retirement_date");
define(BUSINESS_RETIREMENT_REASON,"business_retirement_reason");
define(BUSINESS_APPLICATION_DATE,"business_application_date");
define(BUSINESS_VALIDITY_PERIOD,"business_validity_period");
define(BUSINESS_CREATE_TS,"business_create_ts");
define(BUSINESS_UPDATE_BY,"business_update_by");
define(BUSINESS_UPDATE_TS,"business_update_ts");
define(BUSINESS_COMMENT,"comment");


define(NATURE_BUSINESS_REF_TABLE,"ebpls_business_nature");
define(NATURE_BUSINESS_TABLE,"ebpls_business_enterprise_nature");
define(NATURE_BUSINESS_ID,"business_id");
define(NATURE_BUSINESS_NATURE_CODE,"business_nature_code");
define(NATURE_BUSINESS_NATURE_DESC,"business_nature_desc");
define(NATURE_BUSINESS_CAPITAL_INVESTMENT,"capital_investment");
define(NATURE_BUSINESS_LAST_GROSS,"last_years_gross");
define(NATURE_BUSINESS_APPLICATION_STATUS,"application_status");
define(NATURE_BUSINESS_APPLICATION_YEAR,"application_year");
define(NATURE_UPDATED_BY,"updated_by");
define(NATURE_UPDATED_LAST_TS,"updated_last_ts");
define(NATURE_CREATE_TS,"create_ts");


class EBPLSEnterprise extends DataEncapsulator {

	var $m_objOwner;
	var $m_dbLink;
	var $m_bDebug;
	var $m_arrBusinessNature;
	var $m_arrBusinessNatureKeys;
	var $m_arrBusinessNatureAdd;
	var $m_arrBusinessNatureDelete;
	var $m_arrBusinessNatureUpdate;

	/**
	 * Instatiation method, set $bDebug to true to print debug messages otherwise set to false.
	 * $dbLink is a valid database connection.
	 *
	 */
	function EBPLSEnterprise( $dbLink, $bDebug = false ) {

		$this->m_dbLink = $dbLink;
		$this->m_bDebug = $bDebug;

		// add data elements of class with their respective validation functions as params
		$this->addDataElement( BUSINESS_ID,"is_valid_number", "[VALUE]", true );
		$this->addDataElement( BUSINESS_OWNER_ID,"is_valid_number", "[VALUE]" );
		$this->addDataElement( BUSINESS_NAME,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_BRANCH, NULL, NULL );
		$this->addDataElement( BUSINESS_PERMIT_TRANS_TYPE,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_LOT_NO,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_STREET,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_BARANGAY_CODE,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_ZONE_CODE,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_BARANGAY_NAME,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_DISTRICT_CODE,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_CITY_CODE,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_PROVINCE_CODE,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_ZIP_CODE,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_CONTACT_NO,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_FAX_NO,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_EMAIL_ADDRESS,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_URL,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_LOCATION_DESC,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_BUILDING_NAME,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_PHONE_NO,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_CATEGORY_CODE,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_DOT_ACR_NO,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_SEC_REG_NO,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_TIN_REG_NO,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_DTI_REG_NO,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_DTI_REG_DATE,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_DATE_ESTABLISHED,"is_valid_date", "[VALUE]" );
		$this->addDataElement( BUSINESS_START_DATE,"is_valid_date", "[VALUE]" );
		$this->addDataElement( BUSINESS_OCCUPANCY_CODE,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_OFFC_CODE,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_MAIN_OFFC_NAME,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_MAIN_OFFC_LOT_NO,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_MAIN_OFFC_STREET,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_MAIN_OFFC_BARANGAY_NAME,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_MAIN_OFFC_BARANGAY_CODE,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_MAIN_OFFC_ZONE_CODE,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_MAIN_OFFC_DISTRICT_CODE,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_MAIN_OFFC_CITY_CODE,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_MAIN_OFFC_ZIP_CODE,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_MAIN_OFFC_TIN_NO,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_CAPITAL_INVESTMENT,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_NO_EMPLOYEES,"is_valid_number", "[VALUE]" );
		$this->addDataElement( BUSINESS_NO_DEL_VEHICLES,"is_valid_number", "[VALUE]" );
		$this->addDataElement( BUSINESS_PAYMENT_MODE,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_EXEMPTION_CODE,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_TYPE_CODE,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_NSO_ASSIGNED_NO,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_NSO_ESTAB_ID,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_INDUSTRY_SECTOR_CODE,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_REMARKS,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_STATUS_CODE,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_STATUS_REMARKS,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_APPLICATION_STATUS,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_APPLICATION_STATUS_REM,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_LAST_YRS_CAP_INVEST,"is_valid_number", "[VALUE]" );
		$this->addDataElement( BUSINESS_LAST_YRS_NO_EMPLOYEES,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_LAST_YRS_DEC_GROSS_SALES,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_RETIREMENT_DATE,"is_valid_date", "[VALUE]" );
		$this->addDataElement( BUSINESS_RETIREMENT_REASON,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_APPLICATION_DATE,"is_valid_date", "[VALUE]", true );
		$this->addDataElement( BUSINESS_VALIDITY_PERIOD,"is_not_empty", "[VALUE]" );
		$this->addDataElement( BUSINESS_CREATE_TS,"is_valid_date", "[VALUE]", true );
		$this->addDataElement( BUSINESS_UPDATE_BY,"is_not_empty", "[VALUE]", true );
		$this->addDataElement( BUSINESS_UPDATE_TS,"is_valid_date", "[VALUE]", true );
		$this->addDataElement( BUSINESS_COMMENT,"is_not_empty", "[VALUE]" );

		$this->m_objOwner = NULL;
		$this->m_arrBusinessNature = NULL;
		$this->m_arrBusinessNatureAdd = NULL;
		$this->m_arrBusinessNatureDelete = NULL;

	}

	/**
	 *
	 */
	function add( $creator, $user_level ) {

		if ( $this->m_dbLink ) {

			$clsOwner = new EBPLSOwner ( $this->m_dbLink, $this->m_bDebug );

			$owner_id = $this->data_elems[ BUSINESS_OWNER_ID ];

			if ( $clsOwner->view( $owner_id ) <= 0 )  {

				$this->debug("Onwer with owner id $owner_id not found.");
				return -1;

			}

			/*
			// removed -- adding transfered to transaction class
			if ( $this->m_arrBusinessNatureAdd == NULL ) {

				$this->setError(-1,"Business Enterprise no business nature list, add business nature by invoking addBusinessNature( nature_code, creator, user_level )");
				$this->debug("Add business enterprise failed. No business nature list");
				return -1;

			}
			*/
			
			if ( ( $error_num = $this->validateData() ) > 0 ) {

				mysql_query("LOCK TABLES", $this->m_dbLink);

				$this->m_objOwner = $clsOwner;
				// create reg ts
				$ts = time();
				$nYear = date("Y", $ts);
				$dt = date("Y-m-d H:i:s", $ts);
				$this->data_elems[ BUSINESS_APPLICATION_DATE ] = $dt;
				$this->data_elems[ BUSINESS_UPDATE_BY ] = $creator;
				$this->data_elems[ BUSINESS_UPDATE_TS ] = $dt;
				$this->data_elems[ BUSINESS_CREATE_TS ] = $dt;

				$strValues = $this->data_elems;


				// create permit
				$ret = ebpls_insert_data( $this->m_dbLink, EBPLS_BUSINESS_TABLE, $strValues );

				if ( $ret < 0 ) {

					$this->debug( "CREATE BUS ENTERRPISE FAILED [error:$ret,msg=" . get_db_error() . "]" );
					$this->setError( $ret, get_db_error() );

				} else {

					// add business nature
					$this->data_elems[ BUSINESS_ID ] = $ret;

					for ( $i = 0; $i < count($this->m_arrBusinessNatureAdd); $i++ ) {

						$clsNature = $this->m_arrBusinessNatureAdd[$i];
						$clsNature->setData(NATURE_BUSINESS_ID, $ret );
						$this->debug("$clsNature->add ( $creator );<BR>");
						$clsNature->add ( $creator );

					}

					$this->debug("CREATE BUS ENTERRPIS OK $ret");

				}

				mysql_query("UNLOCK TABLES", $this->m_dbLink);

				return $ret;

			} else {

				$this->debug( "CREATE BUSINESS ENTERRPIS FAILED [error:$error_num,msg=" . get_db_error() . "]" );
				//print_r($this->getError());
				return $error_num;

			}

		} else {

			$this->debug( "CREATE BUSINESS ENTERPRISE FAILED INVALID DB LINK $this->m_dbLink" );
			$this->setError( $ret, "Invalid Db link $this->m_dbLink" );
			return -1;

		}

	}


	/**
	 * View owner data, loads data using bus id as param
	 *
	 */
	function view( $bus_id ) {

		$strValues[$key] = "*";

		$strWhere[BUSINESS_ID] = $bus_id;

		$result = ebpls_select_data( $this->m_dbLink, EBPLS_BUSINESS_TABLE, $strValues, $strWhere, NULL, $strOrderBy, "DESC", NULL );

		if ( is_array($result) ) {

			$owner = new EBPLSOwner( $this->m_dbLink );
			$owner->view( $result[0][BUSINESS_OWNER_ID] );
			$this->data_elems = $result[0];
			$this->m_objOwner = $owner;
			return 1;

		} else {

			if ( $result < 0 || $result == "" ) {
				$this->setError( -1, "Business with business id $bus_id not found");
			} else {
				$this->setError( $result, get_db_error() );

			}
			return -1;

		}

	}

	function search( $business_name, $page = 1, $maxrec = 10, $orderkey = BUSINESS_ID, $is_desc = true ) {

		$strWhere[BUSINESS_NAME] = array("like", "$business_name%");

		// select all columns
		$strValues[] = "*";

		if ( $orderkey != NULL ) {

			$strOrder[$orderkey] = $orderkey;

		} else {

			$strOrder = $orderkey;

		}

		if ( count($strWhere) <= 0 ) {

			$this->setError ( -1, "No search parameters." );
			return -1;

		}

		$result = ebpls_select_data_bypage( $this->m_dbLink, EBPLS_BUSINESS_TABLE, $strValues, $strWhere, NULL, $strOrder, $is_desc?"DESC":"ASC", $page, $maxrec );

		if ( !is_array($result) && $result < 0 ) {


			$this->setError ( $result, get_db_error());
			return $result;

		} else {

			// transform result to EBPLSEnterprise object
			for ( $i = 0 ; $i < count($result["result"]); $i++ ) {
				$records[$i] = new EBPLSEnterprise($this->m_dbLink);				
				$records[$i]->setData( NULL, $result["result"][$i] );				
			}

			$result["result"] = $records;

			return $result;

		}

	}


	function getOwner( ) {

		return $this->m_objOwner;

	}


	function addBusinessNature( $nature_code, $capital = 0, $last_gross = 0 ) {

		if ( $this->m_arrBusinessNatureKeys!=NULL && is_array($this->m_arrBusinessNatureKeys) && in_array( $nature_code,$this->m_arrBusinessNatureKeys ) ) {

			$this->setError(-1,"Code $nature code already exist on business nature list.");
			$this->debug("Code $nature code already exist on business nature list.");
			return -1;

		} else {

			$clsENature = new EBPLSEnterpriseNature ( $this->m_dbLink, $this, $this->m_bDebug);
			$clsENature->setData(NATURE_BUSINESS_NATURE_CODE,$nature_code);			
			$ret = $clsENature->load(); // check if exist on nature reference table

			if ( $ret > 0 ) {

				$clsENature->setData(NATURE_BUSINESS_CAPITAL_INVESTMENT,$capital);
				$clsENature->setData(NATURE_BUSINESS_LAST_GROSS,$last_gross);
				
				print_r($clsENature->data_elems);
				
				$this->debug("Code $nature code found in system business nature list, added to business object.");
				$this->m_arrBusinessNatureAdd[] = $clsENature;
				$this->m_arrBusinessNatureKeys[] = $records[$i][NATURE_BUSINESS_NATURE_CODE];
				return 1;

			} else {

				$this->setError(-1,"Code $nature_code not found in system business nature list.");
				$this->debug("Code $nature_code not found in system business nature list [$ret]");
				return -1;

			}

		}

	}

	function deleteBusinessNature(  $nature_code ) {

		$this->m_arrBusinessNatureDelete[] = $nature_code;

		if ( is_array($this->m_arrBusinessNatureKeys) ) {

			// remove nature code from key list
			$index = array_search( $nature_code, $this->m_arrBusinessNatureKeys );

			if ( $index != NULL && $index != FALSE ) {

				unset($this->m_arrBusinessNatureKeys[$index]);

			}

		}

	}

	function viewBusinessNature( $business_id, $nature_code ) {
		
		$clsENature = new EBPLSEnterpriseNature( $this->m_dbLink, $this, $this->m_bDebug );
				
		if ( $clsENature->viewData( $business_id, $nature_code ) > 0 ) {
			
			return $clsENature;
			
		} 
		
		return -1;
		
	}

	function updateBusinessNature(  $nature_code, $capital = 0, $last_gross = 0, $application_year = 0 ) {

		$clsENature = new EBPLSEnterpriseNature( $this->m_dbLink, $this, $this->m_bDebug );
		$clsENature->setData(NATURE_BUSINESS_NATURE_CODE, $nature_code);
		$clsENature->setData(NATURE_BUSINESS_CAPITAL_INVESTMENT, $capital);
		$clsENature->setData(NATURE_BUSINESS_LAST_GROSS, $last_gross);
		$this->m_arrBusinessNatureUpdate[] = $clsENature;

	}

	function _updateBusinessNatureApplication(  $nature_code, $capital = 0, $last_gross = 0, $application_year = NULL, $application_status = "PENDING" ) {

		$clsENature = new EBPLSEnterpriseNature( $this->m_dbLink, $this, $this->m_bDebug );
		$clsENature->setData(NATURE_BUSINESS_NATURE_CODE, $nature_code);
		$clsENature->setData(NATURE_BUSINESS_CAPITAL_INVESTMENT, $capital);		
		$clsENature->setData(NATURE_BUSINESS_LAST_GROSS, $last_gross);
		$clsENature->setData(NATURE_BUSINESS_APPLICATION_YEAR, ($application_year==NULL)?date("Y"):$application_year );
		$clsENature->setData(NATURE_BUSINESS_APPLICATION_STATUS, $application_status);
		$this->m_arrBusinessNatureUpdate[] = $clsENature;

	}

	function getBusinessNatureList ( $business_id = NULL ) {

		if ( $business_id == NULL ) {

			$business_id = $this->getData(BUSINESS_ID);

		}

		if ( !is_numeric($business_id) ) {

			$this->setError(-1,"Invalid business id value $business_id on invokation of getBusinessNatureList.");
			$this->debug("Invalid business id value $business_id on invokation of getBusinessNatureList.");
			return -1;

		}

		//$this->synchBusinessNature();
		$records = get_bus_enterprise_nature_list( $this->m_dbLink, $business_id );

		if ( count($records)  ) {

			unset($this->m_arrBusinessNatureKeys);
			for ( $i=0; $i< count($records);$i++){

				$clsENature = new EBPLSEnterpriseNature ( $this->m_dbLink, $this, $this->m_bDebug);

				/*
				$clsENature->setData(NATURE_BUSINESS_NATURE_CODE,$records[$i][NATURE_BUSINESS_NATURE_CODE]);
				$clsENature->setData(NATURE_BUSINESS_NATURE_DESC,$records[$i][NATURE_BUSINESS_NATURE_DESC]);
				$clsENature->data_elems[NATURE_UPDATED_BY] = $records[$i][NATURE_UPDATED_BY];
				$clsENature->data_elems[NATURE_UPDATED_LAST_TS] = $records[$i][NATURE_UPDATED_LAST_TS];
				$clsENature->data_elems[NATURE_CREATE_TS] = $records[$i][NATURE_CREATE_TS];
				$clsENature->data_elems[NATURE_BUSINESS_ID] = $records[$i][NATURE_BUSINESS_ID];
				*/

				$clsENature->setData( NULL, $records[$i] );

				// register existing data to nature list
				$this->m_arrBusinessNatureKeys[] = $records[$i][NATURE_BUSINESS_NATURE_CODE];

				$objList[] = $clsENature;

			}

			return $objList;


		} else {

			$this->debug("No business nature found for business with id $business_id");
			return -1;

		}

	}



	/**
	 *
	 *
	 **/
	function update( $bus_id , $admin, $user_level ) {

		$bus_id = ($bus_id==NULL)?($this->getData(BUSINESS_ID)):($bus_id);

		$this->data_elems[BUSINESS_ID] = $bus_id;

		if ( !is_numeric($bus_id) || $bus_id==NULL || $bus_id=="" || $bus_id <= 0 ) {

			$this->debug( "Update failed, invalid business id value = $bus_id." );
			return -2;

		}

		$clsTmp = new EBPLSEnterprise( $this->m_dbLink, $this->m_bDebug );

		if ( $clsTmp->view( $bus_id ) < 0 ) {

			$this->debug( "Update failed, business with id '$bus_id' not found." );
			return -1;

		}


		if ( $this->m_arrBusinessNatureDelete!=NULL ) {

			$this->debug( "UPDATE BUS ENTERPRISE DELETE Nature" );

			for ( $i = 0 ; $i < count($this->m_arrBusinessNatureDelete); $i++ ) {

				$clsENature = new EBPLSEnterpriseNature( $this->m_dbLink, $this, $this->m_bDebug );
				$clsENature->setData( NATURE_BUSINESS_NATURE_CODE, $this->m_arrBusinessNatureDelete[$i]);
				$clsENature->delete();

			}

		} else {

			$this->debug( "UPDATE BUS ENTERPRISE DELETE Nature NOT EXECUTED" );

		}

		if ( $this->m_arrBusinessNatureUpdate!=NULL ) {

			$this->debug( "UPDATE BUS ENTERPRISE UPDATE Nature" );

			for ( $i = 0 ; $i < count($this->m_arrBusinessNatureUpdate); $i++ ) {

				$clsENature = $this->m_arrBusinessNatureUpdate[$i];
				$clsENature->update();

			}

		} else {

			$this->debug( "UPDATE BUS ENTERPRISE UPDATE Nature NOT EXECUTED" );

		}

		if ( $this->m_arrBusinessNatureAdd!=NULL ) {

			$this->debug( "UPDATE BUS ENTERPRISE ADD Nature" );

			for ( $i = 0; $i < count($this->m_arrBusinessNatureAdd); $i++ ) {

				$clsNature = $this->m_arrBusinessNatureAdd[$i];
				$clsNature->setData(NATURE_BUSINESS_ID, $bus_id );				
				$clsNature->add ( $admin );

			}

		} else{

			$this->debug( "UPDATE BUS ENTERPRISE ADD Nature NOT EXECUTED" );

		}



		// exclude those not changed
		$arrData = $this->getData();

		foreach( $arrData as $key=>$value){

			if ( $arrData[$key] != NULL ) {

			  	// remove keys using index number, bug on view method on this class
			  	if ( !is_numeric($key) ) {

					$strValues[$key] = $value;

				}

			}

		}

		if ( ( $error_num = $this->validateData(true) ) > 0 ) {

			$strWhere[BUSINESS_ID] = $bus_id;

			$ret = ebpls_update_data( $this->m_dbLink, EBPLS_BUSINESS_TABLE, $strValues, $strWhere );

			if ( $ret < 0 ) {

				$this->setError( $ret, get_db_error() );
				$this->debug( "UPDATE BUS ENTERPRISE FAILED [error:$ret,msg=" . get_db_error() . "]" );

				return $ret;

			} else {

				$this->debug( "UPDATE BUSINESS ENTERPRISE SUCCESSFULL [$ret]" );
				return $ret;

			}

		} else {

			$this->debug( "UPDATE BUSINESS ENTERPRISE FAILED [error:$ret,msg=" . get_db_error() . "]" );
			return -1;

		}

	}

	/**
	 *
	 * NOTE: - There should be an existing referential integrity constraint on the transaction table to avoid corrupted table data
	 *	 - Table should be InnoDB
	 *
	 **/
	function delete( ) {

		// delete business nature list
		$clsENature = new EBPLSEnterpriseNature( $this->m_dbLink, $this, $this->m_bDebug );
		$clsENature->delete( true );

		// delete business record
		$strWhere[BUSINESS_ID] = $this->getData(BUSINESS_ID);

		$result = ebpls_delete_data ( $this->m_dbLink, EBPLS_BUSINESS_TABLE, $strWhere );

		if ( $result < 0 )  {

			$this->setError( $result, get_db_error() );

		}

		return $result;

	}

	/**
	 * Updates application status of all lines of business of a particular business enterprise.
	 *
	 *
	 **/
	function synchBusinessNature() {
		
		$strValues[NATURE_BUSINESS_APPLICATION_STATUS] = "EXPIRED";
		$strValues[NATURE_UPDATED_LAST_TS] = date("Y-m-d H:i:s");
		$strWhere[NATURE_BUSINESS_ID] = $this->getData(BUSINESS_ID);
		$strWhere[NATURE_BUSINESS_APPLICATION_YEAR] = Array( " < " , date("Y") );

		$ret = ebpls_update_data( $this->m_dbLink, NATURE_BUSINESS_TABLE, $strValues, $strWhere );


	}

	function isBusinessNatureForApplication ( $nature_code ) {

		$clsENature = new EBPLSEnterpriseNature( $this->m_dbLink, $this, $this->m_bDebug );
		$clsENature->setData(NATURE_BUSINESS_NATURE_CODE,$nature_code);

		$ret = $clsENature->view();

		if ( $ret < 0  ) {

			$this->setError( $ret, $clsENature->getError() );

		}

		if ( $clsENature->getData(NATURE_BUSINESS_APPLICATION_STATUS) == "EXPIRED" ) {

			return 1;

		} else {

			return 0;

		}

	}

}

class EBPLSEnterpriseNature extends DataEncapsulator {

	var $business_id;
	var $m_objEnterprise;

	function EBPLSEnterpriseNature( $dbLink, &$objEnterprise, $bDebug = false ) {

		$this->m_dbLink = $dbLink;
		$this->m_objEnterprise = $objEnterprise;
		$this->business_id = $objEnterprise->getData(BUSINESS_ID);
		$this->m_bDebug = $bDebug;
		$this->addDataElement(NATURE_BUSINESS_ID,"is_not_empty", true );
		$this->addDataElement(NATURE_BUSINESS_NATURE_CODE,"is_not_empty");
		$this->addDataElement(NATURE_BUSINESS_NATURE_DESC,"is_not_empty", true);
		$this->addDataElement(NATURE_BUSINESS_CAPITAL_INVESTMENT,"is_valid_number" );
		$this->addDataElement(NATURE_BUSINESS_LAST_GROSS,"is_valid_number" );
		$this->addDataElement(NATURE_BUSINESS_APPLICATION_STATUS,"is_not_empty"  );
		$this->addDataElement(NATURE_BUSINESS_APPLICATION_YEAR,"is_valid_number" );
		$this->addDataElement(NATURE_UPDATED_BY,"is_not_empty", true);
		$this->addDataElement(NATURE_UPDATED_LAST_TS,"is_valid_date", true);
		$this->addDataElement(NATURE_CREATE_TS,"is_valid_date", true);

		$this->data_elems[NATURE_BUSINESS_ID] = $business_id ;

	}

	function add( $creator ){

		$dt = date("Y-m-d H:i:s");

		$this->data_elems[NATURE_BUSINESS_APPLICATION_STATUS] = "PENDING";
		$this->data_elems[NATURE_BUSINESS_APPLICATION_YEAR] = date("Y");
		$this->data_elems[NATURE_UPDATED_BY] = $creator;
		$this->data_elems[NATURE_UPDATED_LAST_TS] = $dt;
		$this->data_elems[NATURE_CREATE_TS] = $dt;

		$strValues = $this->data_elems;

		$ret = ebpls_insert_data( $this->m_dbLink, NATURE_BUSINESS_TABLE, $strValues );

		if ( $ret <  0 ){

			$this->setError(-1,"Insert error : " . get_db_error());
			$this->debug("Add Business nature failed");

		} else {

			$this->debug("Add Business nature ok. $creator");

		}

		return $ret;

	}

	function delete( $bAll = false ){

		$strWhere[NATURE_BUSINESS_ID] = $this->business_id;

		if ( !$bAll ) {
			$strWhere[NATURE_BUSINESS_NATURE_CODE] = $this->getData(NATURE_BUSINESS_NATURE_CODE);
		}

		$result = ebpls_delete_data ( $this->m_dbLink, NATURE_BUSINESS_TABLE, $strWhere );

		if ( $result < 0 )  {

			$this->setError( $result, get_db_error() );

		}

		return $result;

	}

	function update(){

		if ( ( $error_num = $this->validateData(true) ) > 0 ) {

			$strWhere[BUSINESS_ID] = $this->business_id;
			$strWhere[NATURE_BUSINESS_NATURE_CODE] = $this->data_elems[NATURE_BUSINESS_NATURE_CODE];

			$this->data_elems[NATURE_UPDATED_BY] = $creator;
			$this->data_elems[NATURE_UPDATED_LAST_TS] = $dt;

			$strValues = $this->data_elems;

			$ret = ebpls_update_data( $this->m_dbLink, NATURE_BUSINESS_TABLE, $strValues, $strWhere );

			if ( $ret < 0 ) {

				$this->setError( $ret, get_db_error() );
				$this->debug( "Update Business nature [error:$ret,msg=" . get_db_error() . "]" );

				return $ret;

			} else {

				$this->debug( "Update Business nature SUCCESSFULL [$ret]" );
				return $ret;

			}

		} else {

			$this->debug( "Update Business nature FAILED [error:$ret,msg=" . get_db_error() . "]" );
			return -1;

		}


	}

	function view() {

		$strValues[$key] = "*";
		$strWhere[BUSINESS_ID] = $this->business_id;
		$strWhere[NATURE_BUSINESS_NATURE_CODE] = $this->getData(NATURE_BUSINESS_NATURE_CODE);

		$result = ebpls_select_data( $this->m_dbLink, NATURE_BUSINESS_TABLE, $strValues, $strWhere, NULL, $strOrderBy, "DESC", NULL );

		if ( is_array($result) ) {

			$this->data_elems = $result[0];
			return 1;

		} else {

			if ( $result < 0 || $result == "" ) {

				$this->setError( -1, "EBPLSEnterpriseNature with nature code " . $strWhere[NATURE_BUSINESS_NATURE_CODE] . " not found.[$result]");

			} else {

				$this->setError( $result, get_db_error() );

			}

			return -1;

		}

	}

	

	function viewData( $business_id, $nature_code ) {
		
		$strValues[$key] = "*";
		$strWhere[BUSINESS_ID] = $business_id;
		$strWhere[NATURE_BUSINESS_NATURE_CODE] = $nature_code;

		$result = ebpls_select_data( $this->m_dbLink, NATURE_BUSINESS_TABLE, $strValues, $strWhere, NULL, $strOrderBy, "DESC", NULL );

		if ( is_array($result) ) {

			$this->data_elems = $result[0];
			return 1;

		} else {

			if ( $result < 0 || $result == "" ) {

				$this->setError( -1, "EBPLSEnterpriseNature with nature code " . $strWhere[NATURE_BUSINESS_NATURE_CODE] . " not found.[$result]");

			} else {

				$this->setError( $result, get_db_error() );

			}

			return -1;

		}

	}

	function load() {

		$strValues[$key] = "*";
		//$strWhere[BUSINESS_ID] = $this->business_id;
		$strWhere[NATURE_BUSINESS_NATURE_CODE] = $this->getData(NATURE_BUSINESS_NATURE_CODE);

		$result = ebpls_select_data( $this->m_dbLink, NATURE_BUSINESS_REF_TABLE, $strValues, $strWhere, NULL, $strOrderBy, "DESC", NULL );

		if ( is_array($result) ) {

			$this->data_elems[NATURE_BUSINESS_ID] = $this->business_id;
			$this->data_elems[NATURE_BUSINESS_NATURE_CODE] = $result[0][NATURE_BUSINESS_NATURE_CODE];
			unset($this->data_elems[NATURE_BUSINESS_NATURE_DESC]);

			return 1;

		} else {

			if ( $result < 0 || $result == "" ) {

				$this->setError( -1, "EBPLSEnterpriseNature with nature code " . $strWhere[NATURE_BUSINESS_NATURE_CODE] . " not found.[$result]");

			} else {

				$this->setError( $result, get_db_error() );

			}

			return -1;

		}

	}

}

?>
