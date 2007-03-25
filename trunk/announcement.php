<?php
$permit_type='Business';
require'includes/variables.php';
include'lib/phpFunctions-inc.php';
if ($Submit=='Submit') {		
if ($sms_send<>'') {
	$sms_send1=1;
} else {
	$sms_send1=0;
}
//echo $permit_type."<br>";
$bname1 =  mysql_query("select * from ebpls_announcement where announcements='$iannouncement'") or die ("**".mysql_error());
$bc1 = mysql_num_rows($bname1);
$bc2 = mysql_fetch_row($bname1);
if ($com=='edit') {
	$bc1=1;
}

	if ($bc1==0) {
	$lstn =  mysql_query("select * from ebpls_announcement order by eaid desc") 
		or die ("**".mysql_error());
	$lastcnt = mysql_fetch_row($lstn);
	$lstn = $lastcnt[0]+1;
		
			 $newpermitformat = mysql_query("insert into ebpls_announcement
			         values ($lstn, '$announcements', '$announced_by',now(),
				'$usern',$sms_send1)") or die("Arg ".mysql_error());
	$sms_send1=1;
	} else {
		$updatetableindividual = mysql_query("update ebpls_announcement
				         set announcements='$announcements', announced_by='$announced_by', 
					 modified_by='$usern',date_modified=now(), 
					 sms_send=$sms_send1 where eaid=$bcode") 
					or die("Uh Ah ".mysql_error());
    	}


///send to sms

	if ($sms_send1==1) {
		$msgcom = 'Annoucement:'.$announcements.'-'.$announced_by;

		$check1 = mysql_query("select * from sms_archive
		         	where msg='$msgcom'") or die(mysql_error());
		$check1 = mysql_num_rows($check1);

 			if ($check1==0 || $check1=='') {
				$getnum = mysql_query ("select owner_gsm_no from $owner 
					where owner_gsm_no<>''");
				while ($sendit = mysql_fetch_row($getnum))
				{
				$owner_gsm_no = $sendit[0];
				                if (strlen($owner_gsm_no)==13) {
				                        $prefix = substr($owner_gsm_no, 0,4);
		                             		if ($prefix=='+639') {
                			                        $cell=substr($owner_gsm_no,1) ;
                                        		        if (!is_numeric($cell)) {
                                                        	$blak = 1;
	                                	                } else {
        	                                                $blak = 0;
                	                        	        }
	                        		        } else {
			                                   $blak = 1;
                			                }
						} else {
							$blak=1;
						}
					
					if ($blak==0) {
					$sendna = mysql_query("insert into sms_send
					values ('','$sendit[0]', '$msgcom', 1,
						now())") or die ("Insert Error: SMS Send");
					}
				}
			}
	}


}




if ($com=='delete') {
	$bname1 =  mysql_query("delete from ebpls_announcement where eaid=$bcode") or die ("**".mysql_error());
	$com='';
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>eBPLS Business Permit and Licensing System</title>
	<meta name="Author" content="PAARV">
	<link href="stylesheets/default.css" rel="stylesheet" type="text/css">
<title></title>
</head>
<body>
<form ENCTYPE="multipart/form-data" method="post" name=_FRM>
<input type=hidden name=part value="<?php echo $part;?>">
<input type=hidden name=class_type value="<?php echo $class_type;?>">
<input type=hidden name=selMode value="<?php echo $selMode;?>">
<input type=hidden name=action_ value="<?php echo $action_;?>">
<input type=hidden name=itemEvent value="<?php echo $itemEvent;?>">
<table border=0 width=100% align=center cellspacing=0 cellpadding=0>
<?
//if ($bcode<>'') {

if ($com=='edit') {
	$karu = 'where eaid='.$bcode;
} else {
	$karu = '';
}

if ($orderby=='') {
	$orderby = 'eaid desc';
} else {

	if ($wator=='desc') {
		$wator='asc';
	} else {
		$wator='desc';
	}
	$orderby = $orderby." ".$wator;
}
if ($com == 'edit') {
$getinfo1 = mysql_query("select * from ebpls_announcement $karu order by eaid desc") or die("Uh Ah ".mysql_error());
$getinfo1 = mysql_fetch_row($getinfo1);
$announcements=$getinfo1[1];
$announced_by=$getinfo1[2];

if ($getinfo1[5]==1) {
	$sms_send1=1;
} else {
	$sms_send1=0;
}
}
//}
?>
<tr><td colspan=2 class=header align=center width=100%>REFERENCES</td></tr>
<tr>
        <td colspan=2 align=center>
</td>
</tr>
		<tr width=90%>
			<td align=center colspan=3 class=header2> ANNOUNCEMENTS </td>
		</tr>
		<tr><td colspan=3 ><br></td></tr>
		<tr><td colspan=3 ><br></td></tr>

		<tr width=90%>
			<td align=left valign=top colspan=3><div align=center><textarea rows=5 cols=60 align=left name='announcements' class=text180><?php echo $announcements; ?></textarea></div>
			</td>
			<td> &nbsp </td>
		</tr>
		<tr width=90%>
			<td align=right valign=top> Announced By : </td>
			<td align='left' valign=top> &nbsp; <input type=text name='announced_by' size=30 class=text180 value='<?php echo $announced_by;?>'></td>
			<td> &nbsp </td>
		</tr>
		<tr width=90%>
			<td align=right valign=top> SMS Send : </td>
			<?php 
			if ($sms_send1==1) {
			?>
				<td align='left' valign=top> &nbsp; <input type=checkbox name='sms_send' align='left' value=1 checked></td>
			<?php } else { ?>
				<td align='left' valign=top> &nbsp; <input type=checkbox name='sms_send' align='left' value=0 unchecked></td>
			<?php
			}
			?>
			<td> &nbsp </td>
		</tr>
</table>
<table align=center border=0 cellspacing=0 cellpadding=0 width=90%>
	<tr width=90%>
		<td align=center valign=top>
			&nbsp</td>
	</tr>
	<tr width=90%>
		<td align=center valign=top>
			<input type=submit value='Submit' name=Submit>
			<input type=Button value=Cancel onClick='history.go(-1)'>
			<input type=Reset value=Reset>
			&nbsp<br><br></td>
	</tr>
</table>
<?php
require 'setup/setting.php';
if(!isset($_GET['page'])){
    $pagea = 1;
} else {
    $pagea = $_GET['page'];
}
                                                                                                                                                                                                   
// Define the number of results per page
$max_resultsa = $thIntPageLimit;
// Figure out the limit for the query based
// on the current page number.
$froma = abs((($pagea * $max_resultsa) - $max_resultsa));
$searchsqla = "select * from ebpls_announcement order by $orderby limit $froma, $max_resultsa";
$cntsqla = "select count(eaid) as NUM from ebpls_announcement";
$resulta = mysql_query($searchsqla)or die (mysql_error());
                                                                                                                                                                                                   
// Figure out the total number of results in DB:
$total_resultsa = mysql_result(mysql_query($cntsqla),0);
// Figure out the total number of pages. Always round up using ceil()
$total_pagesa = ceil($total_resultsa / $max_resultsa);
                                                                                                                                                                                                   
//$getan = mysql_query("select * from ebpls_announcement order by $orderby") or die(mysql_error());
//while ($getant = mysql_fetch_row($getan))
                                                                                                                                                                                                   
                                                                                                                                                                                                   
echo "<table border=0 width=90%><tr><td><div align=left><br />";
                                                                                                                                                                                                   
                        if($pagea > 1){
                        $preva = ($pagea- 1);
                        echo "<a href=$PHP_SELF?part=4&class_type=Preference&selMode=ebpls_nannouncement&action_=8&itemEvent=1&data_item=0part=4&page=$prev1><< Prev</a>&nbsp;";
                        }
                                                                                                                                                                                                   
                                                                                                                                                                                                   
                        for($i = 1; $i <= $total_pagesa; $i++){
                        if(($pagea) == $i){
                                echo "Page $i&nbsp;";
                        } else {
                        echo "<a href=$PHP_SELF?part=4&class_type=Preference&selMode=ebpls_nannouncement&action_=8&itemEvent=1&data_item=0&page=$i>$i</a>&nbsp;";
                        }
                        }
                                                                                                                                                                                                   
                                                                                                                                                                                                   
                                                                                                                                                                                                   
               // Build Next Link
                                                                                                                                                                                                   
                        if($pagea < $total_pagesa){
                        $nexta = ($pagea + 1);
                        echo "<a href=$PHP_SELF?part=4&class_type=Preference&selMode=ebpls_nannouncement&action_=8&itemEvent=1&data_item=0part=4&page=$nexta>Next>></a>";
                        }
                                                                                                                                                                                                   
                                                                                                                                                                                                   
echo "</td></tr></table>";
?>	
<table align=center border=0 cellspacing=0 cellpadding=0 width=100%>
<tr width=80%>
<td class='hdr' width=5%> &nbsp;No.</td>

<td class='hdr' width=40%>
<a href='index.php?part=4&class_type=Preference&selMode=ebpls_nannouncement&action_=8&itemEvent=1&data_item=0&orderby=announcements&wator=<?php echo $wator; ?>'>Announcement</a></td>
<td  class='hdr' width=20%>
<a href='index.php?part=4&class_type=Preference&selMode=ebpls_nannouncement&action_=8&itemEvent=1&data_item=0&orderby=announced_by&wator=<?php echo $wator; ?>'>
Annouced By</a></td>
<td class='hdr' width=15%>
<a href='index.php?part=4&class_type=Preference&selMode=ebpls_nannouncement&action_=8&itemEvent=1&data_item=0&orderby=date_modified&wator=<?php echo $wator; ?>'>
Date Announce</a></td>
<td class='hdr' width=10%>&nbsp;Send SMS&nbsp;</td>
<td class='hdr' width=10%>Action</td>
</tr>
<?php
require 'setup/setting.php';
$max_resultsr = $thIntPageLimit;
 $pagemulti = $page;
                                                                                                 
if ($pagemulti=='') {
        $pagemulti=1;
}

$norow=($pagemulti*$max_resultsr)-$max_resultsr;
while($getant = mysql_fetch_array($resulta))
{
$norow++;
include'tablecolor-inc.php';
print "<tr bgcolor='$varcolor'>\n";
print "<td width=5%>&nbsp;$norow&nbsp</td>\n";
?>
<td width=40%><?php echo $getant[1]; ?></td>
<td width=20%><?php echo $getant[2]; ?></td>
<td width=15%><?php echo $getant[3]; ?></td>
<?php
	if ($getant[5]==0) {
?>
	<td width=10%>No</td>
<?php  
	} else {
?>
	<td width=10%>Yes</td>
<?php

	}
?>
<td width=10%><a href='index.php?part=4&class_type=Preference&selMode=ebpls_nannouncement&action_=8&itemEvent=1&data_item=0&com=edit&bcode=<?php echo $getant[0]; ?>'>Edit </a> | 
<a href='index.php?part=4&class_type=Preference&selMode=ebpls_nannouncement&action_=8&itemEvent=1&data_item=0&com=delete&bcode=<?php echo $getant[0]; ?>'>Delete </a>
</td></tr>
<?php } //end while ?>
</table>

</form>
</body>
</html>
