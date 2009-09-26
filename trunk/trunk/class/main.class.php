<?php
class MainVar {
var     
	$outrow,
	$outarray,
	$outnumrow,
	$outselect,
	$outresult,
	$outid,
	$outquery;

 		Function Query1($query)
        {

                @$this->outquery = mysql_query($query);
        }

 		Function Result($result, $RowNumber)
        {
                @$this->outresult = mysql_result($result, $RowNumber);
        }


 		Function InsertQuery($tblName,$fields,$values)
        {
				//echo "insert into $tblName $fields values ($values)";
                $r = mysql_query("insert into $tblName $fields values ($values)");
                @$this->outid = mysql_insert_id();
               
       
        }
        Function UpdateQuery($tblName,$fields,$where)
        {
//       echo "update $tblName set $fields where $where";
                $r = mysql_query("update $tblName set $fields where $where") 
			or die(mysql_error());
        }
                                                                                                                                                            
        Function DeleteQuery($tblName,$where)
        {
          //   echo "delete from  $tblName where $where";
                $r = mysql_query("delete from  $tblName where $where");
        }
                                                                                                                                                            
        Function FetchRow($query)
        {
               
                @$this->outrow = mysql_fetch_row($query);
        }
                                                                                                                                                            
        Function FetchArray($query)
        {
                @$this->outarray = @mysql_fetch_array($query);
        }

	    Function NumRows($result)
        {
         
                @$this->outnumrow = mysql_num_rows($result);
        
        }
                                                                                                                                                            
                                                                                                                                                            
        Function SelectDataWhere($tblName,$where)
        {
//echo "select * from $tblName $where";
                @$this->outselect = mysql_query("select * from $tblName $where");
			
        }

         Function SelectMultiData($fld, $tblName,$where)
        {
//echo "select $fld from $tblName $where";
                @$this->outselect = mysql_query("select $fld from $tblName $where");
			
        }
        
}


