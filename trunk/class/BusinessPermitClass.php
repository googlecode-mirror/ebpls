<?php
require_once "lib/dbFunctions.php";
//include "lib/dbsettings.php";

class BusinessPermit {
var
	$outrow,
        $outarray,
        $outnumrow,
        $outselect,
        $outresult,
        $outquery,
        $outid;

	function GetPermit($owner,$business) 
	{
	$this->outselect = SelectDataWhereDB("ebpls_business_enterprise_permit",
			   "where owner_id = '$owner' and
                            business_id = '$business' ");
	$this->outnumrow = NumRowsDB($this->outselect);
	}

	function FetchPermit($query)
	{
	$this->outarray = FetchArrayDB($query);
	}
}
