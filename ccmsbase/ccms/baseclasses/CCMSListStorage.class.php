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
* @file CCMSListStorage.class.php
* Stores List Structures.
*/

/**
* Inputs all Ids in a certain structure and matches values to them.
* This is a recursive object recreating itself to represent a "list" structure with indented entries.<BR>
* It's used in CCMSListStorage to match a CCMSElementValueStorage valuenr to an id and save the structure at the same time.
*/
class CCMSListIdValnrMatch
{
	/**
	* The id of the current entry
	*/
	private $id;

	/**
	* The CCMSElementValueStorage valuenr of the id
	*/
	private $valnr;
	
	/**
	* The indent level of the current object.
	* This is used to retrieve the structure later on
	*/
	private $lvl;

	/**
	* Contains all subelements of that specific element
	*/
	private $children = array();
	
	/**
	* Used to retrieve a certain element by it's number
	*/
	public static $countdown = 0;
	
	/**
	* Which CCMSElementValueStorage valuenr is to be used next
	*/
	public static $curval = -1;

	/**
	* Constructor.
	* @param $id the id of the element
	* @param $lvl the indent level of the object
	* @returns void
	*/
	
	public function __construct($id, $lvl = -1)
	{
		$this->id = $id;
		$this->valnr = CCMSListIdValnrMatch::$curval;
		$this->lvl = $lvl;
		CCMSListIdValnrMatch::$curval++;
	}

	/**
	* Retrieves a elements id
	* @returns the id
	*/
	public function get_id() { return $this->id; }

	/**
	* Retrives the CCMSElementValueStorage valuenr.
	* @returns CCMSElementValueStorage valuenr
	*/
	public function get_valnr() {return $this->valnr; }

	/**
	* Adds a new object into the structure.
	* @param $id the id of the new object
	* @param $subto where to put the new object (structurwise)
	* @returns new CCMSElementValueStorage valuenr on success, false on failure
	*/
	public function add_id($id, $subto)
	{
		if($subto === $this->id){
			for($i=0;$i<count($this->children);$i++)
				if($this->children[$i]->get_id() == $id)
					return $this->children[$i]->get_valnr();
			
			$tmp = new CCMSListIdValnrMatch($id, ($this->lvl + 1));
			array_push($this->children, $tmp);
			return $tmp->get_valnr();
		}else{
			for($i=0;$i<count($this->children);$i++){
				$tmp = $this->children[$i]->add_id($id, $subto);
				if($tmp !== false)
					return $tmp;
			}
		}	
		
		return false;

	}
	
	/**
	* Retrieve a certain element.
	* Retrieves the element given by $nr by just walking the structure that many steps.<BR>
	* This is the function to set the $nr variable so the 
	* @param $nr which element to retrieve getnext() function will work
	* @returns an array with array(id, valnr, level) or false
	*/
	public function get_val($nr)
	{
		CCMSListIdValnrMatch::$countdown = $nr;
		return $this->getnext();
	}

	/**
	* Get the next element.
	* Walk the list until static variable CCMSListIdValnrMatch::$countdown is at 0
	* @returns an array with array(id, valnr, level) or false
	*/
	private function getnext()
	{
		if(CCMSListIdValnrMatch::$countdown == 0){
			return array($this->id, $this->valnr, $this->lvl);
		}else{
			CCMSListIdValnrMatch::$countdown--;
			for($i=0;$i<count($this->children);$i++){
				$tmp = $this->children[$i]->getnext();
					if($tmp !== false)
						return $tmp;
			}
		}
		return false;
	}

}




/**
* The stroage class which contains all neccessary list information.
* Following information is stored here:<BR>
* - What indent level has what kind of elements
* - A match between ids and CCMSElementValueStorage valuenrs
* - A structure on what id is on what level..
* - Information on the attributes to the ul/ol tags..
*/
class CCMSListStorage extends CCMSXMLHandler
{
	
	
	/**
	* Counts the elements (in case an id is missing).
	* Used for construct only
	*/
	private $elementcounter = 0;

	/**
	* Save the elements for each indent here.
	* Structure is $elements[indentnr] = array(elementid1, elementid2...)
	*/
	private $elements = array();

	/**
	* Current indentlevel for reading of xml file
	*/
	private $curindent = false;
	
	/**
	* All settings on the different levels.
	*/
	private $settings = array();

	/**
	* The storage for the CCMSElementValueStorage valuenrs match
	*/
	private $valstor;

	/**
	* Magic function for serialize().
	* Returns all variables to be saved when serializing.
	*/
	public function __sleep()
	{
		return array('elements', 'settings', 'valstor');
	}

	/**
	* Parse a xml file for elements
	* @param $file the filename (must reside in CCMS_CUSTOM)
	* @returns the CCMS Object Name
	*/
	function __construct($file)
	{
		$this->valstor= new CCMSListIdValnrMatch("!_!");
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
		if($name == "ELEMENT" && $this->curindent !== false){
			if(isset($attrs['ID']))
				array_push($this->elements[$this->curindent], $attrs['ID']) ;
			else
				array_push($this->elements[$this->curindent], $this->elementcounter) ;

			$this->elementcounter++;
			return;
		}

		if($name == "INDENT" && is_numeric($attrs['LEVEL'])){
			$this->curindent = $attrs['LEVEL'];
			$this->elements[$attrs['LEVEL']] = array();
			if($attrs['TYPE'] == "numeric") $type = "ol"; else $type = "ul";

			unset($attrs['TYPE']);unset($attrs['LEVEL']);

			$this->settings[$this->curindent] = array($type, $this->flat_attributes($attrs));
			return;
		}

		if($name == "ENTRYCONFIG" && $this->curindent !== false){
			 array_push($this->settings[$this->curindent], $this->flat_attributes($attrs));
			return;
		}
		
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
		if($name == "INDENT"){
			$this->curindent = false;
			return;
		}
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
	* @returns the flattened string
	*/	
	private function flat_attributes($attr) 
	{
		$str='';
		if(!is_array($attr))
			return;
		foreach ($attr as $key => $curval) 
			$str.= ' '.strtolower($key).'="'.$curval.'"';
		return trim($str);
	}		

	/**
	* Retrieve settings.
	* @param $indent the indent level to get the settings from.
	* @param $setting what setting to get
	* @returns an array(indent tag, main attributes, entry attributes) or false on failure
	*/
	public function get_settings($indent, $setting=0)
	{
		if(!isset($this->settings[$indent]))
			return false;
		
		switch($setting){
			case 2:
				return count($this->settings); break;
			case 1:
				return $this->settings[$indent][2];break;
			case 0:default:
				return array($this->settings[$indent][0], $this->settings[$indent][1], count($this->settings)); break;
		}
	}

	/**
	* Retrieve elements for a certain indent.
	* @param $indent the indent level to get the elements from.
	* @returns an array with all elements or false on failure
	*/
	public function get_elements($indent)
	{
		if(isset($this->elements[$indent]))
			return $this->elements[$indent];
		else 
			return false;
	}

	/**
	* Add an id.
	* @param $id the id to add
	* @param $subto add the id beneath that id
	* @returns the CCMSElementValueStorage valuenr for that particular id or false on failure
	*/
	public function add_id($id, $subto)
	{
		return $this->valstor->add_id($id, $subto);
	}

	/**
	* Get a certain valuenr.
	* This basically provides a method to get the CCMSListIdValnrMatch class as a list with 0 being the first entry and so forth...
	* @param $nr the nr to recieve.
	* @returns an array as in CCMSListIdValnrMatch::get_val()
	*/
	public function get_entry($nr)
	{
		return $this->valstor->get_val($nr);
	}

	/**
	* Get the number of entries.
	* @returns the number of entries in the list
	*/
	public function count_entries()
	{
		return CCMSListIdValnrMatch::$curval;
	}

	/**
	* Get the elementnr for a certain indent.
	* For example, if the 0 indent level contains 2 elements, a call with $indent = 1 will give out 2 (because 0 & 1) are used in level 0
	* @param $indent the indent to get it from
	* @returns a number (check details what number)
	*/
	public function get_elementcountstart($indent)
	{
		$end = 0;
		for($i=0;$i<$indent;$i++)
			$end += count($this->elements[$i]);
		return $end;
	}

	/**
	* Get the level for a certain elementnr.
	* @param $startelement the indent to get it from
	* @returns a number 
	*/
	public function get_level($startelement)
	{
		$lvl = 0;
		while($startelement > 0){
			$startelement -= count($this->elements[$lvl]);
			$lvl++;
		}
		return $lvl;
	}

}

/*! @} */

?>
