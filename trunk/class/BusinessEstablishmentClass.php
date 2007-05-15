<?php
require_once "lib/dbFunctions.php";
//include "lib/dbsettings.php";

class BusinessEstablishment {
var
	$outrow,
        $outarray,
        $outnumrow,
        $outselect,
        $outresult,
        $outquery,
        $outid;

	function GetBusinessByName($bname,$bbranch) 
	{
	$this->outselect = SelectDataWhereDB("ebpls_business_enterprise",
                "where business_name  like '$bname%' and
                 business_branch  like '$bbranch%' and retire=1 and blacklist<>1 "); 
	$this->outnumrow = NumRowsDB($this->outselect);
	}

	function GetBusinessAssess($owner,$bus) 
	{
	$this->outselect = SelectDataWhereDB("tempassess ",
                "where owner_id='$owner' and business_id='$bus'"); 
	$this->outnumrow = NumRowsDB($this->outselect);
	}
	
	
	
	function GetBusinessByNameCTC($bname,$bbranch)
        {
        $this->outselect = SelectDataWhereDB("ebpls_business_enterprise",
                "where business_name  like '$bname%' and
                 business_branch  like '$bbranch%' and  owner_id=0 and  business_update_by='ONLINE'");
        $this->outnumrow = NumRowsDB($this->outselect);
        }

	 function GetBusinessByOnline($bname,$bbranch,$owner)
        {
        $this->outselect = SelectDataWhereDB("ebpls_business_enterprise",
                "where business_name  like '$bname%' and
                 business_branch  like '$bbranch%' and  owner_id='$owner' and business_update_by='ONLINE' and business_id not in (select business_id from ebpls_business_enterprise_permit where owner_id='$owner')");
        $this->outnumrow = NumRowsDB($this->outselect);
        }



	function VerifyBusiness($bname,$bbranch,$bid)
        {
        $this->outselect = SelectDataWhereDB("ebpls_business_enterprise",
                "where business_name  = '$bname' and business_id<>'$bid' and
                 business_branch = '$bbranch' and blacklist<>1 and business_branch<>'None'");
        $this->outnumrow = NumRowsDB($this->outselect);
        }

	function VerifyBusinessNoBranch($bname,$bid)
        {
        $this->outselect = SelectDataWhereDB("ebpls_business_enterprise",
                "where business_name  = '$bname' and business_id<>'$bid'
                 and blacklist<>1");
        $this->outnumrow = NumRowsDB($this->outselect);
        }



	function VerifyNsoNo($bid,$nsono)
        {
        $this->outselect = SelectDataWhereDB("ebpls_business_enterprise",
                "where business_id<>'$bid' and
                 business_nso_assigned_no = '$nsono'");
        $this->outnumrow = NumRowsDB($this->outselect);
        }

	function VerifyNsoID($bid,$nsoid)
        {
        $this->outselect = SelectDataWhereDB("ebpls_business_enterprise",
                "where business_id<>'$bid' and
                 business_nso_estab_id = '$nsoid'");
        $this->outnumrow = NumRowsDB($this->outselect);
        }

	function VerifyACR($bid,$acrid)
        {
        $this->outselect = SelectDataWhereDB("ebpls_business_enterprise",
                "where business_id<>'$bid' and
                 business_dot_acr_no = '$acrid'");
        $this->outnumrow = NumRowsDB($this->outselect);
        }
	
	function VerifyDTIReg($bid,$dtino)
        {
        $this->outselect = SelectDataWhereDB("ebpls_business_enterprise",
                "where business_id<>'$bid' and
                 business_dti_reg_no = '$dtino' ");
        $this->outnumrow = NumRowsDB($this->outselect);
        }

	function VerifySecNo($bid,$secno)
        {
        $this->outselect = SelectDataWhereDB("ebpls_business_enterprise",
                "where business_id<>'$bid' and
                 business_sec_reg_no = '$secno'");
        $this->outnumrow = NumRowsDB($this->outselect);
        }

	function VerifyTIN($bid,$tin)
        {
        $this->outselect = SelectDataWhereDB("ebpls_business_enterprise",
                "where business_id<>'$bid' and
                 business_tin_reg_no = '$tin'");
        $this->outnumrow = NumRowsDB($this->outselect);
        }

	function GetBusinessByID($bus_id)
        {
        $this->outselect = SelectDataWhereDB("ebpls_business_enterprise",
			   "where business_id=$bus_id");
        $this->outnumrow = NumRowsDB($this->outselect);
        }


	function UpdateBusinessID($owner_id,$bus_id)
        {
	$this->outid=UpdateQueryDB("ebpls_business_enterprise","owner_id=$owner_id","business_id=$bus_id");
        }


	function GetPaymentRecord($bus_id,$own_id)
	{
	$this->outselect = SelectDataWhereDB("ebpls_transaction_payment_or_details",
			   "where trans_id='$own_id' and payment_id='$bus_id'");
	$this->outnumrow = NumRowsDB($this->outselect);
	}

	function CountBusiness()
        {
        $this->outselect = SelectDataWhereDB("ebpls_business_enterprise",
                           "");
        $this->outnumrow = NumRowsDB($this->outselect);
        }


	function FetchBusinessRow($query) 
	{
	$this->outrow = FetchRowDB($query);	
	}

	function FetchBusinessArray($query)
        {
        $this->outarray = FetchArrayDB($query);
        }


	function ListBusiness($list,$newowner,$gpin)
	{
	$this->outarray=FetchArrayBusinessListDB($list,$newowner,$gpin);
	}

	function InsertNewBusiness($strValues) 
	{
	$this->outid=InsertQueryDB("ebpls_business_enterprise",
			"(owner_id, business_name , business_branch, business_scale,
                          business_lot_no, business_street , business_barangay_code,
                          business_zone_code,  business_district_code,
                          business_city_code, business_province_code, business_zip_code,
                          business_contact_no, business_fax_no , business_email_address,
                          business_location_desc , business_building_name,
                          business_phone_no, business_category_code, business_dot_acr_no,
                          business_sec_reg_no, business_tin_reg_no, business_dti_reg_no,
                          business_dti_reg_date, business_date_established,
                          business_start_date, business_occupancy_code,
                          business_offc_code, 
                          employee_male, business_no_del_vehicles,
                          business_payment_mode, business_nso_assigned_no,
                          business_nso_estab_id, business_industry_sector_code,
                          business_remarks, business_create_ts, business_update_by,
                          business_update_ts, employee_female, blacklist,
                          biztype, subsi, pcname, pcaddress,
                          regname, paidemp, ecoorg, ecoarea,branch_id,edit_by,edit_locked,
                          black_list_date,black_list_reason )",$strValues);
	}

	function UpdateBusiness($strValues,$strWhere)
	{
	$this->outid=UpdateQueryDB("ebpls_business_enterprise",$strValues,$strWhere);
	}





}
