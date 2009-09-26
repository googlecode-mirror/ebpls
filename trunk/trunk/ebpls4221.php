<?php 	
//      Description:ebpls4221.php - one file that serves all other permit templates
//      author: Vnyz Sofhia Ice
//      Trademark: [V[f]X]S!73n+_K!77er
//      Last Updated: Nov 24, 2004 Trece Martires, Cavite
//      table used: permit_templates

require_once "includes/variables.php";

//search existing templates
                                                                                                                                                           
$searchtemp = mysql_query("select count(tempid) as total
                        from permit_templates where
                        permit_type='$tag'")
                        or die ("permit_template".mysql_error());
$tottemp = mysql_fetch_row($searchtemp);
if ($tottemp[0]=='0') { //new template
                                                                                                                                                           
                                                                                                                                                           
} else { //existing template

	if ($prev<>'Preview' and $save<>'Save') {
                                                                                                                                                           
$searchexist = mysql_query("select * from permit_templates
                                where permit_type='$tag'")
                        or die ("permit_template");
                                                                                                                                                           
$exist = mysql_fetch_row($searchexist);
$office = $exist[1];
$permit = $exist[2];
$par1 = $exist[3];
$par2 = $exist[4];
$sign1=$exist[8];
$act1 =$exist[9];

if ($act1==1) {
	$gets = mysql_query ("select * from global_sign where sign_id=$sign1");
	$gs=mysql_fetch_row($gets);
	$gs_name1=$gs[1];
	$t1 = 'CHECKED';
}

$sign2=$exist[10];
$act2 =$exist[11];

if ($act2==1) {
        $gets = mysql_query ("select * from global_sign where sign_id=$sign2");
        $gs=mysql_fetch_row($gets);
        $gs_name2=$gs[1];
        $t2 = 'CHECKED';
}



$sign3=$exist[12];
$act3 =$exist[13];

if ($act3==1) {
        $gets = mysql_query ("select * from global_sign where sign_id=$sign3");
        $gs=mysql_fetch_row($gets);
        $gs_name3=$gs[1];
        $t3 = 'CHECKED';
}


$sign4=$exist[14];
$act4 =$exist[15];

if ($act4==1) {
        $gets = mysql_query ("select * from global_sign where sign_id=$sign4");
        $gs=mysql_fetch_row($gets);
        $gs_name4=$gs[1];
        $t4 = 'CHECKED';
}


$permit_header = $exist[16];
$permit_date = $exist[17];
$permit_sequence = $exist[18];
$permit_position = $exist[19];
                                                                                                                                                           
	}                                                                                                                                                           
                                                                                                                                                           
}

                                                                                                                                                         
require_once "includes/permit.php";




//button commands
                                                                                                                                                                                                               
if ($prev=='Preview' ) { //show template preview
$tag = $permit_type;
$permit_date = $permit_date;
	if ($tag=='Occupational') {
	
	} else {
require_once "templates/permit_prev.php";
	}
} elseif ($save=='Save') { //save to table
                                                                                                                                                                                                               
                                                                  
if (!is_numeric($permit_sequence)) {

   print "<div align=center> <font color=red>Please Input Numeric Sequence Number </font></div>";
                                                                                                                            
} else {                         

if ($permit_date=='1') {
	$permit_date="1";
} else {
	$permit_date="0";
} 

if ($act1=='on') {
        $act1=1;
} else {
        $act1=0;
}
                                                                                                               
if ($act2=='on') {
        $act2=1;
} else {
        $act2=0;
}
                                                                                                               
                                                                                                               
if ($act3=='on') {
        $act3=1;
} else {
        $act3=0;
}
                                                                                                               
if ($act4=='on') {
        $act4=1;
} else {
        $act4=0;
}


        if ($tottemp[0]=='0') { //new template
                $savetemp = mysql_query("Insert into permit_templates
                values
	        ('', '$office', '$permit', '$par1', '$par2', 
		'$tag', '$usern',now(), 
		$sign1, $act1, $sign2, $act2, 
		$sign3, $act3, $sign4, $act4,
		'$permit_header', '$permit_date', $permit_sequence, '$permit_pos'
		)") or die ("die insert

 ('', '$office', '$permit', '$par1', '$par2',
                '$tag', '$usern',now(),
                $sign1, $act1, $sign2, $act2,
                $sign3, $act3, $sign4, $act4,
                '$permit_header', '$permit_date', $permit_sequence, '$permit_pos'

".mysql_error());

        	} else { //update
        
	        $updatetemp = mysql_query("Update permit_templates set
        	office='$office', par1='$par1', par2='$par2',
	        user='$usern', date_entered=now(),
	        sign1='$sign1', sign2='$sign2',
        	sign3='$sign3', sign4='$sign4',
	        act1=$act1, act2=$act2,
	        act3=$act3, act4=$act4,
		permit_header='$permit_header', permit_date='$permit_date',
		permit_sequence=$permit_sequence, permit_pos='$permit_pos'
	        where permit_type='$tag'") or die("update error");

        	}
}                                                                                                                                                                                                               
}
                                                                                                                            

require_once "includes/permit.php";
?>
