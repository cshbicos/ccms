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


/*! @defgroup settings Basic Settings
*  Basic Settings to get CCMS working
*  @{
*/


/** @file settings.unedited.php
*  This file contains most settings for the CCMS
*  All folders and webpaths are set up here.
*/



/**
* maximum characters for a filename.
* @see CCMSFileBase
*/
define("MAX_NAME_LENGTH", 15);

/**
* path delimiter for the used server platform.
* Be carefull to doubleescape windows backslashes (e.g "\\")
*/
define("PATH_SLASH", "/");

/**
* settings for php lock path.
* if php is restricted on a certain folder and its subfolders, place that folder here<BR>
* this is common setup for commericial webhosting<BR>
* place path with path delimiter at the end (e.g /var/www/localhost/htdocs/)
*/
define("CCMS_PHPLOCKPATH", "/var/www/localhost/htdocs/");


/**
* local path to the ccms/ folder.
* if you don't now that path, check out phpinfo() or ask your provider<BR>
* place path with path delimiter at the end (e.g /var/www/localhost/htdocs/ccms/)
*/
define("CCMS_BASEPATH", "/var/www/localhost/htdocs/ccms/");

/**
* path to the elements folder.
* place path with path delimiter at the end (e.g /var/www/localhost/htdocs/)
*/
define("CCMS_ELEMENTSPATH", CCMS_BASEPATH.'elements/');

/**
* URL to the ccms folder via the webpath.
* place the URL with path delimiter at the end (e.g http://csherrmann.com/ )
*/
define("CCMS_ABSWEBPATH", "http://ccms.mrman.de/ccms/");

/**
* Stylesheet folder.
* Usually you don't need to change this
* place the URL with path delimiter at the end (e.g styles/ )
*/
define("CCMS_CSSPATH", CCMS_ABSWEBPATH."styles/");

/**
* Image folder.
* Usually you don't need to change this
* place the URL with path delimiter at the end (e.g img/ )
*/
define("CCMS_IMGPATH", CCMS_ABSWEBPATH."img/");

/**
* CCMS webpath.
* this needs to be the path to the ccms/ directory from the perspective of the files 
* that will later contain the CCMS Objects
* Place the path with path delimiter at the end (e.g ccms/ )
* in most setups this should be ok
*/
define("CCMS_WEBPATH", "ccms/");

/**
* Iframe folder.
* Usually you don't need to change this
* place the relative path with path delimiter at the end (e.g iframes/ )
*/
define("CCMS_IFRAMEPATH", CCMS_WEBPATH."iframes/");

/**
* Popup folder.
* Usually you don't need to change this
* place the relative path with path delimiter at the end (e.g popups/ )
*/
define("CCMS_POPUPPATH", CCMS_WEBPATH."popups/");

/**
* JavaScript folder.
* Usually you don't need to change this
* place the relative path with path delimiter at the end (e.g javascript/ )
*/
define("CCMS_JSPATH", CCMS_WEBPATH."javascript/");


/**
* Custom CCMS Objects include directory.
* absolute path to the ccms_costum include directory
* place the full local path
*/
define("CCMS_CUSTOM", CCMS_PHPLOCKPATH."ccms_custom/");

/**
* DOM Path to iframes.
* specify the path to an iframe. If you do not use framesets, keep as it is.
*/
define("CCMS_IFRAME_DOMPATH", "top");


/**
* Define XHTML use.
* What usage of XHTML to use?
* 0 = transitional
* 1 = strict
* @see xhtml.php
*/

define("CCMS_XHTML_USE", "0");


/*! @} */

/** @defgroup mysqlsettings Database Settings 
*  @ingroup settings 
*  Database Settings.
*  if you want to use a mysql database, this is for convenience to connect to it<BR>
*  If you want to use the default admin area though, you will need to set it up.
*  @see connect.php
*/

/** @ingroup mysqlsettings 
* Database User.
* Username for the Database
*/
define("CCMS_DBUSER", "");

/** @ingroup mysqlsettings 
* Database Name.
* Name of the Database to use
*/
define("CCMS_DB", "");

/** @ingroup mysqlsettings 
* Database Pwd.
* Password for the database
*/
define("CCMS_DBPWD", "");

/** @ingroup mysqlsettings 
* Database Host.
* Host for the Database
*/
define("CCMS_DBHOST", "");




?>
