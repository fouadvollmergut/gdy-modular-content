<?php
	


	
	/*********************************** SET POST LOCK **********************************/
	
	function gdymc_set_editlock( $objectID ) {

		if( !$objectID ) return false;

	    if( !$post = get_post( $objectID ) ) return false;

	    if( 0 == ($user_id = get_current_user_id()) ) return false;
	 
	    $now = time();
	    $lock = "$now:$user_id";
	 
	    update_post_meta( $post->ID, '_edit_lock', $lock );

	    return array( $now, $user_id );

	}




	/*********************************** CHECK POST LOCK **********************************/

	function gdymc_has_editlock( $objectID ) {


		if( !$objectID ) return false;

	 	if( !$lock = get_post_meta( $objectID, '_edit_lock', true ) ) return false;


	    $lock = explode( ':', $lock );
	    $time = $lock[0];
	    $user = $lock[1];

	    $time_window = apply_filters( 'wp_check_post_lock_window', 150 );
	 

	    if ( $time && $time > time() - $time_window && $user != get_current_user_id() ) return $user;

	    return false;

	}


	/*********************************** REMOVE POST LOCK **********************************/

	function gdymc_remove_editlock( $objectID, $userID, $sendTime = null ) {
		
		if( !$objectID ) return false;

	    if ( !$post = get_post( $objectID ) )
	        return false;
	 
	    if ( !$lock = get_post_meta( $post->ID, '_edit_lock', true ) )
	        return false;
	 
	    $lock = explode( ':', $lock );
	    $time = $lock[0];
	    $user = $lock[1];


	    if( $user != $userID ) return false;


	    if( $sendTime AND $sendTime < $time ) return false;


	    update_post_meta( $post->ID, '_edit_lock', '' );

	}










	// Disable takeover
	
	add_filter( 'override_post_lock', '__return_false' );




	// set editlock and enable heartbeat

	add_action( 'wp_head', 'gdymc_editlock_init' );

	function gdymc_editlock_init() {   
		
		if( gdymc_logged() AND !gdymc_has_editlock( gdymc_object_id() ) ):

			gdymc_set_editlock( gdymc_object_id() );

			wp_enqueue_script( 'heartbeat' );

		endif;

	}







	// Refresh the editlock via heartbeat

	add_filter( 'heartbeat_received', 'gdymc_editlock_refresh', 10, 2 );

	function gdymc_editlock_refresh( $response, $data ) {

		if( is_numeric( $data[ 'gdymc_set_editlock' ] ) ):

			gdymc_set_editlock( $data[ 'gdymc_set_editlock' ] );
			
			$response['gdymc_set_editlock'] = $data[ 'gdymc_set_editlock' ];

		endif;

		return $response;

	}



	// Remove the psotlock on leave
	
	add_action( 'wp_ajax_gdymc_editlock_remove', 'gdymc_editlock_remove' );
	
	function gdymc_editlock_remove() {
		
		if( gdymc_logged() ):
		
			if( is_numeric( $_POST[ 'objectID' ] ) AND is_numeric( $_POST[ 'userID' ] ) AND is_numeric( $_POST[ 'sendTime' ] ) ):

				gdymc_remove_editlock( $_POST[ 'objectID' ], $_POST[ 'userID' ], $_POST[ 'sendTime' ] );

			endif;

		endif;
		
	}

	



add_action( 'wp_footer', 'gdymc_heartbeat_send' );

function gdymc_heartbeat_send() {

	if( gdymc_logged() AND !gdymc_has_editlock( gdymc_object_id() ) ):
?>
<script>


	jQuery(window).on('unload beforeunload', function () {

		var data = {
			objectID: gdymc_dynamic_data.object_id,
			userID: gdymc_dynamic_data.current_user,
			sendTime: Math.floor( Date.now() / 1000 ) - 1,
			action: 'gdymc_editlock_remove',
		};

		jQuery.ajax({
			type: 'POST',
			data: data,
			async: false,
			url: gdymc_dynamic_data.ajax_url
		});

	});


	jQuery(document).ready(function() {				

		jQuery(document).on('heartbeat-send', function(e, data) {

			console.log( 'Refresh gdymc edit lock' );
			data[ 'gdymc_set_editlock' ] = gdymc_dynamic_data.object_id;

		});

	});		

</script>
<?php

endif;

}




