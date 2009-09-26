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
				&nbsp;<input type='button' value=' P R I N T ' name='_PRINT' onClick='javascript:window.print();' >
			</td>
		</tr>
	</table>
<!--// end of the formating CTC //-->
</div>