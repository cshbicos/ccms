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

/*! @defgroup baseclasses Baseclasses
*  The baseclasses which provide basic functionality for the CCMS Objects
*  @{
*/


/** @file CCMSErrorHandler.class.php
*  Error Handling class
*/




/** The serverside error handling class.
*   In fact this handles nothing more then giving out a javascript warning of a defined kind
*   <BR>Maybe to be extended in PHP5
*   <BR>This is the highest class in the CCMS hierarchy
*/

class CCMSErrorHandler
{
       /**
       * reports an error via JS alert().
       * @param $error a string.
       * @return void
       */
	function report_error($error)
	{	
		?>alert('<?php echo $error;?>');<?php
	}


}
/*! @} */
?>
