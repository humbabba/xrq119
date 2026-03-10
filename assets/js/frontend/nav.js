/**
 * Priority+ navigation and category dropdown toggles.
 */
export function initNav() {
	const nav = document.querySelector( '[data-priority-nav]' );
	if ( ! nav ) return;
	const ul = nav.querySelector( 'ul' );
	if ( ! ul ) return;

	// ── Category sub-menu toggles (event delegation) ──
	document.addEventListener( 'click', function ( e ) {
		const btn = e.target.closest( '.hud-nav__parent-toggle' );
		if ( btn ) {
			e.stopPropagation();
			const li = btn.closest( '.hud-nav__has-children' );
			const open = li.classList.toggle( 'is-open' );
			btn.setAttribute( 'aria-expanded', String( open ) );
			document.querySelectorAll( '.hud-nav__has-children.is-open' ).forEach( function ( other ) {
				if ( other !== li ) {
					other.classList.remove( 'is-open' );
					other.querySelector( '.hud-nav__parent-toggle' ).setAttribute( 'aria-expanded', 'false' );
				}
			} );
			return;
		}
		document.querySelectorAll( '.hud-nav__has-children.is-open' ).forEach( function ( li ) {
			li.classList.remove( 'is-open' );
			li.querySelector( '.hud-nav__parent-toggle' ).setAttribute( 'aria-expanded', 'false' );
		} );
	} );

	// ── Priority+ overflow nav ──
	ul.style.flexWrap = 'nowrap';

	const moreLi = document.createElement( 'li' );
	moreLi.className = 'hud-nav__more';
	moreLi.style.display = 'none';
	moreLi.innerHTML =
		'<button class="hud-nav__more-toggle" aria-expanded="false">' +
			'<svg viewBox="0 0 12 12"><polyline points="2,4 6,8 10,4"/></svg>' +
		'</button>' +
		'<ul class="hud-nav__dropdown"></ul>';
	ul.appendChild( moreLi );

	const toggle = moreLi.querySelector( '.hud-nav__more-toggle' );
	const dropdown = moreLi.querySelector( '.hud-nav__dropdown' );
	const items = Array.from( ul.children ).filter( function ( li ) {
		return li !== moreLi;
	} );

	const widths = items.map( function ( li ) {
		return li.getBoundingClientRect().width;
	} );
	moreLi.style.display = '';
	const moreWidth = moreLi.getBoundingClientRect().width;
	moreLi.style.display = 'none';

	const gap = 16;

	toggle.addEventListener( 'click', function ( e ) {
		e.stopPropagation();
		const open = moreLi.classList.toggle( 'is-open' );
		toggle.setAttribute( 'aria-expanded', String( open ) );
	} );

	document.addEventListener( 'click', function ( e ) {
		if ( ! moreLi.contains( e.target ) ) {
			moreLi.classList.remove( 'is-open' );
			toggle.setAttribute( 'aria-expanded', 'false' );
		}
	} );

	function flattenChildren( srcLi ) {
		const sub = srcLi.querySelector( '.hud-nav__sub' );
		if ( ! sub ) {
			const clone = srcLi.cloneNode( true );
			clone.style.display = '';
			return [ clone ];
		}
		const flatItems = [];
		const subLinks = sub.querySelectorAll( 'li' );
		subLinks.forEach( function ( child, idx ) {
			const li = document.createElement( 'li' );
			if ( idx === 0 ) {
				li.innerHTML = child.innerHTML;
			} else {
				const a = child.querySelector( 'a' );
				li.innerHTML = '<a href="' + a.getAttribute( 'href' ) + '">- ' + a.textContent + '</a>';
			}
			flatItems.push( li );
		} );
		return flatItems;
	}

	function update() {
		items.forEach( function ( li ) {
			li.style.display = '';
		} );
		dropdown.innerHTML = '';
		moreLi.style.display = 'none';
		moreLi.classList.remove( 'is-open' );

		const available = nav.getBoundingClientRect().width;

		let total = 0;
		let breakIdx = -1;
		for ( let i = 0; i < items.length; i++ ) {
			total += widths[ i ] + ( i > 0 ? gap : 0 );
			if ( total > available ) {
				breakIdx = i;
				break;
			}
		}

		if ( breakIdx === -1 ) return;

		const limit = available - moreWidth - gap;
		total = 0;
		breakIdx = 0;
		for ( let i = 0; i < items.length; i++ ) {
			total += widths[ i ] + ( i > 0 ? gap : 0 );
			if ( total > limit ) {
				breakIdx = i;
				break;
			}
		}

		moreLi.style.display = '';
		for ( let i = breakIdx; i < items.length; i++ ) {
			items[ i ].style.display = 'none';
			flattenChildren( items[ i ] ).forEach( function ( li ) {
				dropdown.appendChild( li );
			} );
		}
	}

	update();
	window.addEventListener( 'resize', update );
}
