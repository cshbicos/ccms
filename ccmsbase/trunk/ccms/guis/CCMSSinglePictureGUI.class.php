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

/**
* @addtogroup GUIS 
* @{
*/

/** 
* @file CCMSSinglePictureGUI.class.php
* The GUI for a single picture object
*/


/**
* The GUI for the CCMS Single Picture Object.
* Displays a single picture and has a "new picture" button up top.
*/
class CCMSSinglePictureGUI extends CCMSGenericGUI
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
			$this->start_label();
			$this->headadd_edit();
			$this->stop_label_head();
			
		}
		$this->generic_container($this->write_gui(0, true), $this->objectname, true);
		if($this->editmode == 1){
			$this->generic_container($this->write_gui(0, false), $this->objectname, false);			
			$this->end_label();
			$this->write_picid();
		}
	}

	/**
	* Writes the "edit" link for the editmode CCMS Object.
	* @returns void
	*/
	private function headadd_edit()
	{
		?><a href="javascript:ccms_open_uplwindow(320, 90, 'singlepic', document.getElementsByName('ccms_<?php echo $this->objectname;?>_picid')[0].value)" class="ccms_headeditlink">upload new</a><?php
	}	

	/**
	* Writes out the pictures fileelement id.
	* This is done in the end, because at header write we don't have it .. yet :)
	* @returns void
	*/
	private function write_picid()
	{
		?><input type="hidden" name="ccms_<?php echo $this->objectname;?>_picid" value="<?php echo CCMSFileElementsRegister::$lastid; ?>" /><?php
	
	}

}

/*! @} */


?>
