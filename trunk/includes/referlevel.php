<?
$user_id = $ThUserData['id'];
                                                                                                 
//get sub levels
                                                                                                 
$getsl = mysql_query("SELECT b.* FROM ebpls_user_sublevel_listings a,
                        ebpls_user_sublevel b  WHERE a.user_id = $user_id
                        and a.sublevel_id  = b.id");
        while ($getd = mysql_fetch_row($getsl)) {
                                                                                                 
                        if ($getd[0]=='References') {
				$reflevel = 1;
                                        if (trim($getd[1])=='General Settings') {
						$gsl = 1;
					}

					if (trim($getd[1])=='Chart of Accounts') {
                                                $coal = 1;
                                        }
					

					if (trim($getd[1])=='Other Permit Fees') {
                                                $opfl = 1;
						$idd = 'ebpls_notherfees';
                                        }
					if (trim($getd[1])=='Fishery Permit Fees') {
                                                $fpfl = 1;
                                       		$opfl = 1;
					//	$idd = ''; 
						if (trim($getd[2])=='Boat Fee') {
                                                $bfl = 1;
						}

						if (trim($getd[2])=='Fish Activities Fee') {
                                                $fcl = 1;
						}

					}

					if (trim($getd[1])=='LGU Listings') {
                                                $lll = 1;
                                        }

					if (trim($getd[1])=='Zip Codes') {
                                                $zcl = 1;
                                        }
                                                                                                 
                                        if (trim($getd[1])=='Province Codes') {
                                                $pcl = 1;
                                        }

					if (trim($getd[1])=='District Codes') {
                                                $dcl = 1;
                                        }
                                                                                                 
                                        if (trim($getd[1])=='Barangay Listings') {
                                                $bll = 1;
                                        }

					if (trim($getd[1])=='Zone Listings') {
                                                $zll = 1;
                                        }

					if (trim($getd[1])=='FAQ') {
                                                $faql = 1;
                                        }
		
					if (trim($getd[1])=='Links') {
                                                $linkl = 1;
                                        }


                                                                                                 
                                        if (trim($getd[1])=='Ownership Types') {
                                                $otl = 1;
                                        }

					if (trim($getd[1])=='Penalty Settings') {
                                                $psl = 1;
                                        }
                                                                                                 
                                        if (trim($getd[1])=='Report Signatories') {
                                                $rsl = 1;
                                        }

					if (trim($getd[1])=='Occupancy Type') {
                                                $occutl = 1;
                                        }

					if (trim($getd[1])=='Industry Sector') {
                                                $iscl = 1;
                                        }

                                        
					if (trim($getd[1])=='CTC Settings') {
                                                $ctcsl = 1;
                                        }
                                                                                                 
                                        if (trim($getd[1])=='Pemit Number Format') {
                                                $pnfl = 1;
                                        }
                                                                                                 
                                        if (trim($getd[1])=='Announcement') {
                                                $anbl = 1;
                                        }

					if (trim($getd[1])=='Citizenship') {
                                                $citi = 1;
                                        }
					
					if (trim($getd[1])=='Lot Pin') {
                                                $lotpl = 1;
                                        }

					
                                                         
                                        if (trim($getd[1])=='Business Permit') {
                                                $brpl = 1;
					
					
                                        	if (trim($getd[2])=='Tax/Fee/Other Charges') {
                                                	$tfol = 1;
                                        	}
                                                                                                 
                                        	if (trim($getd[2])=='Business Nature') {
                                                	$bnl = 1;
                                        	}
 						
						if (trim($getd[2])=='Business Requirements') {
                                                        $brl = 1;
                                                }
					}

			}
	
} // end while
