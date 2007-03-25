<?php
/*
*	This file contain's eBPLS's Report functions.
*/

	
	function getMasterListBusEtab($arrDateStart, $arrDateEnd)
	{
		$strDbHost = "192.168.1.5";
		$strDbUser = "ebpls";
		$strDbUKey = "ebpls";
		$strDbName = "ebpls";
		
		//var_dump($arrDateStart);
		//var_dump($$arrDateEnd);
		
		//$strCurrShade = ($strCurrShade == $thThemeColor3) ? $thThemeColor4 : $thThemeColor3;
		dbConnect(0, $strDbHost, $strDbUser, $strDbUKey, $strDbName, $strDbLink);
		
		//global $thThemeColor3, $thThemeColor4;
		$strDisplay = "\n";
		$strDisplay .= "<table width=\"1000\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" align=\"left\">";
	    $strDisplay .= "<tr bgcolor=".$bgcolor.">"; 
	    $strDisplay .= "<td width=\"200\" class=\"thFieldTitle\"><div align=\"left\">BUSINESS NAME</div></td>";
	    $strDisplay .= "<td width=\"100\" class=\"thFieldTitle\"><div align=\"left\">PERMIT NO.</div></td>";
	    $strDisplay .= "<td width=\"100\" class=\"thFieldTitle\"><div align=\"left\">BUSINESS ADDRESS</div></td>";
	    $strDisplay .= "<td width=\"100\" class=\"thFieldTitle\"><div align=\"left\">NATURE OF BUSINESS</div></td>";
	    $strDisplay .= "<td width=\"100\" class=\"thFieldTitle\"><div align=\"left\">GROSS SALES/CAPITAL INVESTMENT</div></td>";
	    $strDisplay .= "<td width=\"100\" class=\"thFieldTitle\"><div align=\"left\">AMOUNT PAID</div></td>";
	    $strDisplay .= "<td width=\"100\" class=\"thFieldTitle\"><div align=\"left\"></div>OR NO.</td>";
	    $strDisplay .= "<td width=\"100\" class=\"thFieldTitle\"><div align=\"left\"></div>DATE OF PAYMENT</td>";
	    $strDisplay .= "<td width=\"100\" class=\"thFieldTitle\"><div align=\"left\"></div>REMARKS</td>";
	    $strDisplay .= "</tr>";
		

		// store corporate account status in Array
		//$result = th_query("SELECT a.business_name, b.permit_code ,CONCAT_WS(' ' ,a.business_lot_no, a.business_street, a.business_city_code ) AS address, a.business_nature_code, a.business_capital_investment, c.total_amount_paid, c.or_no, c.or_date, a.comment FROM ebpls_business_enterprise a, ebpls_transaction b, ebpls_transaction_payment_or c WHERE b.trans_id = c.trans_id AND a.business_id = b.business_id ORDER BY a.business_name");
		$result = th_query("SELECT a.business_name, d.business_permit_code ,CONCAT_WS(' ' ,a.business_lot_no, a.business_street, e.city_municipality_desc ) AS address, a.business_nature_code, a.business_capital_investment, c.total_amount_paid, c.or_no, c.or_date, a.comment FROM ebpls_business_enterprise a, ebpls_transaction b, ebpls_transaction_payment_or c, ebpls_business_enterprise_permit d, ebpls_city_municipality e WHERE b.trans_id = c.trans_id AND a.business_id = b.business_id AND a.business_id = d.business_id AND a.business_city_code = e.city_municipality_code ORDER BY a.business_name");
		while ($row = mysql_fetch_row($result)) {
			$strDisplay .= "<tr bgcolor=".$bgcolor.">"; 
		    $strDisplay .= "<td width=\"200\" class=\"thText\" ><div align=\"left\" >".strtoupper($row[0])."</div></td>";
		    $strDisplay .= "<td width=\"100\" class=\"thText\" ><div align=\"left\" >".$row[1]."</div></td>";
		    $strDisplay .= "<td width=\"100\" class=\"thText\" ><div align=\"left\" >".$row[2]."</div></td>";
		    $strDisplay .= "<td width=\"100\" class=\"thText\"><div align=\"left\" >".$row[3]."</div></td>";
		    $strDisplay .= "<td width=\"100\" class=\"thText\"><div align=\"right\" >".number_format($row[4],2)."</div></td>";
		    $strDisplay .= "<td width=\"100\" class=\"thText\"><div align=\"right\" >".number_format($row[5],2)."</div></td>";
		    $strDisplay .= "<td width=\"100\" class=\"thText\"><div align=\"left\" >".$row[6]."</div></td>";
		    $strDisplay .= "<td width=\"100\" class=\"thText\"><div align=\"left\" >".$row[7]."</div></td>";
		    $strDisplay .= "<td width=\"100\" class=\"thText\"><div align=\"left\" >".$row[8]."</div></td>";
		    $strDisplay .= "</tr>";
		}
		
		return $strDisplay;
	}

	
	
	function getBusEstabByBarangay($arrDateStart, $arrDateEnd)
	{
		$strDbHost = "localhost";
		$strDbUser = "ebpls";
		$strDbUKey = "ebpls";
		$strDbName = "ebpls";
		
		//var_dump($arrDateStart);
		//var_dump($$arrDateEnd);
		
		//$strCurrShade = ($strCurrShade == $thThemeColor3) ? $thThemeColor4 : $thThemeColor3;
		dbConnect(0, $strDbHost, $strDbUser, $strDbUKey, $strDbName, $strDbLink);
		
		//global $thThemeColor3, $thThemeColor4;
		$strDisplay = "\n";
		$strDisplay .= "<table width=\"1000\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" align=\"left\">";
	    $strDisplay .= "<tr bgcolor=".$bgcolor.">"; 
	    $strDisplay .= "<td width=\"100\" class=\"thFieldTitle\"><div align=\"left\">PERMIT NO.</div></td>";
	    $strDisplay .= "<td width=\"200\" class=\"thFieldTitle\"><div align=\"left\">BUSINESS NAME</div></td>";
	    $strDisplay .= "<td width=\"100\" class=\"thFieldTitle\"><div align=\"left\">BUSINESS CATEGORY</div></td>";
	    $strDisplay .= "<td width=\"100\" class=\"thFieldTitle\"><div align=\"left\">NATURE OF BUSINESS</div></td>";
	    $strDisplay .= "<td width=\"100\" class=\"thFieldTitle\"><div align=\"left\">NAME OF OWNER</div></td>";
	    $strDisplay .= "<td width=\"100\" class=\"thFieldTitle\"><div align=\"left\">CONTACT NO.</div></td>";
	    $strDisplay .= "<td width=\"100\" class=\"thFieldTitle\"><div align=\"left\"></div>CAPITAL INVESTMENTS</td>";
	    $strDisplay .= "<td width=\"100\" class=\"thFieldTitle\"><div align=\"left\"></div>BARANGAY</td>";
	    $strDisplay .= "</tr>";
		

		// store corporate account status in Array
		$result = th_query("SELECT b.permit_code, a.business_name, d.business_category_desc, a.business_nature_code, CONCAT_WS(' ', c.owner_first_name, c.owner_middle_name, c.owner_last_name ) AS owner_name, c.owner_phone_no, a.business_capital_investment, a.business_barangay_code FROM ebpls_business_enterprise a, ebpls_transaction b, ebpls_owner c, ebpls_business_category d WHERE a.business_category_code = d.business_category_code AND a.owner_id = c.owner_id AND a.business_id = b.business_id  GROUP BY a.business_barangay_code, a.business_name ORDER BY a.business_barangay_code, a.business_name");
		while ($row = mysql_fetch_row($result)) {
			$strDisplay .= "<tr bgcolor=".$bgcolor.">"; 
		    $strDisplay .= "<td width=\"100\" class=\"thText\" ><div align=\"left\" >".$row[0]."</div></td>";
		    $strDisplay .= "<td width=\"200\" class=\"thText\" ><div align=\"left\" >".strtoupper($row[1])."</div></td>";
		    $strDisplay .= "<td width=\"100\" class=\"thText\" ><div align=\"left\" >".$row[2]."</div></td>";
		    $strDisplay .= "<td width=\"100\" class=\"thText\"><div align=\"left\" >".$row[3]."</div></td>";
		    $strDisplay .= "<td width=\"100\" class=\"thText\"><div align=\"left\" >".$row[4]."</div></td>";
		    $strDisplay .= "<td width=\"100\" class=\"thText\"><div align=\"left\" >".$row[5]."</div></td>";
		    $strDisplay .= "<td width=\"100\" class=\"thText\"><div align=\"right\" >".number_format($row[6],2)."</div></td>";
		    $strDisplay .= "<td width=\"100\" class=\"thText\"><div align=\"left\" >".$row[7]."</div></td>";
		    $strDisplay .= "</tr>";
		}
		
		return $strDisplay;
	}
	
	function getBusTaxCollection($arrDateStart, $arrDateEnd)
	{
		$strDbHost = "localhost";
		$strDbUser = "ebpls";
		$strDbUKey = "ebpls";
		$strDbName = "ebpls";
		
		//var_dump($arrDateStart);
		//var_dump($$arrDateEnd);
		
		//$strCurrShade = ($strCurrShade == $thThemeColor3) ? $thThemeColor4 : $thThemeColor3;
		dbConnect(0, $strDbHost, $strDbUser, $strDbUKey, $strDbName, $strDbLink);
		
		//global $thThemeColor3, $thThemeColor4;
		$strDisplay = "\n";
		$strDisplay .= "<table width=\"1000\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" align=\"left\">";
	    $strDisplay .= "<tr bgcolor=".$bgcolor.">"; 
	    
	    $strDisplay .= "<td width=\"200\" class=\"thFieldTitle\"><div align=\"left\">BUSINESS NAME</div></td>";
	    $strDisplay .= "<td width=\"200\" class=\"thFieldTitle\"><div align=\"left\">PERMIT NO.</div></td>";
	    $strDisplay .= "<td width=\"100\" class=\"thFieldTitle\"><div align=\"left\">NATURE OF BUSINESS</div></td>";
	    $strDisplay .= "<td width=\"300\" class=\"thFieldTitle\"><div align=\"left\">BUSINESS ADDRESS</div></td>";
	    $strDisplay .= "<td width=\"100\" class=\"thFieldTitle\"><div align=\"left\">OR NO.</div></td>";
	    $strDisplay .= "<td width=\"100\" class=\"thFieldTitle\"><div align=\"left\">OR DATE</div></td>";
	    $strDisplay .= "</tr>";
		

		// store corporate account status in Array
		$result = th_query("SELECT a.business_name, b.permit_code , a.business_nature_code, CONCAT_WS(' ' ,a.business_lot_no, a.business_street, a.business_city_code ) AS address, c.or_no, c.or_date FROM ebpls_business_enterprise a, ebpls_transaction b, ebpls_transaction_payment_or c WHERE b.trans_id = c.trans_id AND a.business_id = b.business_id ORDER BY a.business_name");
		while ($row = mysql_fetch_row($result)) {
			$strDisplay .= "<tr bgcolor=".$bgcolor.">"; 
		    $strDisplay .= "<td width=\"200\" class=\"thText\" ><div align=\"left\" >".strtoupper($row[1])."</div></td>";
		    $strDisplay .= "<td width=\"200\" class=\"thText\" ><div align=\"left\" >".$row[0]."</div></td>";
		    $strDisplay .= "<td width=\"100\" class=\"thText\" ><div align=\"left\" >".$row[2]."</div></td>";
		    $strDisplay .= "<td width=\"300\" class=\"thText\"><div align=\"left\" >".$row[3]."</div></td>";
		    $strDisplay .= "<td width=\"100\" class=\"thText\"><div align=\"left\" >".$row[4]."</div></td>";
		    $strDisplay .= "<td width=\"100\" class=\"thText\"><div align=\"left\" >".$row[5]."</div></td>";
		    $strDisplay .= "</tr>";
		}
		
		return $strDisplay;
	}
	
	function getBusEstabByOwner($arrDateStart, $arrDateEnd)
	{
		$strDbHost = "localhost";
		$strDbUser = "ebpls";
		$strDbUKey = "ebpls";
		$strDbName = "ebpls";
		
		//var_dump($arrDateStart);
		//var_dump($$arrDateEnd);
		
		//$strCurrShade = ($strCurrShade == $thThemeColor3) ? $thThemeColor4 : $thThemeColor3;
		dbConnect(0, $strDbHost, $strDbUser, $strDbUKey, $strDbName, $strDbLink);
		
		//global $thThemeColor3, $thThemeColor4;
		$strDisplay = "\n";
		$strDisplay .= "<table width=\"1000\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" align=\"left\">";
	    $strDisplay .= "<tr bgcolor=".$bgcolor.">"; 
	    $strDisplay .= "<td width=\"100\" class=\"thFieldTitle\"><div align=\"left\">OWNER NAME</div></td>";
	    $strDisplay .= "<td width=\"200\" class=\"thFieldTitle\"><div align=\"left\">ADDRESS</div></td>";
	    $strDisplay .= "<td width=\"100\" class=\"thFieldTitle\"><div align=\"left\">CITIZENSHIP</div></td>";
	    $strDisplay .= "<td width=\"100\" class=\"thFieldTitle\"><div align=\"left\">GENDER</div></td>";
	    $strDisplay .= "<td width=\"100\" class=\"thFieldTitle\"><div align=\"left\">BIRTH DATE</div></td>";
	    $strDisplay .= "<td width=\"100\" class=\"thFieldTitle\"><div align=\"left\">BUSINESS NAME</div></td>";
	    $strDisplay .= "<td width=\"100\" class=\"thFieldTitle\"><div align=\"left\"></div>BUSINESS NATURE</td>";
	    $strDisplay .= "<td width=\"100\" class=\"thFieldTitle\"><div align=\"right\"></div>BUSINESS CAPITAL INVESTMENT</td>";
	    $strDisplay .= "<td width=\"100\" class=\"thFieldTitle\"><div align=\"left\"></div>PERMIT NO.</td>";
	    $strDisplay .= "</tr>";
		

		// store corporate account status in Array
		$result = th_query("SELECT CONCAT_WS(' ', c.owner_first_name, c.owner_middle_name, c.owner_last_name ) AS owner_name, CONCAT_WS(' ' ,c.owner_house_no, c.owner_street, d.city_municipality_desc ) AS address, c.owner_citizenship,  c.owner_gender, c.owner_birth_date, a.business_name, a.business_nature_code, a.business_capital_investment, b.permit_code FROM ebpls_business_enterprise a, ebpls_transaction b, ebpls_owner c, ebpls_city_municipality d WHERE c.owner_city_code = d.city_municipality_code AND a.owner_id = c.owner_id AND c.owner_id = b.owner_id  ORDER BY owner_name");
		while ($row = mysql_fetch_row($result)) {
			$strDisplay .= "<tr bgcolor=".$bgcolor.">"; 
		    $strDisplay .= "<td width=\"100\" class=\"thText\" ><div align=\"left\" >".$row[0]."</div></td>";
		    $strDisplay .= "<td width=\"200\" class=\"thText\" ><div align=\"left\" >".$row[1]."</div></td>";
		    $strDisplay .= "<td width=\"100\" class=\"thText\" ><div align=\"left\" >".$row[2]."</div></td>";
		    $strDisplay .= "<td width=\"100\" class=\"thText\"><div align=\"left\" >".$row[3]."</div></td>";
		    $strDisplay .= "<td width=\"100\" class=\"thText\"><div align=\"left\" >".$row[4]."</div></td>";
		    $strDisplay .= "<td width=\"100\" class=\"thText\"><div align=\"left\" >".$row[5]."</div></td>";
		    $strDisplay .= "<td width=\"100\" class=\"thText\"><div align=\"left\" >".$row[6]."</div></td>";
		    $strDisplay .= "<td width=\"100\" class=\"thText\"><div align=\"right\" >".number_format($row[7],2)."</div></td>";
		    $strDisplay .= "<td width=\"100\" class=\"thText\"><div align=\"left\" >".$row[6]."</div></td>";
		    $strDisplay .= "</tr>";
		}
		
		return $strDisplay;
	}
	
	
	
	function getBusEstabCapInvest($arrDateStart, $arrDateEnd)
	{
		$strDbHost = "localhost";
		$strDbUser = "ebpls";
		$strDbUKey = "ebpls";
		$strDbName = "ebpls";
		
		//var_dump($arrDateStart);
		//var_dump($$arrDateEnd);
		
		//$strCurrShade = ($strCurrShade == $thThemeColor3) ? $thThemeColor4 : $thThemeColor3;
		dbConnect(0, $strDbHost, $strDbUser, $strDbUKey, $strDbName, $strDbLink);
		
		//global $thThemeColor3, $thThemeColor4;
		$strDisplay = "\n";
		$strDisplay .= "<table width=\"1000\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" align=\"left\">";
	    $strDisplay .= "<tr bgcolor=".$bgcolor.">"; 
	    $strDisplay .= "<td width=\"200\" class=\"thFieldTitle\"><div align=\"right\">PERMIT NO.</div></td>";
	    $strDisplay .= "<td width=\"100\" class=\"thFieldTitle\"><div align=\"right\">CAPITAL INVESTMENTS</div></td>";
	    $strDisplay .= "<td width=\"200\" class=\"thFieldTitle\"><div align=\"center\">BUSINESS NAME</div></td>";
	    $strDisplay .= "<td width=\"300\" class=\"thFieldTitle\"><div align=\"left\">BUSINESS ADDRESS</div></td>";
	    $strDisplay .= "<td width=\"200\" class=\"thFieldTitle\"><div align=\"left\">NAME OF OWNER</div></td>";
	    $strDisplay .= "</tr>";
		

		// store corporate account status in Array
		$result = th_query("SELECT b.business_permit_code, a.business_capital_investment, a.business_name, CONCAT_WS(' ' ,a.business_lot_no, a.business_street, d.city_municipality_desc ), CONCAT_WS(' ', c.owner_first_name, c.owner_middle_name, c.owner_last_name ) AS owner_name FROM ebpls_business_enterprise a, ebpls_business_enterprise_permit b, ebpls_owner c, ebpls_city_municipality d WHERE a.business_city_code = d.city_municipality_code AND a.owner_id = c.owner_id GROUP BY a.business_capital_investment, a.business_name ORDER BY a.business_capital_investment DESC, a.business_name");
		while ($row = mysql_fetch_row($result)) {
			$strDisplay .= "<tr bgcolor=".$bgcolor.">"; 
		    $strDisplay .= "<td width=\"200\" class=\"thText\" ><div align=\"right\" >".$row[0]."</div></td>";
		    $strDisplay .= "<td width=\"100\" class=\"thText\" ><div align=\"right\" >".number_format($row[1],2)."</div></td>";
		    $strDisplay .= "<td width=\"200\" class=\"thText\" ><div align=\"center\" >".$row[2]."</div></td>";
		    $strDisplay .= "<td width=\"300\" class=\"thText\"><div align=\"left\" >".$row[3]."</div></td>";
		    $strDisplay .= "<td width=\"200\" class=\"thText\"><div align=\"left\" >".$row[4]."</div></td>";
		    $strDisplay .= "</tr>";
		}
		
		return $strDisplay;
	}
	
	
	function getBusEstabByNature($arrDateStart, $arrDateEnd)
	{
		$strDbHost = "localhost";
		$strDbUser = "ebpls";
		$strDbUKey = "ebpls";
		$strDbName = "ebpls";
		
		//var_dump($arrDateStart);
		//var_dump($$arrDateEnd);
		
		//$strCurrShade = ($strCurrShade == $thThemeColor3) ? $thThemeColor4 : $thThemeColor3;
		dbConnect(0, $strDbHost, $strDbUser, $strDbUKey, $strDbName, $strDbLink);
		
		//global $thThemeColor3, $thThemeColor4;
		$strDisplay = "\n";
		$strDisplay .= "<table width=\"1000\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" align=\"left\">";
	    $strDisplay .= "<tr bgcolor=".$bgcolor.">"; 
	    $strDisplay .= "<td width=\"100\" class=\"thFieldTitle\"><div align=\"left\">PERMIT NO.</div></td>";
	    $strDisplay .= "<td width=\"200\" class=\"thFieldTitle\"><div align=\"left\">BUSINESS NAME</div></td>";
	    $strDisplay .= "<td width=\"100\" class=\"thFieldTitle\"><div align=\"left\">BUSINESS CATEGORY</div></td>";
	    $strDisplay .= "<td width=\"100\" class=\"thFieldTitle\"><div align=\"left\">NATURE OF BUSINESS</div></td>";
	    $strDisplay .= "<td width=\"100\" class=\"thFieldTitle\"><div align=\"left\">NAME OF OWNER</div></td>";
	    $strDisplay .= "<td width=\"100\" class=\"thFieldTitle\"><div align=\"left\">CONTACT NO.</div></td>";
	    $strDisplay .= "<td width=\"100\" class=\"thFieldTitle\"><div align=\"left\"></div>CAPITAL INVESTMENTS</td>";
	    $strDisplay .= "<td width=\"100\" class=\"thFieldTitle\"><div align=\"left\"></div>BARANGAY</td>";
	    $strDisplay .= "</tr>";
		

		// store corporate account status in Array
		$result = th_query("SELECT b.permit_code, a.business_name, d.business_category_desc, a.business_nature_code, CONCAT_WS(' ', c.owner_first_name, c.owner_middle_name, c.owner_last_name ) AS owner_name, c.owner_phone_no, a.business_capital_investment, a.business_barangay_code FROM ebpls_business_enterprise a, ebpls_transaction b, ebpls_owner c, ebpls_business_category d WHERE a.business_category_code = d.business_category_code AND a.owner_id = c.owner_id AND a.business_id = b.business_id  GROUP BY a.business_barangay_code, a.business_name ORDER BY a.business_barangay_code, a.business_name");
		while ($row = mysql_fetch_row($result)) {
			$strDisplay .= "<tr bgcolor=".$bgcolor.">"; 
		    $strDisplay .= "<td width=\"100\" class=\"thText\" ><div align=\"left\" >".$row[0]."</div></td>";
		    $strDisplay .= "<td width=\"200\" class=\"thText\" ><div align=\"left\" >".strtoupper($row[1])."</div></td>";
		    $strDisplay .= "<td width=\"100\" class=\"thText\" ><div align=\"left\" >".$row[2]."</div></td>";
		    $strDisplay .= "<td width=\"100\" class=\"thText\"><div align=\"left\" >".$row[3]."</div></td>";
		    $strDisplay .= "<td width=\"100\" class=\"thText\"><div align=\"left\" >".$row[4]."</div></td>";
		    $strDisplay .= "<td width=\"100\" class=\"thText\"><div align=\"left\" >".$row[5]."</div></td>";
		    $strDisplay .= "<td width=\"100\" class=\"thText\"><div align=\"right\" >".number_format($row[6],2)."</div></td>";
		    $strDisplay .= "<td width=\"100\" class=\"thText\"><div align=\"left\" >".$row[7]."</div></td>";
		    $strDisplay .= "</tr>";
		}
		
		return $strDisplay;
	}
?>
