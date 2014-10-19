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




class CCMSPictureShowGUI extends CCMSGenericGUI
{
	/**
	* the real path to the picture (for upload)
	*/
	private $path;
	
	/**
	* the webpath to the pic
	*/
	private $webpath;

	/**
	* the width of the picture (0 for no limit)
	*/
	private $maxwidth;
	
	/**
	* the height of the picture (0 for no limit)
	*/
	private $maxheight;

	/**
	*the mime type of the pic (empty for none)
	*/
	private $type;

	/**
	* the maximum size (0 for no limit)
	*/
	private $size;

	/**
	* Max width of the preview thumbs
	*/ 
	private $preview_width;
	
	/**
	* Cols for previwe pics
	*/
	private $preview_cols;

	/**
	* A stylesheet for the table itself
	*/
	private $tablestyle;

	/**
	* A style for pictures
	*/
	private $picstyle;

	/**
	* The filehandler id of this object
	*/
	private $myid;
	

	/**
	* The contructor.
	* @param $name name of the CCMS Object
	* @param $editmode show or editmode
	* @returns void
	*/
	public function __construct($name, $editmode)
	{
		parent::__construct($name, $editmode);
		$this->get_settings();
	}




	/**
	* Get the settings out of the CCMSPictureShowElement
	* @returns void
	*/
	private function get_settings()
	{
		list ($this->path, $this->webpath, $this->maxwidth, $this->maxheight, $this->type, $this->size, $this->preview_width, $this->preview_cols, $this->tablestyle, $this->picstyle) = $this->elements->get_element(0, "show", 0, 0);
	}

	/**
	* Loads the serverclass into the session.
	* @param $serverclass the serverclass
	* @param $id the album id
	* @returns void
	*/
	protected function create_serverobject($serverclass, $album)
	{
		$serverobject = new $serverclass($album, $this->preview_cols, $this->webpath, $this->picstyle);
		$_SESSION['ccms_s_'.$this->objectname] = serialize($serverobject);
		
	}

	/**
	* Adds some sort of dyntag function (to add paths on the fly etc.)
	* @param $tag the tag to be changed
	* @param $val the value for the element
	* @returns void
	*/
	protected function add_dyntag($tag, $val)
	{
		$this->$tag = $val;
	}



	/**
	* Registers the pictureshow object with the filehandler.
	* @returns the created id 
	*/
	private function register_filehandler()
	{
		$myid = CCMSFileElementsRegister::register_element($this->objectname, "CCMSPictureShowFile", $this->path,$this->webpath, $this->type, $this->size);
		CCMSFileElementsRegister::extend_register($myid, Array("width" => $this->maxwidth, "height" => $this->maxheight, "prewidth"=> $this->preview_width));
		$this->myid = $myid;
		return $myid;
	}


	/**
	* Creates the GUI.	
	* Writes out the table according to the settings in the xml file.<BR>
	* This is the most basic GUI function to the Picture Show Object. <BR>
	* The $vals array is supposed to look like this: <BR>
	* $vals[picnumber][0] = filename <BR>
	* $vals[picnumber][1] = thumbnail <BR>
	* $vals[picnumber][2] = alternative text
	* @param &$vals the values to load the GUI with
	* @returns void
	*/
	protected function create_me(&$vals)
	{
		
		
		if($this->editmode == 1){	
			//includes the JS to delete pics && the iframe to it
			$this->includes(0, "pictureshow.php");
			$this->start_label();
			
			echo "<a class=\"ccms_headeditlink\" href=\"";
			echo "javascript:ccms_open_uplwindow(320, 290, 'pictureshow_upl', ".$this->register_filehandler().")\">";
			echo "Add</a>";
			$this->stop_label_head();
		}
		//the javascript to open the view popup
		$this->includes('pictureshow_show');


		$pictures = count($vals);
		$missing = $pictures%$this->preview_cols;
		$trs = ($pictures-$missing)/$this->preview_cols;
		
		echo "\n\n<table"; 
		if(!empty($this->tablestyle))
			echo " class=\"".$this->tablestyle."\"";
		echo " id=\"ccms_".$this->objectname."_tbl\">\n";	


		for($i=0;$i<$trs;$i++){
			?><tr><?php
			for($j=0;$j<$this->preview_cols;$j++){
				$cur =& $vals[($i*$this->preview_cols+$j)];
				$this->write_pre_td($cur);
			}
			?></tr><?php
		}

		if($missing > 0){
			?><tr><?php
			for($i = 0;$i<$this->preview_cols;$i++){
				if($missing > $i){
					$cur =& $vals[($pictures-$missing+$i)];
					$this->write_pre_td($cur);
				}
			}
			?></tr><?php
		}
		
		echo "\n</table>"; 
		
		if($this->editmode == 1){
			$this->end_label();
		}

	}
 
	/**
	* Creates one cell for the preview table.
	* The array &$cur looks like this:<BR>
	* $cur[0] = filename<BR>
	* $cur[1] = thumbfile<BR>
	* $cur[2] = alt text<BR>
	* @param &$cur the values to insert
	* @returns void
	* @see CCMSPictureShowGUI::create_me()
	*/	
	protected function write_pre_td(&$cur)
	{
		?>
		<td style="text-align:center;" id="ccms_<?php echo $this->objectname;?>_pic_<?php echo $cur[0];?>">
			<a href="javascript:open_showwindow_<?php echo $this->objectname;?>('<?php echo $cur[0];?>');">
				<img src="<?php echo $this->webpath.$cur[1]; ?>" alt="<?php echo htmlspecialchars($cur[2]);?>" title="<?php echo htmlspecialchars($cur[2]);?>"<?php
				if(!empty($this->picstyle))
					echo " class=\"".$this->picstyle."\"";
				?> />
		</a>
		<?php if($this->editmode == 1){ ?>
			<br />
			<a target="ccms_iframe_<?php echo $this->objectname;?>" class="ccms_headeditlink" href="<?php echo CCMS_IFRAMEPATH;?>pictureshow.php?id=<?php echo $this->myid;?>&amp;picname=<?php echo $cur[0];?>&amp;picprename=<?php echo $cur[1];?>" >Delete</a>
		<?php } ?>
		</td>
		<?php	

	}
	
}


?>

