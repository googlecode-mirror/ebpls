<?php
//gud luk sa inyo bro!!! tibay mo drew!
include("includes/variables.php");
include("lib/multidbconnection.php");  
require_once "lib/ebpls.utils.php";             
include_once "class/TaxFeeOtherChargesClass.php";                                                                            
$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);

if ($copynow==1) {
	$lop=1;
	while ($lop<$howmany+1) {
		if ($x[$lop]=='on') {
//			$col1[$lop] = taxfeeid;
				
			$aTAXFEEid=$col1[$lop];
			if ($aTAXFEEid<>'') {
	                $gTaxFee = new TaxFee;
        	        $gTaxFee->GetTaxFee($aTAXFEEid);
                	$getTax = $gTaxFee->outarray;
	                $tfotype = $getTax[tfo_id];
                	$TaxType= $getTax[taxtype];
	                $Basis= $getTax[basis];
	                $Indicator= $getTax[indicator];

//                        if ($Basis==3) {
                                $uom=$getTax[uom];
  //                      }
                        if ($Indicator==2) {

                                $Fmode = $getTax[mode];
					if ($Fmode==0) {
						$Fmode=1;
					}
					if ($Fmode==1) {
					$AmountFormula = $getTax[amtformula];
?>
                <input type=hidden name=AmountFormula size=20 value="<?php echo $AmountFormula;?>">
<?php
					}



                                        if ($Fmode==2) {
                                                $cntComplex =  new TaxFee;
                                                $cntComplex->CountTaxFeeComplex($aTAXFEEid);
                                                ?>
                                                <input type=hidden name=varflag value=<?php echo
                                                $cntComplex->outnumrow; ?>>
                                                <?php
                                                if ($variable=='') {
                                                $variable = $cntComplex->outnumrow;
                                                } elseif ($varflag<>$variable) {
                                                $variable=$variable;
                                                }
                                                $varval=1;
                                                $complex_formula = $getTax[amtformula];
                                                $editme = "234";
                                                $queryc = $cntComplex->outselect;

					?>
				                <table border=0 width=50% align=left>
				                <tr>
				                <td align=right></td><td></td>
				                </tr>
					<?php
				                $varme = 0 ;
			                        while ($varme < $variable)
			                        {
			                        $varme++;
					?>
			                                <tr><td align=right>
                                <input type=hidden name=ivar[<?php echo $varme; ?>] value=<?php echo "X$varme"; ?>>
                        			        </td><td>
		                        <?php
					        if ($editme==234) {
				                $complexTFO = new TaxFee;
				                $complexTFO->FetchTaxFeeArray($queryc);
        				        $comp_tfo=$complexTFO->outarray;
				                $complex_tfo[$varme] = $comp_tfo[complex_tfoid];
				        ?>
		                <input type=hidden name=compid[<?php echo $varme; ?>] value=<?php echo $comp_tfo[compid]; ?>>
			        <?php
			       			}
					

				        echo get_hidden_data($dbLink,'complex_tfo['.$varme.']','ebpls_buss_tfo a, ebpls_buss_taxfeeother b','taxfeeid','tfodesc',$complex_tfo[$varme],'true',"a.tfoid= b.tfo_id and b.natureid=$natureid and a.tfostatus='A' and  b.taxtype=$TaxType order by taxfeeid",'');
?>
                                </td>
                                </tr>
<?php
			                        }
			                if ($complex_formula=='') {
			                        $complex_formula="X0";
			                }
?>
			                <tr><td></td>
			                <td><input type=hidden name=complex_formula value="<?php echo $complex_formula; ?>">
			                </table>
<?php
       					}
				
                        } elseif ($Indicator==3) {
                                $rangeval=1;
                                $cntRange =  new TaxFee;
                                $cntRange->CountTaxFeeRange($aTAXFEEid);
                                ?>
<input type =hidden name=varflag value=<?php echo $cntRange->outnumrow; ?>>
                                <?php
                                if ($AmountFormula=='') {
                                    $AmountFormula = $cntRange->outnumrow;
                                } elseif ($varflag<>$AmountFormula) {
                                    $AmountFormula=$AmountFormula;
                                }
                                $editme="246";
                                $query=$cntRange->outselect;
?>
<table border=0 width=50% align=center>
                <tr>
                <td></td><td></td><td></td><td></td>
                </tr>
<?php
				
				$rangeme = 0 ;
	                        while ($rangeme < $AmountFormula)
        	                {
                	        if ($editme==246) {
                        	$lRange = new TaxFee;
	                        $lRange->FetchTaxFeeArray($query);
        	                $range_list=$lRange->outarray;
                	        $range_id[$rangeme] = $range_list[rangeid];
                        	?>
	                <input type=hidden name=rangeid[<?php echo $rangeme; ?>] value=<?php echo $range_id[$rangeme]; ?>>
<?php
        	                $rlow[$rangeme] = $range_list[rangelow];
                	        $rhigh[$rangeme] = $range_list[rangehigh];
                        	$rvalue[$rangeme] = $range_list[rangeamount];
                        	}

                                if ($rangeme==0) {

                        ?>
                                <tr><td>
                                </td>
                                <td>
                                <input type=hidden name=rlow[<?php echo $rangeme; ?>] value=0 readonly>
                                </td><td>
                                <input type=hidden name=rhigh[<?php echo $rangeme; ?>] value=<?php echo $rhigh[$rangeme]; ?> <?php echo $readme2; ?>>
                                </td>
                                <td>
                                <input type=hidden name=rvalue[<?php echo $rangeme; ?>] value="<?php echo $rvalue[$rangeme]; ?>">
                                </td>
                                </tr>
                        <?php
                                } elseif ($rangeme==$AmountFormula-1) {
                        ?>
                                <tr><td>
                                </td>
                                <td>
                                <input type=hidden name=rlow[<?php echo $rangeme; ?>] value=<?php echo $rlow[$rangeme]; ?>>
                                </td><td>
                                <input type=hidden name=rhigh[<?php echo $rangeme; ?>] value='' readonly>
                                </td>
                                <td>
                                <input type=hidden name=rvalue[<?php echo $rangeme; ?>] value="<?php echo $rvalue[$rangeme]; ?>">
                                </td>
                                </tr>
                                <?php
                                } else {
                                ?>
                                <tr><td>
                                </td>
                                <td>
                                <input type=hidden name=rlow[<?php echo $rangeme; ?>] value=<?php echo $rlow[$rangeme]; ?>>
                                </td><td>
                                <input type=hidden name=rhigh[<?php echo $rangeme; ?>] value=<?php echo $rhigh[$rangeme]; ?>>
                                </td>
                                <td>
                                <input type=hidden name=rvalue[<?php echo $rangeme; ?>] value="<?php echo $rvalue[$rangeme]; ?>">
                                </td>
                                </tr>
                                <?php
                                }
                                ?>

<?php
                                $rangeme++;
                        }
        }
?>
                <input type=hidden name=howmanycomplex value=<?php echo $varme; ?>>
                <input type=hidden name=howmanyrange value=<?php echo $rangeme; ?>>
<?php




                        }//ind = 3
                if ($notvalid<>2 and $Indicator<>3) {
                $AmountFormula= $getTax[amtformula];
                }
                $MinAmount= $getTax[min_amt];
		$orig_complex = $complex_formula;
		$howmanycomplex=$varme;
		$howmanyrange=$rangeme;
		
		include "copy_nat.php";
		}
	$rangeme=0;
	$varme=0;
	$editme=0;
	$AmountFormula=0;
	$lop++;
	}
?>
<body onload="alert('Copy Completed!'); opener.location.reload(true); window.close();"></body>
<?php
}



?>
<form name=_FRM action=''>
<input type=hidden name=natureid value="<?php echo $natureid; ?>">
<table border =0 width=60% align=center><Br><br>
<tr>
<td> Copy from Line of Business </td>
<td align=center ><?php
		echo get_select_data($dbLink,"fromnat","ebpls_buss_nature","natureid","naturedesc",$fromnat,"true","naturestatus='A' and natureid<>'$natureid' order by naturedesc","onchange='_FRM.submit();'");

?>
</td>
</tr>
</table>
<table border =0 width=70% align=center><Br>
<?php
      
        $ic = 0;
        $col1 = 0;
                $getreq = SelectDataWhere($dbtype,$dbLink,"ebpls_buss_taxfeeother a, ebpls_buss_tfo b", "where a.tfo_id=b.tfoid and a.natureid='$fromnat' order by a.mode");
 
                $gt = NumRows($dbtype,$getreq);

                while ($ic<$gt)
                {
                    while ($s = FetchArray($dbtype,$getreq))
                        {
                        $ic++;
                                if ($col1==0) {
include'tablecolor-inc.php';
print "<tr bgcolor='$varcolor'>\n";

                                }

                      if ($s[taxtype]==1) {
				$lbl = ' (New)';
		      }

		      if ($s[taxtype]==2) {
                                $lbl = ' (ReNew)';
                      }
	
		      if ($s[taxtype]==3) {
                                $lbl = ' (Retire)';
                      }
              if ($s[mode]==2) {
	              $lbl = $lbl." <Br>- Complex Formula";
              } 
		 
$getr[1]=stripslashes($getr[1]);
                                print "
                                <td align=right width=5%><input type=hidden name=col1[$ic]
                                value=$s[taxfeeid]>&nbsp
                                <input type=checkbox name=x[$ic]></td><td align=left width=23%>$s[tfodesc] $lbl
                                </td>";

                        $col1=$col1+1;
                        $arr_id[$i++] = $ic;
                                if ($col1>1) {
                                print "</tr><tr>";
                                $col1=0;
                                }

                }

                }


?>
<script language='Javascript' src='javascripts/default.js'></script>
<script language='Javascript'>

function checkAll(ids)
{
        var _FRM     = document._FRM;
        var _arr_ids = ids.split(":");
        for (var i=0;  i < _FRM.elements.length; i ++)
        {
                var _element = _FRM.elements[i];
                var _type = _element.type;

                if ( _type == "checkbox" )
                {
                        for(j=0;j<_arr_ids.length;j++)
                        {
                                var _chkname = 'x[' + _arr_ids[j] + ']';
                                if(_chkname == _element.name)
                                {
                                        _element.checked = (! _element.checked ) ? true : true;
                                        break;
                                }
                        }
                }
        }
}
</script>

<script language='Javascript'>

function clearAll(ids)
{
        var _FRM     = document._FRM;
        var _arr_ids = ids.split(":");
        for (var i=0;  i < _FRM.elements.length; i ++)
        {
                var _element = _FRM.elements[i];
                var _type = _element.type;

                if ( _type == "checkbox" )
                {
                        for(j=0;j<_arr_ids.length;j++)
                        {
                                var _chkname = 'x[' + _arr_ids[j] + ']';
                                if(_chkname == _element.name)
                                {
                                        _element.checked = (! _element.checked ) ? false : false;
                                        break;
                                }
                        }
                }
        }
}
</script>


                <input type=hidden name=howmany value=<?php echo $ic; ?>>
                </tr>
              <td align="right" valign="top" class='normal' colspan=4>&nbsp;
<?php
 $list_id     = @join(':',$arr_id);
        print "&nbsp;<a href='javascript:checkAll(\"$list_id\")'
                class='subnavwhite' onClick=''>Check All</a>\n";
        print "&nbsp;<a href='javascript:clearAll(\"$list_id\")'
                class='subnavwhite' onClick=''>Clear All</a>\n<br>";


?>

 </td>

</table>
<input type=hidden name=copynow>
<table border =0 width=60% align=center><br>
<tr>
<td align=center><input type=button name=b1 value="COPY" onclick="_FRM.copynow.value=1; _FRM.submit();">&nbsp;
<input type=button name=b2 value="CLOSE" onclick="window.close();">
</td>
</tr>
</table>


