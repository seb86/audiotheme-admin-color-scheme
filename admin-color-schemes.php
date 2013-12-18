<?php
/**
 * Plugin Name: AudioTheme Admin Color Schemes
 * Plugin URI: https://github.com/AudioTheme/audiotheme-admin-color-scheme
 * Description: Admin color schemes based on the AudioTheme branding.
 * Version: 1.0.0
 * Author: AudioTheme
 * Author URI: http://audiotheme.com/
 * Text Domain: admin_schemes
 * Domain Path: /languages
 */

class Audiotheme_Color_Schemes {

	/**
	 * List of colors registered in this plugin.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var array $colors List of colors registered in this plugin.
	 *                    Needed for registering colors-fresh dependency.
	 */
	private $colors = array( 'audiotheme' );

	function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'load_default_css' ) );
		add_action( 'admin_init' , array( $this, 'add_colors' ) );
	}

	/**
	 * Register color schemes.
	 */
	function add_colors() {
		$suffix = is_rtl() ? '-rtl' : '';

		wp_admin_css_color(
			'audiotheme',
			__( 'AudioTheme', 'admin_schemes' ),
			plugins_url( "audiotheme/colors$suffix.css", __FILE__ ),
			array( '#233140', '#2c3e50', '#e74c3c', '#27ae60' ),
			array( 'base' => '#bdc3c7', 'focus' => '#fff', 'current' => '#fff' )
		);
	}

	/**
	 * Make sure core's default `colors.css` gets enqueued, since we can't
	 * @import it from a plugin stylesheet. Also force-load the default colors
	 * on the profile screens, so the JS preview isn't broken-looking.
	 */
	function load_default_css() {

		global $wp_styles, $_wp_admin_css_colors;

		$color_scheme = get_user_option( 'admin_color' );

		$scheme_screens = apply_filters( 'acs_picker_allowed_pages', array( 'profile', 'profile-network' ) );
		if ( in_array( $color_scheme, $this->colors ) || in_array( get_current_screen()->base, $scheme_screens ) ){
			$wp_styles->registered[ 'colors' ]->deps[] = 'colors-fresh';
		}

	}
}

$GLOBALS['atacs_colors'] = new Audiotheme_Color_Schemes;
