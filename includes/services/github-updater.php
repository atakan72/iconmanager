<?php
/**
 * Lightweight GitHub updater for Icon Manager.
 * Shows update notice in WP if a newer GitHub tag (semver vX.Y.Z) exists.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

// Configuration: repository data.
const ICONMANAGER_GH_OWNER = 'atakan72';
const ICONMANAGER_GH_REPO  = 'iconmanager';

/**
 * Build transient key.
 */
function iconmanager_github_transient_key( $suffix ) {
	return 'iconmanager_gh_' . $suffix;
}

/**
 * Fetch latest release/tag info from GitHub (public API, no auth).
 * Returns array: [ 'tag_name' => 'v1.2.3', 'zipball_url' => '...', 'body' => 'changelog' ] or WP_Error.
 */
function iconmanager_github_latest_release() {
	$cache_key = iconmanager_github_transient_key( 'latest_release' );
	$cached    = get_transient( $cache_key );
	if ( $cached ) {
		return $cached;
	}

	$url = sprintf( 'https://api.github.com/repos/%s/%s/releases/latest', ICONMANAGER_GH_OWNER, ICONMANAGER_GH_REPO );
	$args = [
		'headers' => [
			'Accept' => 'application/vnd.github+json',
			'User-Agent' => 'iconmanager-plugin'
		],
		'timeout' => 15,
	];
	$response = wp_remote_get( $url, $args );
	if ( is_wp_error( $response ) ) {
		return $response;
	}

	$code = wp_remote_retrieve_response_code( $response );
	$body = wp_remote_retrieve_body( $response );
	if ( 200 !== $code || empty( $body ) ) {
		return new WP_Error( 'iconmanager_github_http', 'GitHub API HTTP error.' );
	}

	$data = json_decode( $body, true );
	if ( ! is_array( $data ) || empty( $data['tag_name'] ) ) {
		return new WP_Error( 'iconmanager_github_json', 'Invalid GitHub JSON.' );
	}

	// Normalize - ensure semantic tag starts with v.
	$release = [
		'tag_name'   => $data['tag_name'],
		'zipball_url'=> $data['zipball_url'],
		'body'       => isset( $data['body'] ) ? $data['body'] : ''
	];

	// Cache 30 min.
	set_transient( $cache_key, $release, 30 * MINUTE_IN_SECONDS );
	return $release;
}

/**
 * Compare versions ignoring leading 'v'. Returns true if remote > local.
 */
function iconmanager_version_is_newer( $remote_tag, $local_version ) {
	$remote = ltrim( $remote_tag, 'vV' );
	return version_compare( $remote, $local_version, '>' );
}

/**
 * Inject update data into plugins_api (plugin details lightbox) when user clicks 'View details'.
 */
add_filter( 'plugins_api', function( $result, $action, $args ) {
	if ( 'plugin_information' !== $action ) { return $result; }
	if ( empty( $args->slug ) || 'iconmanager' !== $args->slug ) { return $result; }

	$release = iconmanager_github_latest_release();
	if ( is_wp_error( $release ) ) { return $result; }

	$remote_version = ltrim( $release['tag_name'], 'vV' );

	$info = new stdClass();
	$info->name          = 'Icon Manager';
	$info->slug          = 'iconmanager';
	$info->version       = $remote_version;
	$info->author        = '<a href="https://github.com/atakan72">Atakan Öcal</a>';
	$info->author_profile= 'https://github.com/atakan72';
	$info->homepage      = 'https://github.com/atakan72/iconmanager';
	$info->download_link = $release['zipball_url'];
	$info->trunk         = $release['zipball_url'];
	$info->requires      = '5.8';
	$info->tested        = get_bloginfo( 'version' );
	$info->last_updated  = current_time( 'mysql' );
	$info->sections = [
		'description' => wpautop( esc_html__( 'GitHub distributed version of Icon Manager. Install updates by tagging releases.', 'iconmanager' ) ),
		'changelog'   => '<pre style="white-space:pre-wrap">' . esc_html( $release['body'] ) . '</pre>'
	];
	return $info;
}, 10, 3 );

/**
 * Add update response so WP shows "There is a new version".
 */
add_filter( 'site_transient_update_plugins', function( $transient ) {
	if ( empty( $transient ) || ! is_object( $transient ) ) { return $transient; }

	$release = iconmanager_github_latest_release();
	if ( is_wp_error( $release ) ) { return $transient; }

	if ( iconmanager_version_is_newer( $release['tag_name'], ICONMANAGER_VERSION ) ) {
		$remote_version = ltrim( $release['tag_name'], 'vV' );
		$plugin_file = plugin_basename( ICONMANAGER_FILE );
		$transient->response[ $plugin_file ] = (object) [
			'slug'        => 'iconmanager',
			'plugin'      => $plugin_file,
			'new_version' => $remote_version,
			'package'     => $release['zipball_url'],
			'url'         => 'https://github.com/atakan72/iconmanager',
		];
	}
	return $transient;
});

/**
 * Clear our GitHub transient when user forces update check.
 */
add_action( 'wp_update_plugins', function() {
	delete_transient( iconmanager_github_transient_key( 'latest_release' ) );
});

