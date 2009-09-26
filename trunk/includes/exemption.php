<?php
$stsearch=SelectMultiTable($dbtype,$dbLink,"ebpls_business_enterprise",
		"business_category_code","where owner_id=$owner_id and 
		 business_id=$business_id");
$stsearch=FetchRow($dbtype,$stsearch);

$ndsearch=SelectMultiTable($dbtype,$dbLink,"ebpls_business_category",
		"tax_exemption","where business_category_code='$stsearch[0]'");
$ndsearch=FetchRow($dbtype,$ndsearch);

//echo $totexempt;
//$totexempt=$grandamt-$notexempt;
$Exemption=0;
$Exemption=($tottax * ($ndsearch[0] / 100));
if ($Exemption > 0) {
	if ($itemID_==4212) {
?>
		<tr><td width=100></td> <td></td><td align=right>Tax :</td><td align=right>
<?php
		$taxamt=number_format($grandamt-$nyotfee,2);
		print "$taxamt </td></tr>";
		$alignment='right';
		$Pesosign='Php  ';
	} else {
		$alignment='right';
	}
	print "<tr><td width=100></td>";
	if ($itemID_<>2212) {
		print " <td></td>";
//	}
//	if ($itemID_<>2212) {

		$alignment='right';
		$Pesosign='';
	}

	if ($itemID_==2212) {
		$extratd = "<td></td>";
	} else {
		$extratd="";
	}
	print "$extratd<td align=right>$ndsearch[0]% Exemption :</td><td align='$alignment' bgcolor=yellow>";
	$grandamt=$grandamt-$Exemption;
	$exemptot = $exemptot + $Exemption;
	$Exemption=number_format($Exemption,2);
	print " $Pesosign$Exemption</td></tr>";
	
	$Exemption=0;
}

?>
