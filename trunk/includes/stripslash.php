<?php

if ($slash=='add') {

$owner_first_name=addslashes($owner_first_name);
$owner_middle_name=addslashes($owner_middle_name);
$owner_last_name=addslashes($owner_last_name);
$owner_street=addslashes($owner_street);
$owner_phone_no=addslashes($owner_phone_no);
$owner_gsm_no=addslashes($owner_gsm_no);
$owner_email_address=addslashes($owner_email_address);

} 
if ($slash=='update') {

$owner_first_name=stripslashes($owner_first_name);
$owner_middle_name=stripslashes($owner_middle_name);
$owner_last_name=stripslashes($owner_last_name);
$owner_street=stripslashes($owner_street);
$owner_phone_no=stripslashes($owner_phone_no);
$owner_gsm_no=stripslashes($owner_gsm_no);
$owner_email_address=stripslashes($owner_email_address);

} 
if ($slash=='add') {

$mmodel=addslashes($mmodel);
$mnum=addslashes($mnum);
$cnum=addslashes($cnum);
$pnum=addslashes($pnum);
$bnum=addslashes($bnum);
$route=addslashes($route);
$ltype=addslashes($ltype);
$bcolor=addslashes($bcolor);
$ltoreg=addslashes($ltoreg);
$cro=addslashes($cro);

} 

if ($slash=='update') {

$mmodel=stripslashes($mmodel);
$mnum=stripslashes($mnum);
$cnum=stripslashes($cnum);
$pnum=stripslashes($pnum);
$bnum=stripslashes($bnum);
$route=stripslashes($route);
$ltype=stripslashes($ltype);
$bcolor=stripslashes($bcolor);
$ltoreg=stripslashes($ltoreg);
$cro=stripslashes($cro);

} 
if ($slash=='add') {

$pos_app=addslashes($pos_app);
$employer_name=addslashes($employer_name); 
$trade_name=addslashes($trade_name); 
$lot_no=addslashes($lot_no);
$street=addslashes($street);

$merchandise=addslashes($merchandise);
$peddler_bus=addslashes($peddler_bus);

} 
if ($slash=='update') {

$pos_app=stripslashes($pos_app);
$employer_name=stripslashes($employer_name); 
$trade_name=stripslashes($trade_name); 
$lot_no=stripslashes($lot_no);
$street=stripslashes($street);

$merchandise=stripslashes($merchandise);
$peddler_bus=stripslashes($peddler_bus);

} 
if ($slash=='add') {

$business_name=addslashes($business_name); 
$business_branch=addslashes($business_branch); 
$business_lot_no=addslashes($business_lot_no);
$business_street=addslashes($business_street);
$business_contact_no=addslashes($business_contact_no);
$business_fax_no =addslashes($business_fax_no);
$business_email_address=addslashes($business_email_address);
$business_location_desc=addslashes($business_location_desc);    
$business_building_name=addslashes($business_building_name);
$business_phone_no=addslashes($business_phone_no);             
$business_dot_acr_no=addslashes($business_dot_acr_no);
$business_sec_reg_no=addslashes($business_sec_reg_no);
$business_tin_reg_no=addslashes($business_tin_reg_no);
$business_dti_reg_no=addslashes($business_dti_reg_no);
$business_main_offc_name=addslashes($business_main_offc_name);
$business_main_offc_lot_no=addslashes($business_main_offc_lot_no);          
$business_main_offc_street_no=addslashes($business_main_offc_street_no);
$business_main_offc_tin_no=addslashes($business_main_offc_tin_no);
$business_no_employees=addslashes($business_no_employees);
$business_no_del_vehicles=addslashes($business_no_del_vehicles);
$business_nso_assigned_no=addslashes($business_nso_assigned_no);
$business_remarks=addslashes($business_remarks);
                
} 
if ($slash=='update') {

$business_name=stripslashes(stripslashes($business_name)); 
$business_branch=stripslashes($business_branch); 
$business_lot_no=stripslashes($business_lot_no);
$business_street=stripslashes($business_street);
$business_contact_no=stripslashes($business_contact_no);
$business_fax_no =stripslashes($business_fax_no);
$business_email_address=stripslashes($business_email_address);
$business_location_desc=stripslashes($business_location_desc);    
$business_building_name=stripslashes($business_building_name);
$business_phone_no=stripslashes($business_phone_no);             
$business_dot_acr_no=stripslashes($business_dot_acr_no);
$business_sec_reg_no=stripslashes($business_sec_reg_no);
$business_tin_reg_no=stripslashes($business_tin_reg_no);
$business_dti_reg_no=stripslashes($business_dti_reg_no);
$business_main_offc_name=stripslashes($business_main_offc_name);
$business_main_offc_lot_no=stripslashes($business_main_offc_lot_no);          
$business_main_offc_street_no=stripslashes($business_main_offc_street_no);
$business_main_offc_tin_no=stripslashes($business_main_offc_tin_no);
$business_no_employees=stripslashes($business_no_employees);
$business_no_del_vehicles=stripslashes($business_no_del_vehicles);
$business_nso_assigned_no=stripslashes($business_nso_assigned_no);
$business_remarks=stripslashes($business_remarks);
                
}

$slash='';



