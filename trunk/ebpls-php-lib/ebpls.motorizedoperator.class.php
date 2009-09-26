<?

require_once("ebpls-php-lib/ebpls.dataencapsulator.class.php");
require_once("ebpls-php-lib/ebpls.global.funcs.php");
require_once("ebpls-php-lib/ebpls.database.funcs.php");

define(EBPLS_MOTORIZED_OPERATOR_TABLE,"ebpls_motorized_operators");
define(EBPLS_MOTORIZED_VEHICLES_TABLE,"ebpls_motorized_vehicles");

// motorized owner link table columns
define(EBPLS_MOTORIZED_OPERATOR_ID,"motorized_operator_id");
define(EBPLS_MOTORIZED_OWNER_ID,"owner_id");
define(EBPLS_MOTORIZED_AFFILIATIONS,"affiliations");
define(EBPLS_MOTORIZED_LASTUPDATED,"last_updated_ts");
define(EBPLS_MOTORIZED_CREATEDTS,"created_ts");
define(EBPLS_MOTORIZED_UPDATEDBY,"admin");

// motorized operator vehicle columns
define(EBPLS_MOTORIZED_VEH_MOTOR_ID,"motorized_motor_id");
define(EBPLS_MOTORIZED_VEH_OPERATOR_ID,"motorized_operator_id");
define(EBPLS_MOTORIZED_VEH_MOTOR_MODEL,"motorized_motor_model");
define(EBPLS_MOTORIZED_VEH_MOTOR_NO,"motorized_motor_no");
define(EBPLS_MOTORIZED_VEH_CHASSIS_NO,"motorized_chassis_no");
define(EBPLS_MOTORIZED_VEH_PLATE_NO,"motorized_plate_no");
define(EBPLS_MOTORIZED_VEH_BODY_NO,"motorized_body_no");
define(EBPLS_MOTORIZED_VEH_CREATE_TS,"create_ts");
define(EBPLS_MOTORIZED_VEH_UPDATED_TS,"updated_ts");
define(EBPLS_MOTORIZED_VEH_ADMIN,"admin");

class EBPLSMotorizedOperator extends DataEncapsulator {

	var $m_dbLink;

	/**
	 * Instatiation method, set $bDebug to true to print debug messages otherwise set to false.
	 * $dbLink is a valid database connection.
	 *
	 */
	function EBPLSMotorizedOperator( $dbLink, $bDebug = false ) {

		$this->m_dbLink = $dbLink;
		$this->setDebugMode( $bDebug );

		// add data elements of class with their respective validation functions an params, used default value for 4th parameter
		// for error message pair in case validation failed
		$this->addDataElement(EBPLS_MOTORIZED_OWNER_ID,"is_valid_number", "[VALUE]");
		$this->addDataElement(EBPLS_MOTORIZED_AFFILIATIONS, NULL, NULL );
		$this->addDataElement(EBPLS_MOTORIZED_LASTUPDATED,"is_valid_date", "[VALUE]", true);
		$this->addDataElement(EBPLS_MOTORIZED_CREATEDTS,"is_valid_date", "[VALUE]", true);
		$this->addDataElement(EBPLS_MOTORIZED_UPDATEDBY,"is_not_empty", "[VALUE]", true);

	}

	/**
	 * Adds new owner to ebls_owner table
	 *
	 */
	function add( ){

		if ( $this->m_dbLink ) {

			if ( ( $error_num = $this->validateData() ) > 0 ) {

				$this->data_elems[ EBPLS_MOTORIZED_LASTUPDATED ] = date("Y-d-m H:i:s");
				$this->data_elems[ EBPLS_MOTORIZED_CREATEDTS ] = date("Y-d-m H:i:s");
				$strValues = $this->data_elems;

				$this->debug("DATE : " . $this->data_elems[ EBPLS_MOTORIZED_CREATEDTS ]);

				$ret = ebpls_insert_data( $this->m_dbLink, EBPLS_MOTORIZED_OPERATOR_TABLE, $strValues );

				if ( $ret < 0 ) {

					$this->debug( "CREATE MOTORIZED OPERATOR FAILED [error:$ret,msg=" . get_db_error() . "]" );

					$this->setError( $ret, get_db_error() );

					return $ret;

				} else {

					$this->debug( "CREATE MOTORIZED OPERATOR SUCCESSFULL [$ret]" );
					$this->data_elems[ EBPLS_MOTORIZED_OWNER_ID ] = $ret;
					return $ret;

				}


			} else {

				$this->debug( "CREATE MOTORIZED OPERATOR FAILED [error:$ret,msg=" . get_db_error() . "]" );
				return $error_num;

			}

		} else {

			$this->debug( "CREATE MOTORIZED OPERATOR FAILED INVALID DB LINK $this->m_dbLink" );
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
		
		$strWhere[EBPLS_MOTORIZED_OWNER_ID] = $owner_id;
		
		$result = ebpls_select_data( $this->m_dbLink, EBPLS_MOTORIZED_OPERATOR_TABLE, $strValues, $strWhere, NULL, $strOrderBy, "DESC", NULL );

		if ( is_array($result) ) {

			$this->data_elems = $result[0];
			return $result[0][EBPLS_MOTORIZED_OPERATOR_ID];

		} else {

			$this->setError( $result, get_db_error() );
			return -1;

		}

	}

	function addVehicle( $motor_model, $motor_no, $chassis_no, $plate_no, $body_no, $admin ) {

		if ( $motor_model == "" || $motor_no == "" || $chassis_no == "" || $plate_no == "" || $body_no == "" ) {
			
			$this->setError(-1,"Incomplete parameter value passed to addVehicle function.");
			return -1;	
			
		}

		$clsVehicle = new EBPLSMotorizedVehicle( $this->m_dbLink, false );

		if ( $this->getData(EBPLS_MOTORIZED_OWNER_ID) == NULL || $this->getData(EBPLS_MOTORIZED_OWNER_ID) == "") {

			$this->setError(-1,"Add Vehicle failed, EBPLSMotorizedOperator is not yet loaded. Load first object before invoking this function.");
			return -1;

		}

		$clsVehicle->setData(EBPLS_MOTORIZED_VEH_OPERATOR_ID,$this->getData(EBPLS_MOTORIZED_OPERATOR_ID));
		$clsVehicle->setData(EBPLS_MOTORIZED_VEH_MOTOR_MODEL,$motor_model);
		$clsVehicle->setData(EBPLS_MOTORIZED_VEH_MOTOR_NO,$motor_no);
		$clsVehicle->setData(EBPLS_MOTORIZED_VEH_CHASSIS_NO,$chassis_no);
		$clsVehicle->setData(EBPLS_MOTORIZED_VEH_PLATE_NO,$plate_no);
		$clsVehicle->setData(EBPLS_MOTORIZED_VEH_BODY_NO,$body_no);
		$clsVehicle->setData(EBPLS_MOTORIZED_VEH_ADMIN,$admin);

		$ret = $clsVehicle->add();
		
		if ( !is_array($ret) ) {
						
			$err = $clsVehicle->getError();
			$this->setError( -1, $err[0]["err_mesg"] );
				
		}

		return $ret;

	}


	function deleteVehicle( $motor_vehicle_id ) {

		$clsVehicle = new EBPLSMotorizedVehicle( $this->m_dbLink, false );

		$ret = $clsVehicle->delete( $motor_vehicle_id );

		return $ret;

	}

	function updateVehicle( $motor_vehicle_id, $motor_model, $motor_no, $chassis_no, $plate_no, $body_no, $admin ) {

		$clsVehicle = new EBPLSMotorizedVehicle( $this->m_dbLink, false );

		if ( $this->getData(EBPLS_MOTORIZED_OWNER_ID) == NULL || $this->getData(EBPLS_MOTORIZED_OWNER_ID) == "" ) {

			$this->setError(-1,"Update Vehicle failed, EBPLSMotorizedOperator is not yet loaded. Load first object before invoking this function.");
			return -1;

		}

		//$clsVehicle->setData(EBPLS_MOTORIZED_VEH_OPERATOR_ID,$this->getData(EBPLS_MOTORIZED_OPERATOR_ID));
		$clsVehicle->setData(EBPLS_MOTORIZED_VEH_MOTOR_MODEL,$motor_model);
		$clsVehicle->setData(EBPLS_MOTORIZED_VEH_MOTOR_NO,$motor_no);
		$clsVehicle->setData(EBPLS_MOTORIZED_VEH_CHASSIS_NO,$chassis_no);
		$clsVehicle->setData(EBPLS_MOTORIZED_VEH_PLATE_NO,$plate_no);
		$clsVehicle->setData(EBPLS_MOTORIZED_VEH_BODY_NO,$body_no);
		$clsVehicle->setData(EBPLS_MOTORIZED_VEH_ADMIN,$admin);

		$ret = $clsVehicle->update( $motor_vehicle_id );

		return $ret;

	}

	function getVehicles() {

		$clsVehicle = new EBPLSMotorizedVehicle( $this->m_dbLink, false );
		$result = $clsVehicle->search( $this->getData(EBPLS_MOTORIZED_OPERATOR_ID) );
		return $result;

	}

	/**
	 *
	 **/
	function update( $owner_id ) {

		$this->data_elems[ EBPLS_MOTORIZED_LASTUPDATED ] = date("Y-d-m H:i:s");

		$arrData = $this->getData();
		foreach( $arrData as $key=>$value){

			if ( $arrData[$key] != NULL ) {

				$strValues[$key] = $value;

			}

		}

		if ( ( $error_num = $this->validateData(true) ) > 0 ) {

			$strWhere[EBPLS_MOTORIZED_OWNER_ID] = $owner_id;

			$ret = ebpls_update_data( $this->m_dbLink, EBPLS_MOTORIZED_OPERATOR_TABLE, $strValues, $strWhere );

			if ( $ret < 0 ) {

				$this->debug( "UPDATE MOTORIZED OPERATOR FAILED [error:$ret,msg=" . get_db_error() . "]" );

				$this->setError( $ret, get_db_error() );

				return $ret;

			} else {

				$this->debug( "UPDATE MOTORIZED OPERATOR SUCCESSFULL [$ret]" );
				return $ret;

			}

		} else {

			$this->debug( "CREATE OWNER FAILED [error:$ret,msg=" . get_db_error() . "]" );
			return -1;

		}

	}

	function delete( $owner_id ) {

		$strWhere[EBPLS_MOTORIZED_OWNER_ID] = $owner_id;
		
		$result = ebpls_delete_data ( $this->m_dbLink, EBPLS_MOTORIZED_OPERATOR_TABLE, $strWhere );

		if ( $result < 0 )  {

			$this->setError( $result, get_db_error() );

		}

		return $result;

	}


	function search( $mot_owner_id, $page = 1, $maxrec = 1000000000, $orderkey = EBPLS_MOTORIZED_OWNER_ID, $is_desc = true ) {

		$strWhere[EBPLS_MOTORIZED_OWNER_ID] = $mot_owner_id;

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

		$result = ebpls_select_data_bypage( $this->m_dbLink, EBPLS_MOTORIZED_OPERATOR_TABLE, $strValues, $strWhere, NULL, $strOrder, $is_desc?"DESC":"ASC", $page, $maxrec );

		if ( !is_array($result) && $result < 0 ) {


			$this->setError ( $result, get_db_error());
			return $result;

		} else {

			// transform result to EBPLSOwner object
			for ( $i = 0 ; $i < count($result["result"]); $i++ ) {
				$records[$i] = new EBPLSMotorizedOperator($this->m_dbLink);
				$records[$i]->setData( NULL, $result["result"][$i] );
			}

			$result["result"] = $records;

			return $result;

		}

	}


}

class EBPLSMotorizedVehicle extends DataEncapsulator {

	var $m_dbLink;

	function EBPLSMotorizedVehicle( $dbLink, $bDebug = false ) {

		$this->m_dbLink = $dbLink;
		$this->setDebugMode( $bDebug );

		$this->addDataElement(EBPLS_MOTORIZED_VEH_MOTOR_ID,"is_valid_number", "[VALUE]", true );
		$this->addDataElement(EBPLS_MOTORIZED_VEH_OPERATOR_ID, NULL, NULL );
		$this->addDataElement(EBPLS_MOTORIZED_VEH_MOTOR_MODEL,"is_not_empty", "[VALUE]");
		$this->addDataElement(EBPLS_MOTORIZED_VEH_MOTOR_NO,"is_not_empty", "[VALUE]");
		$this->addDataElement(EBPLS_MOTORIZED_VEH_CHASSIS_NO,"is_not_empty", "[VALUE]");
		$this->addDataElement(EBPLS_MOTORIZED_VEH_PLATE_NO,"is_not_empty", "[VALUE]");
		$this->addDataElement(EBPLS_MOTORIZED_VEH_BODY_NO,"is_not_empty", "[VALUE]");
		$this->addDataElement(EBPLS_MOTORIZED_VEH_BODY_NO,"is_not_empty", "[VALUE]");
		$this->addDataElement(EBPLS_MOTORIZED_VEH_CREATE_TS,"is_valid_date","[VALUE]", true );
		$this->addDataElement(EBPLS_MOTORIZED_VEH_UPDATED_TS,"is_valid_date","[VALUE]", true );
		$this->addDataElement(EBPLS_MOTORIZED_VEH_ADMIN,"is_not_empty","[VALUE]" );

	}

	function add(){

		if ( $this->m_dbLink ) {

			$this->data_elems[ EBPLS_MOTORIZED_VEH_CREATE_TS ] = date("Y-d-m H:i:s");
			$this->data_elems[ EBPLS_MOTORIZED_VEH_UPDATED_TS ] = date("Y-d-m H:i:s");
				
			if ( ( $error_num = $this->validateData() ) > 0 ) {

				$strValues = $this->data_elems;

				$this->debug("DATE : " . $this->data_elems[ EBPLS_MOTORIZED_VEH_CREATE_TS ] );

				$ret = ebpls_insert_data( $this->m_dbLink, EBPLS_MOTORIZED_VEHICLES_TABLE, $strValues );

				if ( $ret < 0 ) {

					$this->debug( "CREATE MOTORIZED VEH FAILED [error:$ret,msg=" . get_db_error() . "]" );
					$this->setError( $ret, get_db_error() );

					return $ret;

				} else {

					$this->debug( "CREATE MOTORIZED VEH SUCCESSFULL [$ret]" );
					$this->data_elems[ EBPLS_MOTORIZED_VEH_MOTOR_ID ] = $ret;
					
					return $ret;

				}

			} else {

				$this->debug( "CREATE MOTORIZED VEH FAILED [error:$ret,msg=" . get_db_error() . "]" );
				return $error_num;

			}

		} else {

			$this->debug( "CREATE MOTORIZED VEH FAILED INVALID DB LINK $this->m_dbLink" );
			$this->setError( $ret, "Invalid Db link $this->m_dbLink" );
			return -1;

		}

	}

	function delete( $veh_id ) {

		$strWhere[EBPLS_MOTORIZED_VEH_MOTOR_ID] = $veh_id;

		$result = ebpls_delete_data ( $this->m_dbLink, EBPLS_MOTORIZED_VEHICLES_TABLE, $strWhere );

		if ( $result < 0 )  {

			$this->setError( $result, get_db_error() );

		}

		return $result;

	}

	function update( $veh_id ){

		$this->data_elems[ EBPLS_MOTORIZED_VEH_UPDATED_TS ] = date("Y-d-m H:i:s");

		$arrData = $this->getData();

		foreach( $arrData as $key=>$value ){

			if ( $arrData[$key]!="" && $arrData[$key] != NULL ) {

				$strValues[$key] = $value;

			}

		}

		if ( ( $error_num = $this->validateData(true) ) > 0 ) {

			$strWhere[EBPLS_MOTORIZED_VEH_MOTOR_ID] = $veh_id;

			$ret = ebpls_update_data( $this->m_dbLink, EBPLS_MOTORIZED_VEHICLES_TABLE, $strValues, $strWhere );

			if ( $ret < 0 ) {

				$this->debug( "UPDATE MOTORIZED VEHICLE FAILED [error:$ret,msg=" . get_db_error() . "]" );
				$this->setError( $ret, get_db_error() );

				return $ret;

			} else {

				$this->debug( "UPDATE MOTORIZED VEHICLE SUCCESSFULL [$ret]" );
				return $ret;

			}

		} else {

			$this->debug( "CREATE MOTORIZED VEHICLE FAILED [error:$ret,msg=" . get_db_error() . "]" );
			return -1;

		}

	}

	function search( $operator_id, $motor_model = NULL, $motor_no = NULL, $chassis_no = NULL, $plate_no = NULL, $body_no = NULL, $page = 1, $maxrec = 1000000, $orderkey = EBPLS_MOTORIZED_VEH_MOTOR_ID, $is_desc = true ) {

		if ( $operator_id != NULL )
			$strWhere[EBPLS_MOTORIZED_VEH_OPERATOR_ID] = $operator_id;

		if ( $motor_model != NULL )
			$strWhere[EBPLS_MOTORIZED_VEH_MOTOR_MODEL] = array("like", "$motor_model%");

		if ( $motor_no != NULL )
			$strWhere[EBPLS_MOTORIZED_VEH_MOTOR_NO] = array("like", "$motor_no%");

		if ( $chassis_no != NULL )
			$strWhere[EBPLS_MOTORIZED_VEH_CHASSIS_NO] = array("like", "$chassis_no%");

		if ( $plate_no != NULL )
			$strWhere[EBPLS_MOTORIZED_VEH_PLATE_NO] = array("like", "$plate_no%");

		if ( $body_no != NULL )
			$strWhere[EBPLS_MOTORIZED_VEH_BODY_NO] = array("like", "$body_no%");

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

		$result = ebpls_select_data_bypage( $this->m_dbLink, EBPLS_MOTORIZED_VEHICLES_TABLE, $strValues, $strWhere, NULL, $strOrder, $is_desc?"DESC":"ASC", $page, $maxrec );

		if ( !is_array($result) && $result < 0 ) {

			$this->setError ( $result, get_db_error());
			return $result;

		} else {

			// transform result to EBPLSOwner object
			for ( $i = 0 ; $i < count($result["result"]); $i++ ) {
				$records[$i] = new EBPLSMotorizedVehicle($this->m_dbLink);
				$records[$i]->setData( NULL, $result["result"][$i] );
			}

			$result["result"] = $records;

			return $result;

		}

	}

}

?>
