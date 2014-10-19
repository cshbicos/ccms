<?php
/***************************************************************************
 *   Copyright (C) 2006-2007, Christoph Herrmann (theone@csherrmann.com)   *
 *                                                                         *
 *                                                                         *
 *   CCMS is free software; you can redistribute it and/or modify          *
 *   it under the terms of the GNU General Public License as published by  *
 *   the Free Software Foundation; either version 3 of the License, or     *
 *   (at your option) any later version.                                   *
 *                                                                         *
 *   CCMS is distributed in the hope that it will be useful,               *
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of        *
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         *
 *   GNU General Public License for more details.                          *
 *                                                                         *
 *   You should have received a copy of the GNU General Public License     *
 *   along with CCMS. If not, see <http://www.gnu.org/licenses/>           *
 ***************************************************************************/


if(isset($_GET['startdate']) && !empty($_GET['startdate'])){
	//to support different styles for the dateinput
	$datestyle = array('day', 'month', 'year', '.');
	
	$cur_date = explode($datestyle[3], $_GET['startdate']);
	$cur['day'] = $cur_date[array_search('day', $datestyle)]; 
	$cur['month'] = $cur_date[array_search('month', $datestyle)];
	$cur['year'] = $cur_date[array_search('year', $datestyle)];

	//day is bad
	if(!is_numeric($cur['day']) || $cur['day'] < 1 || $cur['day'] > 31)
		$cur['day'] = date("d");

	//the year is just 2 decimals, make 4!
	if(is_numeric($cur['year']) && strlen($cur['year']) == 2){
		if($cur['year'] < 50)
			$cur['year'] += 2000;
		else
			$cur['year'] += 1900;
	}
	//year is bad
	if(!is_numeric($cur['year']) || strlen($cur['year']) != 4)
		$cur['year'] = date("Y");

	//month is bad
	if(!is_numeric($cur['month']) || $cur['month'] < 1 || $cur['month'] > 12)
		$cur['month'] = date("m");

	if(!isset($_GET['year']))
		$_GET['year'] = $cur['year'];
	
	if(!isset($_GET['month']))
		$_GET['month'] = $cur['month'];

	
		
}else{
	$cur['day'] = date("d");
	$cur['month'] = date("m");
	$cur['year'] = date("Y");
}



if(!isset($_GET['year']))
	$_GET['year'] = date("Y");
if(!isset($_GET['month']))
	$_GET['month'] = date("m");

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	
<head>
	<title>CCMS DatePicker</title>
	<link rel="stylesheet" type="text/css" href="../styles/ccms_datepicker.css" />
	<script type="text/javascript">
		/* <![CDATA[ */
		function take_date_and_close(cur_one){
			opener.document.getElementsByName("<?php echo $_GET['name'];?>")[0].value=cur_one;
			window.close();
		}
		/* ]]> */
	</script>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>


<body>
<table border="0">

<tr>
	<td>
	<form action="datepicker.php" method="GET" accept-charset="UTF-8">
	<input type="hidden" name="startdate" value="<?php echo $_GET['startdate'];?>" />
	<input type="hidden" name="name" value="<?php echo $_GET['name'];?>" />
	<select name="month" onchange="this.form.submit()">
		<?php
		$monate = array("No name", 'Januar', 'Februar', 'M&auml;rz', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember' );
		for($i=1;$i<=12;$i++){
			echo "<option value=\"".$i."\"";
			if($_GET["month"] == $i)
				echo " selected=\"selected\"";
			echo ">".$monate[$i]."</option>";
			
		}
						
		
		?>
	</select>
			
	<select name="year" onchange="this.form.submit()">
		<?php
		if($cur['year'] < date("Y"))
			$start = $cur['year'];
		else
			$start = date("Y");

		for($i=$start;$i<=(date("Y")+7);$i++){
			echo "<option value=\"".$i."\"";
			if($_GET["year"] == $i)
				echo " selected=\"selected\"";
				echo ">".$i."</option>";
					
		}
						
			
		?>
	</select>
	</form>
	</td>
	<td>
	<form method="GET" accept-charset="UTF-8">
		<input type="hidden" name="startdate" value="<?php echo $_GET['startdate'];?>" />
		<input type="hidden" name="month" value="<?php echo date("m");?>" />
		<input type="hidden" name="year" value="<?php echo date("Y");?>" />
		<input type="hidden" name="name" value="<?php echo $_GET['name'];?>" />
		<input type="submit" value="Heute">
	</form>
	</td><td>
	<form method="GET" accept-charset="UTF-8">
		<input type="hidden" name="startdate" value="<?php echo $_GET['startdate'];?>" />
		<input type="hidden" name="name" value="<?php echo $_GET['name'];?>" />
		<input type="submit" value="Ausgew&auml;hlt" />
		<input type="button" onclick="window.close()" value="Schlie&szlig;en" />
	</form>
	</td>
	</tr>
</table>


		
<table cellpadding=5 cellspacing=0 border=0 >

<tr>
		
	<th>Montag</th>
	<th>Dienstag</th>
	<th>Mittwoch</th>
	<th>Donnerstag</th>
	<th>Freitag</th>
	<th>Samstag</th>
	<th>Sonntag</th>
</tr>
<tr>
<?php


/////////////vorbereitung aller tagesabfragen

//was ist der erste für ein tag?
$erster = date("w", mktime(0, 0, 0, $_GET["month"], 1, $_GET["year"]));

//was war der letzte monat
if($_GET["month"] == 1){
	$vorher['monat'] = 12;
	$vorher['jahr'] = $_GET["year"]-1;
}else{
	$vorher['monat'] = $_GET["month"]-1;
	$vorher['jahr'] = $_GET["year"];
}

//wieviele tage hatte der letzte monat
$maxtage = date("t", mktime(0, 0, 0, $vorher['monat'], 1, $vorher['jahr']));

//damit es nicht mit sonntag beginnt
if($erster == 0)
	$erster = 7;

//von wann ab soll der vergangene monat angezeigt werden
$currentvalue = $maxtage - ($erster - 1);

//wieviele tage des naechsten monats werden angezeigt
$mintage = 7 - (($erster - 1)+date("t", mktime(0, 0, 0, $_GET["month"], 1, $_GET["year"])))%7;

//was ist der naechste monat?
if($_GET["month"] == 12){
	$folgend['monat'] = 1;
	$folgend['jahr'] = $_GET["year"]+1;
}else{
	$folgend['monat'] = $_GET["month"]+1;
	$folgend['jahr'] = $_GET["year"];
}

/////////abfragen ende

//ein paar initialisierungen vor dem start der richtigen ausgabe...
$monat = $vorher['monat'];
$jahr = $vorher['jahr'];
$j = 1;

for($i=$currentvalue; $i<=$maxtage;){
	//in den naechsten monat schalten
	if($i == $maxtage && $currentvalue != 0){
		$maxtage = date("t", mktime(0, 0, 0, $_GET["month"], 1, $_GET["year"])) - 1;
		$i=0;
		$currentvalue = 0;
		$monat=$_GET["month"];
		$jahr = $_GET["year"];
	}

	$i++;

	echo "<td";
	if($cur['year'] == $jahr && $cur['day'] == $i && $cur['month'] == $monat)
		echo " id=\"curdate\"";
	echo ">";
		?>

		<a href="javascript:take_date_and_close('<?php
		echo date("d.m.Y", strtotime($jahr."-".$monat."-".$i));

		?>')"><?php echo $i;?></a>

		<?php

	echo "</td>";

	if($j==7){
		echo "</tr><tr>";
		$j=1;
	}else {
		$j++; 
	}

}
$monat = $folgend['monat'];

if($j != 1){
	for($i=1;$i<=(8-$j);$i++){
		echo "<td";
		if($cur['year'] == $jahr && $cur['day'] == $i && $cur['month'] == $monat)
			echo " id=\"curdate\"";
		echo ">";
		?>

		<a href="javascript:take_date_and_close('<?php
		echo date("d.m.Y", strtotime($jahr."-".$monat."-".$i));

		?>')"><?php echo $i;?></a>

		<?php

		echo "</td>";
	}
}


?>
</tr>

</table>
</body>
</html>
