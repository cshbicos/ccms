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

/** @defgroup Filesupport Support for fileelements
*  @ingroup CCMSElements
*  Some helpfull fileupload helping classes.
* @{
*/

/**
* @file CCMSFileBase.class.php
* Basic fileoperations, especially focused on upload methods
*/

/**
* Basic file functions for elements handeling files.
* There usually is an instance of the CCMSFileBase Object in $_SESSION['ccms_filebase']<BR>
* Every element that wants to use fileuploads must register with CCMSFileBase::register_element();<BR>
* It will recieve an ID with which future uploads can be done.<BR>
* It covers 3 different ways to upload files
* - keep the filename of the original file
* - use a unique name and overwrite the already existing file
* - use a base name and attach a number, in case a file exists already
*/

class CCMSFileBase extends CCMSErrorHandler
{
	
	/**
	* runnumber setting.
	* how many digits should the CCMS_FILEBASE_RUN_NUMBER contain?
	* @see CCMS_FILEBASE_RUN_NUMBER
	*/	
	private $runnumber_decimals = 4;
	
	/**
	* Use only one filename, overwrite if neccessary.
	* The file will always be named the same way and therefore will replace the previous one.
	*/
	const CCMS_FILEBASE_FILENAME = 0;

	/**
	* Constant to use a filename and append different numbers.
	* files will be named a certain way, and in case there is a file already, a number will be added.<BR>
	* e.g filename.txt , filename01.txt, filename02.txt ....
	*/
	const CCMS_FILEBASE_RUN_NUMBER = 1;

	/**
	* Use original filename constant.
	* constant if one wants to use the filename=original filename method for uploads
	*/
	const CCMS_FILEBASE_UNIQUE_FILENAME = 2;

	/**
	* Name of the object
	*/
	protected $elementname;
	
	/**
	* Real path to the upload dir
	*/
	protected $path;
	
	/**
	* Webpath to the picdir
	*/
	protected $webpath;

	/**
	* Used for type restriction
	*/
	protected $type="";

	/**
	* Used for size restriction
	*/
	protected $size=0;
	
	
	/**
	* Gets the type limitation.
	* @returns the type limitation
	*/
	public function get_type() { return $this->type; }

	/**
	* Gets the size limitation.
	* @returns the size limitation
	*/
	public function get_size() { return $this->size; }

	/**
	* The constructor.
	* Takes the fileelement registration information from $_SESSION['ccms_fileregister'] and "uses" it.
	* @param $elementname the name of the element
	* @param $path the path to the file
	* @param $webpath the webpath to the file
	* @param $type type restriction of the file to be uploaded
	* @param $size size restriction on the file to be uploaded
	* @returns void
	*/
	public function __construct($elementname,$path,$webpath,$type,$size)
	{
		$this->elementname = $elementname;
		$this->path = $path;
		$this->webpath = $webpath;
		$this->type = $type;
		$this->size = $size;
	}	



	/**
	* rename a file.
	* uses basic error checks.
	* @param $old old path.filename
	* @param $new new path.filename
	* @returns void
	*/

	public function rename_file($old, $new)
	{
		if(!@rename($old, $new))
			$this->report_error('$old konnte nicht in $new geaendert werden');
	}

	/**
	* deletes a file.
	* uses basic error checks.
	* @param $file path.filename
	* @returns void
	*/
	public function del_file($file)
	{
		if(!@unlink($file))
			$this->report_error('Die Datei $file konnte nicht geloescht werden');
	}


	/**
	* Uploads a file.
	* This is the function you should call to upload a file
	* @param $fileelement a $_FILE boxarray usually
	* @param $path uploadpath
	* @param $nametype naming conventision (see options below), default CCMS_FILEBASE_UNIQUE_FILENAME
	* @param $reqtype required filetype, default all allowed
	* @param $reqsize maximum filesize, default none
	* @returns false on failure, the new filename on success
	* @see CCMS_FILEBASE_FILENAME, CCMS_FILEBASE_UNIQUE_FILENAME, CCMS_FILEBASE_RUN_NUMBER
	*/
	
	public function upload_file($fileelement, $path, $nametype=2, $reqtype="", $reqsize=0)
	{
		//checks for any obvious error
		if(!$this->error_check($fileelement['error'], $fileelement['type'], $fileelement['size'], $reqtype, $reqsize))
			//quit on error
			return false;
		
		//check the path and create it if needed
		$path = $this->mkdir_r($path);
		
		//path is correct, no errors => get a filename
		$filename = $this->get_filename($path, $fileelement['name'], $nametype);
		$uploadfile = $path . $filename;
			
		if (move_uploaded_file($fileelement['tmp_name'], $uploadfile)){
			return $filename;
		}else{
			$this->report_error('Irgendwas am Upload ist falsch gelaufen '.$fileelement['tmp_name']);
			return false;
		}
	}

	/**
	* Make a directory (recursive).
	* If a file is uploaded, check if the folder exists and create it if there's need
	* @param $dirName path to the folder (including the folder)
	* @param $rights mods for the folder (777 default)
	* @returns existant path to the folder
	*/
	private function mkdir_r($dirName, $rights=0777){
		//prevent php lockpath errors
		$dirremain = str_replace(CCMS_PHPLOCKPATH, "", $dirName);
		
		
		$dirs = explode(PATH_SLASH, $dirremain);
		$dir=CCMS_PHPLOCKPATH;
		//loop through all folders till the final path is reached
		foreach ($dirs as $part) {
			if($part == CCMS_PHPLOCKPATH || empty($part))
				continue;

			$dir.=$part.PATH_SLASH;
			if (!is_dir($dir) && strlen($dir)>0)
				if(!@mkdir($dir, $rights))
					$this->report_error("Der Ordner \"$dir\" konnte nicht erstellt werden");
		}
		return $dir;
	}
	
	/**
	* Find a filename based on $nametype.
	* Finds a filename, based on the $nametype part
	* @param $path path to the file
	* @param $filename the filename, uncorrected yet
	* @param $nametype the nameing conventsion for the filename
	* @returns a modified and corrected filename
	* @see CCMS_FILEBASE_FILENAME, CCMS_FILEBASE_UNIQUE_FILENAME, CCMS_FILEBASE_RUN_NUMBER
	*/

	private function get_filename($path, $filename, $nametype)
	{
		switch($nametype){
			case self::CCMS_FILEBASE_RUN_NUMBER:
				return $this->find_runnumber($path, $filename);
				break;
			case self::CCMS_FILEBASE_FILENAME:
				return $this->correct_filename($filename);
				break;
			default:
			case self::CCMS_FILEBASE_UNIQUE_FILENAME:
				return $this->find_unused_filename($path, $filename);
				break;
		}
	}

	/**
	* Find unused filename.
	* Original filename is used, no overwrite though, so a number is attached if needed
	* @param $path path to the file
	* @param $filename original filename
	* @returns an original filename
	* @see CCMS_FILEBASE_UNIQUE_FILENAME
	*/
	
	private function find_unused_filename($path, $filename)
	{
		$extension = substr(strrchr($filename, "."), 1);
		$extension = $this->check_extension($extension);
		$file = basename($filename, ".".$extension);
		$file = $this->urlencode_nice($file);
		
		$i=0;
		$finalfile = $file.".".$extension;
		while (file_exists($path.$finalfile)){
			$finalfile = $file."_".$i.".".$extension;
			$i++;
		}
		return $finalfile;

	}
	
	/**
	* Write as a given filename.
	* Whatever the filename was, it is now changed to a given one.<BR>
	* If a file of that name existed already, overwrite it.
	* @param $filename path.filename for the new file
	* @returns the filename, corrected and checked
	* @see CCMS_FILEBASE_FILENAME
	*/
	private function correct_filename($filename)
	{
		$extension = substr(strrchr($filename, "."), 1);
		$extension = $this->check_extension($extension);
		$file = basename($filename, ".".$extension);
		$file = $this->urlencode_nice($file);
		return $file.".".$extension;
	}


	/**
	* Writes the file with a running number.
	* Whatever the filename was, it is now changed to a given one.<BR>
	* If a file of that name existed a number is appended.<BR>
	* No overwriting takes place
	* @param $path path
	* @param $filename filename (nameprefix.ext)
	* @returns the filename, corrected and checked
	* @see CCMS_FILEBASE_RUN_NUMBER
	*/
	private function find_runnumber($path, $filename)
	{
		$extension = substr(strrchr($filename, "."), 1);
		$extension = $this->check_extension($extension);
		
		$prefix = substr($filename, 0, (strlen($filename)-strlen($extension)-1));

		$i=0;
		$finalfile = $prefix.$this->fill_zeros($i).".".$extension;
		while (file_exists($path.$finalfile)){
			$i++;
			$finalfile = $prefix.$this->fill_zeros($i).".".$extension;	
		}
		return $finalfile;
	}

	/**
	* Deletes non english characters.
	* Deletes non english characters with html equivalents, except some known replacements
	* It also limits the filename length
	* @param $name the filename without path
	* @returns a correct filename 
	* @see MAX_NAME_LENGTH
	*/
	private function urlencode_nice($name)
	{
		$end = substr($name, 0, MAX_NAME_LENGTH);
		$end = rawurlencode($end);

		$urlencodes_german = array("%E4", "%FC", "%F6", "%DC", "%D6", "%C4", "%DF", "%20");
		$replaces_german  = array("ae", "ue", "oe", "Ue", "Oe", "Ae", "ss", "_");

		$end = str_replace($urlencodes_german, $replaces_german, $end);
		return $end;
	}

	/**
	* fills zeros.
	* If the number was 5, and CCMSFileBase::$runnumber_decimals is set to 4, the result would be 0005
	* @param $number an interger value
	* @returns a string with trailing zeros
	* @see CCMSFileBase::$runnumber_decimals
	*/
	
	private function fill_zeros($number)
	{
		$result = $number;
		for($i=0;$i<($this->runnumber_decimals-strlen($number));$i++)
			$result = "0".$result;

		return $result;
	}

	/**
	* Checks extensions.
	* Extensions, such as .php can not be allowed for upload (security)<BR>
	* This function replaces them
	* @param $extension the extension to check
	* @returns a "save" extension
	*/
	private function check_extension($extension)
	{
		if($extension == "php" || $extension == "php3")
			$extension = "nop";
		return $extension;
	}



	/**
	* checks for some common upload errors.
	* It checks for mismatch of filetypes, size violations and for all kind of $_FILE errors
	* If the error can be pinned down, an errormessage via CCMSErrorHandler::report_error() is send
	* @param $errormessage given by the $_FILE['error']
	* @param $type actual type
	* @param $size actual size
	* @param $reqtype required type (will be checked for CCMSFileBase::$filetype as well)
	* @param $reqsize required size (will be checked for CCMSFileBase::$filesize as well)
	* @returns true on no error, false on error
	*/
	private function error_check($errormessage, $type, $size, $reqtype, $reqsize)
	{	

		$error = '';
		
		if(empty($reqtype)){
			if(!empty($this->filetype) && $this->filetype != $type){
				$error = 'Falscher Dateityp';}
		}else{
			if($reqtype != $type){
				$error = 'Falscher Dateityp';}
		}

		if(empty($reqsize)){
			if(!empty($this->maxsize) && $this->maxsize != $type){
				$error = 'Falscher Dateityp';}
		}else{
			if($reqsize != $type){
				$error = 'Falscher Dateityp';}
		}


		switch ($errormessage) {
			case UPLOAD_ERR_OK:
				break;
			case UPLOAD_ERR_INI_SIZE:
				$error = "The uploaded file exceeds the upload_max_filesize 
					directive (".ini_get("upload_max_filesize").") in php.ini.";
				break;
			case UPLOAD_ERR_FORM_SIZE:
				$error = "Die Datei war groesser als erlaubt.";
				break;
			case UPLOAD_ERR_PARTIAL:
				$error = "The uploaded file was only partially uploaded.";
				break;
			case UPLOAD_ERR_NO_FILE:
				$error = "No file was uploaded.";
				break;
			case UPLOAD_ERR_NO_TMP_DIR:
				$error = "Missing a temporary folder.";
				break;
			case UPLOAD_ERR_CANT_WRITE:
				$error = "Failed to write file to disk";
				break;
			default:
				$error = "Unknown File Error";
		}
		
		if(!empty($error)){
			$this->report_error($error);
			return false;
		}else{
			return true;
		}
	}

}
/*! @} */
?>
