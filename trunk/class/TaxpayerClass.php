<?php
require_once "lib/dbFunctions.php";
//include "lib/dbsettings.php";

class Taxpayer {
var
	$outrow,
        $outarray,
        $outnumrow,
        $outselect,
        $outresult,
        $outquery,
        $outid;

	function GetTaxPayerByName($fname,$sname) 
	{
	$this->outselect = SelectDataWhereDB("ebpls_owner",
                "where owner_last_name like '$sname%' and
                 owner_first_name like '$fname%'"); 
	$this->outnumrow = NumRowsDB($this->outselect);
	}

	function GetTaxPayerByID($ownid)
	{
	$this->outselect = SelectDataWhereDB("ebpls_owner",
                "where owner_id = '$ownid'");
        $this->outrow = FetchRowDB($this->outselect);
        }

	function ListTaxPayer($list,$permit_type)
	{
	$this->outarray=FetchArrayPayerListDB($list,$permit_type);
	}

	function InsertTaxPayer($strValues) 
	{
	
	$this->outid=InsertQueryDB("ebpls_owner","",$strValues);
	}

	function UpdateTaxPayer($strValues,$strWhere)
	{
	$this->outid=UpdateQueryDB("ebpls_owner",$strValues,$strWhere);
	}

	function GetMotor($mmodel,$mnum,$cnum,$pnum,$bnum,$bcolor,$route,$ltype,$ltoreg,$cro) 
	{
	$this->outselect = SelectDataWhereDB("ebpls_motorized_vehicles",
                "where motorized_motor_model like '$mmodel%' and
                 motorized_motor_no like '$mnum%' and 
                 motorized_chassis_no like '$cnum%' and
                 motorized_plate_no like '$pnum%' and 
                 motorized_body_no  like '$bnum%' and
                 body_color like '$bcolor%' and 
                 route  like '$route%' and
                 linetype  like '$ltype%' and 
                 lto_number  like '$ltoreg%' and
                 cr_number like '$cro%' and retire=2
                 "); 
	$this->outnumrow = NumRowsDB($this->outselect);
	}

	function ListMotor($list,$permit_type,$ownid,$stat)
	{
	$this->outarray=FetchArrayMotorListDB($list,$permit_type,$ownid,$stat);
	}	


}
