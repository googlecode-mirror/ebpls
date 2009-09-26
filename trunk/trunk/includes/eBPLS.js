var version = "0";
brwsr = navigator.appName;
brwsrVer = parseInt(navigator.appVersion);
if (brwsr == "Netscape" && brwsrVer >= 3.0) { version = "1"; }
else if (brwsr == "Microsoft Internet Explorer" && brwsrVer >= 3.0) { version = "2"; }
else version = "0";

var now=new Date();
function myDate() {
	var months=new Array(12);
	months[0]="January";
	months[1]="February";
	months[2]="March";
	months[3]="April";
	months[4]="May";
	months[5]="June";
	months[6]="July";
	months[7]="August";
	months[8]="September";
	months[9]="October";
	months[10]="November";
	months[11]="December";
	var lmonth=months[now.getMonth()];
	var date=now.getDate();
	var year=now.getYear();
	if ((now.getYear()-100) < 10) year="200" + (now.getYear() - 100)
	else if (version == "2") year=now.getYear()
	else year="20" + (now.getYear()-100);
	document.write(" " + lmonth + " ");
	document.write(date + ", " + year);
}
function myDay() {
	var mday = new Array(7);
	mday[0]="Sunday";
	mday[1]="Monday";
	mday[2]="Tuesday";
	mday[3]="Wednesday";
	mday[4]="Thursday";
	mday[5]="Friday";
	mday[6]="Saturday";
	var mSDay=mday[now.getDay()];
	document.write("<b>" + mSDay + "</b>");
}

function popwin(purl, pname) {
	newpopwin = window.open(purl, pname, 'toolbar=0,status=0,menubar=0,scrollbars=1,resizable=0,width=700,height=400');
	newpopwin.focus();
}

function popitup(url) {
        newwindow=window.open(url, 'name', 'toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=0,width=700,height=400');
        if (window.focus) {
          newwindow.focus()
        }
}

function popitup2(url) {
        newwindow=window.open(url, 'name', 'toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=0,width=600,height=350');
        if (window.focus) {
          newwindow.focus()
        }
}

function popitup3(url) {
        newwindow=window.open(url, 'name', 'toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=0,width=600,height=250');
        if (window.focus) {
          newwindow.focus()
        }
}

function popitup4(url) {
        newwindow=window.open(url, 'name', 'toolbar=0,location=1,directories=0,status=0,menubar=0,scrollbars=1,resizable=1,width=700,height=400');
        if (window.focus) {
          newwindow.focus()
        }
}

function reloadParent(){
	opener.location.reload(true);
   self.close();
}

function checkSelected() {
	
	box = eval("document.userForm.frmMenu_sys_set"); 
	//alert("BANG "+box);
	if (box.checked == false){
		box.value = 0;
		//alert("IF "+box.value);
	}else{ 
		box.value = 1;
		//alert("ELSE "+box.value);
	}
	
	box = eval("document.userForm.frmMenu_reports"); 
	if (box.checked == false){
		box.value = 0;
	}else{ 
		box.value = 1;
	}
	
	box = eval("document.userForm.frmMenu_user_mngr"); 
	if (box.checked == false){
		box.value = 0;
	}else{ 
		box.value = 1;
	}
	
	box = eval("document.userForm.frmMenu_ctc"); 
	if (box.checked == false){
		box.value = 0;
	}else{ 
		box.value = 1;
	}
	
	box = eval("document.userForm.frmMenu_application"); 
	if (box.checked == false){
		box.value = 0;
	}else{ 
		box.value = 1;
	}
	
	box = eval("document.userForm.frmMenu_assessment"); 
	if (box.checked == false){
		box.value = 0;
	}else{ 
		box.value = 1;
	}
	
	box = eval("document.userForm.frmMenu_payment"); 
	if (box.checked == false){
		box.value = 0;
	}else{ 
		box.value = 1;
	}
	
	box = eval("document.userForm.frmMenu_approval"); 
	if (box.checked == false){
		box.value = 0;
	}else{ 
		box.value = 1;
	}
	
	box = eval("document.userForm.frmMenu_releasing"); 
	if (box.checked == false){
		box.value = 0;
	}else{ 
		box.value = 1;
	}
	
	box = eval("document.userForm.frmMenu_tax_fee"); 
	if (box.checked == false){
		box.value = 0;
	}else{ 
		box.value = 1;
	}
	
	box = eval("document.userForm.frmMenu_chart_of_accts"); 
	if (box.checked == false){
		box.value = 0;
	}else{ 
		box.value = 1;
	}
	
	box = eval("document.userForm.frmMenu_activity_logs"); 
	if (box.checked == false){
		box.value = 0;
	}else{ 
		box.value = 1;
	}
	
	box = eval("document.userForm.frmMenu_db_details"); 
	if (box.checked == false){
		box.value = 0;
	}else{ 
		box.value = 1;
	}

}


function valid(form){
   
    input=document.prefSetForm.relSwitch.options.selectedIndex;
    
	if(input==0){
	 	//alert("VALUE SELECTED==> "+ input );
	 	document.prefSetForm.relOptButton[0].disabled=true;
	 	document.prefSetForm.relOptButton[1].disabled=true;
	 	document.prefSetForm.relOptButton[2].disabled=true;
	 	document.prefSetForm.relOptButton[3].disabled=false;
	 	document.prefSetForm.relOptButton[3].checked=true;
	 	
	}else{
		//alert("VALUE SELECTED==> "+ input );
		document.prefSetForm.relOptButton[0].disabled=false;
		document.prefSetForm.relOptButton[1].disabled=false;
		document.prefSetForm.relOptButton[2].disabled=false;
		document.prefSetForm.relOptButton[3].disabled=true;
		document.prefSetForm.relOptButton[0].checked=true;
		
	}
}

function validDrop(form){
    input=document.userAddForm.desig.options.selectedIndex;
    
	if((input=='1')||(input=='3')){
	 	//alert("VALUE SELECTED==> "+ input );
	 	document.userAddForm.csGrpSel.disabled=false;
	 	
	}else{
		//alert("VALUE ELSE SELECTED==> "+ input );
		document.userAddForm.csGrpSel.disabled=true;
	}
}

function validDropEdit(form){
    input=document.userUpdateForm.desig.options.selectedIndex;
    
	if((input=='1')||(input=='3')){
	 	//alert("VALUE SELECTED==> "+ input );
	 	document.userUpdateForm.csGrpSel.disabled=false;
	 	
	}else{
		//alert("VALUE ELSE SELECTED==> "+ input );
		document.userUpdateForm.csGrpSel.disabled=true;
	}
}

function validDropClient(form){
    input=document.clientAddForm.statFormObj.options.selectedIndex;
    
	if(input=='0'){
	 	//alert("VALUE SELECTED==> "+ input );
	 	document.clientAddForm.resFormObj.selectedIndex=0;
	 	document.clientAddForm.resFormObj.disabled=true;
	 	
	}else{
		//alert("VALUE ELSE SELECTED==> "+ input );
		document.clientAddForm.resFormObj.disabled=false;
	}
}

function validDropClientEdit(form){
	inputStat=document.clientUpdForm.statFormObj.options.selectedIndex;
    input=document.clientUpdForm.statFormObj.options.selectedIndex;
    
	if(input=='0'){
	 	//alert("VALUE SELECTED==> "+ input );
	 	document.clientUpdForm.resFormObj.selectedIndex=0;
	 	document.clientUpdForm.resFormObj.disabled=true;
	 	
	}else{
		//alert("VALUE ELSE SELECTED==> "+ input );
		document.clientUpdForm.resFormObj.disabled=false;
	}
	
	if(inputStat=='2'){
	 	//alert("VALUE SELECTED==> "+ input );
	 	document.clientUpdForm.statFormObj.selectedIndex=2;
	 	document.clientUpdForm.statFormObj.disabled=true;
	 	document.clientUpdForm.resFormObj.disabled=true;
	 	
	}else{
		//alert("VALUE ELSE SELECTED==> "+ input );
		document.clientUpdForm.statFormObj.disabled=false;
	}
	
}

function validDropClientDelete(form){
	//alert("BENJ");
    
	document.clientDeleteForm.planFormObj.disabled=true;
	document.clientDeleteForm.credLimitFormObj.disabled=true;
	document.clientDeleteForm.statFormObj.disabled=true;
	document.clientDeleteForm.resFormObj.disabled=true;
	
}


function validDropReports(form){
    input=document.dailyReportsForm.togAllTxtFormObj.options.selectedIndex;
    
	if(input=='1'){
	 	//alert("VALUE SELECTED==> "+ input );
	 	document.dailyReportsForm.txtAcctFormObj.disabled=true;
	 	
	}else{
		//alert("VALUE ELSE SELECTED==> "+ input );
		document.dailyReportsForm.txtAcctFormObj.disabled=false;
	}
}

//Report functions
//set todays date
    Now = new Date();
    NowDay = Now.getDate();
    NowMonth = Now.getMonth();
    NowYear = Now.getYear();
    
    if (NowYear < 2000) NowYear += 1900; //for Netscape
    
    //function for returning how many days there are in a month including leap years
    function DaysInMonth(WhichMonth, WhichYear) {
      var DaysInMonth = 31;
      if (WhichMonth == "April" || WhichMonth == "June" || WhichMonth == "September" || WhichMonth == "November") DaysInMonth = 30;
      if (WhichMonth == "February" && (WhichYear/4) != Math.floor(WhichYear/4))	DaysInMonth = 28;
      if (WhichMonth == "February" && (WhichYear/4) == Math.floor(WhichYear/4))	DaysInMonth = 29;
      return DaysInMonth;
    }
    
    //function to change the available days in a months
    function ChangeOptionDays(Which) {
      DaysObject = eval("document.dailyReportsForm." + Which + "Day");
      MonthObject = eval("document.dailyReportsForm." + Which + "Month");
      YearObject = eval("document.dailyReportsForm." + Which + "Year");
      
      Month = MonthObject[MonthObject.selectedIndex].text;
      Year = YearObject[YearObject.selectedIndex].text;
      DaysForThisSelection = DaysInMonth(Month, Year);
      CurrentDaysInSelection = DaysObject.length;
      
      if (CurrentDaysInSelection > DaysForThisSelection) {
        for (i=0; i<(CurrentDaysInSelection-DaysForThisSelection); i++) {
          DaysObject.options[DaysObject.options.length - 1] = null
        }
      }
      
      if (DaysForThisSelection > CurrentDaysInSelection) {
        for (i=0; i<(DaysForThisSelection-CurrentDaysInSelection); i++) {
          NewOption = new Option(DaysObject.options.length + 1);
          DaysObject.add(NewOption);
        }
      }
      
      if (DaysObject.selectedIndex < 0) DaysObject.selectedIndex == 0;
    }

    //function to set options to today
    function SetToToday(Which) {
      DaysObject = eval("document.dailyReportsForm." + Which + "Day");
      MonthObject = eval("document.dailyReportsForm." + Which + "Month");
      YearObject = eval("document.dailyReportsForm." + Which + "Year");
      YearObject[0].selected = true;
      MonthObject[NowMonth].selected = true;
      
      ChangeOptionDays(Which);
      
      DaysObject[NowDay-1].selected = true;
    }
    
        
    //function for returning how many days there are in a month including leap years
    function DaysInMonthTo(WhichMonth, WhichYear) {
      var DaysInMonthTo = 31;
      if (WhichMonth == "April" || WhichMonth == "June" || WhichMonth == "September" || WhichMonth == "November") DaysInMonthTo = 30;
      if (WhichMonth == "February" && (WhichYear/4) != Math.floor(WhichYear/4))	DaysInMonthTo = 28;
      if (WhichMonth == "February" && (WhichYear/4) == Math.floor(WhichYear/4))	DaysInMonthTo = 29;
      return DaysInMonthTo;
    }
    
    //function to change the available days in a months. DATE_TO
    function ChangeOptionDaysTo(Which) {
    	
      DaysObject = eval("document.dailyReportsForm." + Which + "Day");
      MonthObject = eval("document.dailyReportsForm." + Which + "Month");
      YearObject = eval("document.dailyReportsForm." + Which + "Year");
      
      Month = MonthObject[MonthObject.selectedIndex].text;
      Year = YearObject[YearObject.selectedIndex].text;
      DaysForThisSelection = DaysInMonth(Month, Year);
      
      CurrentDaysInSelection = DaysObject.length;
      
      if (CurrentDaysInSelection > DaysForThisSelection) {
        for (i=0; i<(CurrentDaysInSelection-DaysForThisSelection); i++) {
          DaysObject.options[DaysObject.options.length - 1] = null
        }
      }
      
      if (DaysForThisSelection > CurrentDaysInSelection) {
        for (i=0; i<(DaysForThisSelection-CurrentDaysInSelection); i++) {
          NewOption = new Option(DaysObject.options.length + 1);
          DaysObject.add(NewOption);
        }
      }
      
      if (DaysObject.selectedIndex < 0) DaysObject.selectedIndex == 0;
    }

    //function to set options to today. DATE_TO
    function SetToTodayTo(Which) {
      DaysObject = eval("document.dailyReportsForm." + Which + "Day");
      MonthObject = eval("document.dailyReportsForm." + Which + "Month");
      YearObject = eval("document.dailyReportsForm." + Which + "Year");
      YearObject[0].selected = true;
      MonthObject[NowMonth].selected = true;
      
     
      ChangeOptionDaysTo(Which);
      
      DaysObject[NowDay-1].selected = true;
    }
    
    //function to change the available days in a months CLIENT_ADD
    function ChangeOptionDaysADD(Which) {
      DaysObject = eval("document.clientAddForm." + Which + "Day");
      MonthObject = eval("document.clientAddForm." + Which + "Month");
      YearObject = eval("document.clientAddForm." + Which + "Year");
      
      Month = MonthObject[MonthObject.selectedIndex].text;
      Year = YearObject[YearObject.selectedIndex].text;
      DaysForThisSelection = DaysInMonth(Month, Year);
      CurrentDaysInSelection = DaysObject.length;
      
      if (CurrentDaysInSelection > DaysForThisSelection) {
        for (i=0; i<(CurrentDaysInSelection-DaysForThisSelection); i++) {
          DaysObject.options[DaysObject.options.length - 1] = null
        }
      }
      
      if (DaysForThisSelection > CurrentDaysInSelection) {
        for (i=0; i<(DaysForThisSelection-CurrentDaysInSelection); i++) {
          NewOption = new Option(DaysObject.options.length + 1);
          DaysObject.add(NewOption);
        }
      }
      
      if (DaysObject.selectedIndex < 0) DaysObject.selectedIndex == 0;
    }

    //function to set options to today CLIENT_ADD
    function SetToTodayADD(Which) {
      DaysObject = eval("document.clientAddForm." + Which + "Day");
      MonthObject = eval("document.clientAddForm." + Which + "Month");
      YearObject = eval("document.clientAddForm." + Which + "Year");
      YearObject[0].selected = true;
      MonthObject[NowMonth].selected = true;
      
      ChangeOptionDaysADD(Which);
      
      DaysObject[NowDay-1].selected = true;
    }
    
    //function to change the available days in a months CLIENT_UPD
    function ChangeOptionDaysUPD(Which) {
      DaysObject = eval("document.clientUpdForm." + Which + "Day");
      MonthObject = eval("document.clientUpdForm." + Which + "Month");
      YearObject = eval("document.clientUpdForm." + Which + "Year");
      
      Month = MonthObject[MonthObject.selectedIndex].text;
      Year = YearObject[YearObject.selectedIndex].text;
      DaysForThisSelection = DaysInMonth(Month, Year);
      CurrentDaysInSelection = DaysObject.length;
      
      if (CurrentDaysInSelection > DaysForThisSelection) {
        for (i=0; i<(CurrentDaysInSelection-DaysForThisSelection); i++) {
          DaysObject.options[DaysObject.options.length - 1] = null
        }
      }
      
      if (DaysForThisSelection > CurrentDaysInSelection) {
        for (i=0; i<(DaysForThisSelection-CurrentDaysInSelection); i++) {
          NewOption = new Option(DaysObject.options.length + 1);
          DaysObject.add(NewOption);
        }
      }
      
      if (DaysObject.selectedIndex < 0) DaysObject.selectedIndex == 0;
    }

    //function to set options to today CLIENT_UPD
    function SetToTodayUPD(Which) {
      DaysObject = eval("document.clientUpdForm." + Which + "Day");
      MonthObject = eval("document.clientUpdForm." + Which + "Month");
      YearObject = eval("document.clientUpdForm." + Which + "Year");
      YearObject[0].selected = true;
      MonthObject[NowMonth].selected = true;
      
      ChangeOptionDaysUPD(Which);
      
      DaysObject[NowDay-1].selected = true;
    }
    
    //function to write option years plus x
    function WriteYearOptions(YearsAhead) {
      line = "";
      for (i=0; i<YearsAhead; i++) {
        line += "<OPTION value=" + NowYear + ">";
        line += NowYear + i;
      }
      return line;
    }
    
    //function to write option years plus x DATE_TO
    function WriteYearOptionsTo(YearsAhead) {
      line = "";
      for (i=0; i<YearsAhead; i++) {
        line += "<OPTION value=" + NowYear + ">";
        line += NowYear + i;
      }
      return line;
    }
//Colour Functions
function getColour(form){
    input=document.prefSetForm.priColor.options.value;
    alert("COLOR VALUE SELECTED==> "+ input );
    colPass=document.prefSetForm.changeColor.value = input;
    alert("COLOR VALUE PASS ==> "+ colPass );
}


