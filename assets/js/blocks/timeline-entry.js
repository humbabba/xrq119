import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, RichText } from '@wordpress/block-editor';

registerBlockType( 'xrq119/timeline-entry', {
	title: 'Timeline Entry',
	icon: 'backup',
	category: 'xrq119',
	attributes: {
		period: { type: 'string', source: 'html', selector: '.period' },
		title: { type: 'string', source: 'html', selector: 'h3' },
		org: { type: 'string', source: 'html', selector: '.org' },
		items: { type: 'string', source: 'html', selector: 'ul' },
	},
	edit( { attributes, setAttributes } ) {
		const blockProps = useBlockProps( { className: 'timeline-item' } );
		return (
			<div { ...blockProps }>
				<RichText
					tagName="span"
					className="period font-mono text-cyan-600 text-xs uppercase tracking-wider"
					value={ attributes.period }
					onChange={ ( v ) => setAttributes( { period: v } ) }
					placeholder="2018 – Present"
				/>
				<RichText
					tagName="h3"
					className="text-xl font-bold mt-1"
					value={ attributes.title }
					onChange={ ( v ) => setAttributes( { title: v } ) }
					placeholder="Job Title"
				/>
				<RichText
					tagName="p"
					className="org text-gray-500 text-sm mb-3"
					value={ attributes.org }
					onChange={ ( v ) => setAttributes( { org: v } ) }
					placeholder="Company · Location"
				/>
				<RichText
					tagName="ul"
					multiline="li"
					className="space-y-2 text-gray-600 text-sm list-none pl-0"
					value={ attributes.items }
					onChange={ ( v ) => setAttributes( { items: v } ) }
					placeholder="Accomplishment"
				/>
			</div>
		);
	},
	save( { attributes } ) {
		const blockProps = useBlockProps.save( {
			className: 'timeline-item',
		} );
		return (
			<div { ...blockProps }>
				<RichText.Content
					tagName="span"
					className="period font-mono text-cyan-600 text-xs uppercase tracking-wider"
					value={ attributes.period }
				/>
				<RichText.Content
					tagName="h3"
					className="text-xl font-bold mt-1"
					value={ attributes.title }
				/>
				<RichText.Content
					tagName="p"
					className="org text-gray-500 text-sm mb-3"
					value={ attributes.org }
				/>
				<RichText.Content
					tagName="ul"
					className="space-y-2 text-gray-600 text-sm list-none pl-0"
					value={ attributes.items }
				/>
			</div>
		);
	},
} );
