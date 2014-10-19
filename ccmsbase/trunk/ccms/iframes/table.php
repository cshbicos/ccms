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

if(isset($_SESSION['ccms_admin']) &&$_SESSION['ccms_admin'] != 1)
	exit;

if(!isset($_SESSION['ccms_s_'.$_GET['name']]))
	exit;
else
	$serverobject = unserialize($_SESSION['ccms_s_'.$_GET['name']]);


?>
<head>
  <title>ccms table iframe</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <script src="../javascript/generic.js.php" type="text/javascript"></script>
  <?php

	/**
	* Initiate the deleting sequenze
	*/
	if(isset($_GET['del']) && is_numeric($_GET['del'])){
	
		/**
		* Delete the "real" data via the customly provided del_me interface.
		* The databaseid is obtained via CCMSTableServer::get_rowid().
		*/
		$serverobject->del_me($serverobject->get_dbid($_GET['del']));
	
		/**
		* Delete the rowid since it doesn't exist anymore
		*/
		$serverobject->del_rowid($_GET['del']);
	
		/**
		* Print the delete javascript
		*/
		$serverobject->get_deljs($_GET['del']);
	}

  ?>

</head>
<body>

<?php
	/**
	* Adds a new entry to the table
	*/
	if(isset($_GET['new']) && is_numeric($_GET['new'])){
		/**
		* Set the $tmp_newrowid variable
		* @see CCMSTableServer::$tmp_newrowid for further information
		*/
		$serverobject->set_tmp_newrowid($_GET['new']);

		/**
		* Adds a new standard row via the CustomObject call and returns the new databaseid
		*/
		$newid = $serverobject->add_new();
		
		/**
		* Adds the databaseid to the serverobject
		*/	
		$serverobject->add_rowid($_GET['new'], $newid);

		/**
		* Prints the rowadding javascript
		*/
		$serverobject->get_addjs($_GET['new']);
	
		$_POST['rowid'] = $_GET['new'];
		
	}
?>

<form method="POST" name="ccms_table_form" action="table.php?name=<?php echo $_GET['name'];?>" accept-charset="UTF-8">
	<?php
		if(!isset($_POST['rowid']))
			$_POST['rowid'] = false;
	?>
	<input type="text" value="<?php echo $_POST['rowid'];?>" name="rowid" />
	<?php
	/**
	* Shows all "real" objects without structure 
	*/
	$serverobject->showme(false, $_POST, $_POST['rowid']);
	
	/**
	* Del rowid if used for the "new" entry
	*/
	if(isset($_GET['new']))
		$_POST['rowid'] = false;
	?>
</form>


<?php
/**
* If a value was submitted (rowid is set) update everything
*/
 if(isset($_POST['rowid']) && $_POST['rowid'] !== false && is_numeric($_POST['rowid'])){ 

	/**
	* Get the databaseid for the rowid to have the custom object work with it
	*/
	$_POST['id'] = $serverobject->get_dbid($_POST['rowid']);

	/**
	* Update the datasource via Custom Object
	*/
	$serverobject->update_me($_POST);

	/**
	* Show the newly created values in showmode as divs, so they can be copied to the parent window
	*/
	$serverobject->showme(true, $_POST, $_POST['rowid']);
	
?>
	<script type="text/javascript">
		/* <![CDATA[ */
		<?php 
		/**
		* Prints the javascript to put all values back, show and editmode
		*/	
		$serverobject->get_editjs($_POST['rowid']); 

		/**
		* If moverow(move row after insert and update) is used, 
		*/
		if($serverobject->uses_moverow()){
			list($movepoint, $movetype) = $serverobject->moverow($_POST);
			$serverobject->get_movejs($_POST['rowid'], $movepoint, $movetype);
		}
		?>
		/* ]]> */
			
	</script>
<?php } 
$_SESSION['ccms_s_'.$_GET['name']] = serialize($serverobject);
?>

</body>
</html>
