<?php                                  
require_once("../lib/ebpls.utils.php");
define('FPDF_FONTPATH','font/');
require('../ebpls-php-lib/html2pdf_lib/fpdf.php');     
include("../lib/phpFunctions-inc.php");
include("../includes/variables.php");
include("../lib/multidbconnection.php");
                                                                                                 
$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);
$criteria="$brgy_name $owner_last $trans $cap_inv $last_yr";

$result=mysql_query("select lguname, lguprovince, lguoffice from ebpls_buss_preference") 
or die(mysql_error());
$resulta=mysql_fetch_row($result);
$getlgu = @mysql_query("select city_municipality_desc from ebpls_city_municipality where city_municipality_code = '$resulta[0]'");
$getlgu = @mysql_fetch_row($getlgu);
$getprov = @mysql_query("select province_desc from ebpls_province where province_code = '$resulta[1]'");
$getprov = @mysql_fetch_row($getprov);

$result = mysql_query ("select distinct (c.business_permit_code) as pid, a.business_name,
        concat(a.business_lot_no, ' ', a.business_street, ' ', f.barangay_desc, ' ',
        g.city_municipality_desc, ' ', h.province_desc, ' ', a.business_zip_code) as bus_add,
        concat(b.owner_first_name, ' ', b.owner_middle_name, ' ', b.owner_last_name) as fulln,
	b.owner_id, a.business_id, a.blacklist, a.black_list_date , a.black_list_reason, c.released_date
        from ebpls_business_enterprise a, ebpls_owner b, ebpls_business_enterprise_permit c,
	tempbusnature d, ebpls_buss_nature e , ebpls_barangay f , ebpls_city_municipality g , ebpls_province h where
        d.active=1 and a.business_barangay_code = f.barangay_code and g.city_municipality_code = a.business_city_code 
        and h.province_code = a.business_province_code and b.owner_id = a.owner_id and a.business_id = c.business_id and
        c.active=1 and a.blacklist = '1' and b.owner_id = d.owner_id and
	a.business_id=d.business_id
	and a.black_list_date between '$date_from 00:00:00' and '$date_to 23:59:59'
        and a.business_barangay_code  like '$brgy_name%'");
$getuser = @mysql_query("select * from ebpls_user where username = '$usernm'") or die(mysql_error());
$getuser = @mysql_fetch_array($getuser);
$getsignatories = @mysql_query("select * from report_signatories where report_file='Blacklisted Business Establishment' and sign_type='3'");
$getsignatories1 = @mysql_fetch_array($getsignatories);
$getsignatories = @mysql_query("select * from global_sign where sign_id='$getsignatories1[sign_id]'");
$getsignatories1 = @mysql_fetch_array($getsignatories);




?>


<table width=100% border=0 cellpadding=0 cellspacing=0>
	<tr>
		<td align=center rowspan=4>
			<img src='../images/ebpls_logo.jpg' width="100" height="100">
		</td>
		<td align=center>
			<font size=5><b>Province of <? echo $getprov[0];?></b></font>
		</td>
	</tr>
	<tr>
		<td align=center>
			<font size=5><b><? echo $getlgu[0];?></b></font>
		</td>
	</tr>
	<tr>
		<td align=center>
			<font size=5><b>LIST OF BLACKLISTED ESTABLISHMENTS</b></font>
		</td>
	</tr>
	<tr>
		<td align=center>
			<font size=5><b>From <? echo $date_from;?> To <? echo $date_to;?></b></font>
		</td>
	</tr>
	<tr>
		<td align=center colspan=2>
			&nbsp;
		</td>
	</tr>
	<tr>
		<td align=right colspan=2>
			&nbsp;<? echo date('Y-m-d');?>
		</td>
	</tr>
	<tr>
		<td align=left colspan=2>
			<table border=1 cellpadding=0 cellspacing=1>
				<tr>
					<td width=5% align=center><b>No.</b></td>
					<td width=10% align=center><b>Permit Number</b></td>
					<td width=10% align=center><b>Permit Release Date</b></td>
					<td width=15% align=center><b>Owners Name</b></td>
					<td width=15% align=center><b>Business Name</b></td>
					<td width=20% align=center><b>Business Address</b></td>
					<td width=10% align=center><b>Date of Closure</b></td>
					<td width=15% align=center><b>Cause/Violation</b></td>
				</tr>
				<?
				$lrow = 0;
				
				while ($populate = @mysql_fetch_assoc($result))
				{
					$lrow++;
				?>
				<tr>
					<td width=5% align=center><? echo $lrow;?></td>
					<td width=10% align=center><? echo $pid;?></td>
					<td width=10% align=center><? echo $populate['released_date'];?></td>
					<td width=15% align=center><? echo $populate['fulln'];?></td>
					<td width=15% align=center><? echo $populate['business_name'];?></td>
					<td width=20% align=center><? echo $populate['bus_add'];?></td>
					<td width=10% align=center><? echo $populate['black_list_date'];?></td>
					<td width=15% align=center><? echo $populate['black_list_reason'];?></td>
				</tr>
				<?
				}
				?>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan=2>&nbsp;</td>
	</tr>
	<tr width=100%>
		<td align=left colspan=2 width=100%>
			<table border=0 cellpadding=0 cellspacing=0 width=100%>
				<tr>
					<td colspan=4>&nbsp;</td>
				</tr>
				<tr>
					<td width=25%>Prepared By:
					</td>
					<td width=25%>&nbsp;
					</td>
					<td width=25%>Noted By:
					</td>
					<td width=25%>&nbsp;
					</td>
				</tr>
				<tr>
					<td colspan=4>&nbsp;
				</tr>
				<tr>
					<td width=25%>&nbsp;
					</td>
					<td width=25%><? echo $getuser['firstname'].' '.$getuser['lastname'];?>
					</td>
					<td width=25%>&nbsp;
					</td>
					<td width=25%><? echo $getsignatories1['gs_name'];?>
					</td>
				</tr>
				<tr>
					<td width=25%>&nbsp;
					</td>
					<td width=25%>&nbsp;
					</td>
					<td width=25%>&nbsp;
					</td>
					<td width=25%><? echo $getsignatories1['gs_pos'];?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

