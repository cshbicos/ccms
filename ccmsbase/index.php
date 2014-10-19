<?php 
$starttime = explode(' ', microtime());  
$starttime =  $starttime[1] + $starttime[0];


include('ccms/include.php');
$_SESSION['ccms_admin'] = 1;
?>
<head>
  <title></title>
  <?php write_head_tags($_SESSION['ccms_admin']); ?>
  

</head>
<body>
<?php



/**
$csh = new ListEntry(0);

echo $csh->add_id(1, 0);
echo $csh->add_id(2, 0);
echo $csh->add_id(3, 0);
echo $csh->add_id(5, 1);
echo $csh->add_id(4, 2);

//print_r($csh->get_val(4));

print_r($csh);
*/
$singletest = new CCMSCustomSingleGUI("CCMSSingleElements.xml",$_SESSION['ccms_admin']);
$array = $singletest->set_me_up();
$singletest->write_me();



$table = new CCMSCustomListGUI('CCMSListElements.xml', $_SESSION['ccms_admin']);
$table->set_me_up();
$table->write_me();

$table = new CCMSCustomTableGUI('CCMSTableElements.xml', $_SESSION['ccms_admin']);
$table->set_me_up();
$table->write_me();

$mtime = explode(' ', microtime());  
$totaltime = $mtime[0] +  $mtime[1] - $starttime;  
echo "<p>";
printf('Page loaded in %.3f seconds.',  $totaltime); 
echo "</p>";
?>
</body>
</html>
