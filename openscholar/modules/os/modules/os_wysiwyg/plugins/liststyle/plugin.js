(function ($) {

// @todo Array syntax required; 'break' is a predefined token in JavaScript.
	Drupal.wysiwyg.plugins['liststyle'] = {

		/**
		 * Return whether the passed node belongs to this plugin.
		 */
		isNode: function(node) {
			//return ($(node).is('img.wysiwyg-break'));
			return false;
		},

		/**
		 * Execute the button.
		 */
		invoke: function(data, settings, instanceId) {

			//if (data.format == 'html') {
			//	// Prevent duplicating a teaser break.
			//	if ($(data.node).is('img.wysiwyg-break')) {
			//		return;
			//	}
			//	var content = this._getPlaceholder(settings);
			//}
			//else {
			//	// Prevent duplicating a teaser break.
			//	// @todo data.content is the selection only; needs access to complete content.
			//	if (data.content.match(/<!--break-->/)) {
			//		return;
			//	}
			//	var content = '<!--break-->';
			//}
			//if (typeof content != 'undefined') {
			//	Drupal.wysiwyg.instances[instanceId].insert(content);
			//}
		},

		/**
		 * Replace all <!--break--> tags with images.
		 */
		attach: function(content, settings, instanceId) {
			//content = content.replace(/<!--break-->/g, this._getPlaceholder(settings));

			var editor = CKEDITOR.instances[instanceId];

			if ( editor.blockless )
				return;

			var def, cmd;

			//debugger;

			def = new CKEDITOR.dialogCommand( 'numberedListStyle', {
				requiredContent: 'ol',
				allowedContent: 'ol{list-style-type}[start]'
			} );
			cmd = editor.addCommand( 'numberedListStyle', def );
			editor.addFeature( cmd );
			CKEDITOR.dialog.add( 'numberedListStyle', this.path + 'dialogs/liststyle.js' );

			def = new CKEDITOR.dialogCommand( 'bulletedListStyle', {
				requiredContent: 'ul',
				allowedContent: 'ul{list-style-type}'
			} );
			cmd = editor.addCommand( 'bulletedListStyle', def );
			editor.addFeature( cmd );
			CKEDITOR.dialog.add( 'bulletedListStyle', this.path + 'dialogs/liststyle.js' );

			//Register map group;
			editor.addMenuGroup( 'list', 108 );

			editor.addMenuItems( {
				numberedlist: {
					label: Drupal.t('Numbered List Properties'),
					group: 'list',
					command: 'numberedListStyle'
				},
				bulletedlist: {
					label: Drupal.t('Bulleted List Properties'),
					group: 'list',
					command: 'bulletedListStyle'
				}
			} );

			editor.contextMenu.addListener( function( element ) {
				if ( !element || element.isReadOnly() )
					return null;

				while ( element ) {
					var name = element.getName();
					if ( name == 'ol' )
						return { numberedlist: CKEDITOR.TRISTATE_OFF };
					else if ( name == 'ul' )
						return { bulletedlist: CKEDITOR.TRISTATE_OFF };

					element = element.getParent();
				}
				return null;
			} );
			return content;
		},

		/**
		 * Replace images with <!--break--> tags in content upon detaching editor.
		 */
		detach: function(content, settings, instanceId) {
			//var $content = $('<div>' + content + '</div>'); // No .outerHTML() in jQuery :(
			// #404532: document.createComment() required or IE will strip the comment.
			// #474908: IE 8 breaks when using jQuery methods to replace the elements.
			// @todo Add a generic implementation for all Drupal plugins for this.
			//$.each($('img.wysiwyg-break', $content), function (i, elem) {
			//	elem.parentNode.insertBefore(document.createComment('break'), elem);
			//	elem.parentNode.removeChild(elem);
			//});
			//return $content.html();
			return content;
		},

		/**
		* Helper function to return a HTML placeholder.
		*/
		_getPlaceholder: function (settings) {
			return '<img src="' + settings.path + '/images/spacer.gif" alt="&lt;--break-&gt;" title="&lt;--break--&gt;" class="wysiwyg-break drupal-content" />';
		}
	};

})(jQuery);
