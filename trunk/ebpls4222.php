<?php
/*  Purpose: display assessment
*/
$total_sf_compute = isset($total_sf_compute) ? $total_sf_compute : 0; //2008.05.15 RJC Undefined variable

$result = SelectMultiTable($dbtype,$dbLink,"$owner, ebpls_business_enterprise",
		"concat($owner.owner_first_name,' ', $owner.owner_middle_name, ' ', 
		$owner.owner_last_name) as full, ebpls_business_enterprise.business_name, 
		ebpls_business_enterprise.business_payment_mode",
		"where $owner.owner_id=$owner_id and 
		ebpls_business_enterprise.owner_id=$owner_id and
		ebpls_business_enterprise.business_id=$business_id");
$list = FetchRow($dbtype,$result);

$cntrec = SelectDataWhere($dbtype,$dbLink,"tempassess",
		"where owner_id=$owner_id and business_id=$business_id
		and active=1 and transaction='$stat' and date_create like '$yearnow%'");
$cnt = NumRows($dbtype,$cntrec);
if ($cnt<>0){ $PROCESS="COMPUTE"; }
?>

<link rel="stylesheet" href="stylesheets/default.css" type="text/css"/>
<script language='Javascript' src='javascripts/default.js'></script>
<div align="CENTER">
<!---// start of the table //-->
<table border=0 cellspacing=0 cellpadding=0   width='100%'>
                <tr><td align="center" valign="center" class='header'  width='100%'> Assessment for <?php echo ucfirst($permit_type); ?> Permit</td></tr>
                <tr><td align="center" valign="center" class='normal' height=10>&nbsp;</td></tr>
                <tr>
                        <td align="center" valign="center" class='normal'>
                          <form name="_FRM" method="POST" action='index.php?part=4&class_type=Permits&itemID_=4212&owner_id=<?php echo $owner_id;?>&business_id=<?php echo $business_id?>&istat=<?php echo $stat; ?>&busItem=Business&stat=<?php echo $stat; ?>'>
                                        <input type='hidden' name='tx_mode'>
                                        <input type='hidden' name='tx_code' value='<?php echo $tx_code;?>'>
					
                                        <input type='hidden' name='permit_no' value='<?php echo $permit_no;?>'>
                                        <input type='hidden' name='owner_id' value='<?php echo $owner_id;?>'>
                                        <input type='hidden' name='business_id' value='<?php echo $business_id;?>'>
                                        <input type='hidden' name='permit_type' value='<?php echo $permit_type;?>'>
                                   <input type='hidden' name='method_of_application' value='<?php echo $method_of_application;?>'>
					<table border=0 cellspacing=1 cellpadding=1 width = 100%>
					<tr><td align=left>OWNER: <?php echo stripslashes($list[0]); ?> </td>
					<td align=left>BUSINESS: <?php echo stripslashes($list[1]); ?> </td>
					<input type=hidden name = pmode value= <?php echo $list[2]; ?>>
					<td align=left>PAYMENT MODE:<?php echo $list[2]; ?> </td></tr>
					</table>
<input type=hidden name=newpred value="<?php echo $newpred; ?>">
<input type=hidden name=noregfee value="<?php echo $noregfee; ?>">
<br>
<?php

$chkbacktax = SelectDataWhere($dbtype,$dbLink,"ebpls_buss_preference","");
$chkbacktax = FetchArray($dbtype,$chkbacktax);
if ($chkbacktax['sbacktaxes']=='1' and $stat=='Retire') {
	$tftnum=1;
        $htag = 'Assessment';
        $tft = '';
        require "includes/headerassess.php";
        require_once "includes/assessment.php";
	$total_tax_compute = $grandamt;
	$howmany = $df;

?>
<input type='hidden' name='wala_lang'>
<?php
} else {
	$gettag=SelectDataWhere($dbtype,$dbLink,"ebpls_buss_preference","");
	$gettag=FetchArray($dbtype,$gettag);
	$pmode = $list[2];
	$lockit = '';
	if ($gettag['sassess']=='') {
		$tftnum = 1;
		//$tft = ' and c.taxfeetype=1'; // or c.taxfeetype=4';
		$htag = 'Assessment';
		require "includes/headerassess.php";
		$totexempt=0;
		require "includes/assessment.php";
		$total_tax_compute = $grandamt;
		$howmany = $df;
		$tftnum=4;
		$grandamt=0;

	// 	$tft = ' and c.taxfeetype=4';// and c.taxfeetype=4';
	//         $htag = 'Assessment of Special Fees';
	//         require "includes/headerassess.php";
	//         $totexempt=0;
	//         require "includes/assessment.php";
	// $total_sf_compute = $grandamt;
	// $howmany = $df+$howmany;
		if ($noregfee <>1) {
		
			$tft =' and c.taxfeetype<>1';// or c.taxfeetype<>4';
			$htag = 'General Charges';
			require "includes/headerassess.php";
			
			//if ($stat !='Retire') {
			require "includes/feeassess.php";
			//}
		}

	} else {
		$tftnum=1;
		$htag = 'Assessment';
		$tft = '';
		require "includes/headerassess.php";
		require_once "includes/assessment.php";
		$total_tax_compute = $grandamt;
		$howmany = $df;
	}
}

?>


<br>

</table>

<input type = hidden name = howmany value = <?php echo $howmany; ?>>

<?php 

if ($gettag['sassess']=='1') {
$te=0;
//$totfee=0;
} else {
$te = $totfee;
}
$grandamt = $total_tax_compute + $total_sf_compute + $fee;
//$grandamt=$grandamt+$totfee-$te;
$grand=$grandamt;
?>
<table border=0 width=100%>
<tr><td width=25%></td>
<td align=right width=50%><b>Total Taxes, Fees and Other Charges :</b></td>
<td align=right bgcolor=blue>
<font color=white>Php &nbsp;<?php echo $ga=number_format($grand,2);$vart = $grand; ?></font></td></tr>
</table><br>
<!--
<br><table border=1 width=100%>
<tr><td>Total Tax Due</td><td><font color=red><?php echo $ga=number_format($grand,2); ?></font> </td></tr>
</table>-->
<input type='hidden' name='grand' value='<?php echo $grand;?>'>
<?php
if ($gettag['sassess']=='') {

$ota = $grandamt;
}
require "includes/paymentsched.php";
//}
?>

<table border=0 cellspacing=0 cellpadding=1 width='90%'><br>
<tr>
<td>
<br><div align=center>
<?php
if ($reloadna<>1) {
	?>
<body onload="_FRM.reloadna.value=1; _FRM.submit();"></body>
<?php
}
?>
<input type=hidden name='reloadna'>
<input type='hidden' name='tax1' value='<?php echo $totaltax;?>'>
<input type='hidden' name='pan' value='<?php echo $buspen;?>'>
<input type='hidden' name='intn' value='<?php echo $busint;?>'>
<input type='hidden' name='totalwaive' value='<?php echo $totalexptax;?>'>
<input type='hidden' name='bakn' value='<?php echo $nbacktax;?>'>
<input type='hidden' name='tftotal' value='<?php echo $totaltaxfee;?>'>
<input type=hidden name=chcap value="<?php echo $capt; ?>">

<input type=submit name='PROCESS' value='REASSESS'>
<input type=submit name='PROCESS' value='SAVE'>
<input type='button' name='printrec' value='PRINT' onClick='javascript:AssRec("<?php echo $owner_id; ?>","<?php echo $business_id; ?>","<?php echo $stat; ?>", "<?php echo $itemID_; ?>","<?php echo $usern; ?>");' >
<?php

	if ($bpapp==1 ||  $ulev==6 || $ulev==7) {
?>
	<!--<input type=submit name='appbut' value='APPROVAL'>-->
	<input type=button name='appbut' value='APPROVAL' onclick='Approv();'>
<?php
	}

if ($ttax=='') {
	$ttax=0;
}

if ($penamt=='') {
	$penamt=0;
}

	if ($PROCESS=='SAVE' || $appbut=='APPROVAL') {
	$nfyear = date('Y');
	}

	if ($appbut=='APPROVAL') {
?>
	<body onload="parent.location='index.php?part=4&newpred=<?php echo $newpred; ?>&noregfee=<?php echo $noregfee; ?>&class_type=Permits&itemID_=5212&owner_id=<?php echo $owner_id; ?>&com=approve&permit_type=<?php echo $permit_type;?>&stat=<?php echo $stat; ?>&business_id=<?php echo $business_id; ?>&busItem=Business&istat=<?php echo $stat; ?>'"></body>
<?php
	}

?>
</div></td></tr></table>
<script language='Javascript' src='javascripts/default.js'></script>
<script language="Javascript">
function AssRec(x, y, z, i,us)
{
winpopup = window.open('reports/bus_assess.php?gtot=<?php echo $gtotind; ?>&permit_type=Business&owner_id='+ x + '&business_id='+ y + '&stat='+ z + '&itemID_='+ i + '&usernm=' + us);
}

function Approv() {
parent.location='index.php?part=4&class_type=Permits&newpred=<?php echo $newpred; ?>&noregfee=<?php echo $noregfee; ?>&itemID_=5212&owner_id=<?php echo $owner_id; ?>&com=approve&permit_type=<?php echo $permit_type;?>&stat=<?php echo $stat; ?>&business_id=<?php echo $business_id; ?>&busItem=Business&istat=<?php echo $stat; ?>';
}
</script>



<script language='Javascript' src='javascripts/default.js'></script>
<script language="Javascript">
function ComputeTax(ex)
{

	if (isNaN(ex.value)) 
		{
		alert ("Please Input Valid Number");
//		ex.focus();
		}
        else 
		{
		var2.value=ex; // * zay;
		}	
}

</script>

