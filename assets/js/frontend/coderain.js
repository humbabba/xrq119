/**
 * Code rain canvas animation for the HUD header screen.
 */
export function initCoderain() {
	const c = document.getElementById( 'xrq119-coderain' );
	if ( ! c ) return;
	const ctx = c.getContext( '2d' );
	const chars = 'アイウエオカキクケコサシスセソタチツテトナニヌネノハヒフヘホマミムメモヤユヨラリルレロワヲン0123456789ABCDEF';
	let cols, drops;

	function init() {
		c.width = c.offsetWidth;
		c.height = c.offsetHeight;
		cols = Math.floor( c.width / 10 );
		drops = Array( cols ).fill( 1 );
	}

	function draw() {
		ctx.fillStyle = 'rgba(0, 5, 2, 0.15)';
		ctx.fillRect( 0, 0, c.width, c.height );
		ctx.font = '10px monospace';
		for ( let i = 0; i < cols; i++ ) {
			const ch = chars[ Math.floor( Math.random() * chars.length ) ];
			ctx.fillStyle = drops[ i ] * 10 > c.height - 10 ? '#0f0' : 'rgba(0,255,70,0.6)';
			ctx.fillText( ch, i * 10, drops[ i ] * 10 );
			if ( drops[ i ] * 10 > c.height && Math.random() > 0.975 ) drops[ i ] = 0;
			drops[ i ]++;
		}
	}

	init();
	window.addEventListener( 'resize', init );
	setInterval( draw, 60 );
}
