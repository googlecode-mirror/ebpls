<?php

if ($reg_but=='Save') {
	
	
	$chkboat = SelectDataWhere($dbtype,$dbLink,"fish_boat",
				" where boat_name='$boat_name'");
	$chkboat = NumRows($dbtype,$chkboat);
		if ($chkboat==1 and $editit<>1) {
			?>
                <body onload='javascript:alert("Duplicate Boat Name Found");'></body>
		        <?php
        	} else {
			if ($editit<>1) {
				$insboat = InsertQuery($dbtype,$dbLink,"fish_boat","", 
						"'',$owner_id,'$boat_name',
						'$crew_num','$eng_type',$eng_cap,
						'$reg_num',now(),'$usern','',''");
			} else {
				$upboat = UpdateQuery($dbtype,$dbLink,"fish_boat",
						"boat_name='$boat_name', crew='$crew_num',
						engine_type='$eng_type', engine_cap=$eng_cap,
						reg_no='$reg_num', up_date=now(), 
						up_by='$usern'",
						"boat_id=$boatid");
			}
		}
	
	
} else {

	if ($fishcom=='Edit') {

		$getr = SelectDataWhere($dbtype,$dbLink,"fish_boat",
					"where boat_id = $boatid");
		$getb = FetchRow($dbtype,$getr);
		$editit = 1;
	} elseif ($fishcom=='Delete') {
		$getd = DeleteQuery($dbtype,$dbLink,"fish_boat","boat_id = $boatid");
		$editit ='';
	}
}
?>


<table cellspacing=0 cellpadding=0 border=1 width=60%>
        <tr>
        <td align="center" valign="top" class='header2' colspan=2>
        Registration of Fishery Vehicles
        </td>
        </tr>
</table> <br>
<input type=hidden name=useboat value='Boat Registration'>
<input type=hidden name=editit value=<?php echo $editit; ?>>
<input type=hidden name=boatid value=<?php echo $boatid; ?>>
<table border=1 width=60%><br>
<tr>
<td>Boat Name</td>
<td><input type=text name=boat_name value="<?php echo $getb[2]; ?>"> </td>
</tr>
<tr>
<td>Number Of Crew</td>
<td><input type=text name=crew_num value="<?php echo $getb[3]; ?>"> </td>
</tr>
<tr>
<td>Engine Type</td>
<td>
<?php echo get_select_data_where($dbLink,'eng_type','ebpls_engine_type','engine_type_id','engine_type_desc',$getb[4],'1');?>
</td>
</tr>
<tr>
<td>Engine Capacity</td>
<td><input type=text name=eng_cap value="<?php echo $getb[5]; ?>"> </td>
</tr>
<tr>
<td>Registration Number</td>
<td><input type=text name=reg_num value="<?php echo $getb[6]; ?>"> </td>
</tr>
</table><br>
<div align=center>
<input type=hidden name=reg_but>
<input type=button name=reg_but1 value='Save' onclick='VerifyBoat();'>
<input type=submit name=reg_close value=Close>
<input type='reset' name='_RESET' value='Reset'>
</div>
<br>
<?php

if ($owner_id=='') {
	$owner_id=0;
}

$getboat = SelectDataWhere($dbtype,$dbLink,"fish_boat","where owner_id=$owner_id");

?>

<table border=1 width=60%><br>
<tr>
<td>Boat Name</td>
<td>Number of Crew</td>
<td>Engine Type</td>
<td>Engine Capacity</td>
<td>Registration Number</td>
<td>Fee</td>
<td>&nbsp;</td>
</tr>

<?php
while ($getb = FetchRow($dbtype,$getboat))
{
	$getengine = @mysql_query("select * from ebpls_engine_type where engine_type_id = '$getb[4]'");
	$getengine1 = @mysql_fetch_assoc($getengine);
?>
<tr>
<td><?php echo $getb[2]; ?></td>
<td><?php echo $getb[3]; ?></td>
<td><?php echo $getengine1[engine_type_desc]; ?></td>
<td><?php echo $getb[5]; ?></td>
<td><?php echo $getb[6]; ?></td>
<?php
if ($stat=='') {
	$stat='New';
}
$getfee = SelectDataWhere($dbtype,$dbLink,"boat_fee",
			"where boat_type='$getb[4]' and
                        range_lower<$getb[5] and range_higher>=$getb[5] and
                        transaction='$stat' and active = 1");
$getnum= NumRows($dbtype,$getfee);
	if ($getnum==0) {
		$getfee = SelectDataWhere($dbtype,$dbLink,"boat_fee", 
                        "where boat_type='$getb[4]' and
                        range_lower<=$getb[5] and range_higher=0 and
                        transaction='$stat' and active = 1");
	}
$getfee = FetchArray($dbtype,$getfee);
?>
<td><?php  $totfee = $totfee+$getfee[amt]; echo number_format($getfee[amt],2);  ?></td>
<td><a href='index.php?part=4&class_type=Permits&itemID_=1221&useboat=1&owner_id=<?php echo $owner_id; ?>&boatid=<?php echo $getb[0]; ?>&permit_type=Fishery&fishcom=Edit&stat=<?php echo $stat; ?>'>Edit </a>
<a href='index.php?part=4&class_type=Permits&itemID_=1221&useboat=1&owner_id=<?php echo $owner_id; ?>&boatid=<?php echo $getb[0]; ?>&permit_type=Fishery&fishcom=Delete&stat=<?php echo $stat; ?>'>Delete </a>
</td>
</tr>
<?php } ?>
<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>Total Fee</td>
<td><?php $botP = $totfee; echo number_format($botP,2); ?></td></tr>
</table>

<script language='javascript'>
function VerifyBoat()
{
         var a = document._FRM;

		if (isBlank(a.boat_name.value)) {
			alert (<?php echo $invalid_input_error; ?> + " boat name");
			a.boat_name.focus();
			return false;
		}

		if (a.boat_name.value.length>20) {
                        alert ("Boat name " + <?php echo $max_len_error; ?> );
                        a.boat_name.focus();
                        a.boat_name.select();
                        return false;
                }


		 if (isBlank(a.crew_num.value)) {
                        alert (<?php echo $invalid_input_error; ?> + " crew number");
                        a.crew_num.focus();
                        return false;
                }
                                                                                                                             
                if (a.crew_num.value.length>5) {
                        alert ("Crew number " + <?php echo $max_len_error; ?> );
                        a.crew_num.focus();
                        a.crew_num.select();
                        return false;
                }
                
        if (a.crew_num.value<1) {
                alert ("Crew number cannot be lower than zero" );
                a.crew_num.focus();
                a.crew_num.select();
              return false;
        }        

		if (isNaN(a.crew_num.value)){
                        alert ("Please input valid crew number");
                        a.crew_num.focus();
                        return false;
                }


		if (a.eng_cap.value=='' || a.eng_cap.value<=0 || isNaN(a.eng_cap.value)) {
                        alert (<?php echo $invalid_input_error; ?> + " engine capacity");
                        a.eng_cap.focus();
                        return false;
                }
        if (a.eng_cap.value<0) {
                alert ("Engine capacity " + <?php echo $cant_neg; ?> );
                a.eng_cap.focus();
                a.eng_cap.select();
              return false;
        }         
                                                                                                                             
                if (a.eng_cap.value.length>15) {
                        alert ("Engine capacity " + <?php echo $max_len_error; ?> );
                        a.eng_cap.focus();
                        a.eng_cap.select();
                        return false;
                }


		 if (isBlank(a.reg_num.value)) {
                        alert (<?php echo $invalid_input_error; ?> + " registration number");
                        a.reg_num.focus();
                        return false;
                }
                                                                                                                             
                if (a.reg_num.value.length>15) {
                        alert ("Registration number " + <?php echo $max_len_error; ?> );
                        a.reg_num.focus();
                        a.reg_num.select();
                        return false;
                }



                a.reg_but.value="Save";
                a.submit();
                return true;
}
</script>