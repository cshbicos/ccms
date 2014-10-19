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
* @file CCMSRowIDStorage.class.php
* Used for Database ID <-> Rowid matching
*/

class CCMSRowIDStorage
{
	/**
	* Database ID <-> Rowid matching
	*/
	private $rowid=array();

	/**
	* Magic function for serialize().
	* Returns all variables to be saved when serializing.
	*/
	public function __sleep()
	{
		return array('rowid');
	}

	/**
	* Adds a databaseid rowid match.
	* @param $row the "rownumber" of the id
	* @param $id the corresponding databaseid
	* @returns the rowid of the created match
	*/
	public function add_rowid($id, $row=false)
	{	
		if($row === false)
			return (array_push($this->rowid, $id)-1);
		$this->rowid[$row] = $id;
		return $row;
	}

	/**
	* Get corresponding databaseid for a rowid.
	* @param $rownr the rowid
	* @returns the databaseid
	*/
	public function get_dbid($rownr)
	{
		return $this->rowid[$rownr];
	}

	/**
	* Get corresponding rowid for a databaseid.
	* @param $dbid the rowid
	* @returns the rowid
	*/
	public function get_tblid($dbid)
	{
		
		$end = array_search($dbid, $this->rowid);
		return $end;
	}

	/**
	* Check wheter or not a certain dbid has a rowid already.
	* @param $dbid dbid to check
`	* @returns true or false
	*/
	public function check_dbid($dbid)
	{
		if(array_search($dbid, $this->rowid) !== false)
			return true;
		else
			return false;
	}

	/**
	* Deletes a rowid
	* @param $rowid rowid to delete
	* @returns void
	*/
	public function del_rowid($rowid)
	{
		unset($this->rowid[$rowid]);
	}

}

/*! @} */

?>
