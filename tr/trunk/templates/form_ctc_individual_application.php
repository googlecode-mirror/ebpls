<?php
require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");
require_once("ebpls-php-lib/utils/ebpls.search.funcs.php");
$is_new=true;
$dbLink = get_db_connection();
$tdate=date('Y');
?>
<!-- start of CTC formating //-->
<table border=0 cellspacing=0 cellpadding=0 width='100%'>
<tr>
	<td align="center" valign="top">
		<table border=0 cellspacing=1 cellpadding=0  width="100%" bgcolor='#202366'>
			<tr>
				<td align="center" valign="center"  bgcolor='#ffffff' width=240 class='normaltax' colspan=2>COMMUNITY TAX CERTIFICATE</td>
			        <td align="center" valign="center"  bgcolor='#ffffff' width=100 class='normalgray'>&nbsp;INDIVIDUAL</td>
				<td align="center" valign="center" width=180 height=20  bgcolor='#ffffff' class='normaltax'>[ctc_code]</td>
			</tr>
			<tr>
				<td align="center" valign="top"  bgcolor='#ffffff' width=10% align=center><pre><sup class='suptitle'>YEAR</sup><BR><font class=ctc_place_issued><?php echo $tdate;?></font></pre></td>
				<td align="center" valign="top"  bgcolor='#ffffff' width=35% ><sup class='suptitle'>PLACE OF ISSUE ( City / Mun / Prov )<BR><font class=ctc_place_issued>[PLACE_ISSUED]</font></td>
				<td align="center" valign="top"  bgcolor='#ffffff' width=35% ><pre><sup class='suptitle'>DATE ISSUED</sup><BR><font class=ctc_date>[DATE]</font></pre></td>
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
						<td></td>
						<td><input name="ctc_last_name" value="[ctc_last_name]" size=20 maxlength=32></td>
						<td><input name="ctc_first_name" value="[ctc_first_name]" size=20 maxlength=32></td>
						<td><input name="ctc_middle_name" value="[ctc_middle_name]" size=20 maxlength=32></td>
					</tr>
					</table>
				</td>


				<td align="" valign="top"  bgcolor='#ffffff' width=190 ><sup class='suptitle'>&nbsp;TIN IF ANY<BR>&nbsp;&nbsp;<input name="ctc_tin_no" value="[ctc_tin_no]" size=15 maxlength=32></td>
			</tr>
			<tr>
				<td align="" valign="top"  bgcolor='#ffffff' width="70%" align=center colspan=3>
				&nbsp;ADDRESS
				<BR>
				&nbsp;<input name="ctc_address" value="[ctc_address]" size=60 maxlength=128>
				</td>
				<td align="" valign="top"  bgcolor='#ffffff' width=190 ><sup class='suptitle'>&nbsp;SEX<BR>&nbsp;
				[CTC_GENDER]
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
				[CTC_CITIZENSHIP]
				</td>
			        <td align="" valign="center" bgcolor='#ffffff' class='normaltax'>ICR NO. ( If an alien )<BR> <input name="ctc_icr_no" value="[ctc_icr_no]" size=32 maxlength=128></td>
			        <td align="" valign="center" bgcolor='#ffffff' class='normaltax'>PLACE OF BIRTH<BR><input name="ctc_place_of_birth" value="[ctc_place_of_birth]" size=15 maxlength=128></td>
			        <td align="" valign="center" bgcolor='#ffffff' class='normaltax'>HEIGHT(cm)<BR><input name="ctc_height" value="[ctc_height]" size=5 maxlength=128></td>
			</tr>
			<tr>
				<td align="" valign="middle"  bgcolor='#ffffff' class='normaltax' colspan=2>CIVIL STATUS<BR>[CTC_CIVIL_STATUS]</td>
			        <td align="" valign="center"  bgcolor='#ffffff' class='normaltax'>DATE OF BIRTH<BR>[BIRTHDATE]</td>
			        <td align="" valign="center"  bgcolor='#ffffff' class='normaltax'>WEIGHT(kg)<BR><input name="ctc_weight" value="[ctc_weight]" size=5 maxlength=128></td>
			</tr>
			<tr>
				<td align="" valign="center"  bgcolor='#ffffff' class='normaltax' colspan=2>PROFESSION / OCCUPATION / BUSINESS<BR>
				<input name="ctc_occupation" value="[ctc_occupation]" size=60 maxlength=128>
				</td>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax'>TAXABLE AMOUNT</td>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax'>COMMUNITY TAX DUE</td>
			</tr>
			<tr>
				<td align="" valign="center"  bgcolor='#ffffff' class='normaltax' colspan=2>A. BASIC COMMUNITY TAX (Php5.00) Voluntary or Exempted (Php1.00)[CTC_TAX_EXEMPTED]</td>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax'></td>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax'><input type="text" class="ctc_amounttext"  name="ctc_basic_tax" readonly value="[ctc_basic_tax]" size=10 maxlength=30></td>
			</tr>
			<tr>
				<td align="" valign="center"  bgcolor='#ffffff' class='normaltax' colspan=2>B. ADDITIONAL COMMUNITY TAX ( tax not to exceed Php5,000.00) </td>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax'></td>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax'></td>
			</tr>
			<tr>
				<td align="" valign="center"  bgcolor='#ffffff' class='normaltax' colspan=2>1  GROSS RECEIPTS OR EARNINGS DERIVED FROM BUSINESS DURING THE PRECEDING YEAR( Php1.00 for every Php1,000.00)</td>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax'><input type="text" class="ctc_amounttext" name="ctc_additional_tax1" size=10 maxlength=30 value="[ctc_additional_tax1]"></td>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax'><input type="text" class="ctc_amounttext" name="ctc_additional_tax1_due" readonly size=10 maxlength=30 value="[ctc_additional_tax1_due]"></td>
			</tr>
			<tr>
				<td align="" valign="center"  bgcolor='#ffffff' class='normaltax' colspan=2>2  SALARIES OR GROSS RECEIPT OR EARNINGS DERIVED FROM EXERCISE OF PROFESSION OR PURSUIT OF ANY OCCUPATION (Php1.00 for every Php1,000.00)</td>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax'><input type="text" class="ctc_amounttext" name="ctc_additional_tax2" size=10 maxlength=30  value="[ctc_additional_tax2]"></td>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax'><input type="text" class="ctc_amounttext" name="ctc_additional_tax2_due" readonly size=10 maxlength=30 value="[ctc_additional_tax2_due]"></td>
			</tr>
			<tr>
				<td align="" valign="center"  bgcolor='#ffffff' class='normaltax' colspan=2>3  INCOME FROM REAL PROPERTY (Php1.00 for every Php1,000.00)</td>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax'><input type="text" class="ctc_amounttext" name="ctc_additional_tax3" size=10 maxlength=30  value="[ctc_additional_tax3]"></td>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax'><input type="text" class="ctc_amounttext" name="ctc_additional_tax3_due" readonly size=10 maxlength=30 value="[ctc_additional_tax3_due]"></td>
			</tr>
			<tr>
				<td align="" valign="top"  bgcolor='#ffffff' class='normaltax' rowspan=4><!--Right Thumb Print--></td>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax' rowspan=2>
			        <!--<BR>------------------------------------------<BR>Tax Payer's Signature<BR><BR>--></td>
			        <td align="" valign="center"  bgcolor='#ffffff' class='normaltax'>TOTAL(Php)</td>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax'><input type="text"  class="ctc_total_amounttext" name="ctc_total_amount_due" readonly size=10 maxlength=30></td>
			</tr>
			<tr>


			        <td align="" valign="center"  bgcolor='#ffffff' class='normaltax'>Interest(Php)</td>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax'><input type="text"  class="ctc_amounttext" name="ctc_total_interest_due" readonly size=10 maxlength=30></td>
			</tr>
			<tr>

			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax' rowspan=2><!--<BR>------------------------------------------<BR>Tax Payer's Signature<BR><BR>--></td>
			        <td align="" valign="center"  bgcolor='#ffffff' class='normaltax'>Total Amount Paid(Php)</td>
			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax'><input type="text"  class="ctc_total_amounttext" name="ctc_total_paid" readonly size=10 maxlength=30></td>
			</tr>
			<tr>


			        <td align="center" valign="center"  bgcolor='#ffffff' class='normaltax' colspan=2><!--(in Words)--><BR></td>
			</tr>
		</table>
	</td>
</tr>
</table>
<!--// end of the formating CTC //-->
