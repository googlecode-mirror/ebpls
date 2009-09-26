<?php
//	@eBPLS_PAGE_CTC_PROCESS : ctc process page
//	-  will process the ctc from the criteria passed
require_once("ebpls-php-lib/ebpls.ctc.class.php");
require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");

		
		

define('CTC_INDIVIDUAL_CONST_AMT'	,5.00);
define('CTC_BUSINESS_CONST_AMT'		,500.00);
define('CTC_PERCENTAGE'			,0.001);

//--- get connection from DB
$dbLink = get_db_connection();
global $ThUserData;
$debug 	= false;





$comm_tax_cert_owner_first_name 	= trim($comm_tax_cert_owner_first_name);
$comm_tax_cert_owner_middle_name 	= trim($comm_tax_cert_owner_middle_name);
$comm_tax_cert_owner_last_name 		= trim($comm_tax_cert_owner_last_name);
$comm_tax_cert_owner_birth_date 	= trim($comm_tax_cert_owner_birth_date);	
$comm_tax_cert_owner_address 		= trim($comm_tax_cert_owner_address);	
$comm_tax_cert_owner_gender 		= trim($comm_tax_cert_owner_gender);	
$comm_tax_cert_owner_civil_status 	= trim($comm_tax_cert_owner_civil_status);
$comm_tax_cert_last_gross 		= trim($comm_tax_cert_last_gross);
$comm_tax_cert_amount_due 		= trim($comm_tax_cert_amount_due);
$comm_tax_cert_amount_paid 		= trim($comm_tax_cert_amount_paid);
$comm_tax_cert_acct_code 		= trim($comm_tax_cert_acct_code);
$comm_tax_cert_place_issued 		= trim($comm_tax_cert_place_issued);
$comm_tax_cert_type 			= trim($comm_tax_cert_type);
$comm_tax_cert_tin_no			= trim($comm_tax_cert_tin_no);

$comm_tax_cert_place_of_birth		= trim($comm_tax_cert_place_of_birth);
$comm_tax_cert_height			= trim($comm_tax_cert_height);
$comm_tax_cert_weight			= trim($comm_tax_cert_weight);
$comm_tax_cert_citizenship		= trim($comm_tax_cert_citizenship);
$comm_tax_cert_occupation		= trim($comm_tax_cert_occupation);
$comm_tax_cert_icr_no			= trim($comm_tax_cert_icr_no);

//--- calculate the tax
$sub_total = number_format( ( ($comm_tax_cert_last_gross * CTC_PERCENTAGE) + 
			      ( (! strcasecmp($comm_tax_cert_type,'individual')) ? (CTC_INDIVIDUAL_CONST_AMT) : (CTC_BUSINESS_CONST_AMT) ) ), 
			    2, '.', '');
			    
$comm_tax_cert_amount_due 		= $sub_total;
$comm_tax_cert_amount_paid 		= $sub_total;

// insert ctc							
$clsCTC 		= new EBPLSCTC ( $dbLink, $debug );

$clsCTC->setData(COMM_TAX_CERT_OWNER_FIRST_NAME,$comm_tax_cert_owner_first_name);
$clsCTC->setData(COMM_TAX_CERT_OWNER_MIDDLE_NAME,$comm_tax_cert_owner_middle_name);
$clsCTC->setData(COMM_TAX_CERT_OWNER_LAST_NAME,$comm_tax_cert_owner_last_name);
$clsCTC->setData(COMM_TAX_CERT_OWNER_BIRTH_DATE,$comm_tax_cert_owner_birth_date);
$clsCTC->setData(COMM_TAX_CERT_OWNER_ADDRESS,$comm_tax_cert_owner_address);
$clsCTC->setData(COMM_TAX_CERT_OWNER_GENDER,$comm_tax_cert_owner_gender);
$clsCTC->setData(COMM_TAX_CERT_OWNER_CIVIL_STATUS,$comm_tax_cert_owner_civil_status);
$clsCTC->setData(COMM_TAX_CERT_LAST_GROSS,$comm_tax_cert_last_gross);
$clsCTC->setData(COMM_TAX_CERT_AMOUNT_DUE,$comm_tax_cert_amount_due);
$clsCTC->setData(COMM_TAX_CERT_AMOUNT_PAID,$comm_tax_cert_amount_paid);
$clsCTC->setData(COMM_TAX_CERT_ACCT_CODE,$comm_tax_cert_acct_code);
$clsCTC->setData(COMM_TAX_CERT_PLACE_ISSUED,$comm_tax_cert_place_issued);
$clsCTC->setData(COMM_TAX_CERT_TYPE,$comm_tax_cert_type);

$clsCTC->setData(COMM_TAX_CERT_OWNER_TINNO,$comm_tax_cert_tin_no);
$clsCTC->setData(COMM_TAX_CERT_OWNER_PLACE_OF_BIRTH,$comm_tax_cert_place_of_birth);
$clsCTC->setData(COMM_TAX_CERT_OWNER_HEIGHT,$comm_tax_cert_height);
$clsCTC->setData(COMM_TAX_CERT_OWNER_WEIGHT,$comm_tax_cert_weight);
$clsCTC->setData(COMM_TAX_CERT_OWNER_CITIZENSHIP,$comm_tax_cert_citizenship);
$clsCTC->setData(COMM_TAX_CERT_OWNER_OCCUPATION,$comm_tax_cert_occupation);
$clsCTC->setData(COMM_TAX_CERT_OWNER_ICR_NO,$comm_tax_cert_icr_no);
			
			
			
			

?>
<link rel="stylesheet" href="stylesheets/default.css" type="text/css"/>
<script language='Javascript' src='javascripts/default.js'></script>
<div align="CENTER">
<form name="_FRM" method="POST" action="<?php echo(getURI(eBPLS_PAGE_CTC_INPUT)); ?>">
<!---// start of the table //-->
<table border=0 cellspacing=0 cellpadding=0 width='520'>
		<tr><td align="center" valign="center" class='titleblue'> Community Tax Certificate Application</td></tr>
		<tr><td align="center" valign="center" class='normal' height=10>&nbsp;</td></tr>
		<?php 
			// create will validate values set if ok
			if ( $clsCTC->create() < 0 ) 
			{
				print_r($clsCTC->getError());
			   echo '<tr><td align="center" valign="center" class="normal" height=10><hr>ERROR ON CREATE. PLEASE CHECK VALUES<hr></td></tr>';
			}
			else
			{
				 
				//--- get the data
				$ctcData 	=  null;
				$ctcData 	=  $clsCTC->getData();
				if(! strcasecmp($comm_tax_cert_type ,'individual'))
				{
		?>
		<!---// start of displaying the individual     CTC  //-->	
		<tr>
			<td align="center" valign="center" class='title'>
				<!-- start of CTC formating //-->
				<table border=0 cellspacing=0 cellpadding=0  width='520'>
						<tr>
							<td align="center" valign="top" width=520>
								<table border=0 cellspacing=1 cellpadding=0  width=520 bgcolor='#202366'>
									<tr>
										<td align="center" valign="center"  bgcolor='#ffffff' width=240 class='normaltax' colspan=2>COMMUNITY TAX CERTIFICATE</td>
									        <td align="center" valign="center"  bgcolor='#ffffff' width=100 class='normalgray'>INDIVIDUAL</td>
										<td align="center" valign="center" width=180 height=20  bgcolor='#ffffff' class='normaltax'><?php echo $ctcData['comm_tax_cert_code'];?></td>
									</tr>
									<tr>
										<td align="center" valign="top"  bgcolor='#ffffff' width=50  ><pre><sup class='suptitle'>YEAR</sup><p><?php echo date("Y");?></p></pre></td>
										<td align="center" valign="top"  bgcolor='#ffffff' width=190 ><sup class='suptitle'>PLACE OF ISSUE ( City / Mun / Prov )</sup><?php echo $ctcData['comm_tax_cert_place_issued'];?></td>
										<td align="center" valign="top"  bgcolor='#ffffff' width=100 ><pre><sup class='suptitle'>DATE ISSUED</sup><p><?php echo $ctcData['comm_tax_cert_date_issued'];?></p></pre></td>
										<td align="center" valign="top" width=180 height=20  bgcolor='#ffffff' class='normaltax'>TAX PAYER'S COPY</td>
									</tr>
									<tr>
										<td align="left" valign="top"  bgcolor='#ffffff' width=340 colspan=3  ><pre><sup class='suptitle'>NAME    ( SURNAME )      ( FIRST )       (MIDDLE)</sup><p><?php echo $ctcData['comm_tax_cert_owner_last_name']. ' '. $ctcData['comm_tax_cert_owner_first_name'] . ',' . $ctcData['comm_tax_cert_owner_middle_name'];?></p></pre></td>
										<td align="left" valign="top" width=180 height=20  bgcolor='#ffffff' class='normaltax'><pre><sup class='suptitle'>TIN IF ANY</sup><p><?php echo $ctcData['comm_tax_cert_tin_no'];?></p></pre></td>
									</tr>
									<tr>
										<td align="left" valign="top"  bgcolor='#ffffff' width=340 colspan=3  ><pre><sup class='suptitle'>ADDRESS</sup><p><?php echo $ctcData['comm_tax_cert_owner_address'];?></p></pre></td>
										<td align="left" valign="top" width=180 height=30  bgcolor='#ffffff' class='normaltax'>
											<table border=0 cellspacing=0 cellpadding=0  width=180  height=30>
												<tr>
													<td align='center' valign='top' class='normaltax' width=40><sup class='suptitle'>SEX</sup></td>
													<td align='center' valign='top' class='normaltax' width=20><sup class='suptitle'><?php if(! strcasecmp($ctcData['comm_tax_cert_owner_gender'],'M') ) echo "<img src='images/blank.gif' border=1 width=5 height=5>"; else echo "<img src='images/blank.gif' border=0 width=5 height=5>";?>1</sup></td>
													<td align='left' valign='top' class='normaltax' width=120><sup class='suptitle'>MALE</sup></td>
												</tr>
												<tr>
													<td align='left' valign='top' class='normaltax' width=40></td>
													<td align='center' valign='top' class='normaltax' width=20><sup class='suptitle'><?php if(! strcasecmp($ctcData['comm_tax_cert_owner_gender'],'F') ) echo "<img src='images/blank.gif' border=1 width=5 height=5>"; else echo "<img src='images/blank.gif' border=0 width=5 height=5>";?>2</sup></td>
													<td align='left' valign='top' class='normaltax' width=120><sup class='suptitle'>FEMALE</sup></td>
												</tr>
											</table>

										</td>
									</tr>
									<tr>
										<td align="center" valign="top"  bgcolor='#ffffff' width=520 colspan=4>
										<table border=0 cellspacing=0 cellpadding=0  width=520  height=30>
											<tr>
												<td align='left' valign='top' class='normaltax' width=94><pre><sup class='suptitle'>CITIZENSHIP</sup><p><?php echo $ctcData['comm_tax_cert_citizenship'];?></p></pre></td>
												<td align='left' valign='top' class='normaltax' width=1 height=30 bgcolor='#202366'><img src='images/blank.gif' height=30 width=1></td>
												<td align='center' valign='top' class='normaltax' width=94><sup class='suptitle'>ICR NO. ( If an alien )</sup></td>
												<td align='left' valign='top' class='normaltax' width=1 height=30 bgcolor='#202366'><img src='images/blank.gif' height=30 width=1></td>
												<td align='left' valign='top' class='normaltax' width=260><pre><sup class='suptitle'>PLACE OF BIRTH</sup><p><?php echo $ctcData['comm_tax_cert_place_of_birth'];?></p></pre></td>
												<td align='left' valign='top' class='normaltax' width=1 height=30 bgcolor='#202366'><img src='images/blank.gif' height=30 width=1></td>
												<td align='left' valign='top' class='normaltax' width=69><pre><sup class='suptitle'>HEIGHT</sup><p><?php echo $ctcData['comm_tax_cert_height'];?></p></pre></td>
											</tr>
										</table
										</td>
									</tr>
									<tr>
										<td align="center" valign="top"  bgcolor='#ffffff' width=520 colspan=4>
										<table border=0 cellspacing=0 cellpadding=0  width=520  height=30>
											<tr>
												<td align='left' valign='top' class='normaltax' width=355>
													<table border=0 cellspacing=0 cellpadding=0  width=355>
														<tr>
															<td align='center' valign='top' width=50><sup class='suptitle'>CIVIL</sup></td>
															<td align='center' valign='top' width=15><sup class='suptitle'><?php if(! strcasecmp($ctcData['comm_tax_cert_owner_civil_status'],'single')) echo "<img src='images/blank.gif' border=1 width=5 height=5>"; else echo "<img src='images/blank.gif' border=0 width=5 height=5>";?>1</sup></td>
															<td align='left' valign='top' width=50><sup class='suptitle'>Single</sup></td>
															<td align='center' valign='top' width=15><sup class='suptitle'><?php if(! strcasecmp($ctcData['comm_tax_cert_owner_civil_status'],'widowed')) echo "<img src='images/blank.gif' border=1 width=5 height=5>"; else echo "<img src='images/blank.gif' border=0 width=5 height=5>";?>3</sup></td>
															<td align='left' valign='top' width=225><sup class='suptitle'> Widow / Widower / Legally Separated </sup></td>
														</tr>
														<tr>
															<td align='center' valign='top' width=50><sup class='suptitle'>STATUS</sup></td>
															<td align='center' valign='top' width=15><sup class='suptitle'><?php if(! strcasecmp($ctcData['comm_tax_cert_owner_civil_status'],'married')) echo "<img src='images/blank.gif' border=1 width=5 height=5>"; else echo "<img src='images/blank.gif' border=0 width=5 height=5>";?>2</sup></td>
															<td align='left' valign='top' width=50><sup class='suptitle'>Married</sup></td>
															<td align='center' valign='top' width=15><sup class='suptitle'><?php if(! strcasecmp($ctcData['comm_tax_cert_owner_civil_status'],'divorced')) echo "<img src='images/blank.gif' border=1 width=5 height=5>"; else echo "<img src='images/blank.gif' border=0 width=5 height=5>";?>4</sup></td>
															<td align='left' valign='top' width=225><sup class='suptitle'>Divorced</sup></td>
														</tr>
													</table>
												</td>
												<td align='left' valign='top' class='normaltax' width=1 height=30 bgcolor='#202366'><img src='images/blank.gif' height=30 width=1></td>
												<td align='center' valign='top' class='normaltax' width=94><pre><sup class='suptitle'>DATE OF BIRTH</sup><p><?php echo $ctcData['comm_tax_cert_owner_birth_date'];?></p></pre></td>
												<td align='left' valign='top' class='normaltax' width=1 height=30 bgcolor='#202366'><img src='images/blank.gif' height=30 width=1></td>
												<td align='left' valign='top' class='normaltax' width=69><pre><sup class='suptitle'>WEIGHT</sup><p><?php echo $ctcData['comm_tax_cert_weight'];?></p></pre></td>
											</tr>
										</table>
										</td>
									</tr>
									<tr>
										<td align="center" valign="top"  bgcolor='#ffffff' width=520 colspan=4>
										<table border=0 cellspacing=0 cellpadding=0  width=520  height=30>
											<tr>
												<td align='left' valign='top' class='normaltax' width=355><pre><sup class='suptitle'>PROFESSION / OCCUPATION / BUSINESS</sup><p><?php echo $ctcData['comm_tax_cert_occupation'];?></p></pre></td>
												<td align='left' valign='top' class='normaltax' width=1 height=30 bgcolor='#202366'><img src='images/blank.gif' height=30 width=1></td>
												<td align='center' valign='center' class='normaltax' width=94>TAXABLE AMOUNT</td>
												<td align='left' valign='top' class='normaltax' width=1 height=30 bgcolor='#202366'><img src='images/blank.gif' height=30 width=1></td>
												<td align='center' valign='center' class='normaltax' width=69>COMMUNITY TAX DUE</td>
											</tr>
										</table>
										</td>
									</tr>
									<tr>
										<td align="center" valign="top"  bgcolor='#ffffff' width=520 colspan=4>
										<table border=0 cellspacing=0 cellpadding=0  width=520>
											<tr>
												<td align='left' valign='center' class='normaltax2' width=355>A. BASIC COMMUNITY TAX (Php5.00) Voluntary or Exempted (Php1.00)
													</td>
												<td align='left' valign='top' class='normaltax' width=1 height=30 bgcolor='#202366'><img src='images/blank.gif' height=30 width=1></td>
												<td align='center' valign='center' class='normaltax' width=94 >&nbsp;</td>
												<td align='left' valign='top' class='normaltax' width=1 height=30 bgcolor='#202366'><img src='images/blank.gif' height=30 width=1></td>
												<td align='left' valign='center' class='normaltax' width=69><strong>Php</strong>&nbsp;<?php echo number_format(CTC_INDIVIDUAL_CONST_AMT,2,'.',''); ?></td>
											</tr>
										</table>
										</td>
									</tr>
									<tr>
										<td align="center" valign="top"  bgcolor='#ffffff' width=520 colspan=4>
										<table border=0 cellspacing=0 cellpadding=0  width=520>
											<tr>
												<td align='left' valign='center' class='normaltax2' width=355>B. ADDITIONAL COMMUNITY TAX ( tax not to exceed Php5,000.00)
													</td>
												<td align='left' valign='top' class='normaltax' width=1 height=30 bgcolor='#202366'><img src='images/blank.gif' height=30 width=1></td>
												<td align='center' valign='center' class='normaltax' width=94>&nbsp;</td>
												<td align='left' valign='top' class='normaltax' width=1 height=30 bgcolor='#202366'><img src='images/blank.gif' height=30 width=1></td>
												<td align='left' valign='center' class='normaltax' width=69>&nbsp;</td>
											</tr>
										</table>
										</td>
									</tr>
									<tr>
										<td align="center" valign="top"  bgcolor='#ffffff' width=520 colspan=4>
										<table border=0 cellspacing=0 cellpadding=0  width=520>
											<tr>
												<td align='left' valign='center' class='normaltax2' width=355>
													<table border=0 cellspacing=0 cellpadding=0  width=355>
														<tr>
															<td align='left' valign='center' class='normaltax2' width=15>1
															</td>
															<td align='left' valign='center' class='normaltax2' width=340>
																GROSS RECEIPTS OR EARNINGS DERIVED FROM BUSINESS
															</td>
														</tr>
														<tr>
															<td align='left' valign='center' class='normaltax2' width=15>
															</td>
															<td align='left' valign='center' class='normaltax2' width=340>
																DURING THE PRECEDING YEAR( Php1.00 for every Php1,000.00)
															</td>
														</tr>
													</table>

													</td>
												<td align='left' valign='top' class='normaltax' width=1 height=30 bgcolor='#202366'><img src='images/blank.gif' height=30 width=1></td>
												<td align='left' valign='center' class='normaltax' width=94 ><strong>Php</strong>&nbsp;<?php echo number_format($ctcData['comm_tax_cert_last_gross'],2,'.',''); ?></td>
												<td align='left' valign='top' class='normaltax' width=1 height=30 bgcolor='#202366'><img src='images/blank.gif' height=30 width=1></td>
												<td align='left' valign='center' class='normaltax' width=69 ><strong>Php</strong>&nbsp;<?php echo number_format($ctcData['comm_tax_cert_last_gross']*CTC_PERCENTAGE ,2,'.',''); ?></td>
											</tr>
										</table>
										</td>
									</tr>
									<tr>
										<td align="center" valign="top"  bgcolor='#ffffff' width=520 colspan=4>
										<table border=0 cellspacing=0 cellpadding=0  width=520>
											<tr>
												<td align='left' valign='center' class='normaltax2' width=355>
													<table border=0 cellspacing=0 cellpadding=0  width=355>
														<tr>
															<td align='left' valign='center' class='normaltax2' width=15>2
															</td>
															<td align='left' valign='center' class='normaltax2' width=340>
																SALARIES OR GROSS RECEIPT OR EARNINGS DERIVED FROM
															</td>
														</tr>
														<tr>
															<td align='left' valign='center' class='normaltax2' width=15>
															</td>
															<td align='left' valign='center' class='normaltax2' width=340>
																 EXERCISE OF PROFESSION OR PURSUIT OF ANY OCCUPATION (Php1.00 for every Php1,000.00)
															</td>
														</tr>
													</table>

													</td>
												<td align='left' valign='top' class='normaltax' width=1 height=30 bgcolor='#202366'><img src='images/blank.gif' height=30 width=1></td>
												<td align='left' valign='center' class='normaltax' width=94 ><strong></strong></td>
												<td align='left' valign='top' class='normaltax' width=1 height=30 bgcolor='#202366'><img src='images/blank.gif' height=30 width=1></td>
												<td align='left' valign='center' class='normaltax' width=69 >&nbsp;</td>
											</tr>
										</table>
										</td>
									</tr>
									<tr>
										<td align="center" valign="top"  bgcolor='#ffffff' width=520 colspan=4>
										<table border=0 cellspacing=0 cellpadding=0  width=520>
											<tr>
												<td align='left' valign='center' class='normaltax2' width=355>
													<table border=0 cellspacing=0 cellpadding=0  width=355>
														<tr>
															<td align='left' valign='center' class='normaltax2' width=15>3
															</td>
															<td align='left' valign='center' class='normaltax2' width=340>
																INCOME FROM REAL PROPERTY (Php1.00 for every Php1,000.00)
															</td>
														</tr>
													</table>

													</td>
												<td align='left' valign='top' class='normaltax' width=1 height=30 bgcolor='#202366'><img src='images/blank.gif' height=30 width=1></td>
												<td align='left' valign='center' class='normaltax' width=94 ></td>
												<td align='left' valign='top' class='normaltax' width=1 height=30 bgcolor='#202366'><img src='images/blank.gif' height=30 width=1></td>
												<td align='left' valign='center' class='normaltax' width=69 >&nbsp;</td>
											</tr>
										</table>
										</td>
									</tr>

									<tr>
										<td align="center" valign="top"  bgcolor='#ffffff' width=520 colspan=4>
										<table border=0 cellspacing=0 cellpadding=0  width=520>
											<tr>
												<td align='left' valign='top' class='normaltax2' width=355>
													<table border=0 cellspacing=0 cellpadding=0  width=355>
														<tr>
															<td align='left' valign='center' class='normaltax2'>
																<table border=0 cellspacing=0 cellpadding=0  width=355 height=72>
																   <tr><td align='center' valign='top' class='normaltax2' width=100><sup class='suptitle'>Right Thumb Print</sup></td>
																   <td align='left' valign='top' class='normaltax2' width=1 height=72 bgcolor='#202366'><img src='images/blank.gif' height=72 width=1></td>
																   <td align='left' valign='top' class='normaltax2' width=254>
																			<table border=0 cellspacing=0 cellpadding=0  width=254>
																			   <tr><td align='left' valign='top' class='normaltax2' width=254 colspan=3 height=34><sup class='suptitle'>Tax Payer's Signature</sup></td></tr>
																			   <tr><td align='left' valign='top' class='normaltax2' width=254 colspan=3 height=1 bgcolor='#202366'><img src='images/blank.gif' height=1 width=254></td></tr>
																			   <tr><td align='left' valign='top' class='normaltax2' width=254 colspan=3></td></tr>
																			   <tr><td align='left' valign='top' class='normaltax2' width=254 colspan=3 height=25></td></tr>
																			   <tr>
																				<td align='left' valign='top' class='normaltax2' width=27 height=></td>
																				<td align='left' valign='top' class='normaltax2' width=200 height=1 bgcolor='#202366'><img src='images/blank.gif' height=1 width=200></td>
																				<td align='left' valign='top' class='normaltax2' width=27 height=1></td>
																			   </tr>
																			   <tr><td align='center' valign='top' class='normaltax2' width=254 colspan=3 height=10><sup class='suptitle'>Municipal / City Treasurer</sup></td></tr>
																			</table>
																    </td>
																   </tr>
																</table>
															</td>
														</tr>
													</table>
												</td>
												<td align='left' valign='top' class='normaltax' width=1 height=30 bgcolor='#202366'><img src='images/blank.gif' height=30 width=1></td>
												<td align='left' valign='center' class='normaltax' width=164 >
													<table border=0 cellspacing=0 cellpadding=0  width=164>
															<tr>
																<td align='left' valign='center' class='normaltax' width=164 height=17>
																		<table border=0 cellspacing=0 cellpadding=0  width=164>
																			<tr><td align='center' valign='center' class='normaltax2' width=94 >TOTAL</td>
																				<td align='left' valign='top' class='normaltax' width=1 height=17 bgcolor='#202366'><img src='images/blank.gif' height=17 width=1></td>
																				<td align='left' valign='center' class='normaltax' width=69 ><strong>Php</strong></td>
																			</tr>
																			<tr><td align='left' valign='top' class='normaltax' width=164 height=1 bgcolor='#202366' colspan=3><img src='images/blank.gif' height=1 width=64></td>
																			</tr>
																		</table>

																</td>
															</tr>
															<tr>
																<td align='left' valign='center' class='normaltax' width=164 height=17>
																		<table border=0 cellspacing=0 cellpadding=0  width=164>
																			<tr><td align='center' valign='center' class='normaltax2' width=94 >INTEREST</td>
																				<td align='left' valign='top' class='normaltax' width=1 height=17 bgcolor='#202366'><img src='images/blank.gif' height=17 width=1></td>
																				<td align='left' valign='center' class='normaltax' width=69 >

																				</td>
																			</tr>
																			<tr><td align='left' valign='top' class='normaltax' width=164 height=1 bgcolor='#202366' colspan=3><img src='images/blank.gif' height=1 width=64></td>
																			</tr>
																		</table>

																</td>
															</tr>
															<tr>
																<td align='left' valign='center' class='normaltax' width=164 height=17>
																		<table border=0 cellspacing=0 cellpadding=0  width=164>
																			<tr><td align='center' valign='center' class='normaltax2' width=94 >TOTAL AMOUNT PAID</td>
																				<td align='left' valign='top' class='normaltax' width=1 height=17 bgcolor='#202366'><img src='images/blank.gif' height=17 width=1></td>
																				<td align='left' valign='center' class='normaltax' width=69 ><strong>Php</strong>&nbsp;<?php echo $ctcData['comm_tax_cert_amount_paid'];?></td>
																			</tr>
																			<tr><td align='left' valign='top' class='normaltax' width=164 height=1 bgcolor='#202366' colspan=3><img src='images/blank.gif' height=1 width=64></td>
																			</tr>
																		</table>

																</td>
															</tr>
															<tr><td align='left' valign='center' class='normaltax' width=164 height=18>
																	<sup class='suptitle'>(In Words):</sup>
																</td>
															</tr>
													</table>
												</td>
											</tr>
										</table>
										</td>
									</tr>



								</table>
							</td>
						</tr>
						<tr>
								<td align="center" valign="top" width='100%' height=50>&nbsp;

								</td>
						</tr>
						<tr>
							<td align="center" valign="top" width='100%'  >
								&nbsp;<input type='button' value=' P R I N T ' name='_PRINT' onClick='javascript:showNewWin("<?php echo "ebpls105.php?comm_tax_cert_code=".$ctcData['comm_tax_cert_code']?>",600,620);' >
							</td>
						</tr>
				    </table>
				    <!--// end of the formating CTC //-->   

			</td>
		</tr>
		
		<!---// end start of displaying the individual CTC  //-->
		<?php
			}//--- end of if individual
			else 
			{
			?>
			<!---// start of displaying the business/corporate CTC     //-->
			<tr>
			<td align="center" valign="center" class='title'>
			<!-- start of CTC formating //-->
			<table border=0 cellspacing=0 cellpadding=0  width='520'>
				<tr>
					<td align="center" valign="top" width=520>
						<table border=0 cellspacing=1 cellpadding=0  width=520 bgcolor='#202366'>
							<tr>
								<td align="center" valign="center"  bgcolor='#ffffff' width=240 class='normaltax' colspan=2>COMMUNITY TAX CERTIFICATE</td>
								<td align="center" valign="center"  bgcolor='#ffffff' width=100 class='normalgray'>BUSINESS</td>
								<td align="center" valign="center" width=180 height=20  bgcolor='#ffffff' class='normaltax'><?php echo $ctcData['comm_tax_cert_code'];?></td>
							</tr>
							<tr>
								<td align="center" valign="top"  bgcolor='#ffffff' width=50  ><pre><sup class='suptitle'>YEAR</sup><p><?php echo date("Y");?></p></pre></td>
								<td align="center" valign="top"  bgcolor='#ffffff' width=190 ><sup class='suptitle'>PLACE OF ISSUE ( City / Mun / Prov )</sup><?php echo $ctcData['comm_tax_cert_place_issued'];?></td>
								<td align="center" valign="top"  bgcolor='#ffffff' width=100 ><pre><sup class='suptitle'>DATE ISSUED</sup><p><?php echo $ctcData['comm_tax_cert_date_issued'];?></p></pre></td>
								<td align="center" valign="top" width=180 height=20  bgcolor='#ffffff' class='normaltax'>TAX PAYER'S COPY</td>
							</tr>
							<tr>
								<td align="left" valign="top"  bgcolor='#ffffff' width=340 colspan=3  ><pre><sup class='suptitle'>COMPANY'S FULLNAME</sup><p><?php echo $ctcData['comm_tax_cert_owner_last_name']. ' '. $ctcData['comm_tax_cert_owner_first_name'] . ',' . $ctcData['comm_tax_cert_owner_middle_name'];?></p></pre></td>
								<td align="left" valign="top" width=180 height=20  bgcolor='#ffffff' class='normaltax'><pre><sup class='suptitle'>TIN IF ANY</sup><p><?php echo $ctcData['comm_tax_cert_tin_no'];?></p></pre></td>
							</tr>
							<tr>
								<td align="left" valign="top"  bgcolor='#ffffff' width=340 colspan=3  ><pre><sup class='suptitle'>ADDRESS OF PRINCIPAL PLACE OF BUSINESS</sup><p><?php echo $ctcData['comm_tax_cert_owner_address'];?></p></pre></td>
								<td align="left" valign="top" width=180 height=30  bgcolor='#ffffff' class='normaltax'>
									<table border=0 cellspacing=0 cellpadding=0  width=180  height=30>
										<tr>
											<td align='center' valign='top' class='normaltax' width=40><sup class='suptitle'>DATE OF REG./INCORPORATION</sup></td>
										</tr>
									</table>
			
								</td>
							</tr>
							<tr>
								<td align="left" valign="top"  bgcolor='#ffffff' width=340 colspan=3 height=10>
								     <table border=0 cellspacing=0 cellpadding=0  height=10>
										<tr><td align="left" valign="top"  bgcolor='#ffffff' width=200>
											<table border=0 cellspacing=0 cellpadding=0  height=30>
												<tr><td align="left" valign="top"  bgcolor='#ffffff' width=200><pre><sup class='suptitle'>Kind of Organization</sup></pre></td>
													<td align='left' valign='top' class='normaltax' width=1 height=10 bgcolor='#202366'><img src='images/blank.gif' height=10 width=1></td>
													<td align="left" valign="top"  bgcolor='#ffffff' width=140>
														<table border=0 cellspacing=0 cellpadding=0  height=30>
															<tr><td align="left" valign="top"  bgcolor='#ffffff' width=10>&nbsp;</td><td align="left" valign="top"  bgcolor='#ffffff' width=130><sup class='suptitle'>1  Corporation</sup></td></tr>
															<tr><td align="left" valign="top"  bgcolor='#ffffff' width=10>&nbsp;</td><td align="left" valign="top"  bgcolor='#ffffff' width=130><sup class='suptitle'>2  Association</sup></td></tr>
															<tr><td align="left" valign="top"  bgcolor='#ffffff' width=10>&nbsp;</td><td align="left" valign="top"  bgcolor='#ffffff' width=130><sup class='suptitle'>3  Partnership</sup></td></tr>
														</table>
													</td>
												</tr>
											</table>
											</td>
											<td align='left' valign='top' class='normaltax' width=1 height=10 bgcolor='#202366'><img src='images/blank.gif' height=10 width=1></td>
											<td align="left" valign="top"  bgcolor='#ffffff' width=140><pre><sup class='suptitle'>PLACE OF INCORPORATION</sup></pre></td>
										</tr>
									</table>
								</td>
								<td align="left" valign="top" width=180 height=30  bgcolor='#ffffff' class='normaltax'></td>
							</tr>
			
							<tr>
								<td align="center" valign="top"  bgcolor='#ffffff' width=520 colspan=4>
								<table border=0 cellspacing=0 cellpadding=0  width=520  height=30>
									<tr>
										<td align='left' valign='top' class='normaltax' width=355><pre><sup class='suptitle'>KIND /  NATURE OF BUSINESS</sup><p><?php echo $ctcData['comm_tax_cert_occupation'];?></p></pre></td>
										<td align='left' valign='top' class='normaltax' width=1 height=30 bgcolor='#202366'><img src='images/blank.gif' height=30 width=1></td>
										<td align='center' valign='center' class='normaltax' width=94>TAXABLE AMOUNT</td>
										<td align='left' valign='top' class='normaltax' width=1 height=30 bgcolor='#202366'><img src='images/blank.gif' height=30 width=1></td>
										<td align='center' valign='center' class='normaltax' width=69>COMMUNITY TAX DUE</td>
									</tr>
								</table>
								</td>
							</tr>
							<tr>
								<td align="center" valign="top"  bgcolor='#ffffff' width=520 colspan=4>
								<table border=0 cellspacing=0 cellpadding=0  width=520>
									<tr>
										<td align='left' valign='center' class='normaltax2' width=355>A. BASIC COMMUNITY TAX (Php5000.00)
											</td>
										<td align='left' valign='top' class='normaltax' width=1 height=30 bgcolor='#202366'><img src='images/blank.gif' height=30 width=1></td>
										<td align='center' valign='center' class='normaltax' width=94 >&nbsp;</td>
										<td align='left' valign='top' class='normaltax' width=1 height=30 bgcolor='#202366'><img src='images/blank.gif' height=30 width=1></td>
										<td align='left' valign='center' class='normaltax' width=69><strong>Php</strong>&nbsp;<?php echo number_format(CTC_INDIVIDUAL_CONST_AMT,2,'.',''); ?></td>
									</tr>
								</table>
								</td>
							</tr>
							<tr>
								<td align="center" valign="top"  bgcolor='#ffffff' width=520 colspan=4>
								<table border=0 cellspacing=0 cellpadding=0  width=520>
									<tr>
										<td align='left' valign='center' class='normaltax2' width=355>B. ADDITIONAL COMMUNITY TAX ( tax not to exceed Php10,000.00)
											</td>
										<td align='left' valign='top' class='normaltax' width=1 height=30 bgcolor='#202366'><img src='images/blank.gif' height=30 width=1></td>
										<td align='center' valign='center' class='normaltax' width=94>&nbsp;</td>
										<td align='left' valign='top' class='normaltax' width=1 height=30 bgcolor='#202366'><img src='images/blank.gif' height=30 width=1></td>
										<td align='left' valign='center' class='normaltax' width=69>&nbsp;</td>
									</tr>
								</table>
								</td>
							</tr>
							<tr>
								<td align="center" valign="top"  bgcolor='#ffffff' width=520 colspan=4>
								<table border=0 cellspacing=0 cellpadding=0  width=520>
									<tr>
										<td align='left' valign='center' class='normaltax2' width=355>
											<table border=0 cellspacing=0 cellpadding=0  width=355>
												<tr>
													<td align='left' valign='center' class='normaltax2' width=15>1
													</td>
													<td align='left' valign='center' class='normaltax2' width=340>
														ASSESSED VALUE OF REAL PROPERTY OWNED IN THE PHILIPPINES (Php2.00 for every Php5,000.00)
													</td>
												</tr>
												<tr>
													<td align='left' valign='center' class='normaltax2' width=15>
													</td>
													<td align='left' valign='center' class='normaltax2' width=340>
														&nbsp;
													</td>
												</tr>
											</table>
			
											</td>
										<td align='left' valign='top' class='normaltax' width=1 height=30 bgcolor='#202366'><img src='images/blank.gif' height=30 width=1></td>
										<td align='left' valign='center' class='normaltax' width=94 ><strong>Php</strong>&nbsp;<?php echo number_format($ctcData['comm_tax_cert_last_gross'],2,'.',''); ?></td>
										<td align='left' valign='top' class='normaltax' width=1 height=30 bgcolor='#202366'><img src='images/blank.gif' height=30 width=1></td>
										<td align='left' valign='center' class='normaltax' width=69 ><strong>Php</strong>&nbsp;<?php echo number_format($ctcData['comm_tax_cert_last_gross']*CTC_PERCENTAGE ,2,'.',''); ?></td>
									</tr>
								</table>
								</td>
							</tr>
							<tr>
								<td align="center" valign="top"  bgcolor='#ffffff' width=520 colspan=4>
								<table border=0 cellspacing=0 cellpadding=0  width=520>
									<tr>
										<td align='left' valign='center' class='normaltax2' width=355>
											<table border=0 cellspacing=0 cellpadding=0  width=355>
												<tr>
													<td align='left' valign='center' class='normaltax2' width=15>2
													</td>
													<td align='left' valign='center' class='normaltax2' width=340>
														GROSS RECEIPTS INCLUDING DIVIDENDS/EARNINGS DERIVED FROM BUSINESS
													</td>
												</tr>
												<tr>
													<td align='left' valign='center' class='normaltax2' width=15>
													</td>
													<td align='left' valign='center' class='normaltax2' width=340>
														 IN THE PHIL DURING THE PRECEDING YEAR (Php2.00 for every Php5,000.00)
													</td>
												</tr>
											</table>
			
											</td>
										<td align='left' valign='top' class='normaltax' width=1 height=30 bgcolor='#202366'><img src='images/blank.gif' height=30 width=1></td>
										<td align='left' valign='center' class='normaltax' width=94 ><strong></strong></td>
										<td align='left' valign='top' class='normaltax' width=1 height=30 bgcolor='#202366'><img src='images/blank.gif' height=30 width=1></td>
										<td align='left' valign='center' class='normaltax' width=69 >&nbsp;</td>
									</tr>
								</table>
								</td>
							</tr>
							<tr>
								<td align="center" valign="top"  bgcolor='#ffffff' width=520 colspan=4>
								<table border=0 cellspacing=0 cellpadding=0  width=520>
									<tr>
										<td align='left' valign='top' class='normaltax2' width=355>
											<table border=0 cellspacing=0 cellpadding=0  width=355>
												<tr>
													<td align='left' valign='center' class='normaltax2'>
														<table border=0 cellspacing=0 cellpadding=0  width=355 height=72>
														   <!--tr><td align='center' valign='top' class='normaltax2' width=100><sup class='suptitle'>Right Thumb Print</sup></td
														   <td align='left' valign='top' class='normaltax2' width=1 height=72 bgcolor='#202366'><img src='images/blank.gif' height=72 width=1></td>//-->
														   <td align='left' valign='top' class='normaltax2' width=354>
																	<table border=0 cellspacing=0 cellpadding=0  width=354>
																	   <tr><td align='left' valign='top' class='normaltax2' width=354 colspan=3 height=34><sup class='suptitle'>SIGNATURE/POSITION OF AUTHORIZED OFFICER</sup></td></tr>
																	   <tr><td align='left' valign='top' class='normaltax2' width=354 colspan=3 height=1 bgcolor='#202366'><img src='images/blank.gif' height=1 width=254></td></tr>
																	   <tr><td align='left' valign='top' class='normaltax2' width=354 colspan=3></td></tr>
																	   <tr><td align='left' valign='top' class='normaltax2' width=354 colspan=3 height=25></td></tr>
																	   <tr>
																		<td align='left' valign='top' class='normaltax2' width=27 height=></td>
																		<td align='left' valign='top' class='normaltax2' width=200 height=1 bgcolor='#202366'><img src='images/blank.gif' height=1 width=200></td>
																		<td align='left' valign='top' class='normaltax2' width=27 height=1></td>
																	   </tr>
																	   <tr><td align='center' valign='top' class='normaltax2' width=254 colspan=3 height=10><sup class='suptitle'>Municipal / City Treasurer</sup></td></tr>
																	</table>
														    </td>
														   </tr>
														</table>
													</td>
												</tr>
											</table>
										</td>
										<td align='left' valign='top' class='normaltax' width=1 height=30 bgcolor='#202366'><img src='images/blank.gif' height=30 width=1></td>
										<td align='left' valign='center' class='normaltax' width=164 >
											<table border=0 cellspacing=0 cellpadding=0  width=164>
													<tr>
														<td align='left' valign='center' class='normaltax' width=164 height=17>
																<table border=0 cellspacing=0 cellpadding=0  width=164>
																	<tr><td align='center' valign='center' class='normaltax2' width=94 >TOTAL</td>
																		<td align='left' valign='top' class='normaltax' width=1 height=17 bgcolor='#202366'><img src='images/blank.gif' height=17 width=1></td>
																		<td align='left' valign='center' class='normaltax' width=69 ><strong>Php</strong></td>
																	</tr>
																	<tr><td align='left' valign='top' class='normaltax' width=164 height=1 bgcolor='#202366' colspan=3><img src='images/blank.gif' height=1 width=64></td>
																	</tr>
																</table>
			
														</td>
													</tr>
													<tr>
														<td align='left' valign='center' class='normaltax' width=164 height=17>
																<table border=0 cellspacing=0 cellpadding=0  width=164>
																	<tr><td align='center' valign='center' class='normaltax2' width=94 >INTEREST</td>
																		<td align='left' valign='top' class='normaltax' width=1 height=17 bgcolor='#202366'><img src='images/blank.gif' height=17 width=1></td>
																		<td align='left' valign='center' class='normaltax' width=69 >
			
																		</td>
																	</tr>
																	<tr><td align='left' valign='top' class='normaltax' width=164 height=1 bgcolor='#202366' colspan=3><img src='images/blank.gif' height=1 width=64></td>
																	</tr>
																</table>
			
														</td>
													</tr>
													<tr>
														<td align='left' valign='center' class='normaltax' width=164 height=17>
																<table border=0 cellspacing=0 cellpadding=0  width=164>
																	<tr><td align='center' valign='center' class='normaltax2' width=94 >TOTAL AMOUNT PAID</td>
																		<td align='left' valign='top' class='normaltax' width=1 height=17 bgcolor='#202366'><img src='images/blank.gif' height=17 width=1></td>
																		<td align='left' valign='center' class='normaltax' width=69 ><strong>Php</strong>&nbsp;<?php echo $ctcData['comm_tax_cert_amount_paid'];?></td>
																	</tr>
																	<tr><td align='left' valign='top' class='normaltax' width=164 height=1 bgcolor='#202366' colspan=3><img src='images/blank.gif' height=1 width=64></td>
																	</tr>
																</table>
			
														</td>
													</tr>
													<tr><td align='left' valign='center' class='normaltax' width=164 height=18>
															<sup class='suptitle'>(In Words):</sup>
														</td>
													</tr>
											</table>
										</td>
									</tr>
								</table>
								</td>
							</tr>
			
			
			
						</table>
					</td>
				</tr>
				<tr>
						<td align="center" valign="top" width='100%' height=50>&nbsp;
			
						</td>
				</tr>
				<tr>
					<td align="center" valign="top" width='100%'  >
						&nbsp;<input type='button' value=' P R I N T ' name='_PRINT' onClick='javascript:showNewWin("<?php echo "ebpls106.php?comm_tax_cert_code=".$ctcData['comm_tax_cert_code']?>",600,620);' >
					</td>
				</tr>
			</table>
			<!--// end of the formating CTC //-->
			
			</td>
		  </tr>
		<!---// end start of displaying the business/corporate CTC //-->
			
		<?php	
			}//--- end of else its corporate/business
		} //--- end of else
		?>
		<tr><td align="center" valign="center" class='title' height=10></td></tr>
</table>
<!---// end of the table //-->
</div>
