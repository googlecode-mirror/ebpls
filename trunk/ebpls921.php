<?php 
require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");                                                                                                                        
require_once("ebpls-php-lib/utils/ebpls.search.funcs.php");
require_once("includes/eBPLS_header.php");
require_once "includes/variables.php";                                                                                                                                                                                                                                     
$daytoday = date('d');
$monthtoday = date('m');
$yeartoday = date('Y');
//--- get connection from DB
//$dbLink = get_db_connection();

?>

<?php

if ($sys_report=='Add') {

	$sdup = mysql_query("select * from rpt_temp_abs where tfoid=$rpt_temp");
	$sdup = mysql_num_rows($sdup);
	if ($sdup==0){
	$saveit = mysql_query("insert into rpt_temp_abs values ('',$rpt_temp)");
	} else {
	echo "<font color=red>Duplicate Entry Found</font>";
	}
}

if ($com=='delete') {
	$delit = mysql_query("delete from rpt_temp_abs where rpt_id=$rpt_id");
}


if ($buss_select<>'' and $bus_report=='View Report' and $permit_num<>'') {

?>
<body onLoad=" alert ("gggg");"> </body>
<?php
}
?>

<script language="JavaScript" src="javascripts/default.js"></script>
<link rel="stylesheet" href="stylesheets/calendar.css?random=20051112" media="screen"></LINK>
<SCRIPT type="text/javascript" src="javascripts/calendar.js?random=20060118"></script>
<script language="javascript">
function PM(x, pn, bn, ci, gs, ol, tr, br)
        {
       
        var fr = document._FRM;
  
        	if (fr.col_sel.value=='ebpls_orderpayment.php') {
	        	winpopup = window.open('reports/' + fr.col_sel.value + '?date_from=' + fr.checkid1.value + '&date_to=' + fr.checkid2.value + '&owner_last_name=' + fr.owner_last_name.value + '&owner_first_name=' + fr.owner_first_name.value + '&business_name=' + fr.business_name.value,'popup','height=800,width=800,menubar=no,scrollbars=yes,status=no,screenX=100,screenY=0,left=100,top=0,resizable=1');
    		} else {
	
        winpopup = window.open('reports/' + fr.col_sel.value + '?date_from=' + fr.checkid1.value + '&date_to=' + fr.checkid2.value,'popup','height=800,width=800,menubar=no,scrollbars=yes,status=no,screenX=100,screenY=0,left=100,top=0,resizable=1');
    		}
        
                                                                                                                                                            
        }
function AbstractColl(x)
        {
//		alert(x);
        var _FRM = document._FRM;
		var vDateSplit1 = _FRM.checkid1.value.split("/");
		vDate1 = vDateSplit1[0] + vDateSplit1[1] + vDateSplit1[2];
		var vDateSplit2 = _FRM.checkid2.value.split("/");
		vDate2 = vDateSplit2[0] + vDateSplit2[1] + vDateSplit2[2];
		var getDiff = (vDate2-vDate1);
		var today = new Date();
		today.setFullYear(<? echo $yeartoday;?>,<? echo $monthtoday - 1;?>,<? echo $daytoday;?>);
		var datefrom = new Date();
		var dateto = new Date();
		datefrom.setFullYear(vDateSplit1[0],vDateSplit1[1]-1,vDateSplit1[2]);
		dateto.setFullYear(vDateSplit2[0],vDateSplit2[1]-1,vDateSplit2[2]);
		
		if (datefrom > today) {
			alert("Date Range cannot exceed current date.");
			_FRM.checkid1.select();
			return false;
		}
		if (dateto > today) {
			alert("Date Range cannot exceed current date.");
			_FRM.checkid2.select();
			return false;
		}
		if (getDiff < 0) {
			alert("Invalid Date Range!!");
			_FRM.checkid1.select();
			return false;
		}
        winpopup = window.open('reports/' + x + '?date_from=' + _FRM.checkid1.value + '&date_to=' + _FRM.checkid2.value + '&usernm=' + _FRM.usernm.value);
//,'popup','height=500,width=800,menubar=no,scrollbars=yes,status=no,screenX=100,screenY=0,left=100,top=0');
        return true;
                                                                                                 
                                                                                                 
}
function ComparativeAnnual(x)
        {
//              alert(x);
        var _FRM = document._FRM;
		var vDateSplit1 = _FRM.checkid1.value.split("/");
		vDate1 = vDateSplit1[0] + vDateSplit1[1] + vDateSplit1[2];
		var vDateSplit2 = _FRM.checkid2.value.split("/");
		vDate2 = vDateSplit2[0] + vDateSplit2[1] + vDateSplit2[2];
		var getDiff = (vDate2-vDate1);
		var today = new Date();
		today.setFullYear(<? echo $yeartoday;?>,<? echo $monthtoday - 1;?>,<? echo $daytoday;?>);
		var datefrom = new Date();
		var dateto = new Date();
		datefrom.setFullYear(vDateSplit1[0],vDateSplit1[1]-1,vDateSplit1[2]);
		dateto.setFullYear(vDateSplit2[0],vDateSplit2[1]-1,vDateSplit2[2]);
		
		if (datefrom > today) {
			alert("Date Range cannot exceed current date.");
			_FRM.checkid1.select();
			return false;
		}
		if (dateto > today) {
			alert("Date Range cannot exceed current date.");
			_FRM.checkid2.select();
			return false;
		}
		if (getDiff < 0) {
			alert("Invalid Date Range!!");
			_FRM.checkid1.select();
			return false;
		}
        winpopup = window.open('reports/' + x + '?owner_last=' + _FRM.checkid1.value + '&date_to=' + _FRM.checkid2.value + '&usernm=' + _FRM.usernm.value);
//,'popup','height=500,width=800,menubar=no,scrollbars=yes,status=no,screenX=100,screenY=0,left=100,top=0');
        return true;
 
 
}        
function AuditTrail(x)
        {
//              alert(x);
        var _FRM = document._FRM;
        winpopup = window.open('reports/' + x + '?date_from=' + _FRM.checkid1.value + '&date_to=' + _FRM.checkid2.value +  '&lastn=' + _FRM.owner_last.value + '&pin=' + _FRM.pin.value);
//,'popup','height=500,width=800,menubar=no,scrollbars=yes,status=no,screenX=100,screenY=0,left=100,top=0');
        return true;
 
 
}        
function CTCIssueds(x)
        {
//              alert(x);
        var _FRM = document._FRM;
		var vDateSplit1 = _FRM.checkid1.value.split("/");
		vDate1 = vDateSplit1[0] + vDateSplit1[1] + vDateSplit1[2];
		var vDateSplit2 = _FRM.checkid2.value.split("/");
		vDate2 = vDateSplit2[0] + vDateSplit2[1] + vDateSplit2[2];
		var getDiff = (vDate2-vDate1);
		var today = new Date();
		today.setFullYear(<? echo $yeartoday;?>,<? echo $monthtoday - 1;?>,<? echo $daytoday;?>);
		var datefrom = new Date();
		var dateto = new Date();
		datefrom.setFullYear(vDateSplit1[0],vDateSplit1[1]-1,vDateSplit1[2]);
		dateto.setFullYear(vDateSplit2[0],vDateSplit2[1]-1,vDateSplit2[2]);
		
		if (datefrom > today) {
			alert("Date Range cannot exceed current date.");
			_FRM.checkid1.select();
			return false;
		}
		if (dateto > today) {
			alert("Date Range cannot exceed current date.");
			_FRM.checkid2.select();
			return false;
		}
		if (getDiff < 0) {
			alert("Invalid Date Range!!");
			_FRM.checkid1.select();
			return false;
		}
        winpopup = window.open('reports/' + x + '?date_from=' + _FRM.checkid1.value + '&date_to=' + _FRM.checkid2.value);
//,'popup','height=500,width=800,menubar=no,scrollbars=yes,status=no,screenX=100,screenY=0,left=100,top=0');
        return true;
 
 
}        
function ComparativeQuart(x)
        {
//              alert(x);
        var _FRM = document._FRM;
		var vDateSplit1 = _FRM.checkid1.value.split("/");
		vDate1 = vDateSplit1[0] + vDateSplit1[1] + vDateSplit1[2];
		var vDateSplit2 = _FRM.checkid2.value.split("/");
		vDate2 = vDateSplit2[0] + vDateSplit2[1] + vDateSplit2[2];
		var getDiff = (vDate2-vDate1);
		var today = new Date();
		today.setFullYear(<? echo $yeartoday;?>,<? echo $monthtoday - 1;?>,<? echo $daytoday;?>);
		var datefrom = new Date();
		var dateto = new Date();
		datefrom.setFullYear(vDateSplit1[0],vDateSplit1[1]-1,vDateSplit1[2]);
		dateto.setFullYear(vDateSplit2[0],vDateSplit2[1]-1,vDateSplit2[2]);
		
		if (datefrom > today) {
			alert("Date Range cannot exceed current date.");
			_FRM.checkid1.select();
			return false;
		}
		if (dateto > today) {
			alert("Date Range cannot exceed current date.");
			_FRM.checkid2.select();
			return false;
		}
		if (getDiff < 0) {
			alert("Invalid Date Range!!");
			_FRM.checkid1.select();
			return false;
		}
        winpopup = window.open('reports/' + x + '?owner_last=' + _FRM.checkid1.value + '&date_to=' + _FRM.checkid2.value + '&usernm=' + _FRM.usernm.value + '&iQrt=' + _FRM.iQrt.value);
//,'popup','height=500,width=800,menubar=no,scrollbars=yes,status=no,screenX=100,screenY=0,left=100,top=0');
        return true;
 
 
}        
function ComparativeAnnualChart(x)
        {
//              alert(x);
        var _FRM = document._FRM;
		var vDateSplit1 = _FRM.checkid1.value.split("/");
		vDate1 = vDateSplit1[0] + vDateSplit1[1] + vDateSplit1[2];
		var vDateSplit2 = _FRM.checkid2.value.split("/");
		vDate2 = vDateSplit2[0] + vDateSplit2[1] + vDateSplit2[2];
		var getDiff = (vDate2-vDate1);
		var today = new Date();
		today.setFullYear(<? echo $yeartoday;?>,<? echo $monthtoday - 1;?>,<? echo $daytoday;?>);
		var datefrom = new Date();
		var dateto = new Date();
		datefrom.setFullYear(vDateSplit1[0],vDateSplit1[1]-1,vDateSplit1[2]);
		dateto.setFullYear(vDateSplit2[0],vDateSplit2[1]-1,vDateSplit2[2]);
		
		if (datefrom > today) {
			alert("Date Range cannot exceed current date.");
			_FRM.checkid1.select();
			return false;
		}
		if (dateto > today) {
			alert("Date Range cannot exceed current date.");
			_FRM.checkid2.select();
			return false;
		}
		if (getDiff < 0) {
			alert("Invalid Date Range!!");
			_FRM.checkid1.select();
			return false;
		}
        winpopup = window.open('reports/' + x + '?owner_last=' + _FRM.checkid1.value + '&date_to=' + _FRM.checkid2.value );
//,'popup','height=500,width=800,menubar=no,scrollbars=yes,status=no,screenX=100,screenY=0,left=100,top=0');
        return true;


}     
function BussCollQuart(x)
        {
//              alert(x);
        var _FRM = document._FRM;
		var vDateSplit1 = _FRM.checkid1.value.split("/");
		vDate1 = vDateSplit1[0] + vDateSplit1[1] + vDateSplit1[2];
		var vDateSplit2 = _FRM.checkid2.value.split("/");
		vDate2 = vDateSplit2[0] + vDateSplit2[1] + vDateSplit2[2];
		var getDiff = (vDate2-vDate1);
		var today = new Date();
		today.setFullYear(<? echo $yeartoday;?>,<? echo $monthtoday - 1;?>,<? echo $daytoday;?>);
		var datefrom = new Date();
		var dateto = new Date();
		datefrom.setFullYear(vDateSplit1[0],vDateSplit1[1]-1,vDateSplit1[2]);
		dateto.setFullYear(vDateSplit2[0],vDateSplit2[1]-1,vDateSplit2[2]);
		
		if (datefrom > today) {
			alert("Date Range cannot exceed current date.");
			_FRM.checkid1.select();
			return false;
		}
		if (dateto > today) {
			alert("Date Range cannot exceed current date.");
			_FRM.checkid2.select();
			return false;
		}
		if (getDiff < 0) {
			alert("Invalid Date Range!!");
			_FRM.checkid1.select();
			return false;
		}
        winpopup = window.open('reports/' + x + '?owner_last=' + _FRM.checkid1.value + '&date_to=' + _FRM.checkid2.value );
//,'popup','height=500,width=800,menubar=no,scrollbars=yes,status=no,screenX=100,screenY=0,left=100,top=0');
        return true;


}
         
function SummaryCollect(x)
	{
	var _FRM = document._FRM;
	var vDateSplit1 = _FRM.checkid1.value.split("/");
		vDate1 = vDateSplit1[0] + vDateSplit1[1] + vDateSplit1[2];
		var vDateSplit2 = _FRM.checkid2.value.split("/");
		vDate2 = vDateSplit2[0] + vDateSplit2[1] + vDateSplit2[2];
		var getDiff = (vDate2-vDate1);
		var today = new Date();
		today.setFullYear(<? echo $yeartoday;?>,<? echo $monthtoday - 1;?>,<? echo $daytoday;?>);
		var datefrom = new Date();
		var dateto = new Date();
		datefrom.setFullYear(vDateSplit1[0],vDateSplit1[1]-1,vDateSplit1[2]);
		dateto.setFullYear(vDateSplit2[0],vDateSplit2[1]-1,vDateSplit2[2]);
		
		if (datefrom > today) {
			alert("Date Range cannot exceed current date.");
			_FRM.checkid1.select();
			return false;
		}
		if (dateto > today) {
			alert("Date Range cannot exceed current date.");
			_FRM.checkid2.select();
			return false;
		}
		if (getDiff < 0) {
			alert("Invalid Date Range!!");
			_FRM.checkid1.select();
			return false;
		}
	winpopup = window.open('reports/' + x + '?taxtype=' + _FRM.taxtype.value + '&date_from=' + _FRM.checkid1.value + '&date_to=' + _FRM.checkid2.value + '&usernm=' + _FRM.usernm.value);
	return true;
}
</script>







<form method=post name=_FRM action='index.php?part=4&class_type=Reports&itemID_=921&busItem=Reports&permit_type=Reports&rpt=<?php echo $rpt;?>'>

<div align="center">

<table border=0 cellspacing=0 cellpadding=0  width='100%'>
<tr><td colspan=2 class=header align=center width=100%>REPORTS</td></tr>
<tr><td colspan=2 ><br></td></tr>
<?php 
		if ($rpt=='Business') {
		?>
			<tr>
				<td align="center" valign="top" width=100% colspan=2>
					<table border=0 cellspacing=0 cellpadding=0  width='100%'>
						<tr>
							<td class='header2' colspan=2 align=center>Business Permit</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
						</tr>
						<tr>
                       <td width=40% align=right>
                       Reports </td>
                       <td width=60% align=left>&nbsp;
<?php
			
			echo get_select_reports($dbLink,"bus_selecty","ebpls_reports","report_id","report_desc","$bus_selecty","true","report_type='$rpt' order by report_desc asc","onchange='_FRM.submit();'",$user_id);

			if ($bus_selecty<>'') {
			include_once "class/ReportClass.php";
			$gReport=new NewReport;
			$gReport->GetReport($bus_selecty);
			$rfile=$gReport->outarray;
			$report_file=$rfile[report_file];
			$report_desc=$rfile[report_desc];
			}

?>
<input type=hidden name=report_file value="<?php echo $report_file; ?>">
			<input type=hidden name=reetFullYear(<? echo $yeartoday;?>,<? echo $monthtoday - 1;?>,<? echo $daytoday;?>);today.setFullYear(<? echo $yeartoday;?>,<? echo $monthtoday - 1;?>,<? echo $daytoday;?>);ort_file value="<?php echo $report_file; ?>">
			<input type=hidden name=usernm value="<?php echo $ThUserData['username']; ?>">
			</td></tr>
<script language="javascript">
function BusEstab()
        {
	var _FRM = document._FRM;
	var vDateSplit1 = _FRM.date_from.value.split("/");
		vDate1 = vDateSplit1[0] + vDateSplit1[1] + vDateSplit1[2];
		var vDateSplit2 = _FRM.date_to.value.split("/");
		vDate2 = vDateSplit2[0] + vDateSplit2[1] + vDateSplit2[2];
		var getDiff = (vDate2-vDate1);
		var today = new Date();
		today.setFullYear(<? echo $yeartoday;?>,<? echo $monthtoday - 1;?>,<? echo $daytoday;?>);
		var datefrom = new Date();
		var dateto = new Date();
		datefrom.setFullYear(vDateSplit1[0],vDateSplit1[1]-1,vDateSplit1[2]);
		dateto.setFullYear(vDateSplit2[0],vDateSplit2[1]-1,vDateSplit2[2]);
		
		if (datefrom > today) {
			alert("Date Range cannot exceed current date.");
			_FRM.date_from.select();
			return false;
		}
		if (dateto > today) {
			alert("Date Range cannot exceed current date.");
			_FRM.date_to.select();
			return false;
		}
		if (getDiff < 0) {
			alert("Invalid Date Range!!");
			_FRM.date_from.select();
			return false;
		}
		if(parseFloat(_FRM.cap_inv1.value) < 0 || isNaN(_FRM.cap_inv1.value) == true) {
			alert("Invalid Capital Investment Range!!");
			_FRM.cap_inv1.focus();
			_FRM.cap_inv1.select();
			return false;
		}
		if(parseFloat(_FRM.cap_inv1.value) > 999999999999.99) {
			alert("Capital Investment Exceeds Max!");
			_FRM.cap_inv1.focus();
			_FRM.cap_inv1.select();
			return false;
		}
		if (parseFloat(_FRM.cap_inv2.value) < 0 || isNaN(_FRM.cap_inv2.value) == true)
		{
			alert("Invalid Capital Investment Range!!");
			_FRM.cap_inv2.focus();
			_FRM.cap_inv2.select();
			return false;
		}
		if(parseFloat(_FRM.cap_inv2.value) > 999999999999.99) {
			alert("Capital Investment Exceeds Max!");
			_FRM.cap_inv2.focus();
			_FRM.cap_inv2.select();
			return false;
		}
		if (parseFloat(_FRM.cap_inv1.value) > parseFloat(_FRM.cap_inv2.value))
		{
			alert("Invalid Capital Investment Range!!");
			_FRM.cap_inv1.focus();
			_FRM.cap_inv1.select();
			return false;
		}
		winpopup = window.open('reports/' + _FRM.report_file.value + '?date_from=' + _FRM.date_from.value + '&date_to=' + _FRM.date_to.value + '&brgy_name=' + _FRM.brgy_name.value + '&owner_last=' + _FRM.owner_last.value + '&business_nature_code=' + _FRM.business_nature_code.value + '&cap_inv1=' + _FRM.cap_inv1.value + '&cap_inv2=' + _FRM.cap_inv2.value  + '&trans=' + _FRM.trans.value + '&psic=' + _FRM.psic.value + '&usernm=' + _FRM.usernm.value);
//,'popup','height=500,width=800,menubar=no,scrollbars=yes,status=no,screenX=100,screenY=0,left=100,top=0');
	return true;
        }
function BusExempt()
        {
        var _FRM = document._FRM;
		var vDateSplit1 = _FRM.date_from.value.split("/");
		vDate1 = vDateSplit1[0] + vDateSplit1[1] + vDateSplit1[2];
		var vDateSplit2 = _FRM.date_to.value.split("/");
		vDate2 = vDateSplit2[0] + vDateSplit2[1] + vDateSplit2[2];
		var getDiff = (vDate2-vDate1);
		var today = new Date();
		 today.setFullYear(<? echo $yeartoday;?>,<? echo $monthtoday - 1;?>,<? echo $daytoday;?>);
		var datefrom = new Date();
		var dateto = new Date();
		datefrom.setFullYear(vDateSplit1[0],vDateSplit1[1]-1,vDateSplit1[2]);
		dateto.setFullYear(vDateSplit2[0],vDateSplit2[1]-1,vDateSplit2[2]);
		
		if (datefrom > today) {
			alert("Date Range cannot exceed current date.");
			_FRM.date_from.select();
			return false;
		}
		if (dateto > today) {
			alert("Date Range cannot exceed current date.");
			_FRM.date_to.select();
			return false;
		}
		if (getDiff < 0) {
			alert("Invalid Date Range!!");
			_FRM.date_from.select();
			return false;
		}
        winpopup = window.open('reports/' + _FRM.report_file.value + '?date_from=' + _FRM.date_from.value + '&date_to=' + _FRM.date_to.value + '&owner_last=' + _FRM.owner_last.value + '&business_nature_code=' + _FRM.business_nature_code.value + '&business_category=' + _FRM.business_category.value + '&option=' + _FRM.option.value + '&usernm=' + _FRM.usernm.value);
//,'popup','height=500,width=800,menubar=no,scrollbars=yes,status=no,screenX=100,screenY=0,left=100,top=0');
        return true;
                                                                                                                                                            
        }




function TopEstab()
        {
        var _FRM = document._FRM;
		var vDateSplit1 = _FRM.date_from.value.split("/");
		vDate1 = vDateSplit1[0] + vDateSplit1[1] + vDateSplit1[2];
		var vDateSplit2 = _FRM.date_to.value.split("/");
		vDate2 = vDateSplit2[0] + vDateSplit2[1] + vDateSplit2[2];
		var getDiff = (vDate2-vDate1);
		var today = new Date();
		 today.setFullYear(<? echo $yeartoday;?>,<? echo $monthtoday - 1;?>,<? echo $daytoday;?>);
		var datefrom = new Date();
		var dateto = new Date();
		datefrom.setFullYear(vDateSplit1[0],vDateSplit1[1]-1,vDateSplit1[2]);
		dateto.setFullYear(vDateSplit2[0],vDateSplit2[1]-1,vDateSplit2[2]);
		
		if (datefrom > today) {
			alert("Date Range cannot exceed current date.");
			_FRM.date_from.select();
			return false;
		}
		if (dateto > today) {
			alert("Date Range cannot exceed current date.");
			_FRM.date_to.select();
			return false;
		}
		if (getDiff < 0) {
			alert("Invalid Date Range!!");
			_FRM.date_from.select();
			return false;
		}
		if(parseFloat(_FRM.range1.value) < 0  || isNaN(_FRM.range1.value) == true) {
			alert("Invalid Capital Investment Range!!");
			_FRM.range1.focus();
			_FRM.range1.select();
			return false;
		}
		if(parseFloat(_FRM.range1.value) > 999999999999.99) {
			alert("Capital Investment Exceeds Max!");
			_FRM.range1.focus();
			_FRM.range1.select();
			return false;
		}
		if (parseFloat(_FRM.range2.value) < 0 || isNaN(_FRM.range2.value) == true)
		{
			alert("Invalid Capital Investment Range!!");
			_FRM.range2.focus();
			_FRM.range2.select();
			return false;
		}
		if(parseFloat(_FRM.range2.value) > 999999999999.99) {
			alert("Capital Investment Exceeds Max!");
			_FRM.range2.focus();
			_FRM.range2.select();
			return false;
		}
		if (parseFloat(_FRM.range1.value) > parseFloat(_FRM.range2.value))
		{
			alert("Invalid Capital Investment Range!!");
			_FRM.range1.focus();
			_FRM.range1.select();
			return false;
		}
		if (parseFloat(_FRM.list_limit.value) <= 0 || isNaN(_FRM.list_limit.value) == true || _FRM.list_limit.value == "")
		{
			alert("Invalid Limit!");
			_FRM.list_limit.focus();
			_FRM.list_limit.select();
			return false;
		}
		if (parseFloat(_FRM.list_limit.value) > 100 )
		{
			alert("Invalid Limit!");
			_FRM.list_limit.focus();
			_FRM.list_limit.select();
			return false;
		}
        winpopup = window.open('reports/' + _FRM.report_file.value + '?date_from=' + _FRM.date_from.value + '&date_to=' + _FRM.date_to.value + '&brgy_name=' + _FRM.brgy_name.value + '&list_option=' + _FRM.list_option.value + '&range1=' + _FRM.range1.value + '&range2=' + _FRM.range2.value + '&list_limit=' + _FRM.list_limit.value+ '&usernm=' + _FRM.usernm.value);
//,'popup','height=500,width=800,menubar=no,scrollbars=yes,status=no,screenX=100,screenY=0,left=100,top=0');
        return true;
                                                                                                 
                                                                                                 
        }
function ListEstab()
        {
			
        var _FRM = document._FRM;
		var vDateSplit1 = _FRM.date_from.value.split("/");
		vDate1 = vDateSplit1[0] + vDateSplit1[1] + vDateSplit1[2];
		var vDateSplit2 = _FRM.date_to.value.split("/");
		vDate2 = vDateSplit2[0] + vDateSplit2[1] + vDateSplit2[2];
		var getDiff = (vDate2-vDate1);
		var today = new Date();
		 today.setFullYear(<? echo $yeartoday;?>,<? echo $monthtoday - 1;?>,<? echo $daytoday;?>);
		var datefrom = new Date();
		var dateto = new Date();
		datefrom.setFullYear(vDateSplit1[0],vDateSplit1[1]-1,vDateSplit1[2]);
		dateto.setFullYear(vDateSplit2[0],vDateSplit2[1]-1,vDateSplit2[2]);
		
		if (datefrom > today) {
			alert("Date Range cannot exceed current date.");
			_FRM.date_from.select();
			return false;
		}
		if (dateto > today) {
			alert("Date Range cannot exceed current date.");
			_FRM.date_to.select();
			return false;
		}
		if (getDiff < 0) {
			alert("Invalid Date Range!!");
			_FRM.date_from.select();
			return false;
		}
        winpopup = window.open('reports/' + _FRM.report_file.value + '?date_from=' + _FRM.date_from.value + '&date_to=' + _FRM.date_to.value + '&brgy_name=' + _FRM.brgy_name.value + '&owner_last=' + _FRM.owner_last.value + '&paid=' + _FRM.paid.value);
//,'popup','height=500,width=800,menubar=no,scrollbars=yes,status=no,screenX=100,screenY=0,left=100,top=0');
        return true;
                                                                                                 
                                                                                                 
        }
function ListEstabW()
        {
        var _FRM = document._FRM;
		var vDateSplit1 = _FRM.date_from.value.split("/");
		vDate1 = vDateSplit1[0] + vDateSplit1[1] + vDateSplit1[2];
		var vDateSplit2 = _FRM.date_to.value.split("/");
		vDate2 = vDateSplit2[0] + vDateSplit2[1] + vDateSplit2[2];
		var getDiff = (vDate2-vDate1);
		var today = new Date();
		 today.setFullYear(<? echo $yeartoday;?>,<? echo $monthtoday - 1;?>,<? echo $daytoday;?>);
		var datefrom = new Date();
		var dateto = new Date();
		datefrom.setFullYear(vDateSplit1[0],vDateSplit1[1]-1,vDateSplit1[2]);
		dateto.setFullYear(vDateSplit2[0],vDateSplit2[1]-1,vDateSplit2[2]);
		
		if (datefrom > today) {
			alert("Date Range cannot exceed current date.");
			_FRM.date_from.select();
			return false;
		}
		if (dateto > today) {
			alert("Date Range cannot exceed current date.");
			_FRM.date_to.select();
			return false;
		}
		if (getDiff < 0) {
			alert("Invalid Date Range!!");
			_FRM.date_from.select();
			return false;
		}
        winpopup = window.open('reports/' + _FRM.report_file.value + '?date_from=' + _FRM.date_from.value + '&date_to=' + _FRM.date_to.value + '&brgy_name=' + _FRM.brgy_name.value + '&owner_last=' + _FRM.owner_last.value + '&paid=' + _FRM.paid.value);
//,'popup','height=500,width=800,menubar=no,scrollbars=yes,status=no,screenX=100,screenY=0,left=100,top=0');
        return true;
                                                                                                 
                                                                                                 
        }
function BussPermit()
        {
        var _FRM = document._FRM;
		if (_FRM.owner_last.value == "" && _FRM.permit_num.value == "" && _FRM.bus_name.value == "" && _FRM.pin.value == "") {
			alert("Enter Owner Last Name or Permit Number or Business Name or PIN.");
			_FRM.permit_num.focus();
			return false;
		}
        winpopup = window.open('reports/ebpls_buss_permit.php?owner_last=' + _FRM.owner_last.value + '&permit_num=' + _FRM.permit_num.value + '&reportpermit=1&permit_type=Business' + '&bus_name=' + _FRM.bus_name.value + '&pin=' + _FRM.pin.value);
//,'popup','height=500,width=800,menubar=no,scrollbars=yes,status=no,screenX=100,screenY=0,left=100,top=0');
        return true;
                                                                                                 
                                                                                                 
        }
		function BussPro()
        {
        var _FRM = document._FRM;
		if (_FRM.owner_last.value == "" && _FRM.bus_name.value == "" && _FRM.pin.value == "") {
			alert("Enter Owner Last Name or Business Name or PIN.");
			_FRM.owner_last.focus();
			return false;
		}
        winpopup = window.open('reports/ebpls_business_profile.php?owner_last=' + _FRM.owner_last.value + '&reportpermit=1&permit_type=Business' + '&bus_name=' + _FRM.bus_name.value + '&pin=' + _FRM.pin.value);
//,'popup','height=500,width=800,menubar=no,scrollbars=yes,status=no,screenX=100,screenY=0,left=100,top=0');
        return true;
                                                                                                 
                                                                                                 
        }
function Blaklist()
        {
        var _FRM = document._FRM;
		var vDateSplit1 = _FRM.date_from.value.split("/");
		vDate1 = vDateSplit1[0] + vDateSplit1[1] + vDateSplit1[2];
		var vDateSplit2 = _FRM.date_to.value.split("/");
		vDate2 = vDateSplit2[0] + vDateSplit2[1] + vDateSplit2[2];
		var getDiff = (vDate2-vDate1);
		var today = new Date();
		 today.setFullYear(<? echo $yeartoday;?>,<? echo $monthtoday - 1;?>,<? echo $daytoday;?>);
		var datefrom = new Date();
		var dateto = new Date();
		datefrom.setFullYear(vDateSplit1[0],vDateSplit1[1]-1,vDateSplit1[2]);
		dateto.setFullYear(vDateSplit2[0],vDateSplit2[1]-1,vDateSplit2[2]);
		
		if (datefrom > today) {
			alert("Date Range cannot exceed current date.");
			_FRM.date_from.select();
			return false;
		}
		if (dateto > today) {
			alert("Date Range cannot exceed current date.");
			_FRM.date_to.select();
			return false;
		}
		if (getDiff < 0) {
			alert("Invalid Date Range!!");
			_FRM.date_from.select();
			return false;
		} else {
        winpopup = window.open('reports/' + _FRM.report_file.value + '?date_from=' + _FRM.date_from.value + '&date_to=' + _FRM.date_to.value + '&barangay=' + _FRM.brgy_name.value + '&usernm=' + _FRM.usernm.value);
        return true;
		}
		}
function ReqDelin()
        {
        var _FRM = document._FRM;
		var vDateSplit1 = _FRM.date_from.value.split("/");
		vDate1 = vDateSplit1[0] + vDateSplit1[1] + vDateSplit1[2];
		var vDateSplit2 = _FRM.date_to.value.split("/");
		vDate2 = vDateSplit2[0] + vDateSplit2[1] + vDateSplit2[2];
		var getDiff = (vDate2-vDate1);
		var today = new Date();
		 today.setFullYear(<? echo $yeartoday;?>,<? echo $monthtoday - 1;?>,<? echo $daytoday;?>);
		var datefrom = new Date();
		var dateto = new Date();
		datefrom.setFullYear(vDateSplit1[0],vDateSplit1[1]-1,vDateSplit1[2]);
		dateto.setFullYear(vDateSplit2[0],vDateSplit2[1]-1,vDateSplit2[2]);
		
		if (datefrom > today) {
			alert("Date Range cannot exceed current date.");
			_FRM.date_from.select();
			return false;
		}
		if (dateto > today) {
			alert("Date Range cannot exceed current date.");
			_FRM.date_to.select();
			return false;
		}
		if (getDiff < 0) {
			alert("Invalid Date Range!!");
			_FRM.date_from.select();
			return false;
		} else {
        winpopup = window.open('reports/' + _FRM.report_file.value + '?date_from=' + _FRM.date_from.value + '&date_to=' + _FRM.date_to.value + '&barangay=' + _FRM.brgy_name.value + '&usernm=' + _FRM.usernm.value);
        return true;
		}
}
		function BussComp()
        {
        var _FRM = document._FRM;
		var vDateSplit1 = _FRM.date_from.value.split("/");
		var today = new Date();
		 today.setFullYear(<? echo $yeartoday;?>,<? echo $monthtoday - 1;?>,<? echo $daytoday;?>);
		var datefrom = new Date();
		var dateto = new Date();
		datefrom.setFullYear(vDateSplit1[0],vDateSplit1[1]-1,vDateSplit1[2]);
		
		if (datefrom > today) {
			alert("Date cannot exceed current date.");
			_FRM.date_from.select();
			return false;
		}
		winpopup = window.open('reports/' + _FRM.report_file.value + '?date_from=' + _FRM.date_from.value + '&usernm=' + _FRM.usernm.value);
        return true;


        }                                                                                                               
</script>

<?php
			if ($report_file=='ebpls_buslist_blacklist.php') {
				?>
                        <tr>
                        <td width=40% align=right>
                        Date: </td>
                        <td align="left" valign="top" class='normal'>&nbsp;
                  
        
<input type="text" value="<?php echo date('Y/m/d'); ?>" readonly name="date_from" onclick="displayCalendar(date_from,'yyyy/mm/dd',this);">
<img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date" onclick ="displayCalendar(_FRM.date_from,'yyyy/mm/dd',this);_FRM.changeondin.value=1;">
 
<input type="text" value="<?php echo date('Y/m/d'); ?>" readonly name="date_to" onclick="displayCalendar(date_to,'yyyy/mm/dd',this);">
<img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date" onclick ="displayCalendar(_FRM.date_to,'yyyy/mm/dd',this);_FRM.changeondin.value=1;">

                        </td>
                        </tr>
			<tr>
                        <td width=40% align=right>
                        Barangay&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <?php echo get_select_brgy($dbLink,'brgy_name','ebpls_barangay','barangay_code','barangay_desc',$owner[6]);?>
                        </td>
                        </tr>
                        <tr>
                        <td>&nbsp;</td>
                        </tr>
                        <tr>
                        <td colspan=2 align=center>
                        <input type=button name='buss_report' value="View Report" onClick="javascript: Blaklist();">
                        </td>
                        </tr>
<?php

                        }
						if ($report_file=='ebpls_summary_busreq_delinquent.php') {
				?>
                        <tr>
                        <td width=40% align=right>
                        Date: </td>
                        <td align="left" valign="top" class='normal'>&nbsp;
                  
        
<input type="text" value="<?php echo date('Y/m/d'); ?>" readonly name="date_from" onclick="displayCalendar(date_from,'yyyy/mm/dd',this);">
<img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date" onclick ="displayCalendar(_FRM.date_from,'yyyy/mm/dd',this);_FRM.changeondin.value=1;">
 
<input type="text" value="<?php echo date('Y/m/d'); ?>" readonly name="date_to" onclick="displayCalendar(date_to,'yyyy/mm/dd',this);">
<img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date" onclick ="displayCalendar(_FRM.date_to,'yyyy/mm/dd',this);_FRM.changeondin.value=1;">

                        </td>
                        </tr>
			<tr>
                        <td width=40% align=right>
                        Barangay&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <?php echo get_select_brgy($dbLink,'brgy_name','ebpls_barangay','barangay_code','barangay_desc',$owner[6]);?>
                        </td>
                        </tr>
                        <tr>
                        <td>&nbsp;</td>
                        </tr>
                        <tr>
                        <td colspan=2 align=center>
                        <input type=button name='buss_report' value="View Report" onClick="javascript: ReqDelin();">
                        </td>
                        </tr>
<?php

                        }
						if ($report_file=='ebpls_comparative_business.php') {
				?>
                        <tr>
                        <td width=40% align=right>
                        Date: </td>
                        <td align="left" valign="top" class='normal'>&nbsp;
                  
        
<input type="text" value="<?php echo date('Y/m/d'); ?>" readonly name="date_from" onclick="displayCalendar(date_from,'yyyy/mm/dd',this);">
<img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date" onclick ="displayCalendar(_FRM.date_from,'yyyy/mm/dd',this);_FRM.changeondin.value=1;">
                        </td>
                        </tr>
			            <td>&nbsp;</td>
                        </tr>
                        <tr>
                        <td colspan=2 align=center>
                        <input type=button name='buss_report' value="View Report" onClick="javascript: BussComp();">
                        </td>
                        </tr>
<?php

                        }
			if ($report_file=='ebpls_buss_permit.php') {
				?>
                        <tr>
                        <td width=40% align=right>
                        Permit #&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <input type=text name=permit_num>
                        </td>
                        </tr>
						<tr>
                        <td width=40% align=right>
                        Owner Last Name&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <input type=text name=owner_last>
                        </td>
                        </tr>
						<tr>
                        <td width=40% align=right>
                        Business Name&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <input type=text name=bus_name>
                        </td>
                        </tr>
						<tr>
                        <td width=40% align=right>
                        PIN&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <input type=text name=pin>
                        </td>
                        </tr>
                        <tr>
                        <td>&nbsp;</td>
                        </tr>
                        <tr>
                        <td colspan=2 align=center>
                        <input type=button name='buss_report' value="View Report" onClick="javascript: BussPermit();">
                        </td>
                        </tr>
<?php

                        }
						if ($report_file=='ebpls_business_profile.php') {
				?>
                        <tr>
                        <td width=40% align=right>
                        Owner Last Name&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <input type=text name=owner_last>
                        </td>
                        </tr>
						<tr>
                        <td width=40% align=right>
                        Business Name&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <input type=text name=bus_name>
                        </td>
                        </tr>
						<tr>
                        <td width=40% align=right>
                        PIN&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <input type=text name=pin>
                        </td>
                        </tr>
                        <tr>
                        <td>&nbsp;</td>
                        </tr>
                        <tr>
                        <td colspan=2 align=center>
                        <input type=button name='buss_report' value="View Report" onClick="javascript: BussPro();">
                        </td>
                        </tr>
<?php

                        }
			if ($report_file=="ebpls_bus_masterlist.php" || $report_file=='ebpls_bus_exemptedlist_full.php') {
?>
			<tr>
			<td width=40% align=right>
                        Date: </td>
			<td align="left" valign="top" class='normal'>&nbsp;
			        
<input type="text" value="<?php echo date('Y/m/d'); ?>" readonly name="date_from" onclick="displayCalendar(date_from,'yyyy/mm/dd',this);">
<img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date" onclick ="displayCalendar(_FRM.date_from,'yyyy/mm/dd',this);_FRM.changeondin.value=1;">
 
<input type="text" value="<?php echo date('Y/m/d'); ?>" readonly name="date_to" onclick="displayCalendar(date_to,'yyyy/mm/dd',this);">
<img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date" onclick ="displayCalendar(_FRM.date_to,'yyyy/mm/dd',this);_FRM.changeondin.value=1;">

			</td>
			</tr>
				<?php
                        if ($report_file=="ebpls_bus_masterlist.php") {
				?>
			<tr>
                        <td width=40% align=right>
                        Barangay&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <?php echo get_select_brgy($dbLink,'brgy_name','ebpls_barangay','barangay_code','barangay_desc',$owner[6]);?>
                        </td>
                        </tr>
<?php
			}
?>
			<tr>
                        <td width=40% align=right>
                        Owner Last Name&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <input type=text name='owner_last' value='' >
                        </td>
                        </tr>
			<tr>
                        <td width=40% align=right>
                        Line of Business&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
<?php
                echo get_select_brgy($dbLink,'business_nature_code','ebpls_buss_nature','natureid','naturedesc',$business_nature_code, 'true','naturestatus<>"d" order by naturedesc');
                ?>
                        </td>
                        </tr>
<?php
	                        if ($report_file=="ebpls_bus_masterlist.php") {
?>

                        <tr>
                        <td width=40% align=right>
                        <?
                        if ($trans <> 'New' and $trans <> "") {
	                    ?>
	                    Gross Receipt Range
	                    <?
                    	} else {
                    	?>
                        Capital Investment Range
                        <? 
                    	}
                    	if ($trans == 'New') {
	                    	$checkit1 = "selected";
                    	} elseif ($trans == 'ReNew') {
	                    	$checkit2 = "selected";
                    	} elseif ($trans == 'ReNew') {
	                    	$checkit3 = "selected";
                    	} else {
	                    	$checkit = "selected";
                    	}
                    	?>
                        &nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <input type=text name='cap_inv1' value='0' >
			&nbsp; to &nbsp;
                        <input type=text name='cap_inv2' value='0' >
                        </td>
                        </tr>
                        <tr>
                        <td width=40% align=right>
                        Transaction&nbsp;</td>
                        <td>&nbsp;
                        <select name='trans' height="19" style="font-size:10" onchange="javascript: _FRM.submit();">
                        <option value='' <? echo $checkit;?>>-----</option>
                        <option value='New' <? echo $checkit1;?>>New</option>
                        <option value='ReNew' <? echo $checkit2;?>>ReNew</option>
                        <option value='Retire' <? echo $checkit3;?>>Retire</option>
                        </select>
                        </td>
                        </tr>
			<tr>
                        <td width=40% align=right>
                        PSIC&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <input type=text name='psic' value='' >
                        </td>
                        </tr>

	                <tr>
                        <td>&nbsp;</td>
                        </tr>
                        <tr>
                        <td colspan=2 align=center>
                        <input type=button name='buss_report' value="View Report" onClick="javascript:BusEstab();">
                        </td>
                        </tr>
<?php
				} else {
?>
			<tr>
                        <td width=40% align=right>
                        Ownership Type&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
<?php
				if ($report_file=='ebpls_bus_exemptedlist_full.php') {
					echo get_select_brgy($dbLink,'business_category','ebpls_business_category','business_category_code','business_category_desc',$business_category, 'true','business_category_code<>"d" and tax_exemption > 0 order by business_category_desc');
				} else {
					echo get_select_brgy($dbLink,'business_category','ebpls_business_category','business_category_code','business_category_desc',$business_category, 'true','business_category_code<>"d" order by business_category_desc');
				}
                ?>
                        </td>
                        </tr>
                        <tr>
                        <td width=40% align=right>
                        Options&nbsp;</td>
                        <td>&nbsp;
                        <select name='option' height="19" style="font-size:10">
                        <option value='1000'>-----</option>
                        <option value='99'>Partial</option>
                        <option value='100'>Full</option>
                        </select>
                        </td>
                        </tr>
			<tr>
                        <td>&nbsp;</td>
                        </tr>
                        <tr>
                        <td colspan=2 align=center>
                        <input type=button name='buss_report' value="View Report" onClick="javascript:BusExempt();">
                        </td>
                        </tr>


<?php
				}
			} elseif ($report_file=="ebpls_bus_topestablishment.php") {
?>
                        <tr>
                        <td width=40% align=right>
                        Date: </td>
                        <td align="left" valign="top" class='normal'>&nbsp;
                               
<input type="text" value="<?php echo date('Y/m/d'); ?>" readonly name="date_from" onclick="displayCalendar(date_from,'yyyy/mm/dd',this);">
<img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date" onclick ="displayCalendar(_FRM.date_from,'yyyy/mm/dd',this);_FRM.changeondin.value=1;">
 
<input type="text" value="<?php echo date('Y/m/d'); ?>" readonly name="date_to" onclick="displayCalendar(date_to,'yyyy/mm/dd',this);">
<img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date" onclick ="displayCalendar(_FRM.date_to,'yyyy/mm/dd',this);_FRM.changeondin.value=1;">

                        </td>
                        </tr>
                        <tr>
                        <td width=40% align=right>
                        Barangay&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <?php echo get_select_brgy($dbLink,'brgy_name','ebpls_barangay','barangay_code','barangay_desc',$owner[6]);?>
                        </td>
                        </tr>
                        <tr>
                        <td width=40% align=right>
                        Option&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
			<select name="list_option">
			<option value='Capital Investment'>Capital Investment</option>
			<option value='Gross Receipts'>Gross Receipts</option>
                        </td>
                        </tr>
                        <tr>
                        <td width=40% align=right>
                        Income Range&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <input type=text name='range1' value='0' >
			&nbsp; to &nbsp;
                        <input type=text name='range2' value='0' >
                        </td>
                        </tr>
                        <tr>
                        <td width=40% align=right>
                        List Limit&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <input type=text name='list_limit' value='5' >
                        </td>
                        </tr>
                                                                                                                                                                                                                                                                                                                                                                                                       
                        <tr>
                        <td>&nbsp;</td>
                        </tr>
  <td colspan=2 align=center>
                        <input type=button name='buss_report' value="View Report" onClick="javascript:TopEstab();">
                        </td>
                        </tr>
<?php
                        } elseif ($report_file=="ebpls_bus_wpermit.php") {
?>
                        <tr>
                        <td width=40% align=right>
                        Date: </td>
                        <td align="left" valign="top" class='normal'>&nbsp;
                              
<input type="text" value="<?php echo date('Y/m/d'); ?>" readonly name="date_from" onclick="displayCalendar(date_from,'yyyy/mm/dd',this);">
<img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date" onclick ="displayCalendar(_FRM.date_from,'yyyy/mm/dd',this);_FRM.changeondin.value=1;">
 
<input type="text" value="<?php echo date('Y/m/d'); ?>" readonly name="date_to" onclick="displayCalendar(date_to,'yyyy/mm/dd',this);">
<img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date" onclick ="displayCalendar(_FRM.date_to,'yyyy/mm/dd',this);_FRM.changeondin.value=1;">

                        </td>
                        </tr>
                        <tr>
                        <td width=40% align=right>
                        Barangay&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <?php echo get_select_brgy($dbLink,'brgy_name','ebpls_barangay','barangay_code','barangay_desc',$owner[6]);?>
                        </td>
                        </tr>
                        <tr>
                        <td width=40% align=right>
                        Owner Last Name&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <input type=text name='owner_last' value='' >
                        </td>
                        </tr>
			<tr>
                        <td width=40% align=right>
                        Options&nbsp;</td>
                        <td>&nbsp;
                        <select name='paid' height="19" style="font-size:10">
                        <option value=''>-----</option>
                        <option value='1'>Paid</option>
                        <option value='0'>UnPaid</option>
                        </select>
                        </td>
                        </tr>
                        <tr>
                        <td>&nbsp;</td>
                        </tr>
  <td colspan=2 align=center>
                        <input type=button name='buss_report' value="View Report" onClick="javascript:ListEstab();">
                        </td>
                        </tr>
<?php
                        } elseif ($report_file=="ebpls_bus_woutpermit.php") {
?>
                        <tr>
                        <td width=40% align=right>
                        Date: </td>
                        <td align="left" valign="top" class='normal'>&nbsp;
                                
<input type="text" value="<?php echo date('Y/m/d'); ?>" readonly name="date_from" onclick="displayCalendar(date_from,'yyyy/mm/dd',this);">
<img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date" onclick ="displayCalendar(_FRM.date_from,'yyyy/mm/dd',this);_FRM.changeondin.value=1;">
 
<input type="text" value="<?php echo date('Y/m/d'); ?>" readonly name="date_to" onclick="displayCalendar(date_to,'yyyy/mm/dd',this);">
<img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date" onclick ="displayCalendar(_FRM.date_to,'yyyy/mm/dd',this);_FRM.changeondin.value=1;">

                        </td>
                        </tr>
                        <tr>
                        <td width=40% align=right>
                        Barangay&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <?php echo get_select_brgy($dbLink,'brgy_name','ebpls_barangay','barangay_code','barangay_desc',$owner[6]);?>
                        </td>
                        </tr>
                        <tr>
                        <td width=40% align=right>
                        Owner Last Name&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <input type=text name='owner_last' value='' >
                        </td>
                        </tr>
			<tr>
                        <td width=40% align=right>
                        Options&nbsp;</td>
                        <td>&nbsp;
                        <select name='paid' height="19" style="font-size:10">
                        <option value=''>-----</option>
                        <option value='1'>Paid</option>
                        <option value='0'>UnPaid</option>
                        </select>
                        </td>
                        </tr>
                        <tr>
                        <td>&nbsp;</td>
                        </tr>
  <td colspan=2 align=center>
                        <input type=button name='buss_report' value="View Report" onClick="javascript:ListEstabW();">
                        </td>
                        </tr>
<?php
                        }

?>

					</table>	
				</td>
			</tr>
			<?php
			} elseif ($rpt=='Motorized') {
			?>
			<tr>
				<td align="center" valign="top" width=100% colspan=2>
					<table border=0 cellspacing=0 cellpadding=0  width='100%'>
						<tr>
							<td class='header2' colspan=2 align=center>Motorized Permit</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<?php
								//where report_desc like '%business%' or report_desc like 'business%' 
								//$result = mysql_query("select report_desc, report_file from ebpls_reports order by report_desc asc") or die(mysql_error()); 
								$result= mysql_query("select report_desc, report_file from ebpls_reports 
								where report_type like '%motor%' or report_type like 'motor%'  order by report_desc asc") or die(mysql_error()); 
    							//$reports=mysql_fetch_row($result);
  								print "<td width=40% align=right>
    							Reports&nbsp</td>";
    							print "<td width=60% align=left>&nbsp
    								<select name='buss_select' onchange='javascript: _FRM.motor_select.value=_FRM.buss_select.value; _FRM.submit();'>>";
									print"<option value=''>------</option>";
    								while ($reports=mysql_fetch_row($result)) {
										if ($motor_select == $reports[1]) {
		    								$isselect = 'selected';
	    								} else {
		    								$isselect = '';
	    								}
    									$getlevel = mysql_query("select * from ebpls_user_sublevel where
										submenu = '$reports[0]'");
					$getme = mysql_fetch_assoc($getlevel);
					
					if ($$getme[rptvars]==1) {
    									print"<option value=$reports[1] $isselect>$reports[0]</option>";
					}
									}
    								print"</select></td><input type=hidden name=motor_select>";
    							//echo get_select_data($dbLink,'report_list','ebpls_reports','report_file','report_desc',$owner_datarow[report_id]);
    						?>
    					</tr>
						<tr>
							<td>&nbsp;</td>
						</tr>
						<?
						if ($motor_select=='ebpls_masterlist_motor.php') {
?>
                        <tr>
                        <td width=40% align=right>
                        Date: </td>
                        <td align="left" valign="top" class='normal'>&nbsp;
                              
<input type="text" value="<?php echo date('Y/m/d'); ?>" readonly name="date_from" onclick="displayCalendar(date_from,'yyyy/mm/dd',this);">
<img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date" onclick ="displayCalendar(_FRM.date_from,'yyyy/mm/dd',this);_FRM.changeondin.value=1;">
 
<input type="text" value="<?php echo date('Y/m/d'); ?>" readonly name="date_to" onclick="displayCalendar(date_to,'yyyy/mm/dd',this);">
<img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date" onclick ="displayCalendar(_FRM.date_to,'yyyy/mm/dd',this);_FRM.changeondin.value=1;">

                        </td>
                        </tr>
						<tr>
                        <td width=40% align=right>
                        Barangay&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <?php echo get_select_brgy($dbLink,'brgy_name','ebpls_barangay','barangay_code','barangay_desc',$owner[6]);?>
                        </td>
                        </tr>
                        <tr>
                        <td width=40% align=right>
                        Operator's Last Name&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <input name=owner_last>
                        </td>
                        </tr>
                        <tr>
                        <td width=40% align=right>
                        Motor Brand&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <input name=motor_brand>
                        </td>
                        </tr>
                        <tr>
                        <td width=40% align=right>
                        Body Color&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <input name=body_color>
                        </td>
                        </tr>
                        <tr>
                        <td width=40% align=right>
                        Engine Type&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <input name=engine_type>
                        </td>
                        </tr>
                        <tr>
                        <td>&nbsp;</td>
                        </tr>
                        <tr>
                        <td colspan=2 align=center>
                        <input type=button name='buss_report' value="View Report" onClick="javascript: Motorlist();">
                        <input type=hidden name=usernm value="<?php echo $ThUserData['username']; ?>">
                        </td>
                        </tr>
<?php

                        }
						if ($motor_select=='ebpls_motor_permit.php') {
?>
                        <tr>
                        <td width=40% align=right>
                        Owner Last Name&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <input name=owner_last>
                        </td>
                        </tr>
                        <tr>
                        <td width=40% align=right>
                        Permit #&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <input name=permit_num>
                        </td>
                        </tr>
                        <tr>
                        <td>&nbsp;</td>
                        </tr>
                        <tr>
                        <td colspan=2 align=center>
                        <input type=button name='buss_report' value="View Report" onClick="javascript: MotorPermit();">
                        <input type=hidden name=usernm value="<?php echo $ThUserData['username']; ?>">
                        </td>
                        </tr>
<?php

                        }
?>
						<!--
						<tr>
							<td colspan=2 align=center>
								<input type=submit name='motor_report' value="View Report" class=subnavwhite> 
							</td>
						</tr>	-->
					</table>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<?php
			} elseif ($rpt=='Occupational') {
			?>
			<tr>
				<td align="center" valign="top" width=100% colspan=2>
					<table border=0 cellspacing=0 cellpadding=0  width='100%'>
						<tr>
							<td class='header2' colspan=2 align=center>Occupational Permit</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<?php
								//where report_desc like '%business%' or report_desc like 'business%' 
								//$result = mysql_query("select report_desc, report_file from ebpls_reports order by report_desc asc") or die(mysql_error()); 
								$result= mysql_query("select report_desc, report_file from ebpls_reports 
								where report_type like '%occ%' order by report_desc asc") or die(mysql_error()); 
    							//$reports=mysql_fetch_row($result);
  								print "<td width=40% align=right>
    							Reports&nbsp</td>";
    							print "<td width=60% align=left>&nbsp
    								<select name='buss_select' onchange='javascript: _FRM.occu_select.value=_FRM.buss_select.value; _FRM.submit();'>";
    								print"<option value=''>--------</option>";
    								while ($reports=mysql_fetch_row($result)) {
	    								if ($occu_select == $reports[1]) {
		    								$iselecty = 'selected';
	    								} else {
		    								$iselecty = '';
	    								}
    									$getlevel = mysql_query("select * from ebpls_user_sublevel where
										submenu = '$reports[0]'");
					$getme = mysql_fetch_assoc($getlevel);
					
					if ($$getme[rptvars]==1) {
    									print"<option value=$reports[1] $iselecty>$reports[0]</option>";
					}
									}
    								print"</select><input type=hidden name='occu_select'></td>";
    							//echo get_select_data($dbLink,'report_list','ebpls_reports','report_file','report_desc',$owner_datarow[report_id]);
    						?>
    					</tr>
						<tr>
							<td>&nbsp;</td>
						</tr>
						<?
						if ($occu_select=='ebpls_occupational_list.php') {
?>
                        <tr>
                        <td width=40% align=right>
                        Date: </td>
                        <td align="left" valign="top" class='normal'>&nbsp;
                                
<input type="text" value="<?php echo date('Y/m/d'); ?>" readonly name="date_from" onclick="displayCalendar(date_from,'yyyy/mm/dd',this);">
<img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date" onclick ="displayCalendar(_FRM.date_from,'yyyy/mm/dd',this);_FRM.changeondin.value=1;">
 
<input type="text" value="<?php echo date('Y/m/d'); ?>" readonly name="date_to" onclick="displayCalendar(date_to,'yyyy/mm/dd',this);">
<img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date" onclick ="displayCalendar(_FRM.date_to,'yyyy/mm/dd',this);_FRM.changeondin.value=1;">

                        </td>
                        </tr>
						<tr>
                        <td width=40% align=right>
                        Barangay&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <?php echo get_select_brgy($dbLink,'brgy_name','ebpls_barangay','barangay_code','barangay_desc',$owner[6]);?>
                        </td>
                        </tr>
                        <tr>
                        <td width=40% align=right>
                        Owner's Last Name&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <input name=owner_last>
                        </td>
                        </tr>
                        <tr>
                        <td width=40% align=right>
                        Occupation&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <input name=occupation>
                        </td>
                        </tr>
                        <tr>
                        <td width=40% align=right>
                        Gender&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <input name=sex>
                        </td>
                        </tr>
                        <tr>
                        <td width=40% align=right>
                        Employer&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <input name=employer>
                        </td>
                        </tr>
						<!--<tr>
                        <td width=40% align=right>
                        Age&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        Start : <input name=agefrom>&nbsp;&nbsp;End : <input name=ageto>
                        </td>
                        </tr>-->
                        <tr>
                        <td>&nbsp;</td>
                        </tr>
                        <tr>
                        <td colspan=2 align=center>
                        <input type=button name='buss_report' value="View Report" onClick="javascript: Occulist();">
                        <input type=hidden name=usernm value="<?php echo $ThUserData['username']; ?>">
                        </td>
                        </tr>
<?php

                        }if ($occu_select=='ebpls_occ_permit.php') {
?>
                        <tr>
                        <td width=40% align=right>
                        Owner Last Name&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <input name=owner_last>
                        </td>
                        </tr>
                        <tr>
                        <td width=40% align=right>
                        Permit #&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <input name=permit_num>
                        </td>
                        </tr>
                        <tr>
                        <td>&nbsp;</td>
                        </tr>
                        <tr>
                        <td colspan=2 align=center>
                        <input type=button name='buss_report' value="View Report" onClick="javascript: OccuPermit();">
                        <input type=hidden name=usernm value="<?php echo $ThUserData['username']; ?>">
                        </td>
                        </tr>
<?php

                        }
?>
					</table>	
				</td>
			</tr>
			<?php
			} elseif ($rpt=='Franchise') {
			?>
			<tr>
				<td align="center" valign="top" width=100% cospan=2>
					<table border=0 cellspacing=0 cellpadding=0  width='100%'>
						<tr>
							<td class='header2' colspan=2 align=center>Franchise Permit</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
						</tr>
						<tr>
						<td>
						
						<tr>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<?php
								//where report_desc like '%business%' or report_desc like 'business%' 
								//$result = mysql_query("select report_desc, report_file from ebpls_reports order by report_desc asc") or die(mysql_error()); 
								$result= mysql_query("select report_desc, report_file from ebpls_reports 
								where report_type like '%franchise%' order by report_desc asc") or die(mysql_error()); 
    							//$reports=mysql_fetch_row($result);
  								print "<td width=40% align=right>
    							Reports&nbsp</td>";
    							print "<td width=60% align=left>&nbsp
    								<select name='buss_select' onchange='javascript: _FRM.fran_select.value=_FRM.buss_select.value; _FRM.submit();'>";
    								print"<option value=''>------</option>";
									while ($reports=mysql_fetch_row($result)) {
	    								if ($fran_select == $reports[1]) {
		    								$iselecty = 'selected';
	    								} else {
		    								$iselecty = '';
	    								}
	    								$getlevel = mysql_query("select * from ebpls_user_sublevel where
										submenu = '$reports[0]'");
					$getme = mysql_fetch_assoc($getlevel);
					
					if ($$getme[rptvars]==1) {
    									print"<option value=$reports[1] $iselecty>$reports[0]</option>";
					}
									}
    								print"</select>";
    								print"<input type=hidden name='fran_select'></td>";
    							//echo get_select_data($dbLink,'report_list','ebpls_reports','report_file','report_desc',$owner_datarow[report_id]);
    						?>
    					</tr>
    					
						<tr>
							<td>&nbsp;</td>
						</tr>
						<?
						if ($fran_select=='ebpls_franchise_list.php') {
?>
                        <tr>
                        <td width=40% align=right>
                        Date: </td>
                        <td align="left" valign="top" class='normal'>&nbsp;
                              
<input type="text" value="<?php echo date('Y/m/d'); ?>" readonly name="date_from" onclick="displayCalendar(date_from,'yyyy/mm/dd',this);">
<img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date" onclick ="displayCalendar(_FRM.date_from,'yyyy/mm/dd',this);_FRM.changeondin.value=1;">
 
<input type="text" value="<?php echo date('Y/m/d'); ?>" readonly name="date_to" onclick="displayCalendar(date_to,'yyyy/mm/dd',this);">
<img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date" onclick ="displayCalendar(_FRM.date_to,'yyyy/mm/dd',this);_FRM.changeondin.value=1;">

                        </td>
                        </tr>
						<tr>
                        <td width=40% align=right>
                        Barangay&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <?php echo get_select_brgy($dbLink,'brgy_name','ebpls_barangay','barangay_code','barangay_desc',$owner[6]);?>
                        </td>
                        </tr>
                        <tr>
                        <td width=40% align=right>
                        Operator's Last Name&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <input name=owner_last>
                        </td>
                        </tr>
                        <tr>
                        <td width=40% align=right>
                        Motor Brand&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <input name=motor_brand>
                        </td>
                        </tr>
                        <tr>
                        <td width=40% align=right>
                        Body Color&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <input name=body_color>
                        </td>
                        </tr>
                        <tr>
                        <td width=40% align=right>
                        Engine Type&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <input name=engine_type>
                        </td>
                        </tr>
                        <tr>
                        <td>&nbsp;</td>
                        </tr>
                        <tr>
                        <td colspan=2 align=center>
                        <input type=button name='buss_report' value="View Report" onClick="javascript: Franlist();">
                        <input type=hidden name=usernm value="<?php echo $ThUserData['username']; ?>">
                        </td>
                        </tr>
<?php

                        } if ($fran_select=='ebpls_motor_permit.php') {
?>
                        <tr>
                        <td width=40% align=right>
                        Owner Last Name&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <input name=owner_last>
                        </td>
                        </tr>
                        <tr>
                        <td width=40% align=right>
                        Permit #&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <input name=permit_num>
                        </td>
                        </tr>
                        <tr>
                        <td>&nbsp;</td>
                        </tr>
                        <tr>
                        <td colspan=2 align=center>
                        <input type=button name='buss_report' value="View Report" onClick="javascript: FranchisePermit();">
                        <input type=hidden name=usernm value="<?php echo $ThUserData['username']; ?>">
                        </td>
                        </tr>
<?php

                        }
?>
					</table>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<?php
			} elseif ($rpt=='Fishery') {
			?>
			<tr>
				<td align="center" valign="top" width=100% colspan=2>
					<table border=0 cellspacing=0 cellpadding=0  width='100%'>
						<tr>
							<td class='header2' colspan=2 align=center>Fishery Permit</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<?php
								//where report_desc like '%business%' or report_desc like 'business%' 
								//$result = mysql_query("select report_desc, report_file from ebpls_reports order by report_desc asc") or die(mysql_error()); 
								$result= mysql_query("select report_desc, report_file from ebpls_reports 
								where report_type like '%fishery%'  order by report_desc asc") or die(mysql_error()); 
    							//$reports=mysql_fetch_row($result);
  								print "<td width=40% align=right>
    							Reports&nbsp</td>";
    							print "<td width=60% align=left>&nbsp
    								<select name='buss_select' onchange='javascript: _FRM.fish_select.value=_FRM.buss_select.value; _FRM.submit();'>>";
									print"<option value=''>------</option>";
    								while ($reports=mysql_fetch_row($result)) {
										if ($fish_select == $reports[1]) {
		    								$iselecty = 'selected';
	    								} else {
		    								$iselecty = '';
	    								}
    									$getlevel = mysql_query("select * from ebpls_user_sublevel where
										submenu = '$reports[0]'");
					$getme = mysql_fetch_assoc($getlevel);
					
					if ($$getme[rptvars]==1) {
    									print"<option value=$reports[1] $iselecty>$reports[0]</option>";
					}
									}
    								print"</select></td><input type=hidden name=fish_select>";
    							//echo get_select_data($dbLink,'report_list','ebpls_reports','report_file','report_desc',$owner_datarow[report_id]);
    						?>
    					</tr>
						<tr>
						<tr>
							<td>&nbsp;</td>
						</tr>
						<?
						if ($fish_select=='ebpls_fishery_list.php') {
?>
                        <tr>
                        <td width=40% align=right>
                        Date: </td>
                        <td align="left" valign="top" class='normal'>&nbsp;
                                
<input type="text" value="<?php echo date('Y/m/d'); ?>" readonly name="date_from" onclick="displayCalendar(date_from,'yyyy/mm/dd',this);">
<img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date" onclick ="displayCalendar(_FRM.date_from,'yyyy/mm/dd',this);_FRM.changeondin.value=1;">
 
<input type="text" value="<?php echo date('Y/m/d'); ?>" readonly name="date_to" onclick="displayCalendar(date_to,'yyyy/mm/dd',this);">
<img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date" onclick ="displayCalendar(_FRM.date_to,'yyyy/mm/dd',this);_FRM.changeondin.value=1;">

                        </td>
                        </tr>
						<tr>
                        <td width=40% align=right>
                        Barangay&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <?php echo get_select_brgy($dbLink,'brgy_name','ebpls_barangay','barangay_code','barangay_desc',$owner[6]);?>
                        </td>
                        </tr>
                        <tr>
                        <td>&nbsp;</td>
                        </tr>
                        <tr>
                        <td colspan=2 align=center>
                        <input type=button name='buss_report' value="View Report" onClick="javascript: Fishlist();">
                        <input type=hidden name=usernm value="<?php echo $ThUserData['username']; ?>">
                        </td>
                        </tr>
<?php

                        }if ($fish_select=='ebpls_fish_permit.php') {
?>
                        <tr>
                        <td width=40% align=right>
                        Owner Last Name&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <input name=owner_last>
                        </td>
                        </tr>
                        <tr>
                        <td width=40% align=right>
                        Permit #&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <input name=permit_num>
                        </td>
                        </tr>
                        <tr>
                        <td>&nbsp;</td>
                        </tr>
                        <tr>
                        <td colspan=2 align=center>
                        <input type=button name='buss_report' value="View Report" onClick="javascript: FishPermit();">
                        <input type=hidden name=usernm value="<?php echo $ThUserData['username']; ?>">
                        </td>
                        </tr>
<?php

                        }
?>
					</table>	
				</td>
			</tr>
			<?php
			} elseif ($rpt=='Peddlers') {
			?>
			<tr>
				<td align="center" valign="top" width=100% colspan=2>
					<table border=0 cellspacing=0 cellpadding=0  width='100%'>
						<tr>
							<td class='header2' colspan=2 align=center>Peddlers Permit</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<?php
								//where report_desc like '%business%' or report_desc like 'business%' 
								//$result = mysql_query("select report_desc, report_file from ebpls_reports order by report_desc asc") or die(mysql_error()); 
								$result= mysql_query("select report_desc, report_file from ebpls_reports 
								where report_type like '%peddler%' order by report_desc asc") or die(mysql_error()); 
    							//$reports=mysql_fetch_row($result);
  								print "<td width=40% align=right>
    							Reports&nbsp</td>";
    							print "<td width=60% align=left>&nbsp
    								<select name='buss_select' onchange='javascript: _FRM.pedd_select.value=_FRM.buss_select.value; _FRM.submit();'>";
    								print"<option value=''>--------</option>";
    								while ($reports=mysql_fetch_row($result)) {
	    								if ($pedd_select == $reports[1]) {
		    								$iselecty = 'selected';
	    								} else {
		    								$iselecty = '';
	    								}
    									$getlevel = mysql_query("select * from ebpls_user_sublevel where
										submenu = '$reports[0]'");
					$getme = mysql_fetch_assoc($getlevel);
					
					if ($$getme[rptvars]==1) {
    									print"<option value=$reports[1] $iselecty>$reports[0]</option>";
					}
									}
    								print"</select><input type=hidden name='pedd_select'></td>";
    							//echo get_select_data($dbLink,'report_list','ebpls_reports','report_file','report_desc',$owner_datarow[report_id]);
    						?>
    					</tr>
						<tr>
						<tr>
							<td>&nbsp;</td>
						</tr>
						<?
						if ($pedd_select=='ebpls_peddler_list.php') {
?>
                        <tr>
                        <td width=40% align=right>
                        Date: </td>
                        <td align="left" valign="top" class='normal'>&nbsp;
                              
<input type="text" value="<?php echo date('Y/m/d'); ?>" readonly name="date_from" onclick="displayCalendar(date_from,'yyyy/mm/dd',this);">
<img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date" onclick ="displayCalendar(_FRM.date_from,'yyyy/mm/dd',this);_FRM.changeondin.value=1;">
 
<input type="text" value="<?php echo date('Y/m/d'); ?>" readonly name="date_to" onclick="displayCalendar(date_to,'yyyy/mm/dd',this);">
<img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date" onclick ="displayCalendar(_FRM.date_to,'yyyy/mm/dd',this);_FRM.changeondin.value=1;">

                        </td>
                        </tr>
						<tr>
                        <td width=40% align=right>
                        Barangay&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <?php echo get_select_brgy($dbLink,'brgy_name','ebpls_barangay','barangay_code','barangay_desc',$owner[6]);?>
                        </td>
                        </tr>
                        <tr>
                        <td width=40% align=right>
                        Owner's Last Name&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <input name=owner_last>
                        </td>
                        </tr>
                        <tr>
                        <td width=40% align=right>
                        Business Name&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <input name=business>
                        </td>
                        </tr>
                        <tr>
                        <td>&nbsp;</td>
                        </tr>
                        <tr>
                        <td colspan=2 align=center>
                        <input type=button name='buss_report' value="View Report" onClick="javascript: Peddlist();">
                        <input type=hidden name=usernm value="<?php echo $ThUserData['username']; ?>">
                        </td>
                        </tr>
<?php

                        }
						if ($pedd_select=='ebpls_peddler_permit.php') {
?>
                        <tr>
                        <td width=40% align=right>
                        Owner Last Name&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <input name=owner_last>
                        </td>
                        </tr>
                        <tr>
                        <td width=40% align=right>
                        Permit #&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <input name=permit_num>
                        </td>
                        </tr>
                        <tr>
                        <td>&nbsp;</td>
                        </tr>
                        <tr>
                        <td colspan=2 align=center>
                        <input type=button name='buss_report' value="View Report" onClick="javascript: PeddPermit();">
                        <input type=hidden name=usernm value="<?php echo $ThUserData['username']; ?>">
                        </td>
                        </tr>
<?php

                        }
?>
					</table>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<?php
			} elseif ($rpt=='CTC') {
			?>
			<tr>
				<td align="center" valign="top" width=100% colspan=2>
				<input type=hidden name=usernm value="<?php echo $ThUserData['username']; ?>">
				<script language="javascript">
				function CTCIssued()
        {
        var _FRM = document._FRM;
		var vDateSplit1 = _FRM.date_from.value.split("/");
		vDate1 = vDateSplit1[0] + vDateSplit1[1] + vDateSplit1[2];
		var vDateSplit2 = _FRM.date_to.value.split("/");
		vDate2 = vDateSplit2[0] + vDateSplit2[1] + vDateSplit2[2];
		var getDiff = (vDate2-vDate1);
		var today = new Date();
		 today.setFullYear(<? echo $yeartoday;?>,<? echo $monthtoday - 1;?>,<? echo $daytoday;?>);
		var datefrom = new Date();
		var dateto = new Date();
		datefrom.setFullYear(vDateSplit1[0],vDateSplit1[1]-1,vDateSplit1[2]);
		dateto.setFullYear(vDateSplit2[0],vDateSplit2[1]-1,vDateSplit2[2]);
		
		if (datefrom > today) {
			alert("Date Range cannot exceed current date.");
			_FRM.date_from.select();
			return false;
		}
		if (dateto > today) {
			alert("Date Range cannot exceed current date.");
			_FRM.date_to.select();
			return false;
		}
		if (getDiff < 0) {
			alert("Invalid Date Range!!");
			_FRM.date_from.select();
			return false;
		}
        winpopup = window.open('reports/' + _FRM.ctc_select.value + '?date_from=' + _FRM.date_from.value + '&date_to=' + _FRM.date_to.value + '&usern=' + _FRM.usernm.value);
//,'popup','height=500,width=800,menubar=no,scrollbars=yes,status=no,screenX=100,screenY=0,left=100,top=0');
        return true;


        }
		</script>
					<table border=0 cellspacing=0 cellpadding=0  width='100%'>
						<tr>
							<td class='header2' colspan=2 align=center>CTC</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<?php
								//where report_desc like '%business%' or report_desc like 'business%' 
								//$result = mysql_query("select report_desc, report_file from ebpls_reports order by report_desc asc") or die(mysql_error()); 
								$result= mysql_query("select report_desc, report_file from ebpls_reports 
								where report_desc like '%CTC%' order by report_desc asc") or die(mysql_error()); 
    							//$reports=mysql_fetch_row($result);
  								print "<td width=40% align=right>
    							Reports&nbsp</td>";
    							print "<td width=60% align=left>&nbsp
    								<select name='ctc_select' onchange='javascript: _FRM.ctc1_select.value=_FRM.ctc_select.value; _FRM.submit();'>";
    								print"<option value=''>--------</option>";
    								while ($reports=mysql_fetch_row($result)) {
	    								if ($ctc1_select == $reports[1]) {
		    								$iselecty = 'selected';
	    								} else {
		    								$iselecty = '';
	    								}
	    								$str_selected = (! strcasecmp($K,"$selected")) ? ('selected') : ('');
					$getlevel = mysql_query("select * from ebpls_user_sublevel where
										submenu = '$reports[0]'");
					$getme = mysql_fetch_assoc($getlevel);
					
					if ($$getme[rptvars]==1) {
    									print"<option value=$reports[1] $iselecty>$reports[0]</option>";
					}
									}
    								print"</select><input type=hidden name='ctc1_select'></td>";
    							//echo get_select_data($dbLink,'report_list','ebpls_reports','report_file','report_desc',$owner_datarow[report_id]);
    						?>
    					</tr>
						<tr>
						<tr>
							<td>&nbsp;</td>
						</tr>
						</table>
					</td>
				</tr>
						<?
						if ($ctc1_select=='ebpls_ctc_apply_masterlistbus.php' || $ctc1_select=='ebpls_ctc_apply_masterlist.php') {
?>
                        <tr>
                        <td width=40% align=right>
                        Date: </td>
                        <td align="left" valign="top" class='normal'>&nbsp;
                               
<input type="text" value="<?php echo date('Y/m/d'); ?>" readonly name="date_from" onclick="displayCalendar(date_from,'yyyy/mm/dd',this);">
<img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date" onclick ="displayCalendar(_FRM.date_from,'yyyy/mm/dd',this);_FRM.changeondin.value=1;">
 
<input type="text" value="<?php echo date('Y/m/d'); ?>" readonly name="date_to" onclick="displayCalendar(date_to,'yyyy/mm/dd',this);">
<img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date" onclick ="displayCalendar(_FRM.date_to,'yyyy/mm/dd',this);_FRM.changeondin.value=1;">

                        </td>
                        </tr>
                        <tr>
                        <td>&nbsp;</td>
                        </tr>
                        <tr>
                        <td colspan=2 align=center>
                        <input type=button name='buss_report' value="View Report" onClick="javascript:CTCIssued();">
                        </td>
                        </tr>
<?php

                        }
			} elseif ($rpt=='System') {
			?>
			<tr>
				<td align="center" valign="top" colspan=2>
					<table border=0 cellspacing=0 cellpadding=0  width='100%'>
					
						<tr>
							<td class='header2' colspan=2 align=center>System</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td width=40% align=right>
								Activity Log&nbsp;(Start Date)&nbsp;
							</td>
							<td width=60% align=left>&nbsp;
								        
<input type="text" value="<?php echo date('Y/m/d'); ?>" readonly name="date_from" onclick="displayCalendar(date_from,'yyyy/mm/dd',this);">
<img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date" onclick ="displayCalendar(_FRM.date_from,'yyyy/mm/dd',this);_FRM.changeondin.value=1;">
 

							</td>
						</tr>
						<tr>
							<td width=40% align=right>
								(End Date)&nbsp;
							</td>
							<td width=60% align=left>&nbsp;

<input type="text" value="<?php echo date('Y/m/d'); ?>" readonly name="date_to" onclick="displayCalendar(date_to,'yyyy/mm/dd',this);">
<img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date" onclick ="displayCalendar(_FRM.date_to,'yyyy/mm/dd',this);_FRM.changeondin.value=1;">

							</td>
						</tr>
						<tr>
							<td width=40% align=right>
								Users&nbsp;
							</td>
							<td width=60% align=left>&nbsp;
								<input type=text name='users' value='' >
							</td>
						</tr>
						<tr>
							<?php
								$result = mysql_query("select report_desc from ebpls_reports where report_type='System' order by report_desc asc") or die(mysql_error()); 
    							//$reports=mysql_fetch_row($result);
  								print "<td width=40% align=right>";
    							echo $reports;
    							print "Reports&nbsp</td>";
    							print "<td width=60% align=left>&nbsp&nbsp;\n
									<select name='buss_select'>";
    								while ($reports=mysql_fetch_row($result)) {
    									$getlevel = mysql_query("select * from ebpls_user_sublevel where
										submenu = '$reports[0]'");
					$getme = mysql_fetch_assoc($getlevel);
					
					if ($$getme[rptvars]==1) {
    									print"<option value=$reports[1] $iselecty>$reports[0]</option>";
					}
									}
    								print"</select></td>";
    							//echo get_select_data($dbLink,'report_list','ebpls_reports','report_file','report_desc',$owner_datarow[report_id]);
    							print "</td>";
    						?>
    					</tr>
						<tr>
						<tr>
							<td>&nbsp;</td>
						</tr>
						<tr>
						<input type=hidden name=report_file value="<?php echo $report_file; ?>">
			<input type=hidden name=usernm value="<?php echo $ThUserData['username']; ?>">
							<td colspan=2 align=center>
								<input type=button name='sys_report' value="View Report" onclick="javascript: SysRep();"> 
							</td>
						</tr>
					</table>	
				</td>
			</tr>
			<?php
			} elseif ($rpt=='Abstract') {
			?>
			  <tr>
                                <td align="center" valign="top" colspan=2>
                                        <table border=0 cellspacing=0 cellpadding=0  width='100%'>
                                                <tr>
                                                        <td class='header2' colspan=2 align=center>Abstract of General Collection Template</td>
                                                </tr>
                                                <tr>
                                                        <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                        <td width=40% align=right>
                                                                Tax/Fee:
                                                        </td>
                                                        <td width=60% align=left>&nbsp;
								<?php echo get_select_data_where($dbLink,'rpt_temp','ebpls_buss_tfo','tfoid','tfodesc',$owner_datarow[tfoid],'tfostatus ="A"');?>

                                                        </td>
                                                </tr>
						</table>
					
						<table border=0 cellspacing=0 cellpadding=0  width='100%'>

						<tr>
							<td>
							<?php

							$getr = mysql_query("select a.rpt_id, b.tfodesc from rpt_temp_abs a, ebpls_buss_tfo b 
								where a.tfoid = b.tfoid");
							?>
							<table border=1 cellspacing=0 cellpadding=0  width='50%' align=center>
							<tr>
								<td align=center width=70%>Tax/Fee</td><td align=center width=20%>&nbsp;</td>
							</tr>
							<?php
							while($listit = mysql_fetch_row($getr))
							{
							?>
							<tr><td><?php echo $listit[1]; ?></td><td align=center>
							<a href='index.php?part=4&class_type=Reports&itemID_=921&busItem=Reports&permit_type=Reports&com=delete&rpt_id=<?php echo $listit[0]; ?>'>Delete</a></td>		
							</tr>
							<?php
							}
							?>
							</table>
							</td>
						</tr>
						</table>

						<table border=0 cellspacing=0 cellpadding=0  width='100%'>

                                                <tr>
                                                <tr>
                                                        <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                        <td colspan=2 align=center>
                                                                <input type=submit name='sys_report' value="Add">
                                                        </td>
                                                </tr>
                                        </table>
                                </td>
                        </tr>
                        <?php
			} elseif ($rpt=='Tax/Fee') {
			?>
                        
                        
       
			 <tr>
                                <td align="center" valign="top" colspan=2>
                                        <table border=0 cellspacing=0 cellpadding=0  width='100%'>
                                                <tr>
							<td class='header2' colspan=2 align=center>Business Tax/Fee Collection Report</td>
						</tr>
						
						<tr>
							<td>&nbsp;</td>
						</tr>
						
						
						
						  <tr>
				        <td align=right>Previous Date:&nbsp;</td>
					<td align=left>
				<!--	<input type=text align=right  name=checkdate value='' size=10 readonly>-->
<input type="text" value="<?php echo date('Y/m/d'); ?>" readonly name="checkid1" onclick="displayCalendar(checkid1,'yyyy/mm/dd',this);">
<img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date" onclick ="displayCalendar(_FRM.checkid1,'yyyy/mm/dd',this);_FRM.changeondin.value=1;">

				        </tr>
				         <tr>
				        <td align=right>Current Date:&nbsp;</td>
					<td align=left>
				<!--	<input type=text align=right  name=checkdate value='' size=10 readonly>-->
						<input type="text" value="<?php echo date('Y/m/d'); ?>" readonly name="checkid2" onclick="displayCalendar(checkid2,'yyyy/mm/dd',this);">
<img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date" onclick ="displayCalendar(_FRM.checkid2,'yyyy/mm/dd',this);_FRM.changeondin.value=1;">
 		        </tr>
				        <tr>
						
					
						<tr>
							<td>&nbsp;</td>
						</tr>
						
						<tr>
						<!--<input type=hidden name=col_sel value='<? echo $col_sel;?>'>-->
							<?php
								//where report_desc like '%business%' or report_desc like 'business%' 
								$result = mysql_query("select report_desc, report_file from ebpls_reports 
									where report_type like '%colection%' or 
									report_type like '%collection%'
									order by report_desc asc") or die(mysql_error()); 
    		
  								print "<td width=20% align=right>
    							Reports&nbsp</td>";
    							print "<td width=20% align=left>
    								<select name='col_sel' onChange='javascript: submit();'>";
    								print"<option value='' >---------</option>";
    								while ($reports=mysql_fetch_row($result)) {
	    								if ($col_sel==$reports[1]) {
		    								$ifselect="selected";
	    								} else {
		    								$ifselect="";
	    								}
    									$getlevel = mysql_query("select * from ebpls_user_sublevel where
										submenu = '$reports[0]'");
					$getme = mysql_fetch_assoc($getlevel);
					
					if ($$getme[rptvars]==1) {
    									print"<option value=$reports[1] $ifselect>$reports[0]</option>";
					}
									}
    								print"</select></td>";
    							
    							//echo get_select_data($dbLink,'report_list','ebpls_reports','report_file','report_desc',$owner_datarow[report_id]);
    						?>
    					</tr>
    				<?php	
    					if ($col_sel=='ebpls_abstractcoll.php') {
			?>
			  <tr>
                                <td align="center" valign="top" colspan=2>
                                        <table border=0 cellspacing=0 cellpadding=0  width='100%'>
                                                <tr>
                                                        <td class='header2' colspan=2 align=center>Abstract of General Collection Template</td>
                                                </tr>
                                                <tr>
                                                        <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                        <td width=40% align=right>
                                                                Tax/Fee:
                                                        </td>
                                                        <td width=60% align=left>&nbsp;
								<?php echo get_select_data_where($dbLink,'rpt_temp','ebpls_buss_tfo','tfoid','tfodesc',$owner_datarow[tfoid],'tfostatus ="A"');?>

                                                        </td>
                                                </tr>
						</table>
					
						<table border=0 cellspacing=0 cellpadding=0  width='100%'>

						<tr>
							<td>
							<?php

							$getr = mysql_query("select a.rpt_id, b.tfodesc from rpt_temp_abs a, ebpls_buss_tfo b 
								where a.tfoid = b.tfoid");
							?>
							<table border=1 cellspacing=0 cellpadding=0  width='50%' align=center>
							<tr>
								<td align=center width=70%>Tax/Fee</td><td align=center width=20%>&nbsp;</td>
							</tr>
							<?php
							while($listit = mysql_fetch_row($getr))
							{
							?>
							<tr><td><?php echo $listit[1]; ?></td><td align=center>
							<a href='index.php?part=4&class_type=Reports&itemID_=921&busItem=Reports&permit_type=Reports&com=delete&rpt_id=<?php echo $listit[0]; ?>'>Delete</a></td>		
							</tr>
							<?php
							}
							?>
							</table>
							</td>
						</tr>
						</table>

						<table border=0 cellspacing=0 cellpadding=0  width='100%'>

                                                <tr>
                                                <tr>
                                                        <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                        <td colspan=2 align=center>
                                                                <input type=submit name='sys_report' value="Add">
                                                        </td>
                                                </tr>
                                        </table>
                                </td>
                        </tr>
                        <?php
			}
    					
    						if ($col_sel=="ebpls_ctc_issued.php") {
	    					
    						}
							if ($col_sel=="ebpls_collection_summary.php") {
	    						?>
	    				<tr>
	    					<?php
								//where report_desc like '%business%' or report_desc like 'business%' 
								$result1 = mysql_query("select * from ebpls_buss_taxfeetype") or die(mysql_error()); 
    		
  								print "<td width=20% align=right>
    							Type&nbsp</td>";
    							print "<td width=20% align=left>
    								<select name='taxtype'>";
    								while ($reports1=mysql_fetch_row($result1)) {
    									$getlevel = mysql_query("select * from ebpls_user_sublevel where
										submenu = '$reports[0]'");
					$getme = mysql_fetch_assoc($getlevel);
					
					if ($$getme[rptvars]==1) {
    									print"<option value=$reports[1] $iselecty>$reports[0]</option>";
					}
									}
    								print"</select></td>";
    							
    							//echo get_select_data($dbLink,'report_list','ebpls_reports','report_file','report_desc',$owner_datarow[report_id]);
    						?>
	    				</tr>
	    					<?
    						}
							if ($col_sel=="ebpls_audit_trail.php") {
	    						?>
	    				<tr>
                        <td width=40% align=right>
                        Owner's Last Name&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <input name=owner_last>
                        </td>
                        </tr>
                        <tr>
                        <td width=40% align=right>
                        PIN&nbsp;
                        </td>
                        <td width=60% align=left>&nbsp;
                        <input name=pin>
                        </td>
                        </tr>
                        <tr>
                        <td>&nbsp;</td>
                        </tr>
                        
	    					<?
    						}
    						?>
							<?
    						if ($col_sel=="ebpls_orderpayment.php" or $col_sel=="ebpls_recordpayment.php" or $col_sel=="ebpls_periodicbilling.php") {
	    						?>
	    				<tr>
	    					<td width=20% align=right>
    							Owner Last Name&nbsp;:</td>
    							<td width=20% align=left>
    								<input type=text name=owner_last_name>
    								
	    				</tr>
	    				<tr>
	    					<td width=20% align=right>
    							Owner First Name&nbsp;:</td>
    							<td width=20% align=left>
    								<input type=text name=owner_first_name>
    								
	    				</tr>
						<tr>
	    					<td width=20% align=right>
    							Business Name &nbsp;:</td>
    							<td width=20% align=left>
    								<input type=text name=business_name>
    								
	    				</tr>
	    					<?
    						}
							if ($col_sel=="ebpls_comparative_rpts_quart.php") {
	    						?>
	    				<tr>
	    					<td width=20% align=right>
    							Quarter&nbsp;:</td>
    							<td width=20% align=left>
    								<select name="iQrt">
										<option value="1">1st</option>
										<option value="2">2nd</option>
										<option value="3">3rd</option>
										<option value="4">4th</option>
									</select>
								</select>
	    				</tr>
							<?
    						}
    						?>
						<tr>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td colspan=2 align=center>
							<?
							if ($col_sel=='ebpls_abstractcoll.php') {
							?>	
								<input type=button name='buss_report' value="View Report" onClick="javascript:  AbstractColl(_FRM.col_sel.value);">
								<input type=hidden name='usernm' value="<?php echo $ThUserData['username'];?>">
							<?
							} elseif ($col_sel=='ebpls_comparative_annual.php') {
				
							?>
							<input type=hidden name=usernm value="<?php echo $ThUserData['username']; ?>">
								<input type=button name='buss_report' value="View Report" onClick="javascript: ComparativeAnnual(_FRM.col_sel.value);">
							<?
							} elseif ($col_sel=='ebpls_comparative_rpts_quart.php') {
				
							?>
								<input type=hidden name=usernm value="<?php echo $ThUserData['username']; ?>">
								<input type=button name='buss_report' value="View Report" onClick="javascript: ComparativeQuart(_FRM.col_sel.value);">
							<?
							} elseif ($col_sel=='ebpls_comparative_annual_chart.php') {
                                                        ?>
                                                                <input type=button name='buss_report' value="View Report" onClick="javascript: ComparativeAnnualChart(_FRM.col_sel.value);">
                                                        <?
                                                        } elseif ($col_sel=='ebpls_bus_taxcoll_quar.php') {
                                                        ?>
                                                                <input type=button name='buss_report' value="View Report" onClick="javascript: BussCollQuart(_FRM.col_sel.value);">
                                                        <?
                                                        } elseif ($col_sel=='ebpls_collection_summary.php') {
							?>
								<input type=button name='buss_report' value='View Report' onclick="javascript: SummaryCollect(_FRM.col_sel.value);">
								<input type='hidden' name='usernm' value="<? echo $ThUserData['username']; ?>">
							<?
							} elseif  ($col_sel=='ebpls_audit_trail.php') {
							?>
							<tr>
                        <td colspan=2 align=center>
                        <input type=button name='buss_report' value="View Report" onClick="javascript: AuditTrail(_FRM.col_sel.value);">
                        <input type=hidden name=usernm value="<?php echo $ThUserData['username']; ?>">
                        </td>
                        </tr>
						<? } elseif  ($col_sel=='ebpls_ctc_issued.php') {
							?>
								<tr>
                        <td colspan=2 align=center>
                        <input type=button name='buss_report' value="View Report" onClick="javascript: CTCIssueds(_FRM.col_sel.value);">
                        <input type=hidden name=usernm value="<?php echo $ThUserData['username']; ?>">
                        </td>
                        </tr>
						<? } else {
                                                        ?><input type=hidden name=ownname>
                                                        <input type=hidden name=bussname>
                                                        <input type=hidden name=taxtype>
                                                                <input type=button name='buss_report' value="View Report" onClick="javascript:  PM();">                                                        <?
                                                        }

							?>
</td>						 
						</tr>
					<?php 
					}
					?>

					</table>	
					</td>
		</tr>

			
</table>

<br>
<br>
<?php
	if ($buss_report=='View Report') {
		if ($buss_name<>'') {
			$type=$buss_name;
		} elseif ($permit_num<>'') {
			$type=$permit_num;
		} elseif ($brgy<>'') {
			$type=$brgy;
		} elseif ($cap_inv<>'') {
			$type=$cap_inv;
		} elseif ($gross_inc<>'') {
			$type=$gross_inc;
		}
	}
?>
<script language="Javascript">
function xs(type,array)
{
	var trans_id
        var x,y,w,h
        trans_id =  document._FRM.trans_id.value;
        // center on screen
        //buss_name,permit_num,brgy,ci,gi
        if ( cmd == 'CASH' ) {
                w = 400
                h = screen.height - 330
        } else if ( cmd == 'CHECK' ) {
                w = 600
                h = screen.height - 230
        }  else if ( cmd == 'CASHVIEW' ) {
                w = 400
                h = screen.height - 300
        }  else if ( cmd == 'CHECKVIEW' ) {
                w = 600
                h = screen.height - 200
        }  else if ( cmd == 'CHECKSTATUS' ) {
                w = 600
                h = screen.height - 200
        }




        x = screen.width/2 - w/2
        y = screen.height/2 - h/2
        strOption = 'scrollbars=yes,status=yes,width=' + 800 + ',height=' + 600 + ',screenX=' + x + ',screenY=' + y
        window.open ("ebpls_buss_permit.php, cmd, strOption");

}
function SysRep()
        {
        var _FRM = document._FRM;
		var vDateSplit1 = _FRM.date_from.value.split("/");
		vDate1 = vDateSplit1[0] + vDateSplit1[1] + vDateSplit1[2];
		var vDateSplit2 = _FRM.date_to.value.split("/");
		vDate2 = vDateSplit2[0] + vDateSplit2[1] + vDateSplit2[2];
		var getDiff = (vDate2-vDate1);
		var today = new Date();
		 today.setFullYear(<? echo $yeartoday;?>,<? echo $monthtoday - 1;?>,<? echo $daytoday;?>);
		var datefrom = new Date();
		var dateto = new Date();
		datefrom.setFullYear(vDateSplit1[0],vDateSplit1[1]-1,vDateSplit1[2]);
		dateto.setFullYear(vDateSplit2[0],vDateSplit2[1]-1,vDateSplit2[2]);
		
		if (datefrom > today) {
			alert("Date Range cannot exceed current date.");
			_FRM.date_from.select();
			return false;
		}
		if (dateto > today) {
			alert("Date Range cannot exceed current date.");
			_FRM.date_to.select();
			return false;
		}
		if (getDiff < 0) {
			alert("Invalid Date Range!!");
			_FRM.date_from.select();
			return false;
		}
        winpopup = window.open('reports/ebpls_activity_log.php?date_from=' + _FRM.date_from.value + '&date_to=' + _FRM.date_to.value + '&owner_last=' + _FRM.users.value);
//,'popup','height=500,width=800,menubar=no,scrollbars=yes,status=no,screenX=100,screenY=0,left=100,top=0');
        return true;
                                                                                                 
                                                                                                 
        }
                                                                                                               
function Franlist()
        {
		var _FRM = document._FRM;
		var vDateSplit1 = _FRM.date_from.value.split("/");
		vDate1 = vDateSplit1[0] + vDateSplit1[1] + vDateSplit1[2];
		var vDateSplit2 = _FRM.date_to.value.split("/");
		vDate2 = vDateSplit2[0] + vDateSplit2[1] + vDateSplit2[2];
		var getDiff = (vDate2-vDate1);
		var today = new Date();
		 today.setFullYear(<? echo $yeartoday;?>,<? echo $monthtoday - 1;?>,<? echo $daytoday;?>);
		var datefrom = new Date();
		var dateto = new Date();
		datefrom.setFullYear(vDateSplit1[0],vDateSplit1[1]-1,vDateSplit1[2]);
		dateto.setFullYear(vDateSplit2[0],vDateSplit2[1]-1,vDateSplit2[2]);
		
		if (datefrom > today) {
			alert("Date Range cannot exceed current date.");
			_FRM.date_from.select();
			return false;
		}
		if (dateto > today) {
			alert("Date Range cannot exceed current date.");
			_FRM.date_to.select();
			return false;
		}
		if (getDiff < 0) {
			alert("Invalid Date Range!!");
			_FRM.date_from.select();
			return false;
		}
        winpopup = window.open('reports/ebpls_franchise_list.php?date_from=' + _FRM.date_from.value + '&date_to=' + _FRM.date_to.value + '&owner_last=' + _FRM.owner_last.value + '&brgy_name=' + _FRM.brgy_name.value + '&usernm=' + _FRM.usernm.value + '&motor_brand=' + _FRM.motor_brand.value + '&body_color=' + _FRM.body_color.value);
//,'popup','height=500,width=800,menubar=no,scrollbars=yes,status=no,screenX=100,screenY=0,left=100,top=0');
        return true;
                                                                                                 
                                                                                                 
        }
function FranchisePermit()
        {
        var _FRM = document._FRM;
		if (_FRM.owner_last.value == "" && _FRM.permit_num.value == "") {
			alert("Enter Owner Last Name or Permit Number.");
			_FRM.owner_last.focus();
			return false;
		}
        winpopup = window.open('reports/ebpls_motor_permit.php?owner_last=' + _FRM.owner_last.value + '&permit_num=' + _FRM.permit_num.value + '&reportpermit=1&permit_type=Franchise');
//,'popup','height=500,width=800,menubar=no,scrollbars=yes,status=no,screenX=100,screenY=0,left=100,top=0');
        return true;
                                                                                                 
                                                                                                 
        }
function Motorlist()
        {
        var _FRM = document._FRM;
		var vDateSplit1 = _FRM.date_from.value.split("/");
		vDate1 = vDateSplit1[0] + vDateSplit1[1] + vDateSplit1[2];
		var vDateSplit2 = _FRM.date_to.value.split("/");
		vDate2 = vDateSplit2[0] + vDateSplit2[1] + vDateSplit2[2];
		var getDiff = (vDate2-vDate1);
		var today = new Date();
		 today.setFullYear(<? echo $yeartoday;?>,<? echo $monthtoday - 1;?>,<? echo $daytoday;?>);
		var datefrom = new Date();
		var dateto = new Date();
		datefrom.setFullYear(vDateSplit1[0],vDateSplit1[1]-1,vDateSplit1[2]);
		dateto.setFullYear(vDateSplit2[0],vDateSplit2[1]-1,vDateSplit2[2]);
		
		if (datefrom > today) {
			alert("Date Range cannot exceed current date.");
			_FRM.date_from.select();
			return false;
		}
		if (dateto > today) {
			alert("Date Range cannot exceed current date.");
			_FRM.date_to.select();
			return false;
		}
		if (getDiff < 0) {
			alert("Invalid Date Range!!");
			_FRM.date_from.select();
			return false;
		}
        winpopup = window.open('reports/ebpls_motor_list.php?date_from=' + _FRM.date_from.value + '&date_to=' + _FRM.date_to.value + '&owner_last=' + _FRM.owner_last.value + '&brgy_name=' + _FRM.brgy_name.value + '&usernm=' + _FRM.usernm.value + '&motor_brand=' + _FRM.motor_brand.value + '&body_color=' + _FRM.body_color.value);
//,'popup','height=500,width=800,menubar=no,scrollbars=yes,status=no,screenX=100,screenY=0,left=100,top=0');
        return true;
                                                                                                 
                                                                                                 
        }
function MotorPermit()
        {
        var _FRM = document._FRM;
		if (_FRM.owner_last.value == "" && _FRM.permit_num.value == "") {
			alert("Enter Owner Last Name or Permit Number.");
			_FRM.owner_last.focus();
			return false;
		}
        winpopup = window.open('reports/ebpls_motor_permit.php?owner_last=' + _FRM.owner_last.value + '&permit_num=' + _FRM.permit_num.value + '&reportpermit=1&permit_type=Motorized');
//,'popup','height=500,width=800,menubar=no,scrollbars=yes,status=no,screenX=100,screenY=0,left=100,top=0');
        return true;
                                                                                                 
                                                                                                 
        }
function Occulist()
        {
        var _FRM = document._FRM;
		var vDateSplit1 = _FRM.date_from.value.split("/");
		vDate1 = vDateSplit1[0] + vDateSplit1[1] + vDateSplit1[2];
		var vDateSplit2 = _FRM.date_to.value.split("/");
		vDate2 = vDateSplit2[0] + vDateSplit2[1] + vDateSplit2[2];
		var getDiff = (vDate2-vDate1);
		var today = new Date();
		 today.setFullYear(<? echo $yeartoday;?>,<? echo $monthtoday - 1;?>,<? echo $daytoday;?>);
		var datefrom = new Date();
		var dateto = new Date();
		datefrom.setFullYear(vDateSplit1[0],vDateSplit1[1]-1,vDateSplit1[2]);
		dateto.setFullYear(vDateSplit2[0],vDateSplit2[1]-1,vDateSplit2[2]);
		
		if (datefrom > today) {
			alert("Date Range cannot exceed current date.");
			_FRM.date_from.select();
			return false;
		}
		if (dateto > today) {
			alert("Date Range cannot exceed current date.");
			_FRM.date_to.select();
			return false;
		}
		if (getDiff < 0) {
			alert("Invalid Date Range!!");
			_FRM.date_from.select();
			return false;
		}
/*		if (_FRM.agefrom.value < 0 || _FRM.agefrom.value == "" || _FRM.agefrom.value > 140 || isNaN(_FRM.agefrom.value)==true) {
			alert("Invalid Start Age Range!");
			_FRM.agefrom.select();
			return false;
		}
		if (_FRM.ageto.value < 0 || _FRM.ageto.value == "" || _FRM.ageto.value > 140 || isNaN(_FRM.ageto.value)==true) {
			alert("Invalid End Age Range!");
			_FRM.ageto.select();
			return false;
		}
		if (_FRM.agefrom.value > _FRM.ageto.value) {
			alert("Invalid Start Age Range!");
			_FRM.agefrom.select();
			return false;
		}*/
        winpopup = window.open('reports/ebpls_occupational_list.php?date_from=' + _FRM.date_from.value + '&date_to=' + _FRM.date_to.value + '&owner_last=' + _FRM.owner_last.value + '&brgy_name=' + _FRM.brgy_name.value + '&usernm=' + _FRM.usernm.value + '&employer=' + _FRM.employer.value + '&occupation=' + _FRM.occupation.value + '&sex=' + _FRM.sex.value);
//,'popup','height=500,width=800,menubar=no,scrollbars=yes,status=no,screenX=100,screenY=0,left=100,top=0');
        return true;
                                                                                                 
                                                                                                 
        }
function OccuPermit()
        {
        var _FRM = document._FRM;
		if (_FRM.owner_last.value == "" && _FRM.permit_num.value == "") {
			alert("Enter Owner Last Name or Permit Number.");
			_FRM.owner_last.focus();
			return false;
		}
        winpopup = window.open('reports/ebpls_occ_permit.php?owner_last=' + _FRM.owner_last.value + '&permit_num=' + _FRM.permit_num.value + '&reportpermit=1&permit_type=Motorized');
//,'popup','height=500,width=800,menubar=no,scrollbars=yes,status=no,screenX=100,screenY=0,left=100,top=0');
        return true;
                                                                                                 
                                                                                                 
        }
function Fishlist()
        {
        var _FRM = document._FRM;
		var vDateSplit1 = _FRM.date_from.value.split("/");
		vDate1 = vDateSplit1[0] + vDateSplit1[1] + vDateSplit1[2];
		var vDateSplit2 = _FRM.date_to.value.split("/");
		vDate2 = vDateSplit2[0] + vDateSplit2[1] + vDateSplit2[2];
		var getDiff = (vDate2-vDate1);
		var today = new Date();
		 today.setFullYear(<? echo $yeartoday;?>,<? echo $monthtoday - 1;?>,<? echo $daytoday;?>);
		var datefrom = new Date();
		var dateto = new Date();
		datefrom.setFullYear(vDateSplit1[0],vDateSplit1[1]-1,vDateSplit1[2]);
		dateto.setFullYear(vDateSplit2[0],vDateSplit2[1]-1,vDateSplit2[2]);
		
		if (datefrom > today) {
			alert("Date Range cannot exceed current date.");
			_FRM.date_from.select();
			return false;
		}
		if (dateto > today) {
			alert("Date Range cannot exceed current date.");
			_FRM.date_to.select();
			return false;
		}
		if (getDiff < 0) {
			alert("Invalid Date Range!!");
			_FRM.date_from.select();
			return false;
		}
        winpopup = window.open('reports/ebpls_fishery_list.php?date_from=' + _FRM.date_from.value + '&date_to=' + _FRM.date_to.value + '&brgy_name=' + _FRM.brgy_name.value + '&usernm=' + _FRM.usernm.value);
//,'popup','height=500,width=800,menubar=no,scrollbars=yes,status=no,screenX=100,screenY=0,left=100,top=0');
        return true;
                                                                                                 
                                                                                                 
        }
function FishPermit()
        {
        var _FRM = document._FRM;
		if (_FRM.owner_last.value == "" && _FRM.permit_num.value == "") {
			alert("Enter Owner Last Name or Permit Number.");
			_FRM.owner_last.focus();
			return false;
		}
        winpopup = window.open('reports/ebpls_fish_permit.php?owner_last=' + _FRM.owner_last.value + '&permit_num=' + _FRM.permit_num.value + '&reportpermit=1&permit_type=Fishery');
//,'popup','height=500,width=800,menubar=no,scrollbars=yes,status=no,screenX=100,screenY=0,left=100,top=0');
        return true;
                                                                                                 
                                                                                                 
        }
function Peddlist()
        {
        var _FRM = document._FRM;
		var vDateSplit1 = _FRM.date_from.value.split("/");
		vDate1 = vDateSplit1[0] + vDateSplit1[1] + vDateSplit1[2];
		var vDateSplit2 = _FRM.date_to.value.split("/");
		vDate2 = vDateSplit2[0] + vDateSplit2[1] + vDateSplit2[2];
		var getDiff = (vDate2-vDate1);
		var today = new Date();
		 today.setFullYear(<? echo $yeartoday;?>,<? echo $monthtoday - 1;?>,<? echo $daytoday;?>);
		var datefrom = new Date();
		var dateto = new Date();
		datefrom.setFullYear(vDateSplit1[0],vDateSplit1[1]-1,vDateSplit1[2]);
		dateto.setFullYear(vDateSplit2[0],vDateSplit2[1]-1,vDateSplit2[2]);
		
		if (datefrom > today) {
			alert("Date Range cannot exceed current date.");
			_FRM.date_from.select();
			return false;
		}
		if (dateto > today) {
			alert("Date Range cannot exceed current date.");
			_FRM.date_to.select();
			return false;
		}
		if (getDiff < 0) {
			alert("Invalid Date Range!!");
			_FRM.date_from.select();
			return false;
		}
        winpopup = window.open('reports/ebpls_peddler_list.php?date_from=' + _FRM.date_from.value + '&date_to=' + _FRM.date_to.value + '&owner_last=' + _FRM.owner_last.value + '&brgy_name=' + _FRM.brgy_name.value + '&usernm=' + _FRM.usernm.value + '&business=' + _FRM.business.value);
//,'popup','height=500,width=800,menubar=no,scrollbars=yes,status=no,screenX=100,screenY=0,left=100,top=0');
        return true;
                                                                                                 
                                                                                                 
        }
function PeddPermit()
        {
        var _FRM = document._FRM;
		if (_FRM.owner_last.value == "" && _FRM.permit_num.value == "") {
			alert("Enter Owner Last Name or Permit Number.");
			_FRM.owner_last.focus();
			return false;
		}
        winpopup = window.open('reports/ebpls_peddler_permit.php?owner_last=' + _FRM.owner_last.value + '&permit_num=' + _FRM.permit_num.value + '&reportpermit=1&permit_type=Motorized');
//,'popup','height=500,width=800,menubar=no,scrollbars=yes,status=no,screenX=100,screenY=0,left=100,top=0');
        return true;
                                                                                                 
                                                                                                 
        }

</script>
<?php 
//echo get_select_data($dbLink,'report_list','ebpls_reports','report_id','report_desc',$owner_datarow[report_id]);
/*    $result = mysql_query("select report_desc from ebpls_reports order by report_desc asc") or die(mysql_error()); 
    $reports=mysql_fetch_row($result);
  
    echo $reports;
    echo get_select_data($dbLink,'report_list','ebpls_reports','report_file','report_desc',$owner_datarow[report_id]);*/
//	echo get_select_data($dbLink,'report_list','ebpls_reports','report_file',$result,$owner_datarow[report_id]);
   
?></div>

<?php

		if ($search=='Search') {

        
}
?>
    
<?php

//require_once("includes/eBPLS_footer.php");

?>

