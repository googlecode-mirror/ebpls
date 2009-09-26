<?php
?>
<script language='Javascript' src='javascripts/default.js'></script>
<script language="Javascript">
function ValidMotor(z)
{
		var x = document._FRM;
		
		if (isBlank(x.mmodel.value)) {
			alert ("Invalid Motor Model");
			x.mmodel.focus();
			x.mmodel.select();
			return false;
		}
		
		if (x.mmodel.value.length>15) {
			alert ("Motor Model Exceeds Max Length");
			x.mmodel.focus();
			x.mmodel.select();
			return false;
		}
		
		if (isBlank(x.mnum.value)) {
			alert ("Invalid Motor Number");
			x.mnum.focus();
			x.mnum.select();
			return false;
		}
		
		if (x.mnum.value.length>15) {
			alert ("Motor Number Exceeds Max Length");
			x.mnum.focus();
			x.mnum.select();
			return false;
		}

		if (isBlank(x.cnum.value)) {
			alert ("Invalid Chasis Number");
			x.cnum.focus();
			x.cnum.select();
			return false;
		}
		
		if (x.cnum.value.length>15) {
			alert ("Chasis Number Exceeds Max Length");
			x.cnum.focus();
			x.cnum.select();
			return false;
		}

		if (isBlank(x.pnum.value)) {
			alert ("Invalid Plate Number");
			x.pnum.focus();
			x.pnum.select();
			return false;
		}
		
		if (x.pnum.value.length>6) {
			alert ("Plate Number Exceeds Max Length");
			x.pnum.focus();
			x.pnum.select();
			return false;
		}

		if (isBlank(x.bnum.value)) {
			alert ("Invalid Body Number");
			x.bnum.focus();
			x.bnum.select();
			return false;
		}
		
		if (x.bnum.value.length>5) {
			alert ("Body Number Exceeds Max Length");
			x.bnum.focus();
			x.bnum.select();
			return false;
		}

		if (isBlank(x.bcolor.value)) {
			alert ("Invalid Body Color");
			x.bcolor.focus();
			x.bcolor.select();
			return false;
			
		}
		
		if (x.bcolor.value.length>10) {
			alert ("Body Color Exceeds Max Length");
			x.bcolor.focus();
			x.bcolor.select();
			return false;
		}

		if (isBlank(x.route.value)) {
			alert ("Invalid Route");
			x.route.focus();
			x.route.select();
			return false;
		}
		
		if (x.route.value.length>35) {
			alert ("Route Exceeds Max Length");
			x.route.focus();
			x.route.select();
			return false;
		}

		if (isBlank(x.ltype.value)) {
			alert ("Invalid Line Type");
			x.ltype.focus();
			x.ltype.select();
			return false;
		}
		
		if (x.ltype.value.length>15) {
			alert ("Line Type Exceeds Max Length");
			x.ltype.focus();
			x.ltype.select();
			return false;
		}

		if (isBlank(x.ltoreg.value)) {
			alert ("Invalid LTO Registration");
			x.ltoreg.focus();
			x.ltoreg.select();
			return false;
		}
		
		if (x.ltoreg.value.length>25) {
			alert ("LTO Registration Exceeds Max Length");
			x.ltoreg.focus();
			x.ltoreg.select();
			return false;
		}

		if (isBlank(x.cro.value)) {
			alert ("Invalid Certificate of Registration");
			x.cro.focus();
			x.cro.select();
			return false;
		}
		
		if (x.cro.value.length>25) {
			alert ("Certificate of Registration Exceeds Max Length");
			x.cro.focus();
			x.cro.select();
			return false;
		}
		x.addveh.value=z
		x.submit();
		return true;
}

function clearall()
{
}

</script>

&nbsp;
									<input type='hidden' name='vehicles_total' value='<?php echo intval($v_total);?>'>
									<input type='hidden' name='mot_id'>
                                                                                <input type='hidden' name='mot_id_mode'>
                                                                                <input type='hidden' name='motorize_oper_id' value='<?php echo $motorize_oper_id;?>'>
<table border=0>
	<tr>
		<td>
		Motor Model/Brand:
		</td>

		<td>
		<input type=hidden name=mid value=<?php echo $mid; ?>>
		<input type=text name=mmodel value='<?php echo $mmodel; ?>' maxlength=15>
		</td>
		
		<td>
		Motor No.:
		</td>
		<td>
		<input type=text name=mnum value='<?php echo $mnum; ?>' maxlength=15>
		</td>
		
	</tr>
	
	<tr>
		<td>
		Chassis No.:
		</td>
		<td>
		<input type=text name=cnum value='<?php echo $cnum; ?>' maxlength=15>
		</td>
		
		<td>
		Plate No.:
		</td>
		<td>
		<input type=text name=pnum value='<?php echo $pnum; ?>' maxlength=6>
		</td>
	</tr>
	
	<tr>
		<td>
		Body No.:
		</td>
		<td>
		<input type==text name=bnum value='<?php echo $bnum; ?>' maxlength=5>
		</td>
		
		 <td>
                Body Color :
                 </td>
                <td>
                <input type==text name=bcolor value='<?php echo $bcolor; ?>' maxlength=10>
                </td>
		
		
	</tr>


	
	<tr>
		<td>
		Route :
		 </td>
                <td>
                <input type==text name=route value='<?php echo $route; ?>' maxlength=35>
                </td>
                
                
             <td>
                Line Type :
                 </td>
                <td>
                <input type==text name=ltype value='<?php echo $ltype; ?>' maxlength=15>
                </td>    

	</tr>

	

	 <tr>
                <td>
                LTO Registration No. :
                 </td>
                <td>
                <input type==text name=ltoreg value='<?php echo $ltoreg; ?>' maxlength=25>
                </td>
   
                <td>
                Certificate of Registration No. :
                 </td>
                <td>
                <input type==text name=cro value='<?php echo $cro; ?>' maxlength=25>
                </td>
                                                                                                                            
        </tr>

</table>
<table align=center>



	<tr>
		<td>

<input type= hidden name='addveh' value=<?php echo $buttag; ?>>
<input type= button name='addveh1' value="<?php echo $buttag; ?>" onclick='ValidMotor("<?php echo $buttag; ?>");'>
<input type= reset name='clearveh' value=<?php echo $buttag1; ?>>
<input type= button name='Search' value="Search" onclick='_FRM.addveh.value="transfer"; _FRM.submit();'>
		</td>
	</tr>
	</table>
