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

/** @defgroup CCMSCustomExamples Examples of how to use CCMS Objects
* @{
*/


/** 
* @file CCMSCustomSingleGUI.class.php
* An example how to load different Elements in the CCMSSingleGUI
* @see CCMSGenericGUI, CCMSGenericServer, CCMSSingleGUI
*/

/** 
* An example class on how to load different Elements in the CCMSSingleGUI
*/
class CCMSCustomSingleGUI extends CCMSSingleGUI
{
	/**
	* The setup method. 
	* This needs to be called before CCMSSingleGUI::write_me()<BR>
	* If the data is obtained via mysql or whatever, this is a good way to have the values returned.
	* @returns everything you want
	*/
	function set_me_up()
	{
		
		$array = Array( "24.09.1986", "Hidden Value", "http://mrman.com", "1", "TextArea Element", "TextBox Element");
				
		



		$this->add_value("datepicker", $array[0]);
		$this->add_value("hidden", $array[1]);
		$this->add_value("link", $array[2]);
		$this->add_value("select", $array[3]);
		$this->add_value("area", $array[4]);
		$this->add_value("textbox", $array[5]);
		$this->add_dyntag("test", "txt", "mal was anderes", false);
		

		$this->set_serverobject("CCMSCustomSingleServer", 'CCMSSingleElements.xml');
		return $array;
	}
}
/*! @} */


?>
