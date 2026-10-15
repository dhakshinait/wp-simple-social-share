<?php
/**
 * Core plugin wiring: hooks, assets, shortcode, widget.
 *
 * @package WP_Simple_Social_Share
 */

defined( 'ABSPATH' ) || exit;

/**
 * Main plugin class.
 */
class WPSSS_Plugin {

	/**
	 * Whether public CSS was enqueued this request.
	 *
	 * @var bool
	 */
	private static $assets_enqueued = false;

	/**
	 * Whether the copy-link script was enqueued this request.
	 *
	 * @var bool
	 */
	private static $script_enqueued = false;

	/**
	 * Register WordPress hooks.
	 */
	public function run() {
		$settings = new WPSSS_Settings();
		$settings->register();

		add_action( 'wp_enqueue_scripts', array( $this, 'register_public_assets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'maybe_enqueue_public_assets' ) );
		add_shortcode( 'wp_simple_social_share', array( $this, 'render_shortcode' ) );
		add_action( 'widgets_init', array( $this, 'register_widget' ) );
	}

	/**
	 * Register (but do not always print) public assets.
	 */
	public function register_public_assets() {
		wp_register_style(
			'wpsss-public',
			WPSSS_PLUGIN_URL . 'public/css/wpsss-public.css',
			array(),
			WPSSS_VERSION
		);

		wp_register_script(
			'wpsss-public',
			WPSSS_PLUGIN_URL . 'public/js/wpsss-public.js',
			array(),
			WPSSS_VERSION,
			true
		);
	}

	/**
	 * Enqueue early when the widget is active or the shortcode is in the post.
	 */
	public function maybe_enqueue_public_assets() {
		if ( is_active_widget( false, false, 'wpsss_widget', true ) ) {
			self::enqueue_assets();
			return;
		}

		if ( is_singular() ) {
			$post = get_post();
			if ( $post instanceof WP_Post && has_shortcode( $post->post_content, 'wp_simple_social_share' ) ) {
				self::enqueue_assets();
			}
		}
	}

	/**
	 * Enqueue public CSS/JS and inject CSS custom properties from settings.
	 *
	 * @param array<string, mixed>|null $options Options for this render (used for copy script).
	 */
	public static function enqueue_assets( $options = null ) {
		if ( null === $options ) {
			$options = wpsss_get_options();
		}

		if ( ! self::$assets_enqueued ) {
			wp_enqueue_style( 'wpsss-public' );
			wp_add_inline_style( 'wpsss-public', '.wpsss-share{' . wpsss_get_css_variables() . '}' );
			self::$assets_enqueued = true;
		}

		if ( ! self::$script_enqueued && ! empty( $options['networks']['copy'] ) ) {
			wp_enqueue_script( 'wpsss-public' );
			wp_localize_script(
				'wpsss-public',
				'wpsssPublic',
				array(
					'copiedLabel' => __( 'Copied!', 'wp-simple-social-share' ),
				)
			);
			self::$script_enqueued = true;
		}
	}

	/**
	 * Shortcode callback.
	 *
	 * Appearance attributes override Settings → Simple Social Share for that instance.
	 *
	 * @param array<string, string>|string $atts Shortcode attributes.
	 * @return string
	 */
	public function render_shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'url'                  => '',
				'title'                => '',
				'networks'             => '',
				'feed_url'             => '',
				'layout'               => '',
				'mobile_horizontal'    => '',
				'mobile_breakpoint'    => '',
				'shape'                => '',
				'icon_shape'           => '',
				'bg_color'             => '',
				'bg_color_hover'       => '',
				'icon_color'           => '',
				'icon_color_hover'     => '',
				'border_width'         => '',
				'border_color'         => '',
				'border_color_hover'   => '',
				'border_radius'        => '',
				'box_shadow_x'         => '',
				'box_shadow_y'         => '',
				'box_shadow_blur'      => '',
				'box_shadow_spread'    => '',
				'box_shadow_color'     => '',
				'icon_padding'         => '',
				'icon_width'           => '',
				'icon_height'          => '',
			),
			$atts,
			'wp_simple_social_share'
		);

		$url = '';
		if ( '' !== $atts['url'] ) {
			$url = esc_url_raw( $atts['url'], array( 'http', 'https' ) );
		}

		$overrides = $atts;
		unset( $overrides['url'], $overrides['title'] );

		$options = wpsss_apply_option_overrides( wpsss_get_options(), $overrides );
		self::enqueue_assets( $options );

		return WPSSS_Renderer::render(
			array(
				'url'     => $url,
				'title'   => sanitize_text_field( $atts['title'] ),
				'options' => $overrides,
			)
		);
	}

	/**
	 * Register the classic widget.
	 */
	public function register_widget() {
		register_widget( 'WPSSS_Widget' );
	}
}
