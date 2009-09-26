	
	<input type='hidden' name='mode'>
	<table width =500  border=0 cellpadding=1 cellspacing=1 align =center>
	  <!--// start of the owner information //-->
	  
	<tr> 
	    <td align="right"></td>
	   
	    <td align="left" >&nbsp; <?php //echo $permit_code; ?>

	    <td align="left" >Application Date :  </td>
        
	    <td align="left" >&nbsp; <?php echo $permit_date; ?>  </td>
	</tr>

	
	<tr> 
	    <td align="right">Position Applied : </td>

	    <td align="left" >&nbsp; <?php echo $pos_app; ?>
		<input type="hidden" name="pos_app" value="<? echo $pos_app;?>">
		</td>

	    <td align="right"> </font>&nbsp </td>

	    <td align="left" >&nbsp; </td>
	</tr>
	
	<tr> 
	    <td align="right"> Employer Name : </td>

	    <td align="left" >&nbsp; <?php echo $employer_name; ?>
		<input type="hidden" name="employer_business" value="<? echo $employer_name;?>">
		</td>

	    <td align="left" ></td>

	    <td align="left" >&nbsp;<?php //echo $trade_name; ?>  </td>

       </tr>
	

</table>

<!--</form>
</div>
</body>
</html>-->
