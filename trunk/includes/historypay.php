</script>
<?php
$htag='Payment History';
require "includes/headerassess.php";
?>
<table border=1 align =center width=100% class=sub>
<tr><td colspan=5>
<b>Cash Payment</b></td>
<tr><th>Date Of Payment</th>
<th>OR/Control Number</th><th>Amount Paid</th><th>Received By</th></tr>
<?php
//get cash first
$getcas = SelectMultiTable($dbtype,$dbLink,"ebpls_transaction_payment_or a, 
			ebpls_transaction_payment_or_details b", 
			"a.ts_create, concat(a.payment_code,'/',a.or_no), 
			a.total_amount_paid, a.payment_officer, a.or_no, a.total_amount_due,
			a.payment_code, a.or_no",
			"where a.or_no=b.or_no and b.trans_id=$owner_id and 
			b.or_entry_type='CASH' and
			b.payment_id=$business_id and b.transaction='$istat'");
$totcash=0;
while ($getcash=FetchRow($dbtype,$getcas))
{
        if (date('Y',strtotime($getcash[0]))==date('Y')) {
$getcashnf = number_format($getcash[5], 2);
print "<tr><td>$getcash[0]</td><td align=right>$getcash[1]</td><td align=right>$getcashnf</td>
<td align=center>$getcash[3]</td>";
?>
<td><a href='#' onClick='javascript:PaymentCommand("CASHVIEW",1,2,"3","<?php echo $getcash[4]; ?>","<?php echo $getcash[6]; ?>",0,"<?php echo $getcash[6]; ?>");'>
<font color=blue>View Payment Details</font></a></td>
</tr>
<?php
$totcash = $totcash+$getcash[5];			 
$totc = number_format($totcash, 2);
	}
}
?>
<tr><br></tr>
<tr><td></td><td align=right>Total Cash Payment:</td>
<td align=right><?php echo $totc; ?></td></tr>
</table>
<table border=1 align =center class=sub>
<tr><td colspan=5><b>Check Payment</b></td></tr>
<tr>
<th width=20%>Bank Name</th>
<th width=20%>Amount</th>
<th width=20%>OR/Control Number</th>
<th width=20%>Cleared</th><th width=20%> &nbsp;</th></tr>
<?php
$getchek = SelectMultiTable($dbtype,$dbLink,"ebpls_transaction_payment_check a, 
			ebpls_transaction_payment_or b, ebpls_transaction_payment_or_details c",
			"a.check_no, a.check_issue_date, a.check_name, a.check_amount, 
			b.ts_create, concat(b.payment_code, '/',b.or_no), 
			a.check_status,a.ts_clear, b.payment_officer, 
			a.admin, a.check_id, a.remark, b.or_no, b.payment_code",
			"where a.or_no=b.or_no and a.or_no=c.or_no and b.or_no=c.or_no and
			c.or_entry_type='CHECK' and c.transaction='$istat' and
			c.trans_id=$owner_id and c.payment_id=$business_id");
$s=0;
while ($getcheck=FetchRow($dbtype,$getchek))
{
	if (date('Y',strtotime($getcheck[4]))==date('Y')) {
$getchecknf = number_format($getcheck[3], 2);                                                                                                               
print "<tr>
<td>$getcheck[2]</td><td align=right>$getchecknf</td>
<td>$getcheck[5]</td><td>$getcheck[6]</td>
<td>";

	if ($getcheck[6]<>'CLEARED' and $statpin=='') {
?>
	<a href='#' onClick='javascript:PaymentCommand("CHECKSTATUS",1,2,"3", "<?php echo $getcheck[10]; ?>", "<?php echo $getcash[6]; ?>");'>
	<font color=blue>Change Status</font></a>
<?php

	}

	if ($getcheck[6]<>'PENDING' and $statpin=='') {
?>
	<a href='#' onClick="javascript:PaymentCommand('CHECKVIEW',1,2,'3', <?php echo $getcheck[12]; ?>,0,0,'<?php echo $getcheck[13]; ?>')"><font color=blue>View Payment Details</font></a>
<?php
	}
?>
</td>
<tr>
<?
$totscheck = $totscheck+$getcheck[3];
$s++;
	}
}

$getclear = SelectMultiTable($dbtype,$dbLink,"ebpls_transaction_payment_check a, 
			ebpls_transaction_payment_or b,
                        ebpls_transaction_payment_or_details c","sum(a.check_amount)",
                        "where a.or_no=b.or_no and a.or_no=c.or_no and b.or_no=c.or_no and
                        c.or_entry_type='CHECK' and a.check_status='CLEARED' and
			c.transaction='$istat' and
                        c.trans_id=$owner_id and c.payment_id=$business_id");
$totcheck = FetchRow($dbtype,$getclear);
print "<tr><br></tr>";
$totchecknf = number_format($totscheck, 2);
print "<tr><td></td><td></td><td align=right>Total Check Payment:</td><td align=right>$totchecknf</td></tr>";
$totpay =$totcash+$totscheck;
?>
</table>
<table border =0 align=left class=sub>
<tr><td></td><td>Total Payments Made:</td><td><font color=red><b>Php <?php echo number_format($totpay,2);?><b></font></td><td></td>
</table>

<?php

	if ($bpar==1 ||  $ulev==6 || $ulev==7 and  $totpay>0) {

		$ge = SelectDataWhere($dbtype,$dbLink,"havereq",
                                        "where owner_id=$owner_id and
                                        active=1 and business_id=$business_id");
                $ge = NumRows($dbtype,$ge);
                $getreq = SelectDataWhere($dbtype,$dbLink,"ebpls_buss_requirements",
                                        "where recstatus='A' and reqindicator=1 and permit_type='Business'");
                $gt = NumRows($dbtype,$getreq);
		$gettag= SelectDataWhere($dbtype,$dbLink,"ebpls_buss_preference","");
		$getre=FetchArray($dbtype,$gettag);
		
		$checkapp = mysql_query("select * from ebpls_buss_approve a where
                						a.owner_id='$owner_id' and
                                        a.decision=1 and a.business_id=$business_id");
                $checka = mysql_num_rows($checkapp);
                if ($checka==0) {
	                $gud=0;
	                
				} else {
                
					if ($getre[srequire]==1) {
                                                                                                 
                        if ($ge==$gt) {
                                $gud = 1;
                        } else {
								$gud=0;
                        }
	                } else {
	                        $gud = 1;
	                }
                }
?>
<table align=center border=0><br><br><br>
<tr><td>
<?php

		if ($gud==1) {
?>
	<input type=button value=RELEASING onclick="parent.location='index.php?part=4&class_type=Permits&itemID_=3212&owner_id=<?php echo $owner_id; ?>&business_id=<?php echo $business_id; ?>&stat=<?php echo $stat; ?>&com=PrintReport&permit_type=Business&busItem=Business'">
<?php
		} else {
			
			 $checkapp = mysql_query("select * from ebpls_buss_approve a where
                						a.owner_id='$get_info[0]' and
                                        a.decision=1 and a.business_id=$get_info[1]");
                $checka = @mysql_num_rows($checkapp);
                
               if ($checka==0) {
	            		 ?>
                                <td>
                                
                                
                          <font color=red> Not yet approve. Cannot Proceed To Releasing</font>
                                
                                
                                </td>
                            <?php
                                        
            	} else {
			
			
			
			
?>
		Incomplete Requirements. Cannot Proceed To Releasing</font>
		&nbsp 
		<a href='#' onclick="IncReq('<?php echo $owner_id; ?>','<?php echo $business_id; ?>');">View Details</a>
<?php				
				}
		}
?>
	</td></tr></table>
<?php
	}
?>



