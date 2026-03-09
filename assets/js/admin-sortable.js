import Sortable from 'sortablejs';

const { ajaxurl, xrq119Sort } = window;
if ( ! xrq119Sort ) throw new Error( 'xrq119Sort not localized' );

const list = document.getElementById( 'the-list' );
if ( ! list ) throw new Error( '#the-list not found' );

// Inject drag handles into each row
list.querySelectorAll( 'tr' ).forEach( tr => {
    const handle = document.createElement( 'td' );
    handle.className = 'xrq119-drag-handle';
    handle.innerHTML = '<span class="dashicons dashicons-menu"></span>';
    tr.prepend( handle );
} );

// Also add a blank header cell so columns stay aligned
const headerRow = document.querySelector( '.wp-list-table thead tr' );
if ( headerRow ) {
    const th = document.createElement( 'th' );
    th.style.width = '32px';
    headerRow.prepend( th );
}
const footerRow = document.querySelector( '.wp-list-table tfoot tr' );
if ( footerRow ) {
    const th = document.createElement( 'th' );
    th.style.width = '32px';
    footerRow.prepend( th );
}

Sortable.create( list, {
    animation: 150,
    handle: '.xrq119-drag-handle',
    ghostClass: 'xrq119-sort-ghost',
    onEnd() {
        const prefix = xrq119Sort.mode === 'categories' ? 'tag-' : 'post-';
        const order = [ ...list.querySelectorAll( `tr[id^="${ prefix }"]` ) ]
            .map( tr => tr.id.replace( prefix, '' ) );

        const body = new FormData();
        body.append( 'action', xrq119Sort.action );
        body.append( '_ajax_nonce', xrq119Sort.nonce );
        order.forEach( id => body.append( 'order[]', id ) );
        if ( xrq119Sort.cat_id ) {
            body.append( 'cat_id', xrq119Sort.cat_id );
        }
        fetch( ajaxurl, { method: 'POST', body } );
    },
} );
