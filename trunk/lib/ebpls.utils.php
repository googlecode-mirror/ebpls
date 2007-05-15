<?php
//-------------------------------------------------------------------
//	utility functions
//-------------------------------------------------------------------

//--- connect to DB
define('DB_HOST1','192.168.100.1');
define('DB_NAME1','ebpls');
define('DB_USER1','root');
define('DB_PASS1','elguebpls');

define("eBPLS_APP_NAME", "eBPLS");
define("eBPLS_APP_VERSION", "1.0");
define("eBPLS_APP_URL", "http://192.168.100.1/ebpls/");       // do not include filenames
//define("eBPLS_APP_URL", "http://localhost/ebpls-site/");      // do not include filenames
                                                                                                 
//      String Length of GSM Number
define("eBPLS_GSMNUM_LEN", 12);
                                                                                                 
//      Alert Strings: System developers' contact variables
define("eBPLS_MAIL_WEBMASTER", "vdsa_15@yahoo.com,asuquev@dap.edu.ph,radomingo@dap.edu.ph,bobbet_a_domingo@yahoo.com");     // does not support multiple email addresses
define("eBPLS_GSM_WEBMASTER", "+639193354369");  // no spaces (" ") please!!!
                                                                                                 
//      Module Index Handler
define("eBPLS_MODULE_FNAME", "ebplsNNN.php");


/*########################################################################################*/

function get_select_data_biz($dblink,$selectname,$table,$field_code,$field_desc,$selected='',$editable=true,$where = '')
{
        if ($field_desc=='naturedesc') {
                $orderby = 'order by naturedesc';
        } else {
                $orderby = '';
        }
        $sql            = "SELECT $field_code,$field_desc FROM $table $orderby";
        if ( $where ) {
                $sql .= " WHERE $where  ";
        }
        $resultset      = @mysql_query($sql, $dblink);
        $select_str     .= "<select name='$selectname' onchange='javascript:flagchange(changeondin);' class='select200' " . (($editable)?"":"disabled readonly") . " >";
        //--- set the default
        //$select_str .=   "<option value='$K' $str_selected>$V</option>\n";
        //"<option value=' ' $str_selected> ----- </option>\n";
        while($datarow  = @mysql_fetch_assoc($resultset))
        {
                $K      = $datarow["$field_code"];
                $V      = $datarow["$field_desc"];
                $str_selected = (! strcasecmp($K,"$selected")) ? ('selected') : ('');
                $select_str .=   "<option value='$K' $str_selected>$V</option>\n";
        }
        $select_str   .=  "</select>";
        //--- free mem
        if($result)
                @mysql_free_result($result);
        return $select_str;
}


function get_select_data($dblink,$selectname,$table,$field_code,$field_desc,$selected='',$editable=true,$where = '',$javasc)
{
	if ($field_desc=='naturedesc' and $selectname<>'fromnat'){
		$orderby = 'order by naturedesc';
	} elseif ($field_desc=='report_desc' and $selectname<>'fromnat'){
		//$orderby = 'order by report_desc';
	} else {
		$orderby = '';
	}	

	$sql    	= "SELECT $field_code,$field_desc FROM $table $orderby";
	
	if ( $where ) {
	
		$sql .= " WHERE $where	";
		
	}
	//echo $sql;
	//log_err("SQL $sql");
 //echo "$sql=====$selected";	
	$resultset 	= @mysql_query($sql, $dblink);
	$select_str     .= "<select name='$selectname' $javasc  class='select200' " . (($editable)?"":"disabled readonly") . " >";
	//--- set the default
	if($selectname=='employer_business' || $selectname=='fromnat' || $selectname == 'bus_selecty')
	{
	//$select_str .=   "<option value='$K' $str_selected>$V</option>\n";
	$select_str .= "<option value='0' $str_selected> ----- </option>\n";
	}
	while($datarow 	= @mysql_fetch_assoc($resultset))
	{
		$K	= $datarow["$field_code"];
		$V	= $datarow["$field_desc"];
				
		$str_selected = (! strcasecmp($K,"$selected")) ? ('selected') : ('');
		$select_str .=   "<option value='$K' $str_selected>$V</option>\n";
	}
	
	$select_str   .=  "</select>";
	//--- free mem
	if($result)
		@mysql_free_result($result);
	return $select_str;	
}

function get_select_user($dblink,$selectname,$table,$field_code,$field_desc,$selected='',$editable=true,$where = '',$javasc)
{
	

	$sql    	= "SELECT $field_code,concat(lastname,', ', firstname) as fulln FROM $table ";
	
	if ( $where ) {
	
		$sql .= " WHERE $where	";
		
	}
	//echo $sql;
	$resultset 	= @mysql_query($sql, $dblink);
	$select_str     .= "<select name='$selectname' $javasc  class='select200' " . (($editable)?"":"disabled readonly") . " >";
	//--- set the default
	if($selectname=='employer_business' || $selectname=='fromnat' || $selectname == 'bus_selecty')
	{
	//$select_str .=   "<option value='$K' $str_selected>$V</option>\n";
	$select_str .= "<option value='0' $str_selected> ----- </option>\n";
	}
	while($datarow 	= @mysql_fetch_assoc($resultset))
	{
		$K	= $datarow["$field_code"];
		$V	= $datarow["fulln"];
				
		$str_selected = (! strcasecmp($K,"$selected")) ? ('selected') : ('');
		$select_str .=   "<option value='$K' $str_selected>$V</option>\n";
	}
	
	$select_str   .=  "</select>";
	//--- free mem
	if($result)
		@mysql_free_result($result);
	return $select_str;	
}

function get_select_complex_reg($dblink,$selectname,$table,$field_code,$field_desc,$selected='',$editable=true,$where = '',$javasc)
{
	if ($field_desc=='naturedesc' and $selectname<>'fromnat'){
		$orderby = 'order by naturedesc';
	} elseif ($field_desc=='report_desc' and $selectname<>'fromnat'){
		//$orderby = 'order by report_desc';
	} else {
		$orderby = '';
	}	

	$sql    	= "SELECT $field_code,$field_desc FROM $table $orderby";
	
	if ( $where ) {
	
		$sql .= " WHERE $where	";
		
	}
	//echo $sql;
	//log_err("SQL $sql");
 //echo "$sql=====$selected";	
	$resultset 	= @mysql_query($sql, $dblink);
	$select_str     .= "<select name='$selectname'  $javasc  class='select200' " . (($editable)?"":"disabled readonly") . " >";
	//--- set the default
	if($selectname=='employer_business' || $selectname=='fromnat' || $selectname == 'bus_selecty')
	{
	//$select_str .=   "<option value='$K' $str_selected>$V</option>\n";
	$select_str .= "<option value='0' $str_selected> ----- </option>\n";
	}
	while($datarow 	= @mysql_fetch_assoc($resultset))
	{
		$K	= $datarow["$field_code"];
		$V	= $datarow["$field_desc"];
				
		$str_selected = (! strcasecmp($K,"$selected")) ? ('selected') : ('');
		$select_str .=   "<option value='$K' $str_selected>$V</option>\n";
	}
	
	$regfee = mysql_query("select * from ebpls_buss_tfo where tfoindicator=1 and taxfeetype<>1 order by tfodesc");
	while($datarow 	= @mysql_fetch_assoc($regfee))
	{
		$K	= $datarow["tfoid"];
		$V	= $datarow["tfodesc"];
				
		$str_selected = (! strcasecmp($K,"$selected")) ? ('selected') : ('');
		$select_str .=   "<option value='$K' $str_selected>$V</option>\n";
	}
	
	$select_str   .=  "</select>";
	//--- free mem
	if($result)
		@mysql_free_result($result);
	return $select_str;	
}


function get_select_reports($dblink,$selectname,$table,$field_code,$field_desc,$selected='',$editable=true,$where = '',$javasc,$useid)
{
	
	$sql    	= "SELECT $field_code,$field_desc FROM $table $orderby";
	
	if ( $where ) {
	
		$sql .= " WHERE $where	";
		
	}
	
	
 //echo "$sql=====$selected";	
	$resultset 	= @mysql_query($sql, $dblink);
	$select_str     .= "<select name='$selectname' $javasc  class='select200' " . (($editable)?"":"disabled readonly") . " >";
	//--- set the default
	$select_str .=   "<option value='' >------</option>\n";
	while($datarow 	= @mysql_fetch_assoc($resultset))
	{
		$K	= $datarow["$field_code"];
		$V	= $datarow["$field_desc"];
				
		$str_selected = (! strcasecmp($K,"$selected")) ? ('selected') : ('');
					$getlevel = mysql_query("select * from ebpls_user_sublevel where
										submenu = '$V'");
					$getme = mysql_fetch_assoc($getlevel);
					$user_id = $useid;
					
					include "includes/reportlevel.php";
					
					if ($$getme[rptvars]==1) {
		$select_str .=   "<option value='$K' $str_selected>$V</option>\n";
					}
	}
	
	$select_str   .=  "</select>";
	//--- free mem
	if($result)
		@mysql_free_result($result);
	return $select_str;	
}

function get_select_data_ko($dblink,$selectname,$table,$field_code,$field_desc,$selected='',$editable=true,$where = '',$javasc)
{
	if ($field_desc=='naturedesc' and $selectname<>'fromnat'){
		$orderby = 'order by naturedesc';
	} elseif ($field_desc=='report_desc' and $selectname<>'fromnat'){
		//$orderby = 'order by report_desc';
	} else {
		$orderby = '';
	}	

	$sql    	= "SELECT $field_code,$field_desc FROM $table $orderby";
	
	if ( $where ) {
	
		$sql .= " WHERE $where	";
		
	}
	
	//log_err("SQL $sql");
 //echo "$sql=====$selected";	
	$resultset 	= @mysql_query($sql, $dblink);
	$select_str     .= "<select name='$selectname' $javasc  class='select200' " . (($editable)?"":"disabled readonly") . " >";
	//--- set the default
	if($selectname=='employer_business' || $selectname=='fromnat' || $selectname == 'bus_selecty')
	{
	//$select_str .=   "<option value='$K' $str_selected>$V</option>\n";
	$select_str .= "<option value='0' $str_selected> ----- </option>\n";
	}
	while($datarow 	= @mysql_fetch_assoc($resultset))
	{
		$K	= $datarow["$field_code"];
		$V	= $datarow["$field_desc"];
				
		$str_selected = (! strcasecmp($K,"$selected")) ? ('selected') : ('');
		$select_str .=   "<option value='$K' $str_selected>$V</option>\n";
	}
	
	$select_str   .=  "</select>";
	//--- free mem
	if($result)
		@mysql_free_result($result);
	return $select_str;	
}



function get_hidden_data($dblink,$selectname,$table,$field_code,$field_desc,$selected='',$editable=true,$where = '',$javasc)
{
        if ($field_desc=='naturedesc' and $selectname<>'fromnat'){
                $orderby = 'order by naturedesc';
        } else {
                $orderby = '';
        }

        $sql            = "SELECT $field_code,$field_desc FROM $table $orderby";

        if ( $where ) {

                $sql .= " WHERE $where  ";

        }

        //log_err("SQL $sql");
// echo "$sql=====$selected";
        $resultset      = @mysql_query($sql, $dblink);
	$datarow  = @mysql_fetch_assoc($resultset);
        
                $K      = $datarow["$field_code"];
                $V      = $datarow["$field_desc"];

        $select_str     .= "<input type=hidden name='$selectname' value='$K'>";
        //--- set the default
        //--- free mem
        if($result)
                @mysql_free_result($result);
        return $select_str;
}





function get_select_brgy($dblink,$selectname,$table,$field_code,$field_desc,$selected='',$editable=true,$where = '')
{
        if ($field_desc=='naturedesc') {
         //       $orderby = 'order by naturedesc';
        } else {
                $orderby = '';
        }
        $sql            = "SELECT $field_code,$field_desc FROM $table $orderby";
        if ( $where ) {
                $sql .= " WHERE $where  ";
        }
//echo $sql;
        $resultset      = @mysql_query($sql, $dblink);
        $select_str     .= "<select name='$selectname'  class='select200' " . (($editable)?"":"disabled readonly") . " >";
        //--- set the default
      //  $select_str .=   "<option value='$K' $str_selected>$V</option>\n";
        $select_str .="<option value='' $str_selected> ----- </option>\n";
        while($datarow  = @mysql_fetch_assoc($resultset))
        {
                $K      = $datarow["$field_code"];
                $V      = ucfirst($datarow["$field_desc"]);
                $str_selected = (! strcasecmp($K,"$selected")) ? ('selected') : ('');
                $select_str .=   "<option value='$K' $str_selected>$V</option>\n";
        }
        $select_str   .=  "</select>";
        //--- free mem
        if($result)
                @mysql_free_result($result);
        return $select_str;
}

function get_select_data_where($dblink,$selectname,$table,$field_code,$field_desc,$selected='',$where)
{
	$sql    	= "SELECT distinct($field_code),$field_desc FROM $table WHERE $where";
//echo $sql;
	$resultset 	= @mysql_query($sql, $dblink);
	$select_str     .= "<select name='$selectname'  class='select200'>";
	while($datarow 	= @mysql_fetch_assoc($resultset))
	{
		$K	= $datarow["$field_code"];
		$V	= $datarow["$field_desc"];
		$str_selected = (! strcasecmp($K,"$selected")) ? ('selected') : ('');
		$select_str .=   "<option value='$K' $str_selected>$V</option>\n";
	}
	$select_str   .=  "</select>";
	//--- free mem
	if($result)
		@mysql_free_result($result);
	return $select_str;	
}
function get_select_data_boat($dblink,$selectname,$table,$field_code,$field_desc,$selected='',$where)
{
	$sql    	= "SELECT distinct($field_code),$field_desc FROM $table WHERE $where";
//echo $sql;
	$resultset 	= @mysql_query($sql, $dblink);
	$select_str     .= "<select name='$selectname'  class='select200'>";
	while($datarow 	= @mysql_fetch_assoc($resultset))
	{
		$getengine = @mysql_query("select * from ebpls_engine_type where engine_type_id = '$datarow[$field_code]'");
		$getengine1 = @mysql_fetch_assoc($getengine);
		$K	= $datarow["$field_code"];
		$V	= $getengine1["engine_type_desc"];
		$str_selected = (! strcasecmp($K,"$selected")) ? ('selected') : ('');
		$select_str .=   "<option value='$K' $str_selected>$V</option>\n";
	}
	$select_str   .=  "</select>";
	//--- free mem
	if($result)
		@mysql_free_result($result);
	return $select_str;	
}
function set_form_date($value,$nam=1,$plus=0)
{
	$yy = '';
	$mm = '';
	$dd = '';

	$r_dates1 = split("-",$value);
	
	if(strlen($r_dates1[0]) == 4) 
	{
		$yy = $r_dates1[0];
		$mm = $r_dates1[1];
		$dd = $r_dates1[2];	
	}
	else if(strlen($r_dates1[2]) == 4)
	{
		$mm = $r_dates1[0];
		$dd = $r_dates1[1];
		$yy = $r_dates1[2];	
	}
	else
	{
		
		if(strlen($value) == 6)
		{
			//-- mmddyy
			$mm = substr($value,0,2);
			$dd = substr($value,2,2);
			$yy = 1900 + intval(substr($value,4,2));
		}
	}
	//--- get the curdate
	$today  = getdate(); 
	$month  = $today['mon']; 
	$mday   = $today['mday']; 
	$year   = $today['year']+$plus; 
	
	
	$str_year = "<select name='_YEAR$nam'   onchange='javascript:flagchange(changeondin);'>";
		for($i=1900;$i<=$year;$i++)
		{
		   if(strlen($yy) == 4)
		   	$selected = ($i==$yy) ? ('selected') : ('');
		   else
		   	$selected = ($i==$year) ? ('selected') : ('');
		   $str_year .="<option value='$i' $selected >$i</option>\n";
		}
	$str_year .= "</select>";
	
	$selected  = '';
	$str_month = "<select name='_MONTH$nam'  onchange='javascript:flagchange(changeondin);'>";
		for($i=1;$i<=12;$i++)
		{
			
			$j = ($i<10) ? ("0$i") : ($i);
			if(strlen($mm) == 2)
		   		$selected = ($j == $mm) ? ('selected') : ('');
		   	else
		   		$selected = ($j == $month) ? ('selected') : ('');
		        $str_month .= "<option value='$j' $selected>$j</option>\n";
		}
	$str_month .= "</select>";
	$selected   = '';
	$str_day    = "<select name='_DAY$nam'    onchange='javascript:flagchange(changeondin);'>";
		for($i=1;$i<=31;$i++)
		{
		   $j = ($i<10) ? ("0$i") : ($i);
		   if(strlen($mm) == 2)
		   	$selected = ($j == $dd) ? ('selected') : ('');
		   else
		   	$selected = ($j == $mday) ? ('selected') : ('');
		   $str_day .= "<option value='$j' $selected>$j</option>\n";
		}
	$str_day    .= "</select>";
	
	echo $str_year.'&nbsp;'.$str_month.'&nbsp;'.$str_day;
}

function setUrlRedirect($url)
{
	echo "<script language='Javascript'>window.location.href='$url';</script>";
}

function get_select_prov($dblink,$selectname,$table,$field_code,$field_desc,$selected,$where)
{
		$sql= "SELECT $field_code,$field_desc FROM $table ";
		//log_err("SQL $sql");
        $resultset      = @mysql_query($sql);
/*        $select_str     .= "<select name='$selectname'  $locksel  class='select200' 
	onchange='javascript: flagchange(changeondin); _FRM.pro.value=0; _FRM.submit(); '>";

*/
	$select_str     .= "<select name='$selectname'  $locksel  id=dhtmlgoodies_country
              onchange='getCityList(this); flagchange(changeondin); _FRM.pro.value=0;'>";

        //--- set the default
        //$select_str .=   "<option value='$K' $str_selected>$V</option>\n";
       $select_str .= "<option value='' $str_selected> -Please Select Province- </option>\n";
        while($datarow  = @mysql_fetch_assoc($resultset))
        {
                $K      = $datarow["$field_code"];
                $V      = $datarow["$field_desc"];
                $str_selected = (! strcasecmp($K,"$selected")) ? ('selected') : ('');
                $select_str .=   "<option value='$K' $str_selected>$V</option>\n";
        }
        $select_str   .=  "</select>";
        //--- free mem

        if($result)
                @mysql_free_result($result);
        return $select_str;
}

function get_select_prov1($dblink,$selectname,$table,$field_code,$field_desc,$selected,$where)
{
                $sql= "SELECT $field_code,$field_desc FROM $table ";
                //log_err("SQL $sql");
        $resultset      = @mysql_query($sql);
		$select_str     .= "<select name='$selectname'  $locksel  class='select200'
        onchange='javascript: flagchange(changeondin); _FRM.pro.value=0; _FRM.submit(); '>";



        //--- set the default
        //$select_str .=   "<option value='$K' $str_selected>$V</option>\n";
		$select_str .= "<option value=\"\"> -Please Select Province- </option>\n";
        while($datarow  = @mysql_fetch_assoc($resultset))
        {
                $K      = $datarow["$field_code"];
                $V      = $datarow["$field_desc"];
				if ($selected == $K) {
					$str_selected = "selected";
				} else {
					$str_selected = "";
				}
				//$str_selected = (! strcasecmp($K,"$selected")) ? ('selected') : ('');
                $select_str .=   "<option value='$K' $str_selected>$V</option>\n";
        }
        $select_str   .=  "</select>";
        //--- free mem

        if($result)
                @mysql_free_result($result);
        return $select_str;
}




function get_select_prov_ajax($dblink,$selectname,$table,$field_code,$field_desc,$selected,$where)
{
                $sql= "SELECT $field_code,$field_desc FROM $table ";
                //log_err("SQL $sql");
        $resultset      = @mysql_query($sql);
/*        $select_str     .= "<select name='$selectname'  $locksel  class='select200'
        onchange='javascript: flagchange(changeondin); _FRM.pro.value=0; _FRM.submit(); '>";
                                                                                                                             
*/
        $select_str     .= "<select name='$selectname'  $locksel  id=dhtmlgoodies_country
              onchange='getMainCityList(this); flagchange(changeondin); _FRM.pro.value=0;'>";
                                                                                                                             
        //--- set the default
        //$select_str .=   "<option value='$K' $str_selected>$V</option>\n";
       $select_str .= "<option value='' $str_selected> -Please Select Province- </option>\n";
        while($datarow  = @mysql_fetch_assoc($resultset))
        {
                $K      = $datarow["$field_code"];
                $V      = $datarow["$field_desc"];
                $str_selected = (! strcasecmp($K,"$selected")) ? ('selected') : ('');
                $select_str .=   "<option value='$K' $str_selected>$V</option>\n";
        }
        $select_str   .=  "</select>";
        //--- free mem
                                                                                                                             
        if($result)
                @mysql_free_result($result);
        return $select_str;
}


function get_select_province($dblink,$selectname,$table,$field_code,$field_desc,$selected,$where)
{
		$sql= "SELECT $field_code,$field_desc FROM $table ";
		//log_err("SQL $sql");
        $resultset      = @mysql_query($sql);
        $select_str     .= "<select name='$selectname'  $locksel  class='select200' 
		onchange='javascript: flagchange(changeondin); _FRM.pro.value=0; _FRM.submit(); '>";
        //--- set the default
        //$select_str .=   "<option value='$K' $str_selected>$V</option>\n";
        $select_str .= "<option value='' $str_selected></option>\n";
        while($datarow  = @mysql_fetch_assoc($resultset))
        {
                $K      = $datarow["$field_code"];
                $V      = $datarow["$field_desc"];
                $str_selected = (! strcasecmp($K,"$selected")) ? ('selected') : ('');
                $select_str .=   "<option value='$K' $str_selected>$V</option>\n";
        }
        $select_str   .=  "</select>";
        //--- free mem

        if($result)
                @mysql_free_result($result);
        return $select_str;
}

function get_select_city($dblink,$selectname,$table,$field_code,$field_desc,$selected,$where)
{

	if ($where=='') {
		$locksel='disabled';
	} else {
		$locksel ='';
	}
	$sql            = "SELECT $field_code,$field_desc FROM $table ";
//        if ( $where ) {
                $sql .= " WHERE upper = '$where'  ";
  //      }

        //log_err("SQL $sql");
        $resultset      = @mysql_query($sql, $dblink);
        $select_str     .= "<select name='$selectname'  $locksel  class='select200'
                onChange='javascript: flagchange(changeondin); _FRM.pro.value=0; _FRM.submit();'>";
        //--- set the default
        //$select_str .=   "<option value='$K' $str_selected>$V</option>\n";
        //"<option value=' ' $str_selected> ----- </option>\n";
	$select_str .= "<option value='' $str_selected> -Please Select LGU- </option>\n";
        while($datarow  = @mysql_fetch_assoc($resultset))
        {
                $K      = $datarow["$field_code"];
                $V      = $datarow["$field_desc"];
                $str_selected = (! strcasecmp($K,"$selected")) ? ('selected') : ('');
                $select_str .=   "<option value='$K' $str_selected>$V</option>\n";
        }
        $select_str   .=  "</select>";
        //--- free mem
        if($result)
                @mysql_free_result($result);
        return $select_str;
}
function get_select_lgu($selectname,$selected)
{

	if ($where=='') {
		$locksel='disabled';
	} else {
		$locksel ='';
	}
	$sql = "SELECT * FROM ebpls_city_municipality ";

        $resultset      = @mysql_query($sql);
        $select_str     .= "<select name='$selectname'  class='select200'
                onChange='javascript: flagchange(changeondin); _FRM.pro.value=0; _FRM.submit();'>";
	$select_str     .= "<option value=''></option>";
		while($datarow  = @mysql_fetch_assoc($resultset))
        {
	        $province = @mysql_query("select * from ebpls_province where province_code = $datarow[upper]");
	        $get_province = @mysql_fetch_assoc($province);
                $K      = $datarow["city_municipality_code"];
                $V      = "$datarow[city_municipality_desc]-$get_province[province_desc]";
                $str_selected = (! strcasecmp($K,"$selected")) ? ('selected') : ('');
                $select_str .=   "<option value='$K' $str_selected>$V</option>\n";
        }
        $select_str   .=  "</select>";
        //--- free mem
        if($result)
                @mysql_free_result($result);
        return $select_str;
}
function get_select_district($selectname,$selected)
{

	if ($where=='') {
		$locksel='disabled';
	} else {
		$locksel ='';
	}
	$sql = "SELECT * FROM ebpls_district ";

        $resultset      = @mysql_query($sql);
        $select_str     .= "<select name='$selectname'  class='select200'
                onChange='javascript: flagchange(changeondin); _FRM.pro.value=0; _FRM.submit();'>";
        $select_str .=   "<option value=''></option>\n";
		while($datarow  = @mysql_fetch_assoc($resultset))
        {
	        $lgu = @mysql_query("select * from ebpls_city_municipality where city_municipality_code = '$datarow[upper]'");
	        $get_lgu = @mysql_fetch_assoc($lgu);
                $K      = $datarow["district_code"];
                $V      = "$datarow[district_desc]-$get_lgu[city_municipality_desc]";
                $str_selected = (! strcasecmp($K,"$selected")) ? ('selected') : ('');
                $select_str .=   "<option value='$K' $str_selected>$V</option>\n";
        }
        $select_str   .=  "</select>";
        //--- free mem
        if($result)
                @mysql_free_result($result);
        return $select_str;
}
function get_select_barangay($selectname,$selected)
{

	if ($where=='') {
		$locksel='disabled';
	} else {
		$locksel ='';
	}
	$sql = "SELECT * FROM ebpls_barangay ";

        $resultset      = @mysql_query($sql);
        $select_str     .= "<select name='$selectname'  class='select200'
                onChange='javascript: flagchange(changeondin); _FRM.pro.value=0; _FRM.submit();'>";
        $select_str .=   "<option value=''></option>\n";
		while($datarow  = @mysql_fetch_assoc($resultset))
        {
	        $lgu = @mysql_query("select * from ebpls_district where district_code = '$datarow[upper]'");
	        $get_lgu = @mysql_fetch_assoc($lgu);
                $K      = $datarow["barangay_code"];
                $V      = "$datarow[barangay_desc]-$get_lgu[district_desc]";
                $str_selected = (! strcasecmp($K,"$selected")) ? ('selected') : ('');
                $select_str .=   "<option value='$K' $str_selected>$V</option>\n";
        }
        $select_str   .=  "</select>";
        //--- free mem
        if($result)
                @mysql_free_result($result);
        return $select_str;
}
function get_select_dist($dblink,$selectname,$table,$field_code,$field_desc,$selected,$where)
{



        if ($where=='') {
                $locksel='disabled';
        } else {
                $locksel ='';
        }
        $sql            = "SELECT $field_code,$field_desc FROM $table ";
        if ( $where ) {
                $sql .= " WHERE upper = '$where'  ";
        }

        //log_err("SQL $sql");
        $resultset      = @mysql_query($sql, $dblink);
        $select_str     .= "<select name='$selectname'  $locksel  class='select200'
                onChange='javascript: flagchange(changeondin); _FRM.pro.value=0; _FRM.submit();'>";
        //--- set the default
        //$select_str .=   "<option value='$K' $str_selected>$V</option>\n";
        //"<option value=' ' $str_selected> ----- </option>\n";
$select_str .= "<option value='' $str_selected> -Please Select District- </option>\n";
        while($datarow  = @mysql_fetch_assoc($resultset))
        {
                $K      = $datarow["$field_code"];
                $V      = $datarow["$field_desc"];
                $str_selected = (! strcasecmp($K,"$selected")) ? ('selected') : ('');
                $select_str .=   "<option value='$K' $str_selected>$V</option>\n";
        }
        $select_str   .=  "</select>";
        //--- free mem
        if($result)
                @mysql_free_result($result);
        return $select_str;
}

function get_select_dist_ajax($dblink,$selectname,$table,$field_code,$field_desc,$selected,$where)
{
        if ($where=='') {
                $locksel='disabled';
        } else {
                $locksel ='';
        }
        $sql            = "SELECT $field_code,$field_desc FROM $table ";
        if ( $where ) {
                $sql .= " WHERE upper = '$where'  ";
        }
                                                                                                                                                                                                         
        //log_err("SQL $sql");
        $resultset      = @mysql_query($sql, $dblink);
        $select_str     .= "<select name='$selectname'  $locksel  class='select200'
                onChange='getBrgyList(this); flagchange(changeondin); _FRM.pro.value=0;'>";// _FRM.pro.value=0; _FRM.submit();'>";
        //--- set the default
        //$select_str .=   "<option value='$K' $str_selected>$V</option>\n";
        //"<option value=' ' $str_selected> ----- </option>\n";
$select_str .= "<option value='' $str_selected> -Please Select District- </option>\n";
        while($datarow  = @mysql_fetch_assoc($resultset))
        {
                $K      = $datarow["$field_code"];
                $V      = $datarow["$field_desc"];
                $str_selected = (! strcasecmp($K,"$selected")) ? ('selected') : ('');
                $select_str .=   "<option value='$K' $str_selected>$V</option>\n";
        }
        $select_str   .=  "</select>";
        //--- free mem
        if($result)
                @mysql_free_result($result);
        return $select_str;
}



function get_select_barg($dblink,$selectname,$table,$field_code,$field_desc,$selected,$where)
{
                                                                                                                                                            
        if ($where=='') {
                $locksel='disabled';
        } else {
                $locksel ='';
        }
        $sql            = "SELECT $field_code,$field_desc FROM $table ";
        if ( $where ) {
                $sql .= " WHERE upper = '$where'  ";
        }
        //log_err("SQL $sql");
        $resultset      = @mysql_query($sql, $dblink);
        $select_str     .= "<select name='$selectname'  $locksel  class='select200'
                onChange='javascript: flagchange(changeondin); _FRM.pro.value=0; _FRM.submit();'>";
        //--- set the default
        //$select_str .=   "<option value='$K' $str_selected>$V</option>\n";
        //"<option value=' ' $str_selected> ----- </option>\n";
$select_str .= "<option value='' $str_selected> -Please Select Barangay- </option>\n";
        while($datarow  = @mysql_fetch_assoc($resultset))
        {
                $K      = $datarow["$field_code"];
                $V      = $datarow["$field_desc"];
                $str_selected = (! strcasecmp($K,"$selected")) ? ('selected') : ('');
                $select_str .=   "<option value='$K' $str_selected>$V</option>\n";
        }
        $select_str   .=  "</select>";
        //--- free mem
        if($result)
                @mysql_free_result($result);
        return $select_str;
}

function get_select_zone($dblink,$selectname,$table,$field_code,$field_desc,$selected,$where)
{
        
if ($where<>'') {
$zonese = 1;
print "	<input type= hidden name=zonesel value=1>";
}
        if ($where=='') {
                $locksel='disabled';
        } else {
                $locksel ='';
        }
        $sql            = "SELECT $field_code,$field_desc FROM $table ";
        if ( $where ) {
                $sql .= " WHERE upper = '$where'  ";
        }
        //log_err("SQL $sql");
        $resultset      = @mysql_query($sql, $dblink);
        $select_str     .= "<select name='$selectname'  $locksel  class='select200'
                onChange='javascript: flagchange(changeondin); _FRM.pro.value=0; _FRM.submit();'>";
        //--- set the default
        //$select_str .=   "<option value='$K' $str_selected>$V</option>\n";
        //"<option value=' ' $str_selected> ----- </option>\n";
$select_str .= "<option value='' $str_selected> -Please Select Zone- </option>\n";
        while($datarow  = @mysql_fetch_assoc($resultset))
        {
                $K      = $datarow["$field_code"];
                $V      = $datarow["$field_desc"];
                $str_selected = (! strcasecmp($K,"$selected")) ? ('selected') : ('');
                $select_str .=   "<option value='$K' $str_selected>$V</option>\n";
        }
        $select_str   .=  "</select>";


        //--- free mem
        if($result)
                @mysql_free_result($result);
        return $select_str;

}



function get_select_coa($dblink,$selectname,$table,$field_code,$field_desc,$selected='',$editable=true,$where = '',$javasc)
{

	$sql    	= "SELECT $field_code,$field_desc FROM $table $orderby";
	
	if ( $where ) {
	
		$sql .= " WHERE $where	";
		
	}
	
	
	$resultset 	= @mysql_query($sql, $dblink);
	$select_str     .= "<select name='$selectname' $javasc  class='select200' " . (($editable)?"":"disabled readonly") . " >";
	//--- set the default
	
	while($datarow 	= @mysql_fetch_assoc($resultset))
	{
		$K	= $datarow["$field_code"];
		$V	= $datarow["$field_desc"];
				
		$str_selected = (! strcasecmp($K,"$selected")) ? ('selected') : ('');
		$select_str .=   "<option value='$K' $str_selected>$V</option>\n";
	}
	
	if ($selected=='CASH') {
		$str_selected1='selected';
	} elseif ($selected=='CHECK') {
		$str_selected2='selected';
	} elseif ($elected=='PENALTY') {
		$str_selected3='selected';
	} elseif ($selected=='SURCHARGE/INTEREST') {
		$str_selected4='selected';
	}
	
	
	$select_str   .=  "<option value=CASH $str_selected1>CASH</option>";
    $select_str   .=  "<option value=CHECK $str_selected2 >CHECK</option>";
    $select_str   .=  "<option value=PENALTY $str_selected3 >PENALTY</option>";
    $select_str   .=  "<option value=SURCHARGE/INTEREST  $str_selected4>SURCHARGE/INTEREST</option>";
	
	$select_str   .=  "</select>";
	//--- free mem
	if($result)
		@mysql_free_result($result);
	return $select_str;	
}









?>
