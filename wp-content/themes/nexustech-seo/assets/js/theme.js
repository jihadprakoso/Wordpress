/**
 * NexusTech SEO — perilaku front-end.
 * Tanpa dependensi selain Bootstrap bundle yang sudah dimuat lebih dulu.
 */
( function () {
	'use strict';

	var reduceMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	/**
	 * Tambahkan bayangan pada header setelah halaman digulir.
	 */
	function initHeaderShadow() {
		var header = document.querySelector( '.nt-header' );
		if ( ! header ) {
			return;
		}

		function sync() {
			header.classList.toggle( 'is-scrolled', window.scrollY > 8 );
		}

		sync();
		window.addEventListener( 'scroll', sync, { passive: true } );
	}

	/**
	 * Gulir halus untuk tautan anchor internal, dengan kompensasi header sticky.
	 */
	function initSmoothAnchors() {
		if ( reduceMotion ) {
			return;
		}

		var header = document.querySelector( '.nt-header' );

		document.addEventListener( 'click', function ( event ) {
			var link = event.target.closest( 'a[href*="#"]' );
			if ( ! link ) {
				return;
			}

			var hash = link.getAttribute( 'href' ).split( '#' )[ 1 ];
			if ( ! hash || hash.length < 2 ) {
				return;
			}

			var target = document.getElementById( hash );
			if ( ! target ) {
				return;
			}

			event.preventDefault();

			var offset = header ? header.offsetHeight + 16 : 16;
			var top = target.getBoundingClientRect().top + window.scrollY - offset;

			window.scrollTo( { top: top, behavior: 'smooth' } );

			if ( window.history && window.history.pushState ) {
				window.history.pushState( null, '', '#' + hash );
			}
		} );
	}

	/**
	 * Tandai tautan menu yang paling cocok dengan posisi gulir saat ini.
	 */
	function initMenuHighlight() {
		var links = Array.prototype.slice.call( document.querySelectorAll( '.nt-menu .nav-link[href*="#"]' ) );
		if ( ! links.length ) {
			return;
		}

		var sections = links
			.map( function ( link ) {
				var hash = link.getAttribute( 'href' ).split( '#' )[ 1 ];
				return hash ? document.getElementById( hash ) : null;
			} )
			.filter( Boolean );

		if ( ! sections.length ) {
			return;
		}

		function sync() {
			var position = window.scrollY + ( window.innerHeight / 3 );
			var activeId = '';

			sections.forEach( function ( section ) {
				if ( section.offsetTop <= position ) {
					activeId = section.id;
				}
			} );

			links.forEach( function ( link ) {
				var hash = link.getAttribute( 'href' ).split( '#' )[ 1 ];
				link.classList.toggle( 'is-active', hash === activeId );
			} );
		}

		sync();
		window.addEventListener( 'scroll', sync, { passive: true } );
	}

	document.addEventListener( 'DOMContentLoaded', function () {
		initHeaderShadow();
		initSmoothAnchors();
		initMenuHighlight();
	} );
} )();
