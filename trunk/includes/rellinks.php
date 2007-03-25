<?php
//populate table
//while ($get_info = mysql_fetch_row($result)){
include'tablecolor-inc.php';
print "<tr bgcolor='$varcolor'>\n";
//foreach ($get_info as $field )
if ($permit_type=='Business') {
$a= ucfirst(stripslashes($get_info[2]));
$b= ucfirst(stripslashes($get_info[3]));
$c= ucfirst(stripslashes($get_info[4]));
print "<td>&nbsp;$a&nbsp</td>\n";
print "<td>&nbsp;$b&nbsp</td>\n";
print "<td>&nbsp;$c&nbsp</td>\n";
print "<td>&nbsp;$get_info[5]&nbsp</td>\n";
print "<td>&nbsp;$get_info[6]&nbsp</td>\n";
print "<td>&nbsp;$get_info[7]&nbsp</td>\n";
} else {
$a= stripslashes($get_info[1]);
$b= stripslashes($get_info[2]);
$c= stripslashes($get_info[3]);
print "<td>&nbsp;$a&nbsp</td>\n";
print "<td>&nbsp;$b&nbsp</td>\n";
print "<td>&nbsp;$c&nbsp</td>\n";
print "<td>&nbsp;$get_info[4]&nbsp</td>\n";
//print "<td>&nbsp;$field&nbsp</td>\n";
}

if ($stat=='') {
		$stat=$get_info[4];
}

//generate balance
$tfee = SelectMultiTable($dbtype,$dbLink,"ebpls_fees_paid","sum(fee_amount) * multi_by",
			"where owner_id = $get_info[0] and permit_type='$permit_type'");
$totalfee =FetchRow($dbtype,$tfee);
$totpay = $totalfee[0];
$totchnge = SelectMultiTable($dbtype,$dbLink,"temppayment","sum(payamt)",
                        "where owner_id = $get_info[0] and permit_type='$permit_type'");
$amtchange = FetchRow($dbtype,$totchnge);
$totpaid = $amtchange[0];
$amtchange =  $totpay - $amtchange[0] ;
//print "<td><font color=red> $amtchange</font></td>";
 if ($permit_type=='Business') {
	//check if complete req
                $ge = SelectDataWhere($dbtype,$dbLink,"havereq",
					"where owner_id=$get_info[0] and
                                        active=1 and business_id=$get_info[1]");
                $ge = NumRows($dbtype,$ge);
                $getreq = SelectDataWhere($dbtype,$dbLink,"ebpls_buss_requirements",
                         		"where recstatus='A' and reqindicator=1 and permit_type='Business'");
                $gt = NumRows($dbtype,$getreq);
                
                
                $checkapp = mysql_query("select * from ebpls_buss_approve a where
                						a.owner_id='$get_info[0]' and
                                        a.decision=1 and a.business_id=$get_info[1]");
                $checka = mysql_num_rows($checkapp);
                
                
                if ($get_info[8]<>1) {
	                ?>
                                <td>
                              
                                Not yet paid
                                
                                </td>
                            <?php
                } elseif ($checka==0) {
	            		 ?>
                                <td>
                                
                                
                                Not yet approve
                                
                                
                                </td>
                            <?php
                                        
            	} elseif ($getre[0]==1) {

                        if ($ge==$gt) {
                                $gud = 1;
                        } else {
	                        ?>
                                <td><a href='#' onclick="IncReq('<?php echo $get_info[0]; ?>','<?php echo $get_info[1]; ?>');">Incomplete Requirements</a></td>
                            <?php
                        }
            	
                } else {
                        $gud = 1;
                }
                        if ($gud==1) {
	                        $d = 'ebpls_buss_permit.php';
	            ?>
	                    <input type = hidden name=bus_select value='ebpls_buss_permit.php'>
	                    <input type = hidden name=permit_num value='<?php echo $get_info[2]; ?>'>
	        
<?php

if ($get_info==0) {
	$geti = SelectDataWhere($dbtype,$dbLink,$permittable,"");
	$geti = NumRows($dbtype,$geti);
	$get_info[2]=$geti + 1 ;
}
 
$get_info[2]=(int)($get_info[2]);
?> 
                        <td>
	                    <a href='index.php?part=4&class_type=Permits&itemID_=3212&owner_id=<?php echo $get_info[0]; ?>&business_id=<?php echo $get_info[1]; ?>&com=PrintReport&permit_type=<?php echo $tag; ?>&stat=<?php echo $get_info[7]; ?>&permitnumber=<?php echo $get_info[2];?>&busItem=Business' class=subnavwhite'> Print </a>&nbsp
                        </tr>
                        <?php
                        $gud=0;
                        }
        } else {
?>
        <td>
<a href='index.php?part=4&class_type=Permits&itemID_=3212&owner_id=<?php echo $get_info[0]; ?>&com=PrintReport&permit_type=<?php echo $tag; ?>&stat=<?php echo $get_info[4];?>&busItem=<?php echo $permit_type; ?>' class=subnavwhite> Print </a>&nbsp
</tr>
<?php
        }
//}//end while

?>
<script language="JavaScript" src="javascripts/default.js"></script>
<script language="javascript">
function PM(x, pn)
        {
alert (x.value);
       //winpopup=window.open('ebpls_buss_permit.php','popup','height=500,width=800,menubar=no,scrollbars=yes,status=no,toolbar=no,screenX=100,screenY=0,left=100,top=0');
                                                                                                                                                            
         winpopup = window.open('ebpls_buss_permit.php?permit_num=' + pn ,'popup','height=500,width=800,menubar=no,scrollbars=yes,status=no,screenX=100,screenY=0,left=100,top=0');
                                                                                                                                                            
        }
                                                                                                                                                            
</script>
<?php
