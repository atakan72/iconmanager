<?php
/** Frontend / Shortcode */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function iconmanager_shortcode_icon( $atts ) {
    $atts = shortcode_atts([
        'name' => '', 'type' => 'auto', 'size' => 24, 'color' => null, 'class' => ''
    ], $atts, 'icon');
    if ( ! $atts['name'] ) return '';
    return iconmanager_render_icon( $atts['name'], $atts['type'], (int)$atts['size'], $atts['color'], [ 'class' => $atts['class'] ] );
}
add_shortcode( 'icon', 'iconmanager_shortcode_icon' );

if ( ! function_exists( 'iconmanager_icon' ) ) {
    function iconmanager_icon( $name, $type='auto', $size=24, $color=null, $attributes=[] ) {
        echo iconmanager_render_icon( $name, $type, $size, $color, $attributes );
    }
}
