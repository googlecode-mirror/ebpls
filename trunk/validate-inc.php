<?php
switch ($validateID){
	case 0:
		echo "<p align=center><b><i><font color=#ff0033>Data successfully added to the database!!!</font></i></b></p>";
		break;

	case 1:
		echo "<p align=center><b><i><font color=#ff0033>An error has occured during execution time!!! Please report immediately to your IT personnel.</font></i></b></p>";
		break;
		
	case 2:
		echo "<p align=center><b><i><font color=#ff0033>Blank entry is not accepted!!!</font></i></b></p>";
		break;

	case 3:
		echo "<p align=center><b><i><font color=#ff0033>Record is permanently deleted!!!</font></i></b></p>";
		break;
		
	case 4:
		echo "<p align=center><font color=#ff0033><b><i>Record not found!!!</font></i></b></p>";
		break;

	case 5:
		echo "<p align=center><font color=#ff0033><b><i>Record successfully updated!!!</font></i></b></p>";
		break;
		
	case 6:
		echo "<p align=center><font color=#ff0033><b><i>Duplicate record is not accepted!!!</font></i></b></p>";
		break;
		
	case 7:
		echo "<p align=center><font color=#ff0033><b><i>Date is invalid!!!</font></i></b></p>";
		break;

	case 8:
		echo "<p align=center><font color=#ff0033><b><i>Unable to upload file!!!<br>File too large. Allowable maximum file size is 50kb.</font></i></b></p>";
		break;
	
	case 9:
		echo "<p align=center><font color=#ff0033><b><i>Record is deactivated!!!</font></i></b></p>";
		break;
		
}
?>