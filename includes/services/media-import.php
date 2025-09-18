<?php
/**
 * Media Library Import for Icons
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Import a single icon SVG (brand/ui) into Media Library as attachment.
 * Returns attachment ID or WP_Error.
 */
function iconmanager_import_icon_to_media( $icon_name, $type = 'brand' ) {
    $icon_name = sanitize_file_name( $icon_name );
    $type = $type === 'ui' ? 'ui' : 'brand';
    $base = iconmanager_get_upload_base();
    $file = $base . '/' . $type . '/' . $icon_name . '.svg';
    if ( ! file_exists( $file ) ) {
        return new WP_Error( 'icon_not_found', __( 'Icon Datei nicht gefunden.', 'iconmanager' ) );
    }

    // Already in media?
    $existing = get_posts([
        'post_type' => 'attachment',
        'post_status' => 'inherit',
        'posts_per_page' => 1,
        'meta_key' => '_iconmanager_icon_name',
        'meta_value' => $icon_name . '|' . $type
    ]);
    if ( $existing ) {
        return $existing[0]->ID; // reuse
    }

    $upload_dir = wp_upload_dir();
    $contents = file_get_contents( $file );
    if ( $contents === false ) {
        return new WP_Error( 'read_failed', __( 'Konnte SVG nicht lesen.', 'iconmanager' ) );
    }

    $filename = 'iconmanager-' . $type . '-' . $icon_name . '.svg';
    $target = trailingslashit( $upload_dir['path'] ) . $filename;
    if ( ! file_put_contents( $target, $contents ) ) {
        return new WP_Error( 'write_failed', __( 'Konnte Datei nicht in Uploads schreiben.', 'iconmanager' ) );
    }

    $filetype = wp_check_filetype( $filename, null );
    $attachment_id = wp_insert_attachment([
        'post_mime_type' => $filetype['type'] ?? 'image/svg+xml',
        'post_title'     => $icon_name . ' (' . $type . ' icon)',
        'post_content'   => '',
        'post_status'    => 'inherit'
    ], $target );

    if ( is_wp_error( $attachment_id ) ) {
        return $attachment_id;
    }

    update_post_meta( $attachment_id, '_iconmanager_icon_name', $icon_name . '|' . $type );

    // No wp_generate_attachment_metadata for raw SVG needed.
    return $attachment_id;
}

/**
 * Bulk import all icons (returns array of results)
 */
function iconmanager_import_all_icons_to_media() {
    $all = iconmanager_get_available_icons();
    $results = [];
    foreach ( ['brands'=>'brand','ui'=>'ui'] as $group => $t ) {
        foreach ( $all[$group] as $icon ) {
            $res = iconmanager_import_icon_to_media( $icon, $t );
            $results[] = [ 'icon' => $icon, 'type' => $t, 'result' => is_wp_error($res) ? $res->get_error_message() : (int) $res ];
        }
    }
    return $results;
}

/* AJAX Endpoints */
add_action( 'wp_ajax_iconmanager_import_single', function(){
    if ( ! current_user_can( 'upload_files' ) ) wp_die();
    $icon = sanitize_text_field( $_POST['icon'] ?? '' );
    $type = sanitize_text_field( $_POST['type'] ?? 'brand' );
    $r = iconmanager_import_icon_to_media( $icon, $type );
    if ( is_wp_error( $r ) ) { wp_send_json_error( $r->get_error_message() ); }
    wp_send_json_success( sprintf( __( 'Icon %s (%s) importiert. Attachment ID: %d', 'iconmanager' ), $icon, $type, $r ) );
});

add_action( 'wp_ajax_iconmanager_import_all', function(){
    if ( ! current_user_can( 'upload_files' ) ) wp_die();
    $res = iconmanager_import_all_icons_to_media();
    wp_send_json_success( $res );
});
