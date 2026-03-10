const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
const path = require( 'path' );

module.exports = {
	...defaultConfig,
	entry: {
		admin: path.resolve( __dirname, 'assets/js/admin.js' ),
		'admin-sortable': path.resolve( __dirname, 'assets/js/admin/sortable.js' ),
		'modal-sample': path.resolve( __dirname, 'assets/js/frontend/modal-sample.js' ),
	},
	output: {
		...defaultConfig.output,
		path: path.resolve( __dirname, 'dist/js' ),
	},
};
