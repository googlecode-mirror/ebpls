<?

require_once("ebpls-php-lib/ebpls.dataencapsulator.class.php");
require_once("ebpls-php-lib/ebpls.global.funcs.php");
require_once("ebpls-php-lib/ebpls.database.funcs.php");

define(EBPLS_FISHERY_OPERATOR_TABLE,"ebpls_fishery_operators");
define(EBPLS_FISHERY_VEHICLES_TABLE,"ebpls_fishery_vehicles");

// motorized owner link table columns
define(EBPLS_FISHERY_OPERATOR_ID,"fishery_operator_id");
define(EBPLS_FISHERY_OWNER_ID,"owner_id");
define(EBPLS_FISHERY_BUSINESS_NAME,"fishery_business_name");
define(EBPLS_FISHERY_LOCAL_NAME_FISHING_GEAR,"fishery_local_name_fishing_gear");
define(EBPLS_FISHERY_IN_ENGLISH,"fishery_in_english");
define(EBPLS_FISHERY_UNITS_COUNT,"fishery_units_count");
define(EBPLS_FISHERY_ASSESS_VALUE_FISHING_GEAR,"fishery_fishing_gear_value");
define(EBPLS_FISHERY_FISHING_GEAR_SIZE,"fishery_fishing_gear_size");
define(EBPLS_FISHERY_AREA_SIZE,"fishery_area_size");
define(EBPLS_FISHERY_CREW_COUNT,"fishery_crew_count");
define(EBPLS_FISHERY_MOTORIZED,"fishery_motorized");
define(EBPLS_FISHERY_REGISTERED,"fishery_registered");
define(EBPLS_FISHERY_REGISTRATION_NO,"fishery_registration_no");
define(EBPLS_FISHERY_PRESENT_AVE_FISH_CATCH,"fishery_present_ave_fish_catch");
define(EBPLS_FISHERY_LAST2YRS_AVE_FISH_CATCH,"fishery_last2yrs_ave_fish_catch");
define(EBPLS_FISHERY_LOCATION,"fishery_location");
define(EBPLS_FISHERY_RC_NO,"fishery_rc_no");
define(EBPLS_FISHERY_RC_PLACE_ISSUED,"fishery_rc_place_issued");
define(EBPLS_FISHERY_RC_DATE_ISSUED,"fishery_rc_date_issued");
define(EBPLS_FISHERY_LASTUPDATED,"fishery_updated_ts");
define(EBPLS_FISHERY_CREATEDTS,"fishery_created_ts");
define(EBPLS_FISHERY_UPDATEDBY,"fishery_updated_by");

// fishery operator vehicle columns
define(EBPLS_FISHERY_VEHICLE_ID,"fishery_vehicle_id");
define(EBPLS_FISHERY_OPERATOR_ID,"fishery_opertator_id");
define(EBPLS_FISHERY_BOAT_NAME,"fishery_boat_name");
define(EBPLS_FISHERY_BOAT_NO,"fishery_boat_no");
define(EBPLS_FISHERY_PLATE_NO,"fishery_plate_no");
define(EBPLS_FISHERY_CREATEDTS,"fishery_created_ts");
define(EBPLS_FISHERY_UPDATEDTS,"fishery_updated_ts");
define(EBPLS_FISHERY_UPDATEDBY,"fishery_updated_by");

class EBPLSFisheryOperator extends DataEncapsulator {

	var $m_dbLink;

	/**
	 * Instatiation method, set $bDebug to true to print debug messages otherwise set to false.
	 * $dbLink is a valid database connection.
	 *
	 */
	function EBPLSFisheryOperator( $dbLink, $bDebug = false ) {

		$this->m_dbLink = $dbLink;
		$this->setDebugMode( $bDebug );

		$this->addDataElement(EBPLS_FISHERY_OPERATOR_ID,"is_valid_number", "[VALUE]", true);
		$this->addDataElement(EBPLS_FISHERY_OWNER_ID,"is_valid_number", "[VALUE]");
		$this->addDataElement(EBPLS_FISHERY_BUSINESS_NAME,"is_not_empty", "[VALUE]" );
		$this->addDataElement(EBPLS_FISHERY_LOCAL_NAME_FISHING_GEAR,"is_not_empty", "[VALUE]" );
		$this->addDataElement(EBPLS_FISHERY_IN_ENGLISH, NULL, NULL);
		$this->addDataElement(EBPLS_FISHERY_UNITS_COUNT,"is_valid_number", "[VALUE]" );
		$this->addDataElement(EBPLS_FISHERY_ASSESS_VALUE_FISHING_GEAR,"is_not_empty", "[VALUE]" );
		$this->addDataElement(EBPLS_FISHERY_FISHING_GEAR_SIZE,"is_not_empty", "[VALUE]");
		$this->addDataElement(EBPLS_FISHERY_AREA_SIZE,"is_not_empty", "[VALUE]");
		$this->addDataElement(EBPLS_FISHERY_CREW_COUNT,"is_valid_number", "[VALUE]");
		$this->addDataElement(EBPLS_FISHERY_MOTORIZED, NULL, NULL );
		$this->addDataElement(EBPLS_FISHERY_REGISTERED, NULL, NULL );
		$this->addDataElement(EBPLS_FISHERY_REGISTRATION_NO,"is_not_empty");
		$this->addDataElement(EBPLS_FISHERY_PRESENT_AVE_FISH_CATCH,"is_valid_number", "[VALUE]");
		$this->addDataElement(EBPLS_FISHERY_LAST2YRS_AVE_FISH_CATCH,"is_valid_number", "[VALUE]");
		$this->addDataElement(EBPLS_FISHERY_LOCATION,"is_not_empty", "[VALUE]");
		$this->addDataElement(EBPLS_FISHERY_RC_NO,"is_not_empty", "[VALUE]");
		$this->addDataElement(EBPLS_FISHERY_RC_PLACE_ISSUED,"is_not_empty", "[VALUE]");
		$this->addDataElement(EBPLS_FISHERY_RC_DATE_ISSUED,"is_valid_date", "[VALUE]");
		$this->addDataElement(EBPLS_FISHERY_LOCATION,"is_not_empty", "[VALUE]" );
		$this->addDataElement(EBPLS_FISHERY_LASTUPDATED,"is_valid_date", "[VALUE]", true);
		$this->addDataElement(EBPLS_FISHERY_CREATEDTS,"is_valid_date", "[VALUE]", true);
		$this->addDataElement(EBPLS_FISHERY_UPDATEDBY,"is_not_empty", "[VALUE]", true);

	}

	/**
	 * Adds new owner to ebls_owner table
	 *
	 */
	function add( ){

		if ( $this->m_dbLink ) {

			if ( ( $error_num = $this->validateData() ) > 0 ) {

				$this->data_elems[ EBPLS_FISHERY_CREATEDTS ] = date("Y-d-m H:i:s");
				$this->data_elems[ EBPLS_FISHERY_LASTUPDATED ] = date("Y-d-m H:i:s");
				$strValues = $this->data_elems;

				$this->debug("DATE : " . $this->data_elems[ EBPLS_FISHERY_CREATEDTS ]);

				$ret = ebpls_insert_data( $this->m_dbLink, EBPLS_FISHERY_OPERATOR_TABLE, $strValues );

				if ( $ret < 0 ) {

					$this->debug( "CREATE FISHERY OPERATOR FAILED [error:$ret,msg=" . get_db_error() . "]" );

					$this->setError( $ret, get_db_error() );

					return $ret;

				} else {

					$this->debug( "CREATE FISHERY OPERATOR SUCCESSFULL [$ret]" );
					$this->data_elems[ EBPLS_FISHERY_OPERATOR_ID ] = $ret;
					return $ret;

				}


			} else {

				$err = $this->getError();
				$this->debug( "CREATE FISHERY OPERATOR FAILED [error:$ret,msg=" . $err[0]["err_mesg"] . "]" );
				return $error_num;

			}

		} else {

			$this->debug( "CREATE FISHERY OPERATOR FAILED INVALID DB LINK $this->m_dbLink" );
			$this->setError( $ret, "Invalid Db link $this->m_dbLink" );
			return -1;

		}

	}


	/**
	 * View owner data, loads data using owner id as param
	 *
	 */
	function view( $owner_id ) {

		$strValues[$key] = "*";

		$strWhere[EBPLS_FISHERY_OWNER_ID] = $owner_id;

		$result = ebpls_select_data( $this->m_dbLink, EBPLS_FISHERY_OPERATOR_TABLE, $strValues, $strWhere, NULL, $strOrderBy, "DESC", NULL );

		if ( is_array($result) ) {

			$this->data_elems = $result[0];
			return $result[0][EBPLS_FISHERY_OPERATOR_ID];

		} else {

			$this->setError( $result, get_db_error() );
			return -1;

		}

	}


	function update( $owner_id ) {

		$this->data_elems[ EBPLS_FISHERY_LASTUPDATED ] = date("Y-d-m H:i:s");

		$arrData = $this->getData();
		foreach( $arrData as $key=>$value){

			if ( $arrData[$key] != NULL ) {

				$strValues[$key] = $value;

			}

		}

		if ( ( $error_num = $this->validateData(true) ) > 0 ) {

			$strWhere[EBPLS_FISHERY_OWNER_ID] = $owner_id;

			$ret = ebpls_update_data( $this->m_dbLink, EBPLS_FISHERY_OPERATOR_TABLE, $strValues, $strWhere );

			if ( $ret < 0 ) {

				$this->debug( "UPDATE FISHERY OPERATOR FAILED [error:$ret,msg=" . get_db_error() . "]" );

				$this->setError( $ret, get_db_error() );

				return $ret;

			} else {

				$this->debug( "UPDATE FISHERY OPERATOR SUCCESSFULL [$ret]" );
				return $ret;

			}

		} else {

			$this->debug( "CREATE OWNER FAILED [error:$ret,msg=" . get_db_error() . "]" );
			return -1;

		}

	}

	function delete( $owner_id ) {

		$strWhere[EBPLS_FISHERY_OWNER_ID] = $owner_id;

		$result = ebpls_delete_data ( $this->m_dbLink, EBPLS_FISHERY_OPERATOR_TABLE, $strWhere );

		if ( $result < 0 )  {

			$this->setError( $result, get_db_error() );

		}

		return $result;

	}


	function search( $owner_id, $page = 1, $maxrec = 1000000000, $orderkey = EBPLS_FISHERY_OWNER_ID, $is_desc = true ) {

		$strWhere[EBPLS_FISHERY_OWNER_ID] = $owner_id;

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

		$result = ebpls_select_data_bypage( $this->m_dbLink, EBPLS_FISHERY_OPERATOR_TABLE, $strValues, $strWhere, NULL, $strOrder, $is_desc?"DESC":"ASC", $page, $maxrec );

		if ( !is_array($result) && $result < 0 ) {


			$this->setError ( $result, get_db_error());
			return $result;

		} else {

			// transform result to EBPLSOwner object
			for ( $i = 0 ; $i < count($result["result"]); $i++ ) {

				$records[$i] = new EBPLSFisheryOperator($this->m_dbLink);
				$records[$i]->setData( NULL, $result["result"][$i] );
				unset($records[$i]->data_elems_validate_funcs);
				unset($records[$i]->data_elems_validate_params);
				unset($records[$i]->data_elems_validate_readonly);
				unset($records[$i]->data_elems_validate_error);

				//data_elems_validate_params
			}

			$result["result"] = $records;

			return $result;

		}

	}


	function addVehicle( $boat_name, $boat_no, $plate_no, $admin ) {

		if ( $boat_name == "" || $boat_no == "" || $plate_no == "" ) {

			$this->setError(-1,"EBPLSFisheryOperator : Incomplete parameter value passed to addVehicle function.");
			return -1;

		}

		$clsVehicle = new EBPLSFisheryVehicle( $this->m_dbLink, $this->m_bDebug );

		if ( $this->getData(EBPLS_FISHERY_OWNER_ID) == NULL || $this->getData(EBPLS_FISHERY_OWNER_ID) == "") {

			$this->setError(-1,"Add Vehicle failed, EBPLSFisheryOperator is not yet loaded. Load first object before invoking this function.");
			return -1;

		}

		// fishery operator vehicle columns
		$clsVehicle->data_elems[EBPLS_FISHERY_OPERATOR_ID] = $this->getData(EBPLS_FISHERY_OPERATOR_ID);
		$clsVehicle->setData(EBPLS_FISHERY_BOAT_NAME,"$boat_name");
		$clsVehicle->setData(EBPLS_FISHERY_BOAT_NO,"$boat_no");
		$clsVehicle->setData(EBPLS_FISHERY_PLATE_NO,"$plate_no");
		$clsVehicle->data_elems[EBPLS_FISHERY_CREATEDTS] = date("Y-m-d H:i:s");
		$clsVehicle->data_elems[EBPLS_FISHERY_UPDATEDTS] = date("Y-m-d H:i:s");
		$clsVehicle->data_elems[EBPLS_FISHERY_UPDATEDBY] = $admin;

		$ret = $clsVehicle->add();

		$this->debug("$ret = addVehicle($boat_name, $boat_no, $plate_no, $admin)");

		if ( $ret <= 0 ) {

			$err = $clsVehicle->getError();
			$this->setError( -1, $err[0]["err_mesg"] );

		}

		return $ret;

	}
	
	function deleteVehicle( $motor_vehicle_id ) {

		$clsVehicle = new EBPLSFisheryVehicle( $this->m_dbLink, false );

		$ret = $clsVehicle->delete( $motor_vehicle_id );

		return $ret;

	}

	
	function updateVehicle( $motor_vehicle_id,  $boat_name, $boat_no, $plate_no, $admin ) {

		$clsVehicle = new EBPLSFisheryVehicle( $this->m_dbLink, false );
		
		$clsVehicle->setData(EBPLS_FISHERY_BOAT_NAME,"$boat_name");
		$clsVehicle->setData(EBPLS_FISHERY_BOAT_NO,"$boat_no");
		$clsVehicle->setData(EBPLS_FISHERY_PLATE_NO,"$plate_no");
		$clsVehicle->data_elems[EBPLS_FISHERY_CREATEDTS] = date("Y-m-d H:i:s");
		$clsVehicle->data_elems[EBPLS_FISHERY_UPDATEDTS] = date("Y-m-d H:i:s");
		$clsVehicle->data_elems[EBPLS_FISHERY_UPDATEDBY] = $admin;
		
		$ret = $clsVehicle->update( $motor_vehicle_id );

		return $ret;

	}


	function getVehicles() {

		$clsVehicle = new EBPLSFisheryVehicle( $this->m_dbLink, false );
		$result = $clsVehicle->search( $this->getData(EBPLS_FISHERY_OPERATOR_ID) );
		return $result;

	}


}


class EBPLSFisheryVehicle extends DataEncapsulator {

	var $m_dbLink;

	function EBPLSFisheryVehicle( $dbLink, $bDebug = false ) {

		$this->m_dbLink = $dbLink;
		$this->setDebugMode( $bDebug );

		$this->addDataElement(EBPLS_FISHERY_VEHICLE_ID,"is_valid_number", "[VALUE]", true );
		$this->addDataElement(EBPLS_FISHERY_OPERATOR_ID,"is_valid_number", "[VALUE]");
		$this->addDataElement(EBPLS_FISHERY_BOAT_NAME,"is_not_empty", "[VALUE]" );
		$this->addDataElement(EBPLS_FISHERY_BOAT_NO,"is_not_empty", "[VALUE]" );
		$this->addDataElement(EBPLS_FISHERY_PLATE_NO,"is_not_empty", "[VALUE]" );
		$this->addDataElement(EBPLS_FISHERY_CREATEDTS,"is_valid_date", "[VALUE]", true );
		$this->addDataElement(EBPLS_FISHERY_UPDATEDTS,"is_valid_date", "[VALUE]", true );
		$this->addDataElement(EBPLS_FISHERY_UPDATEDBY,"is_not_empty");


	}

	function add(){

		if ( $this->m_dbLink ) {

			$this->data_elems[ EBPLS_FISHERY_CREATEDTS ] = date("Y-d-m H:i:s");
			$this->data_elems[ EBPLS_FISHERY_UPDATEDTS ] = date("Y-d-m H:i:s");

			if ( ( $error_num = $this->validateData() ) > 0 ) {

				$strValues = $this->data_elems;

				$this->debug("DATE : " . $this->data_elems[ EBPLS_FISHERY_CREATEDTS ] );

				$ret = ebpls_insert_data( $this->m_dbLink, EBPLS_FISHERY_VEHICLES_TABLE, $strValues );

				if ( $ret < 0 ) {

					$this->debug( "CREATE FISHERY VEH FAILED [error:$ret,msg=" . get_db_error() . "]" );
					$this->setError( $ret, get_db_error() );

					return $ret;

				} else {

					$this->debug( "CREATE FISHERY VEH SUCCESSFULL [$ret]" );
					$this->data_elems[ EBPLS_FISHERY_VEHICLE_ID ] = $ret;

					return $ret;

				}

			} else {

				$this->debug( "CREATE FISHERY VEH FAILED [error:$ret,msg=" . $this->toStringError() . "]" );
				return $error_num;

			}

		} else {

			$this->debug( "CREATE FISHERY VEH FAILED INVALID DB LINK $this->m_dbLink" );
			$this->setError( $ret, "Invalid Db link $this->m_dbLink" );
			return -1;

		}

	}

	function delete( $veh_id ) {

		$strWhere[EBPLS_FISHERY_VEHICLE_ID] = $veh_id;

		$result = ebpls_delete_data ( $this->m_dbLink, EBPLS_FISHERY_VEHICLES_TABLE, $strWhere );

		if ( $result < 0 )  {

			$this->setError( $result, get_db_error() );

		}

		return $result;

	}

	function update( $veh_id ){


		$this->data_elems[ EBPLS_FISHERY_UPDATEDTS ] = date("Y-d-m H:i:s");

		$arrData = $this->getData();

		foreach( $arrData as $key=>$value ){

			if ( $arrData[$key]!="" && $arrData[$key] != NULL ) {

				$strValues[$key] = $value;

			}

		}

		if ( ( $error_num = $this->validateData(true) ) > 0 ) {

			$strWhere[EBPLS_FISHERY_VEHICLE_ID] = $veh_id;

			$ret = ebpls_update_data( $this->m_dbLink, EBPLS_FISHERY_VEHICLES_TABLE, $strValues, $strWhere );

			if ( $ret < 0 ) {

				$this->debug( "UPDATE FISHERY VEHICLE FAILED [error:$ret,msg=" . get_db_error() . "]" );
				$this->setError( $ret, get_db_error() );

				return $ret;

			} else {

				$this->debug( "UPDATE FISHERY VEHICLE SUCCESSFULL [$ret]" );
				return $ret;

			}

		} else {

			$this->debug( "CREATE FISHERY VEHICLE FAILED [error:$ret,msg=" . get_db_error() . "]" );
			return -1;

		}

	}

	function search( $operator_id, $boat_name = NULL, $boat_no = NULL, $plate_no = NULL, $page = 1, $maxrec = 1000000, $orderkey = EBPLS_FISHERY_BOAT_NAME, $is_desc = true ) {

		if ( $operator_id != NULL )
			$strWhere[EBPLS_FISHERY_OPERATOR_ID] = $operator_id;

		if ( $motor_no != NULL )
			$strWhere[EBPLS_FISHERY_BOAT_NAME] = array("like", "$boat_name%");

		if ( $chassis_no != NULL )
			$strWhere[EBPLS_FISHERY_BOAT_NO] = array("like", "$boat_no%");

		if ( $plate_no != NULL )
			$strWhere[EBPLS_FISHERY_PLATE_NO] = array("like", "$plate_no%");


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

		$result = ebpls_select_data_bypage( $this->m_dbLink, EBPLS_FISHERY_VEHICLES_TABLE, $strValues, $strWhere, NULL, $strOrder, $is_desc?"DESC":"ASC", $page, $maxrec );

		if ( !is_array($result) && $result < 0 ) {

			$this->setError ( $result, get_db_error());
			return $result;

		} else {

			// transform result to EBPLSOwner object
			for ( $i = 0 ; $i < count($result["result"]); $i++ ) {
				$records[$i] = new EBPLSFisheryVehicle($this->m_dbLink);
				$records[$i]->setData( NULL, $result["result"][$i] );
			}

			$result["result"] = $records;

			return $result;

		}

	}

}



?>

