<?php
require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");
require_once("ebpls-php-lib/utils/ebpls.search.funcs.php");
global $ThUserData;
require_once "includes/variables.php";
include("lib/multidbconnection.php");
$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);
$user_id = $ThUserData[id];
include"includes/bizlevel.php";

		$get = mysql_query("select * from ebpls_business_enterprise a, ebpls_owner c
							where a.owner_id='$owner_id' and
                            a.business_id='$business_id' and
                            a.owner_id=c.owner_id ");
        $d = mysql_fetch_assoc($get);
        $fullname = ucfirst($d["owner_first_name"])." ".ucfirst($d["owner_middle_name"])." ".ucfirst($d["owner_last_name"]);
        $bussname = ucfirst($d["business_name"]);      
               
?>
<script language='Javascript' src='includes/datepick/datetimepicker.js'></script>
<link rel="stylesheet" href="stylesheets/default.css" type="text/css"/>


<div align=right><a href="javascript:window.close();"><b>Close this Window [X]</b></a></div>
<br><br>
<script language="Javascript">
function PaymentCommand(cmd,amt,paymde,paypart,or,natid,pens,bustax,busfee, buspen, busint,nbacktax)
{
	//var trans_id
        var x,y,w,h
     //   trans_id =  document._FRM.trans_id.value;
        // center on screen
        if ( cmd == 'CASH' ) {
                w = 400
                h = screen.height - 100
        } else if ( cmd == 'CHECK' ) {
                w = 600
                h = screen.height - 100
        }  else if ( cmd == 'CASHVIEW' ) {
                w = 400
                h = screen.height - 100
        }  else if ( cmd == 'CHECKVIEW' ) {
                w = 600
                h = screen.height - 100
        }  else if ( cmd == 'CHECKSTATUS' ) {
                w = 600
                h = screen.height - 200
        }



        x = screen.width/2 - w/2
        y = screen.height/2 - h/2
        strOption = 'toolbar=0,location=0,directories=0,menubar=0,resizable=0,scrollbars=1,status=1,width=' + w + ',height=' + h + ',screenX=' + x + ',screenY=' + y
      window.open ("ebplspay.php?&nature_id="+ natid + "&or1=" + or +"&paymde="+ paymde + "&owner_id=<?php echo $owner_id; ?>&permit_type=Business&istat=<?php echo $stat; ?>&pensked="+ pens +"&class_type=Permits&business_id=<?php echo $business_id; ?>&paypart="+ paypart + "&amtpay="+ amt + "&cmd=" + cmd  + "&fee=" +  busfee + "&ntax=" +  bustax + "&penamt=" + buspen + "&surcharge=" + busint + "&exemption=<?php echo $totalexptax;?>&pmode=<?php echo $pmode;?>&paympart=" + paymde + "&sbacktax=" + nbacktax, cmd, strOption);

}

</script>
<form name='_FRM'>
<table border=0 align=left>	
	<tr>
	<td> Owner Name: </td><td><?php echo stripslashes($fullname); ?></td>
	</tr>
	<tr>
	<td> Business Name: </td><td><?php echo stripslashes($bussname); ?></td>
	</tr>
</table>
<br><br>
<table border=0 align=center>
	<tr>
		<td><h2>Application History </h2></td>
	</tr>
	<tr><td></td></tr>
</table>
<?php

		$get = mysql_query("select distinct(for_year), business_permit_code, application_date,
							transaction, steps, released, released_date from ebpls_business_enterprise_permit 
							where owner_id='$owner_id' and released_date not like '0%' and
                            business_id='$business_id' order by business_permit_id");
?>

<table border=0 width="70%">
<?php
	while ($d = mysql_fetch_assoc($get)) {
?>	
	<tr>
	<td align="right" width=25%> Permit Number: </td>
	<td width=25%> <?php echo $d["business_permit_code"]; ?></td>
	<td align="right" width=25%> Application Date: </td>
	<td width=25%> <?php echo $d["application_date"]; 
	$yearend =$d["for_year"]."-12-31 23:59:59";
	?></td>
	</tr>	
	
	<tr>
	<td align="right"> Transaction: </td>
	<td> <?php echo $d["transaction"]; ?></td>
	<td align="right"> Status: </td>
	<td> <?php echo $d["steps"]; ?></td>
	</tr>
	<?php
		if ($d["released"]) {
	?>
	<tr>
	<td align="right" width=25%>  Released Date </td>
	<td width=25%> <?php echo $d["released_date"]; ?></td>
	</tr>
	<tr><td colspan=4 align="center">Payment History</td></tr>
	<tr>
	<td colspan=4 align="center">
	<?php
			$getphis = mysql_query("select * from ebpls_transaction_payment_or_details a, 
									ebpls_transaction_payment_or b where
									a.or_no=b.or_no and
									a.trans_id='$owner_id' and a.payment_id='$business_id' and
									a. ts between '$d[application_date]' and '$yearend'");
			while ($p = mysql_fetch_assoc($getphis)) {
	?>
		<table border=0>
		<tr>
		<td align="right" width=20%>OR Number: </td>
		<td width=20%><?php echo $p["payment_code"]; ?></td>
		<td align="right" width=20%>Payment Date: </td>
		<td width=20%><?php echo $p["or_date"]; ?></td>
		</tr>
		<tr>
		<td align="right" width=20%>Payment Type: </td>
		<td><?php echo $p["or_entry_type"]; ?></td>
		<td align="right" width=20%>Amount: </td>
		<td><?php echo number_format($p["amount_due"],2); ?></td>
		</tr>
		<?php
		if ($p["or_entry_type"]=='CHECK') {
			$gs = mysql_query("select * from ebpls_transaction_payment_check where
								or_no='$p[or_no]'");
			$gr = mysql_fetch_assoc($gs);
			
		?>
		<tr>
		<td align="right" width=20%>Bank Name: </td>
		<td><?php echo $gr["check_name"]; ?></td>
		<td align="right" width=20%>Check Number: </td>
		<td><?php echo $gr["check_no"]; ?></td>
		</tr>
		<tr>
		<td align="right" width=20%>Check Date: </td>
		<td><?php echo $gr["check_issue_date"]; ?></td>
		<td align="right" width=20%>Status: </td>
		<td><?php echo $gr["check_status"]; ?></td>
		</tr>
		<?php
			if ($bpay==1 ||  $ulev==6 || $ulev==7 and $gr["check_status"]!='CLEARED') {
?>			<tr>
		<td align="right" width=20%>Change Check Status: </td>
		<td>
		<?php
		if ($gr["check_status"]!='CLEARED') {
?>
	<a href='#' onClick='javascript:PaymentCommand("CHECKSTATUS",1,2,"3", "<?php echo $gr[check_id]; ?>", "<?php echo $p[payment_code]; ?>");'>
	<font color=blue>Change Status</font></a>
<?php

			}
	}
?>
		</td>
		</tr>
<?php
			}
		?>
		</table>							
<?php
			}
?>
	</td>
	</tr>
	<tr>
	<td colspan=4><hr></td>
	</tr>
<?php
		}
	}
?>
</table>
<br><br>
<table border=0 align=center>
	<tr><td>
<input type=button name=canit value=CLOSE onClick='javascript: window.close()'>
	</td></tr>
</table>
</form>

