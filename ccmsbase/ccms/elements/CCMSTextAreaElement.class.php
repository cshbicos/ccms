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
* @file CCMSTextAreaElement.class.php
* The textarea element.
* @see CCMSElements
*/


/**
* Creates the textarea element.
* The typical textarea element which is probably one of the most important elements.
*/

class CCMSTextAreaElement extends CCMSBaseElement
{
	/**
	* XML settigns storage.
	* $settings['editclass'] = Textarea CCS Style in editmode.<br>
	* $settings['showclass'] = Textarea CCS Style in showmode.<br>  
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
		$editbox = "<textarea name=\"".$name."\"";
		if(!empty($this->settings['editclass']))
			$editbox .= " class=\"".$this->settings['editclass']."\"";
		$editbox .= " rows=\"5\" cols=\"30\">".htmlspecialchars($val)."</textarea>\n";
			
		return $editbox;
	}

	/**
	* @see CCMSBaseElement::showmode()
	*/ 
	public function showmode($name, $val)
	{
		if(!empty($this->settings['showclass']))
			return "<span class=\"".$this->settings['showclass']."\">".nl2br($val)."</span>";
		else
			return nl2br($val);
	}

}

/*! @} */

?>
