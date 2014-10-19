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
* @file CCMSGeneric.class.php
* The generic CCMS Object Base.
* Holds the elements engine for gui and server objects.
* @see CCMSGenericGUI, CCMSGenericServer
*/

/**
* The Base for every CCMS Object
* This class is the basic engine for every ccms object.
* It takes the definition xml file and creates all elements of the object.
* More details on the use as an engine:
* @see CCMSGeneric::write_engine()
*/

class CCMSGeneric
{
	/**
	* CCMS Object Name.
	* This variable holds the Name for the CCMS Object to be written.<BR>	
	* It is very important and used quite often throughout all childclasses
	*/
	protected $objectname;
	
	/**
	* Holds all elements in the CCMS Object.
	* @see  CCMSGenericGUI::write_engine()
	*/
	protected $elements;

	/**
	* Holds the filename of the CCMS Object definition.
	*/
	protected $deffile;
	
	/**
	* Magic function for serialize().
	* Returns all variables to be saved when serializing.
	*/
	public function __sleep()
	{
		return array('objectname', 'elements', 'deffile');
	}

	/**
	* The CCMSGeneric constructor.
	* Sets up the CCMS Object Name
	* @param $elementstorage a serialized form of a CCMSElementStorage class
	* @returns void
	* @see CCMSGeneric::$objectname, CCMSElementStorage
	*/
	function __construct($elementstorage)
	{	
		$this->elements = unserialize($elementstorage);
		$this->objectname = $this->elements->get_objectname();
		$this->deffile = $this->elements->get_filename();
	}

	/**
	* Creates a generic container.
	* Those "containers" are nothing but special div tags, hidden or visible, to swicht between editmode and showmode<BR>
	* @param $text the value of the divbox
	* @param $name the name of the containerbox
	* @param $visible visibility setting (hidden or not)
	* @returns void
	* @see CCMSGeneric::write_engine() for usage
	*/
	protected function generic_container($text, $name, $visible)
	{
		if($visible == true)
			$this->container($text, 'ccms_'.$name.'_vis', "ccms_visible");
		else
			$this->container($text, 'ccms_'.$name.'_edit', "ccms_invisible");
	}
	

	/**
	* The html code for the container.
	* @param $text the value of the box
	* @param $id the html id for the tag (basically the name)
	* @param $cssclass the class (mostly visiblity settings)
	* @returns void
	* @see CCMSGenericTextGUI::generic_container()
	*/
	protected function container($text, $id, $cssclass)
	{
		?>
		<span class="<?php echo $cssclass;?>" id="<?php echo $id;?>"><?php echo $text;?></span>
		<?php
	}


	/**
	* The Generic Elements engine.
	* This is the most basic function for all CCMS Objects. It is the function that will put everything together.
	* <BR> How this works:<BR>
	* The function gets all elements to be written, either via $alternativeids or it fetches them itself.<BR>
	* It recieves them as an array with all elementids stored to be written.<BR>
	* The function<BR>CCMSElementStorage::get_element() is called for every element. 
	* @param $valnr which set of values in $elements to use ( see CCMSElementValueStorage)
	* @param $mode the editmode for the elements
	* @param $name_pre if the elementnames need a prefix (e.g for tables)
	* @param $alternativeids do not take all elementids and print them, but rather use this set of ids
	* @returns the htmlcode for all objects.
	* @see CCMSElementValueStorage
	*/

	protected function write_engine($valnr, $mode, $name_pre, $alternativeids=0)
	{
		$result = "";

		if(empty($alternativeids))
			$elementids = $this->elements->get_elementids();		
		else
			$elementids = &$alternativeids;

		for($i=0;$i<count($elementids);$i++)
			$result .= $this->elements->get_element($elementids[$i], $mode, $name_pre, $valnr);
				
		return $result;
	}


}

/*! @} */

?>

