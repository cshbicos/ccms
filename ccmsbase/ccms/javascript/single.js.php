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
<?php
include('../settings.php');
?>

/**
* switches between show and editmode.
* This function switches between show and editmode and adjustes the links in the header accordingly
* @returns void
*/

function switch_boxes_<?php echo $_GET['name'];?>()
{
	vis = document.getElementById('ccms_<?php echo $_GET['name'];?>_vis');
	edit = document.getElementById('ccms_<?php echo $_GET['name'];?>_edit');
	header = document.getElementById('ccms_<?php echo $_GET['name'];?>_editmode_header');
	newhtml = "";
	if(vis.style.display == 'none'){
		vis.style.display = 'block';
		edit.style.display = 'none';
		newhtml = "<a href=\"javascript:switch_boxes_<?php echo $_GET['name'];?>()\" class=\"ccms_headeditlink\">edit</a>";
	}else{
		vis.style.display = 'none';
		edit.style.display = 'block';
		newhtml = "<a href=\"javascript:update_iframe_<?php echo $_GET['name'];?>()\" class=\"ccms_headeditlink\">speichern</a> ";
		newhtml += "<a href=\"javascript:switch_boxes_<?php echo $_GET['name'];?>()\" class=\"ccms_headeditlink\">abbrechen</a>";
		
	}	
	header.innerHTML = newhtml;
}



/**
* updates the iframe with the editmode values and submits.
* This is the function if the GUI wants to have its values updated.<BR>
* It first loads all values into the corresponding boxes on the iframe and then submits the iframe.
* @returns void
*/

function update_iframe_<?php echo $_GET['name'];?>()
{


	var iframe = <?php echo CCMS_IFRAME_DOMPATH;?>.ccms_iframe_<?php echo $_GET['name'];?>.document;

	var i=0;
	while(document.getElementsByName("ccms_<?php echo $_GET['name'];?>_t" + i)[0]){
	  ccms_copy(document.getElementsByName("ccms_<?php echo $_GET['name'];?>_t" + i)[0], iframe.getElementsByName("ccms_t" + i)[0]);
		i++;
	}
	iframe.getElementsByName('ccms_single_form')[0].submit();

}
