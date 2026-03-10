import { PluginDocumentSettingPanel } from '@wordpress/edit-post';
import { ToggleControl, TextControl } from '@wordpress/components';
import { useSelect, useDispatch } from '@wordpress/data';
import { registerPlugin } from '@wordpress/plugins';

const ExternalLinkPanel = () => {
	const meta = useSelect(
		( select ) => select( 'core/editor' ).getEditedPostAttribute( 'meta' ) || {},
		[]
	);
	const { editPost } = useDispatch( 'core/editor' );

	const setMeta = ( key, value ) => {
		editPost( { meta: { ...meta, [ key ]: value } } );
	};

	const enabled = !! meta._xrq119_external_link_enabled;

	return (
		<PluginDocumentSettingPanel
			name="xrq119-external-link"
			title="External Link"
			className="xrq119-external-link-panel"
		>
			<ToggleControl
				label="Link externally?"
				checked={ enabled }
				onChange={ ( val ) => setMeta( '_xrq119_external_link_enabled', val ) }
			/>
			{ enabled && (
				<>
					<TextControl
						label="External URL"
						value={ meta._xrq119_external_url || '' }
						onChange={ ( val ) => setMeta( '_xrq119_external_url', val ) }
						type="url"
						placeholder="https://"
					/>
					<ToggleControl
						label="Open in new tab"
						checked={ !! meta._xrq119_external_new_tab }
						onChange={ ( val ) => setMeta( '_xrq119_external_new_tab', val ) }
					/>
				</>
			) }
		</PluginDocumentSettingPanel>
	);
};

registerPlugin( 'xrq119-external-link', {
	render: ExternalLinkPanel,
} );
