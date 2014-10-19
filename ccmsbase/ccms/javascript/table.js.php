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
* Switches a particular tablecontainer to editmode and back.
* @param id the id of the the container
* @returns void
*/
function switch_boxes_<?php echo $_GET['name'];?>(id)
{

	var i = 0;
	vis = document.getElementById('ccms_<?php echo $_GET['name'];?>_' + id + '_' + i + '_' + 'vis');
	edit = document.getElementById('ccms_<?php echo $_GET['name'];?>_' + id + '_' + i + '_' + 'edit');
	while(vis){
		whichway = switch_em_<?php echo $_GET['name'];?>(vis, edit);
		i++;
		vis = document.getElementById('ccms_<?php echo $_GET['name'];?>_' + id + '_' + i + '_' + 'vis');
		edit = document.getElementById('ccms_<?php echo $_GET['name'];?>_' + id + '_' + i + '_' + 'edit');
	}

	header = document.getElementById('ccms_<?php echo $_GET['name'];?>_' + id + '_fieldset');
	newhtml = "";
	if(whichway == 0){
		newhtml = "<a href=\"javascript:switch_boxes_<?php echo $_GET['name'];?>('" + id + "')\" class=\"ccms_headeditlink\">edit</a><br />";
		newhtml += "<a href=\"javascript:del_tr_<?php echo $_GET['name'];?>('" + id + "')\" class=\"ccms_headeditlink\">delete</a>";
	}else{
		newhtml = "<a href=\"javascript:update_iframe_<?php echo $_GET['name'];?>('" + id + "')\" class=\"ccms_headeditlink\">speichern</a><br />";
		newhtml += "<a href=\"javascript:switch_boxes_<?php echo $_GET['name'];?>('" + id + "')\" class=\"ccms_headeditlink\">abbrechen</a>";
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
		vis.style.display = 'block';
		edit.style.display = 'none';
		return 0;	
	}else{
		vis.style.display = 'none';
		edit.style.display = 'block';	
		return 1;
	}
}

/**
* The function called by the GUI to initate the deleting sequenze on the server.
* @param id the rowid to be deleted
* @returns void
*/
function del_tr_<?php echo $_GET['name'];?>(id)
{
	var newurl = '<?php echo CCMS_IFRAMEPATH;?>table.php?name=<?php echo $_GET['name'];?>&del='+id;
	<?php echo CCMS_IFRAME_DOMPATH;?>.ccms_iframe_<?php echo $_GET['name'];?>.location.href = newurl;
}

/**
* Deletes a row (initated by the iframe)
* After deleting the this function is called and takes care of deleting the row in the GUI
* @param id the rowid to be deleted
* @returns void
*/
function del_tr_<?php echo $_GET['name'];?>_iframe(id)
{
	var i=0;
	cur_node = document.getElementById('ccms_<?php echo $_GET['name'];?>_' + id + '_' + i + '_tr');
	while(cur_node){
		parentnode = cur_node.parentNode;
		del = parentnode.removeChild(cur_node);
		i++;
		cur_node = document.getElementById('ccms_<?php echo $_GET['name'];?>_' + id + '_' + i + '_tr');
	
	}
}

/**
* Updates the values in the iframe window and submits.
* @param the rowid of which the data is read
* @returns void
*/
function update_iframe_<?php echo $_GET['name'];?>(rowid)
{

	var iframe = <?php echo CCMS_IFRAME_DOMPATH;?>.ccms_iframe_<?php echo $_GET['name'];?>.document;
	
	iframe.getElementsByName("rowid")[0].value = rowid; 
	var i=0;
	while(document.getElementsByName("ccms_<?php echo $_GET['name'];?>_t_" + rowid + "_" + i)[0]){
	 hereobject = document.getElementsByName("ccms_<?php echo $_GET['name'];?>_t_" + rowid + "_" + i)[0];
   
   ccms_copy(hereobject, iframe.getElementsByName("ccms_t" + i)[0]);

	 i++;
	}
	iframe.getElementsByName('ccms_table_form')[0].submit();
}

/**
* Changes the iframe so a new row will be added.
* @returns void
*/
function add_tr_<?php echo $_GET['name'];?>()
{
	
	//what will be the rownr for the new tr?
	var newrow = 0;
	while(document.getElementById('ccms_<?php echo $_GET['name'];?>_' +newrow+ '_0_tr'))
		newrow++;
	var newurl = '<?php echo CCMS_IFRAMEPATH;?>table.php?name=<?php echo $_GET['name'];?>&new='+newrow;
	<?php echo CCMS_IFRAME_DOMPATH;?>.ccms_iframe_<?php echo $_GET['name'];?>.location.href = newurl;
}

/**
* Called by the iframe so a new row will be added.
* This is done in here, because of security restrictions in IE
* @returns void
*/
function add_tr_<?php echo $_GET['name'];?>_iframe(tmpThere, nwRow)
{
	var tmpHere = document.getElementById('ccms_<?php echo $_GET['name'];?>_tmptable');

	tmpHere.innerHTML = tmpThere.innerHTML;
	
	var i=0;
	var curNwTr;	
	var curTr;
	var thisTable = document.getElementById("ccms_<?php echo $_GET['name'];?>_tbl");

	//loop through all boxes
	while(curNwTr = document.getElementById('ccms_<?php echo $_GET['name'];?>_'+nwRow+'_' + i + '_tr')){
			curTr = thisTable.insertRow(thisTable.rows.length);
			curTr.parentNode.replaceChild(curNwTr, curTr);
			i++;
	}
	
	switch_boxes_<?php echo $_GET['name'];?>(nwRow);
}


/**
* Moves a row post update.
* Useful for sorting dates etc. 
* As for the movetypes, there are 4:<BR>
* 0-> insert at the end <BR> 1 -> insert before <BR> 2 -> insert after <BR> 3 -> insert at the top<BR>
* @param $moveid the trid to be moved
* @param $movepointdbid the trid used as the point to move it to
* @param $movetype where to move it exactly. See details.
*/
function move_tr(moveid, movepointid, movetype)
{

	//get the amount of actual trs per row
	var realtrs=0;
	while(document.getElementById('ccms_<?php echo $_GET['name'];?>_'+movepointid+'_'+realtrs+'_tr'))
		realtrs++;
	realtrs--;

	//the point on which before and after is performed
	if(movetype == 2)
		movepointnode = document.getElementById('ccms_<?php echo $_GET['name'];?>_'+movepointid+'_'+realtrs+'_tr');
	else
		movepointnode = document.getElementById('ccms_<?php echo $_GET['name'];?>_'+movepointid+'_0_tr');

	//check if the after statment might not work with nextSibling
	if(movetype == 2 && movepointnode.nextSibling == null)
		movetype = 0;


	//the table in which all is performed
	tblnode = movepointnode.parentNode;

	for(i=0;i<=realtrs;i++){
		
		if(movetype < 2)
			movenode = document.getElementById('ccms_<?php echo $_GET['name'];?>_'+moveid+'_'+i+'_tr');
		else
			movenode = document.getElementById('ccms_<?php echo $_GET['name'];?>_'+moveid+'_'+(realtrs-i)+'_tr');

		delnode = tblnode.removeChild(movenode);

		switch(movetype){
			case 0:
				tblnode.appendChild(delnode);
				break;
			case 1:
				tblnode.insertBefore(delnode, movepointnode);
				break;
			case 2:
				tblnode.insertBefore(delnode, movepointnode.nextSibling);
				break;
			case 3:
				tblnode.insertBefore(delnode, tblnode.firstChild);
				break;

		}

	}


}
