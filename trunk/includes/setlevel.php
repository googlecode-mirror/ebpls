<?
$user_id = $ThUserData['id'];
                                                                                                 
//get sub levels
                                                                                                 
$getsl = mysql_query("SELECT b.* FROM ebpls_user_sublevel_listings a,
                        ebpls_user_sublevel b  WHERE a.user_id = $user_id
                        and a.sublevel_id  = b.id");
                        
        while ($getd = mysql_fetch_row($getsl)) {
                                                                                                 
                        if ($getd[0]=='Settings') {
				$setlevel = 1;
                                        if (trim($getd[1])=='User Manager') {
						$uml = 1;
						
					}


					if (trim($getd[1])=='Activity Logs') {
                                                $acl = 1;
                                        }

					if (trim($getd[1])=='Color Scheme Preferences') {
                                                $cspl = 1;
                                        }
		
					if (trim($getd[1])=='System Settings') {
                                                $ssl = 1;
                                        }


			}
	
} // end while
