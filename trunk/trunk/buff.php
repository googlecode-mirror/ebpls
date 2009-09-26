<form name='_FRM' >
<input type='text' name='lineofbusiness_1' ><br>
<input type='text' name='lineofbusiness_1' ><br>
<input type='text' name='lineofbusiness_1' ><br>

<input type='text' name='lineofbusiness_2' ><br>
<input type='text' name='lineofbusiness_2' ><br>

<input type='text' name='lineofbusiness_3' ><br>


<input type='hidden' name='lineofbusiness_ctr' value=3><br>


<input type='button' onClick='checkLineOfBusiness()' value=' CHK '>
<br>
<script language='Javascript' src='javascripts/default.js'></script>
<script language='Javascript'>

function checkLineOfBusiness()
{
	var _FRM = document._FRM;
	var linemax = _FRM.lineofbusiness_ctr.value;
	
	for(var i=1 ; i<= linemax;i++)
	{

		var line = ( eval('_FRM.lineofbusiness_' + i + '.length'));
		
		if(line && isDigit(line))
		{
			//--- input is greater than 1
			for(var j=0;j<line;j++)
			{
				var _val =  ( eval('_FRM.lineofbusiness_' + i + '[' + j + '].value'));
				if(isDigit(_val) == false)
				{
					alert('Please input a valid Line Of Business!');
					eval('_FRM.lineofbusiness_' + i + '[' + j + '].focus();');
					return false;
					
				}
			}
		}
		else
		{
			//--- only one input
			var _val =  ( eval('_FRM.lineofbusiness_' + i + '.value'));
			if(isDigit(_val) == false)
			{
				alert('Please input a valid Line Of Business!');
				eval('_FRM.lineofbusiness_' + i + '.focus();');
				return false;

			}
			
		}
		
	}
}

</script>