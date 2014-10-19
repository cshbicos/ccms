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
if(is_array($_SESSION['ccms_fileregister'][$_GET['id']]))
	$serverobject = unserialize($_SESSION['ccms_fileregister'][$_GET['id']][0]);
else
	exit;

?>
<head>
	<title>CCMS Upload</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="<?php echo CCMS_CSSPATH;?>ccms_generic.css" />	
	<script type="text/javascript">
	/* <![CDATA[ */
	function switch_to_load()
	{
		//document.ccms_file_form.newfile.disabled = true;
		//document.ccms_file_form.filesubmit.disabled = true;
		document.getElementById('ccms_upload_gif').style.display = 'block';

	}
	/* ]]> */
	</script>
</head>
<body>

<form method="POST" name="ccms_file_form" action="singlepic.php?id=<?php echo $_GET['id'];?>" enctype="multipart/form-data" accept-charset="UTF-8">
	<div id="ccms_upload_tbl">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr><td>
				<input type="file" name="newfile" class="ccms_insertboxes"<?php
				$type = $serverobject->get_type();
				if(!empty($type)){
					?> accept="<?php echo $type;?>"<?php
				}
				if($serverobject->get_size() > 0){
					?> maxlength="<?php echo $serverobject->get_size();?>"<?php
				}
				?> />
			</td><td>
				<input type="submit" name="filesubmit" value="Uploaden" onclick="switch_to_load()" class="ccms_insertboxes" />
			</td></tr>
		</table>
	</div>
	<div id="ccms_upload_gif">
		<img src="<?php echo CCMS_IMGPATH;?>loader.gif" alt="loading..." /><br />Uploading...
	</div>

</form>

<?php 


if(isset($_FILES['newfile'])){ ?>
	<script type="text/javascript">
		/* <![CDATA[ */
		<?php
 		$serverobject->upload_new($_FILES['newfile']);
		?>
		/* ]]> */
	
	
	</script>
<?php } ?>

</body>
</html>
