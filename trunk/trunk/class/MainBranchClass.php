<?php
require_once "lib/dbFunctions.php";
//include "lib/dbsettings.php";

class MainBranch {
var
	$outrow,
        $outarray,
        $outnumrow,
        $outselect,
        $outresult,
        $outquery,
        $outid;


	function GetBranch($branch_id)
	{
	$this->outselect = SelectDataWhereDB("main_branch",
                "where branch_id = $branch_id");
        $this->outarray = FetchArrayDB($this->outselect);
        }

	function InsertMainBranch($strValues) 
	{
	$this->outid=InsertQueryDB("main_branch","",$strValues);
	}

	function UpdateMainBranch($strValues,$strWhere)
	{
	$this->outid=UpdateQueryDB("main_branch",$strValues,$strWhere);
	}





}
