<?php
$pathtoccms = "ccms/";


include($pathtoccms.'settings.php');
include($pathtoccms.'functions/connect.php');
include($pathtoccms.'functions/functions.php');

session_start();

if(!isset($_SESSION['ccms_admin']))
	$_SESSION['ccms_admin'] = 0;

if(isset($_POST['userloggedin'])){
	$sql = "SELECT * FROM `admins` WHERE `usrname`='".addslash_check($_POST['username'])."' AND `password`=SHA1('".strtolower(addslash_check($_POST['password']))."')";
	$result = mysql_query($sql, $GLOBALS['ccms_db']);
	//login erfolgreich
	if(mysql_num_rows($result) == 1){
		$userdata = mysql_fetch_array($result);
		
		$_SESSION['ccms_admin'] = 1;
		$_SESSION['ccms_usrid'] = $userdata['id'];	
		$_SESSION['ccms_usrname'] = $userdata['usrname'];
		$_SESSION['ccms_fullname'] = $userdata['fullname'];
	}else{
		$error = "Login falsch";
	}
}

if(isset($_GET['do']) && $_GET['do'] == 0){
	session_destroy();
	unset($_SESSION);
	session_start();
	$_SESSION['ccms_admin'] = 0;
}

if($_SESSION['ccms_admin'] == 1){
  unset($sql);
	if(!isset($_POST['id']))
	   $_POST['id'] = $_SESSION['ccms_usrid'];
  
  	
	if(isset($_POST['changename'])){
		$sql = "UPDATE `admins` SET `usrname`='".addslash_check($_POST['nwusername'])."', 
					`fullname`='".addslash_check($_POST['nwfullname'])."' 
					WHERE `id`='".$_POST['id']."'";
		$id=0;
	}

	if(isset($_POST['nwchangepwd']) && !empty($_POST['nwpassword'])){
		$sql = "UPDATE `admins` SET `password`=SHA1('".strtolower(addslash_check($_POST['nwpassword']))."')
					WHERE `id`='".$_POST['id']."'";
		$id=1;
	}

	if(isset($sql)){
		if(!mysql_query($sql, $GLOBALS['ccms_db'])){
			echo mysql_error()."<BR><BR>".$sql;
		}else{
			if($id==0 && $_POST['id']==$_SESSION['ccms_usrid']){
				$_SESSION['ccms_usrname'] = $_POST['nwusername'];
				$_SESSION['ccms_fullname'] = $_POST['nwfullname'];
			}
		}	
	}

}

echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	
<head>
	<title>CCMS Admin</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="<?php echo $pathtoccms;?>styles/ccms_admin.css" />

<?php if($_SESSION['ccms_admin']==1){ ?>
	<script type="text/javascript">
		/* <![CDATA[ */
		
			function switch_user(userid)
			{
				var newurl = '<?php echo CCMS_IFRAMEPATH;?>admin.php?do=0&id='+userid;
				go_to(newurl);
			}
			
			function add_user()
			{
				var newurl = '<?php echo CCMS_IFRAMEPATH;?>admin.php?do=1';
				go_to(newurl);
			}
			
			function go_to(newurl){
				top.ccms_iframe_admin.location.href = newurl;
			}
			
			function del_user(userid)
			{
				var newurl = '<?php echo CCMS_IFRAMEPATH;?>admin.php?do=2&id='+userid;
				go_to(newurl);
			}
		
		/* ]]> */
	</script>
<?php } ?>

</head>
<body>

<?php
if($_SESSION['ccms_admin']==1){
?>
	<form action="admin.php" method="POST">
		<table id="logintbl" border="1">
			<tr>
				<td colspan="3" id="logindesc">
					<a href="index.php">Zur Hauptwebsite</a><br />
					<a href="admin.php?do=0">Logout</a><br /><br />
					User ver&auml;ndern:
				</td>
			</tr>
			<tr>
	   			<td class="righttext" rowspan="5">
	      				<input type="hidden" name="id" />
        				<select size="10" name="adminlist" id="adminlist" onchange="switch_user(this.value)">
						<?php
						$sql = "SELECT `usrname`, `id` FROM `admins`";
						$result = mysql_query($sql, $GLOBALS['ccms_db']);
					
						for($i=0;$i<mysql_num_rows($result);$i++){
						$curuser = mysql_fetch_array($result);
						echo  "<option value=\"".$curuser['id']."\"";
						if($curuser['id'] == $_POST['id'])
							echo " selected=\"selected\"";
						echo ">".$curuser['usrname']."</option>";
						}
						
						?>
						
					</select><br />
			
					<input type="button" value="Delete" onClick="del_user(document.getElementsByName('adminlist')[0].value)" />
					<input type="button" value="New" onClick="add_user()" />  
					
        				<iframe name="ccms_iframe_admin" id="ccms_iframe_admin" class="hiddeniframe" src="<?php echo CCMS_IFRAMEPATH;?>admin.php?do=0&amp;id=<?php echo $_POST['id']?>"></iframe>
    				</td>
				<td class="righttext">Username :</td>
				<td class="lefttext"><input type="text" name="nwusername" /></td>
			</tr>
			<tr>
				<td class="righttext">Voller Name :</td>
				<td class="lefttext"><input type="text" name="nwfullname" /></td>
			</tr>
			<tr>
				<td class="righttext" colspan="2"><input type="submit" name="changename" value="&Auml;ndern" /></td>
			</tr>
			<tr>
				<td class="righttext">Passwort:</td>
				<td class="lefttext"><input type="password" name="nwpassword" value="" /></td>
			</tr>
			<tr>
				<td class="righttext" colspan="2"><input type="submit" name="nwchangepwd" value="&Auml;ndern" /></td>	
			</tr>
		</table>
	</form>
<?php }else{ ?>
	<form action="admin.php" method="POST">
		<table id="logintbl" border="0">
			<tr>
				<td colspan="2" id="logindesc">
					Bitte hier einloggen um CCMS auf ihrer Seite nutzen zu k&ouml;nnen.<br /><br />
					<a href="index.php">Zur Hauptwebsite</a><br />
				</td>
				
			</tr>
			<?php
			if(isset($error)){
				?>
				<tr>
					<td colspan="2" id="loginerror">
						<?php echo $error;?>
					</td>
						
				</tr>
			<?php } ?>
			<tr>
				<td class="righttext">Username :</td>
				<td class="lefttext"><input type="text" name="username" /></td>
			</tr>
			<tr>
				<td class="righttext">Passwort:</td>
				<td class="lefttext"><input type="password" name="password" /></td>
			</tr>
			<tr>
				<td class="righttext" colspan="2"><input type="submit" name="userloggedin" value="Login" /></td>	
			</tr>
		</table>
	</form>
<?php } ?>	
</body>
</html>
