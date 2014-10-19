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
* Writes headers depending on CCMS_XHTML_USE.
* This function will also write the appropriate &lt;html&gt; tags after all headers are send<br>
* Do not fuck with
* @returns void
* @see settings.php, include.php
*/
function ccms_start_headers()
{
	

	header("Content-Type: text/html;charset=UTF-8");
	header("Vary: Accept");
	header("Cache-Control: no-cache, must-revalidate"); 
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
	switch(CCMS_XHTML_USE)
	{
		case 1:
			echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
			break;
		default:
		case 0:
			header("Content-Type: text/html;charset=UTF-8");
			echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
	}
}	

function write_head_tags($admin)
{
   ?> 
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <?php
   if($admin == 1){
      ?>   
      <link rel="stylesheet" href="ccms/styles/ccms_generic.css" />   
      <script src="ccms/javascript/generic.js.php" type="text/javascript"></script>
      <?php
   }
}

?>
