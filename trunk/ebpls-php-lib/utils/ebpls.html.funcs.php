<?


function createTextField( $name, $value, $label, $class = NULL, $bIsEditable = true ){

	$value = htmlentities($value);
	if ($bIsEditable){
		$extra = "";
	} else {
		$extra = " disabled ";
	}
	return "<input type=text name=\"$name\" value=\"$value\" class=\"$class\" $extra>";

}

function createHiddenInput( $name, $value ){

	$value = htmlentities($value);
	
	return "<input type=hidden name=\"$name\" value=\"$value\">";

}

function createCheckBox( $name, $value, $label, $bChecked, $style = NULL ){

	$value = htmlentities($value);

	return "<input type=checkbox name=\"$name\" " . (($bChecked)?"checked":"") . " value=\"$value\" class=\"$style\">$label";

}

function createSelect( $name, $options, $selected, $script = null, $class_style = null, $bEditable = true ) {
	
	
	if ($bEditable){
		$extra = "";
	} else {
		$extra = " disabled";
	}
		
	$str = "\n<select name=\"$name\" onChange=\"$script\" class=\"$class_style\" $extra>\n";
	$str .= "<option value=\"0\">---\n";

	if( is_array($options) ) {

		foreach( $options as $key => $value ) {

			if ( $key == "" ) {
				$value = $key;
			}

			$key = htmlentities($key);
			$value = htmlentities($value);						
			
			if ( trim($selected) == trim($key) ){				
				$str .= "<option value=\"$key\" selected>$value\n";
			}else{
				$str .= "<option value=\"$key\">$value\n";
			}

		}

	}

	$str .= "\n</select>\n";

	return $str;

}

function createMultipleSelect( $name, $options, $selected, $script = null, $class_style = null ) {

	$str = "\n<select name=\"$name\" multiple onChange=\"$script\" class=\"$class_style\" height=400 width=200>\n";
	$str .= "<option value=0>---\n";

	if( is_array($options) ) {

		foreach( $options as $key => $value ) {

			if ( $key == "" ) $value = $key;

			$key = htmlentities($key);
			$value = htmlentities($value);

			if ( $selected == $key ){
				$str .= "<option value=\"$key\" selected>$value\n";
			}else{
				$str .= "<option value=\"$key\">$value\n";
			}

		}

	}

	$str .= "\n</select>\n";

	return $str;

}

function createDateControl( $form, $name, $date, $bRequireAll = true ) {

$str_script_id = uniqid("datecontrol");
ereg("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})", $date, $date_arr );
$yr = $date_arr[1];
if ( $yr == "" ) $yr = 0;
$mo = $date_arr[2];
if ( $mo == "" ) $mo = 0;
$dy = $date_arr[3];
if ( $dy == "" ) $dy = 0;

if ( $date != "" ) {
	$date_val = "$yr-$mo-$dy";
}

// create month
$strDateControl = "";
$strDateControl .= "<input type=hidden name=$name value='$date_val'>";
for ( $i=1; $i<=12; $i++) {
	$months[$i]= date("M", mktime(0,0,0,$i,1,2004));
}
$strDateControl .= createSelect( $name . "_month", $months, $mo, "javascript:$str_script_id();");


$strDateControl .= "&nbsp;&nbsp;";
for ( $i = 1; $i <=31 ; $i++ ) {
	$days[$i] = $i;
}
$strDateControl .= createSelect( $name . "_days", $days, $dy, "javascript:$str_script_id();");

$strDateControl .= "&nbsp;&nbsp;";
for ( $i = date("Y"); $i >= 1920 ; $i-- ) {
	$years[$i] = $i;
}
$strDateControl .= createSelect( $name . "_year", $years, $yr, "javascript:$str_script_id();");

$strDateControl .= "<script language='Javascript'><!--\n";
$strDateControl .= "function $str_script_id(){";
$strScript = "var j; var frm = $form;\n";
$strScript .= "var days = 0;\n";
$strScript .= "var mo = frm.$name" . "_month.value;\n";
$strScript .= "var form_dy_select = frm.$name" . "_days;\n";
$strScript .= "var form_yr_select = frm.$name" . "_year;\n";
$strScript .= "var form_mo_select = frm.$name" . "_month;\n";
$strScript .= "var form_bdate = frm.$name;\n";
$strScript .= "var yr = frm.$name" . "_year.value;\n";
$strScript .= "if ( mo == 1 || mo == 3 || mo == 5 || mo == 7 || mo == 8 || mo == 10 || mo == 12 ) { \n";
$strScript .= "		days = 31;\n";
$strScript .= "} else if ( mo == 2 ) { // check if leap year\n";
$strScript .= "		days = ((yr%4)==0)?29:28;\n";
$strScript .= "} else { \n";
$strScript .= "		days = 30;\n";
$strScript .= "}\n";
$strScript .= "var selectedIndex = form_dy_select.selectedIndex\n";
$strScript .= "while ( form_dy_select.options.length > 0 ){\n";
$strScript .= "		form_dy_select.remove(0);\n";
$strScript .= "}\n";
$strScript .= " var elem = document.createElement(\"OPTION\");\n";
$strScript .= " elem.value = 0;";
$strScript .= " elem.text = '----';";
$strScript .= "form_dy_select.options.add(elem,i);\n";
$strScript .= "for (var i = 1; i <= days; i++ ){\n";
$strScript .= " var elem = document.createElement(\"OPTION\");\n";
$strScript .= " elem.value = i;";
$strScript .= " elem.text = i;";
$strScript .= "form_dy_select.options.add(elem,i);\n";
$strScript .= "}\n";
$strScript .= " if ( form_dy_select.options.length > selectedIndex ) { form_dy_select.selectedIndex = selectedIndex; }\n";
$strScript .= " else { form_dy_select.selectedIndex = 0; }\n";
$strScript .= "if ( form_mo_select.item(form_mo_select.selectedIndex).value.length < 2 ) form_mo_select.item(form_mo_select.selectedIndex).value = '0' + form_mo_select.item(form_mo_select.selectedIndex).value;";
$strScript .= "if ( form_dy_select.item(form_dy_select.selectedIndex).value.length < 2 ) form_dy_select.item(form_dy_select.selectedIndex).value = '0' + form_dy_select.item(form_dy_select.selectedIndex).value;";

if ($bRequireAll ) {

$strScript .= "if ( form_mo_select.selectedIndex == 0 || form_dy_select.selectedIndex == 0 || form_yr_select.selectedIndex == 0 ) form_bdate.value = '';";
$strScript .= "else form_bdate.value = ( form_yr_select.item(form_yr_select.selectedIndex).value + '-' + form_mo_select.item(form_mo_select.selectedIndex).value + '-' + form_dy_select.item(form_dy_select.selectedIndex).value );\n";

} else {

$strScript .= "form_bdate.value = '';\n";

// year
$strScript .= "if ( form_yr_select.selectedIndex != 0 ) form_bdate.value = form_yr_select.item(form_yr_select.selectedIndex).value + '-';\n";
$strScript .= "else form_bdate.value = '^[0-9]{4}-';\n";

// month only
$strScript .= "if ( form_mo_select.selectedIndex != 0 ) form_bdate.value = form_bdate.value + form_mo_select.item(form_mo_select.selectedIndex).value + '-';\n";
$strScript .= "else form_bdate.value = form_bdate.value + '[0-9]{2}-';\n";

// day
$strScript .= "if ( ( form_mo_select.selectedIndex != 0 || form_yr_select.selectedIndex != 0 ) && form_dy_select.selectedIndex != 0  ) form_bdate.value = form_bdate.value + form_dy_select.item(form_dy_select.selectedIndex).value;\n";
$strScript .= "else if ( form_dy_select.selectedIndex != 0  ) form_bdate.value = form_bdate.value + form_dy_select.item(form_dy_select.selectedIndex).value;\n";
$strScript .= "else form_bdate.value = form_bdate.value + '[0-9]{2}';\n";

}

//$strScript .= "alert(form_bdate.value);";
$strDateControl .= $strScript;

$strDateControl .= "}\n--></script>";

return $strDateControl;

}

function createFormElements( $report_elements  ) {

	global $bDebug;

	if ( !is_array($report_elements) ) return;

	unset($form_elements);
	foreach ( $report_elements as $key=>$value ) {

		$elemName = $key;
		$elemLabel = $value["label"];
		$elemType = $value["type"];

		//$arr_params = Array("dbLink"=>get_db_connection(), "bDebug"=>$bDebug );
		$arr_params = get_db_connection();
		$elemValuesFunction = $value["values_func"];

		if ( $elemValuesFunction != NULL ) {

			$elemValues = @call_user_func_array( $elemValuesFunction, $arr_params );

			//log_err("elemValuesFunction : $elemValuesFunction");

		} else {

			$elemValues = $value["values"];

		}

		$elemDefault = $value["default"];
		$elemRequired = $value["required"];

		switch($elemType){
			case "date":
				{
					$strControl =  createDateControl( "document._FRM", $elemName, $elemDefault, $elemRequired );

				} break;
			case "select":
				{

					$strControl = createSelect( $elemName, $elemValues, $elemDefault, $script, $class_style );

				} break;
			case "multiselect":
				{

					$strControl = createMultipleSelect ( $elemName, $elemValues, $elemDefault, $script, $class_style );

				} break;
			case "input":
				{

					$strControl = createTextField( $elemName, $elemValues, $elemLabel, $class );

				} break;
			case "checkbox":
				{

					$strControl = createCheckBox( $elemName, $elemValues, $elemLabel, $elemDefault );

				} break;
			default :
				{

					$strControl = "cant create control, elem type $elemType undefined.";

				} break;
		}

		$form_elements[] = Array( "label"=>$elemLabel, "required"=>$elemRequired, "control"=>$strControl );

	}

	return $form_elements;

}

function createFormValidateJavaScript( $form_name, $form_elements, $show_error = true, $error_message = "Please provide value for all required inputs." ){

	$strFuncName = uniqid("formValidate");

	$strScript = "<script language='javascript'>\n";

	$strScript .= "\nfunction $strFuncName(){\n\n";

	$strScript .= "var bRet = true;";

	foreach ( $form_elements as $key=>$value ) {

		$elemName = $key;
		$elemLabel = $value["label"];
		$elemType = $value["type"];
		$elemDefault = $value["default"];
		$elemRequired = $value["required"];
		$elemDataType = $value["datatype"];

		if ( $elemRequired ) {

			switch( $elemDataType ) {
				case "text":
					{
						$elemFormName = "$form_name.$key";

						// validate error if empty
						$strScript .= "if ( $elemFormName.value == '' ) bRet = false;\n";

					} break;
				case "int":
					{

						$elemFormName = "$form_name.$key";

						// validate error if empty
						$strScript .= "if ( !isDigit($elemFormName.value) ) bRet = false;\n";
					} break;
				case "array";
					{

						$elemFormName = "$form_name.$key";

						// validate error if no element is selected on array
						$strScript .= "if ( $elemFormName.selectedIndex == 0 ) bRet = false;\n";

					} break;
				case "date";
					{

						$elemFormName = "$form_name.$key";

						// validate error if empty
						$strScript .= "if ( $elemFormName.value == '' ) bRet = false;\n";

					} break;
				default :
					{


					}


			}



		}

	}

	if ( $show_error ) {

		$strScript .= "if ( !bRet ) { \n";
		$strScript .= " alert('" . addSlashes($error_message) . "')\n";
		$strScript .= "return bRet\n";
		$strScript .= "}\n";

	}

	$strScript .= "$form_name.submit();\n\n";
	$strScript .= "return bRet;\n";
	$strScript .= "\n}\n";
	$strScript .= "\n</script>\n";

	return Array("func_name"=>$strFuncName . "()", "code"=>$strScript);

}


function constructTemplate( $templates, $records, $is_file = true ) {

	if ( empty($templates) ) return NULL;

	if ( $is_file ) {
		
		$content = implode ('', file ($templates) );
		
	} else {
		
		$content = $templates;		
		
	}

	if ( is_array( $records ) && $content != "" ) {

		$i = 0;
		foreach ( $records as $r_key=>$r_value ) {

			$content = str_replace("[$r_key]", $r_value, $content );
			$content = str_replace("[$i]", $r_value, $content );

			$i++;

		}

	}	

	return $content;

}

?>

