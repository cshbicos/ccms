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
* @file CCMSElementStorage.class.php
* Elements storage.
*/

/**
* Elements  storage class.
* Every GUI needs to save the lists of its elements somehow, and this is where<BR>
* This class can take all elements from an xml file, match values to them (more then one value per element) and hand them back.
*/

class CCMSElementStorage extends CCMSXMLHandler
{
	
	/**
	* Temporary setting variables to parse the xml document.
	* @{
	*/
	private $tmp_elementnr = 0;
	private $tmp_curelement = false;
	private $tmp_curelement_setting;
	private $tmp_curelement_attributes;
	private $tmp_elementnumbering = 0;
	/*! @} */


	/**
	* The elements.
	* Saved in an array like this:<BR>
	* $elements[ keynumber ] = CCMSElementObject
	*/
	private $elements = array();

	/**
	* The element order.
	*/
	private $elementorder = array();

	/**
	* The element numbering ( what element starts at what number).
	* @see CCMSElementStorage::get_element()
	*/
	private $elementnumber = array();

	/**
	* The values/dyntags storage
	*/
	private $valstore;

	/**
	* The CCMS Object Name.
	*/
	private $objectname;	

	/**
	* The xml filename on where the information came from
	*/	
	private $filename;


	/**
	* Magic function for serialize().
	* Returns all variables to be saved when serializing.
	*/
	public function __sleep()
	{
		return array('elements', 'elementorder', 'elementnumber', 'valstore', 'objectname', 'filename');
	}
	

	/**
	* Parse a xml file for elements
	* @param $file the filename (must reside in CCMS_CUSTOM)
	*/
	function __construct($file)
	{
		$this->valstore = new CCMSValueStorage();
		$this->filename = $file;
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
	
		if($this->tmp_curelement !== false){
			$this->tmp_curelement_setting = $name;
			$this->tmp_curelement_attributes = $attrs;
			return;
		}

		if($name == "ELEMENT" && isset($attrs['TYPE'])){
			if(!isset($attrs['ID']))
				$attrs['ID'] = $this->tmp_elementnr;
			$this->elements[$attrs['ID']] = new $attrs['TYPE']();
			array_push($this->elementorder, $attrs['ID']);
			$this->elementnumber[$attrs['ID']] = array($this->tmp_elementnumbering, $this->elements[$attrs['ID']]->associated_values());
			$this->tmp_curelement = $attrs['ID'];
			$this->tmp_elementnumbering += $this->elements[$attrs['ID']]->associated_values();
		  $this->tmp_elementnr++;
    }
		
		if($name == "CCMS")
			$this->objectname = $attrs['NAME'];
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
		if($name == $this->tmp_curelement_setting){
			$this->tmp_curelement_setting = false;
			$this->tmp_curelement_attributes = false;
			return;
		}
		if($name == "ELEMENT")
			$this->tmp_curelement = false;
	}

	/**
	* Adds the value of a settings tag to the element.
	* @param $parser the parser
	* @param $data the value of the tag
	* @returns void 
	*/	
	protected function xml_adddata($parser, $data) 
	{
		if(preg_match ( "/[^ ]*/", $data) && !empty($this->tmp_curelement_setting)){
			$this->elements[ $this->tmp_curelement ]->load_data(strtolower($this->tmp_curelement_setting), $data, $this->tmp_curelement_attributes);
		}
	}


	/**
	* Adds a value to a given element.
	* check  CCMSValueStorage::$vals for description of the value array
	* @param $elementid valid element id
	* @param $value a value for the element
	* @param $valnr use a number here to add the value to a specific row
	* @returns true or false on fail
	*/
	public function add_value($elementid, $value, $valnr=0)
	{	
		if(isset($this->elements[$elementid])){
			$this->valstore->add_value($valnr, $elementid, $value);
			return true;
		}else{
			return false;
		}
	}


	/**
	* Adds a dyntag to a given element.
	* check  CCMSValueStorage::$dyntags for description of the dyntags array
	* @param $elementid valid element id
	* @param $tag the dyntag to be set
	* @param $attributes attributes to the dyntag
	* @param $value the value for the tag
	* @param $valnr use a number here to add the value to a specific row
	* @returns true or false on fail
	*/
	public function add_dyntag($elementid, $tag, $value, $attributes, $valnr = 0)
	{		
		if(isset($this->elements[$elementid])){
			$this->valstore->add_dyntag($valnr, $elementid, $tag, $value, $attributes);
			return true;
		}else{
			return false;
		}
	}

	/**
	* Returns how many values were stored.
	* @returns the max values stored
	*/
	public function get_valuecount(){	return $this->valstore->get_valuecount();}

	/**
	* Get the Object Name.
	* @returns the object name
	*/
	public function get_objectname(){return $this->objectname;}

	/**
	* Get the Filename of the definition file.
	* @returns the filename
	*/
	public function get_filename(){return $this->filename;}

	/**
	* Get all element ids.
	* @returns Returns an array of all element ids.
	* @see CCMSGeneric::write_engine()
	*/
	public function get_elementids(){	return $this->elementorder;}

	
	/**
	* Gets the HTML text of a element.
	* @param $elementid the elementid
	* @param $mode show or editmode
	* @param $namepre the name for the element without the running number attached
	* @param $valnr the value nr ($val array index)
	* @see CCMSGenericGUI::write_engine()
	*/
	public function get_element($elementid, $mode, $namepre, $valnr=0)
	{
		if(($dyntags = &$this->valstore->get_dyntag($valnr, $elementid)) !== false)
			for($i=0;$i<count($dyntags);$i++)
				$this->elements[ $elementid ]->load_data($dyntags[$i][0],$dyntags[$i][1],$dyntags[$i][2]);	
		
		;
		switch($this->elementnumber[ $elementid ] [1]){
			case 1:
				$name = $namepre.$this->elementnumber[ $elementid ] [0];
				break;
			case 0:
				$name = $namepre."none".$elementid;
				break;
			default:
				$name = array();
				for($i=0;$i<$this->elementnumber[ $elementid ] [1];$i++)
					$name[$i] = $namepre.($this->elementnumber[ $elementid ] [0] + $i);
				break;
		}

		if($mode == true)
			return $this->elements[ $elementid ]->showmode($name, $this->valstore->get_vals($valnr, $elementid));
		else
			return $this->elements[ $elementid ]->editmode($name,  $this->valstore->get_vals($valnr, $elementid));
	}


	/**
	* Adds a $_POST array to the valuerow $rownr.
	* This is called before the write_engine on a serverobject kicks in.
	* @param &$vals the values
	* @param $rownr the rownr to insert it into
	* @param $prefix the prefix for the $_POST arraykeys
	* @returns void
	* @see CCMSGenericServer::write_engine()
	*/
	public function add_servervalues(&$vals, $rownr, $prefix='ccms_t')
	{	
		foreach ($this->elementorder as $elementid) {
			switch($this->elementnumber[ $elementid ] [1]){
				case 1:
					if(!isset($vals[$prefix.$this->elementnumber[ $elementid ] [0]]))
						$value = "";
					else
						$value = $vals[$prefix.$this->elementnumber[ $elementid ] [0]];
					break;
				case 0:
					$value = "";
					break;
				default:
					$value = array();
					for($i=0;$i<$this->elementnumber[ $elementid ] [1];$i++)
						if(!isset($vals[$prefix.($this->elementnumber[ $elementid ] [0] + $i)]))
							$value[$i] = "";
						else
							$value[$i] = $vals[$prefix.($this->elementnumber[ $elementid ] [0] + $i)];
					break;
			}
			$this->valstore->add_value($rownr, $elementid, $value);
		}
	}


}

/*! @} */

?>
