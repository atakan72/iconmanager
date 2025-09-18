<?php
/**
 * Legacy Aliases – ermöglicht Kompatibilität mit vorherigem Plugin (iconmgmt_dsgvo_*)
 * Markiert als deprecated.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! function_exists( 'iconmgmt_dsgvo_render_icon' ) ) {
    function iconmgmt_dsgvo_render_icon( $icon_name, $type='auto', $size=24, $color=null, $attributes=[] ) {
        _doing_it_wrong( __FUNCTION__, 'Use iconmanager_render_icon() instead.', '1.1.0' );
        return iconmanager_render_icon( $icon_name, $type, $size, $color, $attributes );
    }
}
if ( ! function_exists( 'iconmgmt_dsgvo_get_available_icons' ) ) {
    function iconmgmt_dsgvo_get_available_icons() {
        _doing_it_wrong( __FUNCTION__, 'Use iconmanager_get_available_icons() instead.', '1.1.0' );
        return iconmanager_get_available_icons();
    }
}
if ( ! function_exists( 'iconmgmt_dsgvo_download_brand_icon' ) ) {
    function iconmgmt_dsgvo_download_brand_icon( $name ) {
        _doing_it_wrong( __FUNCTION__, 'Use iconmanager_download_brand_icon() instead.', '1.1.0' );
        return iconmanager_download_brand_icon( $name );
    }
}
if ( ! function_exists( 'iconmgmt_dsgvo_download_ui_icon' ) ) {
    function iconmgmt_dsgvo_download_ui_icon( $name ) {
        _doing_it_wrong( __FUNCTION__, 'Use iconmanager_download_ui_icon() instead.', '1.1.0' );
        return iconmanager_download_ui_icon( $name );
    }
}
if ( ! function_exists( 'iconmgmt_dsgvo_download_common_brand_icons' ) ) {
    function iconmgmt_dsgvo_download_common_brand_icons() {
        _doing_it_wrong( __FUNCTION__, 'Use iconmanager_download_common_brand_icons() instead.', '1.1.0' );
        return iconmanager_download_common_brand_icons();
    }
}
if ( ! function_exists( 'iconmgmt_dsgvo_download_common_ui_icons' ) ) {
    function iconmgmt_dsgvo_download_common_ui_icons() {
        _doing_it_wrong( __FUNCTION__, 'Use iconmanager_download_common_ui_icons() instead.', '1.1.0' );
        return iconmanager_download_common_ui_icons();
    }
}
