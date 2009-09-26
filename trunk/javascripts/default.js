/*
*	default.js
*	- this defines all the page specific functions
*/
var _NewWindow = null;

function update_delete_taxfeereq_sysref(kud,typ)
{
	var _FRM = document._FRM;
	//--- set the code
	_FRM.refCode.value=kud;
	_FRM.permType.value=typ;


}


function delete_taxfeeref_maintenance_details_form()
{
	var _FRM = document._FRM;

	var msgTitle = "Tax/Fee/Requirements Maintenance\n";

	var dilet    = confirm(msgTitle + 'Are you sure you want to delete the record?')
	if(dilet)
	{
		_FRM.submit();
	}

}


function validate_taxfeeref_maintenance_details_form(what)
{
	var _FRM = document._FRM;
	var msgTitle = "Tax/Fee/Requirements Maintenance\n";


	var idx = 0;
	if(what == 'app')
	{
		idx = _FRM.business_requirement_code.options.selectedIndex;
		 _FRM.tax_fee_req_desc.value =  _FRM.business_requirement_code.options[idx].text;
	}
	else
	{
		idx = _FRM.tax_fee_code.options.selectedIndex;
		_FRM.tax_fee_req_desc.value = _FRM.tax_fee_code.options[idx].text;
	}





	_FRM.submit();

}


function validate_assessment_fee_frm(tot_frm)
{
	var _FRM = document._FRM;


	for(var i=1;i<=tot_frm;i++)
	{

	}
	_FRM.submit();

}


function update_assessment_list(txid,businessid,ownerid,permitno)
{

	var _FRM = document._FRM;
	//--- set the code
	_FRM.tx_code.value	=txid;
	_FRM.permit_no.value	=permitno;
	_FRM.owner_id.value	=ownerid;
	_FRM.bussiness_id.value	=businessid;


}



function update_delete_transaction_list(txid,permitno,ownerid)
{
	var _FRM = document._FRM;
	//--- set the code
	_FRM.tx_code.value=txid;
	_FRM.permit_no.value=permitno;
	_FRM.owner_id.value=ownerid;
}




function delete_chart_of_accounts_form()
{
	var _FRM = document._FRM;

	var msgTitle = "Chart of Accounts\n";

	var dilet    = confirm(msgTitle + 'Are you sure you want to delete the record?')
	if(dilet)
	{
		_FRM.submit();
	}

}

function set_code_value()
{

	var _FRM = document._FRM;
	
	_FRM.code.value = _FRM.actnum.value + '-' + _FRM.depcode.value + '-' + _FRM.extracode.value;

}

function validate_chart_of_accounts_form()
{
	var _FRM = document._FRM;

	var msgTitle = "Chart of Accounts\n";

	if( isBlank(_FRM.code.value) == true)
	{
		alert( msgTitle + "Please input a valid  code!");
		_FRM.code.focus();
		return false;
	}
	if( isBlank(_FRM.desc.value) == true)
	{
		alert( msgTitle + "Please input a valid  description!");
		_FRM.desc.focus();
		return false;
	}
	if( isBlank(_FRM.actnum.value) == true)
	{
		alert( msgTitle + "Please input a valid  account number!");
		_FRM.actnum.focus();
		return false;
	}
	if( isBlank(_FRM.depcode.value) == true)
	{
		alert( msgTitle + "Please input a valid  department code!");
		_FRM.depcode.focus();
		return false;
	}
	if( isBlank(_FRM.extracode.value) == true)
	{
		alert( msgTitle + "Please input a valid  extra code!");
		_FRM.extracode.focus();
		return false;
	}
	_FRM.submit();

}



function update_delete_chart_of_accounts(kud)
{
	var _FRM = document._FRM;
	//--- set the code
	_FRM.acct_code.value=kud;
}


function validate_chart_of_accounts(what)
{
	var _FRM = document._FRM;
	var msgTitle = "Chart of Accounts\n";


	var max_len  = (_FRM.sysrefcode) ? _FRM.sysrefcode.length : 0;

	var ok	     = 0;
	for(var i=0;i<max_len;i++)
	{
		if(_FRM.sysrefcode[i].checked == true)
		{
			ok = 1;
			break;
		}
	}
	if(what > 1 && ok == 0)
	{
		alert(msgTitle + "Please click a valid code!");
		return false;
	}

	var _action = new Array('add','update','delete');
	_FRM.actionMode.value=_action[what-1];
	_FRM.submit();

}


function validate_taxfee_formula(what)
{
	var _FRM = document._FRM;
	var msgTitle = "Tax Fee \n";

	var max_len  = 0;
	var ok	     = 0;

	if( _FRM.taxfeecode == undefined ||  _FRM.taxfeecode.length == undefined ) {

		ok = 1;

	} else {

		max_len  = _FRM.taxfeecode.length;

		for(var i=0;i<max_len;i++)
		{
			if(_FRM.taxfeecode[i].checked == true)
			{
				ok = 1;
				break;
			}
		}

	}

	if(what > 1 && ok == 0)
	{
		alert(msgTitle + "Please click a valid code!");
		return false;
	}

	var _action = new Array('add','update','delete');
	_FRM.actionMode.value=_action[what-1];
	_FRM.submit();

}

function update_delete_taxfeeformula(kud)
{
	var _FRM = document._FRM;
	//--- set the code
	_FRM.tax_fee_formula_code.value=kud;
}

function validate_db_maintenance_details_form()
{
	var _FRM = document._FRM;
	var msgTitle = "System DB Details Maintenance\n";

	if( isBlank(_FRM.sysref_code.value) == true)
	{
		alert( msgTitle + "Please input a valid  code!");
		_FRM.sysref_code.focus();
		return false;
	}
	if( isBlank(_FRM.sysref_desc.value) == true)
	{
		alert( msgTitle + "Please input a valid  description!");
		_FRM.sysref_desc.focus();
		return false;
	}
	_FRM.submit();

}
function delete_db_maintenance_details_form()
{
	var _FRM = document._FRM;
	var msgTitle = "System DB Details Maintenance\n";
	var dilet    = confirm(msgTitle + 'Are you sure you want to delete the record?')
	if(dilet)
	{
		_FRM.submit();
	}

}

function validate_db_maintenance_details(what)
{
	var _FRM = document._FRM;
	var msgTitle = "System DB Details Maintenance\n";
	var max_len  = (_FRM.sysrefcode) ? _FRM.sysrefcode.length : 0;

	var ok	     = 0;
	for(var i=0;i<max_len;i++)
	{
		if(_FRM.sysrefcode[i].checked == true)
		{
			ok = 1;
			break;
		}
	}
	if(what > 1 && ok == 0)
	{
		alert(msgTitle + "Please click a valid code!");
		return false;
	}

	var _action = new Array('add','update','delete');
	_FRM.actionMode.value=_action[what-1];
	_FRM.submit();

}
function update_delete_sysref(kud)
{
	var _FRM = document._FRM;
	//--- set the code
	_FRM.refCode.value=kud;

}

function delete_tax_fee_table()
{
	var _FRM = document._FRM;
	var msgTitle = "Tax Fee Table Maintenance\n";
	var dilet    = confirm(msgTitle + 'Are you sure you want to delete the record?')
	if(dilet)
	{
		_FRM.submit();
	}

}
function validate_tax_fee_table()
{
	var _FRM = document._FRM;
	var msgTitle = "Tax Fee Table Maintenance\n";
	if( isBlank(_FRM.tax_fee_code.value) == true)
	{
		alert( msgTitle + "Please input a valid tax fee code!");
		_FRM.tax_fee_code.focus();
		return false;
	}
	if( isBlank(_FRM.tax_fee_desc.value) == true)
	{
		alert( msgTitle + "Please input a valid tax fee description!");
		_FRM.tax_fee_desc.focus();
		return false;
	}
	//--- validate if range or by formula
	var what_type = _FRM.tax_fee_formula_type.value;
	var what_ctr  = _FRM.tax_fee_formula_type_ctr.value;
	if(what_type == 'formula')
	{
		if( isBlank(_FRM.tax_fee_formula1.value) == true)
		{
			alert( msgTitle + "Please input a valid formula!");
			_FRM.tax_fee_formula1.focus();
			return false;
		}
	}
	else //--- by range
	{
		var invalid = false;

		for(var i=1;i<=what_ctr;i++)
		{
			var range_name_val  	= eval('_FRM.range_name'+i+'.value;');
			var range_start_val 	= eval('_FRM.range_start'+i+'.value;');
			var range_end_val   	= eval('_FRM.range_end'+i+'.value;');
			var range_formula_val   = eval('_FRM.range_formula'+i+'.value;');

			if( isBlank(range_name_val)    == true)
			{
				alert( msgTitle + "Please input a valid range name!");
				eval('_FRM.range_name' + i + '.focus();');
				invalid = true;
				break;

			}

			if( i>1  && ( isBlank(range_start_val) == true || isDigit(range_start_val) == false ) )
			{
				alert( msgTitle + "Please input a valid start range!");
				eval('_FRM.range_start' + i + '.focus();');
				invalid = true;
				break;

			}
			if( i<what_ctr && (isBlank(range_end_val)     == true || isDigit(range_end_val) == false) )
			{
				alert( msgTitle + "Please input a valid end range!");
				eval('_FRM.range_end' + i + '.focus();');
				invalid = true;
				break;

			}
			if(isBlank(range_formula_val) == true)
			{
				alert( msgTitle + "Please input a valid formula!");
				eval('_FRM.range_formula' + i + '.focus();');
				invalid = true;
				break;

			}
		}
		if (invalid)
		{
			return false;
		}
	}
	_FRM.submit();
	return true;

}

function validate_business_application(_url)
{
		var _FRM = document._FRM;
		var msgTitle = "Business Permit Application\n";

		if( isBlank(_FRM.owner_first_name.value) == true)
		{
			alert( msgTitle + "Please input a valid firstname!");
			_FRM.owner_first_name.focus();
			return false;
		}
		if( isBlank(_FRM.owner_middle_name.value) == true)
		{
			alert( msgTitle + "Please input a valid middlename!");
			_FRM.owner_middle_name.focus();
			return false;
		}
		if( isBlank(_FRM.owner_last_name.value) == true)
		{
			alert( msgTitle + "Please input a valid lastname!");
			_FRM.owner_last_name.focus();
				return false;
		}
		if( isBlank(_FRM.owner_id.value) == true)
		{
			alert( msgTitle + "Please add owner first by clicking either Search link!");
			return false;
		}
		 
		//--- validate the business details

		if( isBlank(_FRM.business_name.value) == true)
		{
			alert( msgTitle + "Please input a valid business name!");
			_FRM.business_name.focus();
			return false;
		}

		var scale_idx 	= _FRM.business_scale.options.selectedIndex;
		var mode_idx  	= _FRM.business_payment_mode.options.selectedIndex;

		 
		var _scale	= _FRM.business_scale.options[scale_idx].value;
		var _mode	= _FRM.business_payment_mode.options[mode_idx].value;
		
		if( isBlank(_scale) == true )
		{
			alert( msgTitle + "Please select a valid business scale!");
			_FRM.business_scale.focus();
			return false;
		}
		
		if( isBlank(_mode) == true )
		{
			alert( msgTitle + "Please select a valid business payment mode!");
			_FRM.business_payment_mode.focus();
			return false;
		}
		if( isBlank(_FRM.business_id.value) == true)
		{
			alert( msgTitle + "Please add business first by clicking either Search link!");
			return false;
		}
		if(_FRM.listings_line_of_business_ctr.value == 0  || _FRM.listings_line_of_business_ctr.value == "") 
		{
			alert( msgTitle + "Please add a line of business first!");
			return false;
	
		}
		 _FRM.action=_url;

		 _FRM.submit();
	 return true;
}

function validate_peddlers_application(_url)
{
	var _FRM = document._FRM;
		var msgTitle = "Peddlers Permit Application\n";

		if( isBlank(_FRM.owner_first_name.value) == true)
		{
			alert( msgTitle + "Please input a valid firstname!");
			_FRM.owner_first_name.focus();
			return false;
		}
		if( isBlank(_FRM.owner_middle_name.value) == true)
		{
			alert( msgTitle + "Please input a valid middlename!");
			_FRM.owner_middle_name.focus();
			return false;
		}
		if( isBlank(_FRM.owner_last_name.value) == true)
		{
			alert( msgTitle + "Please input a valid lastname!");
			_FRM.owner_last_name.focus();
				return false;
		}
		 

		//-- set the bday

		var y_idx = _FRM._YEAR1.options.selectedIndex;
		var m_idx = _FRM._MONTH1.options.selectedIndex;
		var d_idx = _FRM._DAY1.options.selectedIndex;

		_FRM.owner_birth_date.value = _FRM._YEAR1.options[y_idx].value + '-' + _FRM._MONTH1.options[m_idx].value + '-' + _FRM._DAY1.options[d_idx].value;

		if( isBlank(_FRM.owner_birth_date.value) == true || isValidDate(_FRM.owner_birth_date.value) == false)
		{
			alert( msgTitle + "Please input a valid birthdate!");
			_FRM.comm_tax_cert_owner_birth_date.focus();
			return false;
		}

		if(validate_list_tax_fee_codes() == false)
		{
			return false;
		}

		_FRM.action=_url;

		 _FRM.submit();
	 return true;
}

function validate_franchise_application(_url)
{
		var _FRM = document._FRM;



		var msgTitle = "Franchise Permit Application\n";

		if( isBlank(_FRM.owner_first_name.value) == true)
		{
			alert( msgTitle + "Please input a valid firstname!");
			_FRM.owner_first_name.focus();
			return false;
		}
		if( isBlank(_FRM.owner_middle_name.value) == true)
		{
			alert( msgTitle + "Please input a valid middlename!");
			_FRM.owner_middle_name.focus();
			return false;
		}
		if( isBlank(_FRM.owner_last_name.value) == true)
		{
			alert( msgTitle + "Please input a valid lastname!");
			_FRM.owner_last_name.focus();
				return false;
		}
		 
		//-- set the bday



		var y_idx = _FRM._YEAR1.options.selectedIndex;
		var m_idx = _FRM._MONTH1.options.selectedIndex;
		var d_idx = _FRM._DAY1.options.selectedIndex;

		_FRM.owner_birth_date.value = _FRM._YEAR1.options[y_idx].value + '-' + _FRM._MONTH1.options[m_idx].value + '-' + _FRM._DAY1.options[d_idx].value;

		if( isBlank(_FRM.owner_birth_date.value) == true || isValidDate(_FRM.owner_birth_date.value) == false)
		{
			alert( msgTitle + "Please input a valid birthdate!");
			_FRM.comm_tax_cert_owner_birth_date.focus();
			return false;
		}

		//--- validate the buss requirements
		 



		if( validate_list_tax_fee_codes() == false)
		{

			return false;
		}

		 _FRM.action=_url;
		 _FRM.submit();
	 return true;
}




function validate_list_tax_fee_codes()
{

	var _FRM  = document._FRM;



	with(document)
	{

	    var tot_idx = 0;

	    var amt_value = '';
	    var amt_due	  = '';

	    if(_FRM.total_forms)
	    {

		tot_idx = _FRM.total_forms.value;

		for(var i=1;i< tot_idx;i++)
		{

			alert('test' + i);

			amt_value = eval('_FRM.tax_total_taxable_amt' + i + '.value');
			//alert('_FRM.tax_total_taxable_amt' + i + '.value');

			if(isDigit(amt_value) == false)
			{
				alert("Please input a valid Total Taxable Amount !");
				eval('_FRM.tax_total_taxable_amt' + i + '.focus()');
				return false;
			}


		}


	    }

	}
	return true;
}


function filter_permit_no()
{
	var _FRM = document._FRM;
	var idx = _FRM.method_of_application.options.selectedIndex;
	with(document)
	{
		if(idx <= 0)
		{
			reset_application_fields();
		}
		else
		{
			_FRM.search_by_what.disabled=false;
			//_FRM.status_of_application.disabled=false;
			_FRM.search_by.disabled=false;
		}

	}

}

function reset_application_fields()
{

 
	var _FRM = document._FRM;
	_FRM.search_by_what.disabled=true;
	//_FRM.status_of_application.disabled=true;
	_FRM.search_by.disabled=true;
	_FRM.search_by_what.value='';
	 
}

function validate_motorized_application(_url)
{
	var _FRM = document._FRM;

	with(document)	
	{
		var msgTitle = "Motorized Operator Permit Application\n";


		if( isBlank(_FRM.owner_first_name.value) == true)
		{
			alert( msgTitle + "Please input a valid firstname!");
			_FRM.owner_first_name.focus();
			return false;
		}

		if( isBlank(_FRM.owner_middle_name.value) == true)
		{
			alert( msgTitle + "Please input a valid middlename!");
			_FRM.owner_middle_name.focus();
			return false;
		}

		if( isBlank(_FRM.owner_last_name.value) == true)
		{
			alert( msgTitle + "Please input a valid lastname!");
			_FRM.owner_last_name.focus();
				return false;
		}

		if( isBlank(_FRM.motorized_no_of_units.value) == true || isDigit(_FRM.motorized_no_of_units.value) == false)
		{
			alert( msgTitle + "Please input a valid number of units!");
			_FRM.motorized_no_of_units.focus();
			return false;
		}

		if( isBlank(_FRM.motorized_motor_model.value) == true)
			{
				alert( msgTitle + "Please input a valid motor models!");
				_FRM.motorized_motor_model.focus();
				return false;
			}

		if( isBlank(_FRM.motorized_motor_no.value) == true)
			{
				alert( msgTitle + "Please input a valid motor number!");
				_FRM.motorized_motor_no.focus();
				return false;
			}

		if( isBlank(_FRM.motorized_chassis_no.value) == true)
			{
				alert( msgTitle + "Please input a valid chassis number!");
				_FRM.motorized_chassis_no.focus();
				return false;
			}

		if( isBlank(_FRM.motorized_plate_no.value) == true)
			{
				alert( msgTitle + "Please input a valid plate number!");
				_FRM.motorized_plate_no.focus();
				return false;
			}
	//-- set the bday

	var y_idx = _FRM._YEAR1.options.selectedIndex;
	var m_idx = _FRM._MONTH1.options.selectedIndex;
	var d_idx = _FRM._DAY1.options.selectedIndex;

	_FRM.owner_birth_date.value = _FRM._YEAR1.options[y_idx].value + '-' + _FRM._MONTH1.options[m_idx].value + '-' + _FRM._DAY1.options[d_idx].value;

	if( isBlank(_FRM.owner_birth_date.value) == true || isValidDate(_FRM.owner_birth_date.value) == false)
	{
		alert( msgTitle + "Please input a valid birthdate!");
		_FRM.comm_tax_cert_owner_birth_date.focus();
		return false;
	}


	if(validate_list_tax_fee_codes() == false)
		{
			return false;
		}

	_FRM.action=_url;
	_FRM.submit();
	 return true;
    }
}

//--- start CTC application page scripts
function validate_ctc_application()
{

		var _FRM = document._FRM;
		var msgTitle = "Community Tax Certificate Application\n";

		if( isBlank(_FRM.comm_tax_cert_owner_first_name.value) == true)
		{
			alert( msgTitle + "Please input a valid firstname!");
			_FRM.comm_tax_cert_owner_first_name.focus();
			return false;
		}
		if( isBlank(_FRM.comm_tax_cert_owner_middle_name.value) == true)
		{
			alert( msgTitle + "Please input a valid middlename!");
			_FRM.comm_tax_cert_owner_middle_name.focus();
			return false;
		}
		if( isBlank(_FRM.comm_tax_cert_owner_last_name.value) == true)
		{
			alert( msgTitle + "Please input a valid lastname!");
			_FRM.comm_tax_cert_owner_last_name.focus();
			return false;
		}

		if( isBlank(_FRM.comm_tax_cert_owner_address.value) == true)
		{
			alert( msgTitle + "Please input a valid address!");
			_FRM.comm_tax_cert_owner_address.focus();
			return false;
		}

		if( isBlank(_FRM.comm_tax_cert_place_of_birth.value) == true)
		{
			alert( msgTitle + "Please input a valid place of birth!");
			_FRM.comm_tax_cert_place_of_birth.focus();
			return false;
		}
		if( isBlank(_FRM.comm_tax_cert_last_gross.value) == true || isDigit(_FRM.comm_tax_cert_last_gross.value) == false )
		{
			alert( msgTitle + "Please input a valid last gross!");
			_FRM.comm_tax_cert_last_gross.focus();
			return false;
		}
		if( isBlank(_FRM.comm_tax_cert_amount_due.value) == true || isDigit(_FRM.comm_tax_cert_amount_due.value) == false )
		{
			alert( msgTitle + "Please input a valid amount due!");
			_FRM.comm_tax_cert_amount_due.focus();
			return false;
		}
		if( isBlank(_FRM.comm_tax_cert_amount_paid.value) == true || isDigit(_FRM.comm_tax_cert_amount_paid.value) == false)
		{
			alert( msgTitle + "Please input a valid amount paid!");
			_FRM.comm_tax_cert_amount_paid.focus();
			return false;
		}
		if( isBlank(_FRM.comm_tax_cert_acct_code.value) == true)
		{
			alert( msgTitle + "Please input a valid account code!");
			_FRM.comm_tax_cert_acct_code.focus();
			return false;
		}
		if( isBlank(_FRM.comm_tax_cert_place_issued.value) == true)
		{
			alert( msgTitle + "Please input a valid place issued!");
			_FRM.comm_tax_cert_place_issued.focus();
			return false;
		}
		if( isBlank(_FRM.comm_tax_cert_owner_first_name.value) == true)
		{
			alert( msgTitle + "Please input a valid firstname");
			_FRM._firstname.focus();
			return false;
		}

		//--- construct the bday


		var y_idx = _FRM._YEAR1.options.selectedIndex;
		var m_idx = _FRM._MONTH1.options.selectedIndex;
		var d_idx = _FRM._DAY1.options.selectedIndex;

		_FRM.comm_tax_cert_owner_birth_date.value = _FRM._YEAR1.options[y_idx].value + '-' + _FRM._MONTH1.options[m_idx].value + '-' + _FRM._DAY1.options[d_idx].value;

		if( isBlank(_FRM.comm_tax_cert_owner_birth_date.value) == true || isValidDate(_FRM.comm_tax_cert_owner_birth_date.value) == false)
				{
					alert( msgTitle + "Please input a valid birthdate!");
					_FRM.comm_tax_cert_owner_birth_date.focus();
					return false;
		}

		
	return true;
}


function calculate_10p_gross(individual,business)
{
		//--	---- RULE :
		//	( 10% X AMOUNT ) + Php 5.00
		//-------
		var _FRM = document._FRM;
		var msgTitle = "Community Tax Certificate Application\n";

		if(isDigit(_FRM.comm_tax_cert_last_gross.value) == false )
		{
			alert( msgTitle + "Please input a valid last gr's gross income!");
			_FRM.comm_tax_cert_last_gross.focus();
			return false;
		}
		//--- calc the 10 percent
		var amount     = 0.00;

		var ctc_type_idx = _FRM.comm_tax_cert_type.options.selectedIndex;
		var ctc_type     = _FRM.comm_tax_cert_type.options[ctc_type_idx].value;

		var ctc_const_amt = (ctc_type == 'INDIVIDUAL') ? (individual) : (business);
		var gross_val  = _FRM.comm_tax_cert_last_gross.value;

		if(isNaN(gross_val))
			return false;

		// calculate tax due and projected amount payable
		var strValue = new String(gross_val/1000  + ctc_const_amt);
		var dotIndex = strValue.indexOf(".")
		alert(strValue.substr(0,dotIndex+2));

		amount = (dotIndex>0)?strValue.substr(0,dotIndex+2):strValue;

		//--- set the amount due / paid
		if(isDigit(amount))
		{
			_FRM.comm_tax_cert_amount_due.value  = amount;
			_FRM.comm_tax_cert_amount_paid.value = amount;
		}
		return true;
}

//--- end   CTC application page scripts


//--- start CTC criteria page scripts
function filter_method()
{
	var _FRM = document._FRM;
	var idx = _FRM.method_of_application.options.selectedIndex;
	with(document)
	{

		if(idx <= 0)
		{
			reset_fields();
		}
		else
		{
			_FRM.search_by.disabled=false;
			_FRM.status_of_application.disabled=false;
			_FRM.search_by_what.disabled=false;
		}

	}

}

function reset_fields()
{
	var _FRM = document._FRM;
	_FRM.search_by.disabled=true;
	_FRM.status_of_application.disabled=true;
	_FRM.search_by_what.disabled=true;
	_FRM.search_by_what.value='';
}

function set_ctc_renew_code()
{
	var _FRM = document._FRM;
	var msgTitle = "Community Tax Certificate Application\n";
	var max_len  = _FRM.renew_ctc_code.length;
	var mode     = _FRM.method_of_application.value;
	for(var i=0;i<max_len;i++)
	{
		if(_FRM.renew_ctc_code[i].checked == true)
		{
			return true;
		}
	}
	if(mode == 'renew')
	{
		alert(msgTitle + "Please click a valid CTC Code to be renewed!");
		return false;
	}
	return true;
}
function update_ctc_renew_code(kud)
{
	var _FRM = document._FRM;
	//--- set the code
	_FRM.comm_tax_cert_code.value=kud;

}
//--- end CTC criteria page scripts



//---- misc functions


function isValidDate(pDateStr)
{
	var _sepRegExp = /\/|\-/g;
	var _dateArray = pDateStr.split(_sepRegExp);
	if (_dateArray.length != 3)
	{
		return false;
	}

	var _year  = _dateArray[0];
	var _date  = _dateArray[1];
	var _month = _dateArray[2];


	if( (isDigit(_month) == true) && (isDigit(_date)==true)  && (isDigit(_year) == true))
	{
		return true;
	}
	else
	{
		return false;
	}
}

function isValidDecimal(_chars)
			{

	var period = 0;
	for(var i=0; i<_chars.length;i++)
	{
		if(_chars.charAt(i) == ".")
		{
			period++;
			continue;
		}
		if (! ((_chars.charCodeAt(i) >= 48) && (_chars.charCodeAt(i) <= 57)))
			return false;

	}
	if(_chars.length == 0) return false;


	if(period > 1)
		return false;

	return true;

}

function roundDecimal( amount, count ) {

	var val = parseFloat(amount);
	var keyRem = Math.pow(10,count);
	var val2 = val*keyRem
	var val3 = Math.round(val2)/keyRem;
	var strOut = new String(val3);

	if ( strOut.indexOf(".") > 0 ) {
				
		strOutRem = strOut.substring( strOut.indexOf(".") + 1 ); 				
				
		if ( strOutRem.length == 1 ) {
			return strOut + "0";
		} else {
			return strOut;
		}
					
	} else {
	
		return strOut + ".00";

	}

}

function showNewWin(_location,_width,_height)
{

        var _num = Math.round(Math.random() * 100000);
        var _name = "window" + _num;
        if (! (_width) )
              _width  = 320;
        if (! (_height) )
              _height = 320;

        if (_NewWindow && !_NewWindow.closed)
        {
                _NewWindow.location.href=_location;
                _NewWindow.focus();
        }
        else
        {
                if (screen)
                {
                        leftpos = parseInt((screen.width - _width) / 2);
                        toppos = parseInt((screen.height - _height) / 2);
                }
                else
                {
                        leftpos = 200;
                        toppos = 100;
                }
                _NewWindow = window.open(_location,_name,"left="+leftpos+",top="+toppos+",height="+_height+",width="+_width+",menubar=no,resizable=no,scrollbars=yes,status=yes,toolbar=no");

        }
}

function isAlphabetic(pInputVal)
{
	var _inputStr = pInputVal.toString();
	var _char;
	for(var i = 0; i < _inputStr.length; i++) {
		_char = _inputStr.charAt(i);
		if(!((_char >= 'A' && _char <= 'Z') || (_char >= 'a' && _char <= 'z'))) {
			return false;
		}
	}
	return true;
}


function isAlphanumeric(pInputVal)
{
	var _inputStr = pInputVal.toString();
	var _char;
	for(var i = 0; i < _inputStr.length; i++) {
		_char = _inputStr.charAt(i);
		if(!((_char >= 'A' && _char <= 'Z') ||
		     (_char >= 'a' && _char <= 'z') ||
		     (_char >= '0' && _char <= '9'))) {
			return false;
		}
	}
	return true;
}

function isInteger(pInputVal)
{

	var _inputStr = pInputVal.toString();
	var _char;
	for(var i = 0; i < _inputStr.length; i++) {
		_char = _inputStr.charAt(i);
		if(i == 0 && _char == "-") {
			continue;
		}
		if (_char < "0" || _char > "9") {
			return false;
		}
	}
	return true;
}


function isNegativeInteger(pInputVal) {
	if (isNaN(pInputVal)) {
		return false;
	}
	if (Number(pInputVal) >= 0) {
		return false;
	}
	return isInteger(pInputVal);
}


function isNonnegativeInteger(pInputVal)
{
	var _inputStr = pInputVal.toString();
	var _char;
	for (var i = 0; i < _inputStr.length; i++) {
		_char = _inputStr.charAt(i);
		if (_char < "0" || _char > "9") {
			return false;
		}
	}
	return true;
}
function isDigit(_chars)
{

	var period = 0;
	for(var i=0; i<_chars.length;i++)
	{
		if(_chars.charAt(i) == ".")
		{
			period++;
			continue;
		}
		if (! ((_chars.charCodeAt(i) >= 48) && (_chars.charCodeAt(i) <= 57)))
			return false;

	}
	if(_chars.length == 0) return false;


	if(period > 1)
		return false;

	return true;
}




function checkAlphaNumeric(_value)
{

	if(_value.length == 0) return false;
	for(var i=0;i<_value.length;i++)
	{
		if (!( (_value.charCodeAt(i) >=48 &&
		       _value.charCodeAt(i) <=57 ) ||
		      (_value.charCodeAt(i) >= 97 &&
		       _value.charCodeAt(i) <= 122) ||
		      (_value.charCodeAt(i) >= 65 &&
		       _value.charCodeAt(i) <= 90) ||
		      (_value.charCodeAt(i) == 44) ||
		      (_value.charCodeAt(i) == 45) ||
		      (_value.charCodeAt(i) == 46) ||
		      (_value.charCodeAt(i) == 32)
		      ))
		{
			return false;
			break;
		}
	}
	return true;
}




function isEmpty(pInputVal)
{
	return (pInputVal == null || pInputVal == '');
}




function isBlank(_arg)
{
	if(_arg == null || _arg == "undefined" || _arg.length == 0)
	{
		return true;
	}
	else
	{
		var cnt = 0;
		var _str = "";
		for(var i=0;i<_arg.length;i++)
		{
			if( ! (_arg.charCodeAt(i) == 13 || _arg.charCodeAt(i) == 32))
			{
				_str += _arg.charAt(i);
			}

		}
		return (_str.length ==  0 || _str == "") ? (true) : (false);

	}
}

function checkIfDirty(_form)
{
  var _isDirty = false;

  for (var i=0;  i < _form.elements.length; i ++)
  {
    var _element = _form.elements[i];
    var _type = _element.type;

    if ( _type == "checkbox" )
    {
    	_isDirty = (_element.defaultChecked != _element.checked);
    }
    else if ( _type.toLowerCase() == "text" )
    {
      _isDirty = (_element.defaultValue != _element.value);
    }
    else if ( _type.toLowerCase() == "password" )
    {
          _isDirty = (_element.defaultValue != _element.value);
    }
    else if ( _type == "textarea" )
    {
          _isDirty = (_element.defaultValue != _element.value);
    }
    else if ( _type == "select-one" )
    {
          var _options = _element.options;

          for (var x = 0; x < _options.length; x ++) {
            _isDirty = (_options[x].defaultSelected != _options[x].selected);
            if (_isDirty)
            {
	       	break;
    	    }
          }
    }
    if (_isDirty){
    	break;
    }
  }
  return _isDirty;
}


//extracts date from a mysql datetime value with format yyyy-mm-dd hh:mm:ss
function extractDate( val ) {

	var arrDateTime = val.split(" ");

	if ( arrDateTime.length > 1 ) {

		var dt = arrDateTime[0];
		var tm = arrDateTime[1];

		if ( dt.length > 2 ) {

			var dt_elems = dt.split("-");
			return new Date( dt_elems[0], parseInt(dt_elems[1])-1, dt_elems[2] );

		}

	}

	return NULL;

}

function selectDate( itemName, dt ) {

	var win_opener = window.opener;
	var frm = win_opener.document._FRM;

	var dtVal = extractDate( dt );
	
	if ( dtVal == null ) return;
	
	var nYr = dtVal.getFullYear();
	var nDay = dtVal.getDate();
	var nMonth = dtVal.getMonth() + 1;

	var yrItem = frm[itemName+"_year"];
	var dyItem = frm[itemName+"_days"];
	var moItem = frm[itemName+"_month"];

	
	for ( var i = 0; i < dyItem.options.length; i++ ){

		if ( parseInt(dyItem.options(i).text) == nDay ) {
			dyItem.selectedIndex = i;			
			break;
		}

	}	
	
	for ( var i = 0; i < yrItem.options.length; i++ ) {

		if ( parseInt(yrItem.options(i).text) == nYr ) {			
			yrItem.selectedIndex = i;			
			break;

		}

	}		
	
	for ( var i = 0; i < moItem.options.length; i++ ) {

		if ( parseInt(moItem.item(i).value) == nMonth ) {
			moItem.selectedIndex = i;	
			break;
		}

	}	

}



function validate_owner_update_add()
{
	var _FRM = document._FRM;
	
	if( isBlank(_FRM.owner_house_no.value) == true)
	{
		alert( msgTitle + "Please input a valid house number!");
		_FRM.owner_house_no.focus();
		return false;
	}
	if( isBlank(_FRM.owner_street.value) == true)
	{
		alert( msgTitle + "Please input a valid street number!");
		_FRM.owner_street.focus();
		return false;
	}

	if( isBlank(_FRM.owner_citizenship.value) == true)
	{
		alert( msgTitle + "Please input a valid citizenship!");
		_FRM.owner_citizenship.focus();
		return false;
	}
	if( isBlank(_FRM.owner_tin_no.value) == true)
	{
		alert( msgTitle + "Please input a valid TIN number!");
		_FRM.owner_tin_no.focus();
		return false;
	}

		
}


