<?

require_once("ebpls-php-lib/ebpls.database.funcs.php");
require_once("ebpls-php-lib/ebpls.dataencapsulator.class.php");

define(EBPLS_FORMULAS_TABLE,"ebpls_formulas");
define(EBPLS_FORMULAS_FORMULA_ID,"formula_id");
define(EBPLS_FORMULAS_FORMULA_DESC,"formula_desc");
define(EBPLS_FORMULAS_FORMULA_TYPE,"formula_type");
define(EBPLS_FORMULAS_FORMULA_CLASS,"formula_class");
define(EBPLS_FORMULAS_CREATE_TS,"create_ts");
define(EBPLS_FORMULAS_UPDATE_TS,"update_ts");
define(EBPLS_FORMULAS_ADMIN,"admin");
define(EBPLS_FORMULAS_SYSTEMDATA,"system_data");

/**
 * Type :
 *	CONSTANT
 *		- returns only amount no parameters needed (e.g 250, 100)
 *	FORMULA
 *		- tax computation is a mathematical function, requires at least 1 input paramater
 *		  e.g. P2 per 5000 of tax (x1), so formula is 2*(x1/5000) given that x1 is tax
 *	RANGE
 *		- tax computation is a mathematical function or a constant on each given formula range
 *		- first input parameter (x1) is used to compare on the min and max on each range
 **/
class TaxFeeFormula extends DataEncapsulator{

	var $formula_surcharge;
	var $formula_interest;
	var $formula;
	var $formula_range;
	var $formula_type;

	var $parameters;
	var $parameters_values;

	function TaxFeeFormula( $dbLink, $bDebug = false ) {

		$this->m_dbLink = $dbLink;
		$this->setDebugMode($bDebug);				

	 	$this->addDataElement(EBPLS_FORMULAS_FORMULA_ID, NULL, NULL );
		$this->addDataElement(EBPLS_FORMULAS_FORMULA_DESC,"is_not_empty","[VALUE]");
		$this->addDataElement(EBPLS_FORMULAS_FORMULA_TYPE,"is_not_empty","[VALUE]");
		$this->addDataElement(EBPLS_FORMULAS_FORMULA_CLASS,"is_not_empty","[VALUE]");
		$this->addDataElement(EBPLS_FORMULAS_CREATE_TS,"is_valid_date","[VALUE]");
		$this->addDataElement(EBPLS_FORMULAS_UPDATE_TS,"is_valid_date","[VALUE]");
		$this->addDataElement(EBPLS_FORMULAS_ADMIN,"is_not_empty","[VALUE]");
		$this->addDataElement(EBPLS_FORMULAS_SYSTEMDATA, NULL, NULL);

	}

	function add( $admin ){

		$this->debug("Formulatype : " . $this->getData(EBPLS_FORMULAS_FORMULA_TYPE ) );

		if ( $this->m_dbLink ) {			

			$this->data_elems[EBPLS_FORMULAS_CREATE_TS] = date("Y-m-d H:i:s");
			$this->data_elems[EBPLS_FORMULAS_UPDATE_TS] = date("Y-m-d H:i:s");
			$this->data_elems[EBPLS_FORMULAS_ADMIN] = $admin;
			
			$obj = $this;
			unset($obj->err_code);
			unset($obj->err_mesg);
			
			$this->data_elems[EBPLS_FORMULAS_FORMULA_CLASS] = serialize($obj);

			if ( ( $error_num = $this->validateData() ) > 0 ) {

				$strValues = $this->getData();

				$ret = ebpls_insert_data( $this->m_dbLink, EBPLS_FORMULAS_TABLE, $strValues );

				if ( $ret < 0 ) {

					$this->debug( "CREATE FORMULA FAILED [error:$ret,msg=" . get_db_error() . "]" );

					$this->setError( $ret, get_db_error() );

					return $ret;

				} else {

					$this->debug("FORMULA : " . $this->data_elems[EBPLS_FORMULAS_FORMULA_CLASS]->formula );
					$this->debug( "CREATE FORMULA SYSREF SUCCESSFULL [$ret]" );
					return 1;

				}


			} else {

				$this->debug( "CREATE FORMULA FAILED [error:$ret,msg=" . get_db_error() . "]" );
				return $error_num;

			}

		} else {

			$this->debug( "CREATE FORMULA FAILED INVALID DB LINK $this->m_dbLink" );
			$this->setError( $ret, "Invalid Db link $this->m_dbLink" );
			return -1;

		}


	}

	function delete( $formula_id ){

		if ( $formula_id ) {

			$strWhere[EBPLS_FORMULAS_FORMULA_ID] = $formula_id;

		} else {

			$strWhere[EBPLS_FORMULAS_FORMULA_ID] = $this->data_elems[ EBPLS_FORMULAS_FORMULA_ID ];

		}

		$result = ebpls_delete_data ( $this->m_dbLink, EBPLS_FORMULAS_TABLE, $strWhere );

		if ( $result < 0 )  {

			$this->setError( $result, get_db_error() );

		}

		return $result;

	}

	function update( $formula_id, $admin ) {

		unset($this->data_elems[EBPLS_FORMULAS_FORMULA_ID]);
		$this->data_elems[EBPLS_FORMULAS_CREATE_TS] = date("Y-m-d H:i:s");
		$this->data_elems[EBPLS_FORMULAS_UPDATE_TS] = date("Y-m-d H:i:s");
		$this->data_elems[EBPLS_FORMULAS_ADMIN] = $admin;

		$obj = $this;
		
		unset($obj->err_code);
		unset($obj->err_mesg);
		
		$this->debug("Formulatype : " . $obj->getData(EBPLS_FORMULAS_FORMULA_TYPE ) );

		$this->data_elems[EBPLS_FORMULAS_FORMULA_CLASS] = serialize($obj);
		
		foreach( $this->data_elems as $key=>$value ) {
		
			if ( $value!="" ) {		
				
				$strValues[$key] = $value;
				
			}
			
		}

		if ( $formula_id ) {

			$strWhere[EBPLS_FORMULAS_FORMULA_ID] = $formula_id;

		} else {

			$strWhere[EBPLS_FORMULAS_FORMULA_ID] = $this->data_elems[EBPLS_FORMULAS_FORMULA_ID];

		}

		$ret = ebpls_update_data( $this->m_dbLink, EBPLS_FORMULAS_TABLE, $strValues, $strWhere );

		if ( $ret < 0 ) {

			$this->debug( "UPDATE FORMULA FAILED [error:$ret,msg=" . get_db_error() . "]" );
			$this->setError( $ret, get_db_error() );

			return $ret;

		} else {

			$this->debug( "UPDATE FORMULA SUCCESSFULL [$ret]" );
			return $ret;

		}

	}

	function view( $formula_id ) {

		
		$strColumns[] = "*";

		if ( $formula_id ) {

			$strWhere[EBPLS_FORMULAS_FORMULA_ID] = $formula_id;

		} else {

			$strWhere[EBPLS_FORMULAS_FORMULA_ID] = $this->data_elems[ EBPLS_FORMULAS_FORMULA_ID ];

		}

		$ret = ebpls_select_data( $this->m_dbLink, EBPLS_FORMULAS_TABLE, $strColumns, $strWhere );

		if ( $ret < 0 ) {

			//$this->debug( "VIEW FORMULA [error:$ret,msg=" . get_db_error() . "]" );
			$this->setError( $ret, get_db_error() );

			return $ret;

		} else {

			//$this->debug( "VIEW FORMULA SUCCESSFULL [$ret]" );
			//$this->debug("CLS: " . $ret[0][EBPLS_FORMULAS_FORMULA_CLASS] );

			$obj = unserialize($ret[0][EBPLS_FORMULAS_FORMULA_CLASS]);
			
			// check if formula is a valid unserializable stored object!
			if ( $obj ) {								
								
				$this->setData( NULL, $ret[0] );				
				return 1;
				
			} else {

				$this->setError(-1,"Error loading formula object!");
				$this->debug("Error loading formula object!");
				return -1;

			}

			

		}

	}

	function select( $formula_id = NULL, $formula_desc = NULL, $formula_type = NULL, $page = 1, $maxrec = 20, $orderby = EBPLS_FORMULAS_CREATE_TS, $is_desc = false ) {
	
		
		return $this->selectNonSystemData( $formula_id, $formula_desc, $formula_type,  NULL, $page, $maxrec, $orderby, $is_desc );
			
	}

	
	function selectNonSystemData( $formula_id = NULL, $formula_desc = NULL, $formula_type = NULL, $system_data = NULL, $page = 1, $maxrec = 20, $orderby = EBPLS_FORMULAS_CREATE_TS, $is_desc = false ) {

		if ( $formula_id != NULL && $formula_id != "" )
			$strWhere[EBPLS_FORMULAS_FORMULA_ID] = $formula_id;

		if ( $formula_desc != NULL && $formula_desc != "" )
			$strWhere[EBPLS_FORMULAS_FORMULA_DESC] = array("like", "$formula_desc%");



		if ( $formula_type != NULL && $formula_type != ""  )
			$strWhere[EBPLS_FORMULAS_FORMULA_TYPE] = $formula_type;

		if ( !is_null($system_data) && ( $system_data == "0" ||  $system_data == "1" ) ) {
			
			$strWhere[EBPLS_FORMULAS_SYSTEMDATA] = $system_data;
						
		}

		// select all columns
		$strValues[] = "*";

		if ( $orderby != NULL ) {

			$strOrder[ $orderby ] = $orderby;

		} else {

			$strOrder[ $orderkey ] = EBPLS_FORMULAS_FORMULA_ID;

		}

		$result = ebpls_select_data_bypage( $this->m_dbLink, EBPLS_FORMULAS_TABLE, $strValues, $strWhere, NULL, $strOrder, $is_desc?"DESC":"ASC", $page, $maxrec );

		if ( !is_array($result) && $result < 0 ) {

			$this->setError ( $result, get_db_error());
			return $result;

		} else {

			// transform result to EBPLTaxFeeSysRef object
			for ( $i = 0 ; $i < count($result["result"]); $i++ ) {

				$records[$i] = new TaxFeeFormula($this->m_dbLink, false );
				$obj = unserialize($result["result"][$i][EBPLS_FORMULAS_FORMULA_CLASS]);				
				$records[$i]->setData( NULL, $result["result"][$i] );

			}

			$result["result"] = $records;

			return $result;

		}

	}

	function getType(){

		return $this->formula_type;

	}

	function computeTax( $capital = 0, $last_gross = 0 ) {
		
		
		if ( $this->formula_type == "RANGE" ) {

			// always use first parameter as comparision value on min and max of each range
			$amount = $this->getParameterValue("x1");

			$this->debug("taxable range amount : $amount");

			for ( $i = 0; $i < count($this->formula_range) ; $i++ ) {

				if ( is_numeric($this->formula_range[$i][1])&& is_numeric($this->formula_range[$i][2]) && $this->formula_range[$i][1] <= $amount && $amount<= $this->formula_range[$i][2] ) { // middle ranges

					$formula = $this->formula_range[$i][3];
					break;

				} else if ( is_numeric($this->formula_range[$i][1]) && !is_numeric($this->formula_range[$i][2]) && $this->formula_range[$i][1] <= $amount ) { // uppermost range

					$formula = $this->formula_range[$i][3];
					break;

				} else if ( !is_numeric($this->formula_range[$i][1])&& is_numeric($this->formula_range[$i][2]) && $amount <= $this->formula_range[$i][2] ) { // lowermost range

					$formula = $this->formula_range[$i][3];
					break;

				} else {

					$this->debug("Not in range : " . $this->formula_range[$i][3] . "<BR>");

				}

			}


			if ( $formula ) {

				$formula = str_replace( "[CAPITAL]", $capital, $formula );
				$formula = str_replace( "[LAST_GROSS]", $last_gross, $formula );

				return $this->getAmount($formula);

			} else {

				$this->debug("Range Calculate error.");
				return -1;

			}

		} else if ( $this->formula_type == "FORMULA" || $this->formula_type == "CONSTANT" ) {

			$formula = str_replace( "[CAPITAL]", $capital, $this->formula );
			$formula = str_replace( "[LAST_GROSS]", $last_gross, $this->formula );
			
			$this->debug("COMPUTE TAX FORMULA TYPE = " . $this->formula_type );
			$this->debug("COMPUTE TAX : $formula : " . $this->formula);

			return $this->getAmount( $formula );

		} else {
			
			$this->setError( -1, "error on formula type : " . $this->formula_type );
			$this->debug("error on formula type : " . $this->formula_type );
			echo("error on formula type : " . $this->formula_type );
			return -1;
		
		}

	}


	function getFormulaTypes(){

		return array("FORMULA","RANGE","CONSTANT");

	}

	function addRange( $label, $lower, $upper, $formula ) {

		// check first formula...
		if ( $this->getAmount( $formula ) == -100000000 ) {

			$this->setError(-2,"Invalid Formula value.");
			return -2;

		} else {

			for ( $i = 0; $i < strlen($this->formula_range); $i++ ) {

				if ( is_numeric($this->formula_range[$i][1])&& is_numeric($this->formula_range[$i][2]) && $this->formula_range[$i][1] <= $lower && $lower <= $this->formula_range[$i][2] ) { // middle ranges

					$this->setError(-1,"Mid range error.");
					return -1;

				} else if ( $lower!=null &&  is_numeric($this->formula_range[$i][1])&& !is_numeric($this->formula_range[$i][2]) && $this->formula_range[$i][1] <= $lower ) { // uppermost range

					$this->setError(-1,"Upper most range error.");
					return -1;

				} else if ( $lower!=null && !is_numeric($this->formula_range[$i][1])&& is_numeric($this->formula_range[$i][2]) && $lower <= $this->formula_range[$i][2] ) { // lowermost range

					$this->setError(-1,"Lower most range error.");
					return -1;

				}

				if ( is_numeric($this->formula_range[$i][1])&& is_numeric($this->formula_range[$i][2]) && $this->formula_range[$i][1] <= $upper && $upper <= $this->formula_range[$i][2] ) { // middle ranges

					$this->setError(-1,"Mid range error.");
					return -1;

				} else if ( $upper!=null && is_numeric($this->formula_range[$i][1])&& !is_numeric($this->formula_range[$i][2]) && $this->formula_range[$i][1] <= $upper ) { // uppermost range

					$this->setError(-1,"Up most range error.");
					return -1;

				} else if ( $upper!=null && !is_numeric($this->formula_range[$i][1])&& is_numeric($this->formula_range[$i][2]) && $upper <= $this->formula_range[$i][2] ) { // lowermost range

					$this->setError(-1,"Lower most range error.");
					return -1;

				}

			}

			$this->formula_range[] = array( $label, $lower, $upper, $formula );
			return 1;

		}

	}

	function getRange(){

		return $this->formula_range;

	}

	function setFormula( $formula, $type = 0 ) {

		$formula_tmp = $formula;

		if ( $type == 1 ) {

			// used to test surcharge/interest data
			$formula_tmp = str_replace("[BALANCE]","0.0", $formula_tmp);
			$formula_tmp = str_replace("[DUEDATE]",date("Y-m-d"), $formula_tmp);

		}

		if ( $this->getAmount( $formula_tmp ) == -100000000 ) {

			$this->setError(-1,"Error on formula passed $formula.");
			$this->debug("Error on formula passed $formula.");

			return 0;

		} else {

			$this->formula = $formula;
			return 1;

		}

	}

	function setSurchargeFormula( $formula ) {

		$formula_tmp = str_replace("[BALANCE]","0.0", $formula);
		$formula_tmp = str_replace("[DUEDATE]",date("Y-m-d"), $formula_tmp);

		$this->debug( "setSurchargeFormula USED FORMULA TMP $formula_tmp" );

		if ( $this->getAmount( $formula_tmp, 0 ) == -100000000 ) {

			$this->setError(-1,"Error on formula passed $formula.");
			$this->debug("Error on formula passed $formula.");
			return 0;

		} else {

			$this->formula_surcharge = $formula;

			return 1;

		}

	}

	function setInterestFormula( $formula ) {

		$formula_tmp = str_replace("[BALANCE]","0.0", $formula);
		$formula_tmp = str_replace("[DUEDATE]",date("Y-m-d"), $formula_tmp);

		$this->debug( "setInterestFormula USED FORMULA TMP $formula_tmp" );

		if ( $this->getAmount( $formula_tmp, 0 ) == -100000000 ) {

			$this->setError(-1,"Error on formula passed $formula.");
			$this->debug("Error on formula passed $formula.");
			return 0;

		} else {

			$this->formula_interest = $formula;
			return 1;

		}

	}

	function getSurchargeFormula(){

		return $this->formula;

	}

	function getInterestFormula(){

		return $this->formula;

	}

	function getFormula( $i = -1 ){

		if ( $this->formula_type == "RANGE" && $i>0 && $i <= count($this->formula_range) ) {


			return $this->formula_range[$i][3];

		} else {

			return $this->formula;

		}

	}

	function computeSurcharge( $balance, $duedate = NULL ){

		// compute only current tax is overdue
		if ( strtotime($duedate) >= strtotime(date("Y-m-d H:i:s")) ) {

			$this->debug( "SURCHARGE : " . strtotime($duedate). ">=". strtotime(date("Y-m-d H:i:s")) );
			return 0;

		}

		$str_formula = str_replace( "[DUEDATE]", $duedate, $this->formula );
		$str_formula = str_replace( "[BALANCE]", $balance, $this->formula );

		return $this->getAmount($str_formula, $balance, $duedate );

	}

	function computeInterest( $balance, $duedate = NULL ){


		// compute only current tax is overdue
		if ( strtotime($duedate) >= strtotime(date("Y-m-d H:i:s")) ) {

			$this->debug( "INTEREST : " . strtotime($duedate). ">=". strtotime(date("Y-m-d H:i:s")) );
			return 0;

		}

		$str_formula = str_replace( "[DUEDATE]", $duedate, $this->formula );
		$str_formula = str_replace( "[BALANCE]", $balance, $this->formula );

		return $this->getAmount( $str_formula, $balance, $duedate );


	}

	function getAmount( $formula = NULL, $balance = NULL, $duedate = NULL ) {
	
		//$this->setDebugMode(true);

		if ( $formula == NULL ) {

			$formula = $this->formula;

		}

		$this->debug( "USED FORMULA $formula" );

		// replace paramters with values passed thourgh setParamterValues function
		if ( is_array($this->parameters_values) ) {

			foreach( $this->parameters_values as $key=>$value ) {

				$tmp = str_replace( "[BALANCE]", $balance, $this->parameters_values[$key] );

				if ( ( $diff = formula_tag_datediff( $tmp, $duedate ) ) != NULL ) {
					$formula = str_replace("$key",$diff,$formula);
				} else if ( $this->parameters[$key] == "[MONTH]" ) {
					$formula = str_replace("$key",date("m"),$formula);
				} else {
					$formula = str_replace("$key",$value,$formula);
				}

			}

		}


		$this->debug("Formula created : $formula");

		// extract formula less the datediff tags
		$formula = formula_tag_datediff( $formula, $duedate, true );
		$formula = "return $formula;";

		$this->debug("Formula get : $formula");
		$tax_func = @create_function( '', $formula );

		if ( $tax_func ) {

			$tax_total_amount_due = $tax_func();

		} else {

			$tax_total_amount_due = -100000000;

		}

		return $tax_total_amount_due;


	}

	function isConstant() {

		// if there are parameters defined then we are sure this is not a contant type of formula
		// Note : Range type of tax/fee formula requires at least 1 parameter, this paramter will be
		//	  used as a comparison for the range min and max values of each range
		if ( is_array($this->parameters) && count($this->parameters)>0 ) {

			return false;

		}

		return true;

	}

	// add user input parameters f(x1,x2,x3, ... ,xN ) = ...
	function addParameter( $description ) {

		$index = count($this->parameters) + 1;
		$this->parameters["x$index"] = $description;
		$this->parameters_values["x$index"] = 0;
		return $index;

	}

	function setParameter( $index, $description ) {

		if ( $index!=NULL && $index>=0 && $index < count($this->parameters) ) {

			if ( eregi("^x([0-9]+)",$index) ) {

				$this->parameters["$index"] = $description;
				$this->parameters_values["$index"] = 0;

			} else if ( eregi("^([0-9]+)",$index) ) {

				$this->parameters["x$index"] = $description;
				$this->parameters_values["x$index"] = 0;

			} else {

				return -1;

			}

			return 1;

		}

		return -1;

	}


	function getParameterDescription( $index = null, $bAll = false ) {

		if ( $index!=NULL && $index>=0 ) {

			if ( eregi("^x([0-9]+)",$index) ) {
				return $this->parameters["$index"];
			} else if ( eregi("^([0-9]+)",$index) ) {
				return $this->parameters["x$index"];
			} else {
				return NULL;
			}

		} else {

			if ( is_array($this->parameters) && !($bAll) ) {

				$ret = NULL;

				// filter non user input variables
				foreach( $this->parameters as $key=>$value ) {

					if ( $value == "[CAPITAL]" || $value== "[LAST_GROSS]" ) {
						// skip, system populated tags
					} else if ( !eregi("\[(.*)\]",$value ) && trim($value)!="" ) {

						$ret[$key] = $value;

					}

				}

				return $ret;

			} else {

				return $this->parameters;

			}

		}

	}


	function setParameterValue( $index, $value ) {

		if ( $index!=NULL && $index>0 && $index <= count($this->parameters) ) {

			if ( eregi("^x([0-9]+)",$index) ) {
				$this->parameters_values["$index"] = $value;
			} else if ( eregi("^([0-9]+)",$index) ) {
				$this->parameters_values["x$index"] = $value;
			} else {
				return -1;
			}

			return 1;

		}

		return -1;

	}

	function getParameterValue( $index ) {
		
		$desc = $this->parameters_values[$index];

		if ( ( $diff = formula_tag_datediff( $desc ) ) != NULL ) {
			return $diff;
		} else if ( $desc == "[MONTH]" ) {
			return date("m");
		} else {
			return $this->parameters_values["$index"];
		}

	}

	function setParameterTagValue( $tag, $tag_value ) {


		if ( is_array($this->parameters) ) {

			$ret = NULL;

			foreach( $this->parameters as $key=>$value ) {

				if ( $tag == $value ) {

					$this->parameters_values[$key] = $tag_value;

				}

			}

		}

		return 0;

	}

	function getParameterTagValueIndex( $tag  ) {


		//print_r($this->parameters);

		if ( is_array($this->parameters) ) {

			$ret = NULL;
			$index = 1;
			foreach( $this->parameters as $key=>$value ) {

				if ( $tag == $value ) {

					return $index;

				}

				$index++;

			}

		}

		return 0;

	}


	// NOTE: $input_property not yet used...
	function createAutocompleteJScript( $inputFormElements, $outputFormElement, $input_property = NULL ) {

		$str = "\n<script language=Javascript>\n";
		$strFuncName = uniqid( "EBPLSAutoComplete", false );

		$str .= "// Autogenerated javascript\n";
		$str .= "// Formula Type : $this->formula_type\n";
		$str .= "window.setInterval( \"$strFuncName()\", 100 );\n";

		// init input to default values
		// Tags set on parameter description will initialize value to its default value
		if ( $inputFormElements!= NULL && is_array($inputFormElements) ) {

			foreach( $this->parameters as $key=>$value ) {

				$value = $inputFormElements[$key];

				$desc = $this->getParameterDescription($key);

				if ( ( $diff = formula_tag_datediff( $desc ) ) != NULL ) {

				} else if ( $desc == "[MONTH]" ) {
					$str .= "$value.value = '". date("m") ."';\n";
				} else {
					$str .= "if ( isNaN(parseFloat($value.value)) ) {\n";
					$str .= "$value.value = '0.00';\n";
					$str .= "}\n";
				}

			}

		}

		$str .= "\nfunction $strFuncName() {\n";

		if ( $inputFormElements != NULL && is_array($inputFormElements) ) {

			// set values of input elements to local variables
			foreach( $this->parameters as $key=>$value ) {

				$value = $inputFormElements[$key];
				$desc = $this->getParameterDescription($key);


				if ( ( $diff = formula_tag_datediff( $desc ) ) != NULL ) {

					$setVars .= "var $key = $diff;\n";

				} else if ( $desc == "[MONTH]" ) {

					$setVars .= "var dt = new Date();\n";
					$setVars .= "var $key = dt.getMonth() + 1;\n";

				} else {

					if ( $input_property == NULL ) {

						$setVars .= "var $key = parseFloat($value.value);\n";

					} else {

						if ( $input_property == "checked" ) {

							$setVars .= "var $key = 0;\n";
							$setVars .= "if ( $value.$input_property == true ) {\n";
							$setVars .= "$key = 1;\n";
							$setVars .= "}\n";

						} else {

							$setVars .= "var $key = parseFloat($value.value);\n";


						}

					}

				}



			}

			$str .= $setVars;

		} else {

			$str .= "// no input elements passed\n";

		}

		switch( $this->getType() ) {
			case "RANGE" :
				{

					$range_elems = $this->getRange();

					if ( is_array($range_elems) && count($range_elems) ) {

						for( $i = 0; $i < count($range_elems); $i++ ) {

							$elem = $range_elems[$i];
							$str .= "// range $elem[0]\n";
							if ( $i == 0 ) {
								$str .= "if ( x1 <= $elem[2] ) {\n";
							}else if ( $i == ( count($range_elems) -1 ) ) {
								$str .= "if ( $elem[1] <= x1 ) {\n";
							} else {
								$str .= "if ( $elem[1] <= x1 && x1 <= $elem[2] ) {\n";
							}

							$str .= "var tmp  = roundDecimal(" . $elem[3] . ",2);\n";
							$str .= "if ( isNaN(tmp) ) $outputFormElement.value = '0.00';\n";
							$str .= " else $outputFormElement.value = tmp;\n";

							$str .= "return;\n";
							$str .= "}\n\n";

						}

					} else {

						$str .= "// range elements not an array or may have no elements in it\n";
						$str .= "//might be a corrupted range type of formula\n";

					}


				} break;
			case "CONSTANT" :
				{

					$str .= "var tmp = roundDecimal(" . $this->formula . ",2);\n";
					$str .= "if ( isNaN(tmp) ) $outputFormElement.value = '0.00';\n";
					$str .= " else ";
					$str .= "$outputFormElement.value  = roundDecimal(tmp,2);\n";


				} break;
			case "FORMULA" :
				{

					$str .= "var tmp = roundDecimal(" . $this->formula . ",2);\n";
					$str .= "if ( isNaN(tmp) ) $outputFormElement.value = '0.00';\n";
					$str .= " else ";
					$str .= "$outputFormElement.value = roundDecimal(tmp,2);\n";


				} break;
			default :
				{
					$str .= "// undefined formula type\n";
				}
		}

		$str .= "\n}\n";
		$str .= "\n</script>\n";

		return $str;

	}

	function serialize() {

		return serialize($this);

	}

	function unserialize() {

		return unserialize($this);

	}

	function formulaGetCurrentMonth(){

		return date("m");

	}



}


/**
 formula_tag_datediff function parses DAYS_DATEDIFF, MONTH_DATEDIFF and YEAR_DATEDIFF tags set on formula description parameters.
 The parameter values of these tags are retrieved and the computed day, month or year difference is returned.
 There are two parameters PARAM1 and PARAM2, if date param1 is less than param2 returned value is a negative number otherwise
 function returns a positive number.

 Parameters :
 	$value - tag value passed
 	$precision - round precision of computed difference

 Returns :
 	returns NULL on failure of tags,
 	otherwise returns numeric value representing the days,year or month of dates provided on tags.

 **/
function formula_tag_datediff_v1( $value, $duedate, $precision = 0 ) {

	$value = str_replace("[YEAR]", date("Y"), $value);
	$value = str_replace("[MONTH]", date("m"), $value);
	$value = str_replace("[DAY]", date("d"), $value);
	$value = str_replace("[SYSDATE]", date("Y-m-d"), $value);

	if ( $duedate == NULL ) {
		$value = str_replace("[DUEDATE]", date("Y-m-d"), $value);
	} else {
		$value = str_replace("[DUEDATE]", $duedate, $value);
	}

	if ( eregi("\[DAYS_DATEDIFF{[0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2},[0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2}}\]", $value, $tokens ) ) {

		$type = 1;

	} else if ( eregi("\[MONTH_DATEDIFF{[0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2},[0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2}}\]", $value, $tokens ) ) {

		$type = 2;

	} else if ( eregi("\[YEAR_DATEDIFF{[0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2},[0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2}}\]", $value, $tokens )  ) {

		$type = 3;

	} else {

		return NULL;

	}

	for ( $i = 0 ; $i < count($tokens) ; $i++ ) {

		if ( eregi("\[DAYS_DATEDIFF{[0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2},[0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2}}\]", $value, $arrData ) ) {

		} else if ( eregi("\[MONTH_DATEDIFF{[0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2},[0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2}}\]", $value, $arrData ) ) {

		} else if ( eregi("\[YEAR_DATEDIFF{[0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2},[0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2}}\]", $value, $arrData )  ) {

		} else {

		}

		$param1 = $arrData[1];
		$param2 = $arrData[2];

		$tm1 = strtotime($param1);
		$tm2 = strtotime($param2);

		if ( $tm1==-1 || $tm2==-1 ) {

			return NULL;

		}

		$dt1 = getdate($tm1);
		$dt2 = getdate($tm2);

		$epoch1 = mktime( $dt1["hours"], $dt1["minutes"], $dt1["seconds"], $dt1["mon"], $dt1["mday"], $dt1["year"] );
		$epoch2 = mktime( $dt2["hours"], $dt2["minutes"], $dt2["seconds"], $dt2["mon"], $dt2["mday"], $dt2["year"] );

		switch($type){
			case 1 : // day
				$epoch_diff = ($epoch1 - $epoch2)/86400;
				break;
			case 2 : // month
				$epoch_diff = ($epoch1 - $epoch2)/(86400*30);
				break;
			case 3 : // year
				$epoch_diff = ($epoch1 - $epoch2)/(86400*365);
				break;
		}


		return round($epoch_diff,$precision);

	}

}

/**
 formula_tag_datediff function parses DAYS_DATEDIFF, MONTH_DATEDIFF and YEAR_DATEDIFF tags set on formula description parameters.
 The parameter values of these tags are retrieved and the computed day, month or year difference is returned.
 There are two parameters PARAM1 and PARAM2, if date param1 is less than param2 returned value is a negative number otherwise
 function returns a positive number.

 Parameters :
 	$value - tag value passed
 	$precision - round precision of computed difference

 Returns :
 	returns NULL on failure of tags,
 	otherwise returns numeric value representing the days,year or month of dates provided on tags.

 **/
function formula_tag_datediff( $value, $duedate = NULL, $bExtractFormula = false, $precision = 0 ) {

	$value = str_replace("[YEAR]", date("Y"), $value);
	$value = str_replace("[MONTH]", date("m"), $value);
	$value = str_replace("[DAY]", date("d"), $value);
	$value = str_replace("[SYSDATE]", date("Y-m-d"), $value);

	if ( $duedate == NULL ) {
		$value = str_replace("[DUEDATE]", date("Y-m-d"), $value);
	} else {
		$value = str_replace("[DUEDATE]", $duedate, $value);
	}

	// find all datediff tags then compute their datediff and replace value on passed value
	$arrData = extract_datediff_elems( $value );

	if ( $arrData == NULL ) {

		if ( $bExtractFormula ) return $value;
		else return NULL;

	}


	while ( is_array($arrData) && count($arrData) ) {

		$param1 = $arrData[1];
		$param2 = $arrData[2];

		$tm1 = strtotime($param1);
		$tm2 = strtotime($param2);

		if ( $tm1==-1 || $tm2==-1 ) {

			return NULL;

		}

		$dt1 = getdate($tm1);
		$dt2 = getdate($tm2);

		$epoch1 = mktime( $dt1["hours"], $dt1["minutes"], $dt1["seconds"], $dt1["mon"], $dt1["mday"], $dt1["year"] );
		$epoch2 = mktime( $dt2["hours"], $dt2["minutes"], $dt2["seconds"], $dt2["mon"], $dt2["mday"], $dt2["year"] );

		switch($arrData[3]){
			case 1 : // day
				$epoch_diff = ($epoch1 - $epoch2)/86400;
				break;
			case 2 : // month
				$epoch_diff = ($epoch1 - $epoch2)/(86400*30);
				break;
			case 3 : // year
				$epoch_diff = ($epoch1 - $epoch2)/(86400*365);
				break;
		}


		$value = str_replace( $arrData[0], round($epoch_diff,$precision), $value );

		$arrData = extract_datediff_elems( $value );

	}


	if ( $bExtractFormula ) {
		$func = create_function ( '', 'return $value;' );
		$ret = $func();
		return $value;

	} else {

		return $value;
	}

}

function extract_datediff_elems( $value ) {

	if ( eregi("\[DAYS_DATEDIFF{([0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2}),([0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2})}\]", $value, $arrData ) ) {
		$arrData[3] = 1;
	} else if ( eregi("\[MONTH_DATEDIFF{([0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2}),([0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2})}\]", $value, $arrData ) ) {
		$arrData[3] = 2;
	} else if ( eregi("\[YEAR_DATEDIFF{([0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2}),([0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2})}\]", $value, $arrData )  ) {
		$arrData[3] = 3;
	} else {

		unset($arrData);
		return NULL;

	}

	return $arrData;

}

?>
