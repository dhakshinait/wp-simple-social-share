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

You can override appearance (and share target) on each shortcode. Any attribute you omit falls back to **Settings → Simple Social Share**.

```
[wp_simple_social_share
  url="https://example.com/post"
  title="Post title"
  layout="vertical"
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

#### Shortcode attributes

| Attribute | Values | Notes |
|-----------|--------|--------|
| `url` | `https://…` | Share URL override |
| `title` | text | Share title override |
| `feed_url` | `https://…` | RSS feed URL override (when Feed icon is enabled) |
| `layout` | `horizontal`, `vertical` | Icon arrangement |
| `shape` or `icon_shape` | `square`, `circle` | Icon shape |
| `bg_color` | hex (`#1a2744`) | Button background |
| `bg_color_hover` | hex | Background on hover |
| `icon_color` | hex | Icon color |
| `icon_color_hover` | hex | Icon color on hover |
| `border_width` | number (px) | Border thickness |
| `border_color` | hex | Border color |
| `border_color_hover` | hex | Border color on hover |
| `border_radius` | number (px) | Used for square shape only |
| `box_shadow_x` | number (px) | Shadow X offset |
| `box_shadow_y` | number (px) | Shadow Y offset |
| `box_shadow_blur` | number (px) | Shadow blur |
| `box_shadow_spread` | number (px) | Shadow spread |
| `box_shadow_color` | hex / `rgb()` / `rgba()` | Shadow color (e.g. `rgba(0,0,0,0.25)`) |
| `icon_padding` | number (px) | Inner padding |
| `icon_width` | number (px) | Button width |
| `icon_height` | number (px) | Button height |

On singular posts, the current post URL and title are used when `url` / `title` are omitted. Outside that context, the site home URL and blog name are used.

### Widget

**Appearance → Widgets → Simple Social Share**

Add it to a sidebar (for example under a table of contents) to match a left-rail layout. The widget title field is optional.

## Settings

| Option | Default |
|--------|---------|
| Networks | Facebook, X, LinkedIn, WhatsApp, Email on by default; Telegram, Reddit, Pinterest, Copy link, RSS Feed off |
| RSS feed URL | Empty (falls back to the site RSS2 feed) |
| Icon layout | Horizontal |
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
