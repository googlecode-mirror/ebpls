<?php
//	@eBPLS_PAGE_CTC_INPUT : ctc input page
//	-  will process the ctc from the criteria passed
require_once("ebpls-php-lib/ebpls.ctc.class.php");
require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");


//--- get connection from DB
$dbLink 	 = get_db_connection();
$ctcDebug 	 = false;

$clsCTC 	 = new EBPLSCTC ( $dbLink, $ctcDebug );
$ctcData 	 = null;
$ctcRecords	 = null;
$code_found	 = 0;
$total_ctcRecords= 0;

//--- get the passed parameters from the prev pages
$search_by 		= trim($search_by);
$search_by_what 	= trim($search_by_what);
$status_of_application	= trim($status_of_application);
$method_of_application	= trim($method_of_application);

//--- check first the method method_of_application NEW/RENEW
$is_ctc_renew 	= (! strcasecmp($method_of_application,'renew')) ? true : false;
$process_button = (! strcasecmp($method_of_application,'renew')) ? ('R E N E W   C T C') : ('N E W   C T C');
//---- its a renew but first query the 10 records 
//
//	based on the the search parameter passed
//	either filter it by
//
//		- application status(pending,approved,processed)
//		- search by info(code,firstname,middlename,lastname)
//
//	find( $fname, $mname, $lname, $address, $date_issued, $page, $maxrec = 10, $orderkey = COMM_TAX_CERT_DATE_ISSUED, $is_desc = true ) 

if(strlen($search_by_what) > 0 and $is_ctc_renew)
{
	switch(strtolower(trim($search_by)))
	{
		case 'ctcnumber'://--- search by CTC code
				//log_err("search by : $search_by");
				$code_found 	  = $clsCTC->load($search_by_what);
				$total_ctcRecords = ($code_found == -1) ? (0) : (1);
				break;
		case 'firstname'://--- search by firstname
				//log_err("search by : $search_by");
				$ctcRecords 	  = $clsCTC->find( $search_by_what, NULL, NULL, NULL, NULL , 1, 10, COMM_TAX_CERT_DATE_ISSUED, true );			
				$total_ctcRecords = count($ctcRecords["result"]);
				break;
		case 'middlename'://--- search by middlename
				//log_err("search by : $search_by");
				$ctcRecords 	  = $clsCTC->find( NULL, $search_by_what, NULL, NULL, NULL , 1, 10, COMM_TAX_CERT_DATE_ISSUED, true );
				$total_ctcRecords = count($ctcRecords["result"]);
				break;
		case 'lastname'://--- search by lastname
				//log_err("search by : $search_by");
				$ctcRecords 	  = $clsCTC->find( NULL, NULL, $search_by_what , NULL, NULL , 1, 10, COMM_TAX_CERT_DATE_ISSUED, true );
				$total_ctcRecords = count($ctcRecords["result"]);
				break;
		case 'dateissued'://--- search by dateissued
				//log_err("search by : $search_by");
				$ctcRecords 	  = $clsCTC->find( NULL, NULL,  NULL, NULL ,$search_by_what , 1, 10, COMM_TAX_CERT_DATE_ISSUED, true );
				$total_ctcRecords = count($ctcRecords["result"]);
				break;
		default :
				//log_err("search by : default");
				$ctcRecords 	= $clsCTC->find( NULL, NULL, NULL, NULL, NULL , 1, 10, COMM_TAX_CERT_DATE_ISSUED, true );
				$total_ctcRecords = count($ctcRecords["result"]);
				break;
	}
}
else
{
	//--- its a new application
	//	but first query first the 10 records and the display it
	$ctcRecords 	= $clsCTC->find( NULL, NULL, NULL, NULL, NULL , 1, 10, COMM_TAX_CERT_DATE_ISSUED, true );
	$total_ctcRecords = count($ctcRecords["result"]);
	//log_err("its a new application");
}

?>
<link rel="stylesheet" href="stylesheets/default.css" type="text/css"/>
<script language='Javascript' src='javascripts/default.js'></script>
<div align="CENTER">
<br>
<!---// start of the table //-->
<table border=0 cellspacing=0 cellpadding=0   width='620'>
	<tr><td align="center" valign="center" class='titleblue'  width='620'> Community Tax Certificate Application</td></tr>
	<tr><td align="center" valign="center" class='normal' height=10>&nbsp;</td></tr>
	<tr>
		<td align="center" valign="center" class='title'>
		  <form name="_FRM" method="POST" action="<?php echo(getURI(eBPLS_PAGE_CTC_INPUT)); ?>" onSubmit="return set_ctc_renew_code();">
			<input type='hidden' name='method_of_application' value='<?php echo $method_of_application ?>'>
			<input type='hidden' name='comm_tax_cert_code'>
			<table border=0 cellspacing=1 cellpadding=1 width='620' bgcolor='#808080'>
				<tr>
					<td align="center" valign="center" class='normalbold' width=20 bgcolor='#c0c0c0'>
						&nbsp;
					</td>
					<td align="center" valign="center" class='normalbold' width=80 bgcolor='#c0c0c0'>
						Code
					</td>
					<td align="center" valign="center" class='normalbold'  width=100  bgcolor='#c0c0c0'>
						Firstname
					</td>
					<td align="center" valign="center" class='normalbold'  width=100  bgcolor='#c0c0c0'>
						Middlename
					</td>
					<td align="center" valign="center" class='normalbold' width=100  bgcolor='#c0c0c0'>
						Lastname
					</td>
					<td align="center" valign="center" class='normalbold'  width=60  bgcolor='#c0c0c0'>
						Bday
					</td>
					<td align="center" valign="center" class='normalbold'  width=50  bgcolor='#c0c0c0' >
						Gender
					</td>
					<td align="center" valign="center" class='normalbold' width=50  bgcolor='#c0c0c0'>
						Civil Status
					</td>
					<td align="center" valign="center" class='normalbold' width=50  bgcolor='#c0c0c0'>
						Certificate Type
					</td>
				</tr>
				<?php
				if($total_ctcRecords == 0 )
				{
					echo '<tr><td align="center" valign="center" class="errmsg" height=10 colspan=9 bgcolor="#ffffff"><hr>NO RECORD FOUND !<hr></td></tr>';
				}
				else
				{
					//--- check if renew 
					if($is_ctc_renew and $code_found > 0)
					{
						display_record($clsCTC->getData(),true);
					}
					else
					{
						
						$ctc_records = $ctcRecords["result"];			
						for ( $i = 0 ; $i < count($ctcRecords["result"]) ; $i++ ) 
						{
							display_record($ctc_records[$i]->getData(),$is_ctc_renew);
						}
			
					}
				}
				?>
				<tr>
					<td align="center" valign="center" class="normal" height=10 colspan=9 bgcolor='#ffffff'>
					<img src='images/blank.gif' height=10><br>
					&nbsp;<input type='BUTTON' name='_BACK' onClick='javascript:history.go(-1)' value='B A C K'>
					<?php
					if($total_ctcRecords > 0 )
					{
						echo "&nbsp;<input type='SUBMIT' name='_PROCESS' value='$process_button'>";
					}
					?>
					&nbsp;<input type='reset' name='_RESET' onClick='' value='R E S E T' >
					<br>
					<img src='images/blank.gif' height=10>
					</td>
				</tr>
			</table>
		  </form>
		</td>
	</tr>
	<tr><td align="center" valign="center" class='title' height=10></td></tr>
</table>
<!---// end of the table //-->
</div>

<?php 
//--- display the record
function display_record($datarow,$is_renew)
{
	$action_code = $datarow[COMM_TAX_CERT_CODE];
?>
	<tr>
		<td align="center" valign="center" class='normal'  bgcolor='#ffffff'>
		
		<?php echo ( $is_renew ? ("<input type='radio' name='renew_ctc_code' value='$action_code' onClick='update_ctc_renew_code(\"$action_code\");'>") : ('&nbsp;')); ?>
		</td>
		<td align="center" valign="center" class='normal'  bgcolor='#ffffff'>
		<?php echo ($datarow[COMM_TAX_CERT_CODE] ? ($datarow[COMM_TAX_CERT_CODE]) : ('&nbsp;')); ?>
		</td>	
		<td align="left" valign="center" class='normal'  bgcolor='#ffffff'>
		<?php echo ($datarow[COMM_TAX_CERT_OWNER_FIRST_NAME] ? ($datarow[COMM_TAX_CERT_OWNER_FIRST_NAME]) : ('&nbsp;')); ?>
		
		</td>
		<td align="left" valign="center" class='normal'   bgcolor='#ffffff'>
		<?php echo ($datarow[COMM_TAX_CERT_OWNER_MIDDLE_NAME] ? ($datarow[COMM_TAX_CERT_OWNER_MIDDLE_NAME]) : ('&nbsp;')); ?>
		
		</td>
		<td align="left" valign="center" class='normal'  bgcolor='#ffffff'>
		<?php echo ($datarow[COMM_TAX_CERT_OWNER_LAST_NAME] ? ($datarow[COMM_TAX_CERT_OWNER_LAST_NAME]) : ('&nbsp;')); ?>
		
		</td>
		<td align="center" valign="center" class='normal'   bgcolor='#ffffff'>
		<?php echo ($datarow[COMM_TAX_CERT_OWNER_BIRTH_DATE] ? ($datarow[COMM_TAX_CERT_OWNER_BIRTH_DATE]) : ('&nbsp;')); ?>
		
		</td>
		<td align="center" valign="center" class='normal'   bgcolor='#ffffff'>
		<?php echo ($datarow[COMM_TAX_CERT_OWNER_GENDER] ? (strtoupper(substr($datarow[COMM_TAX_CERT_OWNER_GENDER],0,1))) : ('&nbsp;')); ?>
		
		</td>
		<td align="center" valign="center" class='normal'  bgcolor='#ffffff'>
		<?php echo ($datarow[COMM_TAX_CERT_OWNER_CIVIL_STATUS] ? (strtoupper(substr($datarow[COMM_TAX_CERT_OWNER_CIVIL_STATUS],0,1))) : ('&nbsp;')); ?>		
		
		</td>
		<td align="center" valign="center" class='normal'  bgcolor='#ffffff'>
		<?php echo ($datarow[COMM_TAX_CERT_TYPE] ? ($datarow[COMM_TAX_CERT_TYPE]) : ('&nbsp;')); ?>
		
		</td>
	</tr>
<?php
}
?>