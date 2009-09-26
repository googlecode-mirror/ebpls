<?php
//      Description:ebpls1219.php - one file that serves all other permit fees 
//      author: Vnyz Sofhia Ice
//      Trademark: [V[f]X]S!73n+_K!77er
//      Last Updated: Nov 24, 2004 Trece Martires, Cavite

require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");
require_once("ebpls-php-lib/ebpls.sysref.class.php");
require_once("ebpls-php-lib/utils/ebpls.search.funcs.php");
if ($orderbyasde==1) {
	$orderbyasde=0;
	$ascdesc='asc';
} else {
	$orderbyasde=1;
	$ascdesc='desc';
}
if ($ascdesc1=='') {
	$ascdesc1=$ascdesc;
} else {
	$ascdesc=$ascdesc1;
}

$permit_type=$feet;

if ($permit_type=='') {
$permit_type='Franchise';
}

//--- get connection from DB
//$dbLink = get_db_connection();
global $ThUserData;
$debug 	= false;
require "includes/variables.php";
//include("lib/multidbconnection.php");
//$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);

?>
<!--if(
	! is_valid_sublevels(33) or 
	! is_valid_sublevels(53) or 
	! is_valid_sublevels(74) or 
	! is_valid_sublevels(93) or 
	! is_valid_sublevels(113) or 
	! is_valid_sublevels(133) 
  )
 {
 	setUrlRedirect('index.php?part=999');
	
 } 

?>-->

<link rel="stylesheet" href="stylesheets/default.css" type="text/css"/>
<script language='Javascript' src='javascripts/default.js'></script>
<script language="javascript">
function delfee(x)
{
	var _FRM = document._FRM;
	
	condel = confirm("Record will be deleted, Continue?");
	if (condel == true) {
		_FRM.com.value = "Delete";
		_FRM.com1.value = "act";
		_FRM.bbo.value = x;
	} else {
		alert("Transaction Cancelled!!");
		return false;
	}
	_FRM.submit();
	return true;
}
</script>
<div align='center'>
<table border=0 cellspacing=0 cellpadding=0 width='100%'>
<tr><td class=header align=center width=100%>REFERENCES</td></tr>
<tr>
        <td align=center>
</td>
</tr>
<tr><td align="center" valign="center" class='header2'> Fees For <?php echo $permit_type; ?> Permit</td></tr>
<tr><td align="center" valign="center" class='normal' height=10>&nbsp;</td></tr>
</table>

<table border=0 cellspacing=0 cellpadding=0 width='620'>

<tr>
<td align="center" valign="center" class='title'>
<form name="_FRM" method="POST" action = "index.php?part=4&class_type=Preference&selMode=ebpls_notherfees&action_=8&itemEvent=1&data_item=0" >
<input type=hidden name=bbo value='<? echo $bbo;?>'>
<input type=hidden name=confx value='<? echo $confx;?>'>
<input type=hidden name=owner_id value='<? echo $owner_id;?>'>
<input type=hidden name=com>
<input type=hidden name=com1>
</td>
</tr>
<?php
if ($sb =='Submit') {
		if ($owner_id=='') {
			
			$checksame = SelectDataWhere($dbtype,$dbLink,$dtable,
				"where fee_desc='$feedesc' and
				permit_type='$ptype'");
			$chks = NumRows($dbtype,$checksame);
				if ($chks>0) {
?>
			        <body onLoad='javascript:alert ("Duplicate Entry. Cannot Save");'></body>
<?php

//		print "<td align=right><font color=red>Duplicat</font></td>";
  //              print "<td align=left><font color=red>e Entry. Cannot Save</font></td>";
				} else {
				$feedesc = addslashes($feedesc);
				if ($feet == "Motorized") {
				$result = InsertQuery($dbtype,$dbLink,$dtable,
						"(fee_desc, fee_amount, lastupdatedby, 
						lastupdated, permit_type, nyears)",
						"'$feedesc', $feeamount,'$usern', now(), '$ptype', '$MFees'");
					$MFees = "";
				} else {
					$result = InsertQuery($dbtype,$dbLink,$dtable,
						"(fee_desc, fee_amount, lastupdatedby, 
						lastupdated, permit_type)",
						"'$feedesc', $feeamount,'$usern', now(), '$ptype'");
				}
				$feet = "";
		$owner_id = "";
		$feedesc = "";
		$feeamount = "";
		$ptype = "";
		$com = "";
?>
			        <body onLoad='javascript:AddRec();'></body>
<?php
	
				}
		} else {
			if ($feet == "Motorized") {
			$result = UpdateQuery($dbtype,$dbLink,$dtable,
			"fee_desc='$feedesc', fee_amount = $feeamount, lastupdatedby='$usern',
			 lastupdated=now(), permit_type='$ptype', nyears = '$MFees'",
			"fee_id = '$owner_id'");
			$MFees = "";
		} else {
			$result = UpdateQuery($dbtype,$dbLink,$dtable,
			"fee_desc='$feedesc', fee_amount = $feeamount, lastupdatedby='$usern',
			 lastupdated=now(), permit_type='$ptype'",
			"fee_id = '$owner_id'");
		}
		$feet = "";
		$owner_id = "";
		$feedesc = "";
		$feeamount = "";
		$ptype = "";
		$com = "";
?>
			<body onLoad='javascript:UpRec();'></body>
<?php
		}
	

} elseif ($com=='Edit') {

$get = SelectDataWhere($dbtype,$dbLink,$dtable,"where fee_id =$owner_id");
$getr = FetchRow($dbtype,$get);
$feedesc = stripslashes($getr[1]);
$feeamount = $getr[2];
	if ($permit_type<>'Motorized') {
		$ptype = $getr[3];
	} else {
		$ptype = $getr[5];
		$MFees = $getr[7];
	}

require_once "includes/form_mtop.php";

} elseif ($com=='Delete') {
	
		if ($com1=='act') {
			
			$get = SelectDataWhere($dbtype,$dblink,$dtable,"where fee_id = '$bbo'");
			$get1 = @mysql_fetch_assoc($get);
			$desc = $get1[fee_desc];
			$exist = SelectDataWhere($dbtype,$dblink,'ebpls_fess_paid',"where fee_desc = '$desc'");
			$exist1 = @mysql_num_rows($get);
			if ($exist1 > 0) {
				?>
				<body onLoad='javascript:alert ("Cannot Delete, Record exist in other table");'></body>
				<?
			} else {
			$delrec= DeleteQuery($dbtype,$dbLink,$dtable,"fee_id='$bbo'");
?>
		        <body onLoad='javascript:alert ("Fee Deleted"); parent.location="index.php?part=4&class_type=Preference&permit=others&selMode=ebpls_notherfees&action_=8&itemEvent=1&data_item=0";'></body>
<?php
			}
		} else {
			$delrec= UpdateQuery($dbtype,$dbLink,$dtable,
                                 "active=0","fee_id=$owner_id");
?>
		        <body onLoad='javascript:alert ("Fee DeActivated"); parent.location="index.php?part=4&class_type=Preference&permit=others&selMode=ebpls_notherfees&action_=8&itemEvent=1&data_item=0";'></body>
<?php
		}

//$delrec =mysql_query ("delete from  $dtable  where fee_id =$id")  or die("DELETE Error: ".mysql_error());

//int "<td align=right><font color=red>Record Dele</font></td>";
//int "<td align=left><font color=red>ted Successfully</font></td>";
$owner_id='';
$id='';
$feeid='';
$desc='';
$amt='';
$ptype='';
if ($feet == "") {
	$feet = "Motorized";
}
require_once "includes/form_mtop.php";
}

if ($com=='') {
if ($feet == "") {
	$feet = "Motorized";
}
require_once "includes/form_mtop.php";

}

if ($actag=='') {
	$actag='DeActivate';
	$com1='deact';
} else {
	$actag='Activate';
	$com1='act';
}

if ($wator=='asc') {
        $wator='desc';
} else {
        $wator='asc';
}
                                                                                                               
if ($watfld=='') {
        $watfld='fee_desc';
}

if(!isset($_GET['page'])){
    $pager = 1;
} else {
    $pager = $_GET['page'];
}
                                                                                                               
                                                                                                               
// Define the number of results per page
require 'setup/setting.php';
$max_resultsr = $thIntPageLimit;
// Figure out the limit for the query based
// on the current page number.
$fromr = abs((($pager * $max_resultsr) - $max_resultsr));
if ($searchfee=='Search' || $searc==1) {
		if ($ordervalue=='type') {                                                                                                               
			$searchsqlr="select fee_id, fee_desc, fee_amount, permit_type, active from $dtable 
				where fee_desc like '$feedesc%' order by fee_desc $ascdesc limit $fromr, $max_resultsr";
		} elseif ($ordervalue=='head') {                                                                                                               
			$searchsqlr="select fee_id, fee_desc, fee_amount, permit_type, active from $dtable 
				where fee_desc like '$feedesc%' order by fee_amount $ascdesc limit $fromr, $max_resultsr";
		} elseif ($ordervalue=='sequ') {                                                                                                               
			$searchsqlr="select fee_id, fee_desc, fee_amount, permit_type, active from $dtable 
				where fee_desc like '$feedesc%' order by permit_type $ascdesc limit $fromr, $max_resultsr";
		} else {
			$searchsqlr="select fee_id, fee_desc, fee_amount, permit_type, active from $dtable 
				where fee_desc like '$feedesc%' order by fee_desc $ascdesc limit $fromr, $max_resultsr";
		}
	

	//$result = mysql_query ("select fee_id, fee_desc, fee_amount, permit_type, active from $dtable 
	//			where fee_desc like '$feedesc%' ") or die("SELECT Error:".mysql_error());
	} else {
		if ($ordervalue=='type') {                                                                                                               
			$searchsqlr="select fee_id, fee_desc, fee_amount, permit_type, active 
				from $dtable  order by $watfld $ascdesc limit $fromr, $max_resultsr";
		} elseif ($ordervalue=='head') {                                                                                                               
			$searchsqlr="select fee_id, fee_desc, fee_amount, permit_type, active 
				from $dtable  order by $watfld $ascdesc limit $fromr, $max_resultsr";
		} elseif ($ordervalue=='sequ') {                                                                                                               
			$searchsqlr="select fee_id, fee_desc, fee_amount, permit_type, active 
				from $dtable  order by $watfld $ascdesc limit $fromr, $max_resultsr";
		} else {
			$searchsqlr="select fee_id, fee_desc, fee_amount, permit_type, active 
				from $dtable  order by $watfld $ascdesc limit $fromr, $max_resultsr";
		}

	//$result = mysql_query ("select fee_id, fee_desc, fee_amount, permit_type, active 
	//			from $dtable  order by $watfld $wator ") or die("SELECT Error:
	//select fee_id, fee_desc, fee_amount, permit_type from $dtable limit 10
//".mysql_error());
	}
	$cntsqlr = "select count(*) from $dtable";

// Figure out the total number of results in DB:
$total_resultsr = mysql_result(mysql_query($cntsqlr),0);
// Figure out the total number of pages. Always round up using ceil()
$total_pagesr = ceil($total_resultsr / $max_resultsr);
                                                                                                               
                                                                                                               
echo "<tr><td align=left>";
 if($pager > 1){
                        $prevr = ($pager - 1);
						echo "<a href=$PHP_SELF?part=4&class_type=Preference&permit=others&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=1&ascdesc1=$ascdesc1>&lt;&lt;</a>&nbsp;";
                        echo "<a href=$PHP_SELF?part=4&class_type=Preference&permit=others&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$prevr&ascdesc1=$ascdesc1>Prev</a>&nbsp;";
                        }
                        
						if ($pager >=7) {
                                for($i = $pager-5; $i < $pager; $i++){
					echo "<a href=$PHP_SELF?part=4&class_type=Preference&permit=others&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$i&ascdesc1=$ascdesc1>$i</a>&nbsp;";
                                }
                                echo "$pager&nbsp;";
                                if ($total_pagesr > ($pager + 5)) {
                                        $tot_page = $pager + 5;
                                } else {
                                        $tot_page = $total_pagesr;
                                }
                                for($i = $pager+1; $i <= $tot_page; $i++){
					echo "<a href=$PHP_SELF?part=4&class_type=Preference&permit=others&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$i&ascdesc1=$ascdesc1>$i</a>&nbsp;";
                                }
                        } else {
                                if ($total_pages > 11) {
                                        $tot_page = 11;
                                } else {
                                        $tot_page = $total_pagesr;
                                }
                                for($i = 1; $i <= $tot_page; $i++){
									if ($tot_page != '1') {
                                        if(($pager) == $i){
                                                echo "$i&nbsp;";
                                        } else {
						echo "<a href=$PHP_SELF?part=4&class_type=Preference&permit=others&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$i&ascdesc1=$ascdesc1>$i</a>&nbsp;";
                                        }
									}
                                }
                        }
                                                                            
               // Build Next Link
                                                                                                               
                        if($pager < $total_pagesr){
                        $nextr = ($pager + 1);
                        echo "<a href=$PHP_SELF?part=4&class_type=Preference&permit=others&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$nextr&ascdesc1=$ascdesc1>Next</a>&nbsp;";
						echo "<a href=$PHP_SELF?part=4&class_type=Preference&permit=others&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$total_pagesr&ascdesc1=$ascdesc1>&gt;&gt;</a>";
                        }
echo "</td></tr>";                                                                                                               
print "<table border=0 cellspacing=0 cellpadding=1 align=center width=100%>\n";
//print "<td class=bold bgcolor='#E6FFE6'>Fee ID</td>\n";
print "<td class=hdr>&nbsp;No.</td>\n";
print "<td class=hdr><a href='index.php?part=4&class_type=Preference&permit=others&selMode=ebpls_notherfees&action_=8&itemEvent=1&data_item=0&watfld=fee_desc&feet=$permit_type&orderbyasde=$orderbyasde&ordervalue=desc'>Fee Description</a></td>\n";
print "<td class=hdr><a href='index.php?part=4&class_type=Preference&permit=others&selMode=ebpls_notherfees&action_=8&itemEvent=1&data_item=0&watfld=fee_amount&feet=$permit_type&orderbyasde=$orderbyasde&ordervalue=amou'>Fee Amount</a></td>\n";
print "<td class=hdr><a href='index.php?part=4&class_type=Preference&permit=others&selMode=ebpls_notherfees&action_=8&itemEvent=1&data_item=0&watfld=permit_type&feet=$permit_type&orderbyasde=$orderbyasde&ordervalue=type'>Transaction Type</a></td>\n";
print "<td class=hdr>Action</td>\n";
$cmd= "Edit";
$cmd1= "Delete";


	
//populate table
$resultr = Query1($dbtype,$dbLink,$searchsqlr);
require 'setup/setting.php';
$max_resultsr = $thIntPageLimit;
 $pagemulti = $page;
                         
if ($pagemulti=='') {
        $pagemulti=1;
}
 
$norow=($pagemulti*$max_resultsr)-$max_resultsr;
while ($get_info = FetchArray($dbtype,$resultr)){
	$norow++;
if ($get_info[active]==1) {
        $actag='Delete';
        $com1='deact';
} else {
        $actag='Delete';
        $com1='act';
}

include'tablecolor-inc.php';
print "<tr bgcolor='$varcolor'>\n";
//foreach ($get_info as $field )
echo "<td>&nbsp;".$norow."&nbsp;</td>\n";
echo "<td>&nbsp;".$get_info[fee_desc]."&nbsp;</td>\n";
echo "<td>&nbsp;".number_format($get_info[fee_amount],2)."&nbsp;</td>\n";
echo "<td>&nbsp;".$get_info[permit_type]."&nbsp;</td>\n";
?>
<td>
<a class=subnavwhite href='index.php?part=4&class_type=Preference&permit=others&selMode=ebpls_notherfees&action_=8&itemEvent=1&data_item=0&owner_id=<?php echo $get_info[fee_id]; ?>&com=<?php echo $cmd; ?>&feet=<?php echo $tag; ?>'>Edit</a> | 
<a href='#' class=subnavwhite onclick='javascript: delfee("<?php echo $get_info[fee_id]; ?>");'><?php echo $actag; ?></a></td>
<?php
print "</tr>\n";
}
echo "<tr><td align=left>";
 if($pager > 1){
                        $prevr = ($pager - 1);
						echo "<a href=$PHP_SELF?part=4&class_type=Preference&permit=others&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=1&ascdesc1=$ascdesc1>&lt;&lt;</a>&nbsp;";
                        echo "<a href=$PHP_SELF?part=4&class_type=Preference&permit=others&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$prevr&ascdesc1=$ascdesc1>Prev</a>&nbsp;";
                        }
                        
						if ($pager >=7) {
                                for($i = $pager-5; $i < $pager; $i++){
					echo "<a href=$PHP_SELF?part=4&class_type=Preference&permit=others&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$i&ascdesc1=$ascdesc1>$i</a>&nbsp;";
                                }
                                echo "$pager&nbsp;";
                                if ($total_pagesr > ($pager + 5)) {
                                        $tot_page = $pager + 5;
                                } else {
                                        $tot_page = $total_pagesr;
                                }
                                for($i = $pager+1; $i <= $tot_page; $i++){
					echo "<a href=$PHP_SELF?part=4&class_type=Preference&permit=others&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$i&ascdesc1=$ascdesc1>$i</a>&nbsp;";
                                }
                        } else {
                                if ($total_pages > 11) {
                                        $tot_page = 11;
                                } else {
                                        $tot_page = $total_pagesr;
                                }
                                for($i = 1; $i <= $tot_page; $i++){
									if ($tot_page != '1') {
                                        if(($pager) == $i){
                                                echo "$i&nbsp;";
                                        } else {
						echo "<a href=$PHP_SELF?part=4&class_type=Preference&permit=others&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$i&ascdesc1=$ascdesc1>$i</a>&nbsp;";
                                        }
									}
                                }
                        }
                                                                            
               // Build Next Link
                                                                                                               
                        if($pager < $total_pagesr){
                        $nextr = ($pager + 1);
                        echo "<a href=$PHP_SELF?part=4&class_type=Preference&permit=others&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$nextr&ascdesc1=$ascdesc1>Next</a>&nbsp;";
						echo "<a href=$PHP_SELF?part=4&class_type=Preference&permit=others&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$total_pagesr&ascdesc1=$ascdesc1>&gt;&gt;</a>";
                        }
echo "</td></tr>";
print "</table>";

?>	
