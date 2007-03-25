<?php
require_once "includes/variables.php";


$getdata = mysql_query("select * from ebpls_owner a, ebpls_barangay b, ebpls_zone c,
						ebpls_district d, ebpls_city_municipality e, ebpls_province f,
						ebpls_zip g where owner_id = $owner_id and a.owner_barangay_code=b.barangay_code and
			a.owner_zone_code=c.zone_code and a.owner_district_code=d.district_code and
			a.owner_city_code=e.city_municipality_code and a.owner_province_code=f.province_code and
			a.owner_city_code=g.upper");    

		
			
			
$getda=FetchArray($dbtype,$getdata);


if ($getda[zone_desc]=='') {
	
	$getdata = mysql_query("select * from ebpls_owner a, ebpls_barangay b, 
						ebpls_district d, ebpls_city_municipality e, ebpls_province f,
						ebpls_zip g where owner_id = $owner_id and a.owner_barangay_code=b.barangay_code and
			 a.owner_district_code=d.district_code and
			a.owner_city_code=e.city_municipality_code and a.owner_province_code=f.province_code and
			a.owner_city_code=g.upper");  
			 $getda=FetchArray($dbtype,$getdata); 
}
		
			
	



$add = "$getda[owner_street] $getda[barangay_desc] $getda[zone_desc] ,$getda[district_desc] 
		$getda[city_municipality_desc], $getda[province_desc], $getda[zip_code]";





?>
<form name="_FRM" method="POST" action='index.php?part=4&class_type=Permits&newpred=<?php echo $newpred; ?>&noregfee=<?php echo $noregfee; ?>&itemID_=5212&permit_type=Business&owner_id=<?php echo $owner_id; ?>&business_id=<?php echo $business_id; ?>&stat=<?php echo $stat; ?>&busItem=Business&mtopsearch=SEARCH' >
<link rel="stylesheet" href="stylesheets/default.css" type="text/css"/>
<script language='Javascript' src='javascripts/default.js'></script>
<div align="left">
<!---// start of the table //-->
<table border=0 cellspacing=0 cellpadding=0   width='100%' align=left>
	<tr><td align="center" valign="center" class='header' > Business Enterprise Permit Approval</td></tr>
	<tr><td align="center" valign="center" class='normal' height=10>&nbsp;</td></tr>
	<tr>
		<td align="center" valign="center" class='title'>
		<form name="_FRM" method="POST" action="" onSubmit="">
		<input type=hidden name=newpred value="<?php echo $newpred; ?>">
<input type=hidden name=noregfee value="<?php echo $noregfee; ?>">			
		<table border=0 cellspacing=1 cellpadding=1 width='100%' align=center>
        	<!--// start of the owner information //-->
            <tr>
			<td width=50% valign=top>
			<table border=0 cellspacing=0 cellpadding=0   width='100%'>
        	<tr> 
            	<td align="center" valign="top" class='header2' colspan=4 > 
                	Owner Information</td>
            </tr>
            <tr> 
	        	<td align="right" valign="top" class='normal' colspan=4>&nbsp; </td>
            </tr>
            <tr> 
            	<td align="right" valign="top" class='normal' colspan=1> &nbsp; 
                </td>
              	<td align="left" valign="top" class='normal' colspan=3>&nbsp;
		
					<input type='hidden' name='owner_id' maxlength=25  value="<?php echo $owner_id; ?>"> 
              	</td>
          	</tr>
<?php
$getown = SelectDataWhere($dbtype,$dbLink,"ebpls_owner","where owner_id=$owner_id");
$getit = FetchRow($dbtype,$getown);
$owner_first_name=$getit[1];
$owner_middle_name=$getit[2];
$owner_last_name=$getit[3];
?>
			<tr> 
            	<td align="right" valign="top" class='normal' width=125> Name : </td>
            	<td align="left" valign="top" class='normal' width=250>&nbsp;
 <?php echo stripslashes($owner_last_name).", ". stripslashes($owner_middle_name)." ".stripslashes($owner_first_name); ?>
              	</td>
            </tr>
	    	<tr>
	    		<td align="right" valign="top" class='normal' width=125>Address : </td>
				<td>&nbsp; <?php echo stripslashes($add); ?></td>
	    	</tr>
	    	<tr>
	    		<td align="right" valign="top" class='normal' width=125>
                	Contact No.: </td>		
	    		<td> <?php echo $getda[owner_phone_no]; ?> </td>
	    	</tr>
	    	</table>
			</td>
            <!--// end of the owner information //-->
            <!--// start of the business permit information //-->
            <td width=50% valign=top>
            <table border=0 cellspacing=0 cellpadding=0   width='100%'>
            <tr> 
            	<td align="center" valign="top" class='header2' colspan=4 > 
                	Business Enterprise Information</td>
            </tr>
            <tr> 
            	<td align="right" valign="top" class='normal' colspan=4>&nbsp; </td>
            </tr>
	    <tr>
                <td align="right" valign="top" class='normal' colspan=4>&nbsp; </td>
            </tr>

           <!-- <tr> 
            	<td align="right" valign="top" class='normal'> &nbsp; </td>
            	<td align="left" valign="top" class='normal' >&nbsp;  </td>
            	<td align="right" valign="top" class='normal'  > &nbsp;</td>
            	<td align="left" valign="top" class='normal'>&nbsp; </td>
            </tr>-->
            <tr> 
            	<td align="right" valign="top" class='normal'> &nbsp; </td>
	      		<td align="right" valign="top" class='normal'> 
                	Business Name :  </td>
	<?php

		if ($business_id<>'') { 
		$res = SelectDataWhere($dbtype,$dbLink,"ebpls_business_enterprise",
			" where business_id=$business_id");
		$datarow = FetchArray($dbtype,$res);
		}
	?>

              	<td align="left" valign="top" class='normal'>&nbsp; <?php echo stripslashes($datarow[business_name]); ?> </td>
              	<td align="right" valign="top" class='normal'> &nbsp; </td>
            </tr>
         	<tr> 
         		<td align="right" valign="top" class='normal'> &nbsp; </td>
            	<td align="right" valign="top" class='normal' > Business Scale : 
            	</td>
            	<td align="left" valign="top" class='normal'>&nbsp; 
       				<?php echo $datarow[business_scale]; ?>
       			</td>
       		</tr>
       		<tr>
       			<td align="right" valign="top" class='normal'> &nbsp; </td>
              	<td align="right" valign="top" class='normal'  > 
                	Payment Mode : </td>
              	<td align="left" valign="top" class='normal'>&nbsp; 
	  				<?php echo $datarow[business_payment_mode]; ?></td>

            </tr>
            </table>
            </td>
            </tr>
	    	<tr> 
            	<td align="right" valign="top" class='normal' colspan=4>&nbsp; </td>
            </tr>
            </table>
            <table border=0 cellspacing=0 cellpadding=0   width='100%'>
			<tr> 
            	<td align="center" valign="top" class='header2' colspan=4 > 
                	Line of Business</td>
            </tr>
            <tr> 
            	<td align="right" valign="top" class='normal' colspan=4>&nbsp; </td>
            </tr>
	        <!--// start listing of line of business //-->
	

				<table border=1 cellspacing=0 cellpadding=0 width=90% align=center>
		 			<tr>
                    	<td align='center' valign='top' class='normalbold'>Description</td>
                    	<td align='center' valign='top' class='normalbold'>Capital Investment</td>
                    	<td align='center' valign='top' class='normalbold'>Last Year's Gross</td>
        			</tr>
	
				<?php


				//get bus nature		
			  	$getnat = SelectMultiTable($dbtype,$dbLink,"tempbusnature",
						"bus_nature, cap_inv, last_yr",
        		        	 	"where owner_id=$owner_id and 
						business_id=$business_id and active=1");
				while ($getit = FetchRow($dbtype,$getnat)){
       	        		include'tablecolor-inc.php';
				print "<tr bgcolor='$varcolor'>\n";
           				$cap_inv = number_format($getit[1],2);
           				$last_yr = number_format($getit[2],2);
          				print "<td align='center' valign='top' class='normal'>$getit[0]</td>\n";
               			print "<td align='center' valign='top' class='normal'>$cap_inv</td>\n";
               			print "<td align='center' valign='top' class='normal'>$last_yr</td>\n";
				        print "</tr>\n";
				}	
              			
				print "</table><br>";

$chkbacktax = SelectDataWhere($dbtype,$dbLink,"ebpls_buss_preference","");
$chkbacktax = FetchArray($dbtype,$chkbacktax);
 $pmode=$datarow[business_payment_mode];
if ($chkbacktax[sbacktaxes]=='1' and $stat=='Retire') {
$tftnum=1;
$tft = '';
require "includes/headerassess.php";
require_once "includes/assessment.php";
$total_tax_compute = $grandamt;
					$howmany = $df;
?>
<input type='hidden' name='wala_lang'>
<?php
} else {
?>
	<table width=100% align = left border=0><br><br>
		<tr><td align=center>
				<?php
				
				$gettag=SelectDataWhere($dbtype,$dbLink,
					"ebpls_buss_preference","");
				$gettag=FetchArray($dbtype,$gettag);
                                                                                                              
				if ($gettag[sassess]=='') {
					$tftnum = 1;
				        //$tft = ' and c.taxfeetype=1'; // or c.taxfeetype=4';
				        $htag = 'Assessment';
				        require "includes/headerassess.php";
				        $totexempt=0;
				        require "includes/assessment.php";
					$total_tax_compute = $grandamt;
					$howmany = $df;
					
                         if ($noregfee<>1) {                                         
				        $tft =' and c.taxfeetype<>1';// or c.taxfeetype<>4';
				        $htag = 'General Charges';
				        require "includes/headerassess.php";
				        require "includes/feeassess.php";
			        	}

				} else {
					 $tftnum=1;
				         $htag = 'Assessment';
					 $tft = '';

					require "includes/headerassess.php";
					include "includes/assessment.php";
					$total_tax_compute = $grandamt;
				}
			}
if ($gettag[sassess]=='1') {
$te=0;
//$totfee=0;
} else {
$te = $totfee;
}				
			
$grandamt = $total_tax_compute + $total_sf_compute + $fee;
//$grandamt=$grandamt+$totfee-$te;
$grand=$grandamt;				
				
				?>
<table border=0 width=100%>
<tr><td width=25%></td>
<td align=right width=50%><b>Total Taxes, Fees and Other Charges :</b></td>
<td align=right bgcolor=blue>
<font color=white>Php &nbsp;<?php echo $ga=number_format($grand,2);$vart = $grand; ?></font></td></tr>

				<?php



	require_once "includes/paymentsched.php";
print "<br>";

	
?>

</table>

<table border=0 cellspacing=1 cellpadding=1 width=100%><br>

	<tr>
    	<td align="center" valign="top" class='header2' colspan=4 >
        	Approval Decision</td>
	</tr>
</table>
<br>	
<table border=0 cellspacing=1 cellpadding=1 width='90%' align=center>
	<tr>
    </tr>
	<tr> 
		<?php 

		$getapp = SelectDataWhere($dbtype,$dbLink,"ebpls_buss_approve", 
					"where owner_id = $owner_id and 
					 business_id=$business_id");
		$getit = FetchRow($dbtype,$getapp);
		$getnum = NumRows($dbtype,$getapp);
		
		if ($getit[3]==0 and $getit[3]<>'') {
			$decsel = 'selected';
			
		} else {
			$appsel ='selected';
			
		}
		
	
		?>
		<td> Application Status: </td>	
		<td> <select name=decide> 
		
	
			 <option value=1 <?php echo $appsel; ?>>Approved</option>
			 <option value=0 <?php echo $decsel; ?>>Rejected</option>
		     </select>
		</td>
		<td> Comments: </td>
		<td> <textarea name=dec_comment rows=6 cols=15><?php echo stripslashes($getit[4]); ?></textarea> </td>
	</tr>
	
	

</table><br><br>
<table border=0 width=90% align=center>
	<tr>
		<td align="center" valign="top" class='normal' colspan=4> &nbsp; 
        	&nbsp; <input type='submit' name='PROCESS'  value='SAVE'>
			<input type=button value='CANCEL' onClick="history.go(-1)">
			<input type='reset' name='PROCESS'  value='RESET'>
		<?php
			if ($bpay==1 ||  $ulev==6 || $ulev==7) {
		?>
			 <input type=submit name=PROCESS value='PAYMENT'>
		<?php
			}
		?>
        </td>
    </tr>
	    

</table>
</form>
</div>
</html>
