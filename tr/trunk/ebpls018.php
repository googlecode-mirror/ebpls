<?php
//	@eBPLS_DB_DETAILS_MAINTENANCE : process details maintenance tables input page
require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");
//require_once("ebpls-php-lib/ebpls.sysref.class.php");
require_once("ebpls-php-lib/utils/ebpls.search.funcs.php");


//--- get connection from DB
$dbLink = get_db_connection();
global $ThUserData;
$debug 	= false;

$gDB_Details_Tables = array(	'ebpls_business_category'	=>'Ownership Type',
			    	'ebpls_business_nature'		=>'Business Category/Type/Nature/Kind',
			    	'ebpls_business_requirement'	=>'Business Requirement',
				'ebpls_business_status'		=>'Business Status',
				'ebpls_business_type'		=>'Business Type',
				'ebpls_city_municipality'	=>'City / Municipality Codes',
				'ebpls_district'		=>'District Codes',
				'ebpls_province'		=>'Province Codes',
				'ebpls_zip'			=>'Zip Codes',
				'ebpls_zone'			=>'Zone Codes',
				'ebpls_industry_sector'		=>'Industry Sector Codes',
				'ebpls_occupancy_type'		=>'Occupancy Type Codes',
				'ebpls_barangay'		=>'Barangay Codes'
				);

$gDB_Details_Tables_Map= array(	'ebpls_business_category'	=>EBPLS_BUSINESS_CATEGORY,
			    	'ebpls_business_category_offc'	=>EBPLS_BUSINESS_CATEGORY_OFFC,
			    	'ebpls_business_nature'		=>EBPLS_BUSINESS_NATURE,
			    	'ebpls_business_requirement'	=>EBPLS_BUSINESS_REQUIREMENT,
				'ebpls_business_status'		=>EBPLS_BUSINESS_STATUS,
				'ebpls_business_type'		=>EBPLS_BUSINESS_TYPE,
				'ebpls_city_municipality'	=>EBPLS_CITY_MUNICIPALITY,
				'ebpls_district'		=>EBPLS_DISTRICT,
				'ebpls_province'		=>EBPLS_PROVINCE,
				'ebpls_zip'			=>EBPLS_ZIP,
				'ebpls_zone'			=>EBPLS_ZONE,
				'ebpls_industry_sector'		=>EBPLS_INDUSTRY_SECTOR,
				'ebpls_occupancy_type'		=>EBPLS_OCCUPANCY_TYPE,
				'ebpls_barangay'		=>EBPLS_BARANGAY
				);

$gDB_Details_Levels_Map= array(	'ebpls_business_category'	=> 153,
			    	'ebpls_business_category_offc'	=> 154,
			    	'ebpls_business_nature'		=> 155,
			    	'ebpls_business_requirement'	=> 156,
				'ebpls_business_status'		=> 157,
				'ebpls_business_type'		=> 158,
				'ebpls_city_municipality'	=> 159,
				'ebpls_district'		=> 160,
				'ebpls_province'		=> 161,
				'ebpls_zip'			=> 162,
				'ebpls_zone'			=> 163,
				'ebpls_industry_sector'		=> 164,
				'ebpls_occupancy_type'		=> 165,
				'ebpls_barangay'		=> 166
				);


//--- what table to be searched ???
$selMode = trim($selMode);
//--- paging params
$page 		= (strlen(trim($page))==0) ? (1) : ($page);
$maxpage 	= 200;

//--- init the sysref object
$refTable 	= $gDB_Details_Tables_Map["$selMode"];
$k		= $refTable;
$clsSysRef 	= new EBPLSSysRef ( $dbLink, $gDB_Details_Tables_Map["$selMode"] , $debug );
//--- then have a default search
$retValue	= $clsSysRef->select( NULL , $page , $maxpage , SYSREF_CODE , true );
//--- get the total records
$total_records = count($retValue["result"]);

//--- headers
$search_columns[$k.SYSREF_CODE] 		= "Code";
$search_columns[$k.SYSREF_DESC] 		= "Description";


if( $refTable == EBPLS_BUSINESS_NATURE ) 
{
	
	$search_columns[SYSREF_NATURE_TAX_FEE_CODE] 		= "New App Tax/Fee Code";
	$search_columns[SYSREF_NATURE_TAX_FEE_CODE2] 		= "Renew App Tax/Fee Code";
	
}

$search_columns[$k.SYSREF_CREATE_TS] 		= "Date Created";
$search_columns[$k.SYSREF_UPDATE_TS] 		= "Date Updated";
$search_columns[SYSREF_ADMIN] 			= "Updated By";

//--- update_delete_chart_of_accounts('$code');
function createCommand($rec)
{
	$code	= $rec->getCode();
	if ( !intval($rec->getData(SYSREF_SYSTEMDATA)) ) {
		
		return	  "<input type='radio' name='sysrefcode' value='$code' onClick=\"update_delete_sysref('$code')\">";
		
	}
}

//--- chk the sublevels
//if(   ! is_valid_sublevels($gDB_Details_Levels_Map["$selMode"]) )
// {
 //	setUrlRedirect('index.php?part=999');
	
// }    
?>
<link rel="stylesheet" href="stylesheets/default.css" type="text/css"/>
<script language='Javascript' src='javascripts/default.js'></script>
<div align="CENTER">
<br>
<!---// start of the table //-->
<table border=0 cellspacing=0 cellpadding=0   width='620'>
		<tr><td align="center" valign="center" class='titleblue'  width='620'> DB Table Details Maintenance</td></tr>
		<tr><td align="center" valign="center" class='normal' height=10>&nbsp;</td></tr>
		<tr>
			<td align="center" valign="center" class='title'>
			  <form name="_FRM" method="POST" action="<?php echo(getURI(eBPLS_DB_DETAILS_MAINTENANCE_INPUT)); ?>" onSubmit="">
					<input type='hidden' name='selMode' value='<?php echo $selMode; ?>'>
					<input type='hidden' name='actionMode'>
					<input type='hidden' name='refCode'>
					<input type='hidden' name='page' value='<?php echo $page;?>'>
					<table border=0 cellspacing=1 cellpadding=1 width='620'>
					   <tr><td align="center" valign="top" class='subtitleblue' colspan=2 ><?php echo $gDB_Details_Tables["$selMode"]; ?> Information</td></tr>
					        <tr>
							<td align="right" valign="top" class='normal' colspan=2 height=10>&nbsp;</td></tr>
						<tr>
							<td align="right" valign="top" class='normal' colspan=2>
								<!--// start print results here //-->	
								<?php

								//--- display the result
								if(@is_array($retValue) )
								{
									print_search_results(
											"18",
											$HTTP_GET_VARS,
											"&nbsp;",
											$search_columns,
											$retValue,
											"createCommand"
											);
								}
								
								?>
								<!--// print results here //-->	

							</td>
						</tr>

						<tr>
								<td align="right" valign="top" class='normal' colspan=2> &nbsp;
								</td>
						</tr>
						<tr>
								<td align="center" valign="top" class='normal' colspan=2>

										&nbsp;<input type='BUTTON' name='_ADD' onClick='validate_db_maintenance_details(1,<?php echo intval($total_records); ?>)' value='    A D D    '>
										<?php
										if($total_records > 0 )
										{
										?>
											&nbsp;<input type='BUTTON' name='_UPDATE' onClick='validate_db_maintenance_details(2,<?php echo intval($total_records); ?>)' value='E D I T'>
											&nbsp;<input type='BUTTON' name='_DELETE' onClick='validate_db_maintenance_details(3,<?php echo intval($total_records); ?>)' value='D E L E T E'>
										<?php
										}
										?>

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
