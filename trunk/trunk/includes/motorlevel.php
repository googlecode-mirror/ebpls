<?
$user_id = $ThUserData['id'];
                                                                                                 
//get sub levels
                                                                                                 
$getsl = mysql_query("SELECT b.* FROM ebpls_user_sublevel_listings a,
                        ebpls_user_sublevel b  WHERE a.user_id = $user_id
                        and a.sublevel_id  = b.id");
        while ($getd = mysql_fetch_row($getsl)) {
                                                                                                 
                        if ($getd[0]=='Motorized Operator Permit') {
				$pm = 1;
                                $mp = 1;
                                        if (trim($getd[1])=='Application') {
						$mpap = 1;
						$ssap = 1;
						if (trim($getd[2])=='New') {
							$mpapn = 1;
						}

						if (trim($getd[2])=='ReNew') {
                                                        $mpapr = 1;
                                                }
				
						if (trim($getd[2])=='Retire') {
                                                        $mpapt = 1;
                                                }
						 if (trim($getd[2])=='Search') {
                                                        $mpaps = 1;
                                                }

					}


					if (trim($getd[1])=='Payment') {
                                                $mpay = 1;
						$sspay=1;
                                                if (trim($getd[2])=='New') {
                                                        $mpayn = 1;
                                                }
                                                                                                 
                                                if (trim($getd[2])=='ReNew') {
                                                        $mpayr = 1;
                                                }
                                                                                                 
                                                if (trim($getd[2])=='Retire') {
                                                        $mpayt = 1;
                                                }
		
						if (trim($getd[2])=='Search') {
                                                        $mpays = 1;
                                                }

                                        }

					if (trim($getd[1])=='Releasing') {
                                                $mpar = 1;
						$ssar = 1;
                                                if (trim($getd[2])=='New') {
                                                        $mparn = 1;
                                                }
                                                                                                 
                                                if (trim($getd[2])=='ReNew') {
                                                        $mparr = 1;
                                                }
                                                                                                 
                                                if (trim($getd[2])=='Retire') {
                                                        $mpart = 1;
                                                }

						if (trim($getd[2])=='Search') {
                                                        $mpars = 1;
                                                }

                                        }

			}
	
} // end while
