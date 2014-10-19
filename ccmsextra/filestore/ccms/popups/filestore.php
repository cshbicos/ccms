<?php
/***************************************************************************
 *   Copyright (C) 2007, Christoph Herrmann (theone@csherrmann.com)        *
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
  <title>CCMS FileStorage</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link rel="stylesheet" href="<?php echo CCMS_CSSPATH;?>ccms_filestore.css" />	
  <script type="text/javascript">
	/* <![CDATA[ */
	 	function switch_to_load()
	  {
		  document.getElementById('upload_div_img').style.display = 'block';
	  }
	  function change_me(val)
	  {
       window.location.href = "filestore.php?id=<?php echo $_GET['id'];?>&file=" + val;
    
    }
	  
	/* ]]> */
  </script>
</head>

<body>
<?php
if(isset($_POST['filesubmit'])){
  $serverobject->upload_file($_FILES['newfile']);
  $_GET['file'] = $_FILES['newfile']['name']; 
}

if(isset($_GET['del']) && isset($_GET['file'])){
	$serverobject->del_file($_GET['file']);
	unset($_GET['file']);
}

?>


<table id="filetable" border="0">
  <tr>
    <td rowspan="5" id="selecttd">
      <select multiple="multiple" id="fileselect" onchange="change_me(this.value)">
      	<?php 
      	if(!isset($_GET['file']))
      	 $_GET['file'] = $serverobject->get_first_file();
      	
        if($handle = opendir($serverobject->get_path())) {
          while (false !== ($file = readdir($handle))) {
              if ($file != "." && $file != "..") {
                  echo "<option value=\"".$file."\"";
                  if($file == $_GET['file'])
                    echo " selected=\"selected\"";
                  echo ">".$file."</option>";
              }
          }
          closedir($handle);
        }
				
				?>
        
      </select>
    </td>
    <td class="propertytd">Filename:</td>
    <td class="valtd"><?php echo $_GET['file']; ?></td>
  </tr>
  <tr>
    <td class="propertytd">Ge&auml;ndert am:</td>
    <td class="valtd"><?php echo date("d.m.Y<b\\r />H:i", filectime($serverobject->get_path().$_GET['file'])); ?></td>
  </tr>
  <tr>
    <td class="propertytd">Link:</td>
    <td class="valtd"><a target="_blank" href="<?php echo $serverobject->get_webpath().$_GET['file']; ?>" class="linkclass">Link</a></td>
  </tr>
  <tr>
    <td colspan="2" class="valtd">
      <div id="linkdiv">
        <?php echo $serverobject->get_webpath().$_GET['file']; ?>
      </div>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <a href="filestore.php?id=<?php echo $_GET['id'];?>&amp;file=<?php echo $_GET['file'];?>&amp;del=1" class="linkclass">L&ouml;schen</a>
    </td>
  </tr>
  <tr>
    <td colspan="3" id="spacetd">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" id="uploadtxttd">Upload neues File:</td>
  </tr>
  <tr>
    <td colspan="3" id="uploadtd">
       <form method="post" action="filestore.php?id=<?php echo $_GET['id'];?>" enctype="multipart/form-data" accept-charset="UTF-8">
          	<div id="upload_div_box">
          		<input type="file" name="newfile" id="uploadbox"<?php
					$type = $serverobject->get_type();
          				if(!empty($type)){
          					?> accept="<?php echo $type;?>"<?php
          				}
          				if($serverobject->get_size() > 0){
          					?> maxlength="<?php echo $serverobject->get_size();?>"<?php
          				}
          				?> /><br />
          		<input type="submit" name="filesubmit" value="Uploaden" onclick="switch_to_load()" id="uploadsubmit" />
          	</div>
          	<div id="upload_div_img">
          		<img src="<?php echo CCMS_IMGPATH;?>loader.gif" alt="loading..." / class="loadpic"><br />Uploading...
          	</div>
       </form>
    </td>
  </tr>
</table>

</body>
</html>
