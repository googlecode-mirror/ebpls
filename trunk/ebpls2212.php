<?php
//      Description:ebpls2212.php - one file that serves all other permit payments
//      author: Vnyz Sofhia Ice
//      Trademark: [V[f]X]S!73n+_K!77er
//      Last Updated: Dec 20, 2004 Trece Martires, Cavite
//	Note: Please Fill-up "Please Populate"

require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");

require_once("ebpls-php-lib/utils/ebpls.search.funcs.php");

//--- get connection from DB
//$dbLink = get_db_connection();
global $ThUserData;
require_once "includes/variables.php";
$getyearnow = date('Y');
if ($linkpro=='RELEASING') {
        $PROCESS='SAVE';
}

// display search page
if ($delproc == 'yes') {
/*	$deletepaid = DeleteQuery($dbtype,$dbLink,"temppayment",
				"owner_id = $owner_id and permit_type='$permit_type' 
				and status='1' and payid='$payid' and permit_status='$stat'");
	$addpay = "";
	if ($permit_type == "Motorized" || $permit_type == "Fishery" || $permit_type == "Occupational") {
		$insertpay = mysql_query("delete from ebpls_other_penalty_amount where permit_id = '$payid'");
	}
*/
	$getdel = mysql_query("select * from temppayment where
                                                        payid = '$payid'");
        $getdet = mysql_fetch_assoc($getdel);

        $or_no = $getdet['or_no'];

		$checkifexist = @mysql_query("select * from temppayment where or_no = '$newor'");
		$checkifexist1 = @mysql_num_rows($checkifexist);
		if ($checkifexist1 > 0) {
		print "<body onLoad=\" alert('Existing OR Found');\"></body>";
		} else {

        $upor = mysql_query("update  temppayment set or_no='$newor' where
                                                        payid='$payid'");
        $insor = mysql_query("insert into cancel_or values('','$or_no', '$newor', '$reasoncan',now(),'$usern')");
		}
        ?>

	<body onload='window.open("ebplsothrec.php?owner_id=<?php echo $owner_id; ?>&or_no=<?php echo $newor; ?>&permit_type=<?php echo $permit_type; ?>&stat=<?php echo $stat; ?>&payid=<?echo $payid;?>");'></body>

                     <!--	<body onload='javascript:alert("Payment Record Deleted."); parent.location="index.php?part=4&class_type=Permits&itemID_=2212&owner_id=<? echo $owner_id;?>&stat=<? echo $stat;?>&com=cash&permit_type=<? echo $permit_type;?>&busItem=<? echo $permit_type;?>"'></body>-->
<?php
}
if ($com=='cash' || $com=='check' || $com=='reassess' and $addpay<>'Compute') {
	if ($addpay=='' and $cmd=='' and $PROCESS<>'PROCESS') {
	} else {

		if ($cmd=='delete') {
                                                                                                               
        		if ($com=='cash') {
                                                                                                               
                	$checkit = SelectDataWhere($dbtype,$dbLink,"temppayment",
		                        "where owner_id = $owner_id and 
					 permit_type='$permit_type'
		                        and payid=$payid and permit_status='$stat'");
                	$checki = FetchArray($dbtype,$checkit);
                		if ($checki[status]==0) {
                        	require_once "includes/payform.php";
?>
                        	<body onload='javascript:alert("Cannot Delete"); parent.location="index.php?part=4&class_type=Permits&itemID_=2212&owner_id=<? echo $owner_id;?>&stat=<? echo $stat;?>&com=cash&permit_type=<? echo $permit_type;?>&busItem=<? echo $permit_type;?>"'></body>
<?php
                		} else {
							?>
							<body onload='javascript: DeletePayment(<? echo $payid;?>);'></body>
							<?php
                		}

			}
		
		}  elseif ($PROCESS=='SAVE') {
        //check if payment is made
                                                                                                               
                		$checkit = SelectDataWhere($dbtype,$dbLink,"temppayment",
                        		"where owner_id = $owner_id and 
					permit_type='$permit_type' and status = 1
					and permit_status='$stat' ");
                		$checki = NumRows($dbtype,$checkit);
                		
			if ($checki==0) {
					    	require_once "includes/payform.php";
?>
                        	<body onload='javascript:alert("Cannot Process. 
				No Payment Is Made");parent.location="index.php?part=4&class_type=Permits&itemID_=2212&owner_id=<? echo $owner_id;?>&stat=<? echo $stat;?>&com=cash&permit_type=<? echo $permit_type;?>&busItem=<? echo $permit_type;?>"'></body>
<?php
                	} else {

				if ($permit_type=='Motorized' || $permit_type=='Franchise') {
				$slash='add';
				require_once "includes/stripslash.php";
			        $updatevec = UpdateQuery($dbtype,$dbLink,$vehicle,"status = 0",
                		"motorized_operator_id = $owner_id 
				and permit_type='$tag'");
			}

?>
		     	 <body onload='javascript:alert("Transaction Successful");'></body>
                                                                           
<?php                                    
               	}
	}

}
require_once "includes/payform.php";

} elseif ($mtopsearch=='SEARCH') { //search existing
require_once "includes/payment_search.php";

} elseif ($addpay=='Compute' and $PROCESS<>'SAVE' ) {
	$getor = SelectDataWhere($dbtype,$dbLink,"temppayment","where or_no='$orno1'");
	$getor = NumRows($dbtype,$getor);
		if ($getor==0) {
			$insertpay = InsertQuery($dbtype,$dbLink,"temppayment",
			"(payamt, owner_id, permit_type, pay_date, 
			 status, pay_type, permit_status,or_no)",
			"$amtpaid, $owner_id, '$permit_type', now(), 
			 1, 'Cash', '$stat','$orno1'");	
			if ($permit_type == "Motorized" || $permit_type == "Fishery" || $permit_type == "Occupational") {
				$yearsdf = date('Y');
				
				if ($surintamount > 0) {
					$insertpay = mysql_query("insert into ebpls_other_penalty_amount values ('', '$owner_id', '$permit_type', '$insertpay', '$surintamount', '$bt', '$yearsdf', '$usern', now())");
				}
					$checkrentype = @mysql_query("select * from ebpls_motorized_penalty where permit_type = '$permit_type'");
					$checkrentype1 = @mysql_fetch_assoc($checkrentype);
					if ($checkrentype1['renewaltype'] == '2' and $stat=="ReNew") {
						$insertpay12 = mysql_query("update ebpls_motorized_vehicles set paid = '1', updated_ts = now() where motorized_operator_id = '$owner_id' and retire = '4'");
					} 
					if ($checkrentype1['renewaltype'] == '1' and $stat=="ReNew") {
						$insertpay12 = mysql_query("update ebpls_motorized_vehicles set paid = '1', updated_ts = now() where motorized_operator_id = '$owner_id'");
					}
					if ($stat != "New") {
						$insertpay121 = mysql_query("insert into ebpls_mot_penalty values('', '$owner_id', '$hSurcharge', '$hInterest', '$hLate', '$bt', '1', now(), '$usern')");
					}
//					$insertpay = mysql_query("update ebpls_motorize_vehicles set paid = '1' where motorized_operator_id = '$owner_id'");
				/*if ($stat == "Transfer/Dropping"  and $permit_type == "Motorized") {
					<body onload='javascript: parent.location="index.php?part=4&class_type=Permits&permit_type=Motorized&busItem=Motorized&itemID_=2212&mtopsearch=SEARCH"'></body>
				}*/
				
			}
		} else {
?>
			<body onload='javascript:alert("OR Number Exist!"); parent.location="index.php?part=4&class_type=Permits&itemID_=2212&owner_id=<? echo $owner_id;?>&stat=<? echo $stat;?>&com=cash&permit_type=<? echo $permit_type;?>&busItem=<? echo $permit_type;?>"'></body>
<?php
			$com = 'cash';	
		}
	include "includes/payform.php";

} elseif ($addpay=='Compute' and $PROCESS=='SAVE' ) {
	 if ($PROCESS=='SAVE') {
                                                                                                               
                if ($permit_type=='Business') {
                        $ext = ' and and business_id=$business_id';
                } else {
                        $ext = '';
                }
				if ($permit_type != "Business") {
					$getyearnow = date('Y');
					$getpay = SelectMultiTable($dbtype,$dbLink,"temppayment",
					"payid, payamt, pay_date, pay_type, 
					or_no,status", 
					"where owner_id = $owner_id and permit_type='$permit_type' 
					and permit_status='$stat' and pay_date like '$getyearnow%'");
					$payedamount = 0;
					while ($getpayed = mysql_fetch_assoc($getpay)) {
						$payedamount = $payedamount + $getpayed['payamt'];
					}
					$balancefornow = $totalfeexs - $payedamount;
				}
                        $updateit = UpdateQuery($dbtype,$dbLink,$permittable,  
				"steps='For Releasing', paid=1",
                                "owner_id='$owner_id' $ext");
				if ($balancefornow > 0 ) {
					$updateit = UpdateQuery($dbtype,$dbLink,$permittable,  
				"steps='For Payment', paid='0'",
                                "owner_id='$owner_id'");
				}
		 	$checkit = SelectDataWhere($dbtype,$dbLink,"temppayment",
 	                        "where owner_id = $owner_id and permit_type='$permit_type' 
				 and status = 1 and permit_status='$stat' and pay_date like '$getyearnow%'");
                                $checki = NumRows($dbtype,$checkit);
                                if ($checki==0) {
				$com='cash';
				$checkrentype = @mysql_query("select * from ebpls_motorized_penalty where permit_type = '$permit_type'");
	$checkrentype1 = @mysql_fetch_assoc($checkrentype);
	if ($checkrentype1['renewaltype'] == '2' and $stat =="ReNew") {
		if ($linkpro=='RELEASING') {
		$updateit = UpdateQuery($dbtype,$dbLink,"ebpls_motorized_vehicles",
				"retire = '5'", 
				"motorized_operator_id = '$owner_id'  and retire = '4'");
		$updateit12 = UpdateQuery($dbtype,$dbLink,"ebpls_motorized_vehicles",
				"retire = '5'", 
				"motorized_operator_id = '$owner_id'  and retire = '0' and transaction = 'New'");
		$nbvyear = date('Y');
		$updateit1 = UpdateQuery($dbtype,$dbLink,"ebpls_fees_paid",
				"active = '0'", 
				"owner_id = '$owner_id' and active = '1' and input_date like '$nbvyear%'") or die(mysql_error);
				$updatepaid122 = UpdateQuery($dbtype,$dbLink,"temppayment",
				"status = 0","owner_id = $owner_id and 
				permit_type='$permit_type'");
			}
	} elseif ($stat =="Transfer/Dropping") {
$updateit = UpdateQuery($dbtype,$dbLink,"ebpls_motorized_vehicles",
				"retire = '2', motorized_operator_id = ''", 
				"motorized_operator_id = '$owner_id'  and retire = '1'");
		$nbvyear = date('Y');
		$updateit1 = UpdateQuery($dbtype,$dbLink,"ebpls_fees_paid",
				"active = '0'", 
				"owner_id = '$owner_id' and active = '1' and input_date like '$nbvyear%'") or die(mysql_error);
				$checkifmore = SelectDataWhere($dbtype,$dbLink,"ebpls_motorized_vehicles",
 	                        "where motorized_operator_id = '$owner_id'");
				$ifmore = mysql_num_rows($checkifmore);
				if ($ifmore > 0) {	
					$nbvyear = date('Y');
					$updateit1 = UpdateQuery($dbtype,$dbLink,"ebpls_motorized_operator_permit ",
					" motorized_no_of_units = '$ifmore', transaction = 'ReNew', steps = 'For Payment'", 
					"owner_id = '$owner_id' and active = '1' and for_year = '$nbvyear'") or die(mysql_error);
				}
	//Insert Link Here
	}
									if ($linkpro=='RELEASING') {
										
										
?>
                <body onload="parent.location='index.php?part=4&class_type=Permits&itemID_=3212&owner_id=<?php echo $owner_id; ?>&com=PrintReport&permit_type=<?php echo $permit_type; ?>&stat=<?php echo $stat;?>&busItem=<?php echo $permit_type; ?>'">
<?php
        
									} else {			
				
				
				
				
				
				
?>
				<body onload='javascript: alert("Cannot Process. 
				No Payment Is Made");'></body>
<?php
							$nopayment = '1';
								
                                require_once "includes/payform.php";
                            }
				} else {
                                $tfee = SelectMultiTable($dbtype,$dbLink,"ebpls_fees_paid",
				"sum(fee_amount)* multi_by",
                                "where owner_id = $owner_id and
                                 permit_type='$permit_type'");
                                $totalfee = FetchRow($dbtype,$tfee);
                                $totpay = $totalfee[0];
				$totchnge = SelectMultiTable($dbtype,$dbLink,"temppayment",
				"sum(payamt)","where owner_id = $owner_id 
				 and permit_type='$permit_type' and status=1
                                 and permit_status='$stat'");
                                $amtchange = FetchRow($dbtype,$totchnge);
                                $ort = SelectDataWhere($dbtype,$dbLink,"temppayment",
                                "where owner_id = $owner_id and permit_type='$permit_type' 
				 and status=1 and permit_status='$stat'");
				$ort=FetchArray($dbtype,$ort);
                                $totpaid = $amtchange[0];
                                $amtchange =  $totpay - $amtchange[0];
				$orno=$ort[or_no];
//please populate ACCOUNT CODES

				$getmax =SelectDataWhere($dbtype,$dbLink,
				"ebpls_transaction_payment_or","");
                                $or = NumRows($dbtype,$getmax);
				$or = $or+1;
				$trans_id = $owner_id;
				$payment_code = $orno;
				$payment_id = 0;
				$tax_fee_code='taxcode';
				$account_code='acntcode';
				$account_desc ='acnt desc';
				$insertor = InsertQuery($dbtype,$dbLink,
				"ebpls_transaction_payment_or","
                                (or_no, payment_code, trans_id,or_date,total_amount_due,
                                total_amount_less,total_amount_paid,ts_create,payment_officer)",
                                "'$or','$payment_code', $trans_id, now(), $totpaid,
                                $amtchange, $totpaid, now(),'$usern'");
 
				$insertdetails=InsertQuery($dbtype,$dbLink,
				"ebpls_transaction_payment_or_details",
				"(or_no,trans_id, payment_id, tax_fee_code,
                                account_code, account_nature, account_desc, 
				amount_due, ts, permit_type)",
                                "'$or',$trans_id, $payment_id, '$tax_fee_code',
                                '$account_code','CREDIT', '$account_desc', $totpaid,
				now(), '$permit_type'");

				$updatepaid = UpdateQuery($dbtype,$dbLink,"temppayment",
				"status = 0","owner_id = $owner_id and 
				permit_type='$permit_type'");
		                $addpay='';
				}
        	}
	}

if ($com<>'cash' and  $com<>'check' and $com<>'reassess' and $com<>'Select' and $com<>'Edit' and $com<>'Delete' and $com<>'ReNew' and $addpay<>'Compute' and $cmd<>'delete') {
                                                                                                               
// process successful
        if ($PROCESS=='SAVE') {
                                                                                                               
                if ($permit_type=='Business') {
                        $ext = ' and business_id='.$business_id;
                } else {
                        $ext = '';
                }
				
                        $updateit = UpdateQuery($dbtype,$dbLink,$permittable,
				"steps='For Releasing'",
    				"owner_id='$owner_id' $ext");
					$updateit1 = UpdateQuery($dbtype,$dbLink,$permittable,
				"paid='1'",
    				"owner_id='$owner_id' $ext");
					//$updateveches = UpdateQuery($dbtype, $dbLink, 'ebpls_motorized_vehicles', "transaction = '$stat'", 
					//"motorized_operator_id = '$owner_id' and retire = '0'");
    				if ($linkpro=='RELEASING') {
					$checkrentype = @mysql_query("select * from ebpls_motorized_penalty where permit_type = '$permit_type'");
	$checkrentype1 = @mysql_fetch_assoc($checkrentype);
	if ($checkrentype1['renewaltype'] == '2' and $stat =="ReNew") {
		$updateit = UpdateQuery($dbtype,$dbLink,"ebpls_motorized_vehicles",
				"retire = '5'", 
				"motorized_operator_id = '$owner_id' and retire = '4'");
				$vbnmyear = date('Y');
				$updateit321 = UpdateQuery($dbtype,$dbLink,"ebpls_motorized_vehicles",
				"retire = '5'", 
				"motorized_operator_id = '$owner_id' and retire = '0' and create_ts like '$vbnmyear%'");
		$nbvyear = date('Y');
		$updateit = UpdateQuery($dbtype,$dbLink,"ebpls_fees_paid",
				"active = '0'", 
				"owner_id = '$owner_id' and active = '1' and input_date like '$nbvyear%'");
	} elseif ($stat =="Transfer/Dropping") {
$updateit = UpdateQuery($dbtype,$dbLink,"ebpls_motorized_vehicles",
				"retire = '2', motorized_operator_id = ''", 
				"motorized_operator_id = '$owner_id'  and retire = '1'");
		$nbvyear = date('Y');
		$updateit1 = UpdateQuery($dbtype,$dbLink,"ebpls_fees_paid",
				"active = '0'", 
				"owner_id = '$owner_id' and active = '1' and input_date like '$nbvyear%'") or die(mysql_error);
				$checkifmore = SelectDataWhere($dbtype,$dbLink,"ebpls_motorized_vehicles",
 	                        "where motorized_operator_id = '$owner_id'");
				$ifmore = mysql_num_rows($checkifmore);
				if ($ifmore > 0) {	
					$nbvyear = date('Y');
					$updateit1 = UpdateQuery($dbtype,$dbLink,"ebpls_motorized_operator_permit ",
					" motorized_no_of_units = '$ifmore', transaction = 'ReNew', steps = 'For Payment'", 
					"owner_id = '$owner_id' and active = '1' and for_year = '$nbvyear'") or die(mysql_error);
				}
	}
					
						
?>
                <body onload="parent.location='index.php?part=4&class_type=Permits&itemID_=3212&owner_id=<?php echo $owner_id; ?>&com=PrintReport&permit_type=<?php echo $permit_type; ?>&stat=<?php echo $stat;?>&busItem=<?php echo $permit_type; ?>'">
<?php
        
					}
    				
    				
    				
?>
                    <body onload='alert("Transaction Successful");'></body>
<?php
		 require_once "includes/payment_search.php";
                $addpay='';
        }
}
/*
if ($permit_type=='Occupational') {
                                                                                                                                                                                                   
        $delit = DeleteQuery($dbtype,$dbLink,"ebpls_occupational_permit",
                        "active = 0 and occ_permit_application_date = now()");
}
  */                                                                                                                                                                                                 
                                                                                                                                                                                                   
if ($linkpro=='RELEASING' and $checki<>0) {
	
?>
                <body onload="parent.location='index.php?part=4&class_type=Permits&itemID_=3212&owner_id=<?php echo $owner_id; ?>&com=PrintReport&permit_type=<?php echo $permit_type; ?>&stat=<?php echo $stat;?>&busItem=<?php echo $permit_type; ?>'">
<?php
        
}
?>
