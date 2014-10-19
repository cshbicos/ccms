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

/** @addtogroup Filesupport
* @{
*/

/**
* @file CCMSFileElementsRegister.class.php
* Holds the fileelement register functions.
* @see CCMSFileElementsRegister
*/


/**
* The Fileelement register functions.
* Every fileelement needs to be registered in $_SESSION['ccms_fileregister'] to be able to use <BR>
* the fileelement helping classes (CCMSFileBase etc.).<BR>
* This object can be used to register a new element in  $_SESSION['ccms_fileregister'].
*/
class CCMSFileElementsRegister
{
  public static $lastid = false;
  
	/**	
	* check if $_SESSION['ccms_fileregister'] exists already.
	* @param $hash hash of the filebase settings
	* @return the key if the element exists, -1 if not
	*/
	public static function object_exists($hash)
	{
		for($i=0;$i<count($_SESSION['ccms_fileregister']);$i++){
			if($_SESSION['ccms_fileregister'][$i][1] == $hash)
					return $i;
		}
		return -1;
	}

	/**	
	* registers the new element.
	* @param $elementname the name of the element
	* @param $elementtype the type of the element
	* @param $path the path to the file
	* @param $webpath the webpath to the file
	* @param $type type restriction of the file to be uploaded
	* @param $size size restriction on the file to be uploaded
	* @return the created elementid
	*/
	public static function register_element($elementname,$elementtype,$path,$webpath, $type, $size)
	{
		
		if(!isset($_SESSION['ccms_fileregister']))
			$_SESSION['ccms_fileregister'] = array();
		
		$hash = md5($elementname.$path.$webpath.$type.$size);
		$id = CCMSFileElementsRegister::object_exists($hash);
		
		if($id > -1 ) {
		  CCMSFileElementsRegister::$lastid = $id;
			return $id;
		}else{
			$fileobj = new $elementtype($elementname, $path, $webpath, $type, $size);
			$newelementid = array_push($_SESSION['ccms_fileregister'], array(serialize($fileobj), $hash));
			CCMSFileElementsRegister::$lastid = ($newelementid-1);
			return ($newelementid-1);
 		}
	}

	/**
	* Add some additional information to the register.
	* May be used for pictures to set width and height, or other misc stuff.
	* @param $elementid the id of the element in the register
	* @param $extendarray an array containing all information to be added
	* @returns void
	*/
	public static function extend_register($elementid, $extendarray)
	{
		$fileobj = unserialize($_SESSION['ccms_fileregister'][$elementid][0]);
		$fileobj->add_extended($extendarray);
		$_SESSION['ccms_fileregister'][$elementid][0] = serialize($fileobj);
		//print_r($_SESSION['ccms_fileregister']);
	}



}

/*! @} */
?>
