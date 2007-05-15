<?
//please populate ACCOUNT CODES
$trans_id = $owner_id;
$payment_code = 'code';//or no
$payment_id = $business_id; //businee id
$tax_fee_code='taxcode';
$account_code='acntcode';
$account_desc ='acnt desc';
$check_name = addslashes($check_name);               
//check for existing or
$checkor = SelectDataWhere($dbtype,$dbLink,"ebpls_transaction_payment_or",
		"where payment_code='$orno'");
$checkor = NumRows($dbtype,$checkor);

if ($checkor>0) {
?>
	<body onload='javascript:alert("Existing OR Found!");'></body>
<?php
$von='1';
} else {


if ($cmd=='CHECK') {
	$checkf = 1;
} else {
	$checkf = 0;
}
		if ($perline==1) {
			$linepaid=1;
			$nat_id=$nature_id;
		} else {
			$linepaid=0;
			$nat_id=0;
		}

		if (is_numeric($nature_id)) {
			//update tempbus

			
		
			/*$newrecord = InsertQuery($dbtype,$dbLink,"comparative_statement",
				    	"'', '$owner_id', '$business_id', '', '', '$pmode', 
					'$paypart', '$betstax', '$fee', '$penamt', '$surcharge', 
					'$amtpay', '$exemption', '$checkf', '$sbacktax', 
					'$yearnow', '$orno',now(),'$monthnow'");*/
                
		}

	
                                                                       
                                $ctl_no = InsertQuery($dbtype,$dbLink,
					"ebpls_transaction_payment_or",
	                                "(or_no, payment_code, trans_id,or_date,
					 total_amount_due, total_amount_less,total_amount_paid,
					 ts_create,payment_officer)",
		                        "'', '$orno', $trans_id, now(), $amtpay,
	                                 $change, $amtpaid, now(),'$usern'");
				$or_no = @mysql_insert_id();
					
			$newrecord = InsertQuery($dbtype,$dbLink,"comparative_statement","",
					"'', '$owner_id', '$business_id', '', '', '$pmode', 
					'$pay_mode', '$ntax', '$fee', '$penamt', '$surcharge', 
 					'$amtpay', '$exemption', '$checkf', '$sbacktax', 
					'$yearnow', '$orno', now(),'$monthnow'");
				
				if ($cmd=='CHECK') {
                                $insertcheck = InsertQuery($dbtype,$dbLink,
					"ebpls_transaction_payment_check",
		                        "(check_no, check_name, check_issue_date, 
					check_amount, ts_create, remark, or_no, check_status)",
                              		"'$check_no', '$check_name', '$checkid', 
					$amtpaid, now(), '$remark',$ctl_no, 'PENDING'");
                                } 

                if ($istat=='Retire') {

				$wil2 = UpdateQuery($dbtype,$dbLink,"tempbusnature",
					"retire=1, recpaid=1","owner_id=$owner_id and 
					business_id=$business_id and 
					active=1 and transaction='Retire'");

				$updatebusnature=UpdateQuery($dbtype,$dbLink,"tempbusnature",
					"active=0, recpaid=1","owner_id=$owner_id and 
					business_id=$business_id and transaction='Retire'");

	     			$chkretire=SelectDataWhere($dbtype,$dbLink,"tempbusnature",
					"where owner_id=$owner_id and 
					business_id=$business_id and active=1");
	     			$chkretire=FetchRow($dbtype,$chkretire);
	     			if ($chkretire==0) {

		     		$updateretire=UpdateQuery($dbtype,$dbLink,
					"ebpls_business_enterprise",
					"retire=1, business_retirement_date=now()",
					"owner_id=$owner_id and business_id=$business_id");

				$ubp = UpdateQuery($dbtype,$dbLink,
					"ebpls_business_enterprise_permit",
					"active = 0", "owner_id = $owner_id and
					business_id = $business_id");
				
	     				} else {
				
//deact all permit
	$ubp = UpdateQuery($dbtype,$dbLink,"ebpls_business_enterprise_permit",
                            "active = 0","owner_id = $owner_id and
                            business_id = $business_id");
//active 1
	$updatepermit = UpdateQuery($dbtype,$dbLink,"ebpls_business_enterprise_permit",
			    "active=1","owner_id=$owner_id and business_id=$business_id 
			    and transaction<>'Retire' order by business_permit_id desc limit 1");

//change pmode back to orig -- ang orig sa bicol ay baboy
$getpmode = SelectDataWhere($dbtype,$dbLink,"ebpls_business_enterprise_permit",
		"where owner_id=$owner_id and business_id=$business_id 
		and pmode<>'' order by business_permit_id desc limit 1");
$pmode = FetchArray($dbtype,$getpmode);
$pmode = $pmode[pmode];

$updatepmode =  UpdateQuery($dbtype,$dbLink,"ebpls_business_enterprise",
		"business_payment_mode = '$pmode'", 
		"owner_id=$owner_id and business_id=$business_id"); 
				}
     			}
     			
				$updatebiz = UpdateQuery($dbtype,$dbLink,
				"ebpls_business_enterprise_permit",
                                "paid=1","owner_id=$owner_id 
				and business_id=$business_id");
                                
				$insertdetails=InsertQuery($dbtype,$dbLink,
				"ebpls_transaction_payment_or_details","(
                                or_no,trans_id , payment_id, tax_fee_code,
	                        account_code, account_nature, account_desc, 
				amount_due, ts,payment_part, or_entry_type, 
				linepaid, nat_id, transaction,permit_type)",
                                "'$ctl_no',$trans_id, $payment_id, '$tax_fee_code',
	                        '$account_code', 'CREDIT', '$account_desc' , $amtpay, 
				now(),'$pay_mode','$cmd','$linepaid', 
				$nat_id, '$istat', 'Business'");	

				$updateit = UpdateQuery($dbtype,$dbLink,$permittable,
				"steps='For Releasing'",
                                "owner_id=$owner_id and business_id=$business_id");
// save fees paid
			 $gettag=SelectDataWhere($dbtype,$dbLink,"ebpls_buss_preference","");
                         $gettag=FetchArray($dbtype,$gettag);
                                 if ($gettag[sassess]=='') {
					//check if have rec
			 		$chkit = SelectDataWhere($dbtype,$dbLink,
						"bus_fees_paid","where owner_id=$owner_id and 
						business_id=$business_id");
						$chkit = NumRows($dbtype,$chkit);
		
						if ($chkit==0) {
						$getfee = SelectDataWhere($dbtype,$dbLink,
						"ebpls_buss_tfo","where	tfoindicator=1 and 
						taxfeetype=2 and tfostatus='A'");

						while ($getf=FetchRow($dbtype,$getfee))
						{
						$insit = InsertQuery($dbtype,$dbLink, 
						"bus_fees_paid","''","'',$owner_id, $business_id,
						$getf[0], $getf[6],now())");
						}

						}
						}
					
                                                                              
?>
                        <body onunload='opener.location.reload(true); ' onLoad='window.open("ebplsreceipt.php?owner_id=<?php echo $owner_id; ?>&business_id=<?php echo $business_id;?>&or_no=<?php echo $or_no; ?>&cmd=<?php echo $cmd; ?>&paymde=<?php echo $paymde; ?>&nature_id=<?php echo $nature_id; ?>&stat=<?php echo $istat; ?>&amtpay=<?php echo $amtpay; ?>");opener.location.reload(true); window.close();'></body>
                       
<?php

}
?> 
