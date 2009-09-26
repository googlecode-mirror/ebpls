<?php
//culture and fish gear fees
require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");
                                                                                                    
require_once("ebpls-php-lib/utils/ebpls.search.funcs.php");


//--- get connection from DB
$dbLink = get_db_connection();
global $ThUserData;
$debug  = false;
require_once "includes/variables.php";

if ($fee_type==1 or $fee_type==0) {
	$fee_val = 'Constant';
	$fee_type=1;
} elseif ($fee_type==2) {
	$fee_val = 'Formula';
	$fee_type=2;
} else {
	$fee_val = 'Range';
	$fee_type=3;
}

if ($com=='Edit') {

	$result = mysql_query ("select * from biz_fee where biz_id=$biz_id") or die ("edit");
	$res = mysql_fetch_row($result);
	$biz_id = $res[0];
	$biz_desc = $res[1];
	$biz_type = $res[2];

		  if ($res[2]==1) {
	                $biz_val='Constant';
        	  } elseif ($res[2]==2) {
                	$biz_val='Formula';
	          } elseif ($res[2]==3) {
	                $biz_val='Range' ;
			$addrange='Add Range';
			$getcnt= mysql_query("select range_id from biz_range where biz_id=$biz_id") or die ("cnt");
	        	$no_range = mysql_num_rows($getcnt);  
		  }
	$ptype = $res[5];
}

/*if ($biz_val=='') {
	$biz_type=1;
	$biz_val='Constant';
}
*/
?>

<link rel="stylesheet" href="stylesheets/default.css" type="text/css"/>
<br>
<div align='center'>
<table border=0 cellspacing=0 cellpadding=0 width='620'>
<td align="center" valign="center" class='titleblue'> Default Business Fees</td></tr>
<td align="center" valign="center" class='normal' height=10>&nbsp;</td></tr>
</table>
                                                                                                                             
<table border=0 cellspacing=0 cellpadding=0 width='620'>
                                                                                                                             
<tr>
<td align="center" valign="center" class='title'>
<form name="_FRM" method="POST" action = "index.php?part=8112&permit_type=Business&com=<?php echo $com; ?>&biz_id=<?php echo $biz_id; ?>" >
<input type=hidden name=updateit value=<?php echo $updateit; ?>>
</td>
</tr>

<tr>
<td align="right" align="center" class='subtitle'>
Fee Description:
</td>
<td width=200 align="left" align="center" class='subtitle'>
<input type=hidden name='biz_id' value=<?php echo $biz_id; ?>> &nbsp
<input type=text name =biz_desc value="<?php echo $biz_desc;?>" ></td>
<td width=200><input type=submit name='searchfee' value="Search" > </td>
                                                                                                                             
</tr>
<tr>
<td align="right" valign="center" class='subtitle'>
Fee Type:
</td>
<td align="left" valign="center" class='subtitle'>
&nbsp&nbsp<select name =biz_type>
	<option value=<?php echo $biz_type; ?>><?php echo $biz_val; ?>	
	<option value=1>Constant</option>
	<option value=2>Formula</option>
	<option value=3>Range</option>
	</select>
&nbsp;&nbsp;</td>
<td><input type=submit name='addrange' value="Change Type"> </td>
</td>
</tr>
<?php
$i=0;
 $chkread='checkbox';
if ($no_range==0 or is_numeric($no_range)==false) {
                $no_range=2;
		$chkread='checkbox';
        }


if ($biz_type==1 or $biz_type=='') {

	print "<tr><td align=right valign=center class='subtitle'>Amount:</td>
		<td align=left valign=center class='subtitle'>&nbsp;
		<input type=text name=biz_cons value=$biz_cons></td>";
} elseif ($biz_type==2) {
	print "<tr><td align=right valign=center class='subtitle'>Formula:</td>
                <td align=left valign=center class='subtitle'>&nbsp
		<input type=text name=biz_form value=$biz_form></td>";
} elseif ($biz_type==3) {
	print "<tr><td align=right valign=center class='subtitle'>Number of Range:</td>
                <td align=left valign=center class='subtitle'>&nbsp
		<input type=text name=no_range value=$no_range> </td>
		<td>
		<input type=submit name=addrange value='Add Range'></td>";
}
if ($addrange=='Add Range') {
	print "<tr><td></td><td><br>Range Low &nbsp;&nbsp; Range High &nbsp;&nbsp; Amount</td><td></td></tr>";

	if ($com=='Edit') {
         	  $rng = mysql_query("select * from biz_range where biz_id=$biz_id order by range_id limit 1") or die("range".mysql_error());
	 	  $s = mysql_fetch_row($rng);
		  $s = $s[0];
	}


	while ($i<$no_range)
	{
		if ($i=='0'){
			$depval = 'value=0 readonly';
			$depval1='value='.$rangehigh[$i];
		} elseif ($i==$no_range-1) {
			$depval='value='.$rangelow[$i];
			$depval1 = 'value=0 readonly';
       		} else {
			$depval='value='.$rangelow[$i];
			$depval1='value='.$rangehigh[$i];
		} 

			if ($com=='Edit') {
				$getv = mysql_query("select * from biz_range where range_id=$s") or die (mysql_error());
				$getv = mysql_fetch_row($getv);
			        $depval='value='.$getv[2];
	                        $depval1='value='.$getv[3];
				$amt[$i]=$getv[4];
				$s=$s+=2;
			}	
				                                                                                                                     
			print "<tr><td></td><td>&nbsp;
		        <input type=text name=rangelow[$i] size=5 $depval> &nbsp;&nbsp;	
		        <input type=text name=rangehigh[$i] size=5 $depval1> &nbsp;&nbsp;
		        <input type=text name=amt[$i] size=5 value=$amt[$i]></td>
		        </td><td>&nbsp</td>
		        </tr>";
                                                                                                                             
	        $i=$i+=1;
	}
$ftag='Submit';
$chkread='checkbox';

} elseif ($fishadd=='Submit' or $fishadd=='Update') {

	if ($fishadd=='Submit') {
		$searchcul = mysql_query("select biz_desc from biz_fee 
					where biz_desc='$biz_desc'") or die("bizfee");
		$getres = mysql_num_rows($searchcul);
			if ($getres==0) {
				$woki = 2;
			} else {
				print "<div align=center><font color=red>Fee Already Added</font></div>";
			$woki = 3;
			}
	}
if ($woki==2) {	
	if ($biz_type==3) {
		$woki=2;
	} else {
		$woki=4;
	}
}

if ($biz_form=='') {
     $biz_form=0;
} 
if ($biz_cons=='') {
     $biz_cons=0;
}

			 if ($woki==4) {
                                
				if ($userenew==true) {
                                                $biz_id1 = $biz_id+=1;
                                                                                                                             
                                                $insnew=mysql_query("insert into biz_fee values
                                                        ('','$biz_desc', $biz_type, '$biz_form',
                                                        $biz_cons,'New',now(), '$usern',1)") or die ("new".mysql_error());
                                                                                                                             
                                                $insnew=mysql_query("insert into biz_fee values
                                                        ('','$biz_desc', $biz_type, '$biz_form',
                                                        $biz_cons,'ReNew', now(),'$usern',1)") or die ("renew");
                                         } else {
                                                                                                                             
                                                $insnew=mysql_query("insert into biz_fee values
                                                        ('','$biz_desc', $biz_type, '$biz_form',
                                                        $biz_cons,'$ptype',now(),'$usern',1)") 
							or die ("new".mysql_error());
                                         }
                        }
                                                                                                                             
                                                                                                                             



			if ($woki==2) {


			while ($i<$no_range)
		        {
	        	        if (!is_numeric($rangelow[$i]) or !is_numeric($rangehigh[$i]) or !is_numeric($amt[$i]) and $i==$norange){
		                	        print "<div align=center><font color=red>Invalid Input</font></div>";
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
						$cntrec= mysql_query ("select * from biz_range where 
                		        	and biz_id='$biz_id' order by range_id") or die ("edit");
						$no_range = mysql_num_rows($cntrec);
					} else {

						$cntrec=mysql_query("select biz_id from 
						biz_fee") or die ("cnt");
	
						$biz_id = mysql_num_rows($cntrec);
						$biz_id1 = $biz_id+=1;
							 if ($userenew==true) {

								$insnew=mysql_query("insert into biz_fee values 
								('','$biz_desc', $biz_type, '$biz_form',
								$biz_cons,'New', now(), '$usern',1)") or die ("new".mysql_error());

                                                                $insnew=mysql_query("insert into biz_fee values
                                                                ('','$biz_desc', $biz_type, '$biz_form',
                                                                $biz_cons,'ReNew', now(), '$usern',1)") or die ("new".mysql_error());

							 } else {

								
                                                                $insnew=mysql_query("insert into biz_fee values
                                                                ('','$biz_desc', $biz_type, '$biz_form',
                                                                $biz_cons,'$ptype', now(), '$usern',1)") or die ("new".mysql_error());
							 }
					}
			while ($i<$no_range)
	        	{
       				if ($fishadd=='Update') {
                                $insnew = mysql_query("update _range set
                                                range_low='$rangelow[$i]',
                                                range_high='$rangehigh[$i]',
                                                range_amt='$amt[$i]'
                                                where range_id=$feeid[$i]")
                                                or die ("new".mysql_error());
				} else {
			
					if ($userenew==true) {

					$insnew = mysql_query("insert into biz_range values 
						('',$biz_id,'$rangelow[$i]',
						'$rangehigh[$i]','$amt[$i]')") 
						or die ("new");
			

					$insrenew = mysql_query("insert into biz_range
						 values ('',$biz_id + 1,'$rangelow[$i]',
                                        	'$rangehigh[$i]','$amt[$i]')")
	                                        or die ("renew");
					} else {
					$insnew = mysql_query("insert into biz_range values
                                                ('',$biz_id,'$rangelow[$i]',
                                                '$rangehigh[$i]','$amt[$i]')")
                                                or die ("new");
					}
				}
			$i=$i+=1;
	        	}
			}//woki=1
			}//woki=2

$updateit='';
$ftag='Submit';
$chkread='checkbox';


} elseif ($searchfee=='Search') {
$updateit='';
$ftag='Submit';
$chkread='checkbox';


} elseif ($com=='DeAct') {
	print "<div align=center><font color=red>Fee DeActivated</font></div>";
	
		$deact = mysql_query("update biz_fee set active=0 
					where biz_id='$biz_id'") or die("deact");
} elseif ($com=='Act') {
	 print "<div align=center><font color=red>Fee Activated</font></div>";
                                                                                                                             
                $deact = mysql_query("update biz_fee set active=1
                                        where biz_id='$biz_id'") or die("deact");
}


?>

<tr>
<td align="right" valign="center" class='subtitle'>
Transaction Type:
</td>
<td align="left" valign="center" class='subtitle'>
&nbsp
<select name='ptype'>
<?php
if ($com=='' || $com=='Delete') {
print "<option value='New'>New</option>";
print "<option value='ReNew'>ReNew</option>";
} else {
print "<option value='$ptype'>$ptype</option>";
print "<option value='New'>New</option>";
print "<option value='ReNew'>ReNew</option>";
}


?>
                                                                                                                             
</select>
</td>
                                                                                                                             
</tr>
<?php
if ($com<>'Edit') {
?>
<tr><td align="right" valign="center" class='subtitle'>Use Also In ReNew</td>
<td>&nbsp;<input type=<?php echo $chkread; ?> name=userenew></td></tr>
<?php
}
?>
<tr>
<td align="right" valign="center" class='subtitle'>
&nbsp
</td>
<td align="left" valign="center" class='subtitle'>
&nbsp
</td>
</tr>
                                                                                                                             
<?php
if ($com=='Edit') {
        $ftag='Update';
} else {
        $ftag='Submit';
}
?>                                                                                                                             
<tr>
<td align="right" valign="center" class='subtitle'>
<input type = submit value="<?php echo $ftag; ?>" name="fishadd" >
</td>
<td align="left" valign="center" class='subtitle'>
&nbsp<input type = reset value="Clear">
</td>
</tr>
<tr>


<?php
if ($com=='Edit') {
        if ($fishadd=='Update') {
        $i=0;
        $rng = mysql_query("select * from biz_range where biz_id=$biz_id order by range_id limit 1") or die("range".mysql_error());
                  $s = mysql_fetch_row($rng);
                  $s = $s[0];
                                                                                                                                                            
                while ($i<$no_range) {
                    $insnew = mysql_query("update biz_range set
                                    range_low='$rangelow[$i]',
                                    range_high='$rangehigh[$i]',
                                    range_amt='$amt[$i]'
                                    where range_id=$s")
                     or die ("new".mysql_error());
                $s=$s+=2;
                $i++;
                 }
        $com='';
        }
}
?>


<?php require "includes/biz_feelist.php"; ?>
</tr>
                                                                                                                             
                                                                                                                             
                <!---// start of the table //-->
                <table border=0 cellspacing=0 cellpadding=0  width='620'>
                                <tr><td align="center" valign="top" class='subtitleblue' colspan=2>&nbsp;</td>
                                </tr>
                <br>
                </table>


