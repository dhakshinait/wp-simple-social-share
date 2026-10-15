=== Simple Social Share ===
Contributors: Dhakshina
Tags: social share, facebook, twitter, linkedin, email
Requires at least: 5.8
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.2.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Minimal social share buttons. Place them with a shortcode or widget.

== Description ==

Simple Social Share adds a compact row of share buttons styled like dark navy squares with white icons. There is no auto-insert into post content — you choose where they appear.

**Networks**

* Facebook
* X (Twitter)
* LinkedIn
* WhatsApp
* Telegram
* Reddit
* Pinterest
* Email
* Copy link
* RSS Feed (with optional custom feed URL)

**Placement**

* Shortcode: `[wp_simple_social_share]`
* Widget: Appearance → Widgets → Simple Social Share

**Customization**

Under Settings → Simple Social Share you can change:

* Horizontal or vertical icon layout
* Square or circle icon shape
* Background and icon colors (including hover)
* Border width, color, hover color, and radius
* Box shadow (X, Y, blur, spread, color with hex/rgb/rgba)
* Icon size and padding

The same appearance options can also be set per shortcode instance (see FAQ).

== Installation ==

1. Upload the `wp-simple-social-share` folder to `/wp-content/plugins/`
2. Activate the plugin through the Plugins screen
3. Add the shortcode or widget where you want the buttons (for example, a blog sidebar)
4. Optionally adjust colors under Settings → Simple Social Share

== Frequently Asked Questions ==

= Do the buttons appear automatically on posts? =

No. Use the shortcode or the widget so you can place them in a sidebar or anywhere else.

= Can I override the shared URL? =

Yes. Optional shortcode attributes: `[wp_simple_social_share url="https://example.com" title="My title"]`

= Can I override colors, layout, or shape in the shortcode? =

Yes. Pass appearance attributes on the shortcode. Omitted attributes use the settings page defaults.

Example:

`[wp_simple_social_share layout="vertical" shape="circle" bg_color="#1a2744" bg_color_hover="#2a3a5c" icon_color="#ffffff" icon_color_hover="#eeeeee"]`

Supported attributes:

* `url`, `title`, `feed_url`
* `layout` (`horizontal` or `vertical`)
* `shape` or `icon_shape` (`square` or `circle`)
* `bg_color`, `bg_color_hover`
* `icon_color`, `icon_color_hover`
* `border_width`, `border_color`, `border_color_hover`, `border_radius`
* `box_shadow_x`, `box_shadow_y`, `box_shadow_blur`, `box_shadow_spread`, `box_shadow_color`
* `icon_padding`, `icon_width`, `icon_height`

Most colors use hex (for example `#1a2744`). `box_shadow_color` also accepts `rgb()` / `rgba()` (for example `rgba(0,0,0,0.25)`).

== Changelog ==

= 1.2.0 =
* Added horizontal/vertical icon layout option.
* Added square/circle icon shape option.
* Added border width and border color settings.
* Added box shadow settings (X, Y, blur, spread, color).
* Added shortcode attributes to override layout, shape, colors, border, shadow, and size.

= 1.1.0 =
* Added WhatsApp, Telegram, Reddit, Pinterest, and Copy link.
* WhatsApp enabled by default; Telegram, Reddit, Pinterest, and Copy link available in settings.

= 1.0.0 =
* Initial release: Facebook, X, LinkedIn, Email; shortcode + widget; appearance settings.
