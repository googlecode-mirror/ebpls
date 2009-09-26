<?
$user_id = $ThUserData['id'];
                                                                                                 
//get sub levels
                                                                                                 
$getsl = mysql_query("SELECT b.* FROM ebpls_user_sublevel_listings a,
                        ebpls_user_sublevel b  WHERE a.user_id = $user_id
                        and a.sublevel_id  = b.id");
        while ($getd = mysql_fetch_row($getsl)) {
                                                                                        
                        if ($getd[0]=='Franchise Permit') {
				$pm = 1;
                                $fp = 1;
                               
                                        if (trim($getd[1])=='Application') {
	                                          
						$fpap = 1;
						$ssap = 1;
						if (trim($getd[2])=='New') {
							$fpapn = 1;
						}

						if (trim($getd[2])=='ReNew') {
                                                        $fpapr = 1;
                                                }
				
						if (trim($getd[2])=='Retire') {
                                                        $fpapt = 1;
                                                }
						 if (trim($getd[2])=='Search') {
                                                        $fpaps = 1;
                                                }

					}


					if (trim($getd[1])=='Payment') {
                                                $fpay = 1;
						$sspay = 1;
                                                if (trim($getd[2])=='New') {
                                                        $fpayn = 1;
                                                }
                                                                                                 
                                                if (trim($getd[2])=='ReNew') {
                                                        $fpayr = 1;
                                                }
                                                                                                 
                                                if (trim($getd[2])=='Retire') {
                                                        $fpayt = 1;
                                                }
		
						if (trim($getd[2])=='Search') {
                                                        $fpays = 1;
                                                }

                                        }

					if (trim($getd[1])=='Releasing') {
                                                $fpar = 1;
						$sspar = 1;
                                                if (trim($getd[2])=='New') {
                                                        $fparn = 1;
                                                }
                                                                                                 
                                                if (trim($getd[2])=='ReNew') {
                                                        $fparr = 1;
                                                }
                                                                                                 
                                                if (trim($getd[2])=='Retire') {
                                                        $fpart = 1;
                                                }

						if (trim($getd[2])=='Search') {
                                                        $fpars = 1;
                                                }

                                        }

			}
	
} // end while
