# Simple Social Share

Minimal WordPress plugin that displays social share buttons (Facebook, X, LinkedIn, WhatsApp, Telegram, Reddit, Pinterest, Email, Copy link). Styled like dark navy squares with white icons. Place via shortcode or widget only — no automatic content injection.

## Installation

1. Copy this folder into `wp-content/plugins/wp-simple-social-share`
2. Activate **Simple Social Share** in the WordPress admin
3. Place buttons with the shortcode or widget
4. Optionally customize appearance under **Settings → Simple Social Share**

## Usage

### Shortcode

Basic usage:

```
[wp_simple_social_share]
```

#### Shortcode appearance overrides (optional)

Pass any of these attributes on the shortcode. Omitted attributes use **Settings → Simple Social Share**.

| Attribute | Values | Notes |
|-----------|--------|--------|
| `url` | `https://…` | Share URL override. Same-site / allowed hosts only. Requires **URL & network overrides** in settings. |
| `title` | text | Share title override. Requires **URL & network overrides**. |
| `networks` | comma-separated slugs | Icons for this shortcode only. Slugs: `facebook`, `x`, `linkedin`, `whatsapp`, `telegram`, `reddit`, `pinterest`, `email`, `copy`, `feed`. Aliases: `twitter` → `x`, `rss` → `feed`. Requires **URL & network overrides**. |
| `feed_url` | `https://…` | RSS feed URL. Allowed hosts only. Requires **URL & network overrides**. |
| `layout` | `horizontal` \| `vertical` | Icon arrangement |
| `mobile_horizontal` | `1` \| `0` \| `true` \| `false` | Switch vertical to horizontal on mobile |
| `mobile_breakpoint` | number (px) | Max width for the mobile switch (default 768) |
| `shape` / `icon_shape` | `square` \| `circle` | Icon shape |
| `bg_color` | `#hex` (e.g. `#1a2744`) | Button background |
| `bg_color_hover` | `#hex` | Background on hover |
| `icon_color` | `#hex` | Icon color |
| `icon_color_hover` | `#hex` | Icon color on hover |
| `border_width` | number (px) | Border thickness |
| `border_color` | `#hex` | Border color |
| `border_color_hover` | `#hex` | Border color on hover |
| `border_radius` | number (px) | Used for square shape only |
| `box_shadow_x` | number (px) | Shadow X offset |
| `box_shadow_y` | number (px) | Shadow Y offset |
| `box_shadow_blur` | number (px) | Shadow blur |
| `box_shadow_spread` | number (px) | Shadow spread |
| `box_shadow_color` | `#hex` / `rgb()` / `rgba()` | Shadow color (e.g. `rgba(0,0,0,0.25)`) |
| `icon_padding` | number (px) | Inner padding |
| `icon_width` | number (px) | Button width |
| `icon_height` | number (px) | Button height |

Show only specific icons on one page:

```
[wp_simple_social_share networks="facebook,whatsapp,email"]
```

Example with all appearance overrides:

```
[wp_simple_social_share
  url="https://example.com/post"
  title="Post title"
  networks="facebook,x,linkedin,whatsapp"
  feed_url="https://example.com/feed"
  layout="vertical"
  mobile_horizontal="1"
  mobile_breakpoint="768"
  shape="circle"
  bg_color="#1a2744"
  bg_color_hover="#2a3a5c"
  icon_color="#ffffff"
  icon_color_hover="#eeeeee"
  border_width="1"
  border_color="#0f1a30"
  border_color_hover="#2a3a5c"
  border_radius="4"
  box_shadow_x="0"
  box_shadow_y="2"
  box_shadow_blur="6"
  box_shadow_spread="0"
  box_shadow_color="rgba(0,0,0,0.25)"
  icon_padding="8"
  icon_width="40"
  icon_height="40"
]
```

On singular posts, the current post URL and title are used when `url` / `title` are omitted. Outside that context, the site home URL and blog name are used.

Shortcode `url` and `feed_url` values are rejected unless their host matches your site (or a host listed under **Additional allowed hosts**). Appearance attributes (layout, colors, size, etc.) are always allowed.

### Widget

**Appearance → Widgets → Simple Social Share**

Add it to a sidebar (for example under a table of contents) to match a left-rail layout. The widget title field is optional.

## Settings

| Option | Default |
|--------|---------|
| Networks | Facebook, X, LinkedIn, WhatsApp, Email on by default; Telegram, Reddit, Pinterest, Copy link, RSS Feed off |
| RSS feed URL | Empty (falls back to the site RSS2 feed) |
| URL & network overrides | On (allow shortcode `url`, `title`, `feed_url`, `networks`) |
| Additional allowed hosts | Empty (site/home host always allowed for shortcode URLs) |
| Icon layout | Horizontal |
| Mobile layout | Switch vertical → horizontal on mobile (on) |
| Mobile breakpoint | `768` px |
| Icon shape | Square (or Circle) |
| Background color | `#1a2744` |
| Background color (hover) | `#2a3a5c` |
| Icon color | `#ffffff` |
| Icon color (hover) | `#ffffff` |
| Border width | `0` px |
| Border color | `#1a2744` |
| Border color (hover) | `#2a3a5c` |
| Border radius | `4` px |
| Box shadow X / Y / blur / spread | `0` |
| Box shadow color | `rgba(0,0,0,0.25)` (hex, rgb, or rgba) |
| Icon padding | `8` px |
| Icon width | `40` px |
| Icon height | `40` px |

Values are applied as CSS custom properties on `.wpsss-share`.

## File structure

```
wp-simple-social-share.php
includes/
  class-wpsss-plugin.php
  class-wpsss-networks.php
  class-wpsss-renderer.php
  class-wpsss-settings.php
  class-wpsss-widget.php
public/css/wpsss-public.css
admin/css/wpsss-admin.css
uninstall.php
```

## Requirements

- WordPress 5.8+
- PHP 7.4+
