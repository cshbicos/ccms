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
* The  pictureshow filehandler class.
* Extends the CCMSFileBase to provide a way to upload a pictureshow
*/

class CCMSPictureShowFile extends CCMSFileBase
{
	/**
	* The preview width setting
	*/
	private $prewidth;

	/**
	* The maximum width for the pic
	*/
	private $width;

	/**
	* The maximum height
	*/
	private $height;
 

	/**
	* Adds the extended information.
	* @param $extendinfo an array with the extended information.
	* @returns void		
	*/
	public function add_extended($extendinfo)
	{
      $this->width = $extendinfo['width'];
      $this->height = $extendinfo['height'];
	    $this->prewidth = $extendinfo['prewidth'];
	}	

	/**
	* Gets the elementname.
	* @returns void
	* @see pictureshow_upl for futher information.		
	*/		 	
  public function get_elementname(){ return $this->elementname; }


  /**
	* Gets the webpath.
	* @returns void
	* @see pictureshow_upl for futher information.		
	*/		 	
  public function get_webpath(){ return $this->webpath; }

	/**
	* Uploads a new picture for the pictureshow.
	* Uploads a picture without a name yet, the name will be applied by the CCMSFileBase class later and will be returned.
	* @param $fileelement a $_FILES type variable
	* @returns an array with filename, thumbname and description
	*/
	public function upload_pic($fileelement)
	{
		$filename = $this->upload_file($fileelement, $this->path, 1, $this->type, $this->size);
		if($filename != false){
			//if there's a maxsize, scale the pic down
			if($this->width > 0 && $this->height > 0)
				$this->checksize($this->path, $filename, $this->width, $this->height);

			//create the thumb
			$thumbname = $this->create_thumb($this->path, $filename, $this->prewidth);
			return array($filename, $thumbname);
		}else{
			return false;
		}
	}


	/** 
	* Checks the size for a given picture.
	* @param $path the path to the pic
	* @param $filename the filename of the pic
	* @param $maxwidth the maximum width of the pic
	* @param $maxheigth the maximum height of the pic
	* @returns true if the size fits (or it was changed), false if the size can't be changed
	*/
	private function checksize($path, $filename, $maxwidth, $maxheight)
	{
		list($width, $height)=getimagesize($path.$filename); 
		$change = false;

		if($width > $maxwidth){
			$procent = (1/$width)*$maxwidth;
			$width = $maxwidth;
			$height = $height*$procent;
			$change = true;
		}

		if($height > $maxheight){
			$procent = (1/$height)*$maxheight;
			$height = $maxheight;
			$width = $width*$procent;
			$change = true;
		}
		if($change == true){
			if($this->resize_pic($path, $filename, $filename, $width, $height))
				return true;
			else
				return false;
		}else{
			return true;
		}
	}

	/**
	* Resizes a picture to a given size.
	* @param $path the path to the pic
	* @param $filename the filename of the pic
	* @param $newfilename the newfilename of the changed pic
	* @param $newwidth the new width
	* @param $newheight the new height
	* @returns true on success, false on failure
	*/
	private function resize_pic($path, $filename, $newfilename, $newwidth, $newheight)
	{
		list($width, $height, $type) = getimagesize($path.$filename);
		
		// Load
		$thumb = imagecreatetruecolor($newwidth, $newheight);
		
		if($type==1) { 
			// GIF 
			$source=ImageCreateFromGIF($path.$filename); 
		}else{
			$source=ImageCreateFromJPEG($path.$filename); 
		}
		
		// Resize
		imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
		
		// Output
		if(imagejpeg($thumb, $path.$newfilename, 90))
			return true;
		else
			return false;
	}

	

	/**
	* Creates the thumb for a picture.
	* @param $path the path of the pic
	* @param $filename the name of the file to create the thumb from
	* @param $maxwidth the maximum width for the thumb
	* @returns filename on success, false on failure
	*/
	private function create_thumb($path, $filename, $maxwidth)
	{
		$extension = substr(strrchr($filename, "."), 1);
		$file = basename($filename, ".".$extension);
		$newfilename= $file."_pre.".$extension; 
	
		//Erzeugung das Thumbs
		
		list($width, $height) = getimagesize($path.$filename);
		
		$newwidth = $maxwidth;

		while(intval($height*$newwidth/$width) > $maxwidth){
			$newwidth--;
		}
		
		$newheight=intval($height*$newwidth/$width); 
			
	
		if($this->resize_pic($path, $filename, $newfilename, $newwidth, $newheight))
			return $newfilename;
		else
			return false;
	}





	/**
	* Deletes a picture and it's preview.
	* @param $file the picture itself
	* @param $pre the preview
	* @returns void
	*/
	public function delete_picture($file, $pre)
	{

		unlink($this->path.$pre);
		unlink($this->path.$file);
	}





}

?>
