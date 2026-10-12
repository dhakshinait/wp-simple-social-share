<?php
/**
 * Classic widget for Simple Social Share.
 *
 * @package WP_Simple_Social_Share
 */

defined( 'ABSPATH' ) || exit;

/**
 * Widget that outputs the share button row.
 */
class WPSSS_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'wpsss_widget',
			__( 'Simple Social Share', 'wp-simple-social-share' ),
			array(
				'description'                 => __( 'Social share buttons (Facebook, X, LinkedIn, WhatsApp, and more).', 'wp-simple-social-share' ),
				'classname'                   => 'wpsss-widget',
				'customize_selective_refresh' => true,
			)
		);
	}

	/**
	 * Front-end display.
	 *
	 * @param array<string, mixed> $args     Widget arguments.
	 * @param array<string, mixed> $instance Saved values.
	 */
	public function widget( $args, $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : '';
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		WPSSS_Plugin::enqueue_assets();

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( '' !== $title ) {
			echo $args['before_title'] . esc_html( $title ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		// Renderer returns escaped HTML aside from trusted inline SVGs.
		echo WPSSS_Renderer::render(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Back-end widget form.
	 *
	 * @param array<string, mixed> $instance Current settings.
	 */
	public function form( $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : '';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<?php esc_html_e( 'Title:', 'wp-simple-social-share' ); ?>
			</label>
			<input
				class="widefat"
				id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
				type="text"
				value="<?php echo esc_attr( $title ); ?>"
			/>
		</p>
		<p class="description">
			<?php esc_html_e( 'Leave the title empty to show only the share buttons.', 'wp-simple-social-share' ); ?>
		</p>
		<?php
	}

	/**
	 * Sanitize widget form values.
	 *
	 * @param array<string, mixed> $new_instance New values.
	 * @param array<string, mixed> $old_instance Old values.
	 * @return array<string, mixed>
	 */
	public function update( $new_instance, $old_instance ) {
		$instance          = array();
		$instance['title'] = isset( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
		return $instance;
	}
}
