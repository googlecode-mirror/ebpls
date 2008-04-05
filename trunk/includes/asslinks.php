<?php
/* Prepares display of businesses (one line per business) when assessments requested.
Modification History:
2008.04.04 Add NOWRAP to permit#, Date and Action to improve readability by Ron Crabtree
*/
//populate table
// display with alternating light then dark bands
include'tablecolor-inc.php';
print "<tr bgcolor='$varcolor'>\n";
//foreach ($get_info as $field )
                                                                                                                                                                                                                                                                                                                                                                                                    
$a= ucfirst(stripslashes($get_info[2]));
$b= ucfirst(stripslashes($get_info[3]));
$c= ucfirst(stripslashes($get_info[4]));
print "<td>&nbsp;$a&nbsp</td>\n";
print "<td>&nbsp;$b&nbsp</td>\n";
print "<td>&nbsp;$c&nbsp</td>\n";
print "<td>&nbsp;$get_info[5]&nbsp</td>\n";
print "<td NOWRAP>&nbsp;$get_info[6]&nbsp</td>\n";
print "<td>&nbsp;$get_info[7]&nbsp</td>\n";

$staxfee = SelectMultiTable($dbtype,$dbLink,"ebpls_buss_preference",
		"sassess, predcomp","");
$prefset = FetchArray($dbtype,$staxfee);
$sassesscomplex = $prefset['sassess']; // per estab
$predcomp = $prefset['predcomp'];

if ($predcomp==1 ) {
	$owner_id = $get_info[0];
	$business_id = $get_info[1];
	 $getnat =SelectDataWhere($dbtype,$dbLink,"tempbusnature",
                                  " where owner_id=$owner_id and business_id=$business_id and active =1
                                  and transaction='$get_info[7]' and date_create like '$yearnow%'");
             $cntnat =mysql_num_rows($getnat);
             
              $getnat =SelectDataWhere($dbtype,$dbLink,"tempbusnature",
                                  " where owner_id=$owner_id and business_id=$business_id and active =0
                                  and transaction='$get_info[7]' and date_create like '$yearnow%'");
             $orignat =mysql_num_rows($getnat);
             
            $getnat =SelectDataWhere($dbtype,$dbLink,"tempbusnature",
                                  " where owner_id=$owner_id and business_id=$business_id
                                  and transaction='$get_info[7]' and date_create like '$yearnow%'");
             $nat =mysql_num_rows($getnat);
             
             if ($orignat=='') {
	             $orignat=$cntnat;
             }
             
            
             if ($orignat < $nat) {
					$newpred='1';
					$noregfee=1;
					
			 }
			
}



print "<td NOWRAP>
<a href='index.php?part=4&newpred=$newpred&noregfee=$noregfee&class_type=Permits&itemID_=4212&owner_id=$get_info[0]&com=edit&permit_type=$tag&stat=$get_info[7]&business_id=$get_info[1]&busItem=Business&istat=$get_info[7]'> ReAssess</a>
&nbsp;|&nbsp;<a href='index.php?part=4&newpred=$newpred&noregfee=$noregfee&class_type=Permits&itemID_=4212&owner_id=$get_info[0]&com=assess&permit_type=$tag&stat=$get_info[7]&business_id=$get_info[1]&busItem=Business&istat=$get_info[7]'>Assess</a>
</td>";
//}//end while
print "</tr>\n";
?>

