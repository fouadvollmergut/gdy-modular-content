
	
	

	// Shortcuts
	
	Mousetrap.bind( 'mod+b', function() {
		gdymc.editor.format('bold','');
		return false;
	});
	
	Mousetrap.bind( 'mod+i', function() {
		gdymc.editor.format('italic','');
		return false;
	});
	
	Mousetrap.bind( 'mod+s', function() {
		jQuery('#gdymc_save').trigger('click');
		return false;
	});
	
	Mousetrap.bind( 'mod+d', function() {
		jQuery('#gdymc_showmodules').trigger('click');
		return false;
	});

	Mousetrap.bind( 'mod+l', function() {
		jQuery('.gdymc_insertlink').first().trigger('mousedown');
		jQuery('.gdymc_insertlink').first().trigger('click');
		return false;
	});

	Mousetrap.bind( 'mod+u', function() {
		jQuery('.gdymc_unlink').first().trigger('mousedown');
		jQuery('.gdymc_unlink').first().trigger('click');
		return false;
	});
	
	Mousetrap.bind( 'mod+e', function() {
		jQuery('#gdymc_togglesoftpreview').trigger('click');
		return false;
	});

	Mousetrap.bind( 'mod+shift+e', function() {
		var href = jQuery( '#gdymc_togglehardpreview' ).attr( 'href' );
		window.location.href = href;
		return false;
	});

	// GDYMC Button
	class ButtonGroup {
		constructor ( buttonGroupContainer ) {
			this.buttonGroupContainer = buttonGroupContainer;
			this.buttonGroup = buttonGroupContainer.querySelector( '.gdymc_button-group' );
		}

		init () {
			this.buttonGroupContainer.querySelectorAll( '.gdymc_button_addbutton' ).forEach( (button) => {
				this.addButtonListener(button);
			});

			this.buttonGroupContainer.querySelectorAll( '.gdymc_button_removebutton' ).forEach( (button) => {
				this.removeButtonListener(button);
			});

			this.buttonGroupContainer.querySelectorAll( '.gdymc_button_editbutton' ).forEach( (button) => {
				this.editButtonListener(button);
			});

			jQuery( this.buttonGroup ).sortable({
				revert: true,
			});

			jQuery( this.buttonGroup ).disableSelection();
		}


		// Event Listeners
		addButtonListener (button) {
			button.addEventListener( 'click', (event) => {
				this.addButton(event.target);
			});
		}

		removeButtonListener (button) {
			button.addEventListener( 'click', (event) => {
				this.removeButton(event.target);
			});
		}

		editButtonListener (button) {
			button.addEventListener( 'click', (event) => {
				this.editButton(event.target.closest( '.gdymc_button_container' ).querySelector( 'a' ));
			});
		}

		addButton () {
			this.buttonGroup.insertAdjacentHTML( 'beforeend', `
				<div class="gdymc_button_container">
					<a href="#" class="button button-primary" target="_self">Button Text</a>

					<button class="gdymc_button gdymc_inside_button gdymc_button_editbutton" aria-label="Edit button" style="display: none;">
						<span class="dashicons dashicons-edit"></span>
					</button>

					<button class="gdymc_button_delete gdymc_inside_button gdymc_button_removebutton" aria-label="Remove button" style="display: none;">
						<span class="dashicons dashicons-trash"></span>
					</button>
				</div>
			`);

			this.buttonGroupContainer.querySelectorAll( '.gdymc_button_removebutton' ).forEach( (button) => {
				this.removeButtonListener(button);
			});

			this.buttonGroupContainer.querySelectorAll( '.gdymc_button_editbutton' ).forEach( (button) => {
				this.editButtonListener(button);
			});

			document.body.classList.add('gdymc_unsaved');
			gdymc.info.isSaved = false;
		}

		removeButton (button) {
			button.closest( '.gdymc_button_container' ).remove();

			document.body.classList.add('gdymc_unsaved');
			gdymc.info.isSaved = false;
		}

		editButton (button) {
			const data = {
				text: JSON.stringify(button.textContent),
				url: JSON.stringify(button.getAttribute( 'href' )),
				target: JSON.stringify(button.getAttribute( 'target' ) === '_blank' ? 'true' : 'false'),
				type: JSON.stringify(button.classList.contains( 'button-primary' ) ? 'true' : 'false'),
			};

			gdymc.ajax( 'gdymc_action_editbutton', data, function(response) {

				gdymc.info.overlayOpen = true;
				gdymc.info.overlayScroll = jQuery(window).scrollTop();
				jQuery('#gdymc_overlay_shadow').show();
				jQuery('body').append('<div class="gdymc_overlay_link gdymc_overlay_window gdymc_inside gdymc_tabs_container">' + response + '</div>');
				jQuery('#gdymc_insertlink_input').focus();

				setTimeout( function() {
					jQuery('#gdymc_overlay_shadow').addClass( 'gdymc_active' );
					jQuery('.gdymc_overlay_link').addClass( 'gdymc_active' );
				}, 1 );

				document.querySelector('#gdymc_saveedit_button').addEventListener( 'click', function () {
					button.textContent = document.querySelector('#gdymc_editbutton_text').value;
					button.setAttribute( 'href', document.querySelector('#gdymc_insertlink_input').value );
					button.setAttribute( 'target', document.querySelector('#gdymc_editbutton_target').checked ? '_blank' : '_self' );
					button.classList.toggle( 'button-primary', document.querySelector('#gdymc_editbutton_type').checked );

					jQuery('#gdymc_overlay_shadow').hide().removeClass( 'gdymc_active' );
					// document.querySelector( '#gdymc_overlay_shadow' ).classList.remove( 'gdymc_active' );
					jQuery( '.gdymc_overlay_link' ).removeClass( 'gdymc_active' ).remove();
					gdymc.info.overlayOpen = false;

					document.body.classList.add('gdymc_unsaved');
					gdymc.info.isSaved = false;
				});
			});
		}
	}
	
	jQuery( document ).ready(function() {
	

		// Init rangy

		rangy.init();



		// Set scroll position

		var scrollpos = parseFloat( wpCookies.get( 'gdymc_scrollpos' ) );

		if( wpCookies.get( 'gdymc_scrollpos' ) > 0 ) {

			jQuery( window ).scrollTop( scrollpos );

		}

		wpCookies.remove( 'gdymc_scrollpos', gdymc_dynamic_data.cookie_path, gdymc_dynamic_data.cookie_domain );


		// Button groups

		document.querySelectorAll( '.gdymc_button-group_container' ).forEach( (buttonGroupContainer) => {
			let buttonGroup = new ButtonGroup( buttonGroupContainer );
			buttonGroup.init();
		} );


		// Tabs

		jQuery( document.body ).on( 'click', '.gdymc_tabs_button', function() {
			
			var tabButton = jQuery(this);
			var tabTarget = tabButton.attr('data-tab');
			var tabContainer = tabButton.parents('.gdymc_tabs_container');
			
			tabContainer.find('.gdymc_tabs_button').removeClass('gdymc_active');
			tabContainer.find('.gdymc_tabs_content').removeClass('gdymc_active');
			
			tabButton.addClass('gdymc_active');
			tabContainer.find('.gdymc_tabs_content[data-tab="'+tabTarget+'"]').addClass('gdymc_active');
		
		});

		


		// Warn on leave if unsaved

		window.onbeforeunload = function() {


			// Maintain current preview

			wpCookies.set( 'gdymc_hardpreview', 0, 0, gdymc_dynamic_data.cookie_path, gdymc_dynamic_data.cookie_domain );

			if( jQuery( document.body ).hasClass( 'gdymc_softpreview' ) ) {

				wpCookies.set( 'gdymc_softpreview', 1, 0, gdymc_dynamic_data.cookie_path, gdymc_dynamic_data.cookie_domain );

			} else {

				wpCookies.set( 'gdymc_softpreview', 0, 0, gdymc_dynamic_data.cookie_path, gdymc_dynamic_data.cookie_domain );

			}

			

			// Warning if isn't saved

		    if( !gdymc.info.isSaved && !gdymc.info.isSaving ) {

		        return gdymc.lang( 'unload-warning' );

		    } else {

		        return void 0;

		    }

		}



		// Window resize

		function gdymc_formatbuttons_resize() {

			var windowWidth = jQuery( document.body ).width();
			var rightWidth = jQuery( '#gdymc_adminbar > .gdymc_right' ).width();
			var leftWidth = jQuery( '#gdymc_adminbar > .gdymc_left' ).width();
			var space = windowWidth - ( rightWidth + leftWidth );
			var action = false;


			if( space < 30 && jQuery( '#gdymc_formatbuttons button:visible:last' ).length > 0  ) {

				jQuery( '#gdymc_formatbuttons button:visible:last' ).hide();

				action = true;

			} else if( space > 80 && jQuery( '#gdymc_formatbuttons button:hidden:first' ).length > 0  ) {

				jQuery( '#gdymc_formatbuttons button:hidden:first' ).show();

				action = true;

			}


			jQuery( '#gdymc_show_format_buttons' ).remove();
			
			var number = jQuery( '#gdymc_formatbuttons button:hidden' ).length;

			if( number > 0 ) {

				jQuery( '#gdymc_adminbar > .gdymc_left' ).append( '<button id="gdymc_show_format_buttons" data-gdymc-tip="' + gdymc.lang( 'showallformattingoptions' ) + '"><span class="dashicons dashicons-editor-insertmore"></span><span id="gdymc_show_format_buttons_number">' + number + '</span></button>' );

			}

			if( action ) {

				gdymc_formatbuttons_resize();

			}

		}

		jQuery( window ).on( 'resize', function( event ) {

			gdymc_formatbuttons_resize();

		} ); gdymc_formatbuttons_resize();




		// Show all format buttons button

		jQuery( document ).on( 'mousedown', '#gdymc_show_format_buttons', function( event ) {

			gdymc.selection.save();

		} );


		jQuery( document ).on( 'click touchstart', '#gdymc_show_format_buttons', function( event ) {

			jQuery('#gdymc_overlay_shadow').show();
			var holder = jQuery( '<div id="gdymc_show_format_buttons_window" class="gdymc_overlay_images gdymc_overlay_window gdymc_inside"></div>' ).appendTo( 'body' );

			jQuery( '#gdymc_formatbuttons button' ).each( function() {

				var button = jQuery( this ).clone().show();

				button.append( button.attr( 'data-gdymc-tip' ) );
				button.removeAttr( 'data-gdymc-tip' )

				button.appendTo( holder );

			} );

			gdymc.info.overlayOpen = true;
			gdymc.info.overlayScroll = jQuery(window).scrollTop();

			setTimeout( function() {
				jQuery( '#gdymc_overlay_shadow' ).addClass( 'gdymc_active' );
				jQuery( '#gdymc_show_format_buttons_window' ).addClass( 'gdymc_active' );
			}, 1 );

		} );


		jQuery( document ).on( 'click', '#gdymc_show_format_buttons_window button', function( event ) {

			jQuery('#gdymc_overlay_shadow').hide().removeClass( 'gdymc_active' );
			jQuery( '#gdymc_show_format_buttons_window' ).remove();
			gdymc.info.overlayOpen = false;

		} );


		

		// Remove format on paste

		jQuery( document ).on( 'paste', '.gdymc_editable', function( event ) {

			event.preventDefault();
			var text = (event.originalEvent || event).clipboardData.getData('text/plain') || prompt('Paste something..');
			gdymc.editor.format( 'insertText', text );

		} );



		// Editable link click

		jQuery( document ).on( 'click', '.gdymc_edit .gdymc_text a', function( event ) {

			

			if( event.metaKey ) {

				var target = jQuery( this ).attr( 'href' );

				window.location.href = target;

			}

		} );


		// Image links

		jQuery( document ).on( 'click', '.gdymc_image a', function() {
			
			if( jQuery( document.body ).hasClass( 'gdymc_edit' ) ) {

				return false;

			}
		
		});
		
		

		// Block scroll if overlay is open

		jQuery( window ).scroll( function() {
			
			if( gdymc.info.overlayOpen && gdymc.info.blockScroll ) {

				jQuery( window ).scrollTop( gdymc.info.overlayScroll );	
			
			}
			
		} );
		


		// Dropzone

		if( gdymc_dynamic_data.role_uploads ) {

			// general dragover

			jQuery( document.body ).bind( 'dragover', function() {
			    jQuery( document.body ).addClass( 'gdymc_drag_hover' );
			});

			jQuery( '#gdymc_dropzone_overlay' ).bind( 'dragleave drop', function() {
			    jQuery( document.body ).removeClass( 'gdymc_drag_hover' );
			});


			jQuery( '#gdymc_dropzone_overlay' ).dropzone( {

				method: 'post',
				paramName: 'gdymc_upload',
				clickable: '#gdymc_dropzone_trigger',
				url: gdymc_dynamic_data.ajax_url,
				parallelUploads: 1,
				maxFilesize: gdymc_dynamic_data.max_upload,
				uploadMultiple: false,
				acceptedFiles: gdymc_dynamic_data.allowed_filetypes,
				previewsContainer: '#gdymc_dropzone_preview',
				createImageThumbnails: false,
				dictInvalidFileType: gdymc.lang( 'dropzone-invalidfiletype' ),
				dictFileTooBig: gdymc.lang( 'dropzone-filetoobig' ),
				dictResponseError: gdymc.lang( 'dropzone-invalidresponse' ),
				totaluploadprogress: function( progress ) {

					jQuery( '#gdymc_dropzone_progressBar' ).width( progress + '%' );

				},
				init: function() {
		
					this.on("sending", function(file, xhr, formData) {
						formData.append( 'action', 'gdyModularContentUploadAction' );
						formData.append( 'object', gdymc_dynamic_data.object_id );
					});
					
					this.on("addedfile", function(file, xhr, formData) {

						jQuery( '#gdymc_dropzone_uploader_container' ).addClass( 'gdymc_active' );
						jQuery( '#gdymc_dropzone_uploader_container' ).removeClass( 'gdymc_dz_finished' );

						var extension = file.name.split('.').pop();
						jQuery( file.previewElement ).addClass( 'dz-filetype-' + extension );
						jQuery( file.previewElement ).prepend( '<div class="dz-filetype">' + extension + '</div>' );

						jQuery( '.gdymc_overlay_images .gdymc_overlay_close' ).click();
						jQuery( document.body ).addClass( 'gdymc_uploading' );
						jQuery('#gdymc_dropzone_drag').addClass('gdymc_active');
						jQuery('#gdymc_dropzone_previewContainer').show();
						jQuery('#gdymc_dropzone_previewContainer').removeClass('gdymc_dz_finished');

					});
					
					this.on( "queuecomplete", function(file) {

						jQuery( document.body ).removeClass( 'gdymc_uploading' );
						jQuery('#gdymc_dropzone_uploader_container').addClass('gdymc_dz_finished');
						setTimeout( function() {
							jQuery( '#gdymc_dropzone_progressBar' ).width( '0%' );
						}, 200 );

					});

					this.on("success", function(file, response) {
						jQuery( file.previewElement ).wrap( '<a href="' + response + '" target="_blank"></a>' );
					});
					
					this.on( "error", function( file, error, xhr ) {

						console.log( error );

					} );
		
				}
				
				
			} );

		}

		jQuery( document.body ).on( 'click', '#gdymc_dropzone_preview .dz-error', function() {

			var error = jQuery( this ).find( '.dz-error-message' ).text();

			gdymc.functions.error( { text: error } );


		} );
		

		
		
		
		// Swap content

		jQuery( document.body ).on( 'mousedown', '.gdymc_swapcontent', function(e) {
			
			jQuery( document.body ).addClass( 'gdymc_swap' );
			jQuery( ':focus' ).addClass( 'gdymc_swap_source' );

		} );


		jQuery('.gdymc_text').on('focus', function (e){
			
			if( jQuery('body').hasClass('gdymc_swap') ){
				var target = jQuery(this).html();
				var source = jQuery('.gdymc_swap_source').html();

				jQuery(this).html(source);
				jQuery('.gdymc_swap_source').html(target);

				jQuery( '.gdymc_swap' ).removeClass( 'gdymc_swap' );
				jQuery( '.gdymc_swap_source' ).removeClass( 'gdymc_swap_source' );

				jQuery(this).blur();

				jQuery('body').addClass('gdymc_unsaved');
				gdymc.info.isSaved = false;
			}
			
		});

		jQuery(document).on('click', function (e){
			
			var container = jQuery('.gdymc_text');
			
			if(!container.is(e.target) && container.has(e.target).length === 0) {
				jQuery( '.gdymc_swap' ).removeClass( 'gdymc_swap' );
				jQuery( '.gdymc_swap_source' ).removeClass( 'gdymc_swap_source' );
			}

			var container = jQuery('#gdymc_dropzone_previewContainer');
			
			if(!container.is(e.target) && container.has(e.target).length === 0) {
				jQuery( '#gdymc_dropzone_previewContainer' ).hide();
			}
			
		});
	





		
		// Modal stuff
		var gdymc_Textmodal = jQuery('');
		var gdymc_imagemodal = jQuery('');
		
		function gdymc_TextmodalDo(object) {
			
			gdymc_Textmodal.remove();
			
			var height = jQuery(object).height();
			var left = jQuery(object).offset().left;
			var top = jQuery(object).offset().top;
			var tt = top+height;
			var tl = left;
			var characters = jQuery(object).text().length;
			var length = jQuery(object).attr('data-length');
			
			if(characters > length && length != 'auto') {
	jQuery(object).addClass('gdymc_maxtext');
			} else {
	jQuery(object).removeClass('gdymc_maxtext');
			}
			
			gdymc_Textmodal = jQuery('<div>'+characters+' / '+length+'<span class="gdymc_swapcontent" data-gdymc-tip="'+gdymc.lang( 'swap-content' )+'"></span></div>').appendTo('body');
			gdymc_Textmodal.addClass('gdymc_Textmodal').css({
	'top':tt,
	'left':tl	
			});
			if(jQuery(object).hasClass('gdymc_maxtext')) {
	gdymc_Textmodal.addClass('gdymc_maxtext');
			}
			
		}
	
		
		jQuery('body').on('focus keyup input paste', '.gdymc_text', function(){
			
			gdymc_TextmodalDo(this);
			
		});
		
		jQuery('body').on('blur', '.gdymc_text', function(){
			
			gdymc_Textmodal.remove();
			
		});
		
		jQuery(document).on({
			mouseenter: function(){
	gdymc_TextmodalDo(this);
			},
			mouseleave: function(){
	if(!jQuery(this).is(':focus')) {
		gdymc_Textmodal.remove();
	}
	if(jQuery('.gdymc_text:focus').length > 0){
		gdymc_TextmodalDo(jQuery('.gdymc_text:focus'));
	}
			}
		}, '.gdymc_text');
		
		
		
		function gdymc_imagemodalDo(object) {
			
			var height = jQuery(object).height();
			var left = jQuery(object).offset().left;
			var top = jQuery(object).offset().top;
			var tt = top+height;
			var tl = left;
			var iw = jQuery(object).attr('data-width');
			var ih = jQuery(object).attr('data-height');
			
			gdymc_imagemodal = jQuery('<div>'+iw+' x '+ih+'</div>').appendTo('body');
			gdymc_imagemodal.addClass('gdymc_imagemodal').css({
	'top':tt,
	'left':tl	
			});
			
		}
		
		jQuery(document).on({
			mouseenter: function(){
				gdymc_imagemodalDo(this);
			},
			mouseleave: function(){
				gdymc_imagemodal.remove();
			}
		}, '.gdymc_edit .gdymc_image, .gdymc_edit .gdymc_gallery_container');
		
		
		
		jQuery('.gdymc_moduleoptionsbutton').click(function(e){
			
			var moduleOverlay = jQuery(this).parents('.gdymc_module').find('.gdymc_overlay_module');
				
			gdymc.info.overlayOpen = true;
			gdymc.info.overlayScroll = jQuery(window).scrollTop();
			jQuery('#gdymc_overlay_shadow').show();
			moduleOverlay.show();

			setTimeout( function() {
				jQuery('#gdymc_overlay_shadow').addClass( 'gdymc_active' );
				moduleOverlay.addClass( 'gdymc_active' );
			}, 1 );
			
		});
		
		jQuery('.gdymc_Savemoduleoptions').click(function(){
			
			jQuery('.gdymc_save').trigger( 'click' );
			
			jQuery('#gdymc_overlay_shadow').hide().removeClass( 'gdymc_active' );
			jQuery('.gdymc_overlay_window').hide().removeClass( 'gdymc_active' );
			gdymc.info.overlayOpen = false;
			
		});


		


		
		// Load more pages

		jQuery( document.body ).on( 'click', '#gdymc_loadmore_pages', function() {
			
			var currentButton = jQuery( this );
			var newPage = parseFloat( currentButton.attr( 'data-page' ) ) + 1;
			currentButton.addClass( 'gdymc_active' );
			
			var data = {
				p: newPage,
			};
			
			gdymc.ajax( 'gdymc_action_pagelist', data, function(response) {

				currentButton.remove();
				jQuery( '#gdymc_tabs_content_pages' ).append(response);	

			} );
			
		} );


	
		// Load more posts

		jQuery( document.body ).on( 'click', '#gdymc_loadmore_posts', function() {
			
			var currentButton = jQuery( this );
			var newPage = parseFloat( currentButton.attr( 'data-page' ) ) + 1;
			currentButton.addClass( 'gdymc_active' );
			
			var data = {
				p: newPage,
			};
			
			gdymc.ajax( 'gdymc_action_postlist', data, function(response) {

				currentButton.remove();
				jQuery( '#gdymc_tabs_content_posts' ).append(response);	

			} );
			
		} );



		// Load more files

		jQuery( document.body ).on( 'click', '#gdymc_loadmore_files', function() {
			
			var currentButton = jQuery( this );
			var newPage = parseFloat( currentButton.attr( 'data-page' ) ) + 1;
			currentButton.addClass( 'gdymc_active' );
			
			var data = {
				p: newPage,
			};
				
			gdymc.ajax( 'gdymc_action_filelist', data, function(response) {

				currentButton.remove();
				jQuery( '#gdymc_tabs_content_files' ).append(response);	

			} );
			
		} );





		
		
		// Link window
		
		jQuery('body').on('touchstart mousedown', '.gdymc_insertlink', function(){

			gdymc.selection.restore();

			if( jQuery( document.body ).hasClass( 'gdymc_edit' ) ) {

				if( gdymc.selection.in( '.gdymc_editable' ) || gdymc.selection.in( '.gdymc_option-editable' ) ) {

					gdymc.selection.save();

				} else {

					gdymc.functions.error( { title: gdymc.lang( 'focus-title' ), text: gdymc.lang( 'focus-text' ) } );

				}

			} else {

				gdymc.functions.error( { 

					title: gdymc.lang( 'leavepreview-title' ),
					text: gdymc.lang( 'leavepreview-text' ),
					buttons: new Array( {

						text: gdymc.lang( 'button-no' ),
						action: function( object ) {

							object.close();

						}

					}, {

						text: gdymc.lang( 'leavepreview-button' ),
						action: function( object ) {

							object.close();

							jQuery( '#gdymc_togglesoftpreview' ).trigger( 'click' );
							
						}

					} ),

				} );

			}

        } );




		jQuery('body').on('click', '.gdymc_insertlink', function(){
        	
			var button = jQuery( this );
			button.addClass( 'gdymc_active' );

			// Load cursor

			gdymc.ajax( 'gdymc_action_insertlink', null, function(response) {

				gdymc.info.overlayOpen = true;
				gdymc.info.overlayScroll = jQuery(window).scrollTop();
				jQuery('#gdymc_overlay_shadow').show();
				jQuery('body').append('<div class="gdymc_overlay_link gdymc_overlay_window gdymc_inside gdymc_tabs_container">'+response+'</div>');
				jQuery('#gdymc_insertlink_input').focus();
				button.removeClass( 'gdymc_active' );

				setTimeout( function() {
					jQuery('#gdymc_overlay_shadow').addClass( 'gdymc_active' );
					jQuery('.gdymc_overlay_link').addClass( 'gdymc_active' );
				}, 1 );

			});
			
		} );



		
		jQuery('body').on('keypress', '#gdymc_insertlink_input', function(e){
		  if (e.which == 13) {
			jQuery('#gdymc_insertlink_button').trigger( 'click' );
		  }
		});
		




		// Insert link button

		jQuery( document.body ).on( 'click', '#gdymc_insertlink_button', function() {
			
			var linkUrl = encodeURI( decodeURI( jQuery( '#gdymc_insertlink_input' ).val() ) );

			
			if( jQuery( '#gdymc_insertlink_target' ).is( ':checked' ) ) {

				var linkTarget = '_blank';

			} else {

				var linkTarget = '_self';

			}


			var linkClass = jQuery( '#gdymc_insertlink_classes' ).val();



			gdymc.selection.restore();

			gdymc.editor.addtag( 'a', {

                href: linkUrl,
                target: linkTarget,
                className: linkClass

			} );
			

			jQuery( '#gdymc_overlay_shadow' ).hide().removeClass( 'gdymc_active' );
			jQuery( '.gdymc_overlay_link' ).removeClass( 'gdymc_active' ).remove();
			gdymc.info.overlayOpen = false;

			return false;
			
		} );






		jQuery('body').on('click', '#gdymc_overlay_shadow, .gdymc_overlay_close, .gdymc_overlay_close_trigger', function(){
			
			gdymc.functions.close_overlay();
			
		});




		
		jQuery('body').on('click', '.gdymc_insertlink_source-use', function(){
			
			var linkUrl = jQuery(this).attr('data-guid');
			
			jQuery('#gdymc_insertlink_input').val(linkUrl);
			jQuery(this).parents('.gdymc_tabs_container').find('.gdymc_tabs_button').first().trigger( 'click' );
			
		});
		
		
		jQuery('body').on('click', '.gdymc_linktab', function(){
			
			var openTab = jQuery(this).attr('data-open');
			
			if(openTab == 'link') {
	jQuery('#gdymc_overlay_window-Link').css({'height':'365px'});
			} else {
	jQuery('#gdymc_overlay_window-Link').css({'height':'500px'});	
			}
			
			jQuery('.gdymc_linktabContent').hide();
			jQuery('#gdymc_linktabContent-'+openTab).show();
			jQuery('.gdymc_linktabContent-'+openTab).show();
			
			jQuery('.gdymc_linktab').removeClass('gdymc_Active');
			jQuery(this).addClass('gdymc_Active');
			
		});
		
		
		
		



		
		jQuery('.gdymc_image, .gdymc_gallery_container').click(function(){

			if( jQuery( document.body ).hasClass( 'gdymc_edit' ) ) {

				gdymc.info.activeImage = jQuery(this);
				var width = gdymc.info.activeImage.attr('data-width');
				var height = gdymc.info.activeImage.attr('data-height');
				var image =	gdymc.info.activeImage.attr('data-image');
				var multiple = gdymc.info.activeImage.attr('data-multiple');
				gdymc.info.activeImage.addClass('gdymc_active');
				gdymc.info.activeWidth = width;
				gdymc.info.activeHeight = height;

				var data = {
					w: width,
					h: height,
					i: image,
					m: 'exact',
					multiple: multiple,
				};
				
				gdymc.ajax( 'gdymc_action_imageoverlay', data, function(response) {
					gdymc.info.overlayOpen = true;
					gdymc.info.overlayScroll = jQuery(window).scrollTop();
					gdymc.info.activeImage.removeClass('gdymc_active');
					jQuery('#gdymc_overlay_shadow').show();
					jQuery('body').append('<div class="gdymc_overlay_images gdymc_overlay_window gdymc_inside gdymc_tabs_container">'+response+'</div>');

					setTimeout( function() {

						jQuery('#gdymc_overlay_shadow').addClass( 'gdymc_active' );
						jQuery('.gdymc_overlay_images').addClass( 'gdymc_active' );

						jQuery( '.gdymc_image_selection' ).sortable( {

							axis: 'x',
							update: function( event, ui ) {

								var sortedIDs = new Array();

								jQuery.each( jQuery( '.gdymc_image_selection li' ), function() {

									sortedIDs.push( jQuery.parseJSON( jQuery( this ).attr( 'data-image' ) ) );

								} );

								gdymc_set_selected_images( sortedIDs );

							}

					    } ).disableSelection();

					}, 30 );

				});
	  
			}

		});




		// Image overlay

		function gdymc_get_selected_images() {

			var ci = jQuery.parseJSON( jQuery('#gdymc_imagelist_holder').attr( 'data-ci' ) );

			if( jQuery.isArray( ci ) ) {

				return ci;

			} else {

				return [];

			}			

		}

		function gdymc_set_selected_images( selectedImages ) {

			var button = jQuery( '#gdymc_imageinsert' );

			if( jQuery.isArray(selectedImages) && selectedImages.length > 0  ) {

				if( selectedImages.length > 1  ) {
					var text = gdymc.lang( 'imageinsert-plural' ).replace( '%s', selectedImages.length );
					button.html( text );
			    } else {
			    	button.html( gdymc.lang( 'imageinsert-singular' ) );
			    }

				jQuery('#gdymc_imagelist_holder').attr( 'data-ci', JSON.stringify( selectedImages ) );

			} else {

				button.html( gdymc.lang( 'imageinsert-void' ) );

				jQuery('#gdymc_imagelist_holder').attr( 'data-ci', 'null' );

			}

			gdymc_refresh_image_selection();

		}

		function gdymc_refresh_image_selection() {

			var allowMultiple = jQuery( '#gdymc_imagelist_holder' ).attr( 'data-multiple' );
			var selectedImages = gdymc_get_selected_images();

			jQuery( '.gdymc_imagethumb' ).removeClass( 'gdymc_selected' );

			jQuery.each( selectedImages, function( index, value ) {

				jQuery( '.gdymc_imagethumb[data-id="' + value[0] + '"]' ).addClass( 'gdymc_selected' );

			} );

			gdymc_refresh_image_thumbs();

			gdymc_imageinfo_localsettings_visibility();

		}

		function gdymc_refresh_image_thumbs() {

		    jQuery( '.gdymc_image_selection' ).sortable( 'refresh' );

			var selectedImages = gdymc_get_selected_images();
			var selectedContainer = jQuery( '.gdymc_image_selection' );
			var newThumbs = new Array();

			jQuery.each( selectedImages, function( index, value ) {


				var thumbElement = jQuery( '<li class="gdymc_imagethumb" data-image=\'' + JSON.stringify( value ) + '\' data-id="' + value[0] + '"><div class="gdymc_imagethumb_edit"></div></li>' ).append( jQuery( '.gdymc_mediathumb_' + value[0] ).first().clone() );

				newThumbs.push( thumbElement );


			} );

			selectedContainer.html( '' );

			jQuery.each( newThumbs, function( index, content ) {

				content.appendTo( selectedContainer );

			} );

		}


		function gdymc_remove_from_selection( imageID ) {

			imageID = parseInt( imageID );
			
			// Get selected images
			var selectedImages = gdymc_get_selected_images();

			// Remove clicked from array
			selectedImages = jQuery.grep( selectedImages, function( value, index ) {
				return value[0] != imageID;
			});


			// Set selected image
			gdymc_set_selected_images( selectedImages );


			gdymc_refresh_image_selection();

		}


		function gdymc_add_to_selection( imageID ) {

			imageID = parseInt( imageID );

			// Remove selected if only one is allowed
			if( jQuery( '#gdymc_imagelist_holder' ).attr( 'data-multiple' ) == 'false' ) gdymc_set_selected_images('');

			// Get selected images
			var selectedImages = gdymc_get_selected_images();

			var results = jQuery.grep( selectedImages, function( value, index ) {
			    return value[0] == imageID;
			} );

			if( results.length == 0 ) {

				selectedImages.push( [ imageID, null, null ] );
				gdymc_set_selected_images( selectedImages );
				gdymc_refresh_image_selection();

				return true;

			} else {

				return false;

			}
			

		}








		function gdymc_imageinfo_localsettings_visibility() {

			var selectedImages = gdymc_get_selected_images();
			var editing = jQuery( '#gdymc_overlay_content_imageinfoinner' ).attr( 'data-id' );


			jQuery( '#gdymc_overlay_content_imageinfo_local' ).hide();


			jQuery.each( selectedImages, function( key, value ) {

				if( value[0] == editing ) {

					jQuery( '#gdymc_overlay_content_imageinfo_local' ).show();

					jQuery( '#gdymc_imageinfo_linkurl' ).val( value[1] );

					jQuery( '#gdymc_imageinfo_linktarget' ).prop( 'checked', value[2] );

				}

			} );

		}






		jQuery( document.body ).on( 'click', '#gdymc_imagelist_holder .gdymc_imagethumb', function() {
			
			var current = jQuery( this );

			var currentImageID = current.attr( 'data-id' );
			var currentImageType = current.attr( 'data-type' );
			var currentImageGuid = current.attr( 'data-guid' );

			if( !current.hasClass( 'gdymc_selected' ) ) {

				if( currentImageType == 'exact' ) {			

					// Add to selection
					gdymc_add_to_selection( currentImageID )


				} else if( currentImageType == 'smaller' ) {

					gdymc.functions.error( { 

						text: gdymc.lang( 'image-smaller' ),

					} );

				} else {

					current.addClass( 'gdymc_loading' );

					var cropTable = jQuery('#gdymc_croptable').show().css({'opacity':'0.0'});
					var cropHolder = jQuery('#gdymc_cropholder').html('');
					var cropTarget = jQuery('<img src="'+currentImageGuid+'" />').appendTo('#gdymc_cropholder').attr( 'src', currentImageGuid );
					

					// Assign information

					cropTable.attr( 'data-si', currentImageID );

					cropTable.attr( 'data-tw', gdymc.info.activeWidth );
					cropTable.attr( 'data-th', gdymc.info.activeHeight );

					
					cropTarget.Jcrop({
						minSize: [gdymc.info.activeWidth, gdymc.info.activeHeight],
						aspectRatio: gdymc.info.activeWidth / gdymc.info.activeHeight,
						boxWidth: parseInt(jQuery(window).width())-100,
						boxHeight: parseInt(jQuery(window).height())-100,
						onChange: function(c){
							cropTable.attr( 'data-x', c.x );	
							cropTable.attr( 'data-y', c.y );	
							cropTable.attr( 'data-w', c.w );	
							cropTable.attr( 'data-h', c.h );
						}
					});
					

					function gdymcappendcropbutton() {

						var tracker = jQuery('.jcrop-holder > div > div .jcrop-tracker');

						if( tracker.length > 0 ) {

							tracker.append('<div id="gdymc_cropbutton"></div>');
							cropTable.animate({'opacity':'1.0'},500);
							setTimeout( function() {

								current.removeClass( 'gdymc_loading' );

							}, 200 );
							

						} else {

							setTimeout( function() {

								gdymcappendcropbutton();

							}, 10 );

						}

					} gdymcappendcropbutton();

								
				}

			} else {

				gdymc_remove_from_selection( currentImageID );

			}


		});




		// Remove images from selection

		jQuery( document.body ).on( 'click', '.gdymc_image_selection .gdymc_imagethumb', function() {

			var imageObject = jQuery.parseJSON( jQuery( this ).attr( 'data-image' ) );
			
			gdymc_remove_from_selection( imageObject[0] );
			
		});





		jQuery( document.body ).on( 'click', '.gdymc_imagethumb_edit', function() {

			var current = jQuery( this );
			var imageID = current.parents( '.gdymc_imagethumb' ).attr( 'data-id' );

			current.addClass( 'gdymc_loading' );

			var data = {
				image: imageID,
			};


			gdymc.ajax( 'gdymc_action_imageinfo', data, function( response ) {

				current.removeClass( 'gdymc_loading' );

				jQuery( '#gdymc_overlay_content_imageinfo' ).html( response );

				gdymc_imageinfo_localsettings_visibility();

				jQuery( '.gdymc_overlay_images' ).addClass( 'gdymc_editing_image' );

			} );

			return false;
	
		} );

		jQuery( document.body ).on( 'click', '.gdymc_close_imageinfo', function() {

			jQuery( '.gdymc_overlay_images' ).removeClass( 'gdymc_editing_image' );

			return false;
	
		} );





		jQuery( document.body ).on( 'change', '.gdymc_imageinfo_local_input', function() {

			var current = jQuery( this );
			current.parents( '#gdymc_overlay_content_imageinfo_local' ).addClass( 'gdymc_active' );

			var id = parseInt( jQuery( '#gdymc_overlay_content_imageinfoinner' ).attr( 'data-id' ) );
			var url = jQuery( '#gdymc_imageinfo_linkurl' ).val();
			var target = jQuery( '#gdymc_imageinfo_linktarget' ).prop( 'checked' );

			var selectedImages = gdymc_get_selected_images();

			jQuery.each( selectedImages, function( key, value ) {

				if( value[0] == id ) {

					selectedImages[ key ] = [ id, url, target ];

				}

			} );

			gdymc_set_selected_images( selectedImages );

			setTimeout( function() {

				current.parents( '#gdymc_overlay_content_imageinfo_local' ).removeClass( 'gdymc_active' );

			}, 300 );
			
		});


		jQuery( document.body ).on( 'change', '.gdymc_imageinfo_global_input', function() {

			var current = jQuery( this );
			current.parents( '#gdymc_overlay_content_imageinfo_global' ).addClass( 'gdymc_active' );

			var imageID = jQuery( '#gdymc_overlay_content_imageinfoinner' ).attr( 'data-id' );
			var title = jQuery( '#gdymc_imageinfo_title' ).val();
			var caption = jQuery( '#gdymc_imageinfo_caption' ).val();
			var alt = jQuery( '#gdymc_imageinfo_alt' ).val();
			var description = jQuery( '#gdymc_imageinfo_description' ).val();

			var data = {
				imageID: imageID,
				title: title,
				caption: caption,
				alt: alt,
				description: description,
			};


			gdymc.ajax( 'gdymc_update_attachment_image', data, function( response ) {

				current.parents( '#gdymc_overlay_content_imageinfo_global' ).removeClass( 'gdymc_active' );

			} );
			
			
		});






		


		jQuery('body').on('click', '#gdymc_emptyimage', function(){
			
			gdymc.info.activeImage.attr('data-image', '');	
			gdymc.info.activeImage.html('');
			jQuery('body').addClass('gdymc_unsaved');
			gdymc.info.isSaved = false;
			jQuery('.gdymc_overlay_images').remove();
			jQuery('#gdymc_overlay_shadow').hide();
			gdymc.info.overlayOpen = false;
	
		});
		

		jQuery('body').on( 'click', '#gdymc_imageinsert', function() {
			
			var selectedImages = gdymc_get_selected_images();

			if( selectedImages.length == 0 ) selectedImages = null;

			gdymc.info.activeImage.attr( 'data-image', JSON.stringify( selectedImages ) );

			jQuery( '.gdymc_save' ).trigger( 'click' );

			
		});


		
		jQuery( document.body ).on( 'click', '#gdymc_cropbutton', function(){
			
			jQuery( this ).addClass( 'gdymc_active' );

			var cropTable = jQuery('#gdymc_croptable');

			var data = {
				crop_x: cropTable.attr( 'data-x' ),
				crop_y: cropTable.attr( 'data-y' ),
				crop_w: cropTable.attr( 'data-w' ),
				crop_h: cropTable.attr( 'data-h' ),
				target_w: cropTable.attr( 'data-tw' ),
				target_h: cropTable.attr( 'data-th' ),
				source_id: cropTable.attr( 'data-si' ),
			};


			// Load cursor
			

			gdymc.ajax( 'gdymc_action_cropimage', data, function( response ) {

				if(response[0] != '{') {

					gdymc.functions.error( {
						title: 'Unknown server response',
						text: 'Probably the image is too big',
						details: response,
					} );

					
				} else {

					// Parse response
					attachment = jQuery.parseJSON(response);

					// Add image to selection
					gdymc_add_to_selection( attachment['id'] );			



					jQuery( '.gdymc_overlay_images .gdymc_tabs_button[data-mode="exact"]' ).click();
					jQuery('#gdymc_croptable').hide();					


				}

			} );
			
		});



		// Image search

		function refresh_image_list( responsemode, beforeplace ) {

			if( typeof responsemode === 'undefined' ) responsemode = 'replace';
			if( typeof beforeplace === 'undefined' ) beforeplace = false;

			jQuery( '.gdymc_overlay_images' ).addClass( 'gdymc_loading' );

			var holder = jQuery( '#gdymc_imagelist_holder' );
			var input = jQuery( '#gdymc_search_images' );

			var search = input.val();
			var ci = holder.attr('data-ci');
			var tw = holder.attr('data-tw');
			var th = holder.attr('data-th');
			var tnw = holder.attr('data-tnw');
			var tnh = holder.attr('data-tnh');
			var page = holder.attr('data-p');
			var mode = jQuery( '.gdymc_overlay_images .gdymc_tabs_button.gdymc_active' ).attr( 'data-mode' );

			var data = {
				tw: tw,
				th: th,
				tnw: tnw,
				tnh: tnh,
				ci: ci,
				s: search,
				p: page,
				mode: mode,
				ajax: 1,
			};


			gdymc.ajax( 'gdymc_action_imagelist', data, function(response) {

				if( beforeplace ) beforeplace();

				if( responsemode == 'append' ) {

					jQuery('#gdymc_imagelist_holder').append( response );

				} else {

					jQuery('#gdymc_imagelist_holder').html( response );

				}

				jQuery( '.gdymc_overlay_images' ).removeClass( 'gdymc_loading' );
				gdymc_refresh_image_thumbs();

			});

		}


		// Search refresh

		var gdymc_search_images_timer = null;
		jQuery( document.body ).on( 'input', '#gdymc_search_images', function() {

			var input = jQuery( this );

			clearTimeout( gdymc_search_images_timer );
    		gdymc_search_images_timer = setTimeout( function() {

    			jQuery( '#gdymc_imagelist_holder' ).attr( 'data-p', 1 );
    			refresh_image_list();

    		}, 300 );

		} );


		// Tab refresh

		jQuery( document.body ).on( 'click', '.gdymc_overlay_images .gdymc_tabs_button', function() {
			
			jQuery( '#gdymc_imagelist_holder' ).attr( 'data-p', 1 );
			refresh_image_list();
		
		} );


		// Load more images
		
		jQuery( document.body ).on( 'click', '#gdymc_loadmore_images', function() {
			
			var currentPage = parseInt( jQuery( '#gdymc_imagelist_holder' ).attr( 'data-p' ) );

			jQuery( '#gdymc_imagelist_holder' ).attr( 'data-p', currentPage + 1 );

			refresh_image_list( 'append', function() {
				
				jQuery( '#gdymc_loadmore_images' ).remove();
				
			} );
			
		} );
		







		/*************************** EDITABLE TABLES ***************************/

		function gdymctable_getrows( table ) {
			
			var rows = table.find('tr');
			return rows.length;
			
		}
		
		
		function gdymctable_getcols( table ) {

			var cols = table.find('td');
			return cols.length / gdymctable_getrows( table );
			
		}
		
		function gdymctable_disable() {
			
			jQuery( '.gdymc_table td' ).each( function() {
				jQuery( this ).removeAttr( 'contenteditable' ).removeClass( 'gdymc_editable' );	
			} );
			
		}
		
		
		function gdymctable_enable() {
			
			jQuery( '.gdymc_table td' ).each( function() {
				jQuery( this ).attr( 'contenteditable', 'true' ).addClass( 'gdymc_editable mousetrap' );	
			} );
			
		}

		function gdymctable_getjson( table ) {

			var output = new Array();

			jQuery( table ).find( 'tr' ).each( function() {

				var row = new Array();

				jQuery( this ).find( 'td' ).each( function() {

					row.push( jQuery( this ).html() );

				} );

				output.push( row );

			} );

			return JSON.stringify( output );

		}


		if( jQuery( document.body ).hasClass( 'gdymc_edit' ) ) {
			gdymctable_enable();
			jQuery('.gdymc_text').attr('contenteditable', true).addClass( 'gdymc_editable mousetrap' );
		}


		// Add row button

		jQuery( document.body ).on( 'click', '.gdymc_table_addrow', function() {
			
			currentRow = jQuery('<tr></tr>');

			tableObject = jQuery( this ).parents( '.gdymc_table_container' ).find( '.gdymc_table' );
			columnCount = gdymctable_getcols( tableObject );
			
			tableObject.append( currentRow );

			for( var i = 0; i < columnCount; i++ ) {

				currentRow.append( '<td></td>' );
				jQuery('body').addClass('gdymc_unsaved');
				gdymc.info.isSaved = false;

			}
			
			gdymctable_enable();

		});


		// Remove row button

		jQuery( document.body ).on( 'click', '.gdymc_table_removerow', function() {
			
			tableObject = jQuery( this ).parents( '.gdymc_table_container' ).find( '.gdymc_table' );
			rowCount = gdymctable_getrows( tableObject );

			if( rowCount > 1 ) {

				tableObject.find('tr:last-child').remove();
				jQuery('body').addClass('gdymc_unsaved');
				gdymc.info.isSaved = false;
				
			} else {

				gdymc.functions.error( { 

					text: gdymc.lang( 'removerow-text' ),

				} );

			}

		});


		// Add col button

		jQuery( document.body ).on( 'click', '.gdymc_table_addcol', function() {
			
			tableObject = jQuery( this ).parents( '.gdymc_table_container' ).find( '.gdymc_table' );

			tableObject.find( 'tr' ).each( function() {
				jQuery( this ).append( '<td></td>' );
				jQuery('body').addClass('gdymc_unsaved');
				gdymc.info.isSaved = false;	
			} );
			
			gdymctable_enable();

		});


		// Remove col button

		jQuery( document.body ).on( 'click', '.gdymc_table_removecol', function() {
			
			tableObject = jQuery( this ).parents( '.gdymc_table_container' ).find( '.gdymc_table' );

			columnCount = gdymctable_getcols( tableObject );

			if( columnCount > 1 ) {

				jQuery('body').addClass('gdymc_unsaved');
				gdymc.info.isSaved = false;	
				
				tableObject.find( 'td:last-child' ).each( function() {

					jQuery(this).remove();
					jQuery('body').addClass('gdymc_unsaved');

				} );
			
			} else {

				gdymc.functions.error( { 

					text: gdymc.lang( 'removecol-text' ),

				} );

			}

		});

	












	
	// Tooltip
	jQuery( document.body ).on( 'mouseenter focus', '[data-gdymc-tip]:not(.gdymc_active)', function() {
		
		jQuery( '#gdymc_tooltip' ).remove();
		
		var windowWidth = jQuery(window).width();
		var source = jQuery( this );
		var message = source.attr( 'data-gdymc-tip' );
		var sourceTop = source.offset().top+source.height();
		var sourceLeft = source.offset().left;
		var sourceWidth = source.width();
		var sourceRight = windowWidth - sourceLeft - sourceWidth;

		jQuery( '<div id="gdymc_tooltip" class="gdymc_inside"><div id="gdymc_tooltip_arrow"></div>' + message + '</div>' ).appendTo( 'body' );

		var tooltipWidth = jQuery('#gdymc_tooltip').outerWidth();

		if( ( tooltipWidth + sourceLeft ) > windowWidth ) {
			jQuery( '#gdymc_tooltip' ).addClass( 'gdymc_tooltip_right' ).css( { 'top': sourceTop, 'right': sourceRight } );
		} else {
			jQuery( '#gdymc_tooltip' ).addClass( 'gdymc_tooltip_left' ).css( { 'top': sourceTop, 'left': sourceLeft } );
		}

	});
	
	jQuery( document.body ).on( 'mouseleave mousedown click blur', '[data-gdymc-tip]', function() {
		
		jQuery( '#gdymc_tooltip' ).remove();
		
	});
	
	
	
	// Delete module
	jQuery( document.body ).on( 'click', '.gdymc_delete_module', function() {
				
		var moduleID = jQuery( this ).parents( '.gdymc_module' ).attr( 'data-id' );

		if( gdymc.info.isSaved ) {

			gdymc.functions.error( { 

				title: gdymc.lang( 'deletemodule-title' ),
				text: gdymc.lang( 'deletemodule-text' ),
				buttons: new Array( {

					text: gdymc.lang( 'button-no' ),
					action: function( object ) {

						object.close();

					}

				}, {

					text: gdymc.lang( 'button-yes' ),
					action: function( object ) {

						object.close();

						gdymc.actions.deletemodule( moduleID, true );
						
					}

				} ),

			} );

		} else {

			gdymc.functions.error( { 

				title: gdymc.lang( 'unsaved-title' ),
				text: gdymc.lang( 'unsaved-text' ),
				buttons: new Array( {

					text: gdymc.lang( 'button-no' ),
					action: function( object ) {

						object.close();

					}

				}, {

					text: gdymc.lang( 'button-yes' ),
					action: function( object ) {

						object.close();
						gdymc.info.isSaved = true;

						gdymc.actions.deletemodule( moduleID, true );

					}

				} ),

			} );

		}

	});



	// Delete module type

	jQuery( document.body ).on( 'click', '.gdymc_delete_moduletype', function() {
				
		var moduleType = jQuery( this ).parents( '.gdymc_module' ).attr( 'data-type' );

		if( gdymc.info.isSaved ) {

			gdymc.functions.error( { 

				title: gdymc.lang( 'deletemoduletype-title' ),
				text: gdymc.lang( 'deletemoduletype-text' ),
				buttons: new Array( {

					text: gdymc.lang( 'button-no' ),
					action: function( object ) {

						object.close();

					}

				}, {

					text: gdymc.lang( 'button-yes' ),
					action: function( object ) {

						object.close();

						gdymc.actions.deletemoduletype( moduleType, true );
						
					}

				} ),

			} );

		} else {

			gdymc.functions.error( { 

				title: gdymc.lang( 'unsaved-title' ),
				text: gdymc.lang( 'unsaved-text' ),
				buttons: new Array( {

					text: gdymc.lang( 'button-no' ),
					action: function( object ) {

						object.close();

					}

				}, {

					text: gdymc.lang( 'button-yes' ),
					action: function( object ) {

						object.close();
						gdymc.info.isSaved = true;

						gdymc.actions.deletemoduletype( moduleType, true );

					}

				} ),

			} );

		}

	});




	// Change module type

	jQuery( document.body ).on( 'click', '.gdymc_change_moduletype', function() {
				
		var old_module = jQuery( this ).parents( '.gdymc_module' ).attr( 'data-type' );

		if( gdymc.info.isSaved ) {

			gdymc.functions.error( { 

				title: gdymc.lang( 'changemoduletype-title' ),
				text: gdymc.lang( 'changemoduletype-text' ),
				buttons: new Array( {

					text: gdymc.lang( 'button-cancel' ),
					action: function( object ) {

						object.close();

					}

				}, {

					text: gdymc.lang( 'button-change' ),
					action: function( object ) {

						var new_module = jQuery( '#gdymc_error_window select' ).val();

						object.close();

						gdymc.actions.changemoduletype( old_module, new_module, true );
						
					}

				} ),

			} );

		} else {

			gdymc.functions.error( { 

				title: gdymc.lang( 'unsaved-title' ),
				text: gdymc.lang( 'unsaved-text' ),
				buttons: new Array( {

					text: gdymc.lang( 'button-no' ),
					action: function( object ) {

						object.close();

					}

				}, {

					text: gdymc.lang( 'button-yes' ),
					action: function( object ) {

						var new_module = jQuery( '#gdymc_error_window select' ).val();

						object.close();
						gdymc.info.isSaved = true;

						gdymc.actions.changemoduletype( old_module, new_module, true );

					}

				} ),

			} );

		}

	});







	// Change single module type

	jQuery( document.body ).on( 'change', '.gdymc_change_single_moduletype', function() {
		
		var select = jQuery( this );
		var module_id = jQuery( this ).parents( '.gdymc_module' ).attr( 'data-id' );
		var old_val = jQuery( this ).parents( '.gdymc_module' ).attr( 'data-type' );
		var new_val = select.val();

		if( gdymc.info.isSaved ) {

			gdymc.functions.error( { 

				title: gdymc.lang( 'changesinglemoduletype-title' ),
				text: gdymc.lang( 'changesinglemoduletype-text' ),
				buttons: new Array( {

					text: gdymc.lang( 'button-cancel' ),
					action: function( object ) {

						object.close();

					}

				}, {

					text: gdymc.lang( 'button-change' ),
					action: function( object ) {

						object.close();

						select.val( new_val );
						gdymc.actions.changesinglemoduletype( module_id, new_val, true );
						
					}

				} ),
				onClose: function() {
					select.val( old_val );
				}

			} );

		} else {

			gdymc.functions.error( { 

				title: gdymc.lang( 'unsaved-title' ),
				text: gdymc.lang( 'unsaved-text' ),
				buttons: new Array( {

					text: gdymc.lang( 'button-no' ),
					action: function( object ) {

						object.close();

					}

				}, {

					text: gdymc.lang( 'button-yes' ),
					action: function( object ) {

						object.close();
						gdymc.info.isSaved = true;

						select.val( new_val );
						gdymc.actions.changesinglemoduletype( module_id, new_val, true );

					}

				} ),
				onClose: function() {
					select.val( old_val );
				}

			} );

		}

	});


	
	
	
	// Add module

	jQuery( document.body ).on( 'click touchstart', '.gdymc_modules_add_button', function() {
		
		var currentButton = jQuery( this );
		var moduleType = currentButton.attr( 'data-type' );

		var data = {
			type: moduleType,
			object_id: gdymc_dynamic_data.object_id,
			object_type: gdymc_dynamic_data.object_type,
		}


		if( gdymc.info.isSaved ) {

			currentButton.addClass( 'active' );
			

			gdymc.ajax( 'gdymc_action_addmodule', data, function( response ) {
				
				jQuery( document.body ).removeClass( 'gdymc_softpreview' );

				window.location.href = window.location.href.split('#')[0];

			} );

		} else {

			gdymc.functions.error( { 

				title: gdymc.lang( 'unsaved-title' ),
				text: gdymc.lang( 'unsaved-text' ),
				buttons: new Array( {

					text: gdymc.lang( 'button-no' ),
					action: function( object ) {

						object.close();

					}

				}, {

					text: gdymc.lang( 'button-yes' ),
					action: function( object ) {

						currentButton.addClass( 'active' );


						object.close();
						gdymc.info.isSaved = true;

						gdymc.ajax( 'gdymc_action_addmodule', data, function( response ) {
							
							wpCookies.remove( 'gdymc_softpreview', gdymc_dynamic_data.cookie_path, gdymc_dynamic_data.cookie_domain );
							window.location.href = window.location.href.split('#')[0];
							
						} );

					}

				} ),

			} );

		}

	});
		
		


		
	// Saving contents
	
	jQuery( document.body ).on( 'click', '.gdymc_save', function() {
		


		// Save scroll position

		wpCookies.set( 'gdymc_scrollpos', jQuery( window ).scrollTop(), 3600 * 24, gdymc_dynamic_data.cookie_path, gdymc_dynamic_data.cookie_domain );




		// Check if there is a maxtext error

		if( jQuery( '.gdymc_maxtext' ).length > 0 ) {
		
			
			// Alert maxtext error

			alert( gdymc.lang( 'maxtext-error' ) );
		
		
		} else {
			


			// Trigger jQuery gdymc_save event

			jQuery.event.trigger( 'gdymc_save' );

			

			// Data containers
			
			var contents = new Array();
			var modules = new Array();
			var options = new Array();
			
			
			
			// Set to "is saving"
			
			gdymc.info.isSaving = true;
	
			jQuery( document.body ).addClass( 'gdymc_saving' );
	
	
			
			// Gather text
			
			jQuery('.gdymc_text').each(function(){
			
				var handler = new Array();
				var id = jQuery(this).attr('data-id');
				var content = jQuery(this).html();
				
				handler.push( id );
				handler.push( content );
				
				contents.push( handler );
			
			});
	
	
			
			// Gather images
			
			jQuery( '.gdymc_image, .gdymc_gallery_container' ).each( function() {
				
				var handler = new Array();
				var id = jQuery(this).attr('data-id');
				var content = jQuery(this).attr('data-image');

				if( content == 'null' ) content = '';

				handler.push( id );
				handler.push( content );
			
				contents.push( handler );
				
			
			} );



			// Gather tables
			
			jQuery( '.gdymc_table' ).each( function() {
			    
				var handler = new Array();
				var id = jQuery( this ).attr( 'data-id' );
				var content = gdymctable_getjson( this );

				handler.push( id );
				handler.push( content );
				
				contents.push( handler );
			
			} );


			// Gather buttongroups
			
			jQuery( '.gdymc_button-group' ).each( function() {
			    
				var handler = new Array();
				var id = jQuery(this).attr('data-id');
				var content = jQuery(this).html();
				
				handler.push( id );
				handler.push( content );
				
				contents.push( handler );
			
			} );
	
			
			
			// Gather modules
			
			jQuery( '.gdymc_module' ).each( function() {
			
				var id = jQuery(this).attr('data-id');
				modules.push(id);
			
			} );
	
			
			
			// Gather options
			
			jQuery( '.gdymc_option' ).each( function() {
				
				var handler = new Array();
				var name = jQuery( this ).attr('data-name');
				var module = jQuery( this ).attr('data-module');

				if( jQuery( this ).hasClass( 'gdymc_option-editable' ) ) {

					var value = jQuery( this ).html();

				} else {

					var value = jQuery( this ).val();
					
				}
				
				handler.push(name);
				handler.push(value);
				handler.push(module);
				
				options.push(handler);
			
			} );
			
			
			
			// Convert into JSON
			
			contents = JSON.stringify( contents );
			modules = JSON.stringify( modules );
			options = JSON.stringify( options );

			
			
			// Prepare AJAX data
			
			var data = {
				
				contents: contents,
				modules: modules,
				options: options,
				object_id: gdymc_dynamic_data.object_id,
				object_type: gdymc_dynamic_data.object_type
				
			};


			gdymc.ajax( 'gdymc_action_save', data, function( response ) {
					
				window.location.href = window.location.href.split('#')[0];
				
			} );
			
			
	
		}
	
	
	});


	
		
	// Check for content changes

	jQuery( '.gdymc_editable, .gdymc_option' ).bind( 'input propertychange change', function() {

		jQuery( document.body ).addClass( 'gdymc_unsaved' );

		gdymc.info.isSaved = false;

	} );


	// Option enter

	jQuery( '.gdymc_option' ).keyup( function( e ) {

		if( e.keyCode == 13 && !jQuery( this ).is( 'textarea' )  ) {
        
			jQuery( '#gdymc_save' ).click();

    	}

	} );




		
		
		
		
	// Scroll to the Center of an Element
	function gdymc_CenterScroll(element){
					
		var elementOffset = element.offset().top;
		var elementHeight = element.height();
		var windowHeight = jQuery(window).height();
		var scrollTarget;
		
		if (elementHeight < windowHeight) {
			scrollTarget = elementOffset - ((windowHeight / 2) - (elementHeight / 2));
		} else {
			scrollTarget = elementOffset;
		}
		
		gdymc.info.blockScroll = false;
		
		jQuery('html,body').stop().animate({scrollTop: scrollTarget}, 0);
		
		setTimeout(function(){
			gdymc.info.blockScroll = true;
		}, 10);
				
	}
	




	/******************************** BATCH EDITING ********************************/


	// // Calculate selected modules amount

	// function gdymc_recalculateSelectedModuleAmount() {

	// 	var markedModules = jQuery( '.gdymc_module.gdymc_active' ).length;
	// 	var allModules = jQuery( '.gdymc_module' ).length;

	// 	jQuery( '#gdymc_module_batch_number' ).html( markedModules + '<span id="gdymc_module_batch_slash">/</span>' + allModules );

	// 	if( markedModules > 0 ) {

	// 		jQuery( '#gdymc_module_batch' ).show();

	// 	} else {

	// 		jQuery( '#gdymc_module_batch' ).hide();

	// 	}

	// }


	// // Get selected module IDs

	// function gdymc_getSelectedModuleIDs() {

	// 	var selectedModules = new Array();

	// 	jQuery( '.gdymc_module.gdymc_active' ).each( function() {

	// 		selectedModules.push( jQuery( this ).attr( 'data-id' ) );

	// 	} );

	// 	return selectedModules;

	// }


	// // Select single module

	// jQuery( document.body ).on( 'click', '.gdymc_select_module', function( event ) {
	
	// 	var currentModule = jQuery(this).parents('.gdymc_module');
		
	// 	if( currentModule.hasClass( 'gdymc_active' ) ) {

	// 		currentModule.removeClass( 'gdymc_active' );

	// 	} else {

	// 		currentModule.addClass( 'gdymc_active' );

	// 	}

	// 	gdymc_recalculateSelectedModuleAmount();
	
	// } );



	// // Open batch window

	// jQuery( document.body ).on( 'click', '#gdymc_module_batch', function( event ) {
        	
	// 	gdymc.ajax( 'gdymc_action_modulebatch', null, function(response) {

	// 		gdymc.info.overlayOpen = true;
	// 		gdymc.info.overlayScroll = jQuery(window).scrollTop();
	// 		jQuery('#gdymc_overlay_shadow').show();
	// 		jQuery('body').append('<div class="gdymc_overlay_batch gdymc_overlay_window gdymc_inside gdymc_tabs_container">'+response+'</div>');
	// 		jQuery('#gdymc_insertlink_input').focus();

	// 		setTimeout( function() {
	// 			jQuery('#gdymc_overlay_shadow').addClass( 'gdymc_active' );
	// 			jQuery('.gdymc_overlay_batch').addClass( 'gdymc_active' );
	// 		}, 1 );

	// 	} );
		
	// } );


	// // Select all modules

	// jQuery( document.body ).on( 'click', '#gdymc_batch_action_select_all', function( event ) {
        	
	// 	jQuery( '.gdymc_module' ).addClass( 'gdymc_active' );

	// 	gdymc_recalculateSelectedModuleAmount();
		
	// } );


	// // Unselect all modules

	// jQuery( document.body ).on( 'click', '#gdymc_batch_action_select_nothing', function( event ) {
        	
	// 	jQuery( '.gdymc_module' ).removeClass( 'gdymc_active' );

	// 	gdymc_recalculateSelectedModuleAmount();
		
	// } );

	// // Select all visible modules

	// jQuery( document.body ).on( 'click', '#gdymc_batch_action_select_visible', function( event ) {
        	
	// 	jQuery( '.gdymc_module.gdymc_visible' ).addClass( 'gdymc_active' );

	// 	gdymc_recalculateSelectedModuleAmount();
		
	// } );

	// // Select all invisible modules

	// jQuery( document.body ).on( 'click', '#gdymc_batch_action_select_invisible', function( event ) {
        	
	// 	jQuery( '.gdymc_module.gdymc_invisible' ).addClass( 'gdymc_active' );

	// 	gdymc_recalculateSelectedModuleAmount();
		
	// } );


	// // Delete all selected modules

	// jQuery( document.body ).on( 'click', '#gdymc_batch_action_delete', function( event ) {

	// 	var moduleIDs = gdymc_getSelectedModuleIDs().join();

	// 	if( !moduleIDs ) {

	// 		gdymc.functions.error( { 

	// 			title: gdymc.lang( 'batchselectmodule-title' ),
	// 			text: gdymc.lang( 'batchselectmodule-text' ),
	// 		} );

	// 	} else if( gdymc.info.isSaved ) {

	// 		gdymc.functions.error( { 

	// 			title: gdymc.lang( 'deletemodule-title' ),
	// 			text: gdymc.lang( 'deletemodule-text' ),
	// 			buttons: new Array( {

	// 				text: gdymc.lang( 'button-no' ),
	// 				action: function( object ) {

	// 					object.close();

	// 				}

	// 			}, {

	// 				text: gdymc.lang( 'button-yes' ),
	// 				action: function( object ) {

	// 					object.close();

	// 					gdymc.actions.deletemodule( moduleIDs, true );
						
	// 				}

	// 			} ),

	// 		} );

	// 	} else {

	// 		gdymc.functions.error( { 

	// 			title: gdymc.lang( 'unsaved-title' ),
	// 			text: gdymc.lang( 'unsaved-text' ),
	// 			buttons: new Array( {

	// 				text: gdymc.lang( 'button-no' ),
	// 				action: function( object ) {

	// 					object.close();

	// 				}

	// 			}, {

	// 				text: gdymc.lang( 'button-yes' ),
	// 				action: function( object ) {

	// 					object.close();
	// 					gdymc.info.isSaved = true;

	// 					gdymc.actions.deletemodule( moduleIDs, true );

	// 				}

	// 			} ),

	// 		} );

	// 	}

	// });
	
	

	// // Move module

	// jQuery( document.body ).on( 'click', '#gdymc_batch_action_move', function( event ) {
        

 //        var moduleIDs = gdymc_getSelectedModuleIDs().join();
 //        var targetID = jQuery( '#gdymc_batch_action_target_input' ).val();

 //        if( !moduleIDs ) {

	// 		gdymc.functions.error( { 

	// 			title: gdymc.lang( 'batchselectmodule-title' ),
	// 			text: gdymc.lang( 'batchselectmodule-text' ),
	// 		} );

	// 	} else if( targetID.length <= 0 ) {

	// 		gdymc.functions.error( { 

	// 			title: gdymc.lang( 'batchnoid-title' ),
	// 			text: gdymc.lang( 'batchnoid-text' ),
	// 		} );

	// 	} else if( !jQuery.isNumeric( targetID ) ) {

	// 		gdymc.functions.error( { 

	// 			title: gdymc.lang( 'batchnumericid-title' ),
	// 			text: gdymc.lang( 'batchnumericid-text' ),
	// 		} );

	// 	} else {


	// 		var data = {
	// 			object_id: gdymc_dynamic_data.object_id,
	// 			object_type: gdymc_dynamic_data.object_type,
	// 			modules: moduleIDs,
	// 			target: targetID,
	// 		}

	// 		gdymc.ajax( 'gdymc_action_movemodules', data, function( response ) {

	// 			if (response.substring(0, 3) == "_e:") {
				    
	// 				gdymc.functions.error( { title: gdymc.lang( 'batchmove-title' ), text: gdymc.lang( 'batchmove-text' ) } );

	// 			} else {

	// 				window.location.href = response;

	// 			}		
				
	// 		});

	// 	}

		
	// } );


	// // Copy module

	// jQuery( document.body ).on( 'click', '#gdymc_batch_action_copy', function( event ) {
        

 //        var moduleIDs = gdymc_getSelectedModuleIDs().join();
 //        var targetID = jQuery( '#gdymc_batch_action_target_input' ).val();

 //        if( !moduleIDs ) {

	// 		gdymc.functions.error( { 

	// 			title: gdymc.lang( 'batchselectmodule-title' ),
	// 			text: gdymc.lang( 'batchselectmodule-text' ),
	// 		} );

	// 	} else if( targetID.length <= 0 ) {

	// 		gdymc.functions.error( { 

	// 			title: gdymc.lang( 'batchnoid-title' ),
	// 			text: gdymc.lang( 'batchnoid-text' ),
	// 		} );

	// 	} else if( !jQuery.isNumeric( targetID ) ) {

	// 		gdymc.functions.error( { 

	// 			title: gdymc.lang( 'batchnumericid-title' ),
	// 			text: gdymc.lang( 'batchnumericid-text' ),
	// 		} );

	// 	} else {


	// 		var data = {
	// 			object_id: gdymc_dynamic_data.object_id,
	// 			object_type: gdymc_dynamic_data.object_type,
	// 			modules: moduleIDs,
	// 			target: targetID,
	// 		}

	// 		gdymc.ajax( 'gdymc_action_copymodules', data, function( response ) {

	// 			alert( response );

	// 			if (response.substring(0, 3) == "_e:") {
				    
	// 				gdymc.functions.error( { title: gdymc.lang( 'batchmove-title' ), text: gdymc.lang( 'batchmove-text' ) } );

	// 			} else {

	// 				window.location.href = response;

	// 			}		
				
	// 		});

	// 	}
		
		
	// } );










		
	// Move module up

	jQuery('.gdymc_moduleposition_moveup').click(function(e){
	
		var currentModule = jQuery(this).parents('.gdymc_module');
		
		if(currentModule.prev().length > 0) {
			
			currentModule.insertBefore(currentModule.prev());
			gdymc_CenterScroll(currentModule);
			jQuery('body').addClass('gdymc_unsaved');
			gdymc.info.isSaved = false;
			
		}
	
	});
		
		
	// Move module down

	jQuery('.gdymc_moduleposition_movedown').click(function(e){
	
		var currentModule = jQuery(this).parents('.gdymc_module');
		
		if(currentModule.next().length > 0) {
			currentModule.insertAfter(currentModule.next());
			gdymc_CenterScroll(currentModule);
			jQuery('body').addClass('gdymc_unsaved');
			gdymc.info.isSaved = false;
		}
		
	});
	

	// Show module list

	jQuery('#gdymc_showmodules').click(function(){
		
		if( jQuery( '#gdymc_modules' ).hasClass( 'gdymc_active' ) ) {

			jQuery( '#gdymc_showmodules' ).removeClass( 'gdymc_active' );
			jQuery( '#gdymc_modules' ).removeClass( 'gdymc_active' );

		} else {

			setTimeout( function() {

				jQuery( '#gdymc_modules_search' ).focus();

			}, 50 );
				
			jQuery( '#gdymc_showmodules' ).addClass( 'gdymc_active' );
			jQuery( '#gdymc_modules' ).addClass( 'gdymc_active' );
				
		}

	});


	// Module scroller

	jQuery( '#gdymc_modules_inner' ).kinetic();
	jQuery( '#gdymc_dropzone_preview' ).kinetic();


	// Module search

	jQuery( '#gdymc_modules_search' ).fastLiveFilter( '#gdymc_modules_inner', {

		callback: function( total ) {

			if( total == 0 ) {

				if( jQuery( '#gdymc_modules_noresults' ).length == 0 ) jQuery( '#gdymc_modules_inner' ).append( '<div id="gdymc_modules_noresults">' + gdymc.lang( 'nomodulesfound' ) + '</div>' );

			} else {

				jQuery( '#gdymc_modules_noresults' ).remove();

			}

		}

	} );
		
		
		


		// Adminbar dropdown

		jQuery( '.gdymc_dropdown_trigger' ).click( function() {
			
			jQuery( this ).addClass( 'gdymc_active' );
			jQuery( this ).children( '.gdymc_dropdown' ).addClass( 'gdymc_active' );
			
		} );

		

		jQuery( document ).on( 'mousedown', function(e) {

			
			jQuery( '.gdymc_dropdown_trigger' ).each( function() {

				var container = jQuery( this );

				if( !container.is(e.target) && container.has(e.target).length === 0 ) {

					container.removeClass( 'gdymc_active' );
					container.children( '.gdymc_dropdown' ).removeClass( 'gdymc_active' );

				}

			} );

			
			
		} );


		

		




		jQuery( '#gdymc_togglesoftpreview' ).click(function(){
			
			if( jQuery( document.body ).hasClass( 'gdymc_softpreview' ) ) {

				gdymc.functions.disable_softpreview();
				gdymctable_enable();
			
			} else {

				gdymc.functions.enable_softpreview();
				gdymctable_disable();

			}

			return false;
			
		});

		

		
		
		
		jQuery(document).on('mousedown', function (e){
			
			var container = jQuery('#gdymc_modules');
			var container2 = jQuery('#gdymc_showmodules');
			
			if((!container.is(e.target) && container.has(e.target).length === 0) && (!container2.is(e.target) && container2.has(e.target).length === 0)) {
				container.removeClass( 'gdymc_active' );
				container2.removeClass( 'gdymc_active' );
			}
			
			var container = jQuery('.jcrop-holder');
		
			if((!container.is(e.target) && container.has(e.target).length === 0)){
				jQuery('#gdymc_croptable').hide();
			}
			
			var container = jQuery( '#gdymc_dropzone_uploader_container' );
		
			if((!container.is(e.target) && container.has(e.target).length === 0)){
				container.removeClass( 'gdymc_active' );
			}
			
		});
		

		
	}); // End function