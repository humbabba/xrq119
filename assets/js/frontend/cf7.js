import { modal } from './modal.js';

document.addEventListener( 'wpcf7mailsent', function( event ) {
	const m= modal({ title: 'Success!', body: '<p>Message received, thanks.</p>' });
}, false );
