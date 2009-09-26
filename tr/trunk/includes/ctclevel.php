<?
$user_id = $ThUserData['id'];
                                                                                                 
//get sub levels
                                                                                                 
$getsl = mysql_query("SELECT b.* FROM ebpls_user_sublevel_listings a,
                        ebpls_user_sublevel b  WHERE a.user_id = $user_id
                        and a.sublevel_id  = b.id");
        while ($getd = mysql_fetch_row($getsl)) {
                                                                                                 
                        if ($getd[0]=='CTC') {
				$ctc = 1;
                                        if (trim($getd[1])=='Individual') {
						$ctci = 1;
					}


					if (trim($getd[1])=='Business') {
                                                $ctcb = 1;
                                        }

					if (trim($getd[1])=='CTC Report') {
                                                $ctcr = 1;
                                        }

			}
	
} // end while
