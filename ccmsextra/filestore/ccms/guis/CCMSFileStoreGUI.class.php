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
* @file CCMSFileStoreGUI.class.php
* Creates a CCMSFileStore Object
*/

/**
* The Filestorage Object.
* A filestore object is uesed to add a data storage for all kind of data to the website.<br>
* This class parses its own xml file to get its options, since it has no typical server/gui structure.
*/
class CCMSFileStoreGUI extends CCMSXMLHandler
{
  /**
  * Checks wheter or not the editmode is set.
  */    
  private $editmode = 0;
	
	/**
	* All object's settings.
	*/      	
  private $settings = array();
  
  /**
  * Refrence to the current setting.
  */       
  private $cursetting = false;
  
  
  /**
	* Constructor.
	* @param $filename CCMS Object Definition Filename
	* @param $editmode Object editmode or not
	* @returns void
	*/
	function __construct($filename, $editmode) 
	{	
	  if($editmode != 1)
	     return;
	     
	  $this->editmode = $editmode;
		parent::__construct($filename);
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
	   if($name != "CCMS")
      $this->cursetting =& $this->settings[strtolower($name)];
     else
      $this->settings['objectname'] = $attrs['NAME'];
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
	   unset($this->cursetting);
    $this->cursetting = false;
	}

	/**
	* Adds the value of a settings tag to the element.
	* @param $parser the parser
	* @param $data the value of the tag
	* @returns void 
	*/	
	protected function xml_adddata($parser, $data) 
	{
    if($this->cursetting !== false)
      $this->cursetting = $data;
  }

  public function write_me()
  {  
    if($this->editmode == 0)
      return;
      
      
    $myid = CCMSFileElementsRegister::register_element($this->settings['objectname'], 
            "CCMSFileStoreFile", $this->settings['path'], $this->settings['webpath'], "", 
            $this->settings['maxsize']);
            
    echo "<script src=\"".CCMS_JSPATH."generic.js.php?name=".$this->settings['objectname']."\" type=\"text/javascript\"></script>";
    echo "<a href=\"javascript:ccms_".$this->settings['objectname']."_open_uplwindow(360, 300, 'filestore', ".$myid.")\"";
    if(!empty($this->settings['linkstyle']))
      echo " class=\"".$this->settings['linkstyle']."\"";
    echo ">Filestorage</a>";
    
  }


}

/*! @} */

?>

