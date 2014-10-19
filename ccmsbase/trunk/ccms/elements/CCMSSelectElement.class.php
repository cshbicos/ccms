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
* @file CCMSSelectElement.class.php
* A selectbox element.
* @see CCMSElements
*/


/**
* Creates a selectbox element.
* A normal html select box.
*/

class CCMSSelectElement extends CCMSBaseElement
{
	/**
	* XML settigns storage.
	* $settings['editclass'] = Selectbox CCS Style in editmode.<br>
	* $settings['showclass'] = Selectbox CCS Style in showmode.<br>   
	*/            
	private $settings = array();
 
	/**
	* All possible values in the selectbox
	*/
	private $vals = array();


	/**
	* @see CCMSBaseElement::load_data()
	*/
	public function load_data($tag, $value, $attribute)
	{
		if($tag == "option"){
			$this->vals[$attribute['ID']] = $value;return;}
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
		$editbox = "<select name=\"".$name."\"";
		if(!empty($this->settings['editclass']))
			$editbox .= " class=\"".$this->settings['editclass']."\"";
		$editbox .= ">\n";
		
		foreach ($this->vals as $key => $curval) {		
			$editbox .= "<option value=\"".$key."\"";
			
			if($key == $val)
				$editbox .= " selected=\"selected\"";
					
			$editbox .= ">".$curval."</option>\n";
		}
		$editbox .= "</select>\n";
		return $editbox;
	}

	/**
	* @see CCMSBaseElement::showmode()
	*/ 
	public function showmode($name, $val)
	{
		foreach ($this->vals as $key => $curval)
			if($key == $val){
				$return= $curval;break;
			}
		if(empty($return))
			$return = current($this->vals);
		
		if(!empty($this->settings['showclass']))
			return "<span class=\"".$this->settings['showclass']."\">".$return."</span>";
		else
			return $return;			
	}

}

/*! @} */

?>
