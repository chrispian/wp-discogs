<?php
/**
 *
 *  Plugin to pull in Discogs collection, wishlist, etc.
 *
 * @link              http://www.chrispian.com
 * @since             1.0.0
 * @package           Ausm_Plugin
 *
 * @wordpress-plugin
 * Plugin Name:       WP Discogs
 * Plugin URI:        http://www.chrispian.com
 * Description:       Uses Discogs API to pull in data from discogs.com and insert into WordPress
 * Version:           1.0.0
 * Author:            Chrispian H. Burks
 * Author URI:        http://www.chrispian.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-discogs
 * Domain Path:       /languages
 *
 * Todo:
 *
 * - Create settings for discogs API authorization
 *
 */

namespace CHB_WP_Discogs;

require_once ( dirname(__FILE__) . '/vendor/autoload.php' );

// If this file is called directly, abort.
defined( 'ABSPATH' ) || die( 'Direct Access Not Permitted.' );

/**
 * Main initiation class
 *
 * @since  1.0.0
 * @var  string $version  Plugin version
 * @var  string $basename Plugin basename
 * @var  string $url      Plugin URL
 * @var  string $path     Plugin Path
 */
class CHB_WP_Discogs {

	/**
	 * Current version
	 *
	 * @var  string
	 * @since  1.0.0
	 */
	const VERSION = '1.0.0';

	/**
	 * URL of plugin directory
	 *
	 * @var string
	 * @since  1.0.0
	 */
	protected $url = '';

	/**
	 * Path of plugin directory
	 *
	 * @var string
	 * @since  1.0.0
	 */
	protected $path = '';

	/**
	 * Plugin basename
	 *
	 * @var string
	 * @since  1.0.0
	 */
	protected $basename = '';

	/**
	 * Options instance.
	 * @var string
	 */
	protected $options = '';

	/**
	 * Helpers instance.
	 * @var string
	 */
	protected $helpers = '';

	/**
	 * Core instance.
	 * @var string
	 */
	protected $core = '';

	/**
	 * Singleton instance of plugin
	 *
	 * @var CHB_WP_Discogs
	 * @since  1.0.0
	 */
	protected static $single_instance = null;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @since  1.0.0
	 * @return CHB_WP_Discogs A single instance of this class.
	 */
	public static function get_instance() {
		if ( null === self::$single_instance ) {
			self::$single_instance = new self();
		}

		return self::$single_instance;
	}

	/**
	 * Sets up our plugin
	 *
	 * @since  1.0.0
	 */
	protected function __construct() {
		$this->basename = plugin_basename( __FILE__ );
		$this->url      = plugin_dir_url( __FILE__ );
		$this->path     = plugin_dir_path( __FILE__ );

		$this->plugin_classes();
		$this->hooks();
	}

	/**
	 * Attach other plugin classes to the base plugin class.
	 *
	 * @since 1.0.0
	 * @return  null
	 */
	public function plugin_classes() {
		$this->options = new Options( $this ) ;
		$this->helpers = new Helpers( $this );
		$this->core    = new Core( $this );
	}

	/**
	 * Add hooks and filters
	 *
	 * @since 1.0.0
	 * @return null
	 */
	public function hooks() {
		register_activation_hook( __FILE__, array( $this, '_activate' ) );
		register_deactivation_hook( __FILE__, array( $this, '_deactivate' ) );

		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Activate the plugin
	 *
	 * @since  1.0.0
	 * @return null
	 */
	function _activate() {}

	/**
	 * Deactivate the plugin
	 * Uninstall routines should be in uninstall.php
	 *
	 * @since  1.0.0
	 * @return null
	 */
	function _deactivate() {}

	/**
	 * Init hooks
	 *
	 * @since  1.0.0
	 * @return null
	 */
	public function init() {

	}

	/**
	 * Check that all plugin requirements are met
	 *
	 * @since  1.0.0
	 * @return boolean
	 */
	public static function meets_requirements() {

		// We have met all requirements
		return true;
	}

	/**
	 * Check if the plugin meets requirements and
	 * disable it if they are not present.
	 *
	 * @since  1.0.0
	 * @return boolean result of meets_requirements
	 */
	public function check_requirements() {
		if ( ! $this->meets_requirements() ) {

			// Add a dashboard notice
			add_action( 'all_admin_notices', array( $this, 'requirements_not_met_notice' ) );

			// Deactivate our plugin
			deactivate_plugins( $this->basename );

			return false;
		}

		return true;
	}

	/**
	 * Adds a notice to the dashboard if the plugin requirements are not met
	 *
	 * @since  1.0.0
	 * @return null
	 */
	public function requirements_not_met_notice() {
		// Output our error
		echo '<div id="message" class="error">';
		echo '<p>' . sprintf( __( 'Requirements have not been met so this plugin has been <a href="%s">deactivated</a>. Please make sure all requirements are available.', 'ausm-plugin' ), admin_url( 'plugins.php' ) ) . '</p>';
		echo '</div>';
	}

	/**
	 * Magic getter for our object.
	 *
	 * @since  1.0.0
	 * @param string $field
	 * @throws Exception Throws an exception if the field is invalid.
	 * @return mixed
	 */
	public function __get( $field ) {
		switch ( $field ) {
			case 'version':
				return self::VERSION;
			case 'basename':
			case 'url':
			case 'path':
			case 'options':
			case 'helpers':
			case 'core':
				return $this->$field;
			default:
				throw new Exception( 'Invalid '. __CLASS__ .' property: ' . $field );
		}
	}

	/**
	 * Include a file from the includes directory
	 *
	 * @since  1.0.0
	 * @param  string  $filename Name of the file to be included
	 * @return bool    Result of include call.
	 */
	public static function include_file( $filename ) {
		$file = self::dir( 'includes/'. $filename .'.php' );
		if ( file_exists( $file ) ) {
			return include_once( $file );
		}
		return false;
	}

	/**
	 * This plugin's directory
	 *
	 * @since  1.0.0
	 * @param  string $path (optional) appended path
	 * @return string       Directory and path
	 */
	public static function dir( $path = '' ) {
		static $dir;
		$dir = $dir ? $dir : trailingslashit( dirname( __FILE__ ) );
		return $dir . $path;
	}

	/**
	 * This plugin's url
	 *
	 * @since  1.0.0
	 * @param  string $path (optional) appended path
	 * @return string       URL and path
	 */
	public static function url( $path = '' ) {
		static $url;
		$url = $url ? $url : trailingslashit( plugin_dir_url( __FILE__ ) );
		return $url . $path;
	}
}

/**
 * Grab the CHB_WP_Discogs Object and return it.
 * Wrapper for CHB_WP_Discogs::get_instance()
 *
 * @since  2.0.0
 * @return CHB_WP_Discogs Singleton instance of plugin class.
 */
function chb_wp_discogs() {
	return CHB_WP_Discogs::get_instance();
}

// Kick it off
chb_wp_discogs();
