<?php
  $str = file_get_contents('php://input');
  echo $filename = md5(time().uniqid()).".xls";  
  $filename = $_REQUEST["filename"];  
  file_put_contents("./uploads/".$filename,$str);    
?>