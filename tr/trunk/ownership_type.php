<?php
//Author: Robert M. Verzosa (VooDoo)
//Date Created: Matagal nang nagawa, kaya lang maraming comment si Eve.
require'includes/variables.php';
include'lib/phpFunctions-inc.php';
include'class/ownership.class.php';

if ($Submit=='Submit') 
{	
	if ($exemption<>'') 
	{
		$sQuery = "select * from ebpls_business_category where business_category_code='$bcode' or business_category_code='$engcode'";
		
		$nOwnType = new Ownership;
		$nOwnType->Query1($sQuery);
		$chkRecord = $nOwnType->dbResultOut;
		if ($chkRecord==0) 
		{
			if ($com<>edit) 
			{
				$sQuery = "select * from ebpls_business_category where business_category_code='$ownership_code'";
		
				$nOwnType = new Ownership;
				$nOwnType->Query1($sQuery);
				$chkRecord = $nOwnType->dbResultOut;
				if ($chkRecord==1) {
					?>
					<body onload='javascript:alert ("Existing Code Found!!"); return false;'></body>
					<?
				} else {
				$nValues="'$ownership_code', '$ownership_type',now(),now(),'$usern', $exemption";
				$nOwnType->InsertQuery('ebpls_business_category',$nValues);
				$ic = 0;
				$colls = 0;
				
				$getreq = mysql_query("select * from ebpls_buss_tfo
			                  where tfostatus='A' and taxfeetype<>1
			                  or tfodesc like 'Mayor%'") or die("req".mysql_error());
				$gt = mysql_num_rows($getreq);
				while ($ic<$gt)
				{
					while ($getr = mysql_fetch_row($getreq))
		   			{
						$ic++;
						$check1 = mysql_query("select * from fee_exempt where
			        		business_category_code='$ownership_code'
			        		and tfoid=$getr[0]") or die ("c".mysql_error());
						$hreq = mysql_num_rows($check1);
					        if ($hreq==0) 
						{
                        	        		if ($x[$ic]=='on') 
							{
	                                        		$s=1;
        	                        		} else {
                	                        		$s=0;
	                                		}
	 						$insertreq = mysql_query("insert into fee_exempt values	('', '$ownership_code',	$getr[0], $s)")	or die ("ins");
	 					//	echo "insert into fee_exempt values	('', '$ownership_code',	$getr[0], $s)";
	           		  		}
					}
				}
				$Submit = "";
							 ?>
                        <body onload='javascript:alert ("Data is successfully added to the database.");'></body>
                        <?php
				}
			}
			
  		} else {
			if ($com=='edit') {

                		$nUpdOwnType = new Ownership;
				$nUpdOwnType->UpdateQuery('ebpls_business_category',$ownership_code,$ownership_type,$usern,$exemption,$engcode);
        			$xc=0;
        			$i = 1;
        			while ($xc<$howmany) {
					$ic++;
					if ($x[$i]=='on') {
                                                $s=1;
                                        } else {
                                                $s=0;
                                        }
										///VooDoo
										$check1 = mysql_query("select * from fee_exempt where
                                        business_category_code='$ownership_code'
                                        and tfoid='$colls[$i]'") or die ("c".mysql_error());
                                        $hreq = mysql_num_rows($check1);
                                        if ($hreq==0) {
						$insertreq = mysql_query("insert into fee_exempt values ('', '$ownership_code', '$colls[$i]', '$s')") or die ("ins");
					} else {
                			$upreq = @mysql_query("update fee_exempt set active='$s' where business_category_code='$ownership_code' and tfoid='$colls[$i]'") or die ("fgh".mysql_error());
                			//echo "update fee_exempt set active=$s where business_category_code='$ownership_code' and tfoid=$colls[$i]";
                			$xc++;
                			$i++;
					}
        			}
        			$ownership_code='';
        			$ownership_type='';
        			$exemption='';
        			$bcode='';
        			$Submit = "";
        			$com = "";
        			?>
                        <body onload='javascript:alert ("Record Successfully Updated");'></body>
                        <?php
			}
		}
	}
} elseif ($com=='delete') {
	$where = "business_category_code='$bcode'";
        $nDelOwnType = new Ownership;
        $nDelOwnType->DeleteQuery('ebpls_business_category',$where);
        $r = mysql_query("delete from fee_exempt where business_category_code='$bcode'") or die("d");
	$bcode = '';
	$com = "";
}
$bname1 =  mysql_query("select * from ebpls_business_category where business_category_code='$bcode'") or die ("**");
if ($com=='edit') {
	$get_info=mysql_fetch_row($bname1);
}
$bc = mysql_fetch_row($bname1);
$bn = $bc[1];

include'html/ownership_type.html';
if ($bcode=='') {
$ownership_code=0;
}else {
$ownership_code=$bcode;
}
$ic = 0;
$colls = 0;

$getreq = mysql_query("select * from ebpls_buss_tfo 
                  where tfostatus='A' and taxfeetype<>1
		  or tfodesc like 'Mayor%' order by tfodesc") or die("req".mysql_error());
$gt = mysql_num_rows($getreq);
while ($ic<$gt)
{
   while ($getr = mysql_fetch_row($getreq))
   {
   $ic++;
	   if ($colls==0) {

include'tablecolor-inc.php';
print "<tr bgcolor='$varcolor'>\n";
           }

   $check1 = mysql_query("select * from fee_exempt where  
	    business_category_code='$ownership_code'
            and tfoid=$getr[0]") or die ("c".mysql_error());
   $hreq = mysql_num_rows($check1);
   	   if ($hreq==0 and  $ownership_code<>0) {
		/*
                        $insertreq = mysql_query("insert into fee_exempt values
                                     ('', '$business_category_code', 
					$getr[0], 0)")
                                      or die ("ins");
                */                        
					$ch='UNCHECKED';
                                        $gethr[3]=0;
           } else {
                        $gethr = mysql_fetch_row($check1);
                                 if ($gethr[3]==1) {
                                        $ch='CHECKED';
                                        } else {
                                        $ch='UNCHECKED';
                                        }
                                }
	   
$getr[1]=stripslashes($getr[1]);
	//Robert
        print "<td align=right width=5%><input type=hidden name=\"colls[$ic]\"
               value=\"$getr[0]\">&nbsp
               <input type=checkbox name=\"x[$ic]\" $ch></td><td align=left width=23%>$getr[1]
               </td>";
                        
	$colls=$colls+1;
        $arr_id[$i++] = $ic;
                 if ($colls>2) {
                    print "</tr><tr>";
                    $colls=0;
                 }
  }
                                                                                                                                                                                                   
                }

?>
<script language='Javascript' src='javascripts/default.js'></script>
<script language='Javascript'>
function checkAll(ids)
{
        var _FRM     = document._FRM;
        var _arr_ids = ids.split(":");
        for (var i=0;  i < _FRM.elements.length; i ++)
        {
                var _element = _FRM.elements[i];
                var _type = _element.type;
                                                                                                                                                                                                                                                                     
                if ( _type == "checkbox" )
                {
                        for(j=0;j<_arr_ids.length;j++)
                        {
                                var _chkname = 'x[' + _arr_ids[j] + ']';
                                if(_chkname == _element.name)
                                {
                                        _element.checked = (! _element.checked ) ? true : true;
                                        break;
                                }
                        }
                }
        }
}
</script>
<script language='Javascript'>
function clearAll(ids)
{
        var _FRM     = document._FRM;
        var _arr_ids = ids.split(":");
        for (var i=0;  i < _FRM.elements.length; i ++)
        {
                var _element = _FRM.elements[i];
                var _type = _element.type;
                                                                                                                                                                                                                                                                     
                if ( _type == "checkbox" )
                {
                        for(j=0;j<_arr_ids.length;j++)
                        {
                                var _chkname = 'x[' + _arr_ids[j] + ']';
                                if(_chkname == _element.name)
                                {
                                        _element.checked = (! _element.checked ) ? false : false;
                                        break;
                                }
                        }
                }
        }
}
</script>
                <input type=hidden name=howmany value=<?php echo $ic; ?>>
                </tr>
<td></td>
<td></td>
              <td align="right" valign="top" class='normal' colspan=4>&nbsp;
<?php
 $list_id     = @join(':',$arr_id);
        print "&nbsp;<a href='javascript:checkAll(\"$list_id\")'
                class='subnavwhite' onClick=''>Check All</a>\n";
        print "&nbsp;<a href='javascript:clearAll(\"$list_id\")'
                class='subnavwhite' onClick=''>Clear All</a>\n<br>";
?>
	</td></tr>

<script language='Javascript' src='javascripts/default.js'></script>
<script language="Javascript">
function checkowntype()
{
		var _FRM = document._FRM;
		if (isBlank(_FRM.ownership_code.value)) {
                alert (<?php echo $invalid_input_error; ?> + " Ownership Code.");
                _FRM.ownership_code.focus();
                _FRM.ownership_code.select();
              return false;
        }
        
        if (_FRM.ownership_code.value.length>15) {
                alert ("Ownership Code " + <?php echo $max_len_error; ?> );
                _FRM.ownership_code.focus();
                _FRM.ownership_code.select();
              return false;
        }
        
        
        
        if (isBlank(_FRM.ownership_type.value)) {
                alert (<?php echo $invalid_input_error; ?> + " Ownership Type.");
                _FRM.ownership_type.focus();
                _FRM.ownership_type.select();
              return false;
        }
        
        if (_FRM.ownership_type.value.length>30) {
                alert ("Ownership Type " + <?php echo $max_len_error; ?> );
                _FRM.ownership_type.focus();
                _FRM.ownership_type.select();
              return false;
        }
        
        if (isNaN(_FRM.exemption.value)==true || isBlank(_FRM.exemption.value)==true) {
                alert (<?php echo $invalid_input_error; ?> + " Tax Exemption.");
                _FRM.exemption.focus();
                _FRM.exemption.select();
              return false;
        }
        
        if (_FRM.exemption.value.length>5) {
                alert ("Tax Exemption " + <?php echo $max_len_error; ?> );
                _FRM.exemption.focus();
                _FRM.exemption.select();
              return false;
        }
        
        if (_FRM.exemption.value<0) {
                alert ("Tax Exemption " + <?php echo $cant_neg; ?> );
                _FRM.exemption.focus();
                _FRM.exemption.select();
              return false;
        }
        
        if (_FRM.exemption.value>100) {
                alert ("Tax Exemption cannot be greater than 100." );
                _FRM.exemption.focus();
                _FRM.exemption.select();
              return false;
        }
        
        
        
        _FRM.Submit.value = "Submit";
		_FRM.submit();
		return true;
}
function ClearValues()
{
		var _FRM = document._FRM;
		_FRM.ownership_type.value == '';
        _FRM.ownership_type.value == '';
		_FRM.exemption.value == '';
		_FRM.submit();
		return true;
	}
</script>
	</table>
	<table align=center border=0 cellspacing=0 cellpadding=0 width=90%>
		<tr width=90%>
			<td align=center valign=top>
			&nbsp</td>
		</tr>
		<tr width=90%>
			<td align=center valign=top>
			<input type=hidden name=Submit>
			<input type=button value=Save name=Submitq onClick="javascript: checkowntype();">			
			<input type=Button value=Cancel onClick="javascript: ClearValues();">
			<input type=Reset value=Reset>
			&nbsp<br><br></td>
		</tr>
	</table>
</form>
	<table align=center border=0 cellspacing=0 cellpadding=0 width=90%>
		<?php
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
                                                                                                               
if ($valueown=='desc') {                                                                                                               
	$searchsqlr="select * from ebpls_business_category order by business_category_desc $ascdesc limit $fromr, $max_resultsr";
} elseif ($valueown=='code') {                                                                                                               
	$searchsqlr="select * from ebpls_business_category order by business_category_code $ascdesc limit $fromr, $max_resultsr";
} else {
	$searchsqlr="select * from ebpls_business_category order by business_category_desc $ascdesc limit $fromr, $max_resultsr";
}

$cntsqlr = "select count(*) from ebpls_business_category";

	
$resultr = mysql_query($searchsqlr)or die (mysql_error());
                                                                                                               
// Figure out the total number of results in DB:
$total_resultsr = mysql_result(mysql_query($cntsqlr),0);
// Figure out the total number of pages. Always round up using ceil()
$total_pagesr = ceil($total_resultsr / $max_resultsr);
                                                                                                               
                                                                                                               
echo "<tr><td align=left>";
 if($pager > 1){
                        $prevr = ($pager - 1);
                        echo "<a href=$PHP_SELF?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=1&ascdesc1=$ascdesc1>&lt;&lt;</a>&nbsp;";
						echo "<a href=$PHP_SELF?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$prevr&ascdesc1=$ascdesc1>Prev</a>&nbsp;";
                        }
						
						if ($pager >=7) {
                                for($i = $pager-5; $i < $pager; $i++){
					echo "<a href=$PHP_SELF?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$i&ascdesc1=$ascdesc1>$i</a>&nbsp;";
                                }
                                echo "$pager&nbsp;";
                                if ($total_pagesr > ($pager + 5)) {
                                        $tot_page = $pager + 5;
                                } else {
                                        $tot_page = $total_pagesr;
                                }
                                for($i = $pager+1; $i <= $tot_page; $i++){
					echo "<a href=$PHP_SELF?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$i&ascdesc1=$ascdesc1>$i</a>&nbsp;";
                                }
                        } else {
                                if ($total_pages > 11) {
                                        $tot_page = 11;
                                } else {
                                        $tot_page = $total_pagesr;
                                }
                                for($i = 1; $i <= $tot_page; $i++){
									if ($tot_page != '1') {
                                        if(($pager) == $i) {
                                                echo "$i&nbsp;";
                                        } else {
						echo "<a href=$PHP_SELF?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$i&ascdesc1=$ascdesc1>$i</a>&nbsp;";
                                        }
									}
                                }
                        }
                                  
               // Build Next Link
                                                                                                               
                        if($pager < $total_pagesr){
                        $nextr = ($pager + 1);
                        echo "<a href=$PHP_SELF?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$nextr&ascdesc1=$ascdesc1>Next</a>&nbsp;";
						echo "<a href=$PHP_SELF?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$nextr&ascdesc1=$ascdesc1>&gt;&gt;</a>";
                        }
echo "</td></tr>";
		
			$result=mysql_query("select * from ebpls_business_category") or die(mysql_error());
		
		$totalcnt = mysql_num_rows($result);
		if ($totalcnt==0) {
                      //  print "<br><font color=red><div align= center>&nbsp No record found&nbsp</div></font>\n";
                }
        print "</table> <table width=100%>";
		print "<tr><td class='hdr' width=5%>&nbsp;No.&nbsp;</td>";
                print "<td class='hdr' width=20%>&nbsp;<a href=index.php?part=4&class_type=Preference&selMode=ebpls_nownership&action_=8&itemEvent=1&data_item=0&orderbyasde=$orderbyasde&valueown=code>Ownership Code&nbsp</a></td>";
		print "<td class='hdr' width=40%>&nbsp;<a href=index.php?part=4&class_type=Preference&selMode=ebpls_nownership&action_=8&itemEvent=1&data_item=0&orderbyasde=$orderbyasde&valueown=desc>Ownership Type&nbsp</a></td>";
		print "<td class='hdr' width=20%>&nbsp;Exemption( % )&nbsp</td>";
		print "<td class='hdr' align=center width=15%>&nbsp;Action&nbsp;</td>";
		print "</td></tr>";
require 'setup/setting.php';
$max_resultsr = $thIntPageLimit;
 $pagemulti = $page;
                         
if ($pagemulti=='') {
        $pagemulti=1;
}
 
$norow=($pagemulti*$max_resultsr)-$max_resultsr;                
while ($get_info = mysql_fetch_row($resultr)){
                print "<tr bgcolor='$varcolor'>";
                //foreach ($get_info as $field )
$norow++;                                                                                                                                                                                                                                                                     
print "<td width=5%>&nbsp;$norow&nbsp</td>";
print "<td width=20%>&nbsp;$get_info[0]&nbsp</td>";
print "<td width=40%>&nbsp;$get_info[1]&nbsp</td>";
print "<td width=20%>&nbsp;$get_info[5]&nbsp</td>";
print "<td align=center width=15%>&nbsp;<a href='index.php?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&com=edit&bcode=$get_info[0]' class='subnavwhite'>Edit</a>&nbsp;|&nbsp;";
?>
<a href='#' onClick="javascript: confdel('<? echo $get_info[0];?>')"; class='subnavwhite'>Delete</a>
</td></tr>
<?
}
echo "<tr><td align=left>";
 if($pager > 1){
                        $prevr = ($pager - 1);
                        echo "<a href=$PHP_SELF?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=1&ascdesc1=$ascdesc1>&lt;&lt;</a>&nbsp;";
						echo "<a href=$PHP_SELF?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$prevr&ascdesc1=$ascdesc1>Prev</a>&nbsp;";
                        }
						
						if ($pager >=7) {
                                for($i = $pager-5; $i < $pager; $i++){
					echo "<a href=$PHP_SELF?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$i&ascdesc1=$ascdesc1>$i</a>&nbsp;";
                                }
                                echo "$pager&nbsp;";
                                if ($total_pagesr > ($pager + 5)) {
                                        $tot_page = $pager + 5;
                                } else {
                                        $tot_page = $total_pagesr;
                                }
                                for($i = $pager+1; $i <= $tot_page; $i++){
					echo "<a href=$PHP_SELF?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$i&ascdesc1=$ascdesc1>$i</a>&nbsp;";
                                }
                        } else {
                                if ($total_pages > 11) {
                                        $tot_page = 11;
                                } else {
                                        $tot_page = $total_pagesr;
                                }
                                for($i = 1; $i <= $tot_page; $i++){
									if ($tot_page != '1') {
                                        if(($pager) == $i) {
                                                echo "$i&nbsp;";
                                        } else {
						echo "<a href=$PHP_SELF?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$i&ascdesc1=$ascdesc1>$i</a>&nbsp;";
                                        }
									}
                                }
                        }
                                  
               // Build Next Link
                                                                                                               
                        if($pager < $total_pagesr){
                        $nextr = ($pager + 1);
                        echo "<a href=$PHP_SELF?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$nextr&ascdesc1=$ascdesc1>Next</a>&nbsp;";
						echo "<a href=$PHP_SELF?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$nextr&ascdesc1=$ascdesc1>&gt;&gt;</a>";
                        }
echo "</td></tr>";
?>

	</table>


</table>


</form>
</body>
</html>
