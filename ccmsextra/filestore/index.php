<?php
include('ccms/include.php');

if(!isset($_SESSION['ccms_admin']))
	$_SESSION['ccms_admin'] = 1;
?>

<head>
  <title>CCMS Filestorage</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="stylesheet" href="ccms/styles/ccms_generic.css" />
</head>
<body>


<?php 
$files = new CCMSFileStoreGUI('CCMSFileStore.xml', $_SESSION['ccms_admin']);
$files->write_me();
           
?>

</body>
</html>
