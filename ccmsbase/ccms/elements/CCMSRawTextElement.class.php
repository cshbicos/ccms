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
* @file CCMSRawTextElement.class.php
* The raw text element.
* @see CCMSElements, CCMSRawTextElement
*/


/**
* Creates the raw text element.
* This element is no "special" element but rather a way to add constant text/formating to the CCMS Object.<BR>
* It does not modify its value in any way and will be displayed the same way throughout the livetime of the CCMS Object.<BR>
* In other words: It will soley print out any given text/tag/whatever without considering it a "real" element
*/

class CCMSRawTextElement extends CCMSBaseElement
{
	/**
	* XML settigns storage.
	* $settings['txt'] = The current text.<br>
	* $settings['style'] = CCS Style class.<br>  
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
	public function associated_values(){return 0;}

	/**
	* @see CCMSBaseElement::editmode()
	*/
	public function editmode($name, $val)
	{
		if(!empty($this->settings['style']))
			return "<span class=\"".$this->settings['style']."\">".$this->settings['txt']."</span>";
		else
			return $this->settings['txt'];
	}
	
	/**
	* @see CCMSBaseElement::showmode()
	*/
	public function showmode($name, $val)
	{
		if(!empty($this->settings['style']))
			return "<span class=\"".$this->settings['style']."\">".$this->settings['txt']."</span>";
		else
			return $this->settings['txt'];
	}

}

/*! @} */

?>
