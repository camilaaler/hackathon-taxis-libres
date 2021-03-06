<?php
namespace SG_Security\Admin;

use SG_Security;
use SG_Security\Helper\Helper;
use SG_Security\I18n\I18n;

/**
 * Handle all hooks for our custom admin page.
 */
class Admin {

	/**
	 * Subpages array.
	 *
	 * @var array
	 */
	public $subpages = array(
		'site-security'     => 'Site Security',
		'login-settings'    => 'Login Security',
		'activity-log'      => 'Activity Log',
		'post-hack-actions' => 'Post-hack Actions',
	);

	/**
	 * Get the subpages id.
	 *
	 * @since  1.0.0
	 *
	 * @return array The subpages id's array.
	 */
	public function get_plugin_page_ids() {
		$subpage_ids = array(
			'toplevel_page_sg-security',
			'toplevel_page_sg-security-network',
		);

		foreach ( $this->subpages as $id => $title ) {
			$subpage_ids[] = 'sg-security_page_' . $id . '';
			$subpage_ids[] = 'sg-security_page_' . $id . '-network';
		}

		return $subpage_ids;
	}

	/**
	 * Hide all errors and notices on our custom dashboard.
	 *
	 * @since  1.0.0
	 */
	public function hide_errors_and_notices() {
		// Hide all error in our page.
		if (
			isset( $_GET['page'] ) &&
			'sg-security' === $_GET['page']
		) {
			remove_all_actions( 'network_admin_notices' );
			remove_all_actions( 'user_admin_notices' );
			remove_all_actions( 'admin_notices' );
			remove_all_actions( 'all_admin_notices' );

			error_reporting( 0 );
		}
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles() {
		// Bail if we are on different page.
		if ( false === $this->is_plugin_page() ) {
			return;
		}

		wp_enqueue_style(
			'sg-security-admin',
			\SG_Security\URL . '/assets/css/main.min.css',
			array(),
			\SG_Security\VERSION,
			'all'
		);

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {
		// Bail if we are on different page.
		if ( false === $this->is_plugin_page() ) {
			return;
		}

		// Enqueue the sg-security script.
		wp_enqueue_script(
			'sg-security-admin',
			\SG_Security\URL . '/assets/js/main.min.js',
			array( 'jquery' ), // Dependencies.
			\SG_Security\VERSION,
			true
		);
	}

	/**
	 * Check if this is the SG Security page.
	 *
	 * @since  1.0.0
	 *
	 * @return bool True/False
	 */
	public function is_plugin_page() {
		$current_screen = \get_current_screen();

		if ( in_array( $current_screen->id, $this->get_plugin_page_ids() ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Register the top level page into the WordPress admin menu.
	 *
	 * @since 1.0.0
	 */
	public function add_plugin_pages() {
		$page = \add_menu_page(
			__( 'SG Security', 'sg-security' ), // Page title.
			__( 'SG Security', 'sg-security' ), // Menu item title.
			'manage_options',
			\SG_Security\PLUGIN_SLUG,   // Page slug.
			array( $this, 'render' ),
			\SG_Security\URL . '/assets/images/icon.svg'
		);

		foreach ( $this->subpages as $id => $title ) {
			add_submenu_page(
				\SG_Security\PLUGIN_SLUG,   // Parent slug.
				$title,
				$title,
				'manage_options',
				$id,
				array( $this, 'render' )
			);
		}
	}

	/**
	 * Add styles to WordPress admin head.
	 *
	 * @since  1.0.0
	 */
	public function admin_print_styles() {
		echo '<style>.toplevel_page_sg-security.menu-top .wp-menu-image img { width:20px; margin: auto; } </style>';

		$current_screen = \get_current_screen();

		if ( ! in_array( $current_screen->id, $this->get_plugin_page_ids() ) ) {
			return;
		}

		$id = strtoupper( str_replace(
			array(
				'sg-security_page_',
				'_network',
				'-',
			),
			array(
				'',
				'',
				'_',
			),
			$current_screen->id
		));

		if ( 'TOPLEVEL_PAGE_SG_SECURITY' === $id ) {
			$id = 'SECURITY_DASHBOARD';
		}

		$data = array(
			'rest_base'        => untrailingslashit( get_rest_url( null, '/' ) ),
			'home_url'         => Helper::get_site_url(),
			'update_timestamp' => get_option( 'sg_security_update_timestamp', 0 ),
			'localeSlug'       => join( '-', explode( '_', \get_user_locale() ) ),
			'locale'           => I18n::get_i18n_data_json(),
			'wp_nonce'         => wp_create_nonce( 'wp_rest' ),
			'log_page_url'     => admin_url( 'admin.php?page=activity-log' ),
		);

		echo '<script>window.onload = () => {SGSecurity.init({page: SGSecurity.PAGE.' . $id . ',config:' . json_encode( $data ) . '})};</script>';
		echo '<style>.toplevel_page_sg-security.menu-top .wp-menu-image img { width:20px; } </style>';
	}

	/**
	 * Display the admin page.
	 *
	 * @since 1.0.0
	 */
	public function render() {
		echo '<div id="sg-security-container"></div>';
	}

	/**
	 * Reorder the submenu pages.
	 *
	 * @since  1.0.0
	 *
	 * @param   array $menu_order The WP menu order.
	 */
	public function reorder_submenu_pages( $menu_order ) {
		// Load the global submenu.
		global $submenu;
		if ( empty( $submenu['sg-security'] ) ) {
			return;
		}

		$submenu['sg-security'][0][0] = __( 'Dashboard', 'sg-security' );
	}
}
