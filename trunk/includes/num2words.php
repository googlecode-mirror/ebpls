<?php

//*************************************************************
// this function converts an amount into alpha words
// with the words dollars and cents.  Pass it a float.
// Example:  $3.77 = Three Dollars and Seventy Seven Cents
// works up to 999,999,999.99 dollars - Great for checks
//*************************************************************

function makewords($numval)
{
$moneystr = "";
// handle the millions
$milval = (integer)($numval / 1000000);
if($milval > 0)
  {
  $moneystr = getwords($milval) . " Million";
  }

// handle the thousands
$workval = $numval - ($milval * 1000000); // get rid of millions
$thouval = (integer)($workval / 1000);
if($thouval > 0)
  {
  $workword = getwords($thouval);
  if ($moneystr == "")
    {
    $moneystr = $workword . " Thousand";
    }
  else
    {
    $moneystr .= " " . $workword . " Thousand";
    }
  }

// handle all the rest of the dollars
$workval = $workval - ($thouval * 1000); // get rid of thousands
$tensval = (integer)($workval);
if ($moneystr == "")
  {
  if ($tensval > 0)
    {
    $moneystr = getwords($tensval);
    }
  else
    {
    $moneystr = "Zero";
    }
  }
else // non zero values in hundreds and up
  {
  $workword = getwords($tensval);
  $moneystr .= " " . $workword;
  }

// plural or singular 'dollar'
$workval = (integer)($numval);
if ($workval == 1)
  {
  $moneystr .= " Pesos And ";
  }
else
  {
  $moneystr .= " Pesos And ";
  }

// do the pennies - use printf so that we get the
// same rounding as printf
$workstr = sprintf("%3.2f",$numval); // convert to a string
$intstr = substr($workstr,strlen - 2, 2);
$workint = (integer)($intstr);
if ($workint == 0)
  {
  $moneystr .= " Zero";
  }
else
  {
  $moneystr .= getwords($workint);
  }
if ($workint == 1)
  {
  $moneystr .= " Cent";
  }
else
  {
  $moneystr .= " Cents";
  }

// done - let's get out of here!
return $moneystr;
}

//*************************************************************
// this function creates word phrases in the range of 1 to 999.
// pass it an integer value
//*************************************************************
function getwords($workval)
{
$numwords = array(
  1 => "One",
  2 => "Two",
  3 => "Three",
  4 => "Four",
  5 => "Five",
  6 => "Six",
  7 => "Seven",
  8 => "Eight",
  9 => "Nine",
  10 => "Ten",
  11 => "Eleven",
  12 => "Twelve",
  13 => "Thirteen",
  14 => "Fourteen",
  15 => "Fifteen",
  16 => "Sixteen",
  17 => "Seventeen",
  18 => "Eightteen",
  19 => "Nineteen",
  20 => "Twenty",
  30 => "Thirty",
  40 => "Forty",
  50 => "Fifty",
  60 => "Sixty",
  70 => "Seventy",
  80 => "Eighty",
  90 => "Ninety");

// handle the 100's
$retstr = "";
$hundval = (integer)($workval / 100);
if ($hundval > 0)
  {
  $retstr = $numwords[$hundval] . " Hundred";
  }

// handle units and teens
$workstr = "";
$tensval = $workval - ($hundval * 100); // dump the 100's
if (($tensval < 20) && ($tensval > 0))// do the teens
  {
  $workstr = $numwords[$tensval];
  }
else // got to break out the units and tens
  {
  $tempval = ((integer)($tensval / 10)) * 10; // dump the units
  $workstr = $numwords[$tempval]; // get the tens
  $unitval = $tensval - $tempval; // get the unit value
  if ($unitval > 0)
    {
    $workstr .= " " . $numwords[$unitval];
    }
  }

// join all the parts together and leave
if ($workstr != "")
  {
  if ($retstr != "")
    {
    $retstr .= " " . $workstr;
    }
  else
    {
    $retstr = $workstr;
    }
  }
return $retstr;
} 
