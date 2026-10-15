<?php
/**
 * Builds share button HTML.
 *
 * @package WP_Simple_Social_Share
 */

defined( 'ABSPATH' ) || exit;

/**
 * Renders the share button row.
 */
class WPSSS_Renderer {

	/**
	 * Render share buttons.
	 *
	 * @param array<string, mixed> $args {
	 *     Optional. Render arguments.
	 *
	 *     @type string               $url     Override share URL.
	 *     @type string               $title   Override share title.
	 *     @type array<string,mixed>  $options Optional options override (merged with saved settings).
	 * }
	 * @return string
	 */
	public static function render( $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'url'     => '',
				'title'   => '',
				'options' => null,
			)
		);

		$options = wpsss_get_options();
		if ( is_array( $args['options'] ) ) {
			$options = wpsss_apply_option_overrides( $options, $args['options'] );
		}

		$btn_w        = max( 1, absint( $options['icon_width'] ) );
		$btn_h        = max( 1, absint( $options['icon_height'] ) );
		$pad          = absint( $options['icon_padding'] );
		$border_width = absint( $options['border_width'] );
		$svg_w        = max( 1, $btn_w - ( 2 * $pad ) - ( 2 * $border_width ) );
		$svg_h        = max( 1, $btn_h - ( 2 * $pad ) - ( 2 * $border_width ) );

		$context = WPSSS_Networks::resolve_share_context( $args['url'], $args['title'] );
		$labels  = WPSSS_Networks::get_labels();
		$enabled = WPSSS_Networks::get_enabled_network_slugs( $options );

		if ( empty( $enabled ) ) {
			return '';
		}

		$layout            = wpsss_normalize_layout( isset( $options['layout'] ) ? $options['layout'] : 'horizontal' );
		$shape             = wpsss_normalize_icon_shape( isset( $options['icon_shape'] ) ? $options['icon_shape'] : 'square' );
		$mobile_horizontal = ! empty( $options['mobile_horizontal'] );
		$breakpoint        = isset( $options['mobile_breakpoint'] ) ? absint( $options['mobile_breakpoint'] ) : 768;
		$breakpoint        = min( 1600, max( 320, $breakpoint ) );

		$classes = array(
			'wpsss-share',
			'wpsss-layout-' . $layout,
			'wpsss-shape-' . $shape,
		);
		if ( 'vertical' === $layout && $mobile_horizontal ) {
			$classes[] = 'wpsss-mobile-horizontal';
		}

		$instance_id = function_exists( 'wp_unique_id' )
			? wp_unique_id( 'wpsss-share-' )
			: 'wpsss-share-' . uniqid();

		$html = '';

		// Per-instance breakpoint so shortcode overrides work correctly.
		if ( 'vertical' === $layout && $mobile_horizontal ) {
			$html .= sprintf(
				'<style id="%1$s-css">@media (max-width:%2$dpx){#%1$s.wpsss-layout-vertical.wpsss-mobile-horizontal{flex-direction:row;flex-wrap:wrap;align-items:center;}}</style>',
				esc_attr( $instance_id ),
				$breakpoint
			);
		}

		// Inline CSS vars so size is reserved even before the stylesheet loads.
		$html .= sprintf(
			'<div id="%1$s" class="%2$s" role="list" style="%3$s">',
			esc_attr( $instance_id ),
			esc_attr( implode( ' ', $classes ) ),
			esc_attr( wpsss_get_css_variables( $options ) )
		);

		foreach ( $enabled as $network ) {
			$share_url = WPSSS_Networks::get_share_url( $network, $context['url'], $context['title'], $options );
			if ( '' === $share_url ) {
				continue;
			}

			$label = isset( $labels[ $network ] ) ? $labels[ $network ] : $network;
			$svg   = WPSSS_Networks::get_svg( $network, $svg_w, $svg_h );

			if ( 'copy' === $network ) {
				$copy_url = esc_url( $share_url, array( 'http', 'https' ) );
				if ( '' === $copy_url ) {
					continue;
				}

				$html .= sprintf(
					'<a class="wpsss-btn wpsss-copy" href="%1$s" data-wpsss-copy="%1$s" data-wpsss-copied-label="%2$s" rel="nofollow" aria-label="%3$s" title="%3$s" role="listitem" style="width:%4$dpx;height:%5$dpx;box-sizing:border-box;"><span class="wpsss-icon">%6$s</span></a>',
					$copy_url,
					esc_attr__( 'Copied!', 'wp-simple-social-share' ),
					esc_attr( $label ),
					$btn_w,
					$btn_h,
					$svg
				);
				continue;
			}

			$is_mail = ( 'email' === $network );

			$html .= sprintf(
				'<a class="wpsss-btn wpsss-%1$s" href="%2$s"%3$s rel="%4$s" aria-label="%5$s" title="%5$s" role="listitem" style="width:%6$dpx;height:%7$dpx;box-sizing:border-box;"><span class="wpsss-icon">%8$s</span></a>',
				esc_attr( $network ),
				esc_url( $share_url, array( 'http', 'https', 'mailto' ) ),
				$is_mail ? '' : ' target="_blank"',
				$is_mail ? 'nofollow' : 'nofollow noopener',
				esc_attr( $label ),
				$btn_w,
				$btn_h,
				$svg
			);
		}

		$html .= '</div>';

		return $html;
	}
}
