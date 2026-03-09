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
						className="font-mono font-bold text-sm text-cyan-700 mb-3 uppercase tracking-wider"
						value={ attributes.heading }
						onChange={ ( v ) =>
							setAttributes( { heading: v } )
						}
						placeholder="Category"
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
						{ tagList.length === 0 && (
							<span className="text-gray-400 text-sm italic">
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
