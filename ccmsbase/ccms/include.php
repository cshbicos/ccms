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

/*! \mainpage An Introduction to CCMS
* CCMS is intented to be a content management system that provides a powerfull framework to website developers to easily connect all 
* sorts of datasources with the website and represent that data in a unique way.
*
* <b>Requirements:</b>
*
* PHP5 with XML support.
* MySQL for some modules (e.g login area)
*
* <b>Installation:</b>
* 
* Download the latest tagged version in ccmsbase/tags on websvn.mrman.de or be brave and use the latest trunk version available.
* Unpack it on the webspace. Now you should have two folders, ccms and ccms_custom and a file example.php. 
* Last thing to do is edit ccms/settings.unedited.php and rename it to ccms/settings.php
* At that point your ready to start.
*
* <b>The basics:</b>
*
* Let's go into the very basics of CCMS first.
* As for now there are three general representations of data implemented. 
* Single datasets, tables or lists. Those general objects are called "CCMS Objects".
* Each CCMS Object can have countless elements attached to them. Those elements can be textfields, textareas, 
* select boxes, checkboxes, pictures and whatever you can think of.
*
* Every CCMS Object has two parts. A GUI part that retrieves the data from whatever source you like and "writes" it out to the page * and a serverpart that knows how to save the modified data again.
*
* When a CCMS Object is instantiated a variable is passed that determines wheter it is "editable". If it is not, 
* it willl just be shown but no serverobject is created. This is called showmode.
* If the editable variable is set to true the object will be drawn in a frame with appropriate options (edit, add) attached. It is 
* still drawn in showmode, but you can switch to editmode by clicking edit. And on save you can switch back to showmode. All changes * are made instantly via javascript. This way you can have a preview on how it will look for the user.
*
* To put all the information together in how to use CCMS:
* First you find out what CCMS Object you will use (single, table, list). 
* First you write a XML file with all neccessary information on what elements to use etc.
* Then you extend the GUI object to have a place to load your own data from whatever source php might support. You also have 
* to extend the CCMS Object's serverclass to put the data back.
* You tell the GUI the name of your extended serverobject.
*
* After that the only thing left is to instantiate your object on the page you want it to show up.
* And yeah, don't forget to include ccms/include.php and add the stylesheet in ccms/styles/ccms_generic.css
*
*
* <b>Examples:</b>
*
* ccms_custom comes with example implementations to every CCMS object. Take a look at the code and if you read the section 
* "The basics" you will understand pretty quickly. You might want to have a look at examples.php to see those ccms_custom examples 
* working. 
*/






/** @ingroup functions 
* @file
* main include file.
* This file needs to be included in order to include the WHOLE CCMS.<BR>
* It loads the basic functions.
*/

include('settings.php');
session_start();
include("functions/xhtml.php");
ccms_start_headers();



include(CCMS_BASEPATH.'functions/connect.php');
include(CCMS_BASEPATH.'functions/classloader.php');
include(CCMS_BASEPATH.'functions/functions.php');


?>

