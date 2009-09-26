<?
require_once("ebpls-php-lib/ebpls.dataencapsulator.class.php");
require_once("ebpls-php-lib/ebpls.transaction.class.php");
require_once("ebpls-php-lib/ebpls.motorizedoperator.class.php");
require_once("ebpls-php-lib/ebpls.fisheryoperator.class.php");
require_once("ebpls-php-lib/ebpls.reportgenerator.funcs.php");
require_once("ebpls-php-lib/ebpls.peddlers.permit.class.php");
require_once("ebpls-php-lib/utils/ebpls.html.funcs.php");

class EBPLSPermitPrinter extends DataEncapsulator {

	var $m_strTemplatesPath;
	var $m_dbLink;

	function EBPLSPermitPrinter( $dbLink, $templatesPath, $bDebug = true ) {

		$this->setDebugMode( $bDebug );

		if ( eregi("(.*)/$", $templatesPath) ) {
			$this->m_strTemplatesPath = $templatesPath;
		} else {
			$this->m_strTemplatesPath = $templatesPath . "/";
		}
		$this->m_dbLink = $dbLink;

	}

	function printPermit( &$transObj, $additional_info = NULL ) {

		$permit_type = $transObj->getPermitType();

		switch( $permit_type ) {
			case "BUS":
				{

					$this->printBusinessPermit( $transObj, $additional_info );

				} break;
			case "MOT":
			case "FRA":
				{

					$this->printFranchiseMotorizedPermit( $transObj, $additional_info );

				} break;
			case "FIS":
				{
					
					$this->printFisheryPermit( $transObj, $additional_info );
					
				} break;
			case "PED":
				{
					$this->printPeddlerPermit( $transObj, $additional_info );
				} break;
			case "OCC":
				{
					$this->printOccupationalPermit( $transObj, $additional_info );

				} break;

		}


	}


	function printBusinessPermit( &$transObj, $additional_info = NULL ) {

		$permit_type = $transObj->getPermitType();

		$permit = $transObj->getPermit();
		$owner = $transObj->getOwnerInfo();
		$clsBus = new EBPLSEnterprise( $this->m_dbLink, false );

		$clsBus->view( $transObj->getData(TRANS_BUSINESS_ID) );

		// header
		$header_template = $this->m_strTemplatesPath . "bus/header.html";
		// footer

		$footer_template = $this->m_strTemplatesPath . "bus/footer.html";

		// body

		// item 1 -> onwer info
		$infoPath = $this->m_strTemplatesPath . "bus/business_info.html";
		$strContent = constructTemplate( $infoPath, $owner->getData() );

		// item 2 -> business info
		$strContent = constructTemplate( $strContent, $clsBus->getData(), false );

		// item 3 -> line of business
		$strLOBTemplatePath = $this->m_strTemplatesPath . "bus/line_of_business_record.html";
		$arrLOB = $transObj->getLineOfBusiness();
		for ( $i = 0; $i < count( $arrLOB ); $i++ ) {

			$arrLOB[$i]->data_elems[TRANS_BUSNATURE_CAPITAL_INVESTMENT] =  number_format($arrLOB[$i]->getData(TRANS_BUSNATURE_CAPITAL_INVESTMENT),2);
			$arrLOB[$i]->data_elems[TRANS_BUSNATURE_LAST_GROSS] =  number_format($arrLOB[$i]->getData(TRANS_BUSNATURE_LAST_GROSS),2);
			$tmpRec = constructTemplate( $strLOBTemplatePath, $arrLOB[$i]->getData() ) . "\n";
			$arrLOBRec[] = $tmpRec;



		}

		// ctc info

		// payables taxes/fees
		$strFeesTemplatePath = $this->m_strTemplatesPath . "bus/fees_record.html";
		$arrFees = $transObj->getFeesList( );
		$total_fees = 0;
		for ( $i = 0; $i < count( $arrFees ); $i++ ) {

			$total_fees += $arrFees[$i]->getData(TF_TAX_TOTAL_AMOUNT_DUE);
			$arrFees[$i]->data_elems[TF_TAX_TOTAL_AMOUNT_DUE] =  number_format($arrFees[$i]->getData(TF_TAX_TOTAL_AMOUNT_DUE),2);
			$tmpRec = constructTemplate( $strFeesTemplatePath, $arrFees[$i]->getData() ) . "\n";
			$arrFeesData[] = $tmpRec;


		}
		
		
		// create template header
		$strHeader = constructTemplate( $header_template , $additional_info );

		// create template footer
		$strFooter = constructTemplate( $footer_template, $additional_info );


		$strContent = str_replace("[REPORTS_HEADER]", $strHeader, $strContent );
		$strContent = str_replace("[REPORTS_FOOTER]", $strFooter, $strContent );
		$strContent = str_replace("[RECORDS_LOB]", join("\n",$arrLOBRec), $strContent );
		$strContent = str_replace("[RECORDS_FEES]", join("\n",$arrFeesData), $strContent );
		$strContent = str_replace("[TOTAL_FEES]", number_format($total_fees,2), $strContent );

		echo $strContent;

	}


	function printFranchiseMotorizedPermit( &$transObj, $additional_info = NULL ) {

		$permit_type = $transObj->getPermitType();

		$permit = $transObj->getPermit();
		$owner = $transObj->getOwnerInfo();
		
		// header
		$header_template = $this->m_strTemplatesPath . "fra/header.html";
		
		// footer
		$footer_template = $this->m_strTemplatesPath . "fra/footer.html";

		// get vehicles
		$clsMot = new EBPLSMotorizedOperator( $this->m_dbLink, false );
		$clsMot->view( $owner->getData(OWNER_ID) );
		$vehArr = $clsMot->getVehicles();
		
		//print_r($vehArr);
		
		$strVehTemplatePath = $this->m_strTemplatesPath . "fra/vehicle_record.html";				
		for ( $i = 0; $i < count( $vehArr["result"] ); $i++ ) {
						
			$tmpRec = constructTemplate( $strVehTemplatePath, $vehArr["result"][$i]->getData() ) . "\n";
			$arrVehData[] = $tmpRec;

		}
		

		// item 1 -> onwer info
		$infoPath = $this->m_strTemplatesPath . "fra/franchise_info.html";
		$strContent = constructTemplate( $infoPath, $owner->getData() );				
		
		// create template header
		$strHeader = constructTemplate( $header_template , $additional_info );

		// create template footer
		$strFooter = constructTemplate( $footer_template, $additional_info );

		$strContent = str_replace("[REPORTS_HEADER]", $strHeader, $strContent );
		$strContent = str_replace("[REPORTS_FOOTER]", $strFooter, $strContent );
		$strContent = str_replace("[RECORDS_VEH]", join("\n",$arrVehData), $strContent );
	
		echo $strContent;

	}


	function printOccupationalPermit( &$transObj, $additional_info = NULL ) {

		$permit = $transObj->getPermit();
		$owner = $transObj->getOwnerInfo();
		
		// header
		$header_template = $this->m_strTemplatesPath . "occ/header.html";
		
		// footer
		$footer_template = $this->m_strTemplatesPath . "occ/footer.html";

		// get vehicles		

		// item 1 -> onwer info
		$infoPath = $this->m_strTemplatesPath . "occ/occupational_info.html";
		$strContent = constructTemplate( $infoPath, $owner->getData() );				
		$strContent = constructTemplate( $strContent, $permit->getData(), false );
		
		// create template header
		$strHeader = constructTemplate( $header_template , $additional_info );

		// create template footer
		$strFooter = constructTemplate( $footer_template, $additional_info );

		$strContent = str_replace("[REPORTS_HEADER]", $strHeader, $strContent );
		$strContent = str_replace("[REPORTS_FOOTER]", $strFooter, $strContent );
	
		echo $strContent;

	}

	function printFisheryPermit( &$transObj, $additional_info = NULL ) {

		$permit_type = $transObj->getPermitType();

		$permit = $transObj->getPermit();
		$owner = $transObj->getOwnerInfo();
		
		// header
		$header_template = $this->m_strTemplatesPath . "fis/header.html";
		
		// footer
		$footer_template = $this->m_strTemplatesPath . "fis/footer.html";

		// get vehicles
		$clsFis = new EBPLSFisheryOperator( $this->m_dbLink, false );
		$clsFis->view( $owner->getData(OWNER_ID) );
		$vehArr = $clsFis->getVehicles();
		
		//print_r($vehArr);
		
		$strVehTemplatePath = $this->m_strTemplatesPath . "fis/vehicle_record.html";				
		for ( $i = 0; $i < count( $vehArr["result"] ); $i++ ) {
						
			$tmpRec = constructTemplate( $strVehTemplatePath, $vehArr["result"][$i]->getData() ) . "\n";
			$arrVehData[] = $tmpRec;

		}
		

		// item 1 -> onwer info
		$infoPath = $this->m_strTemplatesPath . "fis/fishery_info.html";
		$strContent = constructTemplate( $infoPath, $owner->getData() );				
		
		// create template header
		$strHeader = constructTemplate( $header_template , $additional_info );

		// create template footer
		$strFooter = constructTemplate( $footer_template, $additional_info );

		$strContent = str_replace("[REPORTS_HEADER]", $strHeader, $strContent );
		$strContent = str_replace("[REPORTS_FOOTER]", $strFooter, $strContent );
		$strContent = str_replace("[RECORDS_VEH]", join("\n",$arrVehData), $strContent );
	
		echo $strContent;


	}

	function printPeddlerPermit( &$transObj, $additional_info = NULL ) {

		$permit = $transObj->getPermit();
		$owner = $transObj->getOwnerInfo();
		
		// header
		$header_template = $this->m_strTemplatesPath . "ped/header.html";
		
		// footer
		$footer_template = $this->m_strTemplatesPath . "ped/footer.html";

		// item 1 -> onwer info
		$infoPath = $this->m_strTemplatesPath . "ped/ped_info.html";
		$strContent = constructTemplate( $infoPath, $owner->getData() );				
		$strContent = constructTemplate( $strContent, $permit->getData(), false );
		
		// create template header
		$strHeader = constructTemplate( $header_template , $additional_info );

		// create template footer
		$strFooter = constructTemplate( $footer_template, $additional_info );

		$strContent = str_replace("[REPORTS_HEADER]", $strHeader, $strContent );
		$strContent = str_replace("[REPORTS_FOOTER]", $strFooter, $strContent );
	
		echo $strContent;

	}

}

?>