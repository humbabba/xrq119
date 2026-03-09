const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
const path = require( 'path' );

module.exports = {
    ...defaultConfig,
    entry: {
        index: path.resolve( __dirname, 'assets/js/index.js' ),
        'admin-sortable': path.resolve( __dirname, 'assets/js/admin-sortable.js' ),
    },
    output: {
        ...defaultConfig.output,
        path: path.resolve( __dirname, 'dist/js' ),
    },
};
