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
* Switches a particular listentry to editmode and back.
* @param id the id of the the container
* @returns void
*/
function switch_boxes_<?php echo $_GET['name'];?>(id, startelement)
{

	var i = 0;
	vis = document.getElementById('ccms_<?php echo $_GET['name'];?>_' + id + '_' + 'vis');
	edit = document.getElementById('ccms_<?php echo $_GET['name'];?>_' + id + '_' + 'edit');

	whichway = switch_em_<?php echo $_GET['name'];?>(vis, edit);


	header = document.getElementById('ccms_<?php echo $_GET['name'];?>_' + id + '_fieldset');
	newhtml = "";
	if(whichway == 0){
		newhtml = "<a href=\"javascript:switch_boxes_<?php echo $_GET['name'];?>('" + id + "', '" + startelement + "')\" class=\"ccms_headeditlink\">edit</a> ";
		newhtml += "<a href=\"javascript:del_li_<?php echo $_GET['name'];?>('" + id + "')\" class=\"ccms_headeditlink\">delete</a>";
	}else{
		newhtml = "<a href=\"javascript:update_iframe_<?php echo $_GET['name'];?>('" + id + "', '" + startelement + "')\" class=\"ccms_headeditlink\">speichern</a> ";
		newhtml += "<a href=\"javascript:switch_boxes_<?php echo $_GET['name'];?>('" + id + "', '" + startelement + "')\" class=\"ccms_headeditlink\">abbrechen</a>";
	}	
	header.innerHTML = newhtml;
}

/**
* The function for switching.
* called by switch_boxes_<?php echo $_GET['name'];?>(id)
* @param vis the showbox
* @param edit the editbox
* @returns 0 on switching to showmode, 1 on switch to editmode
*/
function switch_em_<?php echo $_GET['name'];?>(vis, edit)
{
	if(vis.style.display == 'none'){
		vis.style.display = 'inline';
		edit.style.display = 'none';
		return 0;	
	}else{
		vis.style.display = 'none';
		edit.style.display = 'inline';	
		return 1;
	}
}

/**
* The function called by the GUI to initate the deleting sequenze on the server.
* @param id the rowid to be deleted
* @returns void
*/
function del_li_<?php echo $_GET['name'];?>(id)
{
	var newurl = '<?php echo CCMS_IFRAMEPATH;?>list.php?name=<?php echo $_GET['name'];?>&del='+id;
	<?php echo CCMS_IFRAME_DOMPATH;?>.ccms_iframe_<?php echo $_GET['name'];?>.location.href = newurl;
}

/**
* Deletes a row (initated by the iframe)
* After deleting the this function is called and takes care of deleting the row in the GUI
* @param id the rowid to be deleted
* @returns void
*/
function del_li_<?php echo $_GET['name'];?>_iframe(id)
{
	cur_node = document.getElementById('ccms_<?php echo $_GET['name'];?>_' + id + '_vis');
	linode = cur_node.parentNode;
	ulnode = linode.parentNode;
	ulnode.removeChild(linode);
}

/**
* Updates the values in the iframe window and submits.
* @param the rowid of which the data is read
* @returns void
*/
function update_iframe_<?php echo $_GET['name'];?>(rowid, startelement)
{

	var iframe = <?php echo CCMS_IFRAME_DOMPATH;?>.ccms_iframe_<?php echo $_GET['name'];?>.document;
	
	iframe.getElementsByName("rowid")[0].value = rowid; 
	iframe.getElementsByName("startelement")[0].value = startelement; 
	var i=startelement;
	while(document.getElementsByName("ccms_<?php echo $_GET['name'];?>_t_" + rowid + "_" + i)[0]){
	  ccms_copy( document.getElementsByName("ccms_<?php echo $_GET['name'];?>_t_" + rowid + "_" + i)[0], iframe.getElementsByName("ccms_t" + i)[0]); 
		i++;
	}
	iframe.getElementsByName('ccms_list_form')[0].submit();

}

/**
* Changes the iframe so a new entry will be added.
* @returns void
*/
function add_li_<?php echo $_GET['name'];?>(subto, lvl)
{
	//what will be the rownr for the new li?
	var newrow = 0;
 	while(document.getElementById('ccms_<?php echo $_GET['name'];?>_' +newrow+ '_vis')){
		newrow++;
	}
		
	var newurl = '<?php echo CCMS_IFRAMEPATH;?>list.php?name=<?php echo $_GET['name'];?>&new='+newrow+'&subto='+subto+ '&lvl=' + lvl;
	<?php echo CCMS_IFRAME_DOMPATH;?>.ccms_iframe_<?php echo $_GET['name'];?>.location.href = newurl;
	
}

function add_li_<?php echo $_GET['name'];?>_iframe(subto, nwrow, countstart)
{
	
	var parentol = document.getElementById("ccms_<?php echo $_GET['name'];?>_l_" + subto);
	var addli = parentol.lastChild;
	while(addli.nodeName.toLowerCase() != "li"){
		addli = addli.previousSibling;
        }

	iframeli = <?php echo CCMS_IFRAME_DOMPATH;?>.ccms_iframe_<?php echo $_GET['name'];?>.document.getElementById("ccms_preview_list").firstChild;
	var newLI = document.createElement("li");
	var test = parentol.insertBefore(newLI, addli);
	test.innerHTML = iframeli.innerHTML;
	switch_boxes_<?php echo $_GET['name'];?>(nwrow, countstart);
}




