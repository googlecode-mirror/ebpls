<script>
function getdateinfo()
{
     var x = (window.ActiveXObject) ? new ActiveXObject("Microsoft.XMLHTTP") : new XMLHttpRequest();
     var y = (window.ActiveXObject) ? new ActiveXObject("Microsoft.XMLHTTP") : new XMLHttpRequest();
     if (x && y)
     {
          x.onreadystatechange = function()
          {
               if (x.readyState == 4 && x.status == 200)
               {
                    var da_day = x.responseText;
                    document.getElementById ('d_day').innerHTML = da_day;
               }
          }
          y.onreadystatechange = function()
          {
               if (y.readyState == 4 && y.status == 200)
               {
                    var da_time = y.responseText;
                    document.getElementById ('d_time').innerHTML = da_time;
               }
          }
          x.open ("GET", 'date_day.php', true);
          x.send (null);
          y.open ("GET", 'date_time.php', true);
          y.send (null);
     }
     else
     {
          alert ('Script or compatibility error, aborting script...');
     }
     setTimeout ("getdateinfo()", 1000);
}
</script>
 
<!-- contents of date_day.php -->
 
<?php
$date = (date("D")." ".date("M")." ".date("d")." ".date("Y"));
echo $date;
?>
 
<!-- contents of date_time.php -->
 
<?php
$date = (date ("H").":".date("i").":".date("s"));
echo $date;
?>
 
<!-- document's body tag looks like that -->
 
<body onload = "getdateinfo()">