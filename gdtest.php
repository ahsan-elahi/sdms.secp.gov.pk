<?php
	
	$_GET['text'] = 'Wellcome to GD TEST';
	header("Content-type: image/png");

	$string = $_GET['text'];
	$im = imagecreatefrompng("gdimages/button1.png");
	$orange = imagecolorallocate($im, 220, 210, 60);
	$px = (imagesx($im) - 7.5 * strlen($string)) / 2;
	imagestring($im, 3, $px, 9, $string, $orange);
	imagepng($im);

	imagedestroy($im);
?>