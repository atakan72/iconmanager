<?php
/**
 * Icon Registry & Rendering – Icon Manager
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function iconmanager_render_icon( $icon_name, $type = 'auto', $size = 24, $color = null, $attributes = [] ) {
    $icon_name = sanitize_text_field( $icon_name );
    $size = (int) $size;

    $known_brands = [ 'tiktok','instagram','facebook','youtube','linkedin','twitter','x','pinterest','snapchat','discord','telegram','whatsapp','reddit','tumblr','twitch','spotify','soundcloud','github','gitlab','behance','dribbble','medium','codepen','figma','sketch','adobe','shopify','paypal','stripe' ];
    if ( $type === 'auto' ) {
        $type = in_array( strtolower( $icon_name ), $known_brands, true ) ? 'brand' : 'ui';
    }

    $base = iconmanager_get_upload_base();
    $sub  = $type === 'brand' ? 'brands' : 'ui';
    $file = $base . '/' . $sub . '/' . $icon_name . '.svg';

    if ( ! file_exists( $file ) && is_admin() ) {
        $downloaded = false;
        if ( $type === 'brand' ) { $downloaded = iconmanager_download_brand_icon( $icon_name ); }
        else { $downloaded = iconmanager_download_ui_icon( $icon_name ); }
        if ( $downloaded ) {
            wp_cache_delete( 'iconmanager_available_icons_v1', 'iconmanager' );
        }
    }
    if ( ! file_exists( $file ) ) {
        return '<span class="icon-missing" title="Icon not found">❔</span>';
    }

    $attr_defaults = [ 'class' => 'icon', 'aria-hidden' => 'true' ];
    $attributes = array_merge( $attr_defaults, $attributes );
    $attr_string = iconmanager_build_attr_string( $attributes );

    $cache_key = 'iconmanager_render_' . md5( $icon_name . '|' . $type . '|' . $size . '|' . $color . '|' . serialize( $attributes ) );
    $cached = wp_cache_get( $cache_key, 'iconmanager' );
    if ( $cached !== false ) return $cached;

    $svg = file_get_contents( $file );
    if ( ! $svg ) return '';

    if ( $type === 'ui' ) {
        $svg = preg_replace( '/<svg /', '<svg width="' . $size . '" height="' . $size . '" ', $svg, 1 );
        if ( $color ) {
            $svg = preg_replace( '/<svg /', '<svg style="color:' . esc_attr( $color ) . ';" ', $svg, 1 );
        }
        $output = '<span' . $attr_string . '>' . $svg . '</span>';
    } else {
        $upload_dir = wp_upload_dir();
        $url = trailingslashit( $upload_dir['baseurl'] ) . 'iconmanager-icons/' . $sub . '/' . $icon_name . '.svg';
        $style = $color ? ' style="color:' . esc_attr( $color ) . ';"' : '';
        $output = '<img src="' . esc_url( $url ) . '" alt="' . esc_attr( $icon_name ) . '" width="' . $size . '" height="' . $size . '"' . $attr_string . $style . ' />';
    }
    wp_cache_set( $cache_key, $output, 'iconmanager', 7 * DAY_IN_SECONDS );
    return $output;
}

// Kompatibilität für frühere Funktion
if ( ! function_exists( 'pure_aesthetic_render_icon' ) ) {
    function pure_aesthetic_render_icon( $icon_name, $type = 'auto', $size = 24, $color = null, $attributes = [] ) {
        return iconmanager_render_icon( $icon_name, $type, $size, $color, $attributes );
    }
}
