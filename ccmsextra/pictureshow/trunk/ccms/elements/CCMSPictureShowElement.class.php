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
* The Pictureshow element. Used to get the parsed values out of ElementValueStorage
*/

class CCMSPictureShowElement extends CCMSBaseElement
{
	/**
	* the real path to the picture (for upload)
	*/
	private $path;
	
	/**
	* the webpath to the pic
	*/
	private $webpath;

	/**
	* the width of the picture (0 for no limit)
	*/
	private $width;
	
	/**
	* the height of the picture (0 for no limit)
	*/
	private $height;

	/**
	*the mime type of the pic (empty for none)
	*/
	private $type;

	/**
	* the maximum size (0 for no limit)
	*/
	private $size;

	/**
	* Max width of the preview thumbs
	*/ 
	private $prewidth;
	
	/**
	* Cols for previwe pics
	*/
	private $previewcols;
	
	/**
	* A stylesheet for the table itself
	*/
	private $tablestyle;

	/**
	* A style for pictures
	*/
	private $picstyle;

	/**
	* XML data loader.
	* Places the xml tags in the right variables.
	* @param $tag the tag
	* @param $value the value
	* @param $attribute attributes to the tag
	* @returns void
	*/
	public function load_data($tag, $value, $attribute)
	{
		if($tag == "preview_width"){
			$this->prewidth = $value;return;}
		if($tag == "preview_cols"){
			$this->previewcols = $value;return;}
		if($tag == "path"){
			$this->path = $value;return;}
		if($tag == "webpath"){
			$this->webpath = $value;return;}
		if($tag == "maxwidth"){
			$this->width = $value;return;}
		if($tag == "maxheight"){
			$this->height = $value;return;}
		if($tag == "type"){
			$this->type = $value;return;}
		if($tag == "size"){
			$this->size = $value;return;}
		if($tag == "tablestyle"){
			$this->tablestyle = $value;return;}
		if($tag == "picstyle"){
			$this->picstyle = $value;return;}

	}
	
	/**
	* @see CCMSBaseElement::associated_values()
	*/
	public function associated_values(){return 0;}
	
	/**
	* Little trick to have the element return it's settings, coz the PictureShow element needs them...
	* @param $name the element name
	* @param $val the current date value
	* @returns the html code for the element
	*/	
	public function editmode($name, $val)
	{
		return array ($this->path, $this->webpath, $this->width, $this->height, $this->type, $this->size, $this->prewidth, $this->previewcols, $this->tablestyle, $this->picstyle);
	}

	/**
	* Little trick to have the element return it's settings, coz the PictureShow element needs them...
	* @param $name the name of the element
	* @param $val the current date value
	* @returns the html code for the element
	*/
	public function showmode($name, $val)
	{
		return array ($this->path, $this->webpath, $this->width, $this->height, $this->type, $this->size, $this->prewidth, $this->previewcols, $this->tablestyle, $this->picstyle);
	}
}

/*! @} */

?>
