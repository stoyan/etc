<?php
$x = 100;
$cachedir = 'maskcache/';
$cache_on = true;

header ('Content-type: image/png');

if (intval(@$_GET['x'])) {
  $x = intval($_GET['x']);
}

$file = $cachedir . $x . '.png';

if (file_exists($file) && $cache_on) {
  echo file_get_contents($file);
  die();
}


$double = 3 * $x;

$im = @imagecreatetruecolor($double, $double)
  or die('Cannot Initialize new GD image stream');
imagealphablending($im, false);
imagesavealpha($im, true);
$back = imagecolorallocatealpha($im, 255, 255, 255, 127);
imagefilledrectangle($im, 0, 0, $double, $double, $back);
$color = imagecolorallocatealpha($im, 255, 255, 255, 90);
imagefilledellipse($im, $double / 2, 0, 1.7 * $double, $double, $color);
imagepng($im, $file);
imagedestroy($im);

$cmd = array();
$cmd[] = "convert $file -thumbnail $xx$x $file.png";
$cmd[] = "~/bin/pngcrush -rem alla $file.png $file";
$cmd[] = "~/bin/pngquant 256 " . $file;
$cmd[] = "mv " . str_replace('.png', '-fs8.png', $file) . " $file";
$cmd[] = "rm -f $file.png";
exec(implode(';', $cmd));

echo file_get_contents($file);

?>
