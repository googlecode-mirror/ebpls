<?php                                  
require_once("../lib/ebpls.utils.php");
define('FPDF_FONTPATH','font/');
require('../ebpls-php-lib/html2pdf_lib/fpdf.php');     
include("../lib/phpFunctions-inc.php");
include("../includes/variables.php");
include("../lib/multidbconnection.php");
                                                                                                 
$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);
/*$criteria="$brgy_name $owner_last $trans $cap_inv $last_yr";
class PDF extends FPDF
{
var $prov;
var $lgu;
var $office;
var $y0;

	function setLGUinfo($p='', $l='', $o='') {
		$this->prov = $p;
		$this->lgu = $l;
		$this->office = $o;
//		echo 'setLGUinfo'.$this->prov;
	}

function AcceptPageBreak()
{
    //Method accepting or not automatic page break
    if($this->y<2)
    {
        //Set ordinate to top
        $this->SetY($this->y0);
        //Keep on page
        return false;
    }
    else
    {
        return true;
    }
}
	
//Page header
	function Header()
	{
	    //Logo
	    //$this->Image('logo_pb.png',10,8,33);
	    //Arial bold 15
	
	$this->Image('../images/ebpls_logo.jpg',10,8,33);
	$this->SetFont('Arial','B',12);
	$this->Cell(200,5,'REPUBLIC OF THE PHILIPPINES',0,1,'C');
	$this->Cell(200,5,$this->lgu,0,1,'C');
	$this->Cell(200,5,$this->prov,0,2,'C');
	$this->SetFont('Arial','B',14);
	$this->Cell(200,5,$this->office,0,2,'C');
	$this->Cell(200,5,'',0,2,'C');	
	$this->SetFont('Arial','BU',16);
	$this->Cell(200,5,'BUSINESS PROFILE',0,1,'C');
	$this->SetFont('Arial','BU',12);
	$this->Ln(22);
	
} 	
//Page footer
	function Footer()
	{
	    //Position at 1.5 cm from bottom
	    $this->SetY(-15);
	    //Arial italic 8
	    $this->SetFont('Arial','I',8);
	    //Page number
	    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
} // end of PDF class


	$result=mysql_query("select lguname, lguprovince, lguoffice from ebpls_buss_preference") 
	or die(mysql_error());
    $resulta=mysql_fetch_row($result);
$getlgu = @mysql_query("select city_municipality_desc from ebpls_city_municipality where city_municipality_code = '$resulta[0]'");
$getlgu = @mysql_fetch_row($getlgu);
$getprov = @mysql_query("select province_desc from ebpls_province where province_code = '$resulta[1]'");
$getprov = @mysql_fetch_row($getprov);
	
if ($cap_inv2 == "" || $cap_inv2 == 0) {
	$cap_inv2 = 9999999999999;
}
//$pdf=new FPDF('L','mm','Legal');
$pdf=new PDF('P','mm','Legal');
$pdf->setLGUinfo($getlgu[0],$getprov[0],'Office of the Treasurer');
$pdf->AddPage();
$pdf->AliasNbPages();

$pdf->SetFont('Arial','B',10);
$pdf->SetY(40);
$pdf->SetX(10);
$pdf->Cell(25,5,'',0,0,'L');
$pdf->SetX(50);
$pdf->Cell(100,5,'',0,1,'L');

//
if ($owner_last <> "") {
$getinfo = @mysql_query("select owner_id from ebpls_owner where owner_last_name = '$owner_last' limit 1");
$getinfo1 = @mysql_fetch_row($getinfo);
$getinfo = @mysql_query("select owner_id, business_id, business_permit_code from ebpls_business_enterprise_permit where owner_id = '$getinfo1[0]' ORDER BY business_permit_id DESC limit 1");
}
if ($bus_name <> "") {
$getinfo = @mysql_query("select business_id from ebpls_business_enterprise where business_name = '$bus_name' limit 1");
$getinfo1 = @mysql_fetch_row($getinfo);
$getinfo = @mysql_query("select owner_id, business_id, business_permit_code from ebpls_business_enterprise_permit where business_id = '$getinfo1[0]' ORDER BY business_permit_id DESC limit 1");
//echo "select business_id from ebpls_business_enterprise where business_name = '$bus_name' limit 1";
}
if ($pin <> "") {
$getinfo = @mysql_query("select owner_id, business_id, business_permit_code from ebpls_business_enterprise_permit where pin = '$pin' ORDER BY business_permit_id DESC limit 1");
}
$getinfo = @mysql_fetch_assoc($getinfo);

	$result=mysql_query("select a.business_permit_code, a.application_date, 
				b.business_name, a.transaction, 
				concat(c.owner_first_name, ' ', c.owner_middle_name, ' ', c.owner_last_name) as fulln, 
				c.owner_gender,	concat(c.owner_street, ' ', e.zone_desc, ' ', 
				f.barangay_desc, ' ', g.district_desc, ' ', h.city_municipality_desc, ' ', i.province_desc) as owneradd, 
				a.business_permit_id, d.cap_inv, 
				d.last_yr, b.employee_male, b.employee_female, d.bus_nature, b.business_start_date, b.business_date_established, 
				a.for_year, a.business_permit_code, b.business_contact_no, b.business_category_code, 
				b.business_payment_mode, business_occupancy_code, 
				c.owner_civil_status, c.owner_citizenship, c.owner_tin_no, c.owner_birth_date 
				from ebpls_business_enterprise_permit a, 
				ebpls_business_enterprise b, ebpls_owner c, tempbusnature d, 
				ebpls_zone e, ebpls_barangay f, ebpls_district g, ebpls_city_municipality h, ebpls_province i
				where a.business_permit_code ='$getinfo[business_permit_code]' and 
				a.business_id = b.business_id and a.owner_id = c.owner_id 
				and b.business_id = d.business_id and c.owner_zone_code = e.zone_code and 
				c.owner_barangay_code = f.barangay_code and c.owner_district_code = g.district_code and 
				c.owner_city_code = h.city_municipality_code and c.owner_province_code = i.province_code order by a.business_permit_code asc limit 1") or die(mysql_error()."fff");
				 
    $resulta=mysql_fetch_assoc($result);
	$result1=mysql_query("select concat(b.business_lot_no, ' ', 
				b.business_street, ' ', e.zone_desc, ' ', 
				f.barangay_desc, ' ', g.district_desc, ' ', h.city_municipality_desc, ' ', i.province_desc) as busadd
				from ebpls_business_enterprise_permit a, 
				ebpls_business_enterprise b, ebpls_owner c, tempbusnature d, 
				ebpls_zone e, ebpls_barangay f, ebpls_district g, ebpls_city_municipality h, ebpls_province i
				where a.business_permit_code ='$getinfo[business_permit_code]' and 
				a.business_id = b.business_id and a.owner_id = c.owner_id 
				and b.business_id = d.business_id and b.business_zone_code = e.zone_code and 
				b.business_barangay_code = f.barangay_code and b.business_district_code = g.district_code and 
				b.business_city_code = h.city_municipality_code and b.business_province_code = i.province_code order by a.business_permit_code asc limit 1") or die(mysql_error()."fff");
    $resulta1=mysql_fetch_assoc($result1);
$result2=mysql_query("select * from ebpls_business_category where 
business_category_code ='$resulta[business_category_code]'") or die(mysql_error()."fff");
    $resulta2=mysql_fetch_assoc($result2);
$Y_Label_position = 50;
$Y_Table_Position = 55;
$pdf->SetFont('Arial','B',8);
$pdf->SetY($Y_Label_position);
$pdf->SetX(5);
$pdf->Cell(40,5,'OWNER NAME :',0,0,'L');
$pdf->SetFont('Arial','BU',8);
$pdf->Cell(60,5,$resulta[fulln],0,1,'L');
$pdf->SetFont('Arial','B',8);
$pdf->SetX(5);
$pdf->Cell(40,5,'OWNER ADDRESS :',0,0,'L');
$pdf->SetFont('Arial','BU',8);
$pdf->Cell(160,5,$resulta[owneradd],0,1,'L');
$pdf->SetFont('Arial','B',8);
$pdf->SetX(5);
$pdf->Cell(40,5,'NATIONALITY :',0,0,'L');
$pdf->SetFont('Arial','BU',8);
$pdf->Cell(40,5,$resulta[owner_citizenship],0,0,'L');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,5,'TIN :',0,0,'L');
$pdf->SetFont('Arial','BU',8);
$pdf->Cell(25,5,$resula[owner_tin_no],0,1,'L');
$pdf->SetFont('Arial','B',8);
$pdf->SetX(5);
$pdf->Cell(40,5,'BUSINESS NAME :',0,0,'L');
$pdf->SetFont('Arial','BU',8);
$pdf->Cell(60,5,$resulta[business_name],0,1,'L');
$pdf->SetFont('Arial','B',8);
$pdf->SetX(5);
$pdf->Cell(40,5,'BUSINESS ADDRESS :',0,0,'L');
$pdf->SetFont('Arial','BU',8);
$pdf->Cell(160,5,$resulta[busradd],0,1,'L');
$pdf->SetFont('Arial','B',8);
$pdf->SetX(5);
$pdf->Cell(40,5,'TELEPHONE # :',0,0,'L');
$pdf->SetFont('Arial','BU',8);
$pdf->Cell(25,5,$resulta[business_contact_no],0,1,'L');
$pdf->SetFont('Arial','B',8);
$pdf->SetX(5);
$pdf->Cell(40,5,'CAPITAL INVESTMENT :',0,0,'L');
$pdf->SetFont('Arial','BU',8);
$pdf->Cell(50,5,$resula[cap_inv],0,0,'L');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(40,5,'GROSS RECEIPTS :',0,0,'L');
$pdf->SetFont('Arial','BU',8);
$pdf->Cell(50,5,$resula[last_yr],0,1,'L');
$pdf->SetFont('Arial','B',8);
$pdf->SetX(5);
$pdf->Cell(40,5,'TYPE OF OWNERSHIP :',0,0,'L');
$pdf->SetFont('Arial','BU',8);
$pdf->Cell(50,5,$resula2[business_category_desc],0,0,'L');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(40,5,'EXEMPTION :',0,0,'L');
$pdf->SetFont('Arial','BU',8);
$pdf->Cell(50,5,$resula[last_yr],0,1,'L');
$pdf->SetFont('Arial','B',8);
$pdf->SetX(5);
$pdf->Cell(40,5,'START DATE :',0,0,'L');
$pdf->SetFont('Arial','BU',8);
$pdf->Cell(50,5,$resula[business_start_date],0,0,'L');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(40,5,'DATE ESTABLISHED :',0,0,'L');
$pdf->SetFont('Arial','BU',8);
$pdf->Cell(50,5,$resula[business_date_established],0,1,'L');
$pdf->SetFont('Arial','B',8);
$pdf->SetX(5);
$pdf->Cell(40,5,'PERMIT # :',0,0,'L');
$pdf->SetFont('Arial','BU',8);
$pdf->Cell(50,5,$resula[business_permit_code],0,0,'L');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(40,5,'VALIDITY :',0,0,'L');
$pdf->SetFont('Arial','BU',8);
$pdf->Cell(50,5,$resula[for_year],0,1,'L');
$pdf->SetX(5);
$pdf->Cell(10,5,'',0,0,'L');
$pdf->SetX(15);
$pdf->Cell(25,5,'',0,0,'L');
$pdf->SetX(40);
$pdf->Cell(55,5,'',0,0,'L');
$pdf->SetX(95);
$pdf->Cell(60,5,'',0,0,'L');
$pdf->SetX(155);
$pdf->Cell(90,5,'',0,1,'L');
//$pdf->SetX(305);


$pdf->Cell(270,5,'',0,1,'C');
$pdf->Cell(270,5,'',0,1,'C');

//$pdf->SetY(-18);
$pdf->SetX(5);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(150,5,'Prepared By :',0,0,'L');
$pdf->Cell(50,5,'Noted By :',0,1,'L');

$pdf->Cell(270,5,'',0,1,'C');
$pdf->Cell(270,5,'',0,1,'C');

$getuser = @mysql_query("select * from ebpls_user where username = '$usernm'") or die(mysql_error());
$getuser = @mysql_fetch_array($getuser);
$getsignatories = @mysql_query("select * from report_signatories where report_file='Business Profile' and sign_type='3'");
$getsignatories1 = @mysql_fetch_array($getsignatories);
$getsignatories = @mysql_query("select * from global_sign where sign_id='$getsignatories1[sign_id]'");
$getsignatories1 = @mysql_fetch_array($getsignatories);
$pdf->SetX(5);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(150,5,$getuser[firstname].' '.$getuser[lastname],0,0,'L');
$pdf->Cell(50,5,$getsignatories1[gs_name],0,1,'L');
$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(150,5,'',0,0,'C');
$pdf->Cell(50,5,$getsignatories1[gs_pos],0,1,'L');

$report_desc='Business Establishment';
//include '../report_signatories_footer1.php';

$pdf->Output();
*/
	$result=mysql_query("select lguname, lguprovince, lguoffice from ebpls_buss_preference") 
	or die(mysql_error());
    $resulta=mysql_fetch_row($result);
$getlgu = @mysql_query("select city_municipality_desc from ebpls_city_municipality where city_municipality_code = '$resulta[0]'");
$getlgu = @mysql_fetch_row($getlgu);
$getprov = @mysql_query("select province_desc from ebpls_province where province_code = '$resulta[1]'");
$getprov = @mysql_fetch_row($getprov);
if ($owner_last <> "") {
$getinfo = @mysql_query("select owner_id from ebpls_owner where owner_last_name = '$owner_last' limit 1");
$getinfo1 = @mysql_fetch_row($getinfo);
$getinfo = @mysql_query("select owner_id, business_id, business_permit_code from ebpls_business_enterprise_permit where owner_id = '$getinfo1[0]' ORDER BY business_permit_id DESC limit 1");
}
if ($bus_name <> "") {
$getinfo = @mysql_query("select business_id from ebpls_business_enterprise where business_name = '$bus_name' limit 1");
$getinfo1 = @mysql_fetch_row($getinfo);
$getinfo = @mysql_query("select owner_id, business_id, business_permit_code from ebpls_business_enterprise_permit where business_id = '$getinfo1[0]' ORDER BY business_permit_id DESC limit 1");
//echo "select business_id from ebpls_business_enterprise where business_name = '$bus_name' limit 1";
}
if ($pin <> "") {
$getinfo = @mysql_query("select owner_id, business_id, business_permit_code from ebpls_business_enterprise_permit where pin = '$pin' ORDER BY business_permit_id DESC limit 1");
}
$getinfo = @mysql_fetch_assoc($getinfo);

	$result=mysql_query("select a.business_permit_code, a.application_date, 
				b.business_name, a.transaction, 
				concat(c.owner_first_name, ' ', c.owner_middle_name, ' ', c.owner_last_name) as fulln, 
				c.owner_gender,	concat(c.owner_street, ' ', e.zone_desc, ' ', 
				f.barangay_desc, ' ', g.district_desc, ' ', h.city_municipality_desc, ' ', i.province_desc) as owneradd, 
				a.business_permit_id, d.cap_inv, 
				d.last_yr, b.employee_male, b.employee_female, d.bus_nature, b.business_start_date, b.business_date_established, 
				a.for_year, a.business_permit_code, b.business_contact_no, b.business_category_code, 
				b.business_payment_mode, business_occupancy_code, 
				c.owner_civil_status, c.owner_citizenship, c.owner_tin_no, c.owner_birth_date,  c.owner_first_name, c.owner_middle_name, c.owner_last_name, b.biztype , b.business_id, c.owner_id 
				from ebpls_business_enterprise_permit a, 
				ebpls_business_enterprise b, ebpls_owner c, tempbusnature d, 
				ebpls_zone e, ebpls_barangay f, ebpls_district g, ebpls_city_municipality h, ebpls_province i
				where a.business_permit_code ='$getinfo[business_permit_code]' and 
				a.business_id = b.business_id and a.owner_id = c.owner_id 
				and b.business_id = d.business_id and c.owner_zone_code = e.zone_code and 
				c.owner_barangay_code = f.barangay_code and c.owner_district_code = g.district_code and 
				c.owner_city_code = h.city_municipality_code and c.owner_province_code = i.province_code order by a.business_permit_code asc limit 1") or die(mysql_error()."fff");
				 
    $resulta=mysql_fetch_assoc($result);
	$result1=mysql_query("select concat(b.business_lot_no, ' ', 
				b.business_street, ' ', e.zone_desc, ' ', 
				f.barangay_desc, ' ', g.district_desc, ' ', h.city_municipality_desc, ' ', i.province_desc) as busadd
				from ebpls_business_enterprise_permit a, 
				ebpls_business_enterprise b, ebpls_owner c, tempbusnature d, 
				ebpls_zone e, ebpls_barangay f, ebpls_district g, ebpls_city_municipality h, ebpls_province i
				where a.business_permit_code ='$getinfo[business_permit_code]' and 
				a.business_id = b.business_id and a.owner_id = c.owner_id 
				and b.business_id = d.business_id and b.business_zone_code = e.zone_code and 
				b.business_barangay_code = f.barangay_code and b.business_district_code = g.district_code and 
				b.business_city_code = h.city_municipality_code and b.business_province_code = i.province_code order by a.business_permit_code asc limit 1") or die(mysql_error()."fff");

    $resulta1=mysql_fetch_assoc($result1);
$result2=mysql_query("select * from ebpls_business_category where 
business_category_code ='$resulta[business_category_code]'") or die(mysql_error()."fff");
    $resulta2=mysql_fetch_assoc($result2);

$cur_year=date("Y");
$cur_month=date("m");
$cur_day=date("d");            

$dob_year=substr($resulta[owner_birth_date], 0, 4);
$dob_month=substr($resulta[owner_birth_date], 5, 2);
$dob_day=substr($resulta[owner_birth_date], 8, 2);            
       
if($cur_month>$dob_month || ($dob_month==$cur_month && $cur_day>=$dob_day) ) {
	$nAge = $cur_year-$dob_year;
} else {
	$nAge = $cur_year-$dob_year-1;
}
$getctc = @mysql_query("select * from ebpls_ctc_individual where ctc_first_name = '$resulta[owner_first_name]' and ctc_middle_name = '$resulta[owner_middle_name]' and ctc_last_name = '$resulta[owner_last_name]' and ctc_for_year = '$resulta[for_year]' order by ctc_owner_id desc limit 1");
$getctc = mysql_fetch_assoc($getctc);
$getuser = @mysql_query("select * from ebpls_user where username = '$usernm'") or die(mysql_error());
$getuser = @mysql_fetch_array($getuser);
$getsignatories = @mysql_query("select * from report_signatories where report_file='Business Profile' and sign_type='3'");
$getsignatories1 = @mysql_fetch_array($getsignatories);
$getsignatories = @mysql_query("select * from global_sign where sign_id='$getsignatories1[sign_id]'");
$getsignatories1 = @mysql_fetch_array($getsignatories);
$stat = $resulta[transaction];
$owner_id = $resulta[owner_id];
$business_id = $resulta[owner_id];
$reportito = 1;
?>

<table width=100% border=0 cellpadding=0 cellspacing=0>
	<tr>
		<td align=center rowspan=4>
			<img src='../images/ebpls_logo.jpg' height=100 width=100 align=left valign=top>
		</td>
		<td align=center>
			<font size=5><b><? echo $getprov[0];?></b></font>
		</td>
	</tr>
	<tr>
		<td align=center>
			<font size=5><b><? echo $getlgu[0];?></b></font>
		</td>
	</tr>
	<tr>
		<td align=center>
			<font size=5><b>BUSINESS PROFILE</b></font>
		</td>
	</tr>
	<tr>
		<td align=center colspan=2>
			&nbsp;
		</td>
	</tr>
	<tr>
		<td colspan=2 align=right>
			&nbsp;<b><? echo date('Y-m-d');?></b>&nbsp;
		</td>
	</tr>
	<tr width=100%>
		<td align=left colspan=2 width=100%>
			<table border=1 cellpadding=0 cellspacing=0 width=100%>
				<tr>
					<td colspan=4>&nbsp;Owner's Name : <? echo $resulta[fulln];?>
					</td>
				</tr>
				<tr>
					<td colspan=4>&nbsp;Address : <? echo $resulta[owneradd];?>
					</td>
				</tr>
				<tr>
					<td width=25%>&nbsp;Age : <? echo $nAge;?>
					</td>
					<td width=25%>&nbsp;TIN : <? echo $resulta[owner_tin_no];?>
					</td>
					<td width=25%>&nbsp;CTC # : <? echo $resulta[ctc_code];?>
					</td>
					<td width=25%>&nbsp;Nationality : <? echo $resulta[owner_citizenship];?>
					</td>
				</tr>
				<tr>
					<td colspan=4>&nbsp;Business Name : <? echo $resulta[business_name];?>
					</td>
				</tr>
				<tr>
					<td colspan=4>&nbsp;Address : <? echo $resulta1[busadd];?>
					</td>
				</tr>
				<tr>
					<td colspan=2>&nbsp;Capital Investment : <? echo number_format($resulta[cap_inv],2);?>
					</td>
					<td colspan=2>&nbsp;Gross Receipts : <? echo number_format($resulta[last_yr],2);?>
					</td>
				</tr>
				<tr>
					<td width=25%>&nbsp;Type of Application : <? echo $resulta[transaction];?>
					</td>
					<td width=25%>&nbsp;Type of Ownership : <? echo $resulta2[business_category_desc ];?>
					</td>
					<td width=25%>&nbsp;Exemption : <? echo $resulta2[tax_exemption ];?>%
					</td>
					<td width=25%>&nbsp;Branch Type : <? echo $resulta[biztype];?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr width=100%>
		<td align=center colspan=2 width=100%>&nbsp;Assessment Details&nbsp;</td>
	</tr>
	<? include_once "../includes/assessment.php";?>
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


