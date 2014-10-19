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
* @file CCMSXMLHandler.class.php
* Abstract class for all storageclasses working with xml files.
*/

/**
* Abstract class for all XML parsing.
* All storage classes somehow need the xml sheet to configute themselves, so this abstract class is used to provide xml parsing capabilites.
*/

abstract class CCMSXMLHandler
{
	/**
	* The XML parser.
	*/
	protected $parser;
	

	/**
	* Parse a xml file for elements
	* @param $file the filename (must reside in CCMS_CUSTOM)
	* @returns the CCMS Object Name
	*/
	function __construct($file)
	{
		$this->parser = xml_parser_create("UTF-8");

		xml_set_object($this->parser, $this);
		xml_parser_set_option ( $this->parser, XML_OPTION_SKIP_WHITE, 1 );
		xml_set_element_handler($this->parser, "startElement", "endElement");
		xml_set_character_data_handler($this->parser, "xml_adddata");
		if (!($fp = fopen(CCMS_CUSTOM.$file, "r"))) {
			die("could not open XML input");
		}
		
		while ($data = fread($fp, 4096)) {
			if (!xml_parse($this->parser, $data, feof($fp))) {
				die(sprintf("XML error: %s at line %d",
					xml_error_string(xml_get_error_code($this->parser)),
					xml_get_current_line_number($this->parser)));
			}
		}
		xml_parser_free($this->parser);
	}

	/**
	* The tag open function.
	* Sets the current elementid, values etc.
	* @param $parser the parser
	* @param $name the tag
	* @param $attrs the attributes to the tag
	* @returns void
	*/
	abstract protected function startElement($parser, $name, $attrs);
 	

	/**
	* The tag close function.
	* Unsets the current tag settings
	* @param $parser the parser
	* @param $name the tag
	* @returns void
	*/
	abstract protected function endElement($parser, $name); 

	/**
	* Adds the value of a settings tag to the element.
	* @param $parser the parser
	* @param $data the value of the tag
	* @returns void 
	*/	
	abstract protected function xml_adddata($parser, $data);
	
}

/*! @} */

?>
