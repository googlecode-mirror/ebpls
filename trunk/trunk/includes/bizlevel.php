<?

                                                                                                 
//get sub levels
$getsl = mysql_query("SELECT b.* FROM ebpls_user_sublevel_listings a,
                        ebpls_user_sublevel b  WHERE a.user_id = $user_id
                        and a.sublevel_id  = b.id") or die (mysql_error());
        while ($getd = mysql_fetch_row($getsl)) {
                                                                                                 
                        if ($getd[0]=='Business Permit') {
				$pm = 1;
                                $bp = 1;
                                        if (trim($getd[1])=='Application') {
						$bpap = 1;
						$ssap = 1;
						if (trim($getd[2])=='New') {
							$bpapn = 1;
						}

						if (trim($getd[2])=='ReNew') {
                                                        $bpapr = 1;
                                                }
				
						if (trim($getd[2])=='Retire') {
                                                        $bpapt = 1;
                                                }
						 if (trim($getd[2])=='Search') {
                                                        $bpaps = 1;
                                                }

					}


					if (trim($getd[1])=='Assessment') {
                                                $bpas = 1;
						$ssass = 1;
                                                if (trim($getd[2])=='New') {
                                                        $bpasn = 1;
                                                }
                                                                                                 
                                                if (trim($getd[2])=='ReNew') {
                                                        $bpasr = 1;
                                                }
                                                                                                 
                                                if (trim($getd[2])=='Retire') {
                                                        $bpast = 1;
                                                }
						 if (trim($getd[2])=='Search') {
                                                        $bpass = 1;
                                                }

                                        }

					if (trim($getd[1])=='Approval') {
                                                $bpapp = 1;
						$ssapp = 1;
                                                if (trim($getd[2])=='New') {
                                                        $bpappn = 1;
                                                }
                                                                                                 
                                                if (trim($getd[2])=='ReNew') {
                                                        $bpappr = 1;
                                                }
                                                                                                 
                                                if (trim($getd[2])=='Retire') {
                                                        $bpappt = 1;
                                                }
							
						 if (trim($getd[2])=='Search') {
                                                        $bpapps = 1;
                                                }

                                        }

					if (trim($getd[1])=='Payment') {
                                                $bpay = 1;
						$sspay = 1;
                                                if (trim($getd[2])=='New') {
                                                        $bpayn = 1;
                                                }
                                                                                                 
                                                if (trim($getd[2])=='ReNew') {
                                                        $bpayr = 1;
                                                }
                                                                                                 
                                                if (trim($getd[2])=='Retire') {
                                                        $bpayt = 1;
                                                }
		
						if (trim($getd[2])=='Search') {
                                                        $bpays = 1;
                                                }

                                        }

					if (trim($getd[1])=='Releasing') {
                                                $bpar = 1;
						$ssar = 1;
                                                if (trim($getd[2])=='New') {
                                                        $bparn = 1;
                                                }
                                                                                                 
                                                if (trim($getd[2])=='ReNew') {
                                                        $bparr = 1;
                                                }
                                                                                                 
                                                if (trim($getd[2])=='Retire') {
                                                        $bpart = 1;
                                                }

						if (trim($getd[2])=='Search') {
                                                        $bpars = 1;
                                                }

                                        }

			}
	
} // end while
