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

/** @ingroup functions
* @file classloader.php
* Classloader.
* loads all ccms classes.<BR>
* @see CCMS_CUSTOM
*/

/**
* Loads class definitions on the fly.
* PHP5 Feature
*/
function __autoload($class_name) {
    set_include_path(get_include_path() . PATH_SEPARATOR . CCMS_BASEPATH."baseclasses/". 
                    PATH_SEPARATOR . CCMS_BASEPATH. "guis/" .
                    PATH_SEPARATOR . CCMS_BASEPATH. "serverclasses/" .
                    PATH_SEPARATOR . CCMS_ELEMENTSPATH. 
                    PATH_SEPARATOR . CCMS_ELEMENTSPATH. "fileobjects/".
                    PATH_SEPARATOR . CCMS_CUSTOM);
    require_once $class_name . '.class.php';
    restore_include_path() ; 
}


?>

