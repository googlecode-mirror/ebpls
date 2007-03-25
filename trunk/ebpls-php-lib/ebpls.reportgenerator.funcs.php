<?

function reports_get_barangays( $params )
{

	$clsSysRef = new EBPLSSysRef( $params, EBPLS_BARANGAY, $params["bDebug"] );

	$records = $clsSysRef->select();

	if ( is_array($records) && count($records["result"])>0 ) {

		$res = $records["result"];
		for ( $i = 0; $i < count(res); $i++ ) {

			$key = $res[$i]->getCode();
			$desc = $res[$i]->getDescription();
			$elems[$key] = $desc;

		}

		return $elems;

	}

	return NULL;

}

function reports_get_category( $params )
{

	$clsSysRef = new EBPLSSysRef( $params, EBPLS_BUSINESS_NATURE, $params["bDebug"] );

	$records = $clsSysRef->select();

	if ( is_array($records) && count($records["result"])>0 ) {

		$res = $records["result"];
		for ( $i = 0; $i < count($res); $i++ ) {

			$key = $res[$i]->getCode();
			$desc = $res[$i]->getDescription();
			$elems[$key] = $desc;

		}

		return $elems;

	}

	return NULL;

}

/****************************************************************
 *
 * Application Module Reports
 *
 ****************************************************************/


function get_bus_establshment_by_barangay( $dbLink, $post )
{

	$clsSysRef = new EBPLSSysRef( $dbLink, EBPLS_BARANGAY, $params["bDebug"] );

	$records = $clsSysRef->select( $post["barangay"] );

	if ( is_array($records) && count($records["result"])>0 ) {

		$res = $records["result"];
		$brgy_desc = $res[0]->getDescription();
	
	} else {
		
		$brgy_desc = $post["barangay"];
	
	}
	

	$sqlSelect = "SELECT b.business_permit_code, a.business_name, a.business_category_code, d.business_nature_code as business_nature, concat( e.owner_first_name, ' ', e.owner_last_name ) as business_owner, a.business_phone_no, d.capital_investment as business_capital_investment, c.barangay_desc FROM ebpls_business_enterprise as a left join ebpls_business_enterprise_permit as b on a.business_id = b.business_id left join ebpls_barangay as c on a.business_barangay_code = c.barangay_code left join ebpls_business_enterprise_nature as d on a.business_id = d.business_id left join ebpls_owner as e on a.owner_id = e.owner_id WHERE a.business_barangay_code = '" . $post["barangay"] . "' and '" . $post["start_date"] . "' <= b.application_date and b.application_date <= '" . $post["end_date"] . "' and b.business_permit_code != ''";

	//echo("SQL ($dbLink, $post) : $sqlSelect<BR>");

	$res = mysql_query( $sqlSelect, $dbLink );

	if ( $res ) {

		$records = NULL;
				
		while( $row = mysql_fetch_array($res) ) {

			$records[] = $row;

		}

		return $records;

	}

	return NULL;

}

function get_bus_establshment_by_category( $dbLink, $post )
{	
	
	$sqlSelect = "SELECT year(curdate()) as cur_year, b.business_permit_code, concat( a.business_lot_no, ' ', a.business_street, ' ', a.business_zone_code, ' ', a.business_city_code ) as business_address, a.business_phone_no, a.business_name, a.business_category_code, e.business_nature_code,  f.business_nature_desc as category, concat( d.owner_first_name, ' ', d.owner_last_name) as business_owner, a.business_phone_no, e.capital_investment, c.barangay_desc FROM ebpls_business_enterprise as a left join ebpls_business_enterprise_permit as b on a.business_id = b.business_id left join ebpls_barangay as c on a.business_barangay_code = c.barangay_code left join ebpls_owner as d on a.owner_id = d.owner_id left join ebpls_business_enterprise_nature as e on a.business_id = e.business_id left join ebpls_business_nature as f on e.business_nature_code = f.business_nature_code WHERE '" . $post["start_date"] . "' <= b.application_date and b.application_date <= '" . $post["end_date"] . "' and e.business_nature_code = '" . $post["nature_code"] . "' group by e.capital_investment";

	//echo "SQL $sqlSelect<BR>";

	$res = mysql_query( $sqlSelect, $dbLink );

	if ( $res ) {

		$records = NULL;				

		while( $row = mysql_fetch_array($res) ) {

			$records[] = $row;

		}

		return $records;

	}

	return NULL;

}


function get_bus_establshment_by_owner( $dbLink, $post )
{

	$sqlSelect = "SELECT year(curdate()) as year_value, b.owner_first_name, b.owner_middle_name, b.owner_last_name, concat( b.owner_house_no, ' ', b.owner_street, ' ', b.owner_barangay_code, ' ', b.owner_zone_code  ) as owner_address, b.owner_citizenship, b.owner_gender, b.owner_birth_date, a.business_name, d.business_nature_code, d.capital_investment, c.business_permit_code FROM ebpls_business_enterprise as a left join ebpls_owner as b on a.owner_id = b.owner_id left join ebpls_business_enterprise_permit as c on a.business_id = c.business_permit_id left join ebpls_business_enterprise_nature as d on a.business_id = d.business_id";

	//echo "SQL $sqlSelect<BR>";

	$res = mysql_query( $sqlSelect, $dbLink );

	if ( $res ) {

		$records = NULL;

		while( $row = mysql_fetch_array($res) ) {

			$records[] = $row;

		}

		return $records;

	}

	return NULL;

}

function get_bus_establshment_by_capital( $dbLink, $post )
{

	$sqlSelect = "SELECT year(curdate()) as cur_year, b.business_permit_code, concat( a.business_lot_no, ' ', a.business_street, ' ', a.business_zone_code, ' ', a.business_city_code ) as business_address, a.business_name, a.business_category_code, e.business_nature_code, concat( d.owner_first_name, ' ', d.owner_last_name) as business_owner, a.business_phone_no, e.capital_investment, c.barangay_desc FROM ebpls_business_enterprise as a left join ebpls_business_enterprise_permit as b on a.business_id = b.business_id left join ebpls_barangay as c on a.business_barangay_code = c.barangay_code left join ebpls_owner as d on a.owner_id = d.owner_id left join ebpls_business_enterprise_nature as e on a.business_id = e.business_id WHERE '" . $post["start_date"] . "' <= b.application_date and b.application_date <= '" . $post["end_date"] . "' group by e.capital_investment";

	//echo "SQL $sqlSelect<BR>";

	$res = mysql_query( $sqlSelect, $dbLink );

	if ( $res ) {

		$records = NULL;				

		while( $row = mysql_fetch_array($res) ) {

			$records[] = $row;

		}

		return $records;

	}

	return NULL;

}


/****************************************************************
 *
 * Assessment Module Reports
 *
 ****************************************************************/

function get_list_exempted_establishments_full( $dbLink, $post )
{

	$sqlSelect = "";

	echo "SQL $sqlSelect<BR>";

	$res = mysql_query( $sqlSelect, $dbLink );

	if ( $res ) {

		$records = NULL;				

		while( $row = mysql_fetch_array($res) ) {

			$records[] = $row;

		}

		return $records;

	}

	return NULL;

}


function get_list_establishments_without_permit( $dbLink, $post )
{

	$sqlSelect = "";

	echo "SQL $sqlSelect<BR>";

	$res = mysql_query( $sqlSelect, $dbLink );

	if ( $res ) {

		$records = NULL;				

		while( $row = mysql_fetch_array($res) ) {

			$records[] = $row;

		}

		return $records;

	}

	return NULL;

}

function get_list_exempted_establishments_partial( $dbLink, $post )
{

	$sqlSelect = "";

	echo "SQL $sqlSelect<BR>";

	$res = mysql_query( $sqlSelect, $dbLink );

	if ( $res ) {

		$records = NULL;				

		while( $row = mysql_fetch_array($res) ) {

			$records[] = $row;

		}

		return $records;

	}

	return NULL;

}

function get_list_establishments( $dbLink, $post )
{

	$sqlSelect = "";

	echo "SQL $sqlSelect<BR>";

	$res = mysql_query( $sqlSelect, $dbLink );

	if ( $res ) {

		$records = NULL;				

		while( $row = mysql_fetch_array($res) ) {

			$records[] = $row;

		}

		return $records;

	}

	return NULL;

}

function get_bus_establshment_materlist ( $dbLink, $post ) {
			
	$sqlSelect = "SELECT 'permit' as permit_no, a.business_name, concat( a.business_lot_no, ' ', a.business_street, ' ', a.business_barangay_code, ' ', a.business_zone_code ) as business_address, b.capital_investment, c.business_nature_desc, concat(d.owner_first_name, ' ' , d.owner_last_name ) as owner_name  FROM ebpls_business_enterprise as a inner join ebpls_business_enterprise_nature as b on a.business_id = b.business_id left join ebpls_business_nature as c on b.business_nature_code = c.business_nature_code left join ebpls_owner as d on a.owner_id = d.owner_id";

	echo "SQL $sqlSelect<BR>";

	$res = mysql_query( $sqlSelect, $dbLink );

	if ( $res ) {

		$records = NULL;				

		while( $row = mysql_fetch_array($res) ) {

			$records[] = $row;

		}

		return $records;

	}

	return NULL;
}

function get_list_buspermit_application( $dbLink, $post ) {
			
	$sqlSelect = "SELECT b.business_name, concat( b.business_lot_no, ' ', b.business_street, ' ', b.business_barangay_code, ' ', b.business_zone_code ) as business_address, concat( c.owner_first_name, ' ' , c.owner_last_name ) as owner_name, a.trans_application_date, a.trans_application_status from ebpls_transaction as a left join ebpls_business_enterprise as b on a.business_id = b.business_id left join ebpls_owner as c on a.owner_id = c.owner_id WHERE a.permit_type = 'BUS' and a.trans_status = 'APPLICATION'";

	echo "SQL $sqlSelect<BR>";

	$res = mysql_query( $sqlSelect, $dbLink );

	if ( $res ) {

		$records = NULL;				

		while( $row = mysql_fetch_array($res) ) {

			$records[] = $row;

		}

		return $records;

	}

	return NULL;
}

function get_list_fishery_application( $dbLink, $post ) {
			
	$sqlSelect = "SELECT b.fishery_business_name, concat( c.owner_first_name, ' ' , c.owner_last_name ) as owner_name, concat( c.owner_house_no, ' ', c.owner_street, ' ', c.owner_barangay_code, ' ', c.owner_zone_code ) as business_address, a.trans_application_date, a.trans_application_status from ebpls_transaction as a left join ebpls_fishery_operators as b on a.owner_id = b.owner_id left join ebpls_owner as c on a.owner_id = c.owner_id WHERE a.permit_type = 'FIS' and a.trans_status = 'APPLICATION'";

	echo "SQL $sqlSelect<BR>";

	$res = mysql_query( $sqlSelect, $dbLink );

	if ( $res ) {

		$records = NULL;				

		while( $row = mysql_fetch_array($res) ) {

			$records[] = $row;

		}

		return $records;

	}

	return NULL;
}

function get_list_occupational_application( $dbLink, $post ) {
			
	$sqlSelect = "SELECT concat( c.owner_first_name, ' ', c.owner_last_name) as owner_name, b.occ_position_applied, b.occ_permit_application_date, b.occ_employer, concat(b.occ_employer_lot_no, ' ', b.occ_employer_street, ' ', b.occ_employer_barangay_code, ' ', b.occ_employer_zone_code ) as employer_address from ebpls_transaction as a left join ebpls_occupational_permit as b on a.owner_id = b.owner_id left join ebpls_owner as c on a.owner_id = c.owner_id WHERE a.permit_type = 'OCC' and a.trans_status = 'APPLICATION'";

	echo "SQL $sqlSelect<BR>";

	$res = mysql_query( $sqlSelect, $dbLink );

	if ( $res ) {

		$records = NULL;				

		while( $row = mysql_fetch_array($res) ) {

			$records[] = $row;

		}

		return $records;

	}

	return NULL;
}


function get_list_motorized_application( $dbLink, $post ) {
			
	$sqlSelect = "SELECT concat( c.owner_first_name, ' ', c.owner_last_name) as owner_name, b.motorized_operator_permit_application_date, concat(c.owner_house_no, ' ', c.owner_street, ' ', c.owner_barangay_code, ' ', c.owner_zone_code) as owner_address, e.motorized_motor_model, e.motorized_motor_no, e.motorized_chassis_no, e.motorized_plate_no, e.motorized_body_no from ebpls_transaction as a left join ebpls_motorized_operator_permit as b on a.owner_id = b.owner_id left join ebpls_owner as c on a.owner_id = c.owner_id left join ebpls_motorized_operators as d on b.motorized_operator_id = d.motorized_operator_id left join ebpls_motorized_vehicles as e on b.motorized_operator_id = e.motorized_operator_id  WHERE a.permit_type = 'MOT' and a.trans_status = 'APPLICATION'";

	echo "SQL $sqlSelect<BR>";

	$res = mysql_query( $sqlSelect, $dbLink );

	if ( $res ) {

		$records = NULL;				

		while( $row = mysql_fetch_array($res) ) {

			$records[] = $row;

		}

		return $records;

	}

	return NULL;
}


function get_list_peddlers_application( $dbLink, $post ) {
			
	$sqlSelect = "SELECT concat( c.owner_first_name, ' ', c.owner_last_name) as owner_name, b.application_date, b.merchandise_sold, concat(c.owner_house_no, ' ',c.owner_street, ' ' , c.owner_barangay_code) as owner_address from ebpls_transaction as a left join ebpls_peddlers_permit as b on a.owner_id = b.owner_id left join ebpls_owner as c on a.owner_id = c.owner_id WHERE a.permit_type = 'PED' and a.trans_status = 'APPLICATION'";

	echo "SQL $sqlSelect<BR>";

	$res = mysql_query( $sqlSelect, $dbLink );

	if ( $res ) {

		$records = NULL;				

		while( $row = mysql_fetch_array($res) ) {

			$records[] = $row;

		}

		return $records;

	}

	return NULL;
}



?>
