<?

include('ccms/include.php');
$_SESSION['ccms_admin'] = 1;
?>
<head>
  <title>CCMS Pictureshow Sample File</title>
  <?php write_head_tags($_SESSION['ccms_admin']); ?>
  

</head>
<body>

<?php

$album = 1;
$pics = new CCMSCustomPictureShow('CCMSPictureShow.xml', 1);
$pics->write_me($album);

?>
</body>
</html>
