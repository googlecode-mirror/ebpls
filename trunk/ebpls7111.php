<?php
//boat reg fees
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
if ($no_range=='') {
		$no_range=2;
}

//--- get connection from DB
//$dbLink = get_db_connection();
global $ThUserData;
$debug  = false;
require_once "includes/variables.php";
if ($updateit==1) {
	$ftag='Update';
} else {
	$ftag='Save';
}

if ($confx=='1') {

		$cntrec= SelectDataWhere($dbtype,$dbLink,"fish_boat","where engine_type='$bbo'");
		$cnt = NumRows($dbtype,$cntrec);
		
		if ($cnt>0) {
?>
       <body onload='ExistOther();parent.location="index.php?part=4&class_type=Preference&selMode=ebpls_nboatfees&permit=others&action_=8&itemEvent=1&data_item=0&orderbyasdes=1";'></body>
<?php			
		} else {
		$deact = DeleteQuery($dbtype,$dbLink,"boat_fee",
				     "boat_type='$bbo' and transaction='$trans'");
					echo "boat_type='$bbo' and transaction='$trans'"; 
	?>
       <body onload='DelRec();parent.location="index.php?part=4&class_type=Preference&selMode=ebpls_nboatfees&permit=others&action_=8&itemEvent=1&data_item=0&orderbyasdes=1";'></body>
<?php	
		}  
}				   

if ($addrange=='addr') {
		if ($trans=='' and $fishadd=='Update') {
			$trans=$ptype;
			$boat_type=$boat_type1;
		}

                $cntrec= SelectDataWhere($dbtype,$dbLink,"boat_fee",
                        "where boat_type='$boat_type' and transaction='$trans' order by fee_id");
                $no_range = NumRows($dbtype,$cntrec);
        }

?>

<link rel="stylesheet" href="stylesheets/default.css" type="text/css"/>
<div align='center'>
<table border=0 width=100% align=center cellspacing=0 cellpadding=0>
<tr><td colspan=2 class=header align=center width=100%>REFERENCES</td></tr>
<tr>
        <td colspan=2 align=center>
</td>
</tr>
<tr><td colspan=2 class=header2 align=center width=100%>Boat Registration Fee</td></tr>
</table>

<table border=0 cellspacing=0 cellpadding=0 width='100%'>

<tr>
<td align="center" valign="center" class='title'>
<form name="_FRM" method="POST" action=''>
<input type=hidden name=bbo value=''>
<input type=hidden name=confx value=''>
<input type=hidden name=trans value='<?php echo $trans; ?>'>
</td>
</tr>
<tr>
<td align="right" align="center" >
Engine Type:
</td>
<td align="left" align="center" >
<input type=hidden name='fee_id' value=<?php echo $fee_id ?>>
&nbsp
<? echo get_select_data($dbLink,'boat_type','ebpls_engine_type','engine_type_id','engine_type_desc',$boat_type,'false','','');?>

<input type=hidden name =boat_type1 value="<?php echo $boat_type;?>" >

&nbsp; <input type=submit name='searchfee' value="Search" > </td>

</tr>

<tr>
<td align="right" valign="center" >
Number Of Range:
</td>
<td align="left" valign="center" >
&nbsp&nbsp<input type=text name =no_range value='<?php echo $no_range;?>' >&nbsp;&nbsp;
<input type=hidden name=addrange>
<input type=button name='addrange1' value="Add Range" onclick='ValidNum();'> </td>
</td>
</tr>
<?php
$i=0;



if ($fishadd=='Save' or $fishadd=='Update') {
	if ($fishadd=='Save') {
		$searchboat = SelectDataWhere($dbtype,$dbLink,"boat_fee","where boat_type='$boat_type' and transaction = '$ptype'");
		$getres = NumRows($dbtype,$searchboat);
			if ($getres==0) {
				$woki = 2;
			} else {
				
		 ?>
                        <body onload='ExistRec();'></body>
                <?php
			$woki = 3;
			}
	} else {
		$woki=2;
	}
			if ($woki==2) {

		
				if ($addrange<>'Add Range') {

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
							$addrange='Add Range';
	                        $woki=0;
                        
	                        
	                        
	                        
        		        } else {
                	        $woki = 1;
		                }
        		$i++;
				}
					
				if ($updateit==1) {
			       	 	$ftag='Update';
				} else {
			        	$ftag='Save';
				}

			}
	$i=0;

	if ($woki==1) {



		while ($i<$no_range)
        	{
       			if ($updateit=='1') {

                                $insnew = UpdateQuery($dbtype,$dbLink,"boat_fee",
                                                "boat_type='$boat_type',
                                                range_lower=$rangelow[$i],
                                                range_higher=$rangehigh[$i],
                                                unit_measure='$uom',
                                                amt=$amt[$i], transaction = '$ptype'",
                                                "fee_id=$feeid[$i]");
				} else {
					if ($userenew==true) {
						$insnew = InsertQuery($dbtype,$dbLink,"boat_fee","",
							"'','$boat_type',$rangelow[$i],
							$rangehigh[$i],'$uom',$amt[$i],'New',1,3");
	
						$insrenew = InsertQuery($dbtype,$dbLink,"boat_fee","",
							"'','$boat_type',$rangelow[$i],
	                        $rangehigh[$i],'$uom',$amt[$i],'ReNew',1,3");
					} else {

						$insnew = InsertQuery($dbtype,$dbLink,"boat_fee","",
							"'','$boat_type',$rangelow[$i],
	                        	                $rangehigh[$i],'$uom',$amt[$i],'$ptype',1,3");
					}
				}
		$i++;
	        }

		if ($updateit=='1') {
			 ?>
                                <body onload='UpRec();parent.location="index.php?part=4&class_type=Preference&selMode=ebpls_nboatfees&permit=others&action_=8&itemEvent=1&data_item=0&orderbyasdes=1"';></body>
                        <?php
		} else {
			 ?>
                                <body onload='AddRec();parent.location="index.php?part=4&class_type=Preference&selMode=ebpls_nboatfees&permit=others&action_=8&itemEvent=1&data_item=0&orderbyasdes=1";'></body>
                        <?php
		}

		$boat_type='';
		$uom='';

	}
	}

//$updateit='';
$ftag='Save';
//$chkread='checkbox';
$chkread='hidden';

}elseif ($searchfee=='Search') {
$updateit='';
$ftag='Save';
$chkread='checkbox';
} elseif ($com=='Edit') {
$chkread = 'hidden';
$cntrec= SelectDataWhere($dbtype,$dbLink,"boat_fee","where boat_type='$boat_type'
			and transaction='$trans' order by fee_id");
$cnt = NumRows($dbtype,$cntrec);
 print "<tr><td></td><td><br>Range Low &nbsp;&nbsp; Range High &nbsp;&nbsp; Amount</td><td></td></tr>";

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


                        print "<tr><td align =right>Engine Capacity</td><td>&nbsp;
			<input type=hidden name=feeid[$i] value=$getrec[0]>
               <input type=text name=rangelow[$i] size=5 value=$getrec[2] $depval> &nbsp;&nbsp;
                        <input type=text name=rangehigh[$i] size=5 value=$getrec[3] $depval1> &nbsp;&nbsp;
                        <input type=text name=amt[$i] size=5 value=$getrec[5]></td>
                        </td><td>&nbsp</td>
                        </tr>";
$depval='';
							$depval1='';
                	$i++;
        	}

}

} 



if ($addrange=='Add Range') {
 			if ($updateit==1) {
                                $ftag='Update';
                                
                        $chkread = 'hidden';
                        
$cntrec= SelectDataWhere($dbtype,$dbLink,"boat_fee","where boat_type='$boat_type'
			and transaction='$trans' order by fee_id");
$cnt = NumRows($dbtype,$cntrec);
 print "<tr><td></td><td><br>Range Low &nbsp;&nbsp; Range High &nbsp;&nbsp; Amount</td><td></td></tr>";

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


                        print "<tr><td align =right>Engine Capacity</td><td>&nbsp;
			<input type=hidden name=feeid[$i] value=$getrec[0]>
                        <input type=text name=rangelow[$i] size=5 value=$getrec[2] $depval> &nbsp;&nbsp;
                        <input type=text name=rangehigh[$i] size=5 value=$getrec[3] $depval1> &nbsp;&nbsp;
                        <input type=text name=amt[$i] size=5 value=$getrec[5]></td>
                        </td><td>&nbsp</td>
                        </tr>";
$depval='';
							$depval1='';
                	$i++;
        	}

}
        
                                
                                
                                
                                
                                
                                
                                
                                
                        } else {
                                $ftag='Save';
                        

        print "<tr><td></td><td><br>Range Low &nbsp;&nbsp; Range High &nbsp;&nbsp; Amount</td><td></td></tr>";

        while ($i<$no_range)
        {
                if ($i=='0'){
                        $depval = 'value=0 readonly';
                        $depval1="value=$rangehigh[$i]";
                } elseif ($i==$no_range-1) {
                        $depval="value=$rangelow[$i]";
                        $depval1 = 'value=0 readonly';
                } else {
                        $depval="value=$rangelow[$i]";
                        $depval1="value=$rangehigh[$i]";
                }


                        print "<tr><td align =right>Engine Capacity</td><td>&nbsp;
                        <input type=text name=rangelow[$i] size=5 $depval> &nbsp;&nbsp;
                        <input type=text name=rangehigh[$i] size=5 $depval1> &nbsp;&nbsp;
                        <input type=text name=amt[$i] size=5 value=$amt[$i]></td>
                        </td><td>&nbsp</td>
                        </tr>";

                $i=$i+=1;
        }
    }
//$ftag='Save3';
//$chkread='checkbox';
}

?>

<tr>
<td align="right" valign="center" ><br>
Unit Of Measure:
</td>
<td align="left" valign="center" ><br>
&nbsp
<input type=text name="uom" value="<?php echo $uom; ?>">
</td></tr>

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
print "<option value='$ptype'>$ptype</option>";
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
<input type=hidden name=updateit value=<?php echo $updateit; ?>>
<input type=hidden name=com value=<?php echo $com; ?>>


<tr>
<td align="right" valign="center" >
</td>
<td align="left" valign="center" >&nbsp;
<input type = button value="<?php echo $ftag; ?>" name="fadd" onClick='VerifyBoatReg("<?php echo $ftag; ?>","<?php echo $updateit; ?>");' >
<input type=hidden name=fishadd value ="<?php echo $ftag; ?>">
<input type = button value='Cancel' onclick='CancelBoat();'>
<input type = reset value="Reset" onclick='_FRM.boat_type.focus();'>

</td>
</tr>
<tr>

<?php require_once "includes/boat_feelist.php"; ?>
</tr>


                <!---// start of the table //-->
                <table border=0 cellspacing=0 cellpadding=0  width='620'>
                                <tr><td align="center" valign="top" class='subtitleblue' colspan=2>&nbsp;</td>
                                </tr>
                <br>
                <body onload='_FRM.boat_type.focus();'></body>
                </table>
<script language='javascript'>
function CancelBoat()
{

 var _FRM = document._FRM;
                alert ("Transaction cancelled");
parent.location="index.php?part=4&class_type=Preference&selMode=ebpls_nboatfees&permit=others&action_=8&itemEvent=1&data_item=0&orderbyasdes=1";
}

function delboat(x)
{

 var _FRM = document._FRM;
	deleteb = confirm("Delete Record?");
	if (deleteb == true) {
		_FRM.confx.value = '1';
		_FRM.bbo.value = x;
	 } else {
		alert ("Transaction cancelled");
		return false;
	}
	_FRM.submit();
	return true;
}

function VerifyBoatReg(x,y)
{
                var a = document._FRM;
                if (a.boat_type.value=='') {
                        alert ("Input Valid Boat Type.");
                        a.boat_type.focus();
                        return false;
                }
                                                                                                                             
                                                                                                                             
                if (a.boat_type.value.length>15) {
                        alert ("Boat Type Exceeds Max Length");
                        a.boat_type.focus();
                        a.boat_type.select();
                        return false;
                }
                                                                                         
		if (a.uom.value=='') {
                        alert ("Input Valid Unit of Measure.");
                        a.uom.focus();
                        return false;
                }
                                                                                                                             
                                                                                                                             
                if (a.uom.value.length>15) {
                        alert ("Unit of Measure Exceeds Max Length");
                        a.uom.focus();
                        a.uom.select();
                        return false;
                } 
                a.fishadd.value=x;
                a.submit();
                return true;
}
</script>