<?php 
require 'Vhelper.php';
$yh= getBalanceLandlord(4,date("M"),date("Y"))[0];
if (empty($yh)) {
    echo "empty";
}else{
    echo print_r($yh);
}

?>