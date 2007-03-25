<?
$user_id = $ThUserData['id'];
                                                                                                 
//get sub levels
                                                                                                 
$getsl = mysql_query("SELECT b.* FROM ebpls_user_sublevel_listings a,
                        ebpls_user_sublevel b  WHERE a.user_id = $user_id
                        and a.sublevel_id  = b.id");
        while ($getd = mysql_fetch_row($getsl)) {
                                                                                                 
                        if ($getd[0]=='Peddlers Permit') {
				$pm = 1;
                                $pp = 1;
                                        if (trim($getd[1])=='Application') {
						$ppap = 1;
						$ssap = 1;
						if (trim($getd[2])=='New') {
							$ppapn = 1;
						}

						if (trim($getd[2])=='ReNew') {
                                                        $ppapr = 1;
                                                }
				
						if (trim($getd[2])=='Retire') {
                                                        $ppapt = 1;
                                                }
						 if (trim($getd[2])=='Search') {
                                                        $ppaps = 1;
                                                }

					}


					if (trim($getd[1])=='Payment') {
                                                $ppay = 1;
						$sspay = 1;
                                                if (trim($getd[2])=='New') {
                                                        $ppayn = 1;
                                                }
                                                                                                 
                                                if (trim($getd[2])=='ReNew') {
                                                        $ppayr = 1;
                                                }
                                                                                                 
                                                if (trim($getd[2])=='Retire') {
                                                        $ppayt = 1;
                                                }
		
						if (trim($getd[2])=='Search') {
                                                        $ppays = 1;
                                                }

                                        }

					if (trim($getd[1])=='Releasing') {
                                                $ppar = 1;
						$ssar = 1;
                                                if (trim($getd[2])=='New') {
                                                        $pparn = 1;
                                                }
                                                                                                 
                                                if (trim($getd[2])=='ReNew') {
                                                        $pparr = 1;
                                                }
                                                                                                 
                                                if (trim($getd[2])=='Retire') {
                                                        $ppart = 1;
                                                }

						if (trim($getd[2])=='Search') {
                                                        $ppars = 1;
                                                }

                                        }

			}
	
} // end while
