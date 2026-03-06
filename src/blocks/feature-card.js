import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, RichText } from '@wordpress/block-editor';

registerBlockType( 'xrq119/feature-card', {
	title: 'Feature Card',
	icon: 'screenoptions',
	category: 'xrq119',
	attributes: {
		icon: { type: 'string', source: 'html', selector: '.block-icon' },
		heading: { type: 'string', source: 'html', selector: 'h3' },
		description: { type: 'string', source: 'html', selector: '.block-desc' },
	},
	edit( { attributes, setAttributes } ) {
		const blockProps = useBlockProps( {
			className:
				'corner-accent border border-gray-200 rounded-xl p-6 bg-gray-50/50',
		} );
		return (
			<div { ...blockProps }>
				<RichText
					tagName="p"
					className="block-icon text-3xl mb-4"
					value={ attributes.icon }
					onChange={ ( v ) => setAttributes( { icon: v } ) }
					placeholder="⌈"
				/>
				<RichText
					tagName="h3"
					className="font-bold text-lg mb-2 font-mono"
					value={ attributes.heading }
					onChange={ ( v ) => setAttributes( { heading: v } ) }
					placeholder="Feature title"
				/>
				<RichText
					tagName="p"
					className="block-desc text-gray-600 text-sm leading-relaxed"
					value={ attributes.description }
					onChange={ ( v ) => setAttributes( { description: v } ) }
					placeholder="Description…"
				/>
			</div>
		);
	},
	save( { attributes } ) {
		const blockProps = useBlockProps.save( {
			className:
				'corner-accent border border-gray-200 rounded-xl p-6 bg-gray-50/50 transition-all hover:shadow-[0_0_20px_rgba(6,182,212,0.2)] hover:border-cyan-400/50',
		} );
		return (
			<div { ...blockProps }>
				<RichText.Content
					tagName="p"
					className="block-icon text-3xl mb-4"
					value={ attributes.icon }
				/>
				<RichText.Content
					tagName="h3"
					className="font-bold text-lg mb-2 font-mono"
					value={ attributes.heading }
				/>
				<RichText.Content
					tagName="p"
					className="block-desc text-gray-600 text-sm leading-relaxed"
					value={ attributes.description }
				/>
			</div>
		);
	},
} );
