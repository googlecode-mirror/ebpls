<?php
/*  Purpose: Business Maintenance

Maintenance History::
2008.04.14 Added comments and cleaned up display. RJC
*/
require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");
require_once("ebpls-php-lib/utils/ebpls.search.funcs.php");
//setUrlRedirect('index.php?part=999');
//--- get connection from DB
//$dbLink = get_db_connection();
global $ThUserData;
$debug 		= false;

require_once "includes/variables.php";
include_once "class/BusinessEstablishmentClass.php";
include_once "class/MainBranchClass.php";

$tdate = date('Y/m/d');
$business_name=addslashes($business_name);

if ($owner_id==0 and $permit_type<>"CTC") {
?>
	<body onload='alert("Cannot add business w/o owner"); parent.location="index.php?part=4&class_type=Permits&busItem=Business&itemID_=1221&permit_type=Business&mtopadd=ADD&stat=New&busItem=Business";'></body>
<?php
}

if ($subs=='on') {
	$subsi=1;
} else {
	$subsi=0;
}

if ($addbiz=='') {
	$addbiz='Save';
}

if ($business_id<>''){ //editing biz
	$result = new BusinessEstablishment;
	$result->GetBusinessByID($business_id);
	$result->FetchBusinessArray($result->outselect);
	$datarow=$result->outarray;
	if ($datarow[edit_locked]==1 and $datarow[edit_by]<>substr($usern,0,50)) {  //in use
?>
//2008.04.16	<body onload='alert("Cannot edit business, currently used by <?php echo $owner[edit_by]; ?>"); parent.location="index.php?part=4&itemID_=1221&class_type=Permits&permit_type=Business&busItem=Business&mtopsearch=SEARCH";'></body>
        <body onload='alert("Cannot edit business, currently used by <?php echo $datarow[edit_by]; ?>");
         parent.location="index.php?part=4&itemID_=1221&class_type=Permits&permit_type=Business&busItem=Business&mtopsearch=SEARCH";'></body>
        
<?php
	} else {  // lock it
		$bus = new BusinessEstablishment;
		if ($business_id<>'') {
		$strValues="edit_by='$usern',edit_locked=1";
                $strWhere = "business_id='$business_id'";
                $bus->UpdateBusiness($strValues,$strWhere);
		}
	}
} else { // creating biz
$result = new BusinessEstablishment;
$result->CountBusiness();
$bus = $result->outnumrow;
$bus = $bus+1;
$business_id=0;
}

if ($owner_id=='') { $butsave='submit'; }
else { $butsave='button'; }

if ($pro==1) {
	$SearchBranch = new BusinessEstablishment;
	if ($business_branch=='None') {
                                                                                                                             
        	$SearchBranch->VerifyBusinessNoBranch($business_name,$business_id);
	        $checkme = $SearchBranch->outnumrow;
	        if ($checkme>0) {
?>
		        <body onload='alert("Duplicate Business Name Found!");'></body>
<?php
		        $pro=12;
		        }
                                                                                                                             
        } else {

		$SearchBranch->VerifyBusiness($business_name,$business_branch,$business_id);
		$checkme = $SearchBranch->outnumrow;
		if ($checkme>0) {
?>
	<body onload='alert("Duplicate Business Name and Branch Found!");'></body>
<?php
			$pro=12;
		}
	}
}


if ($business_nso_assigned_no<>'') {
	if ($pro==1) {
		$SearchNSO = new BusinessEstablishment;
		$SearchNSO->VerifyNSONo($business_id,$business_nso_assigned_no);
		$checkme = $SearchNSO->outnumrow;
	        if ($checkme>0) {
?>
        <body onload='alert("Duplicate NSO Assigned Number Found!");_FRM.business_nso_assigned_no.focus();_FRM.business_nso_assigned_no.select();'></body>
<?php
        		$pro=12;
        	}
	}
}

if ($business_nso_estab_id<>'') {
	if ($pro==1) {
		$SearchNSOID = new BusinessEstablishment;
		$SearchNSOID->VerifyNsoID($business_id,$business_nso_estab_id);
		$checkme = $SearchNSOID->outnumrow;
	        if ($checkme>0) {
?>
        <body onload='alert("Duplicate NSO Established ID Found!");_FRM.business_nso_estab_id.focus();_FRM.business_nso_estab_id.select();'></body>
<?php
        		$pro=12;
        	}
	}
}

if ($business_dti_reg_no<>'') {
if ($pro==1) {
$SearchDTI = new BusinessEstablishment;
$SearchDTI->VerifyDTIReg($business_id,$business_dti_reg_no);
$checkme = $SearchDTI->outnumrow;
        if ($checkme>0) {
?>
        <body onload='alert("Duplicate DTI Registration Number Found!");_FRM.business_dti_reg_no.focus();_FRM.business_dti_reg_no.select();'></body>
<?php
        $pro=12;
        }
}
}

if ($business_sec_reg_no<>'') {
if ($pro==1) {
$SearchSEC = new BusinessEstablishment;
$SearchSEC->VerifySecNo($business_id,$business_sec_reg_no);
$checkme = $SearchSEC->outnumrow;
        if ($checkme>0) {
?>
        <body onload='alert("Duplicate SEC Number Found!");_FRM.business_sec_reg_no.focus();_FRM.business_sec_reg_no.select();'></body>
<?php
        $pro=12;
        }
}
}

if ($business_dot_acr_no<>'') {
if ($pro==1) {
$SearchDOT = new BusinessEstablishment;
$SearchDOT->VerifyACR($business_id,$business_dot_acr_no);
$checkme = $SearchDOT->outnumrow;
        if ($checkme>0) {
?>
        <body onload='alert("Duplicate DOT ACR Number Found!");_FRM.business_dot_acr_no.focus();_FRM.business_dot_acr_no.select();'></body>
<?php
        $pro=12;
        }
}
}

if ($business_tin_reg_no<>'') {
if ($pro==1) {
$SearchTIN = new BusinessEstablishment;
$SearchTIN->VerifyTIN($business_id,$business_tin_reg_no);
$checkme = $SearchTIN->outnumrow;
        if ($checkme>0) {
?>
        <body onload='alert("Duplicate TIN Found!");;_FRM.business_tin_reg_no.focus();_FRM.business_tin_reg_no.select();'></body>
<?php
        $pro=12;
        }
}
}

if ($pro==1 and $fupload_name<>'') {
	$allowed_file_size_in_bytes=1000000;
	$file_dir=eBPLS_APP_URL;	
	
//	echo "this is the filename $fupload .... $fupload_name";
	//if (!empty($fupload_name)){
	if ($fupload_size <= $allowed_file_size_in_bytes) {
		if (($pos = strrpos($fupload_name, ".")) == FALSE) {
			echo $fupload_name;
			$pro=12;
		?>
	<body onload='javascript:alert ("Cannot upload logo. Error in Filename.");'></body>
	<?php
		} else {
			$extension = substr($fupload_name, $pos + 1);
		}
		if (strtolower($extension) == 'jpg') {
			$pro=11;
			
			} else {
			?>
			<body onload='javascript:alert ("Cannot upload logo. Invalid file type.");'></body>
			<?php
		}
	}
	else {
	//$validateID=8;
		$pro=12			
	?>
	<body onload='javascript:alert ("Cannot upload logo. File exceeds maximum allowable size of 1MB.");'></body>
	<?php
	//print "<hr>Upload Status: &nbsp &nbsp &nbsp Unable to upload file.<br>";
	//print "File too large. Allowable maximum file size is 50kb.<hr>";
	}
} elseif ($pro==1) {
	$pro=11;
}
if ($pro==11) {
//	if ($blacklist=='on') { $blacit = 1; }
//	else { $blacit = 0; }
	$blacit = ($blacklist=='on'?1:0);
	
	if ($blacit==1) {
		$strValues="black_list_date =now(),black_list_reason ='$black_list_reason'";
                $strWhere = "business_id='$business_id'";
                $bus->UpdateBusiness($strValues,$strWhere);
	}
	$bus = new BusinessEstablishment;
	if ($business_id>0) {
                $strValues="edit_by='',edit_locked=0";
                $strWhere = "business_id='$business_id'";
                $bus->UpdateBusiness($strValues,$strWhere);
	}
	if ($changeondin==1) {
		if ($addbiz=='update') {
			if ($branch_id>'0') {
                        $addmain = new MainBranch;
			$strValues="business_main_offc_name='$business_main_offc_name',
	                business_main_offc_lot_no='$business_main_offc_lot_no',
        	        business_main_offc_street='$business_main_offc_street_no',
                	business_main_offc_barangay_name='$business_main_offc_barangay_name',
	                business_main_offc_barangay_code='$business_main_offc_barangay_code',
        	        business_main_offc_zone_code='$business_main_offc_zone_code',
                	business_main_offc_district_code ='$business_main_offc_district_code',
	                business_main_offc_city_code ='$business_main_offc_city_code',
	                business_main_offc_zip_code='$business_main_offc_zip_code',
        	        business_main_offc_tin_no ='$business_main_offc_tin_no',
			main_offc_phone = '$business_phone_no',
			main_office_prov ='$main_office_prov',
			comment = 'addslashes($comment)'";			//2008.04.16
			$strWhere = "branch_id='$branch_id'";
	                $addmain->UpdateMainBranch($strValues,$strWhere);
			} else {
			 if ($business_main_offc_name<>'') {
                                $addmain = new MainBranch;
                                $InsValues = "'','$business_main_offc_name','$business_main_offc_lot_no',
                                        '$business_main_offc_street_no',
                                        '$business_main_offc_barangay_name',
                                        '$business_main_offc_barangay_code',
                                        '$business_main_offc_zone_code',
                                        '$business_main_offc_district_code',
                                        '$business_main_offc_city_code',
                                        '$business_main_offc_zip_code',
                                        '$business_main_offc_tin_no',
                                        '$business_phone_no','$main_office_prov'";
                                $addmain->InsertMainBranch($InsValues);
                                $branch_id = $addmain->outid;
                                }
			}

			$bus = new BusinessEstablishment;
        	        $strValues ="owner_id='$owner_id', business_name='$business_name',
                	business_branch='$business_branch', business_scale='$business_scale',
	                business_lot_no='$business_lot_no',business_street ='$business_street',
        	        business_barangay_code='$business_barangay_code',
			business_zone_code='$business_zone_code',
	                business_district_code='$business_district_code',
			business_city_code='$business_city_code',
	                business_province_code='$business_province_code',
			business_zip_code ='$business_zip_code',
	                business_contact_no='$business_contact_no',business_fax_no ='$business_fax_no ',
        	        business_email_address= '$business_email_address',
			business_location_desc ='$business_location_desc',
	                business_building_name='$business_building_name',
			business_phone_no='$business_phone_no',
                	business_category_code='$business_category_code',
			business_dot_acr_no='$business_dot_acr_no',
        	        business_sec_reg_no='$business_sec_reg_no',
			business_tin_reg_no='$business_tin_reg_no',
	                business_dti_reg_no='$business_dti_reg_no',
			business_dti_reg_date='$business_dti_reg_date',
                	business_date_established='$business_date_established', 
			business_start_date='$business_start_date',
        	        business_occupancy_code='$business_occupancy_code',
			business_offc_code='$business_offc_code',
	                employee_male='$employees_M', employee_female='$employees_F',
			business_no_del_vehicles='$business_no_del_vehicles',
                	business_payment_mode='$business_payment_mode',
			business_nso_assigned_no='$business_nso_assigned_no',
        	        business_nso_estab_id='$business_nso_estab_id',
			business_industry_sector_code='$business_industry_sector_code',
	                business_remarks ='$business_remarks',business_update_by='$usern',
        	        business_update_ts=now(), blacklist='$blacit', 
			biztype='$biztype', 
			comment = '$comment',
			subsi='$subsi',pcname='$pcname',pcaddress='$pcaddress',
			regname='$regname', paidemp='$paidemp', ecoorg='$ecoorg', 
			ecoarea='$ecoarea', branch_id='$branch_id',edit_by='',edit_locked=0";
			$strWhere = "business_id='$business_id'";
			$bus->UpdateBusiness($strValues,$strWhere);
			$pylename = $business_id.".jpg";
			
			if($fupload_name<>'') {
			copy ($fupload, "images/$pylename") or die ("Couldn't copy");
			}
		} else {
		if ($busItem=='CTC') {
			if ($business_main_offc_name<>'') {
        	        	$addmain = new MainBranch;
                	        $InsValues = "'','$business_main_offc_name','$business_main_offc_lot_no',
                                        '$business_main_offc_street_no',
                                        '$business_main_offc_barangay_name',
                                        '$business_main_offc_barangay_code',
                                        '$business_main_offc_zone_code',
                                        '$business_main_offc_district_code',
                                        '$business_main_offc_city_code',
                                        '$business_main_offc_zip_code',
                                        '$business_main_offc_tin_no',
					'$business_phone_no','$main_office_prov'";
	                        $addmain->InsertMainBranch($InsValues);
        	                $branch_id = $addmain->outid;
                	}

		        $addbus = new BusinessEstablishment;
		        $InsValues = "'', '$business_name', '$business_branch',
                                '$business_scale', '$business_lot_no',
                                '$business_street', '$business_barangay_code',
                                '$business_zone_code','$business_district_code',
                                '$business_city_code','$business_province_code',
                                '$business_zip_code','$business_contact_no',
                                '$business_fax_no ','$business_email_address',
                                '$business_location_desc',
                                '$business_building_name','$business_phone_no',
                                '$business_category_code','$business_dot_acr_no',
                                '$business_sec_reg_no','$business_tin_reg_no',
                                '$business_dti_reg_no','$business_dti_reg_date',
                                '$business_date_established','$business_start_date',
                                '$business_occupancy_code','$business_offc_code',
                                '$employees_M','$business_no_del_vehicles',
                                '$business_payment_mode','$business_nso_assigned_no',
                                '$business_nso_estab_id','$business_industry_sector_code',
                                '$business_remarks', now(),'$usern',now(),
                                '$employees_F', '$blacit', 
                                '$biztype','$subsi','$pcname','$pcaddress',
                                '$regname', '$paidemp', '$ecoorg', '$ecoarea','$branch_id','','0','',''";
        	        $addbus->InsertNewBusiness($InsValues);
	        	$business_id = $addbus->outid;
			$pylename = $business_id.".jpg";
			if($fupload_name<>'') {
				copy ($fupload, "images/$pylename") or die ("Couldn't copy");
			}
			setURLRedirect("index.php?part=4&itemID_=1002&busItem=CTC&permit_type=CTC&ctc_type=BUSINESS&item_id=CTC&business_id=$business_id");
		} else {
			if ($addbiz=='Save') {
				if ($business_main_offc_name<>'') {
					$addmain = new MainBranch;
					$InsValues = "'','$business_main_offc_name',
					'$business_main_offc_lot_no',
        	                        '$business_main_offc_street_no',
                	                '$business_main_offc_barangay_name',
                        	        '$business_main_offc_barangay_code',
                                	'$business_main_offc_zone_code',
	                                '$business_main_offc_district_code',
        		                '$business_main_offc_city_code',
                        	        '$business_main_offc_zip_code',
					'$business_main_offc_tin_no',
					'$business_phone_no','$main_office_prov'";
					$addmain->InsertMainBranch($InsValues);
		                	$branch_id = $addmain->outid;
				}

				$addbus = new BusinessEstablishment;
				$InsValues = "$owner_id, '$business_name', '$business_branch', 
				'$business_scale', '$business_lot_no',
		                '$business_street', '$business_barangay_code',
				'$business_zone_code','$business_district_code',
				'$business_city_code','$business_province_code',
				'$business_zip_code','$business_contact_no',
		                '$business_fax_no ','$business_email_address',
				'$business_location_desc',
		                '$business_building_name','$business_phone_no',
				'$business_category_code','$business_dot_acr_no',
				'$business_sec_reg_no','$business_tin_reg_no',
		                '$business_dti_reg_no','$business_dti_reg_date',
                		'$business_date_established','$business_start_date',
				'$business_occupancy_code','$business_offc_code',
                		 $employees_M,'$business_no_del_vehicles',
				'$business_payment_mode','$business_nso_assigned_no',
				'$business_nso_estab_id','$business_industry_sector_code',
				'$business_remarks', now(),'$usern',now(),
				'$employees_F', '$blacit', 
				'$biztype','$subsi','$pcname','$pcaddress',
				'$regname', '$paidemp', '$ecoorg', '$ecoarea','$branch_id','','0','',''";
				$addbus->InsertNewBusiness($InsValues);
		        	$business_id = $addbus->outid;
		        	$pylename = $business_id.".jpg";
				if($fupload_name<>'') {
					copy ($fupload, "images/$pylename") or die ("Couldn't copy");
				}
			} //if adbiz
		}
		}
	}

$redito="index.php?part=4&itemID_1221&upit=1224&permit_type=Business&owner_id=$owner_id&addbiz=$addbiz&1stat=$stat&business_id=$business_id&fr=bs&stat=$stat&genpin=$genpin";
setUrlRedirect($redito);
}
if ($redito=='') {
	$redito="index.php?part=4&class_type=Permits&itemID_=1223&addbus=addbus&owner_id=$owner_id&permit_type=Business&stat=$stat&busItem=Business";
}
if ($business_id>0) {
	$addbiz='update';
}
if($cancelme==1) {
		$bus = new BusinessEstablishment;
		if ($business_id>0) {
                $strValues="edit_by='',edit_locked=0";
                $strWhere = "business_id='$business_id'";
                $bus->UpdateBusiness($strValues,$strWhere);
	?>
        <body onload='parent.location="index.php?part=4";'></body>
	<?php
	} else {
	?>
	<body onload='parent.location="index.php?part=4&owner_id=<?php echo $owner_id; ?>&class_type=Permits&permit_type=Business&busItem=Business&itemID_=1221&mainfrm=Main";'></body>
	<?php	
	}
                                                                                                                             
}
?>
<head>
<title>Business Maintenance</title>
<link rel="stylesheet" href="stylesheets/default.css" type="text/css"/>

<script language='Javascript' src='javascripts/default.js'></script>
<link rel="stylesheet" href="stylesheets/calendar.css?random=20051112" media="screen"></LINK>
<SCRIPT type="text/javascript" src="javascripts/calendar.js?random=20060118"></script>
	
</head>
<body>
<div align="CENTER">
<!---// start of the table //-->
<br>

<form name="_FRM" method="POST" ENCTYPE="multipart/form-data" action="<?php echo $redito; ?>">
<input type=hidden name=changeondin value='<?php echo $changeondin; ?>'>
<input type='hidden' name='owner_id' maxlength=25 class='text180'  value="<?php echo $owner_id; ?>">
<input type='hidden' name='owner_last_name' maxlength=25 class='text180'  value="<?php echo $owner_last_name; ?>">
<input type='hidden' name='owner_first_name' maxlength=25 class='text180'  value="<?php echo $owner_first_name; ?>">
<input type='hidden' name='owner_middle_name' maxlength=25 class='text180'  value="<?php echo $owner_middle_name; ?>">
<input type='hidden' name='business_id' maxlength=25 class='text180'  value="<?php echo $business_id; ?>"> 
<input type='hidden' name='genpin' maxlength=25 class='text180' value=<?php echo $genpin; ?>>
<table width='90%' border=0 cellpadding=1 cellspacing=1>
  <!--// start of the owner information //-->
  <!--// end of the owner information //-->
  <!--// start of the business permit information //-->
  <tr> 
    <td align="center" valign="top" class='header2' colspan=4 > Business Enterprise Maintenance</td>
  </tr>
  <tr><td align=center>
	<table border=1>
	        <tr><td>
        	<img src = 'images/<?php echo $business_id.".jpg"; ?>' width=200 height=200>
	       	</td></tr>
	        </table>
            <input type=file name=fupload onclick='javascript:flagchange(changeondin);'>
            <br />
            <input type=button value="GIS">
   </td>
   <td valign=top><table><tr>
<?php
	if ($stat=='New') {

		if ($stat=='New') {
			$strnew = 'selected';
			$strrenew='';
		} else {	
			$strrenew = 'selected';
			$strnew='';
		}

?>
  	<td align=right size=10 class=normalbold>Status :</td>
  	<td align=left>&nbsp;&nbsp;<select name=stat class=select100>
  		<option value='New' <?php echo $strnew; ?>>New</option>
  		<option value='ReNew' <?php echo $strrenew; ?>>ReNew</option>
  	</select></td></tr><tr>
<?php
	}
	if ($addbiz=='update') {
		if ($blacklist==on) { $checkit = 'checked'; } 
		elseif ($datarow[blacklist]==1) { $checkit = 'checked'; } 
		else { $checkit =''; }
?>
	<td colspan=4 align=left class=normalbold>Blacklisted:
        &nbsp;&nbsp;
	<input type=checkbox name=blacklist <?php echo $checkit; ?> 
		onchange='javascript:flagchange(changeondin);'>
        &nbsp;&nbsp;Reason for Blacklist:
	<input type=text name=black_list_reason value=
	"<?php echo $datarow[black_list_reason]; ?>" 
	onchange='javascript:flagchange(changeondin);'>
        </td></tr><tr>
<?php
	}
?>

    <td align="left" valign="top" class='normalbold' colspan=2><br />Business Information </td>
  </tr><tr> 
    <td align="right" valign="top" class='normal'> <font color="#FF0000">* </font>Business Name: </td>
    <td align="left" valign="top" class='normal' >&nbsp;
<?php
	if ($datarow[business_name]=='') {
		$datarow[business_name]=$business_name;
		
	}
?>
 <input type='text' name='business_name' maxlength=255 class='text180'  value=
 "<?php echo stripslashes($datarow[business_name]); ?>" 
 onchange='javascript:flagchange(changeondin);'>
      </td>
  </tr><tr> 
    <td align="right" valign="top" class='normal'><font color="#FF0000">* </font>Business Branch: </td>
    <td align="left" valign="top" class='normal'>&nbsp; 

<?php
	if ($business_branch=='') {
		$business_branch='None';
	}

        if ($datarow[business_branch]=='') {
                $datarow[business_branch]=$business_branch;
        }
?>
<input type='text' name='business_branch' maxlength=255 class='text180' value=
"<?php echo $datarow[business_branch]; ?>" onchange='javascript:flagchange(changeondin);'> 
    </td>
  </tr><tr> 
    <td align="right" valign="top" class='normal'> Business Scale: </td>
    <td align="left" valign="top" class='normal'>
<?php
        if ($datarow[business_scale]=='') {
                $datarow[business_scale]=$business_scale;
        }
?>

&nbsp;&nbsp;<select name='business_scale' onchange='javascript:flagchange(changeondin);' class='select100'>
		<?php
			if ($addbus<>'addbus' ||  $business_scale<>'' and $business_id<>'') {
		?>
    		    <option value='<?php echo $datarow[business_scale]; ?>'> 
			<?php echo $datarow[business_scale]; ?>	
		<?php
    		   	} //else {    	
	    ?>
        		<option value='MICRO'>Micro</option>
        		<option value='COTTAGE'>Cottage</option>	
        		<option value='SMALL'>Small</option>
        		<option value='MEDIUM'>Medium</option>
        		<option value='LARGE'>Large</option>
    	<?php
        		//}
        ?>
      </select> </td>
  </tr><tr> 
    <td align="right" valign="top" class='normal'> <font color="#FF0000">* </font>Payment Mode: </td>
    <td align="left" valign="top" class='normal'>&nbsp; <select name='business_payment_mode' class='select100' onchange='javascript:flagchange(changeondin);'>
<?php
        if ($datarow[business_payment_mode]=='') {
                $datarow[business_payment_mode]=$business_payment_mode;
        }
?>
<?php
	if ($addbus<>'addbus' || $business_payment_mode<>'' and $business_id<>'') {
?>
    			<option value='<?php echo $datarow[business_payment_mode]; ?>'> 
    			<?php echo $datarow[business_payment_mode]; ?>
<?php
	} //else {
?> 
  <!---	  	    <option value='MONTHLY'>Monthly</option> -->
        		<option value='QUARTERLY'>Quarterly</option>
        		<option value='SEMI-ANNUAL'>Semi-Annual</option>
        		<option value='ANNUAL'>Annual</option>
<?php
	//}
?>
      </select> </td>
  </tr><tr> 
    <td align="right" valign="top" class='normal'>Comment: </td>
    <td align="left" valign="top" class='normal' >&nbsp;
<?php
	if ($datarow[comment]=='') {
		$datarow[comment]=$comment;
	}
?>
 
 <input type='text' name='comment' maxlength=255 class='text180'  value=
 "<?php echo stripslashes($datarow[comment]); ?>" 
 onchange='javascript:flagchange(changeondin);'>
</td>
</tr>

</table></td></tr></table>
<table width='90%' border=0 cellpadding=1 cellspacing=1>
  <tr> 
    <td colspan="4" align="left" valign="top" class='normalbold' >Business Contact Information </td>
    
  </tr>
  <tr>
  	<td align="right" valign="top" class='normal'  > Building Name:</td>
	<td align="left" valign="top" class='normal'>&nbsp;
<?php
        if ($datarow[business_building_name]=='') {
                $datarow[business_building_name]=$business_building_name;
        }
?>
	<input type='text' name='business_building_name' maxlength=255 class='text180'  value=
	"<?php echo $datarow[business_building_name]; ?>"
	onchange='javascript:flagchange(changeondin);'>
	</td>
    <td align="right" valign="top" class='normal'> <font color="#FF0000">* </font>Address: </td>
    <td align="left" valign="top" class='normal'>&nbsp;
<?php
        if ($datarow[business_lot_no]=='') {
                $datarow[business_lot_no]=$business_lot_no;
        }
	if ($datarow[business_street]=='') {
                $datarow[business_street]=$business_street;
        }
?>
	<input type='text' name='business_street' maxlength=255 class='text180' value=
	"<?php echo $datarow[business_street]; ?>" 
	onchange='javascript:flagchange(changeondin);'>

	<input type='hidden' name='business_lot_no' maxlength=255 class='text180' value=
	"<?php echo $datarow[business_lot_no]; ?>"
	onchange='javascript:flagchange(changeondin);'> 
    </td>
    <td align="right" valign="top" class='normal'  ><!-- <font color="#FF0000">* </font>Street 
      :--> </td>
    <td align="left" valign="top" class='normal'>&nbsp;

<?php
        if ($datarow[business_street]=='') {
                $datarow[business_street]=$business_street;
        }
?>
	<input type='hidden' name='business_street1' maxlength=255 class='text180' value=
	"<?php echo $datarow[business_street]; ?>"
	onchange='javascript:flagchange(changeondin);'> 
    </td>
</tr>
<tr>
<?php
	$getall = SelectDataWhere($dbtype,$dbLink,"ebpls_buss_preference","");
	$getct = FetchArray($dbtype,$getall);
		//city
	$getcty = SelectDataWhere($dbtype,$dbLink,"ebpls_city_municipality",
					"where city_municipality_code = '$getct[lguname]'");
	$getcty = FetchRow($dbtype,$getcty);
		//province
	$getpro = SelectDataWhere($dbtype,$dbLink,"ebpls_province",
					"where province_code = '$getct[lguprovince]'");
	$getpro = FetchRow($dbtype,$getpro);

	$getzip = SelectDataWhere($dbtype,$dbLink,"ebpls_zip",
					"where upper = '$getcty[0]'");
	$getzip = FetchRow($dbtype,$getzip);

?>
	<td align="right" valign="top" class='normal'> <font color="#FF0000">* </font>Province:</td>
	<td align="left" valign="top" class='normal'>&nbsp;
	 <input type=hidden name='business_province_code' value="<?php echo $getpro[0]; ?>">
	 <input type=text name='business_pro_desc' maxlength=255 class='text180' readonly value=
	 "<?php echo strtoupper($getpro[1]); ?>">
	</td>
	<td align="right" valign="top" class='normal'> <font color="#FF0000">* </font>Municipality: </td>
	<td align="left" valign="top" class='normal'>&nbsp; 
	<input type=hidden name='business_city_code'value="<?php echo $getcty[0]; ?>">
	<input type=text name='business_city_desc' maxlength=255 class='text180' readonly value=
	"<?php echo strtoupper($getcty[1]); ?>">
    	</td>
</tr>
<tr> 
	<td align="right" valign="top" class='normal'> <font color="#FF0000">* </font>District: </td>
<script type="text/javascript" src="javascripts/ajax.js"></script>
<script type="text/javascript">
var ajax = new sack();
function getBrgyList(sel)
{
        var districtCode = sel.options[sel.selectedIndex].value;
        document.getElementById('business_barangay_code').options.length = 0;        // Empty city select box
        if(districtCode.length>0){
                ajax.requestFile = 'getCities.php?business_district_code='+districtCode;   // Specifying which file to get
                ajax.onCompletion = createBrgy;       // Specify function that will be executed after file has been found
                ajax.runAJAX();         // Execute AJAX function
        }
}
                                                                                                                                               
function createBrgy()
{
        var obj = document.getElementById('business_barangay_code');
        eval(ajax.response);    // Executing the response from Ajax as Javascript code
}
                                                                                                                                               
function getZoneList(sel)
{
        var brgyCode = sel.options[sel.selectedIndex].value;
        document.getElementById('business_zone_code').options.length = 0;        // Empty city select box
        if(brgyCode.length>0){
                ajax.requestFile = 'getCities.php?business_barangay_code='+brgyCode;   // Specifying which file to get
                ajax.onCompletion = createZone;       // Specify function that will be executed after file has been found
                ajax.runAJAX();         // Execute AJAX function
        }
}
                                                                                                                                               
function createZone()
{
        var obj = document.getElementById('business_zone_code');
        eval(ajax.response);    // Executing the response from Ajax as Javascript code
}

function getMainCityList(sel)
{
        var provCode = sel.options[sel.selectedIndex].value;
        document.getElementById('business_main_offc_city_code').options.length = 0;        // Empty city select box
        if(provCode.length>0){
                ajax.requestFile = 'getCities.php?main_office_prov='+provCode;   // Specifying which file to get
                ajax.onCompletion = createMainCities;  // Specify function that will be executed after file has been found
                ajax.runAJAX();         // Execute AJAX function
        }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       
function createMainCities()
{
        var obj = document.getElementById('business_main_offc_city_code');
        eval(ajax.response);    // Executing the response from Ajax as Javascript code
}

function getMainDistrictList(sel)
{
        var cityCode = sel.options[sel.selectedIndex].value;
        document.getElementById('business_main_offc_district_code').options.length = 0;        // Empty city select box
        if(cityCode.length>0){
                ajax.requestFile = 'getCities.php?business_main_offc_city_code='+cityCode;   // Specifying which file to get
                ajax.onCompletion = createMainDistrict;  // Specify function that will be executed after file has been found
                ajax.runAJAX();         // Execute AJAX function
        }
}

function createMainDistrict()
{
        var obj = document.getElementById('business_main_offc_district_code');
        eval(ajax.response);    // Executing the response from Ajax as Javascript code
}

function getMainBrgyList(sel)
{
        var districtCode = sel.options[sel.selectedIndex].value;
        document.getElementById('business_main_offc_barangay_code').options.length = 0;        // Empty city select box
        if(districtCode.length>0){
                ajax.requestFile = 'getCities.php?business_main_offc_district_code='+districtCode;  
                ajax.onCompletion = createMainBrgy;       // Specify function that will be executed after file has been found
                ajax.runAJAX();         // Execute AJAX function
        }
}

function createMainBrgy()
{
        var obj = document.getElementById('business_main_offc_barangay_code');
        eval(ajax.response);    // Executing the response from Ajax as Javascript code
}

function getMainZoneList(sel)
{
        var brgyCode = sel.options[sel.selectedIndex].value;
        document.getElementById('business_main_offc_zone_code').options.length = 0;        // Empty city select box
        if(brgyCode.length>0){
                ajax.requestFile = 'getCities.php?business_main_offc_barangay_code='+brgyCode;  
                ajax.onCompletion = createMainZone;       // Specify function that will be executed after file has been found
                ajax.runAJAX();         // Execute AJAX function
        }
}

function createMainZone()
{
        var obj = document.getElementById('business_main_offc_zone_code');
        eval(ajax.response);    // Executing the response from Ajax as Javascript code
}
                                                                                                                                               
</script>
    <td align="left" valign="top" class='normal'>&nbsp; 
<?php 
if ($business_district_code<>$datarow[business_district_code] and $business_district_code<>'') {
	$datarow[business_district_code] = $business_district_code;
} else {
	$business_district_code=$datarow[business_district_code];
}

echo get_select_dist_ajax($dbLink,'business_district_code','ebpls_district','district_code','district_desc',$datarow[business_district_code],$getcty[0]);

//echo get_select_data($dbLink,'business_district_code','ebpls_district','district_code','district_desc',$datarow[10]);?>
    </td>
    <td align="right" valign="top" class='normal'> <font color="#FF0000">* </font>Barangay: </td>
    <td align="left" valign="top" class='normal'>&nbsp; 
      <?php  
/*
if ($business_barangay_code<>$datarow[business_barangay_code] and $business_barangay_code<>'') {
        $datarow[business_barangay_code] = $business_barangay_code;
} else {
        $business_barangay_code=$datarow[business_barangay_code];
}*/
	if ($business_id>0) { $business_barangay_code=$datarow[business_barangay_code]; }
	$getz = SelectDataWhere($dbtype,$dbLink,"ebpls_barangay",
                        "where barangay_code  = '$business_barangay_code'");
	$business_barangay_desc = FetchArray($dbtype,$getz);
	$business_barangay_desc = $business_barangay_desc[barangay_desc];

//echo get_select_barg($dbLink,'business_barangay_code','ebpls_barangay','barangay_code','barangay_desc',$datarow[business_barangay_code],$business_district_code);
//echo get_select_data($dbLink,'business_barangay_code','ebpls_barangay','barangay_code',	'barangay_desc',$datarow[7]);?>
	<select id="business_barangay_code" name="business_barangay_code" 
		onchange='getZoneList(this);flagchange(changeondin); _FRM.pro.value=0;' class=select200>
                <option value="<?php echo $business_barangay_code; ?>">
                <?php echo $business_barangay_desc; ?> </option>
        </select>
    </td>
  </tr>
  <tr> 
    <td align="right" valign="top" class='normal'> <font color="#FF0000"> </font>Zone: </td>
    <td align="left" valign="top" class='normal'>&nbsp; <?php 

/*if ($business_zone_code<>$datarow[business_zone_code] and $business_zone_code<>'') {
        $datarow[business_zone_code] = $business_zone_code;
} else {*/

if ($business_id>0) {
        $business_zone_code=$datarow[business_zone_code];
}


//echo get_select_zone($dbLink,'business_zone_code','ebpls_zone','zone_code','zone_desc',$datarow[business_zone_code],$business_barangay_code);

//echo get_select_data($dbLink,'business_zone_code','ebpls_zone','zone_code','zone_desc',$datarow[8]);
	$getz = SelectDataWhere($dbtype,$dbLink,"ebpls_zone",
                        "where zone_code  = '$business_zone_code'");
	$business_zone_desc = FetchArray($dbtype,$getz);
	$business_zone_desc = $business_zone_desc[zone_desc];
?> 
<select id="business_zone_code" name="business_zone_code" class=select200 onclick='flagchange(changeondin); _FRM.pro.value=0;'>
                <option value="<?php echo $business_zone_code; ?>"><?php echo $business_zone_desc; ?></option>
                </select>
    </td>
    <td align="right" valign="top" class='normal'> <font color="#FF0000">* </font>Zip: </td>
    <td align="left" valign="top" class='normal'>&nbsp; 
	<input type=text readonly name=business_zip_code maxlength=255 class=text180 value="<?php echo $getzip[0]; ?>"> 

<?php //echo get_select_data($dbLink,'business_zip_code','ebpls_zip','zip_code','zip_desc',$datarow[13]);
?> 
    </td>
  </tr>
  <tr> 
    <td align="right" valign="top" class='normal'> Contact Number: </td>
    <td align="left" valign="top" class='normal'>&nbsp; 
<?php
        if ($datarow[business_contact_no]=='') {
                $datarow[business_contact_no]=$business_contact_no;
        }
?>


<input type='text' name='business_contact_no' maxlength=255 class='text180'  value="<?php echo $datarow[business_contact_no]; ?>" onchange='javascript:flagchange(changeondin);'> 
    </td>
    <td align="right" valign="top" class='normal' > Fax Number: </td>
    <td align="left" valign="top" class='normal'>&nbsp;
<?php
        if ($datarow[business_fax_no]=='') {
                $datarow[business_fax_no]=$business_fax_no;
        }
?>
<input type='text' name='business_fax_no' maxlength=255 class='text180'  value="<?php echo $datarow[business_fax_no]; ?>" onchange='javascript:flagchange(changeondin);'> 
    </td>
  </tr>
  <tr> 
    <td colspan="4" align="right" valign="top" class='normal'><div align="left"></div></td>
  </tr>
  <tr> 
    <td colspan="4" align="left" valign="top" class='normalbold'  > Business Other Information  </td>
    
  </tr>
  <tr> 
    <td align="right" valign="top" class='normal'  > <font color="#FF0000">* </font>Date 
      Established: 

<?php

        if ($datarow[business_date_established]=='') {
                $datarow[business_date_established]=$tdate;
        } else {
	        $datarow[business_date_established]=substr($datarow[business_date_established],0,10);
        }

?>

<!--      <input type='hidden' name='business_date_established' maxlength=10 class='text180' value="<?php echo $datarow[27];?>"> -->
    </td>
    <td align="left" valign="top" class='normal'>&nbsp; 
<input type="text" class='text180' value="<?php echo $datarow[business_date_established] ?>" readonly name="business_date_established" onclick="displayCalendar(business_date_established,'yyyy/mm/dd',this);">
<img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date" onclick ="displayCalendar(_FRM.business_date_established,'yyyy/mm/dd',this);_FRM.changeondin.value=1;">
    </td>
    <td align="right" valign="top" class='normal' > <font color="#FF0000">* </font>No. 
      of Delivery Vehicles: </td>
    <?php
    if ($addbiz=='Save' || $itemID_==1002) {
	?>

<?php
        if ($business_no_del_vehicles=='') {
		$zeroit = 0;
	} else {
                $zeroit=$business_no_del_vehicles;
        }
?>
	    <td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='business_no_del_vehicles' maxlength=255 class='text180'  value='<?php echo $zeroit; ?>' onchange='javascript:flagchange(changeondin);'> 
	    </td>
	<?php
	} elseif ($addbiz=='update' || $itemID_==1002) {
	?>
    	<td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='business_no_del_vehicles' maxlength=255 class='text180'  value="<?php echo $datarow[business_no_del_vehicles]; ?>" onchange='javascript:flagchange(changeondin);'> 
    	</td>
    <?php
	}
	?>
  </tr>
  <tr> 
    <td align="right" valign="top" class='normal'  > <font color="#FF0000">* </font>Start 
      Date :
<?php
        if ($datarow[business_start_date]=='') {
                $datarow[business_start_date]=$tdate;
        } else {
	        $datarow[business_start_date]=substr($datarow[business_start_date],0,10);
        }
?>
    </td>
    <td align="left" valign="top" class='normal'>&nbsp; 

<input type="text" class='text180' value="<?php echo $datarow[business_start_date] ?>" readonly name="business_start_date" onclick="displayCalendar(business_start_date,'yyyy/mm/dd',this);">
<img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date" onclick ="displayCalendar(_FRM.business_start_date,'yyyy/mm/dd',this);_FRM.changeondin.value=1;">
  
        
        
        </td>
    <td align="right" valign="top" class='normal'> Location Desc: </td>
    <td align="left" valign="top" class='normal'>&nbsp; 
<?php
        if ($datarow[business_location_desc]=='') {
                $datarow[business_location_desc]=$business_location_desc;
        }
?>

<input type='text' name='business_location_desc' maxlength=255 class='text180'  value="<?php echo $datarow[business_location_desc]; ?>" onchange='javascript:flagchange(changeondin);'> 
    </td>
  </tr>
  <tr> 
    <td align="right" valign="top" class='normal'  > <font color="#FF0000">* </font>Occupancy 
: </td>
    <td align="left" valign="top" class='normal'>&nbsp; 
<?php
        if ($datarow[business_occupancy_code]=='') {
                $datarow[business_occupancy_code]=$business_occupancy_code;
        }
?>


<?php echo get_select_data_biz($dbLink,'business_occupancy_code','ebpls_occupancy_type','occupancy_type_code','occupancy_type_desc',$datarow[business_occupancy_code]);?> 
    </td>
    <td align="right" valign="top" class='normal'>Remarks: </td>
    <td align="left" valign="top" class='normal'>&nbsp; 

<?php
        if ($datarow[business_remarks]=='') {
                $datarow[business_remarks]=$business_remarks;
        }
?>

<input type='text' name='business_remarks' maxlength=255 class='text180' value="<?php echo $datarow[business_remarks]; ?>" onchange='javascript:flagchange(changeondin);'> 
    </td>
  </tr>
  <tr> 
    <td align="right" valign="top" class='normal'  ><font color="#FF0000">* </font>Ownership Type: </td>
    <td align="left" valign="top" class='normal'>&nbsp; 

<?php
        if ($datarow[business_category_code]=='') {
                $datarow[business_category_code]=$business_category_code;
        }
?>


<?php echo get_select_data_biz($dbLink,'business_category_code','ebpls_business_category','business_category_code','business_category_desc',$datarow[business_category_code]);?> 
    </td>
    <td align="right" valign="top" class='normal'  > &nbsp; </td>
    <td align="left" valign="top" class='normal'>&nbsp;  
    </td>
  </tr>
  <tr>
    <td align="right" valign="top" class='normal' > <font color="#FF0000">* </font>No. 
      of Employees: </td>
    <?php
    if ($addbiz=='Save' || $addbiz=='') {
	?>
<?php
        if ($employees_M=='') {
                $zeroM = 0;;
        } else {
		$zeroM = $employees_M;
	}

	if ($employees_F=='') {
                $zeroF = 0;;
        } else {
                $zeroF = $employees_F;
        }

?>

	<td align="left" valign="top" class='normal' >&nbsp; 
	Male <input type='text' name='employees_M' maxlength=3 size=3 value='<?php echo $zeroM; ?>' onchange='javascript:flagchange(changeondin);'> &nbsp; 
	Female <input type='text' name='employees_F' maxlength=3 size=3 value='<?php echo $zeroF; ?>' onchange='javascript:flagchange(changeondin);'>    
	</td>
	<?php
    } elseif ($addbiz=='update') {
	?>
    	<td align="left" valign="top" class='normal' >&nbsp; Male <input type='text' name='employees_M' maxlength=3 size=3 value="<?php echo $datarow[employee_male]; ?>" onchange='javascript:flagchange(changeondin);'> &nbsp; 
Female <input type='text' name='employees_F' maxlength=3 size=3 value="<?php echo $datarow[employee_female]; ?>" onchange='javascript:flagchange(changeondin);'>    
    	</td>
    <?php
	}
	?>
    <td align="right" valign="top" class='normal'  >&nbsp;</td>
    <td align="left" valign="top" class='normal'>&nbsp; 
    </td>
  </tr>
  <tr> 
    <td align="right" valign="top" class='normal'  > Email Address: </td>
    <td align="left" valign="top" class='normal'>&nbsp; 

<?php
        if ($datarow[business_email_address]=='') {
                $datarow[business_email_address]=$business_email_address;
        }
?>

<input type='text' name='business_email_address' maxlength=255 class='text180'  value="<?php echo $datarow[business_email_address]; ?>" onchange='javascript:flagchange(changeondin);'> 
    </td>
    <td align="right" valign="top" class='normal'  > &nbsp; </td>
    <td align="left" valign="top" class='normal'>&nbsp; 
    </td>
  </tr>
  <tr> 
    <td colspan="4" align="right" valign="top" class='normal'  >&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="4" align="left" valign="top" class='normalbold'> Business Necessities Information</td>
  </tr>
  <tr> 
    <td align="right" valign="top" class='normal'  >DOT ACR No.: </td>
    <td align="left" valign="top" class='normal'>&nbsp; 
<?php
        if ($datarow[business_dot_acr_no]=='') {
                $datarow[business_dot_acr_no]=$business_dot_acr_no;
        }
?>

<input type='text' name='business_dot_acr_no' maxlength=255 class='text180'  value="<?php echo $datarow[business_dot_acr_no]; ?>" onchange='javascript:flagchange(changeondin);'> 
    </td>
    <td align="right" valign="top" class='normal'  >DTI Registration No.: </td>
    <td align="left" valign="top" class='normal'>&nbsp;
<?php
        if ($datarow[business_dti_reg_no]=='') {
                $datarow[business_dti_reg_no]=$business_dti_reg_no;
        }
?>

 <input type='text' name='business_dti_reg_no' maxlength=255 class='text180'  value="<?php echo $datarow[business_dti_reg_no]; ?>" onchange='javascript:flagchange(changeondin);'> 
    </td>
  </tr>
  <tr> 
    <td align="right" valign="top" class='normal'  >SEC Registration No.: </td>
    <td align="left" valign="top" class='normal'>&nbsp;

<?php
        if ($datarow[business_sec_reg_no]=='') {
                $datarow[business_sec_reg_no]=$business_sec_reg_no;
        }
?>


 <input type='text' name='business_sec_reg_no' maxlength=255 class='text180'  value="<?php echo $datarow[business_sec_reg_no]; ?>" onchange='javascript:flagchange(changeondin);'> 
    </td>
    <td align="right" valign="top" class='normal'  > DTI Registration Date:  
<?php
        if ($datarow[business_dti_reg_date]=='') {
                $datarow[business_dti_reg_date]=$tdate;
        } else {
	        $datarow[business_dti_reg_date]=substr($datarow[business_dti_reg_date],0,10);
        }
?>
    </td>
    <td align="left" valign="top" class='normal'>&nbsp; 

<input type="text" class='text180' value="<?php echo $datarow[business_dti_reg_date] ?>" readonly name="business_dti_reg_date" onclick="displayCalendar(business_dti_reg_date,'yyyy/mm/dd',this);">
<img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date" onclick ="displayCalendar(_FRM.business_dti_reg_date,'yyyy/mm/dd',this);_FRM.changeondin.value=1;">
    
        
        
         </td>
  </tr>
  <tr> 
    <td align="right" valign="top" class='normal'  >BIR Registration No.: </td>
    <td align="left" valign="top" class='normal'>&nbsp;
<?php
        if ($datarow[business_tin_reg_no]=='') {
                $datarow[business_tin_reg_no]=$business_tin_reg_no;
        }
?>


 <input type='text' name='business_tin_reg_no' maxlength=255 class='text180'  value="<?php echo $datarow[business_tin_reg_no]; ?>" onchange='javascript:flagchange(changeondin);'> 
    </td>
    <td align="right" valign="top" class='normal'  >NSO Assigned No.: </td>
    <td align="left" valign="top" class='normal'>&nbsp; 
<?php
        if ($datarow[business_nso_assigned_no]=='') {
                $datarow[business_nso_assigned_no]=$business_nso_assigned_no;
        }
?>


<input type='text' name='business_nso_assigned_no' maxlength=255 class='text180'  value="<?php echo $datarow[business_nso_assigned_no]; ?>" onchange='javascript:flagchange(changeondin);'> 
    </td>
  </tr>
  <tr> 
    <td align="right" valign="top" class='normal'  >Industry Sector: </td>
    <td align="left" valign="top" class='normal'>&nbsp; 

<?php
        if ($datarow[business_industry_sector_code]=='') {
                $datarow[business_industry_sector_code]=$business_industry_sector_code;
        }
?>


<?php echo get_select_data_biz($dbLink,'business_industry_sector_code','ebpls_industry_sector','industry_sector_code','industry_sector_desc',$datarow[business_industry_sector_code]);?> 
    </td>
    <td align="right" valign="top" class='normal' >NSO Established ID: </td>
    <td align="left" valign="top" class='normal'>&nbsp; 

<?php
        if ($datarow[business_nso_estab_id]=='') {
                $datarow[business_nso_estab_id]=$business_nso_estab_id;
        }
?>


<input type='text' name='business_nso_estab_id' maxlength=255 class='text180'  value="<?php echo $datarow[business_nso_estab_id]; ?>" onchange='javascript:flagchange(changeondin);'> 
    </td>
  </tr>
  <tr> 
    <td colspan="4" align="right" valign="top" class='normal'  >&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="4" align="left" valign="top" class='normalbold'  >Business Main Information </td>
  </tr>
  <tr>
<input type=hidden name=branch_id value=<?php echo $datarow[branch_id]; ?>>
    <td align="right" valign="top" class='normal'  > Office Name: </td>
    <td align="left" valign="top" class='normal' >&nbsp; 
<?php
	if ($datarow[branch_id]<>'') {
	$gbranch = new MainBranch;
	$gbranch->GetBranch($datarow[branch_id]);
	$branch=$gbranch->outarray;
	}

        if ($branch[business_main_offc_name]=='') {
                $branch[business_main_offc_name]=$business_main_offc_name;
        }
?>


<input type='text' name='business_main_offc_name' maxlength=255 class='text180'  value="<?php echo $branch[business_main_offc_name]; ?>" onchange='javascript:flagchange(changeondin);'> 
    </td>
</tr>
	<tr>

    <td align="right" valign="top" class='normal'  > Office Lot: </td>
    <td align="left" valign="top" class='normal'>&nbsp; 
<?php
        if ($branch[business_main_offc_lot_no]=='') {
                $branch[business_main_offc_lot_no]=$business_main_offc_lot_no;
        }
?>


<input type='text' name='business_main_offc_lot_no' maxlength=255 class='text180'  value="<?php echo $branch[business_main_offc_lot_no]; ?>" onchange='javascript:flagchange(changeondin);'> 
    </td>
    <td align="right" valign="top" class='normal' > Office Street: </td>
    <td align="left" valign="top" class='normal'>&nbsp; 

<?php
        if ($branch[business_main_offc_street]=='') {
                $branch[business_main_offc_street]=$business_main_offc_street_no;
        }
?>

<input type='text' name='business_main_offc_street_no' maxlength=255 class='text180'  value="<?php echo $branch[business_main_offc_street]; ?>" onchange='javascript:flagchange(changeondin);'> 
    </td>
</tr><tr>
    <td align="right" valign="top" class='normal' > Office TIN No.: </td>
    <td align="left" valign="top" class='normal'>&nbsp; 

<?php
        if ($branch[business_main_offc_tin_no]=='') {
                $branch[business_main_offc_tin_no]=$business_main_offc_tin_no;
        }
?>

<input type='text' name='business_main_offc_tin_no' maxlength=255 class='text180'  value="<?php echo $branch[business_main_offc_tin_no]; ?>" onchange='javascript:flagchange(changeondin);'> 
    </td>
    <td align="right" valign="top" class='normal'  > Office Phone Number 
: </td>
    <td align="left" valign="top" class='normal'>&nbsp; 

<?php
        if ($branch[main_offc_phone]=='') {
                $branch[main_offc_phone]=$business_phone_no;
        }
?>

<input type='text' name='business_phone_no' maxlength=255 class='text180'  value="<?php echo $branch[main_offc_phone]; ?>" onchange='javascript:flagchange(changeondin);'> 
    </td>
  </tr>
  <tr>
<td align="right" valign="top" class='normal'> Office Province: </td>
    <td align="left" valign="top" class='normal'>&nbsp; <?php 

if ($main_office_prov<>$branch[main_office_prov] and 
	$main_office_prov<>'' and $branch[main_office_prov]=='') {
                $branch[main_office_prov]=$main_office_prov;
                $branch[business_main_offc_city_code]='';
                $branch[business_main_offc_district_code]='';
                $branch[business_main_offc_barangay_code]='';
                $branch[business_main_offc_zone_code]='';
} else {
		$main_office_prov=$branch[main_office_prov];
}
                                                                                                 
echo get_select_prov_ajax($dbLink,'main_office_prov','ebpls_province','province_code','province_desc',$branch[main_office_prov],$main_office_prov);

//echo get_select_data($dbLink,'main_offc_prov','ebpls_province','province_code','province_desc',$datarow[74]);?>
    </td>

 <td align="right" valign="top" class='normal'> Office City: </td>
    <td align="left" valign="top" class='normal'>&nbsp; <?php 
 if ($business_main_offc_city_code<>$branch[business_main_offc_city_code] 
	and $business_main_offc_city_code<>'') {
                $branch[business_main_offc_city_code]=$business_main_offc_city_code;
        } else {
                $business_main_offc_city_code = $branch[business_main_offc_city_code];
        }
                                               
//echo get_select_city($dbLink,'business_main_offc_city_code','ebpls_city_municipality','city_municipality_code','city_municipality_desc',$branch[business_main_offc_city_code],$main_office_prov);

//echo get_select_data($dbLink,'business_main_offc_city_code','ebpls_city_municipality','city_municipality_code','city_municipality_desc',$datarow[38]);
  		$getz = SelectDataWhere($dbtype,$dbLink,"ebpls_city_municipality",
                        "where city_municipality_code  = '$business_main_offc_city_code'");
                $business_main_offc_city_desc = FetchArray($dbtype,$getz);
                $business_main_offc_city_desc = $business_main_offc_city_desc[city_municipality_desc];
?>
                <select id="business_main_offc_city_code" name="business_main_offc_city_code" onchange='getMainDistrictList(this); flagchange(changeondin); _FRM.pro.value=0;' class=select200>
                <option value="<?php echo $business_main_offc_city_code; ?>"><?php echo $business_main_offc_city_desc; ?></option>
                </select>

    </td> 
  </tr>
<tr>
<td align="right" valign="top" class='normal'> Office District: </td>
    <td align="left" valign="top" class='normal'>&nbsp; <?php 

if($branch[business_main_offc_district_code]<>$business_main_offc_district_code 
   and $business_main_offc_district_code<>'') {
	$branch[business_main_offc_district_code]=$business_main_offc_district_code;
} else {
	$business_main_offc_district_code=$branch[business_main_offc_district_code];
}
                                                                                                 
//echo get_select_dist($dbLink,'business_main_offc_district_code','ebpls_district','district_code','district_desc',$branch[business_main_offc_district_code],$business_main_offc_city_code);
//echo get_select_data($dbLink,'business_main_offc_district_code','ebpls_district','district_code','district_desc',$datarow[37]);
	 $getz = SelectDataWhere($dbtype,$dbLink,"ebpls_district",
                        "where district_code  = '$business_main_offc_district_code'");
                $business_main_offc_district_desc = FetchArray($dbtype,$getz);
                $business_main_offc_district_desc = $business_main_offc_district_desc[district_desc];
                                                                                                                             
?>
                                                                                                                             
             <select id="business_main_offc_district_code" name="business_main_offc_district_code" onchange='getMainBrgyList(this); flagchange(changeondin); _FRM.pro.value=0;' class=select200>
                <option value="<?php echo $business_main_offc_district_code; ?>"><?php echo $business_main_offc_district_desc; ?></option>
                </select>



    </td>
 <td align="right" valign="top" class='normal'  > Office Barangay: </td>
    <td align="left" valign="top" class='normal'>&nbsp;
      <?php 

if($branch[business_main_offc_barangay_code]<>$business_main_offc_barangay_code 
	and $business_main_offc_barangay_code<>'') {
$branch[business_main_offc_barangay_code]=$business_main_offc_barangay_code;
} else {
$business_main_offc_barangay_code=$branch[business_main_offc_barangay_code];
}
                                                                                                 
                                                                                                 
//echo get_select_barg($dbLink,'business_main_offc_barangay_code','ebpls_barangay','barangay_code','barangay_desc',$branch[business_main_offc_barangay_code],$business_main_offc_district_code);


// echo get_select_data($dbLink,'business_main_offc_barangay_code','ebpls_barangay','barangay_code','barangay_desc',$datarow[35]);
		 $getz = SelectDataWhere($dbtype,$dbLink,"ebpls_barangay",
                        "where barangay_code  = '$business_main_offc_barangay_code'");
                $business_main_offc_barangay_desc = FetchArray($dbtype,$getz);
                $business_main_offc_barangay_desc = $business_main_offc_barangay_desc[barangay_desc];
                                                                                                                             
?>
                <select id="business_main_offc_barangay_code" name="business_main_offc_barangay_code" onchange='getMainZoneList(this); flagchange(changeondin); _FRM.pro.value=0;' class=select200>
                <option value="<?php echo $business_main_offc_barangay_code; ?>"><?php echo $business_main_offc_barangay_desc; ?></option>
                </select>


    </td>
</tr>
<tr>
 <td align="right" valign="top" class='normal'> Office Zone: </td>
    <td align="left" valign="top" class='normal'>&nbsp; <?php 

if($branch[business_main_offc_zone_code]<>$business_main_offc_zone_code 
	and $business_main_offc_zone_code<>'') {
$branch[business_main_offc_zone_code]=$business_main_offc_zone_code;
} else {
$business_main_offc_zone_code=$branch[business_main_offc_zone_code];
}
                                                                                                 
                                                                                                 
//echo get_select_zone($dbLink,'business_main_offc_zone_code','ebpls_zone','zone_code','zone_desc',$branch[business_main_offc_zone_code],$business_main_offc_barangay_code);


//echo get_select_data($dbLink,'business_main_offc_zone_code','ebpls_zone','zone_code','zone_desc',$datarow[36]);

  $getz = SelectDataWhere($dbtype,$dbLink,"ebpls_zone",
                        "where zone_code  = '$business_main_offc_zone_code'");
                $business_main_offc_zone_desc = FetchArray($dbtype,$getz);
                $business_main_offc_zone_desc = $business_main_offc_zone_desc[zone_desc];
?>
            <select id="business_main_offc_zone_code" name="business_main_offc_zone_code" class=select200 onchange='flagchange(changeondin); _FRM.pro.value=0;'>
                <option value="<?php echo $business_main_offc_zone_code; ?>"><?php echo $business_main_offc_zone_desc; ?></option>
                </select>


    </td>

<?php
    $getzip = SelectDataWhere($dbtype,$dbLink,"ebpls_zip",
			"where upper = '$branch[business_main_offc_city_code]'");
        $owner_zip = FetchArray($dbtype,$getzip);
        $owner_zip = $owner_zip[zip_desc];
?>
                                                                                                 
            <td align="right" valign="top" class='normal'> Zip: </td>
            <td align="left" valign="top" class='normal'>&nbsp;
            <input type=text name='business_main_offc_zip_code' value='<?php echo $owner_zip; ?>' readonly
                maxlength=10 class='text180'>
                                                                                                 


</tr>


  <tr>
    <td colspan="4" align="right" valign="top" class='normal'  >&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" align="left" valign="top" class='normalbold'  >Business Economic Organization  </td>
  </tr>
  
<?php

	if ($datarow[biztype]=='') {
		$datarow[biztype]=$biztype;
	}
if ($changeondin<>1) {
	if($datarow[biztype]=='Main') {
		$mtag = 'selected';
	} else {
		$subsi=0;
		$ftag = 'selected';
		$datarow[biztype]='Franchise';
	}
} else {
	$datarow[biztype]=$biztype;
	 if($datarow[biztype]=='Main') {
                $mtag = 'selected';
        } else {
		$subsi=0;
                $ftag = 'selected';
                $datarow[biztype]='Franchise';
        }

}

	if ($subs=='on') {
                $ischeck='checked';
        } else {
                $ischeck='';
        }


if($subs=='' and $changeondin<>1) {	
	if ($datarow[subsi]=='') { //new record
		$datarow[subsi]=$subsi;
	}
	if ($datarow[subsi]==1) {
		$ischeck='checked';
	} elseif  ($datarow[subsi]<>1)  {
		$ischeck='';
	}
}

	if ($datarow[pcname]=='') {
                $datarow[pcname]=$pcname;
        }

	if ($datarow[pcaddress]=='') {
                $datarow[pcaddress]=$pcaddress;
        }


	if ($datarow[regname]=='') {
		$datarow[regname]=$regname;
	}

	if ($datarow[paidemp]==0) {
                $datarow[paidemp]=$paidemp;
        }

	
	if ($datarow[ecoorg]=='') {
		$datarow[ecoorg]=$ecoorg;
	}

	if ($datarow[ecoarea]=='') {
                $datarow[ecoarea]=$ecoarea;
        }


?>
	<tr>
	<td align=right size=10 class=normal>Registered Name :</td>
        <td align=left>
	<input type=text name=regname value='<?php echo $datarow[regname]; ?>' onchange='javascript:flagchange(changeondin);'></td>
	<td align=right size=5 class=normal>Paid Employees :</td>
        <td align=left>
	<input type=text name=paidemp value='<?php echo $datarow[paidemp]; ?>' onchange='javascript:flagchange(changeondin);'></td>
	</tr>
	<tr>
        <td align=right size=10 class=normal>Economic Organization :</td>
        <td align=left>
<?php
	echo get_select_data($dbLink,'ecoorg','ebpls_economic_org','economic_org_id','economic_org_desc',$datarow[ecoorg],'true','',"onchange='javascript:flagchange(changeondin);'");
?>

	</td>
        <td align=right size=5 class=normal>Economic Area :</td>
        <td align=left>
<?php
        echo get_select_data($dbLink,'ecoarea','ebpls_economic_area','economic_area_id','economic_area_desc',$datarow[ecoarea],'true','',"onchange='javascript:flagchange(changeondin);'");
?>
        </select>
	</td>
        </tr>

	<tr>
        <td  align=right size=10 class=normal>Business Type :</td>
        <td align=left>
        <select name=biztype class=select100 onchange='javascript:flagchange(changeondin);_FRM.submit();'>
                <option value='Main' <?php echo $mtag; ?>>Main</option>
                <option value='Franchise' <?php echo $ftag; ?>>Franchise</option>
        </select></td>
<?php
	if ($datarow[biztype]=='Main') {
	$flagger=0;
?>
	<input type=hidden name=subsi value=<?php echo $subsi; ?>>
	<td align=right size=10 class=normal>Subsidiary :</td>
        <td align=left><input type=checkbox name=subs <?php echo $ischeck; ?> 
	onchange='javascript:flagchange(changeondin); subsichange(subsi); _FRM.submit()'>
<?php
	}

	$mtag = '';
	$ftag='';
?>      
	</tr>
<?php
	if ($datarow[biztype]=='Franchise' || $ischeck=='checked' ) {
?>
	<tr>
	<td align=right size=10 class=normal>Name :</td>
	<td align=left><input type=text name=pcname value=<?php echo $datarow[pcname]; ?>></td>
	<td align=right size=10 class=normal>Address :</td>
        <td align=left><input type=text name=pcaddress value=<?php echo $datarow[pcaddress]; ?>></td>
<?php 
	} else {	
?>
<input type=hidden name=pcname value=''></td>
<td align=left><input type=hidden name=pcaddress value=''>
<?php
 }
?>
<input type=hidden name='pro' value=''>
<input type=hidden name='addbiz' value='<?php echo $addbiz; ?>'>
  <tr> 
	<td colspan="4" align="right" valign="top" class='normal'>&nbsp;</td>
  </tr>
  <tr>
      <td align="center" valign="top" class='header2' colspan=4 > <img src='images/spacer.gif' height=10 width=10></tr>
  <tr>
	<td align="center" valign="top" class='normal' colspan=4>
	<input type='<?php echo $butsave; ?>' name='_ADDNEW'  value='S A V E'  onClick='javascript:validate_add_new_business_application();'>
	<input type=hidden name=cancelme>
        <input type=button value='CANCEL' onClick="_FRM.cancelme.value=1; _FRM.submit();">
<!--	<input type=button value='CANCEL' onClick="history.go(-1)">-->
	<input type='reset' name='_RESET' onClick='' value='R E S E T' >
	</td>
   </tr>
</table>
</div>
</body>
</html>

<?php
function set_add_status($owner_id,$business_name,$business_id)
{
	//--- set the calling page forms
?>

<?php
}
	if($success_flag)
	{
		set_add_status($owner_id,$business_name , $ret_business_id);
	}
?>


