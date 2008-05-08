<?php
/*Modification History: 
2008.05.08 RJC Define undefined variables and set undefined constants to text to reduce phperror.log
*/
require_once "includes/variables.php";
include_once "class/BusinessPermitClass.php";
include_once "class/BusinessEstablishmentClass.php";
$staxfee = SelectMultiTable($dbtype,$dbLink,"ebpls_buss_preference",
		"staxesfees,swaivetax, predcomp","");
$prefset = FetchArray($dbtype,$staxfee);	

$predcomp = $prefset['predcomp'];

$owner_id = isset($owner_id) ? $owner_id : ''; //2008.05.08
if ($owner_id=='') $owner_id=0;

$strValues = "edit_by='',edit_locked=0";
$strWhere="owner_id='$owner_id'";
$qu = mysql_query("update ebpls_owner set $strValues where $strWhere");

$business_id = isset($business_id) ? $business_id : ''; //2008.05.08
if ($business_id<>''){
	$ifpaid =SelectDataWhere($dbtype,$dbLink,$permittable,"where owner_id = $owner_id
                and business_id = $business_id and active = 1");
        $strValues = "edit_by='',edit_locked=0";
        $strWhere="business_id='$business_id'";
       	$qu = mysql_query("update ebpls_business_enterprise set $strValues where $strWhere");

//$ifpaid = mysql_query("select paid from $permittable where owner_id = $owner_id 
//		and business_id = $business_id and active = 1");
	$ifpaid = FetchRow($dbtype,$ifpaid);
	$ifpaid = $ifpaid[0];
	if ($ifpaid>0) {
		$ifpaid=0;
	}
}
$link23="#";

$getlastapp = mysql_query("select * from ebpls_business_enterprise_permit
					where owner_id='$owner_id' and business_id='$business_id'
					and active=1 order by  business_permit_id desc");
$lastapp = mysql_fetch_assoc($getlastapp);
$nyap = $lastapp['for_year'];
$lastapp = $lastapp['for_year'];
$lastapp = $lastapp + 1;

if ($predcomp==1 and $stat!='New' and $nyap<>'') {
//get pmode
$getpm = mysql_query("select * from ebpls_business_enterprise where owner_id='$owner_id' and business_id='$business_id'");
$pm = mysql_fetch_assoc($getpm);
$pmode = $pm['business_payment_mode'];
		
	
	//get one nature
	
		$getone =mysql_query("select * from tempbusnature where owner_id='$owner_id' and business_id='$business_id'  order by tempid ");
		$milan = mysql_num_rows($getone);
		$geto = mysql_fetch_assoc($getone);
		$busid = $geto['bus_code'];
		$datecreate = $geto['date_create'];
		
		//cnt how many lines last year
		$cntone = mysql_query("select * from tempbusnature where owner_id='$owner_id' and business_id='$business_id'  and bus_code='$busid'  order by tempid desc");
		$ilan = mysql_num_rows($cntone);
		$businessmo = date('m', strtotime($datecreate));

			if ($yearmo==$yearnow) {
                        if (strtolower($pmode)=='quarterly') {
                                if ($businessmo<=3 and $ilan==1) { // 1st q
                                        $getqtr0 = 0;
                                        $getqtr = 0;
                                        $predqtr = 3;
                                }

                                if ($businessmo > 3 and $ilan==2) { // 2st Q
                                        $getqtr1 = 1;
                                        $getqtr = 1;
                                        $predqtr = 2;
                                }
                                if ($businessmo > 6 and $ilan==3) { // 3nd Q
                                        $getqtr2 = 2;
                                        $getqtr = 2;
                                        $predqtr = 1;
                                }
                                if ($businessmo > 9 and $ilan==4) { //4 Q
                                        $getqtr3 = 3;
                                        $getqtr = 3;
                                        $predqtr = 0;
                                }

                        } elseif (strtolower($pmode)=='semi-annual') {
                                if ($businessmo<=6 and $ilan==1) { //1st q
                                        $getsem = 0;
                                        $predqtr = 1;
                                } else { // 2nd Q waive
                                        $getsem = 1;
                                        $predqtr = 0;
                                }
                        }

			
			 } else {
                        if (strtolower($pmode)=='quarterly') {
                                if ($ilan==1) { // 1st q
                                        $getqtr0 = 0;
                                        $getqtr = 0;
                                        $predqtr = 3;
                                }

                                if ($ilan==2) { // 2st Q
                                        $getqtr1 = 1;
                                        $getqtr = 1;
                                        $predqtr = 2;
                                }
                                if ($ilan==3) { // 3nd Q
                                        $getqtr2 = 2;
                                        $getqtr = 2;
                                        $predqtr = 1;
                                }
                                if ($ilan==4) { //4 Q
                                        $getqtr3 = 3;
                                        $getqtr = 3;
                                        $predqtr = 0;
                                }

                        } elseif (strtolower($pmode)=='semi-annual') {
                                if ($ilan==1) { //1st q
                                        $getsem = 0;
                                        $predqtr = 1;
                                } else { // 2nd Q waive
                                        $getsem = 1;
                                        $predqtr = 0;
                                }
                        }


                }

			
			
			if ($predqtr>0) {
				if (strtolower($pmode)=='semi-annual') {
					$kups='period';
				} else {
					$kups='quarters';
				}
				?>
	<body onload='alert("Tax payer have not entered his/her gross sales for the past <?php echo $pending_app; ?> <?php echo $kups; ?>. \n System will need input of the gross sales. "); parent.location="index.php?part=4&class_type=Permits&itemID_=5679&owner_id=<?php echo $owner_id; ?>&stat=<?php echo $stat; ?>&permit_type=Business&business_id=<?php echo $business_id; ?>&busItem=Business&delayed=<?php echo $predqtr; ?>";'></body>
	<?php
}
		

}






// $getlastapp = mysql_query("select * from ebpls_business_enterprise_permit
// 					where owner_id='$owner_id' and business_id='$business_id'
// 					and active=1 order by  business_permit_id desc");
// $lastapp = mysql_fetch_assoc($getlastapp);
// $nyap = $lastapp[for_year];
// $lastapp = $lastapp[for_year];
// $lastapp = $lastapp + 1;

if ($lastapp<$yearnow and $stat!='New' and $nyap<>'') {
	$pending_app =$yearnow  - ($lastapp);
	
	
	
	?>
	<body onload='alert("Tax payer have not renew his/her application for the past <?php echo $pending_app; ?> years. \n System will need input of the gross sales. "); parent.location="index.php?part=4&class_type=Permits&itemID_=6204&owner_id=<?php echo $owner_id; ?>&stat=<?php echo $stat; ?>&permit_type=Business&business_id=<?php echo $business_id; ?>&busItem=Business&delayed=<?php echo $pending_app; ?>";'></body>
	<?php
}


$predq = isset($predq) ? $predq : ''; //2008.05.08
$genpin = isset($genpin) ? $genpin : '';
?>
<form method=post action='index.php?part=4&class_type=Permits&itemID_=1221&permit_type=Business&busItem=Business&PROCESS=&mtopsearch=SEARCH&genpin=<?php echo $genpin; ?>' name="_FRM" >
<link rel="stylesheet" href="stylesheets/default.css" type="text/css"/>
<script language='Javascript' src='javascripts/default.js'></script>
<div align="CENTER">
<!---// start of the table //-->
<table border=0 cellspacing=0 cellpadding=0   width='100%'>
		<tr><td align="center" valign="center" class='header'  width='100%'> Business Enterprise Permit Application</td></tr>
		<tr><td align="center" valign="center" class='normal' height=10>&nbsp;</td></tr>
		<tr>
			<td align="center" valign="center">
			  <form name="_FRM" method="POST" action="" onSubmit="">
<input type=hidden  name =stat value='<?php echo $stat;?>'>		
<input type=hidden name=predq  value='<?php echo $predq; ?>'>		
          <table border=0 cellspacing=0 cellpadding=0 width='90%'>
            <tr> 
              <td align="center" valign="top" class='header2' colspan=4 > 
                Owner Information</td>
            </tr>
            <tr> 
	        <td align="right" valign="top" class='normal' colspan=4 width=20%>&nbsp;</td>
            </tr>
            <tr> 
              <td align="right" valign="top" class='normal' colspan=1> &nbsp; 
                 </td>
              <td align="left" valign="top" class='normal' colspan=3 width=20%>&nbsp;
				<a href='index.php?part=4&class_type=Permits&itemID_=1223&business_id=<?php echo $business_id; ?>&owner_id=<?php echo $owner_id;?>
				&com=search&permit_type=<?php echo $permit_type;?>&stat=<?php echo $stat;?>&busItem=Business&search_type=Owner' class='subnavwhite'>Search</a>&nbsp;
				
<?php
	if ($owner_id<1) {
?>
				 <a href='index.php?part=4&class_type=Permits&busItem=Business&itemID_=1221&permit_type=Business&mtopadd=ADD&stat=New&busItem=Business' class='subnavwhite'>Add New</a>&nbsp;
<?php
	} else {
?>
				<a href='index.php?part=4&class_type=Permits&busItem=Business&itemID_=1222&permit_type=Business&owner_id=<?php echo $owner_id; ?>&stat=<?php echo $stat; ?>&business_id=<?php echo $business_id; ?>&busItem=Business' class='subnavwhite'>Edit</a>&nbsp;
<?php
	}
?>
		<input type='hidden' name='owner_id' maxlength=25  value="<?php echo $owner_id; ?>"> 
              </td>
            </tr>
            <tr> 
<?php require_once "includes/owner_info.php";?>
<?php
	if ($genpin=='') {
                        if ($owner_id>0) {
                                        $getpin = new BusinessPermit;
                                        $getpin->GetPermit($owner_id,$business_id);
                                        $getpin->FetchPermit($getpin->outselect);
                                        $getp = $getpin->outarray[pin];
                        }
                        $getp = isset($getp) ? $getp : ''; //2008.05.08                                                                                                     
                        if ($getp=='') {
                                $getp=$genpin;
                        }
                                                                                                                             
                        if ($getp=='' and $owner_id<>'') {
//                       if ($atachit==1 and $haveat=='') {
                        	require "includes/genpin.php";
                         	$genpin=$pin;
                                                                                                                             
                         	$reds = SelectDataWhere($dbtype,$dbLink,"ebpls_business_enterprise",
                                	"where business_id = '$business_id'");
                         	$ret = FetchRow($dbtype,$reds);
                                                                                                                             
                        	$haveat=1;
                        }
                                                                                                                             
			if ($genpin=='') {
        			$genpin=$getp;
			}
	}		                                                                                                                
                ?>

              <td align="right" valign="top" class='normal' colspan=4>&nbsp; </td>
            </tr>
            <tr> 
              <td align="center" valign="top" class='header2' colspan=4 > 
                Business Enterprise Information</td>
            </tr>
            <tr> 
              <td align="right" valign="top" class='normal' colspan=4>&nbsp; </td>
            </tr>
            <tr> 
              <td align="right" valign="top" class='normal'> &nbsp; </td>
              <td align="left" valign="top" class='normal' >&nbsp;
        <a href='index.php?part=4&class_type=Permits&itemID_=1223&business_id=<?php echo $business_id; ?>&owner_id=<?php echo $owner_id;?>
&com=search&permit_type=<?php echo $permit_type;?>&stat=<?php echo $stat;?>&genpin=<?php echo $genpin; ?>&busItem=Business&search_type=Enterprise' class='subnavwhite'>Search</a>&nbsp;

<?php  if ($business_id=='') {
print	"<a href='index.php?part=4&class_type=Permits&genpin=$genpin&itemID_=1223&addbus=addbus&owner_id=$owner_id&permit_type=$permit_type&stat=New&busItem=$busItem' class='subnavwhite'>Add New</a>";
	} else {
print   "<a href='index.php?part=4&class_type=Permits&itemID_=1224&genpin=$genpin&business_id=$business_id&owner_id=$owner_id&permit_type=$permit_type&stat=$stat&addbiz=update&busItem=$busItem' class='subnavwhite'>Edit</a>";
$atachit = isset($atachit) ? $atachit : ''; //2008.05.08
} ?>
		
         <input type='hidden' name='business_id' maxlength=25 class='text180'  value="<?php echo $business_id; ?>"> 
                 </td>
              <td align="right" valign="top" class='normal'  > &nbsp;</td>
              <td align="left" valign="top" class='normal'>&nbsp; 
              </td>
            </tr>
            <tr>
	<input type=hidden name=atit value='<?php echo $atachit; ?>'> 
	      <td align="right" valign="top" class='normal'> <font color="#FF0000"> 
                </font>Business Name :  </td>
<?php
		if ($business_id<>'') {
		$res = new BusinessEstablishment;
		$res->GetBusinessByID($business_id);
		$res->FetchBusinessArray($res->outselect);
		$datarow = $res->outarray;
		}
	?>

              <td align="left" valign="top" class='normal'>&nbsp;&nbsp;<input type='hidden' name='business_name' maxlength=255 class='text180'  value="<?php echo $datarow['business_name']; ?>" readonly><?php echo stripslashes($datarow['business_name']); ?> </td>
              <td align="right" valign="top" class='normal' width=20%> Access Pin : </td>

<input type=hidden name=transfer_it value=<?php echo $atachit; ?>>
<input type=hidden name=genpin  value='<?php echo $genpin; ?>'>
              <td align="left" valign="top" class='normal' width=35%> <?php echo $genpin; ?> </td>
             </tr>
            <tr> 
              <td align="right" valign="top" class='normal' > Business Scale : 
              </td>
              <td align="left" valign="top" class='normal'>&nbsp; 
       		<input type='hidden' name='business_scale' maxlength=255 class='text180'  value="<?php echo $datarow['business_scale']; ?>"><?php echo $datarow['business_scale']; ?>   </td>
              <td align="right" valign="top" class='normal'  > <font color="#FF0000"> 
                </font>Payment Mode : </td>
              <td align="left" valign="top" class='normal'> 
<input type='hidden' name='business_payment_mode' maxlength=255 class='text180'  value="<?php echo $datarow['business_payment_mode']; ?>">
<?php echo $datarow['business_payment_mode']; ?> </td>

            </tr>
	    <tr> 
              <td align="right" valign="top" class='normal' colspan=4>&nbsp; </td>
            </tr>

		<tr> 
              <td align="center" valign="top" class='header2' colspan=4 > 
                Line of Business</td>
            </tr>

<script language='Javascript' src='javascripts/default.js'></script>
<script language='Javascript'>
function CLine(z)
{
        var _FRM = document._FRM;
        //var x    = _FRM.business_capital_investment.value;
if (z=='New') {
	if(isDigit(_FRM.business_capital_investment.value)==false)
        {
                alert("Please add a valid amount!");
		_FRM.business_capital_investment.focus();
                return false;
        }else if (_FRM.business_capital_investment.value==0) {
		alert("Please add a valid amount!");
                _FRM.business_capital_investment.focus();
                return false;
	}
} else {


	if(isDigit(_FRM.gross_sale.value)==false)
        {
                alert("Please add a valid amount!");
                _FRM.gross_sale.focus();
                return false;
        }else if (_FRM.gross_sale.value==0) {
                alert("Please add a valid amount!");
                _FRM.gross_sale.focus();
                return false;
        }
}

        _FRM.listings_line_of_business_mode.value='add';
        _FRM.submit();
	return true;
	//}
}
</script>

	   <tr> 
              <td align="right" valign="top" class='normal' colspan=4>&nbsp; </td>
            </tr>
	   <tr> 
              <td align="center" valign="top" class='normal' colspan=4 > 
	         <!--// start listing of line of business //-->
		<table border=1 cellspacing=0 cellpadding=0 width=90%>
		<?php
		
                       echo get_line_of_business_header();
		
		include "includes/lineofbusiness.php";
		$listings_line_of_business = isset($listings_line_of_business) ? $listings_line_of_business : ''; //2008.05.08
		$listings_line_of_business_ctr = isset($listings_line_of_business_ctr) ? $listings_line_of_business_ctr : '';
		?>
			
		</table>
		<input type='hidden' name='listings_line_of_business'     value='<?php echo $listings_line_of_business;?>'>
		<input type='hidden' name='listings_line_of_business_mode'  >
		<input type='hidden' name='listings_line_of_business_code'  >
		<input type='hidden' name='listings_line_of_business_buff'>
		<input type='hidden' name='listings_line_of_business_ctr' value='<?php echo $listings_line_of_business_ctr;?>'>
	         <!--// end   listing of line of business //-->
	      </td>
            </tr>
           <tr>
              <td align="right" valign="top" class='normal' colspan=4>&nbsp; </td>
            </tr>

	<tr>
		<td align="center" valign="top" class='header2' colspan=4>Business Requirements </td>
	</tr>
           <tr>
              <td align="right" valign="top" class='normal' colspan=4>&nbsp; </td>
            </tr>
	<?php
	//if ($owner_id<>'' and $business_id<>'') { 
	$ic = 0;
	$col1 = 0;
		$getreq	= SelectDataWhere($dbtype,$dbLink,"ebpls_buss_requirements",
			 		"where recstatus='A' and reqindicator='1' and permit_type='Business'");
		$gt = NumRows($dbtype,$getreq);

		while ($ic<$gt)
		{
		    while ($getr = FetchRow($dbtype,$getreq))
                        {
			$ic++;
				if ($col1==0) {
include'tablecolor-inc.php';
print "<tr bgcolor='$varcolor'>\n";

				}

                          $check1 = SelectDataWhere($dbtype,$dbLink,"havereq",
					" where owner_id=$owner_id
                                   	and business_id=$business_id 
					and reqid=$getr[0]");
				$hreq = NumRows($dbtype,$check1);
				if ($hreq==0) {
                                                                                                               
                                	$insertreq = InsertQuery($dbtype,$dbLink,"havereq","",
                                                "'', $getr[0], $owner_id, $business_id,0");
					$ch='UNCHECKED';
					$gethr[4]=0;
				} else {
				$gethr = FetchRow($dbtype,$check1);
					if ($gethr[4]==1) {
						$ch='CHECKED';
					} else {
						$ch='UNCHECKED';
					}
				}	
$getr[1]=stripslashes($getr[1]);
				print "	
				<td align=right width=5%><input type=hidden name=colre[$ic] 
				value=$getr[0]>&nbsp 
				<input type=checkbox name=x[$ic] $ch></td><td align=left width=23%>$getr[1]
				</td>";

			$col1=$col1+1;
			$arr_id[$i++] = $ic;
				if ($col1>1) {
				print "</tr><tr>";
				$col1=0;
				}
				
		}		
			
		}
	//	}
	
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
              <td align="right" valign="top" class='normal' colspan=4>&nbsp;
<?php
 $list_id     = @join(':',$arr_id);
        print "&nbsp;<a href='javascript:checkAll(\"$list_id\")'
                class='subnavwhite' onClick=''>Check All</a>\n";
	print "&nbsp;<a href='javascript:clearAll(\"$list_id\")'
                class='subnavwhite' onClick=''>Clear All</a>\n<br>";


?>

 </td>
            </tr>
	    <tr> 
              <td align="center" valign="top" class='header2' colspan=4 > 
                &nbsp;</td>
            </tr>
            <tr> 
              <td align="right" valign="top" class='normal' colspan=4>&nbsp; </td>
              <!--// end of the Franchise permit information //-->
            <tr> 
              <td align="center" valign="top" class='normal' colspan=4> &nbsp; 
                <!--// input type='BUTTON' name='_BACK' onClick='javascript:history.go(-1)' value='B A C K' //-->
<?php
	if ($owner_id>0 and $business_id>0) {
?>                
                &nbsp; <input type='submit' name='PROCESS'  value='SAVE'> 
<?php
}
?>                
                &nbsp; <input type='button' name='_CANCEL' value='CANCEL' onClick="CancelAll();">
<?php
	if ($owner_id>0 and $business_id>0) {
?>
 &nbsp; <input type='button' name='printrec' value='PRINT' onClick='javascript:AppRec("<?php echo $owner_id; ?>","<?php echo $business_id; ?>");' >
<?php
}

		if ($bpas==1 ||  $ulev==6 || $ulev==7 and $owner_id>0 and $business_id>0) {
?>
			<input type=hidden name=go_assess>
		 &nbsp; <input type='submit' name='PROCESS'  value='ASSESSMENT' onclick='_FRM.go_assess.value=1;'>
<!--		 &nbsp; <input type='button' name='assbut'  value='ASSESSMENT' 
			onclick="parent.location='index.php?part=4&class_type=Permits&itemID_=4212&owner_id=<?php echo $owner_id; ?>&com=reassess&permit_type=<?php echo $permit_type; ?>&stat=<?php echo $stat; ?>&business_id=<?php echo $business_id; ?>&busItem=Business&istat=<?php echo $stat; ?>'">-->
<?php
		}
		
?>
              </td>
            </tr>


<script language='Javascript' src='javascripts/default.js'></script>
<script language='Javascript'>
function AppRec(x, y)
{

winpopup = window.open("apprec.php?owner_id=<?php echo $owner_id; ?>&pin=<?php echo $genpin; ?>&business_id=<?php echo $business_id; ?>");
//, 'width=300, height=300,toolbar=0,location=0,directories=0,menubar=0,resizable=0,scrollbars=1,status=1'");
}

function CancelAll()
{
// 	var _FRM = document._FRM;
// 	confcan = confirm("All data will be lost.");
// 	if (confcan==true) {
		parent.location ="index.php?part=4&class_type=Permits&itemID_=1221&permit_type=Business&busItem=Business&becancel=true&owner_id=<?php echo $owner_id; ?>&business_id=<?php echo $business_id; ?>";
//	return true;
//	}
}
</script>
	    	
          </table>
	</form>
	</td>
       </tr>

	<tr><td align="center" valign="center" class='title' height=10></td></tr>
</table>
<!---// end of the table //-->
</form>
<script language='Javascript'>

function deleteLineOfBusiness(kud)
{
        var _FRM = document._FRM;

        _FRM.listings_line_of_business_mode.value='delete';
        _FRM.listings_line_of_business_code.value=kud;
        _FRM.submit();
}


</script>
</div>
</body></html>


<?php
function set_line_of_business($buffline1,$buffline2,$buffline3=0,$buffline4=0)
{
?>


<?php
}
function get_line_of_business_header()
{
?>
	<tr>
<!--		      <td align='center' valign='top' class='normalbold'>ID</td>
		      <td align='center' valign='top' class='normalbold'>Business Code</td>-->
		      <td align='center' valign='top' class='normalbold'>Line of Business</td>
		      <td align='center' valign='top' class='normalbold'>Capital Investment</td>
		      <td align='center' valign='top' class='normalbold'>Last Year's Gross</td>
		      <td align='center' valign='top' class='normalbold'>Action</td>
	</tr>
                                                                                                               
<?php
}
$ngros = isset($ngros) ? $ngros : 0 ; //2008.05.08
if ($ngros==1) {
	 $delperm = mysql_query("delete from ebpls_business_enterprise_permit where
	             					owner_id=$owner_id and business_id=$business_id and active =1
                                  and transaction='$stat' and application_date like '$yearnow%'") or die ("!");
                 $updperm = mysql_query("update ebpls_business_enterprise_permit set active=1 where
	             					owner_id=$owner_id and business_id=$business_id 
                                   order by  business_permit_id desc limit 1")or die (mysql_error());
}	             
 


