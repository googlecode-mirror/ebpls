function GenerateLog(a,b,c,d)
{

        var _FRM     = document._FRM;
     	var p
     	p='';
        for (var i=0;  i < _FRM.elements.length; i ++)
        {
                var _element = _FRM.elements[i];
                var _type = _element.type;
                
        		                                                                                   
                if ( _type != "submit" && _type != "button" && _type != "reset" && _type != "hidden" && _type != "Reset")
                {
	                if ( _type == "checkbox" ) {
		                p = p + ' ' + _element.name + '=' + _element.checked + ': ';
	                } else {
	                p = p + ' ' + _element.name + '=' + _element.value + ': ';
               		 }
                }
                
        }
        
        p = 'Menu: ' +  a + ': Module: ' + b +  ': Subcommand: ' + c + ': id: ' + d + ': Data:' + p + ':';
		setCookie("logger",p,"","","","");
}

function SearchAct()
{
	var x     = document.porm;
	setCookie("search_user",x.search_user.value,"","","","");
	setCookie("search_cat",x.search_cat.value,"","","","");
	parent.location="http://192.168.1.104/ebpls/index.php?part=4&itemID_=11&busItem=Settings&permit_type=Settings&settings_type=ActivityLog&item_id=Settings";
}
function ClearAct()
{
	setCookie("search_user","","","","","");
	setCookie("search_cat","","","","","");
}

function UpFoc()
{
var frm = document._FRM;
				
			if (frm.savfoc.value=='') {
					alert ("Record Successfully Updated!!"); 
					parent.location="index.php?natureid=" + frm.natureid.value + "&action_=1&actionID=1&part=4&class_type=Preference&pref_type=Business&selMode=ebpls_nbusiness"
					frm.savfoc.value=1;
					return true
			} 
}

function SaveFoc()
{
				var frm = document._FRM;
				
				if (frm.savfoc.value=='') {
					alert ("Data is successfully added to the database!!"); 
					parent.location="index.php?natureid=" + frm.natureid.value + "&action_=1&actionID=1&part=4&class_type=Preference&pref_type=Business&selMode=ebpls_nbusiness"
					frm.savfoc.value=1;
					return true
				} 
}

function OrderBy(z,y,e)
{
	var x = document.frm;
	x.order.value=z;
	x.page.value=y;
	x.isdesc.value=e;
	x.submit();
	return true;
}

function CancelCat()
{
               var _FRM = document._FRM;
		alert ("Transaction cancelled");
		_FRM.bbo.value=0;
		_FRM.confx.value='cancel';
                _FRM.submit();
                return true;
}

function CheckAF()
{
               var x = document._FRM;
				if (isBlank(x.af_start.value)) {
					alert ("Please enter AF start series");
					x.af_start.focus();
					x.af_start.select();
					return false;
				}
				
				if (isBlank(x.af_end.value)) {
					alert ("Please enter AF end series");
					x.af_end.focus();
					x.af_end.select();
					return false;
				}
				
				if (isBlank(x.min_allow.value)) {
					alert ("Please enter minimum allowed");
					x.min_allow.focus();
					x.min_allow.select();
					return false;
				}
				
				if (isNaN(x.af_start.value)) {
					alert ("Please enter valid start series");
					x.af_start.focus();
					x.af_start.select();
					return false;
				}
				
				if (isNaN(x.af_end.value)) {
					alert ("Please enter valid end series");
					x.af_end.focus();
					x.af_end.select();
					return false;
				}
				
				if (x.bala.value<1 || isNaN(x.bala.value)) {
					alert ("Please AF start series cannot be greater than end series");
					x.af_start.focus();
					x.af_start.select();
					return false;
				}
               
               
                _FRM.submit();
                return true;
}

function ComputeBALA()
{
		var x = document._FRM;
		
		x.bala.value = parseFloat(x.af_end.value) - parseFloat(x.af_start.value);
		return true;
}


function VerifyEcoOrg()
{
                                                                                                                             
                var a = document._FRM;
                                                                                                                             
                 if (isBlank(a.nCode.value)) {
                                                                                                                             
                        alert ("Invalid Economic Organization Code");
                        a.nCode.focus();
						a.nCode.select();
                        return false;
                }
                                                                                                                             
                 if (isBlank(a.nType.value)) {
                                                                                                                             
                        alert ("Invalid Economic Organization Description");
                        a.nType.focus();
						a.nType.select();
                        return false;
                }


		if (a.nCode.value.length>10) {

			alert ("Economic Organization Code Exceeds Max Length");
                        a.nCode.focus();
                        a.nCode.select();
                        return false;
                }

		if (a.nType.value.length>15) {
                                                                                                                             
                        alert ("Economic Organization Description Exceeds Max Length");
                        a.nType.focus();
                        a.nType.select();
                        return false;
                }



                a.sb.value='Submit';
                a.submit();
                return true;
}







function VerifyFAQ()
{
                                                                                                                                                                                                         
                var a = document._FRM;
                                                                                                                                                                                                         
                if (isBlank(a.faq_question.value)) {
                                                                                                                                                                                                         
                        alert ("Input Valid Question!");
                        a.faq_question.focus();
                        return false;
                }
                                                                                                                                                                                                         
                if (isBlank(a.faq_answer.value)) {
                                                                                                                                                                                                         
                        alert ("Input Valid Answer!");
                        a.faq_answer.focus();
                        return false;
                }
                                                                                                                                                                                                         
                if (a.faq_question.value.length>50) {
                                                                                                                                                                                                         
                        alert ("FAQ Question Exceeds Max Length");
                        a.faq_question.focus();
                        a.faq_question.select();
                        return false;
                }
                                                                                                                                                                                                         
                if (a.faq_answer.value.length>50) {
                                                                                                                                                                                                         
                        alert ("FAQ Answer Exceeds Max Length");
                        a.faq_answer.focus();
                        a.faq_answer.select();
                        return false;
                }
                                                                                                                                                                                                         
                a.sb.value='SAVE';
                a.submit();
                return true;
}


function VerifyLink()
{
                                                                                                                                                                                                         
                var a = document._FRM;
                                                                                                                                                                                                         
                if (isBlank(a.link.value)) {
                                                                                                                                                                                                         
                        alert ("Input Valid Link.");
                        a.link.focus();
                        return false;
                }
                                                                                                                                                                                                         
                if (isBlank(a.desc.value)) {
                                                                                                                                                                                                         
                        alert ("Input Valid Description.");
                        a.desc.focus();
                        return false;
                }
                                                                                                                                                                                                         
                if (a.link.value.length>30) {
                                                                                                                                                                                                         
                        alert ("Link Exceeds Max Length");
                        a.link.focus();
                        a.link.select();
                        return false;
                }
                                                                                                                                                                                                         
                if (a.desc.value.length>30) {
                                                                                                                                                                                                         
                        alert ("Description Exceeds Max Length");
                        a.desc.focus();
                        a.desc.select();
                        return false;
                }
                                                                                                                                                                                                         
                a.sb.value='SAVE';
                a.submit();
                return true;
}

function VerifyOtherPermit()
{
                var a = document._FRM;
                if (isBlank(a.feedesc.value)) {
                        alert ("Invalid Fee Description");
                        a.feedesc.focus();
                        a.feedesc.select();
                        return false;
                }
				if (a.feedesc.value.length > 15) {
                        alert ("Fee Description Exceeds Max Length");
                        a.feedesc.focus();
                        a.feedesc.select();
                        return false;
                }
                if (isBlank(a.feeamount.value)) {
                        alert ("Invalid Fee Amount");
                        a.feeamount.focus();
                        a.feeamount.select();
                        return false;
                }
                if (a.feedesc.value.length>15) {
                        alert ("Fee Description Exceeds Max Length");
                        a.feedesc.focus();
                        a.feedesc.select();
                        return false;
                }
                if (a.feeamount.value.length>15) {
                        alert ("Fee Amount Exceeds Max Length");
                        a.feeamount.focus();
                        a.feeamount.select();
                        return false;
                }
		if (isNaN(a.feeamount.value) || a.feeamount.value<1) {
                        alert ("Invalid Amount");
                        a.feeamount.focus();
                        a.feeamount.select();
                        return false;
                }
                a.sb.value='Submit';
                a.submit();
                return true;
}





function confdel(cc)
{
                var _FRM = document._FRM;
                doyou = confirm("Record Will Be Deleted, Continue?");
                        if (doyou==true) {
	                      //  alert("Record Deleted");
                                        _FRM.bbo.value = cc;
                        _FRM.confx.value = 1;
                } else {
                                                                                                                             
                        alert("Transaction cancelled");
                        _FRM.confx.value=0;
                        return false;
                }
                _FRM.submit();
                return true;
}

function DelRec()
{
        alert("Record Deleted");
}
function AddRec()
{
	alert ("Data is successfully added to the database");
}

function UpRec()
{
        alert ("Record Successfully Updated");
}

function ExistRec()
{
	alert ("Existing Record Found.");
}

function ExistOther()
{
	alert ("Cannot Delete. Record is in other table");
}


function EditRec(z)
{
	var x = document.frm;
	
	x.com.value="edit";
	x.bbo.value=z;
	x.submit();
	return true;
}

function DeleteRec(z)
{
	var x = document.frm;
	del = confirm("Delete Record?");
	if (del == true) {
		x.com.value="delete";
	} else {
		return false;
	}
	x.bbo.value=z;
	x.submit();
	return true;
}


function Pagination(z)
{
	var x = document.frm;
	x.page.value=z;
	x.submit();
	return true;
}
function OrderBy(z,y,e)
{
	var x = document.frm;
	x.order.value=z;
	x.page.value=y;
	x.isdesc.value=e;
	x.submit();
	return true;
}

function Paginationd(z)
{
	var x = document._FRM1;
	x.page.value=z;
	x.submit();
	return true;
}
function OrderByd(z,y,e)
{
	var x = document._FRM1;
	x.order.value=z;
	x.page.value=y;
	x.isdesc.value=e;
	x.submit();
	return true;
}
function ViewLog(x)
{
		
		setCookie("logid",x,"","","","");
		myRef = window.open('viewlog.php','viewpay',
                                'left=120,top=50,width=650,height=420,toolbar=0,resizable=1');
        myRef.focus();                        
}
function setCookie( name, value, expires, path, domain, secure ) {
  var today = new Date();
  today.setTime( today.getTime() );
  if ( expires ) {
    expires = expires * 1000 * 60 * 60 * 24;
  }
  var expires_date = new Date( today.getTime() + (expires) );
  document.cookie = name+"="+escape( value ) +
    ( ( expires ) ? ";expires="+expires_date.toGMTString() : "" ) + //expires.toGMTString()
    ( ( path ) ? ";path=" + path : "" ) +
    ( ( domain ) ? ";domain=" + domain : "" ) +
    ( ( secure ) ? ";secure" : "" );
}
function VerifySet() {
        var x=document.prefSetForm;
        if (x.passLen.value=='') {
                alert("Enter Valid Password length.");
                x.passLen.focus();
                x.passLen.select();
                return false;
        }
		if (x.passLen.value < 1 || x.passLen.value > 20) {
                alert("Password length should be from 1 - 20.");
                x.passLen.focus();
                x.passLen.select();
                return false;
        }
		if (isNaN(x.passLen.value) == true) {
			alert("Password length  should be numeric.");
			x.passLen.focus();
            x.passLen.select();
            return false;
		}
		if (x.passLen.value.length > 2) {
                alert("Password Length exceeds max length.");
                x.passLen.focus();
                x.passLen.select();
                return false;
        }
        if (x.passRetLimit.value=='') {
                alert("Password retry limit required.");
                x.passRetLimit.focus();
                x.passRetLimit.select();
                return false;
        }
		if (x.passRetLimit.value < 1 || x.passRetLimit.value > 9) {
                alert("Password retry limit should be from 1 - 9.");
                x.passRetLimit.focus();
                x.passRetLimit.select();
                return false;
        }
		if (isNaN(x.passRetLimit.value) == true) {
			alert("Retry limit  should be numeric.");
			x.passRetLimit.focus();
            x.passRetLimit.select();
            return false;
		}
		if (x.passRetLimit.value.length>1) {
                alert("Password Retry Limit exceeds max length!!");
                x.passRetLimit.focus();
                x.passRetLimit.select();
                return false;
        }
        if (x.cookieSet.value=="") {
                alert("Cookie duration required.");
                x.cookieSet.focus();
                x.cookieSet.select();
                return false;
        }
		if (x.cookieSet.value < 1 || x.cookieSet.value > 86400) {
                alert("Cookie duration should be from 1 - 86400.");
                x.cookieSet.focus();
                x.cookieSet.select();
                return false;
        }
		if (isNaN(x.cookieSet.value) == true) {
			    alert("Cookie duration should be numeric.");
                x.cookieSet.focus();
                x.cookieSet.select();
                return false;
		}
		if (x.cookieSet.value.length>5) {
                alert("Cookie Duration exceeds max length!!");
                x.cokieeSet.focus();
                x.cookieSet.select();
                return false;
        }
        if (x.pageLimit.value=='') {
                alert("Items Per Page required.");
                x.pageLimit.focus();
                x.pageLimit.select();
                return false;
        }
		if (x.pageLimit.value < 1 || x.pageLimit.value > 100) {
                alert("Items Per Page should be from 1 - 100.");
                x.pageLimit.focus();
                x.pageLimit.select();
                return false;
        }
		if (isNaN(x.pageLimit.value) == true) {
			    alert("Items Per Page should be numeric.");
                x.pageLimit.focus();
                x.pageLimit.select();
                return false;
		}
        if (x.pageLimit.value.length>3) {
                alert("Items Per Page exceeds max length!!");
                x.pageLimit.focus();
                x.pageLimit.select();
                return false;
        }
        x.frmSubmitPref.value='Submit';
        x.submit();
        return true;
}

function ValidNum()
{
	 var a = document._FRM;
                if (isNaN(a.no_range.value) || a.no_range.value=='') {
			a.no_range.focus();
                        alert ("Invalid Number");
                        return false;
                }
		a.addrange.value="Add Range";
		a.submit();
		return true;
}	








// (C) 2000 www.CodeLifter.com
// http://www.codelifter.com
// Free for all users, but leave in this  header
                                                                                                                                                                                                         
var good;
function checkEmailAddress(field) {
                                                                                                                                                                                                         
// Note: The next expression must be all on one line...
//       allow no spaces, linefeeds, or carriage returns!
var goodEmail = field.value.match(/\b(^(\S+@).+((\.com)|(\.net)|(\.edu)|(\.mil)|(\.gov)|(\.org)|(\..{2,2}))$)\b/gi);
                
var msgTitle = "Owner Application\n";                                                                                                                                                                                         
if (goodEmail){
   good = true
} else {
   alert(msgTitle + 'Please enter a valid e-mail address.')
   field.focus()
   field.select()
   good = false
   return false;
   }
}
                                                                                                                                                                                                         
function CheckEmail(){
   good = false
if (document._FRM.owner_email_address.value!='') {
   checkEmailAddress(document._FRM.owner_email_address)
   }

}





function GetCity() {
if($F('zip').length == 5) {
var url = 'getCity.cfm';
var params = 'zip=' + $F('zip');
var ajax = new Ajax.Updater(
{success: 'zipResult'},
url,
{method: 'get', parameters: params, onFailure: reportError});
}
}
function reportError(request) {
$F('zipResult') = "Error";
}

//* Validate Date Field script- By JavaScriptKit.com
//* For this script and 100s more, visit http://www.javascriptkit.com
//* This notice must stay intact for usage



function DateFormat(vDateName, vDateValue, e, dateCheck, dateType) {
vDateType = dateType;
// vDateName = object name
// vDateValue = value in the field being checked
// e = event
// dateCheck 
// True  = Verify that the vDateValue is a valid date
// False = Format values being entered into vDateValue only
// vDateType
// 1 = mm/dd/yyyy
// 2 = yyyy/mm/dd
// 3 = dd/mm/yyyy
//Enter a tilde sign for the first number and you can check the variable information.
if (vDateValue == "~") {
alert("AppVersion = "+navigator.appVersion+" \nNav. 4 Version = "+isNav4+" \nNav. 5 Version = "+isNav5+" \nIE Version = "+isIE4+" \nYear Type = "+vYearType+" \nDate Type = "+vDateType+" \nSeparator = "+strSeperator);
vDateName.value = "";
vDateName.focus();
return true;
}
var whichCode = (window.Event) ? e.which : e.keyCode;
// Check to see if a seperator is already present.
// bypass the date if a seperator is present and the length greater than 8
if (vDateValue.length > 8 && isNav4) {
if ((vDateValue.indexOf("-") >= 1) || (vDateValue.indexOf("/") >= 1))
return true;
}
//Eliminate all the ASCII codes that are not valid
var alphaCheck = " abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ/-";
if (alphaCheck.indexOf(vDateValue) >= 1) {
if (isNav4) {
vDateName.value = "";
vDateName.focus();
vDateName.select();
return false;
}
else {
vDateName.value = vDateName.value.substr(0, (vDateValue.length-1));
return false;
   }
}
if (whichCode == 8) //Ignore the Netscape value for backspace. IE has no value
return false;
else {
//Create numeric string values for 0123456789/
//The codes provided include both keyboard and keypad values
var strCheck = '47,48,49,50,51,52,53,54,55,56,57,58,59,95,96,97,98,99,100,101,102,103,104,105';
if (strCheck.indexOf(whichCode) != -1) {
if (isNav4) {
if (((vDateValue.length < 6 && dateCheck) || (vDateValue.length == 7 && dateCheck)) && (vDateValue.length >=1)) {
alert("Invalid Date\nPlease Re-Enter");
vDateName.value = "";
vDateName.focus();
vDateName.select();
return false;
}
if (vDateValue.length == 6 && dateCheck) {
var mDay = vDateName.value.substr(2,2);
var mMonth = vDateName.value.substr(0,2);
var mYear = vDateName.value.substr(4,4)
//Turn a two digit year into a 4 digit year
if (mYear.length == 2 && vYearType == 4) {
var mToday = new Date();
//If the year is greater than 30 years from now use 19, otherwise use 20
var checkYear = mToday.getFullYear() + 30; 
var mCheckYear = '20' + mYear;
if (mCheckYear >= checkYear)
mYear = '19' + mYear;
else
mYear = '20' + mYear;
}
var vDateValueCheck = mMonth+strSeperator+mDay+strSeperator+mYear;
if (!dateValid(vDateValueCheck)) {
alert("Invalid Date\nPlease Re-Enter");
vDateName.value = "";
vDateName.focus();
vDateName.select();
return false;
}
return true;
}
else {
// Reformat the date for validation and set date type to a 1
if (vDateValue.length >= 8  && dateCheck) {
if (vDateType == 1) // mmddyyyy
{
var mDay = vDateName.value.substr(2,2);
var mMonth = vDateName.value.substr(0,2);
var mYear = vDateName.value.substr(4,4)
vDateName.value = mMonth+strSeperator+mDay+strSeperator+mYear;
}
if (vDateType == 2) // yyyymmdd
{
var mYear = vDateName.value.substr(0,4)
var mMonth = vDateName.value.substr(4,2);
var mDay = vDateName.value.substr(6,2);
vDateName.value = mYear+strSeperator+mMonth+strSeperator+mDay;
}
if (vDateType == 3) // ddmmyyyy
{
var mMonth = vDateName.value.substr(2,2);
var mDay = vDateName.value.substr(0,2);
var mYear = vDateName.value.substr(4,4)
vDateName.value = mDay+strSeperator+mMonth+strSeperator+mYear;
}
//Create a temporary variable for storing the DateType and change
//the DateType to a 1 for validation.
var vDateTypeTemp = vDateType;
vDateType = 1;
var vDateValueCheck = mMonth+strSeperator+mDay+strSeperator+mYear;
if (!dateValid(vDateValueCheck)) {
alert("Invalid Date\nPlease Re-Enter");
vDateType = vDateTypeTemp;
vDateName.value = "";
vDateName.focus();
vDateName.select();
return false;
}
vDateType = vDateTypeTemp;
return true;
}
else {
if (((vDateValue.length < 8 && dateCheck) || (vDateValue.length == 9 && dateCheck)) && (vDateValue.length >=1)) {
alert("Invalid Date\nPlease Re-Enter");
vDateName.value = "";
vDateName.focus();
vDateName.select();
return false;
         }
      }
   }
}
else {
// Non isNav Check
if (((vDateValue.length < 8 && dateCheck) || (vDateValue.length == 9 && dateCheck)) && (vDateValue.length >=1)) {
alert("Invalid Date\nPlease Re-Enter");
vDateName.value = "";
vDateName.focus();
return true;
}
// Reformat date to format that can be validated. mm/dd/yyyy
if (vDateValue.length >= 8 && dateCheck) {
// Additional date formats can be entered here and parsed out to
// a valid date format that the validation routine will recognize.
if (vDateType == 1) // mm/dd/yyyy
{
var mMonth = vDateName.value.substr(0,2);
var mDay = vDateName.value.substr(3,2);
var mYear = vDateName.value.substr(6,4)
}
if (vDateType == 2) // yyyy/mm/dd
{
var mYear = vDateName.value.substr(0,4)
var mMonth = vDateName.value.substr(5,2);
var mDay = vDateName.value.substr(8,2);
}
if (vDateType == 3) // dd/mm/yyyy
{
var mDay = vDateName.value.substr(0,2);
var mMonth = vDateName.value.substr(3,2);
var mYear = vDateName.value.substr(6,4)
}
if (vYearLength == 4) {
if (mYear.length < 4) {
alert("Invalid Date\nPlease Re-Enter");
vDateName.value = "";
vDateName.focus();
return true;
   }
}
// Create temp. variable for storing the current vDateType
var vDateTypeTemp = vDateType;
// Change vDateType to a 1 for standard date format for validation
// Type will be changed back when validation is completed.
vDateType = 1;
// Store reformatted date to new variable for validation.
var vDateValueCheck = mMonth+strSeperator+mDay+strSeperator+mYear;
if (mYear.length == 2 && vYearType == 4 && dateCheck) {
//Turn a two digit year into a 4 digit year
var mToday = new Date();
//If the year is greater than 30 years from now use 19, otherwise use 20
var checkYear = mToday.getFullYear() + 30; 
var mCheckYear = '20' + mYear;
if (mCheckYear >= checkYear)
mYear = '19' + mYear;
else
mYear = '20' + mYear;
vDateValueCheck = mMonth+strSeperator+mDay+strSeperator+mYear;
// Store the new value back to the field.  This function will
// not work with date type of 2 since the year is entered first.
if (vDateTypeTemp == 1) // mm/dd/yyyy
vDateName.value = mMonth+strSeperator+mDay+strSeperator+mYear;
if (vDateTypeTemp == 3) // dd/mm/yyyy
vDateName.value = mDay+strSeperator+mMonth+strSeperator+mYear;
} 
if (!dateValid(vDateValueCheck)) {
alert("Invalid Date\nPlease Re-Enter");
vDateType = vDateTypeTemp;
vDateName.value = "";
vDateName.focus();
return true;
}
vDateType = vDateTypeTemp;
return true;
}
else {
if (vDateType == 1) {
if (vDateValue.length == 2) {
vDateName.value = vDateValue+strSeperator;
}
if (vDateValue.length == 5) {
vDateName.value = vDateValue+strSeperator;
   }
}
if (vDateType == 2) {
if (vDateValue.length == 4) {
vDateName.value = vDateValue+strSeperator;
}
if (vDateValue.length == 7) {
vDateName.value = vDateValue+strSeperator;
   }
} 
if (vDateType == 3) {
if (vDateValue.length == 2) {
vDateName.value = vDateValue+strSeperator;
}
if (vDateValue.length == 5) {
vDateName.value = vDateValue+strSeperator;
   }
}
return true;
   }
}
if (vDateValue.length == 10&& dateCheck) {
if (!dateValid(vDateName)) {
// Un-comment the next line of code for debugging the dateValid() function error messages
//alert(err);  
alert("Invalid Date\nPlease Re-Enter");
vDateName.focus();
vDateName.select();
   }
}
return false;
}
else {
// If the value is not in the string return the string minus the last
// key entered.
if (isNav4) {
vDateName.value = "";
vDateName.focus();
vDateName.select();
return false;
}
else
{
vDateName.value = vDateName.value.substr(0, (vDateValue.length-1));
return false;
         }
      }
   }
}




function dateValid(objName) {
var strDate;
var strDateArray;
var strDay;
var strMonth;
var strYear;
var intday;
var intMonth;
var intYear;
var booFound = false;
var datefield = objName;
var strSeparatorArray = new Array("-"," ","/",".");
var intElementNr;
// var err = 0;
var strMonthArray = new Array(12);
strMonthArray[0] = "Jan";
strMonthArray[1] = "Feb";
strMonthArray[2] = "Mar";
strMonthArray[3] = "Apr";
strMonthArray[4] = "May";
strMonthArray[5] = "Jun";
strMonthArray[6] = "Jul";
strMonthArray[7] = "Aug";
strMonthArray[8] = "Sep";
strMonthArray[9] = "Oct";
strMonthArray[10] = "Nov";
strMonthArray[11] = "Dec";
//strDate = datefield.value;
strDate = objName;
if (strDate.length < 1) {
return true;
}
for (intElementNr = 0; intElementNr < strSeparatorArray.length; intElementNr++) {
if (strDate.indexOf(strSeparatorArray[intElementNr]) != -1) {
strDateArray = strDate.split(strSeparatorArray[intElementNr]);
if (strDateArray.length != 3) {
err = 1;
return false;
}
else {
strDay = strDateArray[0];
strMonth = strDateArray[1];
strYear = strDateArray[2];
}
booFound = true;
   }
}
if (booFound == false) {
if (strDate.length>5) {
strDay = strDate.substr(0, 2);
strMonth = strDate.substr(2, 2);
strYear = strDate.substr(4);
   }
}
//Adjustment for short years entered
if (strYear.length == 2) {
strYear = '20' + strYear;
}
strTemp = strDay;
strDay = strMonth;
strMonth = strTemp;
intday = parseInt(strDay, 10);
if (isNaN(intday)) {
err = 2;
return false;
}
intMonth = parseInt(strMonth, 10);
if (isNaN(intMonth)) {
for (i = 0;i<12;i++) {
if (strMonth.toUpperCase() == strMonthArray[i].toUpperCase()) {
intMonth = i+1;
strMonth = strMonthArray[i];
i = 12;
   }
}
if (isNaN(intMonth)) {
err = 3;
return false;
   }
}
intYear = parseInt(strYear, 10);
if (isNaN(intYear)) {
err = 4;
return false;
}
if (intMonth>12 || intMonth<1) {
err = 5;
return false;
}
if ((intMonth == 1 || intMonth == 3 || intMonth == 5 || intMonth == 7 || intMonth == 8 || intMonth == 10 || intMonth == 12) && (intday > 31 || intday < 1)) {
err = 6;
return false;
}
if ((intMonth == 4 || intMonth == 6 || intMonth == 9 || intMonth == 11) && (intday > 30 || intday < 1)) {
err = 7;
return false;
}
if (intMonth == 2) {
if (intday < 1) {
err = 8;
return false;
}
if (LeapYear(intYear) == true) {
if (intday > 29) {
err = 9;
return false;
   }
}
else {
if (intday > 28) {
err = 10;
return false;
      }
   }
}
return true;
}
function LeapYear(intYear) {
if (intYear % 100 == 0) {
if (intYear % 400 == 0) { return true; }
}
else {
if ((intYear % 4) == 0) { return true; }
}
return false;
}




function checkdate(input){
	var validformat=/^\d{4}\/\d{2}\/\d{2}$/ //Basic check for format validity
	var returnval=false
		if (!validformat.test(input.value)) {
				alert("Invalid Date Format. Please correct and submit again." + input.value)
		} else { //Detailed check for valid date ranges
	
			var monthfield=input.value.split("-")[1];
			var dayfield=input.value.split("-")[2];
			var yearfield=input.value.split("-")[0];
			var dayobj = new Date(yearfield, monthfield-1, dayfield);

				if ((dayobj.getMonth()+1!=monthfield)||(dayobj.getDate()!=dayfield)||(dayobj.getFullYear()!=yearfield))
					alert("Invalid Day, Month, or Year range detected. Please correct and submit again.");
				 else {
				returnval=true
				}
		}
		
		if (returnval==false) {
			input.select()
			return returnval
		}
}


function flagchange(x)
{
	x.value=1;
}

function subsichange(d)
{
	if (d.value=='' || d.value==0) {
		d.value=1;
	} else {
		d.value=0;
	}
}

	function business_maintenance_status()
		{
			var win_opener  = window.opener;
			var frm 	= win_opener.document._FRM;

			frm.owner_id.value='<?php echo $owner_id;?>';
			frm.business_name.value='<?php echo $business_name;?>';
			alert("Business Maintenance :\n(<?php echo addslashes($business_name);?>) successfully added" );
			win_opener.attachToApplication('<?php echo $owner_id;?>','<?php echo $business_id?>');
			window.close();
		}
		business_maintenance_status();

function checkValidOwner()
{
        var _FRM = document._FRM;
		var msgTitle = "Owner Application\n";
		var vDateSplit1 = _FRM.owner_birth_date.value.split("-");
		var isValid = true;
		var nDay = vDateSplit1[2]-0;
		var nMonth = vDateSplit1[1]-1;
		var nYear = vDateSplit1[0];
		///////////////////
		var isValidBDay = true;
		var nowDate = new Date();
		year = parseInt(nYear);
		var dateOfBirth = new Date();
		dateOfBirth.setFullYear(nYear,nMonth,nDay);
		var mYear = parseFloat(nYear);
		if (dateOfBirth > nowDate || (mYear + 140) < nowDate.getFullYear())
		{
			isValidBDay = false;
		}
		//////////
		var enteredDate = new Date();
		enteredDate.setFullYear(nYear,nMonth,nDay);
		//var enteredDate = new Date(vDateSplit1[1] + " " + vDateSplit1[2] + " " + vDateSplit1[0]);
		if (enteredDate.getDate() != vDateSplit1[2])
		{
			isValid = false;
		}
		        if (_FRM.busItem.value!='Business') {
                        if( isBlank(_FRM.owner_first_name.value) == true)
                        {
                        alert("Please enter First Name!");
                        _FRM.owner_first_name.focus();
                        return false;
                        }
                        if( isBlank(_FRM.owner_middle_name.value) == true)
                        {
                        alert("Please enter Middle Name!");
                        _FRM.owner_middle_name.focus();
                        return false;
	                 }
                         if( _FRM.owner_first_name.value.length>15)
                        {
                        alert("Invalid number of characters for First Name");
                        _FRM.owner_first_name.focus();
                        _FRM.owner_first_name.select();
                        return false;
                        }
                        if(_FRM.owner_middle_name.value.length>15)
                        {
                        alert("Invalid number of characters for Middle Name");
                        _FRM.owner_middle_name.focus();
                        _FRM.owner_middle_name.select();
                        return false;
                        }
                
               		if( isBlank(_FRM.owner_last_name.value) == true)
	                {
                        alert("Please enter Last Name!");
                        _FRM.owner_last_name.focus();
                        return false;
	                }

			if(_FRM.owner_last_name.value.length>15)
                        {
                        alert("Invalid number of characters for Last Name");
                        _FRM.owner_last_name.focus();
                        _FRM.owner_last_name.select();
                        return false;
                        }

						
		} else {

			        
                                                                                                                                                                                                         
                        if( isBlank(_FRM.owner_last_name.value) == true  && isBlank(_FRM.owner_legal_entity.value)==true)
                        {
                        alert("Please enter Last Name/Legal");
                        _FRM.owner_last_name.focus();
                        return false;
                        }
                                                                                                                                                                                                         
                        if(_FRM.owner_last_name.value.length>50)
                        {
                        alert("Invalid number of characters for Last Name/Entity");
                        _FRM.owner_last_name.focus();
                        _FRM.owner_last_name.select();
                        return false;
                        }

		}
                //if( isBlank(_FRM.owner_house_no.value) == true)
                //{
                //      alert( msgTitle + "Please input a valid house number!");
                //      _FRM.owner_house_no.focus();
                //      return false;
                //}
			/*	if(isValid == true)
                        {
                        alert("xxInvalid Date!\nFormat : yyyy-mm-dd!");
                        _FRM.owner_birth_date.focus();
                        _FRM.owner_birth_date.select();
                        return false;
                        }
						if(isValidBDay == true)
                        {
                        alert("xInvalid Birth Date!\nFormat : yyyy-mm-dd!");
                        _FRM.owner_birth_date.focus();
                        _FRM.owner_birth_date.select();
                        return false;
                        } */
                 if( isBlank(_FRM.owner_street.value) == true)
                {
                        alert("Please enter Address!");
                        _FRM.owner_street.focus();
                        return false;
                }

		if(_FRM.owner_street.value.length>30)
                {
                        alert("Invalid number of characters for Address");
                        _FRM.owner_street.focus();
                        _FRM.owner_street.select();
                        return false;
                }

		
                if( isBlank(_FRM.owner_citizenship.value) == true)
                {
                        alert("Please select citizenship!");
                        _FRM.owner_citizenship.focus();
                        return false;
                }

// 		 if( _FRM.owner_birth_date.value!='')
//                 {

// 			checkdate(_FRM.owner_birth_date);
//                         _FRM.owner_birth_date.focus();
//                         return false;
//                 }

				if( isBlank(_FRM.owner_province_code.value) == true)
                {
                        alert("Please select Province!");
                        _FRM.owner_province_code.focus();
                        return false;
                }
                if( isBlank(_FRM.owner_city_code.value) == true)
                {
                        alert("Please select City!");
                        _FRM.owner_city_code.focus();
                        return false;
                }
                if( isBlank(_FRM.owner_district_code.value) == true)
                {
                        alert("Please select District!");
                        _FRM.owner_district_code.focus();
                        return false;
                }
                if( isBlank(_FRM.owner_barangay_code.value) == true)
                {
                        alert("Please select Barangay!");
                        _FRM.owner_barangay_code.focus();
                        return false;
                }

		if(_FRM.owner_tin_no.value.length>10)
                        {
                        alert("Invalid number of characters for TIN");
                        _FRM.owner_tin_no.focus();
                        _FRM.owner_tin_no.select();
                        return false;
                        }
	
		if(isNaN(_FRM.owner_tin_no.value))
                        {
                        alert("Cannot save record. TIN should be numeric");
                        _FRM.owner_tin_no.focus();
                        return false;
                        }

		if(_FRM.owner_phone_no.value.length>15)
                        {
                        alert( msgTitle + "Cannot save record.\n Telephone No. exceeds max length of 15 numeric characters");
                        _FRM.owner_phone_no.focus();
                        _FRM.owner_phone_no.select();
                        return false;
                        }
                                                                                                                             
                if(isNaN(_FRM.owner_phone_no.value))
                        {
                        alert( msgTitle + "Cannot save record. Telephone Number should be numeric");
                        _FRM.owner_phone_no.focus();
                        return false;
                        }


        //      if( isBlank(_FRM.owner_zone_code.value) == true)
          //      {
            //            alert( msgTitle + "Please input a valid Zone!");
              //          _FRM.owner_zone_code.focus();
                //        return false;
              // }

		   good = false
		if (_FRM.owner_email_address.value!='') {
			 //  checkEmailAddress(_FRM.owner_email_address)
			 
			var good;
			good = false
                                                                                                                             
			// Note: The next expression must be all on one line...
			//       allow no spaces, linefeeds, or carriage returns!
			var goodEmail = _FRM.owner_email_address.value.match(/\b(^(\S+@).+((\.com)|(\.net)|(\.edu)|(\.mil)|(\.gov)|(\.org)|(\..{2,2}))$)\b/gi);
                                                                                                                             
			if (goodEmail)
			{
			   good = true
			} else {
			   alert(msgTitle + 'Please enter a valid e-mail address.')
			   _FRM.owner_email_address.focus()
			   _FRM.owner_email_address.select()
			   good = false
			   return false;
			 }
		   }

	if (_FRM.owner_gsm_no.value!='') {
 	var stripped = _FRM.owner_gsm_no.value.replace(/[\(\)\.\-\ ]/g, '');
 	su = _FRM.owner_gsm_no.value.substr(0,4);
	

	if (_FRM.owner_gsm_no.value.length != 13) {
	alert("Please enter valid mobile number.");
	_FRM.owner_gsm_no.focus()
	_FRM.owner_gsm_no.select()
	return false;
	}

	if (su != '+639') {
	alert("Invalid format for mobile number.");
	_FRM.owner_gsm_no.focus()
	_FRM.owner_gsm_no.select()
	return false;
	}
	
	
 }
   
		   
		   
		   
		 if(_FRM.owner_others.value.length>255)
                        {
                        alert( msgTitle + "Cannot save record.\n Telephone No. exceeds max length of 255 characters");                        _FRM.owner_phone_no.focus();
                        return false;
                        }



                _FRM.pro.value = '1';
                _FRM.addOwner.value='ADD';
                _FRM.search.value='';
                _FRM.blak.value='2';
                _FRM.comm.value='';
                _FRM.mode.value='add';
                _FRM.submit();
                return true;
}



function validate_add_new_business_application()
{
		var _FRM = document._FRM;
		var msgTitle = "Business Permit Application\n";
/*
		
		if( isBlank(_FRM.owner_id.value) == true)
		{
			alert( msgTitle + "Fatal Error : Cant add a business w/o an owner !");
			_FRM.owner_id.focus();
			return false;
		}
*/
		//--- validate the business details
		if( isBlank(_FRM.business_name.value) == true)
		{
			alert( msgTitle + "Please input a valid business name!");
			_FRM.business_name.focus();
			return false;
		}

		if (_FRM.business_name.value.length>150) {
			 alert( msgTitle + "Business name exceeds maximum length!");
                        _FRM.business_name.focus();
                        return false;
		}

		if( isBlank(_FRM.business_branch.value) == true)
		{
			alert( msgTitle + "Please input a valid business branch name!");
			_FRM.business_branch.focus();
			return false;
		}

		if (_FRM.business_branch.value.length>30) {
                         alert( msgTitle + "Business branch exceeds maximum length!");
                        _FRM.business_branch.focus();
                        return false;
                }


		 if (_FRM.business_building_name.value.length>50) {
                         alert( msgTitle + "Building name exceeds maximum length!");
                        _FRM.business_building_name.focus();
                        return false;
                }

/*		if( isBlank(_FRM.business_lot_no.value) == true)
		{
			alert( msgTitle + "Please input a valid business lot #!");
			_FRM.business_lot_no.focus();
			return false;
		}*/
		if( isBlank(_FRM.business_street.value) == true)
		{
			alert( msgTitle + "Please input a valid business street #!");
			_FRM.business_street.focus();
			return false;
		}
 		
		if( isBlank(_FRM.business_district_code.value) == true)
                {
                        alert( msgTitle + "Please input a valid District!");
                        _FRM.business_district_code.focus();
                        return false;
                }
                                                                                                               
                if( isBlank(_FRM.business_barangay_code.value) == true)
                {
                        alert( msgTitle + "Please input a valid Barangay!");
                        _FRM.business_barangay_code.focus();
                        return false;
                }




                /*                                                                                               
                if( isBlank(_FRM.business_zone_code.value) == true)
                {
                        alert( msgTitle + "Please input a valid Zone!");
                        _FRM.business_zone_code.focus();
                        return false;
                }*/

		 if (_FRM.business_contact_no.value.length>15) {
                         alert( msgTitle + "Business contact number exceeds maximum length!");
                        _FRM.business_contact_no.focus();
                        return false;
                }

		 if (_FRM.business_fax_no.value.length>15) {
                         alert( msgTitle + "Business fax number exceeds maximum length!");
                        _FRM.business_fax_no.focus();
                        return false;
                }

		if(_FRM.business_date_established.value=='')
                {
                        alert( msgTitle + "Please input a valid date established!");
                        _FRM.business_date_established.focus();
                        return false;
                }


		if(_FRM.business_start_date.value=='')
                {
                        alert( msgTitle + "Please input a valid start date!");
                        _FRM.business_start_date.focus();
                        return false;
                }


		if( isBlank(_FRM.employees_M.value) == true)
                {
                        alert( msgTitle + "Please input a valid male employees!");
                        _FRM.employees_M.focus();
                        return false;
                }

		if(_FRM.employees_M.value<0)
                {
                        alert( msgTitle + "Please input a valid male employees!");
                        _FRM.employees_M.focus();
                        return false;
                }

		if(isNaN(_FRM.employees_M.value))
                {
                        alert( msgTitle + "Please input a valid male employees!");
                        _FRM.employees_M.focus();
                        return false;
                }		

		if( isBlank(_FRM.employees_F.value) == true)
                {
                        alert( msgTitle + "Please input a valid female employees!");
                        _FRM.employees_F.focus();
                        return false;
                }
                                                                                                                             
                if(_FRM.employees_F.value<0)
                {
                        alert( msgTitle + "Please input a valid female employees!");
                        _FRM.employees_F.focus();
                        return false;
                }
                                                                                                                             
                if(isNaN(_FRM.employees_F.value))
                {
                        alert( msgTitle + "Please input a valid female employees!");
                        _FRM.employees_F.focus();
                        return false;
                }


                if (_FRM.business_email_address.value!='') {
//                           checkEmailAddress(_FRM.business_email_address)

			var good;
			good = false
                                                                                                                             
			// Note: The next expression must be all on one line...
			//       allow no spaces, linefeeds, or carriage returns!
			var goodEmail = _FRM.business_email_address.value.match(/\b(^(\S+@).+((\.com)|(\.net)|(\.edu)|(\.mil)|(\.gov)|(\.org)|(\..{2,2}))$)\b/gi);
                                                                                                                             
			if (goodEmail)
			{
			   good = true
			} else {
			   alert(msgTitle + 'Please enter a valid e-mail address.')
			   _FRM.business_email_address.focus()
			   _FRM.business_email_address.select()
			   good = false
			   return false;
			 }


                }

		 if( isBlank(_FRM.business_no_del_vehicles.value) == true)
                {
                        alert( msgTitle + "Please input a valid delivery vehicles!");
                        _FRM.business_no_del_vehicles.focus();
                        return false;
                }
                                                                                                                             
                if(_FRM.business_no_del_vehicles.value<0)
                {
                        alert( msgTitle + "Please input a valid delivery vehicles!");
                        _FRM.business_no_del_vehicles.focus();
                        return false;
                }
                                                                                                                             
                if(isNaN(_FRM.business_no_del_vehicles.value))
                {
                        alert( msgTitle + "Please input a valid delivery vehicles!");
                        _FRM.business_no_del_vehicles.focus();
                        return false;
                }


		if (_FRM.business_location_desc.value.length>150) {
                         alert( msgTitle + "Business location description exceeds maximum length!");
                        _FRM.business_location_desc.focus();
                        return false;
                }		

		if (_FRM.business_remarks.value.length>255) {
                         alert( msgTitle + "Remarks exceeds maximum length!");
                        _FRM.business_remarks.focus();
                        return false;
                }

		if (_FRM.business_dot_acr_no.value.length>15) {
                         alert( msgTitle + "DOT ACR number exceeds maximum length!");
                        _FRM.business_dot_acr_no.focus();
                        return false;
                }

		if (_FRM.business_sec_reg_no.value.length>15) {
                         alert( msgTitle + "SEC registration number exceeds maximum length!");
                        _FRM.business_sec_reg_no.focus();
                        return false;
                }


		if (_FRM.business_tin_reg_no.value.length>15) {
                         alert( msgTitle + "BIR registration number exceeds maximum length!");
                        _FRM.business_tin_reg_no.focus();
                        return false;
                }

		if (_FRM.business_dti_reg_no.value.length>15) {
                         alert( msgTitle + "DTI registration number exceeds maximum length!");
                        _FRM.business_dti_reg_no.focus();
                        return false;
                }


		if (_FRM.business_nso_assigned_no.value.length>15) {
                         alert( msgTitle + "NSO assigned number exceeds maximum length!");
                        _FRM.business_nso_assigned_no.focus();
                        return false;
                }


		if (_FRM.business_nso_estab_id.value.length>15) {
                         alert( msgTitle + "NSO Established ID exceeds maximum length!");
                        _FRM.business_nso_estab_id.focus();
                        return false;
                }



		if (_FRM.business_main_offc_name.value.length>150) {
                         alert( msgTitle + "Main office name exceeds maximum length!");
                        _FRM.business_main_offc_name.focus();
                        return false;
                }
                                                                                                                             
                if (_FRM.business_main_offc_lot_no.value.length>150) {
                         alert( msgTitle + "Main office lot exceeds maximum length!");
                        _FRM.business_main_offc_lot_no.focus();
                        return false;
                }
                                                                                                                             
                                                                                                                             
                if (_FRM.business_main_offc_street_no.value.length>150) {
                         alert( msgTitle + "Main office street exceeds maximum length!");
                        _FRM.business_main_offc_street_no.focus();
                        return false;
                }
                                                                                                                             
                                                                                                                             
                if (_FRM.business_phone_no.value.length>15) {
                         alert( msgTitle + "Main office telephone number exceeds maximum length!");
                        _FRM.business_phone_no.focus();
                        return false;
                }


		if (_FRM.regname.value.length>150) {
                         alert( msgTitle + "Registered Name exceeds maximum length!");
                        _FRM.regname.focus();
                        return false;
                }


		if (_FRM.pcname.value.length>150) {
                         alert( msgTitle + "Business Type Name exceeds maximum length!");
                        _FRM.pcname.focus();
                        return false;
                }
                                                                                                                             
                                                                                                                             
                if (_FRM.pcaddress.value.length>150) {
                         alert( msgTitle + "Business Type Address exceeds maximum length!");
                        _FRM.pcaddress.focus();
                        return false;
                }


		if(_FRM.paidemp.value<0)
                {
                        alert( msgTitle + "Please input a valid paid employess!");
                        _FRM.paidemp.focus();
                        return false;
                }
                                                                                                                             
                if(isNaN(_FRM.paidemp.value))
                {
                        alert( msgTitle + "Please input a valid paid employess!");
                        _FRM.paidemp.focus();
                        return false;
                }




                _FRM.pro.value = '1';
                                                                                                               



		_FRM.submit();
	 	return true;



}

function IncReq(own,bid)
{
	
        strOption = 'toolbar=0,location=0,directories=0,menubar=0,resizable=0,scrollbars=1,status=1,width=400,height=400'
        window.open ("increq.php?&owner_id=" + own + "&business_id="+ bid , "increq", strOption);

}
function ViewDisApp(own,bid)
{
	
        strOption = 'toolbar=0,location=0,directories=0,menubar=0,resizable=0,scrollbars=1,status=1,width=400,height=400'
        window.open ("disapp.php?&owner_id=" + own + "&business_id="+ bid , "disapp", strOption);

}
function ViewAppHis(own,bid)
{
	
        strOption = 'toolbar=0,location=0,directories=0,menubar=0,resizable=1,scrollbars=1,status=1'
        window.open ("apphis.php?&owner_id=" + own + "&business_id="+ bid , "disapp", strOption);

}
function CheckCap(z,x)
{
	
	if (isNaN(z.value)) {
		alert("Invalid Capital/Gross");
		z.value = x;
		z.focus();
		z.select();
		return false;
	}
}
		
