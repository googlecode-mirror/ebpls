<?php
//matinding nakakahilong sql
$ff = "(";
$outamt='';

$compsql = SelectMultiTable($dbtype,$dbLink,"tempassess a, ebpls_buss_taxfeeother b,
                        ebpls_buss_complex c",
			"a.multi, a.amt, a.formula, c.coptr, c.addons,a.compval", 
			"where a.natureid = b.natureid and 
			a.taxfeeid = b.taxfeeid and a.taxfeeid = c.taxfeeid1 and 
			b.taxfeeid = c.taxfeeid1 and a.tfoid = b.tfoid and 
			a.tfoid = c.tfoid and b.tfoid = c.tfoid and 
			a.natureid = b.natureid and b.natureid = c.natureid 
			and a.natureid = c.natureid and
			c.taxfeeid=$taxfeeid and a.active=1
                        and a.transaction='$stat' and owner_id = $owner_id 
			and business_id = $business_id
			order by compid asc");

while ($comp = FetchRow($dbtype,$compsql))
{
	if (is_numeric($comp[2])) {
		$formula = "((".$comp[0]."*".$comp[2]."))".$comp[3].$comp[4];
	} else {
		$formula = "((".$comp[0].$comp[2].$comp[3].$comp[4];
	}
		$outamt = $outamt.$formula;

}
	$cfr= $fr.$ff.$outamt;//."))";
//	eval ("\$totind=$fr$ff$outamt));");
//	echo $cfr;

//count "(" ")"
	$sop = substr_count($cfr,'(');
	$scp = substr_count($cfr,')');
		if ($sop > $scp) {
			$looper = $scp;
			$addcp='';
				while($looper<$sop) {
					$addcp = $addcp.')';
					$looper++;
					}
		}



	eval ("\$totind=$cfr$addcp;");
//	echo $totind;
	//$cfr ='';
	//$cfr= $fr.$ff.$outamt."))";
//	echo $cfr.$addcp;
?>


