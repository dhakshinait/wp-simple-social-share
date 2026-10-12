<?php
/**
 * Admin settings for colors and spacing.
 *
 * @package WP_Simple_Social_Share
 */

defined( 'ABSPATH' ) || exit;

/**
 * Settings page under Settings → Simple Social Share.
 */
class WPSSS_Settings {

	/**
	 * Option group / page slug.
	 *
	 * @var string
	 */
	const OPTION_KEY = 'wpsss_options';

	/**
	 * Register settings hooks.
	 */
	public function register() {
		add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
	}

	/**
	 * Add Settings submenu page.
	 */
	public function add_menu_page() {
		add_options_page(
			__( 'Simple Social Share', 'wp-simple-social-share' ),
			__( 'Simple Social Share', 'wp-simple-social-share' ),
			'manage_options',
			'wpsss-settings',
			array( $this, 'render_page' )
		);
	}

	/**
	 * Register option and fields.
	 */
	public function register_settings() {
		register_setting(
			'wpsss_settings_group',
			self::OPTION_KEY,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'sanitize_options' ),
				'default'           => wpsss_default_options(),
			)
		);

		add_settings_section(
			'wpsss_networks',
			__( 'Networks', 'wp-simple-social-share' ),
			array( $this, 'render_networks_intro' ),
			'wpsss-settings'
		);

		add_settings_field(
			'networks',
			__( 'Show icons', 'wp-simple-social-share' ),
			array( $this, 'render_networks_field' ),
			'wpsss-settings',
			'wpsss_networks'
		);

		add_settings_section(
			'wpsss_appearance',
			__( 'Appearance', 'wp-simple-social-share' ),
			array( $this, 'render_section_intro' ),
			'wpsss-settings'
		);

		add_settings_field(
			'bg_color',
			__( 'Background color', 'wp-simple-social-share' ),
			array( $this, 'render_color_field' ),
			'wpsss-settings',
			'wpsss_appearance',
			array( 'key' => 'bg_color' )
		);

		add_settings_field(
			'bg_color_hover',
			__( 'Background color (hover)', 'wp-simple-social-share' ),
			array( $this, 'render_color_field' ),
			'wpsss-settings',
			'wpsss_appearance',
			array( 'key' => 'bg_color_hover' )
		);

		add_settings_field(
			'icon_color',
			__( 'Icon color', 'wp-simple-social-share' ),
			array( $this, 'render_color_field' ),
			'wpsss-settings',
			'wpsss_appearance',
			array( 'key' => 'icon_color' )
		);

		add_settings_field(
			'icon_color_hover',
			__( 'Icon color (hover)', 'wp-simple-social-share' ),
			array( $this, 'render_color_field' ),
			'wpsss-settings',
			'wpsss_appearance',
			array( 'key' => 'icon_color_hover' )
		);

		add_settings_field(
			'border_radius',
			__( 'Border radius (px)', 'wp-simple-social-share' ),
			array( $this, 'render_number_field' ),
			'wpsss-settings',
			'wpsss_appearance',
			array(
				'key' => 'border_radius',
				'min' => 0,
				'max' => 50,
			)
		);

		add_settings_field(
			'icon_padding',
			__( 'Icon padding (px)', 'wp-simple-social-share' ),
			array( $this, 'render_number_field' ),
			'wpsss-settings',
			'wpsss_appearance',
			array(
				'key' => 'icon_padding',
				'min' => 0,
				'max' => 40,
			)
		);

		add_settings_field(
			'icon_width',
			__( 'Icon width (px)', 'wp-simple-social-share' ),
			array( $this, 'render_number_field' ),
			'wpsss-settings',
			'wpsss_appearance',
			array(
				'key' => 'icon_width',
				'min' => 16,
				'max' => 128,
			)
		);

		add_settings_field(
			'icon_height',
			__( 'Icon height (px)', 'wp-simple-social-share' ),
			array( $this, 'render_number_field' ),
			'wpsss-settings',
			'wpsss_appearance',
			array(
				'key' => 'icon_height',
				'min' => 16,
				'max' => 128,
			)
		);
	}

	/**
	 * Sanitize saved options.
	 *
	 * @param mixed $input Raw input.
	 * @return array<string, mixed>
	 */
	public function sanitize_options( $input ) {
		$defaults = wpsss_default_options();
		$output   = $defaults;

		if ( ! is_array( $input ) ) {
			return $output;
		}

		$output['networks'] = array();
		foreach ( WPSSS_Networks::get_network_slugs() as $slug ) {
			$output['networks'][ $slug ] = ! empty( $input['networks'][ $slug ] );
		}

		$color_keys = array( 'bg_color', 'bg_color_hover', 'icon_color', 'icon_color_hover' );
		foreach ( $color_keys as $key ) {
			if ( isset( $input[ $key ] ) ) {
				$color = sanitize_hex_color( $input[ $key ] );
				if ( $color ) {
					$output[ $key ] = $color;
				}
			}
		}

		if ( isset( $input['border_radius'] ) ) {
			$output['border_radius'] = min( 50, absint( $input['border_radius'] ) );
		}

		if ( isset( $input['icon_padding'] ) ) {
			$output['icon_padding'] = min( 40, absint( $input['icon_padding'] ) );
		}

		if ( isset( $input['icon_width'] ) ) {
			$output['icon_width'] = min( 128, max( 16, absint( $input['icon_width'] ) ) );
		}

		if ( isset( $input['icon_height'] ) ) {
			$output['icon_height'] = min( 128, max( 16, absint( $input['icon_height'] ) ) );
		}

		return $output;
	}

	/**
	 * Enqueue color picker on the settings page.
	 *
	 * @param string $hook_suffix Current admin page hook.
	 */
	public function enqueue_admin_assets( $hook_suffix ) {
		if ( 'settings_page_wpsss-settings' !== $hook_suffix ) {
			return;
		}

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );

		wp_enqueue_style(
			'wpsss-admin',
			WPSSS_PLUGIN_URL . 'admin/css/wpsss-admin.css',
			array( 'wp-color-picker' ),
			WPSSS_VERSION
		);

		wp_enqueue_style(
			'wpsss-public',
			WPSSS_PLUGIN_URL . 'public/css/wpsss-public.css',
			array(),
			WPSSS_VERSION
		);

		wp_add_inline_style( 'wpsss-public', '.wpsss-share{' . wpsss_get_css_variables() . '}' );

		$options = wpsss_get_options();
		if ( ! empty( $options['networks']['copy'] ) ) {
			wp_enqueue_script(
				'wpsss-public',
				WPSSS_PLUGIN_URL . 'public/js/wpsss-public.js',
				array(),
				WPSSS_VERSION,
				true
			);
		}

		wp_add_inline_script(
			'wp-color-picker',
			"jQuery(function($){ $('.wpsss-color-field').wpColorPicker(); });"
		);
	}

	/**
	 * Networks section description.
	 */
	public function render_networks_intro() {
		echo '<p>' . esc_html__( 'Choose which share icons to display.', 'wp-simple-social-share' ) . '</p>';
	}

	/**
	 * Appearance section description.
	 */
	public function render_section_intro() {
		echo '<p>' . esc_html__( 'Customize how the share buttons look. Place them with the [wp_simple_social_share] shortcode or the Simple Social Share widget.', 'wp-simple-social-share' ) . '</p>';
	}

	/**
	 * Network enable/disable checkboxes.
	 */
	public function render_networks_field() {
		$options = wpsss_get_options();
		$labels  = WPSSS_Networks::get_admin_labels();
		$enabled = isset( $options['networks'] ) && is_array( $options['networks'] )
			? $options['networks']
			: array();

		echo '<fieldset class="wpsss-networks-field">';
		foreach ( WPSSS_Networks::get_network_slugs() as $slug ) {
			$id      = 'wpsss_network_' . $slug;
			$checked = ! empty( $enabled[ $slug ] );
			$label   = isset( $labels[ $slug ] ) ? $labels[ $slug ] : $slug;

			printf(
				'<label for="%1$s" style="display:block;margin:0 0 6px;"><input type="checkbox" id="%1$s" name="%2$s[networks][%3$s]" value="1" %4$s /> %5$s</label>',
				esc_attr( $id ),
				esc_attr( self::OPTION_KEY ),
				esc_attr( $slug ),
				checked( $checked, true, false ),
				esc_html( $label )
			);
		}
		echo '</fieldset>';
	}

	/**
	 * Color picker field.
	 *
	 * @param array<string, string> $args Field args.
	 */
	public function render_color_field( $args ) {
		$options  = wpsss_get_options();
		$defaults = wpsss_default_options();
		$key      = $args['key'];
		$value    = isset( $options[ $key ] ) ? $options[ $key ] : '';
		$default  = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';

		printf(
			'<input type="text" class="wpsss-color-field" name="%1$s[%2$s]" value="%3$s" data-default-color="%4$s" />',
			esc_attr( self::OPTION_KEY ),
			esc_attr( $key ),
			esc_attr( $value ),
			esc_attr( $default )
		);
	}

	/**
	 * Number field.
	 *
	 * @param array<string, mixed> $args Field args.
	 */
	public function render_number_field( $args ) {
		$options = wpsss_get_options();
		$key     = $args['key'];
		$value   = isset( $options[ $key ] ) ? (int) $options[ $key ] : 0;
		$min     = isset( $args['min'] ) ? (int) $args['min'] : 0;
		$max     = isset( $args['max'] ) ? (int) $args['max'] : 100;

		printf(
			'<input type="number" class="small-text" name="%1$s[%2$s]" value="%3$d" min="%4$d" max="%5$d" step="1" />',
			esc_attr( self::OPTION_KEY ),
			esc_attr( $key ),
			$value,
			$min,
			$max
		);
	}

	/**
	 * Render the settings page markup.
	 */
	public function render_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<div class="wrap wpsss-settings-wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

			<div class="wpsss-preview-box">
				<h2><?php esc_html_e( 'Preview', 'wp-simple-social-share' ); ?></h2>
				<p class="description"><?php esc_html_e( 'Sample buttons using your saved colors and spacing. Save changes to refresh.', 'wp-simple-social-share' ); ?></p>
				<?php
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Renderer escapes URLs/attrs; SVGs are trusted.
				echo WPSSS_Renderer::render(
					array(
						'url'   => home_url( '/' ),
						'title' => get_bloginfo( 'name' ),
					)
				);
				?>
			</div>

			<form action="options.php" method="post">
				<?php
				settings_fields( 'wpsss_settings_group' );
				do_settings_sections( 'wpsss-settings' );
				submit_button();
				?>
			</form>

			<hr />
			<h2><?php esc_html_e( 'How to display', 'wp-simple-social-share' ); ?></h2>
			<ul class="wpsss-usage-list">
				<li><?php esc_html_e( 'Shortcode:', 'wp-simple-social-share' ); ?> <code>[wp_simple_social_share]</code></li>
				<li><?php esc_html_e( 'Widget: Appearance → Widgets → Simple Social Share', 'wp-simple-social-share' ); ?></li>
			</ul>
		</div>
		<?php
	}
}
