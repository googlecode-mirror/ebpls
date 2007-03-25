<?php
include_once '../setup/setting.php';
include_once '../ebpls5501.php';
//dbConnect();
/*
echo "$xnatureid<br>";
echo "$xtaxfeeid<br>";
echo "$xtfoid<br>";
*/
$getFormula=mysql_query("Select taxfeeamtfor From ebpls_buss_taxfeeother where natureid='$xnatureid' and taxfeeid='$xtaxfeeid' and tfoid='$xtfoid'", $link2db) or die ("Couldn't connect to the database..");
$getRow=mysql_num_rows($getFormula);
if ($getRow==1){
	$getRec=mysql_fetch_array($getFormula);
	//$cForm=$getRec[taxfeeamtfor];
}
else {
echo "yahooooooooooo..... nghekkkkkkkknghekkkkkkkk";	
}
?>

<html>
<head><title></title></head><body><form>
<input type=hidden name=cForm value=<?php echo $cForm;?>>
<input type=hidden name=cInvestment value=<?php echo $cInvestment;?>>
<input type=hidden name=xnatureid value=<?php echo $xnatureid;?>>
<input type=hidden name=xtaxfeeid value=<?php echo $xtaxfeeid;?>>
<input type=hidden name=xtfoid value=<?php echo $xtfoid;?>>

Enter amount here: <input type=text name=cInvestment size=15 value="<?php echo $cInvestment;?>"><br>
Enter formula here: <input type=text name=cForm size=15 value="<?php echo $cForm;?>"> <input type=submit name=iSubmitForm value="Check Formula"><br><br>

<font color="#ff0033"><i><b>Note:</b></font><font color="#333366"> If error appears in this module (e.g. parse error!) please check the formula you have entered. Thank you.</font></i><br><br>

<u>List of Sample Formulas:</u><br><br>
<li>*.1)*.015)</li>
<li>*.1))</li>
<li>-100)*.015)</li>
<li>*.1)+(1000-999)*.25)</li>
<li>*.1)+(1000+999+888+777)*.25)+700)</li>

<br><hr size=2 width=100%><br>



<?php
if (isset($iSubmitForm)){
$cInvestment=strip_tags(trim($cInvestment));
//$cForm="*.1)+100)";
echo $cInvestment."=".$cForm;
if (is_numeric($cInvestment) and !empty($cForm)){
	$cForm=$cForm;
	$parentheses="((";
	$setform=$cInvestment.$cForm;
	$openpar = substr_count($setform, '(');
	$closepar = substr_count($setform, ')');
	$closepar1 = $closepar-2;
	if ($openpar==$closepar1) {
	echo "Formula: $parentheses$setform ===>>> ";
	eval ("\$compFormula1=$parentheses$setform;");
	$compFormula1=number_format($compFormula1,2);
	echo "<b><font size=+1>Result... $compFormula1</font></b>";
	} else {
		echo "<p align=center><font color=#ff0033>Please enter a valid Formula!</font></p>";	
	}
}
else {
echo "<p align=center><font color=#ff0033>Please enter a valid amount!</font></p>";	
}
}

?>

</form></body></html>
