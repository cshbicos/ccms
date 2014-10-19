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
 
include('../include.php');

if($_SESSION['ccms_admin'] != 1)
	exit;

//checks wheter there is a registered fileelement for the id, and if yes, loads the class that belongs to it
if(isset($_SESSION['ccms_fileregister'][$_GET['id']]))
	$fileobject = unserialize($_SESSION['ccms_fileregister'][$_GET['id']][0]);
else
	exit;


//check for the userobject of the pictureshow

if(!isset($_SESSION['ccms_s_'.$fileobject->get_elementname()]))
    exit;
else
   $serverobject = unserialize($_SESSION['ccms_s_'.$fileobject->get_elementname()]);

$objectname = $fileobject->get_elementname();
?>

<head>
  <title>CCMS Upload</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="stylesheet" href="<?php echo CCMS_CSSPATH;?>ccms_generic.css" />
  <script type="text/javascript">
	/* <![CDATA[ */
	function switch_to_load()
	{
		document.getElementById('ccms_upload_gif').style.display = 'block';
	}

	function addtotable(picname, prename, desc)
	{
		var table = opener.document.getElementById("ccms_<?php echo $objectname;?>_tbl");	
		var precols = <?php echo $serverobject->get_precols();?>;

		if((table.rows.length == 0) || (table.rows[(table.rows.length-1)].cells.length == precols))
			var usedtr = table.insertRow(table.rows.length);
		else
			var usedtr = table.rows[(table.rows.length-1)];
		
		PIC_A = document.createElement("a");			
		PIC_A.setAttribute('href', "javascript:open_showwindow_<?php echo $objectname;?>('" + picname + "')");

		DEL_A = document.createElement("a");		
		DEL_A.setAttribute('href', "<?php echo CCMS_IFRAMEPATH;?>pictureshow.php?id=<?php echo $_GET['id'];?>&picname=" + picname + "&picprename=" + prename);
		DEL_A.setAttribute('class', "ccms_headeditlink");
		DEL_A.setAttribute('target', "ccms_iframe_<?php echo $objectname;?>");

		//the preview pic
		
		PIC_IMG = document.createElement("img");	
		PIC_IMG.setAttribute('alt', desc);
		PIC_IMG.setAttribute('title', desc);
		PIC_IMG.setAttribute('class', "<?php echo $serverobject->get_picstyle();?>");
		PIC_IMG.setAttribute('src', "<?php echo $fileobject->get_webpath();?>" + prename);

		//the delte text
		DEL_TXT = document.createTextNode('delete');

		//the break
		BR = document.createElement("br");

		//"unite them"
			
		PIC_A.appendChild(PIC_IMG);
		DEL_A.appendChild(DEL_TXT);
		
		//the final column
		TD = document.createElement("td");
		TD.setAttribute('style', "text-align:center;" );
		TD.setAttribute('id', "ccms_<?php echo $objectname;?>_pic_" + picname );

		TD.appendChild(PIC_A);
		TD.appendChild(BR);
		TD.appendChild(DEL_A);
		usedtr.appendChild(TD);	

	}
	/* ]]> */
  </script>
</head>
<body>

<form action="pictureshow_upl.php" method="GET">
	<input type="hidden" name="id" value="<?php echo $_GET['id'];?>" />
	Wieviel uploaden?&nbsp;&nbsp;<select name="many" onchange="this.form.submit();" class="ccms_insertboxes"> 
	<?php
	$manys = array(1, 2, 5, 10, 15, 20);
	
	for($i=0;$i<count($manys);$i++){
		echo "<option value=\"".$manys[$i]."\"";
		if($_GET['many'] == $manys[$i])
			echo " selected=\"selected\"";
		echo ">".$manys[$i]."</option>";
	
	}
	?></select>

</form>

<form method="post" name="ccms_file_form" action="pictureshow_upl.php?id=<?php echo $_GET['id'];?>" enctype="multipart/form-data">
	<div id="ccms_upload_tbl">

	<?php
	if(!isset($_GET['many']))
		$_GET['many'] = 1;

	for($i=0;$i<$_GET['many'];$i++){
		?>	
		<p>
		<table cellpadding="0" cellspacing="0" border="0">
			<tr><td>File: </td><td>
			
				<input class="ccms_insertboxes" type="file" name="newfile[]" id="ccms_<?php echo $_GET['name'];?>_upl" <?php
				if($_session['ccms_fileregister'][$_get['id']][4] != ""){
					?>accept="<?php echo $_SESSION['ccms_fileregister'][$_GET['id']][4];?> "<?php
				}
				if($_session['ccms_fileregister'][$_get['id']][5] > 0){
					?>maxlength="<?php echo $_SESSION['ccms_fileregister'][$_GET['id']][5];?> "<?php
				}
				?>>
			</td></tr><tr><td>
				Beschreibung: </td><td><input type="text" name="filedesc[]" class="ccms_insertboxes" />
			</td></tr>
		</table>
		</p>
		<?php
	}
	?><p>
	<input class="ccms_insertboxes" type="submit" name="filesubmit" id="ccms_<?php echo $_GET['id'];?>_submit" value="Uploaden" onclick="switch_to_load()" />
	</p>
	</div>
	<div id="ccms_upload_gif" style = "display : none;">
		<img src="<?php echo CCMS_IMGPATH;?>loader.gif" alt="loading..." /><br />Uploading...
	</div>

</form>

<?php

if(isset($_FILES['newfile'])){ 

	?>
	<script type="text/javascript">
		/* <![CDATA[ */
		<?php
		
		for($i=0;$i<count($_FILES['newfile']['name']);$i++){
			if(empty($_FILES['newfile']['name'][$i]))
				continue;

			$extension = substr(strrchr($_FILES['newfile']['name'][$i], "."), 1);

			$cur = array("name" => "IMG_.".$extension,
				"type" => $_FILES['newfile']['type'][$i],
				"tmp_name" => $_FILES['newfile']['tmp_name'][$i],
				"error" => $_FILES['newfile']['error'][$i],
				"size" => $_FILES['newfile']['size'][$i]);
			list($filename, $thumbname) = $fileobject->upload_pic($cur);
			
			$serverobject->newpic($filename, $thumbname, $_POST['filedesc'][$i]);

			?>
			addtotable('<?php echo $filename;?>', '<?php echo $thumbname;?>', '<?php echo $_POST['filedesc'][$i];?>');
			<?php
		
		}
		
		?>
		/* ]]> */
	
	</script>
	<?php
}
 ?>

</body>
</html>
