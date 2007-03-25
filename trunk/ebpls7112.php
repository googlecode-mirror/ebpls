<?php
//culture and fish gear fees
require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");
                                                                                                    
require_once("ebpls-php-lib/utils/ebpls.search.funcs.php");
if ($orderbyasde==1) {
	$orderbyasde=0;
	$ascdesc='asc';
} else {
	$orderbyasde=1;
	$ascdesc='desc';
}
if ($ascdesc1=='') {
	$ascdesc1=$ascdesc;
} else {
	$ascdesc=$ascdesc1;
}


//--- get connection from DB
//$dbLink = get_db_connection();
global $ThUserData;
$debug  = false;
require_once "includes/variables.php";

if ($updateit==1) {
	$ftag='Update';
} else {
	$updateit='';
	$ftag='Save';
}


if ($confx=='1') {

		$cntrec= SelectDataWhere($dbtype,$dbLink,"fish_assess","where culture_id='$bbo' and transaction='$trans'");
		$cnt = NumRows($dbtype,$cntrec);
		
		if ($cnt>0) {
?>
       <body onload='ExistOther();parent.location="index.php?part=4&class_type=Preference&selMode=ebpls_nfishcfees&permit=others&action_=8&itemEvent=1&data_item=0&orderbyasdes=1";'></body>
<?php			
		} else {
		$deact = DeleteQuery($dbtype,$dbLink,"culture_fee",
				     "culture_id='$bbo' and transaction='$trans'");
		$deact = DeleteQuery($dbtype,$dbLink,"culture_range",
				     "culture_id='$bbo'");
	?>
       <body onload='DelRec();parent.location="index.php?part=4&class_type=Preference&selMode=ebpls_nfishcfees&permit=others&action_=8&itemEvent=1&data_item=0&orderbyasdes=1";'></body>
<?php	
		}  
}			



if ($activ=='FishingGear') {
	$htag = 'Fishing Gear';
} else {
	$htag = 'Fish Activities';
	$htag1 = '" Fish Activities"';
}

//if ($updateit<>1 || $fishadd=='Update') {
//$boat_type=0;
//}

if ($updateit==1) {
$rr = SelectDataWhere($dbtype,$dbLink,"culture_fee","where culture_id='$boat_type'");
$rr = FetchRow($dbtype,$rr);
$fee_type=$rr[2];
} else {
	$rr[1] = $culture_type;
	$rr[6] = $uom;
}

if ($fee_type==1 or $fee_type==0 or $fee_type=='') {
	$fee_val = 'Constant';
	$fee_type=1;
} elseif ($fee_type==2) {
	$fee_val = 'Formula';
	$fee_type=2;
} else {
	$fee_val = 'Range';
	$fee_type=3;
}
//echo $culture_type."VooDoo";
?>


<div align='center'>

<table border=0 width=100% align=center cellspacing=0 cellpadding=0>
<tr><td colspan=2 class=header align=center width=100%>REFERENCES</td></tr>
<tr>
        <td colspan=2 align=center>
</td>
</tr>
<tr><td colspan=2 class=header2 align=center width=100%><?php echo $htag; ?> Fee</td></tr>
</table>


                                                                                                                             
<table border=0 cellspacing=0 cellpadding=0 width='620'>
                                                                                                                             
<tr>
<td align="center" valign="center" class='title'>
<form name="_FRM" method="POST" action = "index.php?part=4&class_type=Preference&selMode=ebpls_nfishcfees&action_=8&itemEvent=1&data_item=0&permit_type=Fishery&boat_type=<?php echo $boat_type; ?>" >
<input type=hidden name=bbo value=''>
<input type=hidden name=confx value=''>
<input type=hidden name=trans value='<?php echo $trans; ?>'>
<input type=hidden name=updateit value=<?php echo $updateit; ?>>
</td>
</tr>
<tr>
<td align="right" >
<?php echo $htag; ?> Type:
</td>
<td width=200 align="left" valign="center" >
<input type=hidden name='culture_id' value=<?php echo $rr[0]; ?>> &nbsp
<? echo get_select_data($dbLink,'culture_type','ebpls_fish_description','fish_id','fish_desc',$rr[1],'false','','');?>
</td>
<td width=200><input type=submit name='searchfee' value="Search" > </td>
                                                                                                                             
</tr>
<?php
if ($updateit<>1 || $ftag=='Save') {
	if ($fee_type==3) {
			$f3 = 'selected';
	} elseif ($fee_type==2) {
			$f2='selected';
	} else {
			$f1='selected';
	}
?>                                             
<tr>
<td align="right" valign="center" >
Fee Type:
</td>
<td align="left" valign="center" >
&nbsp&nbsp<select name =fee_type onchange="_FRM.submit();">
	<option value=1  <?php echo $f1; ?> >Constant</option>
	<option value=2  <?php echo $f2; ?>>Formula</option>
	<option value=3  <?php echo $f3; ?>>Range</option>
	</select>
&nbsp;&nbsp;</td>
<td><!--<input type=submit name='addrange1' value="Change Type">--> </td>
</td>
</tr>
<?php
} else {
?>
<input type=hidden name=fee_type value=<?php echo $fee_type; ?>>
<?php
}
$i=0;

if ($no_range==0 or is_numeric($no_range)==false) {
                $no_range=2;
		$chkread='checkbox';
        }


if ($fee_type==1 or $fee_type=='') {

	print "<tr><td align=right valign=center >Amount:</td>
		<td align=left valign=center >&nbsp;
		<input type=text name=constamt value=$rr[4]></td>";
} elseif ($fee_type==2) {
	print "<tr><td align=right valign=center >Formula:</td>
                <td align=left valign=center >&nbsp
		<input type=text name=formamt value=$rr[3]></td>";
} elseif ($fee_type==3) {
	print "<tr><td align=right valign=center >Number of Range:</td>
                <td align=left valign=center >&nbsp
		<input type=text name=no_range value=$no_range> </td>
		<td>
		<input type=submit name=addrange value='Add Range'></td>";
}

if ($addrange=='Add Range' || $fee_type==3) {
	print "<tr><td></td><td><br>Range Low &nbsp;&nbsp; Range High &nbsp;&nbsp; Amount</td><td></td></tr>";
$i=0;
	while ($i<$no_range)
	{
		
		if ($i=='0'){
			
			$depval = "value='0' readonly";
			$depval1='value='.$rangehigh[$i];
		} elseif ($i==$no_range-1) {
		
			$depval='value='.$rangelow[$i];
			$depval1 = 'value=0 readonly';
       	} else {
	       	
			$depval='value='.$rangelow[$i];
			$depval1='value='.$rangehigh[$i];
		} 

	if ($updateit==1 ) {

		if ($rangeid=='') {
				
			$gg = SelectDataWhere($dbtype,$dbLink,"culture_range",
				"where culture_id = $boat_type order by fee_id limit 1");
		} else {

			$gg = SelectDataWhere($dbtype,$dbLink,"culture_range",
				"where culture_id = $boat_type and fee_id<>'$rangeid' order by fee_id limit 1");
                }
		$r = FetchRow($dbtype,$gg);
                $rangeid = $r[0];
                $depval="value=$r[2]";
		$depval1 ='value='.$r[3];
		
		if ($i=='0'){
			
			$depval = "value=$r[2] readonly";
			$depval1="value=$r[3]";
		} elseif ($i==$no_range-1) {
		
			$depval="value=$r[2]";
			$depval1 = "value=$r[3] readonly";
       	} else {
	       	
			$depval="value=$r[2]";
		$depval1 ="value=$r[3]";
		} 
		
		//$amt[$i]=$r[4];
		
	}
                                                                         
			print "<tr><td></td><td>&nbsp;
			<input type=hidden name=feeid[$i] value = $rangeid>
		        <input type=text name=rangelow[$i] size=5 $depval> &nbsp;&nbsp;	
		        <input type=text name=rangehigh[$i] size=5 $depval1> &nbsp;&nbsp;
		        <input type=text name=amt[$i] size=5 value=$r[4]></td>
		        </td><td>&nbsp</td>
		        </tr>";
                                                                                                                             
	        $i=$i+=1;
	}
if ($updateit<>1) {
$ftag='Save';
}
$chkread='checkbox';
}

if ($fishadd=='Save' or $fishadd=='Update') {
if ($fishadd=='Update') {
	if ($fee_type==3) {
		$i=0;
		while ($i<$no_range)
		        {
	        	    
			            if ($rangelow[$i]<0 or $rangehigh[$i]<0 or 
	        	            !is_numeric($rangelow[$i]) or !is_numeric($rangehigh[$i]) or 
	        	            !is_numeric($amt[$i]) or $amt[$i]<0 or 
	        	             strlen($rangelow[$i])>11 or strlen($rangehigh[$i])>11 or
	        	             strlen($amt[$i])>11){
?>
       <body onload='javascript:alert("Invalid Input");'></body>
<?php

                        	$i=$no_range;
	                        $woki=0;
	                        $updateit=1;
	                        $notwoki=1;
        		        } else {
                	        $woki = 1;
                	        $notwoki=1;
		                }
        		$i++;
			}
			
			
			
		if ($woki==1) {
		$uypi = UpdateQuery($dbtype,$dbLink,"culture_fee",
	                "culture_type='$culture_type',unit_measure='$uom'",
	                "culture_id=$culture_id and fee_type=$fee_type");
		$i=0;
		while ($i<$no_range)
                        {
                                $insnew = UpdateQuery($dbtype,$dbLink,"culture_range",
                                                "range_lower=$rangelow[$i],
                                                range_higher=$rangehigh[$i],
                                                amt=$amt[$i]",
                                                "fee_id=$feeid[$i]");
			$i++;
			}
		?>
		<body onload='UpRec(); parent.location="index.php?part=4&class_type=Preference&selMode=ebpls_nfishcfees&permit=others&action_=8&itemEvent=1&data_item=0&orderbyasdes=1";'></body>
		<?php	
		}
		
		
		
	} else {
if ($constamt=='') {
$constamt=0;
}

@eval("\$isvalid=1$formamt;");

		if (is_numeric($isvalid)) {
		 $uypi = UpdateQuery($dbtype,$dbLink,"culture_fee",
                		"culture_type='$culture_type', formula_amt='$formamt',
				unit_measure='$uom', const_amt=$constamt",
				"culture_id=$culture_id and fee_type=$fee_type");
$updateit='';
?>
		<body onload='UpRec(); parent.location="index.php?part=4&class_type=Preference&selMode=ebpls_nfishcfees&permit=others&action_=8&itemEvent=1&data_item=0&orderbyasdes=1";'></body>
		<?php	
		} else {
			?>
                <body onload='alert("Invalid Formula. Please Check!");'></body>
                <?php
                $notwoki=1;
     	}

		
	}

} else {
	if ($fishadd=='Save') {
		$searchcul = SelectDataWhere($dbtype,$dbLink,"culture_fee", 
					"where culture_type='$culture_type' and transaction='$ptype'");
		$getres = NumRows($dbtype,$searchcul);
			if ($getres==0) {
				if ($culture_type=='') {
                         
				$woki=3;
				} else {
				$woki = 2;
				}
			} else {

?>
       <body onload='javascript:ExistRec();'></body>
<?php

	
			$woki = 3;
			}
	}
if ($woki==2) {	
	if ($fee_type==3) {
		$woki=2;
	} else {
		if ($fee_type==2) {
		@eval("\$isvalid=1$formamt;");
		
		if (is_numeric($isvalid)) {
		$woki=4;
		} else {
			?>
                <body onload='alert("Invalid Formula. Please Check!");'></body>
                <?php
     	}
 		}
	}
}
if ($formamt=='') {
     $formamt=0;
} 
if ($constamt=='') {
     $constamt=0;
}
if ($fee_type==1) {
	$woki=4;
}
			 if ($woki==4) {
                             	   
										if ($userenew==true) {
                                                $culture_id1 = $culture_id+=1;
                                                                                                                             
                                                $insnew=InsertQuery($dbtype,$dbLink,"culture_fee","",	
                                                        "'','$culture_type', $fee_type, '$formamt',
                                                        $constamt,now(),'$uom','New',1");
                                                                                                                             
                                                $insnew=InsertQuery($dbtype,$dbLink,"culture_fee","",
                                                        "'','$culture_type', $fee_type, '$formamt',
                                                        $constamt,now(),'$uom','ReNew',1");
                                         } else {
                                                                                                                             
                                                $insnew=InsertQuery($dbtype,$dbLink,"culture_fee","",
                                                        "'','$culture_type', $fee_type, '$formamt',
                                                        $constamt,now(),'$uom','$ptype',1"); 
                                         }
                        }


			if ($woki==2) {
			$i=0;
			while ($i<$no_range)
		        {
	        	    
			            if ($rangelow[$i]<0 or $rangehigh[$i]<0 or 
	        	            !is_numeric($rangelow[$i]) or !is_numeric($rangehigh[$i]) or 
	        	            !is_numeric($amt[$i]) or $amt[$i]<0 or 
	        	             strlen($rangelow[$i])>11 or strlen($rangehigh[$i])>11 or
	        	             strlen($amt[$i])>11){
?>
       <body onload='javascript:alert("Invalid Input");'></body>
<?php

                        	$i=$no_range;
	                        $woki=0;
        		        } else {
                	        $woki = 1;
		                }
        		$i=$i+=1;
			}
			$i=0;

				if ($woki==1) {
					if ($fishadd=='Update') {

						$cntrec= SelectDataWhere($dbtype,$dbLink,"culture_range",
							"where and culture_id='$culture_id' 
							order by range_id");
						$no_range = NumRows($dbtype,$cntrec);
					} else {

						$cntrec=SelectDataWhere($dbtype,$dbLink,"culture_fee","");
	
						$culture_id = NumRows($dbtype,$cntrec) + 1;
						$culture_id1 = $culture_id+=1;
							 if ($userenew==true) {

								$insnew=InsertQuery($dbtype,$dbLink,"culture_fee","", 
									"'','$culture_type', $fee_type, '$formamt',
									$constamt,now(),'$uom','New',1");
								$culture_new = mysql_insert_id();
								$insnew=InsertQuery($dbtype,$dbLink,"culture_fee","",
	                        	                                "'','$culture_type', $fee_type, '$formamt',
	                                	                        $constamt,now(),'$uom','ReNew',1");
	                            $culture_renew = mysql_insert_id();   	                        
							 } else {

								$insnew=InsertQuery($dbtype,$dbLink,"culture_fee","",
                	                                        	"'','$culture_type', $fee_type, '$formamt',
	                        	                                $constamt,now(),'$uom','$ptype',1") ;
	                        	$culture_new = mysql_insert_id();                             
							 }
					}
			while ($i<$no_range)
	        	{
       				if ($fishadd=='Update') {
	
                                $insnew = UpdateQuery($dbtype,$dbLink,"culture_range",
                                                "range_lower=$rangelow[$i],
                                                range_higher=$rangehigh[$i],
                                                amt=$amt[$i]",
                                                "fee_id=$feeid[$i]");
				} else {
			
					if ($userenew==true) {

					$insnew = InsertQuery($dbtype,$dbLink,"culture_range","", 
						"'',$culture_new,$rangelow[$i],
						$rangehigh[$i],$amt[$i]"); 
			
					$insrenew = InsertQuery($dbtype,$dbLink,"culture_range","",
						 "'',$culture_renew,$rangelow[$i],
                                        	$rangehigh[$i],$amt[$i]");
					} else {

					$insnew = InsertQuery($dbtype,$dbLink,"culture_range","",
						 "'',$culture_new,$rangelow[$i],
                        	                $rangehigh[$i],$amt[$i]");
					}
				}
			$i=$i+=1;
	        	}
			}//woki=1
			}//woki=2
}

	if ($notwoki<>1) {
$updateit='';
$ftag='Save';
$chkread='checkbox';
	}
} elseif ($searchfee=='Search') {
$updateit='';
$ftag='Save';
$chkread='checkbox';
} elseif ($com=='Edit') {
$chkread = 'hidden';
$cntrec= SelectDataWhere($dbtype,$dbLink,"culture_fee","where culture_type='$boat_type' 
			and transaction='$trans' order by culture_id");
$cnt = NumRows($dbtype,$cntrec);
$getd = FetchRow($dbtype,$cntrec);
if ($getd[2]==3) {
 print "<tr><td></td><td><br>Range Low &nbsp;&nbsp; Range High &nbsp;&nbsp; Amount</td><td></td></tr>";
$i=0;
	while ($i<$cnt)
        	{
  		while ($getrec = FetchRow($dbtype,$cntrec)) {

		$boat_type=$getrec[1];
		$no_range=$cnt;
		$uom =$getrec[4];
		$ptype=$getrec[6];

	              	if ($i=='0'){
                        	$depval = 'readonly';
	                } elseif ($i==$cnt-1) {
        	                $depval1 = 'readonly';
                	} else {
				$depval='';
				$depval1='';
			}
                                                                                                                             
                                                                                                                         
                        print "<tr><td></td><td>&nbsp;
			<input type=hidden name=feeid[$i] value=$getrec[0]>
                        <input type=text name=rangelow[$i] size=5 value=$getrec[2] $depval> &nbsp;&nbsp;
                        <input type=text name=rangehigh[$i] size=5 value=$getrec[3] $depval1> &nbsp;&nbsp;
                        <input type=text name=amt[$i] size=5 value=$getrec[5]></td>
                        </td><td>&nbsp</td>
                        </tr>";
                                                                                                                             
                	$i=$i+=1;
        	}	

}
}

} elseif ($com=='DeAct') {
?>
       <body onload='javascript:alert("Fee DeActivated");'></body>
<?php

//	print "<div align=center><font color=red>Fee DeActivated</font></div>";
	
		$deact = UpdateQuery($dbtype,$dbLink,"culture_fee","active=0", 
					"culture_id='$boat_type' and transaction='$trans'");
$chkread="checkbox";
} elseif ($com=='Act') {
?>
       <body onload='javascript:alert("Fee Activated");'></body>
<?php

//	 print "<div align=center><font color=red>Fee Activated</font></div>";
                                                                                                                             
                $deact = UpdateQuery($dbtype,$dbLink,"culture_fee","active=1",
                                        "culture_id='$boat_type' and transaction='$trans'");
$chkread="checkbox";
}


?>

<tr>
<td align="right" valign="center" ><br>
Unit Of Measure:
</td>
<td align="left" valign="center" ><br>
&nbsp
<input type=text name="uom" value="<?php echo $rr[6]; ?>">
</td></tr>                                                        
<?php
if ($updateit<>1) {
?>
                                                                    
<tr>
<td align="right" valign="center" >
Transaction Type:
</td>
<td align="left" valign="center" >
&nbsp
<select name='ptype'>
<?php
if ($com=='' || $com=='Delete') {
print "<option value='New'>New</option>";
print "<option value='ReNew'>ReNew</option>";
} else {
print "<option value='$rr[7]'>$rr[7]</option>";
print "<option value='New'>New</option>";
print "<option value='ReNew'>ReNew</option>";
}


if ($ftag=='Save') {
	$chkread='checkbox';
}

?>
                                                                                                                             
</select>
</td>
                                                                                                                             
</tr>
<tr><td align="right" valign="center" >Use Also In ReNew</td>
<td>&nbsp;<input type=<?php echo $chkread; ?> name=userenew></td></tr>

<tr>
<td align="right" valign="center" >
&nbsp
</td>
<td align="left" valign="center" >
&nbsp
</td>
</tr>
<?php } ?>
                                                                                                                             
</table>
<table>                                                                                                                            
<tr>
<td align="center" valign="center" >
<input type = button value="<?php echo $ftag; ?>" name="fadd" onClick='VerifyFishReg("<?php echo $ftag; ?>","<?php echo $updateit; ?>","<?php echo $fee_type; ?>");' >
<input type = hidden  name="fishadd" >
<input type = button value='Cancel' onclick='CancelFish();'>
<input type = reset value="Reset" onclick='_FRM.culture_type.focus();'>
<body onload="_FRM.culture_type.focus();"></body>
</td>
<td align="left" valign="center" >
&nbsp
</td>
</tr>
<tr>

<?php require_once "includes/culture_feelist.php"; ?>
</tr>
                                                                                                                             
                                                                                                                             
                <!---// start of the table //-->
                <table border=0 cellspacing=0 cellpadding=0  width='620'>
                                <tr><td align="center" valign="top" class='subtitleblue' colspan=2>&nbsp;</td>
                                </tr>
                <br>
                </table>
                
                
<script language='javascript'>

function VerifyFishReg(x,y,z)
{
                var a = document._FRM;
                if (isBlank(a.culture_type.value)) {
                        alert (<?php echo $invalid_input_error; ?> +   <?php echo $htag1; ?>);
                        a.culture_type.focus();
                        a.culture_type.select();
                        return false;
                }
                                                                                                                             
                                                                                                                             
                if (a.culture_type.value.length>20) {
                        alert ("Culture Type " + <?php echo $max_len_error; ?> );
                        a.culture_type.focus();
                        a.culture_type.select();
                        return false;
                }
                                                                                         
		if (isBlank(a.uom.value)) {
                        alert (<?php echo $invalid_input_error; ?> + " Unit of Measure.");
                        a.uom.focus();
                        a.uom.select();
                        return false;
                }
                                                                                                                             
                                                                                                                             
                if (a.uom.value.length>15) {
                        alert ("Unit of Measure " + <?php echo $max_len_error; ?> );
                        a.uom.focus();
                        a.uom.select();
                        return false;
                } 
                
                if (x==1) {
	                                                                                                  
					if (isBlank(a.constamt.value)) {
                        alert (<?php echo $invalid_input_error; ?> + " Amount.");
                        a.constamt.focus();
                        a.constamt.select();
                        return false;
                	}
                                                                                                                             
                                                                                                                             
                	if (a.constamt.value.length>10) {
                        alert ("Amount " + <?php echo $max_len_error; ?> );
                        a.constamt.focus();
                        a.constamt.select();
                        return false;
                	} 
            	}
            	
            	if (x==2) {
	                                                                                                  
					if (isBlank(a.formamt.value)) {
                        alert (<?php echo $invalid_input_error; ?> + " Formula.");
                        a.formamt.focus();
                        a.formamt.select();
                        return false;
                	}
                                                                                                                             
                                                                                                                             
                	if (a.formamt.value.length>15) {
                        alert ("Formula " + <?php echo $max_len_error; ?> );
                        a.formamt.focus();
                        a.formamt.select();
                        return false;
                	} 
            	}
            	
                if (x==3) {
	                                                                                                  
					if (isBlank(a.no_range.value)) {
                        alert (<?php echo $invalid_input_error; ?> + " Formula.");
                        a.no_range.focus();
                        a.no_range.select();
                        return false;
                	}
                                                                                                                             
                                                                                                                             
                	if (a.no_range.value.length>15) {
                        alert ("Formula " + <?php echo $max_len_error; ?> );
                        a.no_range.focus();
                        a.no_range.select();
                        return false;
                	} 
            	}
                
                a.fishadd.value=x;
                a.submit();
                return true;
}

function CancelFish()
{

 var _FRM = document._FRM;
                alert ("Transaction cancelled");
parent.location="index.php?part=4&class_type=Preference&selMode=ebpls_nfishcfees&permit=others&action_=8&itemEvent=1&data_item=0&orderbyasdes=1";
}

</script>
