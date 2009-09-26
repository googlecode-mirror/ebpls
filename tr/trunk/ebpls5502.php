<?php
include'lib/phpFunctions-inc.php';
dbConnect();
$link2db = $dbLink;
if ($cbut=='Add New Record') {
setUrlRedirect('index.php?part=4&class_type=Preference&pref_type=Business&selMode=ebpls_nbusiness&action_=1&reftype=bus');

}
if ($updatetfo=='true') {
echo "<div align=center><font color=red><b><i>Updated Successfully</i></b></font></div>";
}
if ($reqbut=='Add New Record') {
setUrlRedirect('index.php?part=4&class_type=Preference&selMode=ebpls_nbusiness&action_=21&reftype=req');

}
if ($psicbut=='ADD') {
setUrlRedirect('<?php echo $PHP_SELF;?>?part=<?php echo $part;?>&class_type=<?php echo $class_type; ?>&pref_type=Business&selMode=<?php echo $selMode;?>&action_=91&reftype=$reftype');

}
if ($tfobut=='Add New Record') {
setUrlRedirect('index.php?part=4&class_type=Preference&pref_type=Business&selMode=ebpls_nbusiness&action_=3&reftype=tfo');

}
//if ($searchnat=='Search Nature') {
//setUrlRedirect('index.php?part=4&class_type=Preference&pref_type=Business&selMode=ebpls_nbusiness&action_=0&orderbyasdes=1&reftype=bus&ssnat=on&snat=$searcheenat');

//}


if (!empty($compEvent) and $compEvent=='Delete'){
	/*echo "1yahoo....<br>";
	echo "2$natureid<br>";
	echo "3$aTFOID<br>";
	echo "4$aTAXFEEid";*/
	if (!empty($compEvent1)){
		//echo "yayayayayayayayaayayayayayayayaayayayayayayaayayayya $oidof_complex";
		$deleComplex = "DELETE FROM ebpls_buss_complex WHERE compid = '$oidof_complex'";
		$deleComplex1=th_query($deleComplex) or die("Cannot delete...");
		$compEvent1="";
	}
	else {
	$compEvent1=1;	
	}
	
}


if (isset($iSubmitComplex)){
	//echo "yahhhhhhhooooooo";
	
	//echo "$iComplexOption<br>";
	$explodeKey=explode("-",$iComplexOption);
	//echo "1. $explodeKey[0]<br>"; // tfoid
	//echo "2. $explodeKey[1]"; // taxfeeid
	//exit;
	$iAmountFormula=strip_tags($iAmountFormula);
	/*
	echo "litotototototo $iAmountFormula<br>";
	echo "1$natureid<br>";
	echo "2$aTFOID<br>";
	echo "3$aTAXFEEid<br>";
	echo "=Option: $iOption<br>";
	echo "=type: $itfotype<br>";*/
	//exit;
	$iAddOns=strip_tags($iAddOns);
	$insertComplex=mysql_query("INSERT INTO ebpls_buss_complex (taxfeeid,tfoid,coptr,revdate,addons,taxfeeid1,natureid) VALUES ('$aTAXFEEid','$explodeKey[0]','$iOperator',Now(),'$iAddOns','$explodeKey[1]','$natureid' )",$link2db);
	//, taxfeeoption='$iOption', itfotype='$itfotype'
	$updateMe = mysql_query("UPDATE ebpls_buss_taxfeeother SET taxfeeamtfor='$iAmountFormula', taxfeeoption='$iOption', uom='$iuom',defamt=$iMinAmount WHERE natureid = '$natureid'  and tfo_id='$aTFOID' and taxfeeid='$aTAXFEEid' ",$link2db);
	//$tbl_DeleRecord_123=th_query($tbl_DeleRecord123)// or die("Cannot Update...");;
		
	$validateID=0;
	include'validate-inc.php';
	$compEvent1="";
//$eventComplex=1;	
}

if (isset($iCancelTFO)){
header("location:index.php?part=$part&class_type=$class_type&pref_type=Business&selMode=$selMode&action_=5");
}

if (isset($iCancelPSIC)){
header("location:index.php?part=$part&class_type=$class_type&pref_type=Business&selMode=$selMode&action_=9");
}

if (isset($iCancelREQ)){
header("location:index.php?part=$part&class_type=$class_type&pref_type=Business&selMode=$selMode&action_=2");
}


if (isset($iCancelNature)){
header("location:index.php?part=$part&class_type=$class_type&pref_type=Business&selMode=$selMode&action_=0");
}

if (isset($iCancelRange)){
header("location:index.php?part=$part&class_type=$class_type&pref_type=Business&natureid=$natureid&action_=1&actionID=1&selMode=$selMode&natureaction=Edit	");
}



if (!empty($valueof_tfoid)){ // delete mode for taxfeeother module listing
	//echo "development academy of the philippines";
	// this must be given confirmation page first before deletion...(subject for enhancement)
	$chkRecord=mysql_query("Select * from ebpls_buss_taxfeeother where taxfeeid='$valueof_tfoid' and tfo_id='$aTFOID' and natureid='$natureid' ",$link2db);
	$mychkRow=mysql_num_rows($chkRecord);
	if ($mychkRow==1){
	$chkData=mysql_fetch_array($chkRecord);	
	$cnatureid=$chkData[natureid];
	$ctaxfeeid=$chkData[taxfeeid];
	$ctaxfeeind=$chkData[taxfeeind];
	$cMinAmount=$chkData[defamt];
	$ctfoid=$chkData[tfoid];
	$deleteRec = "DELETE FROM ebpls_buss_taxfeeother WHERE taxfeeid = '$valueof_tfoid' and natureid='$cnatureid' and tfo_id='$ctfoid' ";
	$deleteRec_=th_query($deleteRec) or die("Cannot delete...");
		
	if ($ctaxfeeind==3){
	$updateNature=mysql_query("UPDATE ebpls_buss_nature SET natureoption=Null WHERE natureid='$cnatureid'",$link2db); // update both nature with L flag for range link.

	// check for raxfeeid record in taxfeeother table
	
	$seleTaxFeeOther=mysql_query("Select * from ebpls_buss_taxfeeother WHERE taxfeeid='$ctaxfeeid'",$link2db);
	$rowtfother=mysql_num_rows($seleTaxFeeOther);
	if ($rowtfother==1){
	$cdeleteRec = "DELETE FROM ebpls_buss_taxrange WHERE taxfeeid = '$ctaxfeeid' ";
	$cdeleteRec_=th_query($cdeleteRec) or die("Cannot delete...");	
	} // delete only data with one record...NOT applicable to the multi link range	
		
	}
	
	$validateID=3;
	include'validate-inc.php';		
	
	}
	else {
	$validateID=1;
	include'validate-inc.php';	
	}
}

// Range Deletion
if (!empty($OkforDelete) and $OkforDelete=='RangeValue'){
	
	$tbl_DeleRecord = "UPDATE ebpls_buss_taxrange SET rangestatus='X' WHERE rangeid = '$rangeid' ";
	$tbl_DeleRecord_=th_query($tbl_DeleRecord) or die("Cannot delete...");;

	$validateID=3;
	include'validate-inc.php';		
}
// //////////////
if (isset($iSubmitApplytoNature)){
/*echo "hello.....new...... $iCopytoNature....... old $Xnatureid<br>";
echo "hello.....$aTAXFEEid<br>";
echo "hello.....$aTFOID<br>";*/
$getData=mysql_query("Select * FROM ebpls_buss_taxfeeother WHERE natureid='$Xnatureid' and tfo_id='$aTFOID' and taxfeeid='$aTAXFEEid'",$link2db);
$rowGet=mysql_num_rows($getData);
if ($rowGet==1){
	$ngetData=mysql_fetch_array($getData);
	$xOption=$ngetData[taxfeeoption];
	$xInd=$ngetData[taxfeeind];
	$xMode=$ngetData[taxfeemode];
	$xAmt=$ngetData[taxfeeamtfor];
	$xTax=$ngetData[taxtype];
	$niMinAmount=$ngetData[defamt];
	$xuom=$ngetData[uom];
	
	$xgetData=mysql_query("Select * FROM ebpls_buss_taxfeeother WHERE natureid='$iCopytoNature' and tfo_id='$aTFOID' and taxfeeid='$aTAXFEEid'",$link2db);
	$xrowGet=mysql_num_rows($xgetData);
	if ($xrowGet==0){
		
		$selectDistinct=mysql_query("Select DISTINCT taxfeeid FROM ebpls_buss_taxrange order by taxfeeid DESC",$link2db);
		$rowDistinct=mysql_numrows($selectDistinct);
		if ($rowDistinct>0){
		$newgetData=mysql_fetch_array($selectDistinct);
		
		$varnewTaxFeeId=$newgetData[taxfeeid] + 1;
		}
		else {
		exit; // should have a routine here or message for IT support	
		}
		
		$insertGetdata = mysql_query("INSERT INTO ebpls_buss_taxfeeother (natureid,tfo_id,taxfeeid,taxfeeoption,taxfeeind,taxfeemode,taxfeeamtfor,datecreated,taxtype,uom,defamt) VALUES ($iCopytoNature,$aTFOID,$varnewTaxFeeId,'$xOption','$xInd','$xMode','$xAmt',Now(),'$xTax','$xuom',$niMinAmount)",$link2db) or die("1".mysql_error());
		
		$selectRange=mysql_query("Select * from ebpls_buss_taxrange WHERE taxfeeid='$aTAXFEEid' and rangestatus='A' ORDER BY rangeid",$link2db);
		$rowSelectRange=mysql_num_rows($selectRange);
		//echo "number of rows... $rowSelectRange";
		if ($rowSelectRange>0){
		$ctr=0;
		
		//echo "hi to all<br>";
		while ($ctr < $rowSelectRange){
			
		$recngetData=mysql_fetch_array($selectRange);
			$insertIntoDb=mysql_query("INSERT INTO ebpls_buss_taxrange (taxfeeid,rangelow,rangehigh,rangeamount,rangeind,rangestatus,datecreated) VALUES ('$varnewTaxFeeId','$recngetData[rangelow]','$recngetData[rangehigh]','$recngetData[rangeamount]','$recngetData[rangeind]','$recngetData[rangestatus]',Now())",$link2db);
		$ctr++;

		}
		
		$updateNature=mysql_query("UPDATE ebpls_buss_nature SET natureoption='C' WHERE natureid='$Xnatureid' or natureid='$iCopytoNature'",$link2db); // update both nature with L flag for range link.
		
		}	
		$validateID=0;
	}
	else {
	$validateID=6;
	}
	include'validate-inc.php';		
	
}
else {
$validateID=1;
include'validate-inc.php';	
}

}

if (isset($iSubmitCopytoNature)){

$getData=mysql_query("Select * FROM ebpls_buss_taxfeeother WHERE natureid='$Xnatureid' and tfo_id='$aTFOID' and taxfeeid='$aTAXFEEid'",$link2db);
$rowGet=mysql_num_rows($getData);
if ($rowGet==1){
	$ngetData=mysql_fetch_array($getData);
	$xOption=$ngetData[taxfeeoption];
	$xInd=$ngetData[taxfeeind];
	$xMode=$ngetData[taxfeemode];
	$xAmt=$ngetData[taxfeeamtfor];
	$xMinAmount=$ngetData[defamt];
	$xTax=$ngetData[taxtype];
	$xuom=$ngetData[uom];
	$xgetData=mysql_query("Select * FROM ebpls_buss_taxfeeother WHERE natureid='$iCopytoNature' and tfo_id='$aTFOID' and taxfeeid='$aTAXFEEid'",$link2db);
	$xrowGet=mysql_num_rows($xgetData);
	if ($xrowGet==0){
		//echo "hahahahaahhahaahahahahahaha....";
			
		$insertGetdata = mysql_query("INSERT INTO ebpls_buss_taxfeeother (natureid,tfo_id,taxfeeid,taxfeeoption,taxfeeind,taxfeemode,taxfeeamtfor,datecreated,taxtype,uom) VALUES ('$iCopytoNature','$aTFOID','$aTAXFEEid','$xOption','$xInd','$xMode','$xAmt',Now(),'$xTax','$uom',xMinAmount)",$link2db);
		
		$updateNature=mysql_query("UPDATE ebpls_buss_nature SET natureoption='L' WHERE natureid='$Xnatureid' or natureid='$iCopytoNature'",$link2db); // update both nature with L flag for range link.
		
		$validateID=0;
	}
	else {
	$validateID=6;
	}
	include'validate-inc.php';		
	
}
else {
$validateID=1;
include'validate-inc.php';	
}

}

//business nature

if (!empty($actionDeleteStatus) and $actionDeleteStatus=='Ok'){
	//$xmyid = mysql_query("UPDATE $tbl_current SET naturestatus='X' WHERE natureid = '$natureid' ",$link2db);
	$tbl_DeleRecord = "UPDATE $tbl_current SET naturestatus='X' WHERE natureid = '$natureid' ";
	$tbl_DeleRecord_=th_query($tbl_DeleRecord) or die("Cannot delete...");;

	$validateID=3;
	include'validate-inc.php';	
}

if (!empty($natureid) and $natureaction=='Delete'){ //must be cascaded but should check first the link tables...before deletion
	$action_=80;
}
else {
	$xmyid = mysql_query("SELECT * FROM $tbl_current WHERE natureid = '$natureid' ",$link2db);
		
	if (mysql_affected_rows($link2db)==1 ){
	$nidescription_=mysql_fetch_array($xmyid);
	$nidescription=$nidescription_[naturedesc];
	$niPsicCode=$nidescription_[psiccode];
	}
		
}

//tfo
if ($reftype=='tfo' and $tfoaction=='Activate') {
	$tbl_DeleRecord = "UPDATE ebpls_buss_tfo SET tfostatus='A' WHERE tfoid = '$tfoid' ";
	$tbl_DeleRecord_=th_query($tbl_DeleRecord);
}
if (!empty($actionDeleteTfo) and $actionDeleteTfo=='Ok'){
	$tbl_DeleRecord = "UPDATE ebpls_buss_tfo SET tfostatus='X' WHERE tfoid = '$tfoid' ";
	$tbl_DeleRecord_=th_query($tbl_DeleRecord) or die("Cannot delete...");;
	//echo "tama naman $tfoid";
	$validateID=9;
	include'validate-inc.php';	
	//$action_=5;
}

if (!empty($tfoid) and $tfoaction=='Delete'){ //must be cascaded but should check first the link tables...before deletion
	$action_=81;
}
else {
	$xmyid = mysql_query("SELECT * FROM $tbl_current WHERE tfoid = '$tfoid' ",$link2db);
	//$tbl_ResultId="SELECT * FROM $tbl_current WHERE tfoid = '$tfoid' ";
	//$tbl_ResultId_=th_query($tbl_ResultId);
	
	if (mysql_affected_rows($link2db)==1 ){
	$nitfodescription_=mysql_fetch_array($xmyid);
	$nitfodescription=$nitfodescription_[tfodesc];
	$nitfoindicator=$nitfodescription_[tfoindicator];
	$nitfotype=$nitfodescription_[taxfeetype];
	$nilangtaon=$nitfodescription_[or_print];
	$nubert = $nitfodescription_[counter];
	
	$DBtype_=mysql_query("SELECT * FROM ebpls_buss_taxfeetype WHERE taxfeetype = $nitfotype ",$link2db);
	if (mysql_affected_rows($link2db)==1 ){
	$ntype_=mysql_fetch_array($DBtype_);
	$ntypedesc=$ntype_[typedesc];
	
	}		
	
	}	
}

//requirements
if (!empty($actionDeleteReq) and $actionDeleteReq=='Ok'){
	$check_rec = @mysql_query("select count(*) from havereq WHERE reqid = '$bbo' ");
	$get_num_rec = @mysql_fetch_array($check_rec);
	if ($get_num_rec[0] > 0) {
		?>
		print "<div align='center'><font color='red' size='2'>Cannot Delete! Existing Record Found</font></div>";
		<?
	} else {
	$tbl_DeleRecord = mysql_query("Delete from ebpls_buss_requirements WHERE reqid = '$bbo' ");
	//echo "tama naman $tfoid";
	?>
	<body onload='javascript:alert ("Record Successfully Deleted!");'></body>
	<?
	//$action_=5;
	}
}

if (!empty($reqid) and $reqaction=='Delete'){ //must be cascaded but should check first the link tables...before deletion
	//$xmyid = mysql_query("DELETE FROM $tbl_current WHERE tfoid = '$tfoid' ",$link2db);
	//$tbl_DeleRecord = "DELETE FROM $tbl_current WHERE reqid = '$reqid' ";
	//$tbl_DeleRecord_=th_query($tbl_DeleRecord) or die("Cannot delete...");;
	$xmyid = mysql_query("SELECT * FROM $tbl_current WHERE reqid = '$reqid' ",$link2db);
	//$tbl_ResultId="SELECT * FROM $tbl_current WHERE tfoid = '$tfoid' ";
	//$tbl_ResultId_=th_query($tbl_ResultId);
	
	if (mysql_affected_rows($link2db)==1 ){
	$nitfodescription_=mysql_fetch_array($xmyid);
	$nireqdescription=$nitfodescription_[reqdesc];
	$nireqindicator=$nitfodescription_[reqindicator];
	$niPermitType=$nitfodescription_[permit_type];	
	}	

	$action_=82;
}
else {
	$xmyid = mysql_query("SELECT * FROM $tbl_current WHERE reqid = '$reqid' ",$link2db);
	//$tbl_ResultId="SELECT * FROM $tbl_current WHERE tfoid = '$tfoid' ";
	//$tbl_ResultId_=th_query($tbl_ResultId);
	
	if (mysql_affected_rows($link2db)==1 ){
	$nitfodescription_=mysql_fetch_array($xmyid);
	$nireqdescription=$nitfodescription_[reqdesc];
	$nireqindicator=$nitfodescription_[reqindicator];	
	$niPermitType=$nitfodescription_[permit_type];
	}	
}
////////////////////////////

if (isset($ISubmitBussNature)){
	$nidescription=strip_tags(strtoupper($idescription));
	$niPsicCode=strip_tags(strtoupper($iPsicCode));
	if (!empty($nidescription)){
	if ($natureaction=='Edit'){
	$xmyid = mysql_query("SELECT * FROM $tbl_current WHERE naturedesc = '$nidescription'  and natureid <> '$natureid'",$link2db);	
	}
	else {
	$xmyid = mysql_query("SELECT * FROM $tbl_current WHERE naturedesc = '$nidescription' and naturestatus='A'",$link2db);
	}
	
	if (mysql_affected_rows($link2db)==0 ){
		$action_=1;
		$actionID=1;
		if ($natureaction=='Edit'){
			$myid = mysql_query("UPDATE $tbl_current set naturedesc = '$nidescription', psiccode = '$niPsicCode' where natureid = '$natureid'");
			$naedit = 'true';
			} else {
	$myid = mysql_query("INSERT INTO $tbl_current (naturedesc,naturestatus,psiccode) VALUES ('$nidescription','A','$niPsicCode')",$link2db);
		$naedit = 'false';
		?>
		<body onload="AddRec(); _FRM.submit();"></body>
		<?php
		}
	
	if ($myid == TRUE){
	// get natureID//likeoperationiferrorisencountered...
	$mynatureID=mysql_query("SELECT natureid FROM $tbl_current WHERE naturedesc='$nidescription'",$link2db);
	if (mysql_affected_rows($link2db)==1){
		$nidescriptionrec_=mysql_fetch_array($mynatureID);
		$natureid=$nidescriptionrec_[natureid];
		//$natureid=$nidescriptionrec_[psiccode];
		$natureaction='Edit';
	}
	else {
	$validateID=1;
	//include'validate-inc.php';		
	}
	
	// end here
	if ($naedit=='true'){
		$validateID=5;
	} else {
		$validateID=0;
	}
	include'validate-inc.php';	
	}
	else {
		//echo "fhfhfhfhjosel";
	$validateID=1;
	include'validate-inc.php';	
	}

	}
	elseif (mysql_affected_rows($link2db)==1 ){
		?>
			<body onload='javascript:alert ("Existing Record Found!!");_FRM.idescription.focus();'></body>
			<?
	//$myid = mysql_query("UPDATE $tbl_current SET naturedesc='$nidescription', psiccode='$niPsicCode' WHERE natureid='$natureid' ",$link2db);

	//$validateID=5;
	//include'validate-inc.php';	
	}
	
	} // EndIf for not empty description...
	else {
	?>
	<body onload='javascript:alert ("Enter Valid Description!!");_FRM.idescription.focus();'></body>
	<?
	}
}

// requirements
if ($ISubmitREQ=="Submit"){

	$nireqdescription=strip_tags($ireqdescription);
	if ($ireqindicator==1){
	$ireqindicator=1;	
	}
	else {
	$ireqindicator=0;
	} 	
		
	if (!empty($nireqdescription)){
		
	if ($reqaction=='Edit'){
	$dtaReq = mysql_query("SELECT * FROM $tbl_current WHERE reqid = '$reqid' ",$link2db);	
	}
	else {
	$dtaReq = mysql_query("SELECT * FROM $tbl_current WHERE reqdesc = '$nireqdescription' and permit_type='$iPermitType'",$link2db);
	}
	if (mysql_affected_rows($link2db)==0 ){
	$dtaReq1 = mysql_query("INSERT INTO $tbl_current (reqdesc,recstatus,reqindicator,datecreated,revdate,permit_type) VALUES ('$nireqdescription','A','$ireqindicator',Now(),Now(),'$iPermitType')",$link2db);
	//echo "INSERT INTO $tbl_current (reqdesc,recstatus,reqindicator,datecreated,revdate) VALUES ('$nireqdescription','A','$ireqindicator',Now(),Now(),'$iPermitType') $link2db";

	if ($dtaReq1 == TRUE){
	?>
	<body onload='javascript:alert ("Data is successfully added to the database.");'></body>
	<?
	$nireqdescription = "";
	$iPermitType = "";
	}
	else {
	$validateID=1;
	include'validate-inc.php';			
	}
	
	}
	elseif (mysql_affected_rows($link2db)==1) {
	if ($reqaction=='Edit'){
	$DBupdatereq = mysql_query("UPDATE $tbl_current SET reqdesc='$nireqdescription', reqindicator='$ireqindicator', revdate=Now(), permit_type='$iPermitType' WHERE reqid = '$reqid' ",$link2db);
	?>
	<body onload='javascript:alert ("Record Successfully Updated");'></body>
	<?
	$nireqdescription = "";
	$iPermitType = "";
	}
	else {
	?>
	<body onload='javascript:alert ("Duplicate Record is not accepted!!");'></body>
	<?php
              
	}
	
	
	}
	} // EndIf for not empty description...
	else {
	$validateID=2;
	include'validate-inc.php';	
	}
}
// end of requriements

// taxes,fees and other charges...

if (isset($ISubmitTFO)){
	if ($itfoindicator==1) {
			$nitfodescription=strip_tags(strtoupper($itfodescription));
	} else {
			$nitfodescription=strip_tags(strtoupper($itfodescription));
			$defamt=$defamt;
			$itfotype=$itfotype;
	}
			if ($itfoindicator==1){
				$itfoindicator=1;	
			}
			else {
				$itfoindicator=0;
			} 	
			$nitfoindicator=$itfoindicator;
			
				if (!empty($nitfodescription)){
		
					if ($tfoaction=='Edit'){		
						$xmyid = mysql_query("SELECT * FROM $tbl_current WHERE tfoid = '$tfoid' ",$link2db);	
					}
					else {
						$xmyid = mysql_query("SELECT * FROM $tbl_current WHERE tfodesc = '$nitfodescription' ",$link2db);
					}
					if ($nitfoindicator==1) {
					if ($defamt=='' or $defamt==0) {
                        echo "<div align=center><font color=red><b><i>Enter Default Amount</i></b></font></div>";
                        $nitfodescription=$itfodescription;
                        $nitfoindicator=$itfoindicator;
                        $defamt=$defamt;
                        $itfotype=$itfotype;
					                } else {
                        $nitfodescription=strip_tags(strtoupper($itfodescription));
               					
					if (mysql_affected_rows($link2db)==0 ){
		
					$myid = mysql_query("INSERT INTO $tbl_current (tfodesc,tfostatus,tfoindicator,taxfeetype,datecreated,defamt,or_print,counter) VALUES ('$nitfodescription','A','$itfoindicator','$itfotype',Now(),$defamt,'$ilangtaon','$nubert')",$link2db) or die(mysql_error());	
	
						if ($myid == TRUE){
							$validateID=0;
							include'validate-inc.php';
							//setURLRedirect("index.php?tfoid=5&action_=3&class_type=Preference&pref_type=Business&actionID=1&part=4&class_type=Preference&selMode=ebpls_nbusiness&tfoaction=Edit&orderbyasdes=1&reftype=tfo&updatetfo=true");

						}
						else {
							$validateID=1;
							include'validate-inc.php';			
						}
	
					}
					elseif (mysql_affected_rows($link2db)==1) {
						if ($tfoaction=='Edit'){
						$DBupdatetfo = mysql_query("UPDATE $tbl_current SET tfodesc='$nitfodescription', tfoindicator='$itfoindicator', taxfeetype='$itfotype', defamt=$defamt, or_print='$ilangtaon', counter='$ubert' WHERE tfoid = '$tfoid' ",$link2db);
						$action_=5;
						$validateID=5;
						setURLRedirect("index.php?tfoid=5&action_=3&class_type=Preference&pref_type=Business&actionID=1&part=4&class_type=Preference&selMode=ebpls_nbusiness&tfoaction=Edit&orderbyasdes=1&reftype=tfo&updatetfo=true&nitfodescription=$nitfodescription&nitfoindicator=$nitfoindicator&defamt=$defamt&itfotype=$itfotype&tfoid=$tfoid");
						}
						else {
						$validateID=6;
						}
					}
					//$nitfodescription='';
				        //$nitfoindicator='';
        				//$defamt='';
        				//$itfotype='';
					}
					} else {
					 $nitfodescription=strip_tags(strtoupper($itfodescription));
                                                                                                                                                                                                                                                                     
                                        if (mysql_affected_rows($link2db)==0 ){
                                                                                                                                                                                                                                                                     
                                        $myid = mysql_query("INSERT INTO $tbl_current (tfodesc,tfostatus,tfoindicator,taxfeetype,datecreated,defamt,or_print,counter) VALUES ('$nitfodescription','A','$itfoindicator','$itfotype',Now(),$defamt,'$ilangtaon','$nubert')",$link2db)
or die(mysql_error());
                                                                                                                                                                                                                                                                     
                                                if ($myid == TRUE){
                                                        $validateID=0;
                                                        include'validate-inc.php';
                                                       // setURLRedirect("index.php?tfoid=5&action_=3&class_type=Preference&pref_type=Business&actionID=1&part=4&class_type=Preference&selMode=ebpls_nbusiness&tfoaction=Edit&reftype=tfo&updatetfo=true&nitfodescription=$nitfodescription&nitfoindicator=$nitfoindicator&defamt=$defamt&itfotype=$itfotype");
                                                                                                                                                                                                                                                                     
                                                }
                                                else {
                                                        $validateID=1;
                                                        include'validate-inc.php';
                                                }
                                                                                                                                                                                                                                                                     
                                        }
                                        elseif (mysql_affected_rows($link2db)==1) {
                                                if ($tfoaction=='Edit'){
                                                $DBupdatetfo = mysql_query("UPDATE $tbl_current SET tfodesc='$nitfodescription', tfoindicator='$itfoindicator', taxfeetype='$itfotype', defamt=$defamt, or_print='$ilangtaon', counter='$ubert' WHERE tfoid = '$tfoid' ",$link2db);
                                                $action_=5;
                                                $validateID=5;
                                                setURLRedirect("index.php?tfoid=5&action_=3&class_type=Preference&pref_type=Business&actionID=1&part=4&class_type=Preference&selMode=ebpls_nbusiness&tfoaction=Edit&reftype=tfo&updatetfo=true&nitfodescription=$nitfodescription&nitfoindicator=$nitfoindicator&defamt=$defamt&itfotype=$itfotype&tfoid=$tfoid");
                                                }
                                                else {
                                                $validateID=6;
                                                }
					}
                                        }
				} // EndIf for not empty description...
				else {
					$validateID=2;
					include'validate-inc.php';	
				}

}
/***********/
if (isset($ISubmitTax)){

	 $counterTaxFeeID=mysql_query("Select DISTINCT taxfeeid FROM ebpls_buss_taxfeeother order
by taxfeeid DESC",$link2db);
        $rowCounter=mysql_num_rows($counterTaxFeeID);
        if ($rowCounter==0){
        $ctrtaxfeeid=1;
        }
        else {
                ///////////////////////////////////
                /*$xselectDistinct=mysql_query("Select DISTINCT taxfeeid FROM ebpls_buss_taxrange order by taxfeeid DESC",$link2db);
                $xrowDistinct=mysql_numrows($xselectDistinct);
                if ($xrowDistinct>0){
                $xnewgetData=mysql_fetch_array($xselectDistinct);
                                                                                                 
                $ctrtaxfeeid=$newgetData[taxfeeid] + 1;
                }*/
                //////////////////////////////////
                $xnewgetData=mysql_fetch_array($counterTaxFeeID);
                                                                                                 
                $ctrtaxfeeid=$xnewgetData[taxfeeid] + 1;
                //$ctrtaxfeeid=$rowCounter + 1;
        }

	
	if ($iMode==2){
	$eventComplex=2;	
	}
	elseif ($iMode==1){
	$eventComplex=1;	
	}

	
	$niAmountFormula=strip_tags($iAmountFormula);
	if (!empty($niAmountFormula)){
		
		if (empty($aTFOID) and empty($aTAXFEEid)){
		$checkRecord=mysql_query("Select * from ebpls_buss_taxfeeother WHERE natureid=$natureid and tfo_id=$itfotype and taxfeeid=$ctrtaxfeeid ",$link2db); // and taxfeeind='$iIndicator' and taxtype='$itype' and taxfeeoption='$iOption' no duplicate additional
		$rowchkRecord=mysql_num_rows($checkRecord);
		if ($rowchkRecord==0){
			
			$seleDuplicate=mysql_query("select * from ebpls_buss_taxfeeother where tfo_id='$itfotype' and taxfeeind='$iIndicator' and taxtype='$iType' and taxfeeoption='$iOption' and natureid='$natureid'",$link2db);
			$rowDuplicate=mysql_num_rows($seleDuplicate);
			//echo "doble daw... $rowDuplicate<br>";
/*
			echo "orignatureid $natureid<br>";
			echo "origtfoid $aTFOID<br>";
			echo "origtaxfeeid $aTAXFEEid<br>";
			echo "doble daw... $rowDuplicate<br>";
			echo "option $iOption<br>";
			echo "type $iType<br>";
			echo "indicator $iIndicator<br>";
			echo "tfotype $itfotype<br>";
				
*/
		//exit;
	if ($rowDuplicate==0){
	if ($iMinAmount=='') {
		$iMinAmount=0;
	}
	//echo "lito frrrerreererreerererer";
	if ($iIndicator==3){
		$valueof_ResultId=1;
		$checkRange=1;
		$iAmountFormula=1;	
		$niAmountFormula=1;	
		if ($iMinAmount=='') {
			$iMinAmount=0;
		}
		$INSERTdata = mysql_query("INSERT INTO ebpls_buss_taxfeeother (taxfeeid,natureid,tfo_id,taxfeeoption,taxfeeind,taxfeemode,taxfeeamtfor,datecreated,taxtype,uom,defamt) VALUES ($ctrtaxfeeid,$natureid,$itfotype,'$iOption','$iIndicator','$iMode',$niAmountFormula,Now(),'$iType','$iuom',$iMinAmount)",$link2db) or die("die".mysql_error());
	}
	
	if ($iIndicator=='2') {
	$openpar = substr_count($niAmountFormula, '(');
	$closepar = substr_count($niAmountFormula, ')');
	$closepar1 = $closepar-2;
	if ($openpar==$closepar1) {
	$INSERTdata = mysql_query("INSERT INTO ebpls_buss_taxfeeother (taxfeeid,natureid,tfo_id,taxfeeoption,taxfeeind,taxfeemode,taxfeeamtfor,datecreated,taxtype,uom,defamt) VALUES ('$ctrtaxfeeid','$natureid','$itfotype','$iOption','$iIndicator','$iMode','$niAmountFormula',Now(),'$iType','$iuom',$iMinAmount)",$link2db) or die("die".mysql_error());
	} else {
		echo "<div align=center><font color=red<b><i>Invalid Formula ($niAMountFormula)</i></b></font></div>";
	}
	}
	if ($iIndicator=='1') {
		if (is_numeric($niAmountFormula)) {
	$INSERTdata = mysql_query("INSERT INTO ebpls_buss_taxfeeother (taxfeeid,natureid,tfo_id,taxfeeoption,taxfeeind,taxfeemode,taxfeeamtfor,datecreated,taxtype,uom,defamt) VALUES ('$ctrtaxfeeid','$natureid','$itfotype','$iOption','$iIndicator','$iMode','$niAmountFormula',Now(),'$iType','$iuom',$iMinAmount)",$link2db) or die("die".mysql_error());
	} else {
		echo "<div align=center><font color=red<b><i>Invalid Amount ($niAmountFormula)</i></b></font></div>";
	}
	}
	if ($INSERTdata == True){
	
	$RecordtaxID_ = mysql_query("SELECT * FROM ebpls_buss_taxfeeother WHERE natureid = '$natureid' and tfo_id='$itfotype' and taxfeeoption='$iOption' and taxfeeind='$iIndicator' and taxfeemode='$iMode' and taxfeeamtfor='$niAmountFormula' and taxtype='$iType' and taxfeeid='$ctrtaxfeeid'",$link2db);
		
	if (mysql_affected_rows($link2db)==1 ){
	$nRecordId_=mysql_fetch_array($RecordtaxID_);
	$aTAXFEEid=$nRecordId_[taxfeeid];
	$aTFOID=$nRecordId_[tfoid];
	}
	
	$validateID=0;
	include'validate-inc.php';
	
	}
	else {
	$validateID=1;
	include'validate-inc.php';					
	}
	
	} // duplicate record next line...
//	else {
//	echo "<p align=center>Duplicate entry is NOT accepted...</p>";	
//	}
	
	}// no duplicate record found...
	else { // $rowchkRecord==1
	$validateID=1;
	include'validate-inc.php';							
	}
		
	}
	else { // check for range flag here...
	$DBupdateRec_ = mysql_query("UPDATE ebpls_buss_taxfeeother SET taxfeeoption='$iOption', taxfeeind='$iIndicator', taxfeemode='$iMode', taxfeeamtfor='$niAmountFormula',defamt=$iMinAmount, datecreated=Now(), taxtype='$iType', tfo_id='$itfotype', uom='$iuom' WHERE natureid='$natureid' and tfoid = '$aTFOID' and taxfeeid='$aTAXFEEid' ",$link2db);
		// if error or not correct in process remove tfoid='$itfotype'!!!
	$validateID=5;
	include'validate-inc.php';	
				
	}
	
	
	} // Not empty
	else {
	$validateID=2;
	include'validate-inc.php';			
	}

}

if (!empty($bussEvent)){
	$taxfeeAmtFor = mysql_query("SELECT * FROM ebpls_buss_taxfeeother WHERE natureid = '$natureid' and tfo_id='$aTFOID' and taxfeeid='$aTAXFEEid'  ",$link2db);
		
	if (mysql_affected_rows($link2db)==1 ){
	$niAmountFormula_=mysql_fetch_array($taxfeeAmtFor);
	$niAmountFormula=$niAmountFormula_[taxfeeamtfor];
	$niMinAmount=$niAmountFormula_[defamt];
	$niOption=$niAmountFormula_[taxfeeoption];
	$niIndicator=$niAmountFormula_[taxfeeind];
	$niTaxMode=$niAmountFormula_[taxfeemode];
	$niType=$niAmountFormula_[taxtype];
	$niuom=$niAmountFormula_[uom];
	//$niTaxMode=$niAmountFormula_[taxfeemode];
	
	$iTFO_data = mysql_query("SELECT * FROM ebpls_buss_tfo WHERE tfoid = '$aTFOID'",$link2db);
		
	if (mysql_affected_rows($link2db)==1 ){
	$niTFO_data=mysql_fetch_array($iTFO_data);
	$ni_typedesc=$niTFO_data[tfodesc];
	$ni_taxfeetype=$niTFO_data[tfoid];
	}
	
	}

}

// range maintenance
// Save routine
if (isset($iSaveRange)){
	$chkindi = mysql_query("select * from ebpls_buss_taxfeeother where natureid='$natureid' and tfo_id = '$aTFOID' and taxfeeid='$aTAXFEEid'",$link2db);
	$chkindic1=mysql_fetch_array($chkindi);
	$chkiIndicator = $chkindic1[taxfeeind];
	if ($chkiIndicator != $iIndicator) {
		$valueof_ResultId='';
		$eventComplex='';
		$niIndicator=$iIndicator;
		
		//$deleterange = mysql_query("delete from ebpls_buss_taxrange where taxfeeid='$aTAXFEEid'",$link2db);
		$upDateDbrec1=mysql_query("UPDATE ebpls_buss_taxfeeother SET taxfeeoption='$iOption', taxfeeind='$iIndicator', taxfeemode='$iMode', taxfeeamtfor='$niAmountFormula',defamt=$iMinAmount, datecreated=Now(), taxtype='$iType', tfo_id='$itfotype', uom='$iuom' WHERE natureid='$natureid' and tfo_id = '$aTFOID' and taxfeeid='$aTAXFEEid' ",$link2db);
	}
	$ctr=0;
	$varIDreSult_=0;
	while ($ctr < $lastRecord_){
		if ($rangelow[$ctr]==Null || $rangehigh[$ctr]==Null || $rangeamount[$ctr]==Null ){
		}
		else {
		if ($editRange==1){ // for edit range use only
		//echo "for edit range ito ";
			if ($rangeind[$ctr]=='1') {
				if (is_numeric($rangeamount[$ctr])) {
			$upDateDbrec=mysql_query("UPDATE ebpls_buss_taxrange SET rangelow='$rangelow[$ctr]', rangehigh='$rangehigh[$ctr]', rangeamount='$rangeamount[$ctr]', rangeind='$rangeind[$ctr]', datecreated=Now() WHERE taxfeeid='$aTAXFEEid' and rangeid='$rangeID[$ctr]' ",$link2db);
			// and rangeid='$rangeID[$ctr]'
			//$myid = mysql_query("UPDATE $tbl_current SET naturedesc='$nidescription' WHERE natureid='$natureid' ",$link2db);
			$validateID=5;
			//echo "<p align=center><font color=#ff0033><b><i>Record successfully updated!!!</font></i></b></p>";
			//include'validate-inc.php';
		} else {
				echo "<div align=center><font color=red><b><i>Invalid Amount ($rangeamount[$ctr])</i></b></font></div>";
		}
		
		}
		if ($rangeind[$ctr]=='2') {
			$openpar = substr_count($rangeamount[$ctr], '(');
			$closepar = substr_count($rangeamount[$ctr], ')');
			$closepar1 = $closepar-2;
			if ($openpar==$closepar1) {
			$upDateDbrec=mysql_query("UPDATE ebpls_buss_taxrange SET rangelow='$rangelow[$ctr]', rangehigh='$rangehigh[$ctr]', rangeamount='$rangeamount[$ctr]', rangeind='$rangeind[$ctr]', datecreated=Now() WHERE taxfeeid='$aTAXFEEid' and rangeid='$rangeID[$ctr]' ",$link2db);
			// and rangeid='$rangeID[$ctr]'
			//$myid = mysql_query("UPDATE $tbl_current SET naturedesc='$nidescription' WHERE natureid='$natureid' ",$link2db);
			$validateID=5;
			//echo "<p align=center><font color=#ff0033><b><i>Record successfully updated!!!</font></i></b></p>";
			//include'validate-inc.php';
		} else {
				echo "<div align=center><font color=red><b><i>Invalid Amount ($rangeamount[$ctr])</i></b></font></div>";
		}
		
		}
//Robert	
	if (!empty($eventAddRow) and $eventAddRow=='PlusOne'){
        $taddr=0;
        $ctr=0;
        while ($taddr < $noofloop) {
        $AddRangeLow[$ctr]=strip_tags($AddRangeLow[$ctr]);
        $AddRangeHigh[$ctr]=strip_tags($AddRangeHigh[$ctr]);
        $AddRangeAmount[$ctr]=strip_tags($AddRangeAmount[$ctr]);
        $AddRangeInd[$ctr]=strip_tags($AddRangeInd[$ctr]);
                                                                                                                                                                                                                                                                                                                                                                                                       
        if (is_numeric($AddRangeLow[$ctr]) and is_numeric($AddRangeHigh[$ctr])){
                if ($AddRangeInd[$ctr]=='1') {
                                if (is_numeric($AddRangeAmount[$ctr])) {
                        $INSERTdata = mysql_query("INSERT INTO ebpls_buss_taxrange (taxfeeid,rangelow,rangehigh,rangeamount,rangeind,rangestatus,datecreated) VALUES ('$aTAXFEEid','$AddRangeLow[$ctr]','$AddRangeHigh[$ctr]','$AddRangeAmount[$ctr]','$AddRangeInd[$ctr]','A',Now())",$link2db);
                        // and rangeid='$rangeID[$ctr]'
                        //$myid = mysql_query("UPDATE $tbl_current SET naturedesc='$nidescription' WHERE natureid='$natureid' ",$link2db);
                        //$validateID=5;
                        //echo "<p align=center><font color=#ff0033><b><i>Record successfully updated!!!</font></i></b></p>";
                        //include'validate-inc.php';
                } else {
                                echo "<div align=center><font color=red<b><i>Invalid Amount ($rangeamount[$ctr])</i></b></font></div>";
                }
                                                                                                                                                                                                                                                                                                                                                                                                       
                }
                if ($AddRangeInd[$ctr]=='2') {
                        $openpar = substr_count($AddRangeAmount[$ctr], '(');
                        $closepar = substr_count($AddRangeAmount[$ctr], ')');
                        $closepar1 = $closepar-2;
                        if ($openpar==$closepar1) {
                        $INSERTdata = mysql_query("INSERT INTO ebpls_buss_taxrange (taxfeeid,rangelow,rangehigh,rangeamount,rangeind,rangestatus,datecreated) VALUES ('$aTAXFEEid','$AddRangeLow[$ctr]','$AddRangeHigh[$ctr]','$AddRangeAmount[$ctr]','$AddRangeInd[$ctr]','A',Now())",$link2db);
                        // and rangeid='$rangeID[$ctr]'
                        //$myid = mysql_query("UPDATE $tbl_current SET naturedesc='$nidescription' WHERE natureid='$natureid' ",$link2db);
                        //$validateID=5;
                        //echo "<p align=center><font color=#ff0033><b><i>Record successfully updated!!!</font></i></b></p>";
                        //include'validate-inc.php';
                } else {
                                echo "<div align=center><font color=red<b><i>Invalid Amount ($rangeamount[$ctr])</i></b></font></div>";
                }
                                                                                                                                                                                                                                                                                                                                                                                                       
                }
                                                                                                                                                                                                                                                                                                                                                                                                       
                                                                                                                                                                                                                                                                                                                                                                                                       
        $valueof_ResultId=1;
        $eventAddRow='';
        }
        else {
        echo "<br><font color=#ff0033><p align=center>Invalid entry!!! Please check your entry.</p></font>";
        $nofrow=$noofloop;
        }
        $ctr++;
        $taddr++;
        }
	}

//Robert
		}
		else {
			if ($aTAXFEEid==0){
				echo "<p align=center>Error encountered... Please report immediately to your IT personnel.</p>";
			}
			else {			
			//echo "tama naman...";
			if ($rangeind[$ctr]=='1') {
				if (is_numeric($rangeamount[$ctr])) {
			$INSERTdata = mysql_query("INSERT INTO ebpls_buss_taxrange (taxfeeid,rangelow,rangehigh,rangeamount,rangeind,rangestatus,datecreated) VALUES ('$aTAXFEEid','$rangelow[$ctr]','$rangehigh[$ctr]','$rangeamount[$ctr]','$rangeind[$ctr]','A',Now())",$link2db);
			// and rangeid='$rangeID[$ctr]'
			//$myid = mysql_query("UPDATE $tbl_current SET naturedesc='$nidescription' WHERE natureid='$natureid' ",$link2db);
			//$validateID=5;
			//echo "<p align=center><font color=#ff0033><b><i>Record successfully updated!!!</font></i></b></p>";
			//include'validate-inc.php';
			 $ctrtaxfeeid='';
			 setUrlRedirect("index.php?natureid=$natureid&action_=1&actionID=1&part=4&class_type=Preference&pref_type=Business&selMode=ebpls_nbusiness&natureaction=Edit&orderbyasdes=1&reftype=bus");
		} else {
				echo "<div align=center><font color=red><b><i>Invalid Amount ($rangeamount[$ctr])</i></b></font></div>";
		}
		
		}
		if ($rangeind[$ctr]=='2') {
			$openpar = substr_count($rangeamount[$ctr], '(');
			$closepar = substr_count($rangeamount[$ctr], ')');
			$closepar1 = $closepar-2;
			if ($openpar==$closepar1) {
			$INSERTdata = mysql_query("INSERT INTO ebpls_buss_taxrange (taxfeeid,rangelow,rangehigh,rangeamount,rangeind,rangestatus,datecreated) VALUES ('$aTAXFEEid','$rangelow[$ctr]','$rangehigh[$ctr]','$rangeamount[$ctr]','$rangeind[$ctr]','A',Now())",$link2db);
			// and rangeid='$rangeID[$ctr]'
			//$myid = mysql_query("UPDATE $tbl_current SET naturedesc='$nidescription' WHERE natureid='$natureid' ",$link2db);
			$validateID=5;
			//echo "<p align=center><font color=#ff0033><b><i>Record successfully updated!!!</font></i></b></p>";
			//include'validate-inc.php';
		} else {
				echo "<div align=center><font color=red><b><i>Invalid Amount ($rangeamount[$ctr])</i></b></font></div>";
		}
		
		}
			
		
		
		}
		//echo 	"$rangelow[$ctr]..........$rangehigh[$ctr].........$rangeamount[$ctr] <br>";
		}
		
		}
		
	$varIDreSult_++;
	$ctr++;
	}
	//echo "Record TaxFeeID# =>>$aTAXFEEid";
	//exit;
	if (!empty($eventAddRow) and $eventAddRow=='PlusOne'){
	$taddr=0;
	$ctr=0;
	while ($taddr < $noofloop) {
	$AddRangeLow[$ctr]=strip_tags($AddRangeLow[$ctr]);
	$AddRangeHigh[$ctr]=strip_tags($AddRangeHigh[$ctr]);
	$AddRangeAmount[$ctr]=strip_tags($AddRangeAmount[$ctr]);
	$AddRangeInd[$ctr]=strip_tags($AddRangeInd[$ctr]);

	if (is_numeric($AddRangeLow[$ctr]) and is_numeric($AddRangeHigh[$ctr])){
		if ($AddRangeInd[$ctr]=='1') {
				if (is_numeric($AddRangeAmount[$ctr])) {
			$INSERTdata = mysql_query("INSERT INTO ebpls_buss_taxrange (taxfeeid,rangelow,rangehigh,rangeamount,rangeind,rangestatus,datecreated) VALUES ('$aTAXFEEid','$AddRangeLow[$ctr]','$AddRangeHigh[$ctr]','$AddRangeAmount[$ctr]','$AddRangeInd[$ctr]','A',Now())",$link2db);
			// and rangeid='$rangeID[$ctr]'
			//$myid = mysql_query("UPDATE $tbl_current SET naturedesc='$nidescription' WHERE natureid='$natureid' ",$link2db);
			//$validateID=5;
			//echo "<p align=center><font color=#ff0033><b><i>Record successfully updated!!!</font></i></b></p>";
			//include'validate-inc.php';
		} else {
				echo "<div align=center><font color=red<b><i>Invalid Amount ($rangeamount[$ctr])</i></b></font></div>";
		}
		
		}
		if ($AddRangeInd[$ctr]=='2') {
			$openpar = substr_count($AddRangeAmount[$ctr], '(');
			$closepar = substr_count($AddRangeAmount[$ctr], ')');
			$closepar1 = $closepar-2;
			if ($openpar==$closepar1) {
			$INSERTdata = mysql_query("INSERT INTO ebpls_buss_taxrange (taxfeeid,rangelow,rangehigh,rangeamount,rangeind,rangestatus,datecreated) VALUES ('$aTAXFEEid','$AddRangeLow[$ctr]','$AddRangeHigh[$ctr]','$AddRangeAmount[$ctr]','$AddRangeInd[$ctr]','A',Now())",$link2db);
			// and rangeid='$rangeID[$ctr]'
			//$myid = mysql_query("UPDATE $tbl_current SET naturedesc='$nidescription' WHERE natureid='$natureid' ",$link2db);
			//$validateID=5;
			//echo "<p align=center><font color=#ff0033><b><i>Record successfully updated!!!</font></i></b></p>";
			//include'validate-inc.php';
		} else {
				echo "<div align=center><font color=red<b><i>Invalid Amount ($rangeamount[$ctr])</i></b></font></div>";
		}
		
		}
	
	
	$valueof_ResultId=1;
	$eventAddRow='';
	}
	else {
	echo "<br><font color=#ff0033><p align=center>Invalid entry!!! Please check your entry.</p></font>";	
	$nofrow=$noofloop;
	}
	$ctr++;
	$taddr++;
	}
	}
	//else {
	
	
	//} // endif for eventAddrow
	
	if (!empty($aTAXFEEid) and !empty($aTFOID) and !empty($natureid)){
	/*echo "Ok to update...";
	echo "$natureid<br>";
	echo "$aTAXFEEid<br>";
	echo "$aTFOID<br>";
	echo "$iOption<br>";
	//echo "$iIndicator<br>";
	echo "$iMode<br>";
	echo "$iType<br>";
	echo "$niAmountFormula */
	//taxfeeind='$iIndicator', taxfeemode='$iMode', taxfeeamtfor='$niAmountFormula', for Range only!
	$DBupdateRec_ = mysql_query("UPDATE ebpls_buss_taxfeeother SET taxfeeoption='$iOption', datecreated=Now(), taxtype='$iType', tfo_id='$itfotype', uom='$iuom', defamt=$iMinAmount WHERE natureid='$natureid' and tfo_id = '$aTFOID' and taxfeeid='$aTAXFEEid' ",$link2db);
	$aTFOID=$itfotype;
	$niOption=$iOption;
	$niType=$iType;
	$niMinAmount=$iMinAmount;
	$niuom=$iuom;
	$iTFO_data = mysql_query("SELECT * FROM ebpls_buss_tfo WHERE tfoid = '$aTFOID'",$link2db);
		
	if (mysql_affected_rows($link2db)==1 ){
	$niTFO_data=mysql_fetch_array($iTFO_data);
	$ni_typedesc=$niTFO_data[tfodesc];
	$ni_taxfeetype=$niTFO_data[tfoid];
	}
	}
	
}

if (isset($iRows)){
$lastRecord_=$lastRecord_ + 3;	
}
else {
if (empty($varIDreSult_)){
	$lastRecord_=3; // original line of code (1)
}
else {
$lastRecord_=$varIDreSult_;
}
}
// end here for range maintenance

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>eBPLS Business Permit and Licensing System</title>
	<meta name="Author" content=" FourJ's ">
	<link href="includes/eBPLS.css" rel="stylesheet" type="text/css">
<title></title>
</head>
<body>
<form name="_FRM">
<?php
if ($addingrow=='Add Row') {
//$natureid=$natureid;
$class_type=Preference;
$pref_type=Business;
$action_=1;
$actionID=1;
//$part=$part;
$class_type=Preference;
$pref_type=Business;
//$selMode=$selMode;
$natureaction=Edit;
//$bussEvent=$bussEvent;
//$aTFOID=$aTFOID;
$valueof_ResultId=1;
$aTAXFEEid=$aTAXFEEid;
//$rangeid=$rangeid;
$eventAddRow=PlusOne;
//$nofrow=$nofrow;
}
?>
<input type=hidden name=part value="<?php echo $part;?>">
<input type=hidden name=class_type value="<?php echo $class_type;?>">
<input type=hidden name=noofloop value="<?php echo $nofrow;?>">
<input type=hidden name=pref_type value="Business">
<input type=hidden name=selMode value="<?php echo $selMode;?>">
<input type=hidden name=action_ value="<?php echo $action_;?>">
<input type=hidden name=actionID value="<?php echo $actionID;?>">
<input type=hidden name=natureid value="<?php echo $natureid;?>">
<input type=hidden name=natureaction value="<?php echo $natureaction;?>">
<input type=hidden name=tfoid value="<?php echo $tfoid;?>">
<input type=hidden name=reqid value="<?php echo $reqid;?>">
<input type=hidden name=aTFOID value="<?php echo $aTFOID;?>">
<input type=hidden name=aTAXFEEid value="<?php echo $aTAXFEEid;?>">
<input type=hidden name=tfoaction value="<?php echo $tfoaction;?>">
<input type=hidden name=reqaction value="<?php echo $reqaction;?>">
<input type="hidden" name="actionDeleteReq">
<input type="hidden" name="bbo">

<!--Requirements-->
<input type=hidden name=compEvent value="<?php echo $compEvent;?>">
<input type=hidden name=compEvent1 value="<?php echo $compEvent1;?>">
<input type=hidden name=oidof_complex value="<?php echo $oidof_complex;?>">

<input type=hidden name=bussEvent value="<?php echo $bussEvent;?>">
<input type=hidden name=valueof_ResultId value="<?php echo $valueof_ResultId;?>">
<input type=hidden name=eventComplex value="<?php echo $eventComplex;?>">
<input type=hidden name=reftype value="<?php echo $reftype;?>">

<!--
<input type=hidden name=lastRecord_ value="<?php echo $lastRecord_;?>">
<input type=hidden name=rangelow[<?php echo $ctr;?>] value="<?php echo $rangelow[$ctr];?>">
<input type=hidden name=rangehigh[<?php echo $ctr;?>] value="<?php echo $rangehigh[$ctr];?>">
<input type=hidden name=rangeamount[<?php echo $ctr;?>] value="<?php echo $rangeamount[$ctr];?>">
<input type=hidden name=rangeformula[<?php echo $ctr;?>] value="<?php echo $rangeformula[$ctr];?>">
-->

<input type=hidden name=rangelow[] value="<?php echo $rangelow[$ctr];?>">
<input type=hidden name=rangehigh[] value="<?php echo $rangehigh[$ctr];?>">
<input type=hidden name=rangeamount[] value="<?php echo $rangeamount[$ctr];?>">
<input type=hidden name=rangeformula[] value="<?php echo $rangeformula[$ctr];?>">
<input type=hidden name=rangeind[] value="<?php echo $rangeind[$ctr];?>">


<input type=hidden name=eventAddRow value="<?php echo $eventAddRow;?>">

<input type=hidden name=AddRangeLow value="<?php echo $AddRangeLow;?>">
<input type=hidden name=AddRangeHigh value="<?php echo $AddRangeHigh;?>">
<input type=hidden name=AddRangeAmount value="<?php echo $AddRangeAmount;?>">
<input type=hidden name=AddRangeInd value="<?php echo $AddRangeInd;?>">


<table width=100% align=center cellspacing=0 cellpadding=0>
<tr><td colspan=2 class=header align=center width=100%>REFERENCES</td></tr>
<tr>
        <td colspan=2 align=center>
</td>
</tr>


<?php
if ($action_==0){
	if ($confx==1) {
		include_once 'class/TaxFeeOtherChargesClass.php';
		$DelNat = new TaxFee;
		$DelNat->CheckTaxFee($bcode);
		if ($DelNat->outnumrow > 0) {
			?>
			<body onload='javascript:alert ("Cannot Delete, Record exists in other table(s)!!");'></body>
			<?
		} else {
		$strwhere = "natureid='$bcode'";
		$DelNat->DeleteTax('ebpls_buss_nature',$strwhere);
		?>
		<body onload='javascript:alert ("Record Deleted!!");'></body>
		<?
		}
	}
	if ($donfx==1) {
		include_once 'class/TaxFeeOtherChargesClass.php';
		$DelNat = new TaxFee;
		$strvalues = "naturestatus='A'";
		$strwhere = "natureid='$bcode'";
		$DelNat->UpdateNature($strvalues,$strwhere);
	}

?>
<body onload="javascript: _FRM.searcheenat.focus();"></body>
<tr><td colspan=2 class=header2 align=center width=100%>Masterlist of Business Nature</td></tr>
<tr>
<td colspan=2><br><br>&nbsp;<!--To add new record click here.-->

&nbsp;<input type=text name=searcheenat size=20 maxlength=255>&nbsp;<input type = submit name=searchnat value ='Search Nature'><br><br>
<input type = submit name=cbut value ='Add New Record'><br><br>   <!--LEO RENTON.-->

</td>
</tr>

<tr>
<td colspan=2 valign=top align=left><br>
<!--first table within a cell-->
<table width=100%>
<tr bgcolor="#EEEEEE">
<td width=5%>&nbsp;No.</td>
<!--<td width=5%>ID#</td>-->
<td width=60%><a href='index.php?part=4&class_type=Preference&pref_type=Business&selMode=ebpls_nbusiness&action_=0&valueofdesc=desc&orderbyasdes=<?php echo $orderbyasdes;?>&reftype=<?php echo $reftype;?>&searcheenat=<?php echo $searcheenat;?>'>Description</a></td>
<td width=5% align=center>PSIC Code</td>
<td width=20% align=center>Action</td>
</tr>
<input type=hidden name=bcode>
<input type=hidden name=confx>
<input type=hidden name=donfx>
<script language='javascript'>
function DelNature(cc) 

{
         var _FRM = document._FRM;
        doyou = confirm("Record Will Be Deleted, Continue?");
                                                                                                 
                                                                                                 
        if (doyou==true) {
                _FRM.bcode.value = cc;
               _FRM.confx.value = 1;
        } else {
                _FRM.confx.value=0;
				alert("Transaction Cancelled.");
                return false;
        }
              _FRM.submit();
               return true;
}
function ActNature(cc) 

{
         var _FRM = document._FRM;
        doyou = confirm("Record Will Be Activated, Continue?");
                                                                                                 
                                                                                                 
        if (doyou==true) {
                _FRM.bcode.value = cc;
               _FRM.donfx.value = 1;
        } else {
                _FRM.donfx.value=0;
				alert("Transaction Cancelled.");
                return false;
        }
              _FRM.submit();
               return true;
}
</script>

<?php
require 'setup/setting.php';
if(!isset($_GET['page'])){
    $page = 1;
} else {
    $page = $_GET['page'];
}

// Define the number of results per page
$max_results = $thIntPageLimit;
// Figure out the limit for the query based
// on the current page number.
$from = abs((($page * $max_results) - $max_results));

if ($searcheenat<>"") {
	$addends = "where naturedesc like '%$searcheenat%'";
} else {
	$addends = "";
}
if ($valueofdesc=='desc') {
	$searchsql = "SELECT * FROM $tbl_current $addends ORDER BY naturedesc $ascdesc limit $from, $max_results";
} else {
	//if ($searchnat=='Search Nature') {
	//	$searchsql = "SELECT * FROM $tbl_current where naturestatus='A' and naturedesc like '$searcheenat%' ORDER BY naturedesc $ascdesc1 limit $from, $max_results";
	//} else {
		//$searchsql = "SELECT * FROM $tbl_current $addends ORDER BY naturedesc $ascdesc1 limit $from, $max_results";
		$searchsql = "SELECT * FROM ebpls_buss_nature $addends ORDER BY naturedesc $ascdesc1 limit $from, $max_results";
	//}
}


// echo $searcheenat . " ". $searchsql;


if ($searcheenat<>"") {
	$addends = "where naturedesc like '%$searcheenat%'";
} else {
	$addends = "";
}

$cntsql = "SELECT count(*) FROM $tbl_current $addends";



	//$Dbresult=th_query($listmyid);
include'nextpage.php';
	/*
	$listmyid = "SELECT * FROM $tbl_current where naturestatus='A' ORDER BY naturedesc ";
	
	$Dbresult=th_query($listmyid);
	while($dtarow 	= @mysql_fetch_array($Dbresult)){
			$myrow++;	
			include'tablecolor-inc.php';
			echo "<tr bgcolor=$varcolor>"; 
			echo "<td>$myrow</td>";
			//echo "<td>$dtarow[natureid]</td>";
			echo "<td>$dtarow[naturedesc]</td>";
			if ($dtarow[natureoption]=='L'){
			echo "<td align=center><img src=../images/pin.gif width=15 height=15/></td>";
			}
			elseif ($dtarow[natureoption]=='C'){
			echo "<td align=center>-</td>";
			}
			else {
			echo "<td align=center></td>";
			}
			echo "<td align=center> <a href=$PHP_SELF?natureid=$dtarow[natureid]&action_=1&actionID=1&part=$part&class_type=$class_type&pref_type=Business&selMode=$selMode&natureaction=Edit>Edit</a> |  <a href=$PHP_SELF?natureid=$dtarow[natureid]&natureaction=Delete&part=$part&selMode=$selMode>DeActivate</a></td>";//$natureid
			echo "</tr>";
		}
		*/
?>

</table>
<!--end here-->
</td>
</tr>

<?php
}
elseif ($action_==80){
?>
<td colspan=2><b><i>Business Nature (Delete Mode)</i></b> &nbsp <a href=<?php echo $PHP_SELF;?>?part=<?php echo $part;?>&class_type=<?php echo $class_type; ?>&pref_type=Business&selMode=<?php echo $selMode;?>><img src="/images/back1.gif" width=45 height=30/></a></td>

<?php
	$dtaRec = mysql_query("SELECT * FROM $tbl_current WHERE natureid = '$natureid' and naturestatus='A' ",$link2db);
	$numRec=mysql_num_rows($dtaRec);
	
	if ($numRec==1) {
	$nRec=mysql_fetch_array($dtaRec);
	$nidescription=$nRec[naturedesc];
	$niPsicCode=$nRec[psiccode];
	}
	else {
	$validateID=1;
	include'validate-inc.php';
	}
?>

<tr>
<td colspan=2><br></td>
</tr>

<tr>
<td width=15% valign=top>Description</td>
<td width=75%><textarea name=idescription rows=3 cols=60><?php echo $nidescription;?></textarea></td>
</tr>

<tr>
<td width=15% valign=top>PSIC Code</td>
<td width=75%><input type=text name=iPsicCode size=15 maxlength=15 value="<?php echo $niPsicCode;?>"></td>
</tr>

<tr>
<td colspan=2><br></td>
</tr>

<tr>
<td colspan=2 align=center><font color="#ff0033">Are you sure you want to DeActivate this record??? [ <a href=<?php echo $PHP_SELF;?>?natureid=<?php echo $natureid;?>&actionDeleteStatus=Ok&part=<?php echo $part;?>&class_type=<?php echo $class_type; ?>&pref_type=Business&selMode=<?php echo $selMode;?>&reftype=bus&orderbyasdes=1>Yes</a> ] [ <a href=<?php echo $PHP_SELF;?>?part=<?php echo $part;?>&class_type=<?php echo $class_type; ?>&pref_type=Business&selMode=<?php echo $selMode;?>&reftype=bus&orderbyasdes=1>Cancel</a> ] </font></td>
</tr>

<tr>
<td colspan=2><br></td>
</tr>

<tr>
<td colspan=2><br></td>
</tr>

<?php
}
elseif ($action_==1){ // Business Nature Add Module start here...
?>
<tr>
<?php
if ($natureaction=='Edit'){
?>
<td colspan=2 class=header2 align=center width=100%>Business Nature (Edit Mode)</td>
<?php
}
else {
?>	
<td colspan=2 class=header2 align=center width=100%>Business Nature (Add Mode)</td>
<?php
}
?>

</tr>
<? 

if ($actionID=='' and $bussEvent == "") {
	echo "<body onload=\"javascript: _FRM.idescription.focus();\"></body>";
} else {
	echo "<body onload=\"javascript: _FRM.tfotype.focus();\"></body>";
}
?>
<tr bgcolor="#EEEEEE">
<td colspan=2><br></td>
</tr>

<tr>
<td width=15% valign=top>Description</td>
<td width=75%><input type=text name=idescription maxlength=40 size=50 value="<?php echo $nidescription;?>"></td>
</tr>

<tr>
<td width=15% valign=top>PSIC Code</td>
<td width=75%><input type=text name=iPsicCode size=15 maxlength=15 value="<?php echo $niPsicCode;?>"></td>
</tr>

<tr>
<td colspan=2><br></td>
</tr>

<?php
if (empty($bussEvent)){
?>
<tr>
<td><br></td>
<td><input type=submit name=ISubmitBussNature value="Save">

<input type=button name=iCancelNature value="Cancel" onClick=" alert('Transaction Cancelled!!'); parent.location='<?php echo $PHP_SELF;?>?part=<?php echo $part;?>&class_type=<?php echo $class_type; ?>&pref_type=Business&selMode=<?php echo $selMode;?>&action_=0&orderbyasdes=1&reftype=<?php echo $reftype;?>';">

<input type=button name=iCancel1Nature value="View All" onClick="parent.location='index.php?part=4&class_type=Preference&pref_type=Business&selMode=ebpls_nbusiness&action_=0&orderbyasdes=1&reftype=bus&permit_type=Business';">




</td>
</tr>

<tr>
<td colspan=2><br></td>
</tr>

<?php
}
?>

<!-- End here for Business Nature Information-->

<?php
if (!empty($actionID) and $actionID==1){
?>
<!--Event for maintaining the taxes, fees and other charges -->
<?php
if ($bussEvent==1){
include'ebpls5503-1.php';
}
elseif ($bussEvent==2){
include'ebpls5503-2.php';
}
elseif ($bussEvent==3){
include'ebpls5503-3.php';
}
else {
include'ebpls5503-4.php';
}
?>

<!--End here for taxes, fees and other charges maintenance-->

<?php
}
?>

<?php
}
elseif ($action_==101){
?>
<tr>
<td colspan=2><br></td>
</tr>

<tr>
<td colspan=2><b><i>Business Nature (<?php echo $eventApply;?>... Mode)</i></b> &nbsp <a href=<?php echo $PHP_SELF;?>?natureid=<?php echo $natureid;?>&action_=1&actionID=1&part=<?php echo $part;?>&class_type=<?php echo $class_type; ?>&pref_type=Business&selMode=<?php echo $selMode;?>&natureaction=Edit&bussEvent=<?php echo $bussEvent;?>&aTFOID=<?php echo $aTFOID;?>&valueof_ResultId=1&aTAXFEEid=<?php echo $aTAXFEEid;?>&rangeid=<?php echo $rangeid;?>&rangeAction=>Back</a></td>
</tr>

<tr>
<td colspan=2 bgcolor="#EEEEEE"><br></td>
</tr>

<tr>
<td colspan=2><br></td>
</tr>

<tr>
<td colspan=2>Select Business Nature here:</td>
</tr>

<tr>
<td colspan=2><br></td>
</tr>

<tr>
<td colspan=2><select name=iCopytoNature>
<?php
$Xnatureid=$natureid;
$seleNature=mysql_query("Select * from ebpls_buss_nature where natureid!='$natureid' and naturestatus='A' ORDER BY naturedesc",$link2db); // L means with range link attached
//$seleNature=mysql_query("Select * from ebpls_buss_nature where natureid!='$natureid' and naturestatus='A' and natureoption is null ORDER BY naturedesc",$link2db); // L means with range link attached
$rowNature=mysql_num_rows($seleNature);
if ($rowNature>0){
	$myrow=1;
	$ctrrow=0;
	while (list($natureid,$naturedesc) = mysql_fetch_row($seleNature)){
		echo "<option value=$natureid>$naturedesc";
	$myrow++;
	}
}
?>
</select>
</td>
</tr>

<tr>
<td colspan=2><br></td>
</tr>

<?php
//if ($eventApply=='Applyto'){
//?>
<!--<tr>
<td colspan=2><input type=submit name=iSubmitCopytoNature value="Apply Range To"></td>
</tr>-->
//<?php
//}
if ($eventApply=='Applyto') {
?>
<tr>
<td colspan=2><input type=submit name=iSubmitApplytoNature value="Apply Range To"></td>
</tr>

<?php
}
?>
<input type=hidden name=Xnatureid value="<?php echo $Xnatureid;?>">
<input type=hidden name=eventApply value="<?php echo $eventApply;?>">
<input type=hidden name=searcheenat value="<?php echo $searcheenat;?>">

<?php
//include'ebpls5503-4.php';
?>


<?php
}
elseif ($action_==2){
?>

<tr>
<td colspan=2><b><i>&nbsp;&nbsp;Master List of Requirements</i></b> <br> <br>&nbsp;<!--To add new record click here.-->&nbsp;<input type = submit name=reqbut value ='Add New Record'></td>
</tr>

<tr>
<td colspan=2 valign=top align=left><br>
<!--first table within a cell-->
<table width=100%>
<tr><td align=right colspan=5><!--<i>Legend Indicator: </i> <font color="#ff0033">0=Normal | 1=Default</font>--></td></tr>

<tr bgcolor="#EEEEEE">
<td width=5%>&nbsp;No.</td>
<td width=55%><a href=<?php echo $PHP_SELF;?>?part=<?php echo $part;?>&class_type=<?php echo $class_type; ?>&pref_type=Business&selMode=<?php echo $selMode;?>&action_=2&valueofKey1=REQDescription&orderbyasdes=<?php echo $orderbyasdes;?>>Description</a></td>
<td width=10% align=center><a href=<?php echo $PHP_SELF;?>?part=<?php echo $part;?>&class_type=<?php echo $class_type; ?>&pref_type=Business&selMode=<?php echo $selMode;?>&action_=2&valueofKey1=REQIndicator&orderbyasdes=<?php echo $orderbyasdes;?>>Indicator</a></td>
<!--<td width=10% align=center><a href=<?php echo $PHP_SELF;?>?part=<?php echo $part;?>&class_type=<?php echo $class_type; ?>&pref_type=Business&selMode=<?php echo $selMode;?>&action_=2&valueofKey1=REQStatus&orderbyasdes=<?php echo $orderbyasdes;?>>Status</a></td>-->
<td width=20% align=center>Action</td>
</tr>

<?php
	if (!empty($valueofKey1)){
		if ($valueofKey1=='REQDescription'){
			$listmyid = "SELECT * FROM $tbl_current where recstatus='A' ORDER BY reqdesc $ascdesc";	
		}
		elseif ($valueofKey1=='REQIndicator'){
			$listmyid = "SELECT * FROM $tbl_current where recstatus='A' ORDER BY reqindicator,reqdesc $ascdesc";	
		}
		/*
		elseif ($valueofKey1=='REQStatus'){
		$listmyid = "SELECT * FROM $tbl_current where reqstatus='A' ORDER BY taxfeetype,tfodesc ";	
		}*/
		/*else {
		$listmyid = "SELECT * FROM $tbl_current where tfostatus='A' ORDER BY tfodesc ";		
		}*/
				
	}
	else {
		$listmyid = "SELECT * FROM $tbl_current where recstatus='A' ORDER BY reqdesc $ascdesc";
	}
	$Dbresult=th_query($listmyid);
	while($dtarow 	= @mysql_fetch_array($Dbresult)){
			$myrow++;	
			include'tablecolor-inc.php';
			
			echo "<tr bgcolor=$varcolor>"; 
			echo "<td valign=top>&nbsp;$myrow</td>";
			echo "<td valign=top>$dtarow[reqdesc]</td>";
			if ($dtarow[reqindicator]==0) {
				$rindicator='Normal';
			} else {
				$rindicator='Default';
			}
			echo "<td valign=top align=center>$rindicator</td>";			
		//	echo "<td valign=top align=center>$dtarow[reqindicator]</td>";			
			echo "<td valign=top align=center> <a href=$PHP_SELF?reqid=$dtarow[reqid]&action_=21&actionID=1&part=$part&class_type=$class_type&pref_type=Business&selMode=$selMode&reqaction=Edit>Edit</a> | ";
			?>

			<a href="#" onClick="javascript: confirm("Delete Record?"); DeleReq('<? echo $dtarow[reqid];?>');">DeActivate</a></td>
			<?
			echo "</tr>";
		}
?>

</table>

<!--end here-->
</td>
</tr>

<?php
}
elseif ($action_==21){
?>

<tr>
<td colspan=2><b><i>Requirements (Add Module)</i></b></td><!--&nbsp <a href=<?php echo $PHP_SELF;?>?part=<?php echo $part;?>&class_type=<?php echo $class_type; ?>&pref_type=Business&selMode=<?php echo $selMode;?>><img src="/images/back1.gif" width=35 height=35/></a>-->
</tr>

<tr bgcolor="#EEEEEE">
<td colspan=2><br></td>
</tr>

<tr>
<td width=15% valign=top>Description</td>
<td width=75%><input type=text name=ireqdescription size=50 maxlength=100 value="<?php echo$nireqdescription;?>"> &nbsp &nbsp &nbsp 
<?php
if (!empty($nireqindicator) and $nireqindicator == 1){
?>
<input type=checkbox name=ireqindicator value=1 CHECKED> Set as default
<?php
}
else {
?>
<input type=checkbox name=ireqindicator value=1 CHECKED> Set as default
<?php
}
?>

</td>
</tr>
<tr>
<td width=15% valign=top>Permit Type</td>
<td width=75%><select name=iPermitType >
<?php
if ($niPermitType=='') {
	$niPermitType='Business';
}
?>
<option value='<?php echo $niPermitType;?>'><?php echo $niPermitType;?></option>
<option value='Business'>Business</option>
<option value='Motorized'>Motorized</option>
<option value='Occupational'>Occupational</option>
<option value='Peddlers'>Peddlers</option>
<option value='Franchise'>Franchise</option>
<option value='Fishery'>Fishery</option>
</select>
</td>
</tr>
<tr>
<td colspan=2><hr size=2 width=100%></td>
</tr>

<tr>
<td colspan=2><br></td>
</tr>

<tr>
<td><br></td>
<script language="Javascript">
function CheckValues()
{
	var _FRM = document._FRM;
	var msgTitle = "Permit Requirements\n";
	if(_FRM.ireqdescription.value == "")
	{
		alert( msgTitle + "Please input a valid description!");
		return false;
	}
	if(_FRM.ireqdescription.value.length > 30)
	{
		alert("Description exceeds maximum length!");
		return false;
	}
	_FRM.ISubmitREQ.value = "Submit";
	_FRM.submit();
	return true;
}

</script>
<input type="hidden" name="ISubmitREQ">
<input type="hidden" name="actionDeleteReq">
<input type="hidden" name="bbo">
<td><input type=Button name=ISubmitquiue value="Save" onClick="javascript: CheckValues();">
<input type=button name=iCancelREQ value="Cancel" onClick="parent.location=' <?php echo $PHP_SELF;?>?part=<?php echo $part;?>&class_type=<?php echo $class_type; ?>&pref_type=Business&selMode=<?php echo $selMode;?>&action_=2&orderbyasdes=1>';">
<input type=button name=iViewAll value="View All" onClick="parent.location=' <?php echo $PHP_SELF;?>?part=<?php echo $part;?>&class_type=<?php echo $class_type; ?>&pref_type=Business&selMode=<?php echo $selMode;?>&action_=2&orderbyasdes=1>';">
</td>
</tr>

<?php
}
elseif ($action_==82){
?>

<tr>
<td colspan=2><b><i>Requirements (DeActivate Mode)</i></b></td><!--&nbsp <a href=<?php echo $PHP_SELF;?>?part=<?php echo $part;?>&class_type=<?php echo $class_type; ?>&selMode=<?php echo $selMode;?>><img src="/images/back1.gif" width=35 height=35/></a>-->
</tr>

<tr bgcolor="#EEEEEE">
<td colspan=2><br></td>
</tr>

<tr>
<td width=15% valign=top>Description</td>
<td width=75%><input type=text name=ireqdescription size=50 maxlength=100 value="<?php echo$nireqdescription;?>"> &nbsp &nbsp &nbsp 
<?php
if (!empty($nireqindicator) and $nireqindicator == 1){
?>
<input type=checkbox name=ireqindicator value=1 CHECKED> Set as default
<?php
}
else {
?>
<input type=checkbox name=ireqindicator value=1 CHECKED> Set as default
<?php
}
?>

</td>
</tr>

<tr>
<td colspan=2><hr size=2 width=100%></td>
</tr>

<tr>
<td colspan=2><br></td>
</tr>

<tr>
<td colspan=2 align=center><font color="#ff0033">Are you sure you want to deactivate this record??? [ <a href=<?php echo $PHP_SELF;?>?reqid=<?php echo $reqid;?>&actionDeleteReq=Ok&part=<?php echo $part;?>&class_type=<?php echo $class_type; ?>&selMode=<?php echo $selMode;?>&action_=2&reftype=req&orderbyasdes=1>Yes</a> ] [ <a href=<?php echo $PHP_SELF;?>?part=<?php echo $part;?>&class_type=<?php echo $class_type; ?>&selMode=<?php echo $selMode;?>&action_=2&reftype=req&orderbyasdes=1>Cancel</a> ] </font></td>
</tr>

<?php
}
elseif ($action_==9){
?>

<tr>
<td colspan=2><b><i>&nbsp;&nbsp;Master List of PSIC</i></b> <br> <br>&nbsp;To add new record click here.
&nbsp;<input type = submit name=psicbut value = ADD></td>
</tr>

<tr>
<td colspan=2 valign=top align=left><br>
<!--first table within a cell-->
<table width=100%>
<tr bgcolor="#EEEEEE">
<td width=5%>&nbsp;No.</td>
<td width=55%><a href=<?php echo $PHP_SELF;?>?part=<?php echo $part;?>&class_type=<?php echo $class_type; ?>&pref_type=Business&selMode=<?php echo $selMode;?>&action_=9&valueofKey2=PSICCode&orderbyasdes=<?php echo $orderbyasdes;?>>Description</a></td>
<td width=10% align=center><a href=<?php echo $PHP_SELF;?>?part=<?php echo $part;?>&class_type=<?php echo $class_type; ?>&pref_type=Business&selMode=<?php echo $selMode;?>&action_=9&valueofKey2=PSICDescription&orderbyasdes=<?php echo $orderbyasdes;?>>Indicator</a></td>
<!--<td width=10% align=center><a href=<?php echo $PHP_SELF;?>?part=<?php echo $part;?>&class_type=<?php echo $class_type; ?>&pref_type=Business&selMode=<?php echo $selMode;?>&action_=2&valueofKey1=REQStatus&orderbyasdes=<?php $orderbyasdes;?>>Status</a></td>-->
<td width=20% align=center>Action</td>
</tr>

<?php
	if (!empty($valueofKey2)){
		if ($valueofKey1=='PSICDescription'){
		$listmyid = "SELECT * FROM $tbl_current where psicstatus='A' ORDER BY psicdesc $ascdesc";	
		}
		elseif ($valueofKey2=='PSICCode'){
		$listmyid = "SELECT * FROM $tbl_current where psicstatus='A' ORDER BY psiccode,psicdesc $ascdesc";	
		}
		/*
		elseif ($valueofKey1=='REQStatus'){
		$listmyid = "SELECT * FROM $tbl_current where reqstatus='A' ORDER BY taxfeetype,tfodesc ";	
		}*/
		/*else {
		$listmyid = "SELECT * FROM $tbl_current where tfostatus='A' ORDER BY tfodesc ";		
		}*/
				
	}
	else {
	$listmyid = "SELECT * FROM $tbl_current where psicstatus='A' ORDER BY psicdesc $ascdesc";
	}
	$Dbresult=th_query($listmyid);
	while($dtarow 	= @mysql_fetch_array($Dbresult)){
			$myrow++;	
			include'tablecolor-inc.php';
			
			echo "<tr bgcolor=$varcolor>"; 
			echo "<td valign=top>&nbsp;$myrow</td>";
			echo "<td valign=top>$dtarow[psiccode]</td>";
			echo "<td valign=top align=center>$dtarow[psicdesc]</td>";			
			echo "<td valign=top align=center><a href=$PHP_SELF?psicid=$dtarow[psicid]&action_=91&actionID=1&part=$part&class_type=$class_type&pref_type=Business&selMode=$selMode&psicaction=Edit>Edit</a> | <a href=$PHP_SELF?psicid=$dtarow[psicid]&psicaction=Delete&part=$part&class_type=$class_type&pref_type=Business&selMode=$selMode&action_=9>DeActivate</a></td>";
			echo "</tr>";
		}
?>

</table>
<!--end here-->
</td>
</tr>


<?php
}
elseif ($action_==91){
?>

<tr>
<td colspan=2><b><i>PSIC (Add Module)</i></b></td><!--&nbsp <a href=<?php echo $PHP_SELF;?>?part=<?php echo $part;?>&class_type=<?php echo $class_type; ?>&pref_type=Business&selMode=<?php echo $selMode;?>><img src="/images/back1.gif" width=35 height=35/></a>-->
</tr>

<tr bgcolor="#EEEEEE">
<td colspan=2><br></td>
</tr>

<tr>
<td width=15% valign=top>PSIC Code</td>
<td width=75%><input type=text name=ipsiccode size=15 maxlength=20 value="<?php echo$nipsiccode;?>"></td>
</tr>

<tr>
<td width=15% valign=top>Description</td>
<td width=75%><input type=text name=ipsicdescription size=50 maxlength=255 value="<?php echo$nipsicdescription;?>"></td>
</tr>


<tr>
<td colspan=2><hr size=2 width=100%></td>
</tr>

<tr>
<td colspan=2><br></td>
</tr>

<tr>
<td><br></td>
<td><input type=submit name=ISubmitPSIC value="Submit">
<input type=button name=iCancelREQ value="Cancel" onClick="parent.location=' <?php echo $PHP_SELF;?>?part=<?php echo $part;?>&class_type=<?php echo $class_type; ?>&pref_type=Business&selMode=<?php echo $selMode;?>&action_=2&orderbyasdes=1>';">
<input type=button name=iViewAll value="View All" onClick="parent.location=' <?php echo $PHP_SELF;?>?part=<?php echo $part;?>&class_type=<?php echo $class_type; ?>&pref_type=Business&selMode=<?php echo $selMode;?>&action_=2&orderbyasdes=1>';">

</td>
</tr>


<?
}
elseif ($action_==3){
?>
<tr>
<td colspan=2><b><i>Tax, Fee and Other Charges (Add Module)</i></b></td><!--&nbsp <a href=<?php echo $PHP_SELF;?>?part=<?php echo $part;?>&class_type=<?php echo $class_type; ?>&pref_type=Business&selMode=<?php echo $selMode;?>><img src="/images/back1.gif" width=35 height=35/></a>-->
</tr>

<tr bgcolor="#EEEEEE">
<td colspan=2><br></td>
</tr>

<tr>
<td width=15% valign=top>DESCRIPTION</td>
<td width=75%><input type=text name=itfodescription size=50 maxlength=80 value="<?php echo $nitfodescription;?>"></td>
</tr>

<tr>
<td width=15% valign=top>TYPE</td>
<td width=75%><select name=itfotype>
<?php
	$Dbtype_ = "SELECT * FROM  ebpls_buss_taxfeetype ORDER BY taxfeetype ";
	$Dbtyperesult=th_query($Dbtype_);
	while($dtarow 	= @mysql_fetch_array($Dbtyperesult)){
		if ($dtarow[typedesc]==$itfotype) {
			$lec='selected';
		} else {
			$lec='';
		}
		echo "<option value=$dtarow[taxfeetype] $lec>$dtarow[typedesc]";
	}
	echo "<option value=$nitfotype SELECTED>$ntypedesc";
?>
</select> &nbsp &nbsp &nbsp
<?php
if ($nitfoindicator == 1){ //!empty($nitfoindicator) and 
?>
<input type=checkbox name=itfoindicator value=1 CHECKED> SET AS DEFAULT
<?php
}
else {
?>
<input type=checkbox name=itfoindicator value=1> SET AS DEFAULT
<?php
}
?>

</td>
</tr>

<script language='Javascript' src='javascripts/default.js'></script>
<script language="Javascript">

function CheckIt(x)
{
        var _FRM = document._FRM;
                var msgTitle = "Tax, Fee and Other Charges\n";
                if(isNaN(x.value))
                {
                        alert( msgTitle + "Please input a valid amount!");
                        _FRM.pay.focus();
                        return false;
                }
}

</script>

</td>
</tr>
         
<?php 
	if ($tfoaction=='Edit') {
	$getde= mysql_query("select * from ebpls_buss_tfo where tfoid=$tfoid") or die ("-");
	$defamt=mysql_fetch_row($getde);
	$defamt=$defamt[6];
	}
?>
                                                                                        
<tr>
<td width=15% valign=top>DEFAULT AMOUNT</td>
<td width=75%>
<input type=text size=15 name=defamt onBlur='javascript:CheckIt(defamt)' value=<?php if ($defamt=='') { $defamt=0; }  echo $defamt; ?>>
<?php
$chkdb=mysql_query("select or_print from ebpls_buss_preference") or die(mysql_error);
$chkdb=mysql_fetch_row($chkdb);
if ($chkdb[0]==1) {
	print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
if ($niseparate_or == 1){
?>
<input type=checkbox name=iseparate_or value=1 CHECKED> Separate O.R.
<?php
}
else {
?>
<input type=checkbox name=iseparate_or value=1> Separate O.R.
<?php
}
}

if ($nubert==0) {
	$ele = 'selected';
} else {
	$elw = 'selected';
}

?>
</td>


</tr>
<tr>
<td width=15% valign=top>NO. OF YEARS</td>
<td width=75%>
<select name=ubert class=select2000>
<option value=0 <?php echo $ele; ?>>EVERY</option>
<option value=1 <?php echo $elw; ?>>WITH IN</option>
</select>

<input type=text size=15 name=ilangtaon value=<?php echo $nilangtaon; ?>>
</td>
</tr>


<tr>
<td colspan=2><hr size=2 width=100%></td>
</tr>

<tr>
<td colspan=2><br></td>
</tr>

<tr>
<td><br></td>
<td><input type=submit name=ISubmitTFO value="Submit">

<input type=button name=iCancelTFO value="Cancel" onClick="parent.location=' <?php echo $PHP_SELF;?>?part=<?php echo $part;?>&class_type=<?php echo $class_type; ?>&pref_type=Business&selMode=<?php echo $selMode;?>&action_=5&valueofKey=TFODescription&orderbyasdes=1&reftype=<?php echo $reftype;?>';">

<input type=button name=iCancel1TFO value="View All" onClick="parent.location=' <?php echo $PHP_SELF;?>?part=<?php echo $part;?>&class_type=<?php echo $class_type; ?>&pref_type=Business&selMode=<?php echo $selMode;?>&action_=5&valueofKey=TFODescription&orderbyasdes=1&reftype=<?php echo $reftype;?>';">

</td>
</tr>

<?php
}
elseif ($action_==81){
?>
<tr>
<td colspan=2><b><i>Tax, Fee and Other Charges (DeActivate Mode)</i></b></td><!--&nbsp <a href=<?php echo $PHP_SELF;?>?part=<?php echo $part;?>&class_type=<?php echo $class_type; ?>&pref_type=Business&selMode=<?php echo $selMode;?>><img src="/images/back1.gif" width=35 height=35/></a>-->
</tr>

<tr bgcolor="#EEEEEE">
<td colspan=2><br></td>
</tr>

<?php
	$DtaRec = mysql_query("SELECT * FROM $tbl_current WHERE tfoid = '$tfoid' ",$link2db);
	$numDtaRec=mysql_num_rows($DtaRec);
	if ($numDtaRec==1){
	$nTfo=mysql_fetch_array($DtaRec);
	$nitfodescription=$nTfo[tfodesc];
	$nitfoindicator=$nTfo[tfoindicator];
	$nitfotype=$nTfo[taxfeetype];
	$nilangtaon=$nTfo[or_print];
	$nubert=$nTFO[counter];
	
	$DBtype1=mysql_query("SELECT * FROM ebpls_buss_taxfeetype WHERE taxfeetype = $nitfotype ",$link2db);
	if (mysql_affected_rows($link2db)==1 ){
	$nDb1=mysql_fetch_array($DBtype1);
	$ntypedesc=$nDb1[typedesc];
	}
	
	}
?>
<tr>
<td width=15% valign=top>Description</td>
<td width=75%><input type=text name=itfodescription size=50 maxlength=80 value="<?php echo$nitfodescription;?>"></td>
</tr>

<tr>
<td width=15% valign=top>Type</td>
<td width=75%><select name=itfotype>
<?php
	echo "<option value=$nitfotype SELECTED>$ntypedesc";
?>
</select> &nbsp &nbsp &nbsp
<?php
if ($nitfoindicator == 1){ //!empty($nitfoindicator) and 
?>
<input type=checkbox name=itfoindicator value=1 CHECKED> Set as default
<?php
}
elseif ($nitfoindicator == 0) {
?>
<input type=checkbox name=itfoindicator value=1 > Set as default
<?php
}
?>

</td>
</tr>

<tr>
<td colspan=2><br></td>
</tr>

<tr>
<td colspan=2><hr size=2 width=100%></td>
</tr>

<tr>
<td colspan=2><br></td>
</tr>

<tr>
<td colspan=2 align=center><font color="#ff0033">Are you sure you want to deactivate this record??? [ <a href=<?php echo $PHP_SELF;?>?tfoid=<?php echo $tfoid;?>&actionDeleteTfo=Ok&part=<?php echo $part;?>&class_type=<?php echo $class_type; ?>&pref_type=Business&selMode=<?php echo $selMode;?>&action_=5&reftype=tfo&orderbyasdes=1>Yes</a> ] [ <a href=<?php echo $PHP_SELF;?>?part=<?php echo $part;?>&class_type=<?php echo $class_type; ?>&pref_type=Business&selMode=<?php echo $selMode;?>&action_=5&reftype=tfo&orderbyasdes=1>Cancel</a> ] </font></td>
</tr>

<?php
}
elseif ($action_==5){
?>
<tr>
<td colspan=2><b><i>&nbsp;&nbsp;Master List of Taxes, Fees and Other Charges</i></b> <br> <br>

&nbsp;<!--To add new record click here.-->&nbsp;<input type=submit name=tfobut value='Add New Record'></td>
</tr>

<tr>
<td colspan=2 valign=top align=left><br>
<!--first table within a cell-->
<table width=100%>
<tr><td align=right colspan=5><!--<i>Legend Indicator: </i> <font color="#ff0033">0=Normal | 1=Default</font>--></td></tr>

<tr bgcolor="#EEEEEE">
<td width=5%>&nbsp;No.</td>
<td width=35%><a href=<?php echo $PHP_SELF;?>?part=<?php echo $part;?>&class_type=<?php echo $class_type; ?>&pref_type=Business&selMode=<?php echo $selMode;?>&action_=5&valueofKey=TFODescription&orderbyasdes=<?php echo $orderbyasdes;?>&reftype=<?php echo $reftype;?>>Description</a></td>
<td width=10% align=center><a href=<?php echo $PHP_SELF;?>?part=<?php echo $part;?>&class_type=<?php echo $class_type; ?>&pref_type=Business&selMode=<?php echo $selMode;?>&action_=5&valueofKey=TFODefault&orderbyasdes=<?php echo $orderbyasdes;?>&reftype=<?php echo $reftype;?>>Default/Minimum Amount</a></td>
<td width=10% align=center><a href=<?php echo $PHP_SELF;?>?part=<?php echo $part;?>&class_type=<?php echo $class_type; ?>&pref_type=Business&selMode=<?php echo $selMode;?>&action_=5&valueofKey=TFOIndicator&orderbyasdes=<?php echo $orderbyasdes;?>&reftype=<?php echo $reftype;?>>Indicator</a></td>
<td width=10% align=center><a href=<?php echo $PHP_SELF;?>?part=<?php echo $part;?>&class_type=<?php echo $class_type; ?>&pref_type=Business&selMode=<?php echo $selMode;?>&action_=5&valueofKey=TFOType&orderbyasdes=<?php echo $orderbyasdes;?>&reftype=<?php echo $reftype;?>>Type</a></td>
<td width=20% align=center>Action</td>
</tr>

<?php
require 'setup/setting.php';
if(!isset($_GET['page'])){
    $page = 1;
} else {
    $page = $_GET['page'];
}

// Define the number of results per page
$max_results = $thIntPageLimit;
// Figure out the limit for the query based
// on the current page number.
$from = abs((($page * $max_results) - $max_results));

/*if ($valueofdesc=='desc') {
	$searchsql = "SELECT * FROM $tbl_current where tfostatus='A' and tfoindicator!=3 ORDER BY tfodesc $ascdesc";	
	$ascdesc1=$ascdesc;
} else {
	$searchsql = "SELECT * FROM $tbl_current where tfostatus='A' ORDER BY naturedesc $ascdesc1 limit $from, $max_results";
}



*/
	//$Dbresult=th_query($listmyid);


	if (!empty($valueofKey)){
		if ($valueofKey=='TFODescription'){
			$searchsql = "SELECT * FROM $tbl_current where tfoindicator!=3 ORDER BY tfodesc $ascdesc limit $from, $max_results";	
		}
		elseif ($valueofKey=='TFOIndicator'){
			$searchsql = "SELECT * FROM $tbl_current where tfoindicator!=3 ORDER BY tfoindicator $ascdesc,tfodesc limit $from, $max_results";	
		}
		elseif ($valueofKey=='TFOType'){
			$searchsql = "SELECT * FROM $tbl_current where tfoindicator!=3 ORDER BY taxfeetype,tfodesc $ascdesc limit $from, $max_results";	
		}
		elseif ($valueofKey=='TFODefault'){
			$searchsql = "SELECT * FROM $tbl_current where tfoindicator!=3 ORDER BY defamt $ascdesc limit $from, $max_results";	
		}
	}
		

		else {
		$searchsql = "SELECT * FROM $tbl_current ORDER BY tfodesc $ascdesc limit $from, $max_results";		
		}
		
		$cntsql = "SELECT count(*) FROM $tbl_current where tfoindicator!=3";		
		
		include'nextpage.php';
		/*		
		}
		else {
//			if ($orderbyasdes==0) {
				$listmyid = "SELECT * FROM $tbl_current where tfostatus='A' and tfoindicator!=3 ORDER BY tfodesc $ascdesc";
//			} else {
//				$listmyid = "SELECT * FROM $tbl_current where tfostatus='A' and tfoindicator!=3 ORDER BY tfodesc desc";
//			}
		}
	}
	$Dbresult=th_query($listmyid);
	while($dtarow 	= @mysql_fetch_array($Dbresult)){
			$myrow++;	
			include'tablecolor-inc.php';
			$defFormat=number_format($dtarow[defamt],2);
			echo "<tr bgcolor=$varcolor>"; 
			echo "<td valign=top>&nbsp;$myrow</td>";
			echo "<td valign=top>$dtarow[tfodesc]</td>";
			echo "<td valign=top align=right>$defFormat</td>";
			if ($dtarow[tfoindicator]==0) {
				$rindicator='Normal';
			} else {
				$rindicator='Default';
			}
			echo "<td valign=top align=center>$rindicator</td>";			
			
			switch ($dtarow[taxfeetype]){
			 case 1:$itypedesc='TAX'; break;
			 case 2:$itypedesc='FEE'; break;			 
			 case 3:$itypedesc='OTHERS'; break;
			}
									
			echo "<td valign=top align=center>$itypedesc</td>";
			
			echo "<td valign=top align=center> <a href=$PHP_SELF?tfoid=$dtarow[tfoid]&action_=3&class_type=$class_type&pref_type=Business&actionID=1&part=$part&class_type=$class_type&selMode=$selMode&tfoaction=Edit>Edit</a> |  <a href=$PHP_SELF?tfoid=$dtarow[tfoid]&class_type=$class_type&pref_type=Business&tfoaction=Delete&part=$part&selMode=$selMode&action_=5>DeActivate</a></td>";
			echo "</tr>";
		}*/
?>

</table>
<!--end here-->
</td>
</tr>

<?php
}
?>

<tr>
<td colspan=2 align=center>
<?php
$data_item=1;
include'tablemenu-inc.php';
?>
</td>
</tr>

</table>

<?php
//mysql_close($thDbLink);
?>
</form>
</body>
</html>
