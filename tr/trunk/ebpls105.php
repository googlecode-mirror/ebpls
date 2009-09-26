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

$clsCTC 		= new EBPLSCTC ( $dbLink, $ctcDebug );

//--- search for renewal			
$clsCTC->load(trim($comm_tax_cert_code));
$ctcData 	=  $clsCTC->getData();


?>
<link rel="stylesheet" href="stylesheets/default.css" type="text/css"/>
<script language='Javascript' src='javascripts/default.js'></script>
<div align="CENTER">
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
				&nbsp;<input type='button' value=' P R I N T ' name='_PRINT' onClick='javascript:window.print();' >
			</td>
		</tr>
    </table>
<!--// end of the formating CTC //--> 
</div>
