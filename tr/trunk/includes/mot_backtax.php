<?php
$yeartoday = date('Y');
$datenow = date('Y-m-d');
$getpended = @mysql_query("select * from ebpls_motorized_penalty where permit_type = '$permit_type'");
$getpended = @mysql_fetch_assoc($getpended);
$getpen1 = @mysql_query("select * from ebpls_motorized_penalty where permit_type = '$permit_type' and status = '1'");
$getpen = @mysql_fetch_assoc($getpen1);
if ($getpended['renewaltype'] == '1') {
			if ($getpended[backtax] == '1') {
				//BackTax Computation.... Goodluck sakin!
				if ($permit_type == 'Motorized') {
					$totvec=SelectDataWhere($dbtype,$dbLink,"ebpls_motorized_vehicles",
					"where motorized_operator_id = '$owner_id' 
					and permit_type='$permit_type' and retire=0 and transaction='ReNew'");
					$totvec1 = NumRows($dbtype,$totvec);
					$getlastnpaydate = @mysql_query("select * from temppayment where permit_type = '$permit_type' and owner_id = '$owner_id' order by payid desc limit 1");
					$getlastnpaydate = @mysql_fetch_assoc($getlastnpaydate);
					$getlastnpaydate1 = substr($getlastnpaydate[pay_date],0,4);
					$lastpaydateyear = $getlastnpaydate1 + 1;
					$nyeardiff = $yeartoday - $lastpaydateyear;
					$ngyeardiff = $nyeardiff;
					$xloop = 1;
					$backtaxcompute = 0;
					$meronlate = 0;
					while ($xloop <= $nyeardiff) {
						$xloop++;
						//echo $xloop."VooDoo<br>";
						//Motorized Fees
						$getvechswe = @mysql_query("select * from ebpls_motorized_vehicles where motorized_operator_id = '$owner_id' and retire = '0' and transaction = '$stat'");
						$numveh = 0;
						while ($getvechessq = @mysql_fetch_assoc($getvechswe)) {
							$checkvalues = @mysql_query("delete from ebpls_fees_paid where permit_type='$permit_type' and owner_id = '$owner_id' and permit_status = '$stat' and input_date like '$lastpaydateyear%' and multi_by = '$getvechessq[motorized_motor_id]'");
						//load all defaults
							$getfees1 = @mysql_query("select * from ebpls_mtop_fees where permit_type='$stat'");
							while ($getfees = @mysql_fetch_assoc($getfees1)) {
								$updatetempfees = @mysql_query("update ebpls_mtop_temp_fees set active = '0' where year < '$lastpaydateyear' and owner_id  = '$owner_id' and mid = '$getvechessq[motorized_motor_id]'");
								$deletetempfees = @mysql_query("delete from ebpls_mtop_temp_fees where year = '$lastpaydateyear' and owner_id  = '$owner_id' and fee_id = '$getfees[fee_id]' and mid = '$getvechessq[motorized_motor_id]'");
								$insertintotempfees = @mysql_query("insert into ebpls_mtop_temp_fees values ('', '$getfees[fee_id]', '$owner_id', '$getvechessq[motorized_motor_id]', '', '$usern', '$lastpaydateyear', '1','$lastpaydateyear')");
								$selecttemp = @mysql_query("select * from ebpls_mtop_temp_fees where owner_id = '$owner_id' and fee_id = '$getfees[fee_id]' and year <= '$lastpaydateyear' and mid = '$getvechessq[motorized_motor_id]'");
								$selecttemp1 = @mysql_num_rows($selecttemp);
								$getifbill = $selecttemp1 / $getfees['nyears'];
								$getifbill1 = strpos($getifbill, ".");
								$nNow = date('Y-m-d G:i:s');
								$nklyear = date('Y');
								$tempDate = str_replace($nklyear, $lastpaydateyear, $nNow);
								if ($getfees['nyears'] == 1) {
									$insertintotemp = @mysql_query("insert into ebpls_fees_paid values ('', '$owner_id', '$getfees[fee_desc]', '$getfees[fee_amount]', '$getvechessq[motorized_motor_id]', '$permit_type', '$stat','1', '$usern', '$tempDate')");
								} else {
									if ($getifbill1 == 0 and $selecttemp1 != 0) {
										$insertintotemp = @mysql_query("insert into ebpls_fees_paid values ('', '$owner_id', '$getfees[fee_desc]', '$getfees[fee_amount]', '$getvechessq[motorized_motor_id]', '$permit_type', '$stat','1', '$usern', '$tempDate')");
									}
								}
							}
							$numveh++;
						}
						//get from fees paid
						$getfees1 = @mysql_query("select * from ebpls_fees_paid where permit_type= '$permit_type' and permit_status = '$stat' and owner_id = '$owner_id' and input_date like '$lastpaydateyear%'");
						$totalmfee = 0;
						while ($getfees = @mysql_fetch_assoc($getfees1)) {
							$tamt =$getfees[fee_amount];
							$totalmfee = $totalmfee + $tamt;
						}
						$backtaxcompute = $backtaxcompute + $totalmfee; 
						
						$backtotalfee = $totalmfee; 
						//Get Surcharge and Interest
						if ($getpended['indicator'] == '1') {
							$backpen = $getpended['rateofpenalty'];
						} elseif ($getpended['indicator'] == '2') {
							eval("\$backpen=$backtotalfee*$getpended[rateofpenalty];");
						}
						$backtaxcompute = $backtaxcompute + $backpen; 
						//echo "$backtotalfee<br>";
						$d = date('m',strtotime($lastpaydateyear."-".$getpended['renewaldate1'])); //setting date
						$s = date('m'); //curretn date
						$s = $s + ($ngyeardiff * 12);
						//$d = ($s - $d) + 1;
						$d = ($s - 1) + 1;
						
						if ($d > 36) {
							$d = 36;
						}
						$x = 1;
						$counter = 1;
						$intrate = "1$getpended[rateofinterest]";
						if ($getpen['intype'] == '1') {
							$interestrate = $getpended[rateofinterest] * $d;
						} elseif ($getpended['intype'] == '2') {
							while ($x <= $d) {
								$counter = $counter * $intrate;
								$x++;
							}
							$interestrate = $counter - 1;
						}
						if ($getpended['feeonly'] == '1') {
							$backint = $interestrate * $backtotalfee;
						} elseif ($getpended['feeonly'] == '2') {
							$backint = $interestrate * ($backtotalfee +$backpen);
						}
						$backtaxcompute = $backtaxcompute + $backint; 
						$rendate = "$yeartoday-$getpended[renewaldate1]";
						
						if ($datenow < $rendate) {
							if ($getpen[f_status] == '1') {
								if ($meronlate != '1') {
									$backlate = $getpen['late_filing_fee'] * $numveh;
									$meronlate = 1;
								} else {
									$backlate = 0;
								}
							}
						}
						$backtaxcompute = $backtaxcompute + $backlate;
						 
						/////
						$lastpaydateyear = $lastpaydateyear + 1;
						$ngyeardiff = $ngyeardiff - 1;
					}
					$nnbacktax = $nnbacktax + $backtaxcompute;
				}
				$backtaxcompute = $nnbacktax;
			}
		}	elseif ($getpended['renewaltype'] == '2') {
			if ($getpended[backtax] == '1') {
				//BackTax Computation for Scenario 2.... Goodluck sakin!!!!!!
				if ($permit_type == 'Motorized') {
					$getvech = @mysql_query("select * from ebpls_motorized_vehicles where motorized_operator_id = '$owner_id' and permit_type = 'Motorized' and retire='4' and paid = '0'");
					$totvech = mysql_num_rows($getvech);
					$totalotherpen = 0;
					$totalotherint = 0;
					$totalotherlate = 0;
					while ($getvec = @mysql_fetch_assoc($getvech)) {
						$getlen = strlen($getvec[motorized_plate_no]);
						$getlastnum = substr($getvec[motorized_plate_no], $getlen - 1, $getlen);
						$nsrenewaldate = "renewaldate$getlastnum";
						$nmrenewaldate = $getpended[$nsrenewaldate];
						$totvec=SelectDataWhere($dbtype,$dbLink,"ebpls_motorized_vehicles",
						"where motorized_operator_id = '$owner_id' 
						and permit_type='$permit_type' and retire=0");
						$totvec1 = NumRows($dbtype,$totvec);
						$anongyearngayon = date('Y');
						$getlastnpaydate = @mysql_query("select * from renew_vehicle where owner_id = '$owner_id' and motorized_motor_id = $getvec[motorized_motor_id] and date_updated not like '$anongyearngayon%' and paid = '1' order by date_updated desc limit 1");
						$checkifnotempty = @mysql_num_rows($getlastnpaydate);
						if ($checkifnotempty == 0) {
							$getlastnpaydate = @mysql_query("select pay_date as date_updated from temppayment where owner_id = '$owner_id' and permit_type = 'Motorized' and pay_date not like '$anongyearngayon%' order by pay_date desc limit 1");
						}
						$getlastnpaydate = @mysql_fetch_assoc($getlastnpaydate);
						$getlastnpaydate1 = substr($getlastnpaydate[date_updated],0,4);
						$lastpaydateyear = $getlastnpaydate1 + 1;
						$lastpaydateyearys = $getlastnpaydate1 + 1;
						$nyeardiff = $yeartoday - $lastpaydateyear;
						$ngyeardiff = $nyeardiff;
						$xloop = 1;
						$backtaxcompute = 0;
						$meronlate = 0;
						while ($xloop <= $nyeardiff) {
							
							$xloop++;
							//echo $xloop."VooDoo<br>";
							//Motorized Fees
							$checkvalues = @mysql_query("delete from ebpls_fees_paid where permit_type='$permit_type' and owner_id = '$owner_id' and permit_status = '$stat' and input_date like '$lastpaydateyearys%' and active = '1' and multi_by = '$getvec[motorized_motor_id]'");
							//load all defaults
							$getfees1 = @mysql_query("select * from ebpls_mtop_fees where permit_type='$stat'");
							while ($getfees = @mysql_fetch_assoc($getfees1)) {
								$updatetempfees = @mysql_query("update ebpls_mtop_temp_fees set active = '0' where year < '$lastpaydateyear' and owner_id  = '$owner_id'");
								
								//$selectveh = @mysql_query("select * from ebpls_motorized_vehicles where motorized_operator_id = '$owner_id' and retire='4'");
								//$numberofveh = 0;
								//while ($selectveh1 = @mysql_fetch_assoc($selectveh)) {
									$nNow = date('Y-m-d G:i:s');
									$nklyear = date('Y');
									$tempDate = str_replace($nklyear, $lastpaydateyear, $nNow);
									$deletetempfees = @mysql_query("delete from ebpls_mtop_temp_fees where year >= '$lastpaydateyearys' and owner_id  = '$owner_id' and fee_id = '$getfees[fee_id]' and mid = '$getvec[motorized_motor_id]'");
									$insertintotempfees = @mysql_query("insert into ebpls_mtop_temp_fees values ('', '$getfees[fee_id]', '$owner_id', '$getvec[motorized_motor_id]', '', '$usern', '$tempDate', '1','$lastpaydateyearys')");
									$numberofveh++;
									$selecttemp = @mysql_query("select * from ebpls_mtop_temp_fees where owner_id = '$owner_id' and fee_id = '$getfees[fee_id]' and mid = '$getvec[motorized_motor_id]'");
									$selecttemp1 = @mysql_num_rows($selecttemp);
									$getifbill = $selecttemp1 / $getfees['nyears'];
									$getifbill1 = strpos($getifbill, ".");
									if ($getfees['nyears'] == 1) {
										$insertintotemp = @mysql_query("insert into ebpls_fees_paid values ('', '$owner_id', '$getfees[fee_desc]', '$getfees[fee_amount]', '$getvec[motorized_motor_id]', '$permit_type', '$stat','1', '$usern', '$tempDate')");
										/*if ($getvec[motorized_motor_id]==3) {
											echo $getfees['nyears']."<br>";
										echo "insert into ebpls_fees_paid values ('', '$owner_id', '$getfees[fee_desc]', '$getfees[fee_amount]', '$getvec[motorized_motor_id]', '$permit_type', '$stat','1', '$usern', '$tempDate')";
										}*/
									} else {
										if ($getifbill1 == 0) {
											$insertintotemp = @mysql_query("insert into ebpls_fees_paid values ('', '$owner_id', '$getfees[fee_desc]', '$getfees[fee_amount]', '$getvec[motorized_motor_id]', '$permit_type', '$stat','1', '$usern', '$tempDate')");
											echo "insert into ebpls_fees_paid values ('', '$owner_id', '$getfees[fee_desc]', '$getfees[fee_amount]', '$getvec[motorized_motor_id]', '$permit_type', '$stat','1', '$usern', '$tempDate')";
										}
									}
								//}
							}//End Loading of Defaults
							//get from fees paid
							$selectveh = @mysql_query("select * from ebpls_motorized_vehicles where motorized_operator_id = '$owner_id' and retire='4'");
							while ($selectveh1 = @mysql_fetch_assoc($selectveh)) {
								$getfees1 = @mysql_query("select * from ebpls_fees_paid where permit_type= '$permit_type' and permit_status = '$stat' and owner_id = '$owner_id' and input_date like '$lastpaydateyear%' and active = '1' and multi_by = '$selectveh1[motorized_motor_id]'");
								$totalmfee = 0;
								while ($getfees = @mysql_fetch_assoc($getfees1)) {
									$tamt =$getfees[fee_amount];
									$totalmfee = $totalmfee + $tamt;
								}
								$backtaxcompute = $backtaxcompute + ($totalmfee); 
								$backtotalfee = $totalmfee;
							
								//Get Surcharge and Interest
								if ($getpended['indicator'] == '1') {
									$backpen = $getpended['rateofpenalty'];
								} elseif ($getpended['indicator'] == '2') {
									@eval("\$backpen=$backtotalfee*$getpended[rateofpenalty];");
								}
							
								$backtaxcompute = $backtaxcompute + $backpen;
								//Get Renewal Date
							
								$d = date('m',strtotime($lastpaydateyear."-".$nmrenewaldate)); //setting date
								$s = date('m'); //curretn date
								$s = $s + ($ngyeardiff * 12);
							
								$d = ($s - $d) + 1;
								if ($d > 36) {
									$d = 36;
								}
								$x = 1;
								$counter = 1;
								$intrate = "1$getpended[rateofinterest]";
								if ($getpen['intype'] == '1') {
									$interestrate = $getpended[rateofinterest] * $d;
								} elseif ($getpended['intype'] == '2') {
									while ($x <= $d) {
										$counter = $counter * $intrate;
										$x++;
									}
									$interestrate = $counter - 1;
								}
								if ($getpended['feeonly'] == '1') {
									$backint = $interestrate * $backtotalfee;
								} elseif ($getpended['feeonly'] == '2') {
									$backint = $interestrate * ($backtotalfee +$backpen);
								}
								$backtaxcompute = $backtaxcompute + $backint; 
								$rendate = "$yeartoday-$getpended[renewaldate1]";
								if ($datenow < $rendate) {
									if ($getpen[f_status] == '1') {
										if ($meronlate != '1') {
											$backlate = $getpen['late_filing_fee'] * $totvech;
											$meronlate = 1;
										} else {
											$backlate = 0;
										}
									}
								} else {
									$backlate = 0;
								}
								$backtaxcompute = $backtaxcompute + $backlate;
							
								/////
								$lastpaydateyear = $lastpaydateyear + 1;
								$lastpaydateyearys = $lastpaydateyearys + 1;
								$ngyeardiff = $ngyeardiff - 1;
							}
						}
						$nnbacktax = $nnbacktax + $backtaxcompute;
					}
					$backtaxcompute = $nnbacktax;
				}
			}
		}
		
?>