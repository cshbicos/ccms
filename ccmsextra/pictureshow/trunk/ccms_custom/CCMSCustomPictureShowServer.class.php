<?
/**
* Copyright (c) 2006, Christoph Herrmann (theone@csherrmann.com)
*
* All rights reserved.
* Redistribution and use in source and binary forms, with or without
* modification, are permitted provided that the following conditions are met:
*
*     * Redistributions of source code must retain the above copyright
*       notice, this list of conditions and the following disclaimer.
*     * Redistributions in binary form must reproduce the above copyright
*       notice, this list of conditions and the following disclaimer in the
*       documentation and/or other materials provided with the distribution.
*     * Neither the name of the author nor the
*       names of its contributors may be used to endorse or promote products
*       derived from this software without specific prior written permission.
*
* THIS SOFTWARE IS PROVIDED BY THE REGENTS AND CONTRIBUTORS ``AS IS'' AND ANY
* EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
* WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
* DISCLAIMED. IN NO EVENT SHALL THE REGENTS OR CONTRIBUTORS BE LIABLE FOR ANY
* DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
* (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
* LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
* ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
* (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
* SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/


/**
* An example of a Pictureshow server object.
*/

class CCMSCustomPictureShowServer extends CCMSPictureShowServer
{
	
	/**
	* A new pic was inserted, upload it into the database
	* @param $filename the filename of the pic
	* @param $thumbname the thumbfilename
	* @param $desc the text to the pic
	* @returns void
	*/
	public function newpic($filename, $thumbname, $desc)
	{
		if(!isset($_SESSION['ccms_admin']) || $_SESSION['ccms_admin'] != 1)
			return;
		$sql = "INSERT INTO `pics` SET `name`='".$filename."', `preview`='".$thumbname."', `alt`='".$desc."', `date`=NOW(), `album`='".$this->album."', `user`='".$_SESSION['ccms_usrid']."'";		
		$result = mysql_query($sql, $GLOBALS['ccms_db']);
	}


	/**
	* Deletes a pic from the database.
	* @param $file the filename
	* @returns void on success, the mysqlerror on failure
	*/
	public function delete_picture($file, $pre)
	{
		if(!isset($_SESSION['ccms_admin']) || $_SESSION['ccms_admin'] != 1)
			return;
		$sql = "DELETE FROM `pics` WHERE `name` = '".$file."' AND `album`='".$this->album."' LIMIT 1";
		
		if(!mysql_query($sql, $GLOBALS['ccms_db']))
			echo mysql_error();
	}

	/**
	* Alters the description of a picture.
	* @param $newtext the new text of the pic
	* @param $picid the picturename
	* @returns void
	*/
	public function update_alt($newtext, $picid)
	{
		if(!isset($_SESSION['ccms_admin']) || $_SESSION['ccms_admin'] != 1)
			return;

		$sql = "UPDATE `pics` SET `alt` = '".$newtext."' WHERE `name` = '".$picid."' AND `album`='".$this->album."'";
		$result = mysql_query($sql, $GLOBALS['ccms_db']);
	}

	/**
	* Finds the position of a certain picture in the database.
	* This is important to have the show run chronologically.<BR>
	* When found, $this->curnr is set to that value.<BR>
	* This function is called at the opening of the popup, to get everything sorted out.
	* @param $rowid the picture's name (used as the id)
	* @returns void
	*/
	protected function find_pos($rowid)
	{
		$sql = "SELECT `name` FROM `pics` WHERE `album`='".$this->album."' ORDER BY `date`, `name` ASC ";
		$result = mysql_query($sql, $GLOBALS['ccms_db']);
		
		for($i=0;$i<mysql_num_rows($result);$i++){
			$cur = mysql_result($result, $i, 0);
			//echo $i.$rowid.$cur."<BR>";
			if($cur == $rowid){
				$this->curnr = $i;
				break;
			}
		}
	}	

	/**
	* Returns information about a certain picture.
	* Which picture, is determined by $this->curnr
	* @returns an array with all information on the pic
	*/
	protected function get_cur_pic()
	{
		$sql = "SELECT * FROM `pics` WHERE `album`='".$this->album."' ORDER BY `date`, `name` ASC LIMIT ".$this->curnr.",1";
		$result = mysql_query($sql, $GLOBALS['ccms_db']);
			
		$array = mysql_fetch_array($result);
		return $array;
	}	

	/**
	* Returns the number of pictures in the album
	* @returns the number
	*/
	protected function get_max()
	{
		$sql = "SELECT COUNT(`name`) FROM `pics` WHERE `album`='".$this->album."'";
		$result = mysql_query($sql, $GLOBALS['ccms_db']);
		
		return mysql_result($result, 0, 0);
	}
	

}





?>