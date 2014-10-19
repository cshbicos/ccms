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


/** @defgroup Serverclasses CCMS Object Serverclasses
*  @ingroup CCMSObjects
*  The Serverclasses to the CCMS Objects
* @{
*/

/**
* @file CCMSSingleServer.class.php
* Server part of the CCMS Single Object.
*/

/**
* The serverparts for the CCMS Single Object.
* This is the serverobject to any gui. This will be used in the hidden iframe.<BR>
* This is object resides in $_SESSION['ccms_s_'.$objectname] and will be unserialized in the iframe again.<BR>
* There is an $id given for database purposes, but its not neccessary needed. 
*/

abstract class CCMSSingleServer extends CCMSGenericServer
{
  /**
	* The update function.
	* All elements with name will be listed here and the new values are set in an array like <BR>
	* $vars['ccms_t'.$elemennr]
	* @param &$vars a reference to the $_POST array
	* @returns void
	* @see CCMSElements, CCMSSingleServer
	*/
	abstract public function update_me(&$vars);

	/**
	* A id for the current "record".
	* Just for convinience if databases are used
	*/
	protected $id;
	
	/**
	* Magic function for serialize().
	* Returns all variables to be saved when serializing.
	*/
	public function __sleep()
	{
		return array_merge ( parent::__sleep(), array('id'));
	}


	/**
	* Constructor.
	* @param $valstor a serialized CCMSElementValueStorage Object, the same as created for the GUI
	* @param $id the database id (not neccessary)
	* @returns void
	*/
	public function __construct($valstor, $id)
	{
		parent::__construct($valstor);
		$this->id = $id;
	}

	/**
	* Creates the DIVS like in the GUI.
	* The Iframe values are copied two ways:<BR>
	* 1. the editboxes are copied value for value from iframe to the corresponding on the GUI<BR>
	* 2. the "showmode" div is created in the iframe and copied as it is to the GUI replacing the old showmode<BR>
	* This function creates the two different versions ( showmode with divs or all elements)
	* @param $show create the showmode div or the editboxes
	* @param &$vals the values from $_POST
	* @returns void
	* @see CCMSGenericServer::write_div(), CCMSGenericServer::showelements()
	*/
	public function showme(&$vals, $show)
	{
		$return = parent::write_server($vals, $show, 0);
		if($show == true)
			parent::generic_container($return, "t", true);
		else
			echo $return;
	}

	
	/**
	* Creates the javascript to update the GUI.
	* This will for one update the editboxes for the GUI in editmode and copy the div for the showmode to the gui
	* @returns void
	*/
	public function get_js()
	{
		?>
		//update the visual part
		vis = window.parent.document.getElementById('ccms_<?php echo $this->objectname;?>_vis');
		cur_settext = document.getElementById('ccms_t_vis').innerHTML;
		vis.innerHTML = cur_settext;


		//update the editboxes
		parentwin = window.parent.document;
		var i=0;
		while(document.getElementsByName("ccms_t" + i)[0]){
			ccms_copy(document.getElementsByName('ccms_t' + i)[0], parentwin.getElementsByName('ccms_<?php echo $this->objectname;?>_t' + i)[0]);
			i++;
		}
	

		//switch the parten back to view mode
		window.parent.switch_boxes_<?php echo $this->objectname;?>();
		<?php

		
	}


}
/*! @} */

?>
