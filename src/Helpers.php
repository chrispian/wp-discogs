<?php

namespace CHB_WP_Discogs;

/**
 * Class to hold helper functions
 */
class Helpers {
	/**
	 * Parent plugin class
	 *
	 * @var class
	 * @since  1.0.0
	 */
	protected $plugin = null;

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @param  CHB_WP_Discogs $plugin Main plugin class.
	 * @return  void
	 */
	public function __construct( $plugin ) {
		// Parent plugin.
		$this->plugin = $plugin;
	}


	/**
	 * Get Discogs Auth Settings
	 *
	 * @since 1.0.0
	 *
	 * @param  null
	 * @return  void
	 */
	public function get_auth_settings() {

		// Bail early if we don't have the keys.
		if ( get_option( 'wp_discogs_app_consumer_key' ) == false || get_option( 'wp_discogs_app_consumer_secret' ) == false ) {
			return;
		}

		$auth_settings                                   = [];
		$auth_settings['wp_discogs_app_consumer_key']    = get_option( 'wp_discogs_app_consumer_key' );
		$auth_settings['wp_discogs_app_consumer_secret'] = get_option( 'wp_discogs_app_consumer_secret' );
		$auth_settings['wp_discogs_username']            = get_option( 'wp_discogs_username' );

		return $auth_settings;

	}

}
