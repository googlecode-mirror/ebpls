<?php
require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");
require_once("ebpls-php-lib/utils/ebpls.search.funcs.php");
$is_new=true;
//$dbLink = get_db_connection();


?>
<!-- start of CTC formating //-->
<link rel="stylesheet" href="stylesheets/default.css" type="text/css"/>
<?php
	
	
?>
<script language="Javascript">
function PrintCTC(){
window.open('ctc_individual.php?ctc_owner_id=<?php echo $ctc_owner_id; ?>&ctc_code=<?php echo $ctc_code; ?>' ,'popup','height=500,width=800,menubar=no,scrollbars=yes,status=no,screenX=100,screenY=0,left=100,top=0');
}
function iCheckValues() {
	var _FRM = document._FRM;
	if (isBlank(_FRM.ctc_code.value) == true) {
		alert("Please enter CTC Number!");
		_FRM.ctc_code.focus();
		_FRM.ctc_code.select();
		return false;
	}
	if (_FRM.ctc_code.value.length > 15) {
		alert("Invalid CTC Number!");
		_FRM.ctc_code.focus();
		_FRM.ctc_code.select();
		return false;
	}
	if (_FRM.ctc_additional_tax1.value < 0 || isNaN(_FRM.ctc_additional_tax1.value) == true) {
                alert("Enter valid Gross Receipts Amount!!");
		_FRM.ctc_additional_tax1.focus();
		_FRM.ctc_additional_tax1.select();
				return false;         
	}
        if (_FRM.ctc_additional_tax2.value < 0 || isNaN(_FRM.ctc_additional_tax2.value) == true) {
                alert("Enter valid Gross Receipts Amount!!");
		_FRM.ctc_additional_tax2.focus();
		_FRM.ctc_additional_tax2.select();
                return false;
        }
        if (_FRM.ctc_additional_tax3.value < 0 || isNaN(_FRM.ctc_additional_tax3.value) == true) {
                alert("Enter valid Gross Receipts Amount!!");
		_FRM.ctc_additional_tax3.focus();
		_FRM.ctc_additional_tax3.select();
                return false;
        }

	_FRM.ctc_process.value = "PROCESS";
	_FRM.submit();
	return true;
}
function iCheckValues1() {
        var _FRM = document._FRM;
        if (isBlank(_FRM.ctc_code.value) == true) {
                alert("Please enter CTC Number!");
				_FRM.ctc_code.focus();
				_FRM.ctc_code.select();
                return false;
        }
		if (_FRM.ctc_code.value.length > 15) {
		alert("Invalid CTC Number!");
		_FRM.ctc_code.focus();
		_FRM.ctc_code.select();
		return false;
		}
		if (_FRM.ctc_additional_tax1.value < 0 || isNaN(_FRM.ctc_additional_tax1.value) == true) {
                alert("Enter valid Gross Receipts Amount!!");
		_FRM.ctc_additional_tax1.focus();
		_FRM.ctc_additional_tax1.select();
				return false;         
	}
        if (_FRM.ctc_additional_tax2.value < 0 || isNaN(_FRM.ctc_additional_tax2.value) == true) {
                alert("Enter valid Gross Receipts Amount!!");
		_FRM.ctc_additional_tax2.focus();
		_FRM.ctc_additional_tax2.select();
                return false;
        }
        if (_FRM.ctc_additional_tax3.value < 0 || isNaN(_FRM.ctc_additional_tax3.value) == true) {
                alert("Enter valid Gross Receipts Amount!!");
		_FRM.ctc_additional_tax3.focus();
		_FRM.ctc_additional_tax3.select();
                return false;
        }

        _FRM.ctc_process.value = "SAVE";
        _FRM.submit();
        return true;
}
</script>
<table border=0 cellspacing=0 cellpadding=0 width='100%'>
<!--<form method=post action=<?php echo $action;?>>-->
<input type='hidden' name='ctc_additional_tax1_due' value=<?php echo $ctc_additional_tax1_due;?>>
<input type='hidden' name='ctc_additional_tax2_due' value=<?php echo $ctc_additional_tax2_due;?>>
<input type='hidden' name='ctc_additional_tax3_due' value=<?php echo $ctc_additional_tax3_due;?>>
<input type='hidden' name='ctc_basic_tax' value=<?php echo $ctc_basic_tax;?>>
<input type='hidden' name='ctc_total_paid' value=<?php echo $ctc_total_paid;?>>
<input type='hidden' name='exempted' value=<?php echo $exempted;?>>
<input type='hidden' name='ctc_additional_tax1_due' value=<?php echo $ctc_additional_tax1_due;?>>
<input type='hidden' name='submit_button' value=<?php echo $submit_button;?>>
<input type='hidden' name='ctc_process'>

<tr>
	<td align="center" valign="top">
		<table border=0 cellspacing=1 cellpadding=0  width="100%" bgcolor='#202366'>
			<tr>
				<td align="center" valign="center"  bgcolor='#ffffff' width=240 class='normaltax' colspan=2>COMMUNITY TAX CERTIFICATE</td>
			        <td align="center" valign="center"  bgcolor='#ffffff' width=100 class='normalgray'>&nbsp;INDIVIDUAL</td>
				<td align="center" valign="center" width=180 height=20  bgcolor='#ffffff' class='normaltax'><input type='text' name='ctc_code' value=<?php echo $ctc_code;?>></td>
			</tr>
			<tr>
				<td align="center" valign="top"  bgcolor='#ffffff' width=10% align=center><pre><sup class='suptitle'>YEAR</sup><BR><font class=ctc_place_issued><?php echo $tdate;?></font></pre></td>
				<td align="center" valign="top"  bgcolor='#ffffff' width=35% ><sup class='suptitle'>PLACE OF ISSUE ( City / Mun / Prov )<BR>
<?php
                                $getlgu = @mysql_query("select lguname, lguprovince from ebpls_buss_preference");
                                $getlgu = @mysql_fetch_row($getlgu);
				$getlgu1 = @mysql_query("select city_municipality_desc from ebpls_city_municipality where city_municipality_code='$getlgu[0]'");
				$getlgu1 = @mysql_fetch_row($getlgu1);
				$getprov = @mysql_query("select province_desc from ebpls_province where province_code='$getlgu[1]'");
                                $getprov = @mysql_fetch_row($getprov);
                                $lguname = $getlgu1[0];
                                $lguprovince = $getprov[0];
                                $ctc_place_issued=$lguname.", ".$lguprovince;
                                echo $ctc_place_issued;
                                ?></td>
 <input type='hidden' name=ctc_place_issued value='<?php echo $ctc_place_issued;?>'>

				<td align="center" valign="top"  bgcolor='#ffffff' width=35% ><pre><sup class='suptitle'>DATE ISSUED</sup><BR><font class=ctc_date><?php echo $datetoday;?></font></pre></td>
				<td align="center" valign="top" width=20% height=20  bgcolor='#ffffff' class='normaltax'>TAX PAYER'S COPY</td>
			</tr>
			<tr>
				<td align="center" valign="top"  bgcolor='#ffffff' width="85%" align=center colspan=3>
					<table width="100%" border=0 cellpadding=2 >
					<tr>
						<td>NAME</td>
						<td>( SURNAME )</td>
						<td>( FIRST )</td>
						<td>(MIDDLE)</td></tr>
					<tr>
					<?php
						$ctcrecord = mysql_query("select * from ebpls_owner where owner_id=$owner_id") or die("patay".mysql_error());
						$ctcrecord = mysql_fetch_row($ctcrecord);
						$ctc_first_name = $ctcrecord[1];
						$ctc_middle_name = $ctcrecord[2];
						$ctc_last_name = $ctcrecord[3];
					//	$ctc_address=$ctcrecord[4].' '.$ctcrecord[5].' '.$ctcrecord[6].' '.$ctcrecord[7].' '.$ctcrecord[8].' '.$ctcrecord[9];
					//	$ctc_street = $ctcrecord[5];
					//	$ctc_barangay_code = $ctcrecord[6];
					//	$ctc_zone_code = $ctcrecord[7];
					//	$ctc_district_code = $ctcrecord[8];
					//	$ctc_city = $ctcrecord[9];
					  $ctc_city = $ctcrecord[9];
                         $ctc_citizenship = $ctcrecord[13];
                                                $ctc_civil_status = $ctcrecord[14];
                                                $ctc_gender = $ctcrecord[15];
                                                $ctc_tin_no = $ctcrecord[16];
                                                $ctc_birth_date = $ctcrecord[22];

						$getbarangay = mysql_query("select barangay_desc from ebpls_barangay where barangay_code='$ctcrecord[6]'");
						$getbarangay1 = mysql_fetch_row($getbarangay);
						$ctc_barangay_code = $getbarangay1[0];
						$getzone = mysql_query("select zone_desc
from ebpls_zone where zone_code='$ctcrecord[7]'");
                                                $getzone1 = mysql_fetch_row($getzone);
                                                $ctc_zone_code = $getzone1[0];
						$getdistrict = mysql_query("select district_desc
from ebpls_district where district_code='$ctcrecord[8]'");
                                                $getdistrict1 = mysql_fetch_row($getdistrict);
                                                $ctc_district_code = $getdistrict1[0];
						$getcity = mysql_query("select city_municipality_desc
from ebpls_city_municipality  where city_municipality_code='$ctcrecord[9]'");
                                                $getcity1 = mysql_fetch_row($getcity);
                                                $ctc_city = $getcity1[0];
						$ctc_address=$ctcrecord[4].' '.$ctcrecord[5].' '.$ctc_zone_code.' '.$ctc_barangay_code.' '.$ctc_district_code.' '.$ctc_city;

					?>
						<td></td>
						<td><input name="ctc_last_name" value="<?php echo $ctc_last_name;?>" size=20 maxlength=32 readonly></td>
						<td><input name="ctc_first_name" value="<?php echo $ctc_first_name;?>" size=20 maxlength=32 readonly></td>
						<td><input name="ctc_middle_name" value="<?php echo $ctc_middle_name;?>" size=20 maxlength=32 readonly></td>
					</tr>
					</table>
				</td>


				<td align="" valign="top"  bgcolor='#ffffff' width=190 ><sup class='suptitle'>&nbsp;TIN IF ANY<BR>&nbsp;&nbsp;<input  type='text' name="ctc_tin_no" value="<?php echo $ctc_tin_no;?>" size=15 maxlength=32></td>
			</tr>
			<tr>
				<td align="" valign="top"  bgcolor='#ffffff' width="70%" align=center colspan=3>
				&nbsp;ADDRESS
				<BR>
				&nbsp;<input name="ctc_address" value="<?php echo $ctc_address;?>" size=60 readonly>
				</td>
				<td align="" valign="top"  bgcolor='#ffffff' width=190 ><sup class='suptitle'>&nbsp;SEX<BR>&nbsp;
				<select name='ctc_gender'>
                			<option value='M' size=5 <?php echo (! strcasecmp($ctc_gender,'M')) ? ('selected') : (''); ?>>M</option>
                  			<option value='F' size=5 <?php echo (! strcasecmp($ctc_gender,'F')) ? ('selected') : (''); ?>>F</option>
				</td>
			</tr>
		</table>
	</td>
</tr>
<tr>
	<td align="center" valign="top">
		<table border=0 cellspacing=1 cellpadding=5  width="100%" bgcolor='#202366'>
			<tr>
				<td align="" valign="middle" bgcolor='#ffffff' class='normaltax'>CITIZENSHIP<BR>
				<?php echo get_select_brgy($dbLink,'ctc_citizenship','ebpls_citizenship','cit_desc','cit_desc',$ctc_citizenship);?>
				</td>
			        <td align="" valign="center" bgcolor='#ffffff' class='normaltax'>ICR NO. ( If an alien )<BR> <input type='text' name="ctc_icr_no" size=15 maxlength=128 value=<?php echo $ctc_icr_no;?>></td>
			        <td align="" valign="center" bgcolor='#ffffff' class='normaltax'>PLACE OF BIRTH<BR><input type='text' name="ctc_place_of_birth" size=15 maxlength=128 value=<?php echo $ctc_place_of_birth;?>></td>
			        <td align="" valign="center" bgcolor='#ffffff' class='normaltax'>HEIGHT(cm)<BR><input type='text' name="ctc_height" size=15 maxlength=128 value=<?php echo $ctc_height;?>></td>
			</tr>
			<tr>
				<td align="" valign="middle"  bgcolor='#ffffff' class='normaltax' colspan=1>CIVIL STATUS<BR><input type='text' name="ctc_civil_status" size=20 maxlength=25 value=<?php echo $ctc_civil_status;?>></td>
			        <td align="" valign="center"  bgcolor='#ffffff' class='normaltax' colspan=2>DATE OF BIRTH<BR><?php set_form_date($ctc_birth_date, "now", 0) ?></td>
			        <td align="" valign="center"  bgcolor='#ffffff' class='normaltax'>WEIGHT(kg)<BR><input type='text' name="ctc_weight" size=15 maxlength=128 value=<?php echo $ctc_weight;?>></td>
			</tr>
			<tr>
				<td align="" valign="center"  bgcolor='#ffffff' class='normaltax' colspan=2>PROFESSION / OCCUPATION / BUSINESS<BR>
				<input type='text' name="ctc_occupation" size=60 maxlength=128 value=<?php echo $ctc_occupation;?>>
				</td>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax'>TAXABLE AMOUNT</td>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax'>COMMUNITY TAX DUE</td>
			</tr>
			<tr>
				<td align="" valign="center"  bgcolor='#ffffff' class='normaltax' colspan=2>A. BASIC COMMUNITY TAX (Php5.00) Voluntary or 
				<?php
				if ($eexempted==1) {
				?>
				<input type=checkbox name='exempted' value=1 CHECKED>Exempted (Php1.00)</td>
				<?php 
				} else {
				?>
				<input type=checkbox name='exempted' value=0 UNCHECKED>Exempted (Php1.00)</td>
				<?php
				}
				?>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax'></td>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax'><input type="text" align='right' class="ctc_amounttext"  name="ctc_basic_tax" size=10 maxlength=30 readonly value=<?php echo number_format($ctc_basic_tax,2);?>></td>
			</tr>
			<tr>
				<td align="" valign="center"  bgcolor='#ffffff' class='normaltax' colspan=2>B. ADDITIONAL COMMUNITY TAX ( tax not to exceed Php5,000.00) </td>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax'></td>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax'></td>
			</tr>
			<tr>
				<td align="" valign="center"  bgcolor='#ffffff' class='normaltax' colspan=2>1  GROSS RECEIPTS OR EARNINGS DERIVED FROM BUSINESS DURING THE PRECEDING YEAR( Php1.00 for every Php1,000.00)</td>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax'><input type="text" align='right' class="ctc_amounttext" name="ctc_additional_tax1" size=10 maxlength=30 value=<?php $ctc_tax1=$ctc_additional_tax1; echo  $ctc_tax1;?>></td>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax'><input type="text" align='right' class="ctc_amounttext" name="ctc_additional_tax1_due" readonly size=10 maxlength=30 value=<?php $ctc_tax1_due=$ctc_additional_tax1_due; echo number_format($ctc_tax1_due,2);?>></td>
			</tr>
			<tr>
				<td align="" valign="center"  bgcolor='#ffffff' class='normaltax' colspan=2>2  SALARIES OR GROSS RECEIPT OR EARNINGS DERIVED FROM EXERCISE OF PROFESSION OR PURSUIT OF ANY OCCUPATION (Php1.00 for every Php1,000.00)</td>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax'><input type="text" align='right' class="ctc_amounttext" name="ctc_additional_tax2" size=10 maxlength=30  value=<?php $ctc_tax2=$ctc_additional_tax2; echo  $ctc_tax2;?>></td>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax'><input type="text" align='right' class="ctc_amounttext" name="ctc_additional_tax2_due" readonly size=10 maxlength=30 value=<?php $ctc_tax2_due=$ctc_additional_tax2_due; echo number_format($ctc_tax2_due,2);?>></td>
			</tr>
			<tr>
				<td align="" valign="center"  bgcolor='#ffffff' class='normaltax' colspan=2>3  INCOME FROM REAL PROPERTY (Php1.00 for every Php1,000.00)</td>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax'><input type="text" align='right' class="ctc_amounttext" name="ctc_additional_tax3" size=10 maxlength=30  value=<?php $ctc_tax3=$ctc_additional_tax3; echo $ctc_tax3;?>></td>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax'><input type="text" align='right' class="ctc_amounttext" name="ctc_additional_tax3_due" readonly size=10 maxlength=30 value=<?php $ctc_tax3_due=$ctc_additional_tax3_due; echo number_format($ctc_tax3_due,2);?>></td>
			</tr>
			<tr>
				<td align="" valign="top"  bgcolor='#ffffff' class='normaltax' rowspan=4><!--Right Thumb Print--></td>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax' rowspan=2>
			        <!--<BR>------------------------------------------<BR>Tax Payer's Signature<BR><BR>--></td>
			        <td align="" valign="center"  bgcolor='#ffffff' class='normaltax'>TOTAL(Php)</td>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax'><input type="text" align='right' class="ctc_total_amounttext" name="ctc_total_amount_due" readonly size=10 maxlength=30 value=<?php $ctc_total_amount=$ctc_total_amount_due; echo number_format($ctc_total_amount,2);?>></td>
			</tr>
			<tr>


			        <td align="" valign="center"  bgcolor='#ffffff' class='normaltax'>Interest(Php)</td>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax'><input type="text" align='right' class="ctc_amounttext" name="ctc_total_interest_due" readonly size=10 maxlength=30 value=<?php $ctc_total_interest=$ctc_total_interest_due; echo number_format($ctc_total_interest,2);?>></td>
			</tr>
			<tr>

			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax' rowspan=2><!--<BR>------------------------------------------<BR>Tax Payer's Signature<BR><BR>--></td>
			        <td align="" valign="center"  bgcolor='#ffffff' class='normaltax'>Total Amount Paid(Php)</td>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax'><input type="text" align='right' class="ctc_total_amounttext" name="ctc_total_paid" readonly size=10 maxlength=30 value=<?php $ctc_total_paid1=$ctc_total_paid; echo number_format($ctc_total_paid1,2);?>></td>
			</tr>
			<tr>


			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax' colspan=2><!--(in Words)--><BR></td>
			</tr>
		</table>
	</td>
</tr>

</table>
<br><br>
<table border=0 cellspacing=0 cellpadding=0 width='100%'>
	<tr>
		<td align=center>
	<input type='button' name='ctc_proc2' value="PROCESS" onClick="javascript: iCheckValues();" <? echo $processenabled;?>>
	<input type='button' name='ctc_proc1' value="SAVE" onClick="javascript: iCheckValues1();" <? echo $saveenabled;?>>
	<input type='reset' name='ctc_reset' value='RESET'>
	<input type='button' name='ctc_reset' value='CANCEL' onclick="parent.location='index.php?part=4&itemID_=1001&busItem=CTC&permit_type=CTC&ctc_type=INDIVIDUAL&item_id=CTC'">
	<input type='reset' name='ctc_print' value="PRINT" onClick="javascript: PrintCTC();" <? echo $printenabled;?>>
		</td>
	</tr>
</table>
<!--// end of the formating CTC //-->

