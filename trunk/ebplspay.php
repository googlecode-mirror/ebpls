<?php
require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");
require_once("ebpls-php-lib/utils/ebpls.search.funcs.php");
global $ThUserData;
require_once "includes/variables.php";
include("lib/multidbconnection.php");
$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);
?>
<link rel="stylesheet" href="stylesheets/calendar.css?random=20051112" media="screen"></LINK>
<SCRIPT type="text/javascript" src="javascripts/calendar.js?random=20060118"></script>
	
<link rel="stylesheet" href="stylesheets/default.css" type="text/css"/>
<script language='Javascript' src='javascripts/default.js'></script>
<script language="Javascript">
function CancelOR(or) {
var d = document._FRM;	
	doyou = confirm("Cancel OR and re-issue a new one?");
	
	if (doyou==true) {
		newor = prompt("Enter new OR Number","");
		if (isBlank(newor)) {
			alert ("Invalid OR Number");
			
		} else {
			reasoncan = prompt("Enter reason for cancellation");
				if (isBlank(reasoncan)) {
					alert ("Please Incput Valid Reason");
				} else {
					parent.location = 'ebplspay.php?reasoncan=' + reasoncan + '&new_or=1&or_new=' + newor + '&old_or=' + or;
				}
		}
	}
	
}


function changemo() {
var _FRM = document._FRM;	
x =  _FRM.amtpaid.value - _FRM.amtpay.value ;

if (isNaN(x)) {
	
	_FRM.change.value = "Invalid Amt";
	
} else {

_FRM.change.value  = Math.round(x*100)/100;
return true;
}
	
}

function checkValidPay(cmd)
{
         var _FRM = document._FRM;
                var msgTitle = "Payment\n";
                
                 if(isBlank(_FRM.orno.value))
	                {
	                        alert( msgTitle + "Please input a valid or number!");
	                        _FRM.orno.focus();
	                        return false;
	                } 
	                
	                if(_FRM.orno.value.length>15)
	                {
	                        alert( msgTitle + "OR Number exceeds max length!");
	                        _FRM.orno.focus();
	                        _FRM.orno.select();
	                        return false;
	                }
                
                
                
                if (cmd=='CASH') {
                
                 
	                
	                if(isNaN(_FRM.amtpaid.value) || _FRM.amtpaid.value<1 || _FRM.amtpaid.value<_FRM.amtpay.value)
	                {
	                        alert( msgTitle + "Please input a valid amount!");
	                        _FRM.amtpaid.focus();
	                        _FRM.amtpaid.select();
	                        return false;
	                } 
	                
	                
	                if(_FRM.amtpaid.value.length>9)
	                {
	                        alert( msgTitle + "Amount exceeds max length!");
	                        _FRM.amtpaid.focus();
	                        _FRM.amtpaid.select();
	                        return false;
	                }
	                
	                
	              
	                x = _FRM.amtpaid.value - _FRM.amtpay.value;
	                _FRM.change.value  = Math.round(x*100)/100;
	                _FRM.payit.value='SAVE';
	                _FRM.submit();
	                return true;
                }
                
                if (cmd=='CHECK') {
	              //Set the two dates
	              
	              if (isBlank(_FRM.check_no.value)) {
		              alert ("Please input valid check number");
		              _FRM.check_no.focus();
		              _FRM.check_no.select();
		              return false;
	              }
	              
	              if (_FRM.check_no.value.length>20) {
		              alert ("Check number exceeds max length");
		              _FRM.check_no.focus();
		              _FRM.check_no.select();
		              return false;
	              }
	              
	              
	              
	               if (isBlank(_FRM.check_name.value)) {
		              alert ("Please input valid bank name");
		              _FRM.check_name.focus();
		              _FRM.check_name.select();
		              return false;
	              }
	              
	              
	              if (_FRM.check_name.value.length>30) {
		              alert ("Bank name exceeds max length");
		              _FRM.check_name.focus();
		              _FRM.check_name.select();
		              return false;
	              }
	              
	              
	              
	              
	              if (isBlank(_FRM.checkid.value)) {
		              alert ("Please input valid check date");
		              return false;
	              }
	              
	              
	              
	             // var yrstr = new String(_FRM.checkid.value); 
    			//	yrstr = yrstr.replace(/[^0-9]/g, ''); 

    				var yrstr = _FRM.checkid.value;
	     
	              		var yr = yrstr.substr(0,4);
	              	
	              		//check if "-" month
	              		var das = yrstr.substr(7,1);
	              			if (isNaN(das)) {
		              			var mnth = yrstr.substr(5,2);
              				} else {
	              				var mnth = yrstr.substr(5,1);
              				}
		    	
              			//check if "-" dy
	              		var das = yrstr.substr(7,1);
	              			if (isNaN(das)) {
		              			var dy = yrstr.substr(8,2);
              				} else {
	              				var dy = yrstr.substr(7,2);
              				}	
              		
 	              var millennium =new Date(yr, mnth - 1, dy) 

					today=new Date()
// 					//Get 1 day in milliseconds
 					var one_day=1000*60*60*24
				
// 					//Calculate difference btw the two dates, and convert to days
 					var delay =(Math.ceil((today.getTime()-millennium.getTime())/(one_day)))
 					
 						if (delay > 180) {
	 						alert ("Check date cannot be late than 180 days");
	 						return false;
 						}
 					_FRM.payit.value='SAVE';
	                _FRM.submit();
	                return true;
 					
            }
	                
	          if (cmd=='CHECKSTATUS') { 
		          _FRM.payit.value='SAVE';
	                _FRM.submit();
	                return true;
                }     
                
}
</script>
<?php

if ($new_or==1) {
	
	$getdel = mysql_query("select * from ebpls_transaction_payment_or_details where
							or_no = '$old_or'");
	$getdet = mysql_fetch_assoc($getdel);
	
	$business_id = $getdet[payment_id];
	$owner_id = $getdet[trans_id];
	$cmd = $getdet[or_entry_type];	
	$or_no = $old_or;
	
	$upor = mysql_query("update ebpls_transaction_payment_or set payment_code='$or_new' where
							or_no='$or_no'");
	$upor = mysql_query("update comparative_statement set or_no='$or_new' where
							or_no='$or_no'");						
	$insor = mysql_query("insert into cancel_or values('','$or_no', '$or_new', '$reasoncan',now(),'$usern')");
	
	?>
<body onLoad='window.open("ebplsreceipt.php?owner_id=<?php echo $owner_id; ?>&business_id=<?php echo $business_id;?>&or_no=<?php echo $or_no; ?>&cmd=<?php echo $cmd; ?>&paymde=<?php echo $paymde; ?>&nature_id=<?php echo $nature_id; ?>&amtpay=<?php echo $amtpay; ?>");opener.location.reload(true); window.close();'></body>
<?php
						
}


$amtpay = round($amtpay,2);
$amtpay1 = number_format($amtpay,2);
if ($paymde=='Per Line') {
	$perline = 1;
	$txtlock='readonly';
	$fee = $feelne;
} else {
	$perline = 0;
	$txtlock='';
}
$chkreference = SelectMultiTable($dbtype,$dbLink,"ebpls_buss_preference",
		"sassess, staxesfees","");
$chkreference = FetchRow($dbtype,$chkreference);
$getmax = SelectMultiTable($dbtype,$dbLink,"ebpls_transaction_payment_or",
		"or_no","");
$getor = NumRows($dbtype,$getmax);
$or_no = $getor + 1;
if ($pmode=='QUARTERLY' and $paymde<>'Per Line' and $chkreference[0]=1 and $chkreference[1]<>1){
	$divfee=$fee/4;
	$divexempt=$exemption/4;
} elseif ($pmode=='SEMI-ANNUAL' and $paymde<>'Per Line' and $chkreference[0]=1 and $chkreference[1]<>1) {
	$divfee=$fee/2;
	$divexempt=$exemption/2;
} else {
	$divfee=$fee;
	$divexempt=$exemption;
}
$betstax=$amtpay - ($fee + $sbacktax);
	
	if ($cmd=='CHECKVIEW' || $cmd=='CASHVIEW') {

		$re1 = SelectDataWhere($dbtype,$dbLink,"comparative_statement",
				"where owner_id='$owner_id' and business_id='$business_id' and
				or_no='$nature_id'");
		$re = FetchRow($dbtype,$re1);
		$ntax = $re[7];
		$fee = $re[8];
		$penamt = $re[9];
		$surcharge = $re[10];
		$exemption = $re[12];	
		$sbacktax = $re[14];
	}
?>
<input type = hidden name=pensked value='<?php echo $pensked; ?>'>
<input type = hidden name=cmd value='<?php echo $cmd; ?>'>
<div align=right><a href="javascript: opener.location.reload(true); window.close();"><b>Close this Window [X]</b></a></div>
<br><br>
<table border=1 align=center>
	<tr>
		<td colspan=2 align=center> Break Down </td>
	</tr>
	<tr>
		<td>Tax : </td>
		<td align=right>&nbsp;<?php echo number_format($ntax,2);?></td>
	</tr>
	<tr>
		<td>Fee : </td>
		<td align=right>&nbsp;<?php echo number_format($fee,2);?></td>
	</tr>
	<!--<tr>
		<td>Exemption : </td>
		<td align=right>&nbsp;<?php echo number_format($exemption,2);?></td>
	</tr>-->
	<tr>
		<td>Surcharge : </td>
		<td align=right>&nbsp;<?php if ($penamt=='') {$penamt=0;} echo number_format($penamt,2);?></td>
	</tr>
	<tr>
		<td>Interest : </td>
		<td align=right>&nbsp;<?php if ($surcharge=='') {$surcharge=0;} echo number_format($surcharge,2);?></td>
	</tr>
	<tr>
		<td>Back Taxes : </td>
		<td align=right>&nbsp;<?php if ($sbacktax=='') {$sbacktax=0;} echo number_format($sbacktax,2);?></td>
	</tr>
</table>

<?php
if ($cmd=='CASH') {
?>
        <title>Cash Payment</title>
        <form name="_FRM" method="POST"  action ="">
        <table border=1 align=center><br>
	<tr>
        <td>Payment For:</td>
        <td align=right><input type=hidden name=pay_mode value='<?php echo $paymde; ?>' size=10>
        <?php echo $paymde; ?>&nbsp; Payment
        </tr>
	
        <tr>
        <td>Control Number:</td>
        <td align=right><input type=hidden name=ctl_no value=<?php echo $or_no; ?> size=10>
        <?php echo $or_no; ?>
	<input type=hidden name=perline value=<?php echo $perline; ?>>
	<input type=hidden name=nat_id value=<?php echo $nature_id; ?>>
        </tr>
        <tr>
        <td>OR Number:</td>
        <td align=right><input type=text name=orno value='' size=10>
        </tr>
        <tr>
        <td>Amount To Pay:</td>
        <td align=right><input type=hidden name=amtpay value=<?php echo $amtpay; ?> size=10>
        <?php echo $amtpay1; ?>
        </tr>
        <tr>
        <td>Cash Tendered:</td>
        <td align=right><input type=text align=right name=amtpaid value=<?php echo $amtpay; ?>
	size=10  <?php echo $txtlock; ?> onkeyup='changemo();'>
        </tr>
        <tr>
        <td align=right>Change:</td>
        <td align=right><input type=text align=right name=change value=0 size=10 readonly>
        </tr>
        </table>

<?php
} elseif ($cmd=='CHECK') {
?>
        <title>Check Payment</title>
        <form name="_FRM" method="POST"  action ="">
        <table border=1 align=center><br><br>
        <tr>
        <td>Payment For:</td>
        <td align=right><input type=hidden name=pay_mode value='<?php echo $paymde; ?>' size=10>
        <?php echo $paymde; ?>&nbsp; Payment
        </tr>

	<tr>
        <td>Control Number:</td>
        <td align=right><input type=hidden name=ctl_no value=<?php echo $or_no; ?> size=10>
        <?php echo $or_no; ?>
	<input type=hidden name=perline value=<?php echo $perline; ?>
	<input type=hidden name=nat_id value=<?php echo $nature_id; ?>>
        </tr>
        <tr>
        <td>OR Number:</td>
        <td align=right>Will Not Issue Until Cleared
	<?php
	$tempor = 'tempor'.rand(0000, 9999);
	?>
	<input type=hidden name=orno value='<?php echo $tempor; ?>' size=10>
	</td>
        </tr>
	<tr>
        <td>Check Number:</td>
        <td align=right><input type=text name=check_no value='' size=10>
        </tr>
	<tr>
        <td>Bank Name:</td>
        <td align=right><input type=text name=check_name value='' size=20>
        </tr>
        <tr>
        <td>Amount To Pay:</td>
        <td align=right><input type=hidden name=amtpay value=<?php echo $amtpay; ?> size=10>
        <?php echo $amtpay1; ?>
        </tr>
        <tr>
        <td>Check Amount:</td>
        <td align=right><input type=text align=right name=amtpaid value=<?php echo $amtpay; ?> size=10
          <?php echo $txtlock; ?> readonly>
        </tr>
        <tr>
        <td align=right>Check Issued Date:</td>
	<td align=right>
<!--	<input type=text align=right  name=checkdate value='' size=10 readonly>-->
	<input type="text" class='text180' value="<?php echo $datarow[checkid] ?>" readonly name="checkid" onclick="displayCalendar(checkid,'yyyy/mm/dd',this);">
<img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date" onclick ="displayCalendar(_FRM.checkid,'yyyy/mm/dd',this);_FRM.changeondin.value=1;">
       </tr>
        <tr>
        <td align=right>Remarks:<input type=hidden align=right name=change value=0> </td>
        <td>
	<textarea NAME=remark ROWS=3
                       COLS=19></textarea>
	</td>
	</tr>
	</table>
        
<?php
} elseif ($cmd=='CASHVIEW') {

$getcashview = SelectMultiTable($dbtype,$dbLink,"ebpls_transaction_payment_or a, 
			ebpls_transaction_payment_or_details b",
			"a.ts_create, a.payment_code,a.or_no,
	                 total_amount_due, a.payment_officer, b.linepaid, b.nat_id", 
               		"where a.or_no=b.or_no and b.trans_id=$owner_id and 
			 b.or_entry_type='CASH' and b.payment_id=$business_id 
			 and a.or_no=$or1");
$getid = FetchRow($dbtype,$getcashview);
if ($getid[5]==1) {
	$lynpyd='Per Line';
	$nat_id=$getid[6];
} else {
	$lynpyd='';
	$nat_id='';
}
?>
        <title>Cash Payment Details</title>
        <form name="_FRM" method="POST"  action ="">
        <table border=1 align=center><br><br>
	<tr>
        <td>Payment Date:</td>
        <td align=right><?php echo $getid[0]; ?>
        </tr>
        <tr>
        <td>Control Number:</td>
        <td align=right><?php echo $getid[2]; ?>
        </tr>
        <tr>
        <td>OR Number:</td>
        <td align=right><?php echo $getid[1]; ?>
        </tr>
        <tr>
        <td>Amount Paid:</td>
        <td align=right><?php echo $getid[3]; ?>
        </tr>
        <tr>
        <td>Received By:</td>
        <td align=right><?php echo $getid[4]; ?>
        </tr>
        </table>

<?php
} elseif ($cmd=='CHECKVIEW' || $cmd=='CHECKSTATUS') {

	if  ($cmd=='CHECKSTATUS') {
		$exte = 'and a.check_id=';
		$ali = 'center';
	} else {
		$exte = 'and a.or_no=';
		$ali='left';
	}


$getchek = SelectMultiTable($dbtype,$dbLink,"ebpls_transaction_payment_check a, 
			ebpls_transaction_payment_or b, ebpls_transaction_payment_or_details c",
			"a.check_no, a.check_issue_date, a.check_name, a.check_amount,
                        b.ts_create, b.payment_code, a.check_status,a.ts_clear,
                        b.payment_officer, a.admin, a.check_id, a.remark, 
			b.or_no, c.linepaid, c.nat_id",
			"where a.or_no=b.or_no and a.or_no=c.or_no and b.or_no=c.or_no and
                        c.or_entry_type='CHECK' and c.trans_id=$owner_id 
			and c.payment_id=$business_id $exte $or1");

$getcheck=FetchRow($dbtype,$getchek);

if ($getcheck[13]==1) {
        $lynpyd='Per Line';
	$nat_id=$getcheck[14];
} else {
     	$lynpyd='';
}	$nat_id='';

?>
        <title>Check Payment</title>
        <form name="_FRM" method="POST"  action ="">
        <table border=1 align=<?php echo $ali; ?>><br><br>
        <tr>
        <td>Control Number:</td>
        <td><?php $or_no= $getcheck[12]; echo $getcheck[12]; ?>
        </tr>
        <tr>
        <td>OR Number:</td>
		<?php
                        if ($cmd=='CHECKSTATUS') {
                ?>
                        <td><input type=text name=orno value='' size=10></td>
                <?php
                        } else {
                ?>
		        <td><?php echo $getcheck[5]; ?>
		<?php } ?>
        </tr>
        <tr>
        <td>Check Number:</td>
        <td><?php echo $getcheck[0]; ?>
        </tr>
        <tr>
        <td>Bank Name:</td>
        <td><?php echo $getcheck[2]; ?>
        </tr>
        <tr>
        <td>Amount Paid:</td>
        <td align=right><?php echo number_format($getcheck[3],2); ?>
        </tr>
        <tr>
        <td>Check Issued Date:</td>
        <td><?php echo $getcheck[1]; ?>
        </tr>

	<tr>
        <td>Received By:</td>
        <td><?php echo $getcheck[8]; ?>
        </tr>
	<tr>
        <td>Date Received:</td>
        <td><?php echo $getcheck[4]; ?>
        </tr>
	<tr>
        <td>Check Status:</td>

		<?php
			if ($cmd=='CHECKSTATUS') {
		?>
			<td><select name=changestat>
			    	<option value='PENDING'>PENDING </option>
			    	<option value='CLEARED'>CLEARED </option>
				<option value='BOUNCED'>BOUNCED </option>	
			    </select>
			</td>
		<?php
			} else {
		?>
	        <td><?php echo $getcheck[6]; ?>
		<?php
			}
		?>
        </tr>
	<tr>
        <td>Status Change By:</td>
        <td><?php echo $getcheck[9]; ?>
        </tr>
	<tr>
        <td>Date Status Changed:</td>
        <td><?php echo $getcheck[7]; ?>
        </tr>


        <tr>
        <td align=right>Remarks:<input type=hidden align=right name=change value=0> </td>
        <td>
	<textarea NAME=remark ROWS=3 COLS=19><?php echo $getcheck[11]; ?></textarea>
        </td>
        </tr>
        </table>

<?php
}
        if ($payit=='SAVE') {
                if ($amtpaid==0 || $amtpaid=='' || $orno=='' and $cmd<>'CHECKSTATUS' and $cmd<>'CHECK' || !is_numeric($amtpaid)) {
?>
	<!--	<body onLoad='javascript:alert ("Please Input Valid Data");'>
		</body>-->
<?php
		} else {

			$paymade=1;
			if ($cmd<>'CHECKSTATUS') {
				
	                	require_once "includes/cashpayment.php";
	                	
			} else {


                        //check for existing or
$checkor = SelectDataWhere($dbtype,$dbLink,"ebpls_transaction_payment_or",
                "where payment_code='$orno'");
$checkor = NumRows($dbtype,$checkor);
                                                                                                               
if ($checkor>0) {
?>
        <body onload='javascript:alert("Existing OR Found!");'></body>
<?php
	$paymade = 0;
	$changestat = '';
	$guddah = 1;
}



IF ($changestat<>'CLEARED') { 
	$guddah = 1;
}
                                                                                        
				if ($changestat=='CLEARED') {                      
			//update comparative
			$gettempor = SelectMultiTable($dbtype,$dbLink,
					"ebpls_transaction_payment_check a,
                                         ebpls_transaction_payment_or b","b.payment_code",
					"where a.check_id=$or1 and a.or_no=b.or_no");
			$gettempor = FetchRow($dbtype,$gettempor);
			$upcs = UpdateQuery($dbtype,$dbLink,"comparative_statement", 
					"or_no = '$orno', paid = 0",
					"owner_id=$owner_id and 
					business_id=$business_id and
					or_no = '$gettempor[0]'");
 
        		$clearcheck = UpdateQuery($dbtype,$dbLink,
					"ebpls_transaction_payment_check a,
					 ebpls_transaction_payment_or b",
                        		"a.check_status ='$changestat', a.ts_clear=now(),
                                  	 a.admin='$usern', a.remark='$remark',
					 b.payment_code='$orno'",
 					"a.check_id=$or1 and a.or_no=b.or_no");
				} else {
			
						if ($guddah<>1) {
			$clearcheck = UpdateQuery($dbtype,$dbLink,
					"ebpls_transaction_payment_check",
                                        "check_status ='$changestat', ts_clear=now(),
                                         admin='$usern', remark='$remark'",
                                        "check_id=$or1");
						}
				}

			if ($guddah<>1) {
			$updateit = UpdateQuery($dbtype,$dbLink, $permittable,
					"steps='For Releasing'",
	                                "owner_id=$owner_id and business_id=$business_id");
			}
							IF ($changestat=='CLEARED') { 
?>
                        <body onLoad='window.open("ebplsreceipt.php?owner_id=<?php echo $owner_id; ?>&business_id=<?php echo $business_id;?>&or_no=<?php echo $or_no; ?>&cmd=<?php echo $cmd; ?>&paymde=<?php echo $paymde; ?>&nature_id=<?php echo $nature_id; ?>&amtpay=<?php echo $amtpay; ?>");opener.location.reload(true); window.close();'></body>
<?php	
							} else {
							?>
                        <body onLoad=' window.close();'></body>
<?php	
						}
			}

//onload
if ($guddah<>1 and $von<>1) {

$updatebusnature=UpdateQuery($dbtype,$dbLink,"tempbusnature",
                 "recpaid=1","owner_id=$owner_id and business_id=$business_id and active='1'");
?>
<div align=center><a href="ebplsreceipt.php?owner_id=<?php echo $owner_id; ?>&business_id=<?php echo $business_id;?>&or_no=<?php echo $or_no; ?>&cmd=<?php echo $cmd; ?>&paymde=<?php echo $paymde; ?>&nature_id=<?php echo $nature_id; ?>&amtpay=<?php echo $amtpay; ?>">Print OR</a></div>
<?php
}
                }
        }
?>

<?php

if ($paymade==1 and $von<>1) {
        $disabit = 'disabled';
} else {
        $disabit = '';
}


if ($cmd=='CASHVIEW' || $cmd=='CHECKVIEW') {
?>
<table border=0 align=center><br>
<tr><td><a href="ebplsreceipt.php?owner_id=<?php echo $owner_id; ?>&business_id=<?php echo $business_id;?>&or_no=<?php echo $or1; ?>&cmd=<?php echo $cmd; ?>&paymde=<?php echo $lynpyd; ?>&nature_id=<?php echo $nat_id; ?>">Re-Print OR</a>  &nbsp;|

<a href="#" onclick='CancelOR(<?php echo $or1; ?>);'>Cancel OR</a>


<?php
} else {
	
?>
<table border=0 align=center><br>
<tr><td>
<input type=hidden name=payit>
<input type=button  name=payit1 value='SAVE' <?php echo $disabit; ?> onclick='checkValidPay("<?php echo $cmd; ?>");'>
<input type=button name=canit value=CLOSE onClick='javascript: opener.location.reload(true); window.close()'>
<input type=reset value=RESET>
</td></tr></table>
<br>
                                                                                                 
<?php
}

