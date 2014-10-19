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


if(isset($_GET['picname']) && isset($_GET['picprename'])){
	$fileobject->delete_picture($_GET['picname'], $_GET['picprename']);
	$serverobject->delete_picture($_GET['picname'], $_GET['picprename']);
}
?>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <script type="text/javascript">
  /* <![CDATA[ */
	 //the table
   var table = window.parent.document.getElementById("ccms_<?php echo $fileobject->get_elementname();?>_tbl");	
	 var currow =0;
	 var curcell =0;
	 var idpre = "ccms_<?php echo $fileobject->get_elementname();?>_pic_";
	 var prenode;


	 while(table.rows[currow])
	 {
		 while(table.rows[currow].cells[curcell])
		 {
			if(prenode){
				tmpnode = table.rows[currow].cells[curcell].cloneNode(true);
				if(tmpnode.id != "")
					prenode.parentNode.replaceChild(tmpnode, prenode);
				else
					break;
				prenode = table.rows[currow].cells[curcell];
				curcell++;
				continue;
			}
			

			if(table.rows[currow].cells[curcell].id == idpre + "<?php echo $_GET['picname'];?>")
				prenode = table.rows[currow].cells[curcell];
			curcell++;
		}
		
		curcell =0;
		currow++;
	}
	prenode.parentNode.removeChild(prenode);

	if(table.rows[(table.rows.length-1)].cells.length == 0)
		table.deleteRow(table.rows.length-1);
	

  /* ]]> */
  </script>
</head>
<body>


</body>
</html>




