<?

class EBPLSOfficer {

	var $user_name;	
	var $user_level;	
	var $user_error;
	var $bDebug;
	var $dbLink;
	
	function EBPLSOfficer( $dbLink, $user_name, $user_level, $bDebug = false ){
	
		$this->user_name = $user_name;
		$this->user_level = $user_level;	
		$this->bDebug = $bDebug;
		$this->dbLink = $dbLink;
	
	}
	
	function getLevel(){
	
		
	
	}
	
	function getName(){
	
		return $this->user_name;
	
	}
	
	function isValidLevel(){
	
		return ( getLevel() == $this->user_level );
	
	}

	function getError(){
	
		return $this->user_error;
	
	}
	
	function setError( $error_id, $error_message ) {
	
		$this->user_error = array($error_id, $error_message);
	
	}

	function getDebug(){
	
		return $this->bDebug;
		
	}

}

?>