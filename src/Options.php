<?php

namespace CHB_WP_Discogs;

// Call register settings function.
add_action( 'admin_init', [CHB_WP_Discogs()->options, 'save_plugin_settings'] );

// Create custom plugin settings menu.
add_action( 'admin_menu', [CHB_WP_Discogs()->options, 'plugin_create_menu'] );

/**
 * Class to for Options Settings
 */
class Options {
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
	 * Save Plugin Settings
	 *
	 * @since 1.0.0
	 *
	 * @return  void
	 */
	public function save_plugin_settings() {
		if ( isset( $_POST['wp_discogs_action'] ) ) {

			// Delete the option cache when we update
			wp_cache_delete ( 'alloptions', 'options' );

			// Refactor to loop through options array?

			if ( get_option( 'wp_discogs_app_consumer_key' ) !== false ) {
				// We have one so update it
				update_option( 'wp_discogs_app_consumer_key', $_POST['wp_discogs_app_consumer_key'] );

			} else {
				// Don't have it, lets add it
				add_option( 'wp_discogs_app_consumer_key', $_POST['wp_discogs_app_consumer_key'] );

			}

			if ( get_option( 'wp_discogs_app_consumer_secret' ) !== false ) {
				// We have one so update it
				update_option( 'wp_discogs_app_consumer_secret', $_POST['wp_discogs_app_consumer_secret'] );

			} else {
				// Don't have it, lets add it
				add_option( 'wp_discogs_app_consumer_secret', $_POST['wp_discogs_app_consumer_secret'] );

			}

			if ( get_option( 'wp_discogs_username' ) !== false ) {
				// We have one so update it
				update_option( 'wp_discogs_username', $_POST['wp_discogs_username'] );

			} else {
				// Don't have it, lets add it
				add_option( 'wp_discogs_username', $_POST['wp_discogs_username'] );

			}

		}
	}

	/**
	 * Create Settings Menu Link
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function plugin_create_menu() {
		add_menu_page( 'Discog Settings', 'Discog Settings', 'manage_options', __FILE__, [$this, 'plugin_settings_page'], 'dashicons-welcome-widgets-menus', 90 );
	}

	/**
	 * Create Menu UI for plugin settings
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function plugin_settings_page() {
		?>

		<h2>Discog Settings</h2>

		<div class="wrap">
			<div class="card">
				<form method="post" action=''>
					<?php
						$nonce = wp_create_nonce( 'wp_discogs_update_nonce');
					?>
					<input type='hidden' id='wp_discogs_update_nonce' name='wp_discogs_update_nonce' value='<?php echo $nonce; ?>' />
					<input type='hidden' name='wp_discogs_action' value='update_settings' />

					<p>
						Enter your Discogs API information below. If you don't already have this information you can get it here: <a href="https://www.discogs.com/settings/developers" target="_blank">https://www.discogs.com/settings/developers</a>
					</p>

					<table class="form-table">
						<tbody>
							<tr>
								<th scope="row"><label for="wp_discogs_app_consumer_key">Discogs App Cunsumer Key:</label></th>
								<td><input type="text" size="45" name="wp_discogs_app_consumer_key" value="<?php echo esc_attr( get_option( 'wp_discogs_app_consumer_key' ) ); ?>" /></td>
							</tr>
							<tr>
								<th scope="row"><label for="wp_discogs_app_consumer_secret">Discogs App Cunsumer Secret:</label></th>
								<td><input type="text" size="45" name="wp_discogs_app_consumer_secret" value="<?php echo esc_attr( get_option( 'wp_discogs_app_consumer_secret' ) ); ?>" /></td>

							<tr>
								<th scope="row"><label for="wp_discogs_username">Discogs Username:</label></th>
								<td><input type="text" size="45" name="wp_discogs_username" value="<?php echo esc_attr( get_option( 'wp_discogs_username' ) ); ?>" /></td>
							</tr>
						</tbody>
					</table>
					<?php

						submit_button();

					?>

				</form>


			</div>
		</div>
		<?php
	}




}
