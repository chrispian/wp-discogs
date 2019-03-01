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
	 * @since  2.0.0
	 */
	protected $plugin = null;

	/**
	 * Constructor
	 *
	 * @since 2.0.0
	 *
	 * @param  CHB_Auto_Update_Settings_Manager $plugin Main plugin class.
	 * @return  void
	 */
	public function __construct( $plugin ) {
		// Parent plugin.
		$this->plugin = $plugin;
	}


}
