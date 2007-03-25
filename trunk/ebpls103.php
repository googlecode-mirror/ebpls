<?php
//	@eBPLS_PAGE_CTC_INPUT : ctc input page
//	-  will process the ctc from the criteria passed
require_once("ebpls-php-lib/ebpls.ctc.class.php");
require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");


define('CTC_PLACE_ISSSUED',"Pateros, Metro Manila");
define('CTC_INDIVIDUAL_CONST_AMT',5);
define('CTC_BUSINESS_CONST_AMT',  500);

//--- get connection from DB
$dbLink = get_db_connection();

//--- check first the method method_of_application NEW/RENEW
$is_ctc_renew = false;
$ctcDebug 	 = false;
$is_ctc_renew 	 = true;
$clsCTC 	 = new EBPLSCTC ( $dbLink, $ctcDebug );
if(! strcasecmp($method_of_application,'renew'))
{
			
			//--- search for renewal			
			$code_found 	= $clsCTC->load(trim($comm_tax_cert_code));
			$ctcData 	= $clsCTC->getData();
}
else
{
	//--- set to default
	$ctcData['comm_tax_cert_place_issued'] = CTC_PLACE_ISSSUED;
	
}


?>
<link rel="stylesheet" href="stylesheets/default.css" type="text/css"/>
<script language='Javascript' src='javascripts/default.js'></script>
<div align="CENTER">
<br>
<!---// start of the table //-->
<table border=0 cellspacing=0 cellpadding=0   width='520'>
		<tr><td align="center" valign="center" class='titleblue'  width='520'> Community Tax Certificate Application</td></tr>
		<tr><td align="center" valign="center" class='normal' height=10>&nbsp;</td></tr>
		<?php 
			if($is_ctc_renew and $code_found == -1)
			{
			   echo '<tr><td align="center" valign="center" class="errmsg" height=10><hr>NO RECORD FOUND for CTC Code :'.$comm_tax_cert_code.'<hr></td></tr>';
			}
			else
			{
		?>
		<tr>
			<td align="center" valign="center" class='title'>
			  <form name="_FRM" method="POST" action="<?php echo(getURI(eBPLS_PAGE_CTC_PROCESS)); ?>" onSubmit="return validate_ctc_application();">
					<input type='hidden' name='method_of_application' value='<?php echo $method_of_application ?>'>
					<table border=0 cellspacing=1 cellpadding=1 width='520'>
					<tr><td align="center" valign="top" class='subtitleblue' colspan=2 > Information</td></tr>
						<tr>
							<td align="right" valign="top" class='normal' colspan=2> &nbsp;

							</td>
						</tr>
						<tr>
							<td align="right" valign="top" class='normal' width=220>  
								  CTC Type :
							</td>
							<td align="left" valign="top" class='normal'>&nbsp;
								   	<select name='comm_tax_cert_type' class='select100'>
										<option value='INDIVIDUAL' <?php echo (! strcasecmp($ctcData['comm_tax_cert_type'],'INDIVIDUAL')) ? ('selected') : (''); ?> >INDIVIDUAL</option>
										<option value='BUSINESS' <?php echo (! strcasecmp($ctcData['comm_tax_cert_type'],'BUSINESS')) ? ('selected') : (''); ?>>BUSINESS</option>
									</select>
							</td>
						</tr>
						<tr>
							<td align="right" valign="top" class='normal' width=220>  
								  Code :
							</td>
							<td align="left" valign="top" class='normal'>&nbsp;
									<input type='text' name='comm_tax_cert_code' maxlength=20 class='text180' disabled value="<?php echo $ctcData['comm_tax_cert_code'];?>">		
							</td>
						</tr>
						<tr>
							<td align="right" valign="top" class='normal'>
								  First Name :
							</td>
							<td align="left" valign="top" class='normal'>&nbsp;
										<input type='text' name='comm_tax_cert_owner_first_name' maxlength=60 class='text180' value="<?php echo $ctcData['comm_tax_cert_owner_first_name'];?>">
							</td>
						</tr>
						<tr>
							<td align="right" valign="top" class='normal'>
								  Middle Name :
							</td>
							<td align="left" valign="top" class='normal'>&nbsp;
										<input type='text' name='comm_tax_cert_owner_middle_name' maxlength=60 class='text180' value="<?php echo $ctcData['comm_tax_cert_owner_middle_name'];?>">
							</td>
						</tr>
						<tr>
							<td align="right" valign="top" class='normal'>
								  Last Name :
							</td>
							<td align="left" valign="top" class='normal'>&nbsp;
								<input type='text' name='comm_tax_cert_owner_last_name' maxlength=60 class='text180' value="<?php echo $ctcData['comm_tax_cert_owner_last_name'];?>">
							</td>
						</tr>
						<tr>
							<td align="right" valign="top" class='normal'>
								  Address :
							</td>
							<td align="left" valign="top" class='normal'>&nbsp;
								<input type='text' name='comm_tax_cert_owner_address' maxlength=60 class='text180' value="<?php echo $ctcData['comm_tax_cert_owner_address'];?>">

							</td>
						</tr>
						<tr>
							<td align="right" valign="top" class='normal'>
								  Birth Date :
								  <input type='hidden' name='comm_tax_cert_owner_birth_date' maxlength=10 class='text180' value="<?php echo $ctcData['comm_tax_cert_owner_birth_date'];?>">
							</td>
							<td align="left" valign="top" class='normal'>&nbsp;
									<?php set_form_date($ctcData['comm_tax_cert_owner_birth_date']) ?>
							</td>
						</tr>
						<tr>
							<td align="right" valign="top" class='normal'>
								  Place of Birth :
							</td>
							<td align="left" valign="top" class='normal'>&nbsp;
								<input type='text' name='comm_tax_cert_place_of_birth' maxlength=255 class='text180' value="<?php echo $ctcData['comm_tax_cert_place_of_birth'];?>">
							</td>
						</tr>
						

						<tr>
									<td align="right" valign="top" class='normal'>
										Gender :
									</td>
									<td align="left" valign="top" class='normal'>&nbsp;
											  <select name='comm_tax_cert_owner_gender' class='select100'>
												<option value='M' <?php echo (! strcasecmp($ctcData['comm_tax_cert_owner_gender'],'Male')) ? ('selected') : (''); ?> >M</option>
												<option value='F' <?php echo (! strcasecmp($ctcData['comm_tax_cert_owner_gender'],'Female')) ? ('selected') : (''); ?>>F</option>
											  </select>
									</td>
						</tr>
						<tr>
								<td align="right" valign="top" class='normal'>
									Civil Status :
								</td>
								<td align="left" valign="top" class='normal'>&nbsp;
										 <select name='comm_tax_cert_owner_civil_status'  class='select100'>
											<option value='single' <?php echo (! strcasecmp($ctcData['comm_tax_cert_owner_civil_status'],'single')) ? ('selected') : (''); ?>>Single</option>
											<option value='married' <?php echo (! strcasecmp($ctcData['comm_tax_cert_owner_civil_status'],'married')) ? ('selected') : (''); ?>>Married</option>
											<option value='widowed' <?php echo (! strcasecmp($ctcData['comm_tax_cert_owner_civil_status'],'widowed')) ? ('selected') : (''); ?>>Widowed</option>
											<option value='divorced' <?php echo (! strcasecmp($ctcData['comm_tax_cert_owner_civil_status'],'divorced')) ? ('selected') : (''); ?>>Divorced</option>
										  </select>
									
								</td>
						</tr>

						<tr>
							<td align="right" valign="top" class='normal'>
								  Last Year's Gross Income :
							</td>
							<td align="left" valign="top" class='normal'>&nbsp;
									<input type='text' name='comm_tax_cert_last_gross' maxlength=20 class='text180' value="<?php echo $ctcData['comm_tax_cert_last_gross'];?>" onBlur="calculate_10p_gross(<?php echo CTC_INDIVIDUAL_CONST_AMT.','.CTC_BUSINESS_CONST_AMT; ?>);">
							</td>
						</tr>

						<tr>
							<td align="right" valign="top" class='normal'>
								  Amount Due :
							</td>
							<td align="left" valign="top" class='normal'>&nbsp;
									<input type='text' name='comm_tax_cert_amount_due' maxlength=20 class='text180' value="<?php echo $ctcData['comm_tax_cert_amount_due'];?>">
							</td>
						</tr>

						<tr>
							<td align="right" valign="top" class='normal'>
								  Amount Paid :
							</td>
							<td align="left" valign="top" class='normal'>&nbsp;
								<input type='text' name='comm_tax_cert_amount_paid' maxlength=20 class='text180' value="<?php echo $ctcData['comm_tax_cert_amount_paid'];?>">
							</td>
						</tr>

						<tr>
							<td align="right" valign="top" class='normal'>
								  Account Code :
							</td>
							<td align="left" valign="top" class='normal'>&nbsp;
								 <input type='text' name='comm_tax_cert_acct_code' maxlength=20 class='text180' value="<?php echo $ctcData['comm_tax_cert_acct_code']; ?>">
							</td>
						</tr>

						<tr>
							<td align="right" valign="top" class='normal'>
								  Place Issued :
							</td>
							<td align="left" valign="top" class='normal'>&nbsp;
								<input type='text' name='comm_tax_cert_place_issued' maxlength=255 class='text180' value="<?php echo $ctcData['comm_tax_cert_place_issued'];?>">
							</td>
						</tr>

						<tr>
							<td align="right" valign="top" class='normal'>
								  TIN No :
							</td>
							<td align="left" valign="top" class='normal'>&nbsp;
								<input type='text' name='comm_tax_cert_tin_no' maxlength=100 class='text180' value="<?php echo $ctcData['comm_tax_cert_tin_no'];?>">
							</td>
						</tr>
						
						<tr>
							<td align="right" valign="top" class='normal'>
								  Height :
							</td>
							<td align="left" valign="top" class='normal'>&nbsp;
								<input type='text' name='comm_tax_cert_height' maxlength=20 class='text180' value="<?php echo $ctcData['comm_tax_cert_height'];?>">
							</td>
						</tr>	
						<tr>
							<td align="right" valign="top" class='normal'>
								  Weight :
							</td>
							<td align="left" valign="top" class='normal'>&nbsp;
								<input type='text' name='comm_tax_cert_weight' maxlength=20 class='text180' value="<?php echo $ctcData['comm_tax_cert_weight'];?>">
							</td>
						</tr>	
						<tr>
							<td align="right" valign="top" class='normal'>
								  Citizenship :
							</td>
							<td align="left" valign="top" class='normal'>&nbsp;
								<input type='text' name='comm_tax_cert_citizenship' maxlength=100 class='text180' value="<?php echo $ctcData['comm_tax_cert_citizenship'];?>">
							</td>
						</tr>
						<tr>
							<td align="right" valign="top" class='normal'>
								  Occupation :
							</td>
							<td align="left" valign="top" class='normal'>&nbsp;
								<input type='text' name='comm_tax_cert_occupation' maxlength=100 class='text180' value="<?php echo $ctcData['comm_tax_cert_occupation'];?>">
							</td>
						</tr>	
						<tr>
							<td align="right" valign="top" class='normal'>
								  ICR No :
							</td>
							<td align="left" valign="top" class='normal'>&nbsp;
								<input type='text' name='comm_tax_cert_icr_no' maxlength=100 class='text180' value="<?php echo $ctcData['comm_tax_cert_icr_no'];?>">
							</td>
						</tr>		
						<tr>
							<td align="right" valign="top" class='normal'>
								  Date Issued :
							</td>
							<td align="left" valign="top" class='normal'>&nbsp;
									<input type='text' name='comm_tax_cert_date_issued' maxlength=10 class='text180' value="<?php echo $ctcData['comm_tax_cert_date_issued'];?>" disabled>&nbsp;
							</td>
						</tr>
						<tr>
								<td align="right" valign="top" class='normal' colspan=2> &nbsp;

								</td>
						</tr>
						<tr>
								<td align="center" valign="top" class='normal' colspan=2>
										&nbsp;<input type='BUTTON' name='_BACK' onClick='javascript:history.go(-1)' value='B A C K'>
										&nbsp;<input type='SUBMIT' name='_PROCESS' onClick='' value='P R O C E S S'>
										&nbsp;<input type='reset' name='_RESET' onClick='' value='R E S E T' >
								</td>
						</tr>

				</table>
				</form>
			</td>
		</tr>
		<?php
		} //--- end of else
		?>
		<tr><td align="center" valign="center" class='title' height=10></td></tr>
</table>
<!---// end of the table //-->
</form>
</div>

<?php

//--- make a script that will calculate the tax
$clsCTC->printCTCScript('_FRM.comm_tax_cert_last_gross', 
			'_FRM.comm_tax_cert_amount_due',
			'_FRM.comm_tax_cert_amount_paid' 
			);

?>