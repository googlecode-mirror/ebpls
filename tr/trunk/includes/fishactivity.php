<?php

if ($fishactive=='Add') {
/*	if ($actcom<>'Delete') {
		$my = SelectDataWhere($dbtype,$dbLink,"fish_activity",
                "where owner_id=$owner_id and transaction = '$stat'
                and active = 1");
	        $my = NumRows($dbtype,$my);
        	if ($my==0) {
*/
		echo "'',$fish_act,$owner_id,$qty, '$stat',1";
			$inact = InsertQuery($dbtype,$dbLink,"fish_assess","",
			    "'',$fish_act,$owner_id,$qty, '$stat',1");
//		} 
			
//	}
}
?>

<tr>
<td align="center" valign="top" class='normal' colspan=4>
<table cellspacing=0 cellpadding=0 border=1 width=60%><br>
        <tr>
        <td align="center" valign="top" class='header2' colspan=2>
        List of Fishery Activities
        </td>
        </tr>
</table> <br>
</tr>
                    
<table cellspacing=0 cellpadding=0 border=0 width=60%><br>
<tr>
<td width=150 align=right>Activity &nbsp;</td>
<td>
<?php echo get_select_data_where($dbLink,'fish_act','ebpls_fish_description','fish_id','fish_desc',$owner_datarow[culture_id],"1");?>
</td>
<tr>
<td align=right>Quantity &nbsp;</td><td><input type=text name=qty value=0></td>
</tr>
<tr>
<td></td><td>
<script language='javascript'>
function VerifyActivity()
{
	var x = document._FRM;
	if (x.qty.value=='' || isNaN(x.qty.value) || x.qty.value<='0') {
		alert ("Please input valid quantity.");
		x.qty.focus();
		return false;
	}
	
	
	if (x.qty.value.length>5) {
		alert ("Quantity exceeds max length.");
		x.qty.focus();
		x.qty.select();
		return false;
	}
	
	x.fishactive.value='Add';
	x.submit();
	return true
	

}
</script>

<input type=hidden name=fishactive>
<input type=button name=fishactive1 value=Add onclick='VerifyActivity();'>
</td>
</tr>
</table>
<?php

if ($owner_id<>'') {

$rt = SelectMultiTable($dbtype,$dbLink,"culture_fee a, fish_assess b",
			"distinct(a.culture_type), b.amt, b.ass_id, b.culture_id, b.culture_id",
			"where a.culture_type = b.culture_id and b.owner_id = '$owner_id'");

?>
<table cellspacing=0 cellpadding=0 border=1 width=60%><br>
<tr>
<td>Activity Type</td><td>Quantity</td><td>Fee</td><td>Action</td>
</tr>
<?php
while ($ft = FetchRow($dbtype,$rt))
{
	$getengine = @mysql_query("select * from ebpls_fish_description where fish_id = '$ft[0]'");
	$getengine1 = @mysql_fetch_assoc($getengine);
	$getengine21 = @mysql_query("select * from culture_fee where culture_type = '$ft[0]' and transaction = '$stat'");
	$getengine211 = @mysql_fetch_assoc($getengine21);
?>
<tr>
<td><?php echo $getengine1[fish_desc]; ?></td>
<td><?php echo $ft[1]; ?></td>
<?php
	$getfee = SelectDataWhere($dbtype,$dbLink,"culture_fee",
			"where culture_type='$ft[0]'");
	$gf = FetchRow($dbtype,$getfee);
	if ($gf[2]=='1') { //constant
?>
	<td><?php $tfee=$gf[4]; echo number_format($gf[4],2); ?> </td>
<?php 
	} elseif ($gf[2]=='2') { //formula
	eval("\$tfee=$ft[1]$gf[3];");
?>
        <td><?php echo number_format($tfee,2); ?></td>
<?php
	} elseif ($gf[2]=='3') { //range
	
		$getr = SelectDataWhere($dbtype,$dbLink,"culture_range","where
			culture_id=$getengine211[culture_id] and range_lower<=$ft[1] and
			range_higher >= $ft[1]");
			
		$numr = NumRows($dbtype,$getr);
		
			if ($numr==0) {

			$getr = SelectDataWhere($dbtype,$dbLink,"culture_range","where
                        culture_id=$getengine211[culture_id] and range_lower<=$ft[1] and
                        range_higher = '0'");
			}

		$getre = FetchArray($dbtype,$getr);

			if (is_numeric($getre[amt])){
?>
				<td><?php $tfee=$getre[amt]; 
				echo number_format($getre[amt],2); ?></td>
<?php
			} else {
				 eval("\$tfee=$ft[1]$getre[amt];");
?>
        <td><?php echo number_format($tfee,2); ?></td>
<?php
			}
	}
$tfee1 = $tfee1+$tfee;
?>
<td><!--<a href='index.php?part=4&class_type=Permits&itemID_=1221&useboat=&owner_id=<?php echo $owner_id; ?>&assid=<?php echo $ft[2]; ?>&permit_type=Fishery&fishactive=Add&fishi=<?php echo $ft[0]; ?>&actcom=Edit&stat=<?php echo $stat; ?>'>Edit </a>-->
<a href='index.php?part=4&class_type=Permits&itemID_=1221&useboat=&owner_id=<?php echo $owner_id; ?>&assid=<?php echo $ft[2]; ?>&permit_type=Fishery&fishactive=Add&actcom=Delete&stat=<?php echo $stat; ?>'>Delete </a>
</td>
</tr>
<?php
}

?>
<?php }
/* 
if ($owner_id<>'') {

	$my = SelectDataWhere($dbtype,$dbLink,"fish_activity",
		"where owner_id=$owner_id and transaction = '$stat'
		and active = 1");
	$my = NumRows($dbtype,$my);
	if ($my==0 and $tfee1<>'') { 
	$saveit = InsertQuery($dbtype,$dbLink,"fish_activity","", 
		"'',$owner_id, $tfee1, '$stat', now(), '$usern', 1");
	} elseif ($my>0) {
	?>
	<!--	<body onload='javascript:alert("Activity Already Addedd");'></body>-->
<?php
	}
*/
?>

<tr><td>&nbsp;</td><td>Total Fee</td><td><?php $nfishfee = $tfee1; echo number_format($tfee1,2); ?></td></tr>
<?php //} ?>
</table>
