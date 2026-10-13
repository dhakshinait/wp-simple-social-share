=== Simple Social Share ===
Contributors: Dhakshina
Tags: social share, facebook, twitter, linkedin, email
Requires at least: 5.8
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.1.0
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

**Placement**

* Shortcode: `[wp_simple_social_share]`
* Widget: Appearance → Widgets → Simple Social Share

**Customization**

Under Settings → Simple Social Share you can change:

* Background color
* Icon color
* Border radius (px)
* Icon padding (px)

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

== Changelog ==

= 1.1.0 =
* Added WhatsApp, Telegram, Reddit, Pinterest, and Copy link.
* WhatsApp enabled by default; Telegram, Reddit, Pinterest, and Copy link available in settings.

= 1.0.0 =
* Initial release: Facebook, X, LinkedIn, Email; shortcode + widget; appearance settings.
