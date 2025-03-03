<?php
/*
  Plugin Name: Voce Performance Tools
  Plugin URI: http://voceconnect.com
  Description: Provides numerous performance tools and functionalities to assist in developing high performance WordPress themes
  Version: 1.2.3
  Author: Voce Platforms
  License: GPL2
*/

$autoload_path = implode(DIRECTORY_SEPARATOR, array(__DIR__, 'vendor', 'autoload.php'));
if ( file_exists( $autoload_path ) )
	require_once $autoload_path;

/**
 * Helper functions to cache queries and update in background
 * Uses TLC-Transients to cache and update queries
 * @param array $query_args Query arguments
 * @param int $expires_in number of seconds to cache the query
 * @param bool $transient_key optional key name used to cache the query, helpful when even driven expiration is needed
 * @return WP_Query
 */

if ( !function_exists('vpt_get_cached_query') ) {
	function vpt_get_cached_query( $query_args, $expires_in = 180, $transient_key = false ) {
		if ( !$transient_key )
			$transient_key = 'vpt_query_' . substr( md5( serialize( func_get_args() ) ), 0, 25 );

		$query = tlc_transient( $transient_key )
				->updates_with( 'vpt_get_wp_query', array($query_args) )
				->expires_in( $expires_in )
				->get();

		if(!is_a( $query, 'WP_Query' )) {
			return new WP_Query($query_args);
		}

		return $query;
	}
}

if ( !function_exists('vpt_get_wp_query') ) {
	function vpt_get_wp_query( $query_args ) {
		return new WP_Query( $query_args );
	}
}