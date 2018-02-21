<?php
// Load source and mask

$source = imagecreatefrompng( "images/actual.png" );
$mask = imagecreatefrompng( "images/Room4 Areas Mask.png" );
$tilePicture = imagecreatefromjpeg( "images/gray.jpeg" );
// Apply mask to source
imagealphamask( $source, $mask, $source );

//imagealphamask( $tilePicture, $source, $tilePicture );

// Output
header( "Content-type: image/png");
imagepng( $source );

function imagealphamask( &$picture, $mask ,$source) {
// Get sizes and set up new picture
$xSize = imagesx( $picture );
$ySize = imagesy( $picture );
$newPicture = imagecreatetruecolor( $xSize, $ySize );

$repeating = imagecreatefrompng( "images/gray.png" );
//imagecopy($newPicture, $src, 0, 0, 100, 0, imagesx($src), imagesy($src));
// tile repeating image on it
imagesettile($newPicture, $repeating);
imagefill($newPicture, 0, 0, IMG_COLOR_TILED);


imagesavealpha( $newPicture, true );
imagefill( $newPicture, 0, 0, imagecolorallocatealpha( $newPicture, 255, 0, 0, 127 ) );

// Resize mask if necessary
if( $xSize != imagesx( $mask ) || $ySize != imagesy( $mask ) ) {
    $tempPic = imagecreatetruecolor( $xSize, $ySize );
    imagecopyresampled( $tempPic, $mask, 0, 0, 0, 0, $xSize, $ySize, imagesx( $mask ), imagesy( $mask ) );
    imagedestroy( $mask );
    $mask = $tempPic;
}

// Perform pixel-based alpha map application
for( $x = 0; $x < $xSize; $x++ ) {
    for( $y = 0; $y < $ySize; $y++ ) {
        $alpha = imagecolorsforindex( $mask, imagecolorat( $mask, $x, $y ) );

            //if(($alpha['red'] == 0) && ($alpha['green'] == 0) && ($alpha['blue'] == 0) && ($alpha['alpha'] == 0))
            if($alpha['red'] == 255 && $alpha['green'] == 0 )
			{
                // It's a black part of the mask
              //  imagesetpixel( $newPicture, $x, $y, //imagecolorallocatealpha( $newPicture, 0, 0, 0, 127 ) ); // Stick a black, but totally transparent, pixel in.
				
            }
            else
            {

                // Check the alpha state of the corresponding pixel of the image we're dealing with.    
                $alphaSource = imagecolorsforindex( $source, imagecolorat( $source, $x, $y ) );

                if(($alphaSource['alpha'] == 127))
                {
                    imagesetpixel( $newPicture, $x, $y, imagecolorallocatealpha( $newPicture, 0, 0, 0, 127 ) ); // Stick a black, but totally transparent, pixel in.
                } 
                else
                {
                    $color = imagecolorsforindex( $source, imagecolorat( $source, $x, $y ) );
                    imagesetpixel( $newPicture, $x, $y, imagecolorallocatealpha( $newPicture, $color[ 'red' ], $color[ 'green' ], $color[ 'blue' ], $color['alpha'] ) ); // Stick the pixel from the source image in
                }


            }
    }
}

// Copy back to original picture
imagedestroy( $picture );
$picture = $newPicture;
}



?>