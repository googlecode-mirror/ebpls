<?php
if (empty($bussEvent)){
$bussEvent=1;	
}

if ($confx==1) {
//delete tax and iba pang kasama nito
include_once "class/TaxFeeOtherChargesClass.php";
$DelTax = new TaxFee;
$strwhere = "taxfeeid='$bcode'";
$DelTax->DeleteTax("ebpls_buss_taxfeeother",$strwhere);

$strwhere = "taxfeeid='$bcode'";
$DelTax->DeleteTax("ebpls_buss_taxrange",$strwhere);

$strwhere = "complex_taxfeeid='$bcode'";
$DelTax->DeleteTax("ebpls_buss_complex",$strwhere);

}

?>
<table width=100%>
<tr>
<td width=5%>#</td>
<td width=40%>&nbsp;Description</td>
<td width=10%>&nbsp;Formula Type</td>
<td width=5% align=left>&nbsp;Type</td>
<td width=5% align=center>&nbsp;Indicator</td>
<td width=10% align=center>&nbsp;Amt/For</td>
<td width=25% align=center>&nbsp;Action</td>
</tr>
<script language="Javascript">
function DelTax(cc)
{
         var _FRM = document._FRM;
        doyou = confirm("Record Will Be Deleted, Continue?");
                                                                                                 
                                                                                                 
        if (doyou==true) {
                _FRM.bcode.value = cc;
               _FRM.confx.value = 1;
        } else {
                _FRM.confx.value=0;
                return false;
        }
                _FRM.submit();
               return true;
}
</script>
<input type=hidden name=bcode value=''>
<input type=hidden name=confx value=''>

<?php
//and taxfeetype=1
	$listmyid = "SELECT *,a.tfo_id FROM ebpls_buss_taxfeeother a, ebpls_buss_tfo b where a.tfo_id=b.tfoid and natureid='$natureid' ORDER BY a.tfo_id ASC";
	
	$Dbresult=th_query($listmyid);
	while($dtarow 	= @mysql_fetch_array($Dbresult)){
			$myrow++;	
			include'tablecolor-inc.php';
			echo "<tr bgcolor=$varcolor>"; 
			echo "<td>&nbsp;$myrow</td>";
			echo "<td>&nbsp;$dtarow[tfodesc]</td>";
			switch ($dtarow[mode]){
			case 0:$itaxtype='Normal';break;
			case 1:$itaxtype='Normal';break;	
			case 2:$itaxtype='Complex';break;	
			}
			echo "<td>&nbsp;$itaxtype</td>";
			switch ($dtarow[taxtype]){
			case 1:$itaxtype='New';break;	
			case 2:$itaxtype='Renewal';break;	
			case 3:$itaxtype='Retire';break;	
			case 5:$itaxtype='New PQ';break;
			}
			echo "<td>&nbsp;$itaxtype</td>";
			switch ($dtarow[indicator]){
			case 1:$myIndicator='CONSTANT';break;	
			case 2:$myIndicator='FORMULA';break;	
			case 3:$myIndicator='RANGE';break;	
			}
			echo "<td>&nbsp;$myIndicator</td>";
			echo "<td align=right>&nbsp;$dtarow[amtformula]</td>";
			
			if ($dtarow[indicator]==3){
			echo "<td align=center>&nbsp;<a href=$PHP_SELF?natureid=$dtarow[natureid]&action_=1&actionID=1&part=$part&class_type=Preference&pref_type=Business&selMode=$selMode&natureaction=Edit&bussEvent=$bussEvent&aTFOID=$dtarow[tfoid]&valueof_ResultId=1&aTAXFEEid=$dtarow[taxfeeid]&bussEvent=$dtarow[taxfeetype]>Edit</a> | ";
?>
 <a href='#' onclick='DelTax(<?php echo $dtarow[taxfeeid]; ?>);'>Delete</a></td>
<?php
// <a href=$PHP_SELF?natureid=$dtarow[natureid]&aTFOID=$dtarow[tfoid]&valueof_tfoid=$dtarow[taxfeeid]&part=$part&class_type=Preference&pref_type=Business&selMode=$selMode&natureaction=Edit&actionID=1&action_=1>DeActivate</a></td>
			}
			elseif ($dtarow[indicator]==2) {
			echo "<td align=center>&nbsp;<a href=$PHP_SELF?natureid=$dtarow[natureid]&action_=1&actionID=1&part=$part&class_type=Preference&pref_type=Business&selMode=$selMode&natureaction=Edit&bussEvent=$bussEvent&aTFOID=$dtarow[tfoid]&eventComplex=$dtarow[taxfeemode]&aTAXFEEid=$dtarow[taxfeeid]&thisID=1&bussEvent=$dtarow[taxfeetype]>Edit</a> | ";
?>
 <a href='#' onclick='DelTax(<?php echo $dtarow[taxfeeid]; ?>);'>Delete</a></td>
<?php

// <a href=$PHP_SELF?natureid=$dtarow[natureid]&aTFOID=$dtarow[tfoid]&valueof_tfoid=$dtarow[taxfeeid]&part=$part&class_type=Preference&class_type=Preference&pref_type=Business&selMode=$selMode&natureaction=Edit&actionID=1&action_=1>DeActivate</a></td>";	
			}
			else {
			echo "<td align=center>&nbsp;<a href=$PHP_SELF?natureid=$dtarow[natureid]&action_=1&actionID=1&part=$part&class_type=Preference&pref_type=Business&selMode=$selMode&natureaction=Edit&bussEvent=$bussEvent&aTFOID=$dtarow[tfoid]&aTAXFEEid=$dtarow[taxfeeid]&bussEvent=$dtarow[taxfeetype]>Edit</a> | ";
?>
 <a href='#' onclick='DelTax(<?php echo $dtarow[taxfeeid]; ?>);'>Delete</a></td>
<?php

//<a href=$PHP_SELF?natureid=$dtarow[natureid]&aTFOID=$dtarow[tfoid]&valueof_tfoid=$dtarow[taxfeeid]&part=$part&class_type=Preference&pref_type=Business&selMode=$selMode&natureaction=Edit&actionID=1&action_=1>DeActivate</a></td>";
			}
			echo "</tr>";
		}
//natureid=18&action_=1&actionID=1&part=4&selMode=ebpls_nbusiness&natureaction=Edit
?>
</table>
