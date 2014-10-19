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

//check if the user is admin at all
if(isset($_SESSION['ccms_admin']) &&$_SESSION['ccms_admin'] != 1)
	exit;

//check if the serverobject for the requested CCMS Object exists
if(!isset($_SESSION['ccms_s_'.$_GET['name']]))
	exit;
else
	//if yes, load it
	$serverobject = unserialize($_SESSION['ccms_s_'.$_GET['name']]);


?>
<head>
  <title>ccms singleobject iframe</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <script src="../javascript/generic.js.php" type="text/javascript"></script>
</head>
<body>

<form method="post" name="ccms_single_form" action="single.php?name=<?php echo $_GET['name'];?>" accept-charset="UTF-8">
	<input type="hidden" value="nuttin" name="submitted" />
	<?php
		//give out the elements in editmode. This way the javascript from the GUI can fill their values before submit
		$serverobject->showme($_POST, false);
	?>
</form>

<?php if(isset($_POST['submitted'])){ 
	//have the custom ccms object do with the data what it wants
	$serverobject->update_me($_POST);
  $serverobject->showme($_POST, true);
	?>
	<script type="text/javascript">
		/* <![CDATA[ */
		<?php 
			//this returns the javascript to get the gui updated
			$serverobject->get_js(); 
		?>
		/* ]]> */
	</script>
<?php } ?>

</body>
</html>
