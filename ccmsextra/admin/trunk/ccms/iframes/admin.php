<?php
include('../settings.php');
include('../functions/connect.php');

session_start();
 
  
if(!isset($_GET['do']))
  $_GET['do'] = 0;


switch($_GET['do']){
  case 1:
    $sql = "INSERT INTO `admins` SET `usrname`='Neuer User', `fullname`='Voller Name'" ;
    $result = mysql_query($sql, $GLOBALS['ccms_db']);
    $_GET['id'] = mysql_insert_id();
    break;
  case 2:
    $sql = "DELETE FROM `admins` WHERE `id`='".$_GET['id']."'";
    $result = mysql_query($sql, $GLOBALS['ccms_db']);
    $_GET['id'] = $_SESSION['ccms_usrid'];
}

$sql = "SELECT `usrname`, `fullname`, `id` FROM `admins` WHERE `id`='".$_GET['id']."'";
$result = mysql_query($sql, $GLOBALS['ccms_db']);

$curvals = mysql_fetch_array($result);

echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>ccms admin</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>

<body>
	<form method="POST" name="ccms_admin_form" action="admin.php?name=<?php echo $_GET['name'];?>">
		<input type="text" value="<?php echo $curvals['id'];?>" name="id" />
		<input type="text" value="<?php echo $curvals['usrname'];?>" name="usrname" />
		<input type="text" value="<?php echo $curvals['fullname'];?>" name="fullname" />
	</form>
	<script type="text/javascript">
		/* <![CDATA[ */
		parentwin = window.parent.document;
		parentwin.getElementsByName("nwusername")[0].value = document.getElementsByName("usrname")[0].value;
		parentwin.getElementsByName("nwfullname")[0].value = document.getElementsByName("fullname")[0].value;
		parentwin.getElementsByName("id")[0].value = document.getElementsByName("id")[0].value;
		<?php if($_GET['do'] == 1){ ?>
		selbox = parentwin.getElementsByName("adminlist")[0];
		NeuerEintrag = new Option('Neuer User', '<?php echo $_GET['id'];?>', true, true);
		selbox.options[selbox.length] = NeuerEintrag;
		<?php } 
		if($_GET['do'] == 2){ ?>
		selbox = parentwin.getElementsByName("adminlist")[0];
		selbox.options[selbox.selectedIndex] = null;
		<?php } ?>
		/* ]]> */	
	</script>


</body>
</html>
