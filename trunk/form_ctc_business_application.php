<?php
require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");
require_once("includes/variables.php");
require_once("ebpls-php-lib/utils/ebpls.search.funcs.php");
$is_new=true;
//$dbLink = get_db_connection();
$_search=''
?>
<!-- start of CTC formating //-->
<link rel="stylesheet" href="stylesheets/default.css" type="text/css"/>
<script language="Javascript">
function PrintCTC() {
	window.open('ctc_business.php?ctc_code=<?php echo $ctc_code; ?>' ,'popup','height=500,width=800,menubar=no,scrollbars=yes,status=no,screenX=100,screenY=0,left=100,top=0');
	}
function iCheckValues() {
        var _FRM = document._FRM;
        if (_FRM.ctc_code.value == "") {
                alert("Enter valid CTC Code!!");
				_FRM.ctc_code.focus();
				_FRM.ctc_code.select();
                return false;
        }
        if (_FRM.ctc_additional_tax1.value < 0 || isNaN(_FRM.ctc_additional_tax1.value) == true) {
                alert("Enter valid Gross Receipts Amount!!");
                _FRM.ctc_additional_tax1.focus();
                return false;
        }
        if (_FRM.ctc_additional_tax2.value < 0 || isNaN(_FRM.ctc_additional_tax2.value) == true) {
                alert("Enter valid Gross Receipts Amount!!");
                _FRM.ctc_additional_tax2.focus();
                return false;
        }
        _FRM.ctc_process.value = "PROCESS";
        _FRM.submit();
        return true;
}
function iCheckValues1() {
        var _FRM = document._FRM;
        if (_FRM.ctc_code.value == "") {
                alert("Enter valid CTC Code!!");
				_FRM.ctc_code.focus();
				_FRM.ctc_code.select();
                return false;
        }
        if (_FRM.ctc_additional_tax1.value < 0 || isNaN(_FRM.ctc_additional_tax1.value) == true) {
                alert("Enter valid Gross Receipts Amount!!");
                _FRM.ctc_additional_tax1.focus();
                return false;
        }
        if (_FRM.ctc_additional_tax2.value < 0 || isNaN(_FRM.ctc_additional_tax2.value) == true) {
                alert("Enter valid Gross Receipts Amount!!");
                _FRM.ctc_additional_tax2.focus();
                return false;
        }
        _FRM.ctc_process.value = "SAVE";
        _FRM.submit();
        return true;
}
</script>
<table border=0 cellspacing=0 cellpadding=0 width='100%'>
<form method=post action=<?php echo $action;?>>
<input type='hidden' name='ctc_process'>

<tr>
	<td align="center" valign="top">
		<table border=0 cellspacing=1 cellpadding=0  width="100%" bgcolor='#202366'>
			<tr>
				<td align="center" valign="center"  bgcolor='#ffffff' width=33% class='normaltax' colspan=2>COMMUNITY TAX CERTIFICATE</td>
			        <td align="center" valign="center"  bgcolor='#ffffff' width=15% class='normalgray'>BUSINESS</td>
				<td align="center" valign="center" width=18% height=20  bgcolor='#ffffff' class='normaltax'><input type='text' name='ctc_code' value=<?php echo $ctc_code;?>></td>
			</tr>
			<tr>
				<td align="center" valign="top"  bgcolor='#ffffff' width=10% align=center><pre><sup class='suptitle'>YEAR</sup><BR><font class=ctc_year><?php echo $yearnow;?></font></pre></td>
				<td align="center" valign="top"  bgcolor='#ffffff' width=35% ><sup class='suptitle'>PLACE OF ISSUE ( City / Mun / Prov )<BR><font class=ctc_place_issued>
				<?php 
				$getlgu = mysql_query("select lguname, lguprovince from ebpls_buss_preference");
				$getlgu = mysql_fetch_row($getlgu);
				$getlgu1 = mysql_query("select city_municipality_desc from ebpls_city_municipality where city_municipality_code='$getlgu[0]'");
                                $getlgu1 = mysql_fetch_row($getlgu1);
                                $lguname = $getlgu1[0];
				$getprov = @mysql_query("select province_desc from ebpls_province where province_code='$getlgu[1]'");
                                $getprov = @mysql_fetch_row($getprov);
				$lguprovince = $getprov[0];
				$ctc_place_issued=$lguname.", ".$lguprovince;
				echo $ctc_place_issued;
				?>
				<input type='hidden' name=ctc_place_issued value='<?php echo $ctc_place_issued;?>'>
				</font></td>
				<td align="center" valign="top"  bgcolor='#ffffff' width=35% ><pre><sup class='suptitle'>DATE ISSUED</sup><BR><font class=ctc_item><?php echo $tdate;?></font></pre></td>
				<td align="center" valign="top" width=20% height=20  bgcolor='#ffffff' class='normaltax'>TAX PAYER'S COPY</td>
			</tr>
			<tr>




				<td align="center" valign="top"  bgcolor='#ffffff' width="85%" align=center colspan=3>
					<table width="100%" border=0 cellpadding=2 >
					<tr>
						<td>COMPANY'S FULLNAME</td>
					<tr>
					<?php 
					$ctcrecord = mysql_query("select * from ebpls_business_enterprise where business_id=$business_id") or die("patay".mysql_error());
                                                $ctcrecord = mysql_fetch_row($ctcrecord);
                                                $ctc_company = $ctcrecord[2];
//                                                $ctc_company_address=$ctcrecord[5].' '.$ctcrecord[6].' '.$ctcrecord[7].' '.$ctcrecord[8].' '.$ctcrecord[9].' '.$ctcrecord[10];
                                                $ctc_tin_no = $ctcrecord[24];
                                                $ctc_tin_no = $ctcrecord[15];
                                                $ctc_birth_date = $ctcrecord[21];
                                                $ctc_business_name = $ctcrecord[2];
						$getbarangay = mysql_query("select barangay_desc
from ebpls_barangay where barangay_code='$ctcrecord[7]'");
                                                $getbarangay1 = mysql_fetch_row($getbarangay);
                                                $ctc_barangay_code = $getbarangay1[0];
                                                $getzone = mysql_query("select zone_desc
from ebpls_zone where zone_code='$ctcrecord[8]'");
                                                $getzone1 = mysql_fetch_row($getzone);
                                                $ctc_zone_code = $getzone1[0];
                                                $getdistrict = mysql_query("select district_desc
from ebpls_district where district_code='$ctcrecord[9]'");
                                                $getdistrict1 = mysql_fetch_row($getdistrict);
                                                $ctc_district_code = $getdistrict1[0];
                                                $getcity = mysql_query("select city_municipality_desc
from ebpls_city_municipality  where city_municipality_code='$ctcrecord[11]'");
                                                $getcity1 = mysql_fetch_row($getcity);
                                                $ctc_city = $getcity1[0];
                                                $ctc_company_address=$ctcrecord[5].' '.$ctcrecord[6].' '.$ctc_zone_code.' '.$ctc_barangay_code.' '.$ctc_district_code.' '.$ctc_city;
                                                 
                                                                                                 
                                        ?>
						<td><input name="ctc_company" value='<?php echo $ctc_company;?>' size=60 maxlength=128></td>
					</tr>
					</table>
				</td>


				<td align="" valign="top"  bgcolor='#ffffff' width=25% ><sup class='suptitle'>&nbsp;TIN IF ANY<BR>&nbsp;&nbsp;<input name="ctc_tin_no" size=15 maxlength=32 value=<?php echo $ctc_tin_no;?>></td>
			</tr>
			<tr>
				<td align="" valign="top"  bgcolor='#ffffff' width="70%" align=center colspan=2>
				&nbsp;ADDRESS OF PRINCIPAL PLACE OF BUSINESS
				<BR>
				&nbsp;<input name="ctc_company_address" size=50 maxlength=128 value='<?php echo $ctc_company_address;?>'>
				</td>
				<td align="" valign="top"  bgcolor='#ffffff' width=25% colspan=2><sup class='suptitle'>&nbsp;DATE OF REG./INCORPORATION<BR>
				&nbsp;&nbsp;<?php set_form_date($ctc_incorporation_date) ?>
				</td>
			</tr>
		</table>
	</td>
</tr>
<tr>
	<td align="center" valign="top">
		<table border=0 cellspacing=1 cellpadding=5  width="100%" bgcolor='#202366'>
			<tr>
				<td align="" valign="middle" bgcolor='#ffffff' class='normaltax'>KIND OF ORGANIZATION<BR><input name='ctc_organization_type' value=<?php echo $ctc_organization_type;?>>
				</td>
			        <td align="" colspan=3 valign="center" bgcolor='#ffffff' class='normaltax'>PLACE OF INCORPORATION<BR> <input name="ctc_place_of_incorporation" size=32 maxlength=128 value='<?php echo $ctc_place_of_incorporation;?>'></td>
			</tr>
			<tr>
				<td align="" valign="center"  bgcolor='#ffffff' class='normaltax' colspan=2>KIND /  NATURE OF BUSINESS
				<BR>
					 <?php
				 echo get_select_data_where($dbLink,'ctc_business_nature','ebpls_buss_nature ','naturedesc','naturedesc',$ctc_business_nature, "naturestatus='A'  order by naturedesc");
					?>
				</td>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax'>TAXABLE AMOUNT</td>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax'>COMMUNITY TAX DUE</td>
			</tr>
			<tr>
				<td align="" valign="center"  bgcolor='#ffffff' class='normaltax' colspan=2>A. BASIC COMMUNITY TAX (Php500.00)  </td>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax'></td>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax'><input type="text" class="ctc_amounttext"  name="ctc_basic_tax" readonly size=10 maxlength=30 align=right value=<?php echo number_format($ctc_basic_tax,2);?>></td>
			</tr>
			<tr>
				<td align="" valign="center"  bgcolor='#ffffff' class='normaltax' colspan=2>B. ADDITIONAL COMMUNITY TAX ( tax not to exceed Php10,000.00) </td>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax'></td>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax'></td>
			</tr>
			<tr>
				<td align="" valign="center"  bgcolor='#ffffff' class='normaltax' colspan=2>1  ASSESSED VALUE OF REAL PROPERTY OWNED IN THE PHILIPPINES (Php2.00 for every Php5,000.00)</td>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax'><input type="text" class="ctc_amounttext"  name="ctc_additional_tax1"  size=10 maxlength=30 align=right value=<?php echo $ctc_additional_tax1;?>></td>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax'><input type="text" class="ctc_amounttext" name="ctc_additional_tax1_due"  size=10 maxlength=30 readonly align=right value=<?php echo number_format($ctc_additional_tax1_due,2);?>></td>
			</tr>
			<tr>
				<td align="" valign="center"  bgcolor='#ffffff' class='normaltax' colspan=2>2  GROSS RECEIPTS INCLUDING DIVIDENDS/EARNINGS DERIVED FROM BUSINESS IN THE PHIL DURING THE PRECEDING YEAR (Php2.00 for every Php5,000.00)</td>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax'><input type="text" class="ctc_amounttext" name="ctc_additional_tax2"  size=10 maxlength=30 align=right value=<?php echo $ctc_additional_tax2;?>></td>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax'><input type="text" class="ctc_amounttext" name="ctc_additional_tax2_due"  size=10 maxlength=30 readonly align=right value=<?php echo number_format($ctc_additional_tax2_due,2);?>></td>
			</tr>
			<tr>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax' colspan=2 rowspan=2><!--<BR>------------------------------------------<BR>Tax Payer's Signature<BR><BR>--></td>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax'>TOTAL(Php)</td>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax'><input type="text" class="ctc_total_amounttext" name="ctc_total_amount_due" readonly size=10 maxlength=30 align=right value=<?php echo number_format($ctc_total_amount_due,2);?>></td>
			</tr>
			<tr>

			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax'>Interest(Php)</td>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax'><input type="text" class="ctc_amounttext" name="ctc_total_interest_due" readonly size=10 maxlength=30 align=right value=<?php echo number_format($ctc_total_interest_due,2);?>></td>
			</tr>
			<tr>

			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax' colspan=2><!--<BR>------------------------------------------<BR>Municipal / City Treasurer<BR><BR>--></td>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax'>Total Amount Paid(Php)</td>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax'><input type="text" class="ctc_total_amounttext" name="ctc_total_amount_paid" readonly size=10 maxlength=30 align=right value=<?php echo number_format($ctc_total_amount_paid,2);?>></td>
			</tr>

		</table>
	</td>
</tr>
</table>
<br><br>
<table border=0 cellspacing=0 cellpadding=0 width='100%'>
        <tr>
                <td align=center>
		<input type='button' name='ctc_proc1' value="PROCESS" onClick="javascript: iCheckValues();" <? echo $processenabled;?>>
        <input type='button' name='ctc_proc2' value="SAVE" onClick="javascript: iCheckValues1();" <? echo $saveenabled;?>>
		<input type='reset' name='ctc_reset' value='RESET'>
        <input type='button' name='ctc_reset' value='CANCEL' onclick="parent.location='index.php?part=4&itemID_=1002&busItem=CTC&permit_type=CTC&ctc_type=BUSINESS&item_id=CTC'">
		<input type='reset' name='ctc_print' value="PRINT" onClick="javascript: PrintCTC();" <? echo $printenabled;?>>
                        
                                                                                 
                </td>
        </tr>
</table>

<!--// end of the formating CTC //-->
