/**
 * Frontend fix scripts.
 */

/* global jQuery */
;( function ( $ ) {
    'use strict';

    /**
     * WooCommerce ordering.
     */
    function woocommerceOrdering() {
        $( '.woocommerce-ordering' ).on( 'change', 'select.orderby', function() {
            $( this ).closest( 'form' ).trigger( 'submit' );
        } );
    }

    /**
     * WooCommerce order sync.
     */
    function woocommerceOrderSync() {
        let data = wlpfGetFiltersData(),
            base = wlpfGetFilterSortingDefault(),
            sorting = data?.sorting?.term || base,
            ordering = $( '.wl-filterable-products-wrap form.woocommerce-ordering,' +
            '.wlpf-products-wrap form.woocommerce-ordering' ).find( 'select.orderby' );

        $.each( ordering, function() {
            let thisSelect = $( this ),
                option = thisSelect.find( 'option[value="' + sorting + '"]' );

            if (0 < option?.length) {
                thisSelect.val( sorting );
            }
        } );
    }

    /**
     * WooLentor custom tabs.
     */
    function woolentorCustomTabs() {
        $( '.wl-shop-tab-links a' ).on( 'click', function( e ) {
            e.preventDefault();

            var thisLink = $( this ),
                links = thisLink.closest( '.wl-shop-tab-links' ),
                uniqid = links.attr( 'id' ).replace( 'wl-shop-tab-links-', '' ),
                data = thisLink.data( 'tabvalue' ),
                tabpane = $( '#wl-shop-tab-area-' + uniqid );

            thisLink.addClass( 'htactive' ).parent().siblings().children( 'a' ).removeClass( 'htactive' );

            $( tabpane ).removeClass( 'grid_view list_view' );
            $( tabpane ).addClass( data );

            // Refresh slick
            tabpane.find( '.slick-slider' ).slick( 'refresh' );
        });
    }

    /**
     * WooLentor thubmnails slider.
     */
    function woolentorThumbnailsSlider() {
        let thumbnailSlider = $( '.wl-filterable-products-wrap .ht-product-image-slider' );

        if ( thumbnailSlider.length > 0 ) {
            thumbnailSlider.slick( {
                dots: true,
                arrows: true,
                prevArrow: '<button class="slick-prev"><i class="sli sli-arrow-left"></i></button>',
                nextArrow: '<button class="slick-next"><i class="sli sli-arrow-right"></i></button>',
            } );
        }
    }

    /**
     * WooLentor expanding scripts.
     */
    function woolentorExpandingScripts() {
        let expandingGrid = $( '.wl-filterable-products-wrap[data-wl-widget-name="wl-product-expanding-grid"]' );

        if ( ( 'object' === typeof expandingGrid ) && ( 0 < expandingGrid.length ) ) {
            wlInitExpandingScripts();
        }
    }

    /**
     * Ajax filter completed fix.
     */
    $( document ).on( 'wlpf_ajax_filter_completed', function( e, allCompleted = false ) {
        e.preventDefault();

        woocommerceOrdering();
        woocommerceOrderSync();
        woolentorCustomTabs();
        woolentorThumbnailsSlider();

        if ( true === allCompleted ) {
            woolentorExpandingScripts();
        }
    } );

    /**
     * None ajax filter completed fix.
     */
    ( function () {
        woocommerceOrderSync();
    } )();

} )( jQuery );