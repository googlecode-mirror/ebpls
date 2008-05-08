<?php
/*	Description: ebpls1221.php - one file that serves all other permit applications
	author: Vnyz Sofhia Ice

Modification History:
2006.02.02 DAP
2008.05.06: RJC Resolving errors reported in phperror.log
*/
//	- start page for owner search
require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");

require_once("ebpls-php-lib/utils/ebpls.search.funcs.php");
//--- get connection from DB
//$dbLink = get_db_connection();
global $ThUserData;
require_once "includes/variables.php";
require_once "class/BusinessEstablishmentClass.php";
include_once "class/PermitClass.php";
include_once "class/TaxpayerClass.php";
$ownerID = isset($owner_id) ? $owner_id : 0; //2008.05.06
if ($permit_type=='Motorized' || $permit_type=='Franchise') {
$checkrentype = @mysql_query("select * from ebpls_motorized_penalty where permit_type = '$permit_type'");
$checkrentype1 = @mysql_fetch_assoc($checkrentype);
if ($checkrentype1['renewaltype'] == '2' and $stat == "ReNew") {
	if ($nghji=="yuhsp") {
	$settonormal = UpdateQuery($dbtype,$dbLink,"ebpls_motorized_vehicles",
				"transaction='ReNew', retire = '0', paid='0'", 
				"motorized_operator_id = $owner_id and permit_type='$tag' and retire='5'");
	$settonormal1 = UpdateQuery($dbtype,$dbLink,"ebpls_motorized_vehicles",
				"transaction='ReNew', retire = '0', paid='0'", 
				"motorized_operator_id = $owner_id and permit_type='$tag' and transaction='New'");
	}
}

if ($checkrentype1['renewaltype'] == '1' and $stat == "ReNew" and $nghji =="yuhsp") {
	
	$ertyear = date('Y-m-d');
	$settonormal = UpdateQuery($dbtype,$dbLink,"ebpls_motorized_vehicles",
				"transaction='ReNew', paid='0'", 
				"motorized_operator_id = $owner_id and permit_type='$tag' and transaction='New' and create_ts < '$ertyear'");
	$settonormal = UpdateQuery($dbtype,$dbLink,"ebpls_motorized_vehicles",
				"transaction='ReNew', paid='0'", 
				"motorized_operator_id = $owner_id and permit_type='$tag' and transaction='ReNew' and create_ts < '$ertyear'");
	
}
}

$PROCESS = isset($PROCESS) ? $PROCESS : '';  //2008.05.06
if ($PROCESS=='ASSESSMENT') {
	$PROCESS='SAVE';
}

$addveh = isset($adaveh) ? $addveh : ''; //2008.05.06
if ($addveh=='transfer') {
	include "ebpls1223.php";
	$vekatt=0;
}

$vekatt = isset($vekatt) ? $vekatt :  0 ; //2008.05.06
if ($vekatt==1) {
	
	$buttag = 'Add';
	$buttag1='Cancel';

//check for duplicates

	$checkdup = SelectDataWhere($dbtype,$dbLink,$vehicle,
			"where  motorized_operator_id='$owner_id' and retire='0'");
	$tot = NumRows($dbtype,$checkdup);
if ($permit_type=='Franchise') { 
		if ($tot=='0') {
			$okid = 1;
		} else {
			$okid=2;
		}
} else {
		$okid = 1;
} 


	
		if ($okid==1) {
		//save to history
		$instemp = InsertQuery($dbtype,$dbLink,"vehicle_transfer","",
					"'','$mid','$old_owner_id','$owner_id',now(),'$usern'");
					
		//update vehicle
		$updateit = UpdateQuery($dbtype,$dbLink,$vehicle,
			 "motorized_operator_id  = $owner_id,permit_type='$permit_type',retire=0,transaction='$stat'",
			 " motorized_motor_id='$mid'");
			?>
			<body onload='javascript:("Vehicle Transferred");'</body>
			<?php
		}
		

	require_once "includes/form_add_mtoppermit.html";
	require_once "includes/form_add_middlepermit.php";
	require_once "includes/mtop.php";	
}

//2008.05.06 define undefined variables
$becancel = isset($becancel) ? $becancel : '';

 if ($becancel=="true") {
// 	$Biz = new BusinessEstablishment;

// 	
// 	//check tempassess
// 	
// // 	$Biz->GetBusinessAssess($owner_id,$business_id);
// // 	$assrec = $Biz->outnumrow;
// // 	
// // 	if ($assrec=='' || $assrec==0) {
// 	
// 	
// 	//check if have other payment record

// 	$Biz->GetPaymentRecord($business_id, $owner_id);
// 	$hrec = $Biz->outnumrow;

// 	if ($hrec=='' || $hrec==0) {

// 	$Biz->GetBusinessByID($business_id);
// 	$Biz->FetchBusinessArray($Biz->outselect);
// 	$transba = $Biz->outarray;
// 	$retireba = $transba[retire];
// 	$blacklistba = $transba[blacklist];
// 	//check if transfer
// 	if ($retireba==1) {
// 	$transfer_it=0;
// 	} else {
// 	$delme = DeleteQuery($dbtype,$dbLink,"ebpls_business_enterprise",
//                  "business_id=$business_id and owner_id=$owner_id and retire=0");
// 	}
// 	$delme = DeleteQuery($dbtype,$dbLink,"tempbusnature",
// 		 "business_id=$business_id and owner_id=$owner_id 
// 		  and active=1");
// 	

// 	$getcnt = SelectMultiTable($dbtype,$dbLink,
// 	"ebpls_owner a, ebpls_business_enterprise b","a.owner_id",
// 	"where a.owner_id=b.owner_id");
// 	$getcnt = FetchRow($dbtype,$getcnt);
// 	}

// 	
require_once "includes/body.php";
 require_once "includes/business_search.php";
 }

$linkpro = isset($linkpro) ? $linkpro : '';  //2008.05.06
if ($linkpro=='PAYMENT') {
	$PROCESS='SAVE';
}

$orderby = isset($orderby) ? $orderby : ''; //2008.05.06
if ($orderby<>'') {
	$orderby = stripslashes($orderby);
	$orderby = substr($orderby,1);
	$orderby = substr($orderby,0,-1);

}


if ($PROCESS=='SAVE' and $itemID_==1221 and $permit_type=="Business") {
                        $spermit = new NewPermit;
                        $spermit->GetBusinessPermit1($owner_id, $business_id, $permittable,date('Y'));
//                      $spermit->outnumrow;
                        if ($spermit->outnumrow==0) {
                        $sValues = "$business_id, $owner_id,'$currdate[year]',
                                now(), '$usern', '$stat', 0, 'For Assessment',
                                '$genpin', 1";
                        $ipermit = new NewPermit;
			$sv = "active=0";
			$sw = "business_id=$business_id and owner_id=$owner_id";
			$ipermit->UpdateBusinessPermit($sv,$sw,$permittable);
                        $ipermit->InsertBusinessPermit($sValues,$permittable);
                        }
                }

if ($PROCESS=='SAVE' and $itemID_==1221 and $permit_type<>'Fishery') {
	if ($permit_type<>'Fishery') {
                require_once "includes/process_save.php";
//                echo "<div align=center><font color=red>Succesfully Processed</font></div>";
                $mtopsearch='SEARCH';


		if ($permit_type=="Business" and $stat=='New') {
			$spermit = new NewPermit;
			$spermit->GetBusinessPermit($owner_id, $business_id, $permittable);
//			$spermit->outnumrow;
			include_once "class/BusinessEstablishmentClass.php";
			$npermit = new BusinessEstablishment;
			$npermit->UpdateBusinessID($owner_id,$business_id);
			if ($spermit->outnumrow==0) {
			$sValues = "$business_id, $owner_id,'$currdate[year]',
                                now(), '$usern', 'New', 0, 'For Assessment u8uuu',
                                '$genpin', 1";
			$ipermit = new NewPermit;
			$ipermit->InsertBusinessPermit($sValues,$permittable);

			}
		}	

        }
}
// 2008.05.06 set undefined variables
$mtopadd = isset($mtopadd) ? $mtopadd : '';  $addOwner = isset($addOwner) ? $addOwner : ''; $addveh = isset($addveh) ? $addveh : '';
$upOwner = isset($upOwner) ? $upOwner : ''; $com = isset($com) ? $com : '';
$addfee = isset($addfee) ? $addfee : ''; $delfee = isset($delfee) ? $delfee : ''; $mtopsearch = isset($mtopsearch) ? $mtopsearch : '';
$addbiz = isset($addbiz) ? $addbiz : ''; $clearveh = isset($clearveh) ? $clearveh : '';
$useboat = isset($useboat) ? $useboat : ''; $fishactive = isset($fishactive) ? $fishactive : '';
$mainfrm = isset($mainfrm) ? $mainfrm : ''; $subfish = isset($subfish) ? $subfish : '';

// display search form
$addEmp = isset($addEmp) ? $addEmp : ''; //2008.05.06
if ($mtopadd<>' A D D ' and  $addOwner<>'ADD' and $addveh<>'Add' and 
    $upOwner<>'UPDATE' and $com<>'Select' and $com<>'Edit' and $mtopsearch<>'SEARCH' and
    $com<>'Delete' and $addveh<>'Edit' and $com<>'ReNew' and $addEmp<>'A D D' and 
    $addfee<>'Add' and $delfee<>'Delete' and $com<>'Drop' and $com<>'New' and 
    $addbiz<>'Save' and $addbiz<>'Select' and $clearveh<>'Clear' and $addbiz<>'update' and
    $clearveh<>'Cancel' and $addveh<>'Save' and $useboat<>'Boat Registration' and
    $fishactive<>'Add' and $addbiz<>'busline' and $mainfrm<>'Main' and $subfish<>'PROCESS') {
//if payment is made
	if ($PROCESS=='SAVE') {
		if ($permit_type<>'Fishery') {
		require_once "includes/process_save.php";
		echo "<div align=center><font color=red>Succesfully Processed</font></div>";
		$mtopsearch='SEARCH';
		}
	}
print "";
}

if ($permit_type=='Fishery' and $subfish=='PROCESS' and $useboat=='' and $fishactive=='') {
echo "<div align=center><font color=red>Succesfully Processed</font></div>";
$mtopsearch='SEARCH';
$useboat='';
}
if ($permit_type=='Fishery' and $reg_close=='Close') {
$useboat='';
$mainfrm='Main';
}
if ($permit_type=='Fishery' and $useboat<>'' ) {
	$owner_id=$ownerID;
        require_once "includes/form_add_mtoppermit.html";
        require_once "includes/regboat.php";
        require_once "includes/fishactivity.php";
		require_once "includes/mtop.php";
		require_once "includes/form_add_lastpermit.html";
}

if ($fishactive=='Add' and $permit_type=='Fishery') {

	if ($actcom=='Delete') {
		$de = DeleteQuery($dbtype,$dbLink,"fish_assess","ass_id=$assid");
//		$de = DeleteQuery($dbtype,$dbLink,"fish_activity","fish_id=$fishid");
	}
		$owner_id=$ownerID;
		require_once "includes/form_add_mtoppermit.html";
                require_once "includes/boatreg.php";
                require_once "includes/fishactivity.php";
		require_once "includes/mtop.php";
		require_once "includes/form_add_lastpermit.html";


}

if ($mainfrm=='Main') {
	if ($permit_type=='Business') {
		require_once("includes/form_bus_permit.php");
	} elseif ($permit_type=='Franchise' || $permit_type=='Motorized') {
		$owner_id=$ownerID;
		require_once "includes/form_add_mtoppermit.html";
		if ($owner_id<>'') {
		require_once "includes/form_add_middlepermit.php";
		require_once "includes/mtop.php";
		}
	} elseif ($permit_type=='Occupational') {
		$owner_id=$ownerID;
		
                require_once "includes/form_add_mtoppermit.html";
            if ($owner_id<>'') {    
                require_once "includes/form_add_midoccu.php";
				require_once "includes/mtop.php";
			}
    	} elseif ($permit_type=='Peddlers') {
	    	$owner_id=$ownerID;
	    	
	    	require_once "includes/form_add_mtoppermit.html";
	    	if ($owner_id<>'') {
				require_once "includes/form_add_midpeddler.html";
				require_once "includes/mtop.php";
			}
	} elseif ($permit_type=='Fishery') {
		$owner_id=$ownerID;
		require_once "includes/form_add_mtoppermit.html";
		if ($owner_id<>'') {
		if ($useboat=='') {
		require_once "includes/boatreg.php";
		} else {
		require "includes/regboat.php";
		}
		require_once "includes/fishactivity.php";
		 require_once "includes/mtop.php";
		 require "includes/form_add_lastpermit.html";
	}
	}
	

} elseif ($mtopadd=='ADD') { //add new owner
	require_once("includes/form_mtop_owner.php");

} elseif ($addveh=='Add' and $linkpro<>'PAYMENT') { //add new vehicle (for motorized and franchise use only)

	$totvec=SelectDataWhere($dbtype,$dbLink,"ebpls_motorized_vehicles",
			"where motorized_operator_id = $owner_id and  
			permit_type='$tag' and retire=0");
	$tot = NumRows($dbtype,$totvec);

//check for duplicates
if ($vekatt==0) {
	$checkdup = SelectDataWhere($dbtype,$dbLink,$vehicle,
			"where motorized_motor_no='$mnum'");
	$checkdup = NumRows($dbtype,$checkdup);
	if ($checkdup>0) {
		?>
		<body onload='alert("Existing Motor Number Found");_FRM.mnum.focus();
			_FRM.mnum.select();'></body>
		<?php
	} else {
	
	$checkdup = SelectDataWhere($dbtype,$dbLink,$vehicle,
			"where
			 motorized_chassis_no='$cnum' ");
	$checkdup = NumRows($dbtype,$checkdup);
		if ($checkdup>0) {
			?>
			<body onload='alert("Existing Chassis Number Found");_FRM.cnum.focus();
			_FRM.cnum.select();'></body>
			<?php
		} else {
			
	$checkdup = SelectDataWhere($dbtype,$dbLink,$vehicle,
			"where 
			 motorized_plate_no='$pnum' ");
	$checkdup = NumRows($dbtype,$checkdup);
			if ($checkdup>0) {
				?>
				<body onload='alert("Existing Plate Number Found");_FRM.pnum.focus();
			_FRM.pnum.select();'></body>
				<?php
			} else {
	
	
	$checkdup = SelectDataWhere($dbtype,$dbLink,$vehicle,
			"where 
                         motorized_body_no='$bnum' ");
	$checkdup = NumRows($dbtype,$checkdup);
	
				if ($checkdup>0) {
					?>
					<body onload='alert("Existing Body Number Found");_FRM.bnum.focus();
			_FRM.bnum.select();'></body>
					<?php
				} else {
	
	$checkdup = SelectDataWhere($dbtype,$dbLink,$vehicle,
			"where 
			 lto_number='$ltoreg' ");
	$checkdup = NumRows($dbtype,$checkdup);
					if ($checkdup>0) {
						?>
						<body onload='alert("Existing LTO Number Found");_FRM.ltoreg.focus();
			_FRM.ltoreg.select();'></body>
						<?php
					} else {
	
	$checkdup = SelectDataWhere($dbtype,$dbLink,$vehicle,
			"where  cr_number='$cro'");
	$checkdup = NumRows($dbtype,$checkdup);
	
					if ($checkdup>0) {
		?>
		<body onload='alert("Existing Certificate of Registration Number Found");_FRM.cro.focus();
			_FRM.cro.select();'></body>
		<?php
					}
					
					
				}
			}
		}
	}
}
	
} else {
	$checkdup=0;
}

if ($checkdup==0) {	

	
	if ($tag=='Franchise') { 
		if ($tot=='0') {

		$slash='add';
		require_once "includes/stripslash.php";	
		$instemp = InsertQuery($dbtype,$dbLink,$vehicle,
			"(motorized_operator_id, motorized_motor_model, 
			motorized_motor_no, motorized_chassis_no, motorized_plate_no, 
			motorized_body_no ,admin,  status, create_ts, updated_ts,
			permit_type, route, linetype, body_color, lto_number, cr_number)",
			"$owner_id,'$mmodel', '$mnum', '$cnum', 
			'$pnum', '$bnum','$usern', 1, now(), now(),'$tag', 
			'$route', '$ltype', '$bcolor', '$ltoreg', '$cro'"); 
		
		$owner_id=$ownerID;
		require_once "includes/form_add_mtoppermit.html";
		$mid='';
		$mmodel='';
		$mnum='';
		$cnum='';
		$pnum='';
		$bnum='';
		$route='';
		$ltype='';
		$bcolor='';
		$ltoreg='';
		$cro='';
		require_once "includes/form_add_middlepermit.php";
			?>
<body onload='javascript:AddRec();'</body>
<?php
		
		
		
		} else {
			$owner_id=$ownerID;
		require_once "includes/form_add_mtoppermit.html";
		$mid='';
		$mmodel='';
		$mnum='';
		$cnum='';
		$pnum='';
		$bnum='';
		$route='';
		$ltype='';
		$bcolor='';
		$ltoreg='';
		$cro='';
		require_once "includes/form_add_middlepermit.php";
		?>
		<body onload='javascript:alert("Cannot Add More Vehicles");'></body>
		<?php
		}
	} else {

	$slash='add';
	require_once "includes/stripslash.php";
	$ngyear = date('Y');
	$updateit = DeleteQuery($dbtype,$dbLink,$permittable,
					"owner_id = $owner_id and for_year = '$ngyear' and transaction = '$stat'");
	$instemp = InsertQuery($dbtype,$dbLink,$vehicle,
			"(motorized_operator_id, motorized_motor_model, 
			motorized_motor_no, motorized_chassis_no, motorized_plate_no, 
			motorized_body_no ,admin,  status, create_ts, updated_ts,permit_type, 
			route, linetype, body_color, lto_number, cr_number,transaction)", 
			"$owner_id,'$mmodel', '$mnum', '$cnum', '$pnum', 
			'$bnum','$usern', 1, now(), now(),
			'$tag', '$route', '$ltype', '$bcolor', '$ltoreg', '$cro', 'New'");
	$owner_id=$ownerID;
	require_once "includes/form_add_mtoppermit.html";
	$mid='';
	$mmodel='';
	$mnum='';
	$cnum='';
	$pnum='';
	$bnum='';
	$route='';
	$ltype='';
	$bcolor='';
	$ltoreg='';
	$cro='';
	if ($stat == 'ReNew') {
		$fghjyear = date('Y');
		$updatekonato =@mysql_query("update ebpls_motorized_operator_permit set paid = '0', steps = 'For Payment' where owner_id = '$owner_id' and motorized_operator_permit_application_date like '$fghjyear%'");
	}
	require_once "includes/form_add_middlepermit.php";
	?>
<body onload='javascript:AddRec();'</body>
<?php
	
	
	}
require_once "includes/mtop.php";
} else { //have duplicate
?>
<body onload='javascript://ExistRec();'</body>
<?php
include "includes/form_add_mtoppermit.html";
include "includes/form_add_middlepermit.php";
include "includes/mtop.php";
}

if ($permit_type<>'Business' and $permit_type<>'' and $owner_id<>'') {
	$g = SelectDataWhere($dbtype,$dbLink,$permittable,
			"where owner_id = $owner_id and
	                active = 1 and transaction = '$stat'");
	$g = NumRows($dbtype,$g);
if ($stat<>'New') {
        $getpin = SelectDataWhere($dbtype,$dbLink,$permittable,
			"where owner_id = $owner_id limit 1");
        $pin = FetchArray($dbtype,$getpin);
        $pin = $pin[pin];
        	if ($g==0) {
		        $updateit = UpdateQuery($dbtype,$dbLink,$permittable,
			"active = 0", "owner_id = $owner_id");

		        if ($permit_type=='Occupational' and $frmedit<>'yes') {
				
				if ($employer_business>0) {
			 $insertemp = InsertQuery($dbtype,$dbLink,"ebpls_occupational_permit",
			              "(owner_id, occ_permit_application_date, 
					occ_position_applied, for_year, paid,
					transaction, steps, pin, active, business_id)",
			              "$owner_id, now(), '$pos_app',
			              '$currdate[year]', 0,'$stat','For Payment',
				      '$pin', 1,$employer_business");
				} else {
					?><body onload='alert("Please select employer."); parent.location="index.php?part=4&itemID_=1221&permit_type=Occupational&stat=<?php echo $stat; ?>&owner_id=<?php echo $owner_id;?>&upOwner=UPDATE&business_id=<?php echo $business_id;?>&busItem=Occupational";'></body>
	           
				<?php
				}
			} else {
			 $inserttemp = InsertQuery($dbtype,$dbLink,$permittable,
		                       "$incode, owner_id, for_year, $appdate,
		                        paid,transaction, steps, pin, active)",
		                       "'$permitcode', $owner_id, '$currdate[year]',
		                        now(), 0,'$stat', 'For Payment', '$pin', 1)");
			}	
        	}
}
}
}
$page = isset($page) ? $page : '';  //2008.05.06 Define undefined
$howmany = isset($howmany) ? $howmany : '';

if ($mtopsearch=='SEARCH') { //search existing
	if ($page<>'') {
		require_once "includes/business_search.php";
	} else {
		 if ($stat=='New') {
	          if ($business_capital_investment<>'' and $business_capital_investment<>'0') 
		  {
			require_once "includes/form_bus_permit.php";
                  }
                 } elseif ($stat=='ReNew') {
                  if ($gross_sale<>'' and $gross_sale<>'0') {
         	        require_once "includes/form_bus_permit.php";
                  }
		 }
	if ($howmany=='') {
		require_once "includes/business_search.php";
	}
	$search_businesstype = isset($search_businesstype) ? $search_businesstype : 0;  //2008.05.06 Define undefined
	
	if ($search_businesstype==1 || $permit_type<>'s') {
		if ($PROCESS=='SAVE') {
			 $xc=0;
			 $i = 1;
			if ($business_id>0 and $owner_id>0) {
			while ($xc<$howmany) {

				if ($x[$i]=='on') {
					$s=1;
				} else {
					$s=0;
				}

       			$upreq = UpdateQuery($dbtype,$dbLink,"havereq",	"active=$s",
				"owner_id=$owner_id and business_id=$business_id 
				and reqid='$colre[$i]'");
			$xc++;
			$i++;
			}

			if ($go_assess==1) {
				$staxfee = SelectMultiTable($dbtype,$dbLink,"ebpls_buss_preference",
		"sassess, predcomp","");
$prefset = FetchArray($dbtype,$staxfee);
$sassesscomplex = $prefset['sassess']; // per estab
$predcomp = $prefset['predcomp'];

if ($predcomp==1 and $stat=='New') {
	
	 $getnat =SelectDataWhere($dbtype,$dbLink,"tempbusnature",
                                  " where owner_id=$owner_id and business_id=$business_id and active =1
                                  and transaction='$stat' and date_create like '$yearnow%'");
             $cntnat =mysql_num_rows($getnat);
             
              $getnat =SelectDataWhere($dbtype,$dbLink,"tempbusnature",
                                  " where owner_id=$owner_id and business_id=$business_id and active =0
                                  and transaction='$stat' and date_create like '$yearnow%'");
             $orignat =mysql_num_rows($getnat);
             
            $getnat =SelectDataWhere($dbtype,$dbLink,"tempbusnature",
                                  " where owner_id=$owner_id and business_id=$business_id
                                  and transaction='$stat' and date_create like '$yearnow%'");
             $nat =mysql_num_rows($getnat);
             
             if ($orignat=='') {
	             $orignat=$cntnat;
             }
             
            
             if ($orignat < $nat) {
					$newpred='1';
					$noregfee=1;
					
			 }
			
}
				$getgross =SelectDataWhere($dbtype,$dbLink,"tempbusnature",
                                  " where owner_id=$owner_id and business_id=$business_id and active =1
                                  and date_create like '$yearnow%'");
             $gross =mysql_num_rows($getgross);
             
             if ($gross==0) {
	             $delperm = mysql_query("delete from ebpls_business_enterprise_permit where
	             					owner_id=$owner_id and business_id=$business_id and active =1
                                  and transaction='$stat' and application_date like '$yearnow%'") or die ("!");
                 $updperm = mysql_query("update ebpls_business_enterprise_permit set active=1 where
	             					owner_id=$owner_id and business_id=$business_id 
                                  and transaction='$stat' order by  business_permit_id desc limit 1")or die (mysql_error());
	             
?>
<body onload="alert('You have not entered gross sales. Cannot proceed to assessment.'); parent.location='index.php?part=4&class_type=Permits&itemID_=1221&owner_id=<?php echo $owner_id; ?>&com=<?php echo $stat; ?>&permit_type=Business&stat=<?php echo $stat; ?>&business_id=<?php echo $business_id; ?>&busItem=Business&addbiz=update&bizcom=Select&ngros=1';"> </body>
<?php
			} else {
				
				
?>
<body onload="parent.location='index.php?part=4&newpred=<?php echo $newpred; ?>&noregfee=<?php echo $noregfee; ?>&class_type=Permits&itemID_=4212&owner_id=<?php echo $owner_id; ?>&com=edit&permit_type=<?php echo $permit_type; ?>&stat=<?php echo $stat; ?>&business_id=<?php echo $business_id; ?>&busItem=Business&istat=<?php echo $stat; ?>'"> </body>
<?php
			}
			}

			}
		}
	$transfer_it  = isset($transfer_it) ? $transfer_it : 0 ; //2008.05.06 Define undefined
	if ($transfer_it==1) {
			 //save to trans_his
                        $tra = InsertQuery($dbtype,$dbLink,"trans_his","",
                        "'',$ret[1], $owner_id, $business_id, now()");
                                                                                                 
                                                                                                 
                       //update bus_enter
                        $upbiz = UpdateQuery($dbtype,$dbLink,"ebpls_business_enterprise",
                                "owner_id = $owner_id, retire = 0",
                                "business_id = $business_id");
                        $res = InsertQuery($dbtype,$dbLink,"ebpls_business_enterprise_permit",
                                "(business_id, owner_id, for_year,application_date,
                                input_by, transaction, paid, steps, pin, active)",
                                "$business_id, $owner_id,'$currdate[year]',
                                now(), '$usern', 'New', 0, 'For Assessment',
                                '$genpin', 1");
	}

if ($permit_type<>'Business') {
	
//di ko alam kung bakit nid to dito
require_once "includes/business_search.php";	
///////
if ($owner_id=='') {
	$owner_id=0;
}
	$g = SelectDataWhere($dbtype,$dbLink,$permittable,
			"where owner_id = $owner_id and
			 active = 1 and transaction = '$stat'");
	$g = NumRows($dbtype,$g);

	if ($PROCESS=='SAVE') {  //if ($g==0) { 
	
		if ($permit_type=='Occupational') { 
			if ($frmedit<>'yes') {

				
				if ($employer_business>0) {
			$updateit = UpdateQuery($dbtype,$dbLink,$permittable,
					"active = 0 and released = 0",
		                        "owner_id = $owner_id");
			$ngyear = date('Y');
			$updateit = DeleteQuery($dbtype,$dbLink,$permittable,
					"owner_id = $owner_id and for_year = '$ngyear' and transaction = '$stat'");
			require_once "includes/genpin.php";
	                 $insertemp = InsertQuery($dbtype,$dbLink,"ebpls_occupational_permit",
			                "(owner_id, occ_permit_application_date, 
					occ_position_applied, for_year, paid,
					transaction, steps, pin, 
					active, business_id)",
			                "$owner_id, now(), '$pos_app','$currdate[year]', 
					0,'$stat', 'For Payment', '$genpin', 
					1, $employer_business");
				} else {
					?><body onload='alert("Please select employer."); parent.location="index.php?part=4&itemID_=1221&permit_type=Occupational&stat=<?php echo $stat; ?>&owner_id=<?php echo $owner_id;?>&upOwner=UPDATE&business_id=<?php echo $business_id;?>&busItem=Occupational";'></body>
	           
				<?php
				}
			}
	        } else {
				$ngyear = date('Y');
			$updateit = UpdateQuery($dbtype,$dbLink,$permittable,
					"active = 0 and released = 0",
		                        "owner_id = $owner_id");
			$updateit = DeleteQuery($dbtype,$dbLink,$permittable,
					"owner_id = $owner_id and for_year = '$ngyear' and transaction = '$stat'");
			if ($permit_type=='Peddlers') {
			
				require_once "includes/genpin.php";
			$inserttemp = InsertQuery($dbtype,$dbLink,$permittable,
                                        "($incode, owner_id,merchandise_sold, peddlers_business_name,
					 for_year, $appdate,
                                        paid,transaction, steps, pin, active)",
                                        "'$permitcode', $owner_id,'$merchandise','$peddler_bus', '$currdate[year]',
                                        now(), 0,'$stat', 'For Payment', '$genpin', 1");
		
			} else {
				require_once "includes/genpin.php";	
			$inserttemp = InsertQuery($dbtype,$dbLink,$permittable,
		                        "($incode, owner_id, for_year, $appdate,
		                        paid,transaction, steps, pin, active)",
		                        "'$permitcode', $owner_id, '$currdate[year]',
		                        now(), 0,'$stat', 'For Payment', '$genpin', 1");
	                
			}
		}

			if ($permit_type=='Motorized' and $linkpro<>'PAYMENT') {
		?>
			<body onload="AddRec(); parent.location='index.php?part=4&class_type=Permits&itemID_=1221&permit_type=Motorized&busItem=Motorized&mtopsearch=SEARCH';"></body>
		<?
			}

			if ($permit_type=='Franchise' and $linkpro<>'PAYMENT') {
                ?>
                        <body onload="AddRec(); parent.location='index.php?part=4&class_type=Permits&itemID_=1221&permit_type=Franchise&busItem=Franchise&mtopsearch=SEARCH';"></body>
                <?
                        }

			if ($permit_type=='Peddlers' and $linkpro<>'PAYMENT') {
                ?>
                        <body onload="AddRec(); parent.location='index.php?part=4&class_type=Permits&itemID_=1221&permit_type=Peddlers&busItem=Peddlers&mtopsearch=SEARCH';"></body>
                <?
                        }

                        if ($permit_type=='Occupational' and $linkpro<>'PAYMENT') {
                ?>
                        <body onload="AddRec(); parent.location='index.php?part=4&class_type=Permits&itemID_=1221&permit_type=Occupational&busItem=Occupational&mtopsearch=SEARCH';"></body>
                <?
                        }
			if ($permit_type=='Fishery' and $linkpro<>'PAYMENT') {
			
                ?>
                        <body onload="AddRec(); parent.location='index.php?part=4&class_type=Permits&itemID_=1221&permit_type=Fishery&busItem=Fishery&mtopsearch=SEARCH';"></body>
                <?
                        }

	}
}

$business_id = isset($business_id) ? $business_id : 0 ; //2008.05.06 Define undefined
//add code for renew
if ($business_id<>'') {
$g = SelectDataWhere($dbtype,$dbLink,$permittable,
		"where owner_id = $owner_id and	business_id = $business_id 
		and active = 1 and transaction = '$stat'");
$g = NumRows($dbtype,$g);
if ($stat<>'New' || $stat<>'' ) {
	$getpin = SelectDataWhere($dbtype,$dbLink,$permittable,
			"where owner_id = $owner_id and business_id=$business_id limit 1");
	$pin = FetchArray($dbtype,$getpin);
//	$genpin = $pin[pin];

	if ($g==0) {
		if ($stat=='') {
			$stat='New';
		}

	$getexist = SelectDataWhere($dbtype,$dbLink,$permittable,
			"where owner_id = $owner_id and business_id=$business_id and
			transaction='$stat' and active =1");

	$havef = NumRows($dbtype,$getexist);
		if ($havef==0) {
			$updateit = UpdateQuery($dbtype,$dbLink,$permittable,
			"active = 0", "owner_id = $owner_id and 
			business_id=$business_id");
			 $res = InsertQuery($dbtype,$dbLink,$permittable,
				"(business_id, owner_id, for_year
                                ,application_date,input_by, transaction, 
				paid, steps, pin, active)",
                                "$business_id, $owner_id,
                                '$currdate[year]', now(), '$usern', '$stat', 0,
                                'For Assessment ','$genpin', 1");
			$upbusg = UpdateQuery($dbtype,$dbLink,"bus_grandamt",
				"active=0","owner_id = $owner_id and
				business_id=$business_id");
		}

	
	}
}

		if ($business_capital_investment<>'' or $gross_sales<>'') {
				 if ($PROCESS<>'SAVE') {
					require_once "includes/form_bus_permit.php";
				 } else {
					require_once "includes/business_search.php";
				 }
		}elseif ($search_businesstype==4 and $permit_type=='Business') {
		        require_once "includes/assessment_search.php";
		} elseif ($search_businesstype==5 and $permit_type=='Business') {
		        require_once "includes/approve_search.php";
		} elseif ($search_businesstype==2 || $permit_type<>'') {
                        require_once "includes/payment_search.php";
		} elseif ($search_businesstype==3 || $permit_type<>'') {
                        require_once "includes/release_search.php";
		}

}
}
}
} elseif ($addOwner=='ADD' || $upOwner=='UPDATE') { //save to database new owner
$usern = (strtoupper($ThUserData['username']));
$create='Yes';

if ($addOwner=='ADD') {
$getcnt = SelectMultiTable($dbtype,$dbLink,$owner,"max(owner_id)","");
$getcnt = FetchRow($dbtype,$getcnt);
if ($getcnt[0]>0) {
//$owner_id = $getcnt[0] + 1;
} else {
$getcnt = SelectDataWhere($dbtype,$dbLink,$owner,"");
$getcnt = NumRows($dbtype,$getcnt);
//$owner_id = $getcnt + 1;
}
}
$idcnt=$owner_id;
$slash='add';
require_once "includes/stripslash.php";

// save to table ebpls_mtop_owner then go to new mtop
	if ($addOwner=='ADD') {
	
	} elseif ($upOwner=='UPDATE') {

$slash='add';
require_once "includes/stripslash.php";

	if ($permit_type=='Business') {
        require_once("includes/form_bus_permit.php");
	} else {
	if ($permit_type==Franchise || $permit_type==Motorized) {

		require 'includes/variables.php';
                $getcnt = SelectMultiTable($dbtype,$dbLink,$owner_id,"max(owner_id)","");
                $getcnt = FetchRow($dbtype,$getcnt);

		$exis = SelectDataWhere($dbtype,$dbLink,$permittable,
			"where owner_id=$owner_id and active=1");
		$exis=NumRows($dbtype,$exis);
		if ($exis==0) {

		$inserttemp = InsertQuery($dbtype,$dbLink,$permittable,
	                        "($incode, owner_id, for_year, $appdate,
	                        paid,transaction, steps, pin, active)",
	                        "'$permitcode', $owner_id, '$currdate[year]',
	                        now(), 0,'$stat', 'For Payment', '$genpin', 1");
		}
			$owner_id=$ownerID;
	        require_once "includes/form_add_mtoppermit.html";
                require_once "includes/form_add_middlepermit.php";
                require_once "includes/mtop.php";
        	} elseif ($permit_type==Occupational) {
	        	$owner_id=$ownerID;
                require_once "includes/form_add_mtoppermit.html";
                require_once "includes/form_add_midoccu.php";
                require_once "includes/mtop.php";
	        } elseif ($permit_type==Fishery) {
		} elseif ($permit_type==Peddlers) {
		}
	}
}

	if ($permit_type<>'Business') {
		require_once "includes/genpin.php";
		$ch = SelectDataWhere($dbtype,$dbLink,$permittable,"where owner_id=$owner_id");
		$ch = NumRows($dbtype,$ch);
			if ($ch==0) {
				if ($permit_type=='Occupational' and $frmedit<>'yes') {
					if ($employer_business=='') {
						$employer_business=0;
					}
			            $insertemp = InsertQuery($dbtype,$dbLink,$permittable,
			                "(owner_id, occ_permit_application_date, 
					occ_position_applied, for_year, paid,
					transaction, steps, pin, active, business_id)",
			                "$owner_id, now(), '$pos_app', '$currdate[year]',
					 0,'$stat', 'For Payment', '$genpin', 1, 
					 $employer_business");
			        } else {
//von
					$inserttemp = InsertQuery($dbtype,$dbLink,$permittable, 
					"($incode, owner_id, for_year, $appdate, 
					paid,transaction, steps, pin, active)",
					"'$permitcode', $owner_id, '$currdate[year]', 
					now(), 0,'$stat', 'For Payment', '$genpin', 1"); 
				}
			}
	}                                                                                                 
require 'includes/variables.php';
$getcnt = SelectMultiTable($dbtype,$dbLink,$owner,"max(owner_id)","");
$getcnt = FetchRow($dbtype,$getcnt);
$owner_id = $getcnt[0];

	if ($tag=='Occupational' and $permit_type=='Occupational') {
		$slash='update';
		require_once "includes/stripslash.php";
		$owner_id=$ownerID;
		require_once "includes/form_add_mtoppermit.html";
		require_once "includes/form_add_midoccu.php";

	} elseif ($tag=='Business') {
		require_once "includes/form_bus_permit.php";

	} elseif ($tag=='Peddlers') {
		$owner_id=$ownerID;
		require_once "includes/form_add_mtoppermit.html";
		require_once "includes/form_add_midpeddler.html";

	} elseif ($tag=='Fishery') {
		$owner_id=$ownerID;
		require_once "includes/form_add_mtoppermit.html";
		if ($useboat=='') {
                require_once "includes/boatreg.php";
                } else {
                require "includes/regboat.php";
                }

		require_once "includes/fishactivity.php";

	} elseif ($tag=='Motorized' || $tag=='Franchise') {
		$owner_id=$ownerID;
		require_once "includes/form_add_mtoppermit.html";
		require_once "includes/form_add_middlepermit.php";
	}

require_once "includes/mtop.php";

} elseif ($addbiz=='Save' || $addbiz=='Select' || $addbiz=='busline' || $addbiz=='update') {
	   if ($addbiz=='Select') {
       		  require_once "includes/genpin.php";
           }

	$upit = isset($upit) ? $upit : ''; 
	if ($addbiz=='Save') {

 		if ($business_id=='')  $business_id=0;
 
		//search existing
		$exist=SelectDataWhere($dbtype,$dbLink,"ebpls_business_enterprise", 
					"where business_id='$business_id'");
		$getit=NumRows($dbtype,$exist);
		if ($getit==0) { 

			//filter search existing

			$filters=SelectDataWhere($dbtype,$dbLink,"ebpls_business_enterprise",
					"where business_name='$business_name' and
					business_branch= '$business_branch'");
			$getfil=NumRows($dbtype,$filters);
			if ($getfil==0) {
				$slash='add';
				require_once "includes/stripslash.php";

				$getsd=SelectMultiTable($dbtype,$dbLink,
					"ebpls_business_enterprise","max(business_id)","");
				$bid =FetchRow($dbtype,$getsd);
				$business_id=$bid[0];
				require_once "includes/genpin.php";
				$res = InsertQuery($dbtype,$dbLink,$permittable,
				"(business_id, owner_id, for_year,
				application_date,input_by, transaction, 
				paid, steps, pin, active)", 
				"$business_id, $owner_id,
			        '$currdate[year]', now(), '$usern', '$stat', 0,
				'For Assessment','$genpin', 1");

			} else {
			?>
			<body onload='javascript:alert
			("Existing Business Name and Branch Found");'> </body>
			<?php
			include"ebpls1224.php";
			$fd=1;
			}//end getfil

		}//end exisit
	
	} elseif ($addbiz=='update' and $upit==1224 and $addline<>1 ) { //update biz



		  //filter search existing
	if ($business_id=='') {
		$business_id=0;
	}                                                                                                               
                        $filters=SelectDataWhere($dbtype,$dbLink,
				"ebpls_business_enterprise",
				"where business_name='$business_name' and
		                business_branch= '$business_branch' and 
				business_id<>$business_id and retire<>1");
                        $getfil = NumRows($dbtype,$filters);
                        if ($getfil==0) {
				$slash='add';
				require_once "includes/stripslash.php";
		 	} else {
			?>
                        <body onload='javascript:alert
                        ("Existing Business Name and Branch Found");'> </body>
                        <?php
                        include"ebpls1224.php";
			$fd=1;
                        }//end getfil

	}
$bizcom = isset($bizcom) ? $bizcom : ''; //2008.05.08
if ($bizcom=='Delete') {

	$filters=SelectDataWhere($dbtype,$dbLink,$tempbiz,"where tempid=$natcode order by tempid desc");
	$gn = FetchArray($dbtype,$filters);
	$bco = $gn['bus_code'];
	$ow = $gn['owner_id'];
	$bi = $gn['business_id'];

	$delit = DeleteQuery($dbtype,$dbLink,$tempbiz,"tempid=$natcode");

	$delit = UpdateQuery($dbtype,$dbLink,$tempbiz,"active=1",
			"bus_code='$bco' and owner_id='$ow' and business_id='$bi' order by tempid desc limit 1");
	
//	$delit = DeleteQuery($dbtype,$dbLink,$tempbiz,"tempid=$natcode");
	$bizcom='';
//	$de = DeleteQuery($dbtype,$dbLink,"tempassess","owner_id = $owner_id and
//		business_id = $business_id");
}
$fd = isset($fd) ? $fd : 0; //2008.05.08
if ($fd<>1) {
	if ($permit_type=='Business') {
		require_once "includes/form_bus_permit.php";
		$fd=0;
	}
} else {
	$fd=0;
}
//update owner d;etails
// links clicked
} elseif ($com=='ReNew' || $com=='Drop' || $com=='New') {

	$getown= SelectMultiTable($dbtype,$dbLink,$owner,
			"owner_first_name, owner_middle_name,
			 owner_last_name, owner_gender","where owner_id=$owner_id");
	$res=FetchRow($dbtype,$getown);
	$owner_first_name=$res[0];
	$owner_middle_name=$res[1];
	$owner_last_name=$res[2];
	$owner_gender=$res[3];
	$buttag1='Clear';
	$slash='update';
	require_once "includes/stripslash.php";


	$checkit = SelectMultiTable($dbtype,$dbLink,$permittable,
			"distinct $permittable.paid","
			 where active=1 and owner_id = $owner_id order by $appdate desc");
	$check = FetchRow($dbtype,$checkit);

if ($com=='New' || $com=='ReNew' || $com=='Drop') {
	//checks if permit already paid --- change 15 to 1 if u lyk
	
	if ($check[0]==15) {
		
		require_once "includes/form_mtop_search.html";
		
		?>
		<body onload='javascript:alert("Permit Already Paid");'></body>
		<?php
	} else {
		if ($tag=='Occupational' and $permit_type=='Occupational') {
		$slash='update';
		require_once "includes/stripslash.php";
		$owner_id=$ownerID;
		require_once "includes/form_add_mtoppermit.html";
		require_once "includes/form_add_midoccu.php";
		} elseif ($permit_type=='Business') {
		require_once "includes/form_bus_permit.php";
		} elseif ($permit_type=='Peddlers') {
		$getd = SelectMultiTable($dbtype,$dbLink,$permittable,
				"merchandise_sold, peddlers_business_name",
				"where owner_id=$owner_id and active=1");
		$getn = FetchRow($dbtype,$getd);
		$merchandise = $getn[0];
		$peddler_bus = $getn[1];
	
		$slash='update';
		require_once "includes/stripslash.php";
		$owner_id=$ownerID;
		require_once "includes/form_add_mtoppermit.html";
		require_once "includes/form_add_midpeddler.html";
		} elseif ($permit_type=='Fishery') {
			$owner_id=$ownerID;
		require_once "includes/form_add_mtoppermit.html";
		if ($useboat=='') {
                require_once "includes/boatreg.php";
                } else {
                require "includes/regboat.php";
                }

		require_once "includes/fishactivity.php";
		} else {
			$owner_id=$ownerID;
		require_once "includes/form_add_mtoppermit.html";
		
			if ($com<>'Drop') {
				require_once "includes/form_add_middlepermit.php";
			}

		}
	if ($permit_type<>'Business') {
	require_once "includes/mtop.php";
		if ($permit_type=='Fishery') {
			 require "includes/form_add_lastpermit.html";
		}
	}
	}

} else { 
	if ($tag=='Occupational' and $permit_type=='Occupational') {
		$owner_id=$ownerID;
		require_once "includes/form_add_mtoppermit.html";
		require_once "includes/form_add_midoccu.php";
	} else {
		
		$owner_id=$ownerID;
		require_once "includes/form_add_mtoppermit.html";
		require_once "includes/form_add_middlepermit.php";
	}

if ($permit_type<>'Business') {
require_once "includes/mtop.php";
}
}

} elseif ($com=='RenewVeh') {
	$nmkyear = date('Y');
	$deleteexist = @mysql_query("delete from renew_vehicle where owner_id='$owner_id' and motorized_motor_id = '$mid' and date_updated like '$nmkyear%'");
	$insrenewveh = InsertQuery($dbtype,$dbLink,"renew_vehicle","",
					"'','$owner_id', '$mid','0', '$usern',now()");
	$updaterenewveh = UpdateQuery($dbtype,$dbLink,$vehicle,"retire=4, transaction='ReNew'", 
				"motorized_motor_id=$mid");
	require_once "includes/form_add_mtoppermit.html";
	require_once "includes/form_add_middlepermit.php";
	require_once "includes/mtop.php";

} elseif ($com=='Edit') {
	$getown=SelectMultiTable($dbtype,$dbLink,$owner,
			"owner_first_name, owner_middle_name,
			 owner_last_name, owner_gender",
			"where owner_id=$owner_id");
	$res=FetchRow($dbtype,$getown);
	$owner_first_name=$res[0];
	$owner_middle_name=$res[1];
	$owner_last_name=$res[2];
	$owner_gender=$res[3];
	$buttag = 'Save';
	$buttag1='Cancel';
	$slash='update';
	require_once "includes/stripslash.php";

	$getmot=SelectDataWhere($dbtype,$dbLink,$vehicle,
			"where motorized_motor_id = $mid and permit_type='$tag'");

	$mot=mysql_fetch_row($getmot);
	$mid=$mid;
	$mmodel=$mot[2];
	$mnum=$mot[3];
	$cnum=$mot[4];
	$pnum=$mot[5];
	$bnum=$mot[6];
	$route=$mot[9];
	$ltype=$mot[10];
	$bcolor = $mot[14];
	$ltoreg=$mot[15];
	$cro=$mot[16];
	$slash='update';
	require_once "includes/stripslash.php";
	$owner_id=$ownerID;
	require_once "includes/form_add_mtoppermit.html";
	require_once "includes/form_add_middlepermit.php";
	require_once "includes/mtop.php";

} elseif ($addveh=='Save') {
	$getown=SelectMultiTable($dbtype,$dbLink,$owner,
			"owner_first_name, owner_middle_name, 
			 owner_last_name, owner_gender",
			" where owner_id=$owner_id");
	$res=FetchRow($dbtype,$getown);
	$owner_first_name=$res[0];
	$owner_middle_name=$res[1];
	$owner_last_name=$res[2];
	$owner_gender=$res[3];
	$buttag = 'Add';
	$buttag1='Clear';
	$slash='add';
	require_once "includes/stripslash.php";
	$checkdup = SelectDataWhere($dbtype,$dbLink,$vehicle,
			"where motorized_motor_no='$mnum' and motorized_motor_id <> $mid");
	$checkdup = NumRows($dbtype,$checkdup);
	if ($checkdup>0) {
		?>
		<body onload='alert("Existing Motor Number Found");_FRM.mnum.focus();
			_FRM.mnum.select();'></body>
		<?php
	} else {
	
	$checkdup = SelectDataWhere($dbtype,$dbLink,$vehicle,
			"where
			 motorized_chassis_no='$cnum' and motorized_motor_id <> $mid");
	$checkdup = NumRows($dbtype,$checkdup);
		if ($checkdup>0) {
			?>
			<body onload='alert("Existing Chassis Number Found");_FRM.cnum.focus();
			_FRM.cnum.select();'></body>
			<?php
		} else {
			
	$checkdup = SelectDataWhere($dbtype,$dbLink,$vehicle,
			"where 
			 motorized_plate_no='$pnum' and motorized_motor_id <> $mid");
	$checkdup = NumRows($dbtype,$checkdup);
			if ($checkdup>0) {
				?>
				<body onload='alert("Existing Plate Number Found");_FRM.pnum.focus();
			_FRM.pnum.select();'></body>
				<?php
			} else {
	
	
	$checkdup = SelectDataWhere($dbtype,$dbLink,$vehicle,
			"where 
                         motorized_body_no='$bnum' and motorized_motor_id <> $mid");
	$checkdup = NumRows($dbtype,$checkdup);
	
				if ($checkdup>0) {
					?>
					<body onload='alert("Existing Body Number Found");_FRM.bnum.focus();
			_FRM.bnum.select();'></body>
					<?php
				} else {
	
	$checkdup = SelectDataWhere($dbtype,$dbLink,$vehicle,
			"where 
			 lto_number='$ltoreg' and motorized_motor_id <> $mid");
	$checkdup = NumRows($dbtype,$checkdup);
					if ($checkdup>0) {
						?>
						<body onload='alert("Existing LTO Number Found");_FRM.ltoreg.focus();
			_FRM.ltoreg.select();'></body>
						<?php
					} else {
	
	$checkdup = SelectDataWhere($dbtype,$dbLink,$vehicle,
			"where  cr_number='$cro' and motorized_motor_id <> $mid");
	$checkdup = NumRows($dbtype,$checkdup);
	
					if ($checkdup>0) {
		?>
		<body onload='alert("Existing Certificate of Registration Number Found");_FRM.cro.focus();
			_FRM.cro.select();'></body>
		<?php
					}
					
					
				}
			}
		}
	}
}

	if ($checkdup==0) {
	$upmot=UpdateQuery($dbtype,$dbLink,$vehicle," 
		motorized_motor_model='$mmodel', motorized_motor_no='$mnum', 
		motorized_chassis_no='$cnum', motorized_plate_no='$pnum', 
		motorized_body_no='$bnum', admin='$usern', updated_ts=now(), 
		route='$route', linetype='$ltype', body_color = '$bcolor', 
		lto_number = '$ltoreg',	cr_number = '$cro'",
		"motorized_motor_id = $mid");

		
	$mid='';
	$mmodel='';
	$mnum='';
	$cnum='';
	$pnum='';
	$bnum='';
	$route='';
	$ltype='';
	$bcolor='';
	$ltoreg='';
	$cro='';
	$owner_id=$ownerID;
	} else {
		$buttag='Save';
		$buttag1='Cancel';
	}
	require_once "includes/form_add_mtoppermit.html";
	require_once "includes/form_add_middlepermit.php";
	require_once "includes/mtop.php";

} elseif ($com=='Delete' || $com=='Drop') {
	
	$getown=SelectMultiTable($dbtype,$dbLink,$owner,
                        "owner_first_name, owner_middle_name,
                         owner_last_name, owner_gender",
                        " where owner_id=$owner_id");
						
	$res=FetchRow($dbtype,$getown);
        $owner_first_name=$res[0];
        $owner_middle_name=$res[1];
        $owner_last_name=$res[2];
        $owner_gender=$res[3];
	$buttag = 'Add';
	$buttag1='Clear';
	
	$verdel = SelectDataWhere($dbtype,$dbLink,$vehicle,
			"where motorized_motor_id=$mid");
			
	$getver = FetchArray($dbtype,$verdel);
	$stat1=$getver[status];
	$owner_id=$ownerID;
	
	require_once "includes/form_add_mtoppermit.html";
	require_once "includes/form_add_middlepermit.php";

if ($com=='Delete') {
	//search history
	$getown=SelectMultiTable($dbtype,$dbLink,"ebpls_motorized_vehicles a, vehicle_transfer b",
                        "motorized_motor_id",
                        " where a.motorized_motor_id ='$mid' and a.motorized_motor_id=b.motor_id ");
	$gg = NumRows($dbtype,$getown);
	
	if ($gg>0) {
		
		$getown=SelectMultiTable($dbtype,$dbLink,"ebpls_motorized_vehicles a, vehicle_transfer b",
                        "*",
                        " where a.motorized_motor_id ='$mid' and a.motorized_motor_id=b.motor_id and
                         b.new_owner='$owner_id'");
                     
         $ff= NumRows($dbtype,$getown); 
         $ft =  FetchArray($dbtype,$getown);             
		
         	if ($ff>0) {
	         	
		//update vehicle
				$updateit = UpdateQuery($dbtype,$dbLink,$vehicle,
			 		"motorized_operator_id  = $ft[old_owner],permit_type='$permit_type',retire=2,transaction='Drop'",
					 " motorized_motor_id='$mid'");
		$D = DeleteQuery($dbtype,$dbLink,"vehicle_transfer","motor_id='$mid' and
						new_owner='$owner_id'");
			?>
			<body onload='javascript:("Vehicle Transferred Cancelled");'</body>
			<?php
			} else {
	         	
		
	?>
		<body onload='javascript:ExistOther();'></body>
		<?php
			}
	} else {
	$delvec=DeleteQuery($dbtype,$dbLink,$vehicle,"motorized_motor_id=$mid");
	}

} elseif ($com=='Drop')  { //drop vehicle

	$dropvek = UpdateQuery($dbtype,$dbLink,$vehicle,"retire=1, transaction=$com",
			" motorized_motor_id=$mid");
}
require_once "includes/mtop.php";
}elseif ($clearveh=='Clear' or $clearveh=='Cancel') {
	$mid='';
	$mmodel='';
	$mnum='';
	$cnum='';
	$pnum='';
	$bnum='';
	$route='';
	$ltype='';
	$bcolor='';
	$ltoreg='';
	$cro='';
	$buttag1='Clear';
	$owner_id=$ownerID;
	require_once "includes/form_add_mtoppermit.html";
	require_once "includes/form_add_middlepermit.php";
	require_once "includes/mtop.php";
}

//occu permit clean up

if ($permit_type=='Occupational') {

	$delit = DeleteQuery($dbtype,$dbLink,"ebpls_occupational_permit",
			"active = 0 and occ_permit_application_date = now()"); 
} 


if ($linkpro=='PAYMENT') {

	 if ($permit_type=='Franchise') {
?>
                <body onload="parent.location='index.php?part=4&class_type=Permits&itemID_=2212&stat=<?php echo $stat; ?>&com=cash&permit_type=<?php echo $permit_type; ?>&busItem=Franchise&owner_id=<?php echo $owner_id; ?>'">
<?php
        }
        elseif ($permit_type=='Motorized') {
?>
	<body onload="parent.location='index.php?part=4&class_type=Permits&itemID_=2212&owner_id=<?php echo $owner_id; ?>&stat=<?php echo $stat; ?>&com=cash&permit_type=<?php echo $permit_type;?>&busItem=Motorized'">
<?php
        }
        elseif ($permit_type=='Fishery') {
?>
                <body onload="parent.location='index.php?part=4&class_type=Permits&itemID_=2212&owner_id=<?php echo $owner_id; ?>&stat=<?php echo $stat; ?>&com=cash&permit_type=<?php echo $permit_type;?>&busItem=Fishery'">
<?php
        }
        elseif ($permit_type=='Peddlers') {
?>
                <body onload="parent.location='index.php?part=4&class_type=Permits&itemID_=2212&owner_id=<?php echo $owner_id; ?>&stat=<?php echo $stat; ?>&com=cash&permit_type=<?php echo $permit_type;?>&busItem=Peddlers'">
<?php
        }
        elseif ($permit_type=='Occupational') {
?>
                <body onload="parent.location='index.php?part=4&class_type=Permits&itemID_=2212&owner_id=<?php echo $owner_id; ?>&stat=<?php echo $stat; ?>&com=cash&permit_type=<?php echo $permit_type;?>&busItem=Occupational'">
<?php
        }
}
?>

