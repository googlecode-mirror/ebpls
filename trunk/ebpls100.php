<?php
//	@eBPLS_PAGE_APP_INPUT1 : this is the business permit application page
//				-  will input the enterprise permit application
require_once("lib/ebpls.lib.php");

require_once("ebpls-php-lib/ebpls.transaction.class.php");
require_once("ebpls-php-lib/ebpls.enterprise.class.php");
require_once("ebpls-php-lib/ebpls.enterprise.permit.class.php");
require_once("ebpls-php-lib/ebpls.transaction.requirements.class.php");
require_once("ebpls-php-lib/ebpls.owner.class.php");

require_once("ebpls-php-lib/ebpls.taxfeeref.class.php");
require_once("ebpls-php-lib/ebpls.permitdefaultrequirements.class.php");

require_once("ebpls-php-lib/ebpls.transaction.paymentschedule.class.php");
require_once("ebpls-php-lib/ebpls.transaction.fees.class.php");


require_once("lib/ebpls.utils.php");


//--- get connection from DB
$dbLink = get_db_connection();
global $ThUserData;
$debug 		= false;

//--- paging params
$startPage 	= 1;
$endPage 	= 20;

$req_type  		= 'new';
$bus_type		= 'BUS';


//--- get the parameters
$tx_code		= trim($tx_code);
$status_of_application	= trim($status_of_application);
$permit_type		= trim($permit_type);
$permit_no		= trim($permit_no);
$owner_id		= trim($owner_id);
$method_of_application	= trim($method_of_application);

$is_new = true;

//--- check first the method method_of_application NEW

$submit_button_name = 'S U B M I T';

if(! strcasecmp($child_reload,'reload'))
{
	//log_err("refresh");
	//--- get the owner
	$clsOwner 	= new EBPLSOwner ( $dbLink, $debug );

	$clsOwner->view(trim($child_reload_owner_id));
	$owner_datarow 	= $clsOwner->getData();

	//--- get the business name
	if(strlen(trim($child_reload_permit_no)) > 0 )
	{
		$clsEnterprise  = new EBPLSEnterprise ( $dbLink, $debug );
		$permit_id 	= $clsEnterprise->view(trim($child_reload_permit_no));
		$datarow       	= $clsEnterprise->getData();

		//--- get the business_nature_list
		$business_nature_list = $clsEnterprise->getBusinessNatureList($datarow[BUSINESS_ID]);
	}
}


//--- chk the sublevels
/*if(   ! is_valid_sublevels(27) or ! is_valid_sublevels(28) )
 {
 	setUrlRedirect('index.php?part=999');
	
 } 
*/
?>
<link rel="stylesheet" href="stylesheets/default.css" type="text/css"/>
<script language='Javascript' src='javascripts/default.js'></script>
<script language='Javascript'>

function validate_ctc_application(_url)
{
		var _FRM = document._FRM;
		var msgTitle = "Business Permit Application\n";

		if( isBlank(_FRM.owner_first_name.value) == true)
		{
			alert( msgTitle + "Please input a valid firstname!");
			_FRM.owner_first_name.focus();
			return false;
		}
		if( isBlank(_FRM.owner_middle_name.value) == true)
		{
			alert( msgTitle + "Please input a valid middlename!");
			_FRM.owner_middle_name.focus();
			return false;
		}
		if( isBlank(_FRM.owner_last_name.value) == true)
		{
			alert( msgTitle + "Please input a valid lastname!");
			_FRM.owner_last_name.focus();
				return false;
		}
		if( isBlank(_FRM.owner_id.value) == true)
		{
			alert( msgTitle + "Please add owner first by clicking either Search link!");
			return false;
		}
		 
		/* 
		//--- validate the business details
		if( isBlank(_FRM.business_name.value) == true)
		{
			alert( msgTitle + "Please input a valid business name!");
			_FRM.business_name.focus();
			return false;
		}
		
		if( isBlank(_FRM.business_id.value) == true)
		{
			alert( msgTitle + "Please add business first by clicking either Search link!");
			return false;
		}
		*/
		
		 _FRM.action=_url;

		 _FRM.submit();
		 		 
	 return true;
}


</script>
<div align="CENTER">
<!---// start of the table //-->
<table border=0 cellspacing=0 cellpadding=0   width='100%'>
		<tr><td align="center" valign="center" class='header'  width='100%'>Community Tax Certificate Tax Payer Search</td></tr>
		<tr><td align="center" valign="center" class='normal' height=10>&nbsp;</td></tr>
		<tr>
			<td align="center" valign="center" class='title'>
			  <form name="_FRM" method="POST" action="" onSubmit="">
					<input type='hidden' name='cmd' value='CTC1'>
					<input type='hidden' name='tx_code' value='<?php echo $tx_code;?>'>
					<input type='hidden' name='status_of_application' value='<?php echo $status_of_application;?>'>
					<input type='hidden' name='permit_type' value='<?php echo $permit_type;?>'>
					<input type='hidden' name='method_of_application' value='<?php echo $method_of_application;?>'>

					<input type='hidden' name='sub_method_of_application' value='<?php echo $sub_method_of_application;?>'>

					<input type='hidden' name='permit_no' value='<?php echo $permit_no;?>'>

					<input type='hidden' name='child_reload_permit_no' value='<?php echo $child_reload_permit_no;?>'>
					<input type='hidden' name='child_reload' value='<?php echo $child_reload;?>'>
					<input type='hidden' name='child_reload_owner_id' value='<?php echo $child_reload_owner_id;?>'>

					<input type='hidden' name='child_reload_tax' value='<?php echo $child_reload_tax;?>'>
					<input type='hidden' name='child_reload_tax_details_id' value='<?php echo $child_reload_tax_details_id;?>'>

					<input type='hidden' name='child_reload_fee' value='<?php echo $child_reload_fee;?>'>
					<input type='hidden' name='child_reload_fee_details_id' value='<?php echo $child_reload_fee_details_id;?>'>
<?php 
if ($ctc_type=='INDIVIDUAL')
	{
?>
          <table border=0 cellspacing=1 cellpadding=1 width='100%'>
            <!--// start of the owner information //-->
            <tr>
              <td align="center" valign="top" class='header2' colspan=4 >
                Owner Information</td>
            </tr>
            <tr>
	        <td align="right" valign="top" class='normal' colspan=4>&nbsp; </td>
            </tr>
            <tr>
              <td align="right" valign="top" class='normal' colspan=1> &nbsp;
                 </td>
              <td align="left" valign="top" class='normal' colspan=3>&nbsp;
                <?php
			//--- check if new  / renew , then have a search button
			$owner_pop_id = $owner_datarow[OWNER_ID];

			if($is_new)
			{
				echo "&nbsp<a href='javascript:showNewWin(\"ebpls555.php?part=100&mode=new&view_owner_id=$owner_pop_id;\",700,500);' class='subnavwhite'>Search</a>&nbsp";
				if($owner_pop_id > 0)
					echo "&nbsp<a href='javascript:showNewWin(\"owner_update.php?part=100&owner_id=$owner_pop_id&mode=new\",820,500);' class='subnavwhite'>Update</a>&nbsp;";
			}
			else
			{
				echo "&nbsp<a href='javascript:showNewWin(\"owner_update.php?part=100&owner_id=$owner_pop_id&mode=renew\",820,500);' class='subnavwhite'>Update</a>&nbsp";
			}
		?>
		<input type='hidden' name='owner_id' maxlength=25 class='text180'  value="<?php echo $owner_pop_id; ?>">
              </td>
            </tr>
            <tr>
              <td align="right" valign="top" class='normal' width='16.67%'> <font color="#FF0000">*
                </font>First Name : </td>
              <td align="left" valign="top" class='normal' width='33.33%'>&nbsp; <input type='text' name='owner_first_name' maxlength=60 class='text180'  value="<?php echo $owner_datarow[OWNER_FIRST_NAME]; ?>" readonly>
              </td>
              <td align="right" valign="top" class='normal'> <font color="#FF0000">*
                </font>Middle Name : </td>
              <td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='owner_middle_name' maxlength=60 class='text180' value="<?php echo $owner_datarow[OWNER_MIDDLE_NAME]; ?>" readonly>
              </td>
            </tr>
            <tr>
              <td align="right" valign="top" class='normal'> <font color="#FF0000">*
                </font>Last Name : </td>
              <td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='owner_last_name' maxlength=60 class='text180' value="<?php echo $owner_datarow[OWNER_LAST_NAME]; ?>" readonly>
              </td>
              <td align="right" valign="top" class='normal'>Gender : </td>
              <td align="left" valign="top" class='normal'>&nbsp; <select name='owner_gender' class='select100' readonly disabled>
                  <option value='M' <?php echo (! strcasecmp($owner_datarow[OWNER_GENDER],'M')) ? ('selected') : (''); ?> >M</option>
                  <option value='F' <?php echo (! strcasecmp($owner_datarow[OWNER_GENDER],'F')) ? ('selected') : (''); ?>>F</option>
                </select> </td>
            </tr>
     	</table>
            <!--// end of the owner information //-->
            
<?php
	} else {
?>            
		<table border=0 cellspacing=1 cellpadding=1 width='100%'>
            <!--// start of the business permit information //-->
            <tr>
              <td align="right" valign="top" class='normal' colspan=4>&nbsp; </td>
            </tr>
            <tr>
              <td align="center" valign="top" class='subtitleblue' colspan=4 >
                Bussiness Enterprise Information</td>
            </tr>
            <tr>
              <td align="right" valign="top" class='normal' colspan=4>&nbsp; </td>
            </tr>
            <tr>
              <td align="right" valign="top" class='normal'> &nbsp; </td>
              <td align="left" valign="top" class='normal' >&nbsp;
              <?php
              	$business_pop_id = $datarow['business_id'];
		if($is_new)
		{
		?>
			&nbsp<a href='javascript:showNewWin("business_search.php?part=100&mode=new&view_business_id=<?php echo $business_pop_id; ?>&owner_id=<?php echo $owner_pop_id;?>",700,500);' class='subnavwhite'>Search</a>&nbsp
		<?php
			if($business_pop_id > 0)
				echo "&nbsp<a href='javascript:showNewWin(\"business_update.php?part=100&mode=new&business_id=$business_pop_id&owner_id=$owner_pop_id\",700,500);' class='subnavwhite'>Update</a>&nbsp";
		}
		else
		{
			echo "&nbsp<a href='javascript:showNewWin(\"business_update.php?part=100&business_id=$business_pop_id&mode=renew&owner_id=$owner_pop_id\",700,500);' class='subnavwhite'>Update</a>&nbsp";
		}
		?>

              	<input type='hidden' name='business_id' maxlength=25 class='text180'  value="<?php echo $datarow['business_id']; ?>">
                 </td>
              <td align="right" valign="top" class='normal'  > &nbsp;</td>
              <td align="left" valign="top" class='normal'>&nbsp;
              </td>
            </tr>
            <tr>
	      	  <td align="right" valign="top" class='normal'> <font color="#FF0000">*
                </font>Business Name :  </td>
              <td align="left" valign="top" class='normal'>&nbsp;&nbsp;<input type='text' name='business_name' maxlength=255 class='text180'  value="<?php echo $datarow[BUSINESS_NAME]; ?>" readonly> </td>
              <td align="right" valign="top" class='normal'> &nbsp; </td>
              <td align="right" valign="top" class='normal'> &nbsp; </td>
              <td align="right" valign="top" class='normal'> &nbsp; </td>
              <td align="right" valign="top" class='normal'> &nbsp; </td>
              <td align="right" valign="top" class='normal'> &nbsp; </td>
             </tr>
            <tr>
              <td align="right" valign="top" class='normal' >
              </td>
              <td align="left" valign="top" class='normal'>              	
                </td>
              <td align="right" valign="top" class='normal'  ></td>
              <td align="left" valign="top" class='normal'>&nbsp;</td>
            </tr>
            <tr>
              <td align="right" valign="top" class='normal' colspan=4>&nbsp; </td>
              <!--// end of the Franchise permit information //-->
            
<?php
	}
?>
            <tr>
              <td align="center" valign="top" class='normal' colspan=4> &nbsp;
                &nbsp; <input type='BUTTON' name='_PROCESS' onClick='return validate_ctc_application("<?php /*echo(getURI(eBPLS_PAGE_CTC_CRITERIA)*/?>?part=4&itemID_=101&class_type=CTC&busItem=CTC<?php/*);*/?>");' value='<?php echo $submit_button_name; ?>'>
                &nbsp; <input type='reset' name='_RESET' onClick='window.location="index.php?part=4&itemiD_=101&cmd=CTC3"' value='R E N E W' >
              </td>
            </tr>
          </table>
	</form>
	</td>
       </tr>
<tr><td align="center" valign="center" class='title' height=10></td></tr>
</table>
<!---// end of the table //-->
</form>
</div>
</body></html>
