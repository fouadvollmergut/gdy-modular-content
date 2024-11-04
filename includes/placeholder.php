<?php
	
	// Image size
	$imageWidth = is_numeric( $_GET[ 'w' ] ) ? $_GET[ 'w' ] : 0;
	$imageHeight = is_numeric( $_GET[ 'h' ] ) ? $_GET[ 'h' ] : 0;

	// Header
	header ('Content-Type: image/png');

	// Create Image
	$image = imagecreatetruecolor( $imageWidth, $imageHeight );
	imagesavealpha( $image, true );
	$color = imagecolorallocatealpha($image, 0, 0, 0, 127);
	imagefill($image, 0, 0, $color);


	// Ouput
	imagepng( $image );
	imagedestroy( $image );

?>