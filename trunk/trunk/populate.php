<?php

include "includes/variables.php";
include "lib/multidbconnection.php";
//echo "$dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname";
$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);

//--- get connection from DB
//$dbLink = get_db_connection();


$cnt =0;

while ($cnt<5000) {
$cnt++;
	if ($cnt<=1000) {
		$fname = "Von Dioved $cnt";
		$mname = "Sorreda $cnt";
		$lname = "Asuque $cnt";
		$bus_store = "Von Store $cnt";
		$merc_sold = "Guns $cnt";
		$merc_name = "Guns on the Go $cnt";
	} elseif ($cnt<=2000) {
		$fname = "Roberto $cnt";
		$mname = "Ador $cnt";
		$lname = "Domingo $cnt";
		$bus_store = "Domeng Store $cnt";
		$merc_sold = "Chicks $cnt";
		$merc_name = "Daguls Whore on the Go $cnt";
	} elseif ($cnt<=3000) {
		$fname = "Robert $cnt";
		$mname = "Magumcia $cnt";
		$lname = "Verzosa $cnt";
		$bus_store = "Excon D Urborg Store $cnt";
		$merc_sold = "Shabu $cnt";
		$merc_name = "Score ka muna bro $cnt";
	} elseif ($cnt<=4000) {
		$fname = "Jim $cnt";
		$mname = "WooHoo $cnt";
		$lname = "Samonte $cnt";
		$bus_store = "Push the Button Store $cnt";
		$merc_sold = "Sarili ko $cnt";
		$merc_name = "Im on the Go $cnt";
	} elseif ($cnt<=5000) {
		$fname = "Elli $cnt";
		$mname = "Tuazon $cnt";
		$lname = "Santos $cnt";
		$bus_store = "Isusumbong Kita Kay Tulfo Store $cnt";
		$merc_sold = "Mandays $cnt";
		$merc_name = "My mandays on the go $cnt";
	}
	
$insertquery = mysql_query("INSERT INTO `ebpls_owner` (`owner_id`, `owner_first_name`, `owner_middle_name`, `owner_last_name`, `owner_house_no`, `owner_street`, `owner_barangay_code`, `owner_zone_code`, `owner_district_code`, `owner_city_code`, `owner_province_code`, `owner_zip_code`, `owner_citizenship`, `owner_civil_status`, `owner_gender`, `owner_tin_no`, `owner_icr_no`, `owner_phone_no`, `owner_gsm_no`, `owner_email_address`, `owner_others`, `owner_birth_date`, `owner_reg_date`, `owner_lastupdated`, `owner_lastupdated_by`, `edit_by`, `edit_locked`) VALUES ('', '$fname', '$mname', '$lname', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', 'M', '1', '1', '1', '1', '1', '1', '2006-06-22 09:27:00', '2006-06-22 09:27:00', '2006-06-22 09:27:00', '1', '', '')") or die(mysql_error());
$owner_id = mysql_insert_id();
//business

$insertquery = mysql_query("INSERT INTO `ebpls_business_enterprise` (`business_id`, `owner_id`, `business_name`, `business_branch`, `business_permit_trans_type`, `business_lot_no`, `business_street`, `business_barangay_code`, `business_zone_code`, `business_barangay_name`, `business_district_code`, `business_city_code`, `business_province_code`, `business_zip_code`, `business_contact_no`, `business_fax_no`, `business_email_address`, `business_url`, `business_location_desc`, `business_building_name`, `business_phone_no`, `business_category_code`, `business_dot_acr_no`, `business_sec_reg_no`, `business_tin_reg_no`, `business_dti_reg_no`, `business_dti_reg_date`, `business_date_established`, `business_start_date`, `business_occupancy_code`, `business_offc_code`, `business_capital_investment`, `employee_male`, `business_no_del_vehicles`, `business_payment_mode`, `business_exemption_code`, `business_type_code`, `business_nso_assigned_no`, `business_nso_estab_id`, `business_industry_sector_code`, `business_remarks`, `business_status_code`, `business_status_remarks`, `business_application_status`, `business_application_status_rem`, `business_last_yrs_cap_invest`, `business_last_yrs_no_employees`, `business_last_yrs_no_employees_male`, `business_last_yrs_no_employees_female`, `business_last_yrs_dec_gross_sales`, `business_retirement_date`, `business_retirement_reason`, `business_application_date`, `business_validity_period`, `business_req_code`, `business_nature_code`, `business_create_ts`, `business_update_by`, `business_update_ts`, `comment`, `business_scale`, `retire`, `employee_female`, `blacklist`, `biztype`, `subsi`, `pcname`, `pcaddress`, `regname`, `paidemp`, `ecoorg`, `ecoarea`, `business_plate`, `branch_id`, `edit_by`, `edit_locked`) VALUES ('', '$owner_id', '$bus_store', '1', '1', '1', '1', '1', '1', '$owner_id', '1', '1', '1', '1', '1', NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', '2006-06-22 09:28:50', '2006-06-22 09:28:50', '2006-06-22 09:28:50', '', '', '', NULL, '', '', NULL, '', '', '', '', NULL, '', NULL, '', NULL, NULL, '', '', '', '', '2006-06-22 09:28:50', NULL, '2006-06-22 09:28:50', '2006-06-22 09:28:50', '', '', '2006-06-22 09:28:50', '', '2006-06-22 09:28:50', '', NULL, 0, NULL, '', '', '', '', '', '', '', '', '', '', '', '', '')") or die(mysql_error());

$business_id =mysql_insert_id();

$insertquery = mysql_query("INSERT INTO `ebpls_business_enterprise_permit` (`business_permit_id`, `business_permit_code`, `business_id`, `owner_id`, `retirement_code`, `retirement_date`, `retirement_date_processed`, `for_year`, `application_date`, `paid`, `released`, `input_by`, `transaction`, `steps`, `pin`, `active`, `pmode`) VALUES ('', NULL, '$business_id', '$owner_id', NULL, '2006-06-22 09:30:04', '2006-06-22 09:30:04', '2006', '2006-06-22 09:30:04', NULL, NULL, NULL, 'New', NULL, '1111', '1', 'QUARTERLY')") or die(mysql_error());



//motorized

$insertquery = mysql_query("INSERT INTO `ebpls_motorized_operator_permit` (`motorized_operator_permit_id`, `motorized_permit_code`, `owner_id`, `motorized_operator_permit_application_date`, `motorized_no_of_units`, `motorized_motor_model`, `motorized_motor_no`, `motorized_chassis_no`, `motorized_plate_no`, `motorized_retirement_date`, `motorized_retirement_date_processed`, `requirement_code`, `for_year`, `paid`, `released`, `transaction`, `steps`, `pin`, `active`) VALUES ('', '', '$owner_id', '2006-06-22 09:32:36', '', '', '', '', '', '2006-06-22 09:32:36', '2006-06-22 09:32:36', '', '', NULL, NULL, 'New', NULL, '1111', '1')") or die(mysql_error());


//franchise
$insertquery = mysql_query("INSERT INTO `ebpls_franchise_permit` (`franchise_permit_id`, `franchise_permit_code`, `owner_id`, `retirement_code`, `retirement_date`, `retirement_date_processed`, `requirement_code`, `for_year`, `application_date`, `paid`, `released`, `transaction`, `steps`, `pin`, `active`) VALUES ('', '', '$owner_id', NULL, '2006-06-22 09:34:37', '2006-06-22 09:34:37', '', '', '', NULL, NULL, 'New', NULL, '111', '1')") or die(mysql_error());

//fish


$insertquery = mysql_query("INSERT INTO `ebpls_fishery_permit` (`ebpls_fishery_id`, `ebpls_fishery_permit_code`, `owner_id`, `ebpls_fishery_businessname`, `ebpls_fishery_permit_application_date`, `ebpls_fishery_local_name_fishing_gear`, `ebpls_fishery_in_english`, `ebpls_fishery_no_of_units`, `ebpls_fishery_assess_value_fishing_gear`, `ebpls_fishery_fishing_gear_size`, `ebpls_fishery_area_size`, `ebpls_fishery_no_of_crew`, `ebpls_fishery_motorized`, `ebpls_fishery_registered`, `ebpls_fishery_boat_name`, `ebpls_fishery_registration_no`, `ebpls_fishery_ave_fish_catch_present`, `ebpls_fishery_ave_fish_catch_2yrs_ago`, `ebpls_fishery_location`, `ebpls_fishery_rc_no`, `ebpls_fishery_rc_issued_at`, `ebpls_fishery_rc_issued_on`, `transaction`, `for_year`, `paid`, `released`, `steps`, `pin`, `active`) VALUES ('', '', '$owner_id', '', '2006-06-22 09:35:16', '', '', '', '', '', '', '', '', '', '', '', '', '', NULL, NULL, NULL, '2006-06-22 09:35:16', 'New', NULL, NULL, NULL, NULL, '111', '1')") or die(mysql_error());

//ocu
$insertquery = mysql_query("INSERT INTO `ebpls_occupational_permit` (`occ_permit_id`, `occ_permit_code`, `owner_id`, `occ_permit_application_date`, `occ_position_applied`, `occ_employer`, `occ_employer_trade_name`, `occ_employer_lot_no`, `occ_employer_street`, `occ_employer_barangay_code`, `occ_employer_zone_code`, `occ_employer_barangay_name`, `occ_employer_district_code`, `occ_employer_city_code`, `occ_employer_province_code`, `occ_employer_zip_code`, `for_year`, `paid`, `released`, `transaction`, `steps`, `pin`, `active`, `business_id`) VALUES ('', '', '$owner_id', '2006-06-22 09:36:07', '', '', '', '', '', '', '', '', '', '', '', '', '', NULL, NULL, 'New', NULL, NULL, '1', '$business_id')") or die(mysql_error());

//peddler
$insertquery = mysql_query("INSERT INTO `ebpls_peddlers_permit` (`peddlers_permit_id`, `owner_id`, `merchandise_sold`, `peddlers_business_name`, `retirement_code`, `retirement_date`, `retirement_date_processed`, `for_year`, `peddlers_permit_code`, `application_date`, `paid`, `transaction`, `released`, `steps`, `pin`, `active`) VALUES ('', '$owner_id', '$merc_sold', '$merc_name', NULL, '2006-06-22 09:36:58', '2006-06-22 09:36:58', '', '', '', NULL, 'New', NULL, NULL, NULL, '1')") or die(mysql_error());
echo $cnt;
}
