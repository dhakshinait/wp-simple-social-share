=== Simple Social Share ===
Contributors: Dhakshina
Tags: social share, facebook, twitter, linkedin, email
Requires at least: 5.8
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.3.0
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
* Option to switch vertical icons to horizontal on mobile (with breakpoint)
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

= Shortcode appearance overrides (optional) =

Pass any of these attributes on the shortcode. Omitted attributes use the settings page defaults.

* `url` — Share URL (`https://…`)
* `title` — Share title (text)
* `networks` — Comma-separated icons for this shortcode only (overrides settings). Slugs: `facebook`, `x`, `linkedin`, `whatsapp`, `telegram`, `reddit`, `pinterest`, `email`, `copy`, `feed`. Aliases: `twitter` → `x`, `rss` → `feed`
* `feed_url` — RSS feed URL when Feed icon is enabled (`https://…`)
* `layout` — Icon arrangement (`horizontal` or `vertical`)
* `mobile_horizontal` — Switch vertical to horizontal on mobile (`1` / `0` / `true` / `false`)
* `mobile_breakpoint` — Max width for the mobile switch in px (default 768)
* `shape` or `icon_shape` — Icon shape (`square` or `circle`)
* `bg_color` — Button background (`#hex`, e.g. `#1a2744`)
* `bg_color_hover` — Background on hover (`#hex`)
* `icon_color` — Icon color (`#hex`)
* `icon_color_hover` — Icon color on hover (`#hex`)
* `border_width` — Border thickness (px)
* `border_color` — Border color (`#hex`)
* `border_color_hover` — Border color on hover (`#hex`)
* `border_radius` — Corner radius in px (square shape only)
* `box_shadow_x` — Shadow X offset (px)
* `box_shadow_y` — Shadow Y offset (px)
* `box_shadow_blur` — Shadow blur (px)
* `box_shadow_spread` — Shadow spread (px)
* `box_shadow_color` — Shadow color (`#hex` / `rgb()` / `rgba()`, e.g. `rgba(0,0,0,0.25)`)
* `icon_padding` — Inner padding (px)
* `icon_width` — Button width (px)
* `icon_height` — Button height (px)

Show only specific icons on one page:

`[wp_simple_social_share networks="facebook,whatsapp,email"]`

Example with all appearance overrides:

`[wp_simple_social_share url="https://example.com/post" title="Post title" networks="facebook,x,linkedin,whatsapp" feed_url="https://example.com/feed" layout="vertical" mobile_horizontal="1" mobile_breakpoint="768" shape="circle" bg_color="#1a2744" bg_color_hover="#2a3a5c" icon_color="#ffffff" icon_color_hover="#eeeeee" border_width="1" border_color="#0f1a30" border_color_hover="#2a3a5c" border_radius="4" box_shadow_x="0" box_shadow_y="2" box_shadow_blur="6" box_shadow_spread="0" box_shadow_color="rgba(0,0,0,0.25)" icon_padding="8" icon_width="40" icon_height="40"]`

== Changelog ==

= 1.3.0 =
* Added option to switch vertical icons to horizontal on mobile.
* Added configurable mobile breakpoint (default 768px).
* Added shortcode attributes `mobile_horizontal` and `mobile_breakpoint`.
* Added shortcode attribute `networks` to show specific icons per shortcode instance.

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
