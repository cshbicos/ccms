<?php
include('../settings.php');
?>
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
 
function open_showwindow_<?php echo $_GET['name'];?>(file)
{

	Fenster1 = window.open("<?php echo CCMS_POPUPPATH;?>pictureshow_show.php?name=<?php echo $_GET['name'];?>&pic=" + file, "picshow", 
	"width=700,height=600,left=100,top=200,dependent=yes,status=no,location=no,scrollbars=yes,menubar=no,toolbar=no,resizable=no");
	Fenster1.focus();

}
