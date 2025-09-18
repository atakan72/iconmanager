<?php
/** GDPR / Security Headers */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function iconmanager_send_headers() {
    if ( is_admin() ) return;
    $uri = $_SERVER['REQUEST_URI'] ?? '';
    if ( strpos( $uri, 'iconmanager-icons' ) !== false ) {
        header( 'Cache-Control: public, max-age=31536000, immutable' );
        header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + 31536000 ) . ' GMT' );
        header( 'X-Content-Type-Options: nosniff' );
        header( 'X-Frame-Options: DENY' );
        header( 'Referrer-Policy: strict-origin-when-cross-origin' );
        header( 'Vary: Accept-Encoding' );
    }
}
add_action( 'send_headers', 'iconmanager_send_headers' );
