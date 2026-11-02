/* ==========================================================================
   Edita Fácil · by Nakama
   Barra "Editar la página" → cada sección marcada con data-ef-tab muestra
   "Editar: …" → se abre la ventana (modal) con el formulario → al guardar se
   recarga la página en el mismo lugar.
   ========================================================================== */
( () => {
    'use strict';

    const cfg      = window.editaFacil || {};
    const secciones = [ ...document.querySelectorAll( '[data-ef-bloque]' ) ];
    const nuevos    = [ ...document.querySelectorAll( '[data-ef-nuevo]' ) ];
    if ( ( ! secciones.length && ! nuevos.length ) || ! cfg.form ) return;

    const CLAVE_MODO   = 'edita-facil-modo';
    const CLAVE_SCROLL = 'edita-facil-scroll';
    const lapiz = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 17.25V21h3.75L17.8 9.95l-3.75-3.75L3 17.25zm17.7-10.2a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>';

    const leer    = ( k ) => { try { return localStorage.getItem( k ); } catch ( e ) { return null; } };
    const guardar = ( k, v ) => { try { localStorage.setItem( k, v ); } catch ( e ) {} };

    /* ── Barra de abajo: prender / apagar el modo edición ─────────────── */

    const barra = document.createElement( 'div' );
    barra.className = 'ef-barra';
    barra.innerHTML =
        '<button type="button" class="ef-barra__boton" aria-pressed="false"></button>' +
        ( cfg.inicio ? '<a class="ef-barra__panel" href="' + cfg.inicio + '">← Panel</a>' : '' ) +
        '<span class="ef-barra__marca">' + ( cfg.marca || 'Edita Fácil' ) + ' <small>' + ( cfg.autor || '' ) + '</small></span>';
    document.body.appendChild( barra );
    const botonModo = barra.querySelector( 'button' );

    /* ── Botón "Editar: …" en cada sección ────────────────────────────── */

    secciones.forEach( ( sec ) => {
        sec.classList.add( 'ef-seccion' );
        const b = document.createElement( 'button' );
        b.type = 'button';
        b.className = 'ef-editar';
        b.innerHTML = lapiz + '<span>' + escapar( sec.dataset.efBoton || 'Editar: ' + ( sec.dataset.efTitulo || sec.dataset.efTab ) ) + '</span>';
        b.addEventListener( 'click', ( e ) => { e.preventDefault(); e.stopPropagation(); abrir( sec ); } );
        const lugar = sec.querySelector( '[data-ef-lugar]' );   // dónde va el botón (por defecto, arriba de la sección)
        lugar ? lugar.prepend( b ) : sec.prepend( b );
    } );

    function modo( activo ) {
        document.documentElement.classList.toggle( 'ef-modo', activo );
        botonModo.setAttribute( 'aria-pressed', String( activo ) );
        botonModo.innerHTML = activo ? '✔ Terminar de editar' : lapiz + ' Editar la página';
        guardar( CLAVE_MODO, activo ? '1' : '0' );
    }

    // Desde el panel de inicio se llega con ?editar=1: el modo edición ya viene prendido
    if ( new URLSearchParams( location.search ).get( 'editar' ) === '1' ) guardar( CLAVE_MODO, '1' );

    botonModo.addEventListener( 'click', () => modo( ! document.documentElement.classList.contains( 'ef-modo' ) ) );
    modo( leer( CLAVE_MODO ) === '1' );

    /* ── Ventana de edición ────────────────────────────────────────────────── */

    const panel = document.createElement( 'div' );
    panel.className = 'ef-panel';
    panel.hidden = true;
    panel.innerHTML =
        '<div class="ef-panel__fondo"></div>' +
        '<aside class="ef-panel__caja" role="dialog" aria-modal="true" aria-labelledby="ef-panel-titulo">' +
            '<header class="ef-panel__cabecera">' +
                '<div><small>Estás editando</small><strong id="ef-panel-titulo"></strong></div>' +
                '<button type="button" class="ef-panel__cerrar">Cerrar</button>' +
            '</header>' +
            '<div class="ef-panel__cargando">Cargando…</div>' +
            '<iframe class="ef-panel__marco" title="Formulario de edición"></iframe>' +
        '</aside>';
    document.body.appendChild( panel );

    const marco  = panel.querySelector( 'iframe' );
    const titulo = panel.querySelector( '#ef-panel-titulo' );
    let seccionActual = null;

    /* Botones "+ Agregar …": abren el panel con una ficha vacía */
    nuevos.forEach( ( b ) => b.addEventListener( 'click', ( e ) => { e.preventDefault(); abrir( b ); } ) );

    function abrir( sec ) {
        seccionActual = sec;
        titulo.textContent = sec.dataset.efTitulo || sec.dataset.efTab;
        const url = new URL( cfg.form, location.href );
        if ( sec.dataset.efNuevo ) {
            url.searchParams.set( 'nuevo', sec.dataset.efNuevo );
        } else {
            url.searchParams.set( 'post', sec.dataset.efPost );
            url.searchParams.set( 'bloque', sec.dataset.efBloque );
        }
        if ( sec.dataset.efNombre ) url.searchParams.set( 'nombre', '1' );
        if ( sec.dataset.efGrupo ) url.searchParams.set( 'grupo', sec.dataset.efGrupo );
        panel.classList.add( 'is-cargando' );
        marco.src = url.toString();
        panel.hidden = false;
        document.documentElement.classList.add( 'ef-panel-abierto' );
        requestAnimationFrame( () => panel.classList.add( 'is-abierto' ) );
        panel.querySelector( '.ef-panel__cerrar' ).focus();
    }

    function cerrar() {
        panel.classList.remove( 'is-abierto' );
        document.documentElement.classList.remove( 'ef-panel-abierto' );
        setTimeout( () => { panel.hidden = true; marco.src = 'about:blank'; }, 300 );
        if ( seccionActual ) ( seccionActual.querySelector( '.ef-editar' ) || seccionActual ).focus();
    }

    marco.addEventListener( 'load', () => panel.classList.remove( 'is-cargando' ) );
    panel.querySelector( '.ef-panel__cerrar' ).addEventListener( 'click', cerrar );
    panel.querySelector( '.ef-panel__fondo' ).addEventListener( 'click', cerrar );
    document.addEventListener( 'keydown', ( e ) => { if ( e.key === 'Escape' && ! panel.hidden ) cerrar(); } );

    /* ── Guardado: recargar y volver al mismo lugar ───────────────────── */

    window.addEventListener( 'message', ( e ) => {
        if ( e.origin !== location.origin || e.source !== marco.contentWindow || panel.hidden || ! e.data || e.data.editaFacil !== 'guardado' ) return;
        try {
            sessionStorage.setItem( CLAVE_SCROLL, JSON.stringify( {
                y: window.scrollY,
                tab: seccionActual ? seccionActual.dataset.efBloque : '',
            } ) );
        } catch ( err ) {}
        location.reload();
    } );

    try {
        const vuelta = JSON.parse( sessionStorage.getItem( CLAVE_SCROLL ) || 'null' );
        if ( vuelta ) {
            sessionStorage.removeItem( CLAVE_SCROLL );
            window.scrollTo( { top: vuelta.y, behavior: 'instant' } );
            const sec = secciones.find( ( s ) => s.dataset.efBloque === vuelta.tab );
            if ( sec ) {
                sec.classList.add( 'ef-recien-guardada' );
                setTimeout( () => sec.classList.remove( 'ef-recien-guardada' ), 2600 );
            }
            aviso( '✔ Cambios guardados' );
        }
    } catch ( err ) {}

    function aviso( texto ) {
        const t = document.createElement( 'div' );
        t.className = 'ef-aviso';
        t.setAttribute( 'role', 'status' );
        t.textContent = texto;
        document.body.appendChild( t );
        setTimeout( () => t.classList.add( 'is-fuera' ), 2400 );
        setTimeout( () => t.remove(), 3000 );
    }

    function escapar( s ) {
        const d = document.createElement( 'div' );
        d.textContent = s;
        return d.innerHTML;
    }
} )();
