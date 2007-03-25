<?php
require'includes/variables.php';
include'lib/phpFunctions-inc.php';
include'class/lgu.location.class.php';
include'class/city.class.php';
//if ($saveyes=='ssshhh') {
//echo "<div align=center><font color=red><b><i>Record Save Successfully</i></b></font></div>";
//$saveyes='';
//}
if ($sb=='Submit') {
 if ($com=='edit') {
                $bname=strtoupper($bname);
                $sname=strtoupper($sname);
		$nQuery = "select * from $preft where $prefc='$sname-$bname' and $prefd='$bname' and blgf_code='$blgf_codes' and $prefc!='$bbo' and upper='$sname'";
		$nLGU = new LGU_Loc;
		$nLGU->Query1($nQuery);
		$chkDuplicate = $nLGU->dbResultOut;
                if ($chkDuplicate==0) {
			$nQuery = "update $preft set $prefd='$bname', g_zone='$ch', upper='$sname', blgf_code='$blgf_codes'  where $prefc='$bbo'";
			$nLGU = new LGU_Loc;
			$nLGU->UpdateQuery($preft, $nQuery);
                $bbo='';
                $saveyes='ssshhh';
                $com='';
		?>
		<body onload='javascript:alert("Record Save Successfully");'></body>
		<?php
		//echo "<div align=center><font color=red><b><i>Record Save Successfully</i></b></font></div>";
                } else {
?>
                <body onload='javascript:alert("Duplicate Record Found");'></body>
<?php
                                                                                                 
//                      echo "<div align=center>DUPLICATE RECORD</div>";
                }
} else {
$bname1 =  mysql_query("select * from $preft where $prefc='$sname-$bname' and $prefd='$bname' and upper='$sname'") or die ("**");
$bc1 = mysql_num_rows($bname1);
  if ($chk1=='on') {
      $ch = 1;
  } else {
      $ch = 0;
  }
$getaway=$sname."-".$bname;
	if ($bc1==0) {
		$bname=strtoupper($bname);
		$sname=strtoupper($sname);
		$chkduplicate = mysql_query("select * from $preft where $prefc='$getaway' and $prefd='$bname' and upper='$sname'");
		$chkduplicate1 = mysql_num_rows($chkduplicate);
		if ($chkduplicate1==0) {
			$r = mysql_query("insert into $preft
         	values ('$sname-$bname', '$bname',now(),now(),'$usern', $ch,'$sname','$blgf_codes')") or die(mysql_error());
		$saveyes='ssshhh';
		echo "<div align=center><font color=red><b><i>Record Save Successfully</i></b></font></div>";
	 	} else {
?>
		<body onload='javascript:alert("Duplicate Record Found");'></body>
<?php
//	 		echo "<div align=center>DUPLICATE RECORD</div>";
 		}
	}
}
$bcode='';
//$bname='';
}elseif ($confx == 1) {
$bbo = $bcode;
   $r = mysql_query("delete from $preft where $prefc='$bbo'") or die ("ff");
?>
        <body onload='javascript:alert("Record Deleted");'></body>
<?php

}

$bname1 =  mysql_query("select * from $preft where $prefc='$bbo'") or die ("**");
$bc = mysql_fetch_row($bname1);
$bn = $bc[1];
$bn7= $bc[7];
//echo $bn."kjshdksjhdk";
//$spf = mysql_query("select * from $prefu where $prefdc='$bc[6]'");
//$spf = mysql_fetch_row($spf);
//$selectpref2 = $spf[1];
//if ($prefm=='Zip') {
$selectprefup = $bc[6];
//echo $selectprefup."kjsgdkjsgdk";
//}

 if ($bc[5]=='1') {
      $tsek = 'CHECKED';
  } else {
      $tsek = 'UNCHECKED';
  }
$data_item=0;
include'tablemenu-inc.php';
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
$searchsqlr="select a.* $kyeme from $preft a  $kyemet order by a.$prefd $ascdesc limit $fromr, $max_resultsr";
$cntsqlr = "select count(*) from $preft";
include'html/lgu_location.html';
$data_item=1;
include'tablemenu-inc.php';
?>
