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
* @file CCMSCustomTableGUI.class.php
* An example how to load different Elements in the CCMSTableGUI
* @see CCMSGenericGUI, CCMSGenericServer, CCMSTableGUI, CCMSTableServer
*/

/** 
* An example class on how the a CCMSTableGUI class works
*/

class CCMSCustomTableGUI extends CCMSTableGUI
{

	/**
	* Sets the values to for the elements.
	* This could be as well a database connection
	* @returns void (but can be cosumized if needed)
	*/
	public function set_me_up()
	{
		
		$array[0] = array("11.10.3004", 'testgelaber');
		$array[1] = array("12.10.3004", 'testgelaber2');
		$array[2] = array("13.10.3004", 'testgelaber3');

		for($i=0;$i<count($array);$i++){

			$this->add_value('datepicker', $array[$i][0],  'D_'.$i);
			$this->add_value('textbox', $array[$i][1],  'D_'.$i);	
			$this->add_dyntag("test", "txt", "wtf".$i, false, 'D_'.$i);
		}
	
		$this->set_serverobject("CCMSCustomTableServer", true);	
	}
}

/*! @} */


?>
