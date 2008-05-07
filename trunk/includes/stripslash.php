<?php
/* Modification History:
2008.05.06 Check if variable exists to reduce errors in phperror.log
*/
$slash = isset($slash) ? $slash : ''; 
if ($slash=='add') {

$owner_first_name = isset($owner_first_name) ? addslashes($owner_first_name) : ' ';
$owner_middle_name = isset($owner_middle_name) ? addslashes($owner_middle_name) : ' ';
$owner_last_name = isset($owner_last_name) ? addslashes($owner_last_name) : ' ';
$owner_street = isset($owner_street) ? addslashes($owner_street) : ' ';
$owner_phone_no = isset($owner_phone_no) ? addslashes($owner_phone_no) : ' ';
$owner_gsm_no = isset($owner_gsm_no) ? addslashes($owner_gsm_no) : ' ';
$owner_email_address = isset($owner_email_address) ? addslashes($owner_email_address) : ' ';

} 
if ($slash=='update') {
$owner_first_name = isset($owner_first_name) ? stripslashes($owner_first_name) : ' ';
$owner_middle_name = isset($owner_middle_name) ? stripslashes($owner_middle_name) : ' ';
$owner_last_name = isset($owner_last_name) ? stripslashes($owner_last_name) : ' ';
$owner_street = isset($owner_street) ? stripslashes($owner_street) : ' ';
$owner_phone_no = isset($owner_phone_no) ? stripslashes($owner_phone_no) : ' ';
$owner_gsm_no = isset($owner_gsm_no) ? stripslashes($owner_gsm_no) : ' ';
$owner_email_address = isset($owner_email_address) ? stripslashes($owner_email_address) : ' ';
} 
if ($slash=='add') {
$mmodel = isset($mmodel) ? addslashes($mmodel) : ' ';
$mnum = isset($mnum) ? addslashes($mnum) : ' ';
$cnum = isset($cnum) ? addslashes($cnum) : ' ';
$pnum = isset($pnum) ? addslashes($pnum) : ' ';
$bnum = isset($bnum) ? addslashes($bnum) : ' ';
$route = isset($route) ? addslashes($route) : ' ';
$ltype = isset($ltype) ? addslashes($ltype) : ' ';
$bcolor = isset($bcolor) ? addslashes($bcolor) : ' ';
$ltoreg = isset($ltoreg) ? addslashes($ltoreg) : ' ';
$cro = isset($cro) ? addslashes($cro) : ' ';
} 

if ($slash=='update') {

$mmodel = isset($mmodel) ? stripslashes($mmodel) : ' ';
$mnum = isset($mnum) ? stripslashes($mnum) : ' ';
$cnum = isset($cnum) ? stripslashes($cnum) : ' ';
$pnum = isset($pnum) ? stripslashes($pnum) : ' ';
$bnum = isset($bnum) ? stripslashes($bnum) : ' ';
$route = isset($route) ? stripslashes($route) : ' ';
$ltype = isset($ltype) ? stripslashes($ltype) : ' ';
$bcolor = isset($bcolor) ? stripslashes($bcolor) : ' ';
$ltoreg = isset($ltoreg) ? stripslashes($ltoreg) : ' ';
$cro = isset($cro) ? stripslashes($cro) : ' ';

} 
if ($slash=='add') {

$pos_app = isset($pos_app) ? addslashes($pos_app) : ' ';
$employer_name = isset($employer_name) ? addslashes($employer_name) : ' '; 
$trade_name = isset($trade_name) ? addslashes($trade_name) : ' '; 
$lot_no = isset($lot_no) ? addslashes($lot_no) : ' ';
$street = isset($street) ? addslashes($street) : ' ';
$merchandise = isset($merchandise) ? addslashes($merchandise) : ' ';
$peddler_bus = isset($peddler_bus) ? addslashes($peddler_bus) : ' ';

} 
if ($slash=='update') {

$pos_app = isset($pos_app) ? stripslashes($pos_app) : ' ';
$employer_name = isset($employer_name) ? stripslashes($employer_name) : ' '; 
$trade_name = isset($trade_name) ? stripslashes($trade_name) : ' '; 
$lot_no = isset($lot_no) ? stripslashes($lot_no) : ' ';
$street = isset($street) ? stripslashes($street) : ' ';
$merchandise = isset($merchandise) ? stripslashes($merchandise) : ' ';
$peddler_bus = isset($peddler_bus) ? stripslashes($peddler_bus) : ' ';

} 
if ($slash=='add') {

$business_name = isset($business_name) ? addslashes($business_name) : ' '; 
$business_branch = isset($business_branch) ? addslashes($business_branch) : ' '; 
$business_lot_no = isset($business_lot_no) ? addslashes($business_lot_no) : ' ';
$business_street = isset($business_street) ? addslashes($business_street) : ' ';
$business_contact_no = isset($business_contact_no) ? addslashes($business_contact_no) : ' ';
$business_fax_no  = isset($business_fax_no ) ? addslashes($business_fax_no) : ' ';
$business_email_address = isset($business_email_address) ? addslashes($business_email_address) : ' ';
$business_location_desc = isset($business_location_desc) ? addslashes($business_location_desc) : ' ';    
$business_building_name = isset($business_building_name) ? addslashes($business_building_name) : ' ';
$business_phone_no = isset($business_phone_no) ? addslashes($business_phone_no) : ' ';             
$business_dot_acr_no = isset($business_dot_acr_no) ? addslashes($business_dot_acr_no) : ' ';
$business_sec_reg_no = isset($business_sec_reg_no) ? addslashes($business_sec_reg_no) : ' ';
$business_tin_reg_no = isset($business_tin_reg_no) ? addslashes($business_tin_reg_no) : ' ';
$business_dti_reg_no = isset($business_dti_reg_no) ? addslashes($business_dti_reg_no) : ' ';
$business_main_offc_name = isset($business_main_offc_name) ? addslashes($business_main_offc_name) : ' ';
$business_main_offc_lot_no = isset($business_main_offc_lot_no) ? addslashes($business_main_offc_lot_no) : ' ';          
$business_main_offc_street_no = isset($business_main_offc_street_no) ? addslashes($business_main_offc_street_no) : ' ';
$business_main_offc_tin_no = isset($business_main_offc_tin_no) ? addslashes($business_main_offc_tin_no) : ' ';
$business_no_employees = isset($business_no_employees) ? addslashes($business_no_employees) : ' ';
$business_no_del_vehicles = isset($business_no_del_vehicles) ? addslashes($business_no_del_vehicles) : ' ';
$business_nso_assigned_no = isset($business_nso_assigned_no) ? addslashes($business_nso_assigned_no) : ' ';
$business_remarks = isset($business_remarks) ? addslashes($business_remarks) : ' ';
                
} 
if ($slash=='update') {

$business_name = isset($business_name) ? stripslashes(stripslashes($business_name)) : ' '; 
$business_branch = isset($business_branch) ? stripslashes($business_branch) : ' '; 
$business_lot_no = isset($business_lot_no) ? stripslashes($business_lot_no) : ' ';
$business_street = isset($business_street) ? stripslashes($business_street) : ' ';
$business_contact_no = isset($business_contact_no) ? stripslashes($business_contact_no) : ' ';
$business_fax_no  = isset($business_fax_no ) ? stripslashes($business_fax_no) : ' ';
$business_email_address = isset($business_email_address) ? stripslashes($business_email_address) : ' ';
$business_location_desc = isset($business_location_desc) ? stripslashes($business_location_desc) : ' ';    
$business_building_name = isset($business_building_name) ? stripslashes($business_building_name) : ' ';
$business_phone_no = isset($business_phone_no) ? stripslashes($business_phone_no) : ' ';             
$business_dot_acr_no = isset($business_dot_acr_no) ? stripslashes($business_dot_acr_no) : ' ';
$business_sec_reg_no = isset($business_sec_reg_no) ? stripslashes($business_sec_reg_no) : ' ';
$business_tin_reg_no = isset($business_tin_reg_no) ? stripslashes($business_tin_reg_no) : ' ';
$business_dti_reg_no = isset($business_dti_reg_no) ? stripslashes($business_dti_reg_no) : ' ';
$business_main_offc_name = isset($business_main_offc_name) ? stripslashes($business_main_offc_name) : ' ';
$business_main_offc_lot_no = isset($business_main_offc_lot_no) ? stripslashes($business_main_offc_lot_no) : ' ';          
$business_main_offc_street_no = isset($business_main_offc_street_no) ? stripslashes($business_main_offc_street_no) : ' ';
$business_main_offc_tin_no = isset($business_main_offc_tin_no) ? stripslashes($business_main_offc_tin_no) : ' ';
$business_no_employees = isset($business_no_employees) ? stripslashes($business_no_employees) : ' ';
$business_no_del_vehicles = isset($business_no_del_vehicles) ? stripslashes($business_no_del_vehicles) : ' ';
$business_nso_assigned_no = isset($business_nso_assigned_no) ? stripslashes($business_nso_assigned_no) : ' ';
$business_remarks = isset($business_remarks) ? stripslashes($business_remarks) : ' ';
                
}

$slash = isset($slash) ? '' : ' ';



