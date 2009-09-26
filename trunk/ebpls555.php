<?php
//	@eBPLS_PAGE_APP_OWNER: owner criteria page
//	- start page for owner search
require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");
require_once("ebpls-php-lib/ebpls.owner.class.php");

require_once("ebpls-php-lib/utils/ebpls.search.funcs.php");

//--- get connection from DB
$dbLink = get_db_connection();
global $ThUserData;

$debug 		= false;

$status_str   	= "";

//--- paging params
$page 		= (strlen(trim($page))==0) ? (1) : ($page);
$maxpage 	= 20;


//--- get the owner
$clsOwner 	= new EBPLSOwner ( $dbLink, $debug );





?>
<html>
<title>Search Owner</title>
<head>
<link rel="stylesheet" href="stylesheets/default.css" type="text/css"/>
<script language='Javascript' src='javascripts/default.js'></script>
</head>
<body>
<div align='center'>
<table border=0 cellspacing=0 cellpadding=0 width='720'>
<tr><td align="center" valign="center" class='header'> Owner Search</td></tr>
<tr><td align="center" valign="center" class='normal' height=10>&nbsp;</td></tr>
<tr>
	<td align="center" valign="center" class='title'>
	<form name="_FRM" method="POST" action="" onSubmit='document._FRM._search_.value="1"'>
		
		<!---// start of the table //-->
		<table border=0 cellspacing=0 cellpadding=0  width='720'>
			<tr><td align="center" valign="top" class='subtitleblue' colspan=2>&nbsp;</td>
			<tr><td align="center" valign="center" class='title' height=5 colspan=2></td></tr>
			<tr>
				
              <td align="left" valign="top" class='normalbold' colspan=2 height=15> 
                <div align="center">SEARCH BY 
                  <input type='hidden' name='_search_' >
                  <input type='hidden' name='_search_owner_id_' >
                </div></td>
				 
			</tr> 
			<tr>
				<td align="right" valign="top" class='normal' width=353>
					Lastname :
				</td>
				<td width="367" align="left" valign="top" class='normal'>
					&nbsp;<input type='text' name='search_lastname' maxlength=255 class='text180' value='<?php echo $search_lastname; ?>'>
				</td>
			</tr> 
			<tr>
				<td align="right" valign="top" class='normal'>
					Middlename :
				</td>
				<td align="left" valign="top" class='normal'>
					&nbsp;<input type='text' name='search_middlename' maxlength=255 class='text180' value='<?php echo $search_middlename; ?>'>
				</td>
			</tr> 
			<tr>
				<td align="right" valign="top" class='normal'>
					Firstname :
				</td>
				<td align="left" valign="top" class='normal'>
					&nbsp;<input type='text' name='search_firstname' maxlength=255 class='text180' value='<?php echo $search_firstname; ?>'>
				</td>
			</tr> 
			<tr>
				<td align="right" valign="top" class='normal' colspan=2><img src='images/spacer.gif' height=20 width=5>
				</td>
			</tr>
			<tr>
				
              <td align="center" valign="top" class='normal' > <div align="right">
                  <input type='submit' name='_GO' onClick='' value=' S E A R C H ' >
                </div></td>
              <td align="left" valign="top" class='normal' > &nbsp; &nbsp;
			<input type='reset' name='_RESET' value=' R E S E T ' >&nbsp;
				</td>
			</tr>
			<tr>
				<td align="right" valign="top" class='normal' colspan=2>&nbsp; 
				</td>
			</tr>

		</table>
		<!---// end of the table //-->
		<?php
			$total_records = 0;
		if($_search_ == 1)
		{
		?>
		<!---// start of the table //-->
			<?php
				// search( $fname, $mname, $lname, $email, $bdate , $page, $maxrec = 10, $orderkey = OWNER_REG_DATE, $is_desc = true ) 
				$retValue = $clsOwner->search( $search_firstname, $search_middlename, $search_lastname, NULL, NULL, $page, $maxpage, OWNER_REG_DATE , true );					
				//--- get the total records
				$total_records 	= count($retValue["result"]);

				//--- headers
				$search_columns[OWNER_LAST_NAME] 		= "Lastname";
				$search_columns[OWNER_FIRST_NAME] 		= "Firstname";
				$search_columns[OWNER_MIDDLE_NAME] 		= "Middlename";
				$search_columns[OWNER_HOUSE_NO] 		= "House #";
				$search_columns[OWNER_STREET] 			= "Street #";
				$search_columns[OWNER_EMAIL_ADDRESS] 	= "Email";
				$search_columns[OWNER_GENDER] 			= "Gender";
				$search_columns[OWNER_CIVIL_STATUS] 	= "Status";
				$search_columns[OWNER_BIRTH_DATE] 		= "Date of Birth";

				//--- update_delete_chart_of_accounts('$code');
				function createCommand($rec)
				{
					$code	= $rec->getData(OWNER_ID);
					//return	  "<input type='radio' name='sys_owner_id' value='$code' onClick=\"attach_owner_details('$code')\">";
					return    "<a href=\"ebpls1001.php?ctc_type=INDIVIDUAL&owner_id=$code\" onClick=\"javascript:attach_owner_details('$code');\"><font color=red>Attach To Application</font></a>";
				}

				//--- display the result
				print_search_results(
							"555",
							$HTTP_GET_VARS,
							"&nbsp;",
							$search_columns,
							$retValue,
							"createCommand"
							);
			if($total_records == 0 )
			{
				echo '<table border=0 cellspacing=0 cellpadding=0><tr><td height=30><input type="button" onClick="javascript:showNewWin(\'owner_add.php\',820,500);" value="Add New Owner"></td></tr></table>';
			}
			else
			{
				//echo '<tr><td align="center" valign="center" class="errmsg" height=10 colspan=7 bgcolor="#ffffff">&nbsp;<input type="button" name="_ATTACH" onClick="set_attach_owner_details('.$total_records.')" value=" A T T A C H " ></td></tr>';
			}
			?>
		<?php
		}
		?>
		<!---// end of the table //-->
		</form>
	</td>
</tr>
<tr><td align="center" valign="center" class='title' height=10></td></tr>
</table>
</div>
</body>
</html>

<script language='Javascript'>

 
function attach_owner_details(ownerid)
{
	<!--var _FRM = document._FRM;
	var win_opener 	= window.opener;
	var _doc 	= win_opener.document;


	//sys_owner_id
	_FRM._search_owner_id_.value=ownerid;-->


	<!--_doc._FRM.action='index.php?part=4&itemID_=<?php echo $part;?>&ctc_type=INDIVIDUAL&permit_type=CTC&busItem=CTC';-->
	<!--_doc._FRM.action='ebpls1001.php?ctc_type=INDIVIDUAL&owner_id='+ ownerid;-->
	<!--_doc._FRM.child_reload_owner_id.value=_FRM._search_owner_id_.value;-->
	<!--_doc._FRM.child_reload.value='reload';
	_doc._FRM.submit();-->
	self.close();
	<!--return true;-->

}



function set_attach_owner_details(what)
{
	var _FRM = document._FRM;
	
	var msgTitle 	= "Owner Search\n";

	var win_opener 	= window.opener;
	var _doc 	= win_opener.document;

	var max_len  	= (_FRM.sys_owner_id) ? _FRM.sys_owner_id.length : 0;

	var ok	     	= 0;
	
	for(var i=0;i<max_len;i++)
	{
		if(_FRM.sys_owner_id[i].checked == true)
		{
			ok = 1;
			break;
		}
	}
	
	if(ok == 0)
	{
		if(_FRM.sys_owner_id.checked == false)
		{
			alert(msgTitle + "Please click a valid owner!");
			return false;
		}
	}
	if(what > 1 && ok == 0)
	{
		alert(msgTitle + "Please click a valid owner!");
		return false;
	}
	else
	{
		
 
		
		<!--_doc._FRM.action='index.php?part=4&itemID_=<?php echo $part;?>&permit_type=CTC&busItem=CTC&ctc_type=<?php echo $ctc_type; ?>';-->
		_doc._FRM.action='ebpls1001.php?ctc_type=INDIVIDUAL';
		_doc._FRM.child_reload.value='reload';
		_doc._FRM.child_reload_owner_id.value=_FRM._search_owner_id_.value;
		
		_doc._FRM.submit();
		self.close();
		return true;
	}
	return true;
}

 
function return2_parent(id)
{
	var win_opener 	= window.opener;
	var _doc 	= win_opener.document;

	_doc._FRM.child_reload.value='reload';
	_doc._FRM.child_reload_owner_id.value=id;
	_doc._FRM.submit();
	self.close();
	return true;
}

</script>
