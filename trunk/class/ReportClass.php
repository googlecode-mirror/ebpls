<?php
require_once "lib/dbFunctions.php";
//include "lib/dbsettings.php";

class NewReport {
var
	$outrow,
        $outarray,
        $outnumrow,
        $outselect,
        $outresult,
        $outquery,
        $outid;

	function GetReport($report_id) 
	{
	$this->outselect = SelectDataWhereDB("ebpls_reports","where report_id='$report_id'");
	$this->outarray = FetchArrayDB($this->outselect);
	}

	function FetchPermit($query)
	{
	$this->outarray = FetchArrayDB($query);
	}



}
