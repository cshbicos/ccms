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
* @file CCMSCustomListGUI.class.php
* An example how to load different Elements in the CCMSListGUI
* @see CCMSGenericGUI, CCMSGenericServer, CCMSListGUI, CCMSListServer
*/

/** 
* An example class on how the a CCMSListGUI class works
*/

class CCMSCustomListGUI extends CCMSListGUI
{
	
	/**
	* Sets the values to for the elements.
	* This could be as well a database connection
	* @returns void (but can be cosumized if needed)
	*/
	public function set_me_up()
	{
		
		$idcounter = 0;
	
		for($i=0;$i<4;$i++){
			$this->add_val("textbox1",$i,  $idcounter);
			$tmp = $idcounter;
			
			$idcounter++;
			
			for($j = 0;$j<3;$j++){
				$this->add_val("textbox2", $j, $idcounter, $tmp);
				$this->add_dyntag("test", "txt", $idcounter, false,$idcounter, $tmp);
				$idcounter++;
			}
		
			
		}
	
		$this->set_serverobject("CCMSCustomListServer");
	}
}

/*! @} */


?> 
