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
* @addtogroup CCMSElements 
* @{
*/

/** 
* @file CCMSDatePickerElement.class.php
* The datepicker element.
* @see CCMSElements
*/


/**
* The datepicker element.
* Creates the little datepicker box. This element is uses datepicker.php in the popup folder<BR>
* It uses the generic js include, and a picture in the img/ folder.
*/

class CCMSDatePickerElement extends CCMSBaseElement
{
	/**
	* XML settigns storage.
	* $settings['editclass'] = Datebox CCS Style in editmode.<br>
	* $settings['showclass'] = Datebox CCS Style in showmode.<br> 
	* $settings['objectname'] = CCMS Object name (not the elementname)<br>
	*/
	private $settings = array();
	
	/**
	* @see CCMSBaseElement::load_data()
	*/
	public function load_data($tag, $value, $attribute)
	{
	  $this->settings[$tag] = $value;
	}

	/**
	* @see CCMSBaseElement::associated_values()
	*/
	public function associated_values(){return 1;}

	/**
	* @see CCMSBaseElement::editmode()
	*/	
	public function editmode($name, $val)
	{
		$editbox = "<input type=\"text\" name=\"".$name."\"";
		if(!empty($this->settings['editclass']))
			$editbox .= " class=\"".$this->settings['editclass']."\"";
		$editbox .= " value=\"".$val."\" style=\"width:80px\" readonly=\"readonly\" />\n";
		$editbox .= "<a href=\"javascript:ccms_datepicker('".$name."')\">\n";
		$editbox .= "<img style=\"border: none;\" src=\"".CCMS_IMGPATH."calendar.png\" alt=\"Change date\"/></a>\n";
		return $editbox;
	}

	/**
	* @see CCMSBaseElement::showmode()
	*/
	public function showmode($name, $val)
	{
		if(!empty($this->settings['showclass']))
			return "<span class=\"".$this->settings['showclass']."\">".$val."</span>";
		else
			return $val;
	}
}
/*! @} */


?>
