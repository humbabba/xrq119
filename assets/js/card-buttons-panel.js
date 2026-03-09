import { PluginDocumentSettingPanel } from '@wordpress/edit-post';
import { Button, TextControl, ToggleControl, ColorPalette, BaseControl } from '@wordpress/components';
import { useSelect, useDispatch } from '@wordpress/data';
import { registerPlugin } from '@wordpress/plugins';

const BG_PALETTE = [
	{ name: 'Cyan', color: '#0891b2' },
	{ name: 'Purple', color: '#9333ea' },
	{ name: 'Green', color: '#16a34a' },
	{ name: 'Orange', color: '#f97316' },
	{ name: 'Gray', color: '#6b7280' },
];

const TEXT_PALETTE = [
	{ name: 'White', color: '#ffffff' },
	{ name: 'Black', color: '#000000' },
	{ name: 'Cyan', color: '#0891b2' },
	{ name: 'Gray', color: '#374151' },
];

const CardButtonsPanel = () => {
	const meta = useSelect(
		( select ) => select( 'core/editor' ).getEditedPostAttribute( 'meta' ) || {},
		[]
	);
	const { editPost } = useDispatch( 'core/editor' );

	const buttons = (() => {
		try {
			return JSON.parse( meta._xrq119_card_buttons || '[]' );
		} catch {
			return [];
		}
	})();

	const setButtons = ( updated ) => {
		editPost( { meta: { ...meta, _xrq119_card_buttons: JSON.stringify( updated ) } } );
	};

	const updateButton = ( index, key, value ) => {
		const updated = buttons.map( ( btn, i ) =>
			i === index ? { ...btn, [ key ]: value } : btn
		);
		setButtons( updated );
	};

	const addButton = () => {
		setButtons( [ ...buttons, { label: '', url: '', color: '#0891b2', textColor: '#ffffff', new_tab: false } ] );
	};

	const removeButton = ( index ) => {
		setButtons( buttons.filter( ( _, i ) => i !== index ) );
	};

	return (
		<PluginDocumentSettingPanel
			name="xrq119-card-buttons"
			title="Card Buttons"
			className="xrq119-card-buttons-panel"
		>
			{ buttons.map( ( btn, i ) => (
				<div key={ i } style={ { marginBottom: '16px', padding: '12px', background: '#f9f9f9', borderRadius: '4px' } }>
					<TextControl
						label="Label"
						value={ btn.label }
						onChange={ ( val ) => updateButton( i, 'label', val ) }
					/>
					<TextControl
						label="URL"
						value={ btn.url }
						onChange={ ( val ) => updateButton( i, 'url', val ) }
						type="url"
						placeholder="https://"
					/>
					<BaseControl label="Background">
						<ColorPalette
							colors={ BG_PALETTE }
							value={ btn.color }
							onChange={ ( val ) => updateButton( i, 'color', val ) }
						/>
					</BaseControl>
					<BaseControl label="Text">
						<ColorPalette
							colors={ TEXT_PALETTE }
							value={ btn.textColor || '#ffffff' }
							onChange={ ( val ) => updateButton( i, 'textColor', val ) }
						/>
					</BaseControl>
					<TextControl
						label="CSS class(es)"
						value={ btn.className || '' }
						onChange={ ( val ) => updateButton( i, 'className', val ) }
						placeholder="e.g. icon-github"
					/>
					<ToggleControl
						label="Open in new tab"
						checked={ !! btn.new_tab }
						onChange={ ( val ) => updateButton( i, 'new_tab', val ) }
					/>
					<Button
						variant="link"
						isDestructive
						onClick={ () => removeButton( i ) }
						style={ { marginTop: '4px' } }
					>
						Remove
					</Button>
				</div>
			) ) }
			<Button variant="secondary" onClick={ addButton }>
				+ Add Button
			</Button>
		</PluginDocumentSettingPanel>
	);
};

registerPlugin( 'xrq119-card-buttons', {
	render: CardButtonsPanel,
} );
