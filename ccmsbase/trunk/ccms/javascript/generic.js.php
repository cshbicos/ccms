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
<?php
include('../settings.php');
?>

/**
* Opens the upload window.
* This is part of the generic javascripts, because every element will use this function,<BR>
* so no sense on including it for every element
* @param width the width of the window
* @param height the height of the window
* @param file what window in CCMS_POPUPPATH
* @param id the id for the requested fileregister
* @returns void
*/

function ccms_open_uplwindow(width, height, file, id)
{


	Fenster1 = window.open("<?php echo CCMS_POPUPPATH;?>" + file + ".php?id=" + id, "Fileupload" + file , 
	"width=" + width + ",height=" + height + ",left=100,top=200,dependent=yes,status=no,location=no,menubar=no,toolbar=no,resizable=no,scrollbars=yes");
	Fenster1.focus();


}


/**
* Opens the datepicker window.
* This is part of the generic javascripts, because every dateelement will use this function,<BR>
* so no sense on including it for every element.
* @param box the elementname of the box that opened it
* @returns void
*/

function ccms_datepicker(box)
{
	curval = document.getElementsByName(box)[0].value;
	Fenster1 = window.open("<?php echo CCMS_POPUPPATH;?>datepicker.php?name="+box+"&startdate="+curval, "datepick" + box,
	"width=440,height=240,left=100,top=200,dependent=yes,status=no,location=no,scrollbars=no,menubar=no,toolbar=no,resizable=no");
	Fenster1.focus();
}


/**
* Copies certain values from element to element.
* This is used for the back and forth copying of values between iframes and main windows
* @param from the source element
* @param to the destination element
* @returns void
*/
function ccms_copy(from, to)
{
    switch(from.type){
      case "checkbox":
        to.checked = from.checked;break;
      default:
        to.value = from.value;break;
    }
}	
