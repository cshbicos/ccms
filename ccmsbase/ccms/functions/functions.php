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
* @ingroup functions 
* The basic functions that need to be present at all times
* \@{
*/

/**
* @file 
* Some generic functions that can be used anywhere.
*/

/**
* Checks for magic quotes.
* If magic_quotes is enabled, we don't want extra slashes for our quotes. <BR>
* @param $text the text to be slashed
* @returns a quoted string
*
*/

function addslash_check($text){
	if(get_magic_quotes_gpc() == 0){
		return addslashes($text);
	}else{
		return $text;
	}

}


/*\@}*/

?>
