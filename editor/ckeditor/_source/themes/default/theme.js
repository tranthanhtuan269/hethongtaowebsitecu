/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @name CKEDITOR.theme
 * @class
 */

CKEDITOR.themes.add( 'default', (function()
{
	function checkSharedSpace( editor, spaceName )
	{
		var container,
			element;

		// Try to retrieve the target element from the sharedSpaces settings.
		element = editor.config.sharedSpaces;
		element = element && element[ spaceName ];
		element = element && CKEDITOR.document.getById( element );

		// If the element is available, we'll then create the container for
		// the space.
		if ( element )
		{
			// Creates an HTML structure that reproduces the editor class hierarchy.
			var html =
				'<span class="cke_shared "' +
				' dir="'+ editor.lang.dir + '"' +
				'>' +
				'<span class="' + editor.skinClass + ' ' + editor.id + ' cke_editor_' + editor.name + '">' +
				'<span class="' + CKEDITOR.env.cssClass + '">' +
				'<span class="cke_wrapper cke_' + editor.lang.dir + '">' +
				'<span class="cke_editor">' +
				'<div class="cke_' + spaceName + '">' +
				'</div></span></span></span></span></span>';

			var mainContainer = element.append( CKEDITOR.dom.element.createFromHtml( html, element.getDocument() ) );

			// Only the first container starts visible. Others get hidden.
			if ( element.getCustomData( 'cke_hasshared' ) )
				mainContainer.hide();
			else
				element.setCustomData( 'cke_hasshared', 1 );

			// Get the deeper inner <div>.
			container = mainContainer.getChild( [0,0,0,0] );

			// Save a reference to the shared space container.
			!editor.sharedSpaces && ( editor.sharedSpaces = {} );
			editor.sharedSpaces[ spaceName ] = container;

			// When the editor gets focus, we show the space container, hiding others.
			editor.on( 'focus', function()
				{
					for ( var i = 0, sibling, children = element.getChildren() ; ( sibling = children.getItem( i ) ) ; i++ )
					{
						if ( sibling.type == CKEDITOR.NODE_ELEMENT
							&& !sibling.equals( mainContainer )
							&& sibling.hasClass( 'cke_shared' ) )
						{
							sibling.hide();
						}
					}

					mainContainer.show();
				});

			editor.on( 'destroy', function()
				{
					mainContainer.remove();
				});
		}

		return container;
	}

	return /** @lends CKEDITOR.theme */ {
		build : function( editor, themePath )
		{
			var name = editor.name,
				element = editor.element,
				elementMode = editor.elementMode;

			if ( !element || elementMode == CKEDITOR.ELEMENT_MODE_NONE )
				return;

			if ( elementMode == CKEDITOR.ELEMENT_MODE_REPLACE )
				element.hide();

			// Get the HTML for the predefined spaces.
			var topHtml			= editor.fire( 'themeSpace', { space : 'top', html : '' } ).html;
			var contentsHtml	= editor.fire( 'themeSpace', { space : 'contents', html : '' } ).html;
			var bottomHtml		= editor.fireOnce( 'themeSpace', { space : 'bottom', html : '' } ).html;

			var height	= contentsHtml && editor.config.height;

			var tabIndex = editor.config.tabIndex || editor.element.getAttribute( 'tabindex' ) || 0;

			// The editor height is considered only if the contents space got filled.
			if ( !contentsHtml )
				height = 'auto';
			else if ( !isNaN( height ) )
				height += 'px';

			var style = '';
			var width	= editor.config.width;

			if ( width )
			{
				if ( !isNaN( width ) )
					width += 'px';

				style += "width: " + width + ";";
			}

			var sharedTop		= topHtml && checkSharedSpace( editor, 'top' ),
				sharedBottoms	= checkSharedSpace( editor, 'bottom' );

			sharedTop		&& ( sharedTop.setHtml( topHtml )		, topHtml = '' );
			sharedBottoms	&& ( sharedBottoms.setHtml( bottomHtml ), bottomHtml = '' );

			var container = CKEDITOR.dom.element.createFromHtml( [
				'<span' +
					' id="cke_', name, '"' +
					' class="', editor.skinClass, ' ', editor.id, ' cke_editor_', name, '"' +
					' dir="', editor.lang.dir, '"' +
					' title="', ( CKEDITOR.env.gecko ? ' ' : '' ), '"' +
					' lang="', editor.langCode, '"' +
						( CKEDITOR.env.webkit? ' tabindex="' + tabIndex + '"' : '' ) +
					' role="application"' +
					' aria-labelledby="cke_', name, '_arialbl"' +
					( style ? ' style="' + style + '"' : '' ) +
					'>' +
					'<span id="cke_', name, '_arialbl" class="cke_voice_label">' + editor.lang.editor + '</span>' +
					'<span class="' , CKEDITOR.env.cssClass, '" role="presentation">' +
						'<span class="cke_wrapper cke_', editor.lang.dir, '" role="presentation">' +
							'<table class="cke_editor" border="0" cellspacing="0" cellpadding="0" role="presentation"><tbody>' +
								'<tr', topHtml		? '' : ' style="display:none"', ' role="presentation"><td id="cke_top_'		, name, '" class="cke_top" role="presentation">'	, topHtml		, '</td></tr>' +
								'<tr', contentsHtml	? '' : ' style="display:none"', ' role="presentation"><td id="cke_contents_', name, '" class="cke_contents" style="height:', height, '" role="presentation">', contentsHtml, '</td></tr>' +
								'<tr', bottomHtml	? '' : ' style="display:none"', ' role="presentation"><td id="cke_bottom_'	, name, '" class="cke_bottom" role="presentation">'	, bottomHtml	, '</td></tr>' +
							'</tbody></table>' +
							//Hide the container when loading skins, later restored by skin css.
							'<style>.', editor.skinClass, '{visibility:hidden;}</style>' +
						'</span>' +
					'</span>' +
				'</span>' ].join( '' ) );

			container.getChild( [1, 0, 0, 0, 0] ).unselectable();
			container.getChild( [1, 0, 0, 0, 2] ).unselectable();

			if ( elementMode == CKEDITOR.ELEMENT_MODE_REPLACE )
				container.insertAfter( element );
			else
				element.append( container );

			/**
		