<?php
// get total vehicle

if ($comm=='dropping' and $stat=='Transfer/Dropping') {
	$dropvek = UpdateQuery($dbtype,$dbLink,$vehicle,"retire=1, transaction='Drop'", 
				"motorized_motor_id=$mid");
}
if ($owner_id=='') {
        $owner_id=0;
}
if ($stat=='') {
	$stat = 'New';
}
$temptbl = 'tempfees';
if ($permit_type=='Motorized' || $permit_type=='Franchise') {
$checkrentype = @mysql_query("select * from ebpls_motorized_penalty where permit_type = '$permit_type'");
$checkrentype1 = @mysql_fetch_assoc($checkrentype);

if ($checkrentype1['renewaltype'] == '2' and $stat =="ReNew") {
	$totvec=SelectDataWhere($dbtype,$dbLink,"ebpls_motorized_vehicles",
		"where motorized_operator_id = '$owner_id' 
		and permit_type='$permit_type' and retire>'2'");
		$totvehicles = @mysql_query("select * from ebpls_motorized_vehicles where motorized_operator_id = '$owner_id' 
		and permit_type='$permit_type' and retire>'2'");
		$totnewvehicles = @mysql_query("select * from ebpls_motorized_vehicles where motorized_operator_id = '$owner_id' and permit_type='$permit_type' and retire='0' and transaction = 'New'");
} elseif ($stat =="Transfer/Dropping") {
	$totvec=SelectDataWhere($dbtype,$dbLink,"ebpls_motorized_vehicles",
		"where motorized_operator_id = $owner_id 
		and permit_type='$permit_type' and retire='1'");
} else {
	if ($stat == "New") {
	$totvec=SelectDataWhere($dbtype,$dbLink,"ebpls_motorized_vehicles",
		"where motorized_operator_id = $owner_id 
		and permit_type='$permit_type' and retire='0'");
	} else {
		$totvec=SelectDataWhere($dbtype,$dbLink,"ebpls_motorized_vehicles",
		"where motorized_operator_id = $owner_id 
		and permit_type='$permit_type' and retire='0'  and transaction='$stat'");
	}
}
if ($checkrentype1['renewaltype'] == '2' and $stat =="ReNew") {
	$tot = @mysql_num_rows($totvehicles);
	$origtot = $tot;
	
	//$totind1 = @mysql_num_rows($totnewvehicles);
	$tot = $tot + $totind1;

} elseif ($stat=="Transfer/Dropping") {
	$getoldvec = @mysql_query("select * from ebpls_motorized_vehicles where permit_type= '$permit_type' and motorized_operator_id = '$owner_id' and transaction = 'Drop' and retire = '1'");
$theorig = @mysql_num_rows($getoldvec);
	$tot = @mysql_num_rows($totvec);
} else {
	if ($stat == "New") {
        $getoldvec = @mysql_query("select * from ebpls_motorized_vehicles where permit_type= '$permit_type' and motorized_operator_id = '$owner_id' and retire = '0'");
	} else {
		   $getoldvec = @mysql_query("select * from ebpls_motorized_vehicles where permit_type= '$permit_type' and motorized_operator_id = '$owner_id' and transaction = '$stat' and retire = '0'");
	}
$theorig = @mysql_num_rows($getoldvec);
        $tot = @mysql_num_rows($totvec);
}
if ($stat == "ReNew") {
$getfeesnew1111 = @mysql_query("select * from ebpls_motorized_vehicles where permit_type= '$permit_type' and motorized_operator_id = '$owner_id' and transaction = 'New' and retire = '0'");
$wert1 = @mysql_num_rows($getfeesnew1111);

if ($wert1 > 0) {
	$nRMV1 = 1;
} else {
	$nRMV1 = 0;
}
$tot = $tot + $wert1;
}
?>
<input type=hidden name=totalvec value=<?php echo $tot; ?>>
Total vehicles: <?php echo $tot; ?> <br><br>
<table border=1 width=100%>
<tr>
<td align=center> Motor Model </td>
<td align=center> Plate Number </td>
<td align=center> Body Number </td>
<td align=center> Body Color </td>
<td align=center> Route </td>
<td align=center> Line Type </td>
<td align=center> LTO Registration No </td>
<td align=center> Certificate of Registration </td>
<td align=center> Action </td>
</tr>
<?php
//populate vehicle
$checkrentype = @mysql_query("select * from ebpls_motorized_penalty where permit_type = '$permit_type'");
$checkrentype1 = @mysql_fetch_assoc($checkrentype);

if ($checkrentype1['renewaltype'] == '2' and $stat=="ReNew") {
	$result = SelectDataWhere($dbtype,$dbLink, $vehicle, 
				"where motorized_operator_id = $owner_id 
				and permit_type='$permit_type' and retire='0'") or die("");
} elseif ($stat =="Transfer/Dropping") {
	$result = SelectDataWhere($dbtype,$dbLink, $vehicle, 
				"where motorized_operator_id = $owner_id 
				and permit_type='$permit_type' and retire=0") or die("");
} else {
	$result = SelectDataWhere($dbtype,$dbLink, $vehicle, 
				"where motorized_operator_id = $owner_id 
				and permit_type='$permit_type' and retire=0") or die("");
}

if ($com=='Drop') {
$comtag = 'Drop';
} else {
$comtag = 'Delete';
}

	while ($get_info = FetchArray($dbtype,$result)){
?>
		<tr>
		<td align=center>&nbsp;<?php echo $get_info[motorized_motor_model]; ?>&nbsp;</td>
	
                <td align=center>&nbsp;<?php echo $get_info[motorized_plate_no]; ?>&nbsp;</td>
		<td align=center>&nbsp;<?php echo $get_info[motorized_body_no]; ?>&nbsp;</td>
                <td align=center>&nbsp;<?php echo $get_info[body_color]; ?>&nbsp;</td>
		<td align=center>&nbsp;<?php echo $get_info[route]; ?>&nbsp;</td>
                <td align=center>&nbsp;<?php echo $get_info[linetype]; ?>&nbsp;</td>
		<td align=center>&nbsp;<?php echo $get_info[lto_number]; ?>&nbsp;</td>
                <td align=center>&nbsp;<?php echo $get_info[cr_number]; ?>&nbsp;</td>
		<td>
<?php
		if ($com<>'Drop' and $noappa != '3') {
			if ($itemID_<>'') {
			print "
			<a class='subnavwhite' href='index.php?part=4&class_type=Permits&itemID_=1221
			&owner_id=$owner_id&mid=$get_info[motorized_motor_id]
			&com=Edit&permit_type=$tag
			&stat=$status'>Edit</a>";
			
			print "&nbsp<a href='index.php?part=4&class_type=Permits&comm=dropping
			&itemID_=1221&owner_id=$owner_id&mid=$get_info[motorized_motor_id]
			&com=$comtag&permit_type=$tag&stat=$status' class='subnavwhite'>
			$comtag</a>";
			if ($checkrentype1['renewaltype'] == '2' and $stat=="ReNew" and $get_info[transaction]=="ReNew") {
				print "&nbsp<a href='index.php?part=4&class_type=Permits&itemID_=1221
				&owner_id=$owner_id&mid=$get_info[motorized_motor_id]
				&com=RenewVeh&permit_type=$tag&stat=$status' class='subnavwhite'>
				Renew</a></td>
				</tr>\n";
			}
			print "</td></tr>\n";
			
		}
	}
	if ($itemID_<>'') {
	//check if paid
	if ($com<>'Drop') {
	$chkp = SelectDataWhere($dbtype,$dbLink,$vehicle,
				"where motorized_operator_id=$owner_id 
				and motorized_motor_id=$get_info[motorized_motor_id]");
	$chkp = FetchArray($dbtype,$chkp);
	$chkp = $chkp[status];
	} else {
	$chkp = 0;
	}

		if ($chkp<>1) {
		print "&nbsp<a href='index.php?part=4&class_type=Permits&comm=dropping
			&itemID_=1221&owner_id=$owner_id&mid=$get_info[motorized_motor_id]
			&com=$comtag&permit_type=$tag&stat=$status&fordrop=drop' class='subnavwhite'>
			$comtag</a></td>
			</tr>\n";
		}
	}



}//end while

print "</table>";

} // end if not occu



//display fees
print "<br><br>";
require_once "includes/form_add_mtoassesment.html";
if ($permit_type=='Fishery' and $owner_id<>'') {
if ($stat=='') {
	$stat='New';
}

$getboat = SelectDataWhere($dbtype,$dbLink,"fish_boat","where owner_id=$owner_id");
while ($getb = FetchRow($dbtype,$getboat))
{
$getfee = SelectDataWhere($dbtype,$dbLink,"boat_fee",
			"where boat_type='$getb[4]' and
                        range_lower<=$getb[5] and range_higher>=$getb[5] and
                        transaction='$stat' and active = 1");
$getnum = NumRows($dbtype,$getfee);
        if ($getnum==0) {
	            $getfee =  SelectDataWhere($dbtype,$dbLink,"boat_fee",
                        "where boat_type='$getb[4]' and
                        range_lower<=$getb[5] and range_higher=0 and
                        transaction='$stat' and active = 1");

        }
$getfee1 = FetchArray($dbtype,$getfee);
$ttfee = $ttfee+$getfee1[amt]; 
}


$getboat = SelectDataWhere($dbtype,$dbLink,"fish_assess","where owner_id='$owner_id'");
$ttfee1 = 0;
while ($getb = FetchArray($dbtype,$getboat))
{
$getfee = SelectDataWhere($dbtype,$dbLink,"culture_fee",
			"where culture_id='$getb[culture_id]' and
		    transaction='$stat' and active = '1'  ");
			
$getnum = FetchArray($dbtype,$getfee);
		if ($getnum[fee_type]=='3') {
				$getfee = SelectDataWhere($dbtype,$dbLink,"culture_range",
					"where culture_id='$getb[culture_id]' and
		             range_lower<=$getb[amt] and range_higher>=$getb[amt] ");
				$getnum = NumRows($dbtype,$getfee);
		        	if ($getnum==0) {
			
	            		$getfee =  SelectDataWhere($dbtype,$dbLink,"culture_range",
                        "where culture_id='$getb[culture_id]' and
                        range_lower<=$getb[amt] and range_higher=0");
                	}
    	} 

        
$getfee1 = FetchArray($dbtype,$getfee);
//$ttfee1 = $ttfee1+$getfee1[amt]; 
$ttfee1 = $nfishfee;
}



?>
<table width=420>
<tr>
<td>Fees from Boat Registration</td><td><?php echo number_format($ttfee,2); ?></td>
</tr>
<tr>
<td>Fees from Fish Activities</td><td><?php echo number_format($ttfee1,2); ?></td>
</tr>
<tr>
<td>Other Fees</td><td></td></tr>
<?php 
$getot1 = SelectDataWhere($dbtype,$dbLink,"ebpls_fishery_fees","where permit_type='$stat' and active=1");
while ($getj = FetchArray($dbtype,$getot1))
{
?>
<tr>
<td align=right><?php echo $getj[fee_desc]; ?> &nbsp; -----------------</td><td><?php echo $getj[fee_amount]; ?></td>
</tr>
<?php
$ff = $ff + $getj[fee_amount];
}
?>
<tr><td></td><td>_______</td></tr>
<tr>
<td>Total Assessement</td><td><?php $totass = $ttfee+$ttfee1+$ff; echo number_format($totass,2); ?></td>
</tr>
 
<!--<td align=center><input type=submit name=subfish value=Save></td><td></td></tr>-->
<?php
$totalfee = $totass;
include_once "includes/other_permit_penalty.php";
$totass=$totass + $otherpen + $otherint + $otherlate + $backtaxcompute;
$totalfeenf = number_format($totass,2);
?>
<tr>
<td>Grand Total</td><td><?php echo number_format($totass,2); ?></td>
</tr>
<?php
}

if ($tot >0 || $permit_type=='Occupational' || $permit_type=='Peddlers') {
	if ($itemID_=='') {
		$al = 'left';
	} else {
		$al = 'center';
	}
if ($permit_type=='Motorized' || $permit_type=='Franchise') {
	
//Start of Sceenario 2
$checkrentype = @mysql_query("select * from ebpls_motorized_penalty where permit_type = '$permit_type'");
$checkrentype1 = @mysql_fetch_assoc($checkrentype);
if ($checkrentype1['renewaltype'] == '2' and $stat=="ReNew") {
	$totvec=SelectDataWhere($dbtype,$dbLink,"ebpls_motorized_vehicles",
		"where motorized_operator_id = $owner_id 
		and permit_type='$tag' and retire>'2'");
	$rentot = NumRows($dbtype,$totvec);
?>
<input type=hidden name=totalrenvec value=<?php echo $rentot; ?>>
<br><br>
<table border=1 width=100%>
<tr>
<td align=center> Motor Model </td>
<td align=center> Plate Number </td>
<td align=center> Body Number </td>
<td align=center> Body Color </td>
<td align=center> Route </td>
<td align=center> Line Type </td>
<td align=center> LTO Registration No </td>
<td align=center> Certificate of Registration </td>
</tr>
<?php
//populate vehicle

	$result1 = SelectDataWhere($dbtype,$dbLink, $vehicle, 
				"where motorized_operator_id = $owner_id 
				and permit_type='$tag' and retire>'2'") or die("");
	while ($get_info12 = FetchArray($dbtype,$result1)){
?>
		<tr>
		<td align=center>&nbsp;<?php echo $get_info12[motorized_motor_model]; ?>&nbsp;</td>
	
                <td align=center>&nbsp;<?php echo $get_info12[motorized_plate_no]; ?>&nbsp;</td>
		<td align=center>&nbsp;<?php echo $get_info12[motorized_body_no]; ?>&nbsp;</td>
                <td align=center>&nbsp;<?php echo $get_info12[body_color]; ?>&nbsp;</td>
		<td align=center>&nbsp;<?php echo $get_info12[route]; ?>&nbsp;</td>
                <td align=center>&nbsp;<?php echo $get_info12[linetype]; ?>&nbsp;</td>
		<td align=center>&nbsp;<?php echo $get_info12[lto_number]; ?>&nbsp;</td>
                <td align=center>&nbsp;<?php echo $get_info12[cr_number]; ?>&nbsp;</td>
		<td>
<?php


}//end while

print "</table><br>";
} elseif ($stat =="Transfer/Dropping") {
	$totvec=SelectDataWhere($dbtype,$dbLink,"ebpls_motorized_vehicles",
		"where motorized_operator_id = '$owner_id' 
		and permit_type='$tag' and retire='1'");
	$rentot = NumRows($dbtype,$totvec);
?>
<input type=hidden name=totalrenvec value=<?php echo $rentot; ?>>
<br><br>
<table border=1 width=100%>
<tr>
<td align=center> Motor Model </td>
<td align=center> Plate Number </td>
<td align=center> Body Number </td>
<td align=center> Body Color </td>
<td align=center> Route </td>
<td align=center> Line Type </td>
<td align=center> LTO Registration No </td>
<td align=center> Certificate of Registration </td>
</tr>
<?php
//populate vehicle

	$result1 = SelectDataWhere($dbtype,$dbLink, $vehicle, 
				"where motorized_operator_id = $owner_id 
				and permit_type='$tag' and retire='1'") or die("");
	while ($get_info12 = FetchArray($dbtype,$result1)){
?>
		<tr>
		<td align=center>&nbsp;<?php echo $get_info12[motorized_motor_model]; ?>&nbsp;</td>
	
                <td align=center>&nbsp;<?php echo $get_info12[motorized_plate_no]; ?>&nbsp;</td>
		<td align=center>&nbsp;<?php echo $get_info12[motorized_body_no]; ?>&nbsp;</td>
                <td align=center>&nbsp;<?php echo $get_info12[body_color]; ?>&nbsp;</td>
		<td align=center>&nbsp;<?php echo $get_info12[route]; ?>&nbsp;</td>
                <td align=center>&nbsp;<?php echo $get_info12[linetype]; ?>&nbsp;</td>
		<td align=center>&nbsp;<?php echo $get_info12[lto_number]; ?>&nbsp;</td>
                <td align=center>&nbsp;<?php echo $get_info12[cr_number]; ?>&nbsp;</td>
		<td>
<?php


}//end while

print "</table><br>";
}
}
//End of Scenario 2
?>
<table border = 1 align=<?php echo $al; ?> width="50%">
<tr> 
<td>Fee</td> <td>Amount </td>
<?php
if ($permit_type=='Motorized' || $permit_type=='Franchise') {
?>
<td>No. of Units</td><td>Total Amount</td></tr>
<?php
}

if ($permit_type=='Motorized' || $permit_type=='Franchise') {

if ($stat != "New") {
include "includes/mot_backtax.php";
}
$getyearnow = date('Y');
$totvehiclesren=SelectDataWhere($dbtype,$dbLink,"ebpls_motorized_vehicles",
					"where motorized_operator_id = '$owner_id' 
					and permit_type='$permit_type' and retire=0 and transaction='ReNew'");
$totvehiclesren1 = NumRows($dbtype,$totvehiclesren);
		
//clear all defaults
$checkvalues = @mysql_query("delete from ebpls_fees_paid where permit_type='$permit_type' and owner_id = '$owner_id' and permit_status = '$stat' and input_date like '$getyearnow%' and active='1'");
if ($stat == "New") {
	$checkvalues = @mysql_query("delete from ebpls_fees_paid where permit_type='$permit_type' and owner_id = '$owner_id' and permit_status = 'New and input_date like '$getyearnow%' and active='1'");
}
//load all defaults
$getfees122 = @mysql_query("select * from ebpls_mtop_fees where permit_type='$stat'");
while ($getfees = @mysql_fetch_assoc($getfees122)) {
	$de++;
	if ($checkrentype1['renewaltype'] == '2' and $stat=="ReNew") { //Scenario 2
		$nbsyear = date('Y');
		$updatetempfees = @mysql_query("update ebpls_mtop_temp_fees set active = '0' where year < '$nbsyear' and owner_id  = '$owner_id'");
		
		$selectveh = @mysql_query("select * from ebpls_motorized_vehicles where motorized_operator_id = '$owner_id' and retire='4'");
		$numberofveh = 0;
		while ($selectveh1 = @mysql_fetch_assoc($selectveh)) {
			$deletetempfees = @mysql_query("delete from ebpls_mtop_temp_fees where year = '$nbsyear' and owner_id  = '$owner_id' and fee_id = '$getfees[fee_id]' and active = '1' and mid = '$selectveh1[motorized_motor_id]'");
			$insertintotempfees = @mysql_query("insert into ebpls_mtop_temp_fees values ('', '$getfees[fee_id]', '$owner_id', '$selectveh1[motorized_motor_id]', '', '$usern', now(), '1','$nbsyear')");
			$numberofveh++;
			$selecttemp = @mysql_query("select * from ebpls_mtop_temp_fees where owner_id = '$owner_id' and fee_id = '$getfees[fee_id]' and mid = '$selectveh1[motorized_motor_id]'");
			$selecttemp1 = @mysql_num_rows($selecttemp);
			$getifbill = $selecttemp1 / $getfees['nyears'];
			
			$getifbill1 = strpos($getifbill, ".");
			
			if ($getfees['nyears'] == 1) {
				$checkfees = @mysql_query("select * from ebpls_fees_paid where owner_id = '$owner_id' and fee_desc = '$getfees[fee_desc]' and permit_status = '$stat' and input_date like '$nbsyear%' and active = '1' and multi_by = '$selectveh1[motorized_motor_id]'");
				$checkfees1 = @mysql_num_rows($checkfees);
				$checkfees2 = @mysql_fetch_assoc($checkfees);
				if ($checkfees1 == 0) {
					//echo "$origtot VooDoo<br>";
					$insertintotemp = @mysql_query("insert into ebpls_fees_paid values ('', '$owner_id', '$getfees[fee_desc]', '$getfees[fee_amount]', '$selectveh1[motorized_motor_id]', '$permit_type', '$stat','1', '$usern', now())");
				} else {
					$newtot = $checkfees2[tot] + 1;
					
					$updatetemp = @mysql_query("update ebpls_fees_paid set tot = '$newtot' where  owner_id = '$owner_id' and fee_desc = '$getfees[fee_desc]' and permit_status = '$stat' and input_date like = '$nbsyear%' and multi_by = '$selectveh1[motorized_motor_id]'");
				}
			} else {
				
				if ($getifbill1 == 0 || $getifbill1 == "") {
					$checkfees = @mysql_query("select * from ebpls_fees_paid where owner_id = '$owner_id' and fee_desc = '$getfees[fee_desc]' and permit_status = '$stat' and input_date like '$nbsyear%' and permit_type='$permit_type' and multi_by = '$selectveh1[motorized_motor_id]'");
					$checkfees1 = @mysql_num_rows($checkfees);
					$checkfees2 = @mysql_fetch_assoc($checkfees);
					if ($checkfees1 == 0) {
						$insertintotemp = @mysql_query("insert into ebpls_fees_paid values ('', '$owner_id', '$getfees[fee_desc]', '$getfees[fee_amount]', '$selectveh1[motorized_motor_id]', '$permit_type', '$stat','1', '$usern', now())");
						
					} else {
						$newtot = $checkfees2['multi_by'] + 1;
						$updatetemp = @mysql_query("update ebpls_fees_paid set multi_by = '$newtot' where  owner_id = '$owner_id' and fee_desc = '$getfees[fee_desc]' and permit_status = '$stat' and  input_date like '$nbsyear%' and multi_by = '$selectveh1[motorized_motor_id]'");
					}
				}
			}
		}
		$selectnew = mysql_query("select * from ebpls_motorized_vehicles where motorized_operator_id = '$owner_id' and transaction = 'New' and retire = '0'");
		$selectnew1 = mysql_num_rows($selectnew);
		if ($selectnew1 > 0){
			$getfeesfornew = @mysql_query("select * from ebpls_mtop_fees where permit_type='New'");
			while ($getfeesfornew1 = @mysql_fetch_assoc($getfeesfornew)) {
				$checkifexist = mysql_query("select * from ebpls_fees_paid where owner_id = '$owner_id' and permit_status = 'New' and active = '1'");
				$checkifexist1 = mysql_num_rows($checkifexist);
				if ($checkifexist1 > 0) {
					$updateexist = mysql_query("update ebpls_fees_paid set multi_by = '$selectnew1' where owner_id = '$owner_id' and permit_status = 'New' and active = '1'");
				} else {
					$insertfornew = @mysql_query("insert into ebpls_fees_paid values ('', '$owner_id', '$getfees[fee_desc]', '$getfees[fee_amount]', '$selectnew1', '$permit_type', 'New','1', '$usern', now())");
				}
			}
		}
		if ($nRMV1 == '1') {
			$nmsyear = date('Y');
			$deletemuna = @mysql_query("delete from ebpls_fees_paid where permit_status='New' and owner_id = '$owner_id' and permit_type = '$permit_type' and active = '1' and input_date like '$nmsyear%'");
			$getfees1 = @mysql_query("select * from ebpls_mtop_fees where permit_type='New'");
			while ($getfees = @mysql_fetch_assoc($getfees1)) {
				$insertfornew = @mysql_query("insert into ebpls_fees_paid values ('', '$owner_id', '$getfees[fee_desc]', '$getfees[fee_amount]', '$wert1', '$permit_type', 'New','1', '$usern', now())");
			}
		}
		
		//Normal Scenario
	} else {
		
		$nbsyear = date('Y');
		$updatetempfees = @mysql_query("update ebpls_mtop_temp_fees set active = '0' where year < '$nbsyear' and owner_id  = '$owner_id'");
		if ($stat == "New") {
			$getvechswe = @mysql_query("select * from ebpls_motorized_vehicles where motorized_operator_id = '$owner_id' and retire = '0'");
		} else {
			$getvechswe = @mysql_query("select * from ebpls_motorized_vehicles where motorized_operator_id = '$owner_id' and retire = '0' and transaction = '$stat'");
		}
		while ($getvechessq = @mysql_fetch_assoc($getvechswe)) {
			$deletetempfees = @mysql_query("delete from ebpls_mtop_temp_fees where year = '$nbsyear' and owner_id  = '$owner_id' and fee_id = '$getfees[fee_id]' and mid = '$getvechessq[motorized_motor_id]'");
			$insertintotempfees = @mysql_query("insert into ebpls_mtop_temp_fees values ('', '$getfees[fee_id]', '$owner_id', '$getvechessq[motorized_motor_id]', '', '$usern', now(), '1','$nbsyear')");
			$selecttemp = @mysql_query("select * from ebpls_mtop_temp_fees where owner_id = '$owner_id' and fee_id = '$getfees[fee_id]' and mid = '$getvechessq[motorized_motor_id]'");
			$selecttemp1 = @mysql_num_rows($selecttemp);
			$getifbill = $selecttemp1 / $getfees['nyears'];
			$getifbill1 = strpos($getifbill, ".");
			if ($getfees['nyears'] == 1) {
				$insertintotemp = @mysql_query("insert into ebpls_fees_paid values ('', '$owner_id', '$getfees[fee_desc]', '$getfees[fee_amount]', '$getvechessq[motorized_motor_id]', '$permit_type', '$stat','1', '$usern', now())");
			} else {
				if ($getifbill1 == 0) {
					$insertintotemp = @mysql_query("insert into ebpls_fees_paid values ('', '$owner_id', '$getfees[fee_desc]', '$getfees[fee_amount]', '$getvechessq[motorized_motor_id]', '$permit_type', '$stat','1', '$usern', now())");
				}
			}
		} //vehicles

	}
}
if ($stat == "ReNew") {
			
	$greatyear11 = date('Y');
	$checkvalues = @mysql_query("delete from ebpls_fees_paid where permit_type='$permit_type' and owner_id = '$owner_id' and permit_status = 'New' and input_date like '$getyearnow%' and active='1'");
	$getnewvech31 = @mysql_query("select * from ebpls_motorized_vehicles where motorized_operator_id = '$owner_id' and transaction = 'New' and create_ts like '$greatyear11%'");
	$checkifnewis = @mysql_num_rows($getnewvech31);
	if ($checkifnewis > 0) {
		while ($getnewvech311 = @mysql_fetch_assoc($getnewvech31)) {
			$getnewfees11 = @mysql_query("select * from ebpls_mtop_fees where permit_type = 'New' and active = '1'");
			while ($getnewfees111 = @mysql_fetch_assoc($getnewfees11)) {
				$deletetempfees = @mysql_query("delete from ebpls_mtop_temp_fees where year = '$nbsyear' and owner_id  = '$owner_id' and fee_id = '$getnewfees111[fee_id]' and mid = '$getnewvech311[motorized_motor_id]'");
				$insertintotempfees = @mysql_query("insert into ebpls_mtop_temp_fees values ('', '$getnewfees111[fee_id]', '$owner_id', '$getnewvech311[motorized_motor_id]', '', '$usern', now(), '1','$nbsyear')");
				$selecttemp = @mysql_query("select * from ebpls_mtop_temp_fees where owner_id = '$owner_id' and fee_id = '$getnewfees111[fee_id]' and mid = '$getnewvech311[motorized_motor_id]'");
				$selecttemp1 = @mysql_num_rows($selecttemp);
				$getifbill = $selecttemp1 / $getnewfees111['nyears'];
				$getifbill1 = strpos($getifbill, ".");
				if ($getfees['nyears'] == 1) {
					$insertintotemp21 = @mysql_query("insert into ebpls_fees_paid values ('', '$owner_id', '$getnewfees111[fee_desc]', '$getnewfees111[fee_amount]', '$getnewvech311[motorized_motor_id]', '$permit_type', 'New','1', '$usern', now())");
				} else {
					if ($getifbill1 == 0) {
						$insertintotemp21 = @mysql_query("insert into ebpls_fees_paid values ('', '$owner_id', '$getnewfees111[fee_desc]', '$getnewfees111[fee_amount]', '$getnewvech311[motorized_motor_id]', '$permit_type', 'New','1', '$usern', now())");
					}
				}
			}
		}
	} //Vehicles
}
//get from fees paid
if ($checkrentype1['renewaltype'] == '2' and $stat=="ReNew") {
	$getvecsagain = @mysql_query("select * from ebpls_motorized_vehicles where motorized_operator_id = '$owner_id' and transaction = '$stat' and retire > '2' order by motorized_motor_id");
	
} else {
	$getvecsagain = @mysql_query("select * from ebpls_motorized_vehicles where motorized_operator_id = '$owner_id' and transaction = '$stat' order by motorized_motor_id");

}
$totalfee = 0;
while ($getvecsagain1 = @mysql_fetch_assoc($getvecsagain)) {
	$getfees1 = @mysql_query("select * from ebpls_fees_paid where permit_type= '$permit_type' and permit_status = '$stat' and owner_id = '$owner_id' and input_date like '$getyearnow%' and multi_by = '$getvecsagain1[motorized_motor_id]' order by fee_paid_id asc");
	$vechss++;
	print "<tr><td colspan = \"4\">&nbsp;Vehicle $getvecsagain1[motorized_motor_model] $getvecsagain1[motorized_plate_no] ($stat)&nbsp;</td></tr>\n";
	while ($getfees = @mysql_fetch_assoc($getfees1)) {
		print "<tr>\n";
//foreach ($getfee as $field )
		print "<td>&nbsp;$getfees[fee_desc]&nbsp</td>\n";
		print "<td align=right>&nbsp;$getfees[fee_amount]&nbsp</td>\n";
		print "<td align=right>&nbsp;1&nbsp;</td>\n";
		$tamt =$getfees[fee_amount];
		$tamt12 =$getfees[fee_amount];
		$totalamt = number_format($tamt,2);
		print "<td align=right>&nbsp;$totalamt&nbsp;</td>";
		print "</tr>\n";
		$totalfee = $totalfee + $tamt;
		$totalfee1 = $totalfee1 + $tamt;
		$totalfeeren = $totalfeeren + $tamt12 + $tnewamt12;
	}
}	


if ($stat == "ReNew") {
	$portsyearr = date('Y');
	$getvecsagain = @mysql_query("select * from ebpls_motorized_vehicles where motorized_operator_id = '$owner_id' and transaction = 'New' and create_ts like '$portsyearr%' order by motorized_motor_id");
	$totalfeenew = 0;
	while ($getvecsagain1 = @mysql_fetch_assoc($getvecsagain)) {
	$getfees1 = @mysql_query("select * from ebpls_fees_paid where permit_type= '$permit_type' and permit_status = 'New' and owner_id = '$owner_id' and input_date like '$getyearnow%' and multi_by = '$getvecsagain1[motorized_motor_id]' order by fee_paid_id asc");
		$vechss++;
		print "<tr><td colspan = \"4\">&nbsp;Vehicle $getvecsagain1[motorized_motor_model] $getvecsagain1[motorized_plate_no] (New)&nbsp;</td></tr>\n";
		while ($getfees = @mysql_fetch_assoc($getfees1)) {
			print "<tr>\n";
//foreach ($getfee as $field )
			print "<td>&nbsp;$getfees[fee_desc]&nbsp</td>\n";
			print "<td align=right>&nbsp;$getfees[fee_amount]&nbsp</td>\n";
			print "<td align=right>&nbsp;1&nbsp;</td>\n";
			$tnewamt =$getfees[fee_amount];
			$tnewamt12 =$getfees[fee_amount];
			$totalnewamt = number_format($tnewamt,2);
			print "<td align=right>&nbsp;$totalnewamt&nbsp;</td>";
			print "</tr>\n";

			$totalfee = $totalfee + $tnewamt;
			$totalfeeren = $totalfeeren + $tamt12 + $tnewamt12;
		}
	}
}
include_once "includes/other_permit_penalty.php";
$totalfee=$totalfee + $otherpen + $otherint + $otherlate + $backtaxcompute;
$totalfeenf = number_format($totalfee + $gettotlate + $gettotsurcharge + $gettotinterest + $gettotbak,2);
?>
<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td><td>Grand Total:</td><td align=right>
&nbsp;<?php echo $totalfeenf; ?></td></tr>
</table>
</td></table>
<?php
} else {
//occu depolt

$cntfee = SelectDataWhere($dbtype,$dbLink,"ebpls_fees_paid",
			"where permit_type='$permit_type' and 
			owner_id=$owner_id and permit_status='$stat'");
$cntit = NumRows($dbtype,$cntfee);
//load all defaults
if ($cntit==0) {
        $cntfee = SelectDataWhere($dbtype,$dbLink,$temptbl,
			"where owner_id=$owner_id");
$cntit = NumRows($dbtype,$cntfee);
                if ($cntit==0) {

		//get depolt
		$getdepolt = SelectMultiTable($dbtype,$dbLink,$fee,
			"fee_id, fee_desc, fee_amount",
			"where permit_type='$stat' and active=1");
                                                                             
		while ($getfee = FetchRow($dbtype,$getdepolt)){
//foreach ($getfee as $field )
		$getfee[1]=addslashes($getfee[1]);
		
		$insertfee = InsertQuery($dbtype,$dbLink,$temptbl,
                	     "(fee_id, fee_desc, fee_amount, 
			     permit_type, owner_id)",
	                     "$getfee[0], '$getfee[1]',
		              $getfee[2],'$permit_type', $owner_id");
                } //while
        } //2nd cnt
}

$mfee = SelectMultiTable($dbtype,$dbLink,"ebpls_fees_paid",
			"fee_paid_id, fee_desc, fee_amount",
                        "where permit_type='$permit_type' and owner_id=$owner_id 
			and permit_status='$stat'");

while ($getfee = FetchRow($dbtype,$mfee)){
print "<tr>\n";
foreach ($getfee as $field )
print "<td>&nbsp;$field&nbsp</td>\n";
print "</tr>\n";

}

$mfee = SelectMultiTable($dbtype,$dbLink,$temptbl,
		"fee_id, fee_desc, fee_amount", 
		"where permit_type='$permit_type'
		 and owner_id=$owner_id");

while ($getfee = FetchRow($dbtype,$mfee)){
print "<tr>\n";
foreach ($getfee as $field )
print "<td>&nbsp;$field&nbsp;</td>\n";
print "</tr>\n";
}//end while
//total fee
$tfee = SelectMultiTable($dbtype,$dbLink,"ebpls_fees_paid",
			"sum(fee_amount)","where permit_type='$permit_type'
			and owner_id=$owner_id and permit_status='$stat'");
$paidfee=FetchRow($dbtype,$tfee);
$paidfee=$paidfee[0];
$tfee = SelectMultiTable($dbtype,$dbLink,$temptbl,
			"sum(fee_amount)","where permit_type='$permit_type'
			and owner_id=$owner_id");
$totalfee = FetchRow($dbtype,$tfee);
if ($totalfee[0] == "" || $totalfee[0] == 0 and $permit_type=='Occupational') 
{
	$tfee = @mysql_query("select sum(fee_amount) from ebpls_occu_fees where permit_type='$stat'
			and active='1'");
$totalfee = @mysql_fetch_row($tfee);


}
if ($totalfee[0] == "" || $totalfee[0] == 0 and $permit_type=='Peddlers') 
{
	$tfee = @mysql_query("select sum(fee_amount) from ebpls_peddlers_fees where permit_type='$stat'
			and active='1'");
$totalfee = @mysql_fetch_row($tfee);
}
$totalofee = $totalfee[0];
if ($permit_type == 'Occupational') {
	$totalfee = $totalfee[0];
}
include_once "includes/other_permit_penalty.php";
$totalfee=$totalofee + $otherpen + $otherint + $otherlate + $backtaxcompute;
$totalfeenf = number_format($totalfee,2);
?>
<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td><td>Grand Total:</td><td>&nbsp;<?php echo $totalfeenf; ?></td></tr>
</table>
</td></table>
<?php
}
if ($itemID_<>'') {
require_once "includes/form_add_lastpermit.html";
}
}
?>

<script language='Javascript'>
                                                                                                               
function checkvek(varx)
{
        if(varx == 0) {
                alert ('Cannot Delete Vehicle That Is Already Assessed');
        } else {
		alert ('Record Deleted');
	}
                                                                                                               
}
</script>
