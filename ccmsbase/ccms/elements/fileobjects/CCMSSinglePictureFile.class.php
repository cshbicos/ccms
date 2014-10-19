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

class CCMSSinglePictureFile extends CCMSFileBase
{
	/**
	* The picture filename.
	*/
	private $picname;

	/**
	* Dimensions of the pic.
	*/
	private $width;
	private $height;
				 	
	/**
	* Adds the extended information.
	* @param $extendinfo an array with the extended information.
	* @returns void		
	*/
	function add_extended($extendinfo)
	{
      $this->width = $extendinfo['width'];
      $this->height = $extendinfo['height'];
	    $this->picname = $extendinfo['picname'];
	}	

	/**
	* Single Picture upload.
	* Uploads a picture with the name given in the definition of the element. reloads the picture in the GUI afterwards<BR>
	* @param $fileelement a $_FILES type variable
	* @returns void
	*/
	public function upload_new($fileelement)
	{
		$fileelement['name'] = $this->picname;
		if($this->upload_file($fileelement, $this->path, self::CCMS_FILEBASE_FILENAME, $this->type, $this->size)){
			?>
			image = opener.document.getElementById('<?php echo $this->elementname;?>_pic');
			image.src = "<?php echo $this->webpath .  $this->picname;?>?id=<?php echo date("smH");?>";
			image2 = opener.document.getElementById('<?php echo $this->elementname;?>_edit_pic');
			image2.src = "<?php echo $this->webpath .  $this->picname;?>?id=<?php echo date("smH");?>";
			window.close();
			<?php
		}
	}

}
/*! @} */

?>
