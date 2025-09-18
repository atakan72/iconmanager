<?php
/**
 * Helper Funktionen – Icon Manager
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Basis Upload Pfad für Icons (neu: iconmanager-icons)
 */
function iconmanager_get_upload_base() {
    $upload_dir = wp_upload_dir();
    return trailingslashit( $upload_dir['basedir'] ) . 'iconmanager-icons';
}

/**
 * Verfügbare Icons (brands/ui) – 1 Tag gecached
 */
function iconmanager_get_available_icons() {
    $cache_key = 'iconmanager_available_icons_v1';
    $cached = wp_cache_get( $cache_key, 'iconmanager' );
    if ( $cached !== false ) return $cached;

    $base = iconmanager_get_upload_base();
    $brands_dir = $base . '/brands/';
    $ui_dir     = $base . '/ui/';
    $available = [ 'brands' => [], 'ui' => [] ];

    if ( is_dir( $brands_dir ) ) {
        foreach ( glob( $brands_dir . '*.svg' ) as $f ) { $available['brands'][] = basename( $f, '.svg' ); }
    }
    if ( is_dir( $ui_dir ) ) {
        foreach ( glob( $ui_dir . '*.svg' ) as $f ) { $available['ui'][] = basename( $f, '.svg' ); }
    }

    wp_cache_set( $cache_key, $available, 'iconmanager', DAY_IN_SECONDS );
    return $available;
}

/**
 * Attribut-String Builder
 */
function iconmanager_build_attr_string( $attributes ) {
    $attr_string = '';
    foreach ( (array) $attributes as $k => $v ) {
        if ( $v === null || $v === '' ) continue;
        $attr_string .= ' ' . esc_attr( $k ) . '="' . esc_attr( $v ) . '"';
    }
    return $attr_string;
}
