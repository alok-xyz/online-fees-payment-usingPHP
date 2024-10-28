<?php
session_start();
$captcha_code = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, 6);
$_SESSION['captcha'] = $captcha_code;

// Generate captcha image
header('Content-type: image/png');
$image = imagecreate(100, 40);
$bg_color = imagecolorallocate($image, 255, 255, 255);
$text_color = imagecolorallocate($image, 0, 0, 0);
imagestring($image, 5, 15, 10, $captcha_code, $text_color);
imagepng($image);
imagedestroy($image);
?>
