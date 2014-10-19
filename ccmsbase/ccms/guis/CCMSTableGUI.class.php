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
* @file CCMSTableGUI.class.php
* The GUI to all Table CCMS Objects
*/

/**
* The generic table gui class.

*/
class CCMSTableGUI extends CCMSGenericGUI
{
	/**
	* Tablestorage.
	* @see See CCMSTableStorage for further information
	*/
	protected $tablestorage;

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
		return array_merge ( parent::__sleep(), array('tablestorage', 'rowstorage'));
	}


	/**
	* Constructor.
	* @param $filename CCMS Object Definition Filename
	* @param $editmode Table in editmode or not
	* @returns void
	*/
	public function __construct($filename, $editmode) 
	{	
		parent::__construct($filename, $editmode);
		$this->tablestorage = new CCMSTableStorage($filename);
		$this->rowstorage = new CCMSRowIDStorage();
	}

	/**
	* Adds a value to an element. 
	* Basically rerouting to the CCMSElementValueStorage element.
	* @param $elementid the elementid of the element for which the value is set
	* @param $val the value itself
	* @param $dbid  the "databaseid" of the value
	* @returns void
	* @see CCMSElementValueStorage, CCMSRowIdStorage
	*/
	protected function add_value($elementid, $val, $dbid)
	{
		if(($valId = $this->rowstorage->get_tblid($dbid)) === false)
			$valId = $this->rowstorage->add_rowid($dbid, false);

		$this->elements->add_value($elementid, $val, $valId);
		
	}

	/**
	* Adds a dyntag to one of the CCMS Object's elements.
	* @param $elementid the elementid of the element as specified in the xml file
	* @param $tag the tag to be changed
	* @param $val the value for the element
	* @param $attributes attributes to the dyntag
	* @param $dbid the "rownumber" of the value
	* @returns void
	* @see CCMSElementValueStorage
	*/
	protected function add_dyntag($elementid, $tag, $val, $attributes, $dbid)
	{
		if(($valid = $this->rowstorage->get_tblid($dbid)) === false)
			$valid = $this->rowstorage->add_rowid($dbid);

		$this->elements->add_dyntag($elementid, $tag, $val, $attributes, $valid);
	}


	/**
	* The "main table" function.
	* Writes the table in gui mode. <BR> Uses all settings made via constructors.
	* @returns void
	* @see CCMSElementValueStorage, CCMSTableStorage
	*/

	public function write_me()
	{
		if($this->editmode == 1){
			$this->includes('table', 'table.php');
			$this->start_label();
			$this->headadd_edit();
			$this->stop_label_head();
			
		}

		echo "<table ".$this->tablestorage->get_tableattributes()." id=\"ccms_".$this->objectname."_tbl\">\n";
		
		//all entries
		$maxvals = $this->elements->get_valuecount();
		for($i=0;$i<$maxvals;$i++)
			$this->write_trs($i);
		

		echo "</table>\n";
		if($this->editmode == 1){
			//for adding rows, a temporary container :)
			echo "<div id=\"ccms_".$this->objectname."_tmptable\" class=\"ccms_invisible\"></div>";
			$this->end_label();
		}
		
	}


	/**
	* Writes all rows for a tablecontainer.
	* @param $valuerownr the valuerownumber
	* @returns void
	* @see write_tbl()
	*/
	
	protected function write_trs($valuerownr)
	{
	
		$allrows = $this->tablestorage->get_structarray();
		for($i=0;$i<count($allrows);$i++){
			
			echo "<tr id=\"ccms_".$this->objectname."_".$valuerownr."_".$i."_tr\" ".$allrows[$i].">\n";
			$this->write_tds($i, $valuerownr);
			
			if($this->editmode == 1 && $i==0){
				echo "\n<td rowspan=\"".count($allrows)."\" id=\"ccms_".$this->objectname."_".$valuerownr."_fieldset\" style=\"vertical-align:top;\">\n";
				echo "<a href=\"javascript:switch_boxes_".$this->objectname."('".$valuerownr."')\" class=\"ccms_headeditlink\">edit</a><br />\n";
				echo "<a href=\"javascript:del_tr_".$this->objectname."('".$valuerownr."')\" class=\"ccms_headeditlink\">delete</a>\n";
				echo "</td>\n";
			}
			echo "</tr>\n\n";
		}
		
	}
	
	/**
	* Writes all rows for a tablecontainer.
	* @param $rownr the rownumber of the current tablecontainer
	* @param $valuerownr the valuerownr
	* @returns void
	* @see write_tbl()
	*/
	protected function write_tds($rownr, $valuerownr)
	{
		$allcols = $this->tablestorage->get_structarray($rownr);
		$cur_divnr = $this->tablestorage->get_cellnumber($rownr, 0);


		for($i=0;$i<count($allcols);$i++){
			
			echo "<td ".$allcols[$i].">\n";
			
			$allelements = $this->tablestorage->get_cellelements($rownr, $i);

			//in case there's nothing in the td
			if(count($allelements) == 0)
				continue;

			$namepre = "_".$valuerownr."_";

			//write the visible div (do so always)
			$this->generic_container($this->write_gui($valuerownr, true, $namepre, $allelements), 
												$this->objectname.$namepre.$cur_divnr, true);
			
			if($this->editmode == 1){
				$this->generic_container($this->write_gui($valuerownr, false, $namepre, $allelements), 
												$this->objectname.$namepre.$cur_divnr, false);
			}
				
			echo "\n</td>\n";
			$cur_divnr++;
		}
	}

	
	/**
	* Adds the "add" link in the CCMS Object header for table elements.
	* @returns void
	*/
	protected function headadd_edit()
	{
		?><a href="javascript:add_tr_<?php echo $this->objectname;?>()" class="ccms_headeditlink">add</a><?php
	}	

	/**
	* Creates the serverside object.
	* @param $obj the classname
	* @param $moverows move rows after insert or not ( e.g for sorting dates )
	* @returns void
	*/
	protected function set_serverobject($obj, $moverows=false)
	{
		if($this->editmode == 1){	
			$serverobject = new $obj(serialize($this->elements), serialize($this->tablestorage) , serialize($this->rowstorage), $moverows);
			$_SESSION['ccms_s_'.$this->objectname] = serialize($serverobject);
		}
	}


}

/*! @} */

?>

