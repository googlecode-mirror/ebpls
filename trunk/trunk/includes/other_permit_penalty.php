<?php
if ($stat != "New") {
	if ($permit_type == "Motorized") {
		$yeartoday = date('Y');
		$addend = "$yeartoday-";
		$datetoday = date('Y-m-d');
		$getrec = @mysql_query("select * from $permittable where owner_id = '$owner_id' and transaction = '$stat' and active = '1'");
		$getrec = @mysql_fetch_assoc($getrec);
		$getpended = @mysql_query("select * from ebpls_motorized_penalty where permit_type = '$permit_type'");
		$getpended = @mysql_fetch_assoc($getpended);
		$renewalcycle = 0;
		if ($getpended['renewaltype'] == '1') {
			$getfees12 = @mysql_query("select sum(fee_amount) from ebpls_fees_paid where permit_type = 'Motorized' and permit_status = 'ReNew' and input_date like '$yeartoday%' and owner_id = '$owner_id'");
			$getfees122 = @mysql_fetch_row($getfees12);
			$getvecheses = @mysql_query("select count(motorized_motor_id) from ebpls_motorized_vehicles where permit_type = 'Motorized' and transaction = 'ReNew'  and motorized_operator_id = '$owner_id' and retire='0' and paid = '0'");
			$getvecheses1 = @mysql_fetch_row($getvecheses);
			$renewaldate = "$addend$getpended[renewaldate1]";
			$renewaldate = strtotime($renewaldate);
			$renewaldate = date('Y-m-d', $renewaldate);
			$renewalcycle = 1;
			if ($getvecheses1[0] > 0 || $stat == "ReNew") {
				// Surcharge/Interest for 1 Time Renewal
				$getpen = @mysql_query("select * from ebpls_motorized_penalty where permit_type = '$permit_type' and status = '1'");
				$getpen = @mysql_fetch_assoc($getpen);
				if ($datetoday > $renewaldate) {
					$otherpen = 0;
					$getpaydate = @mysql_query("select * from temppayment where permit_type = '$permit_type' and owner_id = '$owner_id' order by payid desc limit 1");
					$getpaydate = @mysql_fetch_assoc($getpaydate);
					$lastpaydate = substr($getpaydate[pay_date],0,4);
					$lpdate = $lastpaydate;
					$yeardiff = 0;
				
					if ($lastpaydate < $yeartoday) {
						$lastpaydate = $lastpaydate + 1;
						$yeardiff = $yeartoday - $lastpaydate;
						$lastpaydate = "$lastpaydate-";
					} else {
						$lastpaydate = "$lastpaydate-";
					}
					
					if ($getpen['indicator'] == '1') {
						$otherpen = $getpen['rateofpenalty'];
					} elseif ($getpen['indicator'] == '2') {
						if ($itemID_ == "1221") {
							@eval("\$otherpen=(($getfees122[0])*$getpen[rateofpenalty]);");
						} else {
							 eval("\$otherpen=($getfees122[0])*$getpen[rateofpenalty];");
						}
					}
					
					$d = date('m',strtotime($lastpaydate.$getpen['renewaldate1'])); //setting date
					$s = date('m'); //curretn date
					//$s = $s + ($yeardiff * 12);
					$d = ($s - $d) + 1;
					$x = 1;
					$counter = 1;
					$intrate = "1$getpen[rateofinterest]";
					if ($getpen['intype'] == '1') {
						$interestrate = $getpen[rateofinterest] * $d;
					} elseif ($getpen['intype'] == '2') {
						while ($x <= $d) {
							$counter = $counter * $intrate;
							$x++;
						}
						$interestrate = $counter - 1;
					}
					if ($getpen['feeonly'] == '1') {
						if ($item_ID_ == "1121") {
							$otherint = $interestrate * ($getfees122[0]);
						} else {
							$otherint = $interestrate * ($getfees122[0]);
						}
					} elseif ($getpen['feeonly'] == '2') {
						if ($itemID_ == "1221") {
							$otherint = $interestrate * ($getfees122[0] +$otherpen);
						} else {
							$otherint = $interestrate * ($getfees122[0] +$otherpen);
						}
					}
					
					$maylatefile = $getpen['f_status'];
					if ($getpen[f_status] == '1') {
						$otherlate = $getpen['late_filing_fee'] * $getvecheses1[0];
					} else {
						$otherlate = 0;
					}
					
					 if ($permit_type == "Motorized") {
									$getstat = mysql_query("select * from temppayment where owner_id = '$owner_id' and pay_date like '$yeartoday' and permit_status = 'Transfer/Dropping'");
									$getstat1 = @mysql_num_rows($getstat);
									if ($stat == 'Transfer/Dropping' and $lpdate < $yeartoday) {
									} elseif ($stat != "Transfer/Dropping") {
									} elseif ($getstat > 0) {
									} else {
											$otherlate = 0;
											$otherpen = 0;
											$otherint = 0;
									}
									if ($getvecheses1[0] == 0) {
										$otherlate = 0;
											$otherpen = 0;
											$otherint = 0;
									}
									$portyear = date('Y');
									$getpensamount = @mysql_query("select * from ebpls_mot_penalty where motorized_permit_id = '$owner_id' and date_input like '$portyear%'");
								$gettotsurcharge = 0;
								$gettotinterest = 0;
								$gettotlate = 0;
								$gettotbak = 0;
								while ($getpensamount1 = @mysql_fetch_assoc($getpensamount)) {
									$gettotsurcharge = $gettotsurcharge + $getpensamount1[surcharge];
									$gettotinterest = $gettotinterest + $getpensamount1[interest];
									$gettotlate = $gettotlate + $getpensamount1[late];
									$gettotbak = $gettotbak + $getpensamount1[backtax];
								}
					if ($otherlate > 0 || $gettotlate > 0 || $otherpen > 0 || $gettotsurcharge > 0 || $otherint > 0 || $gettotinterest > 0) {
	
		?>
					<tr><td>&nbsp;</td><td>&nbsp;</td><td>Total :</td><td align=right>&nbsp;<?php echo number_format($totalfee,2); ?>&nbsp;</td></tr>
					<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
					<tr><td>&nbsp;<b>LATE FILING FEE</b></td><td>&nbsp;</td><td>&nbsp;</td><td align=right>
							&nbsp;<?php echo number_format($otherlate + $gettotlate,2); ?></td></tr>
					<tr><td>&nbsp;<b>SURCHARGE</b></td><td>&nbsp;</td><td>&nbsp;</td><td align=right>
							&nbsp;<?php echo number_format($otherpen + $gettotsurcharge,2); ?></td></tr>
					<tr><td>&nbsp;<b>INTEREST</b></td><td>&nbsp;</td><td>&nbsp;</td><td align=right>
							&nbsp;<?php echo number_format($otherint + $gettotinterest,2); ?></td></tr>
							<input type="hidden" name="hLate" value="<? echo $otherlate;?>">
							<input type="hidden" name="hSurcharge" value="<? echo $otherpen;?>">
							<input type="hidden" name="hInterest" value="<? echo $otherint;?>">
							<input type="hidden" name="permit_id" value="<? echo $owner_id;?>">
		<?php
						}
					}
				}
			}
			if ($getpen[backtax] == '1' and $permit_type == 'Motorized') {
			?>
			<tr>
			<?php echo $addspace;?><td>&nbsp;<b>BACKTAX</b></td><td>&nbsp;</td><td>&nbsp;</td><td align=right><?php echo number_format($backtaxcompute + $gettotbak,2); ?></td>
			</tr>
			<?php
			} else {
				$backtaxcompute = 0;
			}
				
			// End of Surcharge/Interest for 1 Time Renewal
		} elseif ($getpended['renewaltype'] == '2') { // Start of Surcharge/Interest for by Plate Number
			
			$getvech = @mysql_query("select * from ebpls_motorized_vehicles where motorized_operator_id = '$owner_id' and retire = '4' and permit_type = 'Motorized' and paid = '0'");
			$totvech = mysql_num_rows($getvech);
			$otherpen = 0;
			$totalotherpen = 0;
			$totalotherint = 0;
			$totalotherlate = 0;
			while ($getvec = @mysql_fetch_assoc($getvech)) {
				//echo "$datetoday > $renewaldate VooDoo";
				$getlen = strlen($getvec[motorized_plate_no]);
				$getlastnum = substr($getvec[motorized_plate_no], $getlen - 1, $getlen);
				$nsrenewaldate = "renewaldate$getlastnum";
				$nmrenewaldate = $getpended[$nsrenewaldate];
				$renewaldate = "$addend$nmrenewaldate";
				$renewaldate = strtotime($renewaldate);
				$renewaldate = date('Y-m-d', $renewaldate);
				$renewalcycle = 1;
				//Surcharge/Interest
				$getfees12 = @mysql_query("select sum(fee_amount) from ebpls_fees_paid where permit_type = 'Motorized' and permit_status = 'ReNew' and input_date like '$yeartoday%' and owner_id = '$owner_id' and multi_by = '$getvec[motorized_motor_id]'");
				$getfees122 = @mysql_fetch_row($getfees12);
				$getpen = @mysql_query("select * from ebpls_motorized_penalty where permit_type = '$permit_type' and status = '1'");
				$getpen = @mysql_fetch_assoc($getpen);
				if ($datetoday > $renewaldate) {
					$getpaydate = @mysql_query("select * from temppayment where permit_type = '$permit_type' and owner_id = '$owner_id' order by payid desc limit 1");
					$getpaydate = @mysql_fetch_assoc($getpaydate);
					$lastpaydate = substr($getpaydate[pay_date],0,4);
					$lpdate = $lastpaydate;
					$yeardiff = 0;
			
					if ($lastpaydate < $yeartoday) {
						$lastpaydate = $lastpaydate + 1;
						$yeardiff = $yeartoday - $lastpaydate;
						$lastpaydate = "$lastpaydate-";
					} else {
						$lastpaydate = "$lastpaydate-";
					}
					
					if ($getpen['indicator'] == '1') {
						$otherpen = $getpen['rateofpenalty'];
					} elseif ($getpen['indicator'] == '2') {
						eval("\$otherpen=($getfees122[0])*$getpen[rateofpenalty];");
					}
					$totalotherpen = $totalotherpen + $otherpen;
					$d = date('m',strtotime($lastpaydate.$getpen[$nsrenewaldate])); //setting date
					$s = date('m'); //curretn date
					//$s = $s + ($yeardiff * 12);
					$d = ($s - $d) + 1;
					$x = 1;
					$counter = 1;
					$intrate = "1$getpen[rateofinterest]";
					if ($getpen['intype'] == '1') {
						$interestrate = $getpen[rateofinterest] * $d;
					} elseif ($getpen['intype'] == '2') {
						while ($x <= $d) {
							$counter = $counter * $intrate;
							$x++;
						}
						$interestrate = $counter - 1;
					}
					if ($getpen['feeonly'] == '1') {
						$otherint = $interestrate * ($getfees122[0]);
					} elseif ($getpen['feeonly'] == '2') {
						$otherint = $interestrate * (($getfees122[0]) +$otherpen);
					}
					//echo "$otherint = $interestrate * (($getfees122[0]) +$otherpen)<br>";
					$totalotherint = $totalotherint + $otherint;
					$maylatefile = $getpen['f_status'];
					if ($getpen[f_status] == '1') {
						$otherlate = $getpen['late_filing_fee'];
					} else {
						$otherlate = 0;
					}
					$totalotherlate = $totalotherlate + $otherlate;
				}
			}
			$otherpen = $totalotherpen;
			$otherint = $totalotherint;
			$otherlate = $totalotherlate;
			
			 if ($permit_type == "Motorized") {
                                $getstat = mysql_query("select * from temppayment where owner_id = '$owner_id' and pay_date like '$yeartoday' and permit_status = 'Transfer/Dropping'");
									$getstat1 = mysql_num_rows($getstat);
                                if ($stat == 'Transfer/Dropping' and $lpdate < $yeartoday) {
                                } elseif ($stat != "Transfer/Dropping") {
                                } elseif ($getstat > 0) {
                                } else {
                                        $otherlate = 0;
                                        $otherpen = 0;
                                        $otherint = 0;
                                }
								$portyear = date('Y');
								$getpensamount = @mysql_query("select * from ebpls_mot_penalty where motorized_permit_id = '$owner_id' and date_input like '$portyear%'");
								$gettotsurcharge = 0;
								$gettotinterest = 0;
								$gettotlate = 0;
								$gettotbak = 0;
								while ($getpensamount1 = @mysql_fetch_assoc($getpensamount)) {
									$gettotsurcharge = $gettotsurcharge + $getpensamount1[surcharge];
									$gettotinterest = $gettotinterest + $getpensamount1[interest];
									$gettotlate = $gettotlate + $getpensamount1[late];
									$gettotbak = $gettotbak + $getpensamount1[backtax];
								}
								if ($otherlate > 0 || $gettotlate > 0 || $otherpen > 0 || $gettotsurcharge > 0 || $otherint > 0 || $gettotinterest > 0) {

		?>
			<tr><td>&nbsp;</td><td>&nbsp;</td><td>Total :</td><td align=right>&nbsp;<?php echo number_format($totalfee,2); ?>&nbsp;</td></tr>
			<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
			<tr><td>&nbsp;<b>LATE FILING FEE</b></td><td>&nbsp;</td><td>&nbsp;</td><td align=right>
					&nbsp;<?php echo number_format($otherlate + $gettotlate,2); ?></td></tr>
			<tr><td>&nbsp;<b>SURCHARGE</b></td><td>&nbsp;</td><td>&nbsp;</td><td align=right>
					&nbsp;<?php echo number_format($otherpen + $gettotsurcharge,2); ?></td></tr>
			<tr><td>&nbsp;<b>INTEREST</b></td><td>&nbsp;</td><td>&nbsp;</td><td align=right>
					&nbsp;<?php echo number_format($otherint + $gettotinterest,2); ?></td></tr>
					<input type="hidden" name="hLate" value="<? echo $otherlate;?>">
							<input type="hidden" name="hSurcharge" value="<? echo $otherpen;?>">
							<input type="hidden" name="hInterest" value="<? echo $otherint;?>">
							<input type="hidden" name="permit_id" value="<? echo $owner_id;?>">
		<?php
				}
			}
			
			if ($getpen[backtax] == '1' and $permit_type == 'Motorized' and ($backtaxcompute > 0 or $gettotbak > 0)) {
			?>
			<tr>
			<?php echo $addspace;?><td>&nbsp;<b>BACKTAX</b></td><td>&nbsp;</td><td>&nbsp;</td><td align=right><?php echo number_format($backtaxcompute + $gettotbak,2); ?></td>
			</tr>
			<?php
			} else {
				$backtaxcompute = 0;
			}
		} //End of Sucrharge/Interest for by Plate Number
	?>
	<input type="hidden" name="surintamount" value="<? echo $otherpen + $otherint + $otherlate;?>">
	<input type="hidden" name="bt" value="<? echo $backtaxcompute;?>">

<?php
	}
	if ($permit_type == "Fishery" || $permit_type == "Occupational") {
		$yeartoday = date('Y');
		$addend = "$yeartoday-";
		$datetoday = date('Y-m-d');
		$getrec = @mysql_query("select * from $permittable where owner_id = '$owner_id' and transaction = '$stat' and active = '1'");
		$getrec = @mysql_fetch_assoc($getrec);
		$getpended = @mysql_query("select * from ebpls_other_penalty where permit_type = '$permit_type'");
		$getpended = @mysql_fetch_assoc($getpended);
		$renewaldate = "$addend$getpended[renewaldate]";
		$renewaldate = strtotime($renewaldate);
		$renewaldate = date('Y-m-d', $renewaldate);
		$getpen = @mysql_query("select * from ebpls_other_penalty where permit_type = '$permit_type' and status = '1'");
		$getpen = @mysql_fetch_assoc($getpen);
		$getpaydate = @mysql_query("select * from temppayment where permit_type = '$permit_type' and owner_id = '$owner_id' order by payid desc limit 1");
		$getpaydate = @mysql_fetch_assoc($getpaydate);
		$lastpaydate = substr($getpaydate[pay_date],0,4);
		$lastpaydateyear = $lastpaydate;
		$yeardiff = 0;
		if ($datetoday > $renewaldate) {
			$otherpen = 0;
			$getpaydate = @mysql_query("select * from temppayment where permit_type = '$permit_type' and owner_id = '$owner_id' order by payid desc limit 1");
			$getpaydate = @mysql_fetch_assoc($getpaydate);
			$lastpaydate = substr($getpaydate[pay_date],0,4);
			$lastpaydateyear = $lastpaydate;
			$yeardiff = 0;
			
			if ($lastpaydate < $yeartoday) {
				$lastpaydate = $lastpaydate + 1;
				$yeardiff = $yeartoday - $lastpaydate;
				$lastpaydate = "$lastpaydate-";
			} else {
				$lastpaydate = "$lastpaydate-";
			}
			
			if ($getpen['indicator'] == '1') {
				$otherpen = $getpen['rateofpenalty'];
			} elseif ($getpen['indicator'] == '2') {
				eval("\$otherpen=$totalfee*$getpen[rateofpenalty];");
			}
			$d = date('m',strtotime($lastpaydate.$getpen['renewaldate'])); //setting date
			$s = date('m'); //curretn date
			//$s = $s + ($yeardiff * 12);
			$d = ($s - $d) + 1;
			$x = 1;
			$counter = 1;
			$intrate = "1$getpen[rateofinterest]";
			if ($getpen['intype'] == '1') {
				$interestrate = $getpen[rateofinterest] * $d;
			} elseif ($getpen['intype'] == '2') {
				while ($x <= $d) {
					$counter = $counter * $intrate;
					$x++;
				}
				$interestrate = $counter - 1;
			}
			if ($getpen['feeonly'] == '1') {
				$otherint = $interestrate * $totalfee;
			} elseif ($getpen['feeonly'] == '2') {
				$otherint = $interestrate * ($totalfee + $otherpen);
			}
			$maylatefile = $getpen['f_status'];
			if ($getpen[f_status] == '1') {
				$otherlate = $getpen['late_filing_fee'];
			}
			if ($permit_type == "Fishery") {
?>
	<tr>
	<td>&nbsp;</td><td>&nbsp;</td>
	</tr>
	<tr>
	<td><b>LATE FILING FEE</b></td><td><?php echo number_format($otherlate,2); ?></td>
	</tr>
	<tr>
	<td><b>SURCHARGE</b></td><td><?php echo number_format($otherpen,2); ?></td>
	</tr>
	<tr>
	<td><b>INTEREST</b></td><td><?php echo number_format($otherint,2); ?></td>
	</tr>
	<?php
			} elseif ($permit_type == 'Occupational') {
				if ($itemID_ != '2212') {
					$addspace = "<td>&nbsp;</td>";
				}
			?>
	<tr>
	<?php echo $addspace;?><td>&nbsp;</td><td>&nbsp;</td>
	</tr>
	<tr>
	<?php echo $addspace;?><td><b>LATE FILING FEE</b></td><td><?php echo number_format($otherlate,2); ?></td>
	</tr>
	<tr>
	<?php echo $addspace;?><td><b>SURCHARGE</b></td><td><?php echo number_format($otherpen,2); ?></td>
	</tr>
	<tr>
	<?php echo $addspace;?><td><b>INTEREST</b></td><td><?php echo number_format($otherint,2); ?></td>
	</tr>
	<?php
			}
		} else {
			$otherlate = 0;
			$otherpen = 0;
			$otherint = 0;
		}
		if ($getpen[backtax] == '1') {
			//BackTax Computation.... Goodluck sakin!
			if ($permit_type == 'Fishery') {
				$lastpaydateyear = $lastpaydateyear + 1;
				$nyeardiff = $yeartoday - $lastpaydateyear;
				$ngyeardiff = $nyeardiff;
				$xloop = 1;
				$nbacktaxcompute = 0;
				$meronlate = 0;
				while ($xloop <= $nyeardiff) {
					$backtaxcompute = 0;
					$xloop++;
					//echo $xloop."VooDoo<br>";
					//Boat Fees
					$getboat = SelectDataWhere($dbtype,$dbLink,"fish_boat","where owner_id=$owner_id");
					while ($getb = FetchRow($dbtype,$getboat))
					{
						$getfee = SelectDataWhere($dbtype,$dbLink,"boat_fee",
						"where boat_type='$getb[4]' and
						range_lower<=$getb[5] and range_higher>=$getb[5] and
						transaction='Renew' and active = 1");
						$getnum = NumRows($dbtype,$getfee);
						if ($getnum==0) {
							$getfee =  SelectDataWhere($dbtype,$dbLink,"boat_fee",
							"where boat_type='$getb[4]' and
							range_lower<=$getb[5] and range_higher=0 and
							transaction='Renew' and active = 1");
						}
						$getfee1 = FetchArray($dbtype,$getfee);
						$backtaxcompute = $backtaxcompute + $getfee1[amt];
					}
					//Fish Activity Fees
					$rt = SelectMultiTable($dbtype,$dbLink,"culture_fee a, fish_assess b",
					"a.culture_type, b.amt, b.ass_id, b.culture_id",
					"where a.culture_id = b.culture_id and b.owner_id = '$owner_id'");
					$tfee1 = 0;
					while ($ft = FetchRow($dbtype,$rt))
					{
						$getfee = SelectDataWhere($dbtype,$dbLink,"culture_fee",
						"where culture_id='$ft[3]'");
						$gf = FetchRow($dbtype,$getfee);
						if ($gf[2]=='1') { //constant
							$tfee = $gf[4];
						} elseif ($gf[2]=='2') { //formula
							eval("\$tfee=$ft[1]$gf[3];");
						} elseif ($gf[2]=='3') { //range
							$getr = SelectDataWhere($dbtype,$dbLink,"culture_range","where
							culture_id=$ft[3] and range_lower<=$ft[1] and
							range_higher >= $ft[1]");
							$numr = NumRows($dbtype,$getr);
							if ($numr==0) {
								$getr = SelectDataWhere($dbtype,$dbLink,"culture_range","where
								culture_id=$ft[3] and range_lower<=$ft[1] and
								range_higher = '0'");
							}
							$getre = FetchArray($dbtype,$getr);
							if (is_numeric($getre[amt])){
								$tfee=$getre[amt]; 
							} else {
								eval("\$tfee=$ft[1]$getre[amt];");
							}
						}
						$tfee1 = $tfee1+$tfee;
					}
					$backtaxcompute = $backtaxcompute + $tfee1;
					//Other Permit Fees
					$getot1 = SelectDataWhere($dbtype,$dbLink,"ebpls_fishery_fees","where permit_type='$stat' and active=1");
					$ff = 0;
					while ($getj = FetchArray($dbtype,$getot1))
					{
						$otherfee = $getj[fee_amount];
						$ff = $ff + $getj[fee_amount];
					}
					$backtaxcompute = $backtaxcompute + $ff; 
					$backtotalfee = $backtaxcompute; 
					//Get Surcharge and Interest
					if ($getpen['indicator'] == '1') {
						$backpen = $getpen['rateofpenalty'];
					} elseif ($getpen['indicator'] == '2') {
						eval("\$backpen=$backtotalfee*$getpen[rateofpenalty];");
					}
					
					$backtaxcompute = $backtaxcompute + $backpen; 
					$mpyear = date('Y');
					$d = date('m',strtotime($mpyear."-".$getpen['renewaldate'])); //setting date
					$s = date('m'); //curretn date
					$s = $s + ($ngyeardiff * 12);
					//$d = ($s - $d) + 1;
					$d = ($s - 1) + 1;
					if ($d > 36) {
						$d = 36;
					}
					$x = 1;
					$counter = 1;
					$intrate = "1$getpen[rateofinterest]";
					if ($getpen['intype'] == '1') {
						$interestrate = $getpen[rateofinterest] * $d;
					} elseif ($getpen['intype'] == '2') {
						while ($x <= $d) {
							$counter = $counter * $intrate;
							$x++;
						}
						$interestrate = $counter - 1;
					}
					if ($getpen['feeonly'] == '1') {
						$backint = $interestrate * $backtotalfee;
					} elseif ($getpen['feeonly'] == '2') {
						$backint = $interestrate * ($backtotalfee +$backpen);
					}
					$backtaxcompute = $backtaxcompute + $backint;
					if ($otherlate == '0') {
						if ($getpen[f_status] == '1') {
							if ($meronlate != '1') {
								$backlate = $getpen['late_filing_fee'];
								$meronlate = 1;
							} else {
								$backlate = 0;
							}
						}
					}
					$backtaxcompute = $backtaxcompute + $backlate;
					/////
					$ngyeardiff = $ngyeardiff - 1;
					$nbacktaxcompute = $nbacktaxcompute + $backtaxcompute;
				}
				$backtaxcompute = $nbacktaxcompute;
			} elseif ($permit_type == 'Occupational') {
				$lastpaydateyear = $lastpaydateyear + 1;
				$nyeardiff = $yeartoday - $lastpaydateyear;
				$ngyeardiff = $nyeardiff;
				$xloop = 1;
				$backtaxcompute = 0;
				while ($xloop <= $nyeardiff) {
					$xloop++;
					//Get Fees
					$occfee = @mysql_query("select sum(fee_amount) from ebpls_occu_fees where permit_type='$stat'
					and active='1'");
					$backtotalfee = 0;
					while ($occufee = @mysql_fetch_row($occfee)) {
						$backtaxcompute = $backtaxcompute + $occufee[0];
						$backtotalfee = $backtotalfee + $occufee[0];
					}
					//Get Surcharge and Interest
					if ($getpen['indicator'] == '1') {
						$backpen = $getpen['rateofpenalty'];
					} elseif ($getpen['indicator'] == '2') {
						eval("\$backpen=$backtotalfee*$getpen[rateofpenalty];");
					}
					$backtaxcompute = $backtaxcompute + $backpen;
					$d = date('m',strtotime($lastpaydate.$getpen['renewaldate'])); //setting date
					$s = date('m'); //curretn date
					$s = $s + ($ngyeardiff * 12);
					//$d = ($s - $d) + 1;
					$d = ($s - 1) + 1;
					if ($d > 36) {
						$d = 36;
					}
					$x = 1;
					$counter = 1;
					$intrate = "1$getpen[rateofinterest]";
					if ($getpen['intype'] == '1') {
						$interestrate = $getpen[rateofinterest] * $d;
					} elseif ($getpen['intype'] == '2') {
						while ($x <= $d) {
							$counter = $counter * $intrate;
							$x++;
						}
						$interestrate = $counter - 1;
					}
					if ($getpen['feeonly'] == '1') {
						$backint = $interestrate * $backtotalfee;
					} elseif ($getpen['feeonly'] == '2') {
						$backint = $interestrate * ($backtotalfee +$backpen);
					}
					$backtaxcompute = $backtaxcompute + $backint; 
					if ($otherlate == '0') {
						if ($getpen[f_status] == '1') {
							if ($meronlate != '1') {
								$backlate = $getpen['late_filing_fee'];
								$meronlate = 1;
							} else {
								$backlate = 0;
							}
						}
					}
					$backtaxcompute = $backtaxcompute + $backlate;
					/////
					$ngyeardiff = $ngyeardiff - 1;
				}
			}
		}
		if ($getpen[backtax] == '1' and $permit_type == 'Occupational') {
		?>
		<tr>
			<?php echo $addspace;?><td><b>BACKTAX</b></td><td><?php echo number_format($backtaxcompute,2); ?></td>
		</tr>
		<?php
		} elseif ($getpen[backtax] == '1' and $permit_type=='Fishery') {
			?>
		<tr>
		<td><b>BACKTAX</b></td><td><?php echo number_format($backtaxcompute,2); ?></td>
		</tr>
		<?php
		} else {
			$backtaxcompute = 0;
		}
	?>
	<input type="hidden" name="surintamount" value="<? echo $otherpen + $otherint + $otherlate + $backtaxcompute;?>">
<?php
	}
}
?>
