<?php
session_start();
$num=rand(1000,9999);
$_SESSION['ctgps_code']=$num;
//$num='hello';
$im=imagecreate(65,25);

$bg=imagecolorallocate($im,240,240,240);
$fc=imagecolorallocate($im,4,4,4);
//imagestring($im,3,0,0,$num,$fc);
imagettftext($im,20,0,2,22,$fc,'./font.ttf',$num);

header("Content-type: image/jpeg");
imagejpeg($im);
?>
