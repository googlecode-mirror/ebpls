<?

require_once("ebpls-php-lib/ebpls.dataencapsulator.class.php");
require_once("ebpls-php-lib/ebpls.reportgenerator.funcs.php");

define(EBPLS_REPORT_1,1); // ok
define(EBPLS_REPORT_2,2); // ok
define(EBPLS_REPORT_3,3); // ok
define(EBPLS_REPORT_4,4); // ok 
define(EBPLS_REPORT_5,5); // ok same as report 4
define(EBPLS_REPORT_6,6); // ok
define(EBPLS_REPORT_7,7); // ok same as report 4
define(EBPLS_REPORT_8,8); // ok
define(EBPLS_REPORT_9,9); // ok
define(EBPLS_REPORT_10,10); 
define(EBPLS_REPORT_11,11); 
define(EBPLS_REPORT_12,12); 

$tmpArrStartEndDate = Array (
		"start_date"=> createFormElementCreatorArray( "Start Date", "date", "", true, "", "", "date" ),
		"end_date"=> createFormElementCreatorArray( "End Date", "date", "", true, "", "", "date" ) 
		 );

$tmpArrStartEndBrgy = Array ( 
		"start_date"=> createFormElementCreatorArray( "Start Date", "date", "", true, "", "", "date" ), 
		"end_date"=> createFormElementCreatorArray( "End Date", "date", "", true, "", "", "date" ),
		"barangay"=> createFormElementCreatorArray( "Barangay", "multiselect", "", true, "", "reports_get_barangays", "text" )
		);

$tmpArrStartEndCategory= Array ( 
		"start_date"=> createFormElementCreatorArray( "Start Date", "date", "", true, "", "", "date" ), 
		"end_date"=> createFormElementCreatorArray( "End Date", "date", "", true, "", "", "date" ),
		"nature_code"=> createFormElementCreatorArray( "Category/Type/Nature", "select", "", true, "", "reports_get_category", "text" )
		);
		 
$gReportsData[EBPLS_REPORT_1] = createReportDetails('Application for Business Permit/License','get_list_buspermit_application','ebpls_reports_header.html','ebpls_reports_footer.html','ebpls_report1_template.html','ebpls_report1_record_template.html', $tmpArrStartEndDate );
$gReportsData[EBPLS_REPORT_2] = createReportDetails('Application fo Fishing Permit','get_list_fishery_application','ebpls_reports_header.html','ebpls_reports_footer.html','ebpls_report2_template.html','ebpls_report2_record_template.html', $tmpArrStartEndDate );
$gReportsData[EBPLS_REPORT_3] = createReportDetails('Application for Occupational Permit','get_list_occupational_application','ebpls_reports_header.html','ebpls_reports_footer.html','ebpls_report10_template.html','ebpls_report10_record_template.html' , $tmpArrStartEndDate );
$gReportsData[EBPLS_REPORT_4] = createReportDetails('Application for Franchise/Motorized Permit','get_list_motorized_application','ebpls_reports_header.html','ebpls_reports_footer.html','ebpls_report11_template.html','ebpls_report11_record_template.html', $tmpArrStartEndDate);
$gReportsData[EBPLS_REPORT_5] = createReportDetails('Application for Peddlers Permit','get_list_peddlers_application','ebpls_reports_header.html','ebpls_reports_footer.html','ebpls_report12_template.html','ebpls_report12_record_template.html', $tmpArrStartEndDate);
$gReportsData[EBPLS_REPORT_6] = createReportDetails('Business Establishments By Barangay','get_bus_establshment_by_barangay','ebpls_reports_header.html','ebpls_reports_footer.html','ebpls_report3_template.html','ebpls_report3_record_template.html', $tmpArrStartEndBrgy );
$gReportsData[EBPLS_REPORT_7] = createReportDetails('Business Establishments By Business Category/Type/Nature','get_bus_establshment_by_category','ebpls_reports_header.html','ebpls_reports_footer.html','ebpls_report4_template.html','ebpls_report4_record_template.html', $tmpArrStartEndCategory );
$gReportsData[EBPLS_REPORT_8] = createReportDetails('Business Establishments By Business Type','get_bus_establshment_by_category','ebpls_reports_header.html','ebpls_reports_footer.html','ebpls_report5_template.html','ebpls_report5_record_template.html', $tmpArrStartEndCategory);
$gReportsData[EBPLS_REPORT_9] = createReportDetails('Business Establishments By Capital Investment','get_bus_establshment_by_capital','ebpls_reports_header.html','ebpls_reports_footer.html','ebpls_report6_template.html','ebpls_report6_record_template.html', $tmpArrStartEndDate);
$gReportsData[EBPLS_REPORT_10] = createReportDetails('Business Establishments By Nature Of Business','get_bus_establshment_by_category','ebpls_reports_header.html','ebpls_reports_footer.html','ebpls_report7_template.html','ebpls_report7_record_template.html', $tmpArrStartEndCategory);
$gReportsData[EBPLS_REPORT_11] = createReportDetails('Business Establishments By Owner','get_bus_establshment_by_owner','ebpls_reports_header.html','ebpls_reports_footer.html','ebpls_report8_template.html','ebpls_report8_record_template.html', $tmpArrStartEndDate);
$gReportsData[EBPLS_REPORT_12] = createReportDetails('Master List of Business Establishment','get_bus_establshment_materlist','ebpls_reports_header.html','ebpls_reports_footer.html','ebpls_report9_template.html','ebpls_report9_record_template.html', $tmpArrStartEndDate);

class EBPLSReportsGenerator extends DataEncapsulator {

	var $m_dbLink;
	var $m_reportType;
	var $m_reportFormElements;
	var $m_sqlFunction;
	var $m_queryResult;
	var $m_reportTemplatePath;
	var $m_reportData;

	function EBPLSReportsGenerator( $dbLink, $reportTemplatePath, $reportType , $bDebug = false ) {

		global $gReportsData;

		$this->m_dbLink = $dbLink;
		$this->setDebugMode( $bDebug );
		$this->m_reportType = $reportType;
		
		if ( defined( "EBPLS_REPORT_" . $reportType ) ) {
			
			if ( eregi("(.*)/$", $reportTemplatePath ) ) {
				$this->m_reportTemplatePath = $reportTemplatePath;
			} else {
				$this->m_reportTemplatePath = $reportTemplatePath . '/';
			}
			
			$this->m_reportData = $gReportsData[constant("EBPLS_REPORT_" . $reportType)];
			$this->m_reportFormElements = $this->m_reportData["search_elems"];
			$this->m_sqlFunction = $this->m_reportData["get_records_func"];
		
		}
		
		$this->m_queryResult = NULL;

	}	

	function getFormElements(){
						
		if ( $this->m_reportType ) {		
		
			$arr =  $this->m_reportFormElements;											
			
			return $arr;
			
		}
		
		return NULL;
		
	}

	function executeQuery( $parameters = NULL ) {

		$this->m_queryResult = @call_user_func_array( $this->m_sqlFunction, Array($this->m_dbLink, $parameters) );

		if ( $this->m_queryResult ) {

			$this->debug("executeQuery : Result found, executed $this->m_sqlFunction.");
			return $this->m_queryResult;

		}

		$this->debug("executeQuery : No result on call of  user func : $this->m_sqlFunction.");
		return NULL;

	}
		
	function createReport( $additional_info = NULL  ) {
		
		// loop through all the records of the query to build the result list record
		$strRecords = "";
		$i = 0;
		foreach ( $this->m_queryResult as $key=>$value ) {
			
			$tmpRec = constructTemplate( $this->m_reportTemplatePath . $this->m_reportData["template_record"], $value ) . "\n";			
			$strRecords[] = $tmpRec;			
			
			if ( $i == 0 ) {
				
				foreach ( $value as $addKey=>$addValue ) {
					
					$additional_info[$addKey] = $addValue;
					
				}
				
				$i++;	
				
			}

		}		
	
		// create template header
		$strHeader = constructTemplate( $this->m_reportTemplatePath . $this->m_reportData["template_header"] , $additional_info );

		// create template footer
		$strFooter = constructTemplate( $this->m_reportTemplatePath . $this->m_reportData["template_footer"], $additional_info );

		// build report body
		$strContent = constructTemplate( $this->m_reportTemplatePath . $this->m_reportData["template_body"], $additional_info );

		$strContent = str_replace("[REPORTS_HEADER]", $strHeader, $strContent );
		$strContent = str_replace("[REPORTS_FOOTER]", $strFooter, $strContent );
		$strContent = str_replace("[RECORDS]", join("\n",$strRecords), $strContent );

		return $strContent;

	}

}



function createReportDetails( $title,  $func_name, 
			$header = '', $footer = '', 
			$body = '', $record = '', $search_form_elems = '' ) {
	
	return Array(
			'title' => $title,
			'template_header' => $header,
			'template_footer' => $footer,
			'template_body' => $body,
			'template_record' => $record,
			'get_records_func' => $func_name,
			'search_elems' => $search_form_elems
		);
	
}

function createFormElementCreatorArray( 
			$label, $type, $default, $required, 
			$values, $values_func, $datatype ) {

	return Array( 
		"lablel"=>$label,
		"type"=>$type,
		"default"=>$default,
		"required"=>$required,
		"values"=>$values,
		"values_func"=>$values_func,
		"datatype"=>$datatype
		);
	
}


?>