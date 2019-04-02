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
 *
 */

namespace CHB_WP_Discogs;

require_once ('../../../../../wp-load.php' );
require_once ('../../vendor/autoload.php' );

use OAuth\OAuth1\Service\BitBucket;
use OAuth\Common\Storage\Session;
use OAuth\Common\Consumer\Credentials;
use Discogs;

/**
 * Main initiation class
 *
 * @since  1.0.0
 * @var  string $version  Plugin version
 * @var  string $basename Plugin basename
 * @var  string $url      Plugin URL
 * @var  string $path     Plugin Path
 */
class CHB_WP_Discogs_RecordDetails {

	/**
	 * Current version
	 *
	 * @var  string
	 * @since  1.0.0
	 */
	const VERSION = '1.0.0';

	/**
	 * Record to look up
	 *
	 * @var int
	 * @since  1.0.0
	 */
	protected $record_id = '';

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
	 * Helpers instance.
	 * @var string
	 */
	protected $helpers = '';

	/**
	 * Singleton instance of plugin
	 *
	 * @var CHB_WP_Discogs_RecordDetails
	 * @since  1.0.0
	 */
	protected static $single_instance = null;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @since  1.0.0
	 * @return CHB_WP_Discogs_RecordDetails A single instance of this class.
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
		$this->helpers = new Helpers( $this );
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
			case 'record_id':
			case 'path':
			case 'helpers':
				return $this->$field;
			default:
				throw new Exception( 'Invalid '. __CLASS__ .' property: ' . $field );
		}
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



	/**
	 * Get the details for the current record
	 * Will eventually support attributes etc.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function getRecordDetails( $record_id = null ) {

		if ( ! $record_id ) {
			return;
		}

		$cache_key      = "getRecordDetails-" .$record_id;
		$auth_settings  = $this->helpers->get_auth_settings();
		$consumerKey    = $auth_settings['wp_discogs_app_consumer_key'];
		$consumerSecret = $auth_settings['wp_discogs_app_consumer_secret'];

		$client = Discogs\ClientFactory::factory([
			'defaults' => [
				'headers' => ['User-Agent' => 'wp-discogs/1.0 +https://github.com/chrispian/wp-discogs'],
				'query' => [
					'key' => $consumerKey,
					'secret' => $consumerSecret,
				],
			]
		]);
		$client->getHttpClient()->getEmitter()->attach(new Discogs\Subscriber\ThrottleSubscriber());

		// Pull from cache if possible and not rebuilding cache.
		if ( ! $items = get_transient( $cache_key ) ) {

			$record = $client->getRelease([
				'id' => $record_id
			]);

			// Cache to reduce API calls.
			set_transient( $cache_key, $items, DAY_IN_SECONDS );

		}


		// videos (arr, arr (Duration, embed, title, desc, uri))
		// images
		// genres
		// styles
		// tracklist

		// Set a default error message in case we don't have $items.
		$html = "Sorry, something went wrong and we could not get a list of records. Please try again.";

		// Loop through results if we have them.
		if ( $record ) {

			$html = '';

			$image = "<img style=\"float: left;\" src=\"" . $record['thumb'] . "\" alt=\"" . $record['title'] . "\" width=\"150\" height=\"150\" border=\"0\"><br />" . PHP_EOL;

			if ( $record['images'] ) {
				$image = "<img style=\"float: left; padding-right: 15px; padding-bottom: 6px;\" src=\"" . $record['images'][0][uri] . "\" alt=\"" . $record['title'] . "\" width=\"350\" height=\"350\" border=\"0\"><br />" . PHP_EOL;
			}


			if ($record['genres']) {
				foreach ( $record['genres'] as $genre ) {
					$record_genres .= $genre;
					if (end($record['genres']) != $genre) {
						$record_genres .= ', ';
					}
				}
			}

			if ($record['styles']) {
				foreach ( $record['styles'] as $style ) {
					$record_styles .= $style;
					if (end($record['styles']) != $style) {
						$record_styles .= ', ';
					}
				}
			}

			$genres_and_styles = $record_genres . $record_styles;
			if ( $record_genres && $record_styles ) {
				$genres_and_styles = $record_genres . ' / ' . $record_styles;
			}

			$html .= "<div class='discogs_card_modal'>" . PHP_EOL;
			$html .= $image;
			$html .= "Title: " . $record['title'] . "<br />" . PHP_EOL;
			$html .= "Released: " . $record['released_formatted'] . "<br />" . PHP_EOL;
			$html .= "Rating: " . $record['community']['rating']['average'] . " out of 5<br />" . PHP_EOL;
			$html .= "Genre/Style: " . $genres_and_styles . "<br />" . PHP_EOL;

			if ( $record['artists'] ) {
				foreach ($record['artists'] as $artist) {
					if ($artist['name']) {
						$artist_name = str_replace(" (2)", "", $artist['name']);
						$html .= "<p>Artists: " . $artist_name . "</p>" . PHP_EOL;
					}
				}
			}

			if ($record['tracklist']) {
				$html .= "<p><strong>Traclist</strong><br />" . PHP_EOL;
				foreach ( $record['tracklist'] as $track ) {
					$html .= $track['title'] .  " - " . $track['duration'] . "<br />" . PHP_EOL;
				}
			}

			if ($record['notes']) {
				$notes = preg_replace('/\[url=(.+?)\](.+?)\[\/url\]/', '<a href="\1">\2</a>', $record['notes']);
				$html .= "<div style='clear: both;'><p>Notes: " . $notes . "</p></div>" . PHP_EOL;
			}

			if ($record['videos']) {
				$html .= "<p><strong>Videos</strong><br />" . PHP_EOL;
				foreach ( $record['videos'] as $video ) {
					$video_embed_url = str_replace('https://www.youtube.com/watch?v=', 'https://www.youtube.com/embed/', $video['uri']);
					$html .= '<iframe width="740" height="370" src="' . $video_embed_url . '" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe><br />' . PHP_EOL;
				}
			}



			$html .= "</div>" . PHP_EOL;

		}

		return $html;

	}

}

/**
 * Grab the CHB_WP_Discogs_RecordDetails Object and return it.
 * Wrapper for CHB_WP_Discogs_RecordDetails::get_instance()
 *
 * @since  1.0.0
 * @return CHB_WP_Discogs Singleton instance of plugin class.
 */
function chb_wp_discogs_record_details() {
	return CHB_WP_Discogs_RecordDetails::get_instance();
}

// Kick it off

$content = chb_wp_discogs_record_details()->getRecordDetails( $_REQUEST['record_id'] );

echo $content;

