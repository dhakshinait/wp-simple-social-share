<?php
/**
 * Plugin Name:       Simple Social Share
 * Plugin URI:        https://github.com/dhakshinait/wp-simple-social-share/
 * Description:       Minimal social share buttons (Facebook, X, LinkedIn, WhatsApp, Telegram, Reddit, Pinterest, Email, Copy link). Place via shortcode or widget.
 * Version:           1.1.0
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * Author:            Dhakshina
 * Author URI:        https://github.com/dhakshinait/wp-simple-social-share/
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wp-simple-social-share
 */

defined( 'ABSPATH' ) || exit;

define( 'WPSSS_VERSION', '1.1.0' );
define( 'WPSSS_PLUGIN_FILE', __FILE__ );
define( 'WPSSS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WPSSS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once WPSSS_PLUGIN_DIR . 'includes/class-wpsss-networks.php';
require_once WPSSS_PLUGIN_DIR . 'includes/class-wpsss-renderer.php';
require_once WPSSS_PLUGIN_DIR . 'includes/class-wpsss-settings.php';
require_once WPSSS_PLUGIN_DIR . 'includes/class-wpsss-widget.php';
require_once WPSSS_PLUGIN_DIR . 'includes/class-wpsss-plugin.php';

/**
 * Default plugin options (dark navy squares).
 *
 * @return array<string, mixed>
 */
function wpsss_default_options() {
	return array(
		'networks'         => array(
			'facebook'  => true,
			'x'         => true,
			'linkedin'  => true,
			'whatsapp'  => true,
			'telegram'  => false,
			'reddit'    => false,
			'pinterest' => false,
			'email'     => true,
			'copy'      => false,
		),
		'bg_color'         => '#1a2744',
		'bg_color_hover'   => '#2a3a5c',
		'icon_color'       => '#ffffff',
		'icon_color_hover' => '#ffffff',
		'border_radius'    => 4,
		'icon_padding'     => 8,
		'icon_width'       => 40,
		'icon_height'      => 40,
	);
}

/**
 * Get merged plugin options.
 *
 * @return array<string, mixed>
 */
function wpsss_get_options() {
	$defaults = wpsss_default_options();
	$saved    = get_option( 'wpsss_options', array() );
	if ( ! is_array( $saved ) ) {
		$saved = array();
	}

	$options = wp_parse_args( $saved, $defaults );

	if ( isset( $saved['networks'] ) && is_array( $saved['networks'] ) ) {
		$options['networks'] = wp_parse_args( $saved['networks'], $defaults['networks'] );
	} else {
		$options['networks'] = $defaults['networks'];
	}

	return $options;
}

/**
 * Build CSS custom-property declarations from options.
 *
 * @param array<string, mixed>|null $options Optional options; defaults to saved.
 * @return string
 */
function wpsss_get_css_variables( $options = null ) {
	if ( null === $options ) {
		$options = wpsss_get_options();
	}

	return sprintf(
		'--wpsss-bg:%1$s;--wpsss-bg-hover:%2$s;--wpsss-icon:%3$s;--wpsss-icon-hover:%4$s;--wpsss-radius:%5$dpx;--wpsss-padding:%6$dpx;--wpsss-width:%7$dpx;--wpsss-height:%8$dpx;',
		sanitize_hex_color( $options['bg_color'] ) ? $options['bg_color'] : '#1a2744',
		sanitize_hex_color( $options['bg_color_hover'] ) ? $options['bg_color_hover'] : '#2a3a5c',
		sanitize_hex_color( $options['icon_color'] ) ? $options['icon_color'] : '#ffffff',
		sanitize_hex_color( $options['icon_color_hover'] ) ? $options['icon_color_hover'] : '#ffffff',
		absint( $options['border_radius'] ),
		absint( $options['icon_padding'] ),
		max( 1, absint( $options['icon_width'] ) ),
		max( 1, absint( $options['icon_height'] ) )
	);
}

/**
 * Bootstrap the plugin.
 */
function wpsss_run() {
	$plugin = new WPSSS_Plugin();
	$plugin->run();
}
wpsss_run();
