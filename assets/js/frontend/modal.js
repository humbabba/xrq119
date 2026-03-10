/**
 * xrq119 Modal
 *
 * Usage:
 *   import { modal } from './modal.js';
 *   const m = modal({ title: 'Hello', body: '<p>Content here</p>' });
 *   m.close();
 *
 * Options (all optional):
 *   title       - Heading text (string)
 *   body        - HTML string or DOM element for the modal body
 *   closeLabel  - Text for the close button (default: 'Close')
 *   showClose   - Show the close button (default: true)
 *   overlayClose - Close when clicking the overlay (default: true)
 *   escClose    - Close on Escape key (default: true)
 *   width       - Max width CSS value (default: '32rem')
 *   onOpen      - Callback after modal opens
 *   onClose     - Callback after modal closes
 */

const defaults = {
	title: '',
	body: '',
	closeLabel: 'Close',
	showClose: true,
	overlayClose: true,
	escClose: true,
	width: '32rem',
	onOpen: null,
	onClose: null,
};

export function modal( options = {} ) {
	const opts = { ...defaults, ...options };

	// Overlay
	const overlay = document.createElement( 'div' );
	Object.assign( overlay.style, {
		position: 'fixed',
		inset: '0',
		zIndex: '9999',
		display: 'flex',
		alignItems: 'center',
		justifyContent: 'center',
		background: 'rgba(0, 0, 0, 0.75)',
		backdropFilter: 'blur(4px)',
		opacity: '0',
		transition: 'opacity 0.2s ease',
	} );

	// Panel
	const panel = document.createElement( 'div' );
	Object.assign( panel.style, {
		position: 'relative',
		maxWidth: opts.width,
		width: '90%',
		background: 'linear-gradient(180deg, #0a0f1a 0%, #060a12 100%)',
		border: '1px solid rgba(6, 182, 212, 0.3)',
		borderRadius: '0.75rem',
		padding: '1.75rem',
		color: '#e2e8f0',
		fontFamily: 'var(--font-mono, monospace)',
		boxShadow: '0 0 30px rgba(6, 182, 212, 0.15)',
		transform: 'translateY(12px)',
		transition: 'transform 0.2s ease',
		maxHeight: '85vh',
		display: 'flex',
		flexDirection: 'column',
	} );

	// Corner accents
	const cornerCSS = ( pos ) => {
		const s = document.createElement( 'span' );
		Object.assign( s.style, {
			position: 'absolute',
			width: '12px',
			height: '12px',
			borderColor: 'rgba(6, 182, 212, 0.5)',
			pointerEvents: 'none',
		} );
		if ( pos === 'tl' ) {
			Object.assign( s.style, { top: '-1px', left: '-1px', borderTop: '2px solid', borderLeft: '2px solid' } );
		} else {
			Object.assign( s.style, { bottom: '-1px', right: '-1px', borderBottom: '2px solid', borderRight: '2px solid' } );
		}
		return s;
	};
	panel.appendChild( cornerCSS( 'tl' ) );
	panel.appendChild( cornerCSS( 'br' ) );

	// Header (title + X button)
	if ( opts.title || opts.showClose ) {
		const header = document.createElement( 'div' );
		Object.assign( header.style, {
			display: 'flex',
			alignItems: 'center',
			justifyContent: 'space-between',
			marginBottom: '1rem',
			gap: '1rem',
		} );

		if ( opts.title ) {
			const h = document.createElement( 'h2' );
			Object.assign( h.style, {
				margin: '0',
				fontSize: '1rem',
				fontWeight: '700',
				color: '#06b6d4',
				textTransform: 'uppercase',
				letterSpacing: '0.1em',
			} );
			h.textContent = opts.title;
			header.appendChild( h );
		}

		if ( opts.showClose ) {
			const x = document.createElement( 'button' );
			x.type = 'button';
			x.textContent = '\u00d7';
			Object.assign( x.style, {
				background: 'none',
				border: 'none',
				color: '#64748b',
				fontSize: '1.5rem',
				cursor: 'pointer',
				lineHeight: '1',
				padding: '0 0.25rem',
				marginLeft: 'auto',
				transition: 'color 0.2s',
			} );
			x.addEventListener( 'mouseenter', () => ( x.style.color = '#06b6d4' ) );
			x.addEventListener( 'mouseleave', () => ( x.style.color = '#64748b' ) );
			x.addEventListener( 'click', close );
			header.appendChild( x );
		}

		panel.appendChild( header );
	}

	// Body
	const bodyEl = document.createElement( 'div' );
	Object.assign( bodyEl.style, {
		fontSize: '0.875rem',
		lineHeight: '1.7',
		color: '#cbd5e1',
		overflowY: 'auto',
		flex: '1',
	} );
	if ( typeof opts.body === 'string' ) {
		bodyEl.innerHTML = opts.body;
	} else if ( opts.body instanceof HTMLElement ) {
		bodyEl.appendChild( opts.body );
	}
	panel.appendChild( bodyEl );

	// Footer with close button
	if ( opts.showClose && opts.closeLabel ) {
		const footer = document.createElement( 'div' );
		Object.assign( footer.style, {
			marginTop: '1.25rem',
			paddingTop: '1rem',
			borderTop: '1px solid rgba(6, 182, 212, 0.15)',
			display: 'flex',
			justifyContent: 'flex-end',
		} );

		const btn = document.createElement( 'button' );
		btn.type = 'button';
		btn.textContent = opts.closeLabel;
		Object.assign( btn.style, {
			padding: '0.5rem 1.5rem',
			fontFamily: 'var(--font-mono, monospace)',
			fontSize: '0.75rem',
			fontWeight: '700',
			textTransform: 'uppercase',
			letterSpacing: '0.1em',
			color: '#0f172a',
			background: '#06b6d4',
			border: '1px solid #06b6d4',
			borderRadius: '0.375rem',
			cursor: 'pointer',
			transition: 'background 0.2s, box-shadow 0.2s',
		} );
		btn.addEventListener( 'mouseenter', () => {
			btn.style.background = '#22d3ee';
			btn.style.boxShadow = '0 0 15px rgba(6, 182, 212, 0.4)';
		} );
		btn.addEventListener( 'mouseleave', () => {
			btn.style.background = '#06b6d4';
			btn.style.boxShadow = 'none';
		} );
		btn.addEventListener( 'click', close );
		footer.appendChild( btn );
		panel.appendChild( footer );
	}

	overlay.appendChild( panel );

	// Overlay click
	if ( opts.overlayClose ) {
		overlay.addEventListener( 'click', ( e ) => {
			if ( e.target === overlay ) {
				close();
			}
		} );
	}

	// Escape key
	function onKey( e ) {
		if ( e.key === 'Escape' && opts.escClose ) {
			close();
		}
	}

	// Open
	document.body.appendChild( overlay );
	document.addEventListener( 'keydown', onKey );

	// Trigger animation
	requestAnimationFrame( () => {
		overlay.style.opacity = '1';
		panel.style.transform = 'translateY(0)';
	} );

	if ( typeof opts.onOpen === 'function' ) {
		opts.onOpen( api );
	}

	// Close
	function close() {
		overlay.style.opacity = '0';
		panel.style.transform = 'translateY(12px)';
		document.removeEventListener( 'keydown', onKey );
		overlay.addEventListener( 'transitionend', () => {
			overlay.remove();
			if ( typeof opts.onClose === 'function' ) {
				opts.onClose();
			}
		}, { once: true } );
	}

	const api = {
		close,
		overlay,
		panel,
		body: bodyEl,
	};

	return api;
}
