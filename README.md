<div align="center">

# Icon Manager

Lightweight WordPress plugin to locally download & manage Brand (Simple Icons) and UI (Lucide) SVG icons – with caching, auto-download & optional Media Library import.

<strong>DE:</strong> Lokales Verwalten & Herunterladen von Brand (Social) und UI (Lucide) SVG Icons mit Caching & optionalem Medien-Import.

<p>
<a href="https://github.com/atakan72/iconmanager/blob/main/LICENSE"><img alt="License" src="https://img.shields.io/badge/license-GPL--2.0--or--later-blue.svg"></a>
<a href="https://github.com/atakan72/iconmanager/releases"><img alt="Release" src="https://img.shields.io/github/v/release/atakan72/iconmanager?display_name=tag"></a>
<img alt="PHP" src="https://img.shields.io/badge/PHP-7.4%2B-777bb4.svg"> <img alt="WordPress" src="https://img.shields.io/badge/WordPress-6.0%2B-21759b.svg">
</p>

</div>

## Table of Contents
- [Icon Manager](#icon-manager)
  - [Table of Contents](#table-of-contents)
  - [Why / Motivation](#why--motivation)
  - [Features](#features)
  - [Requirements](#requirements)
  - [Installation](#installation)
    - [Composer / Packagist](#composer--packagist)
  - [Usage](#usage)
    - [Parameter Reference](#parameter-reference)
  - [Media Library Import (Optional)](#media-library-import-optional)
  - [Caching \& Performance](#caching--performance)
  - [Localization](#localization)
  - [Auto-Updates (GitHub)](#auto-updates-github)
  - [Icon Sources \& Licenses](#icon-sources--licenses)
  - [Development](#development)
  - [Contributing](#contributing)
  - [Changelog](#changelog)
  - [Security](#security)
  - [License](#license)

## Why / Motivation
Many themes embed remote icon CDNs (tracking / reliability issues). Icon Manager stores SVG files locally after first download and serves them with long-term cache. Minimal footprint, no frontend bloat.

## Features
- Batch & single AJAX download for Brand + UI icons
- Shortcode `[icon name="facebook" type="brand" size="24"]`
- PHP helper: `iconmanager_render_icon()` and echo helper `iconmanager_icon()`
- Auto-download missing icons when rendered in admin context
- Object cache for rendered HTML (7 days) – invalidated on version change
- Strong cache headers for delivered SVG / images
- Optional Media Library import (Gutenberg integration)
- Built-in admin help & license panels (copyable credit snippet)
- Legacy compatibility layer for old prefix (`iconmgmt_dsgvo_`) with `_doing_it_wrong` notice

## Requirements
- WordPress 6.0+ (older may work, untested)
- PHP 7.4+ (recommended 8.1+)
- Object Cache optional but improves performance

## Installation
1. Download or clone: `git clone https://github.com/atakan72/iconmanager` into `wp-content/plugins/iconmanager`.
2. Activate the plugin in WordPress.
3. Go to Admin → Icons and trigger initial downloads (bulk or single).

### Composer / Packagist
Not published (yet). For now install manually or as a Git submodule.

## Usage
PHP examples:
```php
echo iconmanager_render_icon('facebook', 'brand', 24);
iconmanager_icon('menu', 'ui', 32, '#6FA29D', ['class' => 'nav-icon']);
```
Shortcode example:
```
[icon name="menu" type="ui" size="32" class="w-8 h-8" color="#6FA29D"]
```

### Parameter Reference
| Parameter | Shortcode Attr | Description |
|-----------|----------------|-------------|
| name | name | Icon slug without extension |
| type | type | `brand`, `ui` or `auto` (auto detects by known brand list) |
| size | size | Integer pixel size (width & height) |
| color | color | Optional CSS color (applied via inline style) |
| class | class | Additional CSS classes on wrapper/span/img |

## Media Library Import (Optional)
By default icons are served directly from `uploads/iconmanager-icons` (fast & update-safe). Import into Media Library only if editors must pick icons in Gutenberg blocks.
Use the admin help panel buttons:
- Bulk: “Alle Icons in Mediathek importieren”
- Single: per-icon “Import” buttons

Each imported SVG is stored in the date-based uploads folder and tagged with meta `_iconmanager_icon_name` for duplicate detection.

## Caching & Performance
- Rendered HTML cached (object cache) for 7 days
- Long-term immutable browser caching for served static SVG / image assets
- Cache flushed automatically on plugin version change

## Localization
- English source strings
- German translation (`de_DE`)
- Add your own: copy `languages/iconmanager.pot` → create `your-locale.po` & compile MO

## Auto-Updates (GitHub)
The plugin can self-update directly from this GitHub repository (similar UX to wp.org plugins):
1. Make sure the directory name is exactly `iconmanager`.
2. When a new release is tagged on GitHub using a semver tag like `v1.1.3`, WordPress will detect it in the next update check (twice daily) or when you press "Check for updates".
3. Click "Update now" on the Plugins screen – the ZIP served is GitHub's release archive.

Release workflow for maintainers:
```
# Bump version in iconmanager.php & CHANGELOG.md
# Commit & push
git tag v1.1.3
git push origin v1.1.3
# (Optionally create a GitHub Release with notes – auto-updater uses the latest release API.)
```
Notes:
- Caches: We cache GitHub API for 30 minutes; force refresh via "Check for updates" link.
- Only the latest GitHub Release is considered (Drafts ignored). Use proper published releases.
- Tag must start with `v` and follow semantic versioning.

## Icon Sources & Licenses
- Lucide (ISC): https://lucide.dev/license
- Simple Icons (CC0, brand ownership caveats): https://github.com/simple-icons/simple-icons/blob/master/DISCLAIMER.md

Optional credit (copy & paste):
```
Icons: Brand Icons (Simple Icons) & UI Icons (Lucide).
```

## Development
Directory structure:
```
iconmanager.php
includes/
	admin/ admin-page UI
	services/ download, registry, media import, headers
	frontend/ shortcode & render
	compat/ legacy aliases
assets/ (optional future assets)
```

Recommended local steps:
```
wp plugin activate iconmanager
```
Run PHPCS (example):
```
phpcs --standard=WordPress --ignore=vendor .
```

## Contributing
Pull requests welcome. Please:
1. Open an issue first for larger changes.
2. Follow WordPress PHP Coding Standards.
3. Keep functions prefixed `iconmanager_`.
4. Add inline docs for new public functions.
5. Test backward compatibility with legacy aliases when touching affected areas.

See `CONTRIBUTING.md` for full details.

## Changelog
See `CHANGELOG.md`.

## Security
See [SECURITY.md](SECURITY.md). Report vulnerabilities via private advisory preferred; please do not disclose before a fix is released.

## License
GPL-2.0-or-later. See [LICENSE](LICENSE).

Attribution request: Please credit the project (link to this repository) when feasible. This is appreciated but not legally required under GPL.

---
Maintained by @atakan72
