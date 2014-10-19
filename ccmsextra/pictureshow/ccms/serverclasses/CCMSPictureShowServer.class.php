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
* The serverside object for the pictureshow
*/

abstract class CCMSPictureShowServer
{
	/**
	* The id of the album
	*/
	protected $album;
	
	/**
	* The current shown picture nr.
	*/
	protected $curnr;
	
	/**
	* How many cells per row
	*/
	protected $precols;
	
	/**
	* The webpath
	*/
	protected $webpath;

  /*
  * The css class of the picture.
  */
  protected $picstyle;
  	  
  /**
  * So the database won't be queried for the total amount of pics every time...
  */       
  protected $maxpics_nr;

  /**
  * On 0 reload maxnumber of pics;
  */  
  protected $maxcounter = 0;

  /**
  * How many requests to the refresh of maxnumbers?
  */ 
  const REFRESDELAY = 10;


	/**
	* The constructor.
	* @param $album the albumid to use
	* @param $precols how many cols in one row
	* @param $webpath webpath of the pics
	* @param $picstyle the css class of the picture  	
	* @returns void
	*/
	function __construct($album, $precols, $webpath, $picstyle){
		$this->album = $album;
		$this->curnr = 0;
		$this->precols = $precols;
		$this->webpath = $webpath;
		$this->picstyle = $picstyle;
	}
  
  /**
  * Get the pictstyle
  * @returns the $picstyle variable  
  */    
  public function get_picstyle(){ return $this->picstyle; }

	/**
	* Get the precol value.
	* @returns the $precols variable
	*/
	public function get_precols() 
	{ 
		return $this->precols;
	}

	/**
	* Get the webpath
	* @returns the webpath
	*/
	public function get_webpath()
	{
		return $this->webpath;
	}
	
	
	/**
	* Abstract functions that need to be defined by the custom object
	* @{
	*/
	abstract protected function get_cur_pic();
	abstract protected function find_pos($rownr);
	abstract protected function get_max();
	/*! @} */


	/**
	* Sets up the popup after opening.
	* $this->curnr is set to the current value,which is important for the forward and backward functionallity
	* @param $rownr the name of the pic used as an id
	*/
	public function get_cur($rownr)
	{
		if(empty($rownr))
			$this->curnr = 0;
		else
			$this->find_pos($rownr);
	
		return $this->get_cur_pic();
		}

	/**
	* The "back" link was used, find the previous pic.
	* @returns information about the pic
	*/
	public function get_previous()
	{
		$this->curnr--;
		if($this->curnr < 0)
			$this->curnr = $this->check_max()-1;
			
		return $this->get_cur_pic();	
	}

	/**
	* The "forward" link was used, find the next pic.
	* @returns information about the pic
	*/
	public function get_next()
	{
		$this->curnr++;
		if($this->curnr >= $this->check_max())
			$this->curnr = 0;
		return $this->get_cur_pic();
	}

  /**
  * The maxpic puffer function.
  * Reloads the maxpicture number after a given number of requests.    
  * @see CCMSPictureShowServer::REFRESDELAY,  CCMSPictureShowServer::$maxcounter, 
  * CCMSPictureShowServer::$maxpics_nr
  *  
  */       
  private function check_max()
  {
     $this->maxcounter--;
     if($this->maxcounter <= 0){
        $this->maxpics_nr = $this->get_max();
        $this->maxcounter = self::REFRESDELAY;
     }
     return $this->maxpics_nr;
     
  }


}

?>
