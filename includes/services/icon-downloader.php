<?php
/**
 * Icon Download Service – Icon Manager (Simple Icons + Lucide)
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function iconmanager_download_brand_icon( $icon_name ) {
    $icon_name = sanitize_file_name( $icon_name );
    if ( ! $icon_name ) return false;
    $base  = iconmanager_get_upload_base();
    $dir   = $base . '/brands/';
    $local = $dir . $icon_name . '.svg';
    if ( file_exists( $local ) ) return true;
    $remote   = 'https://cdn.simpleicons.org/' . $icon_name;
    $response = wp_remote_get( $remote, [ 'timeout' => 15 ] );
    if ( is_wp_error( $response ) ) return false;
    $body = wp_remote_retrieve_body( $response );
    if ( empty( $body ) || strpos( $body, '<svg' ) === false ) return false;
    wp_mkdir_p( $dir );
    $ok = file_put_contents( $local, $body ) !== false;
    if ( $ok ) { wp_cache_delete( 'iconmanager_available_icons_v1', 'iconmanager' ); }
    return $ok;
}

function iconmanager_download_ui_icon( $icon_name ) {
    $icon_name = sanitize_file_name( $icon_name );
    if ( ! $icon_name ) return false;
    $base  = iconmanager_get_upload_base();
    $dir   = $base . '/ui/';
    $local = $dir . $icon_name . '.svg';
    if ( file_exists( $local ) ) return true;
    $urls = [
        "https://unpkg.com/lucide@latest/icons/{$icon_name}.svg",
        "https://cdn.jsdelivr.net/npm/lucide@latest/icons/{$icon_name}.svg",
        "https://raw.githubusercontent.com/lucide-icons/lucide/main/icons/{$icon_name}.svg"
    ];
    $svg = false;
    foreach ( $urls as $url ) {
        $response = wp_remote_get( $url, [ 'timeout' => 10 ] );
        if ( ! is_wp_error( $response ) ) {
            $body = wp_remote_retrieve_body( $response );
            $code = wp_remote_retrieve_response_code( $response );
            if ( $code === 200 && $body && strpos( $body, '<svg' ) !== false ) { $svg = $body; break; }
        }
    }
    if ( ! $svg ) return false;
    wp_mkdir_p( $dir );
    $ok = file_put_contents( $local, $svg ) !== false;
    if ( $ok ) { wp_cache_delete( 'iconmanager_available_icons_v1', 'iconmanager' ); }
    return $ok;
}

function iconmanager_download_common_brand_icons() {
    $icons = [ 'twitter','x','pinterest','snapchat','discord','telegram','whatsapp','reddit','tumblr','twitch','spotify','soundcloud','github','gitlab','behance','dribbble','medium','codepen' ];
    $c = 0; foreach ( $icons as $i ) { if ( iconmanager_download_brand_icon( $i ) ) $c++; usleep(50000); }
    wp_cache_delete( 'iconmanager_available_icons_v1', 'iconmanager' ); return $c;
}
function iconmanager_download_common_ui_icons() {
    $icons = [ 'home','menu','search','user','settings','heart','star','mail','phone','map-pin','calendar','clock','file-down','upload','edit','trash-2','eye','eye-off','lock','unlock','arrow-left','arrow-right','arrow-up','arrow-down','chevron-left','chevron-right','chevron-up','chevron-down','plus','minus','x','play-circle' ];
    $c = 0; foreach ( $icons as $i ) { if ( iconmanager_download_ui_icon( $i ) ) $c++; usleep(50000); }
    wp_cache_delete( 'iconmanager_available_icons_v1', 'iconmanager' ); return $c;
}

/* AJAX */
add_action( 'wp_ajax_iconmanager_download_brand_icon', function(){ if(!current_user_can('manage_options')) wp_die(); $n=sanitize_text_field($_POST['icon_name']??''); $ok=iconmanager_download_brand_icon($n); $ok?wp_send_json_success(__('Brand Icon geladen.','iconmanager')):wp_send_json_error(__('Fehler beim Download.','iconmanager')); });
add_action( 'wp_ajax_iconmanager_download_ui_icon', function(){ if(!current_user_can('manage_options')) wp_die(); $n=sanitize_text_field($_POST['icon_name']??''); $ok=iconmanager_download_ui_icon($n); $ok?wp_send_json_success(__('UI Icon geladen.','iconmanager')):wp_send_json_error(__('Fehler beim Download.','iconmanager')); });
add_action( 'wp_ajax_iconmanager_download_common_brands', function(){ if(!current_user_can('manage_options')) wp_die(); $c=iconmanager_download_common_brand_icons(); wp_send_json_success(sprintf(__('%d Brand Icons geladen.','iconmanager'),$c)); });
add_action( 'wp_ajax_iconmanager_download_common_ui', function(){ if(!current_user_can('manage_options')) wp_die(); $c=iconmanager_download_common_ui_icons(); wp_send_json_success(sprintf(__('%d UI Icons geladen.','iconmanager'),$c)); });
