<?
$user_id = $ThUserData['id'];
                                                                                                 
//get sub levels
                                                                                                 
$getsl = mysql_query("SELECT b.* FROM ebpls_user_sublevel_listings a,
                        ebpls_user_sublevel b  WHERE a.user_id = $user_id
                        and a.sublevel_id  = b.id");
        while ($getd = mysql_fetch_row($getsl)) {
                                                                                                 
                        if ($getd[0]=='Occupational Permit') {
				$pm = 1;
                                $op = 1;
                                        if (trim($getd[1])=='Application') {
						$opap = 1;
						$ssap = 1;
						if (trim($getd[2])=='New') {
							$opapn = 1;
						}

						if (trim($getd[2])=='ReNew') {
                                                        $opapr = 1;
                                                }
				
						if (trim($getd[2])=='Retire') {
                                                        $opapt = 1;
                                                }
						 if (trim($getd[2])=='Search') {
                                                        $opaps = 1;
                                                }

					}


					if (trim($getd[1])=='Payment') {
                                                $opay = 1;
						$sspay = 1;
                                                if (trim($getd[2])=='New') {
                                                        $opayn = 1;
                                                }
                                                                                                 
                                                if (trim($getd[2])=='ReNew') {
                                                        $opayr = 1;
                                                }
                                                                                                 
                                                if (trim($getd[2])=='Retire') {
                                                        $opayt = 1;
                                                }
		
						if (trim($getd[2])=='Search') {
                                                        $opays = 1;
                                                }

                                        }

					if (trim($getd[1])=='Releasing') {
                                                $opar = 1;
						$ssar = 1;
                                                if (trim($getd[2])=='New') {
                                                        $oparn = 1;
                                                }
                                                                                                 
                                                if (trim($getd[2])=='ReNew') {
                                                        $oparr = 1;
                                                }
                                                                                                 
                                                if (trim($getd[2])=='Retire') {
                                                        $opart = 1;
                                                }

						if (trim($getd[2])=='Search') {
                                                        $opars = 1;
                                                }

                                        }

			}
	
} // end while
