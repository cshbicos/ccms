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
* @defgroup functions Basic functions
* The basic functions that need to be present at all times
*/

/** @ingroup functions
* @file connect.php
* Connects to database.
* This file is used to connect to the database
* <BR>this file will return the global variable $GLOBALS['ccms_db'], which is the connection to the database
* @see settings.php
*/
if(CCMS_DBHOST!="" && CCMS_DB!="" && !isset($GLOBALS['ccms_db']))
{
	$GLOBALS['ccms_db'] = mysql_connect(CCMS_DBHOST, CCMS_DBUSER, CCMS_DBPWD);

	if (!$GLOBALS['ccms_db']) {
	die('Could not connect: ' . mysql_error());
	}
	mysql_select_db(CCMS_DB, $GLOBALS['ccms_db']);
}

?>
