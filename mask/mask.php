<?php
/**
 * Script that generates a mask image
 * to be used as an overlay
 * to get iPnone/iPod-like glossy icons among other things
 *
 * @author Stoyan Stefanov <ssttoo@gmail.com>
 * @link http://phonydev.com/
 */

// defaults, configs
$x = 100;
$cachedir = 'maskcache/';
$cache_on = true;

header ('Content-type: image/png');

// overwriting the width/height?
if (intval(@$_GET['x'])) {
    $x = intval($_GET['x']);
}

// name of the mask file
$file = $cachedir . $x;
$file.= (@$_GET['type'] === 'h') ? 'h' : '';
$file.= (@$_GET['type'] === 'stripe') ? 'stripe' : '';
$file.= (@$_GET['type'] === 'gradient' ? 'gradient' : '') . (@$_GET['flip'] ? 'flip' : '');
$file.= '.png';


// if in the cache, serve
if (file_exists($file) && $cache_on) {
    //echo file_get_contents($file);
    //die();
}


// aliasing trick - generate bigger image than
// needed, then resize
$xplus = 3 * $x;

// new GD image
$xsize = $xplus;
if (@$_GET['type'] === 'h') {
    $xsize = 1;
    $xplus = $x;
}
if (@$_GET['type'] === 'gradient') {
    $xplus = $x = 127;
    $xsize = 1;
}
if (@$_GET['type'] === 'stripe') {
    $x = $xsize = $xplus = 7;    
}



$im = @imagecreatetruecolor($xsize, $xplus)
       or die('Cannot Initialize new GD image stream');
imagealphablending($im, false);
imagesavealpha($im, true);
// background
$back = imagecolorallocatealpha($im, 255, 255, 255, 127);
imagefilledrectangle($im, 0, 0, $xsize, $xplus, $back);
// forecolor
$color = imagecolorallocatealpha($im, 255, 255, 255, 90);

switch(@$_GET['type']) {
    case 'h':
        imagefilledrectangle($im, 0, 0, $xsize, $xplus/2, $color);
        break;
    case 'stripe':
        imagefilledrectangle($im, 0, 0, 0, 0, $color);
        imageline($im, $xsize, 0, 0, $xsize, $color);
        imageline($im, 6, 0, 0, 6, $color);
        imageline($im, 5, 0, 0, 5, $color);
        imagefilledrectangle($im, $xsize, $xsize-1, $xsize-1, $xsize, $color);
        break;
    case 'gradient':
        for($i = 0; $i < $x; $i++) {
            $trns = (empty($_GET['flip'])) ? $i : 127 - $i;
            imageline($im, 0, $i, $x, $i, imagecolorallocatealpha($im, 255, 255, 255, $trns));
        }
        break;
    default:
        imagefilledellipse($im, $xplus / 2, 0, 1.7 * $xplus, $xplus, $color);
}
imagepng($im, $file);
imagedestroy($im);

// a bunch of command-line tools to resize, PNG8-convert and optimize
$cmd = array();
$path = '~/bin/';
$path = '';
// imagemagick resize
$n = $xsize / 3;
if (empty($_GET['type'])) {
    $cmd[] = "convert $file -thumbnail " . $n . "x" . $x . " $file.png";
} else {
    $cmd[] = "cp $file $file.png";
}
// crush the image
$cmd[] = $path . "pngcrush -rem alla $file.png $file";
// convert to PNG8 
$cmd[] = $path . "pngquant 256 " . $file;
// cleanup
$cmd[] = "mv " . str_replace('.png', '-fs8.png', $file) . " $file";
$cmd[] = "rm -f $file.png";
exec(implode(';', $cmd));

// spit out the new image
echo file_get_contents($file);



/**
 * Utility file_put_contents() for PHP4
 * @link http://www.phpied.com/file_get_contents-for-php4/
 */
if (!function_exists('file_put_contents')) {
    function file_put_contents($filename, $data) {
        $f = @fopen($filename, 'w');
        if (!$f) {
            return false;
        } else {
            $bytes = fwrite($f, $data);
            fclose($f);
            return $bytes;
        }
    }
}

?>
