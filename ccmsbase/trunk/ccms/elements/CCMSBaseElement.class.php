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


/** @defgroup CCMSElements Elements for CCMS Objects
*  The CCMS Object's elements
* @{
*/

/** 
* @file CCMSBaseElement.class.php
* The abstract base class for all elements.
* All methods defined in here need to implemented in a element
* @see CCMSElements
*/


/**
* The common element base class.
* This class can't be instatiated, but should be used as a base class for every element class.
*/

abstract class CCMSBaseElement
{
  	/**
	* XML data loader.
	* Places the xml tags in the right variables.
	* @param $tag the tag
	* @param $value the value
	* @param $attribute attributes to the tag
	* @returns void
	*/     
	abstract public function load_data($tag, $value, $attribute);
	
	/**
	* How many values to give to the element?
	* On 0, it is considered no "real" element and won't be listed in numbering of elements
	* @returns the number of values associated with the element  	
	*/
	abstract public function associated_values();
	
	/**
	* Creates the element in editmode.
	* @param $name the name of the element	
	* @param $val the value(s -> in an array) of the element
	* @returns the html code for the element
	*/	
	abstract public function editmode($name, $val);
	
	/**
	* Creates the element in showmode.
	* @param $name the name of the element	
	* @param $val the value(s -> in an array) of the element
	* @returns the html code for the element
	*/	
	abstract public function showmode($name, $val);

}

/*! @} */

?>
