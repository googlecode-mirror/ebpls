<html>
<head>
<title>Formula Creator</title>
</head>
<body>
<form name="_FORMULA_CREATE">
<input type=hidden name="param_count" value="0">
<div id="formulaArea"></div>
<BR>
Formula = <input type="text" name="formula" value="" size=32 maxlength=64>
<BR><BR>
<input type="button" value="Add Parameter" onClick="javascript:addParameter('Description Here');">&nbsp;
<input type="button" value="Check Formula" onClick="javascript:checkFormula();">
</form>

<script language="JavaScript">

function deleteParameterX( nIndex ) {
	
	var nCount = parseInt(document._FORMULA_CREATE.param_count.value);				
	var strParameter = ''
	var tmp_value = ''
				
	if ( nCount >= 1 ) {
		
		var strValues = new Array(nCount-1);			
		var j = 0	
		for ( i = 1 ; i <= nCount; i++  ) {
		
			if ( document._FORMULA_CREATE['x'+i] != undefined && nIndex!=i ) {
				
				strValues[j] = document._FORMULA_CREATE['x' + i ].value;				
				j++;
								
			}
	
		}
		
		for ( var i = 1 ; i <= nCount - 1; i++ ) {
			
			strParameter = strParameter + 'x' + i + "&nbsp;&nbsp;<input type=text size=25 maxlength=128 name=x" + i + " value='" + strValues[i-1] + "'>&nbsp;<input type=button value=' x ' onClick='javascript:deleteParameterX(" + i + ");'><br>"			
			
		}
		
		document._FORMULA_CREATE.param_count.value = nCount - 1;	
		document.all.formulaArea.innerHTML = strParameter;					
			
	}
	
	
	
	
	
}


function addParameter( value ) {
			
	var nCount = parseInt(document._FORMULA_CREATE.param_count.value) + 1;		
	
	if( nCount > 6 ) return;
	
	document._FORMULA_CREATE.param_count.value = nCount;
	
	var strParameter = ''
	var tmp_value
	for ( var i = 1 ; i <= nCount; i++ ) {
	
		if ( document._FORMULA_CREATE['x'+i] != undefined ) {
			tmp_value = document._FORMULA_CREATE['x'+i].value;	
		}
		
		if ( nCount == i ) {
			tmp_value = value;
		}
		
		strParameter = strParameter + 'x' + i + "&nbsp;&nbsp;<input type=text size=25 maxlength=128 name=x" + i + " value='" + tmp_value + "'>&nbsp;<input type=button value=' x ' onClick='javascript:deleteParameterX(" + i + ");'><br>"
		
	}		
	
	document.all.formulaArea.innerHTML = strParameter;
	
}


function checkFormula() {
	
	if ( document._FORMULA_CREATE.formula.value == '' ) {
		
		alert('Formula empty, please input valid formula.');
		return;	
		
	}
	
	var nCount = parseInt(document._FORMULA_CREATE.param_count.value);
	var strSampleVars = "";
	var nResult = 0;
	
	for ( var i = 1 ; i <= nCount; i++ ) {
			
		strSampleVars += strSampleVars + 'x'+ i +'=' + Math.round((Math.random()%100)*10) + ';';

	}
	
	strSampleVars += document._FORMULA_CREATE.formula.value + ';';
		
	
	
	try {
		
		//alert(strSampleVars);	
		nResult = eval(strSampleVars);		
		alert('formula valid : ' + nResult);		
		
	} catch ( er ) {
		
		alert('formula error ' + er.description );
		
	}
	
}

</script>
</body>
</html>