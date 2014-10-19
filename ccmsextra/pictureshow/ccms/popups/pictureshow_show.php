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
 
include('../include.php');

if(!isset($_SESSION['ccms_s_'.$_GET['name']]))
	exit;
else
	$serverobject = unserialize($_SESSION['ccms_s_'.$_GET['name']]);


if(isset($_POST['savealt']) && $_SESSION['ccms_admin'] == 1)
	$serverobject->update_alt($_POST['alttext'], $_GET['pic']);




?>


<head>
  <title>CCMS Picture Show</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="stylesheet" href="<?php echo CCMS_CSSPATH;?>ccms_picshow.css" />
</head>
<body>

<?php
if(!isset($_GET['pic']))
	$_GET['dir'] = 0;


switch($_GET['dir']){
	case 1:
		$cur_pic = $serverobject->get_next();
		break;
	case 2:
		$cur_pic = $serverobject->get_previous();
		break;
	default:
		$cur_pic = $serverobject->get_cur($_GET['pic']);
		break;
}


if($_SESSION['ccms_admin'] == 1){ ?>
<form method="POST" name="ccms_picshow_form" action="pictureshow_show.php?name=<?php echo $_GET['name'];?>&amp;pic=<?php echo $cur_pic['name'];?>">
<?php } ?>

<table border="0" id="ccms_picshow_show">
	<tr>
		<td colspan="2"><img src="<?php echo $serverobject->get_webpath().$cur_pic['name'];?>" border="0" alt="<?php echo htmlspecialchars($cur_pic['alt']);?>" title="<?php echo htmlspecialchars($cur_pic['alt']);?>" class="ccms_showpic" /></td>
	</tr>
	<tr>
		<td class="ccms_show_photographer">Fotograf: <?php echo $cur_pic['user'];?></td>
		<td class="ccms_show_time"><?php echo date("d.m.Y H:i:s", strtotime($cur_pic['date']));?></td>
	</tr>
	<tr>
		<td colspan="2" class="ccms_show_desc">
		<?php
			if($_SESSION['ccms_admin'] == 1){
				?><textarea name="alttext" id="ccms_descbox"><?php echo $cur_pic['alt'];?></textarea><?php
			}else{
				echo $cur_pic['alt'];
			}
		?>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="ccms_saverow">
			<?php
			if($_SESSION['ccms_admin'] == 1){
				?><input type="submit" name="savealt" value="Speichern" id="ccms_savebutton" /><?php
			}
			?>
		</td>
	</tr>
	<tr>
		<td id="td#ccms_goback">
			<a href="pictureshow_show.php?name=<?php echo $_GET['name'];?>&amp;dir=2&amp;pic=<?php echo $cur_pic['name'];?>" class="ccms_showlink">&lt;&lt;</a>
		</td>
		<td id="ccms_goforward">
			<a href="pictureshow_show.php?name=<?php echo $_GET['name'];?>&amp;dir=1&amp;pic=<?php echo $cur_pic['name'];?>" class="ccms_showlink">&gt;&gt;</a>
		</td>
	</tr>
</table>

<?php if($_SESSION['ccms_admin'] == 1){ ?>
</form>
<?php } 
$_SESSION['ccms_s_'.$_GET['name']] = serialize($serverobject);
?>


</body>
</html>
