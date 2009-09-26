<?php

if ($permit_type<>'Business') {


$searchif = SelectDataWhere($dbtype,$dbLink,$permittable,
				" where owner_id=$owner_id");
$existid = NumRows($dbtype,$searchif);

	if ($existid==0){

 $inserttemp = InsertQuery($dbtype,$dbLink,$permittable,
		"($incode, owner_id, for_year, $appdate, paid,transaction)",
		"'$permitcode', $owner_id, '$currdate[year]', '$tdate', 0,'$stat')");
	}

if ($tag=='Occupational' and $permit_type=='Occupational') {

//update occu permit
                if ($existid<>0) {

			if ($frmedit=='yes') {

			$slash='add';
			require_once "includes/stripslash.php";

	                $updateemp = UpdateQuery($dbtype,$dbLink,"ebpls_occupational_permit",
                			"occ_position_applied ='$pos_app',
			                business_id='$employer_business',
			                for_year='$currdate[year]'",
					"owner_id=$owner_id and active=1");
			}
                } else {

		$slash='add';
		require_once "includes/stripslash.php";

			if ($frmedit<>'yes') {
				if ($employer_business>0) {
                 $insertemp = InsertQuery($dbtype,$dbLink,"ebpls_occupational_permit",
                			"(owner_id ,  occ_permit_application_date, 
					occ_position_applied, for_year, paid,
					transaction, steps, pin, active, business_id)",
			                "$owner_id, '$tdate', '$pos_app',
                			'$currdate[year]', 0,'$stat', 'For Payment', 
					'$pin', 1, $employer_business");
					} else {
					?><body onload='alert("Please select employer."); parent.location="index.php?part=4&itemID_=1221&permit_type=Occupational&stat=<?php echo $stat; ?>&owner_id=<?php echo $owner_id;?>&upOwner=UPDATE&business_id=<?php echo $business_id;?>&busItem=Occupational";'></body>
	           
				<?php
				}
			}
                }
} //end if occupational

/*
if ($owner_id=='') {
$owner_id=$id;
}
*/

if ($permit_type=='Occupational' || $permit_type=='Peddlers') {
	$totalvec=1;
}

//need to add drop command
$getcnt = SelectDataWhere($dbtype,$dbLink,$temptbl,
                "where owner_id=$owner_id");
                $cnts=NumRows($dbtype,$getcnt);
               // $lop=1;

		for ($lop=1;  $lop<=$cnts;$lop++)
                        {
		$gettemp =SelectDataWhere($dbtype,$dbLink,$temptbl,
			"where  owner_id=$owner_id limit 1");
		$getfee = FetchArray($dbtype,$gettemp);
		$slash='add';
		require_once "includes/stripslash.php";

//insert fees to main table
		$fee_desc=addslashes($getfee[fee_desc]);
                
		$insertit=InsertQuery($dbtype,$dbLink,"ebpls_fees_paid",
				"(owner_id, fee_desc, fee_amount, 
				multi_by, permit_type, permit_status,
				input_by, input_date)",
                                "$owner_id, '$fee_desc', $getfee[fee_amount], 
				$totalvec, '$permit_type', '$status', 
				'$usern', '$tdate'");

                $deltemp = DeleteQuery($dbtype,$dbLink,$temptbl,
			             	"owner_id =$owner_id and 
					permit_type='$permit_type' limit 1");
                        }




}
?>
