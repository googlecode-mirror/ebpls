<?
require_once("ebpls-php-lib/ebpls.transaction.class.php");
require_once("ebpls-php-lib/ebpls.officer.class.php");

define(APPLICATION_LEVEL_ERROR,"User is not a valid application officer.");

class EBPLSApplicationOfficer extends EBPLSOfficer{
	
	function EBPLSApplicationOfficer( $user_name, $user_level, $bDebug = false ){
	
		$this->EBPLSOfficer( $user_name, $user_level, $bDebug );
		
	}
	
	function getLevel(){
	
		return TRANS_LEVEL_APPLICATION_OFFICER;
	
	}
	
	function newApplication( &$permit ){
	
		if($this->isValidLevel()){
			
			
		
		} else {
		
			
		}
	
	}
	
	function renewApplication(){
	
		if($this->isValidLevel()){
		
		
		} else {
		
		
		}
	
	}
	
	function cancelApplication(){
	
		if($this->isValidLevel()){
		
		
		} else {
		
		
		}
	
	
	}
	
	function printApplication(){
		
		
		
	}
	
	function getTransaction(){
		
		
	
	}
	
	function processTransaction(){
	
		if($this->isValidLevel()){
		
		
		} else {
		
		
		}
	
	
	}
	
	
}

?>