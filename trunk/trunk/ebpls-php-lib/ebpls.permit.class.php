<?
/************************************************************************************

Module : ebpls.permit.class.php

Dependencies : 
	ebpls.database.funcs.php
	ebpls.dataencapsulator.class.php
	ebpls.global.const.php
	ebpls.global.funcs.php
	
Description : 
	- encapsulates permits
	- subclasses of this class are the different the permit types
	- permits can either of type business, occupational, peddler, motorized 
	
	
	
Created By : Stephen Lou B. Banal
Email : sbanal@yahoo.com
Date Created : 3/6/2004 5:51PM

Last Updates : 	
	[ DATE / TIME ] - [ PROGRAMMER ]
	- < DETAILS 1 >
	- < DETAILS 2 >
	- < DETAILS 3 >
	
	
Notes :
	
	
************************************************************************************/

require_once("ebpls-php-lib/ebpls.dataencapsulator.class.php");
require_once("ebpls-php-lib/ebpls.global.funcs.php");
require_once("ebpls-php-lib/ebpls.database.funcs.php");

class EBPLSPermit extends DataEncapsulator {
	
	var $m_dbLink;
	var $m_objOwner;
	var $m_nOwnerId;
	var $m_strPermitNo;
	var $m_strPermitType;
	var $m_strPermitStatus;
	var $m_tsRegDate;
	var $m_tsExpDate;
	var $m_nForYear;
	var $m_objTransaction;
	
	/**
	 * Instatiation method, set $bDebug to true to print debug messages otherwise set to false.
	 * $dbLink is a valid database connection.
	 *
	 */
	function EBPLSPermit( $dbLink, $bDebug = false ) {
	
		$this->m_dbLink = $dbLink;
		$this->setDebugMode( $bDebug );		
		$this->m_objOwner = NULL;
		$m_objTransaction = NULL;
	}	
	
	function getTransaction(){
		
		return $this->m_objTransaction;
	
	}
	
	function getOwner( ) {
		
		if ( $this->m_objOwner == NULL ) {
		
			$this->m_objOwner = new EBPLSOwner( $this->m_dbLink );
			$this->m_objOwner->view( $this->getOwnerId() );
			$this->setOwner( $owner );
			
		}
		
		return $this->m_objOwner;
	
	}	
	
	function getOwnerId( ) {
		
		return $this->m_nOwnerId;
		
	}
	
	function setOwner( &$owner ) {
		
		if ( is_a( $owner, "EBPLSOwner") ) {
					
			$this->m_objOwner = $owner;
			return true;
			
		} else {
			
			$this->debug("setOwner Failed : Invalid Object type $owner, was expecting instance of EBPLSOwner.");
			return false;
			
		}
	
	}
			
	function getPermitNo() {
	
		return $this->m_strPermitNo;
	
	}
	
	
	function getPermitCode() {
	
		return $this->m_strPermitType . str_pad( $this->m_strPermitNo, 15, "0", STR_PAD_LEFT) ;
	
	}
	
	function getPermitType() {
	
		return $this->m_strPermitType;
	
	}
	
	function setPermitType( $permit_type ) {
		
		global $gPermitTypes;
		
		if ( in_array( $permit_type, $gPermitTypes ) )  {
			
			$this->m_strPermitType = $permit_type;
			return true;
			
		} else {
		
			$this->debug("Invalid Permit Type value $permit_type.");
			return false;
			
		}
		
		
	}
	
	function setPermitRegistrationDates( $reg_date, $exp_date, $for_year ) {
	
		if ( is_valid_date($reg_date) && is_valid_date($exp_date) && is_numeric($for_year) ) {
		
			$this->m_tsRegDate = $reg_date;
			$this->m_tsExpDate = $exp_date;
			$this->m_nForYear = $for_year;
			return true;
			
		} else {
		
			$this->debug("Invalid param value on calling method setPermitRegistration( $reg_date, $exp_date, $for_year )"); 
			return false;
		}
		
	}
	
	function getRegistrationDate() {
		
		return $this->m_tsRegDate;
		
	}
	
	function getExpirationDate() {
	
		return $this->m_tsExpDate;
	
	}
	
	function getForYear() {
	
		$this->m_nForYear;
	
	}
		
	function setStatus ( $status )  {
	
		if ( in_array( $status, $gPermitStatus ) )  {
			
			$this->m_strPermitStatus = $status;
			return true;
			
		} else {
		
			$this->debug("Invalid Permit Status value $status.");
			return false;
			
		}
	
	}

	function newApplication( $owner_id, $creator, $user_level ) {
	
	
	}	
	
	function renewApplication( $owner_id, $creator, $user_level ) {
	
	
	}
	
	function view( $code ) {
	

	}

	function retire() {
		
	}
	
	function assignPermitCode( $code ) {
	
	}
	
	function getStatus() {
	
		return $this->m_strPermitStatus;
		
	
	}
	
	
	
	/**
	 * Serves as factory class function of all the permits subclasses supported by the system.
	 *
	 *
	 **/
	function createPermit( $dbLink, $id, $type, $bDebug = false ) {
		
		$cls = NULL;
		
		switch($type){
			case PERMIT_TYPE_BUSINESS :
				{
				
					require_once("ebpls-php-lib/ebpls.enterprise.permit.class.php");
				
					$cls = new EBPLSEnterprisePermit( $dbLink, $bDebug);
					$cls->data_elems[ BE_BUSINESS_PERMIT_ID ] = $id;					
					$cls->m_strPermitNo = $id;
					$cls->m_strPermitType = PERMIT_TYPE_BUSINESS;	
					
				}
				break;
			case PERMIT_TYPE_OCCUPATIONAL :
				{
				
					require_once("ebpls-php-lib/ebpls.occupational.permit.class.php");
				
					$cls = new EBPLSOccupationalPermit( $dbLink, $bDebug);
					$cls->data_elems[ OCC_PERMIT_ID ] = $id;					
					$cls->m_strPermitNo = $id;
					$cls->m_strPermitType = PERMIT_TYPE_OCCUPATIONAL;	
					
				}
				break;
			case PERMIT_TYPE_PEDDLER :
				{
					
					require_once("ebpls-php-lib/ebpls.peddlers.permit.class.php");
				
					$cls = new EBPLSPeddlersPermit( $dbLink, $bDebug);					
					$cls->data_elems[ PEDDLERS_PERMIT_ID ] = $id;					
					$cls->m_strPermitNo = $id;
					$cls->m_strPermitType = PERMIT_TYPE_PEDDLERS;	
					
				}
				break;
			case PERMIT_TYPE_FRANCHISE :
				{
					require_once("ebpls-php-lib/ebpls.franchise.permit.class.php");
					
					$cls = new EBPLSFranchisePermit( $dbLink, $bDebug);					
					$cls->data_elems[ FRANCHISE_PERMIT_ID ] = $id;					
					$cls->m_strPermitNo = $id;
					$cls->m_strPermitType = PERMIT_TYPE_FRANCHISE;	
				}
				break;
			case PERMIT_TYPE_MOTORIZED :
				{
				
					require_once("ebpls-php-lib/ebpls.motorized.permit.class.php");
				
					$cls = new EBPLSMotorizedPermit( $dbLink, $bDebug);
					$cls->data_elems[ MOTORIZED_OPERATOR_PERMIT_ID ] = $id;					
					$cls->m_strPermitNo = $id;
					$cls->m_strPermitType = PERMIT_TYPE_MOTORIZED;	
					
				}
				break;				
			case PERMIT_TYPE_FISHERY :
				{
				
					require_once("ebpls-php-lib/ebpls.fishery.permit.class.php");
				
					$cls = new EBPLSFisheryPermit( $dbLink, $bDebug);
					$cls->data_elems[ FISHERY_PERMIT_ID ] = $id;					
					$cls->m_strPermitNo = $id;
					$cls->m_strPermitType = PERMIT_TYPE_FISHERY;	
					
				}
				break;
			default :
				{
				
					$this->debug("createPermit Failed, Type value $type is invalid");
					
				}
				break;
		}
		
		return $cls;
		
	}
	
	
}


?>