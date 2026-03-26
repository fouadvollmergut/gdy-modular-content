
	/*
	
		@name GDYMC TinyMCE Integration
		@author GDY Modular Content

	*/

	// Initialize TinyMCE for contenteditable divs
	gdymc.editor.initTinyMCE = function() {
		
		// Remove TinyMCE instances if they exist
		if (typeof tinymce !== 'undefined') {
			tinymce.remove('.gdymc_text');
		}

		// Only initialize in edit mode
		if (!jQuery('body').hasClass('gdymc_edit')) {
			return;
		}

		// Get all contenteditable elements
		var editableElements = document.querySelectorAll('.gdymc_text[contenteditable="true"]');
		
		if (editableElements.length === 0) {
			return;
		}

		// Initialize TinyMCE for each element
		editableElements.forEach(function(element) {
			
			// Skip if already initialized
			if (element.classList.contains('gdymc_tinymce_initialized')) {
				return;
			}

			// Add a unique ID if it doesn't have one
			if (!element.id) {
				element.id = 'gdymc_text_' + Math.random().toString(36).substr(2, 9);
			}

			// Initialize TinyMCE in inline mode without its own toolbar
			tinymce.init({
				target: element,
				inline: true,
				menubar: false,
				toolbar: false,
				statusbar: false,
				plugins: 'lists link',
				skin_url: gdymc_dynamic_data.tinymce_skin_url,
				theme_url: gdymc_dynamic_data.tinymce_theme_url,
				content_css: false,
				contextmenu: false,
				setup: function(editor) {
					
					// Mark as initialized
					editor.on('init', function() {
						element.classList.add('gdymc_tinymce_initialized');
						
						// Trigger change event for gdymc
						editor.on('change keyup setcontent', function() {
							document.body.classList.add('gdymc_unsaved');
							gdymc.info.isSaved = false;
						});
					});

					// Handle focus/blur for overlay compatibility
					editor.on('focus', function() {
						gdymc.selection.save();
					});

					editor.on('blur', function() {
						gdymc.selection.save();
					});

				}
			});

		});

	};

	// Override the existing format function to work with TinyMCE
	var originalFormat = gdymc.editor.format;
	
	gdymc.editor.format = function(command, value) {
		
		// Check if we're in a TinyMCE editor
		var activeEditor = tinymce.activeEditor;
		
		if (activeEditor && jQuery(activeEditor.getElement()).hasClass('gdymc_text')) {
			
			// Use TinyMCE commands
			switch(command) {
				case 'bold':
					activeEditor.execCommand('Bold');
					break;
				case 'italic':
					activeEditor.execCommand('Italic');
					break;
				case 'insertunorderedlist':
					activeEditor.execCommand('InsertUnorderedList');
					break;
				case 'insertorderedlist':
					activeEditor.execCommand('InsertOrderedList');
					break;
				case 'justifyleft':
					activeEditor.execCommand('JustifyLeft');
					break;
				case 'justifycenter':
					activeEditor.execCommand('JustifyCenter');
					break;
				case 'justifyright':
					activeEditor.execCommand('JustifyRight');
					break;
				case 'unlink':
					activeEditor.execCommand('Unlink');
					break;
				case 'superscript':
					activeEditor.execCommand('Superscript');
					break;
				case 'subscript':
					activeEditor.execCommand('Subscript');
					break;
				case 'removeformat':
					activeEditor.execCommand('RemoveFormat');
					break;
				case 'insertText':
					activeEditor.execCommand('mceInsertContent', false, value);
					break;
				default:
					// Fallback to original format function
					originalFormat(command, value);
			}
			
		} else {
			// Use original format function
			originalFormat(command, value);
		}
		
	};

	// Override the existing addtag function to work with TinyMCE for link insertion
	var originalAddTag = gdymc.editor.addtag;
	
	gdymc.editor.addtag = function(tag, attributes) {
		
		// Check if we're in a TinyMCE editor
		var activeEditor = tinymce.activeEditor;
		
		if (activeEditor && jQuery(activeEditor.getElement()).hasClass('gdymc_text') && tag === 'a') {
			
			// Use TinyMCE to insert link
			var linkAttrs = {
				href: attributes.href || '',
				target: attributes.target || '',
				class: attributes.className || ''
			};
			
			// Insert/update link using TinyMCE
			activeEditor.execCommand('mceInsertLink', false, linkAttrs);
			
		} else {
			// Use original addtag function
			originalAddTag(tag, attributes);
		}
		
	};

	// Initialize TinyMCE when DOM is ready
	jQuery(document).ready(function() {
		
		// Wait for tinymce to be loaded
		var checkTinyMCE = setInterval(function() {
			if (typeof tinymce !== 'undefined') {
				clearInterval(checkTinyMCE);
				gdymc.editor.initTinyMCE();
			}
		}, 100);

		// Reinitialize when entering edit mode
		jQuery(document).on('gdymc_disable_softpreview', function() {
			setTimeout(function() {
				gdymc.editor.initTinyMCE();
			}, 100);
		});

		// Remove TinyMCE when entering preview mode
		jQuery(document).on('gdymc_enable_softpreview', function() {
			if (typeof tinymce !== 'undefined') {
				tinymce.remove('.gdymc_text');
			}
		});

	});

