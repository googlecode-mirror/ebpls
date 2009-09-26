<?php
//	@eBPLS_PAGE_CTC_CRITERIA: ctc criteria page
//	- start page for applying ctc

require_once("ebpls-php-lib/ebpls.ctc.class.php");
require_once("ebpls-php-lib/ebpls.global.const.php");
require_once("ebpls-php-lib/ebpls.global.funcs.php");
require_once("ebpls-php-lib/ebpls.owner.class.php");
require_once("ebpls-php-lib/ebpls.enterprise.class.php");
require_once("ebpls-php-lib/utils/ebpls.search.funcs.php");
require_once("ebpls-php-lib/utils/ebpls.html.funcs.php");
require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");
require_once("setup/setting.php");

define(CTC_CMD_NEW,"CTC1");
define(CTC_CMD_RENEW,"CTC2");
define(CTC_CMD_FIND,"CTC3");
define(CTC_CMD_FORM,"CTC4");
define(CTC_CMD_PROCESS,"CTC5");
define(CTC_CMD_ADD,"CTC6");

//print_r($HTTP_POST_VARS);

$dbLink = get_db_connection();
$debug = false;

?>
<link rel="stylesheet" href="stylesheets/default.css" type="text/css"/>
<!--<link rel="stylesheet" href="templates/form_ctc.css" type="text/css"/>-->
<script language='Javascript' src='javascripts/default.js'></script>
<?

if ( isset($business_id) ) {
	$ctc_business_id = $business_id;
}

if ( isset($owner_id) ) {
	$ctc_owner_id = $owner_id;
}

if ( !isset($ctc_business_id) && !isset($ctc_owner_id) && ( $cmd == CTC_CMD_NEW || $cmd == "" ) ) {
?>
<script language='Javascript'>
window.location='<?= getURI(eBPLS_PAGE_CTC_SEARCH); ?>';
</script>
<?
} else if ( $cmd == CTC_CMD_NEW && ( isset($ctc_business_id) || isset($ctc_owner_id)  ) ){	
	
	
	// load onwer info
	$clsOwner = new EBPLSOwner( $dbLink );
	$clsOwner->view( $ctc_owner_id );
	
	$HTTP_POST_VARS["ctc_first_name"] = $clsOwner->getData(OWNER_FIRST_NAME);
	$HTTP_POST_VARS["ctc_middle_name"] = $clsOwner->getData(OWNER_MIDDLE_NAME);
	$HTTP_POST_VARS["ctc_last_name"] = $clsOwner->getData(OWNER_LAST_NAME);
	$HTTP_POST_VARS["ctc_tin_no"] = $clsOwner->getData(OWNER_TIN_NO);
	$HTTP_POST_VARS["ctc_birth_date"] = $clsOwner->getData(OWNER_BIRTH_DATE);
	$HTTP_POST_VARS["ctc_civil_status"] = strtoupper($clsOwner->getData(OWNER_CIVIL_STATUS));
	$HTTP_POST_VARS["ctc_citizenship"] = $clsOwner->getData(OWNER_CITIZENSHIP);
	$HTTP_POST_VARS["ctc_gender"] = $clsOwner->getData(OWNER_GENDER);
	$HTTP_POST_VARS["ctc_address"] = $clsOwner->getData(OWNER_HOUSE_NO) . " " . $clsOwner->getData(OWNER_STREET);	
	//$HTTP_POST_VARS["ctc_icr_no"] = $clsOwner->getData(OWNER_ICR_NO);
	$HTTP_POST_VARS["ctc_owner_id"] = $clsOwner->getData(OWNER_ID);
	
	$ctc_type = "INDIVIDUAL";	

	if ( intval($ctc_business_id) ) {

		$ctc_type = "BUSINESS";

		// load business enterprise info
		$clsBus = new EBPLSEnterprise( $dbLink );
		$clsBus->view( $ctc_business_id );
	
		$HTTP_POST_VARS["ctc_company"] = $clsBus->getData(BUSINESS_NAME);
		$HTTP_POST_VARS["ctc_tin_no"] = $clsBus->getData(BUSINESS_TIN_REG_NO);		
		$HTTP_POST_VARS["ctc_place_of_incorporation"] = $clsBus->getData(BUSINESS_TIN_REG_NO);

		$HTTP_POST_VARS["ctc_company_address "] = $clsBus->getData(BUSINESS_LOT_NO) . " " .  $clsBus->getData(BUSINESS_STREET);
		$HTTP_POST_VARS["ctc_place_issued"] = $clsBus->getData(BUSINESS_TIN_REG_NO);

		$HTTP_POST_VARS["ctc_date_issued"] = $clsBus->getData(BUSINESS_DATE_ESTABLISHED);
		$HTTP_POST_VARS["ctc_business_id"] = $clsBus->getData(BUSINESS_ID);

	}

}

?>
<div align="CENTER">
<!---// start of the table //-->
<table border=0 cellspacing=0 cellpadding=0 width='100%'>
<tr><td align="center" valign="center" class='header'>Community Tax Certificate </td></tr>
<tr><td align="center" valign="center" class='normal' height=10>&nbsp;</td></tr>
<tr>
	<td align="center" valign="center" class='title'>
	<?
		
		print_menu( $cmd );

		switch ( $cmd ) {
			case "":
			case CTC_CMD_NEW :
				{
					//echo "NEW $ctc_type<BR>";
					//print_ctc_type_form( $ctc_type, $cmd );
					print_ctc_form ( $ctc_type, $HTTP_POST_VARS );

				} break;
			case CTC_CMD_PROCESS:
				{

					//echo "PROCESS NEW<BR>";
					print_ctc_viewer ( $HTTP_POST_VARS["ctc_type"], $HTTP_POST_VARS, true );

				} break;
			case CTC_CMD_ADD :
				{

					$clsCTC = new EBPLSCTC ( $dbLink, $ctc_type, $debug );

					if ( $ctc_type == CTC_TYPE_INDIVIDUAL ) {

						$clsCTC->setData(CTC_OWNER_ID,$ctc_owner_id);
						$clsCTC->setData(CTC_FIRST_NAME,$ctc_first_name);
						$clsCTC->setData(CTC_MIDDLE_NAME,$ctc_middle_name);
						$clsCTC->setData(CTC_LAST_NAME,$ctc_last_name);
						$clsCTC->setData(CTC_BIRTH_DATE,$ctc_birth_date);
						$clsCTC->setData(CTC_ADDRESS,$ctc_address);
						$clsCTC->setData(CTC_GENDER,$ctc_gender);
						$clsCTC->setData(CTC_CIVIL_STATUS,$ctc_civil_status);
						$clsCTC->setData(CTC_ACCT_CODE,"CTC_ACCT_CODE");
						$clsCTC->setData(CTC_PLACE_ISSUED,$ctc_place_issued);
						$clsCTC->setData(CTC_TINNO,$ctc_tin_no);
						$clsCTC->setData(CTC_PLACE_OF_BIRTH,$ctc_place_of_birth);
						$clsCTC->setData(CTC_HEIGHT,$ctc_height);
						$clsCTC->setData(CTC_WEIGHT,$ctc_weight);
						$clsCTC->setData(CTC_CITIZENSHIP,$ctc_citizenship);
						$clsCTC->setData(CTC_OCCUPATION,$ctc_occupation);
						$clsCTC->setData(CTC_ICR_NO,$ctc_icr_no);
						$clsCTC->setData(CTC_TAX_EXEMPTED,isset($ctc_tax_exempted)?$ctc_tax_exempted:"0");

						$clsCTC->setData(CTC_ADDITIONAL_TAX1,$ctc_additional_tax1 );
						$clsCTC->setData(CTC_ADDITIONAL_TAX2,$ctc_additional_tax2 );
						$clsCTC->setData(CTC_ADDITIONAL_TAX3,$ctc_additional_tax3 );

					} else {

						$clsCTC->setData(CTC_BUSINESS_ID,$ctc_business_id);
						$clsCTC->setData(CTC_COMPANY,$ctc_company);
						$clsCTC->setData(CTC_TIN_NO,$ctc_tin_no);
						$clsCTC->setData(CTC_ORGANIZATION_TYPE,$ctc_organization_type);
						$clsCTC->setData(CTC_PLACE_OF_INCORPORATION,$ctc_place_of_incorporation);
						$clsCTC->setData(CTC_BUSINESS_NATURE,$ctc_business_nature );
						$clsCTC->setData(CTC_ADDITIONAL_TAX1,$ctc_additional_tax1 );
						$clsCTC->setData(CTC_ADDITIONAL_TAX2,$ctc_additional_tax2 );
						$clsCTC->setData(CTC_COMPANY_ADDRESS,$ctc_company_address );
						$clsCTC->setData(CTC_PLACE_ISSUED,$ctc_place_issued);
						$clsCTC->setData(CTC_DATE_ISSUED,$ctc_date_issued);

					}

					if ( $clsCTC->create() < 0 ) {

						echo "CTC ADD ERROR";
						$clsCTC->printError();

					} else {

						$HTTP_POST_VARS["ctc_code"] = $clsCTC->getData(CTC_CODE);
						print_ctc_viewer ( $HTTP_POST_VARS["ctc_type"], $HTTP_POST_VARS, false );

					}

				} break;
			case CTC_CMD_RENEW :
				{

					$clsCTC = new EBPLSCTC ( $dbLink, $ctc_type, $debug );

					if ( $clsCTC->load( $ctc_code ) < 0 ) {

						echo "<P><font class=ctc_note_red>Unable to find CTC# $ctc_code</font><BR>";

					} else {


						print_ctc_type_form( $ctc_type, $cmd );
						print_ctc_form ( $ctc_type, $clsCTC->getData() );

					}


				} break;
			case CTC_CMD_FIND :
				{

					print_ctc_type_form ( $ctc_type, $cmd );

					if ( count($HTTP_POST_VARS) ) {

						print_ctc_search_form( $ctc_type, $HTTP_POST_VARS );

					} else {

						print_ctc_search_form( $ctc_type, $HTTP_GET_VARS );

					}

				} break;

		}

	?>
	</td>
</tr>
<tr><td align="center" valign="center" class='title' height=10></td></tr>
</table>
<!---// end of the table //-->
</div>
<?

function print_menu( $cmd  ){

$url = getURI(eBPLS_PAGE_CTC_CRITERIA);

echo "<table width=\"100%\" >";
echo "<tr>";

if ( $cmd == CTC_CMD_NEW || $cmd == "" )
	echo "<td align=center width=\"33%\" class='ctc_menu_selected'>New CTC</td>";
else
	echo "<td align=center width=\"33%\" class='ctc_menu'><a href='$url&cmd=" . CTC_CMD_NEW . "'>New</font></a></td>";

if ( $cmd == CTC_CMD_FIND )
	echo "<td align=center width=\"33%\" class='ctc_menu_selected'>Find CTC</td>";
else
	echo "<td align=center width=\"33%\" class='ctc_menu'><a href='$url&cmd=" . CTC_CMD_FIND . "'>Find</td>";

echo "</tr>";
echo "</table>";

}

function print_ctc_search_form( $ctc_type, $post_params ) {

	$url = getURI(eBPLS_PAGE_CTC_CRITERIA);

	if ( $ctc_type == "" ) { // default is individual

		$ctc_type = CTC_TYPE_INDIVIDUAL;

	}

?>
<script language="Javascript">
<!--

	function checkCTCSearchFields( ){

		if ( document._FRM.ctc_type.value == '<?= CTC_TYPE_INDIVIDUAL ?>' ) {

			if ( document._FRM.<?= CTC_CODE ?>.value == '' &&
			     document._FRM.<?= CTC_LAST_NAME ?>.value == '' &&
			     document._FRM.<?= CTC_FIRST_NAME ?>.value == '' &&
			     document._FRM.<?= CTC_MIDDLE_NAME ?>.value == '' &&
			     document._FRM.<?= CTC_ADDRESS ?>.value == '' &&
			     document._FRM.<?= CTC_DATE_ISSUED ?>.value == '' )
			  {

			  	alert("Please provide a fill up at least one search field");
			  	document._FRM.<?= CTC_CODE ?>.focus();
			  	return false;

			  } else {

			  	return true;

			  }


		} else if ( document._FRM.ctc_type.value == '<?= CTC_TYPE_BUSINESS ?>' ) {

			if ( document._FRM.<?= CTC_CODE ?>.value == '' &&
			     document._FRM.<?= CTC_COMPANY ?>.value == '' &&
			     document._FRM.<?= CTC_COMPANY_ADDRESS ?>.value == '' &&
			     document._FRM.<?= CTC_ORGANIZATION_TYPE ?>.selectedIndex == 0 &&
			     document._FRM.<?= CTC_BUSINESS_NATURE ?>.value == '' &&
			     document._FRM.<?= CTC_DATE_ISSUED ?>.value == '' )
			  {

			  	alert("Please provide a fill up at least one search field");
			  	document._FRM.<?= CTC_CODE ?>.focus();
			  	return false;

			  } else {

			  	return true;

			  }

		} else {

			alert("Invalid CTC Type");

		}

		return true;

	}

-->
</script>
<form name="_FRM" action="index.php?part=4&itemID_=<?php echo $itemID_;?>&class_type=CTC&busItem=CTC&permit_type=CTC" method="POST" onSubmit="javascript: return checkCTCSearchFields();">
<!--<form name="_FRM" action="<?= $url ?>" method="POST" onSubmit="javascript: return checkCTCSearchFields();">-->
<input type="hidden" name=ctc_type value="<?= $ctc_type ?>">
<input type="hidden" name=cmd value="<?= CTC_CMD_FIND ?>">
<BR>
<?
if ( $ctc_type == CTC_TYPE_INDIVIDUAL ) {
	
?>
	<!-- ctc individual search form -->
	<table cellpadding=3>
	<tr><td>CTC Code</td><td><input name="<?= CTC_CODE ?>" value="<?= $post_params["ctc_code"] ?>"></td></tr>
	<tr><td>Last Name</td><td><input name="<?= CTC_LAST_NAME ?>" value="<?= $post_params["lname"] ?>"></td></tr>
	<tr><td>First Name</td><td><input name="<?= CTC_FIRST_NAME ?>" value="<?= $post_params["fname"] ?>"></td></tr>
	<tr><td>Middle Name</td><td><input name="<?= CTC_MIDDLE_NAME ?>" value="<?= $post_params["mname"] ?>"></tr>
	<tr><td>Address</td><td><input name="<?= CTC_ADDRESS ?>" value="<?= $post_params["address"] ?>"></td></tr>
	<tr><td>Date Issued</td><td><?= get_ctc_date_control("document._FRM", CTC_DATE_ISSUED ,$post_params["date_issued"], false) ?></td></tr>

	<tr><td colspan=2 align=center><BR>
	<input type=submit name="submit" value="Search">&nbsp;&nbsp;<input type=reset value="Clear">
	</td></tr>
	</table>
<?
} else {
	
?>
	<!-- ctc business search form -->
	<table cellpadding=3>
	<tr><td>CTC Code</td><td><input name="<?= CTC_CODE ?>" value="<?= $post_params[CTC_CODE] ?>"></td></tr>
	<tr><td>Company Name</td><td><input name="<?= CTC_COMPANY ?>" value="<?= $post_params[CTC_COMPANY] ?>"></td></tr>
	<tr><td>Address</td><td><input name="<?= CTC_COMPANY_ADDRESS ?>" value="<?= $post_params[CTC_COMPANY_ADDRESS] ?>"></td></tr>
	<tr><td>Organization Type</td><td>
	<select name="<?= CTC_ORGANIZATION_TYPE ?>">
	<option value="">-----------------------
	<option value="CORPORATION" <?= ($post_params[CTC_COMPANY]=="CORPORATION")?"selected":"" ?>>Corporation
	<option value="ASSOCIATION" <?= ($post_params[CTC_COMPANY]=="ASSOCIATION")?"selected":"" ?>>Association
	<option value="PARTNERSHIP" <?= ($post_params[CTC_COMPANY]=="PARTNERSHIP")?"selected":"" ?>>Partnership
	</select>
	</td></tr>
	<tr><td>Kind/Nature of Business</td><td><input name="<?= CTC_MIDDLE_NAME ?>" value="<?= $post_params["business_nature"] ?>"></td></tr>
	<tr><td>Date Issued</td><td><?= get_ctc_date_control("document._FRM","date_issued",$post_params["date_issued"], false ) ?></td></tr>

	<tr><td colspan=2 align=center><BR>
	<input type=submit name="submit" value="Search">&nbsp;&nbsp;<input type=reset value="Clear">
	</td></tr>
	</table>
<?
}
?>
</form>
<?

	if ( count($post_params) > 0 ) {

		if ( $ctc_type == "" )  $ctc_type = CTC_TYPE_INDIVIDUAL;

		// do ctc search
		$dbLink = get_db_connection();

		//--- check first the method method_of_application NEW/RENEW
		$is_ctc_renew = false;
		$ctcDebug 	 = false;
		$is_ctc_renew 	 = true;
		$clsCTC 	 = new EBPLSCTC( $dbLink, $ctc_type, $ctcDebug );
		$max_rec	 = 10;

		if ( $ctc_type == "" || $ctc_type == CTC_TYPE_INDIVIDUAL ) {

			$search_result = $clsCTC->findIndividualCTC( $post_params[CTC_CODE], $post_params[CTC_FIRST_NAME], $post_params[CTC_MIDDLE_NAME],$post_params[CTC_LAST_NAME], $post_params[CTC_ADDRESS], $post_params[CTC_DATE_ISSUED], ($post_params["pg"]>0)?$post_params["pg"]:1, $max_rec, isset($post_params["order_key"])?$post_params["order_key"]:CTC_DATE_ISSUED, ($post_params["order"]=="DESC")?true:false  );

		}else{

			//function findBusinessCTC( $company, $address, $org_type, $bus_nature, $date_issued, $page, $maxrec = 10, $orderkey = CTC_DATE_ISSUED, $is_desc = true ) {
			$search_result = $clsCTC->findBusinessCTC( $post_params[CTC_CODE], $post_params[CTC_COMPANY], $post_params[CTC_COMPANY_ADDRESS],$post_params[CTC_ORGANIZATION_TYPE], $post_params[CTC_BUSINESS_NATURE], $post_params[CTC_DATE_ISSUED], ($post_params["pg"]>0)?$post_params["pg"]:1, $max_rec, isset($post_params["order_key"])?$post_params["order_key"]:CTC_DATE_ISSUED, ($post_params["order"]=="DESC")?true:false );

		}

		if ( $search_result < 0 ) {

			$clsCTC->printError();

		} else {

			print_search_result( $ctc_type, $search_result, $post_params );

		}

	} else {

		echo "<center>NO RECORDS FOUND</center>";

	}

}

function print_ctc_command ( &$record ) {

	$url = getURI(eBPLS_PAGE_CTC_CRITERIA);

	if ( date("Y") > date("Y", strtotime($record->getData(CTC_DATE_ISSUED))) ) {
		$ctc_type = $record->m_ctcType;
		return "<a href=\"$url&ctc_type=$ctc_type&cmd=" . CTC_CMD_RENEW . "&ctc_code=" . $record->getData(CTC_CODE) . "\"><font class=ctc_menu_record>Renew</font></a>";

	} else {

		//return "No Command";
		return "";

	}

}

function print_search_result( $ctc_type, $result, $post_params ) {

	if ( $ctc_type == CTC_TYPE_INDIVIDUAL ) {

		$columns[CTC_CODE] = "CTC #";
		$columns[CTC_LAST_NAME] = "Last Name";
		$columns[CTC_FIRST_NAME] = "First Name";
		$columns[CTC_MIDDLE_NAME] = "Middle Name";
		$columns[CTC_ADDRESS] = "Address";
		$columns[CTC_DATE_ISSUED] = "Date Issued";

		print_search_results( eBPLS_PAGE_CTC_CRITERIA, $post_params, "Search Result", $columns, $result, "print_ctc_command" );



	} else {

		$columns[CTC_CODE] = "CTC #";
		$columns[CTC_COMPANY] = "Company";
		$columns[CTC_COMPANY_ADDRESS] = "Address";
		$columns[CTC_ORGANIZATION_TYPE] = "Organization Type";
		$columns[CTC_BUSINESS_NATURE] = "Business Nature";
		$columns[CTC_DATE_ISSUED] = "Date Issued";

		print_search_results( eBPLS_PAGE_CTC_CRITERIA, $post_params, "Search Result", $columns, $result );


	}

}


function process_form_template( $strFilename, $post_vars, $bFormType = true ) {

	global $gCitizenshipArr;
	global $gCivilStatusArr;
	global $gGenderValues;
	global $gCTCOrganizationType;
	global $thProvince;

	$content = implode ('', file ($strFilename));

	foreach ($post_vars as $key => $value) {

		if(get_magic_quotes_gpc ( )){
			$value = stripslashes($value);
		}
		$value = htmlentities($value);
		$content = str_replace( "[$key]", $value, $content );

	}


	// put default values to standard tags defined
	$content = ereg_replace("\[FOR_YEAR\]",date("Y"),$content);
	$content = ereg_replace("\[FOR_DAY\]",date("d"),$content);
	$content = ereg_replace("\[FOR_MO\]",date("m"),$content);
	$content = ereg_replace("\[DB_DATE\]",date("yyyy-mm-dd"),$content);
	$content = ereg_replace("\[DATE\]",date("F d, Y h:i A"),$content);
	$content = ereg_replace("\[PLACE_ISSUED\]",get_ctc_place_issued(), $content);

	if ( $bFormType ) {

		//createCheckBox( $name, $value, $label, $bChecked )
		$content = ereg_replace("\[CTC_TAX_EXEMPTED\]", createCheckBox( "ctc_tax_exempted", 1, "Exempted", $post_vars["ctc_tax_exempted"] ), $content );
		$content = ereg_replace("\[BIRTHDATE\]", get_ctc_date_control( "document._FRM", "ctc_birth_date", $post_vars["ctc_birth_date"] ), $content );
		$content = ereg_replace("\[CTC_INCORPORATION_DATE\]", get_ctc_date_control("document._FRM","ctc_incorporation_date",$post_vars["ctc_incorporation_date"]), $content );
		$content = ereg_replace("\[CTC_CIVIL_STATUS\]", createSelect("ctc_civil_status", $gCivilStatusArr, $post_vars["ctc_civil_status"], ""), $content );
		$content = ereg_replace("\[CTC_CITIZENSHIP\]", createSelect("ctc_citizenship", $gCitizenshipArr, $post_vars["ctc_citizenship"], "javascript:checkCitizenship();" ), $content );
		$content = ereg_replace("\[CTC_GENDER\]", createSelect("ctc_gender", $gGenderValues, $post_vars["ctc_gender"], ""), $content );
		$content = ereg_replace("\[CTC_ORGANIZATION_TYPE\]", createSelect("ctc_organization_type", $gCTCOrganizationType, $post_vars["ctc_organization_type"], ""), $content );

	}


	// put an empty string to unknown tags
	$content = ereg_replace("\[[A-Za-z_]+\]","",$content);

	echo $content;

}

function get_ctc_date_control( $form, $name, $date, $bRequireAll = true ) {


ereg("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})", $date, $date_arr );
$yr = $date_arr[1];
if ( $yr == "" ) $yr = 0;
$mo = $date_arr[2];
if ( $mo == "" ) $mo = 0;
$dy = $date_arr[3];
if ( $dy == "" ) $dy = 0;

if ( $date != "" ) {
	$date_val = "$yr-$mo-$dy";
}

// create month
$strDateControl = "";
$strDateControl .= "<input type=hidden name=$name value='$date_val'>";
for ( $i=1; $i<=12; $i++) {
	$months[$i]= date("M", mktime(0,0,0,$i,1,2004));
}
$strDateControl .= createSelect( $name . "_month", $months, $mo, "javascript:updateDateControlDays();");


$strDateControl .= "&nbsp;&nbsp;";
for ( $i = 1; $i <=31 ; $i++ ) {
	$days[$i] = $i;
}
$strDateControl .= createSelect( $name . "_days", $days, $dy, "javascript:updateDateControlDays();");

$strDateControl .= "&nbsp;&nbsp;";
for ( $i = date("Y"); $i >= 1920 ; $i-- ) {
	$years[$i] = $i;
}
$strDateControl .= createSelect( $name . "_year", $years, $yr, "javascript:updateDateControlDays();");

$strDateControl .= "<script language='Javascript'><!--\n";
$strDateControl .= "function updateDateControlDays(){";
$strScript = "var j; var frm = $form;\n";
$strScript .= "var days = 0;\n";
$strScript .= "var mo = frm.$name" . "_month.value;\n";
$strScript .= "var form_dy_select = frm.$name" . "_days;\n";
$strScript .= "var form_yr_select = frm.$name" . "_year;\n";
$strScript .= "var form_mo_select = frm.$name" . "_month;\n";
$strScript .= "var form_bdate = frm.$name;\n";
$strScript .= "var yr = frm.$name" . "_year.value;\n";
$strScript .= "if ( mo == 1 || mo == 3 || mo == 5 || mo == 7 || mo == 8 || mo == 10 || mo == 12 ) { \n";
$strScript .= "		days = 31;\n";
$strScript .= "} else if ( mo == 2 ) { // check if leap year\n";
$strScript .= "		days = ((yr%4)==0)?29:28;\n";
$strScript .= "} else { \n";
$strScript .= "		days = 30;\n";
$strScript .= "}\n";
$strScript .= "var selectedIndex = form_dy_select.selectedIndex\n";
$strScript .= "while ( form_dy_select.options.length > 0 ){\n";
$strScript .= "		form_dy_select.remove(0);\n";
$strScript .= "}\n";
$strScript .= " var elem = document.createElement(\"OPTION\");\n";
$strScript .= " elem.value = 0;";
$strScript .= " elem.text = '----';";
$strScript .= "form_dy_select.options.add(elem,i);\n";
$strScript .= "for (var i = 1; i <= days; i++ ){\n";
$strScript .= " var elem = document.createElement(\"OPTION\");\n";
$strScript .= " elem.value = i;";
$strScript .= " elem.text = i;";
$strScript .= "form_dy_select.options.add(elem,i);\n";
$strScript .= "}\n";
$strScript .= " if ( form_dy_select.options.length > selectedIndex ) { form_dy_select.selectedIndex = selectedIndex; }\n";
$strScript .= " else { form_dy_select.selectedIndex = 0; }\n";
$strScript .= "if ( form_mo_select.item(form_mo_select.selectedIndex).value.length < 2 ) form_mo_select.item(form_mo_select.selectedIndex).value = '0' + form_mo_select.item(form_mo_select.selectedIndex).value;";
$strScript .= "if ( form_dy_select.item(form_dy_select.selectedIndex).value.length < 2 ) form_dy_select.item(form_dy_select.selectedIndex).value = '0' + form_dy_select.item(form_dy_select.selectedIndex).value;";

if ($bRequireAll ) {

$strScript .= "if ( form_mo_select.selectedIndex == 0 || form_dy_select.selectedIndex == 0 || form_yr_select.selectedIndex == 0 ) form_bdate.value = '';";
$strScript .= "else form_bdate.value = ( form_yr_select.item(form_yr_select.selectedIndex).value + '-' + form_mo_select.item(form_mo_select.selectedIndex).value + '-' + form_dy_select.item(form_dy_select.selectedIndex).value );\n";

} else {

$strScript .= "form_bdate.value = '';\n";

// year
$strScript .= "if ( form_yr_select.selectedIndex != 0 ) form_bdate.value = form_yr_select.item(form_yr_select.selectedIndex).value + '-';\n";
$strScript .= "else form_bdate.value = '^[0-9]{4}-';\n";

// month only
$strScript .= "if ( form_mo_select.selectedIndex != 0 ) form_bdate.value = form_bdate.value + form_mo_select.item(form_mo_select.selectedIndex).value + '-';\n";
$strScript .= "else form_bdate.value = form_bdate.value + '[0-9]{2}-';\n";

// day
$strScript .= "if ( ( form_mo_select.selectedIndex != 0 || form_yr_select.selectedIndex != 0 ) && form_dy_select.selectedIndex != 0  ) form_bdate.value = form_bdate.value + form_dy_select.item(form_dy_select.selectedIndex).value;\n";
$strScript .= "else if ( form_dy_select.selectedIndex != 0  ) form_bdate.value = form_bdate.value + form_dy_select.item(form_dy_select.selectedIndex).value;\n";
$strScript .= "else form_bdate.value = form_bdate.value + '[0-9]{2}';\n";

}

//$strScript .= "alert(form_bdate.value);";
$strDateControl .= $strScript;

$strDateControl .= "}\n--></script>";

return $strDateControl;

}

function print_ctc_type_form( $type, $cmd ) {
?>
<P>
<form name=ctc_type>
<table align=left width="100%" cellpadding=2 border=0 cellspacing=1 align=center bgcolor='#202366'>
<tr>
<td align=center bgcolor='#ffffff'><font size=3><B>CHOOSE CTC TYPE :</B></font>
<select name="ctc_type" onChange="javascript:document.location=('<?php echo(getURI(eBPLS_PAGE_CTC_CRITERIA)) . "&cmd=$cmd&ctc_type=' + document.ctc_type.ctc_type.value ";?>);">
<option value="INDIVIDUAL" <?php if ( $type == 'INDIVIDUAL' || $type == '' ) echo "selected"; ?>>INDIVIDUAL
<option value="BUSINESS"  <?php if ( $type == 'BUSINESS' ) echo " selected "; ?> >BUSINESS
</p>
</select>
</td></tr>
</table>
</form>
<P><BR>
<?

}

function print_ctc_viewer( $type, $form_elem_values, $bProcess ) {


	if ( $bProcess ) {

	// modify the form command to add to proceed to next step
	$form_elem_values["cmd"] = CTC_CMD_ADD;

?>
	<P align=center><font class=ctc_note_red>Receive Payment</font></p>
	<form name="_FRM" method="POST" action="index.php?part=4&itemID_=<?php echo $itemID_;?>&class_type=CTC&busItem=CTC&permit_type=CTC">
	<!--<form name="_FRM" method="POST" action="<?php echo(getURI(eBPLS_PAGE_CTC_CRITERIA)); ?>">-->
<?

		foreach ( $form_elem_values as $key => $value ){
			if (get_magic_quotes_gpc()){
				$value = stripslashes($value);
			}
			$value = htmlentities($value);
			echo "<input type=hidden name=$key value=\"$value\">\n";
		}

	} else {
?>

	<P align=center><font class=ctc_note_red>Payment Done</font></p>

<?
	}


	if ( $type == CTC_TYPE_INDIVIDUAL || $type == "" ) {

		process_form_template( "templates/form_ctc_individual_application_view.html", $form_elem_values, false);

	} else {

		process_form_template( "templates/form_ctc_business_application_view.html", $form_elem_values, false);

	}


	if ( $bProcess ) {
?>
<P align=center>
<input type=button value="Edit" onClick="javascript:setCommand();">&nbsp;&nbsp;
<input type=submit value="Receive Payment">
</p>
</form>
<?
	} else {
?>
	<input type=button value="Print" onClick="javascript:window.print();">&nbsp;&nbsp;
<?
	}
?>
<script language="Javascript">
<!--

	function setCommand(){

		 document._FRM.cmd.value = "<?= CTC_CMD_NEW ?>";
		 document._FRM.submit();

	}

	function printCTC(){

		w = screen.width - 250
		h = screen.height - 450
		x = screen.width/2 - w/2
		y = screen.height/2 - h/2

		strOption = 'scrollbars=yes,status=yes,width=' + w + ',height=' + h + ',screenX=' + x + ',screenY=' + y
		window.open ( "ebpls101.php?cmd=" + cmd + "&trans_id=<?= $form_elem_values["trans_id"] ?>&ctc_code=<?= $form_elem_values["ctc_code"] ?>" , cmd, strOption );

	}



-->
</script>
<?
}

function print_ctc_form(  $type, $form_elem_values ) {


$dbLink = get_db_connection();

$is_ctc_renew = false;
$ctcDebug 	 = false;
$is_ctc_renew 	 = true;
$clsCTC 	 = new EBPLSCTC ( $dbLink, $ctcDebug );

if ( $type == "" ) $type = CTC_TYPE_INDIVIDUAL;

//--- make a script that will calculate the tax
//$ctc_additional_tax1_due=((int)($ctc_addtional_tax1)/1000));
$tax_a1_fields = Array("in"=>Array("document._FRM.ctc_additional_tax1"),"out"=>"document._FRM.ctc_additional_tax1_due");
$tax_a2_fields = Array("in"=>Array("document._FRM.ctc_additional_tax2"),"out"=>"document._FRM.ctc_additional_tax2_due");

if ( $type == CTC_TYPE_INDIVIDUAL ) {

	$tax_a3_fields = Array("in"=>Array("document._FRM.ctc_additional_tax3"),"out"=>"document._FRM.ctc_additional_tax3_due");
	$basic_tax_field = Array("in"=>Array('document._FRM.ctc_tax_exempted'),"out"=>'document._FRM.ctc_basic_tax');
	$out_total_interest_due = Array("in"=>Array("x1"=>'document._FRM.current_month',"x2"=>'document._FRM.ctc_total_amount_due'),"out"=>'document._FRM.ctc_total_interest_due');
	$out_total_amount_due = Array( "in"=>Array("x1"=>'document._FRM.ctc_basic_tax',"x2"=>'document._FRM.ctc_additional_tax1_due',"x3"=>'document._FRM.ctc_additional_tax2_due',"x4"=>'document._FRM.ctc_additional_tax3_due'), "out"=>'document._FRM.ctc_total_amount_due' );
	$out_total_paid_due = Array("in"=>Array("x1"=>'document._FRM.ctc_total_amount_due',"x2"=>'document._FRM.ctc_total_interest_due'), "out"=>'document._FRM.ctc_total_paid');

} else {

	$basic_tax_field = Array("in"=>NULL,"out"=>'document._FRM.ctc_basic_tax');
	$out_total_interest_due = Array("in"=>Array("x1"=>'document._FRM.current_month',"x2"=>'document._FRM.ctc_total_amount_due'),"out"=>'document._FRM.ctc_total_interest_due');
	$out_total_amount_due = Array( "in"=>Array("x1"=>'document._FRM.ctc_basic_tax',"x2"=>'document._FRM.ctc_additional_tax1_due',"x3"=>'document._FRM.ctc_additional_tax2_due'), "out"=>'document._FRM.ctc_total_amount_due' );
	$out_total_paid_due = Array("in"=>Array("x1"=>'document._FRM.ctc_total_amount_due',"x2"=>'document._FRM.ctc_total_interest_due'), "out"=>'document._FRM.ctc_total_paid');

}

?>
<P><BR>
<script language=Javascript>

function checkCitizenship() {

	if ( document._FRM.ctc_citizenship.selectedIndex > 1 ) {

		document._FRM.ctc_icr_no.disabled = false;

	} else {

		document._FRM.ctc_icr_no.disabled = true;
		document._FRM.ctc_icr_no.value = '';

	}

}

//--- start CTC application page scripts
function validate_ctc_form_application()
{

		var _FRM = document._FRM
		var msgTitle = "Community Tax Certificate Application\n";

		if ( _FRM.ctc_type.value == 'INDIVIDUAL' ) {


				if( isBlank(_FRM.ctc_last_name.value) == true)
				{
					alert( msgTitle + "Please input a valid lastname!");
					_FRM.ctc_last_name.focus();
					return false;
				}


				if( isBlank(_FRM.ctc_first_name.value) == true)
				{
					alert( msgTitle + "Please input a valid firstname!");
					_FRM.ctc_first_name.focus();
					return false;
				}

				if( isBlank(_FRM.ctc_middle_name.value) == true)
				{
					alert( msgTitle + "Please input a valid middlename!");
					_FRM.ctc_middle_name.focus();
					return false;
				}

				if( isBlank(_FRM.ctc_address.value) == true)
				{
					alert( msgTitle + "Please input a valid address!");
					_FRM.ctc_address.focus();
					return false;
				}

				if( _FRM.ctc_gender.selectedIndex == 0 )
				{
					alert( msgTitle + "Please input a valid gender!");
					_FRM.ctc_gender.focus();
					return false;
				}

				if( _FRM.ctc_citizenship.selectedIndex == 0 )
				{
					alert( msgTitle + "Please input a valid citizenship!");
					_FRM.ctc_citizenship.focus();
					return false;
				}

				if( _FRM.ctc_citizenship.item(_FRM.ctc_citizenship.selectedIndex).value != 'FILIPINO' && _FRM.ctc_icr_no.value == '' )
				{
					alert( msgTitle + "Please input a valid ICR No.!");
					_FRM.ctc_icr_no.focus();
					return false;
				}

				if( isBlank(_FRM.ctc_place_of_birth.value) == true)
				{
					alert( msgTitle + "Please input a valid place of birth!");
					_FRM.ctc_place_of_birth.focus();
					return false;
				}

				if( _FRM.ctc_civil_status.selectedIndex == 0 )
				{
					alert( msgTitle + "Please input a valid Civil Status!");
					_FRM.ctc_civil_status.focus();
					return false;
				}

				if( _FRM.ctc_birth_date.value == '' )
				{
					alert( msgTitle + "Please input a valid birthdate!");
					_FRM.ctc_birth_date_month.focus();
					return false;
				}

				//alert(_FRM.ctc_birth_date.value);

				if(  isBlank(_FRM.ctc_additional_tax1.value) == true && isBlank(_FRM.ctc_additional_tax2.value) == true && isBlank(_FRM.ctc_additional_tax3.value) == true )
				{
					alert( msgTitle + "Please input a valid last gross!");
					_FRM.ctc_additional_tax1.focus();
					return false;
				}

				if(  !isDigit(_FRM.ctc_additional_tax1.value) || !isDigit(_FRM.ctc_additional_tax2.value) && !isDigit(_FRM.ctc_additional_tax3.value) )
				{
					alert( msgTitle + "Please input a valid last gross!");
					_FRM.ctc_additional_tax1.focus();
					return false;
				}

				/*
				if(  !parseInt(_FRM.ctc_additional_tax1.value) && !parseInt(_FRM.ctc_additional_tax2.value) && !parseInt(_FRM.ctc_additional_tax3.value) )
				{
					alert( msgTitle + "Please input a valid last gross!");
					_FRM.ctc_additional_tax1.focus();
					return false;
				}
				*/

				if ( isBlank(_FRM.ctc_additional_tax1.value) ) {
					_FRM.ctc_additional_tax1.value = 0.0;
				}

				if ( isBlank(_FRM.ctc_additional_tax2.value) ) {
					_FRM.ctc_additional_tax2.value = 0.0;
				}

				if ( isBlank(_FRM.ctc_additional_tax3.value) ) {
					_FRM.ctc_additional_tax3.value = 0.0;
				}


		} else if ( _FRM.ctc_type.value = 'BUSINESS' ) {

			if( isBlank(_FRM.ctc_company.value) == true)
			{
				alert( msgTitle + "Please input a valid Company name!");
				_FRM.ctc_company.focus();
				return false;
			}

			if( isBlank(_FRM.ctc_tin_no.value) == true)
			{
				alert( msgTitle + "Please input a valid Company Tin No.!");
				_FRM.ctc_tin_no.focus();
				return false;
			}

			if( isBlank(_FRM.ctc_company_address.value) == true)
			{
				alert( msgTitle + "Please input a valid Company Addres!");
				_FRM.ctc_company_address.focus();
				return false;
			}


			if( _FRM.ctc_organization_type.selectedIndex == 0 )
			{
				alert( msgTitle + "Please input a valid Oraganization Type!");
				_FRM.ctc_organization_type.focus();
				return false;
			}

			if( _FRM.ctc_place_of_incorporation.value == '' )
			{
				alert( msgTitle + "Please input a valid place Of incorporation!");
				_FRM.ctc_place_of_incorporation.focus();
				return false;
			}

			if( _FRM.ctc_incorporation_date.value == '' )
			{
				alert( msgTitle + "Please input a valid Incorporation Date!");
				_FRM.ctc_incorporation_date_month.focus();
				return false;
			}

			if( isBlank(_FRM.ctc_business_nature.value) == true)
			{
				alert( msgTitle + "Please input a valid Business nature!");
				_FRM.ctc_business_nature.focus();
				return false;
			}

			if(  isBlank(_FRM.ctc_additional_tax1.value) == true && isBlank(_FRM.ctc_additional_tax2.value) == true )
			{
				alert( msgTitle + "Please input a valid last gross!");
				_FRM.ctc_additional_tax1.focus();
				return false;
			}

			// check if nonzero
			/*
			if(  !parseInt(_FRM.ctc_additional_tax1.value) && !parseInt(_FRM.ctc_additional_tax2.value) )
			{
				alert( msgTitle + "Please input a valid last gross!");
				_FRM.ctc_additional_tax1.focus();
				return false;
			}
			*/

			// check if a digit
			if(  !isDigit(_FRM.ctc_additional_tax1.value) || !isDigit(_FRM.ctc_additional_tax2.value) )
			{
				alert( msgTitle + "Please input a valid last gross!");
				_FRM.ctc_additional_tax1.focus();
				return false;
			}

			if ( isBlank(_FRM.ctc_additional_tax1.value) ) {
				_FRM.ctc_additional_tax1.value = 0.0;
			}

			if ( isBlank(_FRM.ctc_additional_tax2.value) ) {
				_FRM.ctc_additional_tax2.value = 0.0;
			}

		} else {

			alert('System Error : Invalid CTC Type ' + _FRM.ctc_type.value );

		}

	return true;
}



</script>

<form name="_FRM" method="POST" action="index.php?part=4&itemID_=101&class_type=CTC&busItem=CTC"  onSubmit="return validate_ctc_form_application();">
<!--<form name="_FRM" method="POST" action="<?php echo(getURI(eBPLS_PAGE_CTC_CRITERIA)); ?>"  onSubmit="return validate_ctc_form_application();">-->
<input type=hidden name="ctc_type" value="<?= $ctc_type ?>">
<input type=hidden name="ctc_place_issued" value="<?= get_ctc_place_issued() ?>">
<input type=hidden name="cmd" value="<?= CTC_CMD_PROCESS ?>">
<input type=hidden name="current_month" value="<?= date("m") ?>">
<?

if ( $type == CTC_TYPE_INDIVIDUAL ) {
	echo "<input type=hidden name=\"ctc_owner_id\" value=\"" . $form_elem_values["ctc_owner_id"]."\">";
	process_form_template( "templates/form_ctc_individual_application.html", $form_elem_values);
} else {
	echo "<input type=hidden name=\"ctc_business_id\" value=\"" . $form_elem_values["ctc_business_id"]."\">";
	process_form_template( "templates/form_ctc_business_application.html", $form_elem_values);
}

?>
<P align=center>
<?
/*
if ( $type == CTC_TYPE_INDIVIDUAL ) {
	echo "<input type=button value=\"Search Owner\"  onClick=\"javascript:showSearchForm('OWNER');\">";
}else{
	echo "<input type=button value=\"Search Business\" onClick=\"javascript:showSearchForm('BUSINESS');\">";
}
*/
?>
&nbsp;&nbsp;<input type=submit value="Process Payment">&nbsp;&nbsp;<input type=reset value="Clear">
</p>
</form>
<script language="Javascript">

function showSearchForm( val ) {

	if ( val == 'OWNER' ) {

		popwin('owner_search.php','_OWNER_SEARCH');

	} else if ( val == 'BUSINESS' ) {

		popwin('business_search.php','_BUSINESS_SEARCH');

	} else {

		alert('invalid ctc search param');

	}

}

</script>
<?
$clsCTC->printIndividualCTCScript( $type, $basic_tax_field,$tax_a1_fields,$tax_a2_fields,$tax_a3_fields,$out_total_amount_due,$out_total_interest_due,$out_total_paid_due );

}

function get_ctc_place_issued(){
	
	global $thMunicipality;
	
	//echo "BANG = $thProvince <BR>";
	
	return $thMunicipality;	

}

?>
