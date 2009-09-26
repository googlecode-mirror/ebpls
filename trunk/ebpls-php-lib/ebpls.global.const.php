<?
/**
 * Module : ebpls.global.const.php
 * Description : Module defines all global constants and variables needed by ebpls-php-lib classes.
 *
 * Date Created : Stephen Lou B. Banal
 * Email : sbanal@yahoo.com
 * Date Created : 3/6/2004 6:02PM
 *
 *
 *
 **/

// define gender constants and global vars
define(GENDER_MALE,"M");
define(GENDER_FEMALE,"F");
define(GENDER_UNSPECIFIED,"X");

$gGenderValues = array ( GENDER_FEMALE=>"Female", GENDER_MALE=>"Male" );

// define marital status constants
define(MARITAL_STATUS_SINGLE,"SINGLE");
define(MARITAL_STATUS_MARRIED,"MARRIED");
define(MARITAL_STATUS_WIDOWED,"WIDOWED");
define(MARITAL_STATUS_DIVORCED,"DIVORCED");

$gMaritalStatusValues = array ( MARITAL_STATUS_SINGLE, MARITAL_STATUS_MARRIED, MARITAL_STATUS_WIDOWED, MARITAL_STATUS_DIVORCED );

function get_marital_status_list(){
	
	return $gMaritalStatusValues;
		
}

$gCivilStatusArr = Array("SINGLE"=>"Single","WIDOWED"=>"Widowed","MARRIED"=>"Married","DIVORCED"=>"Divorced");
$gCitizenshipArr = Array("FILIPINO"=>"Filipino","AMERICAN"=>"American","CHINEESE"=>"Chineese","MALYSIAN"=>"Malysian","OTHERS"=>"Others");

$gCTCOrganizationType = Array("CORPORATION"=>"CORPORATION","ASSOCIATION"=>"ASSOCIATION","PARTNERSHIP"=>"PARTNERSHIP");

// define transaction types
define(TRANS_TYPE_NEW,"NEW");
define(TRANS_TYPE_RENEW,"RENEW");
define(TRANS_TYPE_RETIREMENT,"RETIREMENT");

// define permit types
define(PERMIT_TYPE_BUSINESS,"BUS");
define(PERMIT_TYPE_OCCUPATIONAL,"OCC");
define(PERMIT_TYPE_PEDDLER,"PED");
define(PERMIT_TYPE_FRANCHISE,"FRA");
define(PERMIT_TYPE_MOTORIZED,"MOT");
define(PERMIT_TYPE_FISHERY,"FIS");

$gPermitTypes = array(PERMIT_TYPE_BUSINESS,PERMIT_TYPE_OCCUPATIONAL, PERMIT_TYPE_PEDDLER, PERMIT_TYPE_FRANCHISE,PERMIT_TYPE_MOTORIZED, PERMIT_TYPE_FISHERY );

function get_permit_types_list(){
	
	return $gPermitTypes;
	
}

// permit status values
define(TRANS_STATUS_APPLICATION,"APPLICATION");
define(TRANS_STATUS_ASSESSMENT,"ASSESSMENT");
define(TRANS_STATUS_PAYMENT,"PAYMENT");
define(TRANS_STATUS_APPROVAL,"APPROVAL");
define(TRANS_STATUS_RELEASING,"RELEASING");
define(TRANS_STATUS_RELEASED,"RELEASED");
define(TRANS_STATUS_REJECTED,"REJECTED");
define(TRANS_STATUS_RETIRED,"RETIRED");

$gTransactionStatusStatus = array('APPLICATION','ASSESSMENT','PAYMENT','APPROVAL','RELEASING','RELEASED','REJECTED','RETIRED');

function get_transaction_status_list(){

	return $gTransactionStatusStatus;	
	
}

// application user levels
/*
0 => array("G", "CTC Officer", "eBPLS_USER_CTC"),
1 => array("O", "Application Officer", "eBPLS_USER_APPLICATION"),
2 => array("P", "Assessment Officer", "eBPLS_USER_ASSESSMENT"),
3 => array("F", "Payment Officer", "eBPLS_USER_PAYMENT"),
4 => array("A", "Approving Officer", "eBPLS_USER_APPROVAL"),
5 => array("C", "Releasing Officer", "eBPLS_USER_RELEASING"),
6 => array("S", "eBPLS Administrator", "eBPLS_USER_ADMIN"),
7 => array("R", "Root Administrator", "eBPLS_ROOT_ADMIN")
*/
define(TRANS_LEVEL_CTC_OFFICER,0);
define(TRANS_LEVEL_APPLICATION_OFFICER,1);
define(TRANS_LEVEL_ASSESSMENT_OFFICER,2);
define(TRANS_LEVEL_PAYMENT_OFFICER,3);
define(TRANS_LEVEL_APPROVING_OFFICER,4);
define(TRANS_LEVEL_RELEASING_OFFICER,5);
define(TRANS_LEVEL_ADMIN_OFFICER,6);
define(TRANS_LEVEL_ROOT_OFFICER,7);

// define permit status
define(PERMIT_STATUS_PENDING,"pending");
define(PERMIT_STATUS_APPROVED,"approved");

// define application requirement status
define(REQUIREMENT_STATUS_PENDING,"PENDING");
define(REQUIREMENT_STATUS_SUBMITTED,"SUBMITTED");

// ebpls code
define(CODES_CTC_IND_COL,"ctc_ind_code");
define(CODES_CTC_BUS_COL,"ctc_bus_code");
define(CODES_APP_COL,"app_code");
define(CODES_ASS_COL,"ass_code");
define(CODES_PAY_COL,"pay_code");
define(CODES_OR_COL,"or_no");
define(CODES_APR_COL,"apr_code");
define(CODES_REL_COL,"rel_code");

define(CODES_PERMIT_BUS_COL,"bpermit_no");
define(CODES_PERMIT_MOTORIZED_COL,"mpermit_no");
define(CODES_PERMIT_OCCUPATIONAL_COL,"opermit_no");
define(CODES_PERMIT_PEDDLER_COL,"ppermit_no");
define(CODES_PERMIT_FISHERY_COL,"fishery_permit_no");
define(CODES_PERMIT_FRANCHISE_BUS_COL,"fpermit_no");
define(CODES_PAYMENT_DUE,"payment_sched_due");

$gCodesKeys = array( CODES_PERMIT_FISHERY_COL, CODES_CTC_IND_COL, CODES_CTC_BUS_COL,CODES_APP_COL, CODES_ASS_COL, CODES_PAY_COL,CODES_OR_COL,
		CODES_APR_COL, CODES_REL_COL, CODES_PERMIT_BUS_COL, CODES_PERMIT_MOTORIZED_COL,
		CODES_PERMIT_OCCUPATIONAL_COL, CODES_PERMIT_PEDDLER_COL, CODES_PERMIT_FRANCHISE_BUS_COL, CODES_PAYMENT_DUE );

define(CTC_TYPE_INDIVIDUAL,"INDIVIDUAL");
define(CTC_TYPE_BUSINESS,"BUSINESS");

$gValidCTCTypes = array(CTC_TYPE_INDIVIDUAL,CTC_TYPE_BUSINESS);

// define payment modes
define(TRANS_PAYMENT_MODE_MONTHLY,"MONTHLY");
define(TRANS_PAYMENT_MODE_QUARTERLY,"QUARTERLY");
define(TRANS_PAYMENT_MODE_SEMIANNUAL,"SEMI-ANNUAL");
define(TRANS_PAYMENT_MODE_ANNUAL,"ANNUAL");
define(TRANS_PAYMENT_MODE_ANNUAL2,"ANNUAL2");

// define payment schedule state
define(TPS_PAYMENT_STATE_PAID,"PAID");
define(TPS_PAYMENT_STATE_UNPAID,"UNPAID");

// define account code type
define(ACCOUNT_CODE_NATURE_DEBIT,"DEBIT");
define(ACCOUNT_CODE_NATURE_CREDIT,"CREDIT");

// check status
define(CHECK_STATUS_PENDING,"PENDING");
define(CHECK_STATUS_CLEARED,"CLEARED");
define(CHECK_STATUS_BOUNCED,"BOUNCED");

$gCheckStatus = array(CHECK_STATUS_PENDING,CHECK_STATUS_CLEARED,CHECK_STATUS_BOUNCED);

define(PAYABLE_TYPE_SITAX,"SITAX");
define(PAYABLE_TYPE_BUSTAX,"BUSTAX");
define(PAYABLE_TYPE_TAX,"TAX");
define(PAYABLE_TYPE_FEE,"FEE");

$gTaxFeeType = array(PAYABLE_TYPE_SITAX=>"Surcharge/Interest Tax",PAYABLE_TYPE_BUSTAX=>"Business Tax",PAYABLE_TYPE_TAX=>"Tax",PAYABLE_TYPE_FEE=>"Fee");

// formula types
define(COMPUTATION_TYPE_CONSTANT, "CONSTANT");
define(COMPUTATION_TYPE_FORMULA, "FORMULA");
define(COMPUTATION_TYPE_RANGE, "RANGE");

$gComputationType = array( COMPUTATION_TYPE_CONSTANT=>"Constant", COMPUTATION_TYPE_FORMULA=>"Formula", COMPUTATION_TYPE_RANGE=>"Range");
?>
