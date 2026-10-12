<?php
/**
 * Uninstall Simple Social Share.
 *
 * Fired when the plugin is deleted via the WordPress admin.
 *
 * @package WP_Simple_Social_Share
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

delete_option( 'wpsss_options' );
