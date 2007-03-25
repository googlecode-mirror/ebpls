<?php
require_once "includes/variables.php";
include_once "class/TaxpayerClass.php";
if ($zonesel<>1) {
$ini = new TaxPayer;
$ini->GetTaxPayerByID($owner_id);
$fi = substr($ini->outrow[1], 0, 1). substr($ini->outrow[2], 0, 1).
      substr($ini->outrow[3], 0, 1);
$s =  substr($permit_type, 0, 1);
$pin = rand(0000, 9999);
$pin = $s.$fi.$pin;
if ($genpin=='') {
	$genpin=$pin;
}
}
?>
