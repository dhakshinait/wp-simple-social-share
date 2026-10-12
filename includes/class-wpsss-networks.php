<?php
/**
 * Share URL builders and inline SVG icons.
 *
 * @package WP_Simple_Social_Share
 */

defined( 'ABSPATH' ) || exit;

/**
 * Network definitions for supported share targets.
 */
class WPSSS_Networks {

	/**
	 * All network slugs in display order.
	 *
	 * @return string[]
	 */
	public static function get_network_slugs() {
		return array(
			'facebook',
			'x',
			'linkedin',
			'whatsapp',
			'telegram',
			'reddit',
			'pinterest',
			'email',
			'copy',
		);
	}

	/**
	 * Network slugs enabled in settings.
	 *
	 * @param array<string, mixed>|null $options Optional options; defaults to saved.
	 * @return string[]
	 */
	public static function get_enabled_network_slugs( $options = null ) {
		if ( null === $options ) {
			$options = wpsss_get_options();
		}

		$networks = isset( $options['networks'] ) && is_array( $options['networks'] )
			? $options['networks']
			: array();

		$enabled = array();
		foreach ( self::get_network_slugs() as $slug ) {
			if ( ! empty( $networks[ $slug ] ) ) {
				$enabled[] = $slug;
			}
		}

		return $enabled;
	}

	/**
	 * Short admin labels for settings checkboxes.
	 *
	 * @return array<string, string>
	 */
	public static function get_admin_labels() {
		return array(
			'facebook'  => __( 'Facebook', 'wp-simple-social-share' ),
			'x'         => __( 'X (Twitter)', 'wp-simple-social-share' ),
			'linkedin'  => __( 'LinkedIn', 'wp-simple-social-share' ),
			'whatsapp'  => __( 'WhatsApp', 'wp-simple-social-share' ),
			'telegram'  => __( 'Telegram', 'wp-simple-social-share' ),
			'reddit'    => __( 'Reddit', 'wp-simple-social-share' ),
			'pinterest' => __( 'Pinterest', 'wp-simple-social-share' ),
			'email'     => __( 'Email', 'wp-simple-social-share' ),
			'copy'      => __( 'Copy link', 'wp-simple-social-share' ),
		);
	}

	/**
	 * Human-readable labels for aria/title attributes.
	 *
	 * @return array<string, string>
	 */
	public static function get_labels() {
		return array(
			'facebook'  => __( 'Share on Facebook', 'wp-simple-social-share' ),
			'x'         => __( 'Share on X', 'wp-simple-social-share' ),
			'linkedin'  => __( 'Share on LinkedIn', 'wp-simple-social-share' ),
			'whatsapp'  => __( 'Share on WhatsApp', 'wp-simple-social-share' ),
			'telegram'  => __( 'Share on Telegram', 'wp-simple-social-share' ),
			'reddit'    => __( 'Share on Reddit', 'wp-simple-social-share' ),
			'pinterest' => __( 'Share on Pinterest', 'wp-simple-social-share' ),
			'email'     => __( 'Share by Email', 'wp-simple-social-share' ),
			'copy'      => __( 'Copy link', 'wp-simple-social-share' ),
		);
	}

	/**
	 * Resolve the post URL and title for sharing.
	 *
	 * @param string $url   Optional override URL.
	 * @param string $title Optional override title.
	 * @return array{url: string, title: string}
	 */
	public static function resolve_share_context( $url = '', $title = '' ) {
		$url   = is_string( $url ) ? trim( $url ) : '';
		$title = is_string( $title ) ? trim( $title ) : '';

		if ( '' !== $url ) {
			$url = esc_url_raw( $url, array( 'http', 'https' ) );
		}
		if ( '' !== $title ) {
			$title = sanitize_text_field( $title );
		}

		if ( '' === $url || '' === $title ) {
			if ( in_the_loop() ) {
				if ( '' === $url ) {
					$url = (string) get_permalink();
				}
				if ( '' === $title ) {
					$title = sanitize_text_field( (string) get_the_title() );
				}
			} elseif ( is_singular() ) {
				$post_id = get_queried_object_id();
				if ( $post_id ) {
					if ( '' === $url ) {
						$url = (string) get_permalink( $post_id );
					}
					if ( '' === $title ) {
						$title = sanitize_text_field( (string) get_the_title( $post_id ) );
					}
				}
			}
		}

		if ( '' === $url ) {
			$url = home_url( '/' );
		}
		if ( '' === $title ) {
			$title = sanitize_text_field( (string) get_bloginfo( 'name' ) );
		}

		// Final guard: only allow http/https share targets.
		$url = esc_url_raw( $url, array( 'http', 'https' ) );
		if ( '' === $url ) {
			$url = home_url( '/' );
		}

		return array(
			'url'   => $url,
			'title' => $title,
		);
	}

	/**
	 * Build the share URL for a network.
	 *
	 * @param string $network Network slug.
	 * @param string $url     Page URL to share.
	 * @param string $title   Page title to share.
	 * @return string
	 */
	public static function get_share_url( $network, $url, $title ) {
		$encoded_url   = rawurlencode( $url );
		$encoded_title = rawurlencode( $title );
		$whatsapp_text = rawurlencode( $title . ' ' . $url );

		switch ( $network ) {
			case 'facebook':
				return 'https://www.facebook.com/sharer/sharer.php?u=' . $encoded_url;

			case 'x':
				return 'https://twitter.com/intent/tweet?text=' . $encoded_title . '&url=' . $encoded_url;

			case 'linkedin':
				return 'https://www.linkedin.com/sharing/share-offsite/?url=' . $encoded_url;

			case 'whatsapp':
				return 'https://api.whatsapp.com/send?text=' . $whatsapp_text;

			case 'telegram':
				return 'https://t.me/share/url?url=' . $encoded_url . '&text=' . $encoded_title;

			case 'reddit':
				return 'https://www.reddit.com/submit?url=' . $encoded_url . '&title=' . $encoded_title;

			case 'pinterest':
				return 'https://www.pinterest.com/pin/create/button/?url=' . $encoded_url . '&description=' . $encoded_title;

			case 'email':
				return 'mailto:?subject=' . $encoded_title . '&body=' . $encoded_url;

			case 'copy':
				return $url;

			default:
				return '';
		}
	}

	/**
	 * Inline SVG markup for a network icon (trusted plugin source).
	 *
	 * Explicit pixel width/height prevent a full-height flash before CSS loads.
	 *
	 * @param string $network Network slug.
	 * @param int    $width   SVG width in pixels.
	 * @param int    $height  SVG height in pixels.
	 * @return string
	 */
	public static function get_svg( $network, $width = 24, $height = 24 ) {
		$width  = max( 1, absint( $width ) );
		$height = max( 1, absint( $height ) );
		$attrs  = sprintf(
			'xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="%1$d" height="%2$d" focusable="false" aria-hidden="true"',
			$width,
			$height
		);

		$paths = array(
			'facebook'  => 'M14 13.5h2.5l1-4H14v-2c0-1.03 0-2 2-2h1.5V2.14C17.174 2.097 15.943 2 14.643 2 11.928 2 10 3.657 10 6.7v2.8H7v4h3V22h4v-8.5z',
			'x'         => 'M18.244 2H21.5l-7.5 8.57L22.5 22h-6.57l-5.14-6.71L5.07 22H1.8l8.03-9.17L1.5 2h6.73l4.64 6.13L18.244 2zm-1.15 18h1.8L7.03 3.94H5.1L17.094 20z',
			'linkedin'  => 'M6.94 6.5A1.94 1.94 0 1 1 5 4.56 1.94 1.94 0 0 1 6.94 6.5zM5.25 8.75h3.38V20H5.25zM10.75 8.75h3.24v1.54h.05a3.55 3.55 0 0 1 3.2-1.76c3.42 0 4.05 2.25 4.05 5.18V20h-3.38v-5.05c0-1.2 0-2.75-1.68-2.75s-1.93 1.31-1.93 2.66V20h-3.38z',
			'whatsapp'  => 'M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38c1.45.79 3.08 1.21 4.79 1.21 5.46 0 9.91-4.45 9.91-9.91C21.95 6.45 17.5 2 12.04 2zm5.79 14.16c-.24.68-1.4 1.25-1.93 1.33-.49.07-1.12.1-1.81-.11-.42-.13-.96-.28-1.65-.55-2.9-1.25-4.79-4.17-4.94-4.36-.14-.19-1.19-1.58-1.19-3.02 0-1.43.75-2.14 1.02-2.43.26-.29.58-.36.77-.36h.56c.18 0 .42-.07.66.5.24.58.82 2 .89 2.15.07.14.12.31.02.5-.1.19-.14.31-.29.48-.14.17-.3.37-.43.5-.14.14-.29.29-.12.56.17.28.75 1.24 1.61 2.01 1.11.99 2.04 1.3 2.33 1.44.29.14.46.12.63-.07.17-.19.73-.85.93-1.14.19-.29.39-.24.66-.14.26.1 1.68.79 1.97.94.29.14.48.22.55.34.07.12.07.7-.17 1.38z',
			'telegram'  => 'M9.78 14.65l-.35 4.9c.5 0 .72-.21.98-.46l2.35-2.23 4.87 3.57c.89.49 1.52.23 1.76-.83l3.19-15.01h.01c.28-1.32-.48-1.84-1.35-1.52L2.4 9.57c-1.28.5-1.26 1.21-.22 1.53l4.77 1.49L18.1 6.7c.58-.38 1.11-.17.67.21L9.78 14.65z',
			'reddit'    => 'M14.24 12.19c-.41 0-.74.33-.74.74 0 .41.33.74.74.74.41 0 .74-.33.74-.74 0-.41-.33-.74-.74-.74zm-4.48 0c-.41 0-.74.33-.74.74 0 .41.33.74.74.74.41 0 .74-.33.74-.74 0-.41-.33-.74-.74-.74zm8.12-3.19c-.64 0-1.2.34-1.51.84a6.8 6.8 0 0 0-3.56-.99l.68-3.17 2.08.44c0 .02.02.03.03.05.33.6 1.01.96 1.72.84.71-.12 1.23-.69 1.3-1.4.08-.72-.36-1.4-1.05-1.62-.69-.22-1.43.08-1.77.71l-2.29-.49c-.2-.04-.4.07-.46.26l-.86 4.02a6.83 6.83 0 0 0-3.67 1.01c-.31-.5-.87-.84-1.51-.84C6.5 9 5.5 10 5.5 11.24c0 .72.35 1.36.88 1.75-.03.2-.05.4-.05.61 0 2.76 3.21 5 7.17 5s7.17-2.24 7.17-5c0-.2-.02-.4-.05-.59.55-.4.92-1.05.92-1.77C21 10 20 9 18.88 9zM16.5 14.5c0 .55-.45 1-1 1H8.5c-.55 0-1-.45-1-1s.45-1 1-1h7c.55 0 1 .45 1 1z',
			'pinterest' => 'M12 2C6.48 2 2 6.48 2 12c0 4.24 2.65 7.87 6.39 9.32-.09-.79-.17-2.01.04-2.87.18-.78 1.19-4.96 1.19-4.96s-.3-.61-.3-1.5c0-1.41.82-2.46 1.84-2.46.86 0 1.28.65 1.28 1.43 0 .87-.55 2.17-.84 3.38-.24 1.01.5 1.83 1.49 1.83 1.79 0 3.17-1.89 3.17-4.61 0-2.41-1.73-4.1-4.21-4.1-2.87 0-4.55 2.15-4.55 4.37 0 .87.33 1.8.75 2.31.08.1.09.19.07.29l-.28 1.15c-.04.18-.15.22-.34.13-1.28-.6-2.08-2.47-2.08-3.98 0-3.24 2.35-6.22 6.79-6.22 3.56 0 6.34 2.54 6.34 5.93 0 3.54-2.23 6.38-5.33 6.38-1.04 0-2.02-.54-2.35-1.18l-.64 2.44c-.23.89-.86 2.01-1.28 2.69A10 10 0 0 0 12 22c5.52 0 10-4.48 10-10S17.52 2 12 2z',
			'email'     => 'M3.4 20.4 20.85 12.92a.6.6 0 0 0 0-1.09L3.4 4.35a.6.6 0 0 0-.87.66l1.7 6.2a.6.6 0 0 0 .45.42l7.36 1.45-7.36 1.45a.6.6 0 0 0-.45.42l-1.7 6.2a.6.6 0 0 0 .87.66z',
			'copy'      => 'M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z',
		);

		if ( ! isset( $paths[ $network ] ) ) {
			return '';
		}

		return '<svg ' . $attrs . '><path fill="currentColor" d="' . $paths[ $network ] . '"/></svg>';
	}
}
