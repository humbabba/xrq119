import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, RichText, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextareaControl } from '@wordpress/components';

registerBlockType( 'xrq119/skill-group', {
	title: 'Skill Group',
	icon: 'tag',
	category: 'xrq119',
	attributes: {
		heading: { type: 'string', source: 'html', selector: 'h3' },
		tags: { type: 'string', default: '' },
	},
	edit( { attributes, setAttributes } ) {
		const blockProps = useBlockProps();
		const tagList = attributes.tags
			? attributes.tags
					.split( ',' )
					.map( ( t ) => t.trim() )
					.filter( Boolean )
			: [];
		return (
			<>
				<InspectorControls>
					<PanelBody title="Tags">
						<TextareaControl
							label="Comma-separated tags"
							value={ attributes.tags }
							onChange={ ( tags ) => setAttributes( { tags } ) }
						/>
					</PanelBody>
				</InspectorControls>
				<div { ...blockProps }>
					<RichText
						tagName="h3"
						style={ { fontFamily: 'var(--font-mono)', fontWeight: 700, fontSize: '0.875rem', color: '#0e7490', margin: '0 0 0.75rem', textTransform: 'uppercase', letterSpacing: '0.05em' } }
						value={ attributes.heading }
						onChange={ ( v ) =>
							setAttributes( { heading: v } )
						}
						placeholder="Category"
					/>
					<div style={ { display: 'flex', flexWrap: 'wrap', gap: '0.5rem' } }>
						{ tagList.map( ( tag, i ) => (
							<span
								key={ i }
								style={ { padding: '0.375rem 0.75rem', fontSize: '0.875rem', fontWeight: 500, borderRadius: '0.375rem', background: '#ecfeff', color: '#155e75', border: '1px solid rgba(165, 243, 252, 0.5)' } }
							>
								{ tag }
							</span>
						) ) }
						{ tagList.length === 0 && (
							<span style={ { color: '#9ca3af', fontSize: '0.875rem', fontStyle: 'italic' } }>
								Add tags in the sidebar →
							</span>
						) }
					</div>
				</div>
			</>
		);
	},
	save( { attributes } ) {
		const blockProps = useBlockProps.save();
		const tagList = attributes.tags
			? attributes.tags
					.split( ',' )
					.map( ( t ) => t.trim() )
					.filter( Boolean )
			: [];
		return (
			<div { ...blockProps }>
				<RichText.Content
					tagName="h3"
					className="font-mono font-bold text-sm text-cyan-700 mb-3 uppercase tracking-wider"
					value={ attributes.heading }
				/>
				<div className="flex flex-wrap gap-2">
					{ tagList.map( ( tag, i ) => (
						<span
							key={ i }
							className="px-3 py-1.5 text-sm font-medium rounded-md bg-cyan-50 text-cyan-800 border border-cyan-200/50"
						>
							{ tag }
						</span>
					) ) }
				</div>
			</div>
		);
	},
} );
