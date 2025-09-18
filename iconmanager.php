<?php
/**
 * Plugin Name: Icon Manager
 * Plugin URI: https://github.com/atakan72/iconmanager
 * Description: DSGVO-konformes Icon Management (Brand & UI) – lokal, caching, Admin-Oberfläche.
 * Version: 1.1.2
 * Author: Atakan Öcal
 * Author URI: https://github.com/atakan72
 * Text Domain: iconmanager
 * Domain Path: /languages
 * License: GPL-2.0-or-later
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

// Konstanten
if ( ! defined( 'ICONMANAGER_FILE' ) ) {
    define( 'ICONMANAGER_FILE', __FILE__ );
}
if ( ! defined( 'ICONMANAGER_PATH' ) ) {
    define( 'ICONMANAGER_PATH', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'ICONMANAGER_URL' ) ) {
    define( 'ICONMANAGER_URL', plugin_dir_url( __FILE__ ) );
}
if ( ! defined( 'ICONMANAGER_VERSION' ) ) {
    define( 'ICONMANAGER_VERSION', '1.1.2' );
}

// Unterdrücke Notices in AJAX Antworten (zerstören sonst JSON). Nur für DOING_AJAX, nicht für normale Seiten.
if ( defined('DOING_AJAX') && DOING_AJAX ) {
    // Entferne NOTICE & DEPRECATED damit andere Plugins unsere JSON Ausgabe nicht kaputt machen
    $current_level = error_reporting();
    // Speichere eventuell für spätere Nutzung (hier nicht zwingend)
    error_reporting( $current_level & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED & ~E_USER_DEPRECATED );
}

// Activation: Prepare upload dirs
function iconmanager_activate() {
    $upload_dir = wp_upload_dir();
    $base = trailingslashit( $upload_dir['basedir'] ) . 'iconmanager-icons';
    wp_mkdir_p( $base . '/brands' );
    wp_mkdir_p( $base . '/ui' );

    // Migration: Falls alter Ordner existiert (altes Plugin) -> Icons kopieren
    $old_base = trailingslashit( $upload_dir['basedir'] ) . 'iconmanagement-dsgvo-icons';
    if ( is_dir( $old_base ) ) {
        foreach ( ['brands','ui'] as $sub ) {
            $src_dir = $old_base . '/' . $sub;
            $dst_dir = $base . '/' . $sub;
            if ( is_dir( $src_dir ) ) {
                $files = glob( $src_dir . '/*.svg' );
                if ( $files ) {
                    foreach ( $files as $file ) {
                        $target = $dst_dir . '/' . basename( $file );
                        if ( ! file_exists( $target ) ) {
                            @copy( $file, $target );
                        }
                    }
                }
            }
        }
    }
}
register_activation_hook( __FILE__, 'iconmanager_activate' );

// Load textdomain
add_action( 'plugins_loaded', function() {
    load_plugin_textdomain( 'iconmanager', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
});

// Bootstrap
function iconmanager_bootstrap() {
    $files = [
        'includes/helpers.php',
        'includes/services/icon-registry.php',
    'includes/services/icon-downloader.php',
    'includes/services/media-import.php',
        'includes/admin/admin-page.php',
        'includes/frontend/icon-render.php',
        'includes/services/gdpr-headers.php',
        'includes/compat/legacy-aliases.php'
    ];
    foreach ( $files as $rel ) {
        $abs = ICONMANAGER_PATH . $rel;
        if ( file_exists( $abs ) ) {
            require_once $abs;
        } else {
            add_action( 'admin_notices', function() use ( $rel ) {
                echo '<div class="notice notice-error"><p><strong>Icon Manager:</strong> Fehlende Datei: ' . esc_html( $rel ) . '</p></div>';
            });
        }
    }
}
add_action( 'init', 'iconmanager_bootstrap', 5 );

// Version Option / Cache invalidation
add_action( 'admin_init', function() {
    $stored = get_option( 'iconmanager_version' );
    if ( $stored !== ICONMANAGER_VERSION ) {
        wp_cache_flush();
        update_option( 'iconmanager_version', ICONMANAGER_VERSION, false );
    }
});
