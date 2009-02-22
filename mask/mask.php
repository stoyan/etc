<?php
/**
 * Script that generates a mask image
 * to be used as an overlay
 * to get iPnone/iPod-like glossy icons
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
$file.= '.png';


// if in the cache, serve
if (file_exists($file) && $cache_on) {
    echo file_get_contents($file);
    die();
}


// aliasing trick - generate bigger image than
// needed, then resize
$xplus = 3 * $x;

// new GD image
$xsize = $xplus;
if (@$_GET['type'] === 'h') {
    $xsize = 1;
    $xsize = 3 * $xsize;
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
    default:
        imagefilledellipse($im, $xplus / 2, 0, 1.7 * $xplus, $xplus, $color);
}
imagepng($im, $file);
imagedestroy($im);

// a bunch of command-line tools to resize, PNG8-convert and optimize
$cmd = array();
// imagemagick resize
$n = $xsize / 3;
$cmd[] = "convert $file -thumbnail " . $n . "x" . $x . " $file.png";
// crush the image
$cmd[] = "~/bin/pngcrush -rem alla $file.png $file";
// convert to PNG8 
$cmd[] = "~/bin/pngquant 256 " . $file;
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
