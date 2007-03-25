<?php

if ($comm_ass==1) {
	$wopi = 0;

	while ($wopi<$gr) {
		if (!is_numeric($gross[$wopi]) || $gross[$wopi]<1) {
?>
	<body onload="alert('Invalid Gross Sales');"></body>
<?php
		$cango = 0;
		} else {
		$cango=1;
		}
		$wopi++;
	}
	
	if ($cango=='1') {
	$wopi=0;
	$ui = UpdateQuery($dbtype,$dbLink,"tempassess",
                "active = 0","owner_id='$owner_id' and
                 business_id='$business_id'");
	while ($wopi<$gr) {
	$tempid = $tempbus[$wopi];
	$lastyr = $gross[$wopi];
	
	
	 $getbus=SelectDataWhere($dbtype,$dbLink,"tempbusnature",
					"where tempid=$tempid");
			        $getbu = FetchRow($dbtype,$getbus);
			          $bus_code = $getbu[1];
			        $bus_nature = $getbu[2];
			        $oldlay = $getbu[4];
			        $result = InsertQuery($dbtype,$dbLink,"tempbusnature","",
		                        "'', '$bus_code', '$bus_nature',$lastyr,
                	        	$lastyr,$owner_id,$business_id, now(),
	                        	0, 1,'','$stat','0'");
        			$wil = UpdateQuery($dbtype,$dbLink,"tempbusnature","active=0",
	                             "tempid='$tempid'");
	


	                             	
		$wopi++;
	} // end wopi
}




?>

<body onload="alert('Assessment Process Completed'); parent.location='index.php?part=4&newpred=1&noregfee=1&class_type=Permits&itemID_=4212&owner_id=<?php echo $owner_id; ?>&com=assess&permit_type=Business&stat=<?php echo $stat; ?>&business_id=<?php echo $business_id; ?>&busItem=Business&istat=<?php echo $stat; ?>&orignat=<?php echo $cntnat; ?>';"></body>

<?php

} //end comass
?>



<table border=0 cellspacing=0 cellpadding=0   width='100%'>
		<tr><td align="center" valign="center" class='header'  width='100%'> Business Enterprise Permit (Gross Needed)</td></tr>
		<tr><td align="center" valign="center" class='normal' height=10>&nbsp;</td></tr>
		<tr>
			<td align="center" valign="center">
			  <form name="_FRM" method="POST" action="" onSubmit="">
<input type=hidden  name =stat value='<?php echo $stat;?>'>				
          <table border=0 cellspacing=0 cellpadding=0 width='90%'>
            <tr> 
              <td align="center" valign="top" class='header2' colspan=4 > 
                Owner Information</td>
            </tr>
            <tr> 
	        <td align="right" valign="top" class='normal' colspan=4 width=20%>&nbsp;</td>
            </tr>
            <input type=hidden name=noregfee value="<?php echo $noregfee; ?>">
            <tr> 
              <td align="right" valign="top" class='normal' colspan=1> &nbsp; 
                 </td>
              <td align="left" valign="top" class='normal' colspan=3 width=20%>&nbsp;
					
<input type='hidden' name='owner_id' maxlength=25  value="<?php echo $owner_id; ?>"> 
              </td>
            </tr>
            <tr> 
<?php require_once "includes/owner_info.php";?>
</tr>
</table><br>

<table border="0" width="51%" id="table1">
<tr><td colspan=2 align="center" valign="center" class='header2' width='100%'> Line of Business </td></tr>
<?php
  //get bus nature
  $lope=0;
  $gr=0;
     $getpm = mysql_query("select * from ebpls_business_enterprise where owner_id='$owner_id' and business_id='$business_id'");
$pm = mysql_fetch_assoc($getpm);
$pmode = $pm['business_payment_mode'];
	
			       
				       	if (strtolower($pmode)=='quarterly') {
					       		$buwannatinngayon = date('m');
								if (strtolower($pmode) == "quarterly") {
									if ($buwannatinngayon >= 4 and $buwannatinngayon <= 6) {
										$lbl = 'First Quarter of';
									} elseif ($buwannatinngayon >= 7 and $buwannatinngayon <= 9) {
										$lbl = 'Second Quarter of';
									} elseif ($buwannatinngayon >= 10 and $buwannatinngayon <= 12) {
										$lbl = 'Third Quarter of';
									}
								}

				       	} elseif (strtolower($pmode)=='semi-annual') {
					       			$lbl = 'First Period of' ;
				       	}
				
?>
<tr><td></td><td >Gross Sales for <?php echo $lbl." ".$yearnow; ?></td></tr>		
<?php
  
  
                $getnat =SelectDataWhere($dbtype,$dbLink,"tempbusnature",
                                  " where owner_id=$owner_id and business_id=$business_id
                                  and active = 1");
                                  
	                while ($getit = FetchArray($dbtype,$getnat)){
					$getit[bus_nature]=stripslashes($getit[bus_nature]);
					
					?>
					<tr>
					<td><?php echo $getit[bus_nature]; ?>
					<input type=hidden name=tempbus[<?php echo $gr; ?>] value=<?php echo $getit[tempid]; ?>>
					<input type=hidden name=dnature[<?php echo $gr; ?>] value="<?php echo $getit[bus_code]; ?>"></td>
					<td><input type=text name=gross[<?php echo $gr; ?>]> </td>
					</tr>
					<?php
					$gr++;
					}
				
				
?>
<tr><td></td></tr>
<input type=hidden name=gr value="<?php echo $gr; ?>">
<input type=hidden name=comm_ass>
<tr><td colspan=2 align="center"><Input type=button name=asse value="Save and Assess" onclick='_FRM.comm_ass.value=1; _FRM.submit();'>&nbsp; <input type=button value="Cancel" onclick='history.go(-1);'></td></tr>
</table>
