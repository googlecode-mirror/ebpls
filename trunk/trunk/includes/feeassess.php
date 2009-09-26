<?php

$resultf = SelectDataWhere($dbtype,$dbLink,"ebpls_buss_tfo",
			"where tfoindicator='1' and tfostatus='A' and taxfeetype<>'1' ");

$cntfee = NumRows($dbtype,$resultf);
$feetype = 1;
?>

<table border=1 align =center width=100% cellspacing=0 class=sub>
<tr><th width=50%>FEES</th>
<th width=25%>AMOUNT</th>
<th width=25%>AMOUNT DUE</th></tr>
<?

	while ($getf=FetchRow($dbtype,$resultf))
	{
		$getex = SelectMultiTable($dbtype,$dbLink,"ebpls_business_enterprise a, 
			fee_exempt b, ebpls_buss_tfo c","a.*",
                        "where a.business_id=$business_id and
                        a.business_category_code=b.business_category_code and
                        c.tfoid=$getf[0] and b.tfoid=$getf[0] and
                        b.active=1");
		$getfeex = NumRows($dbtype,$getex);
		if ($getfeex>0) {
			$exemptedfee = $exemptedfee + $getf[6]; 
		        $usemin = 'Fee Exempted ';
			$totfee =$totfee + $getf[6];
		        $getf[6]=0;
		}
	$havegar = strpos(strtolower($getf[1]),'garbage');


if ($g_zone==0) {
	if ($havegar>-1) {
		$exemptedfee = $exemptedfee + $getf[6];
		$usemin = 'Not in Garbage Zone ';
		$rtag='';
	$totfee = $totfee + $getf[6];
	
        $getf[6]=0;
        
        $totind=0;
	}
}	

	       $havemat=0;
$getyears = mysql_query("select  a.*, b.* from tempassess a, ebpls_buss_tfo b where
	       					a.owner_id='$owner_id' and a.business_id='$business_id' and a.active=0
	       					 and a.tfoid=b.tfoid and a.tfoid='$getf[0]' order by date_create asc");
	       					
$yearsi = mysql_query("select  a.*  from tempassess a, ebpls_buss_tfo b where
	       					a.owner_id='$owner_id' and a.business_id='$business_id'
	       					 and a.tfoid=b.tfoid and a.tfoid='$getf[0]'");							
		$havemat = mysql_num_rows($yearsi);
				
          $getyr  = mysql_fetch_assoc($getyears);
		$bill_date = date('Y') - date('Y',@strtotime($getyr[date_create])); //get last bill year

		if ($havemat>0 and $PROCESS<>"COMPUTE" and $usemin=='') { //have prev record
			//check if will bill
		@$howmanydec = $havemat/$getyr[counter];
			$isdeci = strpos($howmanydec,".");
		
			if ($isdeci>0 and $haveaddpay==$watqtr) { // will not bill


$getf[6] = 0;
$dan=1;

$result = InsertQuery($dbtype,$dbLink,"tempassess",
			            		"(assid, owner_id, business_id, natureid, 
						 taxfeeid, multi, amt, formula, compval,
				                 tfoid,active, transaction,date_create)",
			            		"$varx,$owner_id, $business_id,'',
					         '',1,$getf[6],'$getf[6]',$getf[6], $getf[0],1,
					         '$stat',now()");
		



			} else { //will bill
		
			    if ($PROCESS<>'COMPUTE' and $itemID_=='4212' and $haveaddpay<>$watqtr) {
					
			         $result = InsertQuery($dbtype,$dbLink,"tempassess",
			            		"(assid, owner_id, business_id, natureid, 
						 taxfeeid, multi, amt, formula, compval,
				                 tfoid,active, transaction,date_create)",
			            		"$varx,$owner_id, $business_id,'',
					         '',1,$getf[6],'$getf[6]',$getf[6], $getf[0],1,
					         '$stat',now()");
			        $varx++;
				}
			}
		} else { //new record

			if ($haveaddpay==$watqtr) {
				$haveaddpay='';
			}
				//check if will bill
		@$howmanydec = $havemat/$getyr[counter];
			$isdeci = strpos($howmanydec,".");
			if ($isdeci>0 and $haveaddpay==$watqtr) { // will not bill

$getf[6] = 0;
}
			    if ($PROCESS<>'COMPUTE' and $haveaddpay<>$watqtr  ) {
			         $result = InsertQuery($dbtype,$dbLink,"tempassess",
			            		"(assid, owner_id, business_id, natureid, 
						 taxfeeid, multi, amt, formula, compval,
				                 tfoid,active, transaction,date_create)",
			            		"$varx,$owner_id, $business_id,'',
					         '',1,$getf[6],'$getf[6]',$getf[6], $getf[0],1,
					         '$stat',now()");
			        $varx++;
				} if ($PROCESS=='COMPUTE' and $haveaddpay=='' and $watqtr=='' ) {
                                 $result = InsertQuery($dbtype,$dbLink,"tempassess",
                                                "(assid, owner_id, business_id, natureid,
                                                 taxfeeid, multi, amt, formula, compval,
                                                 tfoid,active, transaction,date_create)",
                                                "$varx,$owner_id, $business_id,'',
                                                 '',1,$getf[6],'$getf[6]',$getf[6], $getf[0],1,
                                                 '$stat',now()");
                                $varx++;
                                } else {
					$geth = mysql_query("select * from tempassess where owner_id='$owner_id' and business_id='$business_id'
										and active=1 and tfoid='$getf[0]' order by date_create desc");
					$getputa = mysql_fetch_assoc($geth);
					$getf[6]=$getputa[compval];
				}
		}


		if ($itemID_<>4212 and $usemin=='') {
			$geth = mysql_query("select * from tempassess where owner_id='$owner_id' and business_id='$business_id'
										and active=1 and tfoid='$getf[0]' order by date_create desc");
			$getputa = mysql_fetch_assoc($geth);
			$getf[6]=$getputa[compval];
			
			}
			
?>

		<tr><td width=50%><?php echo $getf[1]; ?></td>
		 	   
			   <td align=right width=25%>
				<?php echo $usemin; 
				echo number_format($getf[6],2); ?></td>
			   <td align=right width=25%>
				<?php echo $usemin;
				echo number_format($getf[6],2); ?></td>
			   </tr>
<?php
		
				
	$regfee = $regfee + $getf[6];
	$totfee = $totfee+$getf[6];
	
	$usemin='';
	}



if ($tftnum==1 || $itemID_==5212 || $itemID_==2212) {
$totfee = round($totfee+$add2fee,2);//+$totmpf,2);
} elseif ($tftnum==4 || $itemID_==5212 || $itemID_==2212) {
$totfee = round($totfee-$tsf,2);//+$totmpf,2);
}

?>

<input type=hidden name="dan" value="<?php echo $dan; ?>">
<tr><td colspan=2 align=right>Total GENERAL CHARGES</td><td align=right bgcolor="lightblue">
Php &nbsp;<?php $fee=$regfee; $feecompute=$regfee; echo number_format($regfee,2); ?></td></tr>
</table>


