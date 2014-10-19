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

/** @addtogroup CCMSCustomExamples
* @{
*/


/** 
* @file CCMSCustomListServer.class.php
* The serverclass to CCMSCustomListGUI
* @see CCMSGenericGUI, CCMSGenericServer, CCMSListGUI, CCMSListServer
*/

/** 
* An example class on how the a CCMSCustomTableServer class works
*/

class CCMSCustomListServer extends CCMSListServer
{
	/**
	* Adds a new entry filled with standart values.
	* Real values will be filled in via edit. Makes sense if you consider the user only clicks the "add" link to create a new row.<BR>
	* You can also add "standard" values to the newly created row in here. Just add them as done in the GUI object.<BR>
	* @param $lvl the indent level of the added value
	* @returns the id of the new row
	* @see list.php
	*/
	public function add_new($lvl)
	{
		$sql = "INSERT INTO `test` SET `testvalue`='to be insert here'";
		$newid = 0; //mysql_insert_id();	
		$this->add_tmp_val('textbox2', '24.09.2004');
		$this->add_tmp_val('textbox3', 'standard entry');	
		$this->add_dyntag('test', 'txt', 'wtfnew', false);
		return $newid;
	}

	/**
	* Takes care of deleting one data row.
	* The database id will be handed over via id. This function only needs to take care of deleting it.<BR>
	* BEWARE!!!!!! This will not delete any subentries of the list, although it will not show them anymore (until reload)<BR>
	* It is up to you to delete them!!!
	* @param $id the databaseid
	* @returns void
	* @see list.php
	*/
	public function del_me($id)
	{
		$sql = "DELETE FROM `test` WHERE `this`='is an example'";
	}

	/**
	* Updates a row.
	* $vars contains a $_POST array with the values set up like this:<BR>
	* $vars['ccms_t0'], $vars['ccms_t1'] etc. <BR>
	* Note: only real elements are included in numbering, meaning for example CCMSRawTextElements <BR>
	* are excluded and will not "break" the numbering<BR>
	* $vars['id'] contains the databaseid as set during adding the row.
	* @param &$vars the variable array.
	* @param $lvl the indent level
	* @returns void
	* @see list.php
	*/
	public function update_me(&$vars, $lvl)
	{
		$sql = "UPDATE `test` SET `firstcolumn` = '".$vars['ccms_t1']."',  `andsoon`='".$vars['ccms_t2']."'
				WHERE `id`=".$vars['id']." LIMIT 1";
	}




}
/*! @} */
?>
