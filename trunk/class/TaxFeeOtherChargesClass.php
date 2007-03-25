<?php
require_once "lib/dbFunctions.php";
//include "lib/dbsettings.php";

class TaxFee {
var
	$outrow,
        $outarray,
        $outnumrow,
        $outselect,
        $outresult,
        $outquery,
        $outid;

	function GetTaxFee($taxfeeid)
        {
        $this->outselect = SelectDataWhereDB("ebpls_buss_taxfeeother",
			   "where taxfeeid=$taxfeeid");
        $this->outnumrow = NumRowsDB($this->outselect);
	$this->outarray = FetchArrayDB($this->outselect);
        }

	function CountTaxFeeComplex($taxfeeid)
        {
	       // echo "select * from ebpls_buss_complex where complex_taxfeeid=$taxfeeid order by compid asc";
        $this->outselect = SelectDataWhereDB("ebpls_buss_complex",
                           "where complex_taxfeeid=$taxfeeid order by compid asc");
        $this->outnumrow = NumRowsDB($this->outselect);
//	$this->outarray = FetchArrayDB($this->outselect);
//echo "select * from ebpls_buss_complex where complex_taxfeeid=$taxfeeid order by compid asc";
        }

	function FindDuplicate($natid,$tfoid,$transaction)
	{
	$this->outselect = SelectDataWhereDB("ebpls_buss_taxfeeother", "where natureid='$natid' and
				tfo_id='$tfoid' and taxtype='$transaction'");
	$this->outnumrow = NumRowsDB($this->outselect);
	}


	function CountTaxFeeRange($taxfeeid)
        {
        $this->outselect = SelectDataWhereDB("ebpls_buss_taxrange",
                           "where taxfeeid=$taxfeeid order by rangeid");
        $this->outnumrow = NumRowsDB($this->outselect);
        }


	function FetchTaxFeeRow($query) 
	{
	$this->outrow = FetchRowDB($query);	
	}

	function FetchTaxFeeArray($query)
        {
        $this->outarray = FetchArrayDB($query);
        }


	function ListTaxFee($list,$newowner)
	{
	$this->outarray=FetchArrayTaxFeeListDB($list,$newowner);
	}

	function InsertNewTaxFee($strValues) 
	{
	$this->outid=InsertQueryDB("ebpls_buss_taxfeeother",
                          '',$strValues);
	}

	function InsertNewComplex($strValues)
        {
        $this->outid=InsertQueryDB("ebpls_buss_complex",
                          '',$strValues);
        }

	function InsertNewRange($strValues)
        {
        $this->outid=InsertQueryDB("ebpls_buss_taxrange",
                          '',$strValues);
        }



	function UpdateTaxFee($strValues,$strWhere)
	{
	$this->outid=UpdateQueryDB("ebpls_buss_taxfeeother",$strValues,$strWhere);
	}

	function UpdateNature($strValues,$strWhere)
        {
        $this->outid=UpdateQueryDB("ebpls_buss_nature",$strValues,$strWhere);
        }


	function UpdateComplex($strValues,$strWhere)
        {
        $this->outid=UpdateQueryDB("ebpls_buss_complex",$strValues,$strWhere);
        }

	function UpdateRange($strValues,$strWhere)
        {
        $this->outid=UpdateQueryDB("ebpls_buss_taxrange",$strValues,$strWhere);
        }

	function GetComplex($compid)
        {
        $this->outselect = SelectDataWhereDB("ebpls_buss_complex",
                           "where compid=$compid");
        $this->outnumrow = NumRowsDB($this->outselect);
        }

	function GetRange($rangeid)
        {
        $this->outselect = SelectDataWhereDB("ebpls_buss_taxrange",
                           "where rangeid=$rangeid");
        $this->outnumrow = NumRowsDB($this->outselect);
        }

	function ReplaceValue($taxfeeid,$owner_id,$business_id,$natureid) 
	{
	$this->outselect = SelectDataWhereDB("tempassess a, ebpls_buss_complex b",
			   "where a.taxfeeid=b.complex_tfoid and 
			    a.owner_id=$owner_id and a.business_id=$business_id 
			    and a.natureid=$natureid and a.taxfeeid=$taxfeeid and active=1");
	$this->outarray = FetchArrayDB($this->outselect);
	}

	function ReplaceValueDef($taxfeeid,$owner_id,$business_id,$natureid) 
	{
	$this->outselect = SelectDataWhereDB("tempassess a, ebpls_buss_complex b",
			   "where a.taxfeeid=b.complex_tfoid and 
			    a.owner_id=$owner_id and a.business_id=$business_id 
			    and a.natureid=$natureid and a.taxfeeid=$taxfeeid and active=1");
	$rec = mysql_num_rows($this->outselect);
		if ($rec>0) {		    
	   			$this->outarray = FetchArrayDB($this->outselect);
   		} else {
	   		$this->outselect = SelectDataWhereDB("ebpls_buss_tfo a", 
	   					"where a.tfoid = '$taxfeeid' and a.tfoindicator=1");
	   		$this->outarray = FetchArrayDB($this->outselect);
   		}			
	}

	
	function DeleteTax($Table,$strWhere)
        {
        $this->outid=DeleteQueryDB($Table,$strWhere);
        }
		
	function CheckTaxFee($natureid)
        {
        $this->outselect = SelectDataWhereDB("ebpls_buss_taxfeeother",
			   "where natureid='$natureid'");
        $this->outnumrow = NumRowsDB($this->outselect);
		}

}
