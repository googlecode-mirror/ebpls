<?
/*****************************************************


*****************************************************/
require_once("ebpls-php-lib/ebpls.database.funcs.php");
require_once("ebpls-php-lib/ebpls.taxfeeref.class.php");
require_once("ebpls-php-lib/ebpls.enterprise.class.php");
require_once("ebpls-php-lib/ebpls.transaction.class.php");

// code retrieval
function get_next_system_code( $dbLink, $key ) {

	global $gCodesKeys;

	if ( !in_array( $key, $gCodesKeys ) ) {

		echo "<HR><B>get_next_system_code ( $dbLink, $key) == Param Error :Invalid key value passed $key<BR></B>";

	}

	$strValues[$key] = $key;

	$strWhere = NULL;

	$result = ebpls_select_data( $dbLink, "ebpls_codes_table", $strValues, $strWhere, NULL, NULL, "DESC", NULL );

	if ( is_array($result) ) {

		$code = $result[0][$key];
		$code++;

		return $code;

	} else {

		//print_r(get_db_error());
		return NULL;

	}

}


function update_system_code ( $dbLink,  $key, $value ) {

	$strValues[$key] = $value;
	$strWhere["id"] = 1;
	$ret = ebpls_update_data( $dbLink, "ebpls_codes_table", $strValues, $strWhere );

	//print_r(get_db_error());

	return $ret;

}


/**************************************************************************************************************
 * EBPLSTransaction class utility db functions.
 **************************************************************************************************************/

/**
 * Get all permit requirements of a particular type of permit type.
 *
 **/
function get_permit_default_requirements( $permit_type ) {

	$strWhere[$permit_type] = $permit_type;
	$strValues[] = " * ";

	$result = ebpls_select_data( $dbLink, "ebpls_permit_requirements", $strValues, $strWhere, NULL, NULL, "DESC", NULL );

	if ( is_array($result) ) {

		for($i=0; $i< count($result); $i++) {
			$record[] = $result[$i];
		}

		return $record;

	} else {

		//print_r(get_db_error());
		return NULL;

	}

}

/**
 * Create default requiremetns to ebpls_transaction_fees and ebpls_transaction_requirements table
 *
 *
 **/
function create_permit_requirements( $dbLink, $trans_id, $permit_id, $permit_type, $creator, $permit_req_type, $trans_type ) {

	if ( $permit_req_type == "FEE" || $permit_req_type == "TAX"  ) {

		require_once("ebpls-php-lib/ebpls.taxfeeformula.class.php");

		if ( $permit_req_type == "FEE" ) {

			$sql .= " SELECT b.tax_fee_code, b.tax_fee_desc,b.tax_account_code, b.tax_formula_id ";
			$sql .= " FROM ebpls_permit_fee_requirements AS a INNER JOIN ebpls_tax_fee_table AS b on a.tax_fee_code = b.tax_fee_code WHERE a.permit_type = '$permit_type' and a.pfr_trans_type = '$trans_type' and b.tax_fee_type = 'FEE'";

		} else if ( $permit_req_type == "TAX" ) {

			$sql .= " SELECT b.tax_fee_code, b.tax_fee_desc,b.tax_account_code, b.tax_formula_id ";
			$sql .= " FROM ebpls_permit_tax_requirements AS a INNER JOIN ebpls_tax_fee_table AS b on a.tax_fee_code = b.tax_fee_code WHERE a.permit_type = '$permit_type' and a.ptr_trans_type = '$trans_type' and b.tax_fee_type = 'TAX'";

		}

		ebpls_db_funcs_debug ( "create_permit_requirements : $sql" );

		$result = mysql_query( $sql, $dbLink);

		if ( $result ) {

			$i = 0;

			while( $row = mysql_fetch_array( $result ) ) {

				$clsTaxFormula = new TaxFeeFormula( $dbLink, false );
				$clsTaxFormula->view( $row["tax_formula_id"] );
				$clsFormula = unserialize($clsTaxFormula->getData(EBPLS_FORMULAS_FORMULA_CLASS));
				
				if ( $clsFormula ) {
					
					$tax_total_amount_due = $clsFormula->computeTax();
		
					if ( $tax_total_amount_due >= 0 ) {
						
						if ( $tax_total_amount_due == "" || $tax_total_amount_due == NULL ) {
							$tax_total_amount_due = 0;
						}
			
						if ( $permit_req_type == "FEE" ) {
			
							$sql = "INSERT INTO ebpls_transaction_payables ( trans_id, permit_id,permit_type, tax_fee_type, tax_fee_code, tax_fee_desc, tax_account_code, tax_business_nature_code, tax_total_amount_due, ts_create, last_updated_by )";
							$sql .= " values($trans_id, $permit_id, '$permit_type', 'FEE','" . $row["tax_fee_code"] . "','" . $row["tax_fee_desc"] . "','" . $row["tax_account_code"] . "','NONE', $tax_total_amount_due, now(), '$creator')";
			
						} else {
			
							$sql = "INSERT INTO ebpls_transaction_payables ( trans_id, permit_id,permit_type, tax_fee_type, tax_fee_code, tax_business_nature_code, tax_fee_desc, tax_account_code, tax_total_amount_due, ts_create, last_updated_by )";
							$sql .= " values($trans_id, $permit_id, '$permit_type', 'TAX','" . $row["tax_fee_code"] . "','" . $row["tax_business_nature_code"] . "','" . $row["tax_fee_desc"] . "','" . $row["tax_account_code"] . "', $tax_total_amount_due, now(), '$creator')";
			
						}
			
						ebpls_db_funcs_debug ( "create_permit_requirements insert : $sql" );
			
						$rs = mysql_query( $sql, $dbLink);
						$i++;
						
					} else {
										
						set_db_error(NULL, "Invalid formula for tax : " . $row["tax_formula_id"]); 						
						return -5;
						
					}
					
				} else {
					
					set_db_error(NULL, "Invalid formula for tax : " . $row["tax_formula_id"]); 					
					return -5;
					
				}

			}

			return $i;

		} else {

			set_db_error($dbLink);
			return -1;

		}

	} else if ( $permit_req_type == "APP" ) {

		$sql = "INSERT INTO ebpls_transaction_requirements (trans_id,permit_id,permit_type,requirement_code,status,ts_create, last_updated_by )";
		$sql .= " SELECT $trans_id,$permit_id,'$permit_type',requirement_code,'PENDING',now(),'$creator' FROM ebpls_permit_app_requirements WHERE permit_type = '$permit_type' and par_trans_type = '$trans_type'";

		ebpls_db_funcs_debug ( "create_permit_requirements : $sql" );

		$result = mysql_query( $sql, $dbLink);

		if ( $result ) {

			return mysql_affected_rows($dbLink);

		} else {

			set_db_error($dbLink);
			return -1;

		}

	} else {

		set_db_error(NULL, "Invalid param $permit_req_type on function create_permit_requirements ( $dbLink, $trans_id, $permit_id, $permit_type, $creator, $permit_req_type )." );
		return -1;

	}

}


/**
 *
 *
 *
 **/
function add_fee_requirement( $dbLink, $trans_id, $permit_id, $permit_type, $creator, $tax_fee_code, $permit_req_type = 'APP' ) {

	if ( $permit_req_type == "FEE" ) {

		$sql = "INSERT INTO ebpls_transaction_fees ( trans_id, permit_id,permit_type, tax_fee_code, tax_fee_desc, tax_account_code, tax_amount, tax_percentage, tax_total_amount_due, ts_create, last_updated_by )";
		$sql .= " SELECT $trans_id, $permit_id, '$permit_type', b.tax_fee_code, b.tax_fee_desc,b.tax_account_code, b.tax_amount, b.tax_percentage, b.tax_amount, now(), '$creator' " ;
		$sql .= " FROM ebpls_permit_requirements AS a INNER JOIN ebpls_tax_fee_table AS b on a.tax_fee_code = b.tax_fee_code WHERE a.permit_type = '$permit_type' AND a.pr_type = '$permit_req_type' AND b.tax_fee_code = '$tax_fee_code'";

	} elseif ( $permit_req_type == "TAX" ) {

		$sql = "INSERT INTO ebpls_transaction_fees ( trans_id, permit_id,permit_type, tax_fee_code, tax_fee_desc, tax_account_code, tax_amount, tax_percentage, tax_total_amount_due, ts_create, last_updated_by )";
		$sql .= " SELECT $trans_id, $permit_id, '$permit_type', b.tax_fee_code, b.tax_fee_desc,b.tax_account_code, b.tax_amount, b.tax_percentage, b.tax_amount, now(), '$creator' " ;
		$sql .= " FROM ebpls_permit_requirements AS a INNER JOIN ebpls_tax_fee_table AS b on a.tax_fee_code = b.tax_fee_code WHERE a.permit_type = '$permit_type' AND a.pr_type = '$permit_req_type' AND b.tax_fee_code = '$tax_fee_code'";

	} else if ( $permit_req_type == "APP" ) {

		$sql = "INSERT INTO ebpls_transaction_requirements (trans_id,permit_id,permit_type,requirement_code,status,ts_create, last_updated_by )";
		$sql .= " SELECT $trans_id,$permit_id,'$permit_type',requirement_code,'PENDING',now(),'$creator' FROM ebpls_permit_requirements WHERE permit_type = '$permit_type' AND pr_type = '$permit_req_type'";

	} else {

		set_db_error(NULL, "Invalid param $permit_req_type on function create_permit_requirements ( $dbLink, $trans_id, $permit_id, $permit_type, $creator, $permit_req_type )." );
		return -1;

	}

	ebpls_db_funcs_debug ( "create_permit_requirements : $sql" );

	$result = mysql_query( $sql, $dbLink);

	if ( $result ) {

		return mysql_affected_rows($dbLink);

	} else {

		set_db_error($dbLink);
		return -1;

	}

}

function set_application_requirement_status( $dbLink, $trans_id, $req_id, $status, $creator ) {


	if ( $status != REQUIREMENT_STATUS_PENDING && $status != REQUIREMENT_STATUS_SUBMITTED ) {

		set_db_error( null, "Invalid status value $status.");
		return -1;

	}

	$sql = "UPDATE ebpls_transaction_requirements SET status = '$status' WHERE trans_id = $trans_id AND req_id = $req_id";

	ebpls_db_funcs_debug ( "submit_application_requirement : $sql" );

	$result = mysql_query( $sql, $dbLink);

	if ( $result ) {

		return mysql_affected_rows($dbLink);

	} else {

		set_db_error($dbLink);
		return -1;

	}

}


function get_application_requirement_status_count( $dbLink, $trans_id, $status ) {


	if ( $status != REQUIREMENT_STATUS_PENDING && $status != REQUIREMENT_STATUS_SUBMITTED ) {

		set_db_error( null, "Invalid status value $status.");
		return -1;

	}

	$sql = "SELECT count(*) ebpls_transaction_requirements SET status = '$status' WHERE trans_id = $trans_id";

	ebpls_db_funcs_debug ( "submit_application_requirement : $sql" );

	$result = mysql_query( $sql, $dbLink);

	if ( $result ) {

		if( $row = mysql_affected_rows($result) ) {

			return $row[0];

		}

		return -1;

	} else {

		set_db_error($dbLink);
		return -1;

	}

}

function get_application_requirement_list( $dbLink, $trans_id, $status ) {


	if ( $status != NULL && $status != REQUIREMENT_STATUS_PENDING && $status != REQUIREMENT_STATUS_SUBMITTED ) {

		set_db_error( null, "Invalid status value $status.");
		return -1;

	}

	if ( $status == null ) {

		$sql = "SELECT a.*, b.business_requirement_desc FROM ebpls_transaction_requirements as a left join ebpls_business_requirement as b on a.requirement_code = b.business_requirement_code WHERE a.trans_id = $trans_id";

	} else {

		$sql = "SELECT a.*, b.business_requirement_desc FROM ebpls_transaction_requirements as a left join ebpls_business_requirement as b on a.requirement_code = b.business_requirement_code WHERE a.status = '$status' AND a.trans_id = $trans_id";

	}

	//ebpls_db_funcs_debug ( "get_application_requirement_list : $sql" );

	$result = mysql_query( $sql, $dbLink);

	if ( $result ) {

		while ( $row = mysql_fetch_array($result) ) {

			$records[] = $row;

		}

		return $records;

	} else {

		set_db_error($dbLink);
		return -1;

	}

}


/****************************************************************************************************************
 *  Enterprise Class util db funcs
 *
 *
 ****************************************************************************************************************/
function get_bus_enterprise_nature_list( $dbLink, $business_id ) {

	$sqlSelect = "SELECT b.*, a.business_nature_desc FROM ebpls_business_nature as a inner join ebpls_business_enterprise_nature as b on a.business_nature_code = b.business_nature_code AND b.business_id = $business_id";

	ebpls_db_funcs_debug ( "get_bus_enterprise_nature_list : $sqlSelect" );

	$result = mysql_query( $sqlSelect, $dbLink);

	if ( $result ) {

		while( $row = mysql_fetch_array($result) ) {

			$records[] = $row;

		}

		return $records;

	} else {

		set_db_error($dbLink);
		return -1;

	}

}

function create_bus_enterprise_nature_fee( $dbLink, $trans_id, $business_id, $owner_id, $permit_id, $permit_type, $action, $creator, $nature_code = NULL ) {

	// removed bus nature code from tax_fee table instead transfer nature code to business nature code table
	if ( is_array($nature_code) ) {

		$nature_code_lst = "'" . join("','",$nature_code)  . "'";
		$sql = " SELECT a.business_nature_code, c.tax_fee_code, c.tax_fee_desc, c.tax_account_code, c.tax_formula ";
		$sql .= " FROM ebpls_business_nature AS a ";
		$sql .= " INNER JOIN ebpls_tax_fee_table as b ON a.tax_fee_code = b.tax_fee_code WHERE a.business_nature_code IN ('$nature_code_lst')";
		
	} else {
		
		$sql = " SELECT a.business_nature_code, c.tax_fee_code, c.tax_fee_desc, c.tax_account_code, c.tax_formula ";
		$sql .= " FROM ebpls_business_nature AS a ";
		$sql .= " INNER JOIN ebpls_tax_fee_table as b ON a.tax_fee_code = b.tax_fee_code WHERE a.business_nature_code = '$nature_code'";

	}

	ebpls_db_funcs_debug ( "create_bus_enterprise_nature_fee : $sql" );

	$result = mysql_query( $sql, $dbLink);

	if ( $result ) {

		$i = 0;
		while( $row = mysql_fetch_array( $result ) ) {

			$clsTaxFee = new EBPLTaxFeeSysRef( $dbLink, false );
			$res_tax = $clsTaxFee->select( $row["tax_fee_code"] );

			if ( is_array($res_tax) ) {

				$clsNature = new EBPLSTransactionBusinessNature( $dbLink, true );
				$clsNature->setData(TRANS_BUSNATURE_TRANS_ID, $trans_id );
				$clsNature->setData(TRANS_BUSNATURE_BUSINESS_ID, $business_id );
				$clsNature->setData(TRANS_BUSNATURE_OWNER_ID, $owner_id );
				$clsNature->setData(TRANS_BUSNATURE_CAPITAL_INVESTMENT, "0.0" );
				$clsNature->setData(TRANS_BUSNATURE_LAST_GROSS, "0.0" );
				$clsNature->setData(TRANS_BUSNATURE_BUSINESS_NATURE_CODE, $row["business_nature_code"] );

				if ( $clsNature->add() < 0 ) {

					ebpls_db_funcs_debug( "create_bus_enterprise_nature_fee : error on creation of business nature record" );
					return -5;

				}

				$clsTaxFormula = $res_tax["result"][0]->getData(EBPLS_TAX_FORMULA);

				$sql = "INSERT INTO ebpls_transaction_payables ( trans_id, permit_id,permit_type, tax_fee_type, tax_fee_code, tax_business_nature_code, tax_fee_desc, tax_account_code, tax_total_amount_due, ts_create, last_updated_by )";
				$sql .= " values( $trans_id, $permit_id, '$permit_type', 'BUSTAX','" . $row["tax_fee_code"] . "','" . $row["business_nature_code"] . "','" . $row["tax_fee_desc"] . "','" . $row["tax_account_code"] . "', 0, now(), '$creator')";

				ebpls_db_funcs_debug ( "create_bus_enterprise_nature_fee tax/fee found : $sql" );

				$rs = mysql_query( $sql, $dbLink );

				// add nature to business enterprise nature table
				

				if ( !( $rs ) ) {

					set_db_error( $dbLink );
					return -1;

				}

				$i++;

			} else {

				ebpls_db_funcs_debug ( "create_bus_enterprise_nature_fee tax/fee code not found : " . $row["tax_fee_code"] );
				set_db_error( NULL, "create_bus_enterprise_nature_fee tax/fee code not found : " . $row["tax_fee_code"] );
				return -3;

			}

		}

		return $i;

	} else {

		set_db_error($dbLink);
		return -2;


	}

}

?>