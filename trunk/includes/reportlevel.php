<?
//$user_id = $ThUserData['id'];
                                                                                               
//get sub levels
                                                                                                 
$getsl = mysql_query("SELECT b.* FROM ebpls_user_sublevel_listings a,
                        ebpls_user_sublevel b  WHERE a.user_id = '$user_id'
                        and a.sublevel_id  = b.id");
                     
        while ($getd = mysql_fetch_row($getsl)) {
                                                                                                 
                        if ($getd[0]=='Reports Manager') {
				$rmlevel = 1;
                                        if (trim($getd[1])=='Business') {
											$brm1 = 1;
											
											if (trim($getd[2])=='Blacklisted Business Establishment') {
											$brm2 = 1;
											}
											
											if (trim($getd[2])=='Business Masterlist') {
											$brm3 = 1;
											}
											
											if (trim($getd[2])=='Business Permit') {
											$brm4 = 1;
											}
											
											if (trim($getd[2])=='Exempted Business Establishment') {
											$brm5 = 1;
											}
											
											if (trim($getd[2])=='List of Business Requirement Delinquent') {
											$brm6 = 1;
											}
											
											if (trim($getd[2])=='Top Business Establishment') {
											$brm7 = 1;
											}
											
											if (trim($getd[2])=='Business Establishment Comparative') {
											$brm8 = 1;
											}
											
											if (trim($getd[2])=='List of Establishment Without Permit') {
											$brm9 = 1;
											}
											
											if (trim($getd[2])=='List of Establishment') {
											$brm10 = 1;
											}
											
											if (trim($getd[2])=='Business Profile') {
											$brm11 = 1;
											}
										}
										
										
										 if (trim($getd[1])=='Motorized') {
											$mpl1 = 1;
											
											if (trim($getd[2])=='Masterlist of Motorized Vehicles') {
											$mpl2 = 1;
											}
											
											if (trim($getd[2])=='Motorized Permit') {
											$mpl3 = 1;
											}
										}
										
										 if (trim($getd[1])=='Franchise') {
											$fpl1 = 1;
											
											if (trim($getd[2])=='Franchise Permit') {
											$fpl2 = 1;
											}
											
											if (trim($getd[2])=='Franchise Permit') {
											$fpl3 = 1;
											}
										}
										
										 if (trim($getd[1])=='Occupational') {
											$ocl1 = 1;
											
											if (trim($getd[2])=='Occupational Permit') {
											$ocl2 = 1;
											}
											
											if (trim($getd[2])=='Occupational Registry') {
											$ocl3 = 1;
											}
										}
										
										 if (trim($getd[1])=='Peddler') {
											$ppl1 = 1;
											
											if (trim($getd[2])=='Peddler Masterlist') {
											$ppl2 = 1;
											}
											
											if (trim($getd[2])=='Peddlers Permit') {
											$ppl3 = 1;
											}
										}
										
										 if (trim($getd[1])=='Fishery') {
											$fil1 = 1;
											
											if (trim($getd[2])=='Fishery Permit') {
											$fil2 = 1;
											}
											
											if (trim($getd[2])=='Fishery Registry') {
											$fil3 = 1;
											}
										}
										
										 if (trim($getd[1])=='CTC') {
											$ctl1 = 1;
											
											if (trim($getd[2])=='CTC Business Application Masterlist') {
											$ctl2 = 1;
											}
											
											if (trim($getd[2])=='CTC Individual Application Masterlist') {
											$ctl3 = 1;
											}
										}
										
										if (trim($getd[1])=='System') {
											$sys1 = 1;
											
											if (trim($getd[2])=='Activity Log List') {
											$sys2 = 1;
											}
											
										}
										
										if (trim($getd[1])=='Collection') {
											$col1 = 1;
											
											if (trim($getd[2])=='Abstract of Collection') {
											$col2 = 1;
											}
											
											if (trim($getd[2])=='Comparative Annual Report') {
											$col3 = 1;
											}
											
											if (trim($getd[2])=='Comparative Quarterly Report') {
											$col4 = 1;
											}
											
											if (trim($getd[2])=='Master List of Tax Delinquent List') {
											$col5 = 1;
											}
											
											if (trim($getd[2])=='Notice of Business Tax Collection') {
											$col6 = 1;
											}
											
											if (trim($getd[2])=='Order of Payment') {
											$col7 = 1;
											}
											
											if (trim($getd[2])=='Collections Summary') {
											$col8 = 1;
											}
											if (trim($getd[2])=='Audit Trail') {
											$col9 = 1;
											}
											if (trim($getd[2])=='Abstract of CTC Issued') {
											$col10 = 1;
											} 
											if (trim($getd[2])=='Comparative Annual Graph') {
											$col11 = 1;
											}	
											
											
										}

		
			}
	
} // end while
