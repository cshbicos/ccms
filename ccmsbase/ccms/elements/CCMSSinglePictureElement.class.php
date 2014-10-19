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
* @file CCMSSinglePictureElement.class.php
* A single picture element.
* @see CCMSElements
*/


/**
* The single picture element.
* a normal picture element, with many options:).<BR> Note: The picture element does not 
* count as a "named" element, since it is not changed via iframe (upload in iframe restriction)
*/

class CCMSSinglePictureElement extends CCMSBaseElement
{
	/**
	* XML settigns storage.
	* $settings['picname']    = The picture filename<br>
	* $settings['objectname'] = The name of the CCMS Object <br>
	* $settings['style']      = CSS Style for the picture<br>
	* $settings['path']       = The real path to the picture (for upload)<br>
	* $settings['webpath']    = The webpath to the pic<br>
	* $settings['alt']        = Alternative description<br>
	* $settings['width']      = The width of the picture (0 for no limit)<br>
	* $settings['height']     = The height of the picture (0 for no limit)<br>
	* $settings['type']       = The mime type of the pic (empty for none)   <br>  
	* $settings['size']       = The maximum size (0 for no limit) <br>  
	*/
	private $settings = array("type"=>"");
	


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
	* adds a picture element in showmode.
	* @param $name the name of the element	
	* @param $val the value of the element
	* @returns the html code for the pic in showmode
	*/
	public function showmode($name, $val)
	{
		if(!file_exists($this->settings['path'].$this->settings['picname']) && $_SESSION['ccms_admin'] != 1)
			return "";

		$imagecode="";
	
		$imagecode = "<img src=\"".$this->settings['webpath'].$this->settings['picname']."?".rand(0, 1000000)."\"";
		$imagecode .= " id=\"".$name."_pic\"";

		if($this->settings['width'] > 0)
			$imagecode .= " width=\"".$this->settings['width']."px\"";
		if($this->settings['height'] > 0)
			$imagecode .= " height=\"".$this->settings['height']."px\"";

		if(!empty($this->settings['style']))
			$imagecode .= " class=\"".$this->settings['style']."\"";

		if(!empty($this->settings['alt']))
			$imagecode .= " alt=\"".$this->settings['alt']."\"";
		
		$imagecode .= " />";
		return $imagecode;
	}


	/**
	* @see CCMSBaseElement::editmode()
	*/
	public function editmode($name, $val)
	{
		$myid = CCMSFileElementsRegister::register_element($name, "CCMSSinglePictureFile", $this->settings['path'],$this->settings['webpath'], $this->settings['type'], $this->settings['size']);
		CCMSFileElementsRegister::extend_register($myid, Array("width" => $this->settings['width'], "height" => $this->settings['height'], "picname"=> $this->settings['picname']));
		$imagecode = $this->showmode($name."_edit", $val);
		$imagecode .= "<br /><a class=\"ccms_headeditlink\" href=\"";
		$imagecode .= "javascript:ccms_open_uplwindow(320, 90, 'singlepic', ".$myid.")\">";
		$imagecode .= "Neues Bild Uploaden</a>";
		

		return $imagecode;
	}


}
/*! @} */


?>
