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
* @file CCMSValueStorage.class.php
* Stores all elements' values and dyntags.
*/

/**
* Elements' values storage class.
* Every GUI needs to save the values and dyntags before they are outputed into the different elements. This happens here.
*/

class CCMSValueStorage
{
	/**
	* The values for the elements.
	* Saved in an array like this:<BR>
	* $vals[ valuenr][ elementid ] = "value"
	* @see CCMSValueStorage::add_value()
	*/
	private $vals = array();

	/**
	* Dynamic settings for tags storage.
	* Used to create tag values "on the fly", for example texts for a CCMSRawTextElement out of a database<BR>
	* or a picturename for a CCMSSinglePictureElement.<BR>
	* Structurewise:<BR>
	* $dyntags[ valuenr ][ elementid ] [ number ]  = array ( "tag", "value", "attributes");
	* @see CCMSValueStorage::add_dyntag()
	*/
	private $dyntags = array();

	/**
	* Magic function for serialize().
	* Returns all variables to be saved when serializing.
	*/
	public function __sleep()
	{
		return array('dyntags');
	}

	/**
	* Adds a value to a given element.
	* check  CCMSValueStorage::$vals for description of the value array
	* @param $elementid valid element id
	* @param $value a value for the element
	* @param $valnr use a number here to add the value to a specific row
	* @returns void
	*/
	public function add_value($valnr, $elementid, $value)
	{		
		$this->vals[$valnr][$elementid] = $value;
	}

	/**
	* Adds a dyntag to a given element.
	* check  CCMSValueStorage::$dyntags for description of the dyntags array
	* @param $elementid valid element id
	* @param $tag the dyntag to be set
	* @param $attributes attributes to the dyntag
	* @param $value the value for the tag
	* @param $valnr use a number here to add the value to a specific row
	* @returns void
	*/
	public function add_dyntag($valnr, $elementid, $tag, $value, $attributes)
	{		
		if(!isset($this->dyntags[$valnr][$elementid]))
			$this->dyntags[$valnr][$elementid] = array();
		array_push($this->dyntags[$valnr][$elementid], array($tag, $value, $attributes));
	}

	/**
	* Retrieves a dyntag for a certain valnr/elementid combination.
	* @param $valnr the specific row the fetch the dyntag from
	* @param $elementid the elementid to fetch the dyntag for
	* @returns an array of ($tag, $value, $attributes), false on failure
	*/
	public function get_dyntag($valnr, $elementid)
	{
		if(!isset($this->dyntags[$valnr][$elementid]))
			return false;
		else
			return $this->dyntags[$valnr][$elementid];
	}

	/**
	* Retrieves a value for a certain valnr/elementid combination.
	* @param $valnr the specific row the fetch the value from
	* @param $elementid the elementid to fetch the valure for
	* @returns the value of the valnr/elementid combination, false on failure
	*/
	public function get_vals($valnr, $elementid)
	{
		if(!isset($this->vals[$valnr][$elementid]))
			return false;
		else
			return $this->vals[$valnr][$elementid];
	}

	/**
	* Returns how many values were stored.
	* @returns the max values stored
	*/
	public function get_valuecount()
	{
		return count($this->vals);
	}


}

/*! @} */
?>