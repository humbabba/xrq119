import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, RichText } from '@wordpress/block-editor';

registerBlockType( 'xrq119/stat-card', {
	title: 'Stat Card',
	icon: 'chart-bar',
	category: 'xrq119',
	attributes: {
		number: { type: 'string', source: 'html', selector: '.stat-number' },
		label: { type: 'string', source: 'html', selector: '.stat-label' },
	},
	edit( { attributes, setAttributes } ) {
		const blockProps = useBlockProps( {
			style: {
				textAlign: 'center',
				padding: '1.25rem',
				borderRadius: '0.75rem',
				border: '1px solid #e5e7eb',
				background: '#fff',
				boxShadow: '0 0 15px rgba(6, 182, 212, 0.15)',
			},
		} );
		return (
			<div { ...blockProps }>
				<RichText
					tagName="span"
					className="stat-number"
					style={ { display: 'block', fontSize: '1.875rem', fontWeight: 800, color: '#0891b2', fontFamily: 'var(--font-mono)' } }
					value={ attributes.number }
					onChange={ ( v ) => setAttributes( { number: v } ) }
					placeholder="10+"
				/>
				<RichText
					tagName="span"
					className="stat-label"
					style={ { display: 'block', fontSize: '0.75rem', color: '#6b7280', textTransform: 'uppercase', letterSpacing: '0.05em' } }
					value={ attributes.label }
					onChange={ ( v ) => setAttributes( { label: v } ) }
					placeholder="Label"
				/>
			</div>
		);
	},
	save( { attributes } ) {
		const blockProps = useBlockProps.save( {
			className:
				'text-center p-4 sm:p-5 rounded-xl border border-gray-200 bg-white shadow-[0_0_15px_rgba(6,182,212,0.15)]',
		} );
		return (
			<div { ...blockProps }>
				<RichText.Content
					tagName="span"
					className="stat-number block text-2xl sm:text-3xl font-extrabold text-cyan-600 font-mono"
					value={ attributes.number }
				/>
				<RichText.Content
					tagName="span"
					className="stat-label block text-xs text-gray-500 uppercase tracking-wider"
					value={ attributes.label }
				/>
			</div>
		);
	},
} );
