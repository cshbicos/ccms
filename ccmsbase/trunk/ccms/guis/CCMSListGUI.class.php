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
* @addtogroup GUIS 
* @{
*/

/** 
* @file CCMSListGUI.class.php
* The GUI to all List CCMS Objects
*/

/**
* The generic list gui class.
*/
class CCMSListGUI extends CCMSGenericGUI
{
	/**
	* Liststorage.
	* @see See CCMSListStorage for further information
	*/
	protected $liststorage;

	/**
	* Rowid storage.
	* @see See CCMSRowIdStorage for further information
	*/
	protected $rowstorage;

	
	/**
	* Magic function for serialize().
	* Returns all variables to be saved when serializing.
	*/
	public function __sleep()
	{
		return array_merge ( parent::__sleep(), array('liststorage', 'rowstorage'));
	}

	/**
	* Constructor.
	* @param $filename CCMS Object Definition Filename
	* @param $editmode List in editmode or not
	* @returns void
	*/
	public function __construct($filename, $editmode) 
	{	
		parent::__construct($filename, $editmode);
		$this->liststorage = new CCMSListStorage($filename);
		$this->rowstorage = new CCMSRowIDStorage();
	}

	/**
	* Adds a value to an element. 
	* Basically rerouting to the CCMSElementValueStorage element but first we need to find the appropriate valuenr in CCMSListStorage
	* @param $elementid the elementid of the element for which the value is set
	* @param $val the value itself
	* @param $id the database id of the value
	* @param $subto where it belongs
	* @returns void
	* @see CCMSElementValueStorage, CCMSRowIdStorage
	*/
	protected function add_val($elementid, $val, $id, $subto="!_!")
	{
		if(($tmp = $this->rowstorage->get_tblid($id)) === false){
			$tmp = $this->liststorage->add_id($id, $subto);	
			$this->rowstorage->add_rowid($id, $tmp);
		}
		$this->elements->add_value($elementid, $val, $tmp);
		
	}

	/**
	* Adds a dyntag to one of the CCMS Object's elements.
	* @param $elementid the elementid of the element as specified in the xml file
	* @param $tag the tag to be changed
	* @param $val the value for the element
	* @param $attributes attributes to the dyntag
	* @param $id the database id of the value
	* @param $subto where it belongs
	* @returns void
	* @see CCMSElementValueStorage
	*/
	protected function add_dyntag($elementid, $tag, $val, $attributes, $id, $subto="!_!")
	{
		if(($tmp = $this->rowstorage->get_tblid($id)) === false){
			$tmp = $this->liststorage->add_id($id, $subto);
			$this->rowstorage->add_rowid($id, $tmp);
		}
		
		$this->elements->add_dyntag($elementid, $tag, $val, $attributes, $tmp);
	}




	/**
	* The "main list" function.
	* Writes the list in gui mode. <BR> Uses all settings made via constructors.
	* @returns void
	* @see CCMSElementValueStorage, CCMSListStorage
	*/

	public function write_me()
	{
		if($this->editmode == 1){
			$this->includes('list', 'list.php');
			$this->start_label();	
			echo "</div>";
		}

		$this->write_lvl(0, 1, "base");

		if($this->editmode == 1)
			$this->end_label();		
	}

	/**
	* Write a certain lvl starting tags (ol, ul).
	* In case the level is not 0, write the previous ending tag...
	* @param $lvl the indent level to use
	* @param $val the current number of some sorts
	* @param $addval the ul/li tag name to get the "add" button working correctly
	* @returns void
	*/

	private function write_lvl($lvl, $val, $addval)
	{
		list($tag, $attri, $alllvls) = $this->liststorage->get_settings($lvl, 0);
	
		echo "<".$tag . " id=\"ccms_".$this->objectname."_l_".$addval."\" ".$attri.">\n";
			$el_tmp = $this->liststorage->get_entry($val);
			while($lvl == $el_tmp[2] && $el_tmp !== false){
				$this->write_li($el_tmp[1], $el_tmp[2]);
				$val++;
				if($lvl < ($alllvls-1))
					$val = $this->write_lvl(($lvl + 1), $val, $el_tmp[1]);
				echo "</li>\n";

				
				$el_tmp = $this->liststorage->get_entry($val);
				
			}
			if($this->editmode == 1){
				echo "<li><a href=\"javascript:add_li_".$this->objectname."('".$addval."', '".$lvl."')\" class=\"ccms_headeditlink\">Add new</a></li>\n";
			}

		echo "</".strtolower($tag).">\n";
		return $val;
	}
		

	private function write_li($valnr, $lvl)
	{


		echo "<li ".$this->liststorage->get_settings($lvl, 1). ">\n";
		

		$allelements = $this->liststorage->get_elements($lvl);
		//in case there's no element
		if(count($allelements) == 0)
			continue;
	 
		$namepre = "_".$valnr."_";
		//write the visible div (do so always)
		$this->generic_container($this->write_gui($valnr, true, $namepre , $allelements), $this->objectname."_".$valnr, true);
		
		if($this->editmode == 1){
			$this->generic_container($this->write_gui($valnr, false, $namepre, $allelements), $this->objectname."_".$valnr, false);
					
		
			echo "&nbsp;<span id=\"ccms_".$this->objectname."_".$valnr."_fieldset\">";
			echo "<a href=\"javascript:switch_boxes_".$this->objectname."('".$valnr."', '".$this->liststorage->get_elementcountstart($lvl)."')\" class=\"ccms_headeditlink\">edit</a>&nbsp;";
			echo "<a href=\"javascript:del_li_".$this->objectname."('".$valnr."')\" class=\"ccms_headeditlink\">delete</a></span>";
		}
	}







	/**
	* Creates the serverside object.
	* @param $obj the classname
	* @returns void
	*/
	protected function set_serverobject($obj)
	{
		if($this->editmode == 1){	
			$serverobject = new $obj(serialize($this->elements), serialize($this->liststorage) , serialize($this->rowstorage));
      			$_SESSION['ccms_s_'.$this->objectname] = serialize($serverobject);
		}
	}


}

/*! @} */

?>

