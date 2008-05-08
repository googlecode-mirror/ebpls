<?php
/*Modification History:
2008.05.08 RJC Define undefined variables to reduces clutter in phperror.log
*/
$savethis = isset($savethis) ? $savethis : '';
$tempid = isset($tempid) ? $tempid: '';
$watdo = isset($watdo) ? $watdo : '';
?>

<input type=hidden name=savethis value=<?php echo $savethis; ?> >
<input type=hidden name=tempid  value=<?php echo $tempid; ?>>
<input type=hidden name=watdo value=<?php echo $watdo; ?>>
<?php
/*
para sa magbabasa nitong code "GOOD LUCK!!!!"
*/
$business_nature_code = isset($business_nature_code) ? $business_nature_code : ''; 
$business_capital_investment = isset($business_capital_investment) ? $business_capital_investment : 0;
$gross_sale = isset($gross_sale) ? $gross_sale : 0;

$_idx    = $business_nature_code;
$_cap    = $business_capital_investment;
$lastyr = $gross_sale;
if ($lastyr=='') $lastyr=0;

$addrenew = isset($addrenew) ? $addrenew : '';
if ($addrenew=='on') {
		$stat='ReNew';
}

$savethis = isset($savethis) ? $savethis : '';
if ($savethis==23){
	$check = SelectDataWhere($dbtype,$dbLink,$tempbiz,
                 "where bus_code='$_idx' and business_id=$business_id
                 and owner_id=$owner_id");
        $checkit = NumRows($dbtype,$check);


	if ($checkit==0 || $checkit=='') {
	$nat = SelectDataWhere($dbtype,$dbLink,
		        "ebpls_buss_nature", "where natureid='$_idx'");
        $getnat = FetchArray($dbtype,$nat);
        $getnat=$getnat[naturedesc];
	$getnat=addslashes($getnat);
	if ($addrenew=='on') {
		$lstat='ReNew';
		$lastyr=$_cap;
	} else {
		$lstat='New';
	}
	
	
        $saveit = InsertQuery($dbtype,$dbLink,$tempbiz,
                  "(bus_code, bus_nature, cap_inv,
                   last_yr, owner_id, business_id,
                   active, transaction, date_create)",
                  "'$_idx', '$getnat', $_cap,
                  $lastyr, $owner_id, $business_id,
                  1,'$lstat',now()");
	} else {
?>
       <body onload='javascript:alert("Line Already Added");'></body><?php
	}

}



if ($business_id<>'' and $owner_id<>'') {
		if ($savethis=='1') {

			if ($watdo=='addgros') {

                		if ($stat=='Retire') {
		                $getbus=SelectDataWhere($dbtype,$dbLink,"tempbusnature",
					"where tempid=$tempid");
		                $getbu = FetchRow($dbtype,$getbus);
		                $owner_id = $getbu[5];
                		$business_id = $getbu[6];
		                $bus_code = $getbu[1];
		                $bus_nature = $getbu[2];
		                $oldlay = $getbu[4];

                        	$wil = InsertQuery($dbtype,$dbLink,"tempbusnature","",
	                        "'', '$bus_code', '$bus_nature',$oldlay,
        	                $lastyr,$owner_id, $business_id, now(),
                	        0, 1,2,'Retire','0'");

		                $updatepermit = UpdateQuery($dbtype,$dbLink,
				"ebpls_business_enterprise_permit",
	                        "pmode='$pmode'","owner_id=$owner_id and
        	                business_id=$business_id order by
                	        business_permit_id desc limit 1");

                		$wil3 = UpdateQuery($dbtype,$dbLink,"ebpls_business_enterprise",
	                        "business_payment_mode='Annual'",
        	                "owner_id=$owner_id and business_id=$business_id");

                		$wil = UpdateQuery($dbtype,$dbLink,"tempbusnature","active=0",
	                        "tempid=$tempid");

                		$bp = UpdateQuery($dbtype,$dbLink, $permittable,"active=0",
	                        "owner_id=$owner_id and business_id=$business_id
        	                and active=1");

	        		} else {
			/*-------------- reneew */
			        $getbus=SelectDataWhere($dbtype,$dbLink,"tempbusnature",
					"where tempid=$tempid");
			        $getbu = FetchRow($dbtype,$getbus);
			        $owner_id = $getbu[5];
			        $business_id = $getbu[6];
			        $bus_code = $getbu[1];
			        $bus_nature = $getbu[2];
			        $oldlay = $getbu[4];
			        $result = InsertQuery($dbtype,$dbLink,"tempbusnature","",
		                        "'', '$bus_code', '$bus_nature',$oldlay,
                	        	$lastyr,$owner_id,$business_id, now(),
	                        	0, 1,'','ReNew','0'");
        			$wil = UpdateQuery($dbtype,$dbLink,"tempbusnature","active=0",
	                             "tempid=$tempid");
			        } //if retire or renew
                                                                                                                                                                                                                                                                                                                                                                                                       

			} else { //wattodo
//#################################################3
			 $check = SelectDataWhere($dbtype,$dbLink,$tempbiz,
                                        "where bus_code='$_idx' and business_id=$business_id
                                        and owner_id=$owner_id and tempid<>'$savenat'");
                                $checkit = NumRows($dbtype,$check);
                                        if ($checkit==0) {
				$nat = SelectDataWhere($dbtype,$dbLink,
                                        "ebpls_buss_nature", "where natureid='$_idx'");
                                        $getnat = FetchArray($dbtype,$nat);
                                        $getnat=$getnat[naturedesc];
				$result = UpdateQuery($dbtype,$dbLink,"tempbusnature",
				     	  "cap_inv='$_cap',
					  last_yr='$lastyr'",
                	                  "tempid='$savenat'");

					} else {
?>
                                        <body onload='javascript:alert("Line Already Added");'></body><?php
					}
			}

		} else {
			$savenat = isset($savenat) ? $savenat : '';
			if ($savenat<>'') {
				$nat = SelectDataWhere($dbtype,$dbLink,
                                        "ebpls_buss_nature", "where natureid='$_idx'");
                                        $getnat = FetchArray($dbtype,$nat);
                                        $getna=$getnat['naturedesc'];

				$nat = SelectDataWhere($dbtype,$dbLink,"tempbusnature",
                                          "where tempid='$savenat'");
                                $getnat = FetchArray($dbtype,$nat);
                                $transme=$getnat['transaction'];
				if ($transme=='New') {
                                        $multi = $_cap;
					$oldm = $getnat[cap_inv];
                                } else {
                                        $multi = $lastyr;
					$oldm = $getnat[last_yr];
                                }

				$result = UpdateQuery($dbtype,$dbLink,"tempbusnature",
                                          "bus_code=$_idx, bus_nature='$getna', cap_inv='$_cap',
                                          last_yr='$lastyr'",
                                          "tempid='$savenat'");

				$result = UpdateQuery($dbtype,$dbLink,"tempassess",
					  "multi='$multi'","owner_id='$owner_id' and
					  business_id='$business_id' and natureid = '$business_nature_code'
					  and transaction = '$stat' and multi='$oldm'");
			} else {	
                                $check = SelectDataWhere($dbtype,$dbLink,$tempbiz,
                                        "where bus_code='$_idx' and business_id=$business_id
                                        and owner_id=$owner_id");
                                $checkit = NumRows($dbtype,$check);
                                        if ($checkit==0) {
                                        //get nature_desc
                                        $nat = SelectDataWhere($dbtype,$dbLink,"ebpls_buss_nature", "where natureid='$_idx'");
                                        $getnat = FetchArray($dbtype,$nat);
                                        $getnat=$getnat['naturedesc'];
                                        //save to temp table
                                        if ($stat=='New') {
                                                if ($business_capital_investment<>0) {
                                                        $oki = 1;
                                                } else {
                                                        $oki = 0;
                                                }
                                        } else {
                                                if ($gross_sale<>0 || $business_capital_investment<>0) {
                                                        $oki = 1;
                                                } else {
                                                        $oki = 0;
                                                }
                                        }
                                        if ($oki==12){
					$getnat=addslashes($getnat);
                                                $saveit = InsertQuery($dbtype,$dbLink,$tempbiz,
                                                "(bus_code, bus_nature, cap_inv,
                                                last_yr, owner_id, business_id,
                                                active, transaction, recpaid, date_create)",
                                                "'$_idx', '$getnat', $_cap,
                                                $lastyr, $owner_id, $business_id,
                                                1,'New','0',now()");
                                              
                                        }
                                        } else {
                                                if ($PROCESS<>'SAVE') {
?>
                                        <!--<body onload='javascript:alert("Line Already Added");'></body>--><?php
                                                }
                                        }
                        }
		}//editline 
}
if ($business_id=='') {
        $business_id=0;
}
                        //get bus nature
                          $getnat =SelectDataWhere($dbtype,$dbLink,"tempbusnature",
                                  " where owner_id=$owner_id and business_id=$business_id
                                  and active = 1");
                                while ($getit = FetchArray($dbtype,$getnat)){
				$getit['bus_nature']=stripslashes($getit['bus_nature']);
				$bus_code = $getit['bus_code'];
                                $getcapinv =SelectDataWhere($dbtype,$dbLink,"tempbusnature",
                                  " where owner_id=$owner_id and business_id=$business_id and bus_code='$bus_code'
                                  order by tempid asc limit 1");
              			$getcapi  = FetchArray($dbtype,$getcapinv);
				
				$ifpaid=$getit['recpaid'];
				$editline = isset($editline) ? $editline : ''; //2008.05.08
				if ($editline=='y' and $natcode==$getit['tempid']) {
?>
				<tr>
				<td align="left" valign="top" class='normal'>&nbsp;
<?php
		if ($stat=='New' || $stat=='ReNew' and $watdo=='') {
                echo get_select_data_where($dbLink,'business_nature_code','ebpls_buss_nature ','natureid','naturedesc',$getit['bus_code'], "naturestatus='A' and natureid not in (select bus_code from tempbusnature where business_id='$business_id' and owner_id='$owner_id' and bus_code<>$getit[bus_code] and retire < 1) order by naturedesc");
		} else {
		echo $getit['bus_nature'];
		}
                ?>
                
           
                
				 </td>
				<td align="left" valign="top" class='normal'>&nbsp;
		<?php
				if ($stat=='New') {
		?>
				<input type='text' name='business_capital_investment' maxlength=255 size=15 value="<?php echo $getcapi["cap_inv"]; ?>" <?php echo $disablecapinv; ?> >
		<?php
				} else {
		?>
				<input type='hidden' name='business_capital_investment' maxlength=255 size=15 value="<?php echo $getcapi["cap_inv"]; ?>" <?php echo $disablecapinv; ?> >
		<?php
				echo $getcapi["cap_inv"];
				}
		?>		</td>
				<td align="left" valign="top" class='normal'>&nbsp;
		<?php	
				if ($stat=='New') {
		?>
		<input type='hidden' name='gross_sale' maxlength=255 size=15 value="<?php echo $getit['last_yr']; ?>" <?php echo $disablelastyr; ?>>
		<?php
				$gross_sale=$getit['last_yr'];
				echo $getit['last_yr'];
				} else {
		?>
<input type='text' name='gross_sale' maxlength=255 size=15 value="<?php echo $getit['last_yr']; ?>" >
		<?php
				}
		?>
				</td>
 <td align="left" valign="top" class='normal'>&nbsp;
<!--von-->
<?php
				} else {
				 $ci = number_format($getcapi['cap_inv'], 2);
                                $lyg = number_format($getit['last_yr'], 2);
?>
                                <tr>
                                <td align='left' valign='top' class='normal'>
				<?php echo $getit['bus_nature']; ?></td>
                                <td align='right' valign='top' class='normal'>
				<?php echo $ci; ?></td>
                                <td align='right' valign='top' class='normal'>
				<?php echo $lyg; ?></td>
                                <td align='center' valign='top' class='normal'>
<?php
				}
/*==================== will list action links =================*/
             if ($stat=='New' || $stat=='') {
$str = "toolbar=0,location=0,directories=0,menubar=0,resizable=0,scrollbars=1,status=1";
			if ($editline<>'y') {
                                if ($ifpaid==0) {
?>
				<a class='subnavwhite' href='#' onclick='javascript:confdel("<?php echo $getit['tempid']; ?>");'>Delete</a> | 
<?php

                                }
                        ?>
				 <a class='subnavwhite' href='#' onclick='javascript:editline("<?php echo $getit['tempid']; ?>");'>Edit</a>
<?php
			} else {

				if ($natcode==$getit['tempid']) {
?>
<!--von-->
<input type=hidden name=savenat  value=<?php echo $natcode; ?>>
<a href='#' onClick='javascript:CLine("New");' class='subnavwhite'>Save</a>	| 
<?php
				} else {
					if ($ifpaid==0) {
				?>
                                <a class='subnavwhite' href='#' onclick='javascript:confdel("<?php echo $getit['tempid']; ?>");'>Delete</a> |
<?php
					}
?>
                                <a class='subnavwhite' href='#' onclick='javascript:editline("<?php echo $getit['tempid']; ?>");'>Edit</a>
	                <?php
				}
			}
             } elseif ($stat=='ReNew') {
			if ($editline<>'y') {
                                if ($ifpaid==0) {
?>
                                <a class='subnavwhite' href='#' onclick='javascript:confdel("<?php echo $getit['tempid']; ?>");'>Delete</a>  | 
<?php
                                }

			if ($getit['transaction']=='New') {
  ?>
                            
<!--	    <a class='subnavwhite' href='#' onclick='javascript:editline("<?php echo $getit['tempid']; ?>");'>Edit</a>-->
            <a class='subnavwhite' href='#' onclick='javascript:addgross("<?php echo $getit['tempid']; ?>");'>Add New Gross</a>

                        <?php

			} else {
				if (date('Y',(strtotime($getit['date_create'])))==date('Y')) {
                        ?>

					| <a class='subnavwhite' href='#' onclick='javascript:editgross("<?php echo $getit['tempid']; ?>");'>Edit Gross</a>  
			<?php
				} else {
			?>
					| <a class='subnavwhite' href='#' onclick='javascript:addgross("<?php echo
$getit['tempid']; ?>");'>Add New Gross</a>
<?php
				}

			}

			} else {
                                if ($natcode==$getit['tempid']) {
?>
<!--von-->
<input type=hidden name=savenat  value=<?php echo $natcode; ?>>
<a href='#' onClick='javascript:_FRM.tempid.value = <?php echo $getit['tempid']; ?>; _FRM.savethis.value=1; CLine("ReNew"); ' class='subnavwhite'>Save</a>
<?php
                                } else {
			
					if ($ifpaid==1) {
                         ?>
	                                <a class='subnavwhite' href='#' onclick='javascript:confdel("<?php echo $getit['tempid']; ?>");'>Delete</a> | 
			<?php
					}


			if (date('Y',(strtotime($getit['date_create'])))==date('Y')) {
//			if ($getit[last_yr]>0) {
			?>
                                <a class='subnavwhite' href='#' onclick='javascript:editgross("<?php echo $getit['tempid']; ?>");'>Edit Gross</a>  
			<?php
			} else {
			?>
				| <a class='subnavwhite' href='#' onclick='javascript:addgross("<?php echo $getit['tempid']; ?>");'>Add New Gross</a>
                        <?php
			}
                                }


			}
         } elseif ($stat=='Retire') {
			if ($editline<>'y') {
			if (date('Y',(strtotime($getit['date_create'])))==date('Y')) {
//			 if ($getit[last_yr]>0) {
                        ?>
			 <a class='subnavwhite' href='#' onclick='javascript:editgross("<?php echo $getit['tempid']; ?>");'>Edit Gross</a> | 
			<?php
			}
			?>
                                                                                                 
                        <a class='subnavwhite' href='#' onclick='javascript:addgross("<?php echo
$getit[tempid]; ?>");'>Add Retire Gross</a>


                        <?php
                        } else {
                                if ($natcode==$getit['tempid']) {
?>
<!--von-->
<input type=hidden name=savenat  value=<?php echo $natcode; ?>>
<a href='#' onClick='javascript:_FRM.tempid.value = <?php echo $getit['tempid']; ?>; _FRM.savethis.value=1;CLine("Retire");' class='subnavwhite'>Save</a>
<?php
                                } else {
				if (date('Y',(strtotime($getit['date_create'])))==date('Y')) {
//				if ($getit[last_yr]>0) {
				?>
				   <a class='subnavwhite' href='#' onclick='javascript:editgross("<?php echo $getit['tempid']; ?>");'>Edit Gross</a> | 
                                <?php
				}
				?>                                                              
                                <a class='subnavwhite' href='#' onclick='javascript:addgross("<?php echo $getit['tempid']; ?>");'>Add Retire Gross</a>
                        <?php
                               } 

			}
		}
print "</td></tr>\n";
                                }
$kulit = isset($kulit) ? $kulit : ''; //2008.05.08
if ($editline<>'y' || $kulit=='1' and $stat<>'Retire') {
?>
<tr>
<td align="left" valign="top" class='normal'>&nbsp;
<?php
    echo get_select_data_where($dbLink,'business_nature_code','ebpls_buss_nature','natureid','naturedesc',$datarow['naturedesc'], "naturestatus='A' and natureid not in (select bus_code from tempbusnature where business_id='$business_id' and owner_id='$owner_id') order by naturedesc");
?>               
</td>
<td align="center" valign="top" class='normal'> 
<input type='text'align=right name='business_capital_investment' maxlength=255 size=15 value=0.00></td>
<td align="left" valign="top" class='normal'>&nbsp;</td> 
 <td align="center" valign="top" class='normal'>&nbsp;<br>

<?php
      if ($stat<>'New') {
?>
	      <input type=checkbox name=addrenew> Add as ReNew
<?php
      	} 
?>
<!--von-->
<?php
 	if ($owner_id>0 and $business_id>0) {
?>
<a href='#' onClick='_FRM.savethis.value=23; javascript:CLine("New");' class='subnavwhite'>Save</a>
<?php
	}
?>
</td>
</tr>
<?php
}
?>
<input type=hidden name=sta value=<?php echo $stat; ?>>
<input type=hidden name=bcode>
<input type=hidden name=confx>
<script language='Javascript' src='javascripts/default.js'></script>
        <script language="Javascript">
        function confdel(cc)
        {
                var _FRM = document._FRM;
                doyou = confirm("Line of Business would be permanently deleted.");
                                                                                                 
                                                                                                 
                if (doyou==true) {
                        _FRM.bcode.value = cc;
                        _FRM.confx.value = 1;
                } else {
                        _FRM.confx.value=0;
                        return false;
                }
		parent.location ="index.php?part=4&genpin=" + _FRM.genpin.value + "&class_type=Permits&itemID_=1221&natcode=" + cc + "&addbiz=Select&bizcom=Delete&owner_id=<?php echo $owner_id; ?>&business_id=<?php echo $business_id; ?>&permit_type=Business&willup=N&stat=<?php echo $stat; ?>&busItem=Business";
                //_FRM.submit();
                return true;
        }

	function editline(cc)
	{
		var _FRM = document._FRM;
                parent.location ="index.php?part=4&editline=y&genpin=" + _FRM.genpin.value + "&class_type=Permits&itemID_=1221&natcode=" + cc + "&addbiz=Select&owner_id=<?php echo $owner_id;
?>&business_id=<?php echo $business_id; ?>&permit_type=Business&willup=N&stat=<?php echo $stat; ?>&busItem=Business&pmode=<?php echo $datarow['business_payment_mode'];?>&watdo=";
                //_FRM.submit();
                return true;
	}

	function editgross(cc)
        {
                var _FRM = document._FRM;
                parent.location ="index.php?part=4&editline=y&genpin=" + _FRM.genpin.value + "&class_type=Permits&itemID_=1221&natcode=" + cc + "&addbiz=Select&owner_id=<?php echo $owner_id;
?>&business_id=<?php echo $business_id; ?>&permit_type=Business&willup=N&stat=<?php echo $stat; ?>&busItem=Business&pmode=<?php echo $datarow['business_payment_mode'];?>&watdo=editline";
                //_FRM.submit();
                return true;
        }




	function addgross(cc)
        {
                var _FRM = document._FRM;
                parent.location ="index.php?part=4&editline=y&genpin=" + _FRM.genpin.value + "&class_type=Permits&itemID_=1221&natcode=" + cc + "&addbiz=Select&owner_id=<?php echo $owner_id;
?>&business_id=<?php echo $business_id; ?>&permit_type=Business&willup=N&stat=<?php echo $stat; ?>&busItem=Business&pmode=<?php echo $datarow['business_payment_mode'];?>&watdo=addgros";
                //_FRM.submit();
                return true;
        }


        </script>

