<?php
/**
 * Plugin Name:       Simple Social Share
 * Plugin URI:        https://github.com/dhakshinait/wp-simple-social-share/
 * Description:       Minimal social share buttons (Facebook, X, LinkedIn, WhatsApp, Telegram, Reddit, Pinterest, Email, Copy link). Place via shortcode or widget.
 * Version:           1.2.0
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * Author:            Dhakshina
 * Author URI:        https://github.com/dhakshinait/wp-simple-social-share/
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wp-simple-social-share
 */

defined( 'ABSPATH' ) || exit;

define( 'WPSSS_VERSION', '1.2.0' );
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
			'feed'      => false,
		),
		'feed_url'           => '',
		'layout'             => 'horizontal',
		'icon_shape'         => 'square',
		'bg_color'           => '#1a2744',
		'bg_color_hover'     => '#2a3a5c',
		'icon_color'         => '#ffffff',
		'icon_color_hover'   => '#ffffff',
		'border_width'         => 0,
		'border_color'         => '#1a2744',
		'border_color_hover'   => '#2a3a5c',
		'border_radius'        => 4,
		'box_shadow_x'         => 0,
		'box_shadow_y'         => 0,
		'box_shadow_blur'      => 0,
		'box_shadow_spread'    => 0,
		'box_shadow_color'     => 'rgba(0,0,0,0.25)',
		'icon_padding'       => 8,
		'icon_width'         => 40,
		'icon_height'        => 40,
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
 * Normalize layout value.
 *
 * @param mixed $layout Raw layout.
 * @return string horizontal|vertical
 */
function wpsss_normalize_layout( $layout ) {
	return ( 'vertical' === $layout ) ? 'vertical' : 'horizontal';
}

/**
 * Normalize icon shape value.
 *
 * @param mixed $shape Raw shape.
 * @return string square|circle
 */
function wpsss_normalize_icon_shape( $shape ) {
	return ( 'circle' === $shape ) ? 'circle' : 'square';
}

/**
 * Sanitize a CSS color (hex, rgb, or rgba).
 *
 * @param mixed $color Raw color value.
 * @return string Empty string when invalid.
 */
function wpsss_sanitize_css_color( $color ) {
	if ( ! is_string( $color ) ) {
		return '';
	}

	$color = trim( $color );
	if ( '' === $color ) {
		return '';
	}

	$hex = sanitize_hex_color( $color );
	if ( $hex ) {
		return $hex;
	}

	if ( preg_match( '/^rgba?\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*(?:,\s*(0|1|0?\.\d+)\s*)?\)$/i', $color, $matches ) ) {
		$r = min( 255, absint( $matches[1] ) );
		$g = min( 255, absint( $matches[2] ) );
		$b = min( 255, absint( $matches[3] ) );

		if ( isset( $matches[4] ) && '' !== $matches[4] ) {
			$alpha = (float) $matches[4];
			if ( $alpha < 0 ) {
				$alpha = 0;
			}
			if ( $alpha > 1 ) {
				$alpha = 1;
			}
			$alpha_str = rtrim( rtrim( number_format( $alpha, 3, '.', '' ), '0' ), '.' );
			if ( '' === $alpha_str ) {
				$alpha_str = '0';
			}
			return sprintf( 'rgba(%d,%d,%d,%s)', $r, $g, $b, $alpha_str );
		}

		return sprintf( 'rgb(%d,%d,%d)', $r, $g, $b );
	}

	return '';
}

/**
 * Build box-shadow CSS value from options.
 *
 * @param array<string, mixed> $options Plugin options.
 * @return string
 */
function wpsss_get_box_shadow_value( $options ) {
	$x      = isset( $options['box_shadow_x'] ) ? (int) $options['box_shadow_x'] : 0;
	$y      = isset( $options['box_shadow_y'] ) ? (int) $options['box_shadow_y'] : 0;
	$blur   = isset( $options['box_shadow_blur'] ) ? absint( $options['box_shadow_blur'] ) : 0;
	$spread = isset( $options['box_shadow_spread'] ) ? (int) $options['box_shadow_spread'] : 0;
	$color  = isset( $options['box_shadow_color'] ) ? wpsss_sanitize_css_color( $options['box_shadow_color'] ) : '';

	if ( ! $color ) {
		$color = 'rgba(0,0,0,0.25)';
	}

	if ( 0 === $x && 0 === $y && 0 === $blur && 0 === $spread ) {
		return 'none';
	}

	return sprintf( '%dpx %dpx %dpx %dpx %s', $x, $y, $blur, $spread, $color );
}

/**
 * Apply appearance overrides (e.g. from shortcode) onto base options.
 *
 * Empty override values are ignored so settings remain the fallback.
 *
 * @param array<string, mixed> $options   Base options.
 * @param array<string, mixed> $overrides Raw overrides.
 * @return array<string, mixed>
 */
function wpsss_apply_option_overrides( $options, $overrides ) {
	if ( ! is_array( $options ) ) {
		$options = wpsss_get_options();
	}
	if ( ! is_array( $overrides ) ) {
		return $options;
	}

	if ( ! empty( $overrides['layout'] ) ) {
		$options['layout'] = wpsss_normalize_layout( $overrides['layout'] );
	}

	$shape_raw = '';
	if ( ! empty( $overrides['icon_shape'] ) ) {
		$shape_raw = $overrides['icon_shape'];
	} elseif ( ! empty( $overrides['shape'] ) ) {
		$shape_raw = $overrides['shape'];
	}
	if ( '' !== $shape_raw ) {
		$options['icon_shape'] = wpsss_normalize_icon_shape( $shape_raw );
	}

	$hex_color_keys = array(
		'bg_color',
		'bg_color_hover',
		'icon_color',
		'icon_color_hover',
		'border_color',
		'border_color_hover',
	);
	foreach ( $hex_color_keys as $key ) {
		if ( empty( $overrides[ $key ] ) || ! is_string( $overrides[ $key ] ) ) {
			continue;
		}
		$color = sanitize_hex_color( $overrides[ $key ] );
		if ( $color ) {
			$options[ $key ] = $color;
		}
	}

	if ( ! empty( $overrides['box_shadow_color'] ) && is_string( $overrides['box_shadow_color'] ) ) {
		$shadow_color = wpsss_sanitize_css_color( $overrides['box_shadow_color'] );
		if ( $shadow_color ) {
			$options['box_shadow_color'] = $shadow_color;
		}
	}

	if ( isset( $overrides['feed_url'] ) && is_string( $overrides['feed_url'] ) && '' !== trim( $overrides['feed_url'] ) ) {
		$feed_url = esc_url_raw( trim( $overrides['feed_url'] ), array( 'http', 'https' ) );
		if ( $feed_url ) {
			$options['feed_url'] = $feed_url;
		}
	}

	if ( isset( $overrides['border_width'] ) && '' !== $overrides['border_width'] ) {
		$options['border_width'] = min( 20, absint( $overrides['border_width'] ) );
	}
	if ( isset( $overrides['border_radius'] ) && '' !== $overrides['border_radius'] ) {
		$options['border_radius'] = min( 50, absint( $overrides['border_radius'] ) );
	}
	if ( isset( $overrides['icon_padding'] ) && '' !== $overrides['icon_padding'] ) {
		$options['icon_padding'] = min( 40, absint( $overrides['icon_padding'] ) );
	}
	if ( isset( $overrides['icon_width'] ) && '' !== $overrides['icon_width'] ) {
		$options['icon_width'] = min( 128, max( 16, absint( $overrides['icon_width'] ) ) );
	}
	if ( isset( $overrides['icon_height'] ) && '' !== $overrides['icon_height'] ) {
		$options['icon_height'] = min( 128, max( 16, absint( $overrides['icon_height'] ) ) );
	}
	if ( isset( $overrides['box_shadow_x'] ) && '' !== $overrides['box_shadow_x'] ) {
		$options['box_shadow_x'] = max( -50, min( 50, (int) $overrides['box_shadow_x'] ) );
	}
	if ( isset( $overrides['box_shadow_y'] ) && '' !== $overrides['box_shadow_y'] ) {
		$options['box_shadow_y'] = max( -50, min( 50, (int) $overrides['box_shadow_y'] ) );
	}
	if ( isset( $overrides['box_shadow_blur'] ) && '' !== $overrides['box_shadow_blur'] ) {
		$options['box_shadow_blur'] = min( 100, absint( $overrides['box_shadow_blur'] ) );
	}
	if ( isset( $overrides['box_shadow_spread'] ) && '' !== $overrides['box_shadow_spread'] ) {
		$options['box_shadow_spread'] = max( -50, min( 50, (int) $overrides['box_shadow_spread'] ) );
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

	$border_width       = absint( $options['border_width'] );
	$border_color       = sanitize_hex_color( $options['border_color'] ) ? $options['border_color'] : '#1a2744';
	$border_color_hover = sanitize_hex_color( $options['border_color_hover'] ) ? $options['border_color_hover'] : '#2a3a5c';
	$shape              = wpsss_normalize_icon_shape( isset( $options['icon_shape'] ) ? $options['icon_shape'] : 'square' );
	$radius             = ( 'circle' === $shape ) ? '50%' : absint( $options['border_radius'] ) . 'px';

	return sprintf(
		'--wpsss-bg:%1$s;--wpsss-bg-hover:%2$s;--wpsss-icon:%3$s;--wpsss-icon-hover:%4$s;--wpsss-radius:%5$s;--wpsss-padding:%6$dpx;--wpsss-width:%7$dpx;--wpsss-height:%8$dpx;--wpsss-border-width:%9$dpx;--wpsss-border-color:%10$s;--wpsss-border-color-hover:%11$s;--wpsss-shadow:%12$s;',
		sanitize_hex_color( $options['bg_color'] ) ? $options['bg_color'] : '#1a2744',
		sanitize_hex_color( $options['bg_color_hover'] ) ? $options['bg_color_hover'] : '#2a3a5c',
		sanitize_hex_color( $options['icon_color'] ) ? $options['icon_color'] : '#ffffff',
		sanitize_hex_color( $options['icon_color_hover'] ) ? $options['icon_color_hover'] : '#ffffff',
		$radius,
		absint( $options['icon_padding'] ),
		max( 1, absint( $options['icon_width'] ) ),
		max( 1, absint( $options['icon_height'] ) ),
		$border_width,
		$border_color,
		$border_color_hover,
		wpsss_get_box_shadow_value( $options )
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
