<?php 

$actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

$size = getimagesize($actual_link);
if($size['mime'] == 'image/jpeg') {
  header('Content-Type: image/jpeg');
  $image = imagecreatefromjpeg($actual_link);
} else if($size['mime'] == 'image/png') {
  header('Content-Type: image/png');
  $image = imagecreatefrompng($actual_link);
} else if($size['mime'] == 'image/gif') {
  header('Content-Type: image/gif');
  $image = imagecreatefromgif($actual_link);
}

//Giramos la imagen
imageflip($image, IMG_FLIP_BOTH);

//Ponemos en negativos/engris/realzamos
$rand = rand(1,3);
if($rand == 1) imagefilter($image, IMG_FILTER_NEGATE);
else if($rand == 2) imagefilter($image, IMG_FILTER_GRAYSCALE);
else if($rand == 3) imagefilter($image, IMG_FILTER_EMBOSS);

//Pixelamos la imagen
$height = $size[1];
$width = $size[0];
$rand = rand(3,15);
$pixelate_y = $rand;
$pixelate_x = $rand;
for($y = 0;$y < $height;$y += $pixelate_y+1) {
  for($x = 0;$x < $width;$x += $pixelate_x+1) {
    $rgb = imagecolorsforindex($image, imagecolorat($image, $x, $y));
    $color = imagecolorclosest($image, $rgb['red'], $rgb['green'], $rgb['blue']);
    imagefilledrectangle($image, $x, $y, $x+$pixelate_x, $y+$pixelate_y, $color);
  }       
}

if($size['mime'] == 'image/jpeg') imagejpeg($image);
else if($size['mime'] == 'image/png') imagepng($image);
else if($size['mime'] == 'image/gif') imagegif($image);
imagedestroy($image);