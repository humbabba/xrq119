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
		const blockProps = useBlockProps( {
			className: 'timeline-item',
			style: { position: 'relative', marginBottom: '3rem' },
		} );
		return (
			<div { ...blockProps }>
				<RichText
					tagName="span"
					className="period"
					style={ { fontFamily: 'var(--font-mono)', color: '#0891b2', fontSize: '0.75rem', textTransform: 'uppercase', letterSpacing: '0.05em' } }
					value={ attributes.period }
					onChange={ ( v ) => setAttributes( { period: v } ) }
					placeholder="2018 – Present"
				/>
				<RichText
					tagName="h3"
					style={ { fontSize: '1.25rem', fontWeight: 700, margin: '0.25rem 0 0' } }
					value={ attributes.title }
					onChange={ ( v ) => setAttributes( { title: v } ) }
					placeholder="Job Title"
				/>
				<RichText
					tagName="p"
					className="org"
					style={ { color: '#6b7280', fontSize: '0.875rem', margin: '0 0 0.75rem' } }
					value={ attributes.org }
					onChange={ ( v ) => setAttributes( { org: v } ) }
					placeholder="Company · Location"
				/>
				<RichText
					tagName="ul"
					multiline="li"
					style={ { color: '#4b5563', fontSize: '0.875rem', listStyle: 'none', padding: 0, display: 'flex', flexDirection: 'column', gap: '0.5rem' } }
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
