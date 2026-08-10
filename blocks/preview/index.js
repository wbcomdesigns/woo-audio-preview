/**
 * Editor registration for the Audio Preview block.
 *
 * The block is server-rendered - the markup, player markup and templates all live in
 * PHP, so a JavaScript re-implementation would be a second copy of the output that could drift
 * from the first. This file exists only so the block APPEARS IN THE INSERTER and can be
 * configured; the player itself comes from the server through ServerSideRender.
 *
 * Registered without a build step: it uses the wp.* globals WordPress already enqueues, so
 * there is no bundler in the release path to go stale or fail silently.
 */
( function ( blocks, blockEditor, components, serverSideRender, element ) {
	'use strict';

	var el = element.createElement;

	blocks.registerBlockType( 'woo-audio-preview/preview', {
		edit: function ( props ) {
			var productId = props.attributes.productId || 0;

			return el(
				element.Fragment,
				null,
				el(
					blockEditor.InspectorControls,
					null,
					el(
						components.PanelBody,
						{ title: 'Audio Preview' },
						el( components.TextControl, {
							label: 'Product ID',
							help: 'Leave empty to use the product this page is showing.',
							type: 'number',
							value: productId ? String( productId ) : '',
							onChange: function ( value ) {
								props.setAttributes( { productId: value ? parseInt( value, 10 ) : 0 } );
							},
						} )
					)
				),
				el( serverSideRender, {
					block: 'woo-audio-preview/preview',
					attributes: props.attributes,
				} )
			);
		},
		save: function () {
			return null; // Server-rendered.
		},
	} );
} )(
	window.wp.blocks,
	window.wp.blockEditor,
	window.wp.components,
	window.wp.serverSideRender,
	window.wp.element
);
