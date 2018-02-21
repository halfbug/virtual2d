<?php
/**
 * Created by PhpStorm.
 * User: sadaf.siddiqui
 * Date: 2/20/2018
 * Time: 5:04 PM
 */


$room = imagecreatefrompng( "images/".$_GET["room"].".png" );

$tile = imagecreatefrompng( "images/tile/".$_GET["tile"].".png" );

$xSize = imagesx( $room );
$ySize = imagesy( $room );
$floor = imagecreatetruecolor( $xSize, $ySize );

//$repeating = imagecreatefrompng( "images/gray.png" );
//imagecopy($newPicture, $src, 0, 0, 100, 0, imagesx($src), imagesy($src));
// tile repeating image on it
imagesettile($floor, $tile);
imagefill($floor, 0, 0, IMG_COLOR_TILED);

// Merge the red image onto the PNG image
imagecopy($floor, $room, 0, 0, 0, 0,imagesx($room), imagesy($room));

// Output
header( "Content-type: image/png");
//$imb64 = base64_encode(imagepng( $floor ));
//imagedestroy($floor);
//imagedestroy($floor);
//echo $imb64;
////exit;

$id = rand(500,900); //Whereas this generates a random ID number
$file="testimage".$id.".png";
imagepng($floor, $file);
imagedestroy($floor);
echo(base64_encode(file_get_contents($file)));
unlink($file);