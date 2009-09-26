<?php
function updateConfig($arrVarNames='a000',$arrNewValues=0,$strConfigFile="setup/setting.php")
{
	if (count($arrVarNames) == count($arrNewValues)) {
		$arrConfigBuffer = NULL;
		// buffer the config file!
		$fp1 = fopen($strConfigFile,"rb");
		$arrNeedle = array(";","\r","\t","\n");
		while (!feof($fp1)) {
			$strBuffer = fgets($fp1, 4096);
			if (stristr($strBuffer,'$') && stristr($strBuffer,'=') && stristr($strBuffer,';')) {
				$strBuffer = str_replace($arrNeedle,"",$strBuffer);
				$arrTempBuffer = split('=',$strBuffer);
				$strVarName = str_replace("\$","",array_shift($arrTempBuffer));
				$strCurrValue = implode("=",$arrTempBuffer);
				$arrConfigBuffer[trim($strVarName)] = trim($strCurrValue);
			}
		}
		fclose($fp1);
		// update the values of the config buffer
		if (is_array($arrVarNames)) {
			foreach($arrVarNames as $strAssocName) {
				$arrConfigBuffer[$strAssocName] = array_shift($arrNewValues);
			}
		} else {
			$arrConfigBuffer[$arrVarNames] = $arrNewValues;
		}
		echo "<== REACHED THIS PLACE ==><BR>";
		// re-write the file
		$fp2 = fopen($strConfigFile,"wb");
		fwrite($fp2,"<?php\r\n\r\n");
		foreach ($arrConfigBuffer as $key => $value) {
			if (stristr($value,' //')) {
				$value = str_replace(' //',';/* ',$value);
				$value .= ' */';
			} elseif ((stristr($value,'/*') && stristr($value,'*/'))) {
				$value = str_replace('/*',';/*',$value);
			} else {
				$value = $value.";";
			}
			fwrite($fp2,"\$$key = $value\r\n");
		}
		fwrite($fp2,"\r\n?>");
		fclose($fp2);
		return 1;
	} else {
		return FALSE;
	}
}
?>
