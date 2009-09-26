<?php
require_once "lib/dbFunctions.php";
//include "lib/dbsettings.php";

class NewPermit {
var
	$outrow,
        $outarray,
        $outnumrow,
        $outselect,
        $outresult,
        $outquery,
        $outid;

	function GetBusinessPermit($owner,$business,$permittable) 
	{
	$this->outselect = SelectDataWhereDB($permittable,
			   "where owner_id = $owner and
                            business_id = $business and active=1");
	$this->outnumrow = NumRowsDB($this->outselect);
	$this->outarray = FetchArrayDB($this->outselect);
	}

	function GetBusinessPermit1($owner,$business,$permittable, $tyear)
        {
        $this->outselect = SelectDataWhereDB($permittable,
                           "where owner_id = $owner and
                            business_id = $business and for_year = $tyear");
        $this->outnumrow = NumRowsDB($this->outselect);
        $this->outarray = FetchArrayDB($this->outselect);
        }

	function FetchPermit($query)
	{
	$this->outarray = FetchArrayDB($query);
	}

	function InsertBusinessPermit($strValues,$permittable) 
	{
	$this->outid=InsertQueryDB($permittable,
				"(business_id, owner_id, for_year,application_date,
                                input_by, transaction, paid, steps, pin, active)",
				$strValues);
	}

	function UpdateBusinessPermit($strValues,$strWhere,$permittable) 
	{
	 $this->outid=UpdateQueryDB($permittable,$strValues,$strWhere);
        }


}
