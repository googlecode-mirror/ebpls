<?php
// approval
require_once "includes/variables.php";

if ($com<>'approve') {

	// process successful
	if ($PROCESS=='SAVE' || $PROCESS=='PAYMENT') {
		$dec_comment = addslashes($dec_comment);
		$getapp = SelectDataWhere($dbtype,$dbLink,"ebpls_buss_approve",
                		"where owner_id = $owner_id and 
				 business_id=$business_id");
                $getnum = NumRows($dbtype,$getapp);

		if ($getnum==0) {
		$savedata = InsertQuery($dbtype,$dbLink,"ebpls_buss_approve","", 
				"'',$owner_id, $business_id, $decide, 
				 '$dec_comment', '$stat'");
		} else {

		$updata = UpdateQuery($dbtype,$dbLink,"ebpls_buss_approve",
				"decision=$decide,dec_comment='$dec_comment', 
				 transaction='$stat'", 
				"owner_id = $owner_id and business_id=$business_id");
		}

		if ($decide==1) {
		 $updateit = UpdateQuery($dbtype,$dbLink,$permittable,
				"steps='For Payment'",
                                "owner_id=$owner_id and business_id=$business_id");
		}

		if ($PROCESS=='PAYMENT' and $decide==1) {
		?>
			<body onload="parent.location='index.php?part=4&newpred=<?php echo $newpred; ?>&noregfee=<?php echo $noregfee; ?>&class_type=Permits&itemID_=2212&owner_id=<?php echo $owner_id; ?>&com=cash&permit_type=<?php echo $permit_type; ?>&stat=<?php echo $stat; ?>&business_id=<?php echo $business_id; ?>&busItem=Business&istat=<?php echo $stat; ?>'">
		<?php
		} else  if ($PROCESS=='PAYMENT' and $decide<>1) {
		print "<div align=center><font color=red>Cannot Proceed to Payment. Disapproved Application</font></div>";
		}
	}

}

if ($mtopsearch=='SEARCH') { //search existing
	require_once "includes/approve_search.php";
}elseif ($com=='approve') {
		require_once "includes/form_bus_approval.php";
}




