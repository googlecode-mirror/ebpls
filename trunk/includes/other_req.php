          <td align="center" valign="top" class='header2' colspan=4>Requirements </td>
        </tr>
           <tr>
              <td align="right" valign="top" class='normal' colspan=4>&nbsp; </td>
            </tr>
        <?php
        if ($owner_id<>'' and $business_id<>'') {
        $ic = 0;
        $col1 = 0;
                $getreq = SelectDataWhere($dbtype,$dbLink,"ebpls_buss_requirements",
                                        "where recstatus='A' and reqindicator='1' and permit_type='$permit_type'");
                $gt = NumRows($dbtype,$getreq);
                                                                                                                                                                                                                                                           
                while ($ic<$gt)
                {
                    while ($getr = FetchRow($dbtype,$getreq))
                        {
                        $ic++;
                                if ($col1==0) {
include'tablecolor-inc.php';
print "<tr bgcolor='$varcolor'>\n";
                                }
                                                                                                                                                                                                                                                           
                          $check1 = SelectDataWhere($dbtype,$dbLink,"havereq",
                                        " where owner_id=$owner_id
                                        and business_id=$business_id
                                        and reqid=$getr[0]");
                                $hreq = NumRows($dbtype,$check1);
                                if ($hreq==0) {
                                                                                                                                                                                                                                                           
                                                                                                                                                                                                                                                           
                                        $insertreq = InsertQuery($dbtype,$dbLink,"havereq","",
                                                "'', $getr[0], $owner_id, $business_id,0");
                                        $ch='UNCHECKED';
                                        $gethr[4]=0;
                                } else {
                                $gethr = FetchRow($dbtype,$check1);
                                        if ($gethr[4]==1) {
                                                $ch='CHECKED';
                                        } else {
                                                $ch='UNCHECKED';
                                        }
                                }
$getr[1]=stripslashes($getr[1]);
                                print "
                                <td align=right width=5%><input type=hidden name=col1[$ic]
                                value=$getr[0]>&nbsp
                                <input type=checkbox name=x[$ic] $ch></td><td align=left width=23%>$getr[1]
                                </td>";
                                                                                                                                                                                                                                                           
                        $col1=$col1+1;
                        $arr_id[$i++] = $ic;
                                if ($col1>1) {
                                print "</tr><tr>";
                                $col1=0;
                                }
                                                                                                                                                                                                                                                           
                }
                                                                                                                                                                                                                                                           
                }
                }
?>

