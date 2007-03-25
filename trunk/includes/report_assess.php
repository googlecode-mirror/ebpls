<?php
$getbar = SelectMultiTable($dbtype,$dbLink,"ebpls_business_enterprise a, ebpls_barangay b",
			"b.g_zone",
                        "where a.owner_id=$owner_id and a.business_id=$business_id 
			and a. business_barangay_code=b.barangay_code");
$getbara = FetchRow($dbtype,$getbar);
$g_zone=$getbara[0];


if ($stat=='New') {
	$tftype=1;
} elseif ($stat=='ReNew') {
        $tftype=2;
} else {
	$tfttype=3;
}
//update old assess
if ($com=='assess' and $itemID_==4212 and $tftnum==1) {
$yearold = $yearnow - 1;
$ui = UpdateQuery($dbtype,$dbLink,"tempassess",
                "active = 0","owner_id=$owner_id and
                 business_id=$business_id  
		 and date_create like '$yearold%'");

$ui = DeleteQuery($dbtype,$dbLink,"tempassess",
                 "owner_id=$owner_id and
                 business_id=$business_id and active=1");
} else {
if ($com=='edit' || $itemID_<>4212) {
$PROCESS='COMPUTE';
}

}
//use decimal
$dec= SelectDataWhere($dbtype,$dbLink,"ebpls_buss_preference","");
$dec = FetchArray($dbtype,$dec);
$dec = $dec[sdecimal];
if ($dec<>'1') {
	$is_dec = '(int)';
} else {
	$is_dec = '';
}
$df = 0;
//get naturecode

if ($stat=='Retire') {
	$retstr = "and a.retire=2";
} else {
	$retstr='';
}

$getnat = SelectMultiTable($dbtype,$dbLink,"tempbusnature a, ebpls_buss_nature b",
			"a.bus_code, b.naturedesc, a.cap_inv, a.last_yr, a.transaction,a.linepaid",
                        "where owner_id=$owner_id and business_id=$business_id 
			and a.bus_code=b.natureid and active = 1 $retstr");
while ($getn = FetchRow($dbtype,$getnat)){
	$stt=$getn[4];
	if ($stt=='New') {
        	$tftype=1;
	} elseif ($stt=='ReNew') {
        	$tftype=2;
	} elseif ($stt=='Retire') {
        	$tftype=3;
	}
?>