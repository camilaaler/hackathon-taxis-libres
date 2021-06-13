<?php
namespace SG_Security\Feed_Service;

/**
 * Feed_Service class which disable the WordPress feed.
 */
class Feed_Service {

	/**
	 * Disables the WordPress feed.
	 *
	 * @since  1.0.0
	 */
	public function disable_feed() {
		wp_die( esc_html__( 'No feed available, please visit our homepage!' ) );
	}
}
