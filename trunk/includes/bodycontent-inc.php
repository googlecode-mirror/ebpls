<?php
/*Modification History:
2008.04.25: Change constants to strings to remove PHP errors in log lines 99+  line 461??
*/
require_once("lib/ebpls.lib.php");
//require_once("lib/ebpls.utils.php");
                                                                                                               
//require_once("ebpls-php-lib/utils/ebpls.search.funcs.php");
                          
                                                                                     
//--- get connection from DB
//$dbLink = get_db_connection();
global $ThUserData;
require_once "includes/variables.php";
require_once "class/BusinessEstablishmentClass.php";
include_once "class/PermitClass.php";

 if ($permit_type=="Business" and $stat=='New') {
	if ($business_id<>'' and $owner_id<>'') {
                        $spermit = new NewPermit;
                        $spermit->GetBusinessPermit($owner_id, $business_id, $permittable);
//                      $spermit->outnumrow;
                        if ($spermit->outnumrow==0) {
                        $sValues = "$business_id, $owner_id,'$currdate[year]',
                                now(), '$usern', 'New', 0, 'For Assessment',
                                '$genpin', 1";
                        $ipermit = new NewPermit;
                        $ipermit->InsertBusinessPermit($sValues,$permittable);
                        }
	}
} elseif ($permit_type=="Business" and $stat=='New') {
        if ($business_id<>'' and $owner_id<>'') {
                        $spermit = new NewPermit;
                        $spermit->GetBusinessPermit($owner_id, $business_id, $permittable);
//                      $spermit->outnumrow;
                        if ($spermit->outnumrow==0) {
                        $sValues = "$business_id, $owner_id,'$currdate[year]',
                                now(), '$usern', 'ReNew', 0, 'For Assessment',
                                '$genpin', 1";
                        $ipermit = new NewPermit;
                        $ipermit->InsertBusinessPermit($sValues,$permittable);
			
                        }
        }
}

//include 'javascripts/javafuncs.js';
?>

<table border=0 width=100% cellspacing=0 cellpadding=0>
<script language='Javascript' src='includes/datepick/datetimepicker.js'></script>
<script language='Javascript' src='javascripts/javafuncs.js'></script>
<tr>
<td align=center>
<?php
if (isset($Search) && $Search=='SEARCH') {
require 'includes/variables.php';
$itemID_=$search_businesstype;
	  if ($search_businesstype==1221) {
                        require "includes/business_search.php";
                } elseif ($search_businesstype==4212 and $permit_type=='Business') {
                        require "includes/assessment_search.php";
                } elseif ($search_businesstype==5212 and $permit_type=='Business') {
                        require "includes/approve_search.php";
                } elseif ($search_businesstype==2212) {
                        require "includes/payment_search.php";
                } elseif ($search_businesstype==3212) {
                        require "includes/release_search.php";
                }
}


if (!empty($itxt_Search)){
?>

<table width=100%>

<?php
include'lib/phpFunctions-inc.php';
?>

<tr>
<td colspan=6><?php echo MODTITLESTYLE1;?> Search Result(s): <?php echo MODTITLESTYLE2; ?></td></td>
</tr>

<tr bgcolor="#EEEEEE">
<td width=5%>No.</td>
<td width=25% align=left>Name of Owner</td>
<td width=25% align=left>Name of Business</td>
<td width=15% align=center>Type</td>
<td width=10% align=center>Status</td>
<td width=15% align=center>Action</td>
</tr>
</table>
<?php
}
else {
?>

<?php

if (isset($sMode) && $sMode=='bus_nature') {
	include'bus_nature.php';
//	include'ebpls5502.php';
}
$selMode = isset($selMode)?$selMode:"";
if ($selMode=='FAQS') {
	include 'faq/faq.php';
	$permit_type='1';
}
if ($class_type=='Preference' and $selMode=="" and $permit_type=="") {
	include 'html/Ref.html';
	$permit_type='1';
}
$settings_type = isset($settings_type) ? $settings_type : '';
if ($busItem=="Settings" and $item_id=="Settings" and $settings_type=="") {
	include 'html/Sys.html';
	$permit_type='1';
}
elseif ($selMode=='ebpls_faq'){
        include'faq/faq_admin.php';
	$permit_type=1;
}
elseif ($selMode=='links'){
        include'links.php';
        $permit_type=1;
} 
elseif ($selMode=='ebpls_afiss'){
        include'af_iss.php';
        $permit_type=1;
}
elseif ($selMode=='ebpls_link'){
        include'link_admin.php';
        $permit_type=1;
}
elseif ($selMode=='ebpls_nbusiness'){
	include'ebpls5502.php';
}
elseif ($selMode=='ebpls_nRequirements'){
	include'eBPLS_requirements.php';
}
elseif ($selMode=='ebpls_nTFO'){
	include'eBPLS_tfo.php';
}
elseif ($selMode=='ebpls_npreferences'){
	include'ebpls6601.php';
}
elseif ($selMode=='ebpls_npenalty'){
	include'ebpls7701.php';
}
elseif ($selMode=='ebpls_nmotorpenalty'){
	include'eBPLS_motorized_penalty.php';
}
elseif ($selMode=='ebpls_nfishpenalty'){
	include'eBPLS_fishery_penalty.php';
}
elseif ($selMode=='ebpls_noccpenalty'){
	include'eBPLS_occupational_penalty.php';
}
elseif ($selMode=='ebpls_ntemplate'){
        include'ebpls8801.php';
}
elseif ($selMode=='ebpls_npsic'){
        include'ebpls9901.php';
}
elseif ($selMode=='ebpls_nsign'){
        include'eBPLS_signatories.php';
}
elseif ($selMode=='ebpls_nreportsign'){
        include'eBPLS_signtemp.php';
}
elseif ($selMode=='ebpls_notherfees'){
        include'ebpls1219.php';
}
elseif ($selMode=='ebpls_nchart'){
        include'chart.php';
}
elseif ($selMode=='ebpls_nboatfees'){
        include'ebpls7111.php';
}
elseif ($selMode=='ebpls_nfishcfees'){
        include'ebpls7112.php';
}
elseif ($selMode=='ebpls_nctc'){
        include'eBPLS_ctcsettings.php';
}
elseif ($selMode=='ebpls_npermitform'){
        include'eBPLS_permit_format.php';
}
elseif ($selMode=='ebpls_nlotpin'){
        include'lotpin.php';
}
elseif ($selMode=='ebpls_nannouncement'){
        include'eBPLS_announcement.php';
}
elseif ($selMode=='ebpls_ncitizenship'){
        include'eBPLS_citizenship.php';
}
elseif ($selMode=='ebpls_nenginetype'){
        include'eBPLS_engine_type.php';
}
elseif ($selMode=='ebpls_nfishact'){
        include'eBPLS_fish.php';
}
elseif ($selMode=='ebpls_neconomic_area'){
        include'eBPLS_Economic_Area.php';
}
elseif ($selMode=='ebpls_neconomic_org'){
	$nTitle = 'Economic Organization';
        $nTable = 'ebpls_economic_org';
        $nField0 = 'economic_org_id';
        $nField1 = 'economic_org_code';
        $nField2 = 'economic_org_desc';
        include'eBPLS_economic_organization.php';
}
elseif ($selMode=='ebpls_nbarangay'){
	$prefm = 'Barangay';
	$preft = 'ebpls_barangay';
	$prefc = 'barangay_code';
	$prefd = 'barangay_desc';
	$prefdc = 'district_code';
	$prefut = 'District';
	$prefu = 'ebpls_district';
    include 'eBPLS_barangay.php';
}
elseif ($selMode=='ebpls_nzone'){
        $prefm = 'Zone';
        $preft = 'ebpls_zone';
        $prefc = 'zone_code';
        $prefd = 'zone_desc';
        $prefdc = 'barangay_code';
        $prefut = 'Barangay';
		$prefu = 'ebpls_barangay';
        include'eBPLS_zone.php';
}
elseif ($selMode=='ebpls_nzip'){
        $prefm = 'Zip';
        $preft = 'ebpls_zip';
        $prefc = 'zip_code';
        $prefd = 'zip_desc';
        $prefdc = 'city_municipality_code';
        $prefut = 'LGU';
		$prefu = 'ebpls_city_municipality';
        include'eBPLS_zip.php';
}
elseif ($selMode=='ebpls_ndistrict'){
        $prefm = 'District';
        $preft = 'ebpls_district';
        $prefc = 'district_code';
        $prefd = 'district_desc';
        $prefdc = 'city_municipality_code';
        $prefut = 'LGU';
		$prefu = 'ebpls_city_municipality';
        include'eBPLS_district.php';
}
elseif ($selMode=='ebpls_nLGU'){
	$preft = 'ebpls_city_municipality';
        $prefc = 'city_municipality_code';
	$prefd = 'city_municipality_desc';	
	//$prefdc = 'province_desc';	
	$prefdc = 'province_code';	
	$prefm = 'LGU';
	$prefut = 'Province';
	$prefu = 'ebpls_province';
        include'eBPLS_lgu.php';
}
elseif ($selMode=='ebpls_nProvince'){
	$prefm = 'Province';
	$preft = 'ebpls_province';
    $prefc = 'province_code';
	$prefd = 'province_desc';	
    include'eBPLS_province.php';
}

elseif ($selMode=='ebpls_nownership'){
        include'ownership_type.php';
}

elseif ($selMode=='ebpls_noccupancy'){
       include'eBPLS_occupancy_type.php';
}

elseif ($selMode=='ebpls_nindustry'){
		include'eBPLS_industry_sector.php';
}

elseif ($selMode=='ebpls_nadmin'){
		include'includes/iAdmin-inc.php';
}

else {

if ($permit_type=='') {
?>
	<!--<img src='./images/ebpls_logo.jpg' height=100% width=100%>-->
<?php
}

?>
<?php
$itemID_ = isset($itemID_)?$itemID_:0;
if ($permit_type=='Business'){
		if ($itemID_== 1221){
				include'ebpls1221.php';		
		//	include'includes/form_bus_permit.php';
		} elseif ($itemID_== 6204) {
				include 'delayed_yr.php';
		} elseif ($itemID_== 5679) {
				include 'delayed_qtr.php';		
		} elseif ($itemID_ == '0104') {
				include 'predcomp.php';
		} elseif ($itemID_== 4212){
				include'ebpls4212.php';		
		} elseif ($itemID_== 5212){
				include'ebpls5212.php';		
		} elseif ($itemID_== 2212){
				include'ebpls2212.php';		
		} elseif ($itemID_== 3212){
				include'ebpls3212.php';		
                } elseif ($itemID_== 1222){
//                                include'ebpls1222.php';
				include "includes/form_mtop_owner.php";
                } elseif ($itemID_== 1223){
				include'ebpls1223.php';
                } elseif ($itemID_== 1224){
                                include'ebpls1224.php';
                } else {
				include'ebpls1221.php';					
		}
		
} elseif ($permit_type<>'Business') {

		if ($itemID_== 1221){
                                include'ebpls1221.php';
                //      include'includes/form_bus_permit.php';
                }
                elseif ($itemID_== 2212){
                                include'ebpls2212.php';
                }
                elseif ($itemID_== 3212){
                                include'ebpls3212.php';
                }
                elseif ($itemID_== 1222){
                               include "includes/form_mtop_owner.php";
				}
		elseif ($itemID_== 921){
                                include'ebpls921.php';
                }
                elseif ($itemID_==101){
                                include'ebpls101.php';
                }
		elseif ($itemID_==1001) {
				$owner='ebpls_owner';
				include'ebpls1001.php';
		}
		elseif ($itemID_==1002 || $itemID_=='ctcinsearch1') {
				include'ebpls1002.php';
		}
                elseif ($itemID_==1217){
                                include'ebpls1217.php';
                }
                elseif ($itemID_== 1223){
			        include'ebpls1223.php';
                }
		elseif ($itemID_==1218) {
				include'ebpls1218.php';
		}
                elseif ($itemID_==4221){
                                include'ebpls4221.php';
                }
                elseif ($itemID_=='ctc'){
				include'form_ctc_individual_application.php';
            	}
		elseif ($itemID_=='ctcinsearch') {
				include'ebpls1001.php';
                }
           	elseif ($itemID_==6){
				if (isset($v) and $v==1) {
	      ?>
		        <body onload='alert("Record updated successfully");'></body>
	      <?php
				}
                		include'ebpls006.php';
            	}
            	elseif ($itemID_==7){
                		include'ebpls007.php';
            	}
            	elseif ($itemID_==11){
                		include'ebpls011.php';
            	}
            	elseif ($itemID_==21){
                		include'ebpls021.php';
            	}
            	elseif ($itemID_==23){
                		include'ebpls023.php';
            	}
            	elseif ($itemID_<> 1221 and $itemID_<> 2212 and $itemID_<> 3212 and $itemID_<> 1222
            		and $itemID_<> 921 and $itemID_<>101 and $itemID_<>1217 and $itemID_<>4221
            		and $itemID_<>'ctc') {
            			include'ebpls1221.php';
        	}

} else {
?>

<table width=100%>
<tr>
<td valign=top width=70% align=left> &nbsp &nbsp &nbsp The <b><i>eBusiness Permit & Licensing System</i></b> ... description description description description description description description description </td>
<td valign=top width=20% align=center><img src="images/image.gif" border=0 width=90 height=75></td>
</tr>
</table>
<!--<div align="CENTER" class="thText">
<font face="ARIAL" size="3"><b>Welcome to eBPLS:</b></font> &nbsp;
<font face="ARIAL" size="4" color="#000066"><b>"<?php echo(strtoupper($ThUserData['username'])); ?>"</b></font> &nbsp;
<font face="ARIAL" size="3" color="#CC0000"><b><i>(<?php echo($thUserLevel[$ThUserData['level']][1]); ?>)</i></b></font>
<br><br>
<img src="images/image.gif" border=0 width="458" height="297"><br>
<br>
<font face="ARIAL" size="2" color="#CC0000"><b>* <i>Please don't forget to LOG OUT when you are done.</i></b></font>
</div>-->z

<?php

?>

<?php
}
?>

<?php
}
?>

<?php
}
'setCurrentActivityLog'
?>
</td>
</tr>
</table>
