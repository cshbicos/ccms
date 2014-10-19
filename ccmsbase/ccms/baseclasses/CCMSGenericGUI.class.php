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
* @addtogroup baseclasses 
* @{
*/

/** 
* @file CCMSGenericGUI.class.php
* Contains the mother class for all GUIs.
* Holds the elements engine and the basic editmode look
* @see CCMSGenericGUI
*/

/**
* The BaseGUI for every CCMS Object
* This class is especially important for a CCMS Object in edit mode.
* It provides all objects with a common look (the frameset, the edit header, the line...)<BR>
* It also holds the elements engine for all CCMS Objects.<BR>
* More details on the use as an engine:
* @see CCMSGenericGUI::write_engine()
*/
abstract class CCMSGenericGUI extends CCMSGeneric
{

  /**
	* The "main write" function.
	* Writes the GUI. <BR> Uses all settings made via constructors.
	* @returns void
	*/
  abstract public function write_me();

	/**
	* editmode setting.
	* sets whether the object is in editmode or not
	*/
	protected $editmode;
		

	/**
	* Magic function for serialize().
	* Returns all variables to be saved when serializing.
	*/
	public function __sleep()
	{
		return array_merge ( parent::__sleep(), array('editmode'));
	}

	/**
	* The CCMSGenericGUI constructor.
	* Sets up the CCMS Object Name
	* @param $filename filename of the CCMSObject definition file
	* @param $editmode wheter the object is in editable or not
	* @returns void
	* @see CCMSGenericGUI::$objectname
	*/
	function __construct($filename, $editmode)
	{	
		$this->editmode = $editmode;
		$valstor = new CCMSElementStorage($filename);
		
		parent::__construct(serialize($valstor));
	}

	


	/**
	* Starts the editmode header and fieldset.
	* @returns void
	* @see CCMSGenericGUI::stop_label_head(), CCMSGenericGUI::end_label()
	*/

	protected function start_label()
	{
		?>
		
		<fieldset>
			<div id="ccms_<?php echo $this->objectname;?>_editmode_header" class="ccms_editmode_header">
		<?php		
	}

	/**
	* Ends the editmode header.
	* @returns void
	* @see CCMSGenericGUI::start_label(), CCMSGenericGUI::end_label()
	*/

	protected function stop_label_head()
	{
		?></div>
		<hr />
		<?php
	}	

	/**
	* Ends the editmode fieldset.
	* @returns void
	* @see CCMSGenericGUI::start_label(), CCMSGenericGUI::stop_label_head()
	*/
	protected function end_label()
	{
		?></fieldset><?php
	}


	/**
	* Writes tags for Javascript or Iframe or both.
	* This will be called for pretty much every CCMS Object
	* @param $includejsfile the JS file to include (in CCMS_JSPATH)
	* @param $iframefile the Iframe file to use (in CCMS_IFRAMEPATH)
	* @returns void
	* @todo make every GUI use this!
	*/

	protected function includes($includejsfile=0, $iframefile=0)
	{
		if(!empty($includejsfile)){
		?><script src="<?php echo CCMS_JSPATH.$includejsfile;?>.js.php?name=<?php echo $this->objectname;?>" type="text/javascript"></script><?php
		}

		if(!empty($iframefile)){
		?><!--[if IE]>
			<div><iframe name="ccms_iframe_<?php echo $this->objectname;?>" id="ccms_iframe_<?php echo $this->objectname;?>" class="ccms_hiddeniframe" src="<?php echo CCMS_IFRAMEPATH.$iframefile."?name=".$this->objectname;?>"></iframe></div>
		<![endif]-->
		<!--[if !IE]> <-->
			<div>
			<object name="ccms_iframe_<?php echo $this->objectname;?>" id="ccms_iframe_<?php echo $this->objectname;?>" class="ccms_hiddeniframe" type="text/html" data="<?php echo CCMS_IFRAMEPATH.$iframefile."?name=".$this->objectname;?>"></object></div>
		<!--> <![endif]-->
		<?php
		}
	}

	/**
	* The GUI writeengine extension.
	* Sets the right nameprefix and sends it to CCMSGeneric::write_engine()
	* @param $valnr the set of values to be written in the elements
	* @param $mode show or editmode
	* @param $pre prefix to the name (useable for tables)
	* @param $alternativeids if not set all elements will be written, otherwise only this array
	* @returns the html code for the set of elements 
	* @see CCMSGeneric::write_engine()
	*/
	protected function write_gui($valnr, $mode, $pre="", $alternativeids=0)
	{
		$namepre = 'ccms_'.$this->objectname.'_t'.$pre;
		return parent::write_engine($valnr, $mode, $namepre, $alternativeids);
	}

}

/*! @} */

?>

