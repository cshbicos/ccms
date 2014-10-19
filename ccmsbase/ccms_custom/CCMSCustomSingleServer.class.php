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

/** @addtogroup CCMSCustomExamples
* @{
*/


/** 
* @file CCMSCustomSingleServer.class.php
* The corresponding serverobject to CCMSCustomSingleGUI
* @see CCMSGenericGUI, CCMSGenericServer, CCMSSingleServer, CCMSSingleGUI
*/

/** 
* An example class on how the a CCMSSingleServer class works
*/

class CCMSCustomSingleServer extends CCMSSingleServer
{
	/**
	* The update function.
	* All elements with name will be listed here and the new values are set in an array like <BR>
	* $vars['ccms_t'.$elemennr]
	* @param &$vars a reference to the $_POST array
	* @returns void
	* @see CCMSElements, CCMSSingleServer
	*/
	public function update_me(&$vars)
	{
		/**
		* with $this->id the variable one can hand over in CCMSCustomSingleGUI::set_me_up() <BR>
		* as an id in CCMSSingleGUI::set_serverobject() can be used
		*/
		//$this->id
		 
		$vars['ccms_t4'] = addslash_check($vars['ccms_t4'])." geaendert";
		$vars['ccms_t5'] = addslash_check($vars['ccms_t5'])." geaendert";
		
		
	}
}
/*! @} */
