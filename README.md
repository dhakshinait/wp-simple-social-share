# Simple Social Share

Minimal WordPress plugin that displays social share buttons (Facebook, X, LinkedIn, WhatsApp, Telegram, Reddit, Pinterest, Email, Copy link). Styled like dark navy squares with white icons. Place via shortcode or widget only — no automatic content injection.

## Installation

1. Copy this folder into `wp-content/plugins/wp-simple-social-share`
2. Activate **Simple Social Share** in the WordPress admin
3. Place buttons with the shortcode or widget
4. Optionally customize appearance under **Settings → Simple Social Share**

## Usage

### Shortcode

```
[wp_simple_social_share]
```

Optional attributes:

```
[wp_simple_social_share url="https://example.com/post" title="Post title"]
```

On singular posts, the current post URL and title are used when attributes are omitted. Outside that context, the site home URL and blog name are used.

### Widget

**Appearance → Widgets → Simple Social Share**

Add it to a sidebar (for example under a table of contents) to match a left-rail layout. The widget title field is optional.

## Settings

| Option | Default |
|--------|---------|
| Networks | Facebook, X, LinkedIn, WhatsApp, Email on by default; Telegram, Reddit, Pinterest, Copy link off |
| Background color | `#1a2744` |
| Background color (hover) | `#2a3a5c` |
| Icon color | `#ffffff` |
| Icon color (hover) | `#ffffff` |
| Border radius | `4` px |
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
