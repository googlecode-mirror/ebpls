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

//--- get the parameters
$mode			= trim($mode);
$business_id		= trim($business_id);
$rad_business_nature	= trim($rad_business_nature);
$rad_business_nature_list_details= trim($rad_business_nature_list_details);

if(! strcasecmp($mode,'edit'))
{
	$rec_list_bnc_details = @preg_split("/\|/",$rad_business_nature_list_details);
	$ntr_1 = $rec_list_bnc_details[2];
	$ntr_2 = $rec_list_bnc_details[3];
	$ntr_3 = $rec_list_bnc_details[4];
	$ntr_4 = $rec_list_bnc_details[5];
}
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>eBPLS Business Permit and Licensing System</title>
	<meta name="Author" content=" Pagoda, Ltd. ">
<link rel="stylesheet" href="stylesheets/default.css" type="text/css"/>
<script language='Javascript' src='javascripts/default.js'></script>
</head>
<body>
<div align="CENTER">
<!---// start of the table //-->
<br>
<table border=0 cellspacing=0 cellpadding=0   width='750'>
		<tr><td align="center" valign="center" class='titleblue'  width='750'> Business Enterprise Permit Application</td></tr>
		<tr><td align="center" valign="center" class='normal' height=10>&nbsp;</td></tr>
		<tr>
		<td align="center" valign="center" class='title'>
		  <form name="_FRM" method="POST" action="" onSubmit="">
				<table border=1 cellspacing=1 cellpadding=1 width='520'>
					<tr><td align="center" valign="top" class='subtitleblue' colspan=4 > Line of Business</td></tr>

					<tr>
						<td align="right" valign="top" class='normal' colspan=1> &nbsp;
							Business Nature :
						</td>
						<td align="left" valign="top" class='normal' colspan=3> &nbsp;
							<?php 
								if(! strcasecmp($mode,'add'))
									echo get_select_data($dbLink,'business_nature','ebpls_business_nature','business_nature_code','business_nature_desc','');
								if(! strcasecmp($mode,'edit'))	
								{
									list($kud,$nim) = @split(':',$rad_business_nature) ;
									echo strtoupper($nim);
									
								}
							?>  
								<input type='hidden' name='line_bussiness_code_selected'   class='text180'  value="<?php echo $kud; ?>"> 
								<input type='hidden' name='line_bussiness_name_selected'   class='text180'  value="<?php echo $nim; ?>">
						</td>
						
					</tr>
					<tr>
						<td align="right" valign="top" class='normal' colspan=1> &nbsp;
							<font color="#FF0000">* </font>Capital Investment : 
						</td>
						<td align="left" valign="top" class='normal' colspan=3> &nbsp;
							<input type='text' name='business_capital_investment' maxlength=15 class='text180'  value="<?php echo ($ntr_1) ? ($ntr_1) : ('0.00');?>"> 
						</td>
						
					</tr>
					<tr>
						<td align="right" valign="top" class='normal' colspan=1> &nbsp;
							<font color="#FF0000">* </font>Last Year's Investment Cap : 
						</td>
						<td align="left" valign="top" class='normal' colspan=3> &nbsp;
							<input type='text' name='business_last_yrs_cap_invest' maxlength=15 class='text180'  value="<?php echo ($ntr_2) ? ($ntr_2) : ('0.00');?>"> 
						</td>
						
					</tr>
					<tr>
						<td align="right" valign="top" class='normal' colspan=1> &nbsp;
							<font color="#FF0000">* </font>Last Year's Number of Employees : 
						</td>
						<td align="left" valign="top" class='normal' colspan=3> &nbsp;
							<input type='text' name='business_last_yrs_no_employees' maxlength=15 class='text180'  value="<?php echo ($ntr_3) ? ($ntr_3) : ('1');?>"> 
						</td>
						
					</tr>
					<tr>
						<td align="right" valign="top" class='normal' colspan=1> &nbsp;
							<font color="#FF0000">* </font>Last Year's Gross Sales : 
						</td>
						<td align="left" valign="top" class='normal' colspan=3> &nbsp;
							<input type='text' name='business_last_yrs_dec_gross_sales' maxlength=15 class='text180'  value="<?php echo ($ntr_4) ? ($ntr_4) : ('0.00');?>"> 
						</td>
						
					</tr>
					<tr>
						<td align="center" valign="top" class='normal' colspan=4>
						&nbsp;
						</td>
						
					</tr>
					<tr>
						<td align="center" valign="top" class='normal' colspan=4>
						<input type='button' name='process' value='Process' onClick='javascript:procLineOfBusiness("<?php echo $mode; ?>");'>
						</td>
						
					</tr>
				</table>	
			</form>
		</td>
		</tr>
		<tr><td align="center" valign="center" class='title' height=10></td></tr>
</table>
<!--// end of the table //-->
</form>
</div>
</body>
</html>             

<script language='Javascript'>

function procLineOfBusiness(mode)
{
	
	var _FRM 	 = document._FRM;
	var msgTitle 	 = "Line Of Business\n";
	var buff 	 = '';
	with(document)
	{
		
		buff = '';
		buff = _FRM.business_capital_investment.value;
		if( isBlank(buff) == true || isDigit(buff) == false )
		{
			alert(msgTitle + "Please input a valid value!");
			_FRM.business_capital_investment.focus();
			return false;	
		}
		buff = '';
		buff = _FRM.business_last_yrs_cap_invest.value;
		if( isBlank(buff) == true || isDigit(buff) == false  )
		{
			alert(msgTitle + "Please input a valid value!");
			_FRM.business_last_yrs_cap_invest.focus();
			return false;	
		}
		buff = '';
		buff = _FRM.business_last_yrs_no_employees.value;
		if( isBlank(buff) == true || isDigit(buff) == false  )
		{
			alert(msgTitle + "Please input a valid value!");
			_FRM.business_last_yrs_no_employees.focus();
			return false;	
		}
		buff = '';
		buff = _FRM.business_last_yrs_dec_gross_sales.value;
		if( isBlank(buff) == true || isDigit(buff) == false  )
		{
			alert(msgTitle + "Please input a valid value!");
			_FRM.business_last_yrs_dec_gross_sales.focus();
			return false;	
		}
		//--- if valid
		//--- construct a valid data
		/**
		code::
		name::
		capital_inv::
		last_yr_cap_inv::
		last_yr_num_emp::
		last_yr_gross
		**/
		//--- get the list
		// _FRM.rad_business_nature_code_list.value		
		
		
		
	
		if(mode == 'add')
		{
			var idx 		= _FRM.business_nature.options.selectedIndex;
		 	var _selectedCode	= _FRM.business_nature.options[idx].value;
		 	var _selectedName	= _FRM.business_nature.options[idx].text;
			
			
			var old_code_list = window.opener._FRM.rad_business_nature_code_list.value;
			
			//--- LIST FORMAT
			//	CODE|NAME|cap1|cap2|cap3|cap4:
			//	CODE|NAME|cap1|cap2|cap3|cap4:
			
			var _sepRegExp 	  = /\:/g;
			var _codeListArr  = old_code_list.split(_sepRegExp);
			var _totalCode    =_codeListArr.length;
			
			var _sepRegExp2   = /\|/g;
			
			for(var i=0;i<_totalCode;i++)
			{
				
				var _subArr  	   = _codeListArr[i].split(_sepRegExp2);
				var _subtotalCode  = _subArr.length;
				if(_subArr[0] == _selectedCode)
				{
					alert(msgTitle + "Add not allowed, ["+ _selectedCode +"] record already exists!");			
					return false;
				}
					
			}
			//--- add a record to the list
			var new_rec = _selectedCode + '|' + _selectedName + '|' + _FRM.business_capital_investment.value + '|' + _FRM.business_last_yrs_cap_invest.value + '|' + _FRM.business_last_yrs_no_employees.value + '|' + _FRM.business_last_yrs_dec_gross_sales.value ;
			//--- replace the old list
			if(old_code_list == '')
				window.opener._FRM.rad_business_nature_code_list.value = new_rec ;
			else 
				window.opener._FRM.rad_business_nature_code_list.value = old_code_list + ':' + new_rec ;
				
			window.opener.document._FRM.action='index.php?part=230';
			window.opener.document._FRM.submit();
			
			window.close();	
			
		}
		if(mode == 'edit')
		{
			var _selectedCode   = _FRM.line_bussiness_code_selected.value;
			var _selectedName   = _FRM.line_bussiness_name_selected.value;
			
			var old_code_list   = window.opener._FRM.rad_business_nature_code_list.value;
			
			//--- LIST FORMAT
			//	CODE|NAME|cap1|cap2|cap3|cap4:
			//	CODE|NAME|cap1|cap2|cap3|cap4:
			
			var _sepRegExp 	  = /\:/g;
			var _codeListArr  = old_code_list.split(_sepRegExp);
			var _totalCode    =_codeListArr.length;
			
			var _sepRegExp2   = /\|/g;
			var _edited       = false;
			var _subArrEdited = '';
			
			for(var i=0;i<_totalCode;i++)
			{
				
				var _subArr  	   = _codeListArr[i].split(_sepRegExp2);
				var _subtotalCode  = _subArr.length;
				if(_subArr[0] == _selectedCode)
				{
					_edited = true;
					var new_rec = _selectedCode + '|' + _selectedName + '|' + _FRM.business_capital_investment.value + '|' + _FRM.business_last_yrs_cap_invest.value + '|' + _FRM.business_last_yrs_no_employees.value + '|' + _FRM.business_last_yrs_dec_gross_sales.value ;
					_subArrEdited	   = (_subArrEdited == '') ? (new_rec) : (_subArrEdited + ':'  + new_rec);	
				}
				else
				{
					_subArrEdited	   = (_subArrEdited == '') ? (_subArr[i]) : (_subArrEdited + ':'  + _subArr[i]);	
				}
			}
			//--- replace the old list
			window.opener._FRM.rad_business_nature_code_list.value = _subArrEdited ;
			window.opener.document._FRM.action='index.php?part=230';
			window.opener.document._FRM.submit();
			window.close();
		}
	}
}

</script>