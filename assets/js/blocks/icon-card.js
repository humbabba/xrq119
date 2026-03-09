import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, RichText } from '@wordpress/block-editor';

registerBlockType( 'xrq119/icon-card', {
	title: 'Icon Card',
	icon: 'id',
	category: 'xrq119',
	attributes: {
		icon: { type: 'string', source: 'html', selector: '.card-icon' },
		heading: { type: 'string', source: 'html', selector: 'h3' },
		description: {
			type: 'string',
			source: 'html',
			selector: '.card-desc',
		},
	},
	edit( { attributes, setAttributes } ) {
		const blockProps = useBlockProps( {
			className:
				'corner-accent border border-gray-200 rounded-xl p-6 bg-gray-50/50 max-w-md',
		} );
		return (
			<div { ...blockProps }>
				<div className="flex items-center gap-5">
					<RichText
						tagName="div"
						className="card-icon text-4xl shrink-0"
						value={ attributes.icon }
						onChange={ ( v ) => setAttributes( { icon: v } ) }
						placeholder="🎓"
					/>
					<div>
						<RichText
							tagName="h3"
							className="font-bold text-lg"
							value={ attributes.heading }
							onChange={ ( v ) =>
								setAttributes( { heading: v } )
							}
							placeholder="Heading"
						/>
						<RichText
							tagName="p"
							className="card-desc text-gray-500 text-sm"
							value={ attributes.description }
							onChange={ ( v ) =>
								setAttributes( { description: v } )
							}
							placeholder="Description"
						/>
					</div>
				</div>
			</div>
		);
	},
	save( { attributes } ) {
		const blockProps = useBlockProps.save( {
			className:
				'corner-accent border border-gray-200 rounded-xl p-6 bg-gray-50/50 max-w-md transition-all hover:shadow-[0_0_20px_rgba(6,182,212,0.2)] hover:border-cyan-400/50',
		} );
		return (
			<div { ...blockProps }>
				<div className="flex items-center gap-5">
					<RichText.Content
						tagName="div"
						className="card-icon text-4xl shrink-0"
						value={ attributes.icon }
					/>
					<div>
						<RichText.Content
							tagName="h3"
							className="font-bold text-lg"
							value={ attributes.heading }
						/>
						<RichText.Content
							tagName="p"
							className="card-desc text-gray-500 text-sm"
							value={ attributes.description }
						/>
					</div>
				</div>
			</div>
		);
	},
} );
