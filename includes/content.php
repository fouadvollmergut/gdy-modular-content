<?php

	
	
	
	
	function gdymc_responsive_image( $imageID, $imageSize = null, $linkURI = null, $linkTarget = 0 ) {


		do_action( 'gdymc_image_before', $imageID, $imageSize );


		$linkTarget = $linkTarget ? '_blank' : '_self';


		if( !empty( $linkURI ) ) echo '<a href="' . $linkURI . '" target="' . $linkTarget . '">';


			if( is_numeric( $imageID ) AND !empty( $imageID ) ):

				echo wp_get_attachment_image( $imageID, apply_filters( 'gdymc_imagesize', 'full' ) );

			else:

				if( gdymc_logged() AND is_numeric( $imageSize[0] ) AND is_numeric( $imageSize[1] ) ):

					echo '<img class="gdymc_placeholder_image img" src="' . plugins_url('/placeholder.php', __FILE__ ) . '?w=' . $imageSize[0] . '&h=' . $imageSize[1] . '" />';

				else:

					return false;

				endif;

			endif;


		if( !empty( $linkURI ) ) echo '</a>';


		do_action( 'gdymc_image_after', $imageID, $imageSize );

	}



		
	function gdymc_contenttype_image( $contentRealID, $contentOption, $contentSubOption ) {
		
		$imageSize = (empty($contentOption)) ? 'autoxauto' : $contentOption;
		$imageSize = explode('x', $imageSize);
		$imageWidth = (is_numeric($imageSize[0])) ? $imageSize[0].'px' : $imageSize[0];
		$imageHeight = (is_numeric($imageSize[1])) ? $imageSize[1].'px' : $imageSize[1];
		
		// This is an array that hold arrays with 3 values: image id, link url, link target
		$contentString = get_metadata( gdymc_object_type(), gdymc_object_id(), '_gdymc_singlecontent_' . $contentRealID, true );
		$imageObject = json_decode( $contentString );

		// This converts the pre 0.7.4 system into the new one
		if( !is_array( $imageObject ) AND !empty( $contentString ) ):

			$imageObject = array( array( intval( $contentString ), NULL, NULL ) );

		endif;


		if( gdymc_logged() AND current_user_can( 'edit_posts', gdymc_object_id() ) ):
		
			echo '<div class="gdymc_image img" data-multiple="false" data-width="'.$imageSize[0].'" data-height="'.$imageSize[1].'" data-id="'.$contentRealID.'" data-image=\'' . json_encode( $imageObject ) . '\'>';
		
		else:

			echo '<div class="gdymc_image img">';

		endif;


		gdymc_responsive_image( $imageObject[0][0], $imageSize, $imageObject[0][1], $imageObject[0][2] );

		
		if( gdymc_logged() AND current_user_can( 'edit_posts', gdymc_object_id() ) ):
		
			echo '</div>';

		else:

			echo '</div>';

		endif;
		
	}



	function gdymc_contenttype_gallery( $contentRealID, $contentOption, $customRenderer ) {

		$imageSize = (empty($contentOption)) ? 'autoxauto' : $contentOption;
		$imageSize = explode('x', $imageSize);
		$imageWidth = (is_numeric($imageSize[0])) ? $imageSize[0].'px' : $imageSize[0];
		$imageHeight = (is_numeric($imageSize[1])) ? $imageSize[1].'px' : $imageSize[1];
		
		$sliderContents = get_metadata( gdymc_object_type(), gdymc_object_id(), '_gdymc_singlecontent_'.$contentRealID, true);
		$sliderArray = explode( ',', $sliderContents );
		$sliderCount = isset( $sliderArray ) ? count( $sliderArray ) : 0;

		// This is an array that hold arrays with 3 values: image id, link url, link target
		$contentString = get_metadata( gdymc_object_type(), gdymc_object_id(), '_gdymc_singlecontent_' . $contentRealID, true );
		$imageObject = json_decode( $contentString );


		// This converts the pre 0.7.4 system into the new one
		if( !is_array( $imageObject ) AND !empty( $contentString ) ):

			$sliderArray = explode( ',', $contentString );
			$imageObject = array();

			foreach( $sliderArray as $imageID ):

				array_push( $imageObject, array( intval( $imageID ), NULL, NULL ) );

			endforeach;

		endif;


		$sliderCount = isset( $imageObject ) ? count( $imageObject ) : 0;


		if( gdymc_logged() AND current_user_can( 'edit_posts', gdymc_object_id() ) ):

			echo '<div class="gdymc_gallery_container img" data-multiple="true" data-width="'.$imageSize[0].'" data-height="'.$imageSize[1].'" data-id="'.$contentRealID.'" data-image=\'' . json_encode( $imageObject ) . '\'>';

		else:

			echo '<div class="gdymc_gallery_container img">';

		endif;


		if( !empty( $imageObject ) ):

			echo '<ul class="gdymc_gallery" data-images="' . $sliderCount . '">';
			

			$i = 0; foreach( $imageObject as $image ): $i++; 

				$wpImage = get_post( $image[0] );

				echo '<li class="gdymc_gallery_item gdymc_gallery_item_' . $i . '" data-slide="' . $i . '" data-image-id="' . $image[0] . '">';

					do_action( 'gdymc_galleryimage_before', $image[0], $wpImage );


					// Check if custom renderer exists

					if( is_object( $customRenderer ) && ( $customRenderer instanceof Closure ) ):

						$customRenderer( $image, $i );

					else:

						gdymc_responsive_image( $image[0], $imageSize, $image[1], $image[2] );		

					endif;							


					do_action( 'gdymc_galleryimage_after', $image[0], $wpImage );

				echo '</li>';


			endforeach;

			echo '</ul>';

		else:

			if( gdymc_logged() AND is_numeric( $imageSize[0] ) AND is_numeric( $imageSize[1] ) ):

				echo '<img class="gdymc_placeholder_image" src="' . plugins_url('/placeholder.php', __FILE__ ) . '?w=' . $imageSize[0] . '&h=' . $imageSize[1] . '" />';

			endif;
			
		endif;



		if( gdymc_logged() AND current_user_can( 'edit_posts', gdymc_object_id() ) ):

			echo '</div>'; // .gdymc_gallery_container

		else:

			echo '</div>'; // .gdymc_gallery_container

		endif;

			
			

		
	}
	
	
	
	
	function gdymc_contenttype_text( $contentRealID, $contentTag, $contentOption, $contentSubOption ) {

		$length = (is_numeric($contentOption) AND $contentOption > 0) ? $contentOption : 'auto';

		if( gdymc_logged() AND current_user_can( 'edit_posts', gdymc_object_id() ) ):

			$classList = 'gdymc_text mousetrap ' . $contentSubOption;
			echo '<' . $contentTag . ' class="' . trim($classList) . '" data-id="' . $contentRealID . '" data-length="' . $length . '">';

		else:

			$classList = 'gdymc_text ' . $contentSubOption;
			echo '<' . $contentTag . ' class="' . trim($classList) . '">';

		endif;


		$content = get_metadata( gdymc_object_type(), gdymc_object_id(), '_gdymc_singlecontent_' . $contentRealID, true);
		echo apply_filters( 'gdymc_contentfilter', $content );


		if( gdymc_logged() AND current_user_can( 'edit_posts', gdymc_object_id() ) ): 

			echo '</' . $contentTag . '>';	

		else:

			echo '</' . $contentTag . '>';

		endif;

	}



	function gdymc_contenttype_table( $contentRealID, $contentOption, $contentSubOption ) {
		
		$content = get_metadata( gdymc_object_type(), gdymc_object_id(), '_gdymc_singlecontent_'.$contentRealID, true );
		$contentJSON = json_decode( $content );

		echo '<div class="gdymc_table_container">';

			echo '<table class="gdymc_table" data-id="' . $contentRealID . '" width="100%">';

				if( empty( $content ) ):

					$tableSize = empty( $contentOption ) ? '3x2' : $contentOption;
					$tableSize = explode( 'x', $tableSize );

					$tableWidth = ( is_numeric( $tableSize[ 0 ] ) AND $tableSize[ 0 ] > 1 ) ? $tableSize[ 0 ] : '1';
					$tableHeight = ( is_numeric( $tableSize[ 1 ] ) AND $tableSize[ 1 ] > 1 ) ? $tableSize[ 1 ] : '1';

					for( $heightIndex = 1; $heightIndex <= $tableHeight; $heightIndex++ ):

					    echo '<tr>';

							for( $widthIndex = 1; $widthIndex <= $tableWidth; $widthIndex++ ):

							    echo '<td></td>';

							endfor;

						echo '</tr>';

					endfor;

				elseif( empty( $contentJSON ) ):

					// Backward compatibility for pre 0.7.8 were tables are not saved in JSON
					echo $content;

				else:

					foreach( $contentJSON as $row ):

						echo '<tr>';

						foreach( $row as $col ):

							echo '<td>' . $col . '</td>';

						endforeach;

						echo '</tr>';

					endforeach;

				endif;

			echo '</table>';

			if( gdymc_logged() ):

				echo '<button class="gdymc_table_addrow"></button>';
				echo '<button class="gdymc_table_addcol"></button>';
				echo '<button class="gdymc_table_removerow"></button>';
				echo '<button class="gdymc_table_removecol"></button>';

			endif;

		echo '</div>';

	}


	function gdymc_contenttype_buttongroup ( $contentRealID, $contentOption, $contentSubOption ) {

    $content = get_metadata( gdymc_object_type(), gdymc_object_id(), '_gdymc_singlecontent_' . $contentRealID, true );

    echo '<div class="gdymc_button-group_container">';
      echo '<div class="gdymc_button-group" data-id="' . $contentRealID . '">';

        echo $content;

      echo '</div>';

      if( gdymc_logged() && !gdymc_preview()):

        echo '<button class="gdymc_button gdymc_inside_button gdymc_button_addbutton" style="display: none;">' . __('Button hinzufügen', 'gdy-modular-content') . '</button>';

      endif;

    echo '</div>';
  }
	
	
	
	function contentID( $contentKey ) {
		
		global $gdymc_module;
		global $gdymc_object_id;
		global $gdymc_object_contents;


		$contentKey = sanitize_title( $contentKey );

		if( $gdymc_module ):


			if( $gdymc_module->content_exists( $contentKey ) ):
			
				return $gdymc_module->content[$contentKey];
				
			else:
				
				$contentRealID = uniqid();
				update_metadata( gdymc_object_type(), $gdymc_object_id, '_gdymc_singlecontent_'.$contentRealID, '' );
				$gdymc_module->content[ $contentKey ] = $contentRealID;
				
				// Clean the array
				$max = max( array_keys( $gdymc_module->content ) );
				if( $max != 0 ):
					$gdymc_module->content = $gdymc_module->content + array_fill( 0, intval($max), '' );
					ksort( $gdymc_module->content );
				endif;
				
				return $contentRealID;
				
			endif;

		else:


			// Setup page contents

			if( !is_array( $gdymc_object_contents ) ):

				// Get object content
				
				$content = get_metadata( gdymc_object_type(), gdymc_object_id(), '_gdymc_object_contents', true );
				$content = json_decode( $content, true );
				$gdymc_object_contents = is_array( $content ) ? $content : array();

			endif;


			if( isset( $gdymc_object_contents[ $contentKey ] ) AND !empty( $gdymc_object_contents[ $contentKey ] ) ):

				return $gdymc_object_contents[ $contentKey ];
				
			else:

				$contentRealID = uniqid();
				update_metadata( gdymc_object_type(), $gdymc_object_id, '_gdymc_singlecontent_'.$contentRealID, '' );
				$gdymc_object_contents[ $contentKey ] = $contentRealID;
				
				// Clean the array
				$max = max( array_keys( $gdymc_object_contents ) );
				if( $max != 0 ):
					$gdymc_object_contents = $gdymc_object_contents + array_fill( 0, intval($max), '' );
					ksort( $gdymc_object_contents );
				endif;

				return $contentRealID;
				
			endif;

		endif;
		
		
	}
	

	function contentGet( $contentKey ) {
        
		$contentID = contentID( $contentKey );
			
		return get_metadata( gdymc_object_type(), gdymc_object_id(), '_gdymc_singlecontent_' . $contentID, true );
    
    }
	
	
	function contentShow( $contentKey ) {
    
        echo contentGet( $contentKey );
    
    }

	
	
	function contentCheck( $contentKey ) {
		

		foreach( func_get_args() as $contentKey ):

			$content = contentGet( $contentKey );
			$content = str_replace(' ', '', strip_tags( $content ) );

			if( !empty( $content ) OR gdymc_logged() ) return true;

		endforeach;


		return false;
		
		
	}
	
	
	/**************************** CREATES A EDITABLE CONTENT ****************************/

	// Options with wp_parse_args
	
	function contentCreate( $contentKey, $contentType = 'div/text', $contentOption = '', $contentSubOption = '' ) {


		if( !gdymc_object_type() ):


			if( WP_DEBUG ): trigger_error( 'contentCreate ist not supported on this object type' ); endif;


		else:
		
			$contentID = contentID( $contentKey );


			if( $contentType == 'image' ):

				// Option is image size e.g. 300x500 or 250xauto. Default is autoxauto.

				gdymc_contenttype_image( $contentID, $contentOption, $contentSubOption );

			elseif( $contentType == 'table' ):

				// Option is table field size for start e.g. 3x5. Default is 3x1.

				gdymc_contenttype_table( $contentID, $contentOption, $contentSubOption );

			elseif( $contentType == 'buttongroup' ):

				gdymc_contenttype_buttongroup( $contentID, $contentOption, $contentSubOption );

			elseif( $contentType == 'gallery' ):

				// Option is image size e.g. 300x500 or 250xauto. Default is autoxauto.

				gdymc_contenttype_gallery( $contentID, $contentOption, $contentSubOption );

			else:

				// Option is maximum character length. Default is auto (infinite). Suboption are additional calsses for container

				$contentTag = explode('/', $contentType);

				gdymc_contenttype_text( $contentID, $contentTag[0] == 'text' ? 'div' : $contentTag[0], $contentOption, $contentSubOption );

			endif;

		endif;
		
		
	}
	


	

?>