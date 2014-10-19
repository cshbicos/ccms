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
* @file CCMSGenericServer.class.php
* Contains the basic server class for all CCMS Objects handeling text in some way
*/

/**
* The engine for CCMS text objects
* The Base Serverclass for all CCMS Objects containing text of some kind.<BR>
* This object contains the serverside engine to create a stripped down copy for the iframe to sync with the GUI. <BR>
* More details on the use as an engine:
* @see CCMSGenericTextServer::showelements($show, &$vals, &$elementarray)
* 
*/
class CCMSGenericServer extends CCMSGeneric
{

	/**
	* Magic function for serialize().
	* Returns all variables to be saved when serializing.
	*/
	public function __sleep()
	{
		return parent::__sleep();
	}



	/**
	* The contructor.
	* The contructor for the generic server. It sets the name for the CCMS Objects and loads the elements from the defintion file.
	* @param $valstor a serialized CCMSElementValueStorage Object, the same as created for the GUI
	* @returns void
	*/
	function __construct($valstor)
	{
		parent::__construct($valstor);
	}

	/**
	* The Server writeengine extension.
	* Sets the right nameprefix and sends it to CCMSGeneric::write_engine().
	* Also adds the current $_POST value set to the 0 value row in CCMSElementValueStorage
	* @param &$vals the values in $_POST
	* @param $mode show or editmode
	* @param $rownr the rownr to be shown (used for dyntags)
	* @param $alternativeids if not set all elements will be written, otherwise only this array
	* @returns the html code for the set of elements 
	* @see CCMSGeneric::write_engine()
	*/
	protected function write_server(&$vals, $mode, $rownr, $alternativeids=0)
	{
		$namepre = 'ccms_t';
		if(get_magic_quotes_gpc() == 1)
			$val = array_map('stripslashes', $vals);	

		if(count($vals) > 1)
			$this->elements->add_servervalues($val, $rownr);
		return parent::write_engine($rownr, $mode, $namepre, $alternativeids);
	}


}

/*! @} */

?>
