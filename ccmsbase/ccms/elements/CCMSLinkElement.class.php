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
* @file CCMSLinkElement.class.php
* The link element.
* @see CCMSElements
*/


/**
* Creates a link element.
* In showmode the a text linked to a url is displayed as a link or alternatively a email address with a mailto: link.
*/

class CCMSLinkElement extends CCMSBaseElement
{
	/**
	* XML settigns storage.
	* $settings['editclass'] = Textarea CCS Style in editmode.<br>
	* $settings['showclass'] = Textarea CCS Style in showmode.<br> 
	* $settings['mode'] = Mailto:(1) or normal link mode(0)      
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
	public function associated_values(){return 2;}
	
	/**
	* @see CCMSBaseElement::showmode()
	*/
	public function showmode($name, $val)
	{
		if($this->settings['mode'] == 1){
			$answer = "<a href=\"mailto:".$val[0]."\"";
		}else{
			$answer = "<a href=\"".$val[0]."\" target=\"_blank\"";
		}
		

		if(!empty($this->settings['showclass']))
			$answer .= " class=\"".$this->settings['showclass']."\"";
		$answer .= ">".$val[1]."</a>";
		return $answer;
		
	}

	/**
	* @see CCMSBaseElement::editmode()
	*/
	public function editmode($name, $val)
	{
		if($this->settings['mode'] == 1)
			$editbox = "Email :";
		else	
			$editbox = "Link :";

		$editbox .= "<input type=\"text\" name=\"".$name[0]."\"";
		if(!empty($this->settings['editclass']))
			$editbox .= " class=\"".$this->settings['editclass']."\"";
		$editbox .= " value=\"".htmlspecialchars($val[0])."\" />";
		$editbox .= " Text : <input type=\"text\" name=\"".$name[1]."\"";
		if(!empty($this->settings['editclass']))
			$editbox .= " class=\"".$this->settings['editclass']."\"";
		$editbox .= " value=\"".htmlspecialchars($val[1])."\" />";

		return $editbox;
	}	
}

/*! @} */

?>
