<?php
namespace SG_Security\Rest;

use SG_Security;
use SG_Security\Helper\Helper;

/**
 * Rest Helper class that manages the plugin dashboard.
 */
class Rest_Helper_Dashboard extends Rest_Helper {

	/**
	 * Sends notifications info.
	 *
	 * @since  1.0.0
	 */
	public function notifications() {
		// Prepare the response array.
		$response = array();

		// Add notification if we have updates available.
		if ( true === Helper::has_updates() ) {
			$response = array(
				array(
					'title'       => __( 'YOUR WORDPRESS NEEDS ATTENTION', 'sg-security' ),
					'text'        => __( 'There are new updates for your website. Keeping your WordPress updated is crucial for your website security', 'sg-security' ),
					'button_text' => __( 'Update', 'sg-security' ),
					'button_link' => admin_url( 'update-core.php' ),
				),
			);
		}

		// Send the response.
		self::send_json(
			'',
			1,
			$response
		);
	}

	/**
	 * Send information about the security hardening boxes.
	 *
	 * @since  1.0.0
	 */
	public function hardening() {
		self::send_json(
			'',
			1,
			array(
				array(
					'icon'        => 'product-ssl-wildcard',
					'icon_color'  => 'royal',
					'text'        => __( 'Set up rules to harden your website security and prevent malware, bruteforce and other security issues.', 'sg-security' ),
					'button_text' => __( 'Manage Security', 'sg-security' ),
					'button_link' => admin_url( 'admin.php?page=site-security' ),
					'title'       => __( 'Site Security', 'sg-security' ),
				),
				array(
					'icon'        => 'product-ssl-encryption',
					'icon_color'  => 'grassy',
					'text'        => __( 'Protect your login from unauthorised visitors, bots and other human or automated attacks.', 'sg-security' ),
					'button_text' => __( 'Manage Login', 'sg-security' ),
					'button_link' => admin_url( 'admin.php?page=login-settings' ),
					'title'       => __( 'Login Security', 'sg-security' ),
				),
			)
		);
	}

	/**
	 * Sends information about the free ebook.
	 *
	 * @since  1.0.0
	 */
	public function ebook() {

		$data = array(
			'image' => SG_Security\URL . '/assets/images/ebook.png',
			'link'  => 'https://www.siteground.com/wordpress-security-ebook?utm_source=sgesecurity&utm_campaign=ebookwpsecurity',
			'title'  => __( 'Free ebook', 'sg-security' ),
		);

		if ( ! file_exists( '/Z' ) ) {
			$data = array(
				'image' => SG_Security\URL . '/assets/images/banner.png',
				'link'  => 'https://www.siteground.com/wordpress-hosting.htm?mktafcode=9832cb52871fb4c5792efb7c32e4a755',
				'title'  => __( 'Get Secure WordPress Hosting', 'sg-security' ),
			);
		}


		self::send_json(
			'',
			1,
			$data
		);
	}
}

