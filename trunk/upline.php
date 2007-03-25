<?php
//require_once("lib/ebpls.lib.php");
//require_once("lib/ebpls.utils.php"); 
//require_once("ebpls-php-lib/utils/ebpls.search.funcs.php");
//require "includes/variables.php";
//include("lib/multidbconnection.php");
$permit_type='Business';
include("includes/variables.php");
include("lib/multidbconnection.php");
$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);
$permittable = 'ebpls_business_enterprise_permit';

//--- get connection from DB
//$dbLink = get_db_connection();

global $ThUserData;
$getbus=SelectDataWhere($dbtype,$dbLink,"tempbusnature","where tempid=$tempid");
$getbu = FetchRow($dbtype,$getbus);
$owner_id = $getbu[5];
$business_id = $getbu[6];
$bus_code = $getbu[1];
$bus_nature = $getbu[2];
$getp = SelectDataWhere($dbtype,$dbLink,$permittable,"where owner_id =$owner_id 
			and business_id =$business_id and active = 1");
$getp = FetchRow($dbtype,$getp);
$pin = $getp[14];

if ($pin=='') {
	$pin=$genpin;
}

?>

<form action="upline.php" method=post>
<script language='Javascript' src='javascripts/default.js'></script>
<script language="Javascript">
function CheckIt(x)
{
                var msgTitle = "Payment\n";
                if(isNaN(x.value))
                {
                        alert( msgTitle + "Please input a valid amount!");
                        _FRM.pay.focus();
                        return false;
                }
}
</script>
<div align=right><a href="javascript: opener.location.reload(true); window.close();"><b>Close this Window [X]</b></a></div>
<table border = 0 align=center><br><br><br><bR><br>
<tr>
<td align="right" valign="top" class='normal' > Business Nature :&nbsp; </td>
<td align="left" valign="top" class='normal'  colspan=3>&nbsp;
<input type=hidden name='tempid' value=<?php echo $tempid; ?>>
<input type=hidden name='stat' value=<?php echo $stat; ?>>
<input type=hidden name='pmode' value=<?php echo $pmode; ?>>

<?php echo stripslashes($getbu[2]); ?>
</td>
</tr>

<tr>
<td align="right" valign="top" class='normal' > Capital Investment :&nbsp; </td>
<td align="left" valign="top" class='normal'  colspan=3>&nbsp; 
<input type='text' name='ci' maxlength=255 class='text180'  value="<?php echo $getbu[3]; ?>" 
onBlur='javascript:CheckIt(ci)' <?php echo $disablecapinv; ?>>
</td>
</tr>

<tr>
<td align="right" valign="top" class='normal' > Last Year's Gross :&nbsp; </td>
<td align="left" valign="top" class='normal'  colspan=3>&nbsp;
<input type='hidden' name='oldlay' maxlength=255 class='text180' value="<?php echo $getbu[4]; ?>"
<input type='text' name='lay' maxlength=255 class='text180'  value="<?php echo $getbu[4]; ?>"
onBlur='javascript:CheckIt(lay)' 
</td>
</tr>


</table>
<table border=0 align=center>
<tr>
<td><input type=submit name=bsave value=SAVE>&nbsp;
<input type=button name=canit value=CLOSE onClick='javascript: opener.location.reload(true); window.close()'>
</td>
</tr>
</table>
<?php
		


if ($bsave=="SAVE") {
$clr=0;
	if ($stat=='New') {

	$result = UpdateQuery($dbtype,$dbLink,"tempbusnature","cap_inv=".$ci,
			     "tempid=".$tempid);
	$clr = 1;

	} elseif ($stat=='editReNew' || $stat=='editRetire') {
	
	$result = UpdateQuery($dbtype,$dbLink,"tempbusnature","last_yr=".$ly,
			    "tempid=".$tempid);

	$result = UpdateQuery($dbtype,$dbLink,$permittable,"transaction='ReNew'",
			    "owner_id=$owner_id and business_id=$business_id
			     and active=1");
	$clr = 1;

	} elseif ($stat=='ReNew') {

	$result = InsertQuery($dbtype,$dbLink,"tempbusnature","", 
			"'', '$bus_code', '$bus_nature',$oldlay, 
			$lay,$owner_id,$business_id, now(), 
			0, 1,'','ReNew')");

	$luk = SelectDataWhere($dbtype,$dbLink,$permittable," 
				owner_id=$owner_id and 
				business_id=$business_id 
				and active=1 and transaction='ReNew'");
	$luk=NumRows($luk);
	
	if ($luk==0) {

	$res = InsertQuery($dbtype,$dbLink,$permittable,"
				(business_id, owner_id, for_year,
				application_date,input_by, transaction, 
				paid, steps, pin, active)",
                		"$business_id, $owner_id,'$currdate[year]', 
				now(), '$usern', '$stat', 
				0,'For Assessment', '$pin', 1");
	}


	$wil = UpdateQuery($dbtype,$dbLink,"tempbusnature","active=0",
			     "tempid=".$tempid);

	$bp =  UpdateQuery($dbtype,$dbLink,$permittable,"active=0",
                        "owner_id=$owner_id and business_id=$business_id
			and active=1");
	$clr=1;

	} elseif ($stat=='Retire') {
	$wil = InsertQuery($dbtype,$dbLink,"tempbusnature","", 
			"'', '$bus_code', '$bus_nature',$oldlay,
                        $lay,$owner_id, $business_id, now(),
                        0, 1,2,'Retire'");
 	$updatepermit = UpdateQuery($dbtype,$dbLink,"ebpls_business_enterprise_permit",
			"pmode='$pmode'","owner_id=$owner_id and 
			business_id=$business_id order by 
			business_permit_id desc limit 1");

	$wil3 = UpdateQuery($dbtype,$dbLink,"ebpls_business_enterprise",
			"business_payment_mode='Annual'",
			"owner_id=$owner_id and business_id=$business_id");

	$luk = SelectDataWhere($dbtype,$dbLink,$permittable," where 
			owner_id=$owner_id and business_id=$business_id
                        and active=1 and transaction='Retire'");
	$luk=NumRows($luk);
	
	if ($luk==0) {

	$res = InsertQuery($dbtype,$dbLink,$permittable,
			"(business_id, owner_id, for_year,
			application_date,input_by, transaction, 
			paid, steps, pin, active)",
                	"$business_id,$owner_id,'$currdate[year]', 
			now(), '$usern', '$stat', 
			0,'For Assessment', '$pin', 1");
	}


	$wil = UpdateQuery($dbtype,$dbLink,"tempbusnature","active=0",
			"tempid=".$tempid);

	$bp = UpdateQuery($dbtype,$dbLink, $permittable,"active=0",
                        "owner_id=$owner_id and business_id=$business_id
                        and active=1");
	$clr=1;
	}

	if ($clr == 1) {
	$dt = DeleteQuery($dbtype,$dbLink,"tempassess",
	                "owner_id=$owner_id and business_id=$business_id
			and active=1 and transaction='$stat'");

?>
	<body onLoad='javascript: opener.location.reload(true); window.close()'></body>
<?php
	}

}
?>	
