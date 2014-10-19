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
* @file CCMSTableStorage.class.php
* Stores Tablestructures etc.
*/

/**
* Table storage and building class.
* The storage class for tablecontainers ( a representation of a single data row in a CCMSTable object)
* It stores which element belongs to what cell, attributes for all cells and the table as a whole<BR>
* All this data is obtained from a xml file.<BR>
* <BR><B>General information on how this works</B><BR>
* A generic table is created as follows:<BR>
* Everything is grouped in tablecontainers. Every set of new data is another tablecontainer. 
* If a set of data is represented by 2 rows and 3 columns, then this is the tablecontainer.<BR>
* Every single cell of that tabelcontainer holds elements like any single gui.<BR>
* CCMSTableStorage::$tablecontainer holds the structure for a tablecontainer with all the elements for every cell. <BR>
* CCMSTableStorage::$tablecontainer_attributes is used to match attributes to the different cells ( e.g cellspan=3) <BR>
* Poke around in the class to get further information
*/

class CCMSTableStorage extends CCMSXMLHandler
{
	
	/** 
	*  Tablecontainer defintion.
	*  Array of the structure of ONE tablecontainer (one representation of a databaserow for example)<BR>
	*/
	private $tablecontainer=array();

	/** 
	*  Tablecontainer attributes settings.
	*  Every &lt;TD&gt; can have attributes for style etc.<BR>
	* Those attributes are stored in this array. <BR>
	* It is split into $tablecontainer_attributes['rows'] and $tablecontainer_attributes['cols'] to address all trs and tds
	*/
	private $tablecontainer_attributes=array();

	/**
	* Current row postion in $tablecontainer
	*/
	private $currow=false;

	/**
	* Current col position in $tablecontainer
	*/
	private $curcol=false;

	/**
	* TABLE tag attributes
	* just a variable with all attibutes for the &lt;TABLE&gt; tag
	*/
	private $tableattributes;

	

	/**
	* Element counter.
	* To match with noid elements
	*/
	private $elementcounter=0;


	/**
	* Magic function for serialize().
	* Returns all variables to be saved when serializing.
	*/
	public function __sleep()
	{
		return array('tablecontainer', 'tablecontainer_attributes', 'tableattributes');
	}

	/**
	* Parse a xml file for elements
	* @param $file the filename (must reside in CCMS_CUSTOM)
	* @returns the CCMS Object Name
	*/
	function __construct($file)
	{
		parent::__construct($file);
	}

	/**
	* The tag open function.
	* Sets the current elementid, values etc.
	* @param $parser the parser
	* @param $name the tag
	* @param $attrs the attributes to the tag
	* @returns void
	*/
	protected function startElement($parser, $name, $attrs) 
 	{
		if($name == "ELEMENT"){
			if(isset($attrs['ID']))
				$curnr = array_push($this->tablecontainer[$this->currow][$this->curcol], $attrs['ID']) ;
			else
				$curnr = array_push($this->tablecontainer[$this->currow][$this->curcol], $this->elementcounter) ;

			$this->elementcounter++;
		}

		if($name == "TD"){
			$curnr = array_push($this->tablecontainer[$this->currow], array()) - 1;
			$this->curcol = $curnr;
			$this->tablecontainer_attributes['col'][$this->currow][$curnr] = $this->flat_attributes($attrs);
			return;
		}

		if($name == "TR"){
			$curnr = array_push($this->tablecontainer, array()) - 1;
			$this->currow = $curnr;
			$this->tablecontainer_attributes['row'][$curnr] = $this->flat_attributes($attrs);
			return;
		}
					
		if($name == "TABLE")
			$this->tableattributes = $this->flat_attributes($attrs, false);
		
 	}

	/**
	* The tag close function.
	* Unsets the current tag settings
	* @param $parser the parser
	* @param $name the tag
	* @returns void
	*/
	protected function endElement($parser, $name) 
	{
		if($name == "TR"){
			$this->currow = false;
			$this->curcol = false;
			return;
		}

		if($name == "TD"){
			$this->curcol = false;
			return;
		}
		
		if($name == "ELEMENT"){
			$this->curelement = false;return;}
	}

	/**
	* Adds the value of a settings tag to the element.
	* @param $parser the parser
	* @param $data the value of the tag
	* @returns void 
	*/	
	protected function xml_adddata($parser, $data){ }

	/**
	* Flattens the attribute array.
	* The xml parser usually gives back the attributes as an array. <BR>
	* This function changes them back to their original "style".
	* @param $attr the attribute array
	* @param $idrestrict restricts the use of the id attribute
	* @returns the flattened string
	*/	
	private function flat_attributes($attr, $idrestrict=true) 
	{
		$str='';
		if(!is_array($attr))
			return;
		foreach ($attr as $key => $curval) 
			if($key != 'ID' || $idrestrict==false)
				$str.= ' '.strtolower($key).'="'.$curval.'"';
		return trim($str);
	}		
	
	/**
	* Returns the tableattributes.
	* The attributes for the &lt;TABLE&gt; tag
	* @returns CCMSTableStorage::$tableattributes
	*/
	public function get_tableattributes()
	{
		return $this->tableattributes;
	}


	/**
	* Returns the row or col structur with attributes.
	* Read on $tablecontainer_attributes for further information
	* @param $rownr a rownr 
	* @returns a col listing will be returned if $rownr is set otherwise the row listing
	* @see CCMSTableStorage::$tablecontainer_attributes , CCMSTableGUI::write_tbl(), CCMSTableGUI::write_trs()
	*/
	public function get_structarray($rownr=false)
	{
		if($rownr === false)
			return $this->tablecontainer_attributes['row'];
		else
			return $this->tablecontainer_attributes['col'][$rownr];
	}

	/**
	* Returns the elementids of a cell.
	* Basically like get_cellelements() but it doesn't take row and col but only a number which will be matched to a cell.
	* @param $cellnr a cellnr 
	* @returns an array with all elements in that cell
	* @see CCMSTableServer::showme()
	*/
	public function get_cellelements_numbered($cellnr)
	{
		$curnr=0;
		for($i=0;$i<count($this->tablecontainer);$i++){
			for($j=$curnr;$j<count($this->tablecontainer[$i])+$curnr;$j++){
				if($cellnr==$j)
					return $this->tablecontainer[$i][$j-$curnr];
			}
			$curnr=$j;
			
		}
		return false;
	}

	/**
	* Returns the elementids of a cell.
	* @param $row the row of the cell
	* @param $col the col of the cell
	* @returns an array with all elements
	* @see CCMSTableGUI::write_tds();
	*/
	public function get_cellelements($row, $col)
	{
		return $this->tablecontainer[$row][$col];
	}

	/**
	* Gets the number of a cell to match it to a div tag.
	* It goes through all cells counting them until row and col are reached. Empty cells will not be counted though.
	* @param $row row of the cell
	* @param $col col of the cell
	* @returns the number of the cells or false
	*/

	public function get_cellnumber($row, $col)
	{
		$curnr = 0;
		for($i=0;$i<count($this->tablecontainer);$i++){
			for($j=0;$j<count($this->tablecontainer[$i]);$j++){
				if($row == $i && $j == $col)
					return $curnr;
				if(!empty($this->tablecontainer[$i][$j]))
					$curnr++;
			}
		}
		return false;
	}
	


}

/*! @} */

?>
