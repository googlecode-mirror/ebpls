<?
$user_id = $ThUserData['id'];
                                                                                                 
//get sub levels
                                                                                                 
$getsl = mysql_query("SELECT b.* FROM ebpls_user_sublevel_listings a,
                        ebpls_user_sublevel b  WHERE a.user_id = $user_id
                        and a.sublevel_id  = b.id");
        while ($getd = mysql_fetch_row($getsl)) {
                                                                                                 
                        if ($getd[0]=='Fishery Permit') {
				$pm = 1;
                                $ip = 1;
                                        if (trim($getd[1])=='Application') {
						$ipap = 1;
						$ssap = 1;
						if (trim($getd[2])=='New') {
							$ipapn = 1;
						}

						if (trim($getd[2])=='ReNew') {
                                                        $ipapr = 1;
                                                }
				
						if (trim($getd[2])=='Retire') {
                                                        $ipapt = 1;
                                                }
						 if (trim($getd[2])=='Search') {
                                                        $ipaps = 1;
                                                }

					}


					if (trim($getd[1])=='Payment') {
                                                $ipay = 1;
						$sspay = 1;
                                                if (trim($getd[2])=='New') {
                                                        $ipayn = 1;
                                                }
                                                                                                 
                                                if (trim($getd[2])=='ReNew') {
                                                        $ipayr = 1;
                                                }
                                                                                                 
                                                if (trim($getd[2])=='Retire') {
                                                        $ipayt = 1;
                                                }
		
						if (trim($getd[2])=='Search') {
                                                        $ipays = 1;
                                                }

                                        }

					if (trim($getd[1])=='Releasing') {
                                                $ipar = 1;
						$ssar = 1;
                                                if (trim($getd[2])=='New') {
                                                        $iparn = 1;
                                                }
                                                                                                 
                                                if (trim($getd[2])=='ReNew') {
                                                        $iparr = 1;
                                                }
                                                                                                 
                                                if (trim($getd[2])=='Retire') {
                                                        $ipart = 1;
                                                }

						if (trim($getd[2])=='Search') {
                                                        $ipars = 1;
                                                }

                                        }

			}
	
} // end while
