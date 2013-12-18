<?php
/**
 * Plugin Name: AudioTheme Admin Color Schemes
 * Plugin URI: http://audiotheme.com/
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
		add_action( 'admin_head', array( $this, 'desaturate_menu_image_icons' ) );
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

	/**
	 * Print CSS in the admin header to apply grayscale filters to
	 *
	 * @link https://gist.github.com/bradyvercher/7947249
	 */
	function desaturate_menu_image_icons() {
		?>
		<style type="text/css">
		#adminmenu li.menu-top .wp-menu-image img {
			filter: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg'><filter id='grayscale'><feColorMatrix type='matrix' values='0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0 0 0 1 0'/></filter></svg>#grayscale"); /* Firefox 10+, Firefox on Android */
			filter: gray; /* IE6-9 */
			-webkit-filter: grayscale(100%); /* Chrome 19+, Safari 6+, Safari 6+ iOS */
			opacity: 1;
		}

		#adminmenu li.wp-has-current-submenu .wp-menu-image img {
			filter: none;
			-webkit-filter: grayscale(0%);
		}
		</style>
		<?php
	}
}
global $acs_colors;
$acs_colors = new Audiotheme_Color_Schemes;

