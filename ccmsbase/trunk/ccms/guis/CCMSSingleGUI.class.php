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

/** @defgroup CCMSObjects CCMS Objects
*  The CCMS Objects
*/

/** @defgroup GUIS CCMS Object GUIs
*  @ingroup CCMSObjects
*  The GUIs to the CCMS Objects
* @{
*/

/**
* @file CCMSSingleGUI.class.php
* GUI part of the CCMS Single Object.
*/

/**
* The GUI for the CCMS Single Object.
* This is the part the user will see in every case, wheter showmode or not.<BR>
* The single object is one of the fundamental CCMS Objects.<BR>
* It allows for a userdefinded order of elements in a single "entity"
*/


class CCMSSingleGUI extends CCMSGenericGUI
{

	/**
	* Magic function for serialize().
	* Returns all variables to be saved when serializing.
	*/
	public function __sleep()
	{
		parent::__sleep();
	}

	/**
	* The contructor.
	* @param $name name of the CCMS Object
	* @param $editmode show or editmode
	* @returns void
	*/
	public function __construct($name, $editmode)
	{
		parent::__construct($name, $editmode);
	}

	/**
	* Loads the serverobject into a session variable.
	* @param $obj name of the serverobject
	* @param $id an identifier for write options in the serverobject
	* @returns void
	*/
	protected function set_serverobject($obj, $id)
	{
		if($this->editmode == 1){
			$serverobject = new $obj(serialize($this->elements), $id);
			$_SESSION['ccms_s_'.$this->objectname] = serialize($serverobject);
		}
	}

	/**
	* Adds a value to one of the CCMS Object's elements.
	* @param $elementid the elementid of the element as specified in the xml file
	* @param $val the value for the element
	* @returns void
	* @see CCMSElementValueStorage
	*/
	protected function add_value($elementid, $val)
	{
		$this->elements->add_value($elementid, $val);
	}

	/**
	* Adds a dyntag to one of the CCMS Object's elements.
	* @param $elementid the elementid of the element as specified in the xml file
	* @param $tag the tag to be changed
	* @param $attributes attributes to the dyntag
	* @param $val the value for the element
	* @returns void
	* @see CCMSElementValueStorage
	*/
	protected function add_dyntag($elementid, $tag, $val, $attributes)
	{
		$this->elements->add_dyntag($elementid, $tag, $val, $attributes);
	}

	/**
	* Writes the CCMS Object.
	* Usually this is called upon after setting up the object.<BR>
	* It creates the divcontainers, one in showmode and if editmode is on, one in editmode as well<BR>
	* This heavily relies on the CCMSGenericGUI::write_engine()
	* @returns void
	* @see CCMSGenericGUI::write_engine()
	*/
	public function write_me()
	{
		if($this->editmode == 1){
			$this->includes("single", "single.php");
			$this->start_label();
			$this->headadd_edit();
			$this->stop_label_head();
			
		}
		$this->generic_container($this->write_gui(0, true), $this->objectname, true);
		if($this->editmode == 1){
			$this->generic_container($this->write_gui(0, false), $this->objectname, false);			
			$this->end_label();
		}
	}

	/**
	* Writes the "edit" link for the editmode CCMS Object.
	* @returns void
	*/
	private function headadd_edit()
	{
		?><a href="javascript:switch_boxes_<?php echo $this->objectname;?>()" class="ccms_headeditlink">edit</a><?php
	}	

}
/*! @} */


?>
