<?php
require_once "includes/variables.php";
include("lib/multidbconnection.php");
$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);


                                $wil2 = UpdateQuery($dbtype,$dbLink,"tempbusnature",
                                        "retire=1","owner_id=$owner_id and
                                        business_id=$business_id and
                                        active=1 and transaction='Retire'");

                                $updatebusnature=UpdateQuery($dbtype,$dbLink,"tempbusnature",
                                        "active=0","owner_id=$owner_id and
                                        business_id=$business_id and transaction='Retire'");

                                $chkretire=SelectDataWhere($dbtype,$dbLink,"tempbusnature",
                                        "where owner_id=$owner_id and
                                        business_id=$business_id and active=1");
                                $chkretire=FetchRow($dbtype,$chkretire);
                                if ($chkretire==0) {

                                $updateretire=UpdateQuery($dbtype,$dbLink,
                                        "ebpls_business_enterprise",
                                        "retire=1, business_retirement_date=now()",
                                        "owner_id=$owner_id and business_id=$business_id");

                                $ubp = UpdateQuery($dbtype,$dbLink,
                                        "ebpls_business_enterpr1ise_permit",
                                        "active = 0","owner_id = $owner_id and
                                        business_id = $business_id");

                                } else {

//deact all permit
        $ubp = UpdateQuery($dbtype,$dbLink,"ebpls_business_enterprise_permit",
                            "active = 0","owner_id = $owner_id and 
	                     business_id = $business_id");
//active 1
        $updatepermit = UpdateQuery($dbtype,$dbLink,"ebpls_business_enterprise_permit",
                            "active=1","owner_id=$owner_id and business_id=$business_id
                            and transaction<>'Retire' order by business_permit_id desc limit 1");

//change pmode back to orig -- ang orig sa bicol ay baboy
$getpmode = SelectDataWhere($dbtype,$dbLink,"ebpls_business_enterprise_permit",
                "where owner_id=$owner_id and business_id=$business_id
                and pmode<>'' order by business_permit_id desc limit 1");
$pmode = FetchArray($dbtype,$getpmode);
$pmode = $pmode[pmode];

$updatepmode =  UpdateQuery($dbtype,$dbLink,"ebpls_business_enterprise",
                "business_payment_mode = '$pmode'",
                "owner_id=$owner_id and business_id=$business_id");
                                }
?>
<body onload="parent.location='index.php?part=4&class_type=Permits&permit_type=Business&busItem=Business&itemID_=2212&mtopsearch=SEARCH'";></body>





