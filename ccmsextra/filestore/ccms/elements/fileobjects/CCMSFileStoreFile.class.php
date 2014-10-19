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
* @addtogroup Filesupport 
* @{
*/

/** 
* @file CCMSSinglePictureFile.class.php
* The single picture element fileupload support.
* @see CCMSElements, CCMSFileBase
*/

/**
* The single picture upload class.
* Extends the CCMSFileBase to provide a way to upload the single picture things....
*/

class CCMSFileStoreFile extends CCMSFileBase
{
	/**
	* Get the path.
	*/    			 	
  public function get_path(){ return $this->path; }
  
  /**
	* Get the webpath.
	*/    			 	
  public function get_webpath(){ return $this->webpath; }
  /**
  * Get the first filename.
  * Used for setup, if no file is selected yet.
  * @returns a filename.
  */      
  public function get_first_file() 
  { 
    $handle = opendir($this->path);
    while(false !== ($file = readdir($handle)))
          if ($file != "." && $file != "..") 
            return $file;

  }

  /**
  * Upload a file.
  * @param $file the $_FILE variable
  * @returns void.    
  */    
  public function upload_file($file)
  {
    parent::upload_file($file, $this->path, 2, $this->type, $this->size);
  }

	/**
	* deletes a file.
	* @param $file path.filename
	* @returns void
	*/
	public function del_file($file)
	{
		parent::del_file($this->path.$file);
	}

}
/*! @} */

?>
